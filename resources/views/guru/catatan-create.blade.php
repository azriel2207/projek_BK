<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Catatan - Guru BK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Buat Catatan Siswa</h1>
            
            <form action="{{ route('guru.catatan.simpan') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Siswa
                    </label>
                    <select name="user_id" id="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input type="date" name="tanggal" id="tanggal" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-4">
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Catatan
                    </label>
                    <textarea name="isi" id="isi" rows="6" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Tuliskan catatan mengenai siswa..." required></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('guru.catatan.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition duration-200">
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>