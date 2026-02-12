@extends('layouts.tu')

@section('content')
<div class="container-xl">

    {{-- HEADER --}}
    <div class="page-header mb-4">
        <h2 class="page-title fw-normal" style="color: #2c3e50;">Dashboard Tata Usaha</h2>
        <div class="text-muted">Ringkasan data SPP & kegiatan sekolah</div>
    </div>

    {{-- HEADER WITH FILTER --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; background: #ffffff;">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center bg-light px-3 py-2 rounded" style="background-color: #f8fafc;">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2">
                            <i class="bx bx-calendar text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted text-xs mb-1">Tahun Ajaran</div>
                            <div class="fw-medium" style="color: #334155; font-size: 0.95rem;">
                                {{ $tahunAjaran->firstWhere('id', $tahunAjaranId)?->nama_tahun ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FILTER TAHUN AJARAN --}}
                <form method="GET" class="w-25">
                    <select name="tahun_ajaran_id"
                        class="form-select border-1"
                        style="border-color: #d1d9e6; background: white; font-size: 0.9rem; padding: 0.375rem 0.75rem;"
                        onchange="this.form.submit()">
                        @foreach ($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}"
                            {{ $tahunAjaranId == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama_tahun }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- ALERT NONAKTIF --}}
    @if ($tahunAjaranNonaktif)
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert"
        style="border-left: 3px solid #f59e0b; border-radius: 6px; background-color: #fffbeb; padding: 0.75rem 1rem;">
        <div class="d-flex align-items-center">
            <div class="bg-warning bg-opacity-10 rounded-circle p-1 me-3">
                <i class="bx bx-error-circle text-warning"></i>
            </div>
            <div style="font-size: 0.9rem;">
                <strong style="color: #92400e;">Perhatian!</strong>
                <span style="color: #78350f;">
                    Tahun ajaran yang dipilih saat ini <b>NONAKTIF</b>
                </span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size: 0.7rem;"></button>
        </div>
    </div>
    @endif

    {{-- STATISTIK SPP --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #4a90e2;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-user text-primary"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Total Siswa</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #1e40af; font-size: 1.5rem;">{{ $totalSiswa }}</div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-group me-1"></i>Jumlah siswa aktif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #8b5cf6;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-purple bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-receipt text-purple"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Total Tagihan</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #6d28d9; font-size: 1.4rem;">
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-trending-up me-1"></i>Tagihan tahun ini
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #10b981;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-check-circle text-success"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Sudah Dibayar</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #065f46; font-size: 1.4rem;">
                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                    </div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-check me-1"></i>Pembayaran sukses
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #ef4444;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-error-circle text-danger"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Tunggakan</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #991b1b; font-size: 1.4rem;">
                        Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                    </div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-time me-1"></i>Belum terbayar
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="row g-3 mb-4">
        {{-- CHART SPP BULANAN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: white;">
                <div class="card-header border-bottom-0" style="background: #f8fafc; border-radius: 10px 10px 0 0; padding: 0.75rem 1rem;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-chart text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-medium mb-0" style="color: #1e40af; font-size: 1rem;">SPP Bulanan</h5>
                            <div class="text-muted text-xs">Rekap pembayaran per bulan</div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div style="height: 220px;">
                        <canvas id="sppBulananChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- CHART STATUS SPP --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 10px; background: white;">
                <div class="card-header border-bottom-0" style="background: #f8fafc; border-radius: 10px 10px 0 0; padding: 0.75rem 1rem;">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-pie-chart text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-medium mb-0" style="color: #065f46; font-size: 1rem;">Status SPP Tahunan</h5>
                            <div class="text-muted text-xs">Persentase kelunasan</div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div style="height: 220px;">
                        <canvas id="sppStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIK KEGIATAN --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #8b5cf6;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-purple bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-calendar-event text-purple"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Total Kegiatan</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #6d28d9; font-size: 1.5rem;">{{ $totalKegiatan }}</div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-party me-1"></i>Seluruh kegiatan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #f59e0b;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-time text-warning"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Kegiatan Aktif</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #92400e; font-size: 1.5rem;">{{ $kegiatanAktif }}</div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-run me-1"></i>Sedang berlangsung
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; border-left: 3px solid #10b981;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-1 me-3">
                            <i class="bx bx-check text-success"></i>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">Kegiatan Selesai</div>
                    </div>
                    <div class="h4 fw-bold mb-0" style="color: #065f46; font-size: 1.5rem;">{{ $kegiatanSelesai }}</div>
                    <div class="text-muted text-xs mt-1">
                        <i class="bx bx-check-circle me-1"></i>Sudah selesai
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ======================
        // BAR CHART SPP BULANAN
        // ======================
        const sppBulananCtx = document.getElementById('sppBulananChart');
        if (sppBulananCtx) {
            new Chart(sppBulananCtx, {
                type: 'bar',
                data: {
                    labels: @json($bulanLabels),
                    datasets: [{
                        label: 'SPP Lunas',
                        data: @json($sppBulanan),
                        backgroundColor: 'rgba(74, 144, 226, 0.85)',
                        borderRadius: 6,
                        borderWidth: 0,
                        barPercentage: 0.7,
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
                            backgroundColor: 'rgba(30, 58, 138, 0.9)',
                            padding: 10,
                            cornerRadius: 6,
                            titleFont: {
                                size: 12
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    return `${context.raw} siswa`;
                                }
                            }
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
                                font: {
                                    size: 10
                                },
                                padding: 6
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.03)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                padding: 6
                            }
                        }
                    }
                }
            });
        }

        // ======================
        // DOUGHNUT CHART TAHUNAN
        // ======================
        const sppStatusCtx = document.getElementById('sppStatusChart');
        if (sppStatusCtx) {
            new Chart(sppStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Lunas', 'Belum Lunas'],
                    datasets: [{
                        data: @json([
                            $sppTahunan['lunas'],
                            $sppTahunan['belum']
                        ]),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.9)',
                            'rgba(239, 68, 68, 0.9)'
                        ],
                        borderWidth: 1,
                        borderColor: 'white',
                        borderRadius: 8,
                        spacing: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 12,
                                font: {
                                    size: 11
                                },
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#334155'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 10,
                            cornerRadius: 6,
                            titleFont: {
                                size: 12
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} siswa (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // Auto-hide alert after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                bootstrap.Alert.getInstance(alert)?.close();
            }
        }, 5000);

    });
</script>

<style>
    .card {
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
    }

    .form-select:focus {
        border-color: #4a90e2 !important;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15) !important;
    }

    .alert {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    canvas {
        max-width: 100%;
    }

    .text-purple {
        color: #8b5cf6;
    }

    .bg-purple {
        background-color: #8b5cf6;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .h4 {
        font-size: 1.4rem;
    }

    .card-body {
        padding: 0.75rem 1rem;
    }

    .btn-close {
        padding: 0.5rem;
        background-size: 0.8rem;
    }
</style>
@endsection