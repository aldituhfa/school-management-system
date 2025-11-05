@extends('layouts.superadmin')

@section('title', 'Pengaturan')

@section('content')
<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Sistem</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.setting.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Logo</label>
                        <input type="text" name="logo_name" class="form-control" value="{{ old('logo_name', $setting->logo_name ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Logo</label><br>
                        @if(isset($setting->logo))
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="img-thumbnail mb-2" width="120">
                            <div>
                                <button name="delete_logo" value="1" class="btn btn-danger btn-sm">Hapus Logo</button>
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control mt-2">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
