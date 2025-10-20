@extends('layouts.superadmin')

@section('title', 'Daftar Mata Pelajaran')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="ti ti-book me-2"></i> Daftar Mata Pelajaran
                        </h3>
                    </div>

                    {{-- Notifikasi sukses --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <div class="d-flex">
                                <i class="ti ti-circle-check me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    <div class="card-body border-top">
                        {{-- Form Cari + Tambah --}}
                        <div class="row g-2 align-items-center mb-4">
                            {{-- Fitur Cari --}}
                            <div class="col-md-6">
                                <form action="{{ route('superadmin.mata_pelajaran.index') }}" method="GET" class="d-flex">
                                    <div class="input-icon w-100">
                                        <input type="text" name="search" class="form-control" placeholder="Cari mata pelajaran..." value="{{ request('search') }}">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-search"></i>
                                        </span>
                                    </div>
                                    @if(request('search'))
                                        <a href="{{ route('superadmin.mata_pelajaran.index') }}" class="btn btn-link ms-2">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>

                            {{-- Form Tambah --}}
                            <div class="col-md-6">
                                <form action="{{ route('superadmin.mata_pelajaran.store') }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="nama_mata_pelajaran"
                                            class="form-control @error('nama_mata_pelajaran') is-invalid @enderror"
                                            placeholder="Tambah mata pelajaran baru...">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-plus"></i> Tambah
                                        </button>
                                        @error('nama_mata_pelajaran')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tabel --}}
                        <div class="table-responsive">
                            <table class="table table-vcenter table-striped card-table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama Mata Pelajaran</th>
                                        <th class="text-center" style="width: 20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_mata_pelajaran }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $item->id }}">
                                                        <i class="ti ti-edit"></i> Edit
                                                    </button>

                                                    <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $item->id }}">
                                                        <i class="ti ti-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- Modal Edit --}}
                                        <div class="modal modal-blur fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <form action="{{ route('superadmin.mata_pelajaran.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Mata Pelajaran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Mata Pelajaran</label>
                                                                <input type="text" name="nama_mata_pelajaran" value="{{ $item->nama_mata_pelajaran }}" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="ti ti-device-floppy"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Modal Hapus --}}
                                        <div class="modal modal-blur fade" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <form action="{{ route('superadmin.mata_pelajaran.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-content">
                                                        <div class="modal-status bg-danger"></div>
                                                        <div class="modal-body text-center py-4">
                                                            <i class="ti ti-alert-triangle icon-lg text-danger mb-2"></i>
                                                            <h3>Konfirmasi Hapus</h3>
                                                            <div class="text-secondary">
                                                                Hapus <strong>{{ $item->nama_mata_pelajaran }}</strong> dari daftar?
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="w-100 d-flex gap-2">
                                                                <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger w-50">Hapus</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="empty">
                                                    <div class="empty-icon">
                                                        <i class="ti ti-school fs-1 text-muted"></i>
                                                    </div>
                                                    <p class="empty-title mt-3">Belum ada data</p>
                                                    <p class="empty-subtitle text-secondary">Tambahkan mata pelajaran baru menggunakan form di atas.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $data->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
