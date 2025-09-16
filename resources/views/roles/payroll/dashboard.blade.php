@extends('layouts.payroll')

@section('content')
<div class="container mt-4">
    <h3>Dashboard Payroll</h3>
    <p>Selamat datang di dashboard Payroll. Anda dapat mengelola data gaji dan slip pegawai.</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Total Pegawai</h6>
                <h4>45</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Slip Gaji Bulan Ini</h6>
                <h4>45</h4>
            </div>
        </div>
    </div>
</div>
@endsection
