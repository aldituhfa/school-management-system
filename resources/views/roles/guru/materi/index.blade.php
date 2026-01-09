@extends('layouts.guru')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Step Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center position-relative">
                <!-- Progress Line -->
                <div class="position-absolute w-100" style="height: 2px; background: #e0e0e0; top: 15px; z-index: 0;"></div>
                
                <!-- Step 1 -->
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle active mx-auto">1</div>
                    <small class="d-block mt-2 fw-semibold text-primary">Pilih Jadwal</small>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle mx-auto">2</div>
                    <small class="d-block mt-2 text-muted">Upload Materi</small>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle mx-auto">3</div>
                    <small class="d-block mt-2 text-muted">Selesai</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Pilih Jadwal Pembelajaran -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Pilih Jadwal Pembelajaran</h5>
                <div style="max-width: 300px;">
                    <input type="text" class="form-control" id="searchJadwal" placeholder="Cari jadwal...">
                </div>
            </div>

            <div class="row g-3" id="jadwalContainer">
                @forelse($jadwals as $jadwal)
                <div class="col-md-6 col-lg-4 jadwal-item">
                    <div class="card jadwal-card h-100 border" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 fw-bold">
                                    {{ $jadwal->mataPelajaran?->nama_mata_pelajaran ?? 'Mapel belum diatur' }}
                                </h6>
                                <span class="badge bg-primary">
                                    {{ $jadwal->hari ?? 'Senin' }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">{{ $jadwal->kelas->nama_kelas }}</p>
                            
                            <div class="mt-3">
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-book me-2"></i>
                                    <span>
                                    {{ $jadwal->keterangan 
                                        ?? ($jadwal->mataPelajaran?->nama_mata_pelajaran ?? '-') }}
                                </span>
                                </div>
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="bi bi-clock me-2"></i>
                                    <span>
                                        {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <span>{{ $jadwal->keterangan ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 pt-0">
                            <a href="{{ route('guru.materi.create', ['jadwal_id' => $jadwal->id]) }}" 
                               class="btn btn-primary btn-sm w-100">
                                Pilih Jadwal
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Tidak ada jadwal tersedia</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Materi yang Sudah Diupload -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-3">Materi yang Sudah Diupload</h5>
            
            @if($materis->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada materi yang diupload untuk jadwal ini</p>
            </div>
            @else
                @foreach($materis as $jadwalId => $materiList)
                <div class="mb-4">
                   <h6 class="fw-bold mb-3">
                        {{ $materiList->first()->jadwal->mataPelajaran?->nama_mata_pelajaran ?? '-' }}
                        -
                        {{ $materiList->first()->jadwal->kelas?->nama_kelas ?? '-' }}
                    </h6>
                    
                    @foreach($materiList as $materi)
                    <div class="card mb-2 border">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-light rounded p-2">
                                        @if(str_contains($materi->file_name, '.pdf'))
                                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 1.5rem;"></i>
                                        @elseif(str_contains($materi->file_name, '.doc'))
                                            <i class="bi bi-file-earmark-word text-primary" style="font-size: 1.5rem;"></i>
                                        @elseif(str_contains($materi->file_name, '.ppt'))
                                            <i class="bi bi-file-earmark-ppt text-warning" style="font-size: 1.5rem;"></i>
                                        @else
                                            <i class="bi bi-file-earmark text-secondary" style="font-size: 1.5rem;"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="mb-1">{{ $materi->judul }}</h6>
                                    <small class="text-muted">
                                        {{ $materi->file_name }} • {{ $materi->file_size_formatted }} • 
                                        {{ $materi->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('guru.materi.download', $materi->id) }}" 
                                       class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="deleteMateri({{ $materi->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.step-circle.active {
    background: #0d6efd;
    color: white;
}

.jadwal-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.jadwal-card {
    transition: all 0.3s ease;
}
</style>

<script>
// Search Jadwal
document.getElementById('searchJadwal').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const jadwalItems = document.querySelectorAll('.jadwal-item');
    
    jadwalItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Delete Materi
function deleteMateri(id) {
    if (confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = '/guru/materi/' + id;
        form.submit();
    }
}
</script>
@endsection