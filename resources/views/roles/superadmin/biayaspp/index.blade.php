@extends('layouts.superadmin')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Header --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Manajemen Biaya SPP</h2>
                    <div class="text-muted mt-1">Kelola data biaya SPP sekolah</div>
                </div>
                <div class="col-auto ms-auto">
                    {{-- Tombol Utama Tambah Biaya SPP --}}
                    <a href="#" class="btn btn-primary me-2" data-bs-toggle="collapse" data-bs-target="#formTambahSPP">
                        <i class="ti ti-plus me-2"></i>Tambah Biaya SPP
                    </a>

                    {{-- Tombol CRUD Tahun Ajaran --}}
                    <button class="btn btn-outline-primary me-2" type="button" data-bs-toggle="modal" data-bs-target="#tahunModal">
                        <i class="ti ti-calendar-plus me-1"></i>Tahun Ajaran
                    </button>

                    {{-- Tombol CRUD Tingkat --}}
                    <button class="btn btn-outline-primary me-2" type="button" data-bs-toggle="modal" data-bs-target="#tingkatModal">
                        <i class="ti ti-stairs-up me-1"></i>Tingkat
                    </button>

                    {{-- Tombol CRUD Status --}}
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="ti ti-circle-plus me-1"></i>Status
                    </button>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div class="d-flex">
                <div>
                    <svg class="icon" width="24" height="24">
                        <use xlink:href="{{ asset('tabler-icons.svg') }}#ti-circle-check" />
                    </svg>
                </div>
                <div class="ms-3">
                    <h4 class="alert-title">Berhasil!</h4>
                    <div class="text-muted">{{ session('success') }}</div>
                </div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
        @endif

        {{-- Form Tambah Biaya SPP --}}
        <div class="collapse mb-4" id="formTambahSPP">
            <div class="card">
                <div class="card-header bg-primary-lt">
                    <h3 class="card-title text-primary">
                        <i class="ti ti-plus me-2"></i>Tambah Biaya SPP Baru
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.biayaspp.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label required">Tahun Ajaran</label>
                                    <select name="tahun_ajaran_id" class="form-select" required>
                                        <option value="">-- Pilih Tahun Ajaran --</option>
                                        @foreach($tahunAjarans->reverse() as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama_tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label required">Tingkat</label>
                                    <select name="tingkat_id" class="form-select" required>
                                        <option value="">-- Pilih Tingkat --</option>
                                        @foreach($tingkats->reverse() as $t)
                                        <option value="{{ $t->id }}">{{ $t->nama_tingkat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <select name="status_id" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        @foreach($statuses->reverse() as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label required">Nominal</label>
                                    <input type="number" name="nominal" class="form-control" placeholder="500000" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table Data Biaya SPP --}}
        <div class="card mb-4">
            <div class="card-header">
                <div class="row align-items-center w-100">
                    <div class="col">
                        <h3 class="card-title mb-0">Daftar Biaya SPP</h3>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex align-items-center gap-2">
                            {{-- Search --}}
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari..." style="width: 200px;">

                            {{-- Filter Status --}}
                            <select id="statusFilter" class="form-select" style="width: 150px;">
                                <option value="">Semua Status</option>
                                @foreach($statuses as $status)
                                <option value="{{ $status->nama_status }}">{{ $status->nama_status }}</option>
                                @endforeach
                            </select>

                            {{-- Reset --}}
                            <button id="resetFilter" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped text-center">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th>Tahun Ajaran</th>
                            <th>Tingkat</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($biayaSpps->reverse() as $i => $item)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>{{ $item->tahunAjaran->nama_tahun }}</td>
                            <td>{{ $item->tingkat->nama_tingkat }}</td>
                            <td class="text-primary fw-bold">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status->nama_status == 'Aktif' ? 'success' : ($item->status->nama_status == 'Nonaktif' ? 'danger' : 'warning') }}-lt status-badge">
                                    {{ $item->status->nama_status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSPPModal{{ $item->id }}">
                                        <i class="ti ti-pencil me-1"></i>Edit
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('superadmin.biayaspp.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="ti ti-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit Biaya SPP --}}
                        <div class="modal modal-blur fade" id="editSPPModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class="ti ti-pencil me-2"></i>Edit Biaya SPP</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('superadmin.biayaspp.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label required">Tahun Ajaran</label>
                                                <select name="tahun_ajaran_id" class="form-select" required>
                                                    @foreach($tahunAjarans->reverse() as $t)
                                                    <option value="{{ $t->id }}" {{ $item->tahun_ajaran_id == $t->id ? 'selected' : '' }}>
                                                        {{ $t->nama_tahun }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required">Tingkat</label>
                                                <select name="tingkat_id" class="form-select" required>
                                                    @foreach($tingkats->reverse() as $t)
                                                    <option value="{{ $t->id }}" {{ $item->tingkat_id == $t->id ? 'selected' : '' }}>
                                                        {{ $t->nama_tingkat }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required">Status</label>
                                                <select name="status_id" class="form-select" required>
                                                    @foreach($statuses->reverse() as $s)
                                                    <option value="{{ $s->id }}" {{ $item->status_id == $s->id ? 'selected' : '' }}>
                                                        {{ $s->nama_status }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label required">Nominal</label>
                                                <input type="number" name="nominal" class="form-control" value="{{ $item->nominal }}" required>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <i class="ti ti-notes" style="font-size: 2rem;"></i>
                                    </div>
                                    <p class="empty-title">Belum ada data</p>
                                    <p class="empty-subtitle text-muted">Data biaya SPP akan muncul di sini setelah ditambahkan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ==================== MODAL TAHUN AJARAN ==================== --}}
        <div class="modal modal-blur fade" id="tahunModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="ti ti-calendar-plus me-2"></i>Tahun Ajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Form Tambah Tahun --}}
                        <form action="{{ route('superadmin.biayaspp.tahun-ajaran.store') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="nama_tahun" class="form-control" placeholder="2025/2026" required>
                                <button class="btn btn-primary" type="submit"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
                            </div>
                        </form>

                        {{-- List Tahun --}}
                        <table class="table table-bordered text-center mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tahunAjarans->reverse() as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <form action="{{ route('superadmin.biayaspp.tahun-ajaran.update', $t->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama_tahun" value="{{ $t->nama_tahun }}" class="form-control text-center" required>
                                            <button class="btn btn-outline-success btn-sm"><i class="ti ti-edit me-1"></i>Update</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('superadmin.biayaspp.tahun-ajaran.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm"><i class="ti ti-trash me-1"></i>Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== MODAL TINGKAT ==================== --}}
        <div class="modal modal-blur fade" id="tingkatModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="ti ti-stairs-up me-2"></i>Tingkat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Form Tambah Tingkat --}}
                        <form action="{{ route('superadmin.biayaspp.tingkat.store') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="nama_tingkat" class="form-control" placeholder="SMA / SMK" required>
                                <button class="btn btn-primary" type="submit"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
                            </div>
                        </form>

                        {{-- List Tingkat --}}
                        <table class="table table-bordered text-center mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tingkat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tingkats->reverse() as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <form action="{{ route('superadmin.biayaspp.tingkat.update', $t->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama_tingkat" value="{{ $t->nama_tingkat }}" class="form-control text-center" required>
                                            <button class="btn btn-outline-success btn-sm"><i class="ti ti-edit me-1"></i>Update</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('superadmin.biayaspp.tingkat.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tingkat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm"><i class="ti ti-trash me-1"></i>Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== MODAL STATUS ==================== --}}
        <div class="modal modal-blur fade" id="statusModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="ti ti-circle-plus me-2"></i>Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Form Tambah Status --}}
                        <form action="{{ route('superadmin.biayaspp.status.store') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="nama_status" class="form-control" placeholder="Aktif / Nonaktif" required>
                                <button class="btn btn-primary" type="submit"><i class="ti ti-device-floppy me-2"></i>Simpan</button>
                            </div>
                        </form>

                        {{-- List Status --}}
                        <table class="table table-bordered text-center mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statuses->reverse() as $i => $s)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <form action="{{ route('superadmin.biayaspp.status.update', $s->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama_status" value="{{ $s->nama_status }}" class="form-control text-center" required>
                                            <button class="btn btn-outline-success btn-sm"><i class="ti ti-edit me-1"></i>Update</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('superadmin.biayaspp.status.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus status ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm"><i class="ti ti-trash me-1"></i>Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetFilter = document.getElementById('resetFilter');
        const tableBody = document.getElementById('tableBody');
        const originalRows = Array.from(tableBody.getElementsByTagName('tr'));

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            originalRows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                if (cells.length === 0) return;

                const tahunAjaran = cells[1].textContent.toLowerCase();
                const tingkat = cells[2].textContent.toLowerCase();
                const nominal = cells[3].textContent.toLowerCase();
                const statusText = cells[4].textContent.trim();

                const matchesSearch = tahunAjaran.includes(searchTerm) ||
                    tingkat.includes(searchTerm) ||
                    nominal.includes(searchTerm);

                const matchesStatus = statusValue === '' || statusText === statusValue;

                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);

        resetFilter.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            filterTable();
        });
    });
</script>
@endsection