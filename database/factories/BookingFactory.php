<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $startTime = $this->faker->randomElement(['06:00', '08:00', '10:00', '14:00', '16:00', '18:00', '20:00']);
        $duration = $this->faker->randomElement([1, 2, 3]);
        $endTime = date('H:i', strtotime($startTime . ' + ' . $duration . ' hours'));        return [
            'field_id' => Field::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $duration,
            'total_price' => $this->faker->numberBetween(150000, 600000),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid', 'failed']),
            'booking_code' => $this->faker->unique()->numerify('LK-#########'),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }
}
