@extends('layouts.guru')

@section('content')
<div class="page-body bg-light">
  <div class="container-xl py-4">

    {{-- HEADER --}}
    <div class="mb-4">
      <h3 class="fw-bold mb-0">Jadwal Mengajar</h3>
      <p class="text-muted mb-0">Daftar kelas yang Anda ajar</p>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('guru.jadwal.index') }}">
          <div class="row g-3 align-items-center">
            <div class="col-md-6">
              <input 
                type="text" 
                name="search"
                id="searchInput" 
                class="form-control"
                placeholder="Cari nama kelas..."
                value="{{ request('search') }}"
              >
            </div>

            <div class="col-md-3">
              <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-search me-1"></i> Cari
              </button>
            </div>

            <div class="col-md-3">
              @if(request('search'))
              <a href="{{ route('guru.jadwal.index') }}" class="btn btn-outline-primary w-100">
                <i class="ti ti-refresh me-1"></i> Reset
              </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- LIST KELAS --}}
    @if($kelas_list->isEmpty())
      {{-- Empty State --}}
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <div class="empty-img mb-3">
            <img src="{{ asset('tabler/static/illustrations/undraw_printing_invoices_5r4r.svg') }}"
                 height="120" alt="">
          </div>
          <h4 class="fw-bold mb-2">Tidak ada kelas ditemukan</h4>
          <p class="text-muted mb-3">
            @if(request('search'))
              Tidak ada kelas dengan kata kunci "{{ request('search') }}"
            @else
              Anda belum memiliki jadwal mengajar.
            @endif
          </p>
          @if(request('search'))
          <a href="{{ route('guru.jadwal.index') }}" class="btn btn-primary">
            Tampilkan Semua Kelas
          </a>
          @endif
        </div>
      </div>
    @else
      {{-- Card Grid --}}
      <div class="row" id="kelasContainer">
        @foreach($kelas_list as $kelas)
        <div class="col-lg-4 col-md-6 mb-4 kelas-card">
          <div class="card h-100 border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-4">

              {{-- Avatar Icon --}}
              <div class="mb-3">
                <span class="avatar avatar-xl rounded bg-primary text-white">
                  {{ strtoupper(substr($kelas->nama_kelas, 0, 2)) }}
                </span>
              </div>

              {{-- Nama Kelas --}}
              <h5 class="fw-bold mb-2">
                {{ $kelas->nama_kelas }}
              </h5>

              {{-- Info --}}
              <div class="text-muted small mb-2">
                <i class="ti ti-calendar me-1"></i>
                {{ $kelas->jumlah_jadwal }} Jadwal
              </div>

              <div class="text-muted small mb-3">
                <i class="ti ti-clock me-1"></i>
                {{ $kelas->jumlah_hari }} Hari Mengajar
              </div>

              {{-- Button --}}
              <a 
                href="{{ route('guru.jadwal.show', $kelas->id) }}"
                class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center"
              >
                <i class="ti ti-calendar me-1"></i> Lihat Jadwal
              </a>

            </div>
          </div>
        </div>
        @endforeach
      </div>
    @endif

  </div>
</div>
@endsection