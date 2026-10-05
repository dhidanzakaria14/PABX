<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PABX Telephone Billing System')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #0b0f19;
            --bg-card: #111827;
            --bg-card-hover: #172033;
            --bg-glass: rgba(17, 24, 39, 0.85);
            --border-glass: rgba(255, 255, 255, 0.08);
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: rgba(79, 70, 229, 0.15);
            --emerald: #10b981;
            --emerald-light: rgba(16, 185, 129, 0.15);
            --amber: #f59e0b;
            --amber-light: rgba(245, 158, 11, 0.15);
            --cyan: #06b6d4;
            --cyan-light: rgba(6, 182, 212, 0.15);
            --rose: #f43f5e;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(at 10% 20%, rgba(79, 70, 229, 0.12) 0px, transparent 40%),
                radial-gradient(at 90% 10%, rgba(6, 182, 212, 0.1) 0px, transparent 40%),
                radial-gradient(at 50% 90%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* Navbar */
        .navbar {
            background: var(--bg-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-glass);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0.85rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        .navbar-brand .icon-badge {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            font-size: 1rem;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-link.active {
            color: #818cf8;
            background: var(--primary-light);
            border: 1px solid rgba(129, 140, 248, 0.2);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .badge-live {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        /* Container */
        .container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 2rem;
            width: 100%;
            flex: 1;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        /* Typography */
        h1, h2, h3, h4, h5 {
            color: white;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .text-muted {
            color: var(--text-muted);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.5);
        }

        .btn-emerald {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        }

        .btn-emerald:hover {
            background: linear-gradient(135deg, #047857, #059669);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.07);
            color: var(--text-main);
            border: 1px solid var(--border-glass);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
        }

        /* Alert notifications */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.925rem;
            animation: fadeIn 0.3s ease;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--border-glass);
            padding: 1.5rem 2rem;
            background: var(--bg-card);
            color: var(--text-muted);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-tech {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('billing.index') }}" class="navbar-brand">
            <div class="icon-badge">
                <i class="fa-solid fa-phone-volume"></i>
            </div>
            <span>PABX Billing Pro</span>
        </a>

        <ul class="navbar-menu">
            <li>
                <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard & Billing
                </a>
            </li>
            <li>
                <a href="{{ route('billing.schema') }}" class="nav-link {{ request()->routeIs('billing.schema') ? 'active' : '' }}">
                    <i class="fa-solid fa-diagram-project"></i> Relasi Database (25 Tabel)
                </a>
            </li>
            <li>
                <a href="http://localhost/phpmyadmin/index.php?db=pabx" target="_blank" class="nav-link">
                    <i class="fa-solid fa-database"></i> phpMyAdmin Designer <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem; margin-left: 2px;"></i>
                </a>
            </li>
        </ul>

        <div class="navbar-actions">
            <div class="badge-live">
                <div class="pulse-dot"></div>
                <span>MySQL: Connected (pabx)</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-left">
            <span>&copy; {{ date('Y') }} <strong>PABX Telephone Billing System</strong> — Relational Database v2.0 (InnoDB)</span>
        </div>
        <div class="footer-tech">
            <span><i class="fa-solid fa-server"></i> MySQL 3306</span>
            <span><i class="fa-brands fa-laravel"></i> Laravel 12</span>
            <span><i class="fa-solid fa-microchip"></i> SMDR CDR Parser</span>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
