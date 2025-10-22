@extends('layouts.tu')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none mb-4">
            <h2 class="page-title">Detail Data Siswa</h2>
            <a href="{{ route('tu.data_spp.index') }}" class="btn btn-secondary mt-2">← Kembali</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        @foreach($siswa->getAttributes() as $key => $value)
                            @if(!in_array($key, ['id', 'kelas_id', 'status_id', 'created_at', 'updated_at']))
                                <tr>
                                    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
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
    </div>
</div>
@endsection
