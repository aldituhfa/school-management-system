@extends('layouts.superadmin')

@section('content')
<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Daftar Guru yang Mengunggah Materi
                    </h3>
                </div>

                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($guruList as $guru)
                            <li class="list-group-item">
                                <div class="row align-items-center">

                                    {{-- Avatar --}}
                                    <div class="col-auto">
                                        <span class="avatar avatar-sm rounded-circle">
                                            {{ strtoupper(substr($guru->name, 0, 1)) }}
                                        </span>
                                    </div>

                                    {{-- Nama Guru --}}
                                    <div class="col text-truncate">
                                        <div class="fw-semibold">
                                            {{ $guru->name }}
                                        </div>
                                        <div class="text-muted small">
                                            Guru Pengampu
                                        </div>
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="col-auto">
                                        <a href="{{ route('superadmin.materi.guru.show', $guru->id) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-book me-1"></i>
                                            Lihat Materi
                                        </a>
                                    </div>

                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="text-center text-muted py-5">
                                    <i class="ti ti-folder-off fs-1 mb-2"></i>
                                    <div class="fw-semibold">
                                        Belum ada guru yang mengunggah materi
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
