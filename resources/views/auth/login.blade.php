<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $setting->logo_name ?? 'Sistem Manajemen Sekolah' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #4facfe 50%, #00f2fe 100%);
            min-height: 100vh;
            display: flex; 
            align-items: center; 
            justify-content: center;
            position: relative; 
            overflow: hidden;
            padding: 20px;
        }

        /* Animated background elements */
        .bg-decoration {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .bg-decoration:nth-child(1) {
            top: 10%;
            right: 10%;
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #fff, transparent);
            border-radius: 50%;
            animation-delay: -2s;
        }
        
        .bg-decoration:nth-child(2) {
            bottom: 20%;
            left: 15%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #fff, transparent);
            border-radius: 30%;
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container { 
            position: relative; 
            z-index: 2; 
            width: 100%; 
            max-width: 1000px; 
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            min-height: 600px;
        }

        @keyframes slideUp {
            from { 
                opacity: 0; 
                transform: translateY(40px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #4facfe 50%, #00f2fe 100%);
            padding: 60px 50px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .school-logo {
            width: 240px;
            height: 240px;
            margin: 0 auto 20px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 15px;
        }

        .logo-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #fff, #e6f3ff);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }

        .login-left h3 { 
            font-size: 2.5rem; 
            font-weight: 700; 
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }
        
        .login-left p { 
            font-size: 1.5rem; 
            opacity: 0.9; 
            font-weight: 300;
            position: relative;
            z-index: 2;
        }

        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-title h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .login-title p {
            color: #718096;
            font-weight: 400;
        }

        .form-label { 
            font-weight: 600; 
            color: #2d3748; 
            margin-bottom: 8px; 
            font-size: 0.9rem; 
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px 16px 50px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
            font-weight: 400;
        }

        .form-control:focus {
            border-color: #4facfe;
            background: white;
            box-shadow: 
                0 0 0 4px rgba(79, 172, 254, 0.1),
                0 4px 12px rgba(79, 172, 254, 0.15);
            outline: none;
            transform: translateY(-1px);
        }

        .form-control:focus + .input-icon {
            color: #4facfe;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #4facfe 50%, #00f2fe 100%);
            border: none;
            border-radius: 16px;
            padding: 16px 24px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 8px 25px rgba(79, 172, 254, 0.3),
                0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 16px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 35px rgba(79, 172, 254, 0.4),
                0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 1.1rem;
            z-index: 10;
            transition: all 0.3s ease;
            padding: 4px;
        }
        
        .password-toggle:hover { 
            color: #4facfe;
            transform: translateY(-50%) scale(1.1);
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #4facfe;
            cursor: pointer;
        }

        .remember-me label {
            cursor: pointer;
        }

        .back-button {
            position: absolute;
            top: 24px;
            left: 24px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #4a5568;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 20;
        }

        .back-button:hover {
            background: white;
            color: #4facfe;
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(79, 172, 254, 0.2);
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 24px;
            padding: 16px 20px;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
            color: #c53030;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                min-height: auto;
                margin: 10px;
            }
            
            .login-left {
                padding: 40px 30px;
                min-height: 200px;
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            .login-left h3 {
                font-size: 2rem;
            }
            
            .school-logo {
                width: 100px;
                height: 100px;
            }
            
            .logo-icon {
                font-size: 3.5rem;
            }
            
            .back-button {
                top: 16px;
                left: 16px;
                padding: 10px 14px;
            }
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="bg-decoration"></div>
    <div class="bg-decoration"></div>
    
    <div class="login-container">
        <div class="login-card">
            <a href="/" class="back-button">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div class="login-left">
                <div class="logo-container">
                    @if($setting && $setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}"
                            alt="Logo Sekolah"
                            class="school-logo"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <i class="bi bi-mortarboard-fill logo-icon" style="display: none;"></i>
                    @else
                        <i class="bi bi-mortarboard-fill logo-icon"></i>
                    @endif

                    <h3>Welcome To </h3>
                    <p>{{ $setting->logo_name ?? 'System Management School' }}</p>
                </div>
            </div>

            <div class="login-right">
                <div class="login-title">
                    <h2>Login</h2>
                    <p>Login To Your Account</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group-custom">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="your@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="form-control"
                                   placeholder="Enter your password" required>
                            <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe" class="form-label mb-0">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-login" id="loginBtn">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const loginBtn = document.getElementById('loginBtn');
        const loginForm = document.getElementById('loginForm');

        // Password toggle functionality
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Form submission with loading state
        loginForm.addEventListener('submit', function() {
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<span>Processing...</span>';
            
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const rememberCheckbox = document.getElementById('rememberMe');

            if (rememberCheckbox.checked) {
                localStorage.setItem('remember_email', emailInput.value);
                localStorage.setItem('remember_password', passwordInput.value);
                localStorage.setItem('remember_checked', true);
            } else {
                localStorage.removeItem('remember_email');
                localStorage.removeItem('remember_password');
                localStorage.removeItem('remember_checked');
            }
        });

        // Remember me functionality
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const rememberCheckbox = document.getElementById('rememberMe');

            const savedEmail = localStorage.getItem('remember_email');
            const savedPassword = localStorage.getItem('remember_password');
            const remember = localStorage.getItem('remember_checked') === 'true';

            if (remember && savedEmail && savedPassword) {
                emailInput.value = savedEmail;
                passwordInput.value = savedPassword;
                rememberCheckbox.checked = true;
            }
        });

        // Enhanced input animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('.input-icon').style.transform = 'translateY(-50%) scale(1.1)';
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.querySelector('.input-icon').style.transform = 'translateY(-50%) scale(1)';
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>