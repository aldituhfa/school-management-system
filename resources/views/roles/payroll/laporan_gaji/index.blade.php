@extends('layouts.payroll')

@section('content')
<div class="container-xl">
    <h2 class="mb-4">Laporan Gaji</h2>

    {{-- FILTER PERIODE --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Periode Penggajian</label>
                        <select name="period_id" class="form-select" required>
                            <option value="">-- Pilih Periode --</option>
                            @foreach($periods as $period)
                            <option value="{{ $period->id }}"
                                @selected(request('period_id')==$period->id)>
                                {{ $period->bulan }}/{{ $period->tahun }}
                                ({{ ucfirst($period->status) }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedPeriod)
    {{-- RINGKASAN --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Periode</small>
                    <h4>{{ $selectedPeriod->bulan }}/{{ $selectedPeriod->tahun }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Total Uang Keluar</small>
                    <h4 class="text-danger">
                        Rp {{ number_format($totalPaid, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Jumlah Penerima Gaji</small>
                    <h4>{{ $histories->count() }} Orang</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-3">Detail Penggajian</h4>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Gaji Pokok</th>
                        <th>Status</th>
                        <th>Tanggal Dibayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $item)
                    <tr>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ ucfirst($item->user->role) }}</td>
                        <td>
                            Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge 
                                    {{ $item->status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td>
                            {{ $item->paid_at ? $item->paid_at->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada data gaji
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection