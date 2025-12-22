@extends('layouts.superadmin')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Log Finance</h3>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="row g-2 align-items-center">

            {{-- Filter Jenis --}}
            <div class="col-md-3">
                <select name="type" id="typeFilter" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="dana_bos" {{ request('type') == 'dana_bos' ? 'selected' : '' }}>Dana BOS</option>
                    <option value="kas" {{ request('type') == 'kas' ? 'selected' : '' }}>Kas</option>
                </select>
            </div>

            {{-- Filter Aksi --}}
            <div class="col-md-3">
                <select name="action" id="actionFilter" class="form-select">
                    <option value="">Semua Aksi</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                </select>
            </div>

            {{-- Reset --}}
            <div class="col-md-2">
                <button id="resetBtn" class="btn btn-outline-secondary w-100">
                    Reset
                </button>
            </div>

            {{-- Spacer --}}
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end align-items-center gap-2">
                    <a href="{{ route('logs.finances.export.pdf', request()->query()) }}"
                        class="btn btn-outline-danger btn-md shadow-sm px-3">
                        <i class="ti ti-file-type-pdf me-1"></i> PDF
                    </a>

                    <a href="{{ route('logs.finances.export.excel', request()->query()) }}"
                        class="btn btn-outline-success btn-md shadow-sm px-3">
                        <i class="ti ti-file-spreadsheet me-1"></i> Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Aksi</th>
                    <th>Jumlah Sebelum</th>
                    <th>Jumlah Sesudah</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>User</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $logs->firstItem() + $loop->index }}</td>
                    <td>
                        @if($log->action === 'create')
                        <span class="badge bg-green-lt">Create</span>
                        @elseif($log->action === 'update')
                        <span class="badge bg-yellow-lt">Update</span>
                        @elseif($log->action === 'delete')
                        <span class="badge bg-red-lt">Delete</span>
                        @else
                        <span class="badge bg-blue-lt">{{ ucfirst($log->action) }}</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($log->before_amount ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($log->after_amount ?? 0, 0, ',', '.') }}</td>
                    <td><span class="badge bg-azure-lt">{{ ucfirst($log->type) }}</span></td>
                    <td>{{ $log->meta }}</td>
                    <td>{{ $log->user->name ?? '-' }}</td>
                    <td class="text-muted">{{ $log->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <form action="{{ route('logs.finances.destroy', $log->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus log ini?')"
                            style="display:inline-block;">
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
                    <td colspan="9" class="text-center py-4">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-muted">
            Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data
        </p>
        <ul class="pagination m-0 ms-auto">
            {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
        </ul>
    </div>
</div>

{{-- Variabel URL route --}}
<script>
    const routeUrl = "{{ route('logs.finances') }}";
</script>

{{-- JS Filter + Reset --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeFilter = document.getElementById('typeFilter');
        const actionFilter = document.getElementById('actionFilter');
        const resetBtn = document.getElementById('resetBtn');

        function performFilter() {
            const typeValue = typeFilter.value;
            const actionValue = actionFilter.value;

            let url = new URL(routeUrl);
            let params = new URLSearchParams();

            if (typeValue) params.append('type', typeValue);
            if (actionValue) params.append('action', actionValue);

            window.location.href = url + '?' + params.toString();
        }

        typeFilter.addEventListener('change', performFilter);
        actionFilter.addEventListener('change', performFilter);

        resetBtn.addEventListener('click', function() {
            typeFilter.value = '';
            actionFilter.value = '';
            window.location.href = routeUrl;
        });
    });
</script>

@endsection