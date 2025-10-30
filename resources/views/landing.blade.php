<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Manajemen Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

  <style>
    :root {
      --primary: #2563eb;
      --primary-light: #3b82f6;
      --secondary: #64748b;
      --light: #f8fafc;
      --dark: #1e293b;
      --border: #e2e8f0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.6;
    }

    /* Navbar */
    .navbar {
      background-color: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--primary);
    }

    .btn-login {
      background-color: var(--primary);
      color: white;
      padding: 8px 24px;
      border-radius: 6px;
      border: none;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .btn-login:hover {
      background-color: var(--primary-light);
      color: white;
    }

    /* Hero Section */
    .hero {
      min-height: 80vh;
      display: flex;
      align-items: center;
      padding: 80px 0;
      background: linear-gradient(to bottom right, #f0f9ff, #e0f2fe);
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
      color: var(--dark);
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      color: var(--secondary);
    }

    .hero-image {
      text-align: center;
    }

    .hero-image i {
      font-size: 18rem;
      color: rgba(37, 99, 235, 0.1);
    }

    /* Partners Section */
    .partners {
      padding: 60px 0;
      background-color: white;
      overflow: hidden;
    }

    .partners-title {
      text-align: center;
      margin-bottom: 40px;
      color: var(--secondary);
      font-size: 1rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .partners-slider {
      overflow: hidden;
      position: relative;
    }

    .partners-track {
      display: flex;
      animation: scroll 30s linear infinite;
      gap: 60px;
    }

    .partner-logo {
      flex-shrink: 0;
      width: 150px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      filter: grayscale(100%) opacity(0.6);
      transition: all 0.3s ease;
    }

    .partner-logo:hover {
      filter: grayscale(0%) opacity(1);
    }

    .partner-logo img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    @keyframes scroll {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-111%);
      }
    }

    .partners-slider:hover .partners-track {
      animation-play-state: paused;
    }

    /* Features Section */
    .features {
      padding: 80px 0;
      background-color: white;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 1rem;
    }

    .section-title p {
      font-size: 1.1rem;
      color: var(--secondary);
    }

    .feature-card {
      background: white;
      border-radius: 10px;
      padding: 30px 25px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease;
      height: 100%;
      border: 1px solid var(--border);
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
      width: 60px;
      height: 60px;
      background-color: var(--primary);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      font-size: 1.5rem;
      color: white;
    }

    .feature-card h4 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--dark);
    }

    .feature-card p {
      color: var(--secondary);
      line-height: 1.6;
    }

    /* Roles Section */
    .roles {
      padding: 80px 0;
      background-color: #f8fafc;
    }

    .role-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: all 0.2s ease;
      border: 1px solid var(--border);
    }

    .role-card:hover {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .role-header {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .role-icon-circle {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      margin-right: 15px;
      color: white;
    }

    .role-card h5 {
      font-weight: 600;
      color: var(--dark);
      margin: 0;
    }

    .role-card p {
      color: var(--secondary);
      margin: 0;
      line-height: 1.6;
    }

    .superadmin-bg { background-color: #7c3aed; }
    .admin-bg { background-color: #0ea5e9; }
    .guru-bg { background-color: #10b981; }
    .siswa-bg { background-color: #f59e0b; }
    .tu-bg { background-color: #6366f1; }
    .payroll-bg { background-color: #ec4899; }

    /* Stats Section */
    .stats {
      padding: 60px 0;
      background-color: white;
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .stat-label {
      font-size: 1.1rem;
      color: var(--secondary);
      font-weight: 500;
    }

    /* Testimonials Section */
    .testimonials {
      padding: 80px 0;
      background-color: #f8fafc;
    }

    .testimonial-card {
      background: white;
      border-radius: 15px;
      padding: 30px;
      height: 100%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border: 1px solid var(--border);
      display: flex;
      flex-direction: column;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .testimonial-header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .testimonial-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      overflow: hidden;
      border: 3px solid var(--primary);
      flex-shrink: 0;
    }

    .testimonial-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .testimonial-user-info h5 {
      margin: 0;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark);
    }

    .testimonial-user-info p {
      margin: 0;
      font-size: 0.9rem;
      color: var(--secondary);
    }

    .testimonial-rating {
      color: #fbbf24;
      font-size: 1rem;
      margin-top: 5px;
    }

    .testimonial-text {
      font-size: 1rem;
      line-height: 1.7;
      color: var(--secondary);
      flex-grow: 1;
      font-style: italic;
    }

    /* Footer */
    .footer {
      background: var(--dark);
      color: white;
      padding: 50px 0 25px;
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
      color: #cbd5e1;
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-links a:hover {
      color: white;
    }

    .footer-bottom {
      margin-top: 40px;
      padding-top: 25px;
      border-top: 1px solid #334155;
      text-align: center;
      color: #94a3b8;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.2rem;
      }

      .hero p {
        font-size: 1.1rem;
      }

      .section-title h2 {
        font-size: 1.8rem;
      }

      .hero-image i {
        font-size: 12rem;
      }

      .partner-logo {
        width: 120px;
        height: 60px;
      }

      .partners-track {
        gap: 40px;
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
        <div class="col-lg-6">
          <h1>Sistem Manajemen Sekolah Modern</h1>
          <p>Kelola seluruh aktivitas sekolah dengan mudah, efisien, dan terintegrasi dalam satu platform</p>
          <a href="{{ route('login') }}" class="btn btn-login btn-lg">
            Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>
        <div class="col-lg-6 hero-image">
          <i class="bi bi-laptop"></i>
        </div>
      </div>
    </div>
  </section>

  <!-- Partners Section (Logo Berjalan) -->
  <section class="partners">
    <div class="container">
      <div class="partners-title">Dipercaya oleh berbagai institusi pendidikan</div>
    </div>
    <div class="partners-slider">
      <div class="partners-track">
        <!-- Set 1 - Ganti src dengan URL logo Anda -->
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 1">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 2">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 3">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 4">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 5">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 6">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 7">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 8">
        </div>

        <!-- Set 2 - Duplikat untuk loop seamless -->
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 1">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 2">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 3">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 4">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 5">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 6">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/Logo TB.png') }}" alt="Partner 7">
        </div>
        <div class="partner-logo">
          <img src="{{ asset('images/partners/kemendikbud.png') }}" alt="Partner 8">
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
                <i class="bi bi-shield"></i>
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
                <i class="bi bi-people"></i>
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
                <i class="bi bi-mortarboard"></i>
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
                <i class="bi bi-person"></i>
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
                <i class="bi bi-clipboard-data"></i>
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
                <i class="bi bi-calculator"></i>
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
  <section class="stats text-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-3 mb-4">
          <div class="stat-number">{{ number_format($tataUsaha) }}+</div>
          <div class="stat-label">Tata Usaha</div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stat-number">{{ number_format($siswa) }}+</div>
          <div class="stat-label">Siswa</div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="stat-number">{{ number_format($guru) }}+</div>
          <div class="stat-label">Guru</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials">
    <div class="container">
      <div class="section-title">
        <h2>Apa Kata Mereka?</h2>
        <p>Testimoni dari pengguna yang telah merasakan manfaat sistem kami</p>
      </div>
      <div class="row">
        <!-- Testimonial 1 -->
        <div class="col-md-4 mb-4">
          <div class="testimonial-card">
            <div class="testimonial-header">
              <div class="testimonial-avatar">
                <img src="https://i.pravatar.cc/150?img=12" alt="Ahmad Syarif">
              </div>
              <div class="testimonial-user-info">
                <h5>Ahmad Syarif</h5>
                <p>Kepala Sekolah</p>
                <div class="testimonial-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                </div>
              </div>
            </div>
            <div class="testimonial-text">
              "Sistem ini sangat membantu kami dalam mengelola administrasi sekolah. Semua data tersimpan rapi dan mudah diakses kapan saja. Sangat direkomendasikan!"
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="col-md-4 mb-4">
          <div class="testimonial-card">
            <div class="testimonial-header">
              <div class="testimonial-avatar">
                <img src="https://i.pravatar.cc/150?img=47" alt="Siti Putri">
              </div>
              <div class="testimonial-user-info">
                <h5>Siti Putri</h5>
                <p>Guru Matematika</p>
                <div class="testimonial-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                </div>
              </div>
            </div>
            <div class="testimonial-text">
              "Interface yang user-friendly membuat pekerjaan saya sebagai guru menjadi lebih efisien. Input nilai dan absensi siswa jadi sangat mudah dan cepat."
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="col-md-4 mb-4">
          <div class="testimonial-card">
            <div class="testimonial-header">
              <div class="testimonial-avatar">
                <img src="https://i.pravatar.cc/150?img=33" alt="Budi Wibowo">
              </div>
              <div class="testimonial-user-info">
                <h5>Budi Wibowo</h5>
                <p>Bendahara Sekolah</p>
                <div class="testimonial-rating">
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-fill"></i>
                  <i class="bi bi-star-half"></i>
                </div>
              </div>
            </div>
            <div class="testimonial-text">
              "Fitur payroll dan keuangan sangat lengkap dan akurat. Memudahkan kami dalam mengatur gaji guru dan keuangan sekolah dengan transparan."
            </div>
          </div>
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