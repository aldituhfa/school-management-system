@extends('layouts.superadmin')

@section('title', 'Tambah Jam Belajar')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Tambah Jam Belajar Per Kelas</h3></div>
            <div class="card-body">
                <form action="{{ route('superadmin.jam-belajar.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Jam Belajar</label>
                        <input type="number" id="total_jam_belajar" name="total_jam_belajar" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" readonly>
                        </div>
                    </div>

                    {{-- ===========================
                         ISTIRAHAT PERTAMA
                    ============================ --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 1 Mulai</label>
                            <input type="time" id="ist_mulai" name="waktu_istirahat_mulai" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 1 Selesai</label>
                            <input type="time" id="ist_selesai" name="waktu_istirahat_selesai" class="form-control">
                        </div>
                    </div>

                    {{-- ===========================
                         ISTIRAHAT KEDUA (BARU)
                    ============================ --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 2 Mulai</label>
                            <input type="time" id="ist2_mulai" name="waktu_istirahat2_mulai" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu Istirahat 2 Selesai</label>
                            <input type="time" id="ist2_selesai" name="waktu_istirahat2_selesai" class="form-control">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
    const hitungJamSelesai = () => {
        let jamMulai = document.getElementById("jam_mulai").value;
        let totalJam = parseInt(document.getElementById("total_jam_belajar").value);

        if (!jamMulai || !totalJam) return;

        let [h, m] = jamMulai.split(":").map(Number);

        let start = new Date();
        start.setHours(h, m);

        start.setHours(start.getHours() + totalJam);

        let jam = String(start.getHours()).padStart(2, "0");
        let menit = String(start.getMinutes()).padStart(2, "0");

        document.getElementById("jam_selesai").value = jam + ":" + menit;
    };

    document.getElementById("jam_mulai").addEventListener("change", hitungJamSelesai);
    document.getElementById("total_jam_belajar").addEventListener("input", hitungJamSelesai);
</script>
@endpush
