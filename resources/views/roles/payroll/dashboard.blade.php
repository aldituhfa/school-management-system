@extends('layouts.payroll')

@section('content')
<div class="container-xl">

    {{-- HEADER --}}
    <div class="page-header mb-4">
        <h2 class="page-title fw-normal" style="color: #2c3e50;">Dashboard Payroll</h2>
        <div class="text-muted">Monitor penggajian dan keuangan</div>
    </div>

    {{-- HEADER WITH FILTER --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="d-flex align-items-center bg-light px-3 py-2 rounded" style="background-color: #f8fafc;">
                            <i class="bx bx-calendar text-primary me-2"></i>
                            <div>
                                <div class="text-muted small">Periode Aktif</div>
                                <div class="fw-medium" style="color: #334155;">
                                    @if($currentPeriod)
                                    {{ DateTime::createFromFormat('!m', $currentPeriod->bulan)->format('F') }} {{ $currentPeriod->tahun }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($currentPeriod)
                        <span class="badge rounded-pill px-3 py-1 fw-medium"
                            style="background: {{ $currentPeriod->status == 'open' ? '#fef3c7' : ($currentPeriod->status == 'processing' ? '#e0f2fe' : '#d1fae5') }}; 
                                     color: {{ $currentPeriod->status == 'open' ? '#92400e' : ($currentPeriod->status == 'processing' ? '#0369a1' : '#065f46') }};">
                            {{ ucfirst($currentPeriod->status) }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- FILTER PERIODE --}}
                <form method="GET" class="w-25">
                    <select name="period_id"
                        class="form-select border-1"
                        style="border-color: #d1d9e6;"
                        onchange="this.form.submit()">
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}"
                            {{ $currentPeriod && $currentPeriod->id == $period->id ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $period->bulan)->format('M') }} {{ $period->tahun }}
                            ({{ ucfirst($period->status) }})
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #4a90e2;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-user text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Pegawai Aktif</div>
                            <div class="h4 fw-medium mb-0" style="color: #334155;">{{ $totalPegawaiAktif }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #10b981;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-check-circle text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Sudah Dibayar</div>
                            <div class="h4 fw-medium mb-0" style="color: #059669;">{{ $totalDibayar }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #ef4444;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-time text-danger"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Belum Dibayar</div>
                            <div class="h4 fw-medium mb-0" style="color: #dc2626;">{{ $belumDibayar }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; border-left: 3px solid #f59e0b;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                            <i class="bx bx-dollar-circle text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Total Dibayar</div>
                            <div class="h4 fw-medium mb-0" style="color: #d97706;">
                                Rp {{ number_format($totalNominal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PROGRESS BAR --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-medium mb-0" style="color: #1e293b;">Progress Pembayaran Gaji</h6>
                    <div class="text-muted small">{{ $progress }}% gaji telah dibayarkan</div>
                </div>
                <div class="h5 fw-medium" style="color: #4a90e2;">{{ $progress }}%</div>
            </div>
            <div class="progress" style="height: 10px; border-radius: 5px;">
                <div class="progress-bar"
                    role="progressbar"
                    style="width: {{ $progress }}%; background: linear-gradient(90deg, #4a90e2, #2c6cb0); border-radius: 5px;">
                </div>
            </div>
        </div>
    </div>

    {{-- SALDO --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Saldo Kas</div>
                            <div class="h3 fw-medium mb-0" style="color: #4a90e2;">
                                Rp {{ number_format($saldoKas, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bx bx-wallet text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Saldo Dana BOS</div>
                            <div class="h3 fw-medium mb-0" style="color: #10b981;">
                                Rp {{ number_format($saldoBos, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bx bx-credit-card text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-medium mb-0" style="color: #1e293b;">
                    Grafik Pengeluaran Gaji {{ now()->year }}
                </h5>
            </div>
            <div style="height: 250px;"> <!-- Container dengan fixed height -->
                <canvas id="salaryChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salaryChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartValues),
                    borderWidth: 2,
                    borderColor: '#4a90e2',
                    backgroundColor: 'rgba(74, 144, 226, 0.05)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4a90e2',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        cornerRadius: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: v => 'Rp ' + v.toLocaleString('id-ID'),
                            font: {
                                size: 11
                            },
                            padding: 5
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            padding: 5
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                responsive: true,
                maintainAspectRatio: false, // Ini yang penting
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                }
            }
        });
    });
</script>

<style>
    .card {
        transition: all 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08) !important;
    }

    .form-select:focus {
        border-color: #4a90e2 !important;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1) !important;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.85em;
    }

    .progress {
        background-color: #f1f5f9;
    }

    /* Pastikan chart container memiliki height yang fixed */
    #salaryChart {
        width: 100% !important;
    }
</style>
@endsection