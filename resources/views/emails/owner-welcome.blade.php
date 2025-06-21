@component('mail::message')
# Selamat Datang di LapangKuy!

Halo **{{ $user->name }}**,

Terima kasih telah mendaftar sebagai pemilik lapangan di LapangKuy. Kami senang Anda bergabung dengan platform kami.

## Langkah Selanjutnya

@component('mail::panel')
1. Login ke akun Anda
2. Lengkapi profil bisnis Anda
3. Tambahkan lapangan yang ingin disewakan
4. Atur jadwal ketersediaan dan harga
@endcomponent

@component('mail::button', ['url' => route('owner.dashboard'), 'color' => 'success'])
Akses Dashboard Pemilik
@endcomponent

Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi tim dukungan kami.

Salam,<br>
Tim LapangKuy
@endcomponent
