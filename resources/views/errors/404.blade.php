@extends('layouts.app')
@section('title', '404 Not Found')
@section('content')
<div class="container text-center py-5">
    <img src="{{ asset('assets/images/404.svg') }}" alt="404 Not Found" style="max-width:300px;">
    <h1 class="display-4 mt-4">404</h1>
    <p class="lead">Halaman yang Anda cari tidak ditemukan.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
</div>
@endsection
