<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMailable extends Mailable
{
    use Queueable, SerializesModels;

    public Message $message;

    /**
     * Create a new message instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('[Formulaire de contact] Vous avez un nouveau message')
            ->view('email.contact')->with([
                'fullname' => $this->message->fullname,
                'email' => $this->message->email,
                'content' => $this->message->message,
            ]);
    }
}
