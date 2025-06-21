@extends('layouts.app')

@section('title', 'Hubungi Kami - LapangKuy')

@section('content')
<!-- Contact Hero -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="mb-4">Hubungi Kami</h1>
                <p class="lead">Ada pertanyaan atau saran? Jangan ragu untuk menghubungi tim kami. Kami siap membantu Anda!</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info & Form -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Informasi Kontak</h3>
                        
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-geo-alt text-white fs-4"></i>
                                </div>
                            </div>
                            <div>                                <h5>Alamat</h5>
                                <p class="mb-0">
                                    Sekaran, Kec. Gn. Pati<br>
                                    Kota Semarang, Jawa Tengah 50229<br>
                                    Indonesia
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-envelope text-white fs-4"></i>
                                </div>
                            </div>                            <div>
                                <h5>Email</h5>
                                <p class="mb-0">
                                    suport.lapangkuy@gmail.com
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-telephone text-white fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h5>Telepon</h5>
                                <p class="mb-0">
                                    +62 821-3317-3461<br>
                                    +62 822-4264-4228<br>
                                    +62 857-7359-4302<br>
                                    +62 896-9598-5938
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-clock text-white fs-4"></i>
                                </div>
                            </div>
                            <div>                                <h5>Jam Kerja</h5>
                                <p class="mb-0">
                                    Senin - Minggu: 24 Jam<br>
                                    (Buka setiap hari)
                                </p>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <!-- Replace with actual map if available -->
                    <div class="bg-secondary text-center py-5">                        <h4 class="text-white">Lokasi LapangKuy</h4>
                        <p class="text-white mb-0">Sekaran, Kec. Gn. Pati, Kota Semarang, Jawa Tengah 50229</p>
                        <!-- You would typically put an iframe with Google Maps here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
