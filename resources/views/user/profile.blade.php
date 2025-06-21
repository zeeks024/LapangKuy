@extends('layouts.app')
@section('title', 'Profil Pengguna')

@section('styles')
<style>
    .profile-image-upload {
        margin-bottom: 2rem;
    }
    
    .image-upload-container {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
    }
    
    .current-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid #075e54;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .current-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f5f5f5;
    }
    
    .no-image i {
        font-size: 2rem;
        color: #aaa;
        margin-bottom: 0.5rem;
    }
    
    .no-image p {
        margin: 0;
        font-size: 0.8rem;
        color: #888;
    }
    
    .upload-controls {
        flex-grow: 1;
    }    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    /* Additional styling to ensure consistent profile image appearance */
    .profile-image-container {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 50%;
        position: relative;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    }
    
    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #075e54;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        margin-right: 20px;
        border: 3px solid white;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    
    .avatar-placeholder span {
        display: block;
        text-align: center;
    }
    
    .profile-header .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-avatar-header {
        display: flex;
        align-items: center;
    }
      .fallback-img {
        background-color: #f0f0f0;
    }
    
    .img-thumbnail {
        border: none !important;
        padding: 0 !important;
        border-radius: 50% !important;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .custom-file-upload {
        display: flex;
        flex-direction: column;
    }
    
    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    
    #file-name-display {
        color: #666;
        font-style: italic;
    }
    
    .btn-outline-primary {
        border-color: #075e54;
        color: #075e54;
    }
    
    .btn-outline-primary:hover {
        background-color: #075e54;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container fade-in" style="max-width:1000px; margin:40px auto;">
    <div class="profile-container">
        <div class="profile-header">
            <div class="header-content">                <div class="profile-avatar-header">
                    <div class="avatar-placeholder">
                        @if($user->profile_image)
                            <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" class="profile-image" onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text={{ substr($user->name, 0, 1) }}'; this.classList.add('fallback-img');">
                        @else
                            <span>{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="user-info">
                        <h1 class="user-name">{{ $user->name }}</h1>
                        <p class="user-email">{{ $user->email }}</p>
                        <span class="user-badge">
                            @if(Auth::user()->isAdmin())
                                <i class="fas fa-shield-alt"></i> Administrator
                            @elseif(Auth::user()->isFieldOwner())
                                <i class="fas fa-building"></i> Pemilik Lapangan
                            @else
                                <i class="fas fa-user"></i> Pengguna
                            @endif
                        </span>
                    </div>
                </div>                <a href="{{ route('user.dashboard') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Booking Saya
                </a>
            </div>
        </div>        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            @if(session('image_uploaded'))
                <br>Foto profil berhasil diperbarui! Jika foto baru tidak muncul, silakan refresh halaman.
            @endif
        </div>
        @endif<!-- Profile Navigation Tabs -->
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="personal">
                <i class="fas fa-user"></i> Informasi Pribadi
            </button>
            <button class="tab-btn" data-tab="security">
                <i class="fas fa-shield-alt"></i> Keamanan
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Personal Information Tab -->
            <div class="tab-pane active" id="personal">
                <div class="tab-card">                    <h3 class="tab-title">Informasi Pribadi</h3>
                    <form action="{{ route('user.profile.update') }}" method="POST" class="profile-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                          <div class="form-group profile-image-upload">
                            <label for="profile_image">Foto Profil</label>
                            <div class="image-upload-container">
                                <div class="current-image">                                    @if($user->profile_image)
                                        <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="img-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/100?text={{ substr($user->name, 0, 1) }}'; this.classList.add('fallback-img');">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-user"></i>
                                            <p>Belum ada foto</p>
                                        </div>
                                    @endif
                                </div>                                <div class="upload-controls">
                                    <div class="custom-file-upload">
                                        <label for="profile_image" class="btn btn-outline-primary mb-2">
                                            <i class="fas fa-camera me-2"></i>Pilih Foto
                                        </label>
                                        <input type="file" class="form-control visually-hidden" id="profile_image" name="profile_image" 
                                            accept="image/jpeg,image/jpg,image/png" onchange="previewImage(this)">
                                        <div id="file-name-display" class="small text-muted mb-2">Belum ada file yang dipilih</div>
                                        <small class="form-text text-muted">Format yang diterima: JPG, PNG. Maks: 2MB</small>
                                        <small class="form-text text-primary">Foto akan ditampilkan dalam lingkaran</small>
                                    </div>
                                    @error('profile_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Nama Lengkap *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                       class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                          <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   class="form-control @error('phone') is-invalid @enderror" placeholder="+62 xxx-xxxx-xxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror" 
                                      placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="3" class="form-control" 
                                      placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane" id="security">
                <div class="tab-card">
                    <h3 class="tab-title">Keamanan Akun</h3>
                    
                    <!-- Password Change Section -->
                    <div class="security-section">
                        <div class="section-header">
                            <h4>Ganti Password</h4>
                            <p>Pastikan akun Anda menggunakan password yang kuat dan unik</p>
                        </div>
                        <form action="{{ route('user.password.update') }}" method="POST" class="password-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_password">Password Saat Ini *</label>
                                <div class="password-input-group">
                                    <input type="password" id="current_password" name="current_password" 
                                           class="form-control @error('current_password') is-invalid @enderror" required>
                                    <button type="button" class="password-toggle" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="password">Password Baru *</label>
                                    <div class="password-input-group">
                                        <input type="password" id="password" name="password" 
                                               class="form-control @error('password') is-invalid @enderror" required>
                                        <button type="button" class="password-toggle" tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password *</label>
                                    <div class="password-input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation" 
                                               class="form-control" required>
                                        <button type="button" class="password-toggle" tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="password-requirements">
                                <p><i class="fas fa-info-circle"></i> Password harus memenuhi kriteria:</p>
                                <ul>
                                    <li>Minimal 8 karakter</li>
                                    <li>Mengandung huruf besar dan kecil</li>
                                    <li>Mengandung minimal 1 angka</li>
                                    <li>Mengandung karakter khusus (!@#$%^&*)</li>
                                </ul>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>                        </form>                    </div>
                </div>
            </div>

            <!-- Preferences Tab -->
            <div class="tab-pane" id="preferences">
                <div class="tab-card">
                    <h3 class="tab-title">Preferensi</h3>
                    
                    <form action="{{ route('user.preferences.update') }}" method="POST" class="preferences-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Notification Preferences -->
                        <div class="preference-section">
                            <h4>Notifikasi</h4>
                            <div class="preference-group">
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h5>Email Notifications</h5>
                                        <p>Terima notifikasi booking melalui email</p>
                                    </div>
                                    <div class="preference-toggle">
                                        <input type="checkbox" id="email_notifications" name="email_notifications" 
                                               {{ ($user->preferences['email_notifications'] ?? true) ? 'checked' : '' }}>
                                        <label for="email_notifications" class="toggle-label"></label>
                                    </div>
                                </div>
                                
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h5>Promosi & Penawaran</h5>
                                        <p>Terima informasi promo dan penawaran khusus</p>
                                    </div>
                                    <div class="preference-toggle">
                                        <input type="checkbox" id="promotional_emails" name="promotional_emails" 
                                               {{ ($user->preferences['promotional_emails'] ?? false) ? 'checked' : '' }}>
                                        <label for="promotional_emails" class="toggle-label"></label>
                                    </div>
                                </div>
                                
                                <div class="preference-item">
                                    <div class="preference-info">
                                        <h5>Reminder Booking</h5>
                                        <p>Pengingat 24 jam sebelum waktu booking</p>
                                    </div>
                                    <div class="preference-toggle">
                                        <input type="checkbox" id="booking_reminders" name="booking_reminders" 
                                               {{ ($user->preferences['booking_reminders'] ?? true) ? 'checked' : '' }}>
                                        <label for="booking_reminders" class="toggle-label"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Display Preferences -->
                        <div class="preference-section">
                            <h4>Tampilan</h4>
                            <div class="preference-group">
                                <div class="form-group">
                                    <label for="language">Bahasa</label>
                                    <select id="language" name="language" class="form-control">
                                        <option value="id" {{ ($user->preferences['language'] ?? 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                        <option value="en" {{ ($user->preferences['language'] ?? 'id') == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="timezone">Zona Waktu</label>
                                    <select id="timezone" name="timezone" class="form-control">
                                        <option value="Asia/Jakarta" {{ ($user->preferences['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Jakarta)</option>
                                        <option value="Asia/Makassar" {{ ($user->preferences['timezone'] ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Makassar)</option>
                                        <option value="Asia/Jayapura" {{ ($user->preferences['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Jayapura)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sports Preferences -->
                        <div class="preference-section">
                            <h4>Olahraga Favorit</h4>
                            <div class="sports-preferences">
                                @php
                                    $sports = ['Futsal', 'Badminton', 'Basket', 'Voli', 'Tennis', 'Sepak Bola'];
                                    $userSports = $user->preferences['favorite_sports'] ?? [];
                                @endphp
                                @foreach($sports as $sport)
                                <div class="sport-item">
                                    <input type="checkbox" id="sport_{{ strtolower($sport) }}" name="favorite_sports[]" 
                                           value="{{ $sport }}" {{ in_array($sport, $userSports) ? 'checked' : '' }}>
                                    <label for="sport_{{ strtolower($sport) }}" class="sport-label">
                                        <i class="fas fa-futbol"></i>
                                        {{ $sport }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity Tab -->
            <div class="tab-pane" id="activity">
                <div class="tab-card">
                    <h3 class="tab-title">Aktivitas Terbaru</h3>
                    
                    <!-- Stats Cards -->
                    <div class="activity-stats">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <h4>12</h4>
                                <p>Total Booking</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <h4>8</h4>
                                <p>Review Diberikan</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h4>24</h4>
                                <p>Jam Bermain</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-content">
                                <h4>Silver</h4>
                                <p>Level Member</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity Timeline -->
                    <div class="activity-timeline">
                        <h4>Timeline Aktivitas</h4>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon booking">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Booking Lapangan Futsal</h5>
                                    <p>Lapangan Futsal Central Park</p>
                                    <small>2 hari yang lalu</small>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon review">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Memberikan Review</h5>
                                    <p>Rating 5/5 untuk Lapangan Badminton Senayan</p>
                                    <small>1 minggu yang lalu</small>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon payment">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Pembayaran Berhasil</h5>
                                    <p>Rp 150.000 untuk booking 2 jam</p>
                                    <small>1 minggu yang lalu</small>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon profile">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Update Profil</h5>
                                    <p>Mengubah informasi kontak</p>
                                    <small>2 minggu yang lalu</small>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon signup">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <h5>Bergabung dengan LapangKuy</h5>
                                    <p>Selamat datang di komunitas LapangKuy!</p>
                                    <small>1 bulan yang lalu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base Variables */
    :root {
        --primary-color: #043e03;
        --secondary-color: #ff4757;
        --accent-color: #2ed573;
        --white: #ffffff;
        --light-bg: #f8f9fa;
        --border-color: #e0e6ed;
        --light-text: #6c757d;
        --dark-text: #2c3e50;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --radius-sm: 8px;
        --radius-md: 12px;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
        --transition: all 0.3s ease;
    }

    .profile-container {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #2d5016 100%);
        color: var(--white);
        padding: 30px;
    }
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .profile-avatar-header {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }
    
    .avatar-placeholder span {
        font-size: 36px;
        font-weight: 600;
        color: var(--white);
        text-transform: uppercase;
    }
    
    .user-info h1 {
        margin: 0 0 5px 0;
        font-size: 28px;
        font-weight: 600;
    }
    
    .user-email {
        margin: 0 0 10px 0;
        opacity: 0.9;
        font-size: 16px;
    }
    
    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .back-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--white);
        text-decoration: none;
        padding: 10px 15px;
        border-radius: var(--radius-sm);
        transition: var(--transition);
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .back-link:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--white);
        text-decoration: none;
    }
    
    /* Alert Styles */
    .alert {
        padding: 15px 20px;
        margin: 20px 30px;
        border-radius: var(--radius-sm);
        border: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    /* Tab Navigation */
    .profile-tabs {
        display: flex;
        background-color: var(--light-bg);
        border-bottom: 1px solid var(--border-color);
    }
    
    .tab-btn {
        flex: 1;
        padding: 20px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--light-text);
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
    }
    
    .tab-btn:hover {
        background-color: rgba(4, 62, 3, 0.05);
        color: var(--primary-color);
    }
    
    .tab-btn.active {
        background-color: var(--white);
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
    }
    
    /* Tab Content */
    .tab-content {
        position: relative;
    }
    
    .tab-pane {
        display: none;
        animation: fadeIn 0.3s ease;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-card {
        padding: 30px;
    }
    
    .tab-title {
        margin: 0 0 25px 0;
        font-size: 24px;
        color: var(--dark-text);
        font-weight: 600;
    }
    
    /* Form Styles */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark-text);
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: var(--transition);
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(4, 62, 3, 0.1);
        outline: none;
    }
    
    .form-control.is-invalid {
        border-color: var(--secondary-color);
    }
    
    .invalid-feedback {
        color: var(--secondary-color);
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    
    /* Security Section */
    .security-section {
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .security-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .section-header h4 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: var(--dark-text);
    }
    
    .section-header p {
        margin: 0 0 20px 0;
        color: var(--light-text);
        font-size: 14px;
    }
    
    .password-input-group {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--light-text);
        cursor: pointer;
        padding: 5px;
        transition: var(--transition);
    }
    
    .password-toggle:hover {
        color: var(--primary-color);
    }
    
    .password-requirements {
        margin-top: 15px;
        padding: 15px;
        background-color: var(--light-bg);
        border-radius: var(--radius-sm);
        border-left: 4px solid var(--info-color);
    }
    
    .password-requirements p {
        margin: 0 0 10px 0;
        font-weight: 500;
        color: var(--dark-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .password-requirements ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .password-requirements li {
        margin-bottom: 5px;
        color: var(--light-text);
        font-size: 13px;
    }
    
    /* Two Factor Authentication */
    .two-factor-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: var(--light-bg);
        border-radius: var(--radius-sm);
    }
    
    .status-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .status-icon.disabled {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .status-icon.enabled {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .status-content h5 {
        margin: 0 0 5px 0;
        font-size: 16px;
        color: var(--dark-text);
    }
    
    .status-content p {
        margin: 0;
        color: var(--light-text);
        font-size: 14px;
    }
    
    /* Active Sessions */
    .active-sessions {
        space-y: 15px;
    }
    
    .session-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background-color: var(--light-bg);
        border-radius: var(--radius-sm);
        margin-bottom: 10px;
    }
    
    .session-item.current {
        border-left: 4px solid var(--success-color);
    }
    
    .session-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .session-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .session-details h5 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: var(--dark-text);
    }
    
    .current-badge {
        background-color: var(--success-color);
        color: var(--white);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        margin-left: 8px;
    }
    
    .session-details p, .session-details small {
        margin: 0;
        color: var(--light-text);
        font-size: 13px;
    }
    
    /* Preferences */
    .preference-section {
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .preference-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .preference-section h4 {
        margin: 0 0 20px 0;
        font-size: 18px;
        color: var(--dark-text);
    }
    
    .preference-group {
        space-y: 15px;
    }
    
    .preference-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .preference-item:last-child {
        border-bottom: none;
    }
    
    .preference-info h5 {
        margin: 0 0 5px 0;
        font-size: 16px;
        color: var(--dark-text);
    }
    
    .preference-info p {
        margin: 0;
        color: var(--light-text);
        font-size: 14px;
    }
    
    .preference-toggle {
        position: relative;
    }
    
    .preference-toggle input[type="checkbox"] {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-label {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
        background-color: #ccc;
        border-radius: 24px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .toggle-label:before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background-color: var(--white);
        border-radius: 50%;
        transition: var(--transition);
    }
    
    input[type="checkbox"]:checked + .toggle-label {
        background-color: var(--primary-color);
    }
    
    input[type="checkbox"]:checked + .toggle-label:before {
        transform: translateX(26px);
    }
    
    /* Sports Preferences */
    .sports-preferences {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .sport-item {
        position: relative;
    }
    
    .sport-item input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .sport-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
        background-color: var(--white);
    }
    
    .sport-label:hover {
        border-color: var(--primary-color);
        background-color: rgba(4, 62, 3, 0.05);
    }
    
    input[type="checkbox"]:checked + .sport-label {
        border-color: var(--primary-color);
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    /* Activity Stats */
    .activity-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background-color: var(--light-bg);
        padding: 20px;
        border-radius: var(--radius-sm);
        text-align: center;
        transition: var(--transition);
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 20px;
    }
    
    .stat-content h4 {
        margin: 0 0 5px 0;
        font-size: 24px;
        color: var(--primary-color);
        font-weight: 700;
    }
    
    .stat-content p {
        margin: 0;
        color: var(--light-text);
        font-size: 14px;
    }
    
    /* Activity Timeline */
    .activity-timeline h4 {
        margin: 0 0 25px 0;
        font-size: 18px;
        color: var(--dark-text);
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: var(--border-color);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -22px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 12px;
        z-index: 1;
    }
    
    .timeline-icon.booking {
        background-color: var(--primary-color);
    }
    
    .timeline-icon.review {
        background-color: var(--warning-color);
    }
    
    .timeline-icon.payment {
        background-color: var(--success-color);
    }
    
    .timeline-icon.profile {
        background-color: var(--info-color);
    }
    
    .timeline-icon.signup {
        background-color: #6f42c1;
    }
    
    .timeline-content {
        margin-left: 15px;
    }
    
    .timeline-content h5 {
        margin: 0 0 5px 0;
        font-size: 16px;
        color: var(--dark-text);
    }
    
    .timeline-content p {
        margin: 0 0 5px 0;
        color: var(--light-text);
        font-size: 14px;
    }
    
    .timeline-content small {
        color: var(--light-text);
        font-size: 12px;
    }
    
    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 14px;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    .btn-primary:hover {
        background-color: #033002;
        color: var(--white);
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background-color: var(--light-text);
        color: var(--white);
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        color: var(--white);
        text-decoration: none;
    }
    
    .btn-outline-primary {
        background-color: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: var(--white);
        text-decoration: none;
    }
      /* Responsive Design */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }
        
        .profile-avatar-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .avatar-placeholder {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .profile-tabs {
            flex-direction: column;
        }
        
        .tab-btn {
            justify-content: flex-start;
            padding: 15px 20px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .tab-card {
            padding: 20px;
        }
        
        .activity-stats {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        
        .sports-preferences {
            grid-template-columns: 1fr;
        }
        
        .two-factor-status {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }
    
    @media (max-width: 480px) {
        .avatar-placeholder {
            width: 60px;
            height: 60px;
        }
        
        .avatar-placeholder span {
            font-size: 24px;
        }
        
        .user-info h1 {
            font-size: 22px;
        }
        
        .tab-card {
            padding: 15px;
        }
        
        .activity-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
    
    // Password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Password confirmation validation
            const password = form.querySelector('input[name="password"]');
            const passwordConfirmation = form.querySelector('input[name="password_confirmation"]');
            
            if (password && passwordConfirmation) {
                if (password.value !== passwordConfirmation.value) {
                    isValid = false;
                    passwordConfirmation.classList.add('is-invalid');
                    
                    // Show error message
                    let errorDiv = passwordConfirmation.parentNode.querySelector('.invalid-feedback');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.classList.add('invalid-feedback');
                        passwordConfirmation.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Konfirmasi password tidak cocok';
                } else {
                    passwordConfirmation.classList.remove('is-invalid');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
    
    // Image preview function
    function previewImage(input) {
        const fileNameDisplay = document.getElementById('file-name-display');
        
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            fileNameDisplay.textContent = fileName;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImage = document.querySelector('.current-image img');
                const noImage = document.querySelector('.current-image .no-image');
                
                if (previewImage) {
                    // If image already exists, update source
                    previewImage.src = e.target.result;
                } else if (noImage) {
                    // Replace no-image div with an image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Profile Image';
                    img.classList.add('img-thumbnail');
                    
                    const currentImage = document.querySelector('.current-image');
                    currentImage.innerHTML = '';
                    currentImage.appendChild(img);
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            fileNameDisplay.textContent = 'Belum ada file yang dipilih';
        }
    }
});
</script>
@endsection
