@extends('layouts.app')
@section('title', 'Booking Tidak Ditemukan')
@section('content')
<div class="container text-center py-5">
    <img src="{{ asset('assets/images/404.svg') }}" alt="Booking Tidak Ditemukan" style="max-width:300px;">
    <h1 class="display-4 mt-4">Booking Tidak Ditemukan</h1>
    <p class="lead">Booking yang Anda cari tidak ada dalam sistem kami.</p>
    <p>Periksa kembali nomor booking Anda atau lihat riwayat booking di akun Anda.</p>
    <div class="mt-4">
        <a href="{{ route('bookings.index') }}" class="btn btn-primary me-2">Lihat Riwayat Booking</a>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary">Kembali ke Beranda</a>
    </div>
</div>
@endsection
