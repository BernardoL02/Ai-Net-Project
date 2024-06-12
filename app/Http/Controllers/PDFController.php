<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Purchase;
use App\Models\Screening;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function generateReceipt(Purchase $purchase)
    {

        /* $data = [
            'name' => $purchase->customer_name,
            'email' => $purchase->customer_email,
            'nif'=> $purchase->nif,
            'payment_type' => $purchase->payment_type,
            'payment_reference' => $purchase->payment_ref,
            'date' => $purchase->date,
            'total_price' => $purchase->total_price,
            'tickets' => $purchase->tickets
        ]; */
        $data=['purchase'=>$purchase];

        $pdf = PDF::loadView('receipt',$data);

        $content = $pdf->download()->getOriginalContent();

        Storage::put("pdf_purchases/{$purchase->id}.pdf",$content) ;

        $purchase->receipt_pdf_filename="{$purchase->id}.pdf";

        $purchase->save();




       //return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('receipt',$data)->stream();
       return $pdf->download('receipt.pdf');
    }

    public function showReceipt(Purchase $purchase){

        if($purchase->receipt_pdf_filename){
            return Storage::response("pdf_purchases/".$purchase->receipt_pdf_filename);



        }else{
            return null;
        }


    }


    public function downloadReceipt(Purchase $purchase){

        if($purchase->receipt_pdf_filename){

         return Storage::download("pdf_purchases/".$purchase->receipt_pdf_filename);


        }else{
            return null;
        }


    }

    










}




