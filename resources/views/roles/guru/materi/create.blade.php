@extends('layouts.guru')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Step Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center position-relative">
                <div class="position-absolute w-100" style="height: 2px; background: #0d6efd; top: 15px; z-index: 0;"></div>
                
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle completed mx-auto">
                        <i class="bi bi-check"></i>
                    </div>
                    <small class="d-block mt-2 text-success fw-semibold">Pilih Jadwal</small>
                </div>
                
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle active mx-auto">2</div>
                    <small class="d-block mt-2 fw-semibold text-primary">Upload Materi</small>
                </div>
                
                <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                    <div class="step-circle mx-auto">3</div>
                    <small class="d-block mt-2 text-muted">Selesai</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Terpilih -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <span class="fw-semibold">Jadwal Terpilih:</span>
                    </div>
                    <div class="ms-4 mt-1">
                        <span class="me-3">
                            <strong>{{ $jadwal->mataPelajaran->nama_mata_pelajaran }}</strong>
                        </span>
                        <span class="text-muted">|</span>
                        <span class="mx-3">
                            {{ $jadwal->kelas->nama_kelas }}
                        </span>
                        <span class="text-muted">|</span>
                        <span class="mx-3">
                            {{ $jadwal->hari ?? 'Selasa' }}, {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('guru.materi.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Ubah Jadwal
                </a>
            </div>
        </div>
    </div>

    <!-- Form Upload -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-4">Upload Materi Pembelajaran</h5>

            <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

                <!-- Judul Materi -->
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Materi <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" 
                           name="judul" 
                           placeholder="Masukkan judul materi"
                           value="{{ old('judul') }}"
                           required>
                    @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" 
                              name="deskripsi" 
                              rows="4" 
                              placeholder="Masukkan deskripsi materi">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipe Materi -->
                <div class="mb-3">
                    <label for="tipe_materi" class="form-label">Tipe Materi <span class="text-danger">*</span></label>
                    <select class="form-select @error('tipe_materi') is-invalid @enderror" 
                            id="tipe_materi" 
                            name="tipe_materi" 
                            required>
                        <option value="">Pilih Tipe Materi</option>
                        <option value="pdf" {{ old('tipe_materi') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="dokumen" {{ old('tipe_materi') == 'dokumen' ? 'selected' : '' }}>Dokumen (Word, Excel)</option>
                        <option value="presentasi" {{ old('tipe_materi') == 'presentasi' ? 'selected' : '' }}>Presentasi (PPT)</option>
                        <option value="video" {{ old('tipe_materi') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="lainnya" {{ old('tipe_materi') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('tipe_materi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label class="form-label">File Materi <span class="text-danger">*</span></label>
                    <div class="upload-area @error('file') border-danger @enderror" id="uploadArea">
                        <input type="file" 
                               class="d-none" 
                               id="fileInput" 
                               name="file" 
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.avi,.mkv,.jpg,.jpeg,.png"
                               required>
                        
                        <div class="text-center py-5" id="uploadPlaceholder">
                            <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #0d6efd;"></i>
                            <p class="mt-3 mb-1 fw-semibold">Klik atau seret file ke sini</p>
                            <p class="text-muted small mb-0">Mendukung PDF, DOC, DOCX, PPT, PPTX (Maks. 10MB per file)</p>
                        </div>

                        <div class="d-none" id="filePreview">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark text-primary me-3" style="font-size: 2rem;"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold" id="fileName"></p>
                                        <small class="text-muted" id="fileSize"></small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('file')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('guru.materi.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-cloud-upload me-1"></i> Upload Materi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Materi yang Sudah Diupload -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">
            <h5 class="mb-3">Materi yang Sudah Diupload</h5>
            
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada materi yang diupload untuk jadwal ini</p>
            </div>
        </div>
    </div>
</div>

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

.step-circle.completed {
    background: #198754;
    color: white;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.upload-area:hover {
    border-color: #0d6efd;
    background: #f8f9fa;
}

.upload-area.dragover {
    border-color: #0d6efd;
    background: #e7f1ff;
}
</style>

<script>
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const removeFile = document.getElementById('removeFile');

// Click to upload
uploadArea.addEventListener('click', () => {
    fileInput.click();
});

// File change
fileInput.addEventListener('change', handleFile);

// Drag and drop
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFile();
    }
});

// Remove file
removeFile.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.value = '';
    uploadPlaceholder.classList.remove('d-none');
    filePreview.classList.add('d-none');
});

function handleFile() {
    const file = fileInput.files[0];
    if (file) {
        // Validate size
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file maksimal 10MB');
            fileInput.value = '';
            return;
        }
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        uploadPlaceholder.classList.add('d-none');
        filePreview.classList.remove('d-none');
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>
@endsection