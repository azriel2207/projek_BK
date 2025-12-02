<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Counseling System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold flex items-center">
                <i class="fas fa-heart text-white mr-2"></i>
                Counseling System
            </a>
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <i class="fas fa-user-circle mr-2"></i>
                    {{ Auth::user()->username }} ({{ Auth::user()->role }})
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto mt-6 p-4">
        @yield('content')
    </main>

    <!-- Popup Utilities -->
    <script src="{{ asset('js/popup-utils.js') }}"></script>

    <!-- Optional: JavaScript untuk interaksi -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showSuccess("{{ session('success') }}");
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showError("{{ session('error') }}");
            });
        </script>
    @endif

    <script>
        // Initialize Flatpickr for all date inputs dengan format DD/MM/YYYY
        document.addEventListener('DOMContentLoaded', function() {
            // Format tanggal DD/MM/YYYY untuk Indonesia
            flatpickr('input[type="date"]', {
                dateFormat: 'd/m/Y',
                locale: 'id',
                allowInput: true
            });
        });
    </script>
</body>
</html>