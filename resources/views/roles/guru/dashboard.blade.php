@extends('layouts.guru')

@section('content')
<div class="container mt-4">
    <h3>Dashboard Guru</h3>
    <p>Selamat datang di dashboard Guru. Anda bisa mengelola mata pelajaran dan nilai siswa.</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Mata Pelajaran</h6>
                <h4>8</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h6>Siswa Bimbingan</h6>
                <h4>120</h4>
            </div>
        </div>
    </div>
</div>
@endsection
