@extends('layouts.payroll')

@section('content')
<div class="container-xl">
    <h2 class="mb-4">Slip Gaji Digital</h2>

    <div class="row">
        {{-- LIST --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Daftar Slip Gaji</h4>

                    @foreach($slips as $slip)
                    <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $slip->user->name }}</strong><br>
                            <small>
                                {{ $slip->period->bulan }}/{{ $slip->period->tahun }}
                            </small>
                        </div>

                        <div class="text-end">
                            <div class="fw-bold">
                                Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}
                            </div>

                            <a href="{{ route('slip_gaji.index', ['selected' => $slip->id]) }}"
                                class="btn btn-sm btn-outline-primary">
                                Lihat
                            </a>

                            <a href="{{ route('slip_gaji.download', $slip->id) }}"
                                class="btn btn-sm btn-dark">
                                Unduh
                            </a>

                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- DETAIL --}}
        <div class="col-md-4">
            @if($selectedSlip)
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Detail Slip</h4>
                    <hr>

                    <p><small>Nama</small><br>
                        <strong>{{ $selectedSlip->user->name }}</strong>
                    </p>

                    <p><small>Periode</small><br>
                        {{ $selectedSlip->period->bulan }}/{{ $selectedSlip->period->tahun }}
                    </p>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Gaji Pokok</span>
                        <strong>Rp {{ number_format($selectedSlip->gaji_pokok, 0, ',', '.') }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5">
                        <strong>Gaji Bersih</strong>
                        <strong>Rp {{ number_format($selectedSlip->gaji_pokok, 0, ',', '.') }}</strong>
                    </div>

                    <a href="{{ route('slip_gaji.download', $selectedSlip->id) }}"
                        class="btn btn-dark w-100 mt-3">
                        Unduh Slip
                    </a>

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection