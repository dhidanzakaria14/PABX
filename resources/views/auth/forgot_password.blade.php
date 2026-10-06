<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - ANGKASA PURA PABX BILLING</title>
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

        /* Top Red Header Bar */
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

        /* Main Recovery Area */
        .recovery-main-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
            background: radial-gradient(circle at 50% 20%, rgba(211, 47, 47, 0.06) 0%, transparent 70%);
        }

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

        /* Left Side: Angkasa Pura Red */
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
            margin: 2rem 0;
        }

        .hero-headline {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.3;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .hero-headline span {
            color: #fecdd3;
        }

        .hero-desc {
            font-size: 0.9rem;
            color: #ffe4e6;
            margin-top: 0.75rem;
            line-height: 1.6;
            max-width: 460px;
        }

        .security-badge-box {
            margin-top: 1.75rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .security-step-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.825rem;
            color: #ffffff;
            line-height: 1.4;
        }

        .step-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #ffffff;
            color: #b71c1c;
            font-size: 0.75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Right Side: Form Card */
        .auth-form-side {
            background: #ffffff;
            padding: 2.75rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 1.5rem;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .form-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 4px;
        }

        /* Alerts */
        .alert-box {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.825rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 1.25rem;
        }

        .alert-box.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Inputs */
        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.775rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 5px;
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
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .custom-input {
            width: 100%;
            height: 44px;
            padding: 8px 14px 8px 42px;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.875rem;
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
            font-size: 0.95rem;
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

        .btn-submit-reset {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.925rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(211, 47, 47, 0.35);
            transition: all 0.2s ease;
            margin-top: 1.25rem;
        }

        .btn-submit-reset:hover {
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.45);
            transform: translateY(-1px);
        }

        .btn-back-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px;
            margin-top: 0.75rem;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .btn-back-login:hover {
            background: #f1f5f9;
            color: #d32f2f;
        }

        .auth-footer-help {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.75rem;
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
                padding: 2.25rem 1.5rem;
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

    <div class="recovery-main-wrapper">
        <div class="auth-container">
            <!-- Left Side: Brand Showcase & Instructions (Angkasa Pura Red) -->
            <div class="auth-hero-side">
                <div class="hero-decor-circle"></div>

                <div class="hero-brand-top">
                    <div class="brand-badge-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <div class="brand-text-title">ANGKASA PURA</div>
                        <div class="brand-text-sub">Security & Account Recovery</div>
                    </div>
                </div>

                <div class="hero-middle-content">
                    <h1 class="hero-headline">
                        Pemulihan Kata Sandi<br><span>Akun Pengguna</span>
                    </h1>
                    <p class="hero-desc">
                        Layanan mandiri pengaturan ulang kata sandi akun PABX Billing System secara terenkripsi dan aman.
                    </p>

                    <div class="security-badge-box">
                        <div class="security-step-item">
                            <div class="step-num">1</div>
                            <div>Masukkan <strong>Username</strong> dan <strong>Nomor Telepon/Ext</strong> yang terdaftar di akun Anda.</div>
                        </div>
                        <div class="security-step-item">
                            <div class="step-num">2</div>
                            <div>Tuliskan <strong>Kata Sandi Baru</strong> dengan minimal 4 karakter kombinasi.</div>
                        </div>
                        <div class="security-step-item">
                            <div class="step-num">3</div>
                            <div>Klik <strong>Atur Ulang Kata Sandi</strong> untuk langsung mengaktifkan kata sandi baru.</div>
                        </div>
                    </div>
                </div>

                <div class="hero-footer-status">
                    <div class="server-status-pill">
                        <span class="dot-pulse"></span>
                        <span>Server PABX: Gateway Aman</span>
                    </div>
                    <div>Bantuan: Ext 101</div>
                </div>
            </div>

            <!-- Right Side: Reset Password Form -->
            <div class="auth-form-side">
                <div class="form-header">
                    <h2 class="form-title">Lupa Password?</h2>
                    <p class="form-subtitle">Verifikasi identitas akun Anda untuk membuat kata sandi baru.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-box error">
                        <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem; margin-top: 1px;"></i>
                        <div>
                            <div style="font-weight: 700; margin-bottom: 2px;">Terjadi Kesalahan:</div>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <!-- Username Field -->
                    <div class="form-group">
                        <label class="form-label" for="username">Username Akun</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon-lead"></i>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="custom-input" 
                                value="{{ old('username') }}" 
                                placeholder="Contoh: xxadmin atau admin" 
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Phone / Ext Number Field -->
                    <div class="form-group">
                        <label class="form-label" for="telp">Nomor Telepon / Ext Terdaftar</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-phone input-icon-lead"></i>
                            <input 
                                type="text" 
                                id="telp" 
                                name="telp" 
                                class="custom-input" 
                                value="{{ old('telp') }}" 
                                placeholder="Contoh: 0811200 / ext telepon"
                            >
                        </div>
                        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 3px;">
                            *Masukkan nomor kontak sesuai data profil akun Anda.
                        </div>
                    </div>

                    <!-- New Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="passwordInput">Kata Sandi Baru</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon-lead"></i>
                            <input 
                                type="password" 
                                id="passwordInput" 
                                name="password" 
                                class="custom-input" 
                                placeholder="Minimal 4 karakter" 
                                required
                                style="padding-right: 44px;"
                            >
                            <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('passwordInput', 'pwdToggleIcon1')" title="Lihat / Sembunyikan Password">
                                <i class="fa-solid fa-eye" id="pwdToggleIcon1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="passwordConfirmInput">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-shield-halved input-icon-lead"></i>
                            <input 
                                type="password" 
                                id="passwordConfirmInput" 
                                name="password_confirmation" 
                                class="custom-input" 
                                placeholder="Ulangi kata sandi baru" 
                                required
                                style="padding-right: 44px;"
                            >
                            <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('passwordConfirmInput', 'pwdToggleIcon2')" title="Lihat / Sembunyikan Password">
                                <i class="fa-solid fa-eye" id="pwdToggleIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit-reset">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Atur Ulang & Simpan Kata Sandi</span>
                    </button>

                    <!-- Back to Login Button -->
                    <a href="{{ route('login') }}" class="btn-back-login">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Halaman Login</span>
                    </a>
                </form>

                <div class="auth-footer-help">
                    &copy; 2019 - 2026 PT Angkasa Pura Indonesia &bull; PABX System
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
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
