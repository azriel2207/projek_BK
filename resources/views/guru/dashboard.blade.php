<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Guru BK</h1>
            
            <div class="mb-4">
                <p class="text-gray-600">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>!</p>
                <p class="text-gray-500">Role: {{ auth()->user()->role }}</p>
                <p class="text-gray-500">Email: {{ auth()->user()->email }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800">Total Siswa</h3>
                    <p class="text-2xl font-bold text-blue-600">0</p>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-800">Konseling Hari Ini</h3>
                    <p class="text-2xl font-bold text-green-600">0</p>
                </div>
                
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="font-semibold text-purple-800">Jadwal</h3>
                    <p class="text-2xl font-bold text-purple-600">0</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-6">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>