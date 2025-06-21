@extends('layouts.app')
@section('title', 'FAQ – LapangKuy')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Frequently Asked Questions</h1>
                <p class="lead mb-0">Temukan jawaban untuk pertanyaan yang sering diajukan tentang LapangKuy</p>
            </div>
            <div class="col-lg-4 text-end">
                <i class="fas fa-question-circle fa-6x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Quick Search -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="faqSearch" placeholder="Cari pertanyaan yang Anda butuhkan...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Categories -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Kategori Pertanyaan</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                            <h5>Booking & Reservasi</h5>
                            <p class="text-muted">Cara booking, pembatalan, dan perubahan jadwal</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-credit-card fa-3x text-success mb-3"></i>
                            <h5>Pembayaran</h5>
                            <p class="text-muted">Metode pembayaran, refund, dan invoice</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-user fa-3x text-info mb-3"></i>
                            <h5>Akun & Profil</h5>
                            <p class="text-muted">Pendaftaran, login, dan pengaturan akun</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-futbol fa-3x text-warning mb-3"></i>
                            <h5>Lapangan</h5>
                            <p class="text-muted">Informasi lapangan, fasilitas, dan lokasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Accordion -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Pertanyaan & Jawaban</h2>
            <div class="accordion" id="faqAccordion">
                
                <!-- Booking & Reservasi -->
                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="booking">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            <i class="fas fa-calendar-alt text-primary me-3"></i>
                            Bagaimana cara booking lapangan di LapangKuy?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p>Untuk melakukan booking lapangan di LapangKuy, ikuti langkah-langkah berikut:</p>
                                    <ol>
                                        <li><strong>Cari Lapangan:</strong> Gunakan fitur pencarian untuk menemukan lapangan sesuai lokasi dan jenis olahraga</li>
                                        <li><strong>Pilih Jadwal:</strong> Tentukan tanggal dan jam yang diinginkan</li>
                                        <li><strong>Isi Detail:</strong> Lengkapi informasi kontak dan catatan khusus</li>
                                        <li><strong>Pembayaran:</strong> Pilih metode pembayaran dan selesaikan transaksi</li>
                                        <li><strong>Konfirmasi:</strong> Terima email konfirmasi dan kode booking</li>
                                    </ol>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-light p-3 rounded">
                                        <h6><i class="fas fa-lightbulb text-warning"></i> Tips:</h6>
                                        <small>Booking lebih awal untuk mendapatkan slot waktu yang diinginkan!</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="booking">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                            <i class="fas fa-times-circle text-danger me-3"></i>
                            Apakah bisa membatalkan booking?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Ya, pembatalan booking dapat dilakukan dengan ketentuan sebagai berikut:</p>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Waktu Pembatalan</th>
                                            <th>Biaya Pembatalan</th>
                                            <th>Refund</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>≥ 24 jam sebelum booking</td>
                                            <td>Gratis</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>12-24 jam sebelum booking</td>
                                            <td>10% dari total</td>
                                            <td>90%</td>
                                        </tr>
                                        <tr>
                                            <td>< 12 jam sebelum booking</td>
                                            <td>50% dari total</td>
                                            <td>50%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="booking">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            <i class="fas fa-edit text-info me-3"></i>
                            Bisakah mengubah jadwal booking?
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Perubahan jadwal booking dapat dilakukan dengan syarat:</p>
                            <ul>
                                <li>Minimal 6 jam sebelum jadwal semula</li>
                                <li>Slot baru tersedia di tanggal/jam yang diinginkan</li>
                                <li>Tidak ada perbedaan harga (jika ada selisih harga, akan ditagih/dikembalikan)</li>
                                <li>Maksimal 2 kali perubahan per booking</li>
                            </ul>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Catatan:</strong> Perubahan jadwal gratis untuk member premium!
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="payment">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            <i class="fas fa-credit-card text-success me-3"></i>
                            Metode pembayaran apa saja yang tersedia?
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>LapangKuy menyediakan berbagai metode pembayaran untuk kemudahan Anda:</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-credit-card fa-2x text-success mb-2"></i>
                                            <h6>Kartu Kredit/Debit</h6>
                                            <small>Visa, Mastercard, JCB</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-mobile-alt fa-2x text-primary mb-2"></i>
                                            <h6>E-Wallet</h6>
                                            <small>OVO, GoPay, DANA, ShopeePay</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <i class="fas fa-university fa-2x text-warning mb-2"></i>
                                            <h6>Transfer Bank</h6>
                                            <small>BCA, Mandiri, BRI, BNI</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="payment">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                            <i class="fas fa-clock text-warning me-3"></i>
                            Berapa lama waktu untuk konfirmasi pembayaran?
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Waktu konfirmasi pembayaran bervariasi tergantung metode yang dipilih:</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-credit-card text-success"></i> Kartu Kredit/Debit</td>
                                            <td><span class="badge bg-success">Instan</span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-mobile-alt text-primary"></i> E-Wallet</td>
                                            <td><span class="badge bg-primary">Instan</span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-university text-warning"></i> Transfer Bank</td>
                                            <td><span class="badge bg-warning">1-3 jam</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Akun & Profil -->
                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="account">
                    <h2 class="accordion-header" id="faq6">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                            <i class="fas fa-user-plus text-info me-3"></i>
                            Bagaimana cara mendaftar akun baru?
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Pendaftaran akun di LapangKuy sangat mudah dan gratis:</p>
                            <ol>
                                <li>Klik tombol "Daftar" di pojok kanan atas</li>
                                <li>Isi form pendaftaran dengan data yang valid</li>
                                <li>Verifikasi email yang dikirim ke inbox Anda</li>
                                <li>Login dengan akun baru dan lengkapi profil</li>
                            </ol>
                            <div class="alert alert-success">
                                <i class="fas fa-gift"></i> 
                                <strong>Bonus:</strong> Dapatkan diskon 10% untuk booking pertama!
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="account">
                    <h2 class="accordion-header" id="faq7">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                            <i class="fas fa-key text-danger me-3"></i>
                            Lupa password, bagaimana cara reset?
                        </button>
                    </h2>
                    <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="faq7" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Jika Anda lupa password, ikuti langkah berikut:</p>
                            <ol>
                                <li>Klik "Lupa Password" di halaman login</li>
                                <li>Masukkan email yang terdaftar</li>
                                <li>Cek email dan klik link reset password</li>
                                <li>Buat password baru yang aman</li>
                                <li>Login dengan password baru</li>
                            </ol>
                            <div class="bg-light p-3 rounded mt-3">
                                <h6><i class="fas fa-shield-alt text-primary"></i> Tips Keamanan:</h6>
                                <ul class="mb-0">
                                    <li>Gunakan kombinasi huruf besar, kecil, angka, dan simbol</li>
                                    <li>Minimal 8 karakter</li>
                                    <li>Jangan gunakan password yang sama dengan akun lain</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lapangan -->
                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="field">
                    <h2 class="accordion-header" id="faq8">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                            <i class="fas fa-futbol text-warning me-3"></i>
                            Jenis olahraga apa saja yang tersedia?
                        </button>
                    </h2>
                    <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="faq8" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>LapangKuy menyediakan berbagai jenis lapangan olahraga:</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-futbol text-success"></i> Futsal</li>
                                        <li><i class="fas fa-basketball-ball text-warning"></i> Basket</li>
                                        <li><i class="fas fa-volleyball-ball text-primary"></i> Voli</li>
                                        <li><i class="fas fa-table-tennis text-info"></i> Tenis Meja</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-running text-danger"></i> Badminton</li>
                                        <li><i class="fas fa-swimmer text-cyan"></i> Renang</li>
                                        <li><i class="fas fa-dumbbell text-dark"></i> Fitness</li>
                                        <li><i class="fas fa-plus text-secondary"></i> Dan lainnya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="field">
                    <h2 class="accordion-header" id="faq9">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
                            <i class="fas fa-star text-warning me-3"></i>
                            Bagaimana sistem rating dan review?
                        </button>
                    </h2>
                    <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="faq9" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Sistem rating dan review membantu user lain dalam memilih lapangan:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Cara Memberikan Review:</h6>
                                    <ol>
                                        <li>Selesaikan booking Anda</li>
                                        <li>Buka halaman "Riwayat Booking"</li>
                                        <li>Klik "Berikan Review"</li>
                                        <li>Beri rating 1-5 bintang</li>
                                        <li>Tulis ulasan (opsional)</li>
                                    </ol>
                                </div>
                                <div class="col-md-6">
                                    <h6>Kriteria Penilaian:</h6>
                                    <ul>
                                        <li>⭐ Sangat Buruk (1)</li>
                                        <li>⭐⭐ Buruk (2)</li>
                                        <li>⭐⭐⭐ Cukup (3)</li>
                                        <li>⭐⭐⭐⭐ Baik (4)</li>
                                        <li>⭐⭐⭐⭐⭐ Sangat Baik (5)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support & Kontak -->
                <div class="accordion-item mb-3 shadow-sm border-0 rounded-3" data-category="support">
                    <h2 class="accordion-header" id="faq10">
                        <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
                            <i class="fas fa-headset text-primary me-3"></i>
                            Bagaimana cara menghubungi customer service?
                        </button>
                    </h2>
                    <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="faq10" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <p>Tim customer service LapangKuy siap membantu Anda 24/7:</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded">
                                        <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                        <h6>Email</h6>
                                        <small>support@lapangkuy.com</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded">
                                        <i class="fas fa-phone fa-2x text-success mb-2"></i>
                                        <h6>Telepon</h6>
                                        <small>0800-1234-5678</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 border rounded">
                                        <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                                        <h6>WhatsApp</h6>
                                        <small>+62 812-3456-7890</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-primary text-white text-center">
                <div class="card-body py-5">
                    <h3>Masih ada pertanyaan?</h3>
                    <p class="lead mb-4">Tim support kami siap membantu Anda kapan saja!</p>
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-envelope me-2"></i>Hubungi Kami
                    </a>
                    <a href="https://wa.me/6281234567890" class="btn btn-success btn-lg" target="_blank">
                        <i class="fab fa-whatsapp me-2"></i>Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.accordion-button:not(.collapsed) {
    background-color: rgba(13, 110, 253, 0.1);
    border-color: rgba(13, 110, 253, 0.25);
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

#faqSearch {
    font-size: 1.1rem;
    padding: 0.75rem;
}

.table td {
    vertical-align: middle;
}

.bg-cyan {
    background-color: #17a2b8 !important;
}

.text-cyan {
    color: #17a2b8 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Search functionality
    const searchInput = document.getElementById('faqSearch');
    const accordionItems = document.querySelectorAll('.accordion-item');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        accordionItems.forEach(item => {
            const question = item.querySelector('.accordion-button').textContent.toLowerCase();
            const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = searchTerm === '' ? 'block' : 'none';
            }
        });
    });

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
