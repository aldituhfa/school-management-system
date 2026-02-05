@extends('layouts.payroll')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="border-left: 4px solid #28a745; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="bx bx-check-circle fs-4 me-3" style="color: #28a745;"></i>
                <div class="flex-grow-1">
                    <strong class="me-2">Berhasil!</strong>
                    <span class="text-dark">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        {{-- ALERT ERROR --}}
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert"
            style="border-left: 4px solid #dc3545; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="bx bx-error-circle fs-4 me-3" style="color: #dc3545;"></i>
                <div class="flex-grow-1">
                    <strong class="me-2">Gagal!</strong>
                    <span class="text-dark">{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <h2 class="page-title fw-normal mb-1" style="color: #2c3e50;">Periode Penggajian</h2>
                <div class="text-muted">Kelola periode gaji bulanan dengan mudah</div>
            </div>

            <button class="btn btn-primary px-4 py-2"
                data-bs-toggle="collapse"
                data-bs-target="#formPeriode"
                style="background: linear-gradient(135deg, #3498db, #2980b9); border: none;">
                <i class="bx bx-plus me-2"></i>
                Buat Periode Baru
            </button>
        </div>

        {{-- FORM COLLAPSE --}}
        <div class="collapse mb-5" id="formPeriode">
            <div class="card border-0 shadow-lg" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('periode-penggajian.store') }}">
                        @csrf
                        <div class="row g-4 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-medium mb-2" style="color: #5a6c7d;">Bulan</label>
                                <select name="bulan" class="form-select border-1" style="border-color: #e0e6ed;">
                                    @for($i=1;$i<=12;$i++)
                                        <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m',$i)->format('F') }}
                                        </option>
                                        @endfor
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium mb-2" style="color: #5a6c7d;">Tahun</label>
                                <input type="number"
                                    name="tahun"
                                    class="form-control border-1"
                                    style="border-color: #e0e6ed;"
                                    value="{{ date('Y') }}"
                                    min="2020"
                                    max="2030">
                            </div>

                            <div class="col-md-4">
                                <button class="btn w-100 py-2"
                                    style="background: linear-gradient(135deg, #3498db, #2980b9); border: none; color: white; font-weight: 500;">
                                    <i class="bx bx-check me-2"></i>
                                    Simpan Periode
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- CONTAINER --}}
        <div class="card border-0 shadow-lg" style="border-radius: 16px;">
            <div class="card-body p-4">

                {{-- FILTER --}}
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background-color: #f8fafc;">
                                <i class="bx bx-search text-muted"></i>
                            </span>
                            <input type="text"
                                id="searchInput"
                                class="form-control border-0"
                                style="background-color: #f8fafc;"
                                placeholder="Cari periode...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select id="filterBulan" class="form-select border-0" style="background-color: #f8fafc;">
                            <option value="">Semua Bulan</option>
                            @for($i=1;$i<=12;$i++)
                                <option value="{{ DateTime::createFromFormat('!m',$i)->format('F') }}">
                                {{ DateTime::createFromFormat('!m',$i)->format('F') }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="filterTahun" class="form-select border-0" style="background-color: #f8fafc;">
                            <option value="">Semua Tahun</option>
                            @foreach($periods->pluck('tahun')->unique()->sortDesc() as $th)
                            <option value="{{ $th }}">{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- RESET --}}
                    <div class="col-md-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100 border-1">
                            <i class="bx bx-reset me-1"></i> Reset
                        </button>
                    </div>
                </div>

                {{-- CARD LIST --}}
                <div class="row g-4" id="periodeWrapper">
                    @forelse($periods as $p)
                    <div class="col-md-4 periode-card"
                        data-bulan="{{ DateTime::createFromFormat('!m',$p->bulan)->format('F') }}"
                        data-tahun="{{ $p->tahun }}">

                        <div class="card h-100 border-0 shadow-sm"
                            style="border-radius: 12px; border-left: 4px solid #3498db; transition: all 0.3s ease;"
                            onmouseenter="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.1)'"
                            onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'">
                            <div class="card-body p-4">

                                {{-- HEADER --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <div class="fs-5 fw-medium periode-title mb-1" style="color: #2c3e50;">
                                            {{ DateTime::createFromFormat('!m',$p->bulan)->format('F') }}
                                            {{ $p->tahun }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bx bx-calendar me-1"></i>
                                            {{ $p->tanggal_proses ? date('d M Y', strtotime($p->tanggal_proses)) : 'Belum diproses' }}
                                        </div>
                                    </div>

                                    <span class="badge px-3 py-2 rounded-pill fw-medium
                                        @if($p->status == 'open')
                                            bg-info-lt text-info
                                        @elseif($p->status == 'processing')
                                            bg-warning-lt text-warning
                                        @elseif($p->status == 'closed')
                                            bg-success-lt text-success
                                        @else
                                            bg-secondary-lt text-secondary
                                        @endif">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </div>

                                {{-- INFO BOX --}}
                                <div class="bg-light rounded p-3 mb-4" style="background-color: #f8fafc;">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3" style="color: #3498db;">
                                            <i class="bx bx-calendar-check bx-sm"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Periode Aktif</div>
                                            <div class="fw-medium">
                                                {{ date('d', strtotime($p->created_at)) }} -
                                                {{ date('t', strtotime($p->created_at)) }}/{{ date('m/Y', strtotime($p->created_at)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION --}}
                                @if($p->status != 'closed')
                                <div class="d-flex gap-2">

                                    {{-- PROSES --}}
                                    @if($p->status == 'open')
                                    <form method="POST"
                                        action="{{ route('periode-penggajian.update',$p->id) }}"
                                        class="w-100">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="process">
                                        <button class="btn btn-sm w-100 py-2"
                                            style="background: #e3f2fd; border: 1px solid #bbdefb; color: #1976d2; font-weight: 500; transition: all 0.2s;"
                                            onmouseenter="this.style.background='#d1eaff'; this.style.transform='translateY(-1px)'"
                                            onmouseleave="this.style.background='#e3f2fd'; this.style.transform='translateY(0)'">
                                            <i class="bx bx-play me-2"></i> Proses
                                        </button>
                                    </form>
                                    @endif

                                    {{-- CANCEL --}}
                                    @if($p->status == 'processing')
                                    <form method="POST"
                                        action="{{ route('periode-penggajian.update',$p->id) }}"
                                        class="w-100">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="cancel">
                                        <button class="btn btn-sm w-100 py-2"
                                            style="background: #fff3e0; border: 1px solid #ffe0b2; color: #f57c00; font-weight: 500; transition: all 0.2s;"
                                            onmouseenter="this.style.background='#ffeacc'; this.style.transform='translateY(-1px)'"
                                            onmouseleave="this.style.background='#fff3e0'; this.style.transform='translateY(0)'">
                                            <i class="bx bx-x-circle me-2"></i> Cancel
                                        </button>
                                    </form>
                                    @endif

                                    {{-- HAPUS --}}
                                    <form method="POST"
                                        action="{{ route('periode-penggajian.destroy',$p->id) }}"
                                        onsubmit="return confirm('Hapus periode ini?')"
                                        class="w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm w-100 py-2"
                                            style="background: #ffebee; border: 1px solid #ffcdd2; color: #d32f2f; font-weight: 500; transition: all 0.2s;"
                                            onmouseenter="this.style.background='#ffdde0'; this.style.transform='translateY(-1px)'"
                                            onmouseleave="this.style.background='#ffebee'; this.style.transform='translateY(0)'">
                                            <i class="bx bx-trash me-2"></i> Hapus
                                        </button>
                                    </form>

                                </div>
                                @endif

                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="py-4">
                            <i class="bx bx-calendar-x bx-lg mb-3" style="color: #bdc3c7;"></i>
                            <div class="h5 text-muted mb-2">Tidak ada periode penggajian</div>
                            <div class="text-muted">Mulai dengan membuat periode baru</div>
                        </div>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>

{{-- LIVE SEARCH + RESET --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const search = document.getElementById('searchInput');
        const bulan = document.getElementById('filterBulan');
        const tahun = document.getElementById('filterTahun');
        const reset = document.getElementById('resetFilter');
        const cards = document.querySelectorAll('.periode-card');

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                setTimeout(() => {
                    bsAlert.close();
                }, 5000);
            });
        }, 500);

        function filterData() {
            const s = search.value.toLowerCase();
            const b = bulan.value;
            const t = tahun.value;

            cards.forEach(card => {
                const title = card.querySelector('.periode-title').innerText.toLowerCase();
                const cb = card.dataset.bulan;
                const ct = card.dataset.tahun;

                const matchSearch = title.includes(s);
                const matchBulan = !b || cb === b;
                const matchTahun = !t || ct === t;

                if (matchSearch && matchBulan && matchTahun) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        }

        reset.addEventListener('click', () => {
            search.value = '';
            bulan.value = '';
            tahun.value = '';
            filterData();
        });

        search.addEventListener('input', filterData);
        bulan.addEventListener('change', filterData);
        tahun.addEventListener('change', filterData);

        // Initialize animation
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        });

        setTimeout(() => {
            cards.forEach(card => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            });
        }, 100);
    });
</script>

<style>
    .alert {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .periode-card {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection