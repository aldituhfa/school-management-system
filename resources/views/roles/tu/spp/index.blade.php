@extends('layouts.tu')

@section('content')
<div class="container mt-4">
    <h2>Pembayaran SPP</h2>
    <a href="{{ route('tu.spp.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Diproses Oleh</th>
                <th>Tanggal Dibayar</th>
                <th>Aksi</th> <!-- 👈 Kolom aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach($spps as $index => $spp)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $spp->student_name }}</td>
                <td>{{ $spp->student_identifier ?? '-' }}</td>
                <td>{{ $spp->month }}</td>
                <td>{{ $spp->year }}</td>
                <td>{{ number_format($spp->amount,0,',','.') }}</td>
                <td>
                    @if($spp->status == 'paid')
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                    @endif
                </td>
                <td>{{ $spp->tu ? $spp->tu->name : '-' }}</td>
                <td>{{ $spp->paid_at ? $spp->paid_at->format('d-m-Y H:i') : '-' }}</td>
                <td>
                    {{-- Tombol Bayar --}}
                    @if($spp->status == 'unpaid')
                        <form action="{{ route('tu.spp.pay', $spp->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Bayar</button>
                        </form>
                    @endif

                    {{-- Tombol Edit --}}
                    <a href="{{ route('tu.spp.edit', $spp->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('tu.spp.destroy', $spp->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
