<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Manajemen Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">t">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      color: #2d3748;
      overflow-x: hidden;
    }

    /* Navbar */
    .navbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: #4e54c8;
    }

    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 10px 30px;
      border-radius: 25px;
      border: none;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
      color: white;
    }

    /* Hero Section */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 80px 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      top: -200px;
      right: -200px;
    }

    .hero::after {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      bottom: -150px;
      left: -150px;
    }

    .hero-content {
      color: white;
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }

    .hero p {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      opacity: 0.95;
    }

    .hero-image {
      position: relative;
      z-index: 2;
    }

    .hero-image img {
      max-width: 100%;
      filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.2));
    }

    /* Features Section */
    .features {
      padding: 100px 0;
      background: white;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 1rem;
    }

    .section-title p {
      font-size: 1.1rem;
      color: #718096;
    }

    .feature-card {
      background: white;
      border-radius: 15px;
      padding: 40px 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      height: 100%;
      border: 1px solid #e2e8f0;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .feature-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 25px;
      font-size: 2rem;
      color: white;
    }

    .feature-card h4 {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 15px;
      color: #2d3748;
    }

    .feature-card p {
      color: #718096;
      line-height: 1.7;
    }

    /* Roles Section */
    .roles {
      padding: 100px 0;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .role-card {
      background: white;
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 20px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .role-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-color: #667eea;
    }

    .role-header {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .role-icon-circle {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-right: 20px;
    }

    .role-card h5 {
      font-weight: 600;
      color: #2d3748;
      margin: 0;
    }

    .role-card p {
      color: #718096;
      margin: 0;
      line-height: 1.6;
    }

    .superadmin-bg { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .admin-bg { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .guru-bg { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .siswa-bg { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .tu-bg { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
    .payroll-bg { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

    /* Stats Section */
    .stats {
      padding: 80px 0;
      background: white;
    }

    .stat-card {
      text-align: center;
      padding: 30px;
    }

    .stat-number {
      font-size: 3rem;
      font-weight: 800;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 10px;
    }

    .stat-label {
      font-size: 1.1rem;
      color: #718096;
      font-weight: 500;
    }

    /* Footer */
    .footer {
      background: #2d3748;
      color: white;
      padding: 60px 0 30px;
    }

    .footer h5 {
      font-weight: 600;
      margin-bottom: 20px;
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 10px;
    }

    .footer-links a {
      color: #a0aec0;
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-links a:hover {
      color: white;
    }

    .footer-bottom {
      margin-top: 40px;
      padding-top: 30px;
      border-top: 1px solid #4a5568;
      text-align: center;
      color: #a0aec0;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1.1rem;
      }

      .section-title h2 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center w-100">
        <a class="navbar-brand" href="#">
          <i class="bi bi-mortarboard-fill"></i> School MS
        </a>
        <a href="{{ route('login') }}" class="btn btn-login">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 hero-content">
          <h1>Sistem Manajemen Sekolah Modern</h1>
          <p>Kelola seluruh aktivitas sekolah dengan mudah, efisien, dan terintegrasi dalam satu platform</p>
          <a href="{{ route('login') }}" class="btn btn-login btn-lg">
            Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>
        <div class="col-lg-6 hero-image text-center">
          <i class="bi bi-laptop" style="font-size: 20rem; color: rgba(255,255,255,0.2);"></i>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="container">
      <div class="section-title">
        <h2>Fitur Unggulan</h2>
        <p>Solusi lengkap untuk kebutuhan manajemen sekolah Anda</p>
      </div>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-speedometer2"></i>
            </div>
            <h4>Dashboard Interaktif</h4>
            <p>Pantau semua data penting sekolah dalam satu tampilan yang mudah dipahami dengan visualisasi data real-time</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-people-fill"></i>
            </div>
            <h4>Manajemen Pengguna</h4>
            <p>Kelola akses dan hak pengguna dengan sistem role-based yang fleksibel dan aman untuk semua level</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
            <h4>Laporan Lengkap</h4>
            <p>Generate laporan akademik, keuangan, dan administrasi dengan mudah dan akurat</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-shield-check"></i>
            </div>
            <h4>Keamanan Terjamin</h4>
            <p>Data sekolah terlindungi dengan sistem keamanan berlapis dan backup otomatis</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-phone"></i>
            </div>
            <h4>Responsive Design</h4>
            <p>Akses sistem dari berbagai perangkat - desktop, tablet, atau smartphone dengan tampilan optimal</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="bi bi-clock-history"></i>
            </div>
            <h4>Real-time Updates</h4>
            <p>Informasi selalu update secara otomatis untuk memastikan data terkini dan akurat</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Roles Section -->
  <section class="roles">
    <div class="container">
      <div class="section-title">
        <h2>Akses Multi-Role</h2>
        <p>Sistem yang mendukung berbagai peran pengguna dengan hak akses yang sesuai</p>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="role-card">
            <div class="role-header">
              <div class="role-icon-circle superadmin-bg">
                <i class="bi bi-shield text-white"></i>
              </div>
              <div>
                <h5>Super Admin</h5>
                <p>Akses penuh ke seluruh sistem dan pengaturan</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="role-card">
            <div class="role-header">
              <div class="role-icon-circle admin-bg">
                <i class="bi bi-people text-white"></i>
              </div>
              <div>
                <h5>Admin Sekolah</h5>
                <p>Mengelola data siswa, guru, dan operasional sekolah</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="role-card">
            <div class="role-header">
              <div class="role-icon-circle guru-bg">
                <i class="bi bi-mortarboard text-white"></i>
              </div>
              <div>
                <h5>Guru</h5>
                <p>Input nilai, absensi, dan materi pembelajaran</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="role-card">
            <div class="role-header">
              <div class="role-icon-circle siswa-bg">
                <i class="bi bi-person text-white"></i>
              </div>
              <div>
                <h5>Siswa</h5>
                <p>Akses nilai, jadwal, dan informasi akademik</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="role-card">
            <div class="role-header">
              <div class="role-icon-circle tu-bg">
                <i class="bi bi-clipboard-data text-white"></i>
              </div>
              <div>
                <h5>Tata Usaha</h5>
                <p>Mengelola administrasi dan dokumentasi sekolah</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="role-card">
            <div class="role-header">
              <div class="role-icon-circle payroll-bg">
                <i class="bi bi-calculator text-white"></i>
              </div>
              <div>
                <h5>Payroll/Keuangan</h5>
                <p>Kelola gaji, pembayaran, dan keuangan sekolah</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-5 text-center">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-3 mb-4">
                <h2 class="fw-bold text-primary">{{ number_format($tataUsaha) }}+</h2>
                <p>Tata Usaha</p>
            </div>

            <div class="col-md-3 mb-4">
                <h2 class="fw-bold text-primary">{{ number_format($siswa) }}+</h2>
                <p>Siswa</p>
            </div>

            <div class="col-md-3 mb-4">
                <h2 class="fw-bold text-primary">{{ number_format($guru) }}+</h2>
                <p>Guru</p>
            </div>

        </div>
    </div>
</section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5><i class="bi bi-mortarboard-fill"></i> School MS</h5>
          <p class="text-muted">Sistem Manajemen Sekolah yang modern, efisien, dan mudah digunakan untuk mendukung kemajuan pendidikan.</p>
        </div>
        <div class="col-md-4 mb-4">
          <h5>Menu</h5>
          <ul class="footer-links">
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Fitur</a></li>
            <li><a href="#">Tentang</a></li>
            <li><a href="#">Kontak</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h5>Kontak</h5>
          <ul class="footer-links">
            <li><i class="bi bi-envelope me-2"></i>info@schoolms.id</li>
            <li><i class="bi bi-telephone me-2"></i>+62 812-3456-7890</li>
            <li><i class="bi bi-geo-alt me-2"></i>Bekasi, West Java, ID</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 School Management System. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

 

  
 

  
</body>
</html>