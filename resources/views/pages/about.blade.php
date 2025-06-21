@extends('layouts.app')

@section('title', 'Tentang Kami - LapangKuy')

@section('content')
<!-- About Hero -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="mb-4">Tentang LapangKuy</h1>
                <p class="lead mb-4">Platform booking lapangan olahraga online terpercaya di Indonesia.</p>
                <p>LapangKuy adalah platform online yang memudahkan Anda mencari dan memesan lapangan olahraga seperti futsal, basket, badminton, tennis, dan lainnya di berbagai lokasi di Indonesia. Kami hadir untuk menghubungkan pengelola lapangan dengan masyarakat yang ingin berolahraga dengan cara yang mudah dan efisien.</p>
            </div>            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets/images/logo.png') }}" alt="LapangKuy Logo" class="img-fluid" style="max-width: 300px;">
            </div>
        </div>
    </div>
</section>

<!-- Our Vision -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="display-4 text-primary mb-3">
                            <i class="bi bi-eyeglasses"></i>
                        </div>
                        <h3>Visi Kami</h3>
                        <p>Menjadi platform booking lapangan olahraga terdepan di Indonesia yang dapat meningkatkan gaya hidup sehat dan aktif masyarakat melalui kemudahan akses terhadap fasilitas olahraga.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="display-4 text-primary mb-3">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h3>Misi Kami</h3>
                        <ul class="ps-3">
                            <li class="mb-2">Menyediakan platform yang user-friendly untuk booking lapangan olahraga</li>
                            <li class="mb-2">Menghubungkan pengelola lapangan dengan calon pengguna</li>
                            <li class="mb-2">Mendorong gaya hidup aktif dan sehat melalui olahraga</li>
                            <li>Memberikan pengalaman booking yang transparan dan terpercaya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Tim Kami</h2>
            <p class="lead">Kenali orang-orang di balik LapangKuy</p>
        </div>          <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">                        <img src="{{ file_exists(public_path('assets/images/team/arzaki.jpeg')) ? asset('assets/images/team/arzaki.jpeg') : asset('assets/images/user-avatar.svg') }}" 
                             alt="Arzaki Zunior Putra" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h4>Arzaki Zunior Putra</h4>
                        <p class="text-muted">Project Lead & Developer</p>
                        <p>Memimpin pengembangan platform LapangKuy dengan fokus pada pengalaman pengguna yang optimal.</p>
                        <div class="social-links mt-3 d-flex justify-content-center">
                            <a href="#" class="social-link me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link me-2"><i class="bi bi-github"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">                        <img src="{{ file_exists(public_path('assets/images/team/furqon.jpeg')) ? asset('assets/images/team/furqon.jpeg') : asset('assets/images/user-avatar.svg') }}" 
                             alt="Muhammad Nur Furqon" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h4>Muhammad Nur Furqon</h4>
                        <p class="text-muted">Backend Developer</p>
                        <p>Ahli dalam pengembangan sistem backend dan database yang memastikan performa aplikasi yang handal.</p>
                        <div class="social-links mt-3 d-flex justify-content-center">
                            <a href="#" class="social-link me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link me-2"><i class="bi bi-github"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">                        <img src="{{ file_exists(public_path('assets/images/team/tyto.jpeg')) ? asset('assets/images/team/tyto.jpeg') : asset('assets/images/user-avatar.svg') }}" 
                             alt="Tyto Rinandi" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h4>Tyto Rinandi</h4>
                        <p class="text-muted">Frontend Developer</p>
                        <p>Berfokus pada antarmuka pengguna yang menarik dan responsif untuk semua perangkat.</p>
                        <div class="social-links mt-3 d-flex justify-content-center">
                            <a href="#" class="social-link me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link me-2"><i class="bi bi-github"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
              <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">                        <img src="{{ file_exists(public_path('assets/images/team/azzam.jpeg')) ? asset('assets/images/team/azzam.jpeg') : asset('assets/images/user-avatar.svg') }}" 
                             alt="Muhammad Azzam Fadhlullah" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <h4>Muhammad Azzam Fadhlullah</h4>
                        <p class="text-muted">Full Stack Developer</p>
                        <p>Menguasai pengembangan frontend dan backend untuk menciptakan solusi terintegrasi yang sempurna.</p>
                        <div class="social-links mt-3 d-flex justify-content-center">
                            <a href="#" class="social-link me-2"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link me-2"><i class="bi bi-github"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-3">Bergabung Bersama LapangKuy</h2>
                <p class="lead mb-lg-0">Mulai cari lapangan olahraga favoritmu sekarang dan nikmati kemudahan booking online!</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('fields.index') }}" class="btn btn-light btn-lg">Cari Lapangan Sekarang</a>
            </div>
        </div>
    </div>
</section>
@endsection
