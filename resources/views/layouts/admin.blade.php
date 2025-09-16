<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="d-flex">

    <!-- Sidebar -->
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 250px; height: 100vh;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="bx bx-cog fs-4 me-2"></i>
            <span class="fs-6 fw-bold">Admin Panel</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto small">
            <li class="nav-item">
                <a href="{{ route('roles.admin.dashboard') }}" class="nav-link text-white">
                    <i class="bx bx-home me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="bx bx-user me-2"></i> Profil
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="bx bx-file me-2"></i> Laporan
                </a>
            </li>
        </ul>
        <hr>
        <!-- Logout -->
        <li class="nav-item mt-3">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="#" class="text-white small"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bx bx-log-out"></i> Logout
            </a>
        </li>

    </div>

    <!-- Content -->
    <div class="p-4 w-100">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>