@extends('layouts.superadmin')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- TITLE --}}
    <div class="d-flex align-items-center mb-3">
      <h1 class="page-title">Data Siswa Lulus</h1>
    </div>

    {{-- FILTER CARD --}}
    <div class="card mb-3">
      <div class="card-body">
        <form method="GET" id="filterForm" class="row g-3 align-items-center">

          {{-- SEARCH --}}
          <div class="col-md-4">
            <input type="text"
              name="search"
              id="searchInput"
              value="{{ request('search') }}"
              class="form-control"
              placeholder="Cari nama atau NISN...">
          </div>

          {{-- FILTER TAHUN --}}
          <div class="col-md-3">
            <select name="tahun_lulus" id="tahunFilter" class="form-select">
              <option value="">Semua Tahun</option>
              @foreach($tahunList as $tahun)
              <option value="{{ $tahun }}"
                {{ request('tahun_lulus') == $tahun ? 'selected' : '' }}>
                {{ $tahun }}
              </option>
              @endforeach
            </select>
          </div>

          {{-- RESET --}}
          <div class="col-md-2">
            <a href="{{ route('siswa.lulus.index') }}"
              class="btn btn-outline-secondary w-100">
              Reset
            </a>
          </div>

          {{-- EXPORT BUTTON --}}
          <div class="col-md-3 text-end">
            <a href="{{ route('siswa.lulus.export.excel', request()->query()) }}"
              class="btn btn-outline-success me-1">
              Excel
            </a>

            <a href="{{ route('siswa.lulus.export.pdf', request()->query()) }}"
              class="btn btn-outline-danger">
              PDF
            </a>
          </div>

        </form>
      </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover table-vcenter">

          <thead class="table-light">
            <tr class="text-muted text-uppercase">
              <th width="60">No</th>
              <th>NISN</th>
              <th>Nama Siswa</th>
              <th>Jenis Kelamin</th>
              <th>Agama</th>
              <th>Status</th>
              <th>Tahun Lulus</th>
              <th class="text-center" width="100">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($siswa as $tahun => $list)

            {{-- HEADER TAHUN --}}
            <tr class="table-secondary fw-semibold tahun-header"
              data-tahun="{{ $tahun }}">
              <td colspan="8">
                <div class="d-flex justify-content-between align-items-center">

                  <div>
                    Tahun Lulus {{ $tahun }}
                    <span class="text-muted">
                      ({{ $list->total() }} siswa)
                    </span>
                  </div>

                  {{-- MENU TITIK 3 --}}
                  <div class="dropdown">
                    <button
                      class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 px-2"
                      data-bs-toggle="dropdown"
                      data-bs-display="static"
                      aria-expanded="false"
                      style="border-radius:8px">
                      <i class="ti ti-dots-vertical"></i>
                      <i class="ti ti-chevron-down"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm dropdown-fixed">
                      {{-- HIDE / SHOW --}}
                      <li>
                        <button
                          type="button"
                          class="dropdown-item d-flex align-items-center gap-2 toggle-siswa"
                          data-tahun="{{ $tahun }}">
                          <i class="ti ti-eye-off"></i>
                          <span>Hide Siswa</span>
                        </button>
                      </li>

                      <li>
                        <hr class="dropdown-divider">
                      </li>

                      {{-- DELETE ALL --}}
                      <li>
                        <form
                          action="{{ route('siswa.lulus.deleteAll', $tahun) }}"
                          method="POST"
                          onsubmit="return confirm(
                'Apakah Anda yakin ingin menghapus SEMUA siswa lulus tahun {{ $tahun }}?\n\nData akan dihapus permanen!'
              )">
                          @csrf
                          @method('DELETE')

                          <button
                            type="submit"
                            class="dropdown-item text-danger d-flex align-items-center gap-2">
                            <i class="ti ti-trash"></i>
                            <span>Delete All</span>
                          </button>
                        </form>
                      </li>

                    </ul>
                  </div>

                </div>
              </td>
            </tr>


            @php
            $no = ($list->currentPage() - 1) * $list->perPage() + 1;
            @endphp

            @foreach($list as $s)
            <tr class="siswa-row siswa-{{ $tahun }}">
              <td>{{ $no++ }}</td>
              <td>{{ $s->nisn }}</td>
              <td class="fw-medium">{{ $s->nama_siswa }}</td>
              <td>{{ $s->jenis_kelamin }}</td>
              <td>{{ $s->agama }}</td>
              <td>
                <span class="badge bg-success-lt">Lulus</span>
              </td>
              <td>{{ $s->tahun_lulus }}</td>
              <td class="text-center">
                <form action="{{ route('siswa.lulus.destroy', $s->id) }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    Hapus
                  </button>
                </form>
              </td>
            </tr>
            @endforeach

            {{-- PAGINATION PER TAHUN --}}
            @if ($list->hasPages())
            <tr>
              <td colspan="8">
                <div class="d-flex justify-content-end py-2">
                  {{ $list->links('pagination::bootstrap-5') }}
                </div>
              </td>
            </tr>
            @endif

            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                Data tidak ditemukan
              </td>
            </tr>
            @endforelse
          </tbody>

        </table>
      </div>
    </div>

  </div>
</div>

{{-- LIVE SEARCH --}}
<script>
  const searchInput = document.getElementById('searchInput');
  const tahunFilter = document.getElementById('tahunFilter');
  const form = document.getElementById('filterForm');

  let typingTimer;

  searchInput.addEventListener('keyup', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
      form.submit();
    }, 500);
  });

  tahunFilter.addEventListener('change', function() {
    form.submit();
  });


  /* ===============================
     DROPDOWN POSITION (SUDAH ADA)
  =============================== */
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    const button = dropdown.querySelector('[data-bs-toggle="dropdown"]');
    const menu = dropdown.querySelector('.dropdown-menu');

    button.addEventListener('click', () => {
      const rect = button.getBoundingClientRect();
      menu.style.top = rect.bottom + 'px';
      menu.style.left = (rect.right - menu.offsetWidth) + 'px';
    });
  });

  /* ===============================
     HIDE / SHOW SISWA + PERSIST
  =============================== */
  document.querySelectorAll('.toggle-siswa').forEach(btn => {
    const tahun = btn.dataset.tahun;
    const rows = document.querySelectorAll('.siswa-' + tahun);

    if (!rows.length) return;

    const icon = btn.querySelector('i');
    const text = btn.querySelector('span');

    /* ---- CEK STATUS SAAT PAGE LOAD ---- */
    const savedState = localStorage.getItem('hide_tahun_' + tahun);

    if (savedState === 'true') {
      rows.forEach(row => row.style.display = 'none');
      icon.className = 'ti ti-eye';
      text.innerText = 'Show Siswa';
    }

    /* ---- SAAT DIKLIK ---- */
    btn.addEventListener('click', function() {
      const isHidden = rows[0].style.display === 'none';

      rows.forEach(row => {
        row.style.display = isHidden ? '' : 'none';
      });

      if (isHidden) {
        localStorage.setItem('hide_tahun_' + tahun, 'false');
        icon.className = 'ti ti-eye-off';
        text.innerText = 'Hide Siswa';
      } else {
        localStorage.setItem('hide_tahun_' + tahun, 'true');
        icon.className = 'ti ti-eye';
        text.innerText = 'Show Siswa';
      }
    });
  });
</script>

<style>
  .dropdown-fixed {
    position: fixed !important;
    z-index: 1060;
  }
</style>
@endsection