@extends('layouts.app')
@section('title', 'Ganti Password')
@section('content')
<div class="container fade-in" style="max-width:600px; margin:40px auto;">
    <div class="change-password-container">
        <div class="profile-header">
            <h1 class="section-title">Ganti Password</h1>
            <a href="{{ route('user.profile') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Profil
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="password-card">
            <form action="{{ route('user.password.update') }}" method="POST" class="password-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <div class="password-input-group">
                        <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="password-input-group">
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="password-requirements">
                        <p>Password harus:</p>
                        <ul>
                            <li>Minimal 8 karakter</li>
                            <li>Mengandung minimal 1 huruf besar</li>
                            <li>Mengandung minimal 1 angka</li>
                        </ul>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="password-input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .change-password-container {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .profile-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }
    
    .back-link {
        display: flex;
        align-items: center;
        color: var(--primary-color);
        font-size: 14px;
    }
    
    .back-link i {
        margin-right: 5px;
    }
    
    .password-card {
        padding: 30px;
    }
    
    .password-form .form-group {
        margin-bottom: 25px;
    }
    
    .password-form label {
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
    
    .password-input-group {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--light-text);
        cursor: pointer;
    }
    
    .password-requirements {
        margin-top: 10px;
        padding: 10px;
        background-color: #f7f7f7;
        border-radius: var(--radius-sm);
        font-size: 13px;
    }
    
    .password-requirements p {
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .password-requirements ul {
        padding-left: 20px;
        margin: 0;
    }
    
    .password-requirements li {
        margin-bottom: 3px;
    }
    
    .form-actions {
        margin-top: 30px;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: var(--radius-sm);
    }
    
    .alert-success {
        background-color: #e6f2e6;
        color: var(--primary-color);
        border: 1px solid rgba(4, 62, 3, 0.2);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.password-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
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
});
</script>
@endsection
