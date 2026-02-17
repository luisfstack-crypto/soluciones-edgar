<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(\App\Models\Order $order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu trámite está listo - Soluciones Edgar',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.completed',
        );
    }

    public function attachments(): array
    {
        if ($this->order->result_file_path) {
            $disk = config('filesystems.default', 'local');
            
            if (\Illuminate\Support\Facades\Storage::disk($disk)->exists($this->order->result_file_path)) {
                return [
                    Attachment::fromStorageDisk($disk, $this->order->result_file_path)
                        ->as('Resultado_Pedido_' . $this->order->id . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
        }

        return [];
    }
}