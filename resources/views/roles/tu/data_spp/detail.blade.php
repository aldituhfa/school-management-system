@extends('layouts.tu')

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="page-header d-print-none mb-4">
      <h2 class="page-title">Detail Data Siswa</h2>
      <a href="{{ route('tu.data_spp.index') }}" class="btn btn-secondary mt-2">← Kembali</a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Detail Siswa --}}
    <div class="card mb-4">
      <div class="card-body">
        <table class="table table-bordered">
          <tbody>
            @foreach($siswa->getAttributes() as $key => $value)
            @if(!in_array($key, ['id','kelas_id','status_id','created_at','updated_at']))
            <tr>
              <th>{{ ucfirst(str_replace('_',' ',$key)) }}</th>
              <td>
                @if($key === 'tanggal_lahir' && $value)
                {{ \Carbon\Carbon::parse($value)->translatedFormat('d M Y') }}
                @else
                {{ $value ?? '-' }}
                @endif
              </td>
            </tr>
            @endif
            @endforeach
            <tr>
              <th>Kelas</th>
              <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>{{ $siswa->status->nama_status ?? '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- Table Tagihan SPP --}}
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tagihan SPP - {{ $tahunAjaran->nama_tahun }}</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>Bulan</th>
              <th>Nominal</th>
              <th>Tanggal Bayar</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tagihan as $t)
            <tr>
              <td>{{ $t->bulan }}</td>
              <td>Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
              <td>{{ $t->tanggal_bayar ? \Carbon\Carbon::parse($t->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
              <td>
                <span class="badge {{ $t->status == 'lunas' ? 'bg-success' : 'bg-warning' }}">
                  {{ ucfirst($t->status) }}
                </span>
              </td>
              <td>{{ $t->keterangan ?? '-' }}</td>
              <td>
                @if($t->status == 'belum lunas')
                <form method="POST" action="{{ route('tu.data_spp.bayar', $t->id) }}" onsubmit="return confirm('Yakin ingin menandai bulan {{ $t->bulan }} sebagai lunas?')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-primary">Bayar</button>
                </form>
                @else
                <span class="text-success">✓ Lunas</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection