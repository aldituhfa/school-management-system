<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin - @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet">

    <style>
        :root {
            --tblr-primary: #206bc4;
            --tblr-border-color: #e6e7e9;
            --tblr-sidebar-width: 240px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
            font-size: 0.875rem;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: var(--tblr-sidebar-width);
            background-color: #fff;
            border-right: 1px solid var(--tblr-border-color);
            position: fixed;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            left: 0;
            top: 0;
        }

        .sidebar .nav-link {
            color: #576176;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            margin-bottom: 0.125rem;
            display: flex;
            align-items: center;
            font-size: 0.8125rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(32, 107, 196, 0.08);
            color: var(--tblr-primary);
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            font-size: 1rem;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid var(--tblr-border-color);
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: inherit;
            width: 100%;
            cursor: default;
            /* Tambahkan ini */
        }

        .logo-img {
            width: 64px;
            /* Diperbesar dari 48px */
            height: 64px;
            /* Diperbesar dari 48px */
            object-fit: contain;
            border-radius: 6px;
        }

        .school-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #1a1a1a;
            text-align: center;
            line-height: 1.3;
        }

        .logo-fallback {
            width: 64px;
            /* Diperbesar dari 48px */
            height: 64px;
            /* Diperbesar dari 48px */
            border-radius: 6px;
            background-color: var(--tblr-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            /* Diperbesar dari 0.75rem */
        }

        .main-content {
            margin-left: var(--tblr-sidebar-width);
            min-height: 100vh;
            background-color: #f5f7fb;
        }

        .navbar {
            background-color: #fff;
            border-bottom: 1px solid var(--tblr-border-color);
            padding: 0.5rem 1rem;
            height: 50px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-wrapper {
            padding: 1rem;
        }

        .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--tblr-primary);
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-dropdown .dropdown-menu {
            border: 1px solid var(--tblr-border-color);
            font-size: 0.8125rem;
            min-width: 150px;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.8125rem;
            color: #6c757d;
        }

        /* Sidebar dropdown styles */
        .sidebar-dropdown {
            position: relative;
        }

        .sidebar-dropdown-menu {
            position: static;
            float: none;
            width: 100%;
            margin: 0;
            background-color: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-dropdown.show .sidebar-dropdown-menu {
            max-height: 200px;
        }

        .sidebar-dropdown-item {
            padding: 0.375rem 1rem 0.375rem 2rem;
            font-size: 0.8125rem;
            color: #576176;
            border-radius: 4px;
            margin-bottom: 0.125rem;
            text-decoration: none;
            display: block;
            transition: all 0.2s;
        }

        .sidebar-dropdown-item:hover,
        .sidebar-dropdown-item.active {
            background-color: rgba(32, 107, 196, 0.08);
            color: var(--tblr-primary);
        }

        .sidebar .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .sidebar .dropdown-toggle::after {
            content: "›";
            border: none;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
            margin-left: auto;
            transform: rotate(90deg);
            width: auto;
            height: auto;
        }

        .sidebar .dropdown-toggle.show::after {
            transform: rotate(-90deg);
        }

        .sidebar-content {
            padding: 0.5rem 0;
        }

        .sidebar .nav {
            gap: 0.125rem;
        }

        .nav-item {
            margin: 0;
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
            font-size: 1.25rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--tblr-sidebar-width));
                transition: margin-left 0.3s ease;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .navbar {
                position: relative;
            }

            .logo-img {
                width: 56px;
                /* Diperbesar dari 40px */
                height: 56px;
                /* Diperbesar dari 40px */
            }

            .logo-fallback {
                width: 56px;
                /* Diperbesar dari 40px */
                height: 56px;
                /* Diperbesar dari 40px */
            }

            .school-name {
                font-size: 0.8125rem;
            }
        }
    </style>
</head>

@php
use App\Models\Setting;
$setting = Setting::first();
@endphp

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                @if($setting && $setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}"
                    alt="Logo Sekolah"
                    class="logo-img"
                    onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex';">
                @else
                <div class="logo-fallback" id="logo-fallback">LOGO</div>
                @endif

                <div class="school-name">
                    {{ $setting->logo_name ?? 'SMS' }}
                </div>
            </div>
        </div>

        <div class="sidebar-content">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.superadmin.dashboard') ? 'active' : '' }}"
                        href="{{ route('roles.superadmin.dashboard') }}">
                        <i class="ti ti-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                     <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('account.main') ? 'active' : '' }}" href="{{ route('account.main') }}">
                        <i class="ti ti-user-circle"></i>
                        <span>Managemen Akun</span>
                    </a>
                </li>

                <li class="nav-item sidebar-dropdown" id="financeDropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('finances') || request()->is('logs/finances') ? 'active' : '' }}"
                        href="#" role="button" aria-expanded="false">
                        <i class="ti ti-wallet"></i>
                        <span>Keuangan</span>
                    </a>
                    <div class="sidebar-dropdown-menu">
                        <a class="sidebar-dropdown-item {{ request()->is('finances') ? 'active' : '' }}" href="{{ url('/finances') }}">
                            Keuangan Kas & Dana BOS
                        </a>
                        <a class="sidebar-dropdown-item {{ request()->is('logs/finances') ? 'active' : '' }}" href="{{ url('logs/finances') }}">
                            Riwayat Keuangan 
                        </a>
                    </div>
                </li>

                <!-- DROPDOWN SISWA -->
                <li class="nav-item sidebar-dropdown" id="siswaDropdown">
                    <a class="nav-link dropdown-toggle
            {{ request()->routeIs('siswa.index') || request()->routeIs('siswa.perkelas') || request()->routeIs('siswa.showByKelas') ? 'active' : '' }}"
                        href="#" role="button" aria-expanded="false">
                        <i class="ti ti-users"></i>
                        <span>Siswa</span>
                    </a>
                    <div class="sidebar-dropdown-menu">
                        <a class="sidebar-dropdown-item {{ request()->routeIs('siswa.index') ? 'active' : '' }}"
                            href="{{ route('siswa.index') }}">
                            Data Siswa
                        </a>
                        <a class="sidebar-dropdown-item {{ request()->routeIs('siswa.perkelas') || request()->routeIs('siswa.showByKelas') ? 'active' : '' }}"
                            href="{{ route('siswa.perkelas') }}">
                            Data Siswa per Kelas
                        </a>

                        <a class="sidebar-dropdown-item {{ request()->routeIs('superadmin.jam-belajar.index') ? 'active' : '' }}"
                            href="{{ route('superadmin.jam-belajar.index') }}">
                            Jam Belajar Per Kelas
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.biayaspp.index') ? 'active' : '' }}" href="{{ route('superadmin.biayaspp.index') }}">
                        <i class="ti ti-credit-card"></i>
                        <span>Biaya SPP</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('superadmin.data_spp.index') }}"
                        class="nav-link {{ request()->routeIs('superadmin.data_spp.*') ? 'active' : '' }}">
                        <i class="bx bx-list-ul"></i> SPP
                    </a>
                </li>

                <!-- Dropdown Mata Pelajaran -->
                <li class="nav-item sidebar-dropdown" id="mataPelajaranDropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('superadmin.mata_pelajaran.*') || request()->routeIs('roles.superadmin.pilihmapel.*') ? 'active' : '' }}"
                        href="#" role="button" aria-expanded="false">
                        <i class="ti ti-book"></i>
                        <span>Kelola Guru</span>
                    </a>
                    <div class="sidebar-dropdown-menu">
                        <a class="sidebar-dropdown-item {{ request()->routeIs('superadmin.mata_pelajaran.index') ? 'active' : '' }}"
                            href="{{ route('superadmin.mata_pelajaran.index') }}">
                            Data Mata Pelajaran
                        </a>
                        <a class="sidebar-dropdown-item {{ request()->routeIs('roles.superadmin.pilihmapel.index') ? 'active' : '' }}"
                            href="{{ route('roles.superadmin.pilihmapel.index') }}">
                            Pilih Mata Pelajaran
                        </a>
                    </div>
                </li>

                <!-- Dropdown Jadwal Pelajaran -->
                <li class="nav-item sidebar-dropdown" id="jadwalDropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('jadwal.*') ? 'active' : '' }}"
                        href="#" role="button" aria-expanded="false">
                        <i class="ti ti-calendar"></i>
                        <span>Kelola Jadwal</span>
                    </a>

                    <div class="sidebar-dropdown-menu">
                    <a class="sidebar-dropdown-item {{ request()->routeIs('superadmin.jam-belajar.index') ? 'active' : '' }}"
                            href="{{ route('superadmin.jam-belajar.index') }}">
                            Jam Belajar Kelas
                        </a>

                        <a class="sidebar-dropdown-item {{ request()->routeIs('jadwal.index') ? 'active' : '' }}"
                            href="{{ route('jadwal.index') }}">
                            Jadwal Pelajaran
                        </a>

                        
                        <!-- Optional: Tambahkan menu lain jika perlu -->
                        <!-- <a class="sidebar-dropdown-item" href="#">
                            Lihat Jadwal per Kelas
                        </a> -->
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('superadmin.setting') ? 'active' : '' }}"
                        href="{{ route('superadmin.setting') }}">
                        <i class="ti ti-settings"></i>
                        <span>Setting Logo</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
                    <i class="ti ti-menu-2"></i>
                </button>

                <div class="d-none d-md-flex align-items-center me-auto">
                    <h2 class="page-title mb-0">@yield('title')</h2>
                </div>

                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown user-dropdown">
                        <a href="#" class="nav-link d-flex align-items-center p-0" data-bs-toggle="dropdown">
                            <div class="avatar me-2">
                                @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo_url }}"
                                    alt="Profile"
                                    class="rounded-circle"
                                    style="width:28px; height:28px; object-fit:cover;">
                                @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                @endif
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('superadmin.profile') }}" style="font-size: 0.8125rem;">
                                <i class="ti ti-user me-2"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="font-size: 0.8125rem;">
                                    <i class="ti ti-logout me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Semua dropdown sidebar
            const dropdowns = [{
                    id: 'financeDropdown'
                },
                {
                    id: 'mataPelajaranDropdown'
                },
                {
                    id: 'siswaDropdown'
                },
                {
                    id: 'jadwalDropdown'
                }
            ];

            // Fungsi toggle dropdown
            function toggleDropdown(dropdown, toggle) {
                dropdown.classList.toggle('show');
                toggle.classList.toggle('show');
                const key = dropdown.id + 'Open';
                localStorage.setItem(key, dropdown.classList.contains('show') ? 'true' : 'false');
            }

            // Tutup dropdown lain
            function closeOtherDropdowns(currentDropdown) {
                document.querySelectorAll('.sidebar-dropdown').forEach(d => {
                    if (d !== currentDropdown && d.classList.contains('show')) {
                        d.classList.remove('show');
                        d.querySelector('.dropdown-toggle').classList.remove('show');
                        localStorage.setItem(d.id + 'Open', 'false');
                    }
                });
            }

            // Setup tiap dropdown
            dropdowns.forEach(({
                id
            }) => {
                const dropdown = document.getElementById(id);
                if (!dropdown) return;

                const toggle = dropdown.querySelector('.dropdown-toggle');
                const items = dropdown.querySelectorAll('.sidebar-dropdown-item');

                // Toggle dropdown on click
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleDropdown(dropdown, toggle);
                    closeOtherDropdowns(dropdown);
                });

                // Setup item click
                items.forEach(item => {
                    item.addEventListener('click', () => {
                        items.forEach(i => i.classList.remove('active'));
                        item.classList.add('active');
                        dropdown.classList.add('show');
                        toggle.classList.add('show', 'active');
                        localStorage.setItem(id + 'Open', 'true');
                    });
                });

                // Restore state dari localStorage
                const savedState = localStorage.getItem(id + 'Open');
                if (savedState === 'true') {
                    dropdown.classList.add('show');
                    toggle.classList.add('show', 'active');
                }
            });

            // Auto aktif berdasarkan path URL
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar-dropdown-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                    const parentDropdown = item.closest('.sidebar-dropdown');
                    if (parentDropdown) {
                        parentDropdown.classList.add('show');
                        const toggle = parentDropdown.querySelector('.dropdown-toggle');
                        toggle?.classList.add('show', 'active');
                        localStorage.setItem(parentDropdown.id + 'Open', 'true');
                    }
                }
            });

            // Logo fallback (biar aman)
            const logoImg = document.querySelector('.logo-img');
            const logoFallback = document.getElementById('logo-fallback');
            if (logoImg && logoFallback) {
                if (logoImg.complete && logoImg.naturalHeight === 0) {
                    logoImg.style.display = 'none';
                    logoFallback.style.display = 'flex';
                } else {
                    logoImg.addEventListener('error', () => {
                        logoImg.style.display = 'none';
                        logoFallback.style.display = 'flex';
                    });
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>