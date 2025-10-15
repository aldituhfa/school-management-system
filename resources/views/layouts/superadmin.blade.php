<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin - @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />

    <style>
        .nav-item.dropdown .dropdown-menu {
            display: none;
        }

        .nav-item.dropdown.show .dropdown-menu {
            display: block;
        }

        /* Active state untuk menu dan submenu */
        .nav-link.active {
            background-color: var(--tblr-primary);
            color: white !important;
        }

        .dropdown-item.active {
            background-color: var(--tblr-primary);
            color: white;
        }

        /* Hover effect untuk logout */
        .btn-logout {
            background-color: transparent;
            border: 1px solid var(--tblr-border-color);
            color: var(--tblr-body-color);
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: var(--tblr-danger);
            color: white;
            border-color: var(--tblr-danger);
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="{{ route('roles.superadmin.dashboard') }}" class="text-decoration-none text-white">
                        Super Admin
                    </a>
                </h1>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('roles.superadmin.dashboard') ? 'active' : '' }}" href="{{ route('roles.superadmin.dashboard') }}">
                                <span class="nav-link-icon">
                                    <i class="bx bx-home"></i>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>

                        <!-- Finance dengan Submenu -->
                        <li class="nav-item dropdown {{ request()->is('finances') || request()->is('logs/finances') ? 'show' : '' }}">
                            <a class="nav-link dropdown-toggle {{ request()->is('finances') || request()->is('logs/finances') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="{{ request()->is('finances') || request()->is('logs/finances') ? 'true' : 'false' }}">
                                <span class="nav-link-icon">
                                    <i class="bx bx-wallet"></i>
                                </span>
                                <span class="nav-link-title">Finance</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item {{ request()->is('finances') ? 'active' : '' }}" href="{{ url('/finances') }}">
                                        <i class="bx bx-wallet me-2"></i> Finance
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('logs/finances') ? 'active' : '' }}" href="{{ url('logs/finances') }}">
                                        <i class="bx bx-history me-2"></i> Log Finance
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Account Management -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('account.main') ? 'active' : '' }}" href="{{ route('account.main') }}">
                                <span class="nav-link-icon">
                                    <i class="bx bx-user-circle"></i>
                                </span>
                                <span class="nav-link-title">Account Management</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('siswa.index') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                                <span class="nav-link-icon">
                                    <i class="bx bx-group"></i>
                                </span>
                                <span class="nav-link-title">Data Siswa</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('superadmin.biayaspp.index') ? 'active' : '' }}"
                                href="{{ route('superadmin.biayaspp.index') }}">
                                <span class="nav-link-icon">
                                    <i class="bx bx-money"></i>
                                </span>
                                <span class="nav-link-title">Biaya SPP</span>
                            </a>
                        </li>

                        <!-- Logout -->
                        <li class="nav-item mt-4">
                            <form action="{{ route('logout') }}" method="POST" class="px-3">
                                @csrf
                                <button type="submit" class="btn btn-logout w-100">
                                    <i class="bx bx-log-out me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Page Content -->
        <div class="page-wrapper">
            <!-- Header -->
            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-xl">
                    <div class="navbar-nav flex-row order-md-last">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                                <div class="d-none d-xl-block ps-2">
                                    <div>Super Admin</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="navbar-nav flex-row">
                        <h2 class="page-title">@yield('title')</h2>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <div class="page-body">
                <div class="container-xl">
                    <!-- Content -->
                    <div class="card">
                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Inisialisasi dropdown manual
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownTriggers = document.querySelectorAll('.dropdown-toggle');

            dropdownTriggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    var dropdown = this.closest('.dropdown');
                    dropdown.classList.toggle('show');

                    // Close other dropdowns
                    document.querySelectorAll('.dropdown').forEach(function(otherDropdown) {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('show');
                        }
                    });
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown').forEach(function(dropdown) {
                        dropdown.classList.remove('show');
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>