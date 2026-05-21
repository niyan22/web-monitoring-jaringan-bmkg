<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (function(){
            const _origAlert = window.alert;
            window.alert = function(msg){
                try{ if(String(msg).includes('JS MASUK')) return; }catch(e){}
                return _origAlert.apply(this, arguments);
            };
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, width 0.3s ease;
        }

        body {
            background-color: #f7f8fa;
            font-family: 'Figtree', sans-serif;
        }

        body.dark-mode {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .flex-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            padding: 1.5rem;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .sidebar {
            background: #1f1f1f;
            border-right-color: #374151;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
            overflow: hidden;
        }

        .sidebar.collapsed .logo h6 {
            display: none;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .logo {
            /* center logo block */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            animation: slideInLeft 0.5s ease-out;
        }

        .logo img {
            width: 60px;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            object-fit: cover;
        }

        .logo h6 {
            margin-top: 0.75rem;
            font-weight: 700;
            font-size: 1rem;
        }

        .nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        body.dark-mode .nav-link {
            color: #e0e0e0;
        }

        .nav-link:hover {
            background-color: #e7f3ed;
            color: #16a34a;
            transform: translateX(5px);
        }

        body.dark-mode .nav-link:hover {
            background-color: #374151;
            color: #4ade80;
        }

        .nav-link.active {
            background-color: #e7f3ed;
            color: #16a34a;
            font-weight: 600;
            box-shadow: inset 0 0 0 3px #16a34a;
        }

        body.dark-mode .nav-link.active {
            background-color: #374151;
            color: #4ade80;
            box-shadow: inset 0 0 0 3px #4ade80;
        }

        .nav-link i {
            font-size: 1.25rem;
            min-width: 1.5rem;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .logout-section {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        body.dark-mode .logout-section {
            border-top-color: #374151;
        }

        .logout-section form {
            width: 100%;
        }

        .logout-section .btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
            animation: slideInUp 0.5s ease-out;
        }

        .sidebar.collapsed .logout-section .btn {
            justify-content: center;
            padding: 0.75rem;
        }

        /* Main Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Navbar Styles */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        body.dark-mode .navbar {
            background: #1f1f1f;
            border-bottom-color: #374151;
        }

        .btn-light {
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            color: #333;
        }

        body.dark-mode .btn-light {
            background-color: #374151;
            border-color: #4b5563;
            color: #e0e0e0;
        }

        .btn-light:hover {
            background-color: #e5e7eb;
            color: #333;
        }

        body.dark-mode .btn-light:hover {
            background-color: #4b5563;
            color: #e0e0e0;
        }

        .btn-light:active {
            background-color: #d1d5db;
        }

        body.dark-mode .btn-light:active {
            background-color: #6b7280;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        body.dark-mode .main-content {
            background-color: #1a1a1a;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        body.dark-mode .card {
            background-color: #2a2a2a;
            color: #e0e0e0;
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .nav-link {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .nav-item:nth-child(1) .nav-link { animation-delay: 0.1s; }
        .nav-item:nth-child(2) .nav-link { animation-delay: 0.2s; }
        .nav-item:nth-child(3) .nav-link { animation-delay: 0.3s; }
        .nav-item:nth-child(4) .nav-link { animation-delay: 0.4s; }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 0;
                --sidebar-collapsed-width: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 260px;
                z-index: 1000;
            }

            .sidebar.collapsed {
                width: 260px;
                transform: translateX(0);
            }

            .sidebar.collapsed .logo h6 {
                display: block;
            }

            .sidebar.collapsed .nav-text {
                display: inline;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar.collapsed ~ .main-wrapper {
                margin-left: 0;
            }
        }

        .badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>

<body>
    <div class="flex-container">
        @auth
            @include('partials.sidebar')
        @endauth

        <div class="main-wrapper">
            @auth
                @include('partials.navbar')
            @endauth

            <main class="main-content">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Logout Confirmation
        function showLogoutConfirm() {
            if (confirm('Apakah Anda yakin ingin keluar dari aplikasi ini?\n\nAnda dapat login kembali kapan saja menggunakan akun Anda.')) {
                // Create and submit a form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Dark Mode Toggle
        const toggleDarkBtn = document.getElementById('toggleDark');
        const htmlElement = document.documentElement;

        if (toggleDarkBtn) {
            toggleDarkBtn.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                const icon = toggleDarkBtn.querySelector('i');
                if (document.body.classList.contains('dark-mode')) {
                    icon.classList.remove('bi-moon');
                    icon.classList.add('bi-sun');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    icon.classList.remove('bi-sun');
                    icon.classList.add('bi-moon');
                    localStorage.setItem('darkMode', 'disabled');
                }
            });

            // Check if dark mode was previously enabled
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                toggleDarkBtn.querySelector('i').classList.remove('bi-moon');
                toggleDarkBtn.querySelector('i').classList.add('bi-sun');
            }
        }

        // Sidebar Toggle
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        if (toggleSidebarBtn && sidebar) {
            toggleSidebarBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Check if sidebar was previously collapsed
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
            }
        }

        // Update active link based on current route
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentUrl) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>
