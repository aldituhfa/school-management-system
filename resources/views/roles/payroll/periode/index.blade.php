@extends('layouts.payroll')

@section('content')
<div class="container-xl">
    <div class="page-header mb-4">
        <h2 class="page-title fw-bold">Periode Penggajian</h2>
        <div class="text-muted">Kelola periode gaji bulanan</div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="bx bx-error-circle"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Form Tambah --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('periode-penggajian.store') }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4">
                        <select name="bulan" class="form-select">
                            @for($i=1;$i<=12;$i++)
                                <option value="{{ $i }}">{{ DateTime::createFromFormat('!m',$i)->format('F') }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100">
                            <i class="bx bx-plus"></i> Tambah Periode
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover card-table">
                <thead class="bg-muted-lt">
                    <tr>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Tanggal Proses</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periods as $p)
                    <tr>
                        <td class="fw-semibold">
                            {{ DateTime::createFromFormat('!m',$p->bulan)->format('F') }} {{ $p->tahun }}
                        </td>
                        <td>
                            <span class="badge 
                                {{ $p->status == 'open' ? 'bg-secondary' :
                                   ($p->status == 'processing' ? 'bg-warning' : 'bg-success') }}">
                                {{ strtoupper($p->status) }}
                            </span>
                        </td>
                        <td>{{ $p->tanggal_proses ?? '-' }}</td>
                        <td class="text-center">
                            @if($p->status != 'closed')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">

                                    {{-- PROSES --}}
                                    @if($p->status == 'open')
                                    <form method="POST" action="{{ route('periode-penggajian.update',$p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="process">
                                        <button class="dropdown-item">
                                            <i class="bx bx-play"></i> Proses
                                        </button>
                                    </form>
                                    @endif

                                    {{-- CANCEL --}}
                                    @if($p->status == 'processing')
                                    <form method="POST" action="{{ route('periode-penggajian.update',$p->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="cancel">
                                        <button class="dropdown-item text-warning">
                                            <i class="bx bx-x-circle"></i> Cancel
                                        </button>
                                    </form>
                                    @endif

                                    {{-- HAPUS --}}
                                    <form method="POST"
                                        action="{{ route('periode-penggajian.destroy',$p->id) }}"
                                        onsubmit="return confirm('Hapus periode ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </form>

                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection