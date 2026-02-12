@extends('layouts.guru')

@section('content')
<div class="container-fluid">
    <!-- Header Dashboard -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Guru</h1>
        <div class="d-flex">
            <span class="mr-2 text-blue">
                <i class="fas fa-calendar-alt fa-sm mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards Compact -->
    <div class="row mb-4">
        <!-- Total Siswa -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Siswa</p>
                            <h4 class="mb-0 font-weight-bold">{{ $totalSiswa }}</h4>
                            <p class="text-blue small mb-0 mt-1">
                                <i class="fas fa-check-circle fa-xs mr-1"></i> Semua aktif
                            </p>
                        </div>
                        <div class="bg-blue text-white rounded-circle p-2">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent py-2 px-3 border-top-0">
                    <a href="#" class="text-blue small d-block text-right">
                        Lihat detail <i class="fas fa-arrow-right ml-1 fa-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Jadwal Minggu Ini -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Jadwal Minggu Ini</p>
                            <h4 class="mb-0 font-weight-bold">{{ $jadwalMingguIni }}</h4>
                            <p class="text-blue small mb-0 mt-1">
                                <i class="fas fa-clock fa-xs mr-1"></i> Minggu ke-{{ \Carbon\Carbon::now()->weekOfYear }}
                            </p>
                        </div>
                        <div class="bg-blue text-white rounded-circle p-2">
                            <i class="fas fa-calendar fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent py-2 px-3 border-top-0">
                    <a href="#" class="text-blue small d-block text-right">
                        Lihat jadwal <i class="fas fa-arrow-right ml-1 fa-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Materi -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Materi</p>
                            <h4 class="mb-0 font-weight-bold">{{ $totalMateri }}</h4>
                            <p class="text-blue small mb-0 mt-1">
                                <i class="fas fa-upload fa-xs mr-1"></i> Terbaru hari ini
                            </p>
                        </div>
                        <div class="bg-blue text-white rounded-circle p-2">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent py-2 px-3 border-top-0">
                    <a href="#" class="text-blue small d-block text-right">
                        Upload baru <i class="fas fa-arrow-right ml-1 fa-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Kelas</p>
                            <h4 class="mb-0 font-weight-bold">{{ $totalKelas }}</h4>
                            <p class="text-blue small mb-0 mt-1">
                                <i class="fas fa-chalkboard-teacher fa-xs mr-1"></i> Kelas diampu
                            </p>
                        </div>
                        <div class="bg-blue text-white rounded-circle p-2">
                            <i class="fas fa-school fa-lg"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent py-2 px-3 border-top-0">
                    <a href="#" class="text-blue small d-block text-right">
                        Lihat detail <i class="fas fa-arrow-right ml-1 fa-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-7 mb-4">
            <!-- Data Siswa Terbaru -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-blue">
                            <i class="fas fa-users fa-sm mr-2"></i> Data Siswa Terbaru
                        </h6>
                        <div class="search-box">
                            <form method="GET" action="{{ route('roles.guru.dashboard') }}" class="form-inline">
                                <div class="input-group input-group-sm">
                                    <input type="text" 
                                           name="search_siswa" 
                                           class="form-control form-control-sm" 
                                           placeholder="Cari siswa..."
                                           value="{{ $searchSiswa ?? '' }}"
                                           style="border-radius: 20px 0 0 20px; border-right: 0; font-size: 0.75rem;">
                                    <div class="input-group-append">
                                        @if($searchSiswa)
                                        <a href="{{ route('roles.guru.dashboard') }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           style="border-radius: 0; border-right: 0; border-left: 0; padding: 0.25rem 0.5rem;"
                                           title="Clear">
                                            <i class="fas fa-times fa-xs"></i>
                                        </a>
                                        @endif
                                        <button class="btn btn-sm btn-blue" 
                                                type="submit"
                                                style="border-radius: 0 20px 20px 0; padding: 0.25rem 0.75rem;">
                                            <i class="fas fa-search fa-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 py-2 px-3" style="width: 50px;">No</th>
                                    <th class="border-0 py-2 px-3">NISN</th>
                                    <th class="border-0 py-2 px-3">Nama Siswa</th>
                                    <th class="border-0 py-2 px-3">JK</th>
                                    <th class="border-0 py-2 px-3">TTL</th>
                                    <th class="border-0 py-2 px-3" style="width: 80px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaTerbaru as $index => $siswa)
                                <tr>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="badge bg-light-blue text-blue">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="font-weight-bold">{{ $siswa->nisn }}</span>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-2">
                                                <div class="avatar bg-blue text-white rounded-circle">
                                                    {{ substr($siswa->nama_siswa, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $siswa->nama_siswa }}</div>
                                                <small class="text-muted">{{ $siswa->agama }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        @if($siswa->jenis_kelamin == 'Laki-laki')
                                            <span class="badge bg-blue text-white">L</span>
                                        @else
                                            <span class="badge bg-light-blue text-blue">P</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <div class="small">
                                            {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted smaller">
                                            {{ $siswa->tempat_lahir }}
                                        </div>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="badge bg-success text-white smaller">
                                            <i class="fas fa-check-circle fa-xs mr-1"></i> Aktif
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-2x text-gray-300 mb-2"></i>
                                            <p class="text-muted mb-0 small">Belum ada data siswa</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2 px-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($searchSiswa)
                            <span class="text-muted small">
                                <i class="fas fa-search fa-xs mr-1"></i>
                                Hasil pencarian "<strong>{{ $searchSiswa }}</strong>": {{ $siswaTerbaru->total() }} siswa
                            </span>
                            @else
                            <a href="#" class="btn btn-sm btn-outline-blue py-1">
                                <i class="fas fa-eye mr-1"></i> Lihat Semua Siswa
                            </a>
                            @endif
                        </div>
                        <div class="pagination-sm mb-0">
                            {{ $siswaTerbaru->appends(['search_siswa' => $searchSiswa])->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold text-blue">
                        <i class="fas fa-history fa-sm mr-2"></i> Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="activity-container" style="max-height: 400px; overflow-y: auto;">
                        @forelse($aktivitasTerbaru as $aktivitas)
                        <div class="activity-item d-flex align-items-start p-3 border-bottom">
                            <div class="activity-icon mr-3">
                                @php
                                    $tipeFile = strtolower(pathinfo($aktivitas->file_path, PATHINFO_EXTENSION));
                                    $iconClass = 'fas fa-file';
                                    $iconBg = 'bg-secondary';
                                    
                                    if (in_array($tipeFile, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'])) {
                                        $iconClass = 'fas fa-file-video';
                                        $iconBg = 'bg-danger';
                                    } elseif (in_array($tipeFile, ['pdf'])) {
                                        $iconClass = 'fas fa-file-pdf';
                                        $iconBg = 'bg-danger';
                                    } elseif (in_array($tipeFile, ['doc', 'docx'])) {
                                        $iconClass = 'fas fa-file-word';
                                        $iconBg = 'bg-primary';
                                    } elseif (in_array($tipeFile, ['ppt', 'pptx'])) {
                                        $iconClass = 'fas fa-file-powerpoint';
                                        $iconBg = 'bg-warning';
                                    }
                                @endphp
                                <div class="{{ $iconBg }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="{{ $iconClass }}"></i>
                                </div>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <p class="mb-1 font-weight-bold">Upload Materi: {{ $aktivitas->judul }}</p>
                                <p class="text-muted small mb-1">
                                    {{ $aktivitas->jadwal->mataPelajaran->nama_mata_pelajaran ?? '-' }} • 
                                    Kelas {{ $aktivitas->jadwal->kelas->nama_kelas ?? '-' }}
                                </p>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted smaller">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $aktivitas->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="activity-status">
                                <span class="badge bg-success text-white smaller">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-gray-300 mb-2"></i>
                            <p class="text-muted mb-0 small">Belum ada aktivitas</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white py-2 px-3 border-top">
                    <div class="pagination-sm mb-0">
                        {{ $aktivitasTerbaru->appends(request()->except('aktivitas_page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-5">
            <!-- Profile Card -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold text-blue">
                        <i class="fas fa-user-circle fa-sm mr-2"></i> Profile Guru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="profile-img-container mb-2">
                            <img src="{{ $guru->profile_photo_url }}" alt="Profile" 
                                 class="profile-img rounded-circle shadow-sm">
                        </div>
                        <h6 class="font-weight-bold mb-1">{{ $guru->name }}</h6>
                        <p class="text-muted mb-2 small">{{ $guru->email }}</p>
                        <span class="badge bg-blue text-white px-2 py-1">
                            <i class="fas fa-user-tie fa-xs mr-1"></i> {{ ucfirst($guru->role) }}
                        </span>
                    </div>

                    <div class="profile-info mb-3">
                        <div class="info-item d-flex align-items-center mb-2">
                            <div class="info-icon bg-blue text-white rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-book fa-sm"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Mata Pelajaran</p>
                                <p class="font-weight-bold mb-0">{{ $guru->mataPelajaran->count() }} Mapel</p>
                            </div>
                        </div>
                        <div class="info-item d-flex align-items-center mb-2">
                            <div class="info-icon bg-blue text-white rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-calendar-alt fa-sm"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Jadwal Minggu Ini</p>
                                <p class="font-weight-bold mb-0">{{ $jadwalMingguIni }} Jadwal</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <h6 class="font-weight-bold mb-2 small">Mata Pelajaran</h6>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($guru->mataPelajaran as $mapel)
                        <span class="badge badge-outline-blue smaller mr-1 mb-1">
                            {{ $mapel->nama_mata_pelajaran }}
                        </span>
                        @empty
                        <div class="w-100 text-center">
                            <i class="fas fa-book-open fa-lg mb-2 text-gray-300"></i>
                            <p class="text-muted small mb-0">Belum ada mata pelajaran</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Jadwal Minggu Ini -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 font-weight-bold text-blue">
                        <i class="fas fa-calendar-alt fa-sm mr-2"></i> Jadwal Minggu Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="schedule-container" style="max-height: 400px; overflow-y: auto;">
                        @if($jadwalDetail->count() > 0)
                            @foreach($jadwalDetail->items() as $hari => $jadwals)
                            <div class="schedule-day p-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="day-badge bg-blue text-white rounded px-2 py-1">
                                        {{ $hari }}
                                    </span>
                                    <span class="ml-2 text-muted small">
                                        {{ count($jadwals) }} kelas
                                    </span>
                                </div>
                                @foreach($jadwals as $jadwal)
                                <div class="schedule-item mb-2 p-2 bg-light-blue rounded">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="font-weight-bold mb-1 small">
                                                <i class="far fa-clock text-blue fa-xs mr-1"></i>
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                            </p>
                                            <p class="text-muted mb-0 smaller">
                                                <i class="fas fa-book fa-xs mr-1"></i>
                                                {{ $jadwal->mataPelajaran->nama_mata_pelajaran }}
                                            </p>
                                        </div>
                                        <span class="badge bg-blue text-white smaller">
                                            {{ $jadwal->kelas->nama_kelas }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-2x text-gray-300 mb-2"></i>
                            <p class="text-muted small mb-0">Belum ada jadwal minggu ini</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white py-2 px-3 border-top">
                    <div class="pagination-sm mb-0">
                        {{ $jadwalDetail->appends(request()->except('jadwal_page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Materi Pembelajaran -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-blue">
                            <i class="fas fa-book mr-2"></i> Materi Pembelajaran
                        </h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">JUDUL MATERI</th>
                                    <th class="border-0 py-3 px-4 text-center">MAPEL</th>
                                    <th class="border-0 py-3 px-4 text-center">KELAS</th>
                                    <th class="border-0 py-3 px-4 text-center">TIPE</th>
                                    <th class="border-0 py-3 px-4 text-center">TANGGAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materiPembelajaran as $materi)
                                @php
                                    $tipeFile = strtolower(pathinfo($materi->file_path, PATHINFO_EXTENSION));
                                    $iconClass = 'fas fa-file';
                                    $badgeClass = 'bg-secondary';
                                    $badgeText = 'File';
                                    
                                    if (in_array($tipeFile, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'])) {
                                        $iconClass = 'fas fa-file-video';
                                        $badgeClass = 'bg-danger';
                                        $badgeText = 'Video';
                                    } elseif (in_array($tipeFile, ['pdf'])) {
                                        $iconClass = 'fas fa-file-pdf';
                                        $badgeClass = 'bg-danger';
                                        $badgeText = 'PDF';
                                    } elseif (in_array($tipeFile, ['doc', 'docx'])) {
                                        $iconClass = 'fas fa-file-word';
                                        $badgeClass = 'bg-primary';
                                        $badgeText = 'Doc';
                                    } elseif (in_array($tipeFile, ['ppt', 'pptx'])) {
                                        $iconClass = 'fas fa-file-powerpoint';
                                        $badgeClass = 'bg-warning text-dark';
                                        $badgeText = 'PPT';
                                    }
                                    
                                    $fileSize = $materi->file_size;
                                    $fileSizeFormatted = '';
                                    if ($fileSize >= 1048576) {
                                        $fileSizeFormatted = number_format($fileSize / 1048576, 1) . ' MB';
                                    } else {
                                        $fileSizeFormatted = number_format($fileSize / 1024, 1) . ' KB';
                                    }
                                @endphp
                                <tr>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="file-icon bg-light-blue text-blue rounded d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 56px; height: 56px;">
                                                <i class="{{ $iconClass }} fa-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold mb-1">{{ $materi->judul }}</div>
                                                <div class="text-muted small mb-1">
                                                    {{ Str::limit($materi->deskripsi, 50) }}
                                                </div>
                                                <div class="text-muted smaller">
                                                    <i class="fas fa-weight-hanging fa-xs mr-1"></i>
                                                    {{ $fileSizeFormatted }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        <span class="badge bg-light-blue text-blue">
                                            {{ $materi->jadwal->mataPelajaran->nama_mata_pelajaran ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        <span class="badge bg-blue text-white">
                                            {{ $materi->jadwal->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        <span class="badge {{ $badgeClass }} text-white smaller px-3 py-1">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 align-middle text-center">
                                        <div class="small font-weight-bold">
                                            {{ $materi->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-muted smaller">
                                            {{ $materi->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-muted mb-0">Belum ada materi pembelajaran</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 px-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $materiPembelajaran->firstItem() ?? 0 }} - {{ $materiPembelajaran->lastItem() ?? 0 }} dari {{ $materiPembelajaran->total() }} materi
                        </div>
                        <div class="pagination-sm mb-0">
                            {{ $materiPembelajaran->appends(request()->except('materi_page'))->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --blue: #3498db;
        --blue-light: #2980b9;
        --blue-lighter: #ecf4fd;
    }

    .text-blue { color: var(--blue) !important; }
    .bg-blue { background-color: var(--blue) !important; }
    .bg-light-blue { background-color: var(--blue-lighter) !important; }
    
    .btn-outline-blue {
        color: var(--blue);
        border-color: var(--blue);
    }
    
    .btn-outline-blue:hover {
        background-color: var(--blue);
        border-color: var(--blue);
        color: white;
    }
    
    .badge.bg-blue { 
        background-color: var(--blue) !important; 
        color: white !important;
    }
    
    .badge.bg-light-blue { 
        background-color: var(--blue-lighter) !important; 
        color: var(--blue) !important;
    }
    
    .badge-outline-blue {
        border: 1px solid var(--blue);
        color: var(--blue);
        background-color: transparent;
    }

    .card { border-radius: 8px; }
    
    .table thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #6c757d;
        background-color: #f8fafc;
    }
    
    .table tbody tr:hover { background-color: #f8fafc; }

    .profile-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
    }

    .avatar {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .schedule-item {
        border-left: 2px solid var(--blue);
    }

    .activity-item:hover { background-color: #f8fafc; }

    .smaller { font-size: 0.75rem !important; }
    .small { font-size: 0.875rem !important; }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #f8f9fa; }
    ::-webkit-scrollbar-thumb { background: var(--blue); }

    /* Pagination Styles */
    .pagination-sm .pagination {
        margin-bottom: 0;
    }
    
    .pagination-sm .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        color: var(--blue);
        border-color: #dee2e6;
    }
    
    .pagination-sm .page-link:hover {
        background-color: var(--blue-lighter);
        border-color: var(--blue);
        color: var(--blue);
    }
    
    .pagination-sm .page-item.active .page-link {
        background-color: var(--blue);
        border-color: var(--blue);
        color: white;
    }
    
    .pagination-sm .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    /* Search Box Styles */
    .search-box .input-group-sm .form-control {
        height: calc(1.5em + 0.5rem + 2px);
    }
    
    .search-box .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .search-box .form-control:focus {
        border-color: var(--blue);
        box-shadow: none;
    }
    
    .search-box .btn-blue {
        background-color: var(--blue);
        border-color: var(--blue);
        color: white;
    }
    
    .search-box .btn-blue:hover {
        background-color: var(--blue-light);
        border-color: var(--blue-light);
    }
    
    .search-box .btn-outline-secondary {
        border-color: #ced4da;
        color: #6c757d;
    }
    
    .search-box .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #6c757d;
    }
</style>
@endpush