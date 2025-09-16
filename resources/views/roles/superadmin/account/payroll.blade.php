@extends('layouts.superadmin')

@section('content')
<div class="container mt-3">
    <h3>Manajemen Akun Payroll</h3>

    <!-- Tombol Tambah -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addPayrollModal">
        + Tambah Payroll
    </button>

    <!-- Tabel data -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th><th>Email</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                        data-bs-target="#editPayrollModal{{ $u->id }}">
                        Edit
                    </button>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editPayrollModal{{ $u->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('account.update', $u->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Payroll</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="name" value="{{ $u->name }}" class="form-control mb-2" required>
                                        <input type="email" name="email" value="{{ $u->email }}" class="form-control mb-2" required>
                                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ganti">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-warning">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Hapus -->
                    <form action="{{ route('account.destroy', $u->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addPayrollModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('account.store', 'payroll') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
