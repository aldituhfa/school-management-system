@extends('layouts.tu')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <h3 class="fw-bold mb-3">{{ $kegiatan->nama_kegiatan }}</h3>

        <div class="card p-3 shadow-sm mb-3">
            <p><strong>Tanggal:</strong> {{ $kegiatan->tanggal_pelaksanaan }}</p>

            <p>
                <strong>Kelas:</strong>
                @foreach($kelasList as $k)
                    <span class="badge bg-secondary">{{ $k->nama_kelas }}</span>
                @endforeach
            </p>

            <p><strong>Target Dana:</strong> Rp {{ number_format($kegiatan->target_dana, 0, ',', '.') }}</p>
            <p><strong>Terkumpul:</strong> Rp {{ number_format($kegiatan->terkumpul, 0, ',', '.') }}</p>

            <p><strong>Status:</strong>
                @if($kegiatan->status == 'Selesai')
                    <span class="badge bg-success">Selesai</span>
                @elseif($kegiatan->status == 'Sedang Berlangsung')
                    <span class="badge bg-primary">Sedang Berlangsung</span>
                @else
                    <span class="badge bg-secondary">Perencanaan</span>
                @endif
            </p>

            {{-- Progress Bar --}}
            <div class="progress my-3" style="height:8px;">
                <div class="progress-bar bg-dark"
                    role="progressbar"
                    style="width: {{ round($kegiatan->progress, 2) }}%;"
                    aria-valuenow="{{ round($kegiatan->progress, 2) }}"
                    aria-valuemin="0"
                    aria-valuemax="100">
                </div>
            </div>

            {{-- Tombol selesai hanya muncul jika belum selesai --}}
            @if($kegiatan->status != 'Selesai')
                <form action="{{ route('tu.kegiatan.selesai', $kegiatan->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-dark">Tandai Selesai</button>
                </form>
            @endif
        </div>

        <h5>Daftar Siswa</h5>
        <div class="card p-3 shadow-sm">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Aksi</th>
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
                            <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                            <td>Rp {{ number_format($kegiatan->nominal_per_siswa, 0, ',', '.') }}</td>

                            <td>
                                @if($p && $p->status == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>

                            <td>{{ $p && $p->tanggal_bayar ? $p->tanggal_bayar->format('Y-m-d H:i') : '-' }}</td>

                            <td>

                                {{-- Jika kegiatan selesai → tidak ada aksi --}}
                                @if($kegiatan->status == 'Selesai')
                                    <span class="text-muted">-</span>

                                {{-- Belum selesai --}}
                                @else
                                    
                                    {{-- Jika belum bayar --}}
                                    @if(!$p || $p->status == 'belum')
                                        <form method="POST"
                                            action="{{ route('tu.kegiatan.bayar', [$kegiatan->id, $s->id]) }}"
                                            onsubmit="return confirm('Konfirmasi bayar untuk {{ $s->nama_siswa }}?');">
                                            @csrf
                                            <button class="btn btn-sm btn-dark" type="submit">Bayar</button>
                                        </form>

                                    {{-- Sudah bayar --}}
                                    @else
                                        <form method="POST"
                                            action="{{ route('tu.kegiatan.cancel', [$kegiatan->id, $s->id]) }}"
                                            onsubmit="return confirm('Batalkan pembayaran untuk {{ $s->nama_siswa }}?');">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Cancel</button>
                                        </form>
                                    @endif

                                @endif

                            </td>

                        </tr>
                    @endforeach

                    @if($siswa->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">Belum ada siswa untuk kelas ini.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
