<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $user;

    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            subject: '商品が注文されました。',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.ordered',
        );
    }

    public function attachments()
    {
        return [];
    }
}
