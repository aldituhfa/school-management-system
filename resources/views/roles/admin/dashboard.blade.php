@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard Admin</h2>
        <p class="text-muted">Selamat datang di dashboard Admin. Silakan kelola data sesuai kebutuhan Anda.</p>
    </div>

    <!-- TOP CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total User</h6>
                <h2 class="fw-bold">120</h2>
                <p class="text-success small mb-0">+10 pengguna baru</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Pengumuman</h6>
                <h2 class="fw-bold">5</h2>
                <p class="text-primary small mb-0">2 pengumuman baru</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Laporan Masuk</h6>
                <h2 class="fw-bold">18</h2>
                <p class="text-danger small mb-0">+3 laporan minggu ini</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Modul Aktif</h6>
                <h2 class="fw-bold">12</h2>
                <p class="text-info small mb-0">modul aktif sekarang</p>
            </div>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="row g-4 mb-4">

        <!-- USER GROWTH -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">Pertumbuhan User (12 Bulan)</h5>
                <canvas id="userChart" height="328"></canvas>
            </div>
        </div>

        <!-- ACTIVITY DISTRIBUTION -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">Distribusi Aktivitas</h5>
                <canvas id="activityPie" height="180"></canvas>
            </div>
        </div>

    </div>

    <!-- ACTIVITY LOG -->
    <div class="card shadow-sm border-0 rounded-4 p-4 mb-5">
        <h5 class="fw-bold mb-4">Aktivitas Admin Terbaru</h5>

        <div class="list-group">

            <!-- ITEM 1 -->
            <div class="list-group-item border-0 d-flex align-items-start">
                <div class="me-3">
                    <span class="badge bg-primary rounded-circle p-3">
                        <i class="bi bi-person-plus"></i>
                    </span>
                </div>
                <div>
                    <h6 class="fw-bold">Menambah User Baru</h6>
                    <p class="text-muted mb-1">Admin menambahkan user <strong>Andri Saputra</strong>.</p>
                    <small class="text-muted">1 jam lalu</small>
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="list-group-item border-0 d-flex align-items-start">
                <div class="me-3">
                    <span class="badge bg-warning rounded-circle p-3">
                        <i class="bi bi-megaphone"></i>
                    </span>
                </div>
                <div>
                    <h6 class="fw-bold">Membuat Pengumuman Baru</h6>
                    <p class="text-muted mb-1">Pengumuman kegiatan sekolah minggu depan telah dibuat.</p>
                    <small class="text-muted">Kemarin</small>
                </div>
            </div>

            <!-- ITEM 3 -->
            <div class="list-group-item border-0 d-flex align-items-start">
                <div class="me-3">
                    <span class="badge bg-success rounded-circle p-3">
                        <i class="bi bi-check-circle"></i>
                    </span>
                </div>
                <div>
                    <h6 class="fw-bold">Verifikasi Laporan Siswa</h6>
                    <p class="text-muted mb-1">Laporan keterlambatan siswa kelas XI RPL telah diverifikasi.</p>
                    <small class="text-muted">2 hari lalu</small>
                </div>
            </div>

            <!-- ITEM 4 -->
            <div class="list-group-item border-0 d-flex align-items-start">
                <div class="me-3">
                    <span class="badge bg-danger rounded-circle p-3">
                        <i class="bi bi-trash"></i>
                    </span>
                </div>
                <div>
                    <h6 class="fw-bold">Menghapus Data User</h6>
                    <p class="text-muted mb-1">1 user nonaktif telah dihapus dari sistem.</p>
                    <small class="text-muted">5 hari lalu</small>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // USER GROWTH CHART
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            datasets: [{
                label: 'Jumlah User',
                data: [90, 92, 94, 96, 100, 103, 106, 110, 113, 117, 118, 120],
                borderColor: "#0d6efd",
                borderWidth: 3,
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: false }
            }
        }
    });

    // ACTIVITY PIE CHART
    const actCtx = document.getElementById('activityPie');
    new Chart(actCtx, {
        type: 'pie',
        data: {
            labels: ["User", "Pengumuman", "Laporan", "Modul"],
            datasets: [{
                data: [45, 20, 18, 12],
                backgroundColor: ["#0d6efd", "#ffc107", "#dc3545", "#20c997"]
            }]
        }
    });
</script>

@endsection
