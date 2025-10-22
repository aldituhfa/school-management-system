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
          <button class="btn btn-outline-secondary ms-2" data-bs-toggle="modal" data-bs-target="#modalStatus">
            <i class="ti ti-tag"></i> Status
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
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama atau NISN...">
          </div>
          <div class="col-md-3">
            <select name="kelas_id" class="form-select">
              <option value="">Semua Kelas</option>
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
              <option value="{{ $s->id }}" {{ request('status_id') == $s->id ? 'selected' : '' }}>
                {{ $s->nama_status }}
              </option>
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
          <table class="table table-hover">
            <thead>
              <tr>
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
                <td><span class="badge bg-blue-lt">{{ $item->kelas->nama_kelas }}</span></td>
                <td>
                  <span class="badge bg-{{ $item->status->nama_status == 'Aktif' ? 'green' : 'orange' }}-lt">
                    {{ $item->status->nama_status }}
                  </span>
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
            <label class="form-label">Kelas</label>
            <select name="kelas_id" class="form-select" required>
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelas as $k)
              <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status_id" class="form-select" required>
              <option value="">-- Pilih Status --</option>
              @foreach($status as $s)
              <option value="{{ $s->id }}">{{ $s->nama_status }}</option>
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
              <option value="">-- Pilih --</option>
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
              <option value="{{ $s->id }}">{{ $s->nama_status }}</option>
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
{{-- MODAL KELOLA STATUS --}}
<div class="modal fade" id="modalStatus" tabindex="-1">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kelola Status Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- Form tambah status --}}
        <form id="formTambahStatus" action="{{ route('status.store') }}" method="POST" class="mb-3 d-flex">
          @csrf
          <input type="text" name="nama_status" class="form-control" placeholder="Tambah status baru" required>
          <button type="submit" class="btn btn-outline-primary ms-2">Tambah</button>
        </form>

        {{-- List status --}}
        <ul class="list-group">
          @foreach($status as $s)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <form action="{{ route('status.update', $s->id) }}" method="POST" class="d-flex w-100 align-items-center">
              @csrf
              @method('PUT')
              <input type="text" name="nama_status" value="{{ $s->nama_status }}" class="form-control me-2">
              <button type="submit" class="btn btn-sm btn-outline-warning me-2">Update</button>
            </form>

            <form action="{{ route('status.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin hapus status ini?')">
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
                <td>{{ $col }}</td>
                <td class="text-center">
                  <form action="{{ route('siswa.deleteColumn', $col) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('Hapus kolom {{ $col }}?')">
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
        </div>
      </div>
    </div>
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


    // reset form setelah tambah
    const formTambahSiswa = document.querySelector('#formTambahSiswa');
    formTambahSiswa.addEventListener('submit', () => {
      setTimeout(() => window.location.reload(), 800);
    });

    const formTambahKelas = document.querySelector('#formTambahKelas');
    formTambahKelas.addEventListener('submit', () => {
      setTimeout(() => window.location.reload(), 800);
    });

    const formTambahStatus = document.querySelector('#formTambahStatus');
    formTambahStatus.addEventListener('submit', () => {
      setTimeout(() => window.location.reload(), 800);
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
</script>

@endsection