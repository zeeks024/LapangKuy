<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Dikonfirmasi - ' . $this->booking->field->name)
                    ->view('emails.booking-confirmation')
                    ->with([
                        'booking' => $this->booking,
                        'user' => $this->booking->user,
                        'field' => $this->booking->field,
                    ]);
    }
}
