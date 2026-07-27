<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') — 3ajeeba Admin</title>

    <!-- Cairo Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?t={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-layout.css') }}?t={{ time() }}">

    @stack('styles')
</head>
<body class="admin-body">

@include('layouts.parts.sidebar')

<!-- MAIN -->
<div class="admin-main">
    @include('layouts.parts.header')

    <main class="admin-content">
        @if(session('success'))
            <div class="mc-alert mc-alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mc-alert mc-alert-error">
                <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Close profile dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dd = document.getElementById('profileDropdown');
        if (dd && !dd.contains(e.target)) {
            dd.classList.remove('open');
        }
    });
</script>
@stack('scripts')
</body>
</html>
