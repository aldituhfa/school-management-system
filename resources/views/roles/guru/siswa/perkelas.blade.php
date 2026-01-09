@extends('layouts.guru')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- HEADER --}}
    <div class="page-header mb-4">
      <h2 class="page-title fw-semibold">Data Siswa per Kelas</h2>
      <p class="text-muted mb-0">Ringkasan jumlah siswa berdasarkan kelas</p>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="row g-3 align-items-center">

          <div class="col-md-4">
            <div class="input-icon">
              <span class="input-icon-addon">
                <i class="ti ti-search"></i>
              </span>
              <input type="text"
                id="searchInput"
                class="form-control"
                placeholder="Cari kelas...">
            </div>
          </div>

          <div class="col-md-4">
            <select id="filterKelas" class="form-select">
              <option value="">Semua Kelas</option>
              @foreach($kelas as $k)
              <option value="{{ strtolower($k->nama_kelas) }}">
                {{ $k->nama_kelas }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <button id="resetBtn" class="btn btn-outline-secondary w-100">
              <i class="ti ti-refresh me-1"></i> Reset
            </button>
          </div>

        </div>
      </div>
    </div>

    {{-- LIST KELAS --}}
    <div class="row g-3" id="kelasContainer">

      @forelse($kelas as $k)
      <div class="col-md-4 col-sm-6 kelas-card"
        data-nama="{{ strtolower($k->nama_kelas) }}">

        <div class="card border-0 shadow-sm h-100">
          <div class="card-body py-3 px-3">

            <div class="d-flex align-items-center gap-3">

              {{-- ICON KIRI --}}
              <span class="avatar bg-primary-lt">
                <i class="ti ti-school text-primary"></i>
              </span>

              {{-- INFO --}}
              <div class="flex-grow-1">
                <div class="fw-semibold">{{ $k->nama_kelas }}</div>
                <div class="text-muted small">
                  {{ $k->siswa_count }} siswa
                </div>
              </div>

              {{-- BUTTON --}}
              <a href="{{ route('siswa.showByKelas', $k->id) }}"
                class="btn btn-sm btn-outline-primary">
                <i></i> Detail Siswa
              </a>

            </div>

          </div>
        </div>

      </div>
      @empty

      <div class="col-12">
        <div class="text-center text-muted py-5">
          <i class="ti ti-info-circle fs-1 mb-2"></i>
          <div>Tidak ada data kelas</div>
        </div>
      </div>

      @endforelse

    </div>

  </div>
</div>

{{-- SCRIPT LIVE SEARCH & FILTER --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterKelas = document.getElementById('filterKelas');
    const resetBtn = document.getElementById('resetBtn');
    const cards = document.querySelectorAll('.kelas-card');

    function filterCards() {
      const searchValue = searchInput.value.toLowerCase();
      const filterValue = filterKelas.value.toLowerCase();

      cards.forEach(card => {
        const nama = card.getAttribute('data-nama');
        const cocokSearch = nama.includes(searchValue);
        const cocokFilter = !filterValue || nama === filterValue;

        card.style.display = (cocokSearch && cocokFilter) ? '' : 'none';
      });
    }

    searchInput.addEventListener('input', filterCards);
    filterKelas.addEventListener('change', filterCards);
    resetBtn.addEventListener('click', () => {
      searchInput.value = '';
      filterKelas.value = '';
      filterCards();
    });
  });
</script>
@endsection