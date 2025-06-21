@extends('layouts.app')

@section('title', 'Edit Lapangan')

@push('styles')
<style>
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #007bff;
    }
    
    .form-section h5 {
        color: #007bff;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .tips-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        position: sticky;
        top: 20px;
    }
    
    .tips-sidebar h6 {
        color: #ffffff;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .tips-sidebar .tip-item {
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        backdrop-filter: blur(10px);
    }
    
    .tips-sidebar .tip-item:last-child {
        margin-bottom: 0;
    }
    
    .facility-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .facility-card:hover {
        border-color: #007bff;
        box-shadow: 0 2px 8px rgba(0,123,255,0.1);
    }
    
    .facility-card.selected {
        border-color: #007bff;
        background: #f0f8ff;
    }
    
    .facility-card .form-check-input {
        margin-top: 0;
    }
    
    .image-preview-container {
        position: relative;
        max-width: 300px;
        margin-top: 1rem;
    }
    
    .image-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    .remove-image {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-size: 14px;
        cursor: pointer;
    }
    
    .progress-indicator {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }
    
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 12px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #e9ecef;
    }
    
    .progress-step.active::after {
        background: #007bff;
    }
    
    .progress-step .step-number {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }
    
    .progress-step.active .step-number {
        background: #007bff;
        color: white;
    }
    
    .progress-step.completed .step-number {
        background: #28a745;
        color: white;
    }
    
    .current-field-info {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    
    .current-field-info h4 {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .field-stats {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .stat-item {
        background: rgba(255,255,255,0.15);
        border-radius: 8px;
        padding: 0.75rem;
        text-align: center;
        flex: 1;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">Edit Lapangan</h1>
                    <p class="text-muted mb-0">Perbarui informasi lapangan Anda</p>
                </div>
                <a href="{{ route('owner.fields') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Current Field Info -->
    <div class="current-field-info">
        <h4>{{ $field->name }}</h4>
        <p class="mb-0">{{ $field->category }} • {{ $field->location }}</p>
        <div class="field-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $field->bookings()->count() }}</div>
                <div class="stat-label">Total Booking</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ number_format($field->reviews()->avg('rating') ?: 0, 1) }}</div>
                <div class="stat-label">Rating</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Rp {{ number_format($field->price, 0, ',', '.') }}</div>
                <div class="stat-label">Harga/Jam</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Progress Indicator -->
            <div class="progress-indicator">
                <div class="progress-steps">
                    <div class="progress-step active completed">
                        <div class="step-number">1</div>
                        <small>Info Dasar</small>
                    </div>
                    <div class="progress-step active completed">
                        <div class="step-number">2</div>
                        <small>Lokasi & Harga</small>
                    </div>
                    <div class="progress-step active completed">
                        <div class="step-number">3</div>
                        <small>Fasilitas</small>
                    </div>
                    <div class="progress-step active">
                        <div class="step-number">4</div>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>

            <!-- Form -->            <form method="POST" action="{{ route('owner.fields.update', $field) }}" id="editFieldForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h5><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lapangan *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $field->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori Olahraga *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Futsal" {{ old('category', $field->category) == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                                    <option value="Badminton" {{ old('category', $field->category) == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                                    <option value="Basket" {{ old('category', $field->category) == 'Basket' ? 'selected' : '' }}>Basket</option>
                                    <option value="Voli" {{ old('category', $field->category) == 'Voli' ? 'selected' : '' }}>Voli</option>
                                    <option value="Tenis" {{ old('category', $field->category) == 'Tenis' ? 'selected' : '' }}>Tenis</option>
                                    <option value="Sepak Bola" {{ old('category', $field->category) == 'Sepak Bola' ? 'selected' : '' }}>Sepak Bola</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Lapangan</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Deskripsikan lapangan Anda secara detail...">{{ old('description', $field->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-lightbulb me-1"></i>
                            Semakin detail deskripsi, semakin mudah pelanggan memahami fasilitas lapangan Anda
                        </div>
                    </div>                    <div class="mb-4">
                        <x-file-upload 
                            name="image" 
                            label="Upload Foto Lapangan" 
                            help="Upload foto lapangan baru (JPG, PNG, max 2MB)" 
                            accept="image/jpeg,image/png"
                            :currentImage="$field->image_url" 
                        />
                        
                        <div class="mt-3">
                            <label for="image_url" class="form-label">Atau Masukkan URL Gambar</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                                <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                    id="image_url" name="image_url" value="{{ old('image_url', $field->image_url) }}"
                                    placeholder="https://example.com/gambar-lapangan.jpg">
                                @error('image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Masukkan URL gambar online jika tidak mengupload file</small>
                        </div>
                        
                        @if($field->image_url)
                        <div class="image-preview-container mt-3" id="imagePreviewContainer">
                            <img src="{{ $field->image_url }}" alt="{{ $field->name }}" class="image-preview">
                            <button type="button" class="remove-image" id="removeImageBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Gallery Images Upload -->
                    <div class="mt-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-images me-2"></i>
                            Galeri Foto Lapangan
                        </label>
                        <div class="card border">
                            <div class="card-body">
                                <div class="custom-file-upload">
                                    <input type="file" class="form-control mb-3 @error('gallery.*') is-invalid @enderror" 
                                        id="gallery" name="gallery[]" accept="image/jpeg,image/png" multiple>
                                    
                                    <div id="galleryPreview" class="row g-2 mt-2">
                                        @if($field->gallery && is_array($field->gallery))
                                            @foreach($field->gallery as $index => $galleryImage)
                                                <div class="col-4 col-md-3 mb-2">
                                                    <div class="position-relative">
                                                        <img src="{{ $galleryImage }}" class="img-thumbnail" alt="Gallery image {{ $index + 1 }}">
                                                        <span class="position-absolute top-0 end-0 badge bg-dark">
                                                            {{ $index + 1 }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    @error('gallery.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="text-muted small mt-2">
                                    <ul class="mb-0 ps-3">
                                        <li>Upload beberapa foto untuk menampilkan detail lapangan</li>
                                        <li>Format yang didukung: JPG, PNG (max 2MB per file)</li>
                                        <li>File baru yang diupload akan ditambahkan ke galeri yang sudah ada</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Pricing Section -->
                <div class="form-section">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>Lokasi & Harga</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="location" class="form-label">Alamat Lengkap *</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $field->location) }}" 
                                       placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota" required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga per Jam *</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $field->price) }}" 
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operating Hours Section -->
                <div class="form-section">
                    <h5><i class="fas fa-clock me-2"></i>Jam Operasional</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="open_time" class="form-label">Jam Buka *</label>
                                <input type="time" class="form-control @error('open_time') is-invalid @enderror" 
                                       id="open_time" name="open_time" value="{{ old('open_time', $field->open_time) }}" required>
                                @error('open_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="close_time" class="form-label">Jam Tutup *</label>
                                <input type="time" class="form-control @error('close_time') is-invalid @enderror" 
                                       id="close_time" name="close_time" value="{{ old('close_time', $field->close_time) }}" required>
                                @error('close_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facilities Section -->
                <div class="form-section">
                    <h5><i class="fas fa-star me-2"></i>Fasilitas Lapangan</h5>
                    <p class="text-muted mb-3">Pilih fasilitas yang tersedia di lapangan Anda</p>
                    
                    <div class="row">                        @php
                            $currentFacilities = $field->facilities ?? [];
                            // If facilities is a string, try to decode it
                            if (is_string($currentFacilities) && !is_null($currentFacilities)) {
                                $decoded = json_decode($currentFacilities, true);
                                $currentFacilities = is_array($decoded) ? $decoded : [];
                            } elseif (!is_array($currentFacilities)) {
                                $currentFacilities = [];
                            }
                            
                            $facilitiesData = [
                                ['name' => 'Parkir Luas', 'icon' => 'fas fa-parking', 'desc' => 'Area parkir yang luas dan aman'],
                                ['name' => 'Toilet', 'icon' => 'fas fa-restroom', 'desc' => 'Toilet bersih dan terawat'],
                                ['name' => 'Kantin', 'icon' => 'fas fa-utensils', 'desc' => 'Kantin dengan makanan dan minuman'],
                                ['name' => 'WiFi', 'icon' => 'fas fa-wifi', 'desc' => 'Akses internet WiFi gratis'],
                                ['name' => 'Kamar Ganti', 'icon' => 'fas fa-tshirt', 'desc' => 'Ruang ganti yang nyaman'],
                                ['name' => 'Loker', 'icon' => 'fas fa-lock', 'desc' => 'Loker untuk menyimpan barang'],
                                ['name' => 'AC', 'icon' => 'fas fa-snowflake', 'desc' => 'Pendingin ruangan (AC)'],
                                ['name' => 'Sound System', 'icon' => 'fas fa-volume-up', 'desc' => 'Sistem audio berkualitas']
                            ];
                        @endphp
                        
                        @foreach($facilitiesData as $index => $facility)
                        <div class="col-lg-6">
                            <div class="facility-card {{ in_array($facility['name'], $currentFacilities) ? 'selected' : '' }}" 
                                 onclick="toggleFacility({{ $index }})">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="facilities[]" 
                                               value="{{ $facility['name'] }}" id="facility{{ $index }}"
                                               {{ in_array($facility['name'], $currentFacilities) ? 'checked' : '' }}>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="{{ $facility['icon'] }} me-2 text-primary"></i>
                                            <strong>{{ $facility['name'] }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $facility['desc'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="form-section">
                    <h5><i class="fas fa-tags me-2"></i>Opsi Tambahan</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="promo" 
                               id="promo" {{ old('promo', $field->promo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="promo">
                            <strong>Lapangan sedang promo</strong>
                            <div class="small text-muted">Tandai jika lapangan sedang ada diskon khusus</div>
                        </label>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('owner.fields') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Update Lapangan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips Sidebar -->
        <div class="col-lg-4">
            <div class="tips-sidebar">
                <h6><i class="fas fa-lightbulb me-2"></i>Tips Edit Lapangan</h6>
                
                <div class="tip-item">
                    <h6 class="mb-2">📸 Foto Berkualitas</h6>
                    <p class="mb-0 small">Update foto lapangan dengan gambar yang jelas dan menarik untuk meningkatkan minat pelanggan</p>
                </div>
                
                <div class="tip-item">
                    <h6 class="mb-2">💰 Harga Kompetitif</h6>
                    <p class="mb-0 small">Sesuaikan harga dengan kualitas dan fasilitas yang ditawarkan. Cek harga pesaing untuk referensi</p>
                </div>
                
                <div class="tip-item">
                    <h6 class="mb-2">⭐ Fasilitas Lengkap</h6>
                    <p class="mb-0 small">Semakin lengkap fasilitas, semakin menarik bagi pelanggan. Update fasilitas sesuai kondisi terkini</p>
                </div>
                
                <div class="tip-item">
                    <h6 class="mb-2">📝 Deskripsi Detail</h6>
                    <p class="mb-0 small">Jelaskan kondisi lapangan, ukuran, dan keunggulan secara detail untuk menarik pelanggan</p>
                </div>
                
                <div class="tip-item">
                    <h6 class="mb-2">🕐 Jam Operasional</h6>
                    <p class="mb-0 small">Pastikan jam operasional akurat dan sesuai dengan kemampuan pelayanan Anda</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image_url').addEventListener('input', function() {
        const url = this.value;
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        
        if (url) {
            preview.src = url;
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    });
    
    // Add functionality to remove image button
    const removeImageBtn = document.getElementById('removeImageBtn');
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            document.getElementById('image_url').value = '';
            document.getElementById('imagePreviewContainer').style.display = 'none';
        });
    }
    
    // Gallery images preview
    const galleryInput = document.getElementById('gallery');
    const galleryPreview = document.getElementById('galleryPreview');
    
    if (galleryInput) {
        galleryInput.addEventListener('change', function() {
            // Get existing gallery items count
            const existingCount = document.querySelectorAll('#galleryPreview > div').length;
            
            if (this.files) {
                Array.from(this.files).forEach((file, index) => {
                    if (!file.type.match('image.*')) return;
                    
                    const reader = new FileReader();
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-4 col-md-3 mb-2';
                    
                    reader.onload = function(e) {
                        colDiv.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" alt="New gallery image">
                                <span class="position-absolute top-0 end-0 badge bg-success">
                                    New ${index + 1}
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
    
    // Facility toggle functionality
    function toggleFacility(index) {
        const checkbox = document.getElementById('facility' + index);
        const card = checkbox.closest('.facility-card');
        
        checkbox.checked = !checkbox.checked;
        
        if (checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    }
    
    // Form validation
    document.getElementById('editFieldForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });
      // Price formatting disabled to allow direct input of numbers
    document.getElementById('price').addEventListener('input', function() {
        this.setCustomValidity('');
    });
    
    // Time validation
    document.getElementById('open_time').addEventListener('change', validateTime);
    document.getElementById('close_time').addEventListener('change', validateTime);
    
    function validateTime() {
        const openTime = document.getElementById('open_time').value;
        const closeTime = document.getElementById('close_time').value;
        
        if (openTime && closeTime && openTime >= closeTime) {
            document.getElementById('close_time').setCustomValidity('Jam tutup harus lebih besar dari jam buka');
        } else {            document.getElementById('close_time').setCustomValidity('');
        }
    }
</script>
@endpush
@endsection
