@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="fw-semibold mb-0">Laporan SPP Tahunan</h4>
        <small class="text-muted">
          Tahun Ajaran:
          <span class="badge bg-secondary bg-opacity-10 text-secondary">
            {{ $tahunAjaran->where('id',$tahunAjaranId)->first()->nama_tahun ?? '-' }}
          </span>
        </small>
      </div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body py-2">
        <form method="GET" class="row gx-3 gy-2 align-items-center">
          <div class="col-md-4">
            <label class="form-label text-muted mb-1">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="form-select form-select-sm" onchange="this.form.submit()">
              @foreach($tahunAjaran as $t)
              <option value="{{ $t->id }}" {{ $tahunAjaranId == $t->id ? 'selected' : '' }}>
                {{ $t->nama_tahun }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label text-muted mb-1">Kelas</label>
            <select name="kelas_id" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">Semua Kelas</option>
              @foreach($kelas as $k)
              <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2 align-self-end">
            <a href="{{ route('tu.laporan_tagihan_spp.index') }}"
              class="btn btn-outline-secondary btn-sm w-100">
              <i class="ti ti-refresh"></i> Reset
            </a>
          </div>
        </form>
      </div>
    </div>

    {{-- STATISTIK --}}
    {{-- STATISTIK (COMPACT) --}}
    <div class="row g-2 mb-3">

      {{-- TOTAL TAGIHAN --}}
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between">
              <small class="text-muted text-uppercase">Tagihan</small>
              <small class="text-muted">100%</small>
            </div>

            <div class="fw-semibold fs-5 mb-1">
              Rp {{ number_format($totalTagihan ?? 0, 0, ',', '.') }}
            </div>

            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-secondary" style="width:100%"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- TOTAL LUNAS --}}
      @php
      $persenLunas = $totalTagihan > 0
      ? round(($totalLunas / $totalTagihan) * 100)
      : 0;
      @endphp

      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between">
              <small class="text-muted text-uppercase">Lunas</small>
              <small class="text-success fw-semibold">
                {{ $persenLunas }}%
              </small>
            </div>

            <div class="fw-semibold fs-5 text-success mb-1">
              Rp {{ number_format($totalLunas ?? 0, 0, ',', '.') }}
            </div>

            <div class="progress" style="height: 4px;">
              <div
                class="progress-bar bg-success"
                style="width: {{ $persenLunas }}%">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- TOTAL TUNGGAKAN --}}
      @php
      $persenTunggakan = $totalTagihan > 0
      ? round(($totalTunggakan / $totalTagihan) * 100)
      : 0;
      @endphp

      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between">
              <small class="text-muted text-uppercase">Tunggakan</small>
              <small class="text-danger fw-semibold">
                {{ $persenTunggakan }}%
              </small>
            </div>

            <div class="fw-semibold fs-5 text-danger mb-1">
              Rp {{ number_format($totalTunggakan ?? 0, 0, ',', '.') }}
            </div>

            <div class="progress" style="height: 4px;">
              <div
                class="progress-bar bg-danger"
                style="width: {{ $persenTunggakan }}%">
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>


    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
      <div class="card-body">

        {{-- TOOLS --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            <input type="text" name="search" value="{{ $search ?? '' }}"
              class="form-control form-control-sm"
              placeholder="Cari Nama / NISN">
          </form>

          <div class="d-flex gap-2">
            <a href="{{ route('tu.laporan_tagihan_spp.exportPdf', request()->query()) }}"
              class="btn btn-outline-danger btn-sm">
              <i class="ti ti-file-type-pdf"></i> PDF
            </a>
            <a href="{{ route('tu.laporan_tagihan_spp.exportExcel', request()->query()) }}"
              class="btn btn-outline-success btn-sm">
              <i class="ti ti-file-spreadsheet"></i> Excel
            </a>
          </div>
        </div>

        {{-- DATA --}}
        <div class="table-responsive">
          <table class="table table-hover align-middle">
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
                <td class="fw-semibold">{{ $sw->nama_siswa }}</td>
                <td>{{ $sw->kelas->nama_kelas ?? '-' }}</td>
                <td>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary">
                    {{ $sw->status->nama_status ?? '-' }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-success bg-opacity-10 text-success">
                    {{ $sw->bulan_lunas }}
                  </span>
                  /
                  <span class="text-muted">{{ $sw->total_bulan }}</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
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
@endsection