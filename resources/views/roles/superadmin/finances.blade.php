@extends('layouts.superadmin')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Header --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title fw-bold">Manajemen Keuangan</h2>
                    <p class="text-muted">Kelola transaksi keuangan Dana BOS & Kas Sekolah</p>
                </div>
            </div>
        </div>

        {{-- Card Saldo --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Saldo Dana BOS</h6>
                        <h3 class="fw-bold text-primary">{{ number_format($totals['dana_bos']['saldo'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Saldo Kas</h6>
                        <h3 class="fw-bold text-success">{{ number_format($totals['kas']['saldo'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Saldo</h6>
                        <h3 class="fw-bold text-dark">{{ number_format($totals['total_saldo'] ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Grafik Pemasukan & Pengeluaran</h5>
                <canvas id="financeChart" height="100"></canvas>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Daftar Transaksi --}}
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">

                {{-- Header Action --}}
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFinanceModal">
                        <i class="ti ti-plus me-1"></i> Tambah Transaksi
                    </button>
                </div>

                {{-- Search & Filter --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <input type="text" id="searchInput" class="form-control w-auto flex-fill" placeholder="Cari transaksi...">
                    <select id="filterType" class="form-select w-auto">
                        <option value="">Semua Dana</option>
                        <option value="dana_bos">Dana BOS</option>
                        <option value="kas">Dana Kas</option>
                    </select>
                    <select id="filterInOut" class="form-select w-auto">
                        <option value="">Semua In/Out</option>
                        <option value="in">Pemasukan</option>
                        <option value="out">Pengeluaran</option>
                    </select>
                    <button class="btn btn-outline-secondary" id="resetFilter">Reset</button>
                </div>

                {{-- Tabel Dana BOS --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="fw-semibold mb-3">Transaksi DANA BOS</h4>
                        <div class="table-responsive">
                            <table id="tableDanaBos" class="table table-vcenter text-nowrap table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>In/Out</th>
                                        <th>User</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($danaBos as $finance)
                                    <tr>
                                        <td>{{ $finance->id }}</td>
                                        <td>{{ $finance->category }}</td>
                                        <td>{{ number_format($finance->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($finance->in_out == 'in')
                                            <span class="badge bg-success-subtle text-success">IN</span>
                                            @else
                                            <span class="badge bg-danger-subtle text-danger">OUT</span>
                                            @endif
                                        </td>
                                        <td>{{ $finance->user->name ?? '-' }}</td>
                                        <td>{{ $finance->created_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ $finance->description ?? '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#editFinanceModal{{ $finance->id }}">Edit</button>
                                            <form action="{{ route('finances.destroy', $finance->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Yakin hapus transaksi?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada transaksi Dana BOS</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $danaBos->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>

                {{-- Tabel Kas --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-semibold mb-3">Transaksi KAS</h4>
                        <div class="table-responsive">
                            <table id="tableKas" class="table table-vcenter text-nowrap table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                        <th>In/Out</th>
                                        <th>User</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kas as $finance)
                                    <tr>
                                        <td>{{ $finance->id }}</td>
                                        <td>{{ $finance->category }}</td>
                                        <td>{{ number_format($finance->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($finance->in_out == 'in')
                                            <span class="badge bg-success-subtle text-success">IN</span>
                                            @else
                                            <span class="badge bg-danger-subtle text-danger">OUT</span>
                                            @endif
                                        </td>
                                        <td>{{ $finance->user->name ?? '-' }}</td>
                                        <td>{{ $finance->created_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ $finance->description ?? '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                data-bs-target="#editFinanceModal{{ $finance->id }}">Edit</button>
                                            <form action="{{ route('finances.destroy', $finance->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Yakin hapus transaksi?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada transaksi Kas</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $kas->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addFinanceModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('finances.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Jenis</label>
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
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>In/Out</label>
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
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
@foreach($danaBos as $finance)
<div class="modal fade" id="editFinanceModal{{ $finance->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('finances.update', $finance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi Dana BOS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Jenis</label>
                        <select name="type" class="form-control" required>
                            <option value="dana_bos" {{ $finance->type == 'dana_bos' ? 'selected' : '' }}>Dana BOS</option>
                            <option value="kas" {{ $finance->type == 'kas' ? 'selected' : '' }}>Kas</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Kategori</label>
                        <input type="text" name="category" value="{{ $finance->category }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="number" name="amount" value="{{ $finance->amount }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>In/Out</label>
                        <select name="in_out" class="form-control" required>
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
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Edit untuk Kas --}}
@foreach($kas as $finance)
<div class="modal fade" id="editFinanceModal{{ $finance->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('finances.update', $finance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi Kas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Jenis</label>
                        <select name="type" class="form-control" required>
                            <option value="dana_bos" {{ $finance->type == 'dana_bos' ? 'selected' : '' }}>Dana BOS</option>
                            <option value="kas" {{ $finance->type == 'kas' ? 'selected' : '' }}>Kas</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Kategori</label>
                        <input type="text" name="category" value="{{ $finance->category }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="number" name="amount" value="{{ $finance->amount }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>In/Out</label>
                        <select name="in_out" class="form-control" required>
                            <option value="in" {{ $finance->in_out == 'in' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="out" {{ $finance->in_out == 'out' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control">{{ $finance->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer" <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const totals = @json($totals ?? new stdClass());
    const ctx = document.getElementById('financeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Dana BOS', 'Kas'],
            datasets: [{
                    label: 'Pemasukan',
                    data: [totals.dana_bos.in, totals.kas.in],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Pengeluaran',
                    data: [totals.dana_bos.out, totals.kas.out],
                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => new Intl.NumberFormat('id-ID').format(v)
                    }
                }
            }
        }
    });

    // --- FILTER & SEARCH ---
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterType = document.getElementById('filterType');
        const filterInOut = document.getElementById('filterInOut');
        const resetBtn = document.getElementById('resetFilter');
        const rows = document.querySelectorAll('tbody tr');

        function filterTable() {
            const q = searchInput.value.toLowerCase();
            const t = filterType.value;
            const io = filterInOut.value;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const type = row.closest('table').id.includes('DanaBos') ? 'dana_bos' : 'kas';
                const inout = row.querySelector('span.badge')?.innerText.toLowerCase() || '';

                const matchSearch = text.includes(q);
                const matchType = !t || type === t;
                const matchIO = !io || inout.includes(io);

                row.style.display = (matchSearch && matchType && matchIO) ? '' : 'none';
            });
        }

        [searchInput, filterType, filterInOut].forEach(el => el.addEventListener('input', filterTable));
        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            filterType.value = '';
            filterInOut.value = '';
            filterTable();
        });
    });
</script>
@endpush