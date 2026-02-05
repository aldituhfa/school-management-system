@extends('layouts.payroll')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- PAGE HEADER --}}
        <div class="page-header mb-3 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="page-title fs-3">
                    Setting Gaji
                </h2>
                <div class="text-muted">
                    {{ $user->name }}
                </div>
            </div>

            {{-- BUTTON KEMBALI --}}
            <a href="{{ route('payroll.data_penggajian.index') }}"
                class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i>
                Kembali
            </a>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Gagal menyimpan</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('payroll.data_penggajian.update',$user->id) }}">
            @csrf

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="row g-3">

                        {{-- GAJI POKOK --}}
                        <div class="col-md-6">
                            <label class="form-label">Gaji Pokok</label>
                            <input type="text"
                                id="gaji_pokok_view"
                                class="form-control"
                                placeholder="Rp 0"
                                value="{{ number_format($user->payrollSetting->gaji_pokok ?? 0,0,',','.') }}">
                            <input type="hidden"
                                name="gaji_pokok"
                                id="gaji_pokok">
                        </div>

                        {{-- STATUS AKTIF --}}
                        <div class="col-md-3">
                            <label class="form-label">Status Aktif</label>
                            <select name="status_aktif" class="form-select">
                                <option value="1"
                                    {{ old('status_aktif', $user->payrollSetting->status_aktif ?? 1) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="0"
                                    {{ old('status_aktif', $user->payrollSetting->status_aktif ?? 1) == 0 ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                        {{-- STATUS PENGGAJIAN --}}
                        <div class="col-md-3">
                            <label class="form-label">Status Penggajian</label>
                            <select name="status_penggajian" class="form-select">
                                <option value="1"
                                    {{ old('status_penggajian', $user->payrollSetting->status_penggajian ?? 1) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="0"
                                    {{ old('status_penggajian', $user->payrollSetting->status_penggajian ?? 1) == 0 ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-end">
                    <button class="btn btn-primary">
                        <i class="bx bx-save me-1"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- SCRIPT FORMAT RUPIAH --}}
<script>
    const rupiahInput = document.getElementById('gaji_pokok_view');
    const realInput = document.getElementById('gaji_pokok');

    function formatRupiah(value) {
        return value
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    rupiahInput.addEventListener('input', function() {
        const raw = this.value.replace(/\D/g, '');
        this.value = 'Rp ' + formatRupiah(raw);
        realInput.value = raw;
    });

    // set nilai awal
    realInput.value = rupiahInput.value.replace(/\D/g, '');
</script>
@endsection