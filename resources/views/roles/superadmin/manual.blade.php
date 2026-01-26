@extends('layouts.superadmin')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold">Transaksi Manual</h2>
                    <p class="text-muted">
                        Input transaksi keuangan manual (di luar sistem payroll)
                    </p>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addManualModal">
                        <i class="ti ti-plus"></i> Tambah Transaksi
                    </button>
                </div>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- TABLE --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">

                {{-- SEARCH --}}
                <div class="mb-3">
                    <input type="text" id="searchInput"
                        class="form-control"
                        placeholder="Cari kategori / deskripsi...">
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>NO</th>
                                <th>Jenis Dana</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>In / Out</th>
                                <th>Source</th>
                                <th>User</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($manuals as $i => $finance)
                            <tr>
                                <td>{{ $manuals->firstItem() + $i }}</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ strtoupper($finance->type) }}
                                    </span>
                                </td>
                                <td>{{ $finance->category }}</td>
                                <td>
                                    Rp {{ number_format($finance->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($finance->in_out === 'in')
                                    <span class="badge bg-success-subtle text-success">IN</span>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger">OUT</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        MANUAL
                                    </span>
                                </td>
                                <td>{{ $finance->user->name ?? '-' }}</td>
                                <td>{{ $finance->created_at->format('d-m-Y') }}</td>
                                <td>{{ $finance->description ?? '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editManualModal{{ $finance->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('finances.destroy', $finance->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Hapus transaksi manual ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Belum ada transaksi manual
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-3">
                    {{ $manuals->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL ADD ================= --}}
<div class="modal fade" id="addManualModal">
    <div class="modal-dialog">
        <form action="{{ route('finances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="source" value="manual">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi Manual</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Jenis Dana</label>
                        <select name="type" class="form-control" required>
                            <option value="dana_bos">Dana BOS</option>
                            <option value="kas">Kas</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Kategori</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="text"
                            class="form-control rupiah"
                            placeholder="Rp 0"
                            autocomplete="off"
                            required>

                        <input type="hidden"
                            name="amount"
                            class="amount-real">
                    </div>

                    <div class="mb-2">
                        <label>In / Out</label>
                        <select name="in_out" class="form-control" required>
                            <option value="in">Pemasukan</option>
                            <option value="out">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
@foreach($manuals as $finance)
<div class="modal fade" id="editManualModal{{ $finance->id }}">
    <div class="modal-dialog">
        <form action="{{ route('finances.update', $finance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi Manual</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>Jenis Dana</label>
                        <select name="type" class="form-control">
                            <option value="dana_bos" {{ $finance->type == 'dana_bos' ? 'selected' : '' }}>Dana BOS</option>
                            <option value="kas" {{ $finance->type == 'kas' ? 'selected' : '' }}>Kas</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Kategori</label>
                        <input type="text" name="category"
                            class="form-control"
                            value="{{ $finance->category }}">
                    </div>

                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="text"
                            class="form-control rupiah"
                            placeholder="Rp 0"
                            autocomplete="off"
                            required>

                        <input type="hidden"
                            name="amount"
                            class="amount-real">
                    </div>

                    <div class="mb-2">
                        <label>In / Out</label>
                        <select name="in_out" class="form-control">
                            <option value="in" {{ $finance->in_out == 'in' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="out" {{ $finance->in_out == 'out' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control">{{ $finance->description }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
    });


    function formatRupiah(angka) {
        return 'Rp ' + angka
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.querySelectorAll('.rupiah').forEach(input => {
        const hidden = input.closest('form').querySelector('.amount-real');

        input.addEventListener('input', function() {
            let raw = this.value.replace(/\D/g, '');
            this.value = raw ? formatRupiah(raw) : '';
            hidden.value = raw;
        });
    });
</script>
@endpush