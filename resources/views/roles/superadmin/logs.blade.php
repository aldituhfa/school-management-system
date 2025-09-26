{{-- resources/views/roles/superadmin/logs.blade.php --}}
@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Riwayat Transaksi</h1>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('logs.finances') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Jenis Transaksi</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="dana_bos" {{ request('type') == 'dana_bos' ? 'selected' : '' }}>Dana BOS</option>
                        <option value="kas" {{ request('type') == 'kas' ? 'selected' : '' }}>Kas</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="action" class="form-label">Aksi</label>
                    <select name="action" id="action" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="user" class="form-label">User</label>
                    <select name="user_id" id="user" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('logs.finances') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Logs --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Aksi</th>
                        <th>Jumlah Sebelum</th>
                        <th>Jumlah Sesudah</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>
                            <td>
                                @if($log->action === 'create')
                                    <span class="badge bg-success">Create</span>
                                @elseif($log->action === 'update')
                                    <span class="badge bg-warning text-dark">Update</span>
                                @elseif($log->action === 'delete')
                                    <span class="badge bg-danger">Delete</span>
                                @else
                                    <span class="badge bg-info">{{ ucfirst($log->action) }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($log->before_amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($log->after_amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($log->type) }}</td>
                            <td>{{ $log->meta }}</td>
                            <td>{{ $log->user->name ?? '-' }}</td>
                            <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
