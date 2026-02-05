@extends('layouts.payroll')

@section('content')
<div class="container-xl">
    <div class="page-header mb-4">
        <h2 class="page-title fw-normal" style="color: #2c3e50;">Laporan Gaji</h2>
        <div class="text-muted">Analisis dan laporan penggajian per periode</div>
    </div>

    {{-- FILTER PERIODE DENGAN EXPORT --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1 me-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <select name="period_id" class="form-select border-1" style="border-color: #d1d9e6;">
                                @foreach($periods as $period)
                                <option value="{{ $period->id }}"
                                    @selected($selectedPeriod && $selectedPeriod->id == $period->id)>
                                    {{ DateTime::createFromFormat('!m', $period->bulan)->format('F') }} {{ $period->tahun }}
                                    <span class="text-muted">({{ ucfirst($period->status) }})</span>
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100 py-2"
                                style="background: #4a90e2; border: none;">
                                <i class="bx bx-search me-1"></i>
                                Tampilkan
                            </button>
                        </div>
                    </form>
                </div>

                @if($selectedPeriod)
                <div class="d-flex gap-2">
                    <a href="{{ route('payroll.laporan_gaji.export.pdf', ['period_id' => $selectedPeriod->id]) }}"
                        class="btn btn-outline-danger px-3 d-flex align-items-center gap-2">
                        <i class="bx bx-file-pdf"></i>
                        PDF
                    </a>

                    <a href="{{ route('payroll.laporan_gaji.export.excel', ['period_id' => $selectedPeriod->id]) }}"
                        class="btn btn-outline-success px-3 d-flex align-items-center gap-2">
                        <i class="bx bx-file"></i>
                        Excel
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RINGKASAN STATISTIK --}}
    @if($selectedPeriod)
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #4a90e2;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-calendar text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Periode</div>
                            <div class="h5 fw-medium mb-0" style="color: #334155;">
                                {{ DateTime::createFromFormat('!m', $selectedPeriod->bulan)->format('F') }} {{ $selectedPeriod->tahun }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #ef4444;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-dollar text-danger"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Total Uang Keluar</div>
                            <div class="h5 fw-medium mb-0" style="color: #dc2626;">
                                Rp {{ number_format($totalPaid, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #10b981;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-user text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Jumlah Penerima</div>
                            <div class="h5 fw-medium mb-0" style="color: #059669;">
                                {{ $histories->count() }} Orang
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL PENGGAJIAN --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h4 class="card-title mb-0 fw-medium" style="color: #1e293b;">
                Detail Penggajian
            </h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th class="border-bottom py-3 px-4 text-muted small fw-normal">Nama</th>
                            <th class="border-bottom py-3 px-4 text-muted small fw-normal">Role</th>
                            <th class="border-bottom py-3 px-4 text-muted small fw-normal">Gaji Pokok</th>
                            <th class="border-bottom py-3 px-4 text-muted small fw-normal">Status</th>
                            <th class="border-bottom py-3 px-4 text-muted small fw-normal">Tanggal Dibayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td class="py-3 px-4">
                                <div class="fw-medium" style="color: #334155;">{{ $item->user->name }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge rounded-pill fw-normal px-3"
                                    style="background: #e0f2fe; color: #0369a1;">
                                    {{ ucfirst($item->user->role) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="fw-medium" style="color: #065f46;">
                                    Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if($item->status == 'paid')
                                <span class="badge rounded-pill fw-normal px-3"
                                    style="background: #d1fae5; color: #065f46;">
                                    PAID
                                </span>
                                @else
                                <span class="badge rounded-pill fw-normal px-3"
                                    style="background: #fef3c7; color: #92400e;">
                                    PENDING
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($item->paid_at)
                                <div class="text-muted">
                                    {{ $item->paid_at->format('d/m/Y') }}
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 px-4">
                                <div class="py-4">
                                    <div class="text-muted h5 mb-2">Tidak ada data gaji</div>
                                    <div class="text-muted small">Belum ada transaksi pada periode ini</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    {{-- STATE KOSONG --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body text-center py-5">
            <div class="text-muted h5 mb-2">Pilih Periode</div>
            <div class="text-muted small">Silakan pilih periode penggajian untuk melihat laporan</div>
        </div>
    </div>
    @endif
</div>

<style>
    .card {
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    .badge {
        padding: 0.35em 0.85em;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #4a90e2 !important;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1) !important;
    }

    .btn-outline-danger,
    .btn-outline-success {
        border-width: 1px;
    }

    .btn-outline-danger:hover {
        background-color: #ef4444;
        color: white;
    }

    .btn-outline-success:hover {
        background-color: #10b981;
        color: white;
    }
</style>
@endsection