<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@lapangkuy.com'],
            [
                'name' => 'Admin LapangKuy',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '08123456789',
                'address' => 'Sekaran, Kec. Gn. Pati, Kota Semarang, Jawa Tengah 50229'
            ]
        );

        // Create Field Owner Users
        User::updateOrCreate(
            ['email' => 'owner1@lapangkuy.com'],
            [
                'name' => 'Pemilik Lapangan Jakarta',
                'password' => Hash::make('password'),
                'role' => 'field_owner',
                'phone' => '08234567890',
                'address' => 'Sekaran, Kec. Gn. Pati, Kota Semarang, Jawa Tengah 50229'
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner2@lapangkuy.com'],
            [
                'name' => 'Pemilik Lapangan Bandung',
                'password' => Hash::make('password'),
                'role' => 'field_owner',
                'phone' => '08345678901',
                'address' => 'Bandung, Jawa Barat'
            ]
        );

        // Create Regular Users
        User::updateOrCreate(
            ['email' => 'user1@lapangkuy.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '08456789012',
                'address' => 'Jakarta Timur, Indonesia'
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@lapangkuy.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '08567890123',
                'address' => 'Surabaya, Jawa Timur'
            ]
        );

        User::updateOrCreate(
            ['email' => 'user3@lapangkuy.com'],
            [
                'name' => 'Ahmad Rahman',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '08678901234',
                'address' => 'Medan, Sumatera Utara'
            ]
        );
    }
}
