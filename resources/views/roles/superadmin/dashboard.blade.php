@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Header --}}
        <div class="page-header mb-3">
            <h2 class="page-title fw-bold text-dark">Dashboard Super Admin</h2>
            <p class="text-muted">Ringkasan aktivitas dan kondisi sistem keuangan.</p>
        </div>

        {{-- Statistik Utama --}}
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="text-primary me-3 fs-2"><i class="ti ti-users"></i></span>
                            <div>
                                <div class="text-muted">Total Akun Terdaftar</div>
                                <div class="fw-bold fs-4">128</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="text-success me-3 fs-2"><i class="ti ti-wallet"></i></span>
                            <div>
                                <div class="text-muted">Total Saldo (BOS + Kas)</div>
                                <div class="fw-bold fs-4">Rp 1.600.000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="text-warning me-3 fs-2"><i class="ti ti-report-money"></i></span>
                            <div>
                                <div class="text-muted">Transaksi Bulan Ini</div>
                                <div class="fw-bold fs-4">42</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="text-danger me-3 fs-2"><i class="ti ti-alert-circle"></i></span>
                            <div>
                                <div class="text-muted">Pengeluaran Tertinggi</div>
                                <div class="fw-bold fs-4">Rp 500.000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Simulasi Grafik (tanpa chart) --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">Statistik Keuangan</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <div class="fw-bold text-muted">Dana BOS</div>
                        <div class="bg-primary rounded mt-2 mb-2" style="height: 120px; width: 50px; margin: auto; opacity: 0.6;"></div>
                        <small>Pemasukan: Rp 1.400.000</small><br>
                        <small>Pengeluaran: Rp 300.000</small>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-muted">Kas</div>
                        <div class="bg-success rounded mt-2 mb-2" style="height: 90px; width: 50px; margin: auto; opacity: 0.6;"></div>
                        <small>Pemasukan: Rp 1.000.000</small><br>
                        <small>Pengeluaran: Rp 400.000</small>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-muted">Total</div>
                        <div class="bg-info rounded mt-2 mb-2" style="height: 150px; width: 50px; margin: auto; opacity: 0.6;"></div>
                        <small>Pemasukan: Rp 2.400.000</small><br>
                        <small>Pengeluaran: Rp 700.000</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="card">
            <div class="card-header fw-bold">Aktivitas Terbaru</div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Kegiatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10 Okt 2025</td>
                            <td>Admin Sekolah</td>
                            <td>Menambahkan transaksi Dana BOS</td>
                            <td><span class="badge bg-success">Berhasil</span></td>
                        </tr>
                        <tr>
                            <td>09 Okt 2025</td>
                            <td>Bendahara</td>
                            <td>Menghapus log keuangan</td>
                            <td><span class="badge bg-warning">Diperiksa</span></td>
                        </tr>
                        <tr>
                            <td>08 Okt 2025</td>
                            <td>Super Admin</td>
                            <td>Menambah akun baru</td>
                            <td><span class="badge bg-info">Sukses</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
