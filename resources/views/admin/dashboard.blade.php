@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="muted small flex-grow-1">Total Program Studi</span>
                        <span class="stat-icon" style="background:#e6edfd;color:#2563eb"><i class="bi bi-mortarboard"></i></span>
                    </div>
                    <div class="num">{{ $totalProdi }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="muted small flex-grow-1">Lowongan Aktif</span>
                        <span class="stat-icon" style="background:#e2f3eb;color:#198754"><i class="bi bi-briefcase"></i></span>
                    </div>
                    <div class="num">{{ $activeJobs }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="muted small flex-grow-1">Total Perusahaan</span>
                        <span class="stat-icon" style="background:#fdf3da;color:#ca8a04"><i class="bi bi-building"></i></span>
                    </div>
                    <div class="num">{{ $totalCompanies }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="muted small flex-grow-1">Total Pelamar</span>
                        <span class="stat-icon" style="background:#eee9fc;color:#7c3aed"><i class="bi bi-people"></i></span>
                    </div>
                    <div class="num">{{ $totalApplicants }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="flex-grow-1">Lowongan Terbaru</h2>
            <a href="{{ route('admin.job') }}" class="text-decoration-none fw-semibold small">Lihat semua →</a>
        </div>
        <div class="table-responsive">
            <table class="table tbl">
                <thead>
                    <tr>
                        <th>Judul Lowongan</th>
                        <th>Perusahaan</th>
                        <th>Pelamar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentJobs as $job)
                        <tr>
                            <td class="fw-medium">{{ $job->title }}</td>
                            <td class="muted">{{ $job->companyProfile->company_name ?? '—' }}</td>
                            <td>{{ $job->applications_count }}</td>
                            <td>
                                @if ($job->deadline_date >= now())
                                    <span class="pill pill-open">Open</span>
                                @else
                                    <span class="pill pill-closed">Closed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center muted py-4">Belum ada lowongan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
