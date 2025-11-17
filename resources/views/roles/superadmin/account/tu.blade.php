@extends('layouts.superadmin')

@section('content')
<div class="container-xl mt-4">

    {{-- SweetAlert 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Tombol OK untuk Error */
        .swal2-confirm.btn-error {
            background-color: #d63939 !important; /* Merah Tabler */
            color: #fff !important;
            border-radius: 8px !important;
            padding: 8px 22px !important;
            font-weight: 600 !important;
        }
        .swal2-confirm.btn-error:hover {
            background-color: #b92f2f !important;
        }

        /* Tombol OK untuk Success (edit/update) */
        .swal2-confirm.btn-success-custom {
            background-color: #2fb344 !important; /* Hijau Tabler */
            color: #fff !important;
            border-radius: 8px !important;
            padding: 8px 22px !important;
            font-weight: 600 !important;
        }
        .swal2-confirm.btn-success-custom:hover {
            background-color: #249937 !important;
        }
    </style>

    {{-- Alert Error --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn-error'
                }
            });
        </script>
    @endif

    {{-- Alert Sukses (Tambah / Edit) --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn-success-custom'
                }
            });
        </script>
    @endif

    {{-- Tombol Kembali --}}
    <a href="{{ route('account.main') }}" class="btn btn-outline-secondary mb-3">
        &lt;
    </a>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold m-0">Manajemen Akun Tata Usaha</h3>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTuModal">
                Tambah TU
            </button>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-vcenter table-striped table-hover card-table">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width: 35%; padding:14px;">Nama</th>
                        <th style="width: 35%; padding:14px;">Email</th>
                        <th style="width: 30%; padding:14px; text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td style="padding:14px;">{{ $u->name }}</td>
                        <td style="padding:14px;">{{ $u->email }}</td>

                        <td style="text-align:center; padding:14px;">

                            {{-- Tombol Edit --}}
                            <button class="btn btn-outline-warning btn-sm me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTuModal{{ $u->id }}">
                                Edit
                            </button>

                            {{-- Tombol Hapus --}}
                            <button class="btn btn-outline-danger btn-sm"
                                    onclick="hapusData('{{ $u->id }}')">
                                Hapus
                            </button>

                            {{-- Form delete --}}
                            <form id="formHapus-{{ $u->id }}" action="{{ route('account.destroy', $u->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editTuModal{{ $u->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('account.update', $u->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tata Usaha</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="text" name="name" value="{{ $u->name }}" class="form-control mb-3" required>
                                                <input type="email" name="email" value="{{ $u->email }}" class="form-control mb-3" required>
                                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-warning">Update</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- END MODAL --}}

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($users->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
            </div>

            <nav>
                <ul class="pagination mb-0">
                    {{-- Tombol Previous --}}
                    @if($users->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $users->previousPageUrl() }}">&laquo;</a></li>
                    @endif

                    @php
                        $currentPage = $users->currentPage();
                        $lastPage = $users->lastPage();
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($startPage > 1)
                        <li class="page-item"><a class="page-link" href="{{ $users->url(1) }}">1</a></li>
                        @if($startPage > 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif

                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if($page == $currentPage)
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $users->url($page) }}">{{ $page }}</a></li>
                        @endif
                    @endfor

                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item"><a class="page-link" href="{{ $users->url($lastPage) }}">{{ $lastPage }}</a></li>
                    @endif

                    @if($users->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif

    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addTuModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('account.store', 'tu') }}" method="POST">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tata Usaha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-3" placeholder="Nama" required>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Tambah</button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- SCRIPT HAPUS --}}
<script>
function hapusData(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d63939",   // Merah Tabler
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal"
    }).then((res) => {
        if (res.isConfirmed) {
            document.getElementById("formHapus-" + id).submit();
        }
    });
}
</script>

@endsection