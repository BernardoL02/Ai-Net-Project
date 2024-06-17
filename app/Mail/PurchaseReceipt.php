<?php

namespace App\Mail;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;
    public $pdfContent;
    public $filename;

    /**
     * Create a new message instance.
     */
    public function __construct(Purchase $purchase, $pdfContent, $filename)
    {
        $this->purchase = $purchase;
        $this->pdfContent = $pdfContent;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Purchase Receipt')
                    ->markdown('emails.purchase.receipt')
                    ->attachData($this->pdfContent, $this->filename, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
