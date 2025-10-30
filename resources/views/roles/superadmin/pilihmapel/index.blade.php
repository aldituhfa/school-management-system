@extends('layouts.superadmin')

@section('content')
<div class="page-body">
    <div class="container-xl mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Pilih Mata Pelajaran untuk Guru</h3>

                {{-- Search bar --}}
                <form method="GET" action="{{ route('roles.superadmin.pilihmapel.index') }}" class="d-flex" style="max-width: 300px;">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="20"
                                height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari nama guru..." />
                    </div>
                </form>
            </div>

            <div class="card-body border-top p-0">
                @if(session('success'))
                    <div class="alert alert-success m-3">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru as $index => $g)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $g->name }}</td>
                                    <td>
                                        @forelse($g->mataPelajaran as $mp)
                                            <span class="badge bg-success me-1">{{ $mp->nama_mata_pelajaran }}</span>
                                        @empty
                                            <span class="text-muted">Belum dipilih</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ route('roles.superadmin.pilihmapel.edit', $g->id) }}"
                                            class="btn btn-sm btn-primary">
                                            Pilih Mapel
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Tidak ada data guru ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (opsional jika pakai paginate) --}}
                @if(method_exists($guru, 'links'))
                    <div class="mt-3 px-3">
                        {{ $guru->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
