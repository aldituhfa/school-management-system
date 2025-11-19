@extends('layouts.payroll')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard Payroll</h2>
        <p class="text-muted">Selamat datang di dashboard Payroll. Anda dapat mengelola data gaji dan slip pegawai.</p>
    </div>

    <!-- TOP CARDS -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total Pegawai</h6>
                <h2 class="fw-bold">45</h2>
                <p class="text-success small mb-0">+3 pegawai baru</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Slip Gaji Bulan Ini</h6>
                <h2 class="fw-bold">45</h2>
                <p class="text-primary small mb-0">Semua pegawai sudah dibuatkan slip</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Total Pengeluaran Gaji</h6>
                <h2 class="fw-bold">Rp 98.200.000</h2>
                <p class="text-danger small mb-0">+12% dari bulan lalu</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <h6 class="text-muted">Potongan & Tunjangan</h6>
                <h2 class="fw-bold">Rp 14.750.000</h2>
                <p class="text-info small mb-0">detail bulan ini</p>
            </div>
        </div>

    </div>

    <!-- CHART AREA -->
    <div class="row g-4">

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">Grafik Pengeluaran Gaji (Per Bulan)</h5>
                <canvas id="salaryChart" height="328"></canvas>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">Tunjangan vs Potongan</h5>
                <canvas id="pieChart" height="180"></canvas>
            </div>
        </div>

    </div>

    <!-- PROGRESS SECTION -->
    <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
        <h5 class="fw-bold mb-3">Status Anggaran Payroll Tahun Ini</h5>

        <p class="fw-semibold mb-1">98.2 Juta / 200 Juta</p>
        <div class="progress" style="height: 12px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 49%;"></div>
        </div>
        <small class="text-muted">49% dari anggaran telah terpakai</small>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Salary Line Chart
    const ctx = document.getElementById('salaryChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            datasets: [{
                label: 'Pengeluaran Gaji',
                data: [82, 85, 88, 90, 93, 95, 97, 98, 99, 100, 101, 102],
                borderColor: "#0d6efd",
                borderWidth: 3,
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: false },
            }
        }
    });

    // Pie Chart
    const ctx2 = document.getElementById('pieChart');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ["Tunjangan", "Potongan"],
            datasets: [{
                data: [10500000, 4200000],
                backgroundColor: ["#198754", "#dc3545"]
            }]
        }
    });
</script>

@endsection
