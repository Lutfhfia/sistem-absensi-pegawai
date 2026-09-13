<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Absensi Pegawai')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            color: #1f2937;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* SIDEBAR */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;

            width: 260px;

            background: #ffffff;

            border-right: 1px solid #e5e7eb;

            z-index: 1050;

            display: flex;
            flex-direction: column;

            transition:
                width 0.25s ease,
                transform 0.25s ease;

            overflow: hidden;
        }

        /* SIDEBAR COLLAPSED */

        .sidebar.collapsed {
            width: 78px;
        }

        /* BRAND */

        .sidebar-brand {
            height: 76px;

            padding: 0 18px;

            display: flex;
            align-items: center;

            gap: 11px;

            border-bottom: 1px solid #edf0f4;

            flex-shrink: 0;
        }

        .brand-icon {
            width: 40px;
            height: 40px;

            min-width: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: #0d6efd;
            color: #ffffff;

            font-size: 20px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;

            white-space: nowrap;

            line-height: 1.1;

            transition:
                opacity 0.15s ease,
                width 0.25s ease;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 800;

            color: #1f2937;
        }

        .brand-subtitle {
            margin-top: 4px;

            font-size: 9px;

            color: #9ca3af;

            font-weight: 500;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
        }

        /* SIDEBAR TOGGLE */

        .sidebar-toggle {
            position: absolute;

            top: 22px;
            right: 13px;

            width: 32px;
            height: 32px;

            border: 0;

            border-radius: 8px;

            background: #f3f6fa;

            color: #6b7280;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: #eaf2ff;
            color: #0d6efd;
        }

        /* SIDEBAR CONTENT */

        .sidebar-content {
            flex: 1;

            padding: 20px 12px;

            overflow-y: auto;
            overflow-x: hidden;
        }

        /* MENU LABEL */

        .menu-label {
            padding: 0 12px;

            margin-bottom: 8px;

            color: #a0a7b1;

            font-size: 10px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.7px;

            white-space: nowrap;

            transition: opacity 0.15s ease;
        }

        .sidebar.collapsed .menu-label {
            opacity: 0;
        }

        /* MENU */

        .sidebar-menu {
            list-style: none;

            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-link {
            width: 100%;

            min-height: 45px;

            padding: 10px 12px;

            display: flex;
            align-items: center;

            gap: 12px;

            border-radius: 10px;

            color: #6b7280;

            font-size: 13px;

            font-weight: 600;

            white-space: nowrap;

            transition: all 0.2s ease;
        }

        .sidebar-link i {
            width: 22px;
            min-width: 22px;

            text-align: center;

            font-size: 17px;
        }

        .sidebar-link:hover {
            color: #0d6efd;

            background: #f1f6ff;
        }

        .sidebar-link.active {
            color: #0d6efd;

            background: #eef5ff;
        }

        .sidebar-link-text {
            transition:
                opacity 0.15s ease,
                width 0.25s ease;
        }

        .sidebar.collapsed .sidebar-link {
            justify-content: center;

            padding-left: 0;
            padding-right: 0;
        }

        .sidebar.collapsed .sidebar-link-text {
            opacity: 0;
            width: 0;
        }

        /* SIDEBAR FOOTER */

        .sidebar-footer {
            padding: 12px;

            border-top: 1px solid #edf0f4;

            flex-shrink: 0;
        }

        /* USER */

        .sidebar-user {
            display: flex;
            align-items: center;

            gap: 10px;

            padding: 9px 10px;

            border-radius: 11px;

            background: #f8fafc;

            margin-bottom: 8px;

            overflow: hidden;
        }

        .sidebar-user-avatar {
            width: 34px;
            height: 34px;

            min-width: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e8f1ff;

            color: #0d6efd;

            font-size: 15px;
        }

        .sidebar-user-info {
            display: flex;
            flex-direction: column;

            line-height: 1.2;

            min-width: 0;

            white-space: nowrap;

            transition:
                opacity 0.15s ease,
                width 0.25s ease;
        }

        .sidebar-user-name {
            font-size: 12px;

            font-weight: 700;

            color: #374151;

            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            margin-top: 3px;

            font-size: 9px;

            color: #9ca3af;
        }

        .sidebar.collapsed .sidebar-user {
            justify-content: center;

            padding-left: 0;
            padding-right: 0;
        }

        .sidebar.collapsed .sidebar-user-info {
            opacity: 0;
            width: 0;
        }

        /* LOGOUT */

        .logout-button {
            width: 100%;

            border: 0;

            background: transparent;

            min-height: 42px;

            padding: 10px 12px;

            display: flex;
            align-items: center;

            gap: 12px;

            border-radius: 10px;

            color: #dc3545;

            font-size: 13px;

            font-weight: 600;

            cursor: pointer;

            white-space: nowrap;

            transition: all 0.2s ease;
        }

        .logout-button:hover {
            background: #fff0f0;
        }

        .logout-button i {
            width: 22px;
            min-width: 22px;

            text-align: center;

            font-size: 17px;
        }

        .logout-text {
            transition:
                opacity 0.15s ease,
                width 0.25s ease;
        }

        .sidebar.collapsed .logout-button {
            justify-content: center;

            padding-left: 0;
            padding-right: 0;
        }

        .sidebar.collapsed .logout-text {
            opacity: 0;
            width: 0;
        }

        /* MAIN AREA */

        .main-area {
            min-height: 100vh;

            margin-left: 260px;

            transition: margin-left 0.25s ease;
        }

        .sidebar.collapsed ~ .main-area {
            margin-left: 78px;
        }

        /* TOPBAR */

        .topbar {
            height: 76px;

            background: #ffffff;

            border-bottom: 1px solid #e5e7eb;

            display: flex;
            align-items: center;

            padding: 0 30px;

            position: sticky;

            top: 0;

            z-index: 1000;
        }

        .mobile-menu-button {
            width: 40px;
            height: 40px;

            border: 0;

            border-radius: 9px;

            background: #f3f6fa;

            color: #4b5563;

            display: none;

            align-items: center;
            justify-content: center;

            font-size: 19px;

            cursor: pointer;
        }

        .mobile-menu-button:hover {
            background: #eaf2ff;
            color: #0d6efd;
        }

        .topbar-title {
            font-size: 15px;

            font-weight: 700;

            color: #374151;
        }

        .topbar-spacer {
            flex: 1;
        }

        .topbar-user {
            display: flex;
            align-items: center;

            gap: 9px;
        }

        .topbar-user-avatar {
            width: 36px;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e8f1ff;

            color: #0d6efd;
        }

        .topbar-user-info {
            display: flex;
            flex-direction: column;

            line-height: 1.2;
        }

        .topbar-user-name {
            font-size: 12px;

            font-weight: 700;

            color: #374151;
        }

        .topbar-user-role {
            margin-top: 3px;

            font-size: 9px;

            color: #9ca3af;
        }

        /* MOBILE OVERLAY */

        .sidebar-overlay {
            position: fixed;

            inset: 0;

            background: rgba(15, 23, 42, 0.45);

            z-index: 1040;

            opacity: 0;

            visibility: hidden;

            transition: all 0.25s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;

            visibility: visible;
        }

        /* PAGE */

        .page-wrapper {
            min-height: calc(100vh - 76px);

            padding: 30px 0 50px;
        }

        /* PAGE HEADER */

        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            margin: 0;

            font-size: 28px;

            font-weight: 800;

            color: #1f2937;
        }

        .page-description {
            margin-top: 7px;

            margin-bottom: 0;

            font-size: 14px;

            color: #6b7280;
        }

        /* CARD */

        .app-card {
            border: 1px solid #e8ecf2;

            border-radius: 16px;

            background: #ffffff;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.04);

            overflow: hidden;
        }

        .app-card-header {
            padding: 20px 22px;

            border-bottom: 1px solid #edf0f4;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .app-card-title {
            margin: 0;

            font-size: 16px;

            font-weight: 800;

            color: #1f2937;
        }

        .app-card-subtitle {
            margin: 5px 0 0;

            font-size: 12px;

            color: #9ca3af;
        }

        .app-card-body {
            padding: 22px;
        }

        /* STAT CARD */

        .stat-card {
            height: 100%;

            padding: 20px;

            background: #ffffff;

            border: 1px solid #e8ecf2;

            border-radius: 16px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.04);
        }

        .stat-card-inner {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;
        }

        .stat-label {
            font-size: 12px;

            color: #9ca3af;

            margin-bottom: 7px;
        }

        .stat-value {
            font-size: 28px;

            font-weight: 800;

            color: #1f2937;

            line-height: 1;
        }

        .stat-icon {
            width: 48px;
            height: 48px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: #eef5ff;

            color: #0d6efd;

            font-size: 21px;
        }

        /* BUTTON */

        .btn {
            border-radius: 10px;

            font-size: 13px;

            font-weight: 600;

            padding: 9px 15px;
        }

        .btn-primary {
            background: #0d6efd;

            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background: #0b5ed7;

            border-color: #0b5ed7;
        }

        /* TABLE */

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;

            border-bottom: 1px solid #e5e7eb;

            color: #6b7280;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.4px;

            padding: 13px 15px;

            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px 15px;

            border-bottom: 1px solid #f0f2f5;

            color: #374151;

            font-size: 13px;

            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: #fafcff;
        }

        /* BADGE */

        .status-badge {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 700;
        }

        .status-success {
            background: #eaf8ef;

            color: #198754;
        }

        .status-warning {
            background: #fff7df;

            color: #a36a00;
        }

        .status-danger {
            background: #fff0f0;

            color: #dc3545;
        }

        .status-info {
            background: #eaf5ff;

            color: #0d6efd;
        }

        .status-secondary {
            background: #f1f3f5;

            color: #6c757d;
        }

        /* FORM */

        .form-label {
            color: #374151;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 44px;

            border-radius: 10px;

            border: 1px solid #dce2e9;

            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;

            box-shadow:
                0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        /* CAMERA */

        .camera-container {
            position: relative;

            width: 100%;

            overflow: hidden;

            background: #111827;

            border-radius: 14px;
        }

        .camera-video {
            display: block;

            width: 100%;

            min-height: 300px;

            max-height: 500px;

            object-fit: cover;

            background: #111827;
        }

        .camera-overlay {
            position: absolute;

            inset: 0;

            pointer-events: none;
        }

        .camera-corner {
            position: absolute;

            width: 35px;
            height: 35px;

            border-color: #ffffff;

            border-style: solid;

            opacity: 0.8;
        }

        .camera-corner.top-left {
            top: 20px;

            left: 20px;

            border-width: 3px 0 0 3px;
        }

        .camera-corner.top-right {
            top: 20px;

            right: 20px;

            border-width: 3px 3px 0 0;
        }

        .camera-corner.bottom-left {
            bottom: 20px;

            left: 20px;

            border-width: 0 0 3px 3px;
        }

        .camera-corner.bottom-right {
            bottom: 20px;

            right: 20px;

            border-width: 0 3px 3px 0;
        }

        /* GPS */

        .gps-status {
            display: flex;

            align-items: center;

            gap: 10px;

            padding: 12px 14px;

            border-radius: 11px;

            background: #f8fafc;

            border: 1px solid #e5e7eb;
        }

        .gps-status-icon {
            width: 34px;
            height: 34px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: #eef5ff;

            color: #0d6efd;

            flex-shrink: 0;
        }

        .gps-status-title {
            font-size: 12px;

            font-weight: 700;

            color: #374151;
        }

        .gps-status-text {
            margin-top: 3px;

            font-size: 11px;

            color: #9ca3af;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 50px 20px;

            text-align: center;

            color: #9ca3af;
        }

        .empty-state-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 15px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 15px;

            background: #f1f5f9;

            font-size: 25px;
        }

        .empty-state-title {
            color: #6b7280;

            font-size: 14px;

            font-weight: 700;
        }

        .empty-state-text {
            margin-top: 5px;

            font-size: 12px;
        }

        /* FOOTER */

        .app-footer {
            padding-top: 25px;

            text-align: center;

            color: #9ca3af;

            font-size: 11px;
        }

        /* RESPONSIVE */

        @media (max-width: 991.98px) {

            .sidebar {
                width: 260px;

                transform: translateX(-100%);

                box-shadow:
                    10px 0 30px rgba(0, 0, 0, 0.08);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 260px;
            }

            .sidebar.collapsed .brand-text,
            .sidebar.collapsed .menu-label,
            .sidebar.collapsed .sidebar-link-text,
            .sidebar.collapsed .sidebar-user-info,
            .sidebar.collapsed .logout-text {
                opacity: 1;

                width: auto;
            }

            .sidebar.collapsed .sidebar-link,
            .sidebar.collapsed .sidebar-user,
            .sidebar.collapsed .logout-button {
                justify-content: flex-start;

                padding-left: 12px;
                padding-right: 12px;
            }

            .sidebar.collapsed .brand-text {
                width: auto;
            }

            .sidebar-toggle {
                display: none;
            }

            .main-area,
            .sidebar.collapsed ~ .main-area {
                margin-left: 0;
            }

            .mobile-menu-button {
                display: flex;
            }

            .topbar {
                height: 68px;

                padding: 0 18px;
            }

            .topbar-title {
                margin-left: 12px;
            }

            .topbar-user-info {
                display: none;
            }

            .page-wrapper {
                min-height: calc(100vh - 68px);

                padding-top: 22px;
            }
        }

        @media (max-width: 767.98px) {

            .page-wrapper {
                padding-top: 20px;
            }

            .page-title {
                font-size: 23px;
            }

            .page-description {
                font-size: 13px;
            }

            .app-card-header {
                padding: 16px;
            }

            .app-card-body {
                padding: 16px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 24px;
            }

            .camera-video {
                min-height: 260px;
            }

            .topbar {
                padding-left: 14px;
                padding-right: 14px;
            }

            .topbar-title {
                font-size: 14px;
            }

            .app-footer {
                padding-left: 15px;
                padding-right: 15px;
            }
        }

    </style>

    @stack('head')

</head>

<body>

@auth

    <!-- SIDEBAR -->

    <aside
        class="sidebar"
        id="sidebar"
    >

        <!-- BRAND -->

        <div class="sidebar-brand">

            <div class="brand-icon">
                <i class="bi bi-fingerprint"></i>
            </div>

            <div class="brand-text">

                <div class="brand-title">
                    Absensi Pegawai
                </div>

                <div class="brand-subtitle">
                    Sistem Kehadiran
                </div>

            </div>

            <button
                type="button"
                class="sidebar-toggle"
                id="sidebarToggle"
                aria-label="Tutup sidebar"
            >
                <i class="bi bi-list"></i>
            </button>

        </div>

        <!-- SIDEBAR CONTENT -->

        <div class="sidebar-content">

            @if (auth()->user()->role === 'super_admin')

                <div class="menu-label">
                    Menu Admin
                </div>

                <ul class="sidebar-menu">

                    <li>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        >
                            <i class="bi bi-speedometer2"></i>

                            <span class="sidebar-link-text">
                                Dashboard
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                        >
                            <i class="bi bi-people-fill"></i>

                            <span class="sidebar-link-text">
                                Manajemen User
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('admin.locations.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}"
                        >
                            <i class="bi bi-geo-alt-fill"></i>

                            <span class="sidebar-link-text">
                                Lokasi Absensi
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('rekapan') }}"
                            class="sidebar-link {{ request()->routeIs('rekapan') ? 'active' : '' }}"
                        >
                            <i class="bi bi-bar-chart-line-fill"></i>

                            <span class="sidebar-link-text">
                                Rekapan
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('admin.izin.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.izin.*') ? 'active' : '' }}"
                        >
                            <i class="bi bi-file-earmark-text-fill"></i>

                            <span class="sidebar-link-text">
                                Pengajuan Izin
                            </span>
                        </a>
                    </li>

                </ul>

            @else

                <div class="menu-label">
                    Menu Pegawai
                </div>

                <ul class="sidebar-menu">

                    <li>

                        <a
                            href="{{ route('absen') }}"
                            class="sidebar-link {{ request()->routeIs('absen') ? 'active' : '' }}"
                        >

                            <i class="bi bi-camera-fill"></i>

                            <span class="sidebar-link-text">
                                Absensi
                            </span>

                        </a>

                    </li>

                    <li>

                        <a
                            href="{{ route('izin.index') }}"
                            class="sidebar-link {{ request()->routeIs('izin.*') ? 'active' : '' }}"
                        >

                            <i class="bi bi-file-earmark-text-fill"></i>

                            <span class="sidebar-link-text">
                                Izin / Sakit
                            </span>

                        </a>

                    </li>

                </ul>

            @endif

        </div>

        <!-- SIDEBAR FOOTER -->

        <div class="sidebar-footer">

            <div class="sidebar-user">

                <div class="sidebar-user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div class="sidebar-user-info">

                    <div class="sidebar-user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="sidebar-user-role">

                        {{ auth()->user()->role === 'super_admin'
                            ? 'Administrator'
                            : 'Pegawai' }}

                    </div>

                </div>

            </div>

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >

                    <i class="bi bi-box-arrow-right"></i>

                    <span class="logout-text">
                        Keluar
                    </span>

                </button>

            </form>

        </div>

    </aside>

    <!-- MOBILE OVERLAY -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>

@endauth

<!-- MAIN AREA -->

<div class="main-area">

    @auth

        <!-- TOPBAR -->

        <header class="topbar">

            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Buka menu"
            >

                <i class="bi bi-list"></i>

            </button>

            <div class="topbar-title">

                @yield(
                    'topbar-title',
                    'Sistem Absensi'
                )

            </div>

            <div class="topbar-spacer"></div>

            <div class="topbar-user">

                <div class="topbar-user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div class="topbar-user-info">

                    <div class="topbar-user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="topbar-user-role">

                        {{ auth()->user()->role === 'super_admin'
                            ? 'Administrator'
                            : 'Pegawai' }}

                    </div>

                </div>

            </div>

        </header>

    @endauth

    <!-- MAIN CONTENT -->

    <main class="page-wrapper">

        <div class="container">

            @if (session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >

                    <i class="bi bi-check-circle-fill me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            @endif

            @if (session('error'))

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            @endif

            @if ($errors->any())

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >

                    <div class="fw-bold mb-1">
                        Terjadi kesalahan:
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            @endif

            @yield('content')

            <!-- FOOTER -->

            <div class="app-footer">

                &copy; {{ date('Y') }}

                Absensi Pegawai.

                Sistem Informasi Kehadiran Pegawai.

            </div>

        </div>

    </main>

</div>

<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const sidebar =
            document.getElementById('sidebar');

        const sidebarToggle =
            document.getElementById('sidebarToggle');

        const mobileMenuButton =
            document.getElementById('mobileMenuButton');

        const sidebarOverlay =
            document.getElementById('sidebarOverlay');

        if (!sidebar) {
            return;
        }

        // Desktop sidebar

        if (sidebarToggle) {

            sidebarToggle.addEventListener(
                'click',
                function () {

                    sidebar.classList.toggle(
                        'collapsed'
                    );

                }
            );

        }

        // Mobile sidebar

        if (mobileMenuButton) {

            mobileMenuButton.addEventListener(
                'click',
                function () {

                    sidebar.classList.add(
                        'mobile-open'
                    );

                    if (sidebarOverlay) {

                        sidebarOverlay.classList.add(
                            'show'
                        );

                    }

                }
            );

        }

        // Close mobile sidebar

        if (sidebarOverlay) {

            sidebarOverlay.addEventListener(
                'click',
                function () {

                    sidebar.classList.remove(
                        'mobile-open'
                    );

                    sidebarOverlay.classList.remove(
                        'show'
                    );

                }
            );

        }

        // Close sidebar after clicking menu on mobile

        const sidebarLinks =
            document.querySelectorAll(
                '.sidebar-link'
            );

        sidebarLinks.forEach(function (link) {

            link.addEventListener(
                'click',
                function () {

                    if (window.innerWidth <= 991) {

                        sidebar.classList.remove(
                            'mobile-open'
                        );

                        if (sidebarOverlay) {

                            sidebarOverlay.classList.remove(
                                'show'
                            );

                        }

                    }

                }
            );

        });

        // Reset mobile state when screen becomes desktop

        window.addEventListener(
            'resize',
            function () {

                if (window.innerWidth > 991) {

                    sidebar.classList.remove(
                        'mobile-open'
                    );

                    if (sidebarOverlay) {

                        sidebarOverlay.classList.remove(
                            'show'
                        );

                    }

                }

            }
        );

    });

</script>

@stack('scripts')

</body>

</html>
