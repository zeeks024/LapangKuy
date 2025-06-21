<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'LapangKuy'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lapangkuy.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/footer.css') }}" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    
    <!-- Stack for additional styles -->
    @stack('styles')
    
    <style>
        /* Ensure critical styles load immediately */
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        .btn-primary {
            background-color: #043E03;
            border-color: #043E03;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: #0A5C08;
            border-color: #0A5C08;
        }
        
        footer {
            background-color: #343A40;
            color: #F8F9FA;
            padding-top: 4rem;
        }
        
        /* Ensure proper UI for admin pages */
        .admin-container {
            margin-bottom: 0;
        }
        
        .admin-container + footer {
            display: none;
        }
    </style>
</head>
<body>
    <div id="app">
        {{-- Include shared navbar --}}
        @include('partials.navbar')

        <!-- Main Content -->
        <main>
            @if(session('success'))
                <div class="container mt-3">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="container mt-3">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>

        {{-- Include shared footer --}}
        @include('partials.footer')
    </div>
    
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/file-upload.js') }}" defer></script>
    
    <!-- Stack for additional scripts -->
    @stack('scripts')
</body>
</html>
