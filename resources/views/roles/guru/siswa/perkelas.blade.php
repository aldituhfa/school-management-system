@extends('layouts.guru')

@section('content')
<div class="page-body bg-light">
  <div class="container-xl py-4">

    {{-- HEADER --}}
    <div class="mb-4">
      <h3 class="fw-bold mb-0">Data Siswa per Kelas</h3>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="row g-3 align-items-center">
          <div class="col-md-4">
            <input 
              type="text" 
              id="searchInput" 
              class="form-control"
              placeholder="Cari nama kelas..."
            >
          </div>

          <div class="col-md-4">
            <select id="filterKelas" class="form-select">
              <option value="">-- Filter Berdasarkan Kelas --</option>
              @foreach($kelas as $k)
                <option value="{{ strtolower($k->nama_kelas) }}">
                  {{ $k->nama_kelas }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <button id="resetBtn" class="btn btn-outline-primary w-100">
              <i class="ti ti-refresh me-1"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- LIST KELAS --}}
    <div class="row" id="kelasContainer">
      @forelse($kelas as $k)
      <div 
        class="col-lg-4 col-md-6 mb-4 kelas-card"
        data-nama="{{ strtolower($k->nama_kelas) }}"
      >
        <div class="card h-100 border-0 shadow-sm rounded-3">
          <div class="card-body text-center py-4">

            <h5 class="fw-bold mb-2">
              {{ $k->nama_kelas }}
            </h5>

            <div class="text-muted small mb-3">
              <i class="ti ti-user me-1"></i>
              Jumlah Siswa: {{ $k->siswa_count }}
            </div>

           <a 
              href="{{ route('siswa.showByKelas', $k->id) }}"
              class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center"
            >
              <i class="ti ti-users me-1"></i> Lihat Siswa
            </a>


          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center text-muted py-5">
        <i class="ti ti-info-circle me-1"></i>
        Tidak ada kelas yang tersedia
      </div>
      @endforelse
    </div>

  </div>
</div>

{{-- SCRIPT SEARCH & FILTER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
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
