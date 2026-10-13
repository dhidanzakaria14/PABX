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
            --ap-primary: #0284c7;
            --ap-primary-dark: #0369a1;
            --ap-red: #0284c7;
            --ap-dark-red: #0369a1;
            --sidebar-bg: #f8fafc;
            --sidebar-border: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f1f5f9;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --btn-blue: #0284c7;
            --btn-blue-hover: #0369a1;
            --btn-green: #059669;
            --btn-green-hover: #047857;
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

        /* Top Modern Ocean Header Bar */
        .top-header {
            background: linear-gradient(90deg, #075985 0%, #0284c7 55%, #0ea5e9 100%);
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            color: white;
            box-shadow: 0 2px 10px rgba(7, 89, 133, 0.22);
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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0369a1 100%);
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
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
            border-left: 3px solid #0284c7;
        }

        .nav-link.active-root i {
            color: #0284c7;
        }

        .nav-link.menu-open {
            color: #0f172a;
            font-weight: 600;
        }

        .nav-link.menu-open .nav-toggle-icon {
            color: #0284c7 !important;
        }

        /* Sub-menu tree */
        .sub-menu {
            list-style: none;
            background: #f8fafc;
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
            color: #0284c7;
            background: rgba(2, 132, 199, 0.08);
            padding-left: 3.1rem;
        }

        .sub-nav-link.active {
            color: #0369a1;
            font-weight: 700;
            background: rgba(2, 132, 199, 0.12);
            border-left: 3px solid #0284c7;
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

        /* Modern Angkasa Pura Card System */
        .report-card, .card-setting {
            background: white;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #0284c7;
            border-radius: 14px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: box-shadow 0.25s ease;
        }

        .report-card:hover, .card-setting:hover {
            box-shadow: 0 8px 25px -3px rgba(15, 23, 42, 0.08), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
        }

        .report-header, .card-setting-header {
            padding: 1.15rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
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

        .alert-bar.danger {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        /* =========================================================
           MODERN CARD & MODAL SYSTEM (ANGKASA PURA PABX BILLING)
           ========================================================= */
        .setting-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .card-setting-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .card-setting-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-import-blue {
            background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
            color: #ffffff;
            border: none;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-import-blue:hover {
            background: linear-gradient(135deg, #039be5 0%, #0288d1 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 4px 12px rgba(2, 136, 209, 0.35);
        }

        .btn-new-green {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: #ffffff;
            border: none;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 2px 6px rgba(46, 125, 50, 0.25);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-new-green:hover {
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.35);
        }

        .card-setting-body {
            padding: 1.5rem;
        }

        /* Toolbar Styling */
        .table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.75rem;
            background: #f8fafc;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .dt-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .btn-dt {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.775rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-dt:hover {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            transform: translateY(-1px);
        }

        .dt-search {
            font-size: 0.8rem;
            color: #475569;
            display: flex;
            align-items: center;
        }

        .dt-search-box {
            border: 1.5px solid #cbd5e1;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 0.8rem;
            outline: none;
            margin-left: 6px;
            height: 32px;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .dt-search-box:focus {
            border-color: #0288d1;
            box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15);
        }

        /* Modern Table */
        .setting-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.825rem;
            text-align: left;
        }

        .setting-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            padding: 11px 14px;
            border-bottom: 2px solid #e2e8f0;
            border-top: 1px solid #f1f5f9;
            cursor: pointer;
            user-select: none;
            font-size: 0.785rem;
            letter-spacing: 0.02em;
        }

        .setting-table th .sort-icon {
            color: #94a3b8;
            font-size: 0.75rem;
            margin-left: 4px;
        }

        .setting-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        .setting-table tbody tr {
            transition: background 0.15s;
        }

        .setting-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Action Buttons */
        .action-btn-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action-wrench {
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            color: #0288d1;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-action-wrench:hover {
            background: #0288d1;
            color: #ffffff;
            border-color: #0288d1;
            transform: scale(1.1);
            box-shadow: 0 2px 6px rgba(2, 136, 209, 0.3);
        }

        .btn-action-trash {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #ef4444;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-action-trash:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            transform: scale(1.1);
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
        }

        /* Table Footer */
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.25rem;
            padding-top: 0.75rem;
            font-size: 0.8rem;
            color: #64748b;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .table-pagination {
            display: flex;
            gap: 4px;
        }

        .page-btn {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 5px 12px;
            font-size: 0.8rem;
            cursor: pointer;
            color: #334155;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .page-btn:hover:not(.disabled) {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .page-btn.active {
            background: linear-gradient(135deg, #0288d1, #0277bd);
            color: #ffffff;
            border-color: #0288d1;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
        }

        .page-btn.disabled {
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* =========================================================
           MODAL DESIGN SYSTEM (POPUP CARDS)
           ========================================================= */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 1200;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            animation: modalBackdropFadeIn 0.2s ease-out;
        }

        @keyframes modalBackdropFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-card {
            background: #ffffff;
            border-radius: 18px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(226, 232, 240, 0.9);
            overflow: hidden;
            animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
        }

        @keyframes modalPopIn {
            0% {
                opacity: 0;
                transform: scale(0.92) translateY(12px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-card-header {
            padding: 1.15rem 1.5rem;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-card-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.05rem;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .modal-header-icon-badge {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            color: #0288d1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 2px 6px rgba(2, 136, 209, 0.15);
            flex-shrink: 0;
        }

        .modal-header-icon-badge.create-badge {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #16a34a;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.15);
        }

        .modal-header-icon-badge.edit-badge {
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            color: #0288d1;
            box-shadow: 0 2px 6px rgba(2, 136, 209, 0.15);
        }

        .modal-header-icon-badge.import-badge {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.15);
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 1.25rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .modal-close:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #ef4444;
            transform: rotate(90deg) scale(1.05);
        }

        .modal-card-body {
            padding: 1.5rem 1.75rem;
            background: #ffffff;
        }

        .form-group-modal {
            margin-bottom: 1.25rem;
        }

        .form-group-modal:last-child {
            margin-bottom: 0;
        }

        .form-group-modal label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        .form-group-modal label i {
            color: #0288d1;
            font-size: 0.8rem;
            width: 14px;
        }

        .required-star {
            color: #ef4444;
            font-weight: 700;
            margin-left: 2px;
        }

        .form-control-modal {
            width: 100%;
            height: 42px;
            padding: 8px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.875rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-control-modal:hover {
            border-color: #94a3b8;
            background: #ffffff;
        }

        .form-control-modal:focus {
            border-color: #0288d1;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 136, 209, 0.15);
        }

        .modal-card-footer {
            padding: 1.15rem 1.75rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 18px;
        }

        .btn-modal-cancel {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-modal-cancel:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .btn-modal-save {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            border: none;
            padding: 8px 22px;
            border-radius: 10px;
            font-size: 0.825rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 10px rgba(22, 163, 74, 0.28);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-modal-save:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.38);
        }

        .btn-modal-save:active {
            transform: translateY(0);
        }

        /* Upload Dropzone */
        .upload-dropzone {
            border: 2px dashed #93c5fd;
            border-radius: 12px;
            padding: 2rem 1.5rem;
            text-align: center;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .upload-dropzone:hover {
            border-color: #0288d1;
            background: #f0f9ff;
        }

        .upload-dropzone-icon {
            font-size: 2.5rem;
            color: #0288d1;
            margin-bottom: 0.5rem;
        }

        .upload-dropzone-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1e293b;
        }

        .upload-dropzone-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .upload-file-input {
            margin-top: 1rem;
            font-size: 0.8rem;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace !important;
        }

        /* =========================================================
           ANGKASA PURA AVIATION PRELOADER (AIRPLANE ANIMATION)
           ========================================================= */
        .ap-preloader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 50% 40%, rgba(15, 23, 42, 0.95) 0%, rgba(2, 6, 23, 0.98) 100%);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            opacity: 1;
            visibility: visible;
            transition: opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.45s ease;
        }

        .ap-preloader.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .ap-preloader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 440px;
            position: relative;
        }

        /* Flight Sky Stage */
        .flight-scene {
            width: 100%;
            height: 155px;
            background: linear-gradient(180deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.96) 100%);
            border: 1px solid rgba(226, 232, 240, 0.12);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.75), 0 0 45px rgba(211, 47, 47, 0.2);
            margin-bottom: 1.5rem;
        }

        /* Subtle airport radar grid */
        .flight-scene::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 75% 30%, rgba(211, 47, 47, 0.15) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Drifting clouds */
        .flight-clouds {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .flight-cloud {
            position: absolute;
            color: rgba(255, 255, 255, 0.14);
            animation: driftCloud linear infinite;
        }

        .cloud-1 {
            top: 22px;
            font-size: 1.75rem;
            animation-duration: 4.5s;
        }

        .cloud-2 {
            top: 65px;
            font-size: 1.2rem;
            animation-duration: 6.2s;
            animation-delay: -2s;
        }

        .cloud-3 {
            top: 38px;
            font-size: 2.2rem;
            color: rgba(255, 255, 255, 0.08);
            animation-duration: 8s;
            animation-delay: -4s;
        }

        @keyframes driftCloud {
            from {
                transform: translateX(460px);
            }
            to {
                transform: translateX(-100px);
            }
        }

        /* Runway with glowing lights at the bottom */
        .flight-runway {
            position: absolute;
            bottom: 12px;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.12);
            overflow: hidden;
        }

        .flight-runway::after {
            content: '';
            position: absolute;
            top: -2px;
            left: 0;
            width: 200%;
            height: 6px;
            background: repeating-linear-gradient(90deg, #38bdf8 0, #38bdf8 18px, transparent 18px, transparent 36px);
            opacity: 0.7;
            animation: runwayRun 1s linear infinite;
        }

        @keyframes runwayRun {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        /* Airplane Track Across Sky */
        .flight-plane-wrapper {
            position: absolute;
            top: 42px;
            left: 0;
            width: 100%;
            height: 60px;
            pointer-events: none;
        }

        .flight-plane-track {
            position: absolute;
            display: flex;
            align-items: center;
            animation: planeFlightCruise 3s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
        }

        .flight-plane-icon {
            font-size: 2.6rem;
            color: #ffffff;
            filter: drop-shadow(0 4px 12px rgba(211, 47, 47, 0.75)) drop-shadow(0 0 24px rgba(255, 255, 255, 0.45));
            transform: rotate(-8deg);
            display: inline-block;
            animation: planeHoverTilt 1.5s ease-in-out infinite alternate;
        }

        /* Jet Contrail (Vapor Trail) */
        .jet-contrail {
            position: absolute;
            right: 85%;
            top: 50%;
            transform: translateY(-50%);
            width: 190px;
            height: 4px;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.1) 20%, rgba(211, 47, 47, 0.5) 70%, #ff5252 100%);
            border-radius: 999px;
            box-shadow: 0 0 12px rgba(211, 47, 47, 0.9);
            opacity: 0.9;
        }

        /* Jet Engine Exhaust Glow */
        .jet-flame {
            position: absolute;
            right: 86%;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 8px;
            background: radial-gradient(ellipse at center, #ffffff 0%, #ffeb3b 40%, #ff5722 80%, transparent 100%);
            border-radius: 50%;
            animation: flamePulse 0.15s ease-in-out infinite alternate;
        }

        @keyframes planeFlightCruise {
            0% {
                transform: translateX(-50px) translateY(10px);
            }
            50% {
                transform: translateX(180px) translateY(-14px);
            }
            100% {
                transform: translateX(420px) translateY(-4px);
            }
        }

        @keyframes planeHoverTilt {
            0% {
                transform: rotate(-12deg) translateY(-2px);
            }
            100% {
                transform: rotate(-5deg) translateY(4px);
            }
        }

        @keyframes flamePulse {
            0% { transform: translateY(-50%) scale(0.85); opacity: 0.75; }
            100% { transform: translateY(-50%) scale(1.25); opacity: 1; }
        }

        /* Flight Status & Brand */
        .flight-status-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
        }

        .flight-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            margin-bottom: 1.15rem;
        }

        .brand-tag {
            background: linear-gradient(135deg, #0284c7, #0ea5e9);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 900;
            letter-spacing: 0.14em;
            padding: 4px 16px;
            border-radius: 999px;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.45);
            text-transform: uppercase;
        }

        .brand-sub {
            font-size: 0.825rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #cbd5e1;
            margin-top: 5px;
        }

        /* Progress Bar */
        .flight-progress-bar {
            width: 100%;
            height: 5px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            overflow: hidden;
            position: relative;
            margin-bottom: 0.85rem;
        }

        .flight-progress-fill {
            position: absolute;
            height: 100%;
            width: 45%;
            background: linear-gradient(90deg, #0284c7, #38bdf8, #67e8f9);
            border-radius: 999px;
            box-shadow: 0 0 14px rgba(2, 132, 199, 0.9);
            animation: progressGlide 1.3s ease-in-out infinite;
        }

        @keyframes progressGlide {
            0% {
                left: -45%;
                width: 30%;
            }
            50% {
                width: 55%;
            }
            100% {
                left: 100%;
                width: 25%;
            }
        }

        /* Radar Status Text */
        .flight-status-text {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.825rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .radar-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 10px #22c55e;
            display: inline-block;
            animation: radarBlink 1s infinite alternate;
        }

        @keyframes radarBlink {
            0% { opacity: 0.4; transform: scale(0.85); }
            100% { opacity: 1; transform: scale(1.25); }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- ANGKASA PURA AVIATION PRELOADER -->
    <div id="ap-preloader" class="ap-preloader">
        <div class="ap-preloader-content">
            <!-- Flight Sky Stage with Animated Plane -->
            <div class="flight-scene">
                <!-- Drifting clouds -->
                <div class="flight-clouds">
                    <span class="flight-cloud cloud-1"><i class="fa-solid fa-cloud"></i></span>
                    <span class="flight-cloud cloud-2"><i class="fa-solid fa-cloud"></i></span>
                    <span class="flight-cloud cloud-3"><i class="fa-solid fa-cloud"></i></span>
                </div>

                <!-- Animated Flying Airplane with Contrail -->
                <div class="flight-plane-wrapper">
                    <div class="flight-plane-track">
                        <div class="jet-contrail"></div>
                        <div class="jet-flame"></div>
                        <i class="fa-solid fa-plane-departure flight-plane-icon"></i>
                    </div>
                </div>

                <!-- Ground Runway Lights -->
                <div class="flight-runway"></div>
            </div>

            <!-- Brand & Progress -->
            <div class="flight-status-card">
                <div class="flight-brand">
                    <span class="brand-tag">ANGKASA PURA</span>
                    <span class="brand-sub">PABX BILLING SYSTEM</span>
                </div>

                <!-- Progress Bar -->
                <div class="flight-progress-bar">
                    <div class="flight-progress-fill"></div>
                </div>

                <!-- Radar Text Indicator -->
                <div class="flight-status-text">
                    <span class="radar-dot"></span>
                    <span id="apPreloaderStatus">Memuat Sistem & Data PABX...</span>
                </div>
            </div>
        </div>
    </div>

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
            <i class="fa-solid fa-ellipsis-vertical" style="cursor: pointer; opacity: 0.85;"></i>
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
                    @php $isSettingActive = request()->is('settings*'); @endphp
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link {{ $isSettingActive ? 'active-root' : '' }}" onclick="toggleNavCollapse(this, 'sub-setting')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-table-cells-large"></i>
                                <span>Setting</span>
                            </div>
                            <i class="fa-solid {{ $isSettingActive ? 'fa-minus' : 'fa-plus' }} nav-toggle-icon" style="font-size: 0.75rem; color: {{ $isSettingActive ? '#0284c7' : '#94a3b8' }}; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-setting" class="sub-menu" style="display: {{ $isSettingActive ? 'block' : 'none' }};">
                            <li><a href="{{ route('settings.admin') }}" class="sub-nav-link {{ request()->routeIs('settings.admin') ? 'active' : '' }}">Admin Setting</a></li>
                            <li><a href="{{ route('settings.business-phone') }}" class="sub-nav-link {{ request()->routeIs('settings.business-phone') ? 'active' : '' }}">Business Phone</a></li>
                            <li><a href="{{ route('settings.department-group') }}" class="sub-nav-link {{ request()->routeIs('settings.department-group') ? 'active' : '' }}">Department Group</a></li>
                            <li><a href="{{ route('settings.phone-code') }}" class="sub-nav-link {{ request()->routeIs('settings.phone-code') ? 'active' : '' }}">Master Phone Code</a></li>
                            <li><a href="{{ route('settings.rate') }}" class="sub-nav-link {{ request()->routeIs('settings.rate') ? 'active' : '' }}">Master Rate</a></li>
                            <li><a href="{{ route('settings.special-rate') }}" class="sub-nav-link {{ request()->routeIs('settings.special-rate') ? 'active' : '' }}">Master Special Rate</a></li>
                            <li><a href="{{ route('settings.user') }}" class="sub-nav-link {{ request()->routeIs('settings.user') ? 'active' : '' }}">Master User</a></li>
                            <li><a href="{{ route('settings.prefix') }}" class="sub-nav-link {{ request()->routeIs('settings.prefix') ? 'active' : '' }}">Prefix Code</a></li>
                        </ul>
                    </li>

                    <!-- Report -->
                    @php $isReportOpen = request()->is('reports*'); @endphp
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link {{ request()->is('reports*') ? 'active-root' : '' }}" onclick="toggleNavCollapse(this, 'sub-reports')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-table-cells"></i>
                                <span>Report</span>
                            </div>
                            <i class="fa-solid {{ $isReportOpen ? 'fa-minus' : 'fa-plus' }} nav-toggle-icon" style="font-size: 0.75rem; color: {{ $isReportOpen ? '#0284c7' : '#94a3b8' }}; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-reports" class="sub-menu" style="display: {{ $isReportOpen ? 'block' : 'none' }};">
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
                                <a href="{{ route('reports.personal-detail') }}" class="sub-nav-link {{ request()->routeIs('reports.personal-detail') ? 'active' : '' }}">
                                    Personal Detail
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.personal-favorite-area') }}" class="sub-nav-link {{ request()->routeIs('reports.personal-favorite-area') ? 'active' : '' }}">
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
                                <a href="{{ route('reports.phone-id-detail') }}" class="sub-nav-link {{ request()->routeIs('reports.phone-id-detail') ? 'active' : '' }}">
                                    Phone-ID Detail
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.phone-id-summary') }}" class="sub-nav-link {{ request()->routeIs('reports.phone-id-summary') ? 'active' : '' }}">
                                    Phone-ID Summary
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.unit-summary') }}" class="sub-nav-link {{ request()->routeIs('reports.unit-summary') ? 'active' : '' }}">
                                    Unit Summary
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Information -->
                    @php $isInfoActive = request()->is('information*'); @endphp
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link {{ $isInfoActive ? 'active-root' : '' }}" onclick="toggleNavCollapse(this, 'sub-info')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>Information</span>
                            </div>
                            <i class="fa-solid {{ $isInfoActive ? 'fa-minus' : 'fa-plus' }} nav-toggle-icon" style="font-size: 0.75rem; color: {{ $isInfoActive ? '#0284c7' : '#94a3b8' }}; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-info" class="sub-menu" style="display: {{ $isInfoActive ? 'block' : 'none' }};">
                            <li><a href="{{ route('information.call-history') }}" class="sub-nav-link {{ request()->routeIs('information.call-history') ? 'active' : '' }}">Call History</a></li>
                            <li><a href="{{ route('information.recalculating-call') }}" class="sub-nav-link {{ request()->routeIs('information.recalculating-call') ? 'active' : '' }}">Recalculating Call</a></li>
                            <li><a href="{{ route('information.monitoring-port') }}" class="sub-nav-link {{ request()->routeIs('information.monitoring-port') ? 'active' : '' }}">Listening Port</a></li>
                        </ul>
                    </li>

                    <!-- Utility -->
                    @php $isUtilActive = request()->is('utility*'); @endphp
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link {{ $isUtilActive ? 'active-root' : '' }}" onclick="toggleNavCollapse(this, 'sub-util')">
                            <div class="nav-link-left">
                                <i class="fa-solid fa-wrench"></i>
                                <span>Utility</span>
                            </div>
                            <i class="fa-solid {{ $isUtilActive ? 'fa-minus' : 'fa-plus' }} nav-toggle-icon" style="font-size: 0.75rem; color: {{ $isUtilActive ? '#0284c7' : '#94a3b8' }}; transition: all 0.2s;"></i>
                        </a>
                        <ul id="sub-util" class="sub-menu" style="display: {{ $isUtilActive ? 'block' : 'none' }};">
                            <li><a href="{{ route('utility.server-health') }}" class="sub-nav-link {{ request()->routeIs('utility.server-health') ? 'active' : '' }}">Server Health & Diagnostics</a></li>
                            <li><a href="{{ route('utility.backup-maintenance') }}" class="sub-nav-link {{ request()->routeIs('utility.backup-maintenance') ? 'active' : '' }}">Backup & Maintenance</a></li>
                            <li><a href="{{ route('utility.run-service') }}" class="sub-nav-link {{ request()->routeIs('utility.run-service') ? 'active' : '' }}">Run Service (PABX Listener)</a></li>
                            <li><a href="{{ route('utility.level-akses') }}" class="sub-nav-link {{ request()->routeIs('utility.level-akses') ? 'active' : '' }}">Level Akses (Hak Akses)</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <div>&copy; 2019 - 2026 <strong style="color: #0284c7;">PABX Billing</strong></div>
                <div>Develop By: SSW</div>
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

            @if(session('error'))
                <div class="alert-bar danger">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-bar danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <ul style="margin: 0; padding-left: 1.2rem;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // =========================================================
        // AVIATION AIRPLANE PRELOADER CONTROLLER
        // =========================================================
        let preloaderTimeout = null;
        function showPreloader(message) {
            const preloader = document.getElementById('ap-preloader');
            const statusText = document.getElementById('apPreloaderStatus');
            if (statusText && message) {
                statusText.innerText = message;
            }
            if (preloader) {
                preloader.style.display = 'flex';
                preloader.classList.remove('fade-out');
            }
            clearTimeout(preloaderTimeout);
            preloaderTimeout = setTimeout(hidePreloader, 5000);
        }

        function hidePreloader() {
            clearTimeout(preloaderTimeout);
            const preloader = document.getElementById('ap-preloader');
            if (preloader) {
                preloader.classList.add('fade-out');
                setTimeout(() => {
                    if (preloader.classList.contains('fade-out')) {
                        preloader.style.display = 'none';
                    }
                }, 450);
            }
        }

        // Hide preloader as soon as DOM is ready or after brief moment
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(hidePreloader, 250);
        });

        // Hide preloader when window finishes loading
        window.addEventListener('load', function() {
            hidePreloader();
        });

        // Safety fallback: guaranteed hide preloader after max 650ms
        setTimeout(hidePreloader, 650);

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
                    icon.style.color = '#0284c7';
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
        // Global CSV Export Utility for All Reports
        function exportTableToCSV(tableId, filename) {
            const table = document.getElementById(tableId) || document.querySelector('.ap-table');
            if (!table) {
                alert('Tabel tidak ditemukan untuk diekspor.');
                return;
            }
            let csv = [];
            const rows = table.querySelectorAll('tr');
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length; j++) {
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
                    text = text.replace(/"/g, '""');
                    row.push('"' + text + '"');
                }
                if (row.length > 0) {
                    csv.push(row.join(','));
                }
            }
            const csvContent = '\uFEFF' + csv.join('\r\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', filename || 'laporan_pabx.csv');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Attach preloader to navigation links for seamless airplane flight transitions
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript"]):not([download])');
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (e.metaKey || e.ctrlKey || e.shiftKey) return;
                    const href = link.getAttribute('href');
                    if (href && !href.startsWith('#') && !href.startsWith('javascript:') && href !== window.location.href && href !== window.location.pathname) {
                        showPreloader('Mempersiapkan Navigasi Halaman...');
                    }
                });
            });

            // Attach preloader to forms on submit
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    if (form.checkValidity && !form.checkValidity()) return;
                    const isDelete = form.querySelector('input[name="_method"][value="DELETE"]');
                    const method = (form.getAttribute('method') || 'GET').toUpperCase();
                    if (isDelete) {
                        showPreloader('Menghapus Data dari Server PABX...');
                    } else if (method === 'GET') {
                        showPreloader('Memproses & Mengambil Laporan PABX...');
                    } else {
                        showPreloader('Menyimpan Data ke Server PABX...');
                    }
                });
            });

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
