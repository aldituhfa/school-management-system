@extends('layouts.guru')

@section('content')
<div class="page-body bg-light">
  <div class="container-xl py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0">
        Data Siswa Kelas:
        <span class="text-primary">{{ $kelas->nama_kelas }}</span>
      </h4>

      <a href="{{ route('guru.siswa.perkelas') }}"
         class="btn btn-outline-primary btn-sm">
        <i class="ti ti-arrow-left me-1"></i>
        Kembali ke Daftar Kelas
      </a>
    </div>

    {{-- TABEL --}}
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body">

        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="border-bottom">
              <tr class="text-uppercase text-muted small">
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Tempat / Tanggal Lahir</th>
                <th>Agama</th>

                {{-- kolom tambahan --}}
                @foreach($columns as $col)
                  @if(!in_array($col, [
                    'id','nisn','nama_siswa','jenis_kelamin',
                    'tempat_lahir','tanggal_lahir','kelas_id',
                    'agama','status_id','created_at','updated_at'
                  ]))
                    <th>{{ strtoupper(str_replace('_',' ',$col)) }}</th>
                  @endif
                @endforeach

                <th>Status</th>
              </tr>
            </thead>

            <tbody>
              @forelse($siswa as $index => $item)
              <tr>
                <td>{{ $siswa->firstItem() + $index }}</td>
                <td>{{ $item->nisn }}</td>
                <td class="fw-semibold">{{ $item->nama_siswa }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>
                  {{ $item->tempat_lahir }},
                  {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y') }}
                </td>
                <td>{{ $item->agama }}</td>

                {{-- kolom tambahan --}}
                @foreach($columns as $col)
                  @if(!in_array($col, [
                    'id','nisn','nama_siswa','jenis_kelamin',
                    'tempat_lahir','tanggal_lahir','kelas_id',
                    'agama','status_id','created_at','updated_at'
                  ]))
                    <td>{{ $item->$col }}</td>
                  @endif
                @endforeach

                <td>
                  <span class="badge rounded-pill px-2
                    {{ $item->status->nama_status === 'Aktif'
                        ? 'bg-success-subtle text-success'
                        : 'bg-warning-subtle text-warning' }}">
                    {{ strtolower($item->status->nama_status) }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="100%" class="text-center text-muted py-4">
                  <i class="ti ti-info-circle me-1"></i>
                  Belum ada siswa di kelas ini
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
          {{ $siswa->links('pagination::bootstrap-5') }}
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
