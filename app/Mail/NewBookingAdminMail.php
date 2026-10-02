<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        $trip = $this->booking->is_custom ? 'Custom trip request' : $this->booking->package?->name;

        return new Envelope(
            subject: 'New booking #' . $this->booking->id . ' — ' . $trip,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-admin');
    }
}
