<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{
    protected $model = Field::class;

    public function definition(): array
    {        $categories = ['Futsal', 'Badminton', 'Basketball', 'Tennis', 'Volleyball'];
        $locations = ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat', 'Semarang'];
        
        return [
            'owner_id' => User::factory(),
            'name' => 'Lapangan ' . $this->faker->company(),
            'location' => $this->faker->randomElement($locations) . ', ' . $this->faker->streetAddress(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100000, 300000),
            'category' => $this->faker->randomElement($categories),
            'facilities' => json_encode($this->faker->randomElements([
                'Parking', 'Toilet', 'Shower', 'Cafeteria', 'AC', 'Sound System', 'Lighting'
            ], $this->faker->numberBetween(2, 5))),
            'image_url' => $this->faker->imageUrl(800, 600, 'sports'),
            'open_time' => '06:00',
            'close_time' => '23:00',
        ];
    }
}
