@extends('layouts.payroll')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="page-header mb-4">
            <h2 class="page-title fw-bold">Data Penggajian</h2>
            <div class="text-muted">Kelola data gaji pegawai dari seluruh role</div>
        </div>

        <div class="card shadow-sm bg-muted-lt border-0">
            <div class="table-responsive">
                <table class="table table-vcenter table-hover card-table">
                    <thead class="bg-dark-lt">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Gaji Pokok</th>
                            <th>Status Aktif</th>
                            <th>Status Penggajian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>

                            <td class="text-muted">
                                {{ $user->email }}
                            </td>

                            <td>
                                <span class="badge bg-indigo">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>

                            <td class="fw-bold text-dark">
                                Rp {{ number_format($user->payrollSetting->gaji_pokok ?? 0,0,',','.') }}
                            </td>

                            <td>
                                @if($user->payrollSetting)
                                <span class="badge {{ $user->payrollSetting->status_aktif ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->payrollSetting->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @else
                                <span class="badge bg-secondary-lt">Belum di set</span>
                                @endif
                            </td>

                            <td>
                                @if($user->payrollSetting)
                                <span class="badge {{ $user->payrollSetting->status_penggajian ? 'bg-primary' : 'bg-warning text-dark' }}">
                                    {{ $user->payrollSetting->status_penggajian ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @else
                                <span class="badge bg-secondary-lt">Belum di set</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('payroll.data_penggajian.edit',$user->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-edit"></i> Set Gaji
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection