@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-fluid">
    <h5 class="mb-3 fw-semibold">Laporan SPP Tahunan </h5>

    {{-- FILTER CARD --}}
    <div class="card shadow-sm mb-3 border-0">
      <div class="card-body py-2">
        <form method="GET" class="row gx-2 gy-2 align-items-center">
          <div class="col-md-3 d-flex align-items-center">
            <label class="form-label mb-0 me-2 text-muted">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="form-select form-select-sm" onchange="this.form.submit()">
              @foreach($tahunAjaran as $t)
              <option value="{{ $t->id }}" {{ ($tahunAjaranId == $t->id) ? 'selected' : '' }}>
                {{ $t->nama_tahun }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 d-flex align-items-center">
            <label class="form-label mb-0 me-2 text-muted">Kelas</label>
            <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">Semua</option>
              @foreach($kelas as $k)
              <option value="{{ $k->id }}" {{ ($kelasId == $k->id) ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <a href="{{ route('tu.laporan_tagihan_spp.index') }}" class="btn btn-outline-secondary btn-sm w-100">
              <i class="ti ti-refresh"></i> Reset
            </a>
          </div>
        </form>
      </div>
    </div>

    {{-- 3 CARD STATISTIK --}}
    <div class="row g-2 mb-3">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-2">
          <div class="card-body d-flex align-items-center py-2">
            <i class="ti ti-wallet fs-3 text-secondary me-3"></i>
            <div>
              <h7 class="fw-normal mb-1 text-muted">Total Tagihan Tahunan </h7>
              <h5 class="fw-semibold mb-0">Rp {{ number_format($totalTagihan ?? 0, 0, ',', '.') }}</h5>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-2">
          <div class="card-body d-flex align-items-center py-2">
            <i class="ti ti-cash fs-3 text-secondary me-3"></i>
            <div>
              <h7 class="fw-normal mb-1 text-muted">Total Lunas</h7>
              <h5 class="fw-semibold mb-0">Rp {{ number_format($totalLunas ?? 0, 0, ',', '.') }}</h5>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-2">
          <div class="card-body d-flex align-items-center py-2">
            <i class="ti ti-users fs-3 text-secondary me-3"></i>
            <div>
              <h7 class="fw-normal mb-1 text-muted">Total Tunggakan</h7>
              <h5 class="fw-semibold mb-0">
                Rp {{ number_format($totalTunggakan ?? 0, 0, ',', '.') }}
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- TABLE WRAPPER --}}
    <div class="card border-0 shadow-sm">
      <div class="card-body py-3">

        {{-- TOOLS --}}
        <div class="p-2 rounded shadow-sm mb-3 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
          <form method="GET" class="d-flex align-items-center" style="gap: 8px;">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control form-control-sm"
              placeholder="Cari Nama / NISN..." style="max-width: 230px;">
          </form>

          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tu.laporan_tagihan_spp.exportPdf', request()->query()) }}" class="btn btn-outline-danger btn-sm shadow-sm">
              <i class="ti ti-file-type-pdf"></i> PDF
            </a>
            <a href="{{ route('tu.laporan_tagihan_spp.exportExcel', request()->query()) }}" class="btn btn-outline-success btn-sm shadow-sm">
              <i class="ti ti-file-spreadsheet"></i> Excel
            </a>
          </div>
        </div>

        {{-- TABLE --}}
        <h6 class="fw-semibold mb-2">Daftar Siswa (Sesuai Filter)</h6>
        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Bulan Lunas</th>
              </tr>
            </thead>
            <tbody>
              @forelse($siswa as $sw)
              <tr>
                <td>{{ $sw->nisn }}</td>
                <td>{{ $sw->nama_siswa }}</td>
                <td>{{ $sw->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $sw->status->nama_status ?? '-' }}</td>
                <td>{{ $sw->bulan_lunas }} dari {{ $sw->total_bulan }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection