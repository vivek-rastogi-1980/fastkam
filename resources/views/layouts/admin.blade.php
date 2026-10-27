<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') | a4Digital</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')

    <style>
        :root {
            --sidebar-width: 270px;
            --primary-navy: #0f172a;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #2563eb;
            --accent-blue: #3b82f6;
            --accent-cyan: #06b6d4;
            --surface-bg: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface-bg);
            color: #1e293b;
            min-height: 100vh;
        }

        code, pre, .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            max-height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .sidebar-brand {
            flex-shrink: 0;
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-menu-wrapper {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar-menu-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-menu-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
        }

        .brand-header {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .nav-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            padding: 1.25rem 1.25rem 0.5rem;
            font-weight: 700;
        }

        .nav-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1.25rem;
            margin: 0.2rem 0.75rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.925rem;
            transition: all 0.2s ease;
        }

        .nav-item-custom:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
            transform: translateX(3px);
        }

        .nav-item-custom.active {
            color: #ffffff;
            background-color: var(--sidebar-active);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .nav-item-custom i {
            font-size: 1.15rem;
        }

        /* Main Content wrapper */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 0.85rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .wallet-pill {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 0.45rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .wallet-pill-icon {
            color: #2563eb;
            font-size: 1.1rem;
        }

        /* Card stylings */
        .glass-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
        }

        .glass-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
        }

        .service-badge {
            font-size: 0.78rem;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Global Pagination Styles & SVG containment */
        .pagination {
            margin-bottom: 0;
            gap: 5px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .pagination svg,
        .page-link svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block;
            vertical-align: middle;
        }

        .pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--card-border);
            color: #475569;
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.38rem 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            text-decoration: none;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        .pagination .page-item .page-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .pagination .page-item.disabled .page-link {
            color: #94a3b8;
            background-color: #f8fafc;
            border-color: #e2e8f0;
            opacity: 0.7;
        }

        /* Status pulse animation for live indicators */
        .status-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }
        .status-pulse-dot::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: inherit;
            animation: statusPulse 2s infinite ease-out;
            opacity: 0.75;
        }
        @keyframes statusPulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        @media (max-width: 992px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.open {
                margin-left: 0;
            }
            #main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <nav id="sidebar">
        <div class="sidebar-brand brand-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-logo-icon">
                    <i class="bi bi-cpu-fill"></i>
                </div>
                <div>
                    <h6 class="text-white mb-0 fw-bold letter-spacing-1">a4Digital</h6>
                </div>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.65rem;">v2.5</span>
        </div>

        <div class="sidebar-menu-wrapper">
            <div class="nav-section-title">Core Navigation</div>
            <div class="nav flex-column">
                <!-- Requirement 3: "Our Services" -->
                <a href="{{ route('admin.services') }}" class="nav-item-custom {{ request()->routeIs('admin.services') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Our Services</span>
                </a>

                <!-- Add Token / Recharge Wallet -->
                <a href="{{ route('wallet.add_balance') }}" class="nav-item-custom {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet-fill text-warning"></i>
                    <span>Add Token</span>
                </a>

                @if(Auth::check() && Auth::user()->role === 'admin')
                <!-- Master-Franchise Architecture Views -->
                <a href="{{ route('admin.franchises') }}" class="nav-item-custom {{ request()->routeIs('admin.franchises*') ? 'active' : '' }}">
                    <i class="bi bi-building-fill-gear"></i>
                    <span>Franchises</span>
                </a>

                <!-- Default Price for New Franchise Only -->
                <a href="{{ route('admin.services.pricing') }}" class="nav-item-custom {{ request()->routeIs('admin.services.pricing*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i>
                    <span>Default Price (New Franchise)</span>
                </a>

                @endif

                <a href="{{ route('admin.logs') }}" class="nav-item-custom {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i>
                    <span>Verification Logs</span>
                </a>
            </div>

            <div class="nav-section-title">Account & Security</div>
            <div class="nav flex-column mb-3">
                <!-- Requirement 3: "Update Password" -->
                <a href="{{ route('admin.password.edit') }}" class="nav-item-custom {{ request()->routeIs('admin.password.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Update Password</span>
                </a>

                <!-- Requirement 3: "Logout" -->
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0">
                    @csrf
                    <button type="submit" class="nav-item-custom text-danger bg-transparent border-0 w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>


    </nav>

    <!-- Main Content Area -->
    <div id="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary btn-sm d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-sm-block">
                    <h5 class="mb-0 fw-bold text-dark">@yield('page_title', 'Dashboard')</h5>
                    @if(trim($__env->yieldContent('page_subtitle')))
                        <small class="text-muted">@yield('page_subtitle')</small>
                    @endif
                </div>
            </div>

            <!-- Header Right: Wallet Balance & User Profile -->
            <div class="d-flex align-items-center gap-3">
                <!-- Wallet/Token System: 1 INR = 1 Token -->
                <div class="wallet-pill" title="1 INR = 1 Token">
                    <i class="bi bi-coin wallet-pill-icon"></i>
                    <span>Wallet: <strong id="topNavbarWalletBalance">{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</strong> Tokens</span>
                    <span class="badge bg-primary text-white" style="font-size: 0.65rem;">1 INR = 1 Token</span>
                </div>

                <a href="{{ route('wallet.add_balance') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm" title="Recharge Wallet Tokens via ApnaCSC UPI">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Add Token</span>
                </a>

                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border rounded-pill px-3 py-1" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="text-start d-none d-md-block">
                            <span class="fw-semibold d-block text-truncate" style="max-width: 140px; font-size: 0.85rem;">{{ Auth::user()->name ?? 'User' }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><h6 class="dropdown-header">Signed in as {{ Auth::user()->email }}</h6></li>
                        <li><a class="dropdown-item" href="{{ route('admin.password.edit') }}"><i class="bi bi-key me-2"></i>Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item text-danger" onclick="document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Alerts -->
        <div class="container-fluid px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-info-circle-fill text-info fs-5"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                        <strong>Please resolve the following errors:</strong>
                    </div>
                    <ul class="mb-0 ps-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto py-3 px-4 bg-white border-top text-muted" style="font-size: 0.84rem;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2 text-secondary">
                    <span>&copy; {{ date('Y') }} <strong class="text-dark">a4Digital</strong></span>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
