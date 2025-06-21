<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            [
                'field_id' => 1,
                'user_id' => 1,
                'date' => '2025-05-25',
                'start_time' => '16:00',
                'end_time' => '18:00',
                'duration' => 2,
                'total_price' => 300000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'booking_code' => 'LK' . strtoupper(Str::random(8)),
            ],
            [
                'field_id' => 2,
                'user_id' => 1,
                'date' => '2025-05-28',
                'start_time' => '19:00',
                'end_time' => '21:00',
                'duration' => 2,
                'total_price' => 400000,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'booking_code' => 'LK' . strtoupper(Str::random(8)),
            ],
            [
                'field_id' => 3,
                'user_id' => 1,
                'date' => '2025-06-01',
                'start_time' => '09:00',
                'end_time' => '11:00',
                'duration' => 2,
                'total_price' => 200000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'booking_code' => 'LK' . strtoupper(Str::random(8)),
            ],
            [
                'field_id' => 4,
                'user_id' => 1,
                'date' => '2025-06-05',
                'start_time' => '07:00',
                'end_time' => '09:00',
                'duration' => 2,
                'total_price' => 500000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'booking_code' => 'LK' . strtoupper(Str::random(8)),
            ],
            [
                'field_id' => 5,
                'user_id' => 1,
                'date' => '2025-05-20',
                'start_time' => '10:00',
                'end_time' => '12:00',
                'duration' => 2,
                'total_price' => 360000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_code' => 'LK' . strtoupper(Str::random(8)),
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
