@extends('layouts.superadmin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><i class="fas fa-table"></i> Jadwal Pelajaran Kelas</h5>
                        <p class="mb-0 small">
                            <i class="fas fa-school"></i> Kelas: <strong>{{ $kelas->nama_kelas }}</strong>
                        </p>
                    </div>
                    <a href="{{ route('jadwal.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                
                <div class="card-body">
                    @php
                        $hasJadwal = false;
                        foreach($hari as $h) {
                            if(isset($jadwal[$h]) && count($jadwal[$h]) > 0) {
                                $hasJadwal = true;
                                break;
                            }
                        }
                    @endphp

                    @if(!$hasJadwal)
                        <div class="alert alert-warning text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h5>Belum Ada Jadwal</h5>
                            <p class="mb-0">Kelas ini belum memiliki jadwal pelajaran.</p>
                        </div>
                    @else
                        @foreach($hari as $h)
                            @if(isset($jadwal[$h]) && count($jadwal[$h]) > 0)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info text-white px-3 py-2 rounded-start">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <div class="bg-info bg-opacity-10 flex-grow-1 px-3 py-2 rounded-end border border-info">
                                        <strong>{{ $h }}</strong>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" width="10%">Jam Ke</th>
                                                <th width="18%">Waktu</th>
                                                <th width="35%">Mata Pelajaran</th>
                                                <th width="25%">Guru</th>
                                                <th width="12%">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jadwal[$h] as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-info fs-6">{{ $item->jam_ke }}</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-clock text-muted"></i> 
                                                    {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                                                </td>
                                                <td>
                                                    <i class="fas fa-book-open text-success"></i> 
                                                    <strong>{{ $item->mataPelajaran->nama_mata_pelajaran ?? '-' }}</strong>
                                                </td>
                                                <td>
                                                    <i class="fas fa-chalkboard-teacher text-primary"></i> 
                                                    {{ $item->guru->name ?? '-' }}
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $item->keterangan ?? '-' }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        <!-- Summary -->
                        <div class="card bg-light mt-4">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <i class="fas fa-list-ol fa-2x text-primary mb-2"></i>
                                            <h5 class="mb-0">{{ $jadwal->flatten()->count() }}</h5>
                                            <small class="text-muted">Total Jam Pelajaran</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <i class="fas fa-book fa-2x text-success mb-2"></i>
                                            <h5 class="mb-0">{{ $jadwal->flatten()->pluck('mata_pelajaran_id')->unique()->count() }}</h5>
                                            <small class="text-muted">Mata Pelajaran</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <i class="fas fa-users fa-2x text-warning mb-2"></i>
                                            <h5 class="mb-0">{{ $jadwal->flatten()->pluck('guru_id')->unique()->count() }}</h5>
                                            <small class="text-muted">Guru Pengajar</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Print Button -->
                        <div class="text-center mt-3">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print"></i> Cetak Jadwal
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .table thead th {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .badge {
        padding: 0.5em 0.8em;
    }
    
    /* Print Styles */
    @media print {
        .btn, .card-header a {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
    }
</style>
@endpush