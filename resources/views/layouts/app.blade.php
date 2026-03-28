<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Assembly Scanner</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
            color: white;
        }

        .nav-header {
            padding: 20px;
            font-weight: bold;
            font-size: 1.2rem;
            border-bottom: 1px solid #495057;
        }

        .content {
            padding: 20px;
        }

        .card-custom {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
    </style>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3" style="width: 250px;">
            <div class="nav-header">🏭 Assembly Sys</div>
            <ul class="nav nav-pills flex-column mb-auto mt-4">
                <li>
                    <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ url('/scan') }}" class="{{ request()->is('scan') ? 'active' : '' }}">
                        <i class="bi bi-upc-scan me-2"></i> Scanning
                    </a>
                </li>
                <li>
                    <a href="{{ url('/export') }}" class="{{ request()->is('export') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-excel me-2"></i> Export Data
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-light bg-white shadow-sm mb-4 ps-4">
                <span class="navbar-brand mb-0 h1">{{ $title ?? 'System' }}</span>
                <div class="d-flex me-4">
                    <span class="me-3">👤 {{ Auth::user()->name ?? 'Operator' }}</span>
                    <!-- Demo Logout Form (if using standard auth) -->
                    <!-- <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button></form> -->
                </div>
            </nav>
            <div class="container-fluid content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>

</html>