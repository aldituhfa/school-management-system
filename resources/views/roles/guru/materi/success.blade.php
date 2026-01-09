@extends('layouts.guru')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Step Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center position-relative">
                <div class="position-absolute w-100" style="height: 2px; background: #198754; top: 15px; z-index: 0;"></div>

                @foreach (['Pilih Jadwal', 'Upload Materi', 'Selesai'] as $step)
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle completed mx-auto">
                        <i class="bi bi-check"></i>
                    </div>
                    <small class="d-block mt-2 text-success fw-semibold">{{ $step }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-check-circle-fill text-success mb-4" style="font-size: 5rem;"></i>

            <h3 class="mb-3">Materi Berhasil Diupload!</h3>
            <p class="text-muted mb-4">
                Materi pembelajaran Anda telah berhasil tersimpan dan dapat diakses oleh siswa.
            </p>

            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('guru.materi.index') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Upload Materi Lagi
                </a>
                <a href="{{ route('guru.materi.list') }}" class="btn btn-outline-primary">
                    <i class="bi bi-list-ul me-1"></i> Lihat Semua Materi
                </a>
            </div>
        </div>
    </div>

    {{-- Detail Materi --}}
    @if(session()->has('materi'))
    @php $materi = session('materi'); @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-3">Detail Materi yang Diupload</h5>

            <div class="card border">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Info -->
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-3">
                                {{ $materi['judul'] ?? '-' }}
                            </h6>

                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Jadwal:</small>
                                <span class="badge bg-primary me-2">
                                    {{ $materi['mapel'] ?? '-' }}
                                </span>
                                <span class="badge bg-secondary">
                                    {{ $materi['kelas'] ?? '-' }}
                                </span>
                            </div>

                            @if(!empty($materi['deskripsi']))
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Deskripsi:</small>
                                <p class="mb-0">{{ $materi['deskripsi'] }}</p>
                            </div>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    Diupload pada {{ $materi['created_at'] ?? '-' }}
                                </small>
                            </div>
                        </div>

                        <!-- File -->
                        <div class="col-md-4 text-end">
                            <div class="bg-light rounded p-3 d-inline-block">
                                @php $file = strtolower($materi['file_name'] ?? ''); @endphp

                                @if(str_ends_with($file, '.pdf'))
                                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 3rem;"></i>
                                @elseif(str_ends_with($file, '.doc') || str_ends_with($file, '.docx'))
                                    <i class="bi bi-file-earmark-word text-primary" style="font-size: 3rem;"></i>
                                @elseif(str_ends_with($file, '.ppt') || str_ends_with($file, '.pptx'))
                                    <i class="bi bi-file-earmark-ppt text-warning" style="font-size: 3rem;"></i>
                                @elseif(str_ends_with($file, '.xls') || str_ends_with($file, '.xlsx'))
                                    <i class="bi bi-file-earmark-excel text-success" style="font-size: 3rem;"></i>
                                @else
                                    <i class="bi bi-file-earmark text-secondary" style="font-size: 3rem;"></i>
                                @endif
                            </div>

                            <div class="mt-2">
                                <small class="text-muted d-block">
                                    {{ $materi['file_name'] ?? '-' }}
                                </small>
                                <small class="text-muted">
                                    {{ $materi['file_size'] ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
.step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #198754;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}
</style>
@endsection
