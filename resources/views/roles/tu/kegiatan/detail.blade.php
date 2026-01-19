@extends('layouts.tu')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="row mb-3">
            <div class="col">
                <div class="page-pretitle">Kegiatan Sekolah</div>
                <h2 class="page-title">{{ $kegiatan->nama_kegiatan }}</h2>
            </div>
            <div class="col-auto">
                <a href="{{ route('tu.kegiatan.index') }}" class="btn btn-outline-secondary">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- INFO KEGIATAN --}}
        <div class="card mb-3">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">Informasi Kegiatan</h3>
            </div>

            <div class="card-body py-3 position-relative">

                {{-- STATUS --}}
                <div class="position-absolute top-0 end-0 mt-2 me-3">
                    @if($kegiatan->status == 'Selesai')
                    <span class="px-3 py-1 rounded-pill fw-semibold"
                        style="background:#e8f5e9; color:#2e7d32; font-size:13px;">
                        {{ $kegiatan->status }}
                    </span>
                    @elseif($kegiatan->status == 'Sedang Berlangsung')
                    <span class="px-3 py-1 rounded-pill fw-semibold"
                        style="background:#e3f2fd; color:#1565c0; font-size:13px;">
                        {{ $kegiatan->status }}
                    </span>
                    @else
                    <span class="px-3 py-1 rounded-pill fw-semibold"
                        style="background:#fff8e1; color:#ef6c00; font-size:13px;">
                        {{ $kegiatan->status }}
                    </span>
                    @endif
                </div>

                {{-- CONTAINER INFO --}}
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-10">

                            <div class="row g-2 text-center text-md-start">

                                <div class="col-md-6">
                                    <strong>Tanggal Pelaksanaan</strong><br>
                                    {{ $kegiatan->tanggal_pelaksanaan }}
                                </div>

                                <div class="col-md-6">
                                    <strong>Target Dana</strong><br>
                                    Rp {{ number_format($kegiatan->target_dana, 0, ',', '.') }}
                                </div>

                                <div class="col-md-6">
                                    <strong>Kelas</strong><br>
                                    @foreach($kelasList as $k)
                                    <span class="fw-semibold me-1 text-muted">
                                        {{ $k->nama_kelas }}
                                    </span>
                                    @endforeach
                                </div>

                                <div class="col-md-6">
                                    <strong>Dana Terkumpul</strong><br>
                                    Rp {{ number_format($kegiatan->terkumpul, 0, ',', '.') }}
                                </div>

                            </div>

                            {{-- PROGRESS --}}
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Progress</span>
                                    <span class="text-muted">
                                        {{ round($kegiatan->progress, 2) }}%
                                    </span>
                                </div>

                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        style="width: {{ round($kegiatan->progress, 2) }}%">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- ACTION --}}
            <div class="card-footer py-2 d-flex justify-content-between">
                <div>
                    @if($kegiatan->status != 'Selesai')
                    <form action="{{ route('tu.kegiatan.selesai', $kegiatan->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-dark btn-sm">
                            Tandai Selesai
                        </button>
                    </form>
                    @endif
                </div>

                <div>
                    <form action="{{ route('tu.kegiatan.destroy', $kegiatan->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus kegiatan ini? Semua data pembayaran akan ikut terhapus!')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            Hapus Kegiatan
                        </button>
                    </form>
                </div>
            </div>
        </div>


        {{-- DAFTAR SISWA --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Siswa</h3>
            </div>

            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($siswa as $index => $s)
                        @php
                        $p = $pembayarans->get($s->id);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $s->nama_siswa }}</td>
                            <td>
                                <span class="text-primary fw-semibold">
                                    {{ $s->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td>
                                Rp {{ number_format($kegiatan->nominal_per_siswa, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($p && $p->status == 'lunas')
                                <span class="text-success fw-semibold">
                                    Lunas
                                </span>
                                @else
                                <span class="text-danger fw-semibold">
                                    Belum
                                </span>
                                @endif
                            </td>
                            <td>
                                {{ $p && $p->tanggal_bayar ? $p->tanggal_bayar->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="text-center">

                                @if($kegiatan->status == 'Selesai')
                                <span class="text-muted">-</span>
                                @else

                                @if(!$p || $p->status == 'belum')
                                <form method="POST"
                                    action="{{ route('tu.kegiatan.bayar', [$kegiatan->id, $s->id]) }}"
                                    onsubmit="return confirm('Konfirmasi bayar untuk {{ $s->nama_siswa }}?');">
                                    @csrf
                                    <button class="btn btn-outline-dark btn-sm">
                                        Bayar
                                    </button>
                                </form>
                                @else
                                <form method="POST"
                                    action="{{ route('tu.kegiatan.cancel', [$kegiatan->id, $s->id]) }}"
                                    onsubmit="return confirm('Batalkan pembayaran untuk {{ $s->nama_siswa }}?');">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm">
                                        Cancel
                                    </button>
                                </form>
                                @endif

                                @endif

                            </td>
                        </tr>
                        @endforeach

                        @if($siswa->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada siswa untuk kelas ini.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection