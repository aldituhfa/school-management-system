<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Super Admin - @yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Sneat Core CSS -->
    <link rel="stylesheet"
        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/vendor/css/core.css" />
    <link rel="stylesheet"
        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet"
        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/css/demo.css" />

    <!-- Perfect Scrollbar -->
    <link rel="stylesheet"
        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />

    <style>
        /* Sidebar tetap di kiri */
        #layout-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        /* Konten bergeser ke kanan supaya tidak ketutup sidebar */
        .layout-page {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->
            <aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme p-3">
                <div class="app-brand demo mb-4">
                    <a href="{{ route('roles.superadmin.dashboard') }}" class="app-brand-link text-decoration-none">
                        <span class="app-brand-text fw-bold fs-5">Super Admin</span>
                    </a>
                </div>

                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item mb-2">
                        <a href="{{ route('roles.superadmin.dashboard') }}" class="nav-link active">
                            <i class="bx bx-home me-2"></i> Dashboard
                        </a>
                    </li>

                    <!-- Manajemen User -->
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link">
                            <i class="bx bx-user me-2"></i> Manajemen User
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ url('/finances') }}" class="nav-link">
                            <i class="bx bx-wallet me-2"></i> Keuangan
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ url('logs/finances') }}" class="nav-link">
                            <i class="bx bx-wallet me-2"></i> Logs Finances
                        </a>
                    </li>

                    <!-- Account (Dropdown) -->
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center" data-bs-toggle="collapse" href="#accountMenu"
                            role="button" aria-expanded="false" aria-controls="accountMenu">
                            <i class="bx bx-id-card me-2"></i> Account
                            <i class="bx bx-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse ps-4" id="accountMenu">
                            <ul class="nav flex-column">
                                <li class="menu-item">
                                    <a href="{{ url('/roles/superadmin/account/admin') }}" class="menu-link">
                                        <div>Admin</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('roles/superadmin/account/guru') }}" class="menu-link">
                                        <div>Guru</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('roles/superadmin/account/payroll') }}" class="menu-link">
                                        <div>Payroll</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('roles/superadmin/account/siswa') }}" class="menu-link">
                                        <div>Siswa</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('roles/superadmin/account/tu') }}" class="menu-link">
                                        <div>Tata Usaha</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item mt-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger w-100">Logout</button>
                        </form>
                    </li>
                </ul>
            </aside>
            <!-- /Sidebar -->

            <!-- Page Content -->
            <div class="layout-page">
                <div class="content-wrapper">
                    <!-- Header -->
                    <div class="container-xxl py-3">
                        <h4 class="fw-bold">@yield('title')</h4>
                    </div>
                    <!-- /Header -->

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- /Page Content -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>

</html>