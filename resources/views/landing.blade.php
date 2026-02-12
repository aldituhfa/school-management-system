<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School MS - Sistem Manajemen Sekolah Modern</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary: #2563eb;
      --primary-light: #3b82f6;
      --primary-dark: #1e40af;
      --secondary: #64748b;
      --light: #f8fafc;
      --dark: #1e293b;
      --border: #e2e8f0;
      --success: #10b981;
      --warning: #f59e0b;
      --purple: #7c3aed;
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
      overflow-x: hidden;
    }
 
    /* Navbar */
    .navbar {
      background-color: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.3s ease;
    }
 
    .navbar.scrolled {
      padding: 0.5rem 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
 
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--primary);
      transition: transform 0.3s ease;
    }
 
    .navbar-brand:hover {
      transform: scale(1.05);
    }
 
    .btn-contact, .btn-login {
      padding: 8px 24px;
      border-radius: 8px;
      border: none;
      font-weight: 500;
      transition: all 0.3s ease;
      margin-left: 10px;
    }
 
    .btn-contact {
      background-color: transparent;
      color: var(--primary);
      border: 2px solid var(--primary);
    }
 
    .btn-contact:hover {
      background-color: var(--primary);
      color: white;
      transform: translateY(-2px);
    }
 
    .btn-login {
      background-color: var(--primary);
      color: white;
    }
 
    .btn-login:hover {
      background-color: var(--primary-light);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(50, 37, 235, 0.3);
    }
 
    /* Hero Slider */
    .hero-slider {
      position: relative;
      height: 90vh;
      overflow: hidden;
    }
 
    .hero-slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      transition: opacity 1s ease-in-out;
      display: flex;
      align-items: center;
    }
 
    .hero-slide.active {
      opacity: 1;
    }
 
    .hero-slide-1 {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%),
                  url('images/dashboard.jpg') center/cover;
    }
 
    .hero-slide-2 {
      background: linear-gradient(135deg, rgba(240, 147, 251, 0.9) 0%, rgba(245, 87, 108, 0.9) 100%),
                  url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1920&h=1080&fit=crop') center/cover;
    }
 
    .hero-slide-3 {
      background: linear-gradient(135deg, rgba(79, 172, 254, 0.9) 0%, rgba(0, 242, 254, 0.9) 100%),
                  url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1920&h=1080&fit=crop') center/cover;
    }
 
    .hero-content {
      color: white;
      z-index: 2;
    }
 
    .hero-content h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      animation: fadeInUp 1s ease;
    }
 
    .hero-content p {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      animation: fadeInUp 1s ease 0.2s both;
    }
 
    .hero-cta {
      animation: fadeInUp 1s ease 0.4s both;
    }
 
    .hero-image {
      animation: float 3s ease-in-out infinite;
    }
 
    .hero-image i {
      font-size: 20rem;
      color: rgba(255, 255, 255, 0.2);
    }
 
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
 
    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-20px);
      }
    }
 
    .slider-dots {
      position: absolute;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
      z-index: 3;
    }
 
    .slider-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: all 0.3s ease;
    }
 
    .slider-dot.active {
      background-color: white;
      width: 30px;
      border-radius: 6px;
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
      background: white;
      border-radius: 10px;
      padding: 15px;
    }
 
    .partner-logo:hover {
      filter: grayscale(0%) opacity(1);
      transform: scale(1.1);
    }
 
    .partner-logo i {
      font-size: 3rem;
      color: var(--secondary);
    }
 
    @keyframes scroll {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-50%);
      }
    }
 
    .partners-slider:hover .partners-track {
      animation-play-state: paused;
    }
 
    /* Features Section - Phone Centered Design */
    .features {
      padding: 100px 0;
      background: linear-gradient(to bottom, white, #f8fafc);
      position: relative;
      overflow: hidden;
    }
 
    .features-showcase {
      position: relative;
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 700px;
    }
 
    .phone-mockup {
      position: relative;
      z-index: 10;
      animation: floatPhone 3s ease-in-out infinite;
    }
 
    .phone-frame {
      width: 320px;
      height: 650px;
      background: linear-gradient(135deg, #000000ff 0%, #283f93ff 100%);
      border-radius: 40px;
      padding: 15px;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
      position: relative;
    }
 
    .phone-screen {
      width: 100%;
      height: 100%;
      background: white;
      border-radius: 30px;
      overflow: hidden;
      position: relative;
    }
 
    .phone-notch {
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 120px;
      height: 25px;
      background: #1e293b;
      border-radius: 0 0 20px 20px;
      z-index: 1;
    }
 
    .phone-content {
      padding: 35px 20px 20px;
      height: 100%;
      background: linear-gradient(to bottom, #f8fafc, white);
    }
 
    .phone-header {
      text-align: center;
      margin-bottom: 20px;
    }
 
    .phone-header h4 {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 5px;
    }
 
    .phone-header p {
      font-size: 0.85rem;
      color: var(--secondary);
    }
 
    .phone-stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-top: 20px;
    }
 
    .phone-stat-card {
      background: white;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      text-align: center;
    }
 
    .phone-stat-icon {
      width: 35px;
      height: 35px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 8px;
      color: white;
      font-size: 0.9rem;
    }
 
    .phone-stat-number {
      font-size: 1.3rem;
      font-weight: 800;
      color: var(--dark);
      display: block;
    }
 
    .phone-stat-label {
      font-size: 0.75rem;
      color: var(--secondary);
    }
 
 
 
    .feature-orbit {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      height: 100%;
      max-width: 1200px;
      max-height: 800px;
    }
 
    .orbit-item {
      position: absolute;
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border: 2px solid var(--border);
      transition: all 0.3s ease;
      max-width: 280px;
    }
 
    .orbit-item:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
      border-color: var(--primary);
    }
 
    .orbit-item-1 {
      top: 5%;
      left: 10%;
    }
 
    .orbit-item-2 {
      top: 5%;
      right: 10%;
    }
 
    .orbit-item-3 {
      top: 36%;
      left: 0%;
    }
 
    .orbit-item-4 {
      top: 36%;
      right: 0%;
 
    }
 
    .orbit-item-5 {
      bottom: 5%;
      left: 10%;
    }
 
    .orbit-item-6 {
      bottom: 5%;
      right: 10%;
    }
 
    .orbit-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 15px;
      font-size: 1.5rem;
      color: white;
    }
 
    .orbit-item h5 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 10px;
      color: var(--dark);
    }
 
    .orbit-item p {
      font-size: 0.9rem;
      color: var(--secondary);
      line-height: 1.5;
      margin: 0;
    }
 
    .orbit-bg-1 { background: linear-gradient(135deg, #667eea, #004bfaff); }
    .orbit-bg-2 { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .orbit-bg-3 { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .orbit-bg-4 { background: linear-gradient(135deg, #43e97b, #38f9d7); }
    .orbit-bg-5 { background: linear-gradient(135deg, #fa709a, #fee140); }
    .orbit-bg-6 { background: linear-gradient(135deg, #30cfd0, #330867); }
 
    .section-title {
      text-align: center;
      margin-bottom: 80px;
    }
 
    .section-title h2 {
      font-size: 2.8rem;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 1rem;
      position: relative;
      display: inline-block;
    }
 
    .section-title h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--purple));
      border-radius: 2px;
    }
 
    .section-title p {
      font-size: 1.2rem;
      color: var(--secondary);
      margin-top: 20px;
    }
 
    /* Video Demo Section */
    .video-demo {
      padding: 100px 0;
      background: linear-gradient(135deg, #667eea 0%, #0062ffff 100%);
      color: white;
    }
 
    .video-demo .section-title h2,
    .video-demo .section-title p {
      color: white;
    }
 
    .video-demo .section-title h2::after {
      background: white;
    }
 
    .video-thumbnail {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
      cursor: pointer;
      transition: transform 0.4s ease;
    }
 
    .video-thumbnail:hover {
      transform: scale(1.02);
    }
 
    .video-thumbnail img {
      width: 100%;
      height: auto;
      display: block;
    }
 
    .play-button {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100px;
      height: 100px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
 
    .play-button i {
      font-size: 3rem;
      color: var(--primary);
      margin-left: 5px;
    }
 
    .video-thumbnail:hover .play-button {
      transform: translate(-50%, -50%) scale(1.1);
      background: white;
    }
 
    /* Video Modal */
    .video-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.9);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
 
    .video-modal.active {
      display: flex;
    }
 
    .video-modal-content {
      width: 90%;
      max-width: 1000px;
      position: relative;
    }
 
    .video-modal-close {
      position: absolute;
      top: -40px;
      right: 0;
      color: white;
      font-size: 2rem;
      cursor: pointer;
      transition: transform 0.3s ease;
    }
 
    .video-modal-close:hover {
      transform: scale(1.2);
    }
 
    .video-modal iframe {
      width: 100%;
      height: 500px;
      border-radius: 10px;
    }
 
    /* Multi-Role Iconic Section */
    .multi-role {
      padding: 100px 0;
      background-color: white;
    }
 
    .role-slider-container {
      position: relative;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 60px;
    }
 
    .role-slider {
      overflow: hidden;
    }
 
    .role-slides {
      display: flex;
      transition: transform 0.5s ease;
    }
 
    .role-slide {
      min-width: 100%;
      display: flex;
      align-items: center;
      gap: 60px;
      padding: 40px;
    }
 
    .role-image {
      flex: 1;
      position: relative;
    }
 
    .role-image-wrapper {
      position: relative;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }
 
    .role-image-wrapper img {
      width: 100%;
      height: auto;
      display: block;
    }
 
    .role-content {
      flex: 1;
    }
 
    .role-badge {
      display: inline-block;
      padding: 8px 20px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: white;
    }
 
    .role-content h3 {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 20px;
      color: var(--dark);
    }
 
    .role-content p {
      font-size: 1.1rem;
      color: var(--secondary);
      margin-bottom: 30px;
      line-height: 1.8;
    }
 
    .role-features {
      list-style: none;
      padding: 0;
    }
 
    .role-features li {
      padding: 12px 0;
      display: flex;
      align-items: center;
      font-size: 1.05rem;
      color: var(--dark);
    }
 
    .role-features li i {
      margin-right: 15px;
      color: var(--success);
      font-size: 1.3rem;
    }
 
    .slider-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      z-index: 10;
    }
 
    .slider-nav:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-50%) scale(1.1);
    }
 
    .slider-nav.prev {
      left: 0;
    }
 
    .slider-nav.next {
      right: 0;
    }
 
    .slider-indicators {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 40px;
    }
 
    .slider-indicator {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: var(--border);
      cursor: pointer;
      transition: all 0.3s ease;
    }
 
    .slider-indicator.active {
      background: var(--primary);
      width: 30px;
      border-radius: 6px;
    }
 
    /* Stats Section */
    .stats {
      padding: 80px 0;
      background: linear-gradient(135deg, #4c6cfbff 0%, #4000ffff 100%);
      color: white;
      position: relative;
      overflow: hidden;
    }
 
    .stats::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>');
      opacity: 0.3;
    }
 
    .stat-item {
      text-align: center;
      position: relative;
      z-index: 1;
    }
 
    .stat-icon {
      font-size: 3rem;
      margin-bottom: 20px;
      opacity: 0.9;
    }
 
    .stat-number {
      font-size: 3.5rem;
      font-weight: 900;
      margin-bottom: 10px;
      display: block;
    }
 
    .stat-label {
      font-size: 1.2rem;
      font-weight: 500;
      opacity: 0.95;
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
      padding: 60px 0 30px;
    }
 
    .footer text {
      color: #ffffff;
    }
 
    .footer h5 {
      color: #cbd5e1;
      font-weight: 600;
      margin-bottom: 25px;
      font-size: 1.3rem;
    }
 
    .footer-links {
      list-style: none;
      padding: 0;
    }
 
    .footer-links li {
      margin-bottom: 12px;
    }
 
    .footer-links a {
      color: #cbd5e1;
      text-decoration: none;
      transition: all 0.3s;
      display: inline-block;
    }
 
    .footer-links a:hover {
      color: white;
      transform: translateX(5px);
    }
 
    .footer-bottom {
      margin-top: 50px;
      padding-top: 30px;
      border-top: 1px solid #334155;
      text-align: center;
      color: #94a3b8;
    }
 
    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
 
    .social-links a {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      transition: all 0.3s ease;
    }
 
    .social-links a:hover {
      background: var(--primary);
      transform: translateY(-3px);
    }
 
    /* Responsive */
    @media (max-width: 1200px) {
      .orbit-item {
        max-width: 220px;
        padding: 20px;
      }
 
      .orbit-item-1 { top: 3%; left: 10%; }
      .orbit-item-2 { top: 3%; right: 10%; }
      .orbit-item-3 { left: -5%; }
      .orbit-item-4 { right: -5%; }
      .orbit-item-5 { bottom: 3%; left: 10%; }
      .orbit-item-6 { bottom: 3%; right: 10%; }
    }
 
    @media (max-width: 992px) {
      .features-showcase {
        min-height: 500px;
      }
 
      .phone-frame {
        width: 280px;
        height: 570px;
      }
 
      .orbit-item {
        display: none;
      }
 
      .role-slide {
        flex-direction: column;
        text-align: center;
      }
 
      .hero-content h1 {
        font-size: 2.5rem;
      }
    }
 
    @media (max-width: 768px) {
      .phone-frame {
        width: 260px;
        height: 530px;
      }
 
      .hero-content h1 {
        font-size: 2rem;
      }
 
      .hero-content p {
        font-size: 1.1rem;
      }
 
      .hero-device-mockup img {
        max-width: 350px;
      }
 
      .role-content h3 {
        font-size: 2rem;
      }
 
      .stat-number {
        font-size: 2.5rem;
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
        <div>
         <a href="https://wa.me/6285817093056?text=Halo%20saya%20ingin%20bertanya" 
   class="btn btn-contact" target="_blank">
    <i class="bi bi-whatsapp me-2"></i>Contact Me
</a>

          <a href="{{ route('login') }}" class="btn btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
          </a>
        </div>
      </div>
    </div>
  </nav>
 
  <!-- Hero Slider -->
  <section class="hero-slider">
    <div class="hero-slide hero-slide-1 active">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 hero-content">
            <h1>Sistem Manajemen Sekolah Modern</h1>
            <p>Kelola seluruh aktivitas sekolah dengan mudah, efisien, dan terintegrasi dalam satu platform canggih</p>
            <div class="hero-cta">
              <a href="#" class="btn btn-light btn-lg me-3">
                Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i>
              </a>
              <a href="#features" class="btn btn-outline-light btn-lg">
                Lihat Fitur
              </a>
            </div>
          </div>
          <div class="col-lg-6 hero-image text-center">
            <i class="bi bi-laptop"></i>
          </div>
        </div>
      </div>
    </div>
 
    <div class="hero-slide hero-slide-2">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 hero-content">
            <h1>Dashboard Interaktif & Real-time</h1>
            <p>Pantau semua aktivitas sekolah secara real-time dengan dashboard yang intuitif dan mudah dipahami</p>
            <div class="hero-cta">
              <a href="#" class="btn btn-light btn-lg me-3">
                Demo Gratis <i class="bi bi-play-circle ms-2"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-6 hero-image text-center">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
        </div>
      </div>
    </div>
 
    <div class="hero-slide hero-slide-3">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 hero-content">
            <h1>Keamanan Data Terjamin</h1>
            <p>Data sekolah Anda terlindungi dengan sistem keamanan berlapis dan backup otomatis setiap hari</p>
            <div class="hero-cta">
              <a href="#" class="btn btn-light btn-lg me-3">
                Pelajari Lebih Lanjut <i class="bi bi-shield-check ms-2"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-6 hero-image text-center">
            <i class="bi bi-shield-lock"></i>
          </div>
        </div>
      </div>
    </div>
 
    <div class="slider-dots">
      <div class="slider-dot active" data-slide="0"></div>
      <div class="slider-dot" data-slide="1"></div>
      <div class="slider-dot" data-slide="2"></div>
    </div>
  </section>
 
  <!-- Partners Section -->
  <section class="partners">
    <div class="container">
      <div class="partners-title">Dipercaya oleh berbagai institusi pendidikan</div>
    </div>
    <div class="partners-slider">
      <div class="partners-track">
        <div class="partner-logo"><i class="bi bi-building"></i></div>
        <div class="partner-logo"><i class="bi bi-mortarboard"></i></div>
        <div class="partner-logo"><i class="bi bi-book"></i></div>
        <div class="partner-logo"><i class="bi bi-bank"></i></div>
        <div class="partner-logo"><i class="bi bi-briefcase"></i></div>
        <div class="partner-logo"><i class="bi bi-diagram-3"></i></div>
        <div class="partner-logo"><i class="bi bi-globe"></i></div>
        <div class="partner-logo"><i class="bi bi-award"></i></div>
 
        <div class="partner-logo"><i class="bi bi-building"></i></div>
        <div class="partner-logo"><i class="bi bi-mortarboard"></i></div>
        <div class="partner-logo"><i class="bi bi-book"></i></div>
        <div class="partner-logo"><i class="bi bi-bank"></i></div>
        <div class="partner-logo"><i class="bi bi-briefcase"></i></div>
        <div class="partner-logo"><i class="bi bi-diagram-3"></i></div>
        <div class="partner-logo"><i class="bi bi-globe"></i></div>
        <div class="partner-logo"><i class="bi bi-award"></i></div>
      </div>
    </div>
  </section>
 
  <!-- Features Section - Phone Centered -->
  <section class="features" id="features">
    <div class="container">
      <div class="section-title">
        <h2>Fitur Unggulan</h2>
        <p>Solusi lengkap untuk kebutuhan manajemen sekolah modern</p>
      </div>
 
      <div class="features-showcase">
        <!-- Phone Mockup Center -->
        <div class="phone-mockup">
          <div class="phone-frame">
            <div class="phone-screen">
              <div class="phone-notch"></div>
              <div class="phone-content">
                <div class="phone-header">
                  <h4>School MS Dashboard</h4>
                  <p>Kelola sekolah dengan mudah</p>
                </div>
                <div class="phone-stats">
                  <div class="phone-stat-card">
                    <div class="phone-stat-icon">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="phone-stat-number">1.2K</span>
                    <div class="phone-stat-label">Siswa</div>
                  </div>
                  <div class="phone-stat-card">
                    <div class="phone-stat-icon">
                      <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span class="phone-stat-number">85</span>
                    <div class="phone-stat-label">Guru</div>
                  </div>
                  <div class="phone-stat-card">
                    <div class="phone-stat-icon">
                      <i class="bi bi-book-fill"></i>
                    </div>
                    <span class="phone-stat-number">42</span>
                    <div class="phone-stat-label">Kelas</div>
                  </div>
                  <div class="phone-stat-card">
                    <div class="phone-stat-icon">
                      <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <span class="phone-stat-number">98%</span>
                    <div class="phone-stat-label">Hadir</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
 
        <!-- Orbiting Features -->
        <div class="feature-orbit">
          <div class="orbit-item orbit-item-1">
            <div class="orbit-icon orbit-bg-1">
              <i class="bi bi-speedometer2"></i>
            </div>
            <h5>Dashboard Real-time</h5>
            <p>Monitor aktivitas sekolah secara langsung dengan update otomatis</p>
          </div>
 
          <div class="orbit-item orbit-item-2">
            <div class="orbit-icon orbit-bg-2">
              <i class="bi bi-shield-check"></i>
            </div>
            <h5>Keamanan Tinggi</h5>
            <p>Enkripsi data dan backup otomatis untuk perlindungan maksimal</p>
          </div>
 
          <div class="orbit-item orbit-item-3">
            <div class="orbit-icon orbit-bg-3">
              <i class="bi bi-people-fill"></i>
            </div>
            <h5>Multi-Role Access</h5>
            <p>Sistem akses berlapis untuk berbagai tingkat pengguna</p>
          </div>
 
          <div class="orbit-item orbit-item-4">
            <div class="orbit-icon orbit-bg-4">
              <i class="bi bi-graph-up-arrow"></i>
            </div>
            <h5>Laporan Otomatis</h5>
            <p>Generate laporan lengkap dengan satu klik tombol</p>
          </div>
 
          <div class="orbit-item orbit-item-5">
            <div class="orbit-icon orbit-bg-5">
              <i class="bi bi-phone"></i>
            </div>
            <h5>Responsive Design</h5>
            <p>Akses dari perangkat apapun dengan tampilan optimal</p>
          </div>
 
          <div class="orbit-item orbit-item-6">
            <div class="orbit-icon orbit-bg-6">
              <i class="bi bi-cloud-check"></i>
            </div>
            <h5>Cloud Storage</h5>
            <p>Data tersimpan aman di cloud dengan sinkronisasi otomatis</p>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Video Demo Section -->
  <section class="video-demo">
    <div class="container">
      <div class="section-title">
        <h2>Lihat Demo Sistem</h2>
        <p>Tonton video demo untuk melihat bagaimana sistem kami bekerja</p>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="video-thumbnail" onclick="openVideoModal()">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&h=675&fit=crop" alt="Video Demo">
            <div class="play-button">
              <i class="bi bi-play-fill"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Video Modal -->
  <div class="video-modal" id="videoModal">
    <div class="video-modal-content">
      <div class="video-modal-close" onclick="closeVideoModal()">
        <i class="bi bi-x-circle-fill"></i>
      </div>
      <iframe id="videoFrame" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div>
 
  <!-- Stats Section -->
  <section class="stats">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="bi bi-clipboard-data"></i>
            </div>
            <span class="stat-number counter" data-target="{{ $tataUsaha ?? 150 }}">0</span>
            <div class="stat-label">Tata Usaha Aktif</div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="bi bi-people-fill"></i>
            </div>
            <span class="stat-number counter" data-target="{{ $siswa ?? 5000 }}">0</span>
            <div class="stat-label">Siswa Terdaftar</div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="stat-item">
            <div class="stat-icon">
              <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span class="stat-number counter" data-target="{{ $guru ?? 350 }}">0</span>
            <div class="stat-label">Guru</div>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Multi-Role Iconic Section -->
  <section class="multi-role">
    <div class="container">
      <div class="section-title">
        <h2>Akses Multi-Role</h2>
        <p>Sistem yang dirancang untuk berbagai peran dengan fitur khusus masing-masing</p>
      </div>
 
      <div class="role-slider-container">
        <div class="role-slider">
          <div class="role-slides" id="roleSlides">
            <!-- Slide 1: Super Admin -->
            <div class="role-slide">
              <div class="role-content">
                <div class="role-badge" style="background: #7c3aed;">
                  <i class="bi bi-shield-fill me-2"></i>Super Admin
                </div>
                <h3>Kontrol Penuh Sistem</h3>
                <p>Super Admin memiliki akses penuh ke seluruh sistem untuk mengelola konfigurasi, user management, dan pengaturan tingkat lanjut.</p>
                <ul class="role-features">
                  <li><i class="bi bi-check-circle-fill"></i> Manajemen seluruh role</li>
                  <li><i class="bi bi-check-circle-fill"></i> Konfigurasi Logo sekolah</li>
                  <li><i class="bi bi-check-circle-fill"></i> Akses ke semua modul dan fitur</li>
                  <li><i class="bi bi-check-circle-fill"></i> Laporan lengkap dan audit log</li>
                </ul>
              </div>
              <div class="role-image">
                <div class="role-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=600&h=400&fit=crop" alt="Super Admin Dashboard">
                </div>
              </div>
            </div>
 
            <!-- Slide 2: Admin Sekolah -->
            <!-- <div class="role-slide">
              <div class="role-content">
                <div class="role-badge" style="background: #0ea5e9;">
                  <i class="bi bi-people-fill me-2"></i>Admin Sekolah
                </div>
                <h3>Kelola Operasional Sekolah</h3>
                <p>Admin sekolah mengelola data siswa, guru, kelas, dan seluruh operasional harian sekolah dengan mudah dan efisien.</p>
                <ul class="role-features">
                  <li><i class="bi bi-check-circle-fill"></i> Manajemen data siswa dan guru</li>
                  <li><i class="bi bi-check-circle-fill"></i> Pengaturan kelas dan jadwal</li>
                  <li><i class="bi bi-check-circle-fill"></i> Monitoring kehadiran dan prestasi</li>
                  <li><i class="bi bi-check-circle-fill"></i> Generate laporan akademik</li>
                </ul>
              </div>
              <div class="role-image">
                <div class="role-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop" alt="Admin Dashboard">
                </div>
              </div>
            </div> -->
 
            <!-- Slide 3: Guru -->
            <div class="role-slide">
              <div class="role-content">
                <div class="role-badge" style="background: #10b981;">
                  <i class="bi bi-mortarboard-fill me-2"></i>Guru
                </div>
                <h3>Manajemen Pembelajaran</h3>
                <p>Guru dapat mengelola proses pembelajaran, serta melakukan pencatatan
                   aktivitas kelas secara efisien melalui sistem ini.</p>
                <ul class="role-features">
                  <li><i class="bi bi-check-circle-fill"></i> Dashboard Interaktif</li>
                  <li><i class="bi bi-check-circle-fill"></i> Melihat data siswa</li>
                  <li><i class="bi bi-check-circle-fill"></i> Melihat jadwal mengajar secara real-time</li>
                  <li><i class="bi bi-check-circle-fill"></i> Upload materi pembelajaran</li>
                </ul>
              </div>
              <div class="role-image">
                <div class="role-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&h=400&fit=crop" alt="Guru Dashboard">
                </div>
              </div>
            </div>
 
            <!-- Slide 4: Siswa -->
            <!-- <div class="role-slide">
              <div class="role-content">
                <div class="role-badge" style="background: #f59e0b;">
                  <i class="bi bi-person-fill me-2"></i>Siswa
                </div>
                <h3>Portal Pembelajaran Siswa</h3>
                <p>Siswa dapat mengakses nilai, jadwal pelajaran, materi pembelajaran, dan informasi akademik dengan mudah kapan saja.</p>
                <ul class="role-features">
                  <li><i class="bi bi-check-circle-fill"></i> Lihat nilai dan rapor online</li>
                  <li><i class="bi bi-check-circle-fill"></i> Akses jadwal dan materi pelajaran</li>
                  <li><i class="bi bi-check-circle-fill"></i> Cek kehadiran dan izin</li>
                  <li><i class="bi bi-check-circle-fill"></i> Informasi tugas dan pengumuman</li>
                </ul>
              </div>
              <div class="role-image">
                <div class="role-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=400&fit=crop" alt="Siswa Dashboard">
                </div>
              </div>
            </div> -->
 
            <!-- Slide 5: Tata Usaha -->
            <div class="role-slide">
              <div class="role-content">
                <div class="role-badge" style="background: #6366f1;">
                  <i class="bi bi-clipboard-data-fill me-2"></i>Tata Usaha
                </div>
                <h3>Administrasi Sekolah</h3>
                <p> Sistem ini dirancang untuk mempermudah pengelolaan administrasi sekolah,
                    khususnya dalam pencatatan SPP, pembuatan laporan, serta pengelolaan kegiatan sekolah
                    secara terpusat dan efisien.</p>
                <ul class="role-features">
                  <li><i class="bi bi-check-circle-fill"></i> Manajemen dan pemantauan data SPP siswa</li>
                  <li><i class="bi bi-check-circle-fill"></i> Laporan SPP bulanan yang rapi dan terstruktur</li>
                  <li><i class="bi bi-check-circle-fill"></i> Rekap laporan SPP tahunan secara otomatis</li>
                  <li><i class="bi bi-check-circle-fill"></i> Pengelolaan kegiatan sekolah </li>
                </ul>
              </div>
              <div class="role-image">
                <div class="role-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop" alt="Tata Usaha Dashboard">
                </div>
              </div>
            </div>
 
            <!-- Slide 6: Payroll/Keuangan -->
            <div class="role-slide">
              <div class="role-content">
                <div class="role-badge" style="background: #ec4899;">
                  <i class="bi bi-calculator-fill me-2"></i>Payroll & Keuangan
                </div>
                <h3>Manajemen Keuangan</h3>
                <p>Kelola gaji guru dan staff, pembayaran SPP, serta seluruh transaksi keuangan sekolah dengan transparan dan akurat.</p>
                <ul class="role-features">
                  <li><i class="bi bi-check-circle-fill"></i> Penggajian otomatis</li>
                  <li><i class="bi bi-check-circle-fill"></i> Manajemen pembayaran SPP</li>
                  <li><i class="bi bi-check-circle-fill"></i> Laporan keuangan lengkap</li>
                  <li><i class="bi bi-check-circle-fill"></i> Tracking transaksi real-time</li>
                </ul>
              </div>
              <div class="role-image">
                <div class="role-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&h=400&fit=crop" alt="Payroll Dashboard">
                </div>
              </div>
            </div>
          </div>
        </div>
 
        <div class="slider-nav prev" onclick="prevRoleSlide()">
          <i class="bi bi-chevron-left"></i>
        </div>
        <div class="slider-nav next" onclick="nextRoleSlide()">
          <i class="bi bi-chevron-right"></i>
        </div>
 
        <div class="slider-indicators" id="roleIndicators">
          <div class="slider-indicator active" onclick="goToRoleSlide(0)"></div>
          <div class="slider-indicator" onclick="goToRoleSlide(1)"></div>
          <div class="slider-indicator" onclick="goToRoleSlide(2)"></div>
          <div class="slider-indicator" onclick="goToRoleSlide(3)"></div>
          <!-- <div class="slider-indicator" onclick="goToRoleSlide(4)"></div>
          <div class="slider-indicator" onclick="goToRoleSlide(5)"></div> -->
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
                <h5>Diono Iwan</h5>
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
  <footer class="footer" id="contact">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5><i class="bi bi-mortarboard-fill"></i> School MS</h5>
          <p class="text">Sistem Manajemen Sekolah yang modern, efisien, dan mudah digunakan untuk mendukung kemajuan pendidikan di Indonesia.</p>
          <div class="social-links">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <h5>Menu</h5>
          <ul class="footer-links">
            <li><a href="#">Beranda</a></li>
            <li><a href="#features">Fitur</a></li>
            <li><a href="#contact">Tentang</a></li>
            <li><a href="#contact">Kontak</a></li>
          </ul>
        </div>
        <div class="col-md-2 mb-4">
          <h5>Layanan</h5>
          <ul class="footer-links">
            <li><a href="#">Demo</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">Support</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>
        <div class="col-md-3 mb-4">
          <h5>Kontak</h5>
          <ul class="footer-links">
            <li><i class="bi bi-envelope me-2"></i>info@schoolms.id</li>
            <li><i class="bi bi-telephone me-2"></i>+62 858-1709-3056</li>
            <li><i class="bi bi-geo-alt me-2"></i>Depok, West Java, ID</li>
            <li><i class="bi bi-clock me-2"></i>Senin - Jumat, 10:00 - 17:00</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 School Management System. All rights reserved. Made with <i class="bi bi-heart-fill text-danger"></i> in Indonesia</p>
      </div>
    </div>
  </footer>
 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Hero Slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
 
    function showSlide(n) {
      slides.forEach(slide => slide.classList.remove('active'));
      dots.forEach(dot => dot.classList.remove('active'));
 
      currentSlide = (n + slides.length) % slides.length;
      slides[currentSlide].classList.add('active');
      dots[currentSlide].classList.add('active');
    }
 
    function nextSlide() {
      showSlide(currentSlide + 1);
    }
 
    // Auto slide
    setInterval(nextSlide, 5000);
 
    // Dot navigation
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => showSlide(index));
    });
 
    // Video Modal
    function openVideoModal() {
      const modal = document.getElementById('videoModal');
      const iframe = document.getElementById('videoFrame');
      iframe.src = 'https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1';
      modal.classList.add('active');
    }
 
    function closeVideoModal() {
      const modal = document.getElementById('videoModal');
      const iframe = document.getElementById('videoFrame');
      iframe.src = '';
      modal.classList.remove('active');
    }
 
    // Close modal on click outside
    document.getElementById('videoModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeVideoModal();
      }
    });
 
    // Role Slider
    let currentRoleSlide = 0;
    const roleSlides = document.getElementById('roleSlides');
    const roleIndicators = document.querySelectorAll('#roleIndicators .slider-indicator');
    const totalRoleSlides = 4;
 
    function updateRoleSlide() {
      roleSlides.style.transform = `translateX(-${currentRoleSlide * 100}%)`;
      roleIndicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentRoleSlide);
      });
    }
 
    function nextRoleSlide() {
      currentRoleSlide = (currentRoleSlide + 1) % totalRoleSlides;
      updateRoleSlide();
    }
 
    function prevRoleSlide() {
      currentRoleSlide = (currentRoleSlide - 1 + totalRoleSlides) % totalRoleSlides;
      updateRoleSlide();
    }
 
    function goToRoleSlide(index) {
      currentRoleSlide = index;
      updateRoleSlide();
    }
 
    // Auto slide for roles
    setInterval(nextRoleSlide, 6000);
 
    // Counter Animation
    function animateCounter(element) {
      const target = parseInt(element.getAttribute('data-target'));
      const duration = 2000;
      const step = target / (duration / 16);
      let current = 0;
 
      const timer = setInterval(() => {
        current += step;
        if (current >= target) {
          element.textContent = target.toLocaleString();
          clearInterval(timer);
        } else {
          element.textContent = Math.floor(current).toLocaleString();
        }
      }, 16);
    }
 
    // Intersection Observer for counter
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && entry.target.textContent === '0') {
          animateCounter(entry.target);
        }
      });
    }, { threshold: 0.5 });
 
    document.querySelectorAll('.counter').forEach(counter => {
      counterObserver.observe(counter);
    });
 
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
 
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
          e.preventDefault();
          const target = document.querySelector(href);
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        }
      });
    });
  </script>