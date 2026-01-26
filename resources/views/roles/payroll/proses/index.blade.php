@extends('layouts.payroll')

@section('content')
<div class="container-xl">

    {{-- HEADER --}}
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title fw-bold">Proses Penggajian</h2>
            <div class="text-muted">
                Periode aktif:
                <strong>
                    {{ DateTime::createFromFormat('!m',$period->bulan)->format('F') }}
                    {{ $period->tahun }}
                </strong>
            </div>
        </div>

        {{-- TUTUP PERIODE --}}
        <form method="POST"
            action="{{ route('payroll.proses.close',$period->id) }}"
            onsubmit="return confirm('Tutup periode penggajian? Pastikan semua pegawai sudah dibayar')">
            @csrf
            <button class="btn btn-danger">
                <i class="bx bx-lock"></i> Tutup Periode
            </button>
        </form>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- CARD --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Daftar Pegawai</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-hover card-table align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Gaji Pokok</th>
                        <th>Status Pembayaran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)

                    @php
                    $history = $u->payrollHistories->first();
                    $paid = $history && $history->status === 'paid';
                    @endphp
                    
                    <tr>
                        <td class="fw-semibold">{{ $u->name }}</td>

                        <td>
                            <span class="badge bg-blue text-white">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>

                        <td>
                            Rp {{ number_format($u->payrollSetting->gaji_pokok,0,',','.') }}
                        </td>

                        <td>
                            @if($paid)
                            <span class="badge bg-success">
                                <i class="bx bx-check"></i> DIBAYAR
                            </span>
                            @else
                            <span class="badge bg-warning text-dark">
                                <i class="bx bx-time"></i> BELUM DIBAYAR
                            </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">
                            @if(!$paid)
                            <form action="{{ route('payroll.proses.pay', [$period->id, $u->id]) }}" method="POST">
                                @csrf
                                <select name="source" required class="form-select form-select-sm mb-1">
                                    <option value="">Sumber Dana</option>
                                    <option value="kas">Kas</option>
                                    <option value="dana_bos">BOS</option>
                                </select>
                                <button class="btn btn-success btn-sm w-100">
                                    <i class="bx bx-money"></i> Bayar
                                </button>
                            </form>
                            @else
                            <form method="POST"
                                action="{{ route('payroll.proses.cancel', [$period->id, $u->id]) }}">
                                @csrf
                                <button class="btn btn-sm btn-warning w-100"
                                    onclick="return confirm('Batalkan pembayaran gaji?')">
                                    <i class="bx bx-x-circle"></i> Cancel
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @if($users->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Tidak ada pegawai yang bisa diproses
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection