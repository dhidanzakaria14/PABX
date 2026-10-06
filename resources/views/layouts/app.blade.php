<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ANGKASA PURA - PABX Billing')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --ap-red: #d32f2f;
            --ap-dark-red: #b71c1c;
            --sidebar-bg: #f8fafc;
            --sidebar-border: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-body: #edf2f7;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --btn-blue: #0288d1;
            --btn-blue-hover: #0277bd;
            --btn-green: #2e7d32;
            --btn-green-hover: #1b5e20;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Prevent giant SVGs from breaking layout */
        svg {
            max-width: 100%;
        }
        svg.w-5, .w-5, svg.h-5, .h-5, nav[role="navigation"] svg {
            width: 18px !important;
            height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            display: inline-block !important;
            vertical-align: middle;
        }

        /* Top Red Header Bar */
        .top-header {
            background: linear-gradient(90deg, #d32f2f, #e53935);
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
        }

        .menu-toggle-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.15rem;
            cursor: pointer;
            padding: 4px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .live-badge {
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
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

        /* App Wrapper: Sidebar + Main Content */
        .app-wrapper {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 54px);
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        /* User Profile Box */
        .user-panel {
            background: linear-gradient(135deg, #e65100 0%, #d81b60 50%, #8e24aa 100%);
            padding: 1rem 1rem;
            color: white;
            position: relative;
            box-shadow: inset 0 -1px 3px rgba(0,0,0,0.1);
        }

        .user-panel-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background 0.15s;
        }

        .user-panel-info:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-details {
            flex: 1;
            overflow: hidden;
        }

        .user-name {
            font-weight: 700;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* User Dropdown Menu */
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% - 6px);
            left: 12px;
            right: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            z-index: 1100;
            display: none;
            overflow: hidden;
            animation: fadeInDropdown 0.15s ease-out;
            padding: 4px;
        }

        .user-dropdown-menu.show {
            display: block;
        }

        @keyframes fadeInDropdown {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            font-size: 0.825rem;
            color: #334155;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.15s;
            cursor: pointer;
            border: none;
            width: 100%;
            background: none;
            text-align: left;
            border-radius: 5px;
        }

        .user-dropdown-item i {
            width: 18px;
            text-align: center;
            color: #64748b;
            font-size: 0.875rem;
            transition: color 0.15s;
        }

        .user-dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0288d1;
        }

        .user-dropdown-item:hover i {
            color: #0288d1;
        }

        .user-dropdown-item.sign-out:hover {
            background-color: #fff1f2;
            color: #e11d48;
        }

        .user-dropdown-item.sign-out:hover i {
            color: #e11d48;
        }

        .user-dropdown-divider {
            height: 1px;
            background-color: #f1f5f9;
            margin: 4px 6px;
        }

        /* Navigation Menu */
        .sidebar-nav {
            padding: 0.75rem 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section-title {
            padding: 0.5rem 1.25rem 0.35rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-list {
            list-style: none;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 1.25rem;
            color: #334155;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            cursor: pointer;
            user-select: none;
        }

        .nav-toggle-icon {
            font-size: 0.75rem;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .nav-link:hover .nav-toggle-icon {
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-link-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link-left i {
            width: 18px;
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .nav-link.active-root {
            background: #fee2e2;
            color: #b91c1c;
            font-weight: 600;
            border-left: 3px solid #d32f2f;
        }

        .nav-link.active-root i {
            color: #d32f2f;
        }

        .nav-link.menu-open {
            color: #0f172a;
            font-weight: 600;
        }

        .nav-link.menu-open .nav-toggle-icon {
            color: #d32f2f !important;
        }

        /* Sub-menu tree */
        .sub-menu {
            list-style: none;
            background: #f1f5f9;
            padding: 0.25rem 0;
        }

        .sub-nav-link {
            display: block;
            padding: 0.45rem 1.25rem 0.45rem 2.85rem;
            color: #475569;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.15s ease;
        }

        .sub-nav-link:hover {
            color: #d32f2f;
            background: rgba(211, 47, 47, 0.06);
            padding-left: 3.1rem;
        }

        .sub-nav-link.active {
            color: #b91c1c;
            font-weight: 700;
            background: rgba(211, 47, 47, 0.1);
            border-left: 3px solid #d32f2f;
        }

        .sub-nav-link.active::before {
            content: '>';
            margin-right: 4px;
            font-weight: bold;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--sidebar-border);
            font-size: 0.725rem;
            color: #94a3b8;
            text-align: center;
        }

        /* Content Area */
        .main-content {
            flex: 1;
            padding: 1.5rem 1.75rem;
            overflow-x: auto;
        }

        /* Classic Angkasa Pura White Report Card */
        .report-card {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            margin-bottom: 2rem;
        }

        .report-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Filters Bar */
        .filter-section {
            padding: 1.25rem;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 1.25rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .filter-label {
            font-size: 0.775rem;
            color: #475569;
            font-weight: 600;
        }

        .input-text, .select-box {
            height: 32px;
            padding: 4px 8px;
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            font-size: 0.8rem;
            color: #1e293b;
            outline: none;
            background: white;
            min-width: 140px;
        }

        .input-text:focus, .select-box:focus {
            border-color: #0288d1;
            box-shadow: 0 0 0 1px #0288d1;
        }

        /* Buttons */
        .btn-search {
            height: 32px;
            padding: 0 16px;
            background: var(--btn-blue);
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }

        .btn-search:hover {
            background: var(--btn-blue-hover);
        }

        .btn-export {
            height: 32px;
            padding: 0 16px;
            background: var(--btn-green);
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
            text-decoration: none;
        }

        .btn-export:hover {
            background: var(--btn-green-hover);
        }

        /* Clean Corporate Data Table */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .ap-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.825rem;
            text-align: left;
        }

        .ap-table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .ap-table td {
            padding: 9px 14px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }

        .ap-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .ap-table tfoot td {
            background: #f8fafc;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            color: #0f172a;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Alert notifications */
        .alert-bar {
            padding: 0.75rem 1.25rem;
            border-radius: 4px;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-bar.success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Red Header Bar -->
    <header class="top-header">
        <div class="header-brand">
            <button class="menu-toggle-btn" title="Toggle Navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="brand-text">ANGKASA PURA</a>
        </div>
        <div class="header-right">
            <div class="live-badge">
                <div class="pulse-dot"></div>
                <span>Server PABX: 10.3.16.12 (Connected)</span>
            </div>
            <a href="{{ route('billing.schema') }}" style="color: white; text-decoration: none; font-size: 0.8rem; background: rgba(0,0,0,0.18); padding: 4px 10px; border-radius: 4px;" title="Cek Relasi Database 25 Tabel">
                <i class="fa-solid fa-diagram-project"></i> Relasi DB
            </a>
            <a href="http://localhost/phpmyadmin/index.php?db=pabx" target="_blank" style="color: white; text-decoration: none; font-size: 0.8rem;" title="Buka di phpMyAdmin">
                <i class="fa-solid fa-database"></i> phpMyAdmin
            </a>
            <i class="fa-solid fa-ellipsis-vertical" style="cursor: pointer;"></i>
        </div>
    </header>

    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- User Profile Box with Abstract Pattern -->
            <div class="user-panel" id="userPanelContainer">
                <div class="user-panel-info" id="userPanelToggle" title="Klik untuk menu profil dan sign out">
                    <div class="user-avatar">
                        @if(isset($currentUser) && $currentUser && $currentUser->photo && file_exists(public_path('uploads/profile/' . $currentUser->photo)))
                            <img src="{{ asset('uploads/profile/' . $currentUser->photo) }}" alt="Avatar">
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ $currentUser->nama ?? $currentUser->username ?? 'xadmin' }}</div>
                        <div class="user-role">{{ $currentUser->leveluser ?? ($currentUser->level->level ?? 'Administrator') }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down" id="userChevron" style="font-size: 0.75rem; opacity: 0.8; transition: transform 0.2s;"></i>
                </div>

                <!-- Floating Dropdown Menu -->
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    <a href="{{ route('profile.show') }}" class="user-dropdown-item">
                        <i class="fa-solid fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <div class="user-dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="user-dropdown-item sign-out" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="sidebar-nav">
                <div class="nav-section-title">MAIN NAVIGATION</div>
                <ul class="nav-list">
                    <!-- Home -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') || request()->routeIs('billing.index') ? 'active-root' : '' }}">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-house"></i>
                                <span>Home</span>
                            </div>
                        </a>
                    </li>

                    <!-- Setting -->
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link" onclick="toggleNavCollapse(this, 'sub-setting')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-table-cells-large"></i>
                                <span>Setting</span>
                            </div>
                            <i class="fa-solid fa-plus nav-toggle-icon" style="font-size: 0.75rem; color: #94a3b8; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-setting" class="sub-menu" style="display: none;">
                            <li><a href="{{ route('home') }}" class="sub-nav-link">PABX Machines</a></li>
                            <li><a href="{{ route('home') }}" class="sub-nav-link">Extension Master</a></li>
                            <li><a href="{{ route('home') }}" class="sub-nav-link">Tariff & Rate Code</a></li>
                            <li><a href="{{ route('home') }}" class="sub-nav-link">Area & Zone Prefix</a></li>
                        </ul>
                    </li>

                    <!-- Report (Default Expanded) -->
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link {{ request()->is('reports*') ? 'active-root' : '' }}" onclick="toggleNavCollapse(this, 'sub-reports')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-table-cells"></i>
                                <span>Report</span>
                            </div>
                            <i class="fa-solid fa-minus nav-toggle-icon" style="font-size: 0.75rem; color: #d32f2f; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-reports" class="sub-menu" style="display: block;">
                            <li>
                                <a href="{{ route('reports.division-summary') }}" class="sub-nav-link {{ request()->routeIs('reports.division-summary') ? 'active' : '' }}">
                                    Division Summary
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.favourite-area') }}" class="sub-nav-link {{ request()->routeIs('reports.favourite-area') ? 'active' : '' }}">
                                    Favourite Area
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.favourite-business') }}" class="sub-nav-link {{ request()->routeIs('reports.favourite-business') ? 'active' : '' }}">
                                    Favourite Business
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.peak-time') }}" class="sub-nav-link {{ request()->routeIs('reports.peak-time') ? 'active' : '' }}">
                                    Peak Time
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}" class="sub-nav-link">
                                    Personal Detail
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.favourite-area') }}" class="sub-nav-link">
                                    Personal Favorite Area
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.personal-favorite-dialed') }}" class="sub-nav-link {{ request()->routeIs('reports.personal-favorite-dialed') ? 'active' : '' }}">
                                    Personal Favorite Dialed Number
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.personal-summary') }}" class="sub-nav-link {{ request()->routeIs('reports.personal-summary') ? 'active' : '' }}">
                                    Personal Summary
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}" class="sub-nav-link">
                                    Phone-ID Detail
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}" class="sub-nav-link">
                                    Phone-ID Summary
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.division-summary') }}" class="sub-nav-link">
                                    Unit Summary
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Information -->
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link" onclick="toggleNavCollapse(this, 'sub-info')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Information</span>
                            </div>
                            <i class="fa-solid fa-plus nav-toggle-icon" style="font-size: 0.75rem; color: #94a3b8; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-info" class="sub-menu" style="display: none;">
                            <li><a href="{{ route('home') }}" class="sub-nav-link">Raw SMDR Log Buffer</a></li>
                            <li><a href="{{ route('home') }}" class="sub-nav-link">Scheduler Job Logs</a></li>
                        </ul>
                    </li>

                    <!-- Utility -->
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link" onclick="toggleNavCollapse(this, 'sub-util')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-wrench"></i>
                                <span>Utility</span>
                            </div>
                            <i class="fa-solid fa-plus nav-toggle-icon" style="font-size: 0.75rem; color: #94a3b8; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-util" class="sub-menu" style="display: none;">
                            <li><a href="{{ route('billing.schema') }}" class="sub-nav-link">Relasi Database (25 Tabel)</a></li>
                            <li><a href="http://localhost/phpmyadmin/index.php?db=pabx" target="_blank" class="sub-nav-link">phpMyAdmin Designer</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div>&copy; 2019 - 2026 <strong>PABX Billing</strong></div>
                <div>Angkasa Pura PABX System</div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert-bar success">
                    <i class="fa-solid fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Toggle Sidebar Accordion Sub-Menu with dynamic +/- indicator
        function toggleNavCollapse(navLink, subMenuId) {
            const subMenu = document.getElementById(subMenuId);
            if (!subMenu) return;

            const icon = navLink.querySelector('.nav-toggle-icon');
            const isClosed = subMenu.style.display === 'none' || window.getComputedStyle(subMenu).display === 'none';

            if (isClosed) {
                subMenu.style.display = 'block';
                navLink.classList.add('menu-open');
                if (icon) {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                    icon.style.color = '#d32f2f';
                }
            } else {
                subMenu.style.display = 'none';
                navLink.classList.remove('menu-open');
                if (icon) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                    icon.style.color = '#94a3b8';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // User Panel Dropdown
            const userPanelToggle = document.getElementById('userPanelToggle');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const userChevron = document.getElementById('userChevron');

            if (userPanelToggle && userDropdownMenu) {
                userPanelToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = userDropdownMenu.classList.toggle('show');
                    if (userChevron) {
                        userChevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!userDropdownMenu.contains(e.target) && !userPanelToggle.contains(e.target)) {
                        userDropdownMenu.classList.remove('show');
                        if (userChevron) {
                            userChevron.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            }

            // Mobile / Desktop Hamburger Menu Toggle
            const menuToggleBtn = document.querySelector('.menu-toggle-btn');
            const sidebar = document.querySelector('.sidebar');
            if (menuToggleBtn && sidebar) {
                menuToggleBtn.addEventListener('click', function() {
                    if (sidebar.style.display === 'none') {
                        sidebar.style.display = 'flex';
                    } else {
                        sidebar.style.display = 'none';
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
