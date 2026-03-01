<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Elite Digital Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f2f5; overflow-x: hidden; }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0f0c29, #302b63, #24243e);
            position: fixed;
            top: 0; left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .sidebar-brand h5 { color:#fff; font-weight:700; margin:0; font-size:1.05rem; }
        .sidebar-brand span { font-size:0.72rem; color:rgba(255,255,255,0.5); }
        .nav-section {
            padding: 0.75rem 1rem 0.25rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.3);
            font-weight: 600;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 0.55rem 1.25rem;
            border-radius: 8px;
            margin: 2px 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .sidebar .nav-link i { font-size:1rem; width:20px; flex-shrink:0; }
        .sidebar-nav-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar-nav-scroll::-webkit-scrollbar { width: 3px; }
        .sidebar-nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius:4px; }
        .sidebar-footer {
            padding: 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { display: block; }

        /* Main */
        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            padding: 0.7rem 1.1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }
        .btn-hamburger {
            display: none;
            background: none;
            border: none;
            padding: 5px 7px;
            border-radius: 8px;
            color: #302b63;
            font-size: 1.3rem;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
        }
        .btn-hamburger:hover { background: #f3f4f6; }
        .btn-sidebar-close {
            display: none;
            background: none;
            border: none;
            padding: 4px 6px;
            border-radius: 6px;
            color: rgba(255,255,255,0.5);
            font-size: 1.1rem;
            cursor: pointer;
            margin-left: auto;
            flex-shrink: 0;
        }
        .btn-sidebar-close:hover { color: #fff; background: rgba(255,255,255,0.1); }

        /* Shared */
        .stat-card { background:#fff; border-radius:12px; padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.06); }
        .stat-card .icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
        .card { border:none; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06); }
        .card-header { background:#fff; border-bottom:1px solid #f0f0f0; padding:1rem 1.25rem; font-weight:600; }
        .badge-success { background:#d1fae5; color:#065f46; }
        .badge-warning { background:#fef3c7; color:#92400e; }
        .badge-danger  { background:#fee2e2; color:#991b1b; }
        .table th { font-size:0.8rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#6b7280; }
        .btn-primary { background:#302b63; border-color:#302b63; }
        .btn-primary:hover { background:#24243e; border-color:#24243e; }

        /* ── RESPONSIVE ── */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,0.35); }
            .main-content { margin-left: 0; padding: 0.9rem; }
            .btn-hamburger { display: flex; align-items:center; justify-content:center; }
            .btn-sidebar-close { display: block; }
            .topbar-date { display: none !important; }
        }
        @media (max-width: 575.98px) {
            .main-content { padding: 0.65rem; }
            .topbar { padding: 0.6rem 0.75rem; border-radius: 10px; margin-bottom: 1rem; }
            .stat-card { padding: 0.9rem; }
            .card-header { padding: 0.75rem 1rem; font-size: 0.9rem; }
            .topbar-user-name { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-lightning-fill text-white"></i>
            </div>
            <div class="overflow-hidden">
                <h5>Elite Agency</h5>
                <span>Management System</span>
            </div>
            <button class="btn-sidebar-close" onclick="closeSidebar()" aria-label="Close menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-nav-scroll">
        <nav class="py-2">
            <span class="nav-section">Main</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <span class="nav-section">Portfolio</span>
            <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects.index') || request()->routeIs('admin.projects.create') || request()->routeIs('admin.projects.edit') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-collection-fill"></i> All Projects
            </a>
            <a href="{{ route('admin.projects.categories') }}" class="nav-link {{ request()->routeIs('admin.projects.categories') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-grid-3x3-gap-fill"></i> By Category
            </a>
            <a href="{{ route('admin.projects.featured') }}" class="nav-link {{ request()->routeIs('admin.projects.featured') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-star-fill"></i> Featured Projects
                @php $featCount = \App\Models\Project::where('featured', true)->where('status','published')->count(); @endphp
                @if($featCount)
                    <span class="badge ms-auto rounded-pill" style="background:rgba(255,193,7,0.3);color:#ffc107;font-size:0.65rem">{{ $featCount }}</span>
                @endif
            </a>

            <span class="nav-section">Business</span>
            <a href="{{ route('admin.companies.index') }}" class="nav-link {{ request()->routeIs('admin.companies*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-building"></i> Companies
            </a>
            <a href="{{ route('admin.financials.index') }}" class="nav-link {{ request()->routeIs('admin.financials*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-graph-up-arrow"></i> Financials
            </a>

            <span class="nav-section">Communications</span>
            <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-envelope-fill"></i> Contacts
                @php $newContacts = \App\Models\Contact::where('status','new')->count(); @endphp
                @if($newContacts)
                    <span class="badge bg-danger ms-auto rounded-pill" style="font-size:0.65rem">{{ $newContacts }}</span>
                @endif
            </a>
            <a href="{{ route('admin.campaigns.index') }}" class="nav-link {{ request()->routeIs('admin.campaigns*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="bi bi-send-fill"></i> Email Campaigns
            </a>
        </nav>
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <button class="btn-hamburger" onclick="openSidebar()" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
            <h6 class="mb-0 fw-600 text-truncate" style="font-size:0.95rem">@yield('title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            <span class="text-muted small topbar-date">{{ now()->format('l, M d Y') }}</span>
            <div class="d-flex align-items-center gap-2">
                <div style="width:32px;height:32px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-person-fill text-white" style="font-size:0.85rem"></i>
                </div>
                <span class="small fw-500 topbar-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) closeSidebar();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeSidebar();
    });
</script>
@stack('scripts')
</body>
</html>