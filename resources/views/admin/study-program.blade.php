@extends('layouts.admin')

@section('title', 'Program Studi')

@section('content')
    <div class="card">
        <div class="card-head">
            <h2 class="flex-grow-1">Daftar Program Studi</h2>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProdiModal">
                <i class="bi bi-plus-lg"></i> Tambah Program Studi
            </button>
        </div>

        <div class="table-responsive">
            <table class="table tbl align-middle">
                <thead>
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Nama Program Studi</th>
                        <th style="width:140px">Mahasiswa</th>
                        <th style="width:140px" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($studyPrograms as $prodi)
                        <tr>
                            <td class="muted">{{ $prodi->id }}</td>
                            <td class="fw-medium">{{ $prodi->name }}</td>
                            <td class="muted">{{ $prodi->student_profiles_count }}</td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#editProdi{{ $prodi->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#delProdi{{ $prodi->id }}" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center muted py-4">Belum ada program studi. Klik "Tambah Program Studi".</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($studyPrograms->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $studyPrograms->links() }}
            </div>
        @endif
    </div>

    <!-- ===== Modal Tambah ===== -->
    <div class="modal fade" id="addProdiModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('admin.study-program.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_form" value="add">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Program Studi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nama Program Studi</label>
                    <input type="text" name="name"
                        class="form-control @if ($errors->any() && old('_form') === 'add') @error('name') is-invalid @enderror @endif"
                        value="{{ old('_form') === 'add' ? old('name') : '' }}" placeholder="contoh: Teknik Informatika">
                    @if ($errors->any() && old('_form') === 'add')
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== Modal Edit & Hapus per baris ===== -->
    @foreach ($studyPrograms as $prodi)
        <div class="modal fade" id="editProdi{{ $prodi->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin.study-program.update', $prodi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form" value="edit">
                    <input type="hidden" name="_id" value="{{ $prodi->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Program Studi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama Program Studi</label>
                        <input type="text" name="name"
                            class="form-control @if ($errors->any() && old('_id') == $prodi->id) @error('name') is-invalid @enderror @endif"
                            value="{{ old('_id') == $prodi->id ? old('name') : $prodi->name }}">
                        @if ($errors->any() && old('_id') == $prodi->id)
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="delProdi{{ $prodi->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" action="{{ route('admin.study-program.destroy', $prodi) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Program Studi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus <strong>{{ $prodi->name }}</strong>?
                        @if ($prodi->student_profiles_count > 0)
                            <div class="alert alert-warning small mt-2 mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                Program studi ini masih dipakai {{ $prodi->student_profiles_count }} mahasiswa, jadi tidak dapat dihapus.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" @disabled($prodi->student_profiles_count > 0)>Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = @json(old('_form'));
                const id = @json(old('_id'));
                let el = null;
                if (form === 'edit' && id) {
                    el = document.getElementById('editProdi' + id);
                } else {
                    el = document.getElementById('addProdiModal');
                }
                if (el) { new bootstrap.Modal(el).show(); }
            });
        </script>
    @endif
@endpush
