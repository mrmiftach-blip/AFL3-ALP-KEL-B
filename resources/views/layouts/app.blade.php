<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Magang Portal')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <x-navbar />

    <main class="container mt-4 mb-5 flex-grow-1">
        @yield('content')
    </main>

    <footer class="border-top bg-white mt-auto">
        <div class="container py-3 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-1">
            <span class="text-secondary small">© {{ date('Y') }} Magang Portal — Platform lowongan magang mahasiswa.</span>
            <span class="text-secondary small">
                <i class="bi bi-mortarboard"></i> Web Development Project
            </span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
