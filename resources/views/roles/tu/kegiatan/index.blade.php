@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">

    <h2 class="mb-1 fw-bold">Pembayaran Kegiatan</h2>
    <p>Manajemen pembayaran kegiatan sekolah (study tour, lomba, dsb)</p>

    {{-- Card Statistik --}}
    <div class="row mb-3">
      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Target Penerimaan</h6>
          <h3>Rp {{ number_format($totalTarget/1000000, 1) }}M</h3>
          <small>{{ $kegiatans->count() }} kegiatan</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Terkumpul</h6>
          <h3 class="text-success">Rp {{ number_format($totalTerkumpul/1000000, 1) }}M</h3>
          <small>{{ round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}% terkumpul</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm p-3">
          <h6>Kurang</h6>
          <h3 class="text-danger">Rp {{ number_format($totalKurang/1000000, 1) }}M</h3>
          <small>dari target keseluruhan</small>
        </div>
      </div>
    </div>

    {{-- Tombol --}}
    <div class="d-flex mb-3">
      <button class="btn btn-dark me-2"><i class="ti ti-download"></i> Export</button>
      <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Kegiatan</button>
    </div>

    {{-- Card Kegiatan --}}
    <div class="row">
      @foreach($kegiatans as $kegiatan)
      <div class="col-md-6 mb-3">
        <div class="card p-3 shadow-sm">
          <div class="d-flex justify-content-between">
            <h5 class="fw-bold">{{ $kegiatan->nama_kegiatan }}</h5>
            @if($kegiatan->status == 'Selesai')
              <span class="badge bg-success">Selesai</span>
            @elseif($kegiatan->status == 'Sedang Berlangsung')
              <span class="badge bg-primary">Berlangsung</span>
            @else
              <span class="badge bg-secondary">Perencanaan</span>
            @endif
          </div>
          <p class="mb-0">{{ $kegiatan->tanggal_pelaksanaan }}</p>

          {{-- Progress Bar --}}
          <div class="progress mt-2" style="height:8px;">
            <div class="progress-bar bg-dark"
                 role="progressbar"
                 style="width: {{ round($kegiatan->progress, 2) }}%;"
                 aria-valuenow="{{ round($kegiatan->progress, 2) }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
            </div>
          </div>

          <div class="d-flex justify-content-between mt-2">
            <small>Target: <strong>Rp {{ number_format($kegiatan->target_dana, 0, ',', '.') }}</strong></small>
            <small>Terkumpul: <strong class="text-success">Rp {{ number_format($kegiatan->terkumpul, 0, ',', '.') }}</strong></small>
          </div>
          <a href="{{ route('tu.kegiatan.show', $kegiatan->id) }}" class="btn btn-sm btn-outline-dark mt-3">Detail Kegiatan</a>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</div>

{{-- Modal Tambah Kegiatan --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('tu.kegiatan.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kegiatan</h5>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label>Nama Kegiatan</label>
          <input type="text" name="nama_kegiatan" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Tanggal Pelaksanaan</label>
          <input type="date" name="tanggal_pelaksanaan" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Target Dana</label>
          <input type="number" name="target_dana" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Nominal per Siswa</label>
          <input type="number" name="nominal_per_siswa" class="form-control" required>
        </div>

        <div class="mb-2">
          <label>Kelas (pilih lebih dari 1)</label>
          <select name="kelas_ids[]" class="form-select" multiple required>
            @foreach(App\Models\Kelas::all() as $kelas)
              <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
          </select>
          <small class="text-muted">Tekan Ctrl (Windows) / Cmd (Mac) untuk memilih banyak kelas.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-dark" type="submit">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
