@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- HEADER --}}
    <div class="row mb-3 align-items-center">
      <div class="col">
        <div class="page-pretitle">Keuangan</div>
        <h2 class="page-title">Pembayaran Kegiatan</h2>
        <div class="text-muted">
          Manajemen pembayaran kegiatan sekolah (study tour, lomba, dsb)
        </div>
      </div>
      <div class="col-auto">
        <button class="btn btn-primary"
          data-bs-toggle="modal"
          data-bs-target="#modalTambah">
          + Tambah Kegiatan
        </button>
      </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="subheader">Target Penerimaan</div>
            <div class="h2 mb-1">
              Rp {{ number_format($totalTarget, 0, ',', '.') }}
            </div>
            <div class="text-muted">
              {{ $kegiatans->count() }} kegiatan
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="subheader">Terkumpul</div>
            <div class="h2 mb-1 text-success">
              Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}
            </div>
            <div class="text-muted">
              {{ round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}% dari target
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="subheader">Kurang</div>
            <div class="h2 mb-1 text-danger">
              Rp {{ number_format($totalKurang, 0, ',', '.') }}
            </div>
            <div class="text-muted">
              dari target keseluruhan
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- LIST KEGIATAN --}}
    <div class="row g-3">
      @foreach($kegiatans as $kegiatan)
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-body">

            {{-- JUDUL + STATUS --}}
            <div class="d-flex align-items-center justify-content-between mb-1">
              <h3 class="card-title mb-0">
                {{ $kegiatan->nama_kegiatan }}
              </h3>

              @if($kegiatan->status == 'Selesai')
              <span class="badge bg-success">Selesai</span>
              @elseif($kegiatan->status == 'Sedang Berlangsung')
              <span class="badge bg-primary">Berlangsung</span>
              @else
              <span class="badge bg-secondary">Perencanaan</span>
              @endif
            </div>

            <div class="text-muted mb-2">
              {{ $kegiatan->tanggal_pelaksanaan }}
            </div>

            {{-- PROGRESS --}}
            <div class="progress mb-2" style="height: 6px;">
              <div class="progress-bar bg-dark"
                style="width: {{ round($kegiatan->progress, 2) }}%">
              </div>
            </div>

            {{-- TARGET & TERKUMPUL --}}
            <div class="d-flex justify-content-between text-sm mb-3">
              <div>
                Target:
                <strong>
                  Rp {{ number_format($kegiatan->target_dana, 0, ',', '.') }}
                </strong>
              </div>
              <div class="text-success">
                Terkumpul:
                <strong>
                  Rp {{ number_format($kegiatan->terkumpul, 0, ',', '.') }}
                </strong>
              </div>
            </div>

            {{-- BUTTON DETAIL (TEXT CENTER) --}}
            <a href="{{ route('tu.kegiatan.show', $kegiatan->id) }}"
              class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
              Lihat Detail Kegiatan
            </a>

          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form action="{{ route('tu.kegiatan.store') }}"
      method="POST"
      class="modal-content">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Tambah Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama Kegiatan</label>
          <input type="text" name="nama_kegiatan" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Pelaksanaan</label>
          <input type="date" name="tanggal_pelaksanaan" class="form-control" required>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Target Dana</label>
            <input type="number" name="target_dana" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nominal per Siswa</label>
            <input type="number" name="nominal_per_siswa" class="form-control" required>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Kelas (boleh lebih dari 1)</label>
          <select name="kelas_ids[]" class="form-select" multiple required>
            @foreach(App\Models\Kelas::all() as $kelas)
            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
          </select>
          <div class="text-muted mt-1">
            Gunakan Ctrl / Cmd untuk memilih banyak kelas
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary">
      </div>
    </form>
  </div>
</div>
@endsection