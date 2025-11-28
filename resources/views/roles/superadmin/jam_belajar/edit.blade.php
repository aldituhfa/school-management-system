@extends('layouts.superadmin')

@section('title', 'Edit Jam Belajar')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="card">
            <div class="card-header"><h3 class="card-title">Edit Jam Belajar Per Kelas</h3></div>

            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('superadmin.jam-belajar.update', $jamBelajar->id) }}" method="POST">
                    @csrf 
                    @method('PUT')

                    {{-- KELAS --}}
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            @foreach($kelas as $k)
                                @php
                                    $sudahDipakai = \App\Models\JamBelajar::where('kelas_id', $k->id)
                                        ->where('id', '!=', $jamBelajar->id)
                                        ->exists();
                                @endphp
                                <option value="{{ $k->id }}"
                                    {{ $jamBelajar->kelas_id == $k->id ? 'selected' : '' }}
                                    {{ $sudahDipakai ? 'disabled' : '' }}>
                                    {{ $k->nama_kelas }} {{ $sudahDipakai ? '(sudah dipakai)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOTAL JAM BELAJAR --}}
                    <div class="mb-3">
                        <label class="form-label">Total Jam Belajar</label>
                        <input type="number" id="total_jam_belajar" name="total_jam_belajar"
                            value="{{ $jamBelajar->total_jam_belajar }}" class="form-control" required>
                    </div>

                    {{-- JAM MULAI & SELESAI --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai"
                                value="{{ \Carbon\Carbon::parse($jamBelajar->jam_mulai)->format('H:i') }}"
                                class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" id="jam_selesai" class="form-control"
                                value="{{ \Carbon\Carbon::parse($jamBelajar->jam_selesai)->format('H:i') }}"
                                readonly>
                            <input type="hidden" id="jam_selesai_hidden" name="jam_selesai"
                                value="{{ \Carbon\Carbon::parse($jamBelajar->jam_selesai)->format('H:i') }}">
                        </div>
                    </div>

                    {{-- ISTIRAHAT 1 --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 1 Mulai</label>
                            <input type="time" name="waktu_istirahat_mulai"
                                value="{{ $jamBelajar->waktu_istirahat_mulai ? \Carbon\Carbon::parse($jamBelajar->waktu_istirahat_mulai)->format('H:i') : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 1 Selesai</label>
                            <input type="time" name="waktu_istirahat_selesai"
                                value="{{ $jamBelajar->waktu_istirahat_selesai ? \Carbon\Carbon::parse($jamBelajar->waktu_istirahat_selesai)->format('H:i') : '' }}"
                                class="form-control">
                        </div>
                    </div>

                    {{-- ISTIRAHAT 2 (BARU) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 2 Mulai</label>
                            <input type="time" name="waktu_istirahat2_mulai"
                                value="{{ $jamBelajar->waktu_istirahat2_mulai ? \Carbon\Carbon::parse($jamBelajar->waktu_istirahat2_mulai)->format('H:i') : '' }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 2 Selesai</label>
                            <input type="time" name="waktu_istirahat2_selesai"
                                value="{{ $jamBelajar->waktu_istirahat2_selesai ? \Carbon\Carbon::parse($jamBelajar->waktu_istirahat2_selesai)->format('H:i') : '' }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('superadmin.jam-belajar.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function hitungJamSelesai() {
        let jamMulai = document.getElementById("jam_mulai").value;
        let totalJam = parseInt(document.getElementById("total_jam_belajar").value);

        if (!jamMulai || !totalJam) return;

        let [h, m] = jamMulai.split(":").map(Number);
        let start = new Date();
        start.setHours(h, m);
        start.setHours(start.getHours() + totalJam);

        let jam = String(start.getHours()).padStart(2, "0");
        let menit = String(start.getMinutes()).padStart(2, "0");

        let hasil = jam + ":" + menit;

        document.getElementById("jam_selesai").value = hasil;
        document.getElementById("jam_selesai_hidden").value = hasil;
    }

    document.getElementById("jam_mulai").addEventListener("change", hitungJamSelesai);
    document.getElementById("total_jam_belajar").addEventListener("input", hitungJamSelesai);
</script>
@endpush
