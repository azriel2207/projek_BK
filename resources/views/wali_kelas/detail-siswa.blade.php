@extends('layouts.wali-kelas-layout')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('wali_kelas.daftar-siswa') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">{{ $student->nama_lengkap }}</h1>
        <p class="text-gray-600 mt-2">NIS: {{ $student->nis }} | Kelas: {{ $student->kelas ?? '-' }}</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 mb-8 border-b">
        <button onclick="showTab('info')" id="tab-info" class="px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600">
            Informasi
        </button>
        <button onclick="showTab('catatan')" id="tab-catatan" class="px-4 py-2 font-semibold text-gray-600">
            Catatan
        </button>
    </div>

    <!-- Info Tab -->
    <div id="info" class="tab-content">
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Pribadi</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Nama Lengkap</dt>
                        <dd class="text-gray-800">{{ $student->nama_lengkap }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">NIS</dt>
                        <dd class="text-gray-800">{{ $student->nis }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Email</dt>
                        <dd class="text-gray-800">{{ $student->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Kelas</dt>
                        <dd class="text-gray-800">{{ $student->kelas ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Tanggal Lahir</dt>
                        <dd class="text-gray-800">
                            @if($student->tgl_lahir)
                                {{ $student->tgl_lahir->format('d M Y') }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
                <a href="{{ route('wali_kelas.data-diri', $student->id) }}" class="text-blue-600 hover:text-blue-800 mt-4 inline-block">
                    <i class="fas fa-edit mr-1"></i>Edit Data
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kontak</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">No. HP</dt>
                        <dd class="text-gray-800">{{ $student->no_hp ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Alamat</dt>
                        <dd class="text-gray-800">{{ $student->alamat ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Catatan Tab -->
    <div id="catatan" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tambah Catatan</h3>
            <form action="{{ route('wali_kelas.catatan.tambah', $student->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="tipe_catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Catatan
                    </label>
                    <select name="tipe_catatan" id="tipe_catatan" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="perkembangan">Perkembangan</option>
                        <option value="prestasi">Prestasi</option>
                        <option value="masalah">Masalah</option>
                        <option value="konsultasi">Konsultasi</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="tanggal_catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input 
                        type="date" 
                        name="tanggal_catatan" 
                        id="tanggal_catatan"
                        value="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border rounded-lg"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea 
                        name="catatan" 
                        id="catatan"
                        rows="4"
                        class="w-full px-4 py-2 border rounded-lg"
                        required
                    ></textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Simpan Catatan
                </button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Catatan Siswa</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tipe</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Catatan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($catatanWali as $c)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-600">{{ $c->tanggal_catatan->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        {{ ucfirst($c->tipe_catatan) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-800">{{ substr($c->catatan, 0, 50) }}...</td>
                                <td class="px-6 py-4">
                                    <button onclick="editCatatan({{ $c->id }})" class="text-blue-600 hover:text-blue-800 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('wali_kelas.catatan.hapus', $c->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus catatan?')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada catatan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($catatanWali->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $catatanWali->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id^="tab-"]').forEach(el => {
        el.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        el.classList.add('text-gray-600');
    });

    // Show selected tab
    document.getElementById(tabName).classList.remove('hidden');
    document.getElementById('tab-' + tabName).classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    document.getElementById('tab-' + tabName).classList.remove('text-gray-600');
}
</script>
@endsection
