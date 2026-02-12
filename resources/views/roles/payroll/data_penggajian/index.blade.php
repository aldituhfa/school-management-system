@extends('layouts.payroll')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- PAGE HEADER --}}
        <div class="page-header mb-4">
            <h2 class="page-title" style="font-weight: 500; color: var(--tblr-secondary);">
                Data Penggajian
            </h2>
            <div class="text-muted mt-1">
                Kelola data gaji pegawai dari seluruh role
            </div>
        </div>

        {{-- FILTER ROLE --}}
        <div class="mb-4 d-flex gap-2 flex-wrap">
            <a href="{{ route('payroll.data_penggajian.index') }}"
                class="btn btn-pill {{ empty($role) ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>

            <a href="{{ route('payroll.data_penggajian.index', ['role' => 'super_admin']) }}"
                class="btn btn-pill {{ ($role ?? '') === 'super_admin' ? 'btn-primary' : 'btn-outline-primary' }}">
                Super Admin
            </a>

            <a href="{{ route('payroll.data_penggajian.index', ['role' => 'guru']) }}"
                class="btn btn-pill {{ ($role ?? '') === 'guru' ? 'btn-primary' : 'btn-outline-primary' }}">
                Guru
            </a>

            <a href="{{ route('payroll.data_penggajian.index', ['role' => 'payroll']) }}"
                class="btn btn-pill {{ ($role ?? '') === 'payroll' ? 'btn-primary' : 'btn-outline-primary' }}">
                Payroll
            </a>
        </div>

        {{-- CARD --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <div class="px-4 pt-4 pb-2">
                        <div class="input-icon">
                            <input type="text"
                                id="liveSearch"
                                class="form-control"
                                placeholder="Cari nama atau email...">
                        </div>
                    </div>

                    <table class="table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th class="text-muted text-uppercase small fw-normal">Nama</th>
                                <th class="text-muted text-uppercase small fw-normal">Email</th>
                                <th class="text-muted text-uppercase small fw-normal">Gaji Pokok</th>
                                <th class="text-muted text-uppercase small fw-normal">Status Pegawai</th>
                                <th class="text-muted text-uppercase small fw-normal">Status Penggajian</th>
                                <th class="text-muted text-uppercase small fw-normal text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="payrollTable">
                            @forelse($users as $user)
                            <tr>
                                <td class="fw-medium">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td class="text-muted">
                                    Rp {{ number_format($user->payrollSetting->gaji_pokok ?? 0,0,',','.') }}
                                </td>
                                {{-- STATUS AKTIF --}}
                                <td>
                                    @if($user->payrollSetting)
                                    <span class="badge {{ $user->payrollSetting->status_aktif
            ? 'bg-success-lt text-success'
            : 'bg-danger-lt text-danger' }}">
                                        {{ $user->payrollSetting->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary-lt text-secondary">
                                        Belum di set
                                    </span>
                                    @endif
                                </td>

                                {{-- STATUS PENGGAJIAN --}}
                                <td>
                                    @if($user->payrollSetting)
                                    <span class="badge {{ $user->payrollSetting->status_penggajian
            ? 'bg-primary-lt text-primary'
            : 'bg-warning-lt text-warning' }}">
                                        {{ $user->payrollSetting->status_penggajian ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary-lt text-secondary">
                                        Belum di set
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('payroll.data_penggajian.edit',$user->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-edit me-1"></i> Set Gaji
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="bx bx-user-x bx-lg mb-3" style="opacity: 0.5;"></i>
                                        <div>Data tidak ditemukan</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const searchInput = document.getElementById('liveSearch');
    const rows = document.querySelectorAll('#payrollTable tr');

    searchInput.addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
</script>

@endsection