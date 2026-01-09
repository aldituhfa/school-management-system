@extends('layouts.guru')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Semua Materi Pembelajaran</h4>
            <p class="text-muted mb-0">Kelola semua materi yang telah Anda upload</p>
        </div>
        <a href="{{ route('guru.materi.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Upload Materi Baru
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('guru.materi.list') }}">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label small">Cari Materi</label>
                        <input type="text" name="search" class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Cari judul atau deskripsi">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Jadwal</label>
                        <select name="jadwal_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Jadwal</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}"
                                    {{ request('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ optional($jadwal->mapel)->nama_mapel ?? '-' }}
                                    -
                                    {{ optional($jadwal->kelas)->nama_kelas ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small">Tipe Materi</label>
                        <select name="tipe_materi" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach(['pdf','dokumen','presentasi','video','lainnya'] as $tipe)
                                <option value="{{ $tipe }}"
                                    {{ request('tipe_materi') === $tipe ? 'selected' : '' }}>
                                    {{ ucfirst($tipe) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->query())
                            <a href="{{ route('guru.materi.list') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- List Materi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            @forelse($materis as $materi)
                @php
                    $ext = strtolower(pathinfo($materi->file_name, PATHINFO_EXTENSION));
                @endphp

                <div class="card mb-3 border">
                    <div class="card-body p-3">
                        <div class="row align-items-center">

                            {{-- Icon --}}
                            <div class="col-auto">
                                <div class="bg-light rounded p-3">
                                    @switch($ext)
                                        @case('pdf') <i class="bi bi-file-earmark-pdf text-danger fs-3"></i> @break
                                        @case('doc') @case('docx') <i class="bi bi-file-earmark-word text-primary fs-3"></i> @break
                                        @case('ppt') @case('pptx') <i class="bi bi-file-earmark-ppt text-warning fs-3"></i> @break
                                        @case('xls') @case('xlsx') <i class="bi bi-file-earmark-excel text-success fs-3"></i> @break
                                        @default <i class="bi bi-file-earmark text-secondary fs-3"></i>
                                    @endswitch
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="col">
                                <h6 class="fw-bold mb-1">{{ $materi->judul }}</h6>

                                <div class="mb-2">
                                    <span class="badge bg-primary">
                                        {{ optional($materi->jadwal?->mapel)->nama_mapel ?? '-' }}
                                    </span>
                                    <span class="badge bg-secondary">
                                        {{ optional($materi->jadwal?->kelas)->nama_kelas ?? '-' }}
                                    </span>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($materi->tipe_materi) }}
                                    </span>
                                </div>

                                @if($materi->deskripsi)
                                    <p class="text-muted small mb-2">
                                        {{ \Illuminate\Support\Str::limit($materi->deskripsi, 100) }}
                                    </p>
                                @endif

                                <small class="text-muted">
                                    {{ $materi->file_name }}
                                    • {{ $materi->file_size_formatted ?? '-' }}
                                    • {{ $materi->created_at->format('d M Y H:i') }}
                                </small>
                            </div>

                            {{-- Action --}}
                            <div class="col-auto">
                                <a href="{{ route('guru.materi.download', $materi->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteMateri({{ $materi->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Tidak ada materi ditemukan</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            {{ $materis->links() }}

        </div>
    </div>
</div>

{{-- Delete --}}
<form id="deleteForm" method="POST">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteMateri(id) {
    if (confirm('Hapus materi ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/guru/materi/${id}`;
        form.submit();
    }
}
</script>
@endsection
