<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />

       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #1a1a1a;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--tblr-primary);
            border-color: var(--tblr-primary);
        }

        .page-header-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
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
                <span class="school-name">{{ $setting->logo_name ?? 'SMS' }}</span>
            </div>
        </div>

        <ul class="nav flex-column px-2">
            {{-- DASHBOARD --}}
            <li class="nav-item">
                <a href="{{ route('roles.guru.dashboard') }}"
                    class="nav-link {{ request()->routeIs('roles.guru.dashboard') ? 'active' : '' }}">
                    <i class="bx bx-home me-2"></i>
                    Dashboard
                </a>
            </li>

            {{-- DATA SISWA PER KELAS --}}
            <li class="nav-item">
                <a href="{{ route('guru.siswa.perkelas') }}"
                    class="nav-link {{
                            request()->routeIs('guru.siswa.perkelas') ||
                            request()->routeIs('siswa.showByKelas')
                            ? 'active' : ''
                    }}">
                    <i class="bi bi-people me-2"></i>
                    Data Siswa
                </a>
            </li>

            <!-- Jadwal Mengajar -->
            <li class="nav-item">
                <a href="{{ route('guru.jadwal.index') }}"
                    class="nav-link {{ request()->routeIs('guru.jadwal.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-week me-2"></i>
                    Jadwal Mengajar
                </a>
            </li>


            <!-- Materi Pembelajaran -->
            <li class="nav-item">
                <a href="{{ route('guru.materi.index') }}"
                    class="nav-link {{ request()->routeIs('guru.materi.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Materi Pembelajaran
                </a>
            </li>

              {{-- list --}}
            <li class="nav-item">
                <a href="{{ route('guru.materi.list') }}"
                    class="nav-link {{ request()->routeIs('guru.materi.list') ? 'active' : '' }}">
                    <i class="bx bx-home me-2"></i>
                    List Materi 
                </a>
            </li>

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Page Title -->
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
                            <a class="dropdown-item" href="{{ route('guru.profile') }}" style="font-size: 0.8125rem;">
                                <i class="ti ti-user me-2"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="font-size: 0.8125rem;">
                                    <i class="bx bx-log-out me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Content Area - DI SINI DASHBOARD ANDA AKAN DITAMPILKAN -->
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Check if logo loaded successfully
        document.addEventListener('DOMContentLoaded', function() {
            const logoImg = document.querySelector('.logo-img');
            const logoFallback = document.getElementById('logo-fallback');

            if (logoImg && logoFallback) {
                if (logoImg.complete) {
                    if (logoImg.naturalHeight === 0) {
                        logoImg.style.display = 'none';
                        logoFallback.style.display = 'flex';
                    }
                } else {
                    logoImg.addEventListener('error', function() {
                        this.style.display = 'none';
                        logoFallback.style.display = 'flex';
                    });
                }
            }
        });
    </script>
</body>

</html>