@extends('layouts.superadmin')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="page-title mb-1">Dashboard Super Admin</h2>
                <div class="text-muted">Ringkasan aktivitas dan kondisi sistem</div>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-primary-lt px-3 py-2">
                    <i class="ti ti-calendar me-1"></i>
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>

        {{-- STAT CARD --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-primary-lt me-3 rounded">
                                <i class="ti ti-users text-primary"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Total Akun</div>
                                <div class="h2 fw-bold mb-0">{{ $totalUsers }}</div>
                                <div class="text-muted small mt-1">Seluruh pengguna</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-blue-lt me-3 rounded">
                                <i class="ti ti-school text-blue"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Total Siswa</div>
                                <div class="h2 fw-bold mb-0">{{ $totalSiswa }}</div>
                                <div class="text-muted small mt-1">Siswa aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-green-lt me-3 rounded">
                                <i class="ti ti-wallet text-green"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Total Saldo</div>
                                <div class="h2 fw-bold mb-0 text-success">
                                    Rp {{ number_format($totalSaldo,0,',','.') }}
                                </div>
                                <div class="text-muted small mt-1">Semua sumber dana</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-orange-lt me-3 rounded">
                                <i class="ti ti-calendar-stats text-orange"></i>
                            </div>
                            <div>
                                <div class="text-muted small text-uppercase fw-semibold tracking-wide">Transaksi Bulan Ini</div>
                                <div class="h2 fw-bold mb-0 text-primary">{{ $transaksiBulanIni }}</div>
                                <div class="text-muted small mt-1">Total transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pt-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-lt rounded p-2 me-3">
                                <i class="ti ti-chart-bar text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-semibold">Statistik Keuangan</h5>
                                <div class="text-muted small">Tahun {{ now()->year }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <canvas id="financeChart" style="height: 280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- AKTIVITAS TERBARU --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <div class="d-flex align-items-center">
                    <div class="bg-info-lt rounded p-2 me-3">
                        <i class="ti ti-clock text-info"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-semibold">Aktivitas Terbaru</h5>
                        <div class="text-muted small">Log transaksi dan aktivitas sistem</div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-vcard align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 ps-4 text-muted small fw-semibold">WAKTU</th>
                            <th class="border-0 py-3 text-muted small fw-semibold">USER</th>
                            <th class="border-0 py-3 text-muted small fw-semibold">AKSI</th>
                            <th class="border-0 py-3 text-muted small fw-semibold">NOMINAL</th>
                            <th class="border-0 py-3 text-muted small fw-semibold">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitas as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-medium">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-muted small">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs bg-secondary-lt me-2">
                                        <i class="ti ti-user"></i>
                                    </div>
                                    <span class="fw-medium">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                $badgeClass = match($log->action) {
                                'tambah', 'bayar', 'paid' => 'bg-success-lt text-success',
                                'edit', 'update' => 'bg-warning-lt text-warning',
                                'hapus', 'delete' => 'bg-danger-lt text-danger',
                                'login' => 'bg-info-lt text-info',
                                default => 'bg-secondary-lt text-secondary'
                                };
                                @endphp
                                <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 fw-normal">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($log->after_amount,0,',','.') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $log->status === 'success' ? 'success' : 'warning' }}-lt">
                                    {{ $log->status ?? 'SUCCESS' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <i class="ti ti-history" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                    </div>
                                    <p class="empty-title h6">Belum ada aktivitas</p>
                                    <p class="empty-subtitle text-muted">Aktivitas terbaru akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('financeChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Dana BOS', 'Kas Sekolah', 'Total Dana'],
                datasets: [{
                    label: 'Saldo',
                    data: [
                        @json($totalBos),
                        @json($totalKas),
                        @json($totalSemua)
                    ],
                    backgroundColor: [
                        'rgba(97, 146, 219, 0.85)',
                        'rgba(111, 246, 136, 0.85)',
                        'rgba(241, 161, 96, 0.85)'
                    ],
                    borderRadius: 8,
                    barPercentage: 0.65,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: {
                            size: 12,
                            weight: '500'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.03)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            },
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    .card {
        transition: all 0.2s ease;
        border-radius: 12px;
    }

    .card-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05) !important;
    }

    .avatar {
        --tblr-avatar-size: 2.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-xs {
        --tblr-avatar-size: 1.75rem;
    }

    .tracking-wide {
        letter-spacing: 0.5px;
    }

    .table th {
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        background-color: #f8fafc;
    }

    .table td {
        padding: 1rem 0.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.02);
        vertical-align: middle;
    }

    .badge {
        font-weight: 500;
        font-size: 0.7rem;
    }

    .bg-primary-lt {
        background: rgba(13, 110, 253, 0.08);
    }

    .bg-blue-lt {
        background: rgba(13, 110, 253, 0.08);
    }

    .bg-green-lt {
        background: rgba(47, 179, 68, 0.08);
    }

    .bg-orange-lt {
        background: rgba(253, 126, 20, 0.08);
    }

    .bg-info-lt {
        background: rgba(13, 202, 240, 0.08);
    }

    .text-blue {
        color: #0d6efd;
    }

    .text-green {
        color: #2fb344;
    }

    .text-orange {
        color: #fd7e14;
    }

    .border-0 {
        border: none !important;
    }

    .shadow-sm {
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02) !important;
    }

    .empty {
        padding: 1.5rem 0;
    }

    .empty-icon {
        margin-bottom: 0.75rem;
    }
</style>
@endsection