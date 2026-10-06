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
            --primary-blue: #0288d1;
            --primary-hover: #0277bd;
            --text-main: #0f172a;
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
            background: linear-gradient(135deg, #091223 0%, #102a45 45%, #0f1e36 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glowing Background Elements */
        .ambient-glow-1 {
            position: absolute;
            top: -100px;
            left: -100px;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(2, 136, 209, 0.25) 0%, rgba(2, 136, 209, 0) 70%);
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: -120px;
            right: -120px;
            width: 550px;
            height: 550px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(211, 47, 47, 0.22) 0%, rgba(211, 47, 47, 0) 70%);
            pointer-events: none;
        }

        /* Main Container: Split-Screen Hero */
        .auth-container {
            width: 100%;
            max-width: 1080px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            min-height: 600px;
            position: relative;
            z-index: 10;
        }

        /* Left Side: Brand & Aerospace Telephony Showcase */
        .auth-hero-side {
            background: linear-gradient(145deg, #0f1c3f 0%, #172c5b 50%, #0d1938 100%);
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
            width: 380px;
            height: 380px;
            border: 1px dashed rgba(255, 255, 255, 0.12);
            border-radius: 50%;
            right: -140px;
            top: -100px;
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
            background: linear-gradient(135deg, #0288d1, #0277bd);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 8px 18px rgba(2, 136, 209, 0.4);
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
            color: #93c5fd;
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
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 0.9rem;
            color: #cbd5e1;
            margin-top: 0.75rem;
            line-height: 1.6;
            max-width: 460px;
        }

        .security-badge-box {
            margin-top: 1.75rem;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
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
            color: #e2e8f0;
            line-height: 1.4;
        }

        .step-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #0288d1;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
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
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .server-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .dot-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 8px #22c55e;
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
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            background: #ffffff;
            border-color: #0288d1;
            box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15);
        }

        .input-wrapper:focus-within .input-icon-lead {
            color: #0288d1;
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
            color: #0288d1;
        }

        .btn-submit-reset {
            width: 100%;
            height: 46px;
            background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
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
            box-shadow: 0 4px 14px rgba(2, 136, 209, 0.35);
            transition: all 0.2s ease;
            margin-top: 1.25rem;
        }

        .btn-submit-reset:hover {
            background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
            box-shadow: 0 6px 20px rgba(2, 136, 209, 0.45);
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
            color: #0f172a;
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
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="auth-container">
        <!-- Left Side: Brand Showcase & Instructions -->
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
