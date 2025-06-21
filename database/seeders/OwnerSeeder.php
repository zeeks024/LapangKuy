<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    /**
     * Seed owner user for testing.
     */
    public function run(): void
    {
        // Create test owner user if not exists
        if (!User::where('email', 'owner@lapangkuy.com')->exists()) {
            User::create([
                'name' => 'Field Owner',
                'email' => 'owner@lapangkuy.com',
                'password' => Hash::make('password'),                'role' => 'owner',
                'phone' => '081234567890',
                'address' => 'Sekaran, Kec. Gn. Pati, Kota Semarang, Jawa Tengah 50229',
            ]);

            $this->command->info('Test owner user created: owner@lapangkuy.com / password');
        }
    }
}
