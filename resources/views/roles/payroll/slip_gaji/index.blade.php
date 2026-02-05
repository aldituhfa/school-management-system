@extends('layouts.payroll')

@section('content')
<div class="container-xl">
    <div class="page-header mb-4">
        <h2 class="page-title" style="font-weight: 600; color: #2c3e50;">Slip Gaji Digital</h2>
        <div class="text-muted">Lihat dan unduh slip gaji pegawai</div>
    </div>

    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                {{-- SEARCH --}}
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bx bx-search text-muted"></i>
                        </span>
                        <input type="text"
                            id="searchInput"
                            class="form-control border-start-0"
                            placeholder="Ketik nama pegawai...">
                    </div>
                </div>

                {{-- FILTER PERIODE --}}
                <div class="col-md-4">
                    <select id="periodFilter" class="form-select">
                        <option value="all">Semua Periode</option>
                        @foreach($slips->unique(fn($s) => $s->period->bulan.$s->period->tahun) as $s)
                        <option value="{{ $s->period->bulan }}-{{ $s->period->tahun }}">
                            {{ DateTime::createFromFormat('!m',$s->period->bulan)->format('F') }}
                            {{ $s->period->tahun }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- RESET --}}
                <div class="col-md-3">
                    <button id="resetFilter" class="btn btn-outline-secondary w-100 py-2">
                        <i class="bx bx-reset me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- LIST SLIP --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h4 class="card-title mb-0" style="color: #1e293b; font-weight: 600;">Daftar Slip Gaji</h4>
                </div>
                <div class="card-body p-0">
                    <div id="slipList" class="list-group list-group-flush">
                        @foreach($slips as $slip)
                        <div class="list-group-item border-0 slip-item py-3 px-4"
                            data-name="{{ strtolower($slip->user->name) }}"
                            data-period="{{ $slip->period->bulan }}-{{ $slip->period->tahun }}"
                            style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;"
                            onmouseenter="this.style.backgroundColor='#f8fafc'"
                            onmouseleave="this.style.backgroundColor=''">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-medium mb-1" style="color: #334155;">{{ $slip->user->name }}</div>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                                            <i class="bx bx-calendar me-1"></i>
                                            {{ DateTime::createFromFormat('!m',$slip->period->bulan)->format('F') }}
                                            {{ $slip->period->tahun }}
                                        </span>
                                        <div class="text-muted small">
                                            <i class="bx bx-dollar-circle me-1"></i>
                                            Rp {{ number_format($slip->gaji_pokok,0,',','.') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('slip_gaji.index', ['selected' => $slip->id]) }}"
                                        class="btn btn-sm d-flex align-items-center gap-1"
                                        style="background: #e8f4ff; color: #1976d2; border: 1px solid #bbdefb;">
                                        <i class="bx bx-show"></i> Lihat
                                    </a>

                                    <a href="{{ route('slip_gaji.download', $slip->id) }}"
                                        class="btn btn-sm d-flex align-items-center gap-1"
                                        style="background: #1e293b; color: white; border: none;">
                                        <i class="bx bx-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="emptyState" class="text-center text-muted py-5 d-none">
                        <i class="bx bx-file bx-lg mb-3 opacity-50"></i>
                        <div class="h5">Tidak ada slip gaji yang cocok</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DETAIL SLIP --}}
        <div class="col-lg-4">
            @if(request()->filled('selected') && $selectedSlip)
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h4 class="card-title mb-0" style="color: #1e293b; font-weight: 600;">
                        <i class="bx bx-file me-2"></i>Detail Slip
                    </h4>
                </div>
                <div class="card-body">
                    {{-- INFO PEGAWAI --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bx bx-user text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="color: #334155;">{{ $selectedSlip->user->name }}</div>
                                <div class="text-muted small">{{ $selectedSlip->user->email }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bx bx-calendar text-info"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Periode</div>
                                <div class="fw-medium">
                                    {{ DateTime::createFromFormat('!m',$selectedSlip->period->bulan)->format('F') }}
                                    {{ $selectedSlip->period->tahun }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RINCIAN GAJI --}}
                    <div class="border rounded p-3 mb-4" style="background-color: #f8fafc;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted">Gaji Pokok</div>
                            <div class="fw-bold" style="color: #065f46;">
                                Rp {{ number_format($selectedSlip->gaji_pokok,0,',','.') }}
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-medium">Gaji Bersih</div>
                            <div class="fw-bold h5" style="color: #065f46;">
                                Rp {{ number_format($selectedSlip->gaji_pokok,0,',','.') }}
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('slip_gaji.download', $selectedSlip->id) }}"
                        class="btn w-100 d-flex align-items-center justify-content-center gap-2"
                        style="background: #1e293b; color: white; border: none; font-weight: 500; padding: 10px;">
                        <i class="bx bx-download"></i>
                        Unduh Slip Gaji
                    </a>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 100%;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-5">
                    <i class="bx bx-file bx-lg mb-3" style="color: #cbd5e1;"></i>
                    <div class="h5 text-muted mb-2">Pilih slip gaji</div>
                    <div class="text-muted">Klik tombol "Lihat" untuk melihat detail slip</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- JS --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const periodFilter = document.getElementById('periodFilter');
        const resetBtn = document.getElementById('resetFilter');
        const slips = document.querySelectorAll('.slip-item');
        const emptyState = document.getElementById('emptyState');

        function filterSlip() {
            const search = searchInput.value.toLowerCase();
            const period = periodFilter.value;
            let visible = 0;

            slips.forEach(slip => {
                const name = slip.dataset.name;
                const slipPeriod = slip.dataset.period;

                const matchName = name.includes(search);
                const matchPeriod = period === 'all' || slipPeriod === period;

                if (matchName && matchPeriod) {
                    slip.style.display = 'block';
                    visible++;
                } else {
                    slip.style.display = 'none';
                }
            });

            emptyState.classList.toggle('d-none', visible > 0);
        }

        searchInput.addEventListener('input', filterSlip);
        periodFilter.addEventListener('change', filterSlip);

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            periodFilter.value = 'all';
            filterSlip();
        });

        // Initialize
        filterSlip();
    });
</script>

<style>
    .card {
        border-radius: 12px;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .sticky-top {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
</style>
@endsection