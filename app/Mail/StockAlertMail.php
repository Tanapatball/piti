<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StockAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $lowStock,
        public Collection $overStock,
    ) {}

    public function envelope(): Envelope
    {
        $count = $this->lowStock->count() + $this->overStock->count();
        return new Envelope(
            subject: "[WMS] แจ้งเตือนสต็อกสินค้า ({$count} รายการ)",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-alert',
        );
    }
}
