@extends('layouts.guru')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- HEADER --}}
    <div class="page-header mb-4">
      <div class="row align-items-center">
        <div class="col">
          <h2 class="page-title">
            Data Siswa Kelas:
            <span class="text-primary">{{ $kelas->nama_kelas }}</span>
          </h2>
        </div>
        <div class="col-auto">
          <a href="{{ route('siswa.perkelas') }}"
            class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>
            Kembali ke Daftar Kelas
          </a>
        </div>
      </div>
    </div>

    {{-- TOOLBAR CONTAINER --}}
    <div class="card shadow-sm mb-3">
      <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

          {{-- SEARCH --}}
          <div class="input-group" style="max-width: 280px;">
            <input type="text"
              id="liveSearch"
              class="form-control"
              placeholder="Cari siswa...">
          </div>

          {{-- EXPORT --}}
          <div class="d-flex gap-2">
            <a href="{{ route('siswa.perkelas.exportPdf', $kelas->id) }}"
              class="btn btn-outline-danger">
              <i class="ti ti-file-type-pdf me-1"></i>
              PDF
            </a>

            <a href="{{ route('siswa.perkelas.exportExcel', $kelas->id) }}"
              class="btn btn-outline-success">
              <i class="ti ti-file-spreadsheet me-1"></i>
              Excel
            </a>
          </div>

        </div>
      </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Tempat / Tanggal Lahir</th>
                <th>Agama</th>

                @foreach($columns as $col)
                @if(!in_array($col, [
                'id','nisn','nama_siswa','jenis_kelamin',
                'tempat_lahir','tanggal_lahir','kelas_id',
                'agama','status_id','created_at','updated_at'
                ]))
                <th>{{ ucfirst(str_replace('_',' ',$col)) }}</th>
                @endif
                @endforeach

                <th>Status</th>
              </tr>
            </thead>

            <tbody id="table-body">
              @forelse($siswa as $index => $item)
              <tr>
                <td>{{ $siswa->firstItem() + $index }}</td>
                <td>{{ $item->nisn }}</td>
                <td class="fw-semibold">{{ $item->nama_siswa }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>
                  {{ $item->tempat_lahir }},
                  {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y') }}
                </td>
                <td>{{ $item->agama }}</td>

                @foreach($columns as $col)
                @if(!in_array($col, [
                'id','nisn','nama_siswa','jenis_kelamin',
                'tempat_lahir','tanggal_lahir','kelas_id',
                'agama','status_id','created_at','updated_at'
                ]))
                <td>{{ $item->$col }}</td>
                @endif
                @endforeach

                <td>
                  <span class="badge bg-{{ $item->status->nama_status == 'Aktif' ? 'green' : 'orange' }}-lt">
                    {{ $item->status->nama_status }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="100%" class="text-center text-muted py-4">
                  <i class="ti ti-info-circle me-2"></i>
                  Belum ada siswa di kelas ini.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mt-3">
      {{ $siswa->links('pagination::bootstrap-5') }}
    </div>

  </div>
</div>
@endsection

{{-- LIVE SEARCH --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearch');
    const rows = document.querySelectorAll('#table-body tr');

    if (!searchInput) return;

    searchInput.addEventListener('keyup', function() {
      const keyword = this.value.toLowerCase();

      rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(keyword) ?
          '' :
          'none';
      });
    });
  });
</script>