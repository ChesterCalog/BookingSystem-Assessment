<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly mixed $booking,
        public readonly string $newStatus   // 'approved' | 'rejected' | 'completed'
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->newStatus) {
            'approved'  => 'Your Booking Has Been Approved! 🎤',
            'rejected'  => 'Booking Update — ' . $this->booking->reference_number,
            'completed' => 'Thank You for Your Visit!',
            default     => 'Booking Status Update',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-status');
    }
}
