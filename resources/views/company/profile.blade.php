@extends('layouts.dashboard')
@section('title', 'Profil Perusahaan')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="mb-0 fw-bold">Kelola Profil Perusahaan</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <form action="{{ route('company.profile') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Nama Perusahaan <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="company_name"
                                    class="form-control form-control-lg @error('company_name') is-invalid @enderror"
                                    value="{{ old('company_name', $profile->company_name ?? '') }}" required
                                    placeholder="Masukkan nama perusahaan">
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Deskripsi Perusahaan</label>
                                <textarea name="description" rows="5"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Ceritakan tentang perusahaan Anda...">{{ old('description', $profile->description ?? '') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Alamat Lengkap</label>
                                <textarea name="address" rows="3"
                                    class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Alamat kantor / operasional...">{{ old('address', $profile->address ?? '') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-medium">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection