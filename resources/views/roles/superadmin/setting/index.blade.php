@extends('layouts.superadmin')

@section('title', '')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Alert sukses pakai SweetAlert2 --}}
        @if(session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `{{ session('success') }}`,
                        showConfirmButton: false,   
                        timer: 2000,
                        timerProgressBar: true,
                        background: '#f0f8ff',
                        color: '#206bc4'
                    });
                });
            </script>
        @endif

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-4">
            <div class="card-header bg-primary bg-gradient text-white py-3 px-4">
                <h3 class="card-title mb-0">Pengaturan Logo</h3>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('superadmin.setting.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4 align-items-start">

                        {{-- Nama Logo --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Nama Logo</label>
                            <input 
                                type="text" 
                                name="logo_name" 
                                class="form-control form-control-lg rounded-3 border-0 shadow-sm" 
                                placeholder="Masukkan nama logo sekolah..." 
                                value="{{ old('logo_name', $setting->logo_name ?? '') }}">
                            <small class="text-muted mt-1 d-block">Contoh: “SMK Taruna Bhakti”.</small>
                        </div>

                        {{-- Upload Logo --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">Logo Sekolah</label>
                            <div class="d-flex flex-column align-items-start">
                                <div class="position-relative mb-3">
                                    <img 
                                        id="logoPreview" 
                                        src="{{ isset($setting->logo) ? asset('storage/' . $setting->logo) : 'https://via.placeholder.com/200x200?text=No+Logo' }}" 
                                        alt="Logo Preview" 
                                        class="img-thumbnail rounded-3 shadow-sm border-0 bg-white" 
                                        width="200"
                                        style="transition: all .3s ease;">
                                </div>

                                <input type="file" name="logo" id="logoInput" class="form-control form-control-lg rounded-3 border-0 shadow-sm" onchange="validateAndPreviewLogo(event)">
                                <small class="text-muted mt-2">Format: JPG, PNG, atau SVG &middot; Maks: 5MB</small>

                                @if(isset($setting->logo))
                                    <button type="submit" name="delete_logo" value="1" class="btn btn-outline-danger btn-sm mt-3">
                                        Hapus Logo
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm"
                            style="background: linear-gradient(90deg, #206bc4, #0054a6); font-size: 15px;">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- JS: Validasi dan Preview Logo --}}
<script>
    function validateAndPreviewLogo(event) {
        const file = event.target.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        // Jika format tidak valid
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format tidak valid!',
                text: 'Silakan pilih file dengan format JPG, PNG, atau SVG.',
                confirmButtonColor: '#206bc4',
                confirmButtonText: 'OK'
            });
            event.target.value = ''; // reset input
            return;
        }

        // Jika ukuran terlalu besar
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'warning',
                title: 'Ukuran file terlalu besar!',
                text: 'Maksimal ukuran logo adalah 2MB.',
                confirmButtonColor: '#206bc4',
                confirmButtonText: 'OK'
            });
            event.target.value = ''; // reset input
            return;
        }

        // Preview jika valid
        const preview = document.getElementById('logoPreview');
        preview.src = URL.createObjectURL(file);
        preview.classList.add('shadow-lg');
    }
</script>
@endsection
