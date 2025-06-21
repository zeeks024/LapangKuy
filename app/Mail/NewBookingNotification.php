<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\User;

class NewBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $fieldOwner;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, User $fieldOwner)
    {
        $this->booking = $booking;
        $this->fieldOwner = $fieldOwner;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Baru Masuk - ' . $this->booking->field->name)
                    ->view('emails.new-booking-notification')
                    ->with([
                        'booking' => $this->booking,
                        'fieldOwner' => $this->fieldOwner,
                        'user' => $this->booking->user,
                        'field' => $this->booking->field,
                    ]);
    }
}
