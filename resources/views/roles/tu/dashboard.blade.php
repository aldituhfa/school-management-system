@extends('layouts.tu')

@section('content')
<style>
    .stat-card {
        border-radius: 18px;
        padding: 20px;
        transition: .3s;
        background: white;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .chart-card {
        border-radius: 18px;
    }
</style>

<div class="container mt-4">

    <h3 class="fw-bold">Dashboard Tata Usaha</h3>
    <p class="text-muted">Selamat datang di dashboard TU. Anda dapat mengelola data administrasi sekolah.</p>

    {{-- STATISTIC CARDS --}}
    <div class="row g-3 mt-3">

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Total Dokumen</h6>
                <h2 class="fw-bold">120</h2>
                <small class="text-success">+12 dokumen baru</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Permintaan Baru</h6>
                <h2 class="fw-bold">8</h2>
                <small class="text-warning">Menunggu diproses</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Surat Keluar</h6>
                <h2 class="fw-bold">34</h2>
                <small class="text-primary">Bulan ini</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Surat Masuk</h6>
                <h2 class="fw-bold">27</h2>
                <small class="text-secondary">Update harian</small>
            </div>
        </div>

    </div>

    {{-- CHART SECTION --}}
    <div class="row mt-4 g-4">

        {{-- DOKUMEN BULANAN --}}
        <div class="col-md-8">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold">Grafik Dokumen Masuk Per Bulan</h5>
                <canvas id="dokumenChart" height="325"></canvas>
            </div>
        </div>

        {{-- STATUS PERMINTAAN --}}
        <div class="col-md-4">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold">Status Permintaan Dokumen</h5>
                <canvas id="permintaanChart" height="220"></canvas>
            </div>
        </div>

    </div>

    {{-- PROGRES ADMINISTRASI --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold">Progres Administrasi Mingguan</h5>
                <canvas id="progresChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- TABEL DOKUMEN TERBARU --}}
    <div class="row mt-4 g-4">

        <div class="col-md-8">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Dokumen Terbaru</h5>

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Dokumen</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Surat Keterangan Aktif</td>
                            <td>Surat Masuk</td>
                            <td>12 Nov 2025</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                        <tr>
                            <td>Permohonan Legalitas</td>
                            <td>Permintaan</td>
                            <td>10 Nov 2025</td>
                            <td><span class="badge bg-warning text-dark">Diproses</span></td>
                        </tr>
                        <tr>
                            <td>Laporan Pengarsipan</td>
                            <td>Dokumen</td>
                            <td>07 Nov 2025</td>
                            <td><span class="badge bg-primary">Baru</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PENGUMUMAN TU --}}
        <div class="col-md-4">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Pengumuman TU</h5>

                <div class="alert alert-info mb-2">
                    <strong>Info:</strong> Deadline pengarsipan semester 20 November.
                </div>
                <div class="alert alert-danger mb-2">
                    <strong>Penting:</strong> Sistem TU maintenance 22 November.
                </div>
                <div class="alert alert-success mb-2">
                    <strong>Selesai:</strong> Update data siswa berhasil dilakukan.
                </div>
            </div>
        </div>

    </div>

    {{-- RIWAYAT AKTIVITAS --}}
    <div class="row mt-4 mb-5">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Riwayat Aktivitas</h5>

                <ul class="list-group">
                    <li class="list-group-item">
                        ✔ Memproses 3 permintaan dokumen — <span class="text-muted">2 jam lalu</span>
                    </li>
                    <li class="list-group-item">
                        ✔ Mengarsip 5 surat masuk — <span class="text-muted">Kemarin</span>
                    </li>
                    <li class="list-group-item">
                        ✔ Mengupdate data pegawai — <span class="text-muted">3 hari lalu</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- CHART.JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ====== 1. Chart Dokumen Bulanan ======
    new Chart(document.getElementById('dokumenChart'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Dokumen Masuk',
                data: [40, 55, 48, 65, 70, 60],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderRadius: 12,
                borderWidth: 2
            }]
        }
    });

    // ====== 2. Chart Status Permintaan ======
    new Chart(document.getElementById('permintaanChart'), {
        type: 'doughnut',
        data: {
            labels: ['Diproses', 'Selesai', 'Pending'],
            datasets: [{
                data: [8, 34, 5],
                backgroundColor: [
                    'rgba(255, 205, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ]
            }]
        }
    });

    // ====== 3. Chart Progres Administrasi ======
    new Chart(document.getElementById('progresChart'), {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: 'Progres',
                data: [30, 45, 60, 75, 90],
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.3)',
                borderWidth: 3,
                tension: 0.3,
                fill: true
            }]
        }
    });
</script>

@endsection
