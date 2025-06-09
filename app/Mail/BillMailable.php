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
use Illuminate\Support\Str;

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
        return $this->subject(sprintf('Votre facture #%d', $this->bill->id))
            ->view('email.bill')->with([
                'name' => $this->bill->customer_name,
            ])
            ->attach($this->pdfPath, [
                'as' =>  htmlspecialchars($this->str_ascii(sprintf('facture_%d_%s_%s.pdf', $this->bill->id, $this->bill->provider_name, $this->bill->customer_name))),
                'mime' => 'application/pdf',
            ]);
    }

    /**
     *
     * @param string $str
     */
    private function str_ascii(string $str): string
    {
        // Remplace les espaces
        $str = str_replace(' ', '', $str);
        // Translittération basique (remplace les accents, etc.)
        // Note: iconv peut ne pas être suffisant pour tous les cas, mais c'est une approximation
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        // Retire les caractères non alphanumériques si besoin
        $str = preg_replace('/[^a-zA-Z0-9_]/', '', $str);
        return $str;
    }
}
