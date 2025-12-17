@extends('layouts.superadmin')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- Header judul --}}
    <div class="page-header d-print-none mb-3">
      <h2 class="page-title fw-bold" style="font-size: 1.40rem;">Data SPP</h2>
    </div>

    {{-- CARD UTAMA --}}
    <div class="card shadow-sm" style="border-radius: 10px;">

      {{-- HEADER FILTER --}}
      <div class="card-body border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">

          {{-- Count --}}
          <div class="text-muted" style="font-size: 0.95rem;">
            Menampilkan {{ $siswa->firstItem() ?? 0 }}-{{ $siswa->lastItem() ?? 0 }} dari {{ $siswa->total() }} data
          </div>

          {{-- Filter --}}
          <div class="d-flex gap-2">

            {{-- Tahun Ajaran --}}
            <form method="GET" id="tahunAjaranForm">
              <select name="tahun_ajaran_id"
                class="form-select"
                style="width: 180px; font-size: 0.95rem; padding: 6px;"
                onchange="konfirmasiGantiTahun(this)">

                @foreach($tahunAjaran as $t)
                <option value="{{ $t->id }}" {{ $tahunAjaranId == $t->id ? 'selected' : '' }}>
                  {{ $t->nama_tahun }}
                </option>
                @endforeach

              </select>
            </form>

            {{-- Search --}}
            <input type="text" id="searchInput"
              class="form-control"
              style="width: 200px; font-size: 0.95rem; padding: 6px;"
              placeholder="Cari nama/NISN...">

            {{-- Filter kelas --}}
            <select id="filterKelas"
              class="form-select"
              style="width: 160px; font-size: 0.95rem; padding: 6px;">
              <option value="">Semua Kelas</option>
              @foreach($kelas as $k)
              <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>

          </div>
        </div>
      </div>

      {{-- TABEL DATA --}}
      <div class="table-responsive">
        <table class="table table-vcenter table-striped"
          id="siswaTable"
          style="font-size: 0.90rem;">

          <thead style="font-size: 1rem;">
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

              {{-- BUTTON DETAIL --}}
              <td class="text-center">
                @if($tahunAjaranId)
                <a href="{{ route('superadmin.data_spp.detail', ['id' => $item->id, 'tahun_ajaran_id' => $tahunAjaranId]) }}"
                  class="btn btn-outline-primary"
                  style="font-size: 0.7rem; padding: 5px 16px; font-weight: 600;">
                  Detail
                </a>
                @else
                <button onclick="alert('Tahun ajaran belum diisi oleh super admin!')"
                  class="btn btn-outline-primary"
                  style="font-size: 0.9rem; padding: 7px 16px; font-weight: 600;">
                  Detail
                </button>
                @endif
              </td>

            </tr>
            @endforeach
          </tbody>

        </table>
      </div>

      {{-- PAGINATION --}}
      <div class="card-footer d-flex align-items-center py-2">
        <div class="text-muted" style="font-size: 0.9rem;">
          Halaman {{ $siswa->currentPage() }} dari {{ $siswa->lastPage() }}
        </div>
        <div class="ms-auto">
          {{ $siswa->links() }}
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Script Filter --}}
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
    if (confirm('Apakah Anda yakin ingin mengganti tahun ajaran?')) {
      form.submit();
    } else {
      select.value = "{{ $tahunAjaranId }}";
    }
  }
</script>

@endsection
