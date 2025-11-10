<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tata Usaha Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
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
            cursor: default; /* Tambahkan ini */
        }

        .logo-img {
            width: 64px; /* Diperbesar dari 48px */
            height: 64px; /* Diperbesar dari 48px */
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
            width: 64px; /* Diperbesar dari 48px */
            height: 64px; /* Diperbesar dari 48px */
            border-radius: 6px;
            background-color: var(--tblr-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem; /* Diperbesar dari 0.75rem */
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

        /* Card Styles */
        .card {
            border: 1px solid var(--tblr-border-color);
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--tblr-border-color);
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0;
            color: #1a1a1a;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        /* Button Styles */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .btn-primary {
            background-color: var(--tblr-primary);
            border-color: var(--tblr-primary);
        }

        /* Table Styles */
        .table {
            font-size: 0.8125rem;
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid var(--tblr-border-color);
            padding: 0.75rem;
            color: #374151;
        }

        .table td {
            vertical-align: middle;
            padding: 0.75rem;
            border-color: var(--tblr-border-color);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
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
                width: 56px; /* Diperbesar dari 40px */
                height: 56px; /* Diperbesar dari 40px */
            }

            .logo-fallback {
                width: 56px; /* Diperbesar dari 40px */
                height: 56px; /* Diperbesar dari 40px */
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

        <div class="p-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('roles.tu.dashboard') }}" class="nav-link {{ request()->routeIs('roles.tu.dashboard') ? 'active' : '' }}">
                        <i class="bx bx-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bx bx-folder"></i> Arsip
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bx bx-envelope"></i> Surat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tu.spp.index') }}" class="nav-link {{ request()->routeIs('tu.spp.index') ? 'active' : '' }}">
                        <i class="bx bx-money"></i> Pembayaran SPP
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tu.data_spp.index') }}"
                        class="nav-link {{ request()->routeIs('tu.data_spp.*') ? 'active' : '' }}">
                        <i class="bx bx-list-ul"></i> Data SPP
                    </a>
                </li>
            </ul>
        </div>
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
                            <a class="dropdown-item" href="{{ route('payroll.profile') }}" style="font-size: 0.8125rem;">
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
                <!-- Content Area -->
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
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

    @stack('scripts')
</body>

</html>