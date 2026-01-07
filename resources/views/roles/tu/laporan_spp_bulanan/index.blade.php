@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">

    <div class="page-header mb-3">
      <h2 class="page-title">Laporan SPP Bulanan</h2>
    </div>

    {{-- FILTER (LIVE) --}}
    <div class="card mb-3">
      <div class="card-body">
        <div class="row g-2">

          <div class="col-md-3">
            <label class="form-label">Tahun Ajaran</label>
            <select id="tahun_ajaran_id" class="form-select">
              @foreach($tahunAjaran as $t)
              <option value="{{ $t->id }}" {{ $tahunAjaranId == $t->id ? 'selected' : '' }}>
                {{ $t->nama_tahun }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Bulan</label>
            <select id="bulan" class="form-select">
              @foreach($daftarBulan as $b)
              <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                {{ $b }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Kelas</label>
            <select id="kelas_id" class="form-select">
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

    {{-- SUMMARY --}}
    <div class="row g-3 mb-3" id="summary">
      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="text-muted">Total Tagihan</div>
            <div class="fs-3 fw-bold">Rp {{ number_format($totalTagihan,0,',','.') }}</div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="text-muted">Total Dibayar</div>
            <div class="fs-3 fw-bold text-success">Rp {{ number_format($totalDibayar,0,',','.') }}</div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="text-muted">Total Tunggakan</div>
            <div class="fs-3 fw-bold text-danger">Rp {{ number_format($totalTunggakan,0,',','.') }}</div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center">
          <div class="card-body">
            <div class="text-muted">Siswa Lunas</div>
            <div class="fs-3 fw-bold">{{ $siswaLunas }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- TABLE --}}
    <div class="card">
      <div class="table-responsive">
        <table class="table table-sm table-vcenter">
          <thead>
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
              <td>{{ $row->nama_siswa }}</td>
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
                <span class="badge bg-success text-white">Lunas</span>
                @else
                <span class="badge bg-warning text-dark">Menunggak</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Tidak ada data</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

{{-- LIVE FILTER SCRIPT --}}
<script>
  const tahun = document.getElementById('tahun_ajaran_id');
  const bulan = document.getElementById('bulan');
  const kelas = document.getElementById('kelas_id');
  const tbody = document.getElementById('table-body');
  const summary = document.getElementById('summary');

  [tahun, bulan, kelas].forEach(el => {
    el.addEventListener('change', loadData);
  });

  function loadData() {
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
      });
  }
</script>
@endsection