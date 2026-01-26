@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header d-print-none mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title fw-bold">Manajemen Akun Berdasarkan Role</h2>
                <div class="text-muted">Kelola akun pengguna berdasarkan peran masing-masing di sistem.</div>
            </div>
        </div>
    </div>

    <!-- Statistik Role -->
    <div class="row row-cards mb-4">
        @php
        $roles = [
        ['icon' => 'bx bx-chalkboard', 'label' => 'Guru', 'count' => $counts['guru'] ?? 0, 'bg' => 'bg-green-lt'],
        ['icon' => 'bx bx-money', 'label' => 'Payroll', 'count' => $counts['payroll'] ?? 0, 'bg' => 'bg-yellow-lt'],
        ['icon' => 'bx bx-book-content', 'label' => 'TU', 'count' => $counts['tu'] ?? 0, 'bg' => 'bg-orange-lt'],
        ['icon' => 'bx bx-group', 'label' => 'Total', 'count' => $total ?? 0, 'bg' => 'bg-cyan-lt'],
        ];
        @endphp

        @foreach ($roles as $role)
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-2">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="me-3">
                        <div class="icon-lg text-white rounded-circle d-flex align-items-center justify-content-center {{ $role['bg'] }}"
                            style="width:50px; height:50px;">
                            <i class="{{ $role['icon'] }} fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-muted">{{ $role['label'] }}</div>
                        <div class="h3 fw-bold mb-0">{{ $role['count'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- Info tambahan -->
    <div class="alert alert-secondary shadow-sm mb-4" role="alert">
        <i class="bx bx-info-circle me-2"></i>
        Terakhir diperbarui: <strong>{{ $lastUpdate ?? 'Belum ada data' }}</strong>
    </div>

    <!-- Tombol CRUD -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title mb-0">Kelola Akun Role</h4>
        </div>
        <div class="card-body">
            <div class="btn-list">
                <!-- <a href="{{ route('account.index', ['role' => 'admin']) }}" class="btn btn-outline-primary">
                    <i class="bx bx-shield-quarter me-1"></i> Admin
                </a> -->
                <a href="{{ route('account.index', ['role' => 'guru']) }}" class="btn btn-outline-primary">
                    <i class="bx bx-chalkboard me-1"></i> Guru
                </a>
                <a href="{{ route('account.index', ['role' => 'payroll']) }}" class="btn btn-outline-primary">
                    <i class="bx bx-money me-1"></i> Payroll
                </a>
                <!-- <a href="{{ route('account.index', ['role' => 'siswa']) }}" class="btn btn-outline-primary">
                    <i class="bx bx-user me-1"></i> Siswa
                </a> -->
                <a href="{{ route('account.index', ['role' => 'tu']) }}" class="btn btn-outline-primary">
                    <i class="bx bx-book-content me-1"></i> TU
                </a>
            </div>
        </div>
    </div>

    <!-- Akun Terbaru -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Akun Terbaru</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-primary-lt">{{ ucfirst($user->role) }}</span></td>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada akun</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection