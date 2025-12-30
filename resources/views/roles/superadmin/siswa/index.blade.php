@extends('layouts.superadmin')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Terjadi kesalahan:</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header --}}
    <div class="page-header mb-3">
      <div class="row align-items-center">
        <div class="col">
          <h1 class="page-title">Manajemen Data Siswa</h1>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
            <i class="ti ti-plus"></i> Tambah Siswa
          </button>
          <button class="btn btn-outline-secondary ms-2" data-bs-toggle="modal" data-bs-target="#modalKelas">
            <i class="ti ti-building"></i> Kelas
          </button>
          <button class="btn btn-outline-secondary ms-2" data-bs-toggle="modal" data-bs-target="#modalTambahKolom">
            <i class="ti ti-tag"></i> kolom
          </button>
        </div>
      </div>
    </div>

    {{-- Filter dan Search --}}
    <div class="card mb-3">
      <div class="card-body">
        <form id="filterForm" method="GET" action="{{ route('siswa.index') }}" class="row g-2 align-items-end">
          <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, kelas atau NISN...">
          </div>
          <div class="col-md-3">
            <select name="kelas_id" class="form-select">
              <option value="">Semua Kelas</option>
              <option value="belum_kelas" {{ request('kelas_id') == 'belum_kelas' ? 'selected' : '' }}>
                Belum Ada Kelas
              </option>
              @foreach($kelas as $k)
              <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="status_id" class="form-select">
              <option value="">Semua Status</option>
              @foreach($status as $s)
              @if(in_array($s->nama_status, ['Aktif','Nonaktif']))
              <option value="{{ $s->id }}" {{ request('status_id') == $s->id ? 'selected' : '' }}>
                {{ $s->nama_status }}
              </option>
              @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <button type="button" id="btnReset" class="btn btn-outline-secondary w-100">Reset</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Table Data Siswa --}}
    <div class="card">
      <div class="card-body border-bottom py-3">
        <div class="table-responsive">
          <div id="bulkAction" class="mb-3 d-none">
            <button class="btn btn-outline-primary" id="btnBulkKelas">Pilih Kelas</button>
            <button class="btn btn-outline-warning" id="btnBulkEdit">Edit</button>
            <button class="btn btn-outline-danger" id="btnBulkDelete">Hapus</button>
          </div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>
                  <input type="checkbox" id="checkAll">
                </th>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Tempat/Tanggal Lahir</th>
                <th>Agama</th>
                {{-- ✅ Tambahan otomatis untuk kolom baru --}}
                @foreach($columns as $col)
                @if(!in_array($col, ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at']))
                <th>{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                @endif
                @endforeach
                <th>Kelas</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>

            <tbody>
              @forelse($siswa as $index => $item)
              <tr>
                <td td>
                  <input type="checkbox"
                    class="checkItem"
                    data-has-kelas="{{ $item->kelas_id ? 1 : 0 }}"
                    value="{{ $item->id }}">
                </td>
                <td>{{ $siswa->firstItem() + $index }}</td>
                <td><span class="text-muted">{{ $item->nisn }}</span></td>
                <td><strong>{{ $item->nama_siswa }}</strong></td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->tempat_lahir }}, {{ $item->tanggal_lahir->format('d M Y') }}</td>
                <td>{{ $item->agama }}</td>
                {{-- ✅ Isi otomatis kolom tambahan --}}
                @foreach($columns as $col)
                @if(!in_array($col, ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at']))
                <td>{{ $item->$col }}</td>
                @endif
                @endforeach
                <td>
                  @if($item->kelas)
                  <span class="badge bg-blue-lt">{{ $item->kelas->nama_kelas }}</span>
                  @else
                  <span class="badge bg-secondary-lt">Belum ada kelas</span>
                  @endif
                </td>
                <td>
                  @if($item->status && $item->status->nama_status === 'Aktif')
                  <span class="badge bg-success-lt">Aktif</span>

                  @elseif($item->status && $item->status->nama_status === 'Nonaktif')
                  <span class="badge bg-danger-lt">Nonaktif</span>

                  @else
                  <span class="badge bg-secondary-lt">Tidak Valid</span>
                  @endif
                </td>

                <td class="text-center">
                  <button class="btn btn-sm btn-outline-warning btnEdit"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditSiswa"
                    data-id="{{ $item->id }}"
                    data-nisn="{{ $item->nisn }}"
                    data-nama="{{ $item->nama_siswa }}"
                    data-jk="{{ $item->jenis_kelamin }}"
                    data-tempat="{{ $item->tempat_lahir }}"
                    data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('Y-m-d') }}"
                    data-kelas="{{ $item->kelas_id }}"
                    data-agama="{{ $item->agama }}"
                    data-status="{{ $item->status_id }}"
                    @foreach($columns as $col)
                    @if(!in_array($col, ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at']))
                    data-{{ $col }}="{{ $item->$col }}"
                    @endif
                    @endforeach>
                    Edit
                  </button>


                  <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="100%" class="text-center text-muted py-4">
                  <i class="ti ti-info-circle me-2"></i>Belum ada data siswa
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $siswa->links('pagination::bootstrap-5') }}
    </div>

  </div>
</div>

{{-- ================================================== --}}
{{-- MODAL TAMBAH SISWA --}}
<div class="modal fade" id="modalTambahSiswa" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <form id="formTambahSiswa" action="{{ route('siswa.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">NISN</label>
            <input type="text" name="nisn" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Siswa</label>
            <input type="text" name="nama_siswa" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
              <option value="">-- Pilih --</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Agama</label>
            <input type="text" name="agama" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select" required>
              <option value="">-- Pilih Status --</option>
              @foreach($status as $s)
              @if(in_array($s->nama_status, ['Aktif','Nonaktif']))
              <option value="{{ $s->id }}"
                {{ $s->nama_status === 'Aktif' ? 'selected' : '' }}>
                {{ $s->nama_status }}
              </option>
              @endif
              @endforeach
            </select>
          </div>
          {{-- ✅ Input otomatis kolom tambahan --}}
          @php
          $defaultCols = ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at'];
          $customCols = array_diff($columns, $defaultCols);
          @endphp

          @foreach($customCols as $col)
          <div class="col-md-6">
            <label class="form-label">{{ ucfirst(str_replace('_', ' ', $col)) }}</label>
            <input type="text" name="{{ $col }}" class="form-control" placeholder="Masukkan {{ str_replace('_', ' ', $col) }}">
          </div>
          @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-outline-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- ================================================== --}}
{{-- MODAL EDIT SISWA --}}
<div class="modal fade" id="modalEditSiswa" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <form id="formEditSiswa" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body row g-3">
          {{-- Field bawaan --}}
          <div class="col-md-6">
            <label class="form-label">NISN</label>
            <input type="text" name="nisn" id="edit_nisn" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nama Siswa</label>
            <input type="text" name="nama_siswa" id="edit_nama" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="edit_jk" class="form-select" required>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Agama</label>
            <input type="text" name="agama" id="edit_agama" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="edit_tempat" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="edit_tanggal" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" id="edit_kelas" class="form-select" required>
              @foreach($kelas as $k)
              <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status_id" id="edit_status" class="form-select" required>
              @foreach($status as $s)
              @if(in_array($s->nama_status, ['Aktif','Nonaktif']))
              <option value="{{ $s->id }}">{{ $s->nama_status }}</option>
              @endif
              @endforeach
            </select>
          </div>

          {{-- Kolom tambahan dinamis --}}
          @foreach($columns as $col)
          @if(!in_array($col, ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at']))
          <div class="col-md-6">
            <label class="form-label text-capitalize">{{ str_replace('_', ' ', $col) }}</label>
            <input type="text" name="{{ $col }}" id="edit_{{ $col }}" class="form-control">
          </div>
          @endif
          @endforeach
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-outline-warning">Perbarui</button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- ================================================== --}}
{{-- MODAL KELOLA KELAS --}}
<div class="modal fade" id="modalKelas" tabindex="-1">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kelola Data Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        {{-- Alert sukses --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Alert error --}}
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="ti ti-alert-circle me-2"></i>
          {{ $errors->first('nama_kelas') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Form tambah kelas --}}
        <form id="formTambahKelas" action="{{ route('kelas.store') }}" method="POST" class="mb-3 d-flex">
          @csrf
          <input type="text" name="nama_kelas" class="form-control" placeholder="Nama kelas baru" required>
          <button type="submit" class="btn btn-outline-primary ms-2">Tambah</button>
        </form>

        {{-- List kelas --}}
        <ul class="list-group">
          @foreach($kelas as $k)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <form action="{{ route('kelas.update', $k->id) }}" method="POST" class="d-flex w-100 align-items-center">
              @csrf
              @method('PUT')
              <input type="text" name="nama_kelas" value="{{ $k->nama_kelas }}" class="form-control me-2">
              <button type="submit" class="btn btn-sm btn-outline-warning me-2">Update</button>
            </form>

            <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>


{{-- ================================================== --}}
{{-- MODAL KELOLA KOLOM TAMBAHAN --}}
<div class="modal fade" id="modalTambahKolom" tabindex="-1" aria-labelledby="modalTambahKolomLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahKolomLabel">Kelola Kolom Tambahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- Form tambah kolom --}}
        <form method="POST" action="{{ route('siswa.addColumn') }}" class="mb-3 d-flex">
          @csrf
          <input type="text" name="column_name" class="form-control" placeholder="contoh: prestasi" required>
          <button type="submit" class="btn btn-outline-primary ms-2">Tambah</button>
        </form>

        {{-- List kolom tambahan --}}
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Nama Kolom</th>
                <th width="15%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @php
              $defaultCols = ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at'];
              $customCols = array_diff($columns, $defaultCols);
              @endphp

              @forelse($customCols as $col)
              <tr>
                <td>
                  <form action="{{ route('siswa.updateColumn') }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="old_name" value="{{ $col }}">

                    <input type="text"
                      name="new_name"
                      value="{{ $col }}"
                      class="form-control form-control-sm me-2"
                      required>

                    <button type="submit"
                      class="btn btn-sm btn-outline-warning me-1">
                      Edit
                    </button>
                  </form>
                </td>

                <td class="text-center">
                  <form action="{{ route('siswa.deleteColumn', $col) }}"
                    method="POST"
                    onsubmit="return confirm('Hapus kolom {{ $col }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      Hapus
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="2" class="text-center text-muted">Belum ada kolom tambahan</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          <select id="kelasTemplate" class="d-none">
            <option value="">-- Pilih --</option>
            @foreach($kelas as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
            @endforeach
          </select>

          <select id="statusTemplate" class="d-none">
            @foreach($status as $s)
            <option value="{{ $s->id }}">{{ $s->nama_status }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>
</div>


{{-- MODAL EDIT KOLOM --}}
<div class="modal fade" id="modalEditKolom" tabindex="-1">
  <div class="modal-dialog modal-md">
    <form method="POST" id="formEditKolom">
      @csrf
      @method('PUT')

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Nama Kolom</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Nama Kolom Baru</label>
            <input type="text" name="new_column_name" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-outline-warning">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>



{{-- ================================================== --}}
{{-- MODAL BULK PILIH KELAS--}}
<div class="modal fade" id="modalBulkKelas">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('siswa.bulkKelas') }}" id="formBulkKelas">
      @csrf
      <div id="bulkKelasInputs"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h5>Pilih Kelas</h5>
        </div>
        <div class="modal-body">
          <select name="kelas_id" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelas as $k)
            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- ================================================== --}}
{{-- MODAL MULTI EDIT SISWA --}}
<div class="modal fade" id="modalMultiEdit" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form method="POST" action="{{ route('siswa.multiEditUpdate') }}">
      @csrf

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Banyak Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" id="multiEditContainer">
          {{-- form siswa akan di-generate JS --}}
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Semua</button>
        </div>
      </div>
    </form>
  </div>
</div>



{{-- ================================================== --}}
{{-- SCRIPT UNTUK MODAL EDIT + RESET FORM --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btnEdit');
    const formEdit = document.getElementById('formEditSiswa');

    editButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.dataset.id;
        formEdit.action = `/roles/superadmin/siswa/${id}`;

        // Kolom bawaan
        document.getElementById('edit_nisn').value = this.dataset.nisn || '';
        document.getElementById('edit_nama').value = this.dataset.nama || '';
        document.getElementById('edit_jk').value = this.dataset.jk || '';
        document.getElementById('edit_tempat').value = this.dataset.tempat || '';
        document.getElementById('edit_agama').value = this.dataset.agama || '';
        document.getElementById('edit_kelas').value = this.dataset.kelas || '';
        document.getElementById('edit_status').value = this.dataset.status || '';

        // Format tanggal lahir (pastikan format Y-m-d)
        if (this.dataset.tanggal) {
          document.getElementById('edit_tanggal').value = this.dataset.tanggal;
        }

        // Kolom tambahan dinamis
        Object.keys(this.dataset).forEach(key => {
          if (
            !['id', 'nisn', 'nama', 'jk', 'tempat', 'tanggal', 'kelas', 'agama', 'status'].includes(key)
          ) {
            const input = document.getElementById('edit_' + key);
            if (input) {
              input.value = this.dataset[key];
            }
          }
        });
      });
    });

    // Fungsi reset filter
    const btnReset = document.getElementById('btnReset');
    btnReset.addEventListener('click', function() {
      // Reset semua input dan select
      const filterForm = document.getElementById('filterForm');
      const inputs = filterForm.querySelectorAll('input, select');

      inputs.forEach(input => {
        if (input.type === 'text') {
          input.value = '';
        } else if (input.tagName === 'SELECT') {
          input.selectedIndex = 0;
        }
      });

      // Submit form untuk menampilkan semua data
      filterForm.submit();
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = filterForm.querySelector('input[name="search"]');
    const kelasSelect = filterForm.querySelector('select[name="kelas_id"]');
    const statusSelect = filterForm.querySelector('select[name="status_id"]');
    let typingTimer;

    // Auto submit untuk search (delay biar gak spam)
    searchInput.addEventListener('keyup', function() {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(() => filterForm.submit(), 400);
    });

    // Auto submit langsung kalau select berubah
    kelasSelect.addEventListener('change', () => filterForm.submit());
    statusSelect.addEventListener('change', () => filterForm.submit());
  });

  const bulkAction = document.getElementById('bulkAction');
  const btnKelas = document.getElementById('btnBulkKelas');
  const checkItems = document.querySelectorAll('.checkItem');

  function updateBulkAction() {
    const checked = [...checkItems].filter(c => c.checked);
    if (checked.length === 0) {
      bulkAction.classList.add('d-none');
      return;
    }

    bulkAction.classList.remove('d-none');

    const hasKelas = checked.some(c => c.dataset.hasKelas == 1);
    const noKelas = checked.some(c => c.dataset.hasKelas == 0);

    // KETENTUAN KAMU
    if (!hasKelas && noKelas) {
      btnKelas.classList.remove('d-none');
    } else {
      btnKelas.classList.add('d-none');
    }
  }

  checkItems.forEach(c => c.addEventListener('change', updateBulkAction));

  document.getElementById('checkAll').addEventListener('change', function() {
    checkItems.forEach(c => c.checked = this.checked);
    updateBulkAction();
  });

  // BULK DELETE
  document.getElementById('btnBulkDelete').addEventListener('click', function() {
    const checked = [...checkItems].filter(c => c.checked);
    if (!confirm(`Apakah anda yakin ingin menghapus ${checked.length} siswa ini?`)) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('siswa.bulkDelete') }}";
    form.innerHTML = `@csrf`;
    checked.forEach(c => {
      form.innerHTML += `<input type="hidden" name="siswa_ids[]" value="${c.value}">`;
    });
    document.body.appendChild(form);
    form.submit();
  });

  // BULK KELAS
  btnKelas.addEventListener('click', () => {
    const ids = [...checkItems]
      .filter(c => c.checked)
      .map(c => c.value);

    const container = document.getElementById('bulkKelasInputs');
    container.innerHTML = '';

    ids.forEach(id => {
      container.innerHTML += `
      <input type="hidden" name="siswa_ids[]" value="${id}">
    `;
    });

    new bootstrap.Modal(
      document.getElementById('modalBulkKelas')
    ).show();
  });


  // MULTI EDIT 
  document.getElementById('btnBulkEdit').addEventListener('click', function() {
    const checked = [...document.querySelectorAll('.checkItem:checked')];
    const container = document.getElementById('multiEditContainer');
    container.innerHTML = '';

    const kelasOptions = document.getElementById('kelasTemplate').innerHTML;
    const statusOptions = document.getElementById('statusTemplate').innerHTML;

    checked.forEach((checkbox, index) => {
      const row = checkbox.closest('tr');
      const btn = row.querySelector('.btnEdit');
      const data = btn.dataset;

      let fieldsHtml = '';

      Object.keys(data).forEach(key => {
        // SKIP bootstrap & non-field
        if (
          ['id', 'bsToggle', 'bsTarget'].includes(key)
        ) return;

        // mapping nama field
        const fieldNameMap = {
          nama: 'nama_siswa',
          jk: 'jenis_kelamin',
          tempat: 'tempat_lahir',
          tanggal: 'tanggal_lahir',
          kelas: 'kelas_id',
          status: 'status_id'
        };

        const name = fieldNameMap[key] || key;
        const value = data[key] ?? '';

        // SELECT
        if (key === 'kelas') {
          fieldsHtml += `
          <div class="col-md-6">
            <label>Kelas</label>
            <select name="siswa[${index}][${name}]" class="form-select kelas-select">
              ${kelasOptions}
            </select>
          </div>
        `;
          return;
        }

        if (key === 'status') {
          fieldsHtml += `
          <div class="col-md-6">
            <label>Status</label>
            <select name="siswa[${index}][${name}]" class="form-select status-select">
              ${statusOptions}
            </select>
          </div>
        `;
          return;
        }

        if (key === 'jk') {
          fieldsHtml += `
          <div class="col-md-6">
            <label>Jenis Kelamin</label>
            <select name="siswa[${index}][${name}]" class="form-select">
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
        `;
          return;
        }

        // INPUT BIASA (TERMASUK KOLOM TAMBAHAN)
        fieldsHtml += `
        <div class="col-md-6">
          <label>${name.replace('_',' ')}</label>
          <input type="${key === 'tanggal' ? 'date' : 'text'}"
            name="siswa[${index}][${name}]"
            value="${value}"
            class="form-control">
        </div>
      `;
      });

      container.innerHTML += `
      <div class="border rounded p-3 mb-4">
        <h6 class="mb-3">Siswa ${index + 1}</h6>
        <input type="hidden" name="siswa[${index}][id]" value="${data.id}">
        <div class="row g-3">
          ${fieldsHtml}
        </div>
      </div>
    `;
    });

    // SET selected value
    container.querySelectorAll('.kelas-select').forEach((select, i) => {
      select.value = checked[i].closest('tr').querySelector('.btnEdit').dataset.kelas;
    });

    container.querySelectorAll('.status-select').forEach((select, i) => {
      select.value = checked[i].closest('tr').querySelector('.btnEdit').dataset.status;
    });

    new bootstrap.Modal(document.getElementById('modalMultiEdit')).show();
  });
</script>

@endsection