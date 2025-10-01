@extends('layouts.tu')

@section('content')
<div class="container mt-4">
    <h2>Edit Pembayaran SPP</h2>

    <form action="{{ route('tu.spp.update', $spp->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="student_name" class="form-label">Nama Siswa</label>
            <input type="text" name="student_name" class="form-control" 
                   value="{{ old('student_name', $spp->student_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="student_identifier" class="form-label">NIS</label>
            <input type="text" name="student_identifier" class="form-control" 
                   value="{{ old('student_identifier', $spp->student_identifier) }}">
        </div>

        <div class="mb-3">
            <label for="month" class="form-label">Bulan</label>
            <input type="number" name="month" class="form-control" 
                   value="{{ old('month', $spp->month) }}" min="1" max="12" required>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Tahun</label>
            <input type="number" name="year" class="form-control" 
                   value="{{ old('year', $spp->year) }}" min="2000" max="2100" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah</label>
            <input type="number" name="amount" class="form-control" 
                   value="{{ old('amount', $spp->amount) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="unpaid" {{ $spp->status == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="paid" {{ $spp->status == 'paid' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('tu.spp.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
