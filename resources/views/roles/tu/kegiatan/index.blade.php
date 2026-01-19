@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- HEADER --}}
    <div class="row mb-3 align-items-center">
      <div class="col">
        <h4 class="fw-semibold mb-0">Kegiatan Sekolah</h4>
        <div class="text-muted">
          Manajemen kegiatan sekolah (study tour, lomba, dsb)
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
    <div class="row g-3 mb-2">

      {{-- TARGET --}}
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body py-2">

            <div class="d-flex justify-content-between align-items-center">
              <span class="text-uppercase text-muted small fw-semibold">
                Target
              </span>
              <span class="text-muted small fw-semibold">100%</span>
            </div>

            <div class="h5 fw-bold mb-1">
              Rp {{ number_format($totalTarget, 0, ',', '.') }}
            </div>

            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-secondary" style="width: 100%"></div>
            </div>

          </div>
        </div>
      </div>

      {{-- TERKUMPUL --}}
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body py-2">

            <div class="d-flex justify-content-between align-items-center">
              <span class="text-uppercase text-muted small fw-semibold">
                Terkumpul
              </span>
              <span class="text-success small fw-semibold">
                {{ round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%
              </span>
            </div>

            <div class="h5 fw-bold text-success mb-1">
              Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}
            </div>

            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-success"
                style="width: {{ round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%">
              </div>
            </div>

          </div>
        </div>
      </div>

      {{-- KURANG --}}
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body py-2">

            <div class="d-flex justify-content-between align-items-center">
              <span class="text-uppercase text-muted small fw-semibold">
                Kurang
              </span>
              <span class="text-danger small fw-semibold">
                {{ 100 - round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%
              </span>
            </div>

            <div class="h5 fw-bold text-danger mb-1">
              Rp {{ number_format($totalKurang, 0, ',', '.') }}
            </div>

            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-danger"
                style="width: {{ 100 - round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%">
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>


    {{-- LIST KEGIATAN --}}
    <div class="card shadow-sm">
      <div class="card-body">

        {{-- SEARCH (pendek, kiri, dalam container) --}}
        <div class="mb-4">
          <div class="col-md-3 p-0">
            <input type="text"
              id="searchKegiatan"
              class="form-control"
              placeholder="Cari kegiatan...">
          </div>
        </div>

        <div class="row g-4" id="kegiatanList">
          @foreach($kegiatans as $kegiatan)
          <div class="col-md-4 kegiatan-item"
            data-nama="{{ strtolower($kegiatan->nama_kegiatan) }}">

            {{-- CARD KEGIATAN --}}
            <div class="card h-100 shadow border-0">
              <div class="card-body d-flex flex-column">

                {{-- JUDUL & STATUS --}}
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-bold mb-0">
                    {{ $kegiatan->nama_kegiatan }}
                  </h6>

                  @if($kegiatan->status == 'Perencanaan')
                  <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary">
                    Perencanaan
                  </span>
                  @elseif($kegiatan->status == 'Sedang Berlangsung')
                  <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">
                    Sedang Berlangsung
                  </span>
                  @elseif($kegiatan->status == 'Selesai')
                  <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                    Selesai
                  </span>
                  @endif
                </div>

                <small class="text-muted mb-3">
                  {{ $kegiatan->tanggal_pelaksanaan }}
                </small>

                {{-- PROGRESS --}}
                <div class="progress mb-3" style="height: 6px;">
                  <div class="progress-bar bg-dark"
                    style="width: {{ round($kegiatan->progress, 2) }}%">
                  </div>
                </div>

                {{-- TARGET & TERKUMPUL --}}
                <div class="d-flex justify-content-between text-sm mb-4">
                  <div>
                    Target<br>
                    <strong>
                      Rp {{ number_format($kegiatan->target_dana, 0, ',', '.') }}
                    </strong>
                  </div>
                  <div class="text-success text-end">
                    Terkumpul<br>
                    <strong>
                      Rp {{ number_format($kegiatan->terkumpul, 0, ',', '.') }}
                    </strong>
                  </div>
                </div>

                {{-- BUTTON --}}
                <a href="{{ route('tu.kegiatan.show', $kegiatan->id) }}"
                  class="btn btn-outline-primary text-center mt-auto">
                  Lihat Detail Kegiatan
                </a>

              </div>
            </div>

          </div>
          @endforeach
        </div>

      </div>
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

            {{-- INPUT TAMPILAN --}}
            <input type="text"
              id="target_dana_view"
              class="form-control"
              placeholder="Rp 0"
              oninput="formatRupiah(this, 'target_dana')"
              required>

            {{-- INPUT ASLI KE BACKEND --}}
            <input type="hidden" name="target_dana" id="target_dana">
          </div>

          <div class="col-md-6">
            <label class="form-label">Nominal per Siswa</label>

            {{-- INPUT TAMPILAN --}}
            <input type="text"
              id="nominal_view"
              class="form-control"
              placeholder="Rp 0"
              oninput="formatRupiah(this, 'nominal_per_siswa')"
              required>

            {{-- INPUT ASLI KE BACKEND --}}
            <input type="hidden" name="nominal_per_siswa" id="nominal_per_siswa">
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


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const search = document.getElementById('searchKegiatan');
    const items = document.querySelectorAll('.kegiatan-item');

    search.addEventListener('input', function() {
      const value = this.value.toLowerCase();
      items.forEach(item => {
        item.style.display =
          item.dataset.nama.includes(value) ? '' : 'none';
      });
    });
  });



  function formatRupiah(input, hiddenId) {
    let value = input.value.replace(/[^0-9]/g, '');

    if (!value) {
      document.getElementById(hiddenId).value = '';
      input.value = '';
      return;
    }

    document.getElementById(hiddenId).value = value;

    input.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
  }
</script>

@endsection