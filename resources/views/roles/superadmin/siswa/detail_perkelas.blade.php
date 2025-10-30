@extends('layouts.superadmin')

@section('content')
<div class="page-body">
  <div class="container-xl">

    {{-- Header --}}
    <div class="page-header mb-4">
      <div class="row align-items-center">
        <div class="col">
          <h2 class="page-title">
            Data Siswa Kelas: <span class="text-primary">{{ $kelas->nama_kelas }}</span>
          </h2>
        </div>
        <div class="col-auto">
          <a href="{{ route('siswa.perkelas') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Kembali ke Daftar Kelas
          </a>
        </div>
      </div>
    </div>

    {{-- Tabel Data Siswa --}}
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Tempat/Tanggal Lahir</th>
                <th>Agama</th>
                {{-- kolom tambahan --}}
                @foreach($columns as $col)
                  @if(!in_array($col, ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at']))
                    <th>{{ ucfirst(str_replace('_',' ',$col)) }}</th>
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
                <td><strong>{{ $item->nama_siswa }}</strong></td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y') }}</td>
                <td>{{ $item->agama }}</td>

                {{-- kolom tambahan --}}
                @foreach($columns as $col)
                  @if(!in_array($col, ['id','nisn','nama_siswa','jenis_kelamin','tempat_lahir','tanggal_lahir','kelas_id','agama','status_id','created_at','updated_at']))
                    <td>{{ $item->$col }}</td>
                  @endif
                @endforeach

                <td>
                  <span class="badge bg-{{ $item->status->nama_status == 'Aktif' ? 'green' : 'orange' }}-lt">
                    {{ $item->status->nama_status }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="100%" class="text-center text-muted py-4">
                  <i class="ti ti-info-circle me-2"></i>Belum ada siswa di kelas ini.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $siswa->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
