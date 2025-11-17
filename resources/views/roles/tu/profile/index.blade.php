@extends('layouts.tu')

@section('title', '')

@section('content')
<div class="page-body"> 
    <div class="container-xl" style="max-width: 1000px;">

        {{-- ===== SWEETALERT2 ===== --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `{{ session('success') }}`,
                        confirmButtonText: 'Oke',
                        confirmButtonColor: '#3085d6',
                        background: '#f8fbff',
                        color: '#1e3a8a',
                        width: '400px',
                        customClass: { popup: 'shadow-lg rounded-4' }
                    });
                });
            </script>
        @endif

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: `{{ session('error') }}`,
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#d33',
                        background: '#fff5f5',
                        color: '#991b1b',
                        width: '400px',
                        customClass: { popup: 'shadow-lg rounded-4' }
                    });
                });
            </script>
        @endif

        {{-- ===== HEADER PROFIL ===== --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0 position-relative">

                {{-- Background banner --}}
                <div class="bg-primary position-relative" style="height: 120px; border-top-left-radius: .5rem; border-top-right-radius: .5rem;"></div>

                {{-- Form Profil --}}
                <form id="formProfile" action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center position-relative" style="margin-top: -60px;">
                        <div class="position-relative d-inline-block">
                            @if($user->profile_photo)
                                <img id="profilePreview"
                                    src="{{ asset('storage/' . $user->profile_photo) }}"
                                    alt="Foto Profil"
                                    class="rounded-circle border border-2 border-white shadow"
                                    width="115" height="115"
                                    style="object-fit: cover;">
                            @else
                               <div id="profilePreview" class="rounded-circle bg-primary text-white border border-2 border-white shadow d-flex align-items-center justify-content-center"
                                    style="width:115px; height:115px; font-size:36px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                            @endif
                            <label for="profile_photo"
                                class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm"
                                style="cursor:pointer; font-size: 14px;">
                                <i class="ti ti-camera"></i>
                            </label>
                        </div>
                        <input id="profile_photo" type="file" name="profile_photo" class="d-none" accept="image/*">
                        <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
                        <p class="text-muted mb-2" style="font-size: 15px;">{{ $user->email }}</p>
                    </div>

                    {{-- ===== FORM INFORMASI AKUN ===== --}}
                    <div class="card shadow-sm border-0 rounded-3 mt-3">
                        <div class="card-header bg-white border-bottom-0 pb-0">
                            <h5 class="card-title text-primary mb-2" style="font-size: 17px;">
                                <i class="ti ti-user-cog me-1"></i> Informasi Akun
                            </h5>
                        </div>

                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 15px;">
                                        <i class="ti ti-id me-1"></i> Nama Lengkap
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                           class="form-control form-control-sm" placeholder="Masukkan nama lengkap" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold" style="font-size: 15px;">
                                        <i class="ti ti-mail me-1"></i> Email
                                    </label>
                                    <input type="email" value="{{ $user->email }}"
                                           class="form-control form-control-sm bg-light" disabled>
                                </div>
                            </div>

                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 15px;">
                                        <i class="ti ti-lock me-1"></i> Password
                                    </label>
                                    <input type="password" value="********" class="form-control form-control-sm bg-light" disabled>
                                    <small class="text-muted" style="font-size: 13px;">Hubungi Admin untuk mengganti password.</small>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm"
                                        style="background: linear-gradient(90deg, #206bc4, #0054a6); font-size: 15px;">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- ===== FOOTER INFO ===== --}}
                <div class="text-center mt-3 text-muted small">
                    <i class="ti ti-clock me-1"></i> 
                    Terakhir diperbarui: {{ $user->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ===== SCRIPT: PREVIEW FOTO & VALIDASI ===== --}}
<script>
    const inputPhoto = document.getElementById('profile_photo');
    let preview = document.getElementById('profilePreview');

    inputPhoto.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        if (file && !allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'File Tidak Valid',
                text: 'Harap unggah file gambar dengan format JPG, PNG, atau GIF.',
                confirmButtonColor: '#d33',
                background: '#fff5f5',
                color: '#991b1b'
            });
            inputPhoto.value = '';
            return;
        }

        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                // Jika preview bukan img (artinya awalnya inisial)
                if (preview.tagName.toLowerCase() !== 'img') {
                    const img = document.createElement('img');
                    img.id = 'profilePreview';
                    img.className = 'rounded-circle border border-2 border-white shadow';
                    img.width = 115;
                    img.height = 115;
                    img.style.objectFit = 'cover';
                    preview.parentNode.replaceChild(img, preview);
                    preview = img; // update ke elemen baru
                }

                // Tampilkan gambar preview
                preview.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
