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
            <tr class="table-secondary fw-semibold">
              <td colspan="8">
                Tahun Lulus {{ $tahun }}
                <span class="text-muted">
                  ({{ $list->count() }} siswa)
                </span>
              </td>
            </tr>

            @php $no = 1; @endphp

            @foreach($list as $s)
            <tr>
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
</script>
@endsection