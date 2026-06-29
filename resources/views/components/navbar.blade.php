<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/">
            Magang Portal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item me-2">
                        <a href="{{ route('notification.list') }}" class="nav-link mt-1 d-flex align-items-center">
                            <div class="position-relative">
                                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                                <i
                                    class="bi bi-bell-fill fs-5 {{ $unreadCount > 0 ? 'text-warning' : 'text-primary' }}"></i>
                                @if($unreadCount > 0)
                                    <span class="position-absolute translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.65rem; padding: 0.25em 0.4em; margin-top: 0.1rem; margin-left: 0rem;">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    </li>
                    <li class="nav-item"> | </li>
                    <li class="nav-item ms-2 dropdown">
                        <a class="nav-link dropdown-toggle fw-medium text-primary" href="#" role="button"
                            data-bs-toggle="dropdown">
                            @if(auth()->user()->role->value === 'company')
                                <i class="bi bi-building fs-5 me-1 text-primary"></i>
                                {{ auth()->user()->companyProfile->company_name ?? auth()->user()->name }}
                            @else
                                <i class="bi bi-person-circle fs-5 me-1 text-primary"></i>
                                {{ auth()->user()->name }}
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                            @if(auth()->user()->role->value === 'company')
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('company.dashboard') }}">
                                        Dashboard Perusahaan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('company.profile') }}">
                                        Kelola Profil Perusahaan
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @elseif(auth()->user()->role->value === 'student')
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('student.profile') }}">
                                        Kelola Profil Mahasiswa
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @elseif(auth()->user()->role->value === 'admin')
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                        Panel Admin
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger py-2">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-primary px-4 rounded-pill fw-medium" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>