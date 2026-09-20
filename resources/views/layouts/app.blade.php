<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Solar Maintenance System') }}
    </title>


    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    {{-- AdminLTE 4 --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css"
    >


    {{-- Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <style>

        :root {

            --solar-green: #198754;
            --solar-green-dark: #146c43;

            --solar-yellow: #ffc107;
            --solar-yellow-dark: #d39e00;

            --solar-white: #ffffff;
            --solar-light: #f8f9fa;

            --solar-border: #e5e7eb;

            --solar-text: #343a40;
            --solar-muted: #6c757d;

            --sidebar-dark: #212529;
            --sidebar-darker: #1b1f22;

        }


        /*
        |--------------------------------------------------------------------------
        | General
        |--------------------------------------------------------------------------
        */

        body {

            background-color: var(--solar-light);

            color: var(--solar-text);

        }


        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */

        .app-sidebar {

            background-color: var(--sidebar-dark) !important;

        }


        .app-sidebar .sidebar-brand {

            background-color: var(--sidebar-darker);

        }


        .app-sidebar .brand-link {

            color: var(--solar-white) !important;

            text-decoration: none;

        }


        .app-sidebar .brand-link:hover {

            color: var(--solar-yellow) !important;

        }


        .brand-image {

            width: 35px;

            height: 35px;

            object-fit: contain;

        }


        /*
        |--------------------------------------------------------------------------
        | Sidebar Navigation
        |--------------------------------------------------------------------------
        */

        .app-sidebar .nav-link {

            color: #ced4da !important;

            border-radius: 6px;

            margin: 2px 8px;

            transition: all 0.2s ease;

        }


        .app-sidebar .nav-link:hover {

            background-color: rgba(25, 135, 84, 0.20);

            color: var(--solar-white) !important;

        }


        .app-sidebar .nav-link.active {

            background-color: var(--solar-green) !important;

            color: var(--solar-white) !important;

            box-shadow: none;

        }


        .app-sidebar .nav-link.active .nav-icon {

            color: var(--solar-yellow) !important;

        }


        .app-sidebar .nav-icon {

            color: #adb5bd;

        }


        .app-sidebar .nav-link:hover .nav-icon {

            color: var(--solar-yellow);

        }


        .nav-header {

            color: #868e96 !important;

            font-size: 0.70rem;

            font-weight: 700;

            letter-spacing: 0.08em;

            padding: 18px 16px 6px !important;

        }


        /*
        |--------------------------------------------------------------------------
        | Brand
        |--------------------------------------------------------------------------
        */

        .brand-text {

            font-weight: 700;

            letter-spacing: 0.2px;

        }


        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        .app-header {

            background-color: var(--solar-white) !important;

            border-bottom: 1px solid var(--solar-border);

        }


        .app-header .nav-link {

            color: var(--solar-text);

        }


        .app-header .nav-link:hover {

            color: var(--solar-green);

        }


        /*
        |--------------------------------------------------------------------------
        | Main Content
        |--------------------------------------------------------------------------
        */

        .app-main {

            background-color: var(--solar-light);

        }


        /*
        |--------------------------------------------------------------------------
        | Cards
        |--------------------------------------------------------------------------
        */

        .card {

            border-radius: 10px;

        }


        /*
        |--------------------------------------------------------------------------
        | Buttons
        |--------------------------------------------------------------------------
        */

        .btn-success {

            background-color: var(--solar-green);

            border-color: var(--solar-green);

        }


        .btn-success:hover {

            background-color: var(--solar-green-dark);

            border-color: var(--solar-green-dark);

        }


        /*
        |--------------------------------------------------------------------------
        | Form Controls
        |--------------------------------------------------------------------------
        */

        .form-control:focus,
        .form-select:focus {

            border-color: var(--solar-green);

            box-shadow:
                0 0 0 0.2rem rgba(25, 135, 84, 0.15);

        }


        /*
        |--------------------------------------------------------------------------
        | Tables
        |--------------------------------------------------------------------------
        */

        .table > :not(caption) > * > * {

            padding-top: 0.85rem;

            padding-bottom: 0.85rem;

        }


        /*
        |--------------------------------------------------------------------------
        | Sidebar Treeview
        |--------------------------------------------------------------------------
        */

        .nav-treeview {

            background-color: rgba(0, 0, 0, 0.12);

        }


        .nav-treeview .nav-link {

            font-size: 0.92rem;

            padding-left: 42px !important;

        }


        /*
        |--------------------------------------------------------------------------
        | User Avatar
        |--------------------------------------------------------------------------
        */

        .user-avatar {

            width: 34px;

            height: 34px;

            border-radius: 50%;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            background-color: var(--solar-green);

            color: white;

            font-weight: 700;

        }


        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 767.98px) {

            .app-main {

                padding-left: 0 !important;

            }

            .card-body {

                padding: 1rem;

            }

        }

    </style>

    @stack('styles')

</head>


<body class="layout-fixed sidebar-expand-lg">

<div class="app-wrapper">


    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <nav class="app-header navbar navbar-expand bg-white">

        <div class="container-fluid">


            {{-- Sidebar Toggle --}}
            <ul class="navbar-nav">

                <li class="nav-item">

                    <a class="nav-link"
                       data-lte-toggle="sidebar"
                       href="#"
                       role="button">

                        <i class="bi bi-list fs-4"></i>

                    </a>

                </li>

            </ul>


            {{-- Right Navigation --}}
            <ul class="navbar-nav ms-auto">


                {{-- Notifications --}}
                <li class="nav-item">

                    <a class="nav-link"
                       href="#"
                       title="Notifications">

                        <i class="bi bi-bell"></i>

                    </a>

                </li>


                {{-- User Dropdown --}}
                @auth

                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle
                                  d-flex align-items-center gap-2"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown">

                            <span class="user-avatar">

                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                            </span>

                            <span class="d-none d-md-inline">

                                {{ auth()->user()->name }}

                            </span>

                        </a>


                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">


                            <li>

                                <div class="dropdown-header">

                                    <strong>
                                        {{ auth()->user()->name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        {{ auth()->user()->email }}

                                    </small>

                                </div>

                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>


                            <li>

                                <a class="dropdown-item"
                                   href="{{ route('profile.edit') }}">

                                    <i class="bi bi-person me-2"></i>

                                    My Profile

                                </a>

                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>


                            <li>

                                <form method="POST"
                                      action="{{ route('logout') }}">

                                    @csrf

                                    <button type="submit"
                                            class="dropdown-item text-danger">

                                        <i class="bi bi-box-arrow-right me-2"></i>

                                        Logout

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </li>

                @endauth

            </ul>

        </div>

    </nav>


    {{-- ============================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ============================================================= --}}

    <aside class="app-sidebar
                  bg-dark
                  shadow"
           data-bs-theme="dark">


        {{-- Brand --}}
        <div class="sidebar-brand">

            <a href="{{ route('dashboard') }}"
               class="brand-link">

                <i class="bi bi-sun-fill text-warning fs-4 me-2"></i>

                <span class="brand-text">

                    Solar Maintenance

                </span>

            </a>

        </div>


        {{-- Sidebar Menu --}}
        <div class="sidebar-wrapper">

            <nav class="mt-2">

                <ul class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu">


                    {{-- ================================================= --}}
                    {{-- DASHBOARD --}}
                    {{-- ================================================= --}}

                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}"
                           class="nav-link
                           {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-speedometer2"></i>

                            <p>
                                Dashboard
                            </p>

                        </a>

                    </li>


                    {{-- ================================================= --}}
                    {{-- INSTALLATION MANAGEMENT --}}
                    {{-- ================================================= --}}

                    <li class="nav-header">
                        INSTALLATION MANAGEMENT
                    </li>


                    <li class="nav-item
                        {{ request()->routeIs('installations.*') ? 'menu-open' : '' }}">

                        <a href="#"
                           class="nav-link
                           {{ request()->routeIs('installations.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-building"></i>

                            <p>

                                Installations

                                <i class="nav-arrow bi bi-chevron-right"></i>

                            </p>

                        </a>


                        <ul class="nav nav-treeview">

                            <li class="nav-item">

                                <a href="{{ route('installations.index') }}"
                                   class="nav-link
                                   {{ request()->routeIs('installations.index') ? 'active' : '' }}">

                                    <i class="nav-icon bi bi-circle"></i>

                                    <p>
                                        All Installations
                                    </p>

                                </a>

                            </li>


                            <li class="nav-item">

                                <a href="{{ route('installations.create') }}"
                                   class="nav-link
                                   {{ request()->routeIs('installations.create') ? 'active' : '' }}">

                                    <i class="nav-icon bi bi-circle"></i>

                                    <p>
                                        Add Installation
                                    </p>

                                </a>

                            </li>

                        </ul>

                    </li>


                    {{-- ================================================= --}}
                    {{-- COMPONENTS --}}
                    {{-- ================================================= --}}

                    <li class="nav-header">
                        COMPONENTS
                    </li>


                    <li class="nav-item
                        {{ request()->routeIs('components.*') || request()->routeIs('component-types.*') ? 'menu-open' : '' }}">

                        <a href="#"
                           class="nav-link
                           {{ request()->routeIs('components.*') || request()->routeIs('component-types.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-box-seam"></i>

                            <p>

                                Components

                                <i class="nav-arrow bi bi-chevron-right"></i>

                            </p>

                        </a>


                        <ul class="nav nav-treeview">

                            <li class="nav-item">

                                <a href="{{ route('components.index') }}"
                                   class="nav-link
                                   {{ request()->routeIs('components.index') ? 'active' : '' }}">

                                    <i class="nav-icon bi bi-circle"></i>

                                    <p>
                                        All Components
                                    </p>

                                </a>

                            </li>


                            <li class="nav-item">

                                <a href="{{ route('components.create') }}"
                                   class="nav-link
                                   {{ request()->routeIs('components.create') ? 'active' : '' }}">

                                    <i class="nav-icon bi bi-circle"></i>

                                    <p>
                                        Register Component
                                    </p>

                                </a>

                            </li>


                            <li class="nav-item">

                                <a href="{{ route('component-types.index') }}"
                                   class="nav-link
                                   {{ request()->routeIs('component-types.*') ? 'active' : '' }}">

                                    <i class="nav-icon bi bi-circle"></i>

                                    <p>
                                        Component Types
                                    </p>

                                </a>

                            </li>

                        </ul>

                    </li>


                    {{-- ================================================= --}}
                    {{-- INSPECTION & MONITORING --}}
                    {{-- ================================================= --}}

                    <!-- Inspection & Monitoring -->
<li class="nav-header">INSPECTION & MONITORING</li>

<li class="nav-item">
    <a href="{{ route('inspections.index') }}"
       class="nav-link {{ request()->routeIs('inspections.index') ? 'active' : '' }}">
        <i class="nav-icon bi bi-clipboard-check"></i>
        <p>Inspections</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('inspections.create') }}"
       class="nav-link {{ request()->routeIs('inspections.create') ? 'active' : '' }}">
        <i class="nav-icon bi bi-plus-circle"></i>
        <p>New Inspection</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('measurements.index') }}"
       class="nav-link {{ request()->routeIs('measurements.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-speedometer2"></i>
        <p>Measurements</p>
    </a>
</li>

                    {{-- Measurements --}}
                    <li class="nav-item">

                        <a href="{{ route('measurements.index') }}"
                           class="nav-link
                           {{ request()->routeIs('measurements.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-speedometer2"></i>

                            <p>
                                Measurements
                            </p>

                        </a>

                    </li>


                    {{-- ================================================= --}}
                    {{-- MAINTENANCE --}}
                    {{-- ================================================= --}}

                    <li class="nav-header">
                        MAINTENANCE
                    </li>


                    {{-- Maintenance Schedule --}}
                    <li class="nav-item">

                        <a href="{{ route('maintenance-schedules.index') }}"
                           class="nav-link
                           {{ request()->routeIs('maintenance-schedules.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-calendar-check"></i>

                            <p>
                                Maintenance Schedule
                            </p>

                        </a>

                    </li>


                    {{-- Maintenance Records --}}
                    <li class="nav-item">

                        <a href="{{ route('maintenance-records.index') }}"
                           class="nav-link
                           {{ request()->routeIs('maintenance-records.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-clipboard-check"></i>

                            <p>
                                Maintenance Records
                            </p>

                        </a>

                    </li>


                    {{-- ================================================= --}}
                    {{-- ANALYSIS & DECISION SUPPORT --}}
                    {{-- ================================================= --}}

                  <!-- Analysis & Decision Support -->
<li class="nav-header">ANALYSIS & DECISION SUPPORT</li>

<!-- Degradation Analysis -->
<li class="nav-item">
    <a href="{{ route('degradation.index') }}"
       class="nav-link {{ request()->routeIs('degradation.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-bar-chart-line"></i>
        <p>
            Degradation Analysis
        </p>
    </a>
</li>

<!-- Cost Management -->
<li class="nav-item">
    <a href="{{ route('cost-records.index') }}"
       class="nav-link {{ request()->routeIs('cost-records.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-cash-stack"></i>
        <p>
            Cost Management
        </p>
    </a>
</li>

<!-- Replacement Forecast -->
<li class="nav-item">
    <a href="{{ route('replacement-forecasts.index') }}"
       class="nav-link {{ request()->routeIs('replacement-forecasts.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-graph-up-arrow"></i>
        <p>Replacement Forecast</p>
    </a>
</li>

<!-- Reports -->
<li class="nav-item">
    <a href="{{ route('reports.index') }}"
       class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
        <p>Reports</p>
    </a>
</li>
                    {{-- ================================================= --}}
                    {{-- SYSTEM --}}
                    {{-- ================================================= --}}

                    <li class="nav-header">
                        SYSTEM
                    </li>


                    {{-- User Management --}}
                    <li class="nav-item">

                        <a href="#"
                           class="nav-link">

                            <i class="nav-icon bi bi-people"></i>

                            <p>

                                User Management

                                <span class="badge
                                             bg-warning
                                             text-dark
                                             ms-auto">

                                    Soon

                                </span>

                            </p>

                        </a>

                    </li>


                    {{-- Profile --}}
                    <li class="nav-item">

                        <a href="{{ route('profile.edit') }}"
                           class="nav-link
                           {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                            <i class="nav-icon bi bi-person-circle"></i>

                            <p>
                                My Profile
                            </p>

                        </a>

                    </li>


                    {{-- Logout --}}
                    <li class="nav-item">

                        <form method="POST"
                              action="{{ route('logout') }}">

                            @csrf

                            <button type="submit"
                                    class="nav-link border-0 bg-transparent w-100 text-start">

                                <i class="nav-icon bi bi-box-arrow-right"></i>

                                <p>
                                    Logout
                                </p>

                            </button>

                        </form>

                    </li>


                </ul>

            </nav>

        </div>

    </aside>


    {{-- ============================================================= --}}
    {{-- MAIN --}}
    {{-- ============================================================= --}}

    <main class="app-main">

    @hasSection('content')
        @yield('content')
    @elseif(isset($slot))
        {{ $slot }}
    @endif

</main>
</div>


{{-- AdminLTE JS --}}
<script
    src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js">
</script>


{{-- Bootstrap JS --}}
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


@stack('scripts')

</body>

</html>