@extends('layouts.tu')

@section('content')
<div class="container mt-4">
    <h2>Tambah Pembayaran SPP</h2>
    <a href="{{ route('tu.spp.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tu.spp.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="student_name" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="student_name" name="student_name" required>
        </div>
        <div class="mb-3">
            <label for="student_identifier" class="form-label">NIS (Opsional)</label>
            <input type="text" class="form-control" id="student_identifier" name="student_identifier">
        </div>
        <div class="mb-3">
            <label for="month" class="form-label">Bulan</label>
            <select class="form-select" id="month" name="month" required>
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <input type="hidden" name="year" value="{{ date('Y') }}">
        <input type="hidden" name="amount" value="200000">
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
