@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="fw-semibold mb-0">Laporan SPP Bulanan</h4>
        <small class="text-muted">
          Tahun Ajaran:
          <span id="label-tahun"
            class="badge bg-secondary bg-opacity-10 text-secondary">
            {{ $tahunAjaran->where('id',$tahunAjaranId)->first()->nama_tahun ?? '-' }}
          </span>
          |
          Bulan:
          <span id="label-bulan"
            class="badge bg-secondary bg-opacity-10 text-secondary">
            {{ $bulan }}
          </span>
        </small>
      </div>
    </div>


    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body py-2">
        <div class="row gx-3 gy-2 align-items-center">

          <div class="col-md-3">
            <label class="form-label text-muted mb-1">Tahun Ajaran</label>
            <select id="tahun_ajaran_id" class="form-select form-select-sm">
              @foreach($tahunAjaran as $t)
              <option value="{{ $t->id }}" {{ $tahunAjaranId == $t->id ? 'selected' : '' }}>
                {{ $t->nama_tahun }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted mb-1">Bulan</label>
            <select id="bulan" class="form-select form-select-sm">
              @foreach($daftarBulan as $b)
              <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                {{ $b }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted mb-1">Kelas</label>
            <select id="kelas_id" class="form-select form-select-sm">
              <option value="">Semua Kelas</option>
              @foreach($kelas as $k)
              <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
              </option>
              @endforeach
            </select>
          </div>

        </div>
      </div>
    </div>

    @php
    $persenDibayar = $totalTagihan > 0
    ? round(($totalDibayar / $totalTagihan) * 100)
    : 0;

    $persenTunggakan = $totalTagihan > 0
    ? round(($totalTunggakan / $totalTagihan) * 100)
    : 0;
    @endphp

    {{-- STATISTIK --}}
    <div class="row g-2 mb-3" id="summary">

      {{-- TAGIHAN --}}
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <small class="text-muted text-uppercase">Tagihan</small>
            <div class="fw-semibold fs-5 mb-1">
              Rp {{ number_format($totalTagihan,0,',','.') }}
            </div>
            <div class="progress" style="height:4px">
              <div class="progress-bar bg-secondary" style="width:100%"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- DIBAYAR --}}
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between">
              <small class="text-muted text-uppercase">Dibayar</small>
              <small class="text-success fw-semibold">{{ $persenDibayar }}%</small>
            </div>
            <div class="fw-semibold fs-5 text-success mb-1">
              Rp {{ number_format($totalDibayar,0,',','.') }}
            </div>
            <div class="progress" style="height:4px">
              <div class="progress-bar bg-success"
                style="width: {{ $persenDibayar }}%"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- TUNGGAKAN --}}
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between">
              <small class="text-muted text-uppercase">Tunggakan</small>
              <small class="text-danger fw-semibold">{{ $persenTunggakan }}%</small>
            </div>
            <div class="fw-semibold fs-5 text-danger mb-1">
              Rp {{ number_format($totalTunggakan,0,',','.') }}
            </div>
            <div class="progress" style="height:4px">
              <div class="progress-bar bg-danger"
                style="width: {{ $persenTunggakan }}%"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- SISWA LUNAS --}}
      <div class="col-md-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <small class="text-muted text-uppercase">Siswa Lunas</small>
            <div class="fw-semibold fs-5">
              {{ $siswaLunas }}
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- TOOLBAR ATAS TABLE --}}
    <div class="card border-0 shadow-sm mb-2">
      <div class="card-body py-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

          {{-- LIVE SEARCH --}}
          <input type="text" id="liveSearch"
            class="form-control form-control-sm w-auto"
            placeholder="Cari nama siswa / kelas...">

          {{-- EXPORT --}}
          <div class="d-flex gap-2">
            <button class="btn btn-outline-danger btn-sm" onclick="exportPdf()">
              <i class="ti ti-file-type-pdf"></i> PDF
            </button>
            <button class="btn btn-outline-success btn-sm" onclick="exportExcel()">
              <i class="ti ti-file-spreadsheet"></i> Excel
            </button>
          </div>

        </div>
      </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Bulan</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nominal</th>
                <th>Tgl Bayar</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="table-body">
              @forelse($laporanBulanan as $row)
              <tr>
                <td>{{ $row->bulan }}</td>
                <td class="fw-semibold">{{ $row->nama_siswa }}</td>
                <td>{{ $row->nama_kelas ?? '-' }}</td>
                <td>Rp {{ number_format($row->nominal,0,',','.') }}</td>
                <td>
                  @if($row->status === 'Lunas')
                  {{ \Carbon\Carbon::parse($row->tanggal_bayar)->format('d/m/Y') }}
                  @else
                  -
                  @endif
                </td>
                <td>
                  @if($row->status === 'Lunas')
                  <span class="badge bg-success bg-opacity-10 text-success">Lunas</span>
                  @else
                  <span class="badge bg-warning bg-opacity-10 text-warning">Menunggak</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  Tidak ada data
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

{{-- SCRIPT (FUNGSI LAMA TETAP) --}}
<script>
  const tahun = document.getElementById('tahun_ajaran_id');
  const bulan = document.getElementById('bulan');
  const kelas = document.getElementById('kelas_id');
  const tbody = document.getElementById('table-body');
  const summary = document.getElementById('summary');
  const labelTahun = document.getElementById('label-tahun');
  const labelBulan = document.getElementById('label-bulan');

  [tahun, bulan, kelas].forEach(el => {
    el.addEventListener('change', loadData);
  });

  function loadData() {
    // 🔹 UPDATE HEADER LANGSUNG
    labelTahun.textContent =
      tahun.options[tahun.selectedIndex].text;

    labelBulan.textContent =
      bulan.options[bulan.selectedIndex].text;

    const params = new URLSearchParams({
      tahun_ajaran_id: tahun.value,
      bulan: bulan.value,
      kelas_id: kelas.value
    });

    fetch(`{{ route('tu.laporan_spp_bulanan.index') }}?${params.toString()}`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.text())
      .then(html => {
        const dom = new DOMParser().parseFromString(html, 'text/html');
        tbody.innerHTML = dom.querySelector('#table-body').innerHTML;
        summary.innerHTML = dom.querySelector('#summary').innerHTML;
        applySearch();
      });
  }


  function getExportParams() {
    return new URLSearchParams({
      tahun_ajaran_id: tahun.value,
      bulan: bulan.value,
      kelas_id: kelas.value
    }).toString();
  }

  function exportPdf() {
    window.location.href =
      `{{ route('tu.laporan_spp_bulanan.exportPdf') }}?` + getExportParams();
  }

  function exportExcel() {
    window.location.href =
      `{{ route('tu.laporan_spp_bulanan.exportExcel') }}?` + getExportParams();
  }

  // LIVE SEARCH (CLIENT SIDE)
  const searchInput = document.getElementById('liveSearch');

  function applySearch() {
    const keyword = searchInput.value.toLowerCase();
    document.querySelectorAll('#table-body tr').forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(keyword) ?
        '' :
        'none';
    });
  }

  searchInput.addEventListener('keyup', applySearch);
</script>
@endsection