{{-- resources/views/roles/superadmin/finances.blade.php --}}
@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Manajemen Keuangan</h1>

    {{-- Card Saldo --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">Saldo Dana BOS</h5>
                    <h3>{{ number_format($totals['dana_bos']['saldo'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">Saldo Kas</h5>
                    <h3>{{ number_format($totals['kas']['saldo'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Saldo</h5>
                    <h3>{{ number_format($totals['total_saldo'] ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3">Grafik Pemasukan & Pengeluaran</h5>
            <canvas id="financeChart" height="100"></canvas>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Daftar Transaksi --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h5>Daftar Transaksi</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFinanceModal">
                    Tambah Transaksi
                </button>
            </div>

            {{-- Tabel Dana BOS --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5>Daftar Transaksi Dana BOS</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>In/Out</th>
                                <th>User</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($danaBos as $finance)
                            <tr>
                                <td>{{ $finance->id }}</td>
                                <td>{{ $finance->category }}</td>
                                <td>{{ number_format($finance->amount, 0, ',', '.') }}</td>
                                <td>{{ strtoupper($finance->in_out) }}</td>
                                <td>{{ $finance->user->name ?? '-' }}</td>
                                <td>{{ $finance->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $finance->description ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editFinanceModal{{ $finance->id }}">Edit</button>
                                    <form action="{{ route('finances.destroy', $finance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editFinanceModal{{ $finance->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('finances.update', $finance->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Transaksi</h5>
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
                                                    <input type="text" name="category" class="form-control" value="{{ $finance->category }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Jumlah</label>
                                                    <input type="number" name="amount" class="form-control" value="{{ $finance->amount }}" required>
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
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada transaksi Dana BOS</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $danaBos->links() }}
                </div>
            </div>

            {{-- Tabel Kas --}}
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5>Daftar Transaksi Kas</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>In/Out</th>
                                <th>User</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kas as $finance)
                            <tr>
                                <td>{{ $finance->id }}</td>
                                <td>{{ $finance->category }}</td>
                                <td>{{ number_format($finance->amount, 0, ',', '.') }}</td>
                                <td>{{ strtoupper($finance->in_out) }}</td>
                                <td>{{ $finance->user->name ?? '-' }}</td>
                                <td>{{ $finance->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $finance->description ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editFinanceModal{{ $finance->id }}">Edit</button>
                                    <form action="{{ route('finances.destroy', $finance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus transaksi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editFinanceModal{{ $finance->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('finances.update', $finance->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Transaksi</h5>
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
                                                    <input type="text" name="category" class="form-control" value="{{ $finance->category }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Jumlah</label>
                                                    <input type="number" name="amount" class="form-control" value="{{ $finance->amount }}" required>
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
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada transaksi Kas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $kas->links() }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Tambah Transaksi --}}
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
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const totals = @json($totals ?? new stdClass());
    const ctx = document.getElementById('financeChart').getContext('2d');
    const financeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Dana BOS', 'Kas'],
            datasets: [
                {
                    label: 'Pemasukan',
                    data: [totals.dana_bos.in, totals.kas.in],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                },
                {
                    label: 'Pengeluaran',
                    data: [totals.dana_bos.out, totals.kas.out],
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
