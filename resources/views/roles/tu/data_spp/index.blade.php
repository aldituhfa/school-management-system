@extends('layouts.tu')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none mb-3">
            <h2 class="page-title" style="font-size: 1.25rem; font-weight: 600;">Data SPP</h2>
        </div>

        <div class="card">
            <div class="card-body border-bottom py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $siswa->firstItem() ?? 0 }}-{{ $siswa->lastItem() ?? 0 }} dari {{ $siswa->total() }} data
                    </div>
                    <div class="d-flex gap-2">
                        {{-- Live Search --}}
                        <input type="text" id="searchInput" class="form-control form-control-sm" style="width: 180px;" placeholder="Cari nama/NISN...">

                        {{-- Filter Kelas --}}
                        <select id="filterKelas" class="form-select form-select-sm" style="width: 140px;">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-vcenter" id="siswaTable" style="font-size: 0.8125rem;">
                    <thead>
                        <tr>
                            <th class="py-1 px-2">NISN</th>
                            <th class="py-1 px-2">Nama Siswa</th>
                            <th class="py-1 px-2">Kelas</th>
                            <th class="py-1 px-2">Status</th>
                            <th class="py-1 px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $item)
                            <tr>
                                <td class="py-1 px-2">{{ $item->nisn }}</td>
                                <td class="py-1 px-2">{{ $item->nama_siswa }}</td>
                                <td class="py-1 px-2">
                                    <span class="badge badge-info">{{ $item->kelas->nama_kelas ?? '-' }}</span>
                                </td>
                                <td class="py-1 px-2">
                                    @php
                                        $status = strtolower($item->status->nama_status ?? 'tidak diketahui');
                                    @endphp
                                    <span class="text-orange fw-semibold">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="py-1 px-2 text-center">
                                    <a href="{{ route('tu.data_spp.detail', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex align-items-center py-2">
                <div class="text-muted small">
                    Halaman {{ $siswa->currentPage() }} dari {{ $siswa->lastPage() }}
                </div>
                <div class="ms-auto">
                    @if($siswa->hasPages())
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if($siswa->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">‹</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $siswa->previousPageUrl() }}" rel="prev">‹</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach($siswa->getUrlRange(1, $siswa->lastPage()) as $page => $url)
                                @if($page == $siswa->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if($siswa->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $siswa->nextPageUrl() }}" rel="next">›</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">›</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Live Search + Filter Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const filterKelas = document.getElementById('filterKelas');
        const rows = document.querySelectorAll('#siswaTable tbody tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedKelas = filterKelas.value.toLowerCase();

            rows.forEach(row => {
                const nama = row.children[1].textContent.toLowerCase();
                const nisn = row.children[0].textContent.toLowerCase();
                const kelas = row.children[2].textContent.toLowerCase();

                const matchSearch = nama.includes(searchValue) || nisn.includes(searchValue);
                const matchKelas = selectedKelas === "" || kelas.includes(selectedKelas);

                row.style.display = (matchSearch && matchKelas) ? "" : "none";
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        filterKelas.addEventListener('change', filterTable);
    });
</script>
@endsection