@extends('layouts.guru-layout')

@section('title', 'Dashboard Guru BK - Sistem BK')

@section('page-content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-md p-6 mb-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-blue-100">Ada {{ $stats['permintaan_menunggu'] ?? 0 }} permintaan konseling yang menunggu konfirmasi</p>
            </div>
            <div class="text-5xl opacity-20">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Permintaan Baru</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['permintaan_menunggu'] ?? 0 }}</p>
                    <p class="text-orange-600 text-xs mt-2 flex items-center">
                        <i class="fas fa-clock mr-1"></i> Menunggu konfirmasi
                    </p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-inbox text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Konseling Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['konseling_hari_ini'] ?? 0 }}</p>
                    <p class="text-blue-600 text-xs mt-2 flex items-center">
                        <i class="fas fa-calendar-check mr-1"></i> Sudah dikonfirmasi
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_siswa'] ?? 0 }}</p>
                    <p class="text-green-600 text-xs mt-2 flex items-center">
                        <i class="fas fa-users mr-1"></i> Aktif bimbingan
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Selesai Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['selesai_bulan_ini'] ?? 0 }}</p>
                    <p class="text-blue-600 text-xs mt-2 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i> Konseling selesai
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-tasks text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Permintaan Konseling -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-inbox mr-2 text-orange-600"></i>Permintaan Konseling Baru
                </h3>
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-medium">
                    {{ isset($permintaanBaru) ? $permintaanBaru->count() : 0 }} Baru
                </span>
            </div>
            
            <div class="space-y-3">
                @if(isset($permintaanBaru) && $permintaanBaru->count() > 0)
                    @foreach($permintaanBaru as $janji)
                    <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500 hover:bg-yellow-100 transition" data-id="{{ $janji->id }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="bg-yellow-200 p-2 rounded-full">
                                        <i class="fas fa-user text-yellow-700 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $janji->name }}</p>
                                        <p class="text-xs text-gray-600 capitalize">{{ str_replace('_', ' ', $janji->jenis_bimbingan) }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 ml-11 mb-2">{{ Str::limit($janji->keluhan, 100) }}</p>
                                <div class="flex flex-wrap gap-4 text-xs text-gray-600 ml-11">
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($janji->tanggal)->format('d-m-Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $janji->waktu }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 ml-4">
                                <form action="{{ route('guru.permintaan.konfirmasi', $janji->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded text-xs hover:bg-green-700 transition font-medium flex items-center justify-center">
                                        <i class="fas fa-check mr-1"></i>Konfirmasi
                                    </button>
                                </form>
                                <button class="w-full bg-blue-100 text-blue-800 px-3 py-2 rounded text-xs hover:bg-blue-200 transition font-medium flex items-center justify-center detail-btn"
                                        data-id="{{ $janji->id }}"
                                        data-nama="{{ $janji->name }}"
                                        data-jenis="{{ str_replace('_', ' ', $janji->jenis_bimbingan) }}"
                                        data-keluhan="{{ $janji->keluhan }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($janji->tanggal)->format('d-m-Y') }}"
                                        data-waktu="{{ $janji->waktu }}">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                        <p class="text-sm">Tidak ada permintaan baru</p>
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('guru.permintaan') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                    Lihat Semua Permintaan <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-day mr-2 text-blue-600"></i>Jadwal Hari Ini
            </h3>
            
            <div class="space-y-3">
                @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                    @foreach($jadwalHariIni as $jadwal)
                    <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $jadwal->name }}</p>
                                <p class="text-xs text-gray-600 capitalize">{{ str_replace('_', ' ', $jadwal->jenis_bimbingan) }}</p>
                            </div>
                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">{{ $jadwal->waktu }}</span>
                        </div>
                        <p class="text-xs text-gray-700">{{ Str::limit($jadwal->keluhan, 60) }}</p>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-calendar-check text-gray-300 text-2xl mb-2"></i>
                        <p class="text-gray-500 text-sm">Tidak ada jadwal hari ini</p>
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('guru.jadwal.tambah') }}" class="block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm text-center font-medium">
                    <i class="fas fa-calendar-plus mr-1"></i>Tambah Jadwal
                </a>
            </div>
        </div>
    </div>

    <!-- Riwayat Konseling Summary -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600 mb-2 font-medium">Konseling Selesai Bulan Ini</p>
                <p class="text-3xl font-bold text-blue-600">{{ isset($stats['selesai_bulan_ini']) ? $stats['selesai_bulan_ini'] : 0 }}</p>
            </div>
            <div class="text-5xl text-blue-200">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-200">
            <a href="{{ route('guru.riwayat.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm inline-flex items-center">
                <i class="fas fa-arrow-right mr-1"></i>Lihat Riwayat Lengkap
            </a>
        </div>
    </div>

    <!-- Modal Detail - Enhanced with animations -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform transition-all duration-300">
            <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-green-600 text-white p-6 rounded-t-2xl sticky top-0 shadow-md">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold flex items-center">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        Detail Permintaan Konseling
                    </h3>
                    <button onclick="closeDetailModal()" class="text-white hover:text-gray-100 text-2xl hover:bg-white hover:bg-opacity-20 p-2 rounded-full transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <!-- Student Info Card -->
                <div class="flex items-start space-x-4 p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-l-4 border-blue-600 shadow-sm hover:shadow-md transition">
                    <div class="bg-blue-600 p-3 rounded-full shadow-md">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <label class="text-xs font-bold text-blue-700 uppercase tracking-wider">👤 Nama Siswa</label>
                        <p id="modal-nama" class="text-lg font-bold text-blue-900 mt-1"></p>
                    </div>
                </div>

                <!-- Consultation Type & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-5 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-l-4 border-green-600 shadow-sm hover:shadow-md transition">
                        <label class="text-xs font-bold text-green-700 uppercase tracking-wider flex items-center">
                            <i class="fas fa-bookmark mr-2"></i>Jenis Bimbingan
                        </label>
                        <p id="modal-jenis" class="text-gray-800 font-semibold mt-2"></p>
                    </div>

                    <div class="p-5 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border-l-4 border-amber-600 shadow-sm hover:shadow-md transition">
                        <label class="text-xs font-bold text-amber-700 uppercase tracking-wider flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>Tanggal
                        </label>
                        <p id="modal-tanggal" class="text-gray-800 font-semibold mt-2"></p>
                    </div>
                </div>

                <!-- Time -->
                <div class="p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-l-4 border-blue-600 shadow-sm hover:shadow-md transition">
                    <label class="text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center">
                        <i class="fas fa-clock mr-2"></i>Waktu Konseling
                    </label>
                    <p id="modal-waktu" class="text-gray-800 font-semibold mt-2"></p>
                </div>

                <!-- Complaint -->
                <div class="p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-l-4 border-gray-600 shadow-sm">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center mb-3">
                        <i class="fas fa-comment-dots mr-2"></i>Keluhan / Permasalahan
                    </label>
                    <p id="modal-keluhan" class="text-gray-700 leading-relaxed text-base bg-white bg-opacity-50 p-3 rounded-lg"></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button onclick="konfirmasiDariModal()" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold text-sm flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105">
                        <i class="fas fa-check mr-2"></i>Konfirmasi Janji
                    </button>
                    <button onclick="closeDetailModal()" class="flex-1 bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 transition font-semibold text-sm flex items-center justify-center shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>

    <script>
        let currentModalId = null;

        const menuToggle = document.getElementById('menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) sidebar.classList.toggle('active');
            });
        }

        function konfirmasiJanji(id) {
            if(!id) return;
            
            showConfirm(
                '<i class="fas fa-handshake text-blue-500 text-3xl mb-3"></i><br><strong>Konfirmasi Janji Konseling</strong>',
                'question',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/guru/permintaan/${id}/konfirmasi`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                'Konfirmasi Janji?'
            );
        }

        function showDetailModal(id, nama, jenis, keluhan, tanggal, waktu) {
            currentModalId = id;
            document.getElementById('modal-nama').textContent = nama || '';
            document.getElementById('modal-jenis').textContent = jenis || '';
            document.getElementById('modal-keluhan').textContent = keluhan || '';
            document.getElementById('modal-tanggal').textContent = tanggal || '';
            document.getElementById('modal-waktu').textContent = waktu || '';
            document.getElementById('detailModal').classList.remove('hidden');
            
            // Add smooth slide-in animation
            const modal = document.getElementById('detailModal');
            modal.style.animation = 'fadeIn 0.3s ease-in';
        }

        function closeDetailModal() {
            const dm = document.getElementById('detailModal');
            if(dm) {
                dm.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    dm.classList.add('hidden');
                }, 300);
            }
            currentModalId = null;
        }

        function konfirmasiDariModal() {
            if(currentModalId) {
                // Langsung submit form tanpa dialog konfirmasi
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/guru/permintaan/${currentModalId}/konfirmasi`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                // Close modal sebelum submit
                closeDetailModal();
                
                // Submit form untuk redirect ke halaman input catatan
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Detail button event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers untuk detail buttons
            const detailButtons = document.querySelectorAll('.detail-btn');
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const jenis = this.dataset.jenis;
                    const keluhan = this.dataset.keluhan;
                    const tanggal = this.dataset.tanggal;
                    const waktu = this.dataset.waktu;
                    showDetailModal(id, nama, jenis, keluhan, tanggal, waktu);
                });
            });
        });

        // Add CSS animations for modal
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: scale(1);
                }
                to {
                    opacity: 0;
                    transform: scale(0.95);
                }
            }
        `;
        document.head.appendChild(style);

        // Auto-close alerts (hanya untuk notification, bukan modal)
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('> .container > [class*="border-green-500"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.3s';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }, 5000);
            });
        });
    </script>
</div>
@endsection