<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .admin-topbar { background:#1f2937; }
        .admin-brand { color:#fff; font-weight:600; }
        .sidebar {
            position: fixed; top: 56px; bottom: 0; left: 0; width: 240px;
            background: #111827; color: #cbd5e1; overflow-y: auto;
        }
        .sidebar a { color:#cbd5e1; text-decoration:none; display:block; padding:10px 16px; border-radius:8px; }
        .sidebar a:hover, .sidebar a.active { background:#374151; color:#fff; }
        .content-wrapper { margin-left: 240px; padding: 24px; }
        
        /* Dashboard Styles */
        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        
        .chart-area { position: relative; height: 20rem; width: 100%; }
        .chart-pie { position: relative; height: 15rem; }
        
        .card { border: none; margin-bottom: 1.5rem; }
        .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        
        .shadow { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }
        
        .table th { 
            border-top: none; 
            font-weight: 600 !important; 
            color: #2c3e50 !important; 
            background-color: #f8f9fc;
        }
        
        .table td { 
            color: #2c3e50 !important; 
            font-weight: 400;
        }
        
        .badge { 
            font-size: 0.75em; 
            font-weight: 600;
            padding: 0.5em 0.75em;
        }
        
        /* Tăng độ tương phản cho badges */
        .badge.text-dark {
            color: #2c3e50 !important;
            font-weight: 700 !important;
        }
        
        .badge.badge-warning {
            background-color: #ffc107 !important;
            border: 1px solid #e0a800;
        }
        
        .badge.badge-info {
            background-color: #17a2b8 !important;
            border: 1px solid #138496;
        }
        
        .badge.badge-success {
            background-color: #28a745 !important;
            border: 1px solid #1e7e34;
        }
        
        .badge.badge-danger {
            background-color: #dc3545 !important;
            border: 1px solid #c82333;
        }
        
        .badge.badge-secondary {
            background-color: #6c757d !important;
            border: 1px solid #545b62;
        }
        
        .form-select {
            border-color: #dee2e6;
            font-weight: 500;
        }
        
        .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary { background-color: #4e73df; border-color: #4e73df; }
        .btn-primary:hover { background-color: #2e59d9; border-color: #2653d4; }
        
        /* Tăng độ tương phản cho chữ */
        .text-dark { color: #2c3e50 !important; font-weight: 500 !important; }
        .font-weight-bold { font-weight: 600 !important; }
        
        h1, h2, h3, h4, h5, h6 { color: #2c3e50 !important; font-weight: 600 !important; }
    </style>
    @stack('styles')
    @yield('styles')
    @stack('head')
    @yield('head')
    @vite([])
</head>
<body>
    <nav class="navbar navbar-expand navbar-dark admin-topbar shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand admin-brand" href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge-high me-2"></i>Admin Panel
            </a>
            <ul class="navbar-nav ms-auto">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-gear me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.home') }}"><i class="fa-solid fa-house me-2"></i>Trang admin</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="sidebar p-3">
        <div class="mb-3 small text-uppercase text-secondary">Quản trị</div>
        <a href="{{ route('admin.dashboard') }}" class="mb-1 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line me-2"></i>Dashboard</a>
        <a href="{{ route('admin.products.index') }}" class="mb-1 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="fa-solid fa-box-open me-2"></i>Sản phẩm</a>
        <a href="{{ route('admin.orders.index') }}" class="mb-1 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="fa-solid fa-shopping-cart me-2"></i>Đơn hàng</a>
        <a href="{{ route('admin.banners.index') }}" class="mb-1 {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"><i class="fa-solid fa-image me-2"></i>Banner</a>
        <a href="{{ route('admin.categories.index') }}" class="mb-1 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="fa-solid fa-layer-group me-2"></i>Danh mục</a>
      
        <a href="{{ route('admin.menus.index') }}" class="mb-1 {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}"><i class="fa-solid fa-bars me-2"></i>Menu</a>
        <a href="{{ route('admin.posts.index') }}" class="mb-1 {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"><i class="fa-solid fa-newspaper me-2"></i>Bài viết</a>
        <a href="{{ route('admin.users.index') }}" class="mb-1 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fa-solid fa-users me-2"></i>Người dùng</a>
    </div>

    <main class="content-wrapper">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
