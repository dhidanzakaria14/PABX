<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ANGKASA PURA PABX</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --ap-red: #d32f2f;
            --ap-dark-red: #b71c1c;
            --bg-body: #edf2f7;
            --btn-blue: #0288d1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Red Header */
        .top-header {
            background: linear-gradient(90deg, #d32f2f, #e53935);
            height: 54px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
        }

        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }

        .login-card-header {
            background: linear-gradient(135deg, #e65100 0%, #d81b60 50%, #8e24aa 100%);
            padding: 1.5rem 1.25rem;
            color: white;
            text-align: center;
        }

        .login-card-header i {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .login-card-header h2 {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .login-card-header p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 4px;
        }

        .login-card-body {
            padding: 1.75rem 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .form-label {
            font-size: 0.825rem;
            font-weight: 600;
            color: #334155;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group i {
            position: absolute;
            left: 12px;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            height: 38px;
            padding: 6px 12px 6px 36px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 0.875rem;
            color: #1e293b;
            outline: none;
            transition: all 0.15s;
        }

        .form-input:focus {
            border-color: #0288d1;
            box-shadow: 0 0 0 2px rgba(2, 136, 209, 0.2);
        }

        .btn-submit {
            width: 100%;
            height: 38px;
            background: #d32f2f;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: #b71c1c;
        }

        .alert-box {
            padding: 0.75rem 1rem;
            border-radius: 4px;
            font-size: 0.825rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-box.success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .alert-box.error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .login-footer {
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            padding-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <header class="top-header">
        <a href="{{ route('home') }}" class="brand-text">ANGKASA PURA</a>
    </header>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-card-header">
                <i class="fa-solid fa-phone-volume"></i>
                <h2>PABX BILLING SYSTEM</h2>
                <p>Silakan login untuk masuk ke aplikasi</p>
            </div>

            <div class="login-card-body">
                @if (session('success'))
                    <div class="alert-box success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-box error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-group">
                            <i class="fa-solid fa-user"></i>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                value="{{ old('username') }}" 
                                placeholder="Masukkan username" 
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group">
                            <i class="fa-solid fa-lock"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Masukkan password" 
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Sign In</span>
                    </button>
                </form>
            </div>

            <div class="login-footer">
                &copy; 2019 - 2026 Angkasa Pura PABX System
            </div>
        </div>
    </div>
</body>
</html>
