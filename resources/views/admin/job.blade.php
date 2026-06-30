@extends('layouts.dashboard')

@section('title', 'Daftar Lowongan')

@section('content')
    <div class="card">
        <div class="card-head gap-2 flex-wrap">
            <h2 class="flex-grow-1">Semua Lowongan</h2>
            <form action="{{ route('admin.job') }}" method="GET" class="input-group input-group-sm" style="max-width:300px">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari judul / perusahaan...">
                @if ($search)
                    <a href="{{ route('admin.job') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table tbl align-middle">
                <thead>
                    <tr>
                        <th>Judul Lowongan</th>
                        <th>Perusahaan</th>
                        <th>Program Studi</th>
                        <th style="width:130px">Deadline</th>
                        <th style="width:90px">Pelamar</th>
                        <th style="width:100px">Status</th>
                        <th style="width:130px" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jobs as $job)
                        <tr>
                            <td class="fw-medium">{{ $job->title }}</td>
                            <td class="muted">{{ $job->companyProfile->company_name ?? '—' }}</td>
                            <td class="muted">{{ $job->studyPrograms->pluck('name')->join(', ') ?: '—' }}</td>
                            <td class="muted">{{ $job->deadline_date->format('d M Y') }}</td>
                            <td>{{ $job->applications_count }}</td>
                            <td>
                                @if ($job->deadline_date >= now())
                                    <span class="pill pill-open">Open</span>
                                @else
                                    <span class="pill pill-closed">Closed</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#takedown{{ $job->id }}">
                                    <i class="bi bi-trash"></i> Take Down
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center muted py-4">
                                {{ $search ? 'Tidak ada lowongan yang cocok dengan pencarian.' : 'Belum ada lowongan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jobs->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>

    <!-- ===== Modal Take Down per baris ===== -->
    @foreach ($jobs as $job)
        <div class="modal fade" id="takedown{{ $job->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin.job.destroy', $job) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Take Down Lowongan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Hapus lowongan <strong>{{ $job->title }}</strong>
                        dari <strong>{{ $job->companyProfile->company_name ?? '—' }}</strong>?
                        <div class="alert alert-danger small mt-2 mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Tindakan permanen. Semua data lamaran terkait ({{ $job->applications_count }} pelamar) ikut terhapus.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Take Down</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
