@extends('layouts.superadmin')

@section('content')
<div class="page-wrapper">
    <div class="container-xl">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                     Jadwal Pelajaran
                    </h2>
                    <div class="text-muted mt-1">
                        Kelola jadwal pembelajaran sekolah
                    </div>
                </div>
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="ti ti-plus me-1"></i> Tambah Jadwal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter Data</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('jadwal.index') }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Kelas</label>
                                        <select name="kelas_id" class="form-select">
                                            <option value="">-- Semua Kelas --</option>
                                            @foreach($kelasList as $kelas)
                                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                    {{ $kelas->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Guru</label>
                                        <select name="guru_id" class="form-select">
                                            <option value="">-- Semua Guru --</option>
                                            @foreach($guruList as $guru)
                                                <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                                                    {{ $guru->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Hari</label>
                                        <select name="hari" class="form-select">
                                            <option value="">-- Semua Hari --</option>
                                            @foreach($hari as $h)
                                                <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>
                                                    {{ $h }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                                        <button type="submit" class="btn btn-info w-50">
                                            <i class="ti ti-filter me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('jadwal.index') }}" class="btn btn-outline-primary w-50">
                                            <i class="ti ti-refresh me-1"></i> Reset
                                        </a>

                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center w-1">No</th>
                                            <th>Hari</th>
                                            <th class="text-center">Jam Ke</th>
                                            <th>Waktu</th>
                                            <th>Kelas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                            <th>Keterangan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jadwal as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $item->hari }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $item->jam_ke }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-fill">
                                                        <div class="font-weight-medium">{{ $item->kelas->nama_kelas ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted">
                                                {{ $item->mataPelajaran->nama_mata_pelajaran ?? '-' }}
                                            </td>
                                            <td class="text-muted">
                                                {{ $item->guru->name ?? '-' }}
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $item->keterangan ?? '-' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-list flex-nowrap">
                                                    <button type="button" 
                                                            class="btn btn-outline-warning btn-sm edit-btn"
                                                            data-id="{{ $item->id }}"
                                                            data-kelas="{{ $item->kelas_id }}"
                                                            data-guru="{{ $item->guru_id }}"
                                                            data-mapel="{{ $item->mata_pelajaran_id }}"
                                                            data-hari="{{ $item->hari }}"
                                                            data-jam="{{ $item->jam_ke }}"
                                                            data-mulai="{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}"
                                                            data-selesai="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                                            data-keterangan="{{ $item->keterangan }}">
                                                        <i class="ti ti-edit"></i>Edit
                                                    </button>
                                                    
                                                    <form action="{{ route('jadwal.destroy', $item->id) }}" 
                                                          method="POST" 
                                                          class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-outline-danger btn-sm delete-btn">
                                                            <i class="ti ti-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="empty">
                                                    <div class="empty-img">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3 -3v-1" />
                                                            <path d="M5 8v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            <path d="M9 5h6" />
                                                            <path d="M15 8v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            <path d="M19 5h4" />
                                                        </svg>
                                                    </div>
                                                    <p class="empty-title">Tidak ada data jadwal</p>
                                                    <p class="empty-subtitle text-muted">
                                                        Tambah jadwal baru dengan mengklik tombol "Tambah Jadwal"
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal modal-blur fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('jadwal.store') }}" method="POST" id="createForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-calendar-plus me-2"></i> Tambah Jadwal Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Kelas Selection -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Kelas</label>
                            <select name="kelas_id" id="create_kelas_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hari Selection -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Hari</label>
                            <select name="hari" id="create_hari" class="form-select" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($hari as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jam Ke (Auto) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Jam Ke</label>
                            <input type="number" name="jam_ke" id="create_jam_ke" class="form-control" min="1" readonly required>
                            <small class="text-muted">Otomatis terisi berdasarkan jadwal yang ada</small>
                        </div>
                    </div>

                    <!-- Info Jam Belajar Kelas -->
                    <div id="jamBelajarInfo" class="alert alert-info d-none mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="ti ti-info-circle icon"></i>
                            </div>
                            <div class="flex-fill">
                                <h4 class="alert-title">Informasi Jam Belajar Kelas</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <strong>Jam Belajar:</strong> <span id="info_jam_belajar">-</span>
                                        </div>
                                        <div class="mb-1">
                                            <strong>Istirahat 1:</strong> <span id="info_istirahat1">-</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <strong>Total Jam:</strong> <span id="info_total_jam">-</span>
                                        </div>
                                        <div class="mb-1">
                                            <strong>Istirahat 2:</strong> <span id="info_istirahat2">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Guru Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Guru</label>
                            <select name="guru_id" id="create_guru_id" class="form-select" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mata Pelajaran (Dynamic based on Guru) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="create_mata_pelajaran_id" class="form-select" required disabled>
                                <option value="">-- Pilih Guru Terlebih Dahulu --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Waktu Mulai -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="create_waktu_mulai" class="form-control" required>
                            <small class="text-muted">Harus dalam rentang jam belajar kelas</small>
                        </div>

                        <!-- Waktu Selesai -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" id="create_waktu_selesai" class="form-control" required>
                            <small class="text-muted">Tidak boleh bentrok dengan waktu istirahat</small>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Ruang Lab Komputer"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal modal-blur fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-edit me-2"></i> Edit Jadwal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Kelas</label>
                            <select name="kelas_id" id="edit_kelas_id" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Hari</label>
                            <select name="hari" id="edit_hari" class="form-select" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($hari as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Jam Ke</label>
                            <input type="number" name="jam_ke" id="edit_jam_ke" class="form-control" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Guru</label>
                            <select name="guru_id" id="edit_guru_id" class="form-select" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guruList as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="edit_mata_pelajaran_id" class="form-select" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="edit_waktu_mulai" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" id="edit_waktu_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-device-floppy me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tabler Icons Configuration
    const iconConfig = {
        success: 'ti ti-circle-check',
        error: 'ti ti-circle-x',
        warning: 'ti ti-alert-circle',
        info: 'ti ti-info-circle'
    };

    // CREATE MODAL - Load Mata Pelajaran when Guru is selected
    document.getElementById('create_guru_id').addEventListener('change', function() {
        const guruId = this.value;
        const mapelSelect = document.getElementById('create_mata_pelajaran_id');

        if (!guruId) {
            mapelSelect.innerHTML = '<option value="">-- Pilih Guru Terlebih Dahulu --</option>';
            mapelSelect.disabled = true;
            return;
        }

        // Fetch mata pelajaran by guru
        fetch(`{{ route('jadwal.index') }}/ajax/mapel-guru/${guruId}`)
            .then(response => response.json())
            .then(data => {
                mapelSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                
                if (data.success && data.data.length > 0) {
                    data.data.forEach(mapel => {
                        mapelSelect.innerHTML += `<option value="${mapel.id}">${mapel.nama_mata_pelajaran}</option>`;
                    });
                    mapelSelect.disabled = false;
                } else {
                    mapelSelect.innerHTML = '<option value="">Guru ini belum memiliki mata pelajaran</option>';
                    mapelSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Gagal memuat data mata pelajaran');
            });
    });

    // CREATE MODAL - Load Jam Belajar Info when Kelas is selected
    document.getElementById('create_kelas_id').addEventListener('change', function() {
        loadJamBelajarInfo(this.value);
        autoFillJamKe();
    });

    // CREATE MODAL - Auto fill Jam Ke when Hari is selected
    document.getElementById('create_hari').addEventListener('change', function() {
        autoFillJamKe();
    });

    function loadJamBelajarInfo(kelasId) {
        if (!kelasId) {
            document.getElementById('jamBelajarInfo').classList.add('d-none');
            return;
        }

        fetch(`{{ route('jadwal.index') }}/ajax/jam-belajar/${kelasId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const jb = data.data;
                    document.getElementById('info_jam_belajar').textContent = `${jb.jam_mulai} - ${jb.jam_selesai}`;
                    document.getElementById('info_total_jam').textContent = `${jb.total_jam_belajar} Jam`;
                    document.getElementById('info_istirahat1').textContent = jb.waktu_istirahat_mulai ? `${jb.waktu_istirahat_mulai} - ${jb.waktu_istirahat_selesai}` : '-';
                    document.getElementById('info_istirahat2').textContent = jb.waktu_istirahat2_mulai ? `${jb.waktu_istirahat2_mulai} - ${jb.waktu_istirahat2_selesai}` : '-';
                    document.getElementById('jamBelajarInfo').classList.remove('d-none');
                } else {
                    showToast('warning', data.message || 'Informasi tidak ditemukan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function autoFillJamKe() {
        const kelasId = document.getElementById('create_kelas_id').value;
        const hari = document.getElementById('create_hari').value;

        if (!kelasId || !hari) {
            return;
        }

        fetch(`{{ route('jadwal.index') }}/ajax/next-jam?kelas_id=${kelasId}&hari=${hari}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('create_jam_ke').value = data.jam_ke;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // EDIT MODAL - Load Mata Pelajaran when Guru is changed
    document.getElementById('edit_guru_id').addEventListener('change', function() {
        const guruId = this.value;
        const mapelSelect = document.getElementById('edit_mata_pelajaran_id');

        if (!guruId) {
            mapelSelect.innerHTML = '<option value="">-- Pilih Guru Terlebih Dahulu --</option>';
            return;
        }

        fetch(`{{ route('jadwal.index') }}/ajax/mapel-guru/${guruId}`)
            .then(response => response.json())
            .then(data => {
                mapelSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                
                if (data.success && data.data.length > 0) {
                    data.data.forEach(mapel => {
                        mapelSelect.innerHTML += `<option value="${mapel.id}">${mapel.nama_mata_pelajaran}</option>`;
                    });
                }
            });
    });

    // EDIT Button Handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const guruId = this.dataset.guru;
            
            document.getElementById('editForm').action = `{{ route('jadwal.index') }}/${id}`;
            document.getElementById('edit_kelas_id').value = this.dataset.kelas;
            document.getElementById('edit_hari').value = this.dataset.hari;
            document.getElementById('edit_jam_ke').value = this.dataset.jam;
            document.getElementById('edit_waktu_mulai').value = this.dataset.mulai;
            document.getElementById('edit_waktu_selesai').value = this.dataset.selesai;
            document.getElementById('edit_keterangan').value = this.dataset.keterangan || '';
            
            // Set guru first
            document.getElementById('edit_guru_id').value = guruId;
            
            // Then load mapel and set selected
            fetch(`{{ route('jadwal.index') }}/ajax/mapel-guru/${guruId}`)
                .then(response => response.json())
                .then(data => {
                    const mapelSelect = document.getElementById('edit_mata_pelajaran_id');
                    mapelSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                    
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(mapel => {
                            mapelSelect.innerHTML += `<option value="${mapel.id}">${mapel.nama_mata_pelajaran}</option>`;
                        });
                        mapelSelect.value = btn.dataset.mapel;
                    }
                });
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // DELETE Confirmation
    document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.delete-form');

        Swal.fire({
            icon: 'warning',
            title: 'Yakin ingin menghapus?',
            html: '<span style="color:#6c757d">Data tidak dapat dikembalikan!</span>',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3',
                icon: 'border-warning text-warning',
                confirmButton: 'btn btn-danger px-4',
                cancelButton: 'btn btn-secondary px-4 me-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

    // Custom Toast Function with Tabler Icons
    function showToast(type, message) {
        const icon = iconConfig[type] || iconConfig.info;
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: type,
            title: message,
            customClass: {
                container: 'swal2-tabler'
            }
        });
    }

    // Alerts
    @if(session('success'))
        showToast('success', '{{ session("success") }}');
    @endif

    @if(session('error'))
        showToast('error', '{{ session("error") }}');
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal!',
            html: `
                <div class="text-start">
                    <ul class="list-unstyled mb-0">
                        @foreach($errors->all() as $error)
                            <li class="mb-1">
                                <span class="badge bg-danger-lt">!</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    @endif
</script>
@endpush

@push('styles')
<style>
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.85em;
    }
    
    .btn-icon {
        width: 2rem;
        height: 2rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .table-vcenter td, .table-vcenter th {
        vertical-align: middle;
    }
    
    .empty {
        padding: 2rem 0;
    }
    
    .empty-img {
        height: 8rem;
        margin-bottom: 1rem;
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .swal2-tabler .swal2-icon {
        border-color: transparent;
    }
</styl>
@endpush