@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Dashboard Admin</h3>
    <p>Selamat datang di dashboard Admin. Silakan kelola data sesuai kebutuhan Anda.</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Total User</h6>
                <h4>120</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Pengumuman</h6>
                <h4>5</h4>
            </div>
        </div>
    </div>
</div>
@endsection
