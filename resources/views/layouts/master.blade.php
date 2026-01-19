<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Sistem BK">
    <meta name="theme-color" content="#1e40af">
    <title>@yield('title', 'Sistem BK')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    
    <!-- SweetAlert2 for notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    
    <style>
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
        
        html, body {
            width: 100%;
            height: 100%;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }
        
        input, textarea, select, button {
            -webkit-user-select: text;
            -moz-user-select: text;
            user-select: text;
        }
        
        /* Sidebar links clickable */
        .sidebar a {
            display: block;
            cursor: pointer;
            pointer-events: auto;
            z-index: 51;
        }
        
        .sidebar button {
            cursor: pointer;
            pointer-events: auto;
            z-index: 51;
        }
        
        .sidebar { 
            transition: transform 0.3s ease, margin-left 0.3s ease;
        }
        
        .main-content { 
            transition: margin-left 0.3s ease;
            width: 100%;
        }
        
        /* Desktop view - Simple & Clean */
        @media (min-width: 769px) {
            .sidebar { 
                transform: translateX(0);
                margin-left: 0;
                position: fixed;
                top: 0;
                left: 0;
                width: 16rem;
                height: 100vh;
                box-shadow: none;
            }
            
            .main-content { 
                margin-left: 16rem;
                width: calc(100% - 16rem);
            }
            
            .sidebar-overlay {
                display: none !important;
            }
            
            #menu-toggle {
                display: none !important;
            }
            
            #close-sidebar {
                display: none !important;
            }
        }
        
        /* Tablet and Mobile view */
        @media (max-width: 768px) {
            .sidebar { 
                transform: translateX(-100%);
                width: 100%;
                max-width: 16rem;
                z-index: 50;
                position: fixed;
                top: 0;
                height: 100vh;
                box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
            }
            
            .sidebar.active { 
                transform: translateX(0);
            }
            
            .main-content { 
                margin-left: 0;
            }
            
            body.sidebar-open {
                overflow: hidden;
            }
            
            /* Overlay saat sidebar aktif - separate element */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                pointer-events: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
            
            .sidebar-overlay.active {
                display: block;
                pointer-events: auto;
            }
        }
        
        /* Small mobile optimization */
        @media (max-width: 480px) {
            main {
                padding: 1rem !important;
            }
            
            .p-6 {
                padding: 1rem;
            }
            
            .container {
                max-width: 100%;
            }
        }
        
        /* Prevent zoom on input focus in iOS */
        @media (max-width: 768px) {
            input, textarea, select {
                font-size: 16px !important;
            }
        }
        
        /* Touch-friendly buttons */
        button, a {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Safe area for notches and home bars */
        @supports (padding: max(0px)) {
            body {
                padding-left: max(0px, env(safe-area-inset-left));
                padding-right: max(0px, env(safe-area-inset-right));
                padding-top: max(0px, env(safe-area-inset-top));
                padding-bottom: max(0px, env(safe-area-inset-bottom));
            }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100">
    <!-- Overlay for sidebar - Mobile only -->
    <div class="sidebar-overlay fixed inset-0 md:hidden" aria-hidden="true"></div>
    
    <!-- Sidebar with improved mobile behavior -->
    <div class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-blue-700 text-white overflow-y-auto" role="navigation" aria-label="Sidebar navigation">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                    <i class="fas fa-hands-helping text-2xl"></i>
                    <h1 class="text-lg md:text-xl font-bold">Sistem BK</h1>
                </div>
                <button id="close-sidebar" class="md:hidden text-white hover:text-gray-200 text-xl p-2" aria-label="Close sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <nav class="mt-8">
            @yield('sidebar')
        </nav>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-3 text-red-300 hover:text-red-100 transition w-full">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-40">
            <div class="flex justify-between items-center p-4 md:p-4">
                <div class="flex items-center space-x-2 md:space-x-4 min-w-0 flex-1">
                    <button id="menu-toggle" class="md:hidden text-gray-600 hover:text-gray-800 p-2 -ml-2" aria-label="Toggle menu" aria-expanded="false">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg md:text-xl font-semibold text-gray-800 truncate">@yield('header_title', 'Sistem BK')</h2>
                </div>
                <div class="flex items-center space-x-2 md:space-x-3 flex-shrink-0">
                    <span class="text-sm md:text-base text-gray-700 hidden sm:inline truncate">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-user text-sm md:text-base"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 md:p-6">
            @yield('content')
        </main>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    html: '{{ session("success") }}',
                    icon: 'success',
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    backdrop: true,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (modal) => {
                        const confirmBtn = modal.querySelector('.swal2-confirm');
                        if (confirmBtn) {
                            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
                        }
                    }
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Kesalahan!',
                    html: '{{ session("error") }}',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    backdrop: true,
                    didOpen: (modal) => {
                        const confirmBtn = modal.querySelector('.swal2-confirm');
                        if (confirmBtn) {
                            confirmBtn.classList.add('rounded-lg', 'px-6', 'py-2', 'font-semibold', 'transition', 'hover:shadow-lg');
                        }
                    }
                });
            });
        </script>
    @endif

    <script>
        // Mobile menu toggle dengan improved behavior
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const closeSidebar = document.getElementById('close-sidebar');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const body = document.body;
            
            // Helper function to close sidebar
            function closeSidebarPanel() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                body.style.overflow = '';
                if (menuToggle) menuToggle.setAttribute('aria-expanded', 'false');
            }
            
            // Helper function to open sidebar
            function openSidebarPanel() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                body.style.overflow = 'hidden';
                if (menuToggle) menuToggle.setAttribute('aria-expanded', 'true');
            }
            
            // Open sidebar
            if (menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openSidebarPanel();
                });
            }
            
            // Close sidebar button
            if (closeSidebar) {
                closeSidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeSidebarPanel();
                });
            }
            
            // Close sidebar when clicking on overlay
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth <= 768) {
                        closeSidebarPanel();
                    }
                });
            }
            
            // Close sidebar when clicking on a link
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        closeSidebarPanel();
                    }
                });
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                    closeSidebarPanel();
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeSidebarPanel();
                }
            });

            // Real-time timestamp updates
            function updateRelativeTime() {
                const timeElements = document.querySelectorAll('.relative-time');
                timeElements.forEach(el => {
                    const timestamp = el.getAttribute('data-timestamp');
                    if (!timestamp) return;
                    
                    const createdDate = new Date(timestamp);
                    const now = new Date();
                    const diffMs = now - createdDate;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    const diffDays = Math.floor(diffMs / 86400000);
                    
                    let displayText = '';
                    
                    if (diffMins < 1) {
                        displayText = 'baru saja';
                    } else if (diffMins < 60) {
                        displayText = diffMins + ' menit yang lalu';
                    } else if (diffHours < 24) {
                        displayText = diffHours + ' jam yang lalu';
                    } else if (diffDays < 7) {
                        displayText = diffDays + ' hari yang lalu';
                    } else {
                        // Show exact date for older timestamps
                        displayText = createdDate.toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'short', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    
                    el.textContent = displayText;
                    el.title = createdDate.toLocaleString('id-ID');
                });
            }
            
            // Update timestamps immediately and then every 30 seconds
            updateRelativeTime();
            setInterval(updateRelativeTime, 30000);
        });
    </script>

    @yield('scripts')
</body>
</html>
