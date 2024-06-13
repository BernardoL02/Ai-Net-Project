<?php

namespace App\Http\Controllers;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;


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


    public function downloadTickets(Purchase $purchase){

        $data=['purchase'=>$purchase,'download'=>true];

        $pdf = PDF::loadView('tickets',$data);

        return $pdf->download('ticket.pdf');

    }


    public function index(Request $request)
    {

        $filterByPaymentType = $request->query('type');
        $filterByPrice = $request->input('price');
        $filterByPriceOption= $request->priceOption;
        $purchasesQuery = Purchase::query();

        if ($filterByPaymentType !== null) {
            $purchasesQuery->where('payment_type', $filterByPaymentType);
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

        $purchases = $purchasesQuery->orderby('customer_name')->paginate(20)->withQueryString();                                /* ->orderby('total_price') */
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


        $testingDate = '2024-06-01';//alter to current day after we inserted atleast a few tickets

        $invalidTickets = $purchase->tickets->filter(function($ticket) use ($testingDate) {
            return $ticket->screening->date < $testingDate;
        });

        return view('purchases.show')
            ->with('purchase', $purchase)->with('invalidTickets',$invalidTickets);

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
