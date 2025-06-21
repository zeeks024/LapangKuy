<footer class="bg-dark text-light pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="mb-3">LapangKuy</h5>
                <p class="mb-3">Platform booking lapangan olahraga terpercaya di Indonesia.</p>
                <div class="social-links">
                    <h6 class="mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light social-icon" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-light social-icon" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-light social-icon" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h6 class="mb-3">Perusahaan</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-light text-decoration-none">Tentang Kami</a></li>
                    <li class="mb-2"><a href="{{ route('terms') }}" class="text-light text-decoration-none">Syarat & Ketentuan</a></li>
                    <li class="mb-2"><a href="{{ route('privacy') }}" class="text-light text-decoration-none">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h6 class="mb-3">Bantuan</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="{{ route('faq') }}" class="text-light text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-light text-decoration-none">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <h6 class="mb-3">Hubungi Kami</h6>
                <div class="contact-info">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <span>info@lapangkuy.site</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone me-2"></i>
                        <span>+62 812-3456-7890</span>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="fas fa-map-marker-alt me-2 mt-1"></i>
                        <span>Jakarta, Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="row align-items-center py-3">
            <div class="col-md-6 text-center text-md-start">
                <small>&copy; {{ date('Y') }} LapangKuy. All rights reserved.</small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="text-muted">Made with <i class="fas fa-heart text-danger"></i> in Indonesia</small>
            </div>
        </div>
    </div>
</footer>
