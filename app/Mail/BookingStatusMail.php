<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    private const SUBJECTS = [
        'confirmed' => 'Your booking is confirmed / Pesananmu dikonfirmasi',
        'cancelled' => 'Your booking was cancelled / Pesananmu dibatalkan',
        'completed' => 'Thanks for travelling with us / Terima kasih sudah ikut trip bareng kami',
    ];

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        $subject = self::SUBJECTS[$this->booking->status] ?? 'Booking update / Update pesanan';

        return new Envelope(subject: $subject . ' — Overlander #' . $this->booking->id);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-status');
    }
}
