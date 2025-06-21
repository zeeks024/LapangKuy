@extends('layouts.app')

@section('title', 'Edit Lapangan')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Edit Lapangan</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('fields.update', $field->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lapangan</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $field->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $field->location) }}" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="futsal" {{ $field->category == 'futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="basket" {{ $field->category == 'basket' ? 'selected' : '' }}>Basket</option>
                        <option value="badminton" {{ $field->category == 'badminton' ? 'selected' : '' }}>Badminton</option>
                        <option value="tennis" {{ $field->category == 'tennis' ? 'selected' : '' }}>Tennis</option>
                        <option value="voli" {{ $field->category == 'voli' ? 'selected' : '' }}>Voli</option>
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Harga per Jam (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $field->price) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="open_time" class="form-label">Buka</label>
                        <input type="time" class="form-control" id="open_time" name="open_time" value="{{ old('open_time', $field->open_time) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="close_time" class="form-label">Tutup</label>
                        <input type="time" class="form-control" id="close_time" name="close_time" value="{{ old('close_time', $field->close_time) }}" required>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $field->description) }}</textarea>
                </div>                <div class="mb-3">
                    <x-file-upload 
                        name="image" 
                        label="Foto Lapangan" 
                        help="Upload foto baru untuk mengganti foto lapangan (JPG, PNG, max 2MB)" 
                        accept="image/jpeg,image/png"
                        :currentImage="$field->image_url"
                    />
                    
                    <div class="mt-3">
                        <label for="image_url" class="form-label">Atau Masukkan URL Gambar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                            <input type="url" class="form-control" id="image_url" name="image_url" 
                                value="{{ old('image_url', $field->image_url) }}" placeholder="https://example.com/gambar-lapangan.jpg">
                        </div>
                        <small class="form-text text-muted">Masukkan URL gambar online jika tidak mengupload file</small>
                    </div>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="promo" name="promo" {{ $field->promo ? 'checked' : '' }}>
                    <label class="form-check-label" for="promo">Promo</label>
                </div>
                <button type="submit" class="btn btn-primary">Update Lapangan</button>
            </form>
        </div>
    </div>
</div>
@endsection
