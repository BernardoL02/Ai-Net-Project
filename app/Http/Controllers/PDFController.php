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

        $data=['purchase'=>$purchase];

        $pdf = PDF::loadView('receipt',$data);

        $content = $pdf->download()->getOriginalContent();

        Storage::put("pdf_purchases/{$purchase->id}.pdf",$content) ;

        $purchase->receipt_pdf_filename="{$purchase->id}.pdf";

        $purchase->save();


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




