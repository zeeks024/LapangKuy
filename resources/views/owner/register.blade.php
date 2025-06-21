@extends('layouts.app')

@section('title', 'Daftar Sebagai Pemilik Lapangan - LapangKuy')

@section('content')
<!-- Field Owner Registration Page -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-lg-5">
                        <div class="row">
                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <div class="pe-lg-4">
                                    <h2 class="mb-4">Daftarkan Lapangan Anda di LapangKuy</h2>
                                    <p class="text-muted mb-4">Bergabunglah dengan platform booking lapangan olahraga terpercaya dan tingkatkan bisnis Anda dengan kemudahan pengelolaan dan peningkatan visibilitas.</p>
                                    
                                    <div class="mb-4">
                                        <h5 class="mb-3">Keuntungan Menjadi Mitra:</h5>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-primary p-2 me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                <i class="fas fa-globe text-white"></i>
                                            </div>
                                            <div>Jangkauan Lebih Luas</div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-success p-2 me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                <i class="fas fa-calendar-check text-white"></i>
                                            </div>
                                            <div>Sistem Booking Otomatis</div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-info p-2 me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                <i class="fas fa-chart-line text-white"></i>
                                            </div>
                                            <div>Laporan dan Analitik</div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-warning p-2 me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                <i class="fas fa-hand-holding-usd text-white"></i>
                                            </div>
                                            <div>Tingkatkan Pendapatan</div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('contact') }}?subject=owner" class="btn btn-outline-primary">
                                            <i class="fas fa-phone me-2"></i>Hubungi Tim Kami
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-7">
                                <div class="bg-white p-4 rounded shadow-sm">
                                    <h4 class="mb-4 text-center">Daftar Sekarang</h4>                                      <form method="POST" action="{{ route('register') }}" id="ownerRegistrationForm">                                        @csrf
                                        {{-- Tetapkan role sebagai "field_owner" (pemilik lapangan) --}}
                                        <input type="hidden" name="role" value="field_owner">
                                        
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Alamat Email</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Alamat</label>
                                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="2" required>{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Password</label>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 form-check">
                                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                Saya menyetujui <a href="{{ route('terms') }}" target="_blank">Syarat & Ketentuan</a> LapangKuy
                                            </label>
                                            @error('terms')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                          <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-user-plus me-2"></i>Daftar Sebagai Pemilik Lapangan
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <script>
                                    document.getElementById('ownerRegistrationForm').addEventListener('submit', function(e) {
                                        // Log submit untuk debugging
                                        console.log('Form submitted');
                                    });
                                    </script>
                                    
                                    <div class="mt-3 text-center">
                                        <small class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}">Login</a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section for Field Owners -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h3>Pertanyaan Umum</h3>
            <p class="text-muted">Informasi penting untuk pemilik lapangan</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="ownerFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Bagaimana cara mendaftarkan lapangan saya?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#ownerFaq">
                            <div class="accordion-body">
                                Setelah mendaftar sebagai pemilik lapangan, Anda dapat login ke dashboard pemilik lapangan dan mengikuti petunjuk untuk menambahkan lapangan baru. Anda perlu mengunggah foto lapangan, menentukan harga, dan mengatur jadwal ketersediaan.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Berapa biaya untuk bergabung sebagai pemilik lapangan?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#ownerFaq">
                            <div class="accordion-body">
                                Pendaftaran sebagai pemilik lapangan di LapangKuy tidak dikenakan biaya. Kami hanya mengenakan komisi kecil untuk setiap booking yang berhasil melalui platform kami. Detail lengkap tersedia dalam perjanjian kemitraan setelah pendaftaran.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Bagaimana sistem pembayaran untuk pemilik lapangan?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#ownerFaq">
                            <div class="accordion-body">
                                LapangKuy mengelola semua pembayaran online dan mentransfer dana ke rekening bank Anda setiap minggunya. Anda dapat melacak semua transaksi dan pendapatan melalui dashboard pemilik lapangan Anda.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Bagaimana saya bisa mengatur jadwal lapangan saya?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#ownerFaq">
                            <div class="accordion-body">
                                Setelah menambahkan lapangan, Anda dapat mengatur jadwal ketersediaan, harga per jam, dan hari libur melalui dashboard pemilik lapangan. Sistem kami akan secara otomatis menunjukkan slot waktu yang tersedia kepada pelanggan berdasarkan pengaturan Anda.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<style>
.icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.rounded-circle {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
