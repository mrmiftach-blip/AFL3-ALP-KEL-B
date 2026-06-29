<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — Magang Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
            --bg: #f8f9fa;
            --tint: #eff4ff;
        }

        body { background: var(--bg); color: var(--ink); }

        .admin-wrap { display: flex; min-height: 100vh; }

        /* Sidebar */
        .admin-sidebar {
            background: #fff;
            border-right: 1px solid var(--line);
        }
        @media (min-width: 992px) {
            .admin-sidebar {
                width: 264px;
                flex-shrink: 0;
                position: sticky;
                top: 0;
                height: 100vh;
            }
        }
        .admin-sidebar .offcanvas-body,
        .admin-sidebar .sidebar-inner {
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            height: 100%;
        }
        .brand .name { font-weight: 700; font-size: 1.25rem; color: var(--bs-primary); line-height: 1.1; }
        .brand .sub { font-size: .7rem; font-weight: 600; letter-spacing: 1px; color: var(--muted); }
        .nav-admin .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 12px; border-radius: 8px; color: var(--ink);
            font-weight: 500; font-size: .9rem; margin-bottom: 4px;
        }
        .nav-admin .nav-link i { font-size: 1.1rem; color: var(--muted); }
        .nav-admin .nav-link:hover { background: #f3f4f6; }
        .nav-admin .nav-link.active { background: var(--tint); color: var(--bs-primary); font-weight: 600; }
        .nav-admin .nav-link.active i { color: var(--bs-primary); }
        .nav-admin .nav-link.logout { color: var(--bs-danger); }
        .nav-admin .nav-link.logout i { color: var(--bs-danger); }

        /* Main */
        .admin-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .admin-topbar {
            background: #fff; border-bottom: 1px solid var(--line);
            padding: 14px 24px; display: flex; align-items: center; gap: 12px;
            position: sticky; top: 0; z-index: 5;
        }
        .admin-topbar h1 { font-size: 1.3rem; font-weight: 700; margin: 0; }
        .admin-topbar .user { margin-left: auto; display: flex; align-items: center; gap: 12px; }
        .bell { position: relative; width: 40px; height: 40px; border-radius: 50%; background: #f1f3f5;
            display: grid; place-items: center; color: var(--muted); font-size: 1.05rem; }
        .bell .dot { position: absolute; top: 6px; right: 7px; width: 9px; height: 9px;
            background: var(--bs-danger); border: 2px solid #fff; border-radius: 50%; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--bs-primary);
            color: #fff; display: grid; place-items: center; font-weight: 600; font-size: .85rem; flex-shrink: 0; }
        .admin-content { padding: 24px; flex: 1; }
        .admin-footer { background: #fff; border-top: 1px solid var(--line); padding: 16px 24px;
            display: flex; color: var(--muted); font-size: .8rem; }

        /* Shared pieces */
        .card { border-color: var(--line); }
        .card-head { display: flex; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--line); }
        .card-head h2 { font-size: 1rem; font-weight: 600; margin: 0; }
        table.tbl { margin: 0; }
        table.tbl thead th { background: var(--bg); color: var(--muted); font-size: .78rem;
            font-weight: 600; border-bottom: 1px solid var(--line); padding: 12px 20px; white-space: nowrap; }
        table.tbl tbody td { padding: 14px 20px; vertical-align: middle; }
        .pill { font-size: .72rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; white-space: nowrap; }
        .pill-open { background: #e2f3eb; color: #198754; }
        .pill-closed { background: #edeef0; color: var(--muted); }
        .stat-card .num { font-size: 1.9rem; font-weight: 700; }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; display: grid; place-items: center; font-size: 1.1rem; flex-shrink: 0; }
        .muted { color: var(--muted); }
    </style>
    @stack('styles')
</head>

<body>
    @php
        $navItems = [
            ['route' => 'admin.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard'],
            ['route' => 'admin.study-program', 'icon' => 'bi-mortarboard', 'label' => 'Program Studi'],
            ['route' => 'admin.job', 'icon' => 'bi-briefcase', 'label' => 'Daftar Lowongan'],
        ];
    @endphp

    <div class="admin-wrap">
        <!-- ===== SIDEBAR (offcanvas di mobile, statis di desktop) ===== -->
        <div class="offcanvas-lg offcanvas-start admin-sidebar" tabindex="-1" id="adminSidebar">
            <div class="offcanvas-header d-lg-none border-bottom">
                <div class="brand">
                    <div class="name">Magang Portal</div>
                    <div class="sub">PANEL ADMIN</div>
                </div>
                <button class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar"></button>
            </div>
            <div class="offcanvas-body sidebar-inner">
                <div class="brand d-none d-lg-block mb-2">
                    <div class="name">Magang Portal</div>
                    <div class="sub">PANEL ADMIN</div>
                </div>
                <nav class="nav-admin flex-column flex-grow-1 mt-2">
                    @foreach ($navItems as $item)
                        <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                            href="{{ route($item['route']) }}">
                            <i class="bi {{ $item['icon'] }}"></i> {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link logout border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- ===== MAIN ===== -->
        <div class="admin-main">
            <header class="admin-topbar">
                <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#adminSidebar" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>
                <h1>@yield('title', 'Panel Admin')</h1>
                <div class="user">
                    <span class="bell"><i class="bi bi-bell-fill"></i>
                        @if (auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="dot"></span>
                        @endif
                    </span>
                    <span class="fw-semibold d-none d-sm-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                </div>
            </header>

            <main class="admin-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="admin-footer">
                <span>© {{ date('Y') }} Magang Portal — Panel Admin</span>
                <span class="ms-auto">v1.0</span>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
