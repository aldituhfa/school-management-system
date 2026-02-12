@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- HEADER --}}
    <div class="page-header mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h2 class="page-title fw-normal mb-1" style="color: #2c3e50;">Kegiatan Sekolah</h2>
          <div class="text-muted">Manajemen kegiatan sekolah (study tour, lomba, dsb)</div>
        </div>
        <button class="btn btn-primary px-4 py-2"
          data-bs-toggle="modal"
          data-bs-target="#modalTambah"
          style="background: #4a90e2; border: none;">
          <i class="bx bx-plus me-2"></i>
          Tambah Kegiatan
        </button>
      </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">
      {{-- TARGET --}}
      <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 3px solid #64748b;">
          <div class="card-body py-3">
            <div class="d-flex align-items-center">
              <div class="bg-secondary bg-opacity-10 rounded p-2 me-3">
                <i class="bx bx-target-lock text-secondary"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-muted small mb-1">Target</div>
                <div class="d-flex justify-content-between align-items-end">
                  <div class="h4 fw-medium mb-0" style="color: #334155;">
                    Rp {{ number_format($totalTarget, 0, ',', '.') }}
                  </div>
                  <div class="text-muted small">100%</div>
                </div>
              </div>
            </div>
            <div class="progress mt-2" style="height: 4px;">
              <div class="progress-bar bg-secondary" style="width: 100%"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- TERKUMPUL --}}
      <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 3px solid #10b981;">
          <div class="card-body py-3">
            <div class="d-flex align-items-center">
              <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                <i class="bx bx-check-circle text-success"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-muted small mb-1">Terkumpul</div>
                <div class="d-flex justify-content-between align-items-end">
                  <div class="h4 fw-medium mb-0" style="color: #059669;">
                    Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}
                  </div>
                  <div class="text-success small fw-medium">
                    {{ round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%
                  </div>
                </div>
              </div>
            </div>
            <div class="progress mt-2" style="height: 4px; background-color: #e2e8f0;">
              <div class="progress-bar bg-success"
                style="width: {{ round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- KURANG --}}
      <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 3px solid #ef4444;">
          <div class="card-body py-3">
            <div class="d-flex align-items-center">
              <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                <i class="bx bx-error-circle text-danger"></i>
              </div>
              <div class="flex-grow-1">
                <div class="text-muted small mb-1">Kurang</div>
                <div class="d-flex justify-content-between align-items-end">
                  <div class="h4 fw-medium mb-0" style="color: #dc2626;">
                    Rp {{ number_format($totalKurang, 0, ',', '.') }}
                  </div>
                  <div class="text-danger small fw-medium">
                    {{ 100 - round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%
                  </div>
                </div>
              </div>
            </div>
            <div class="progress mt-2" style="height: 4px; background-color: #e2e8f0;">
              <div class="progress-bar bg-danger"
                style="width: {{ 100 - round(($totalTerkumpul / max(1,$totalTarget)) * 100) }}%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- LIST KEGIATAN --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
      <div class="card-body">
        {{-- SEARCH --}}
        <div class="mb-4">
          <div class="row align-items-center">
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                  <i class="bx bx-search text-muted"></i>
                </span>
                <input type="text"
                  id="searchKegiatan"
                  class="form-control border-start-0"
                  placeholder="Cari kegiatan...">
              </div>
            </div>
          </div>
        </div>

        <div class="row g-4" id="kegiatanList">
          @foreach($kegiatans as $kegiatan)
          <div class="col-lg-4 col-md-6 kegiatan-item"
            data-nama="{{ strtolower($kegiatan->nama_kegiatan) }}">

            <div class="card border-0 shadow-sm h-100"
              style="border-radius: 12px; transition: all 0.2s ease;"
              onmouseenter="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.08)'"
              onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">

              <div class="card-body d-flex flex-column">
                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="fw-medium mb-0" style="color: #1e293b; line-height: 1.3;">
                    {{ $kegiatan->nama_kegiatan }}
                  </h6>

                  @if($kegiatan->status == 'Perencanaan')
                  <span class="badge rounded-pill px-3 py-1 fw-medium"
                    style="background: #f1f5f9; color: #64748b;">
                    Perencanaan
                  </span>
                  @elseif($kegiatan->status == 'Sedang Berlangsung')
                  <span class="badge rounded-pill px-3 py-1 fw-medium"
                    style="background: #e0f2fe; color: #0369a1;">
                    Berlangsung
                  </span>
                  @elseif($kegiatan->status == 'Selesai')
                  <span class="badge rounded-pill px-3 py-1 fw-medium"
                    style="background: #d1fae5; color: #065f46;">
                    Selesai
                  </span>
                  @endif
                </div>

                {{-- DATE --}}
                <div class="d-flex align-items-center text-muted small mb-3">
                  <i class="bx bx-calendar me-1"></i>
                  {{ $kegiatan->tanggal_pelaksanaan }}
                </div>

                {{-- PROGRESS --}}
                <div class="mb-3">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="text-muted small">Progress</div>
                    <div class="text-muted small fw-medium">{{ round($kegiatan->progress, 2) }}%</div>
                  </div>
                  <div class="progress" style="height: 6px; background-color: #f1f5f9;">
                    <div class="progress-bar"
                      style="width: {{ round($kegiatan->progress, 2) }}%; background: #4a90e2; border-radius: 3px;">
                    </div>
                  </div>
                </div>

                {{-- FUNDING --}}
                <div class="border-top pt-3 mt-2">
                  <div class="row g-2">
                    <div class="col-6">
                      <div class="text-muted small mb-1">Target</div>
                      <div class="fw-medium" style="color: #334155;">
                        Rp {{ number_format($kegiatan->target_dana, 0, ',', '.') }}
                      </div>
                    </div>
                    <div class="col-6 text-end">
                      <div class="text-muted small mb-1">Terkumpul</div>
                      <div class="fw-medium" style="color: #059669;">
                        Rp {{ number_format($kegiatan->terkumpul, 0, ',', '.') }}
                      </div>
                    </div>
                  </div>
                </div>

                {{-- BUTTON --}}
                <a href="{{ route('tu.kegiatan.show', $kegiatan->id) }}"
                  class="btn btn-sm mt-4 d-flex align-items-center justify-content-center gap-2"
                  style="background: #e8f4ff; color: #1976d2; border: 1px solid #bbdefb; font-weight: 500;">
                  <i class="bx bx-show"></i>
                  Lihat Detail
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
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('tu.kegiatan.store') }}"
      method="POST"
      class="modal-content border-0 shadow"
      style="border-radius: 12px;">
      @csrf

      <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
        <h5 class="modal-title fw-medium" style="color: #1e293b;">Tambah Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4">
        <div class="mb-3">
          <label class="form-label mb-2">Nama Kegiatan</label>
          <input type="text"
            name="nama_kegiatan"
            class="form-control border-1"
            style="border-color: #d1d9e6;"
            placeholder="Nama kegiatan"
            required>
        </div>

        <div class="mb-3">
          <label class="form-label mb-2">Tanggal Pelaksanaan</label>
          <input type="date"
            name="tanggal_pelaksanaan"
            class="form-control border-1"
            style="border-color: #d1d9e6;"
            required>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label mb-2">Target Dana</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">Rp</span>
              <input type="text"
                id="target_dana_view"
                class="form-control border-start-0"
                placeholder="0"
                oninput="formatRupiah(this, 'target_dana')"
                required>
              <input type="hidden" name="target_dana" id="target_dana">
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label mb-2">Nominal per Siswa</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0">Rp</span>
              <input type="text"
                id="nominal_view"
                class="form-control border-start-0"
                placeholder="0"
                oninput="formatRupiah(this, 'nominal_per_siswa')"
                required>
              <input type="hidden" name="nominal_per_siswa" id="nominal_per_siswa">
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label class="form-label mb-2">Kelas (boleh lebih dari 1)</label>
          <select name="kelas_ids[]"
            class="form-select border-1"
            style="border-color: #d1d9e6;"
            multiple
            required>
            @foreach(App\Models\Kelas::all() as $kelas)
            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
          </select>
          <div class="text-muted small mt-2">
            Gunakan Ctrl / Cmd untuk memilih banyak kelas
          </div>
        </div>
      </div>

      <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
          Batal
        </button>
        <button type="submit" class="btn px-4" style="background: #4a90e2; color: white; border: none;">
          Simpan Kegiatan
        </button>
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
        if (item.dataset.nama.includes(value)) {
          item.style.display = 'block';
          item.style.opacity = '1';
          item.style.transform = 'scale(1)';
        } else {
          item.style.display = 'none';
        }
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

    input.value = new Intl.NumberFormat('id-ID').format(value);
  }
</script>

<style>
  .card {
    border-radius: 12px;
  }

  .progress {
    border-radius: 3px;
    overflow: hidden;
  }

  .form-select:focus,
  .form-control:focus {
    border-color: #4a90e2 !important;
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1) !important;
  }

  .input-group:focus-within {
    box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    border-radius: 6px;
  }

  .badge {
    font-weight: 500;
    padding: 0.35em 0.85em;
  }

  .modal-content {
    animation: modalFadeIn 0.3s ease-out;
  }

  @keyframes modalFadeIn {
    from {
      opacity: 0;
      transform: translateY(-20px) scale(0.95);
    }

    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  .kegiatan-item {
    transition: all 0.3s ease;
  }
</style>
@endsection