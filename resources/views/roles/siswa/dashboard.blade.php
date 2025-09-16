@extends('layouts.siswa')

@section('content')
<div class="container mt-4">
    <h3>Dashboard Siswa</h3>
    <p>Selamat datang di dashboard Siswa. Anda dapat melihat nilai, jadwal, dan informasi sekolah.</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Nilai Semester</h6>
                <h4>12 Mapel</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Pengumuman</h6>
                <h4>3</h4>
            </div>
        </div>
    </div>
</div>
@endsection
