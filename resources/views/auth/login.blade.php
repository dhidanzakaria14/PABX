<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ANGKASA PURA PABX BILLING</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --ap-red: #d32f2f;
            --ap-red-dark: #b71c1c;
            --ap-red-deep: #881337;
            --ap-red-hover: #c62828;
            --bg-body: #edf2f7;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', 'Roboto', sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: var(--bg-body);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Top Red Header Bar (Identik dengan halaman internal aplikasi) */
        .top-header {
            background: linear-gradient(90deg, #d32f2f, #e53935);
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.18);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .live-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #69f0ae;
            box-shadow: 0 0 6px #69f0ae;
        }

        /* Login Main Area */
        .login-main-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
            position: relative;
            background: radial-gradient(circle at 50% 20%, rgba(211, 47, 47, 0.06) 0%, transparent 70%);
        }

        /* Split-Screen Hero Card */
        .auth-container {
            width: 100%;
            max-width: 1060px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 12px 35px -8px rgba(183, 28, 28, 0.15), 0 4px 14px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            min-height: 560px;
            position: relative;
            z-index: 10;
        }

        /* Sisi Kiri: Angkasa Pura Red Hero Showcase */
        .auth-hero-side {
            background: linear-gradient(145deg, #991b1b 0%, #b71c1c 45%, #7f1d1d 100%);
            padding: 3.5rem 3rem;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .hero-decor-circle {
            position: absolute;
            width: 420px;
            height: 420px;
            border: 1.5px dashed rgba(255, 255, 255, 0.18);
            border-radius: 50%;
            right: -150px;
            top: -110px;
            pointer-events: none;
        }

        .hero-brand-top {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-badge-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #ffffff;
            color: #d32f2f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .brand-text-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-text-sub {
            font-size: 0.75rem;
            color: #fecdd3;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .hero-middle-content {
            margin: 2.25rem 0;
        }

        .hero-headline {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1.3;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .hero-headline span {
            color: #fecdd3;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-desc {
            font-size: 0.925rem;
            color: #ffe4e6;
            margin-top: 0.75rem;
            line-height: 1.6;
            max-width: 460px;
            opacity: 0.95;
        }

        .hero-feature-list {
            margin-top: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .hero-feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            color: #ffffff;
        }

        .feature-icon-pill {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .hero-footer-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 0.75rem;
            color: #fecdd3;
        }

        .server-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.28);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .dot-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 6px #4ade80;
        }

        /* Sisi Kanan: Form Login */
        .auth-form-side {
            background: #ffffff;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .form-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 6px;
        }

        /* Alert Notifikasi */
        .alert-box {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.825rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-box.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Inputs */
        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-lead {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .custom-input {
            width: 100%;
            height: 46px;
            padding: 8px 14px 8px 44px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.925rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            background: #ffffff;
            border-color: #d32f2f;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.15);
        }

        .input-wrapper:focus-within .input-icon-lead {
            color: #d32f2f;
        }

        .btn-toggle-eye {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1rem;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s;
        }

        .btn-toggle-eye:hover {
            color: #d32f2f;
        }

        .form-actions-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.25rem;
            margin-bottom: 1.75rem;
            font-size: 0.825rem;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox input {
            accent-color: #d32f2f;
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .link-forgot-pwd {
            color: #d32f2f;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .link-forgot-pwd:hover {
            color: #b71c1c;
            text-decoration: underline;
        }

        /* Tombol Masuk ke Sistem (Angkasa Pura Red) */
        .btn-submit-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(211, 47, 47, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit-login:hover {
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.45);
            transform: translateY(-1px);
        }

        .auth-footer-help {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.775rem;
            color: #94a3b8;
        }

        /* Responsive Breakpoint */
        @media (max-width: 860px) {
            .auth-container {
                grid-template-columns: 1fr;
            }
            .auth-hero-side {
                display: none;
            }
            .auth-form-side {
                padding: 2.5rem 1.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header Merah Khas Angkasa Pura -->
    <header class="top-header">
        <div class="header-brand">
            <a href="{{ route('home') }}" class="brand-text">ANGKASA PURA</a>
        </div>
        <div class="header-right">
            <div class="live-badge">
                <div class="pulse-dot"></div>
                <span>Server PABX: 10.3.16.12 (Connected)</span>
            </div>
        </div>
    </header>

    <div class="login-main-wrapper">
        <div class="auth-container">
            <!-- Sisi Kiri: Angkasa Pura Red Hero Showcase -->
            <div class="auth-hero-side">
                <div class="hero-decor-circle"></div>

                <div class="hero-brand-top">
                    <div class="brand-badge-icon">
                        <i class="fa-solid fa-plane-departure"></i>
                    </div>
                    <div>
                        <div class="brand-text-title">ANGKASA PURA</div>
                        <div class="brand-text-sub">Telecommunication Systems</div>
                    </div>
                </div>

                <div class="hero-middle-content">
                    <h1 class="hero-headline">
                        PABX Billing &<br><span>Monitoring Platform</span>
                    </h1>
                    <p class="hero-desc">
                        Sistem pemantauan telekomunikasi cerdas dan pelaporan tagihan telepon internal terintegrasi untuk seluruh unit operasional bandara.
                    </p>

                    <div class="hero-feature-list">
                        <div class="hero-feature-item">
                            <div class="feature-icon-pill">
                                <i class="fa-solid fa-phone-volume"></i>
                            </div>
                            <span>Pencatatan 50.000+ Call Detail Records (CDR) otomatis</span>
                        </div>

                        <div class="hero-feature-item">
                            <div class="feature-icon-pill">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <span>Analisis tarif multi-zona & laporan telekomunikasi real-time</span>
                        </div>

                        <div class="hero-feature-item">
                            <div class="feature-icon-pill">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <span>Akses terlindungi dengan autentikasi berjenjang</span>
                        </div>
                    </div>
                </div>

                <div class="hero-footer-status">
                    <div class="server-status-pill">
                        <span class="dot-pulse"></span>
                        <span>Server PABX 10.3.16.12 : Online</span>
                    </div>
                    <div>Versi 3.4.2 Enterprise</div>
                </div>
            </div>

            <!-- Sisi Kanan: Form Login -->
            <div class="auth-form-side">
                <div class="form-header">
                    <h2 class="form-title">Selamat Datang</h2>
                    <p class="form-subtitle">Silakan masukkan username dan password Anda untuk masuk.</p>
                </div>

                @if (session('success'))
                    <div class="alert-box success">
                        <i class="fa-solid fa-circle-check" style="font-size: 1rem;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-box error">
                        <i class="fa-solid fa-circle-exclamation" style="font-size: 1rem;"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <!-- Username Field -->
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon-lead"></i>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="custom-input" 
                                value="{{ old('username') }}" 
                                placeholder="Masukkan username akun Anda" 
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="passwordInput">Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon-lead"></i>
                            <input 
                                type="password" 
                                id="passwordInput" 
                                name="password" 
                                class="custom-input" 
                                placeholder="Masukkan kata sandi Anda" 
                                required
                                style="padding-right: 44px;"
                            >
                            <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility()" title="Lihat / Sembunyikan Password">
                                <i class="fa-solid fa-eye" id="pwdToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Actions Row: Remember Me & Forgot Password Link -->
                    <div class="form-actions-row">
                        <label class="remember-checkbox">
                            <input type="checkbox" name="remember" value="1">
                            <span>Ingat saya di perangkat ini</span>
                        </label>

                        <a href="{{ route('password.request') }}" class="link-forgot-pwd" title="Klik untuk mengatur ulang kata sandi jika lupa">
                            <i class="fa-solid fa-key" style="font-size: 0.75rem;"></i>
                            <span>Lupa Password?</span>
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit-login">
                        <span>Masuk ke Sistem</span>
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </button>
                </form>

                <div class="auth-footer-help">
                    &copy; 2019 - 2026 PT Angkasa Pura Indonesia &bull; PABX System
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('pwdToggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
