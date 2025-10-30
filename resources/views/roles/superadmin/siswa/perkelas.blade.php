@extends('layouts.superadmin')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="page-header mb-4">
      <h2 class="page-title">Data Siswa per Kelas</h2>
    </div>

    {{-- FILTER DAN SEARCH --}}
    <div class="card mb-4">
      <div class="card-body">
        <div class="row g-3 align-items-center">
          <div class="col-md-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama kelas...">
          </div>
          <div class="col-md-4">
            <select id="filterKelas" class="form-select">
              <option value="">-- Filter Berdasarkan Kelas --</option>
              @foreach($kelas as $k)
              <option value="{{ strtolower($k->nama_kelas) }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <button id="resetBtn" class="btn btn-outline-secondary w-100">
              <i class="ti ti-refresh"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- LIST KELAS --}}
    <div class="row" id="kelasContainer">
      @forelse($kelas as $k)
      <div class="col-md-4 col-sm-6 mb-3 kelas-card" data-nama="{{ strtolower($k->nama_kelas) }}">
        <div class="card card-borderless shadow-sm h-100">
          <div class="card-body text-center">
            <h3 class="card-title mb-2">{{ $k->nama_kelas }}</h3>
            <p class="text-muted mb-3">
              <i class="ti ti-user"></i> Jumlah Siswa: <strong>{{ $k->siswa_count }}</strong>
            </p>
            <a href="{{ route('siswa.showByKelas', $k->id) }}" class="btn btn-outline-primary w-100">
              <i class="ti ti-users"></i> Lihat Siswa
            </a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center text-muted py-4">
        <i class="ti ti-info-circle me-2"></i>Tidak ada kelas yang tersedia.
      </div>
      @endforelse
    </div>
  </div>
</div>

{{-- SCRIPT UNTUK LIVE SEARCH & FILTER --}}
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

      if (cocokSearch && cocokFilter) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
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
