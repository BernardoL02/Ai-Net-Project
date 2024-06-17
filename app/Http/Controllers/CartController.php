<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Student;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Screening;
use App\Services\Payment;
use Illuminate\View\View;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PurchaseReceipt;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CartConfirmationFormRequest;

class CartController extends Controller
{
    public function show(): View
    {
        $user = null;
        $customer = null;

        if (Auth::check()) {
            $user = Auth::user();
            $customer = Customer::where('id', Auth::user()->id)->first();
        }

        $ticketPrice = DB::table('configuration')->where('id', '1')->value('ticket_price');

        $cart = session('cart', []);
        return view('cart.show', compact('cart','user','customer','ticketPrice'));
    }

    public function addToCart(Request $request, Screening $screening): RedirectResponse
    {
        if(Carbon::now()->toDateString() >= $screening->date && Carbon::now()->toTimeString() > Carbon::parse($screening->start_time)->addMinutes(6)->toTimeString()){
            return back()->with('alert-type', 'warning')->with('alert-msg', 'Impossible to add tickets. The session has already started!');
        }

        $cart = session('cart', []);

        $seats = $request->seats ?? [];
        $rows = $request->rows ?? [];
        $seats_adicionados = [];
        foreach($seats as $seat_id){
            $id = $screening->id.'_'.$seat_id;
            if(!array_key_exists($id,$cart)){
                $seat = Seat::findOrFail($seat_id);
                $cart[$id] = ["screening"=>$screening, "seat"=>$seat];
                $seats_adicionados[] = $seat->row.$seat->seat_number;
            }
        }

        if(count($seats_adicionados)){
            session(['cart'=> $cart]);
            $alertType = 'success';
            $htmlMessage = "Seats <strong>".implode(',',$seats_adicionados)."</strong> was(were) added to Cart";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }else{
            $alertType = 'warning';
            $htmlMessage = "No seats were added to the cart";
        }

        return back()
        ->with('alert-msg', $htmlMessage)
        ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, $id): RedirectResponse
    {
        $cart = session('cart',[]);
        if(count($cart) == 0){

            $alertType = 'warning';
            $htmlMessage = 'Cart is empty';

        }else{

            if(!array_key_exists($id, $cart)){

                $alertType = 'warning';
                $htmlMessage = "Selected item does not exist in cart.";
            }else{
                $alertType = 'Success';
                $htmlMessage = "Seat <strong>" . $cart["$id"]["seat"]->row.$cart["$id"]["seat"]->seat_number . "</strong> was removed to the cart.";

                unset($cart[$id]);
                session(['cart' => $cart]);
            }

        }

        return back()->with('alert-msg', $htmlMessage)->with('alert-type', $alertType);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }

    public function confirm(CartConfirmationFormRequest $request): RedirectResponse
{
    $validatedData = $request->validated();
    $customerID = Auth::check() ? $request->user()->id : null;

    if ($customerID && $request->user()->customer == null) {
        Customer::create(['id' => $customerID]);
    }

    $cart = session('cart', []);
    if (empty($cart)) {
        return back()->with('alert-type', 'danger')->with('alert-msg', "Cart was not confirmed because it is empty!");
    }

    $purchaseData = [
        'date' => now(),
        'total_price' => 0,
        'nif' => $validatedData['nif'] ?? null,
        'customer_id' => $customerID,
        'customer_email' => $validatedData['email'],
        'customer_name' => $validatedData['name'],
        'payment_type' => $validatedData['payment_type'],
        'payment_ref' => $validatedData['payment_ref'],
    ];

    $ticketPrice = DB::table('configuration')->where('id', '1')->value('ticket_price');

    try {
        DB::beginTransaction();
        $purchase = Purchase::create($purchaseData);
        $purchaseId = $purchase->id;
        $totalPrice = 0;
        $tickets = [];

        foreach ($cart as $item) {
            $qrcodeUrl = url('/tickets/validate/' . Str::random(40));
            $ticketData = [
                'purchase_id' => $purchaseId,
                'screening_id' => $item['screening']->id,
                'seat_id' => $item['seat']->id,
                'price' => $ticketPrice,
                'customer_name' => $validatedData['name'],
                'customer_email' => $validatedData['email'],
                'customer_nif' => $validatedData['nif'],
                'qrcode_url' => $qrcodeUrl,
            ];

            $ticket = Ticket::create($ticketData);
            $totalPrice += $ticketPrice;

            $qrCode = QrCode::create($qrcodeUrl);
            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode)->getString();
            $qrCodePath = 'ticket_qrcodes/qrcode_' . $ticket->id . '.png';
            Storage::put($qrCodePath, $qrCodeImage);

            $tickets[] = $ticket;
        }

        if (Auth::check()) {
            $discount = DB::table('configuration')->where('id', '1')->value('registered_customer_ticket_discount');
            $totalPrice -= count($cart) * $discount;
        }

        $purchase->update(['total_price' => $totalPrice]);

        switch ($validatedData['payment_type']) {
            case 'VISA':
                $paymentSuccessful = Payment::payWithVisa($validatedData['payment_ref'], substr($validatedData['payment_ref'], -3));
                break;
            case 'PAYPAL':
                $paymentSuccessful = Payment::payWithPaypal($validatedData['payment_ref']);
                break;
            case 'MBWAY':
                $paymentSuccessful = Payment::payWithMBway($validatedData['payment_ref']);
                break;
            default:
                return back()->with('alert-type', 'danger')->with('alert-msg', 'Invalid payment type selected.');
        }

        if ($paymentSuccessful) {
            DB::commit();
            $request->session()->forget('cart');

            $pdfReceipt = PDF::loadView('receipt', compact('purchase'));
            $pdfFilename = 'receipt_' . $purchaseId . '.pdf';
            $pdfPath = storage_path('app/pdf_purchases/' . $pdfFilename);
            $pdfReceipt->save($pdfPath);
            $pdfTickets = PDF::loadView('tickets', ['tickets' => $tickets, 'purchase' => $purchase, 'validTickets' => [], 'invalidTickets' => []])->output();


            try {
                Mail::to($purchase->customer_email)->send(new PurchaseReceipt($purchase, $pdfPath, $pdfTickets));
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
                return back()->with('alert-type', 'danger')->with('alert-msg', 'Failed to send purchase confirmation email. Please contact support.');
            }

            return back()->with('alert-type', 'success')->with('alert-msg', 'Purchase successfully completed!');
        } else {
            DB::rollback();
            return back()->with('alert-type', 'danger')->with('alert-msg', 'Payment was not successful. Purchase not finalized.');
        }
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Failed to complete the purchase: ' . $e->getMessage());

        return back()
            ->with('alert-type', 'danger')
            ->with('alert-msg', 'Failed to complete the purchase. Please try again later.');
    }
}
}


