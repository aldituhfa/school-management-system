@extends('layouts.superadmin')

@section('content')
<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">

            {{-- Header --}}
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Materi oleh {{ $guru->name }}
                        </h2>
                        <div class="text-muted mt-1">
                            Daftar materi yang telah diunggah
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('superadmin.materi.guru') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card --}}
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Ukuran</th>
                                <th>File</th>
                                <th>Tanggal Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materi as $item)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $item->judul }}
                                    </td>

                                    <td>
                                        <span class="badge bg-azure-lt text-uppercase">
                                            {{ $item->tipe_materi }}
                                        </span>
                                    </td>

                                    <td class="text-muted">
                                        {{ number_format($item->file_size / 1024, 2) }} KB
                                    </td>

                                    <td>
                                        <a href="{{ asset('storage/'.$item->file_path) }}"
                                           target="_blank"
                                           class="text-decoration-none">
                                            <i class="ti ti-file-text me-1"></i>
                                            {{ $item->file_name }}
                                        </a>
                                    </td>

                                    <td class="text-muted">
                                        {{ $item->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center text-muted py-5">
                                            <i class="ti ti-folder-off fs-1 mb-2"></i>
                                            <div class="fw-semibold">
                                                Materi belum tersedia
                                            </div>
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
@endsection
