@extends('layouts.guru')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header d-print-none mb-4">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col d-flex align-items-center gap-2">

                {{-- BACK ICON --}}
                <a href="{{ route('guru.jadwal.index') }}"
                   class="btn btn-icon btn-outline-primary"
                   title="Kembali">
                    <i class="ti ti-arrow-left"></i>
                </a>

                <div>
                    <div class="page-pretitle text-muted">Jadwal Kelas</div>
                    <h2 class="page-title mb-0">
                        {{ $kelas->nama_kelas }}
                    </h2>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- PAGE BODY --}}
<div class="page-body">
    <div class="container-xl">

        {{-- FILTER HARI --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('guru.jadwal.show', $kelas->id) }}">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="fw-semibold text-muted me-2">
                            <i class="ti ti-filter"></i>
                        </span>

                        <button type="submit" name="hari" value=""
                            class="btn btn-sm {{ request('hari') == null ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua
                        </button>

                        @php
                            $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        @endphp

                        @foreach ($urutanHari as $hari)
                            <button type="submit" name="hari" value="{{ $hari }}"
                                class="btn btn-sm {{ request('hari') == $hari ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $hari }}
                            </button>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        {{-- NOTES KETERANGAN --}}
        <div class="alert alert-info d-flex align-items-center gap-3 mb-4 shadow-sm">
            <span class="avatar avatar-sm bg-primary text-white">
                <i class="ti ti-user"></i>
            </span>
            <div>
                <div class="fw-semibold text-primary">
                    Keterangan Jadwal
                </div>
                <div class="text-muted">
                    Jadwal yang <span class="fw-semibold text-primary">berwarna biru</span>
                    dan memiliki label
                    <span class="badge bg-primary-lt text-primary">Jadwal Anda</span>
                    menandakan jadwal mengajar Anda.
                </div>
            </div>
        </div>

        {{-- JADWAL --}}
        @if($jadwal_grouped->isEmpty())

            {{-- EMPTY STATE --}}
            <div class="card shadow-sm">
                <div class="card-body text-center py-6">
                    <i class="ti ti-calendar-off text-muted" style="font-size:56px;"></i>
                    <h3 class="mt-3 mb-1">Belum Ada Jadwal Untuk Anda</h3>
                    <p class="text-muted mb-0">
                        Jadwal pelajaran belum tersedia untuk kelas ini
                    </p>
                </div>
            </div>

        @else

            @foreach ($urutanHari as $hari)
                @if($jadwal_grouped->has($hari))
                <div class="card mb-4 shadow-sm">

                    {{-- HEADER HARI --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="ti ti-calendar-event text-primary me-2"></i>
                            {{ $hari }}
                        </h3>
                        <span class="badge bg-primary-lt">
                            {{ $jadwal_grouped[$hari]->count() }} Jadwal
                        </span>
                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter card-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="w-1 text-center text-muted">Jam</th>
                                    <th>Waktu</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($jadwal_grouped[$hari] as $jadwal)
                                <tr>

                                    {{-- JAM --}}
                                    <td class="text-center fw-semibold text-muted">
                                        {{ $jadwal->jam_ke }}
                                    </td>

                                    {{-- WAKTU --}}
                                    <td class="text-muted">
                                        <i class="ti ti-clock me-1"></i>
                                        {{ date('H:i', strtotime($jadwal->waktu_mulai)) }}
                                        –
                                        {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}
                                    </td>

                                    {{-- MAPEL --}}
                                    <td class="fw-semibold">
                                        {{ $jadwal->nama_mata_pelajaran }}
                                    </td>

                                    {{-- GURU --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-sm
                                                {{ $jadwal->guru_id == Auth::id() ? 'bg-primary text-white' : 'bg-secondary text-white' }}">
                                                {{ strtoupper(substr($jadwal->nama_guru, 0, 2)) }}
                                            </span>

                                            <div class="lh-sm">
                                                <div class="fw-medium d-flex align-items-center gap-1
                                                    {{ $jadwal->guru_id == Auth::id() ? 'text-primary' : 'text-dark' }}">
                                                    {{ $jadwal->nama_guru }}

                                                    @if($jadwal->guru_id == Auth::id())
                                                        <i class="ti ti-circle-check text-success"
                                                           title="Jadwal Anda"></i>
                                                    @endif
                                                </div>

                                                @if($jadwal->guru_id == Auth::id())
                                                    <span class="badge bg-primary-lt mt-1 text-primary">
                                                        Jadwal Anda
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KETERANGAN --}}
                                    <td class="text-muted">
                                        {{ $jadwal->keterangan ?? '-' }}
                                    </td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
                @endif
            @endforeach

        @endif

    </div>
</div>

@endsection
