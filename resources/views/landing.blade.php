<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Manajemen Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .role-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    .role-card {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      position: relative;
      text-align: left;
      border: 1px solid #e9ecef;
      transition: all 0.3s ease;
    }
    .role-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      border-color: #4e54c8;
    }
    .role-icon {
      font-size: 28px;
      margin-bottom: 15px;
      color: #343a40;
    }
    .role-name {
      font-weight: 600;
      font-size: 16px;
      margin-bottom: 5px;
      color: #343a40;
    }
    .role-email {
      font-size: 13px;
      color: #868e96;
      margin-bottom: 15px;
    }
    .role-label {
      position: absolute;
      top: 15px;
      right: 15px;
      padding: 2px 10px;
      border-radius: 5px;
      font-size: 12px;
      font-weight: 500;
      color: #fff;
    }
    .superadmin { background-color: #f8d7da; color: #842029; }
    .admin { background-color: #cfe2ff; color: #084298; }
    .guru { background-color: #d1e7dd; color: #0f5132; }
    .siswa { background-color: #e5dbff; color: #3d0f7d; }
    .tatausaha { background-color: #ffe5b4; color: #663c00; }
    .payroll { background-color: #fff3cd; color: #664d03; }

    .role-button {
      font-size: 12px;
      padding: 6px 15px;
      background-color: #f8f9fa;
      color: #343a40;
      border: 1px solid #ced4da;
      border-radius: 5px;
      width: 100%;
      text-align: center;
      transition: all 0.2s;
    }
    .role-button:hover {
      background-color: #4e54c8;
      color: #fff;
      border-color: #4e54c8;
    }

    @media (max-width: 992px) {
      .role-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 576px) {
      .role-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Sistem Manajemen Sekolah</h2>
      <p>Pilih role untuk masuk ke dashboard</p>
    </div>

    <div class="role-grid">
      <div class="role-card">
        <div class="role-label superadmin">Super Admin</div>
        <div class="role-icon"><i class="bi bi-shield"></i></div>
        <div class="role-name">Super Admin</div>
        <div class="role-email">superadmin@sekolah.id</div>
        <a href="{{ route('login') }}" class="role-button">Masuk sebagai Super Admin</a>
      </div>

      <div class="role-card">
        <div class="role-label admin">Admin</div>
        <div class="role-icon"><i class="bi bi-people"></i></div>
        <div class="role-name">Admin Sekolah</div>
        <div class="role-email">admin@sekolah.id</div>
        <a href="{{ route('login') }}" class="role-button">Masuk sebagai Admin</a>
      </div>

      <div class="role-card">
        <div class="role-label guru">Guru</div>
        <div class="role-icon"><i class="bi bi-mortarboard"></i></div>
        <div class="role-name">Guru</div>
        <div class="role-email">guru@sekolah.id</div>
        <a href="{{ route('login') }}" class="role-button">Masuk sebagai Guru</a>
      </div>

      <div class="role-card">
        <div class="role-label siswa">Siswa</div>
        <div class="role-icon"><i class="bi bi-person"></i></div>
        <div class="role-name">Siswa</div>
        <div class="role-email">siswa@sekolah.id</div>
        <a href="{{ route('login') }}" class="role-button">Masuk sebagai Siswa</a>
      </div>

      <div class="role-card">
        <div class="role-label tatausaha">Tata Usaha</div>
        <div class="role-icon"><i class="bi bi-clipboard-data"></i></div>
        <div class="role-name">TU</div>
        <div class="role-email">tu@sekolah.id</div>
        <a href="{{ route('login') }}" class="role-button">Masuk sebagai Tata Usaha</a>
      </div>

      <div class="role-card">
        <div class="role-label payroll">Payroll</div>
        <div class="role-icon"><i class="bi bi-calculator"></i></div>
        <div class="role-name">Keuangan</div>
        <div class="role-email">keuangan@sekolah.id</div>
        <a href="{{ route('login') }}" class="role-button">Masuk sebagai Payroll</a>
      </div>
    </div>
  </div>
</body>
</html>


<!-- "{{ route('login') }}" -->