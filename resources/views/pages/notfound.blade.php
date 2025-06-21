@extends('layouts.app')
@section('title', 'Halaman Tidak Ditemukan')
@section('content')
<div class="container py-5 text-center">
    <h1 class="display-4">404</h1>
    <p class="lead">Halaman tidak ditemukan.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
</div>
@endsection
