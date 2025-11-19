@extends('layouts.guru')

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

    <h3 class="mb-1 fw-bold">Dashboard Guru</h3>
    <p class="text-muted">Selamat datang kembali. Berikut ringkasan aktivitas Anda.</p>

    {{-- STATISTIC CARDS --}}
    <div class="row g-3 mt-3">

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Mata Pelajaran</h6>
                <h2 class="fw-bold">8</h2>
                <small class="text-success">+2 mapel baru</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Siswa Bimbingan</h6>
                <h2 class="fw-bold">120</h2>
                <small class="text-primary">Stable</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Total Jam Mengajar / Minggu</h6>
                <h2 class="fw-bold">18 Jam</h2>
                <small class="text-warning">Termasuk kelas tambahan</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card shadow-sm">
                <h6 class="text-muted">Gaji Bulan Ini</h6>
                <h2 class="fw-bold">Rp 4.850.000</h2>
                <small class="text-success">+5% dari bulan lalu</small>
            </div>
        </div>

    </div>

    {{-- CHART SECTION --}}
    <div class="row mt-4 g-4">

        {{-- Gaji Per Bulan --}}
        <div class="col-md-8">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold">Grafik Gaji Per Bulan</h5>
                <canvas id="gajiChart" height="300"></canvas>
            </div>
        </div>

        {{-- Mapel Diagram --}}
        <div class="col-md-4">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Distribusi Mata Pelajaran</h5>
                <canvas id="mapelChart" height="220"></canvas>
            </div>
        </div>

    </div>

    {{-- JADWAL MENGAJAR --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Jadwal Mengajar Minggu Ini</h5>
                <canvas id="jadwalChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- BAGIAN BARU – MENUTUP AREA KOSONG --}}
    <div class="row mt-4 g-4">

        {{-- TABEL JADWAL MENGAJAR --}}
        <div class="col-md-8">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Jadwal Mengajar Detail</h5>

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hari</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Senin</td>
                            <td>Pemrograman Web</td>
                            <td>X RPL 3</td>
                            <td>07:00 - 09:00</td>
                        </tr>
                        <tr>
                            <td>Selasa</td>
                            <td>Basis Data</td>
                            <td>XI RPL 2</td>
                            <td>08:00 - 10:00</td>
                        </tr>
                        <tr>
                            <td>Rabu</td>
                            <td>PBO</td>
                            <td>XII RPL 3</td>
                            <td>09:00 - 12:00</td>
                        </tr>
                        <tr>
                            <td>Kamis</td>
                            <td>PKK</td>
                            <td>XI RPL 3</td>
                            <td>10:00 - 12:00</td>
                        </tr>
                        <tr>
                            <td>Jumat</td>
                            <td>PWPB</td>
                            <td>X RPL 1</td>
                            <td>07:00 - 09:00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- NOTIFIKASI / PENGUMUMAN --}}
        <div class="col-md-4">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Pengumuman Untuk Guru</h5>

                <div class="alert alert-info mb-2">
                    <strong>Info:</strong> Pengumpulan nilai akhir maksimal tanggal 28.
                </div>
                <div class="alert alert-warning mb-2">
                    <strong>Reminder:</strong> Rapat wali kelas hari Jumat.
                </div>
                <div class="alert alert-success mb-2">
                    <strong>Selamat!</strong> Anda mendapat rating mengajar 4.8 minggu ini.
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN RIWAYAT AKTIVITAS --}}
    <div class="row mt-4 mb-5">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 chart-card">
                <h5 class="fw-bold mb-3">Riwayat Aktivitas</h5>

                <ul class="list-group">
                    <li class="list-group-item">
                        ✔ Menginput nilai siswa — <span class="text-muted">2 jam lalu</span>
                    </li>
                    <li class="list-group-item">
                        ✔ Mengisi jadwal mengajar — <span class="text-muted">Kemarin</span>
                    </li>
                    <li class="list-group-item">
                        ✔ Mengupdate data kelas — <span class="text-muted">3 hari lalu</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- CHART.JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ====== 1. Chart Gaji ======
    const gajiCtx = document.getElementById('gajiChart');
    new Chart(gajiCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Gaji (Rp)',
                data: [4500000, 4600000, 4550000, 4700000, 4800000, 4850000],
                backgroundColor: ['rgba(54, 162, 235, 0.5)'],
                borderColor: ['rgba(54, 162, 235, 1)'],
                borderWidth: 2,
                borderRadius: 12
            }]
        }
    });

    // ====== 2. Chart Distribusi Mapel Guru ======
    const mapelCtx = document.getElementById('mapelChart');
    new Chart(mapelCtx, {
        type: 'doughnut',
        data: {
            labels: ['RPL', 'Basis Data', 'PBO', 'PWPB', 'PKK'],
            datasets: [{
                data: [3, 2, 1, 1, 1],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
            }]
        }
    });

    // ====== 3. Chart Jadwal Mengajar ======
    const jadwalCtx = document.getElementById('jadwalChart');
    new Chart(jadwalCtx, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: "Jam Mengajar",
                data: [4, 3, 5, 3, 3],
                borderWidth: 3,
                tension: 0.3,
                borderColor: 'rgba(255, 159, 64,1)',
                backgroundColor: 'rgba(255, 159, 64,0.3)',
                fill: true
            }]
        }
    });
</script>

@endsection
