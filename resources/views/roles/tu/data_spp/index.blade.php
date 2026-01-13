@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="page-header d-print-none mb-3">
      <h2 class="page-title" style="font-size: 1.25rem; font-weight: 600;">Data SPP</h2>
    </div>

    @if($isNonaktif)
    <div class="alert alert-warning alert-dismissible fade show">
      <strong>Perhatian!</strong>
      Tahun ajaran yang Anda pilih <b>sudah Nonaktif</b>.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
      <div class="card-body border-bottom py-2">
        <div class="d-flex justify-content-between align-items-center">
          <div class="text-muted small">
            Menampilkan {{ $siswa->firstItem() ?? 0 }}-{{ $siswa->lastItem() ?? 0 }} dari {{ $siswa->total() }} data
          </div>
          <div class="d-flex gap-2">
            {{-- Dropdown Tahun Ajaran --}}
            <form method="GET" id="tahunAjaranForm">
              <select name="tahun_ajaran_id"
                class="form-select form-select-sm"
                style="width: 160px;"
                onchange="konfirmasiGantiTahun(this)">
                @foreach($tahunAjaran as $t)
                <option value="{{ $t->id }}" {{ $tahunAjaranId == $t->id ? 'selected' : '' }}>
                  {{ $t->nama_tahun }}
                </option>
                @endforeach
              </select>
            </form>

            {{-- Live Search --}}
            <input type="text" id="searchInput" class="form-control form-control-sm" style="width: 180px;" placeholder="Cari nama/NISN...">

            {{-- Filter Kelas --}}
            <select id="filterKelas" class="form-select form-select-sm" style="width: 140px;">
              <option value="">Semua Kelas</option>
              @foreach($kelas as $k)
              <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-sm table-vcenter" id="siswaTable" style="font-size: 0.8125rem;">
          <thead>
            <tr>
              <th>NISN</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($siswa as $item)
            <tr>
              <td>{{ $item->nisn }}</td>
              <td>{{ $item->nama_siswa }}</td>
              <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
              <td>{{ ucfirst(strtolower($item->status->nama_status ?? 'Tidak diketahui')) }}</td>
              <td class="text-center">
                @if($tahunAjaranId)
                <a href="{{ route('tu.data_spp.detail', ['id' => $item->id, 'tahun_ajaran_id' => $tahunAjaranId]) }}"
                  class="btn btn-sm btn-outline-primary">Detail</a>
                @else
                <button onclick="alert('Tahun ajaran belum diisi oleh super admin!')"
                  class="btn btn-sm btn-outline-primary">
                  Detail
                </button>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="card-footer d-flex align-items-center py-2">
        <div class="text-muted small">
          Halaman {{ $siswa->currentPage() }} dari {{ $siswa->lastPage() }}
        </div>
        <div class="ms-auto">
          {{ $siswa->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Live Search + Filter --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterKelas = document.getElementById('filterKelas');
    const rows = document.querySelectorAll('#siswaTable tbody tr');

    function filterTable() {
      const searchValue = searchInput.value.toLowerCase();
      const selectedKelas = filterKelas.value.toLowerCase();

      rows.forEach(row => {
        const nama = row.children[1].textContent.toLowerCase();
        const nisn = row.children[0].textContent.toLowerCase();
        const kelas = row.children[2].textContent.toLowerCase();
        const matchSearch = nama.includes(searchValue) || nisn.includes(searchValue);
        const matchKelas = selectedKelas === "" || kelas.includes(selectedKelas);
        row.style.display = (matchSearch && matchKelas) ? "" : "none";
      });
    }

    searchInput.addEventListener('keyup', filterTable);
    filterKelas.addEventListener('change', filterTable);
  });

  function konfirmasiGantiTahun(select) {
    const form = document.getElementById('tahunAjaranForm');
    const konfirmasi = confirm('Apakah Anda yakin ingin mengganti tahun ajaran?');
    if (konfirmasi) {
      form.submit();
    } else {
      // Batalkan perubahan dropdown
      select.value = "{{ $tahunAjaranId }}";
    }
  }
</script>
@endsection