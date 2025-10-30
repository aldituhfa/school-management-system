@extends('layouts.payroll')

@section('title', 'Profile Payroll')

@section('content')
<div class="page-body">
    <div class="container-xl">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Profil Payroll</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('payroll.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 text-center">
                        <img src="{{ $user->profile_photo_url }}" alt="Foto Profil" class="rounded-circle mb-3" width="120" height="120">
                        <div>
                            <input type="file" name="profile_photo" class="form-control w-50 mx-auto">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (tidak dapat diubah)</label>
                        <input type="email" value="{{ $user->email }}" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password (tidak dapat diubah)</label>
                        <input type="password" value="********" class="form-control" disabled>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
