<?php

namespace App\Mail;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\Codec\StringCodec;

class BillMailable extends Mailable
{
    public Bill $bill;

    public string $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Bill $bill, string $pdfPath)
    {
        $this->bill = $bill;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Votre facture #%d', $this->bill->id)
            ->view('email.bill')->with([
                'name' => $this->bill->customer_name,
            ])
            ->attach($this->pdfPath, [
                'as' => sprintf('facture_%s.pdf', $this->bill->id),
                'mime' => 'application/pdf',
            ]);
    }
}
