<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SmartPulse</title>
    <meta name="description" content="SmartPulse Classroom OS - Teaching Feedback & Attendance Management System">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 14px; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #F0F4F8; color: #1E293B; min-height: 100vh; }

        /* ─── Sidebar ───────────────────────────────────────────────────────── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #E2E8F0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            overflow-y: auto;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.45);
            z-index: 99;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 8px 0 30px rgba(0,0,0,0.15); }
            .sidebar-overlay.open { display: block; }
            .main-content { margin-left: 0 !important; }
        }

        /* Sidebar Brand */
        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(37,99,235,0.30);
        }
        .brand-logo svg { color: #fff; }
        .brand-text .brand-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #0F172A;
            letter-spacing: -0.01em;
        }
        .brand-text .brand-tag {
            font-size: 0.65rem;
            font-weight: 600;
            color: #3B82F6;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Sidebar User */
        .sidebar-user {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #F1F5F9;
            background: #FAFBFF;
        }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #1D4ED8;
            flex-shrink: 0;
            border: 2px solid #DBEAFE;
        }
        .user-info .user-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #0F172A;
        }
        .user-info .user-role {
            font-size: 0.68rem;
            color: #64748B;
            text-transform: capitalize;
        }

        /* Sidebar Nav */
        .sidebar-nav { padding: 12px 0; flex: 1; }
        .nav-section-label {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #94A3B8;
            padding: 10px 20px 4px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 16px;
            margin: 1px 10px;
            border-radius: 9px;
            color: #64748B;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.18s;
        }
        .nav-link:hover { background: #EFF6FF; color: #2563EB; }
        .nav-link.active {
            background: #EFF6FF;
            color: #1D4ED8;
            font-weight: 600;
        }
        .nav-link.active .nav-dot {
            width: 6px; height: 6px;
            background: #3B82F6;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .nav-link svg { flex-shrink: 0; width: 16px; height: 16px; }

        /* Sidebar Logout */
        .sidebar-footer {
            padding: 14px 10px;
            border-top: 1px solid #F1F5F9;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 16px;
            border-radius: 9px;
            color: #64748B;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.18s;
        }
        .logout-btn:hover { background: #FEF2F2; color: #DC2626; }

        /* ─── Main Content ─────────────────────────────────────────────────── */
        .main-content { margin-left: 240px; min-height: 100vh; display: flex; flex-direction: column; }

        /* Top Header */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 28px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: #64748B;
        }
        .breadcrumb .current { color: #0F172A; font-weight: 600; }
        .breadcrumb-sep { color: #CBD5E1; }

        /* Hamburger (mobile) */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            padding: 6px;
            border-radius: 8px;
            cursor: pointer;
            color: #475569;
        }
        .hamburger-btn:hover { background: #F1F5F9; }
        @media (max-width: 1023px) { .hamburger-btn { display: flex; } }

        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 9px;
            padding: 7px 12px;
            font-size: 0.8rem;
            color: #94A3B8;
            min-width: 180px;
        }
        .topbar-user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #1D4ED8;
            cursor: pointer;
        }
        .topbar-user-name { font-size: 0.82rem; font-weight: 600; color: #0F172A; }

        /* Page Header */
        .page-header {
            padding: 22px 28px 0;
        }
        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.02em;
        }
        .page-header p {
            font-size: 0.82rem;
            color: #64748B;
            margin-top: 3px;
        }

        /* Page Content */
        .page-content { padding: 20px 28px 32px; flex: 1; }

        /* Flash Alerts */
        .alert { padding: 12px 16px; border-radius: 11px; margin-bottom: 18px; font-size: 0.85rem; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
        .alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
        .alert-info    { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
        .alert-warning { background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; }

        /* ─── Cards & Tables ─────────────────────────────────────────────────── */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h3 { font-size: 0.9rem; font-weight: 700; color: #0F172A; }
        .card-body { padding: 20px; }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.07); }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }
        .stat-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #64748B; }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: #0F172A; margin-top: 4px; letter-spacing: -0.02em; }

        /* Tables */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            padding: 11px 16px;
            text-align: left;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94A3B8;
            border-bottom: 1px solid #F1F5F9;
            background: #FAFBFC;
        }
        .data-table td {
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #334155;
            border-bottom: 1px solid #F8FAFC;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #FAFBFF; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-green   { background: #DCFCE7; color: #15803D; }
        .badge-red     { background: #FEE2E2; color: #B91C1C; }
        .badge-yellow  { background: #FEF9C3; color: #A16207; }
        .badge-blue    { background: #DBEAFE; color: #1D4ED8; }
        .badge-gray    { background: #F1F5F9; color: #475569; }
        .badge-purple  { background: #EDE9FE; color: #6D28D9; }
        .badge-orange  { background: #FFEDD5; color: #C2410C; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.18s;
            box-shadow: 0 2px 10px rgba(37,99,235,0.25);
            font-family: 'Inter', sans-serif;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(37,99,235,0.35); }
        .btn-secondary {
            background: #fff;
            color: #374151;
            border: 1.5px solid #E2E8F0;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.18s;
            font-family: 'Inter', sans-serif;
        }
        .btn-secondary:hover { background: #F8FAFC; border-color: #CBD5E1; }
        .btn-danger {
            background: #DC2626;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.18s;
            font-family: 'Inter', sans-serif;
        }
        .btn-danger:hover { background: #B91C1C; }
        .btn-success {
            background: #16A34A;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.18s;
            font-family: 'Inter', sans-serif;
        }
        .btn-success:hover { background: #15803D; }
        .btn-warning {
            background: #D97706;
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.18s;
            font-family: 'Inter', sans-serif;
        }
        .btn-warning:hover { background: #B45309; }
        .btn-sm { padding: 5px 12px !important; font-size: 0.77rem !important; }

        /* Forms */
        .form-label  { display: block; font-size: 0.82rem; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-input  {
            width: 100%;
            border: 1.5px solid #E2E8F0;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 0.85rem;
            color: #0F172A;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.18s;
            background: #FAFBFC;
        }
        .form-input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.10); background: #fff; }
        .form-select {
            width: 100%;
            border: 1.5px solid #E2E8F0;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 0.85rem;
            color: #0F172A;
            font-family: 'Inter', sans-serif;
            outline: none;
            background: #FAFBFC;
            transition: all 0.18s;
        }
        .form-select:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.10); background: #fff; }
        .form-textarea {
            width: 100%;
            border: 1.5px solid #E2E8F0;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 0.85rem;
            color: #0F172A;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.18s;
            background: #FAFBFC;
            resize: vertical;
        }
        .form-textarea:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,0.10); background: #fff; }

        /* Star ratings */
        .star { color: #F59E0B; }
        .star-empty { color: #E2E8F0; }

        /* Pagination */
        .pagination { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            color: #475569;
            border: 1px solid #E2E8F0;
            background: #fff;
        }
        .pagination a:hover { background: #EFF6FF; border-color: #BFDBFE; color: #1D4ED8; }
        .pagination .active span { background: #3B82F6; color: #fff; border-color: #3B82F6; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94A3B8;
        }
        .empty-state svg { margin: 0 auto 12px; opacity: 0.4; }
        .empty-state p { font-size: 0.85rem; }

        /* Driver.js Custom Theme */
        .driver-popover {
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15) !important;
            padding: 24px !important;
            border: 1px solid #E2E8F0 !important;
            background: #ffffff !important;
            max-width: 340px !important;
        }
        .driver-popover-title {
            font-family: 'Inter', sans-serif !important;
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: #0F172A !important;
            margin-bottom: 8px !important;
        }
        .driver-popover-description {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.88rem !important;
            color: #475569 !important;
            line-height: 1.5 !important;
        }
        .driver-popover-footer {
            margin-top: 20px !important;
        }
        .driver-popover-footer button {
            font-family: 'Inter', sans-serif !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 0.8rem !important;
            padding: 8px 14px !important;
            text-shadow: none !important;
        }
        .driver-popover-next-btn {
            background: linear-gradient(135deg, #3B82F6, #2563EB) !important;
            color: white !important;
            border: none !important;
            box-shadow: 0 2px 8px rgba(37,99,235,0.2) !important;
        }
        .driver-popover-prev-btn, .driver-popover-close-btn {
            background: #F8FAFC !important;
            color: #475569 !important;
            border: 1px solid #E2E8F0 !important;
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-logo">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div class="brand-text">
            <div class="brand-name">SmartPulse</div>
            <div class="brand-tag">Classroom OS</div>
        </div>
    </div>

    <!-- Current User -->
    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->role }}</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        @yield('sidebar-nav')
    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger-btn" onclick="openSidebar()" aria-label="Open menu">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="breadcrumb">
                <span>{{ ucfirst(auth()->user()->role) }}</span>
                <span class="breadcrumb-sep">/</span>
                <span class="current">@yield('title', 'Dashboard')</span>
            </div>
        </div>
        <div class="topbar-right">
            <div class="search-box" style="display:none;"> {{-- hidden on smaller screens --}}
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search…
            </div>
            <button id="start-tour-btn" class="btn-secondary btn-sm" style="border-radius: 20px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tour
            </button>
            <div class="topbar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span class="topbar-user-name" style="display:none;">{{ auth()->user()->name }}</span>
        </div>
    </header>

    <!-- Page Header -->
    @if(isset($header))
    <div class="page-header">
        <h1>{{ $header }}</h1>
        @isset($subheader)<p>{{ $subheader }}</p>@endisset
    </div>
    @endif

    <!-- Page Body -->
    <div class="page-content">
        {{-- Flash messages --}}
        @if(session('success'))
        <div class="alert alert-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-error" style="flex-direction:column; align-items:flex-start; gap:6px;">
            @foreach($errors->all() as $error)
            <div style="display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ $error }}
            </div>
            @endforeach
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    function openSidebar()  {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    }

    // Delete confirmations
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm(this.dataset.confirm)) e.preventDefault();
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
@stack('scripts')
</body>
</html>