@extends('layouts.app')

@section('title', 'Tambah Lapangan')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">Tambah Lapangan Baru</h1>
                    <p class="text-muted mb-0">Daftarkan lapangan olahraga Anda ke platform kami</p>
                </div>
                <a href="{{ route('owner.fields') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Informasi Lapangan
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle me-1"></i>Terdapat kesalahan:
                            </h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('owner.fields.store') }}" id="fieldForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-info-circle me-1"></i>Informasi Dasar
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">
                                            Nama Lapangan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Contoh: Lapangan Futsal Bintang"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Berikan nama yang menarik dan mudah diingat</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="category" class="form-label fw-bold">
                                            Kategori Olahraga <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('category') is-invalid @enderror" 
                                                id="category" 
                                                name="category" 
                                                required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Futsal" {{ old('category') == 'Futsal' ? 'selected' : '' }}>⚽ Futsal</option>
                                            <option value="Badminton" {{ old('category') == 'Badminton' ? 'selected' : '' }}>🏸 Badminton</option>
                                            <option value="Basket" {{ old('category') == 'Basket' ? 'selected' : '' }}>🏀 Basket</option>
                                            <option value="Voli" {{ old('category') == 'Voli' ? 'selected' : '' }}>🏐 Voli</option>
                                            <option value="Tenis" {{ old('category') == 'Tenis' ? 'selected' : '' }}>🎾 Tenis</option>
                                            <option value="Sepak Bola" {{ old('category') == 'Sepak Bola' ? 'selected' : '' }}>⚽ Sepak Bola</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Deskripsi Lapangan</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Jelaskan detail lapangan, fasilitas utama, dan keunggulan yang membedakan dari yang lain...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Deskripsi yang menarik akan membantu menarik lebih banyak customer</div>
                            </div>
                        </div>

                        <!-- Location & Pricing -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-geo-alt me-1"></i>Lokasi & Harga
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="location" class="form-label fw-bold">
                                            Alamat Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('location') is-invalid @enderror" 
                                               id="location" 
                                               name="location" 
                                               value="{{ old('location') }}" 
                                               placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota"
                                               required>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Sertakan alamat lengkap untuk memudahkan customer menemukan lokasi</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="price" class="form-label fw-bold">
                                            Harga per Jam <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>                                            <input type="number" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" 
                                                   name="price" 
                                                   value="{{ old('price') }}" 
                                                   placeholder="150000"
                                                   required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Harga kompetitif akan menarik lebih banyak booking</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-clock me-1"></i>Jam Operasional
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="open_time" class="form-label fw-bold">
                                            Jam Buka <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" 
                                               class="form-control @error('open_time') is-invalid @enderror" 
                                               id="open_time" 
                                               name="open_time" 
                                               value="{{ old('open_time', '08:00') }}" 
                                               required>
                                        @error('open_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="close_time" class="form-label fw-bold">
                                            Jam Tutup <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" 
                                               class="form-control @error('close_time') is-invalid @enderror" 
                                               id="close_time" 
                                               name="close_time" 
                                               value="{{ old('close_time', '22:00') }}" 
                                               required>
                                        @error('close_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-1"></i>
                                <small>Pastikan jam operasional sesuai dengan ketersediaan Anda untuk melayani customer</small>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-image me-1"></i>Foto Lapangan
                            </h6>                            <div class="mb-3">
                                <x-file-upload 
                                    name="image" 
                                    label="Upload Foto Lapangan" 
                                    help="Upload foto lapangan (JPG, PNG, max 2MB)" 
                                    accept="image/jpeg,image/png"
                                    :currentImage="old('image_url')" 
                                />
                                
                                <div class="mt-3">
                                    <label for="image_url" class="form-label fw-bold">Atau Masukkan URL Gambar</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                                        <input type="url" 
                                            class="form-control @error('image_url') is-invalid @enderror" 
                                            id="image_url" 
                                            name="image_url" 
                                            value="{{ old('image_url') }}"
                                            placeholder="https://example.com/gambar-lapangan.jpg">
                                        @error('image_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Masukkan URL gambar online jika tidak mengupload file</small>
                                </div>
                            </div>

                            <!-- Gallery Images Upload -->
                            <div class="mt-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-images me-1"></i>
                                    Galeri Foto Lapangan
                                </label>
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="custom-file-upload">
                                            <input type="file" class="form-control mb-3 @error('gallery.*') is-invalid @enderror" 
                                                id="gallery" name="gallery[]" accept="image/jpeg,image/png" multiple>
                                            <div id="galleryPreview" class="row g-2 mt-2">
                                                <!-- JavaScript will populate this -->
                                            </div>
                                            @error('gallery.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="text-muted small mt-2">
                                            <ul class="mb-0 ps-3">
                                                <li>Upload beberapa foto untuk menampilkan detail lapangan</li>
                                                <li>Format yang didukung: JPG, PNG (max 2MB per file)</li>
                                                <li>Semakin banyak foto, semakin menarik bagi calon penyewa</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Facilities -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-check-circle me-1"></i>Fasilitas Tersedia
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Parkir Luas" id="facility1" {{ in_array('Parkir Luas', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility1">
                                            <i class="bi bi-car-front me-1"></i>Parkir Luas
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Toilet" id="facility2" {{ in_array('Toilet', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility2">
                                            <i class="bi bi-door-open me-1"></i>Toilet
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Kantin" id="facility3" {{ in_array('Kantin', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility3">
                                            <i class="bi bi-cup-hot me-1"></i>Kantin
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="WiFi" id="facility4" {{ in_array('WiFi', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility4">
                                            <i class="bi bi-wifi me-1"></i>WiFi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Kamar Ganti" id="facility5" {{ in_array('Kamar Ganti', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility5">
                                            <i class="bi bi-door-closed me-1"></i>Kamar Ganti
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Loker" id="facility6" {{ in_array('Loker', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility6">
                                            <i class="bi bi-safe me-1"></i>Loker
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="AC" id="facility7" {{ in_array('AC', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility7">
                                            <i class="bi bi-snow me-1"></i>AC
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Sound System" id="facility8" {{ in_array('Sound System', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility8">
                                            <i class="bi bi-volume-up me-1"></i>Sound System
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="CCTV" id="facility9" {{ in_array('CCTV', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility9">
                                            <i class="bi bi-camera me-1"></i>CCTV
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Lampu Penerangan" id="facility10" {{ in_array('Lampu Penerangan', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility10">
                                            <i class="bi bi-lightbulb me-1"></i>Lampu Penerangan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Tribun Penonton" id="facility11" {{ in_array('Tribun Penonton', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility11">
                                            <i class="bi bi-people me-1"></i>Tribun Penonton
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-check-custom">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="Mushola" id="facility12" {{ in_array('Mushola', old('facilities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="facility12">
                                            <i class="bi bi-moon me-1"></i>Mushola
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-gear me-1"></i>Pengaturan Tambahan
                            </h6>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="promo" 
                                       id="promo" {{ old('promo') ? 'checked' : '' }}>
                                <label class="form-check-label" for="promo">
                                    <strong>Lapangan sedang promo</strong>
                                    <br><small class="text-muted">Centang jika lapangan sedang memberikan diskon atau promo khusus</small>
                                </label>
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active" 
                                       id="active" checked>
                                <label class="form-check-label" for="active">
                                    <strong>Aktifkan lapangan</strong>
                                    <br><small class="text-muted">Lapangan aktif akan muncul di pencarian dan dapat dibooking</small>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('owner.fields') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="bi bi-check-circle me-1"></i>Simpan Lapangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar with Tips -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb me-1"></i>Tips Sukses
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-primary rounded-circle p-2 me-3 flex-shrink-0">
                            <i class="bi bi-camera text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Foto Berkualitas</h6>
                            <p class="text-muted small mb-0">Gunakan foto dengan pencahayaan baik dan tunjukkan seluruh area lapangan</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-success rounded-circle p-2 me-3 flex-shrink-0">
                            <i class="bi bi-star text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Deskripsi Menarik</h6>
                            <p class="text-muted small mb-0">Jelaskan keunggulan dan fasilitas yang membedakan lapangan Anda</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-info rounded-circle p-2 me-3 flex-shrink-0">
                            <i class="bi bi-currency-dollar text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Harga Kompetitif</h6>
                            <p class="text-muted small mb-0">Riset harga lapangan serupa di sekitar untuk menetapkan harga yang menarik</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="bg-warning rounded-circle p-2 me-3 flex-shrink-0">
                            <i class="bi bi-clock text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Jam Operasional</h6>
                            <p class="text-muted small mb-0">Buka lebih lama = lebih banyak peluang booking</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-question-circle me-1"></i>Bantuan
                    </h6>
                </div>                <div class="card-body">
                    <p class="small mb-3">Butuh bantuan dalam mendaftarkan lapangan? Tim kami siap membantu!</p>
                    <div class="d-grid gap-2">                        <a href="mailto:suport.lapangkuy@gmail.com" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-envelope me-1"></i>Email Support
                        </a>
                        <a href="tel:+6282133173461" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-telephone me-1"></i>Hubungi Kami (24 Jam)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-check-custom .form-check-input {
    margin-top: 0.1rem;
}

.form-check-custom .form-check-label {
    font-size: 0.9rem;
    line-height: 1.3;
}

#imagePreview img {
    border: 2px solid #e9ecef;
    border-radius: 0.375rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image URL preview
    const imageUrlInput = document.getElementById('image_url');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    imageUrlInput.addEventListener('input', function() {
        const url = this.value;
        if (url && isValidUrl(url)) {
            previewImg.src = url;
            imagePreview.style.display = 'block';
            previewImg.onerror = function() {
                imagePreview.style.display = 'none';
            };
        } else {
            imagePreview.style.display = 'none';
        }
    });
    
    // Form validation
    document.getElementById('fieldForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
    });
      // Price formatting disabled to allow direct input of numbers
    
    
    // Time validation
    const openTimeInput = document.getElementById('open_time');
    const closeTimeInput = document.getElementById('close_time');
    
    function validateTime() {
        const openTime = openTimeInput.value;
        const closeTime = closeTimeInput.value;
        
        if (openTime && closeTime) {
            if (openTime >= closeTime) {
                closeTimeInput.setCustomValidity('Jam tutup harus lebih dari jam buka');
            } else {
                closeTimeInput.setCustomValidity('');
            }
        }
    }
    
    openTimeInput.addEventListener('change', validateTime);
    closeTimeInput.addEventListener('change', validateTime);
    
    // Gallery images preview
    const galleryInput = document.getElementById('gallery');
    const galleryPreview = document.getElementById('galleryPreview');
    
    if (galleryInput) {
        galleryInput.addEventListener('change', function() {
            galleryPreview.innerHTML = ''; // Clear existing previews
            
            if (this.files) {
                Array.from(this.files).forEach((file, index) => {
                    if (!file.type.match('image.*')) return;
                    
                    const reader = new FileReader();
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-4 col-md-3 mb-2';
                    
                    reader.onload = function(e) {
                        colDiv.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" alt="Gallery preview ${index + 1}">
                                <span class="position-absolute top-0 end-0 badge bg-dark">
                                    ${index + 1}
                                </span>
                            </div>
                        `;
                        galleryPreview.appendChild(colDiv);
                    };
                    
                    reader.readAsDataURL(file);
                });
            }
        });
    }
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
});
</script>
@endsection
