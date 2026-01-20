@extends('layouts.payroll')

@section('content')
<div class="container-xl">
    <h3 class="fw-bold mb-3">Set Gaji: {{ $user->name }}</h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Gagal menyimpan!</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('payroll.data_penggajian.update',$user->id) }}">
        @csrf

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Gaji Pokok</label>
                    <input type="number" name="gaji_pokok" class="form-control"
                        value="{{$user->payrollSetting->gaji_pokok ?? 0 }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Aktif</label>
                    <select name="status_aktif" class="form-select">
                        <option value="1"
                            {{ old('status_aktif', $user->payrollSetting->status_aktif ?? 1) == 1 ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0"
                            {{ old('status_aktif', $user->payrollSetting->status_aktif ?? 1) == 0 ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Penggajian</label>
                    <select name="status_penggajian" class="form-select">
                        <option value="1"
                            {{ old('status_penggajian', $user->payrollSetting->status_penggajian ?? 1) == 1 ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="0"
                            {{ old('status_penggajian', $user->payrollSetting->status_penggajian ?? 1) == 0 ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection