<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* CSS kamu tetap sama */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a4d3f0ff 0%, #b3daf4ff 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; width: 400px; height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%; top: -150px; right: -150px;
        }
        body::after {
            content: ''; position: absolute; width: 300px; height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%; bottom: -100px; left: -100px;
        }
        .login-container { position: relative; z-index: 2; width: 100%; max-width: 900px; padding: 20px; }
        .login-card {
            background: white; border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden; animation: slideUp 0.5s ease;
            display: flex; min-height: 500px;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #b6d4e9ff 0%, #2796dfff 100%);
            padding: 50px 40px; color: white;
            display: flex; flex-direction: column; justify-content: center;
            text-align: center;
        }
        .login-left i { font-size: 4rem; margin-bottom: 20px; opacity: 0.9; }
        .login-left h3 { font-size: 2rem; font-weight: 700; margin-bottom: 10px; }
        .login-left p { font-size: 1.1rem; opacity: 0.9; margin: 0; }
        .login-right { flex: 1; padding: 50px 40px; display: flex; flex-direction: column; justify-content: center; }
        .form-label { font-weight: 600; color: #2d3748; margin-bottom: 8px; font-size: 0.9rem; }
        .input-group-custom { position: relative; margin-bottom: 20px; }
        .input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 1.1rem; z-index: 10; }
        .form-control {
            border: 2px solid #e2e8f0; border-radius: 12px;
            padding: 12px 15px 12px 45px;
            font-size: 0.95rem; transition: all 0.3s ease; background: #f7fafc;
        }
        .form-control:focus {
            border-color: #3282b8; background: white;
            box-shadow: 0 0 0 4px rgba(50, 130, 184, 0.1); outline: none;
        }
        .btn-login {
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
            border: none; border-radius: 12px; padding: 14px;
            font-size: 1rem; font-weight: 600; color: white;
            width: 100%; transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(15, 76, 117, 0.4); margin-top: 10px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(15, 76, 117, 0.5); }
        .password-toggle {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #a0aec0; font-size: 1.1rem; z-index: 10; transition: color 0.3s ease;
        }
        .password-toggle:hover { color: #3282b8; }
        .remember-me { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-left">
                <i class="bi bi-mortarboard-fill"></i>
                <h3>Selamat Datang</h3>
                <p>Sistem Manajemen Sekolah</p>
            </div>

            <div class="login-right">
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
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Masukkan email"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                required
                            >
                            <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- 🔹 Checkbox Remember Me -->
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe">
                        <label for="rememberMe" class="form-label mb-0">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>

                <div class="back-link">
                    <a href="/">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 🔹 Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // 🔹 Remember Me logic using localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const rememberCheckbox = document.getElementById('rememberMe');

            // Load saved credentials
            const savedEmail = localStorage.getItem('remember_email');
            const savedPassword = localStorage.getItem('remember_password');
            const remember = localStorage.getItem('remember_checked') === 'true';

            if (remember && savedEmail && savedPassword) {
                emailInput.value = savedEmail;
                passwordInput.value = savedPassword;
                rememberCheckbox.checked = true;
            }

            // Save or remove credentials when submitting
            document.getElementById('loginForm').addEventListener('submit', function() {
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
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
