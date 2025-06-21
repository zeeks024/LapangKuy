<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $cancelReason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, $cancelReason = null)
    {
        $this->booking = $booking;
        $this->cancelReason = $cancelReason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Dibatalkan - ' . $this->booking->field->name)
                    ->view('emails.booking-cancellation')
                    ->with([
                        'booking' => $this->booking,
                        'cancelReason' => $this->cancelReason,
                        'user' => $this->booking->user,
                        'field' => $this->booking->field,
                    ]);
    }
}
