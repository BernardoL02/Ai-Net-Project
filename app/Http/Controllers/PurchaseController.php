<?php

namespace App\Http\Controllers;
use PDF;
use Carbon\Carbon;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Mail\PurchaseReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getReceipt(Purchase $purchase){


        if($purchase->receipt_pdf_filename){
            return Storage::response("pdf_purchases/".$purchase->receipt_pdf_filename);

        }else{
            return null;
        }
    }

    public function sendReceiptEmail(Purchase $purchase)
    {
        $testingDate = '2024-06-01';

        $validTickets = $purchase->tickets->filter(function($ticket) use ($testingDate) {
            return $ticket->screening->date >= $testingDate;
        });

        $invalidTickets = $purchase->tickets->filter(function($ticket) use ($testingDate) {
            return $ticket->screening->date < $testingDate;
        });

        $data=['purchase'=>$purchase,'download'=>false,'validTickets'=>$validTickets,'invalidTickets'=>$invalidTickets];
        $pdf = PDF::loadView('tickets',$data);

        $filename = 'receipt_' . $purchase->id . '.pdf';

        Mail::to($purchase->customer_email)->send(new PurchaseReceipt($purchase, $pdf, $filename));
    }

    public function showTickets(Purchase $purchase){

        $testingDate = '2024-06-01';

        $validTickets = $purchase->tickets->filter(function($ticket) use ($testingDate) {
            return $ticket->screening->date >= $testingDate;
        });

        $invalidTickets = $purchase->tickets->filter(function($ticket) use ($testingDate) {
            return $ticket->screening->date < $testingDate;
        });

        $data=['purchase'=>$purchase,'download'=>false,'validTickets'=>$validTickets,'invalidTickets'=>$invalidTickets];

        $pdf = PDF::loadView('tickets',$data);

        return $pdf->stream();
    }

    public function showTicketsOfCostumer(Purchase $purchase)
    {
        $tickets = $purchase->tickets()->get();

        foreach($tickets as $ticket){
            if(Carbon::now()->toDateString() >= $ticket->screening->date && Carbon::now()->toTimeString() > Carbon::parse($ticket->screening->start_time)->addMinutes(5)->toTimeString()){
                $ticket->status = 'invalid';
                $ticket->save();
            }
        }

        return view('purchases.show-tickets', compact('purchase', 'tickets'));
    }

    public function downloadTickets(Purchase $purchase){

        $data=['purchase'=>$purchase,'download'=>true];
        $pdf = PDF::loadView('tickets',$data);
        return $pdf->download('ticket.pdf');

    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string|in:MBWAY,PAYPAL,VISA', // Assume que estes são os tipos de pagamento válidos
            'price' => 'nullable|numeric|min:0',
            'priceOption' => 'nullable|integer|in:0,1',
            'email' => 'nullable|email',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filterByPaymentType = $request->query('type');
        $filterByPrice = $request->input('price');
        $filterByPriceOption= $request->priceOption;
        $purchasesQuery = Purchase::query();

        if ($filterByPaymentType !== null) {
            $purchasesQuery->where('payment_type', $filterByPaymentType);
        }

        if($request->filled('date')){
            $purchasesQuery->where('date', $request->date)->pluck('date');
        }

        //Verificar se a query está correta
        if($request->filled('email')){

            $purchasesQuery->where('customer_email', $request->email)->pluck('customer_email');
        }

        if ($filterByPrice !== null) {
            if($filterByPriceOption == 0){
                $purchasesQuery->where('total_price','>=' , $filterByPrice);

            }else{
                $purchasesQuery->where('total_price','<=', $filterByPrice);

            }
        }

        $purchases = $purchasesQuery->orderby('customer_name')->paginate(20)->withQueryString();

        return view(
            'purchases.index',
            compact('purchases','filterByPaymentType','filterByPrice','filterByPriceOption')
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        /* $validTickets = $purchase->tickets->filter(function($ticket) use ($testingDate) {
            return $ticket->screening->date >= $testingDate;
        }); */

        $testingDate = '2024-06-01';

        $invalidTickets = $purchase->tickets->filter(function ($ticket) use ($testingDate) {
            return $ticket->screening->date < $testingDate;
        });

        // Buscar o photo_filename corretamente usando o relacionamento ajustado
        $photoFilename = null;
        if ($purchase->customer && $purchase->customer->user) {
            $photoFilename = $purchase->customer->user;
        }

        return view('purchases.show', [
            'purchase' => $purchase,
            'invalidTickets' => $invalidTickets,
            'photoFilename' => $photoFilename,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
