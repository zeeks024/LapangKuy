<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name', 'LapangKuy') }}" height="45">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('fields.*') ? 'active fw-semibold' : '' }}" href="{{ route('fields.index') }}">Lapangan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active fw-semibold' : '' }}" href="{{ route('about') }}">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active fw-semibold' : '' }}" href="{{ route('contact') }}">Kontak</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('faq') || request()->routeIs('terms') || request()->routeIs('privacy') ? 'active fw-semibold' : '' }}" href="#" id="infoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-info-circle me-1"></i>Info
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0" aria-labelledby="infoDropdown">
                        <li><a class="dropdown-item {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">
                            <i class="bi bi-question-circle-fill me-2"></i>FAQ
                        </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('terms') ? 'active' : '' }}" href="{{ route('terms') }}">
                            <i class="bi bi-file-text-fill me-2"></i>Syarat & Ketentuan
                        </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('privacy') ? 'active' : '' }}" href="{{ route('privacy') }}">
                            <i class="bi bi-shield-lock-fill me-2"></i>Kebijakan Privasi
                        </a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('owner.register') }}"><i class="bi bi-building me-1"></i>Untuk Pemilik Lapangan</a></li>
                    @if(Route::has('login'))<li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>@endif
                    @if(Route::has('register'))<li class="nav-item"><a class="btn btn-success btn-sm fw-semibold" href="{{ route('register') }}"><i class="bi bi-person-plus-fill me-1"></i>Register</a></li>@endif
                @else
                    <li class="nav-item dropdown">                        <a id="userMenu" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset(Auth::user()->profile_image ?? 'assets/images/default-avatar.png') }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('assets/images/default-avatar.png') }}';">
                            {{ Auth::user()->name }}
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-danger rounded-pill ms-2">Admin</span>
                            @elseif(Auth::user()->isFieldOwner())
                                <span class="badge bg-info rounded-pill ms-2">Owner</span>
                            @else
                                <span class="badge bg-secondary rounded-pill ms-2">User</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userMenu">
                            @if(Auth::user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-shaded me-2"></i> Admin Dashboard</a></li>
                                <li><hr class="dropdown-divider my-1"></li>
                            @elseif(Auth::user()->isFieldOwner())
                                <li><a class="dropdown-item" href="{{ route('owner.dashboard') }}"><i class="bi bi-columns-gap me-2"></i> Owner Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.fields') }}"><i class="bi bi-map-fill me-2"></i> Lapangan Saya</a></li>
                                <li><hr class="dropdown-divider my-1"></li>                            @endif                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('bookings.index') }}"><i class="bi bi-calendar-check-fill me-2"></i> Booking Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('reviews.reviewable-bookings') }}"><i class="bi bi-star-fill me-2"></i> Beri Ulasan</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person-circle me-2"></i> Profil Saya</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item text-danger fw-semibold" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    padding: 0.8rem 0;
    border-bottom: 1px solid #e9ecef;
    transition: padding 0.3s ease-in-out, box-shadow 0.3s ease-in-out; /* Added box-shadow transition */
}

.navbar-scrolled {
    padding: 0.5rem 0; /* Reduced padding on scroll */
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.07) !important; /* More pronounced shadow on scroll */
}

.navbar-brand img {
    height: 45px; /* Slightly larger logo */
    transition: transform 0.3s ease-in-out;
}

.navbar-brand:hover img {
    transform: scale(1.08); /* More noticeable hover */
}

.navbar-nav .nav-link {
    font-weight: 500;
    color: #495057; /* Darker text for better contrast */
    padding: 0.6rem 1rem;
    margin: 0 0.15rem;
    border-radius: 0.375rem; /* Bootstrap standard border-radius */
    transition: all 0.25s ease-in-out;
    display: flex;
    align-items: center;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    background-color: rgba(25, 135, 84, 0.07);
    color: #0f5132;
    transform: translateY(-2px); /* Added hover animation */
}

.navbar-nav .nav-link.active {
    font-weight: 600; /* Bolder active link */
}

.navbar-toggler {
    border: none;
}

.navbar-toggler:focus {
    box-shadow: none;
}

.dropdown-menu {
    border-radius: 0.375rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

.dropdown-item {
    padding: 0.5rem 1.2rem;
    font-weight: 500;
    transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    display: flex;
    align-items: center;
}

.dropdown-item i {
    color: #6c757d; /* Icon color */
    transition: color 0.2s ease-in-out;
}

.dropdown-item:hover,
.dropdown-item:focus {
    background-color: rgba(25, 135, 84, 0.07);
    color: #0f5132;
}

.dropdown-item:hover i,
.dropdown-item:focus i {
    color: #0f5132;
}

.dropdown-item.active,
.dropdown-item:active {
    background-color: rgba(25, 135, 84, 0.1);
    color: #0f5132;
    font-weight: 600;
}

.dropdown-item.active i,
.dropdown-item:active i {
    color: #0f5132;
}

.dropdown-divider {
    border-top: 1px solid #e9ecef;
}

.btn-success {
    padding: 0.4rem 0.8rem; /* Slightly smaller padding for sm button */
}

/* Ensure dropdown toggle arrow is vertically centered */
.dropdown-toggle::after {
    margin-left: 0.35em;
    vertical-align: 0.15em;
}

/* User avatar in navbar */
#userMenu img {
    border: 2px solid #dee2e6;
}

</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            window.addEventListener('scroll', function () {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
        }
    });
</script>
@endpush
