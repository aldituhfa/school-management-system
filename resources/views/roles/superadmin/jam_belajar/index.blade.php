@extends('layouts.superadmin')

@section('title', '')

@section('content')
<div class="page-body">
    <div class="container-xl">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="page-title">Jam Belajar Per Kelas</h2>
            <a href="{{ route('superadmin.jam-belajar.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Tambah Jam Belajar
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Daftar Jam Belajar</h3>
            </div>

            <div class="card-body border-top">

                {{-- ALERT SUCCESS --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-check me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <a href="#" class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- SEARCH & FILTER --}}
                <div class="card mb-3 border-0 shadow-sm bg-light p-3 rounded-3">
                    <form action="{{ route('superadmin.jam-belajar.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label text-muted mb-1">
                                    <i class="ti ti-search"></i> Cari Nama Kelas
                                </label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="form-control shadow-sm" placeholder="Masukkan nama kelas...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted mb-1">
                                    <i class="ti ti-filter"></i> Filter Kelas
                                </label>
                                <select name="filter_kelas" class="form-select shadow-sm">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" {{ request('filter_kelas') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="ti ti-search"></i> Cari
                                </button>
                                <a href="{{ route('superadmin.jam-belajar.index') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="ti ti-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Total Jam</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Istirahat 1</th>
                                <th>Istirahat 2</th> {{-- ➕ DITAMBAHKAN --}}
                                <th class="text-center w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $item)
                                <tr>
                                    <td><strong>{{ $item->kelas->nama_kelas }}</strong></td>
                                    <td>{{ $item->total_jam_belajar }}</td>
                                    <td>{{ $item->jam_mulai ?? '-' }}</td>
                                    <td>{{ $item->jam_selesai ?? '-' }}</td>

                                    {{-- ISTIRAHAT 1 --}}
                                    <td>
                                        @if($item->waktu_istirahat_mulai && $item->waktu_istirahat_selesai)
                                            {{ $item->waktu_istirahat_mulai }} - {{ $item->waktu_istirahat_selesai }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- ➕ ISTIRAHAT 2 --}}
                                    <td>
                                        @if($item->waktu_istirahat2_mulai && $item->waktu_istirahat2_selesai)
                                            {{ $item->waktu_istirahat2_mulai }} - {{ $item->waktu_istirahat2_selesai }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center">
                                            <a href="{{ route('superadmin.jam-belajar.edit', $item->id) }}" 
                                               class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 me-1">
                                                <i class="ti ti-edit"></i> Edit
                                            </a>

                                            <form action="{{ route('superadmin.jam-belajar.destroy', $item->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1"
                                                    onclick="return confirm('Hapus data ini?')">
                                                    <i class="ti ti-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="ti ti-info-circle"></i> Belum ada data jam belajar
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
.btn-outline-warning {
    border: 1px solid #ff9800;
    color: #ff9800;
    transition: 0.2s;
}
.btn-outline-warning:hover {
    background-color: #ff9800;
    color: #fff;
}
.btn-outline-danger {
    border: 1px solid #f44336;
    color: #f44336;
    transition: 0.2s;
}
.btn-outline-danger:hover {
    background-color: #f44336;
    color: #fff;
}
.btn {
    font-weight: 500;
    border-radius: 6px;
    padding: 6px 12px;
}
.card.bg-light {
    border: 1px solid #e6e9ef;
}
.form-control, .form-select {
    border-radius: 8px;
}
.form-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}
</style>

@endsection
