<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Student;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Screening;
use App\Services\Payment;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
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

        if (Auth::check()) {
            $customerID = $request->user()->id;
        }

        // Verifique se há itens no carrinho antes de prosseguir
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed because it is empty!");
        }

        // Crie um array para armazenar as informações da compra e dos tickets
        $purchaseData = [
            'date' => now(), // Data da compra
            'total_price' => 0, // Inicialize o preço total como zero e calcule-o conforme adiciona os tickets
            'nif' => $validatedData['nif'] ?? null, // NIF (opcional)
            'customer_id' => $customerID ?? null,
            'customer_email' => $validatedData['email'],
            'customer_name' => $validatedData['name'],
            'payment_type' => $validatedData['payment_type'],
            'payment_ref' => $validatedData['payment_ref'],
        ];

        $ticketPrice = DB::table('configuration')->where('id', '1')->value('ticket_price');

        // Inicie uma transação de banco de dados para garantir a integridade dos dados
        try {

            DB::beginTransaction();

            $purchase = Purchase::create($purchaseData);
            $purchaseId = $purchase->id;

            // Processar cada item no carrinho
            foreach ($cart as $item) {

                $ticketData = [
                    'purchase_id' => $purchaseId,
                    'screening_id' => $item['screening']->id,
                    'seat_id' => $item['seat']->id,
                    'price' => $ticketPrice,
                    'customer_name' => $validatedData['name'],
                    'customer_email' => $validatedData['email'],
                    'customer_nif' => $validatedData['nif'],
                ];

                Ticket::create($ticketData);

                $purchaseData['total_price'] += $ticketPrice;
            }

            if (Auth::check()) {
                $discount = DB::table('configuration')->where('id', '1')->value('registered_customer_ticket_discount');

                $num_tickets = count($cart);

                $purchaseData['total_price'] -= $num_tickets * $discount;

                $purchase->update(['total_price' => $purchaseData['total_price']]);
            }
            else{
                $purchase->update(['total_price' => $purchaseData['total_price']]);
            }

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
                    return back()
                        ->with('alert-type', 'danger')
                        ->with('alert-msg', 'Invalid payment type selected.');
            }

            if ($paymentSuccessful) {

                DB::commit();

                $request->session()->forget('cart');

                return back()
                    ->with('alert-type', 'success')
                    ->with('alert-msg', 'Purchase successfully completed!');
            } else {
                DB::rollback();

                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', 'Payment was not successful. Purchase not finalized.');
            }

        } catch (\Exception $e) {
            // Rollback da transação em caso de exceção
            DB::rollback();

            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', 'Failed to complete the purchase. Please try again later.');
        }
    }
}


