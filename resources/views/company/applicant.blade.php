@extends('layouts.dashboard')
@section('title', 'Daftar Pelamar')
@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">

            <div>
                <div class="d-flex align-items-center">
                    <a href="{{ route('company.dashboard') }}" class="text-dark text-decoration-none me-3 hover-opacity">
                        <i class="bi bi-arrow-left" style="font-size: 1.8rem; cursor: pointer;"></i>
                    </a>
                    <h2 class="mb-1 fw-bold">Daftar Pelamar</h2>
                </div>
                <p class="text-secondary mb-0">Lowongan: <span
                        class="text-primary fw-medium">{{ $jobPosting->title }}</span></p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3 text-secondary">No</th>
                                <th class="py-3 text-secondary">Nama Pelamar</th>
                                <th class="py-3 text-secondary">NIM</th>
                                <th class="py-3 text-secondary">Tanggal Melamar</th>
                                <th class="py-3 text-secondary text-center">CV</th>
                                <th class="py-3 text-secondary">Status Saat Ini</th>
                                <th class="px-4 py-3 text-end text-secondary">Aksi Status</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($applications as $index => $app)
                                <tr>
                                    <td class="px-4">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $app->studentProfile->user->name ?? 'Unknown' }}</td>
                                    <td>{{ $app->studentProfile->nim }}</td>
                                    <td>{{ $app->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        @if($app->studentProfile->cv_path)
                                            <a href="{{ route('student.download-cv', $app->studentProfile->id) }}" target="_blank"
                                                class="btn btn-sm btn-secondary fw-medium rounded-3 px-3">Lihat PDF</a>
                                        @else
                                            <span class="text-muted small">Tidak ada CV</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($app->status->value === 'Submitted')
                                            <span class="badge bg-secondary rounded-pill fw-normal">Submitted</span>
                                        @elseif($app->status->value === 'Reviewed')
                                            <span class="badge bg-info text-dark rounded-pill fw-normal">Reviewed</span>
                                        @elseif($app->status->value === 'Accepted')
                                            <span class="badge bg-success rounded-pill fw-normal">Accepted</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill fw-normal">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-end">
                                        <form action="{{ route('company.applicant', $jobPosting->id) }}" method="POST"
                                            class="d-flex justify-content-end gap-2 mb-0">
                                            @csrf
                                            <input type="hidden" name="application_id" value="{{ $app->id }}">

                                            <button type="submit" name="status" value="Reviewed"
                                                class="btn btn-sm btn-warning text-dark fw-medium rounded-3 px-3">Review</button>
                                            <button type="submit" name="status" value="Accepted"
                                                class="btn btn-sm btn-success fw-medium rounded-3 px-3">Accept</button>
                                            <button type="submit" name="status" value="Rejected"
                                                class="btn btn-sm btn-danger fw-medium rounded-3 px-3">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="fs-4 mb-2">📄</div>
                                        Belum ada pelamar untuk lowongan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
