<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Invoice Pesanan #' . $this->order->order_id);
    }

    public function content(): Content
    {
        $printUrl = URL::temporarySignedRoute(
            'orders.invoice-print',
            now()->addDays(7),
            ['id' => $this->order->order_id]
        );

        return new Content(view: 'orders.invoice', with: [
            'order' => $this->order,
            'printUrl' => $printUrl,
        ]);
    }
}
