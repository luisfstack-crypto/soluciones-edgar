<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\DepositRequest;

class AdminDepositCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $depositRequest;

    public function __construct(DepositRequest $depositRequest)
    {
        $this->depositRequest = $depositRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Solicitud de Abono de Saldo',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.deposit_created',
            with: [
                'depositRequest' => $this->depositRequest,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
