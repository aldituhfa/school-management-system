<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="d-flex">

    <!-- Sidebar Payroll -->
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 240px; height: 100vh;">
        <h5 class="text-white mb-4"><i class="bx bx-money"></i> Payroll</h5>
        <ul class="nav nav-pills flex-column mb-auto small">
            <li><a href="{{ route('roles.payroll.dashboard') }}" class="nav-link text-white"><i class="bx bx-home me-2"></i> Dashboard</a></li>
            <li><a href="#" class="nav-link text-white"><i class="bx bx-wallet me-2"></i> Gaji</a></li>
            <li><a href="#" class="nav-link text-white"><i class="bx bx-file me-2"></i> Laporan</a></li>
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

    <div class="p-4 w-100">@yield('content')</div>
</body>

</html>