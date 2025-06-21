<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get field owners
        $owner1 = User::where('email', 'owner1@lapangkuy.com')->first();
        $owner2 = User::where('email', 'owner2@lapangkuy.com')->first();

        $fields = [
            [
                'name' => 'Lapangan Futsal Bintang',
                'description' => 'Lapangan futsal dengan rumput sintetis berkualitas tinggi. Cocok untuk pertandingan maupun latihan rutin.',
                'location' => 'Semarang, Sekaran, Kec. Gn. Pati',
                'price' => 150000,
                'image_url' => 'https://placehold.co/800x400/043E03/FFFFFF?text=Futsal+Bintang',
                'category' => 'Futsal',
                'open_time' => '00:00',
                'close_time' => '23:59',
                'facilities' => json_encode(['Parkir Luas', 'Toilet', 'Kantin', 'WiFi']),
                'promo' => true,
                'owner_id' => $owner1->id,
            ],
            [
                'name' => 'Lapangan Basket Central',
                'description' => 'Lapangan basket indoor dengan lantai kayu dan penerangan yang baik. Tersedia papan skor elektronik.',
                'location' => 'Semarang, Sekaran, Kec. Gn. Pati',
                'price' => 200000,
                'image_url' => 'https://placehold.co/800x400/043E03/FFFFFF?text=Basket+Central',
                'category' => 'Basket',
                'open_time' => '00:00',
                'close_time' => '23:59',
                'facilities' => json_encode(['Parkir Luas', 'Toilet', 'Kamar Ganti', 'Loker']),
                'promo' => false,
                'owner_id' => $owner1->id,
            ],
            [
                'name' => 'GOR Badminton Mitra',
                'description' => 'Lapangan badminton dengan kualitas internasional. Lantai vinyl dengan penerangan yang optimal.',
                'location' => 'Semarang, Sekaran, Kec. Gn. Pati',
                'price' => 100000,
                'image_url' => 'https://placehold.co/800x400/043E03/FFFFFF?text=Badminton+Mitra',
                'category' => 'Badminton',
                'open_time' => '00:00',
                'close_time' => '23:59',
                'facilities' => json_encode(['Parkir Luas', 'Toilet', 'Kamar Ganti', 'Kantin']),
                'promo' => true,
                'owner_id' => $owner2->id,
            ],
            [
                'name' => 'Lapangan Tennis Elite',
                'description' => 'Lapangan tennis outdoor dengan permukaan hard court berkualitas tinggi. Cocok untuk pertandingan profesional.',
                'location' => 'Jakarta Utara',
                'price' => 250000,
                'image_url' => 'https://placehold.co/800x400/043E03/FFFFFF?text=Tennis+Elite',
                'category' => 'Tennis',
                'open_time' => '06:00',
                'close_time' => '18:00',
                'facilities' => json_encode(['Parkir Luas', 'Toilet', 'Kamar Ganti', 'Kafetaria']),
                'promo' => false,
                'owner_id' => $owner2->id,
            ],
            [
                'name' => 'Lapangan Voli Pantai',
                'description' => 'Lapangan voli pantai dengan pasir putih berkualitas premium. Nuansa pantai yang autentik.',
                'location' => 'Jakarta Selatan',
                'price' => 180000,
                'image_url' => 'https://placehold.co/800x400/043E03/FFFFFF?text=Voli+Pantai',
                'category' => 'Voli',
                'open_time' => '07:00',
                'close_time' => '19:00',
                'facilities' => json_encode(['Parkir', 'Toilet', 'Shower', 'Kantin']),
                'promo' => false,
                'owner_id' => $owner1->id,
            ],
            [
                'name' => 'Lapangan Futsal Galaxy',
                'description' => 'Lapangan futsal indoor dengan rumput sintetis dan penerangan standar FIFA. Ideal untuk kompetisi resmi.',
                'location' => 'Jakarta Pusat',
                'price' => 170000,
                'image_url' => 'https://placehold.co/800x400/043E03/FFFFFF?text=Futsal+Galaxy',
                'category' => 'Futsal',
                'open_time' => '08:00',
                'close_time' => '23:00',
                'facilities' => json_encode(['Parkir Luas', 'Toilet', 'Kamar Ganti', 'Loker', 'Kantin']),
                'promo' => true,
                'owner_id' => $owner2->id,
            ],
        ];

        foreach ($fields as $field) {
            Field::create($field);
        }
    }
}
