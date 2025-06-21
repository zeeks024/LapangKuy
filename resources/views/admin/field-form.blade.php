@extends('layouts.app')
@section('title', isset($field) ? 'Edit Lapangan' : 'Tambah Lapangan')
@section('content')
<div class="admin-container fade-in">
    <div class="admin-sidebar">        <div class="admin-profile">
            <div class="admin-avatar">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="admin-avatar-img" onerror="this.onerror=null; this.src='{{ asset('assets/images/default-avatar.png') }}'; this.parentNode.innerHTML = '<span>{{ substr(auth()->user()->name, 0, 1) }}</span>';">
                @else
                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="admin-info">
                <h3>{{ auth()->user()->name }}</h3>
                <p>Administrator</p>
            </div>
        </div>
        
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.fields') }}" class="admin-nav-item active">
                <i class="fas fa-futbol"></i>
                <span>Kelola Lapangan</span>
            </a>
            <a href="{{ route('admin.bookings') }}" class="admin-nav-item">
                <i class="fas fa-calendar-check"></i>
                <span>Kelola Booking</span>
            </a>
            <a href="{{ route('admin.users') }}" class="admin-nav-item">
                <i class="fas fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>
            <a href="{{ route('home') }}" class="admin-nav-item">
                <i class="fas fa-home"></i>
                <span>Kembali ke Website</span>
            </a>
        </nav>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>{{ isset($field) ? 'Edit Lapangan' : 'Tambah Lapangan Baru' }}</h1>
            <div class="admin-actions">
                <a href="{{ route('admin.fields') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="form-card">
            <form action="{{ isset($field) ? route('admin.fields.update', $field->id) : route('admin.fields.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($field))
                    @method('PUT')
                @endif
                
                <div class="form-grid">
                    <div class="form-section">
                        <h3 class="section-title">Informasi Dasar</h3>
                        
                        <div class="form-group">
                            <label for="name">Nama Lapangan <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', isset($field) ? $field->name : '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="sport_type">Jenis Olahraga <span class="required">*</span></label>
                                <select id="sport_type" name="sport_type" class="form-control @error('sport_type') is-invalid @enderror" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="futsal" {{ old('sport_type', isset($field) ? $field->sport_type : '') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                                    <option value="basket" {{ old('sport_type', isset($field) ? $field->sport_type : '') == 'basket' ? 'selected' : '' }}>Basket</option>
                                    <option value="badminton" {{ old('sport_type', isset($field) ? $field->sport_type : '') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                                    <option value="voli" {{ old('sport_type', isset($field) ? $field->sport_type : '') == 'voli' ? 'selected' : '' }}>Voli</option>
                                    <option value="tennis" {{ old('sport_type', isset($field) ? $field->sport_type : '') == 'tennis' ? 'selected' : '' }}>Tennis</option>
                                </select>
                                @error('sport_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Lokasi <span class="required">*</span></label>
                                <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', isset($field) ? $field->location : '') }}" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi Lapangan <span class="required">*</span></label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', isset($field) ? $field->description : '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Harga & Ketersediaan</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price_per_hour">Harga Per Jam (Rp) <span class="required">*</span></label>
                                <input type="number" id="price_per_hour" name="price_per_hour" class="form-control @error('price_per_hour') is-invalid @enderror" value="{{ old('price_per_hour', isset($field) ? $field->price_per_hour : '') }}" required>
                                @error('price_per_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="capacity">Kapasitas Pemain</label>
                                <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', isset($field) ? $field->capacity : '') }}">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="opening_hour">Jam Buka</label>
                                <input type="time" id="opening_hour" name="opening_hour" class="form-control @error('opening_hour') is-invalid @enderror" value="{{ old('opening_hour', isset($field) ? $field->opening_hour : '08:00') }}">
                                @error('opening_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="closing_hour">Jam Tutup</label>
                                <input type="time" id="closing_hour" name="closing_hour" class="form-control @error('closing_hour') is-invalid @enderror" value="{{ old('closing_hour', isset($field) ? $field->closing_hour : '22:00') }}">
                                @error('closing_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" {{ old('is_available', isset($field) ? $field->is_available : true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Lapangan Tersedia untuk Booking</label>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Gambar & Fasilitas</h3>
                        
                        <div class="form-group">
                            <label for="image">Gambar Utama</label>
                            <div class="image-upload">
                                <label for="image" class="image-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Pilih Gambar</span>
                                </label>
                                <input type="file" id="image" name="image" class="image-upload-input @error('image') is-invalid @enderror" accept="image/*">
                                <div class="image-preview">
                                    @if(isset($field) && $field->image)
                                        <img src="{{ $field->image }}" alt="{{ $field->name }}">
                                    @endif
                                </div>
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="gallery">Galeri Gambar (Multiple)</label>
                            <div class="image-upload">
                                <label for="gallery" class="image-upload-label">
                                    <i class="fas fa-images"></i>
                                    <span>Pilih Beberapa Gambar</span>
                                </label>
                                <input type="file" id="gallery" name="gallery[]" class="image-upload-input @error('gallery') is-invalid @enderror" accept="image/*" multiple>
                                <div class="gallery-preview">
                                    @if(isset($field) && $field->gallery)
                                        @foreach(json_decode($field->gallery) as $gallery)
                                            <div class="gallery-item">
                                                <img src="{{ $gallery }}" alt="{{ $field->name }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @error('gallery')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Fasilitas</label>
                            <div class="facilities-list">
                                <div class="facility-item">
                                    <input type="checkbox" id="facility_parking" name="facilities[]" value="parking" {{ isset($field) && is_array(json_decode($field->facilities)) && in_array('parking', json_decode($field->facilities)) ? 'checked' : '' }}>
                                    <label for="facility_parking">Parkir</label>
                                </div>
                                <div class="facility-item">
                                    <input type="checkbox" id="facility_toilet" name="facilities[]" value="toilet" {{ isset($field) && is_array(json_decode($field->facilities)) && in_array('toilet', json_decode($field->facilities)) ? 'checked' : '' }}>
                                    <label for="facility_toilet">Toilet</label>
                                </div>
                                <div class="facility-item">
                                    <input type="checkbox" id="facility_wifi" name="facilities[]" value="wifi" {{ isset($field) && is_array(json_decode($field->facilities)) && in_array('wifi', json_decode($field->facilities)) ? 'checked' : '' }}>
                                    <label for="facility_wifi">WiFi</label>
                                </div>
                                <div class="facility-item">
                                    <input type="checkbox" id="facility_canteen" name="facilities[]" value="canteen" {{ isset($field) && is_array(json_decode($field->facilities)) && in_array('canteen', json_decode($field->facilities)) ? 'checked' : '' }}>
                                    <label for="facility_canteen">Kantin</label>
                                </div>
                                <div class="facility-item">
                                    <input type="checkbox" id="facility_shower" name="facilities[]" value="shower" {{ isset($field) && is_array(json_decode($field->facilities)) && in_array('shower', json_decode($field->facilities)) ? 'checked' : '' }}>
                                    <label for="facility_shower">Kamar Mandi/Shower</label>
                                </div>
                                <div class="facility-item">
                                    <input type="checkbox" id="facility_locker" name="facilities[]" value="locker" {{ isset($field) && is_array(json_decode($field->facilities)) && in_array('locker', json_decode($field->facilities)) ? 'checked' : '' }}>
                                    <label for="facility_locker">Loker</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">{{ isset($field) ? 'Update Lapangan' : 'Tambah Lapangan' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Admin structure styles are already added in the dashboard */
    
    .form-card {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        padding: 30px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
    
    .form-section {
        margin-bottom: 25px;
    }
    
    .form-section:nth-child(3) {
        grid-column: span 2;
    }
    
    .section-title {
        font-size: 18px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
        color: var(--primary-color);
    }
    
    .required {
        color: var(--secondary-color);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: var(--transition);
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(4, 62, 3, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: var(--secondary-color);
    }
    
    .invalid-feedback {
        color: var(--secondary-color);
        font-size: 13px;
        margin-top: 5px;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        margin-top: 15px;
    }
    
    .form-check-input {
        margin-right: 8px;
    }
    
    .image-upload {
        position: relative;
        margin-bottom: 15px;
    }
    
    .image-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 120px;
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
    }
    
    .image-upload-label:hover {
        border-color: var(--primary-color);
    }
    
    .image-upload-label i {
        font-size: 24px;
        margin-bottom: 10px;
        color: var(--primary-color);
    }
    
    .image-upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .image-preview {
        margin-top: 15px;
        border-radius: var(--radius-sm);
        overflow: hidden;
        max-width: 300px;
    }
    
    .image-preview img {
        width: 100%;
        height: auto;
    }
    
    .gallery-preview {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .gallery-item {
        width: 100px;
        height: 100px;
        border-radius: var(--radius-sm);
        overflow: hidden;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .facilities-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    
    .facility-item {
        display: flex;
        align-items: center;
    }
    
    .facility-item input {
        margin-right: 8px;
    }
    
    .form-actions {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    @media (max-width: 992px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-section:nth-child(3) {
            grid-column: span 1;
        }
        
        .facilities-list {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .facilities-list {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview for single image upload
    const imageInput = document.getElementById('image');
    const imagePreview = document.querySelector('.image-preview');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Image preview for gallery upload
    const galleryInput = document.getElementById('gallery');
    const galleryPreview = document.querySelector('.gallery-preview');
    
    if (galleryInput) {
        galleryInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                galleryPreview.innerHTML = '';
                
                for (let i = 0; i < this.files.length; i++) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        galleryPreview.innerHTML += `
                            <div class="gallery-item">
                                <img src="${e.target.result}" alt="Gallery Preview">
                            </div>
                        `;
                    }
                    
                    reader.readAsDataURL(this.files[i]);                }
            }
        });
    }
});
</script>
@endsection
