<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Phosphor Icons -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast {
            background-color: #6D4534;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            transform: translateX(120%);
            transition: transform 0.3s ease-in-out;
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast-icon {
            margin-right: 12px;
        }
        .toast-message {
            flex-grow: 1;
        }
        .toast-close {
            cursor: pointer;
            padding: 0 5px;
        }
        
        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }
        
        .dropdown-content {
            display: none;
            background-color: #8B4513;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            margin-left: 20px;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        
        .dropdown-content a:hover {
            background-color: #A0522D;
        }
        
        .dropdown.active .dropdown-content {
            display: block;
        }
        
        .dropdown-toggle {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .dropdown-arrow {
            transition: transform 0.3s;
        }
        
        .dropdown.active .dropdown-arrow {
            transform: rotate(180deg);
        }
    </style>
    <!-- Di bagian head, tambahkan: -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Di bagian bawah sebelum </body>, tambahkan: -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</head>
<body class="bg-gray-100">
    <!-- Toast Container untuk notifikasi -->
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-60 bg-brown-500 text-white">
            <!-- Logo yang dimodifikasi - dicentering dan ditambahkan border bottom -->
            <div class="p-[26px] text-2xl font-bold text-center border-b border-white pb-4">
                <span class="text-white">Lestari</span><span class="text-green-300">Jaya</span>
            </div>
            <nav class="mt-8">
                <a href="{{ route('dashboard') }}" class="flex items-center py-3 px-4 hover:bg-brown-600 {{ request()->routeIs('dashboard') ? 'bg-brown-600' : '' }}">
                    <i class="ph ph-house mr-3 text-xl"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('sugar-cane.index') }}" class="flex items-center py-3 px-4 hover:bg-brown-600 {{ request()->routeIs('sugar-cane.*') ? 'bg-brown-600' : '' }}">
                    <i class="ph ph-clipboard-text mr-3 text-xl"></i>
                    <span>Pencatatan pengiriman tebu</span>
                </a>
                
                <!-- Dropdown Menu untuk Pelaporan Gula -->
                <div class="dropdown {{ request()->routeIs('sugar-input.*') || request()->routeIs('sugar-output.*') ? 'active' : '' }}">
                    <div class="dropdown-toggle flex items-center py-3 px-4 hover:bg-brown-600 {{ request()->routeIs('sugar-input.*') || request()->routeIs('sugar-output.*') ? 'bg-brown-600' : '' }}" onclick="toggleDropdown(this)">
                        <i class="ph ph-currency-circle-dollar mr-3 text-xl"></i>
                        <span class="flex-1">Pelaporan Gula</span>
                        <i class="ph ph-caret-down dropdown-arrow text-xl"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('sugar-input.index') }}" class="{{ request()->routeIs('sugar-input.*') ? 'bg-brown-700' : '' }}">
                            <span>Gula Masuk</span>
                        </a>
                        <a href="{{ route('sugar-output.index') }}" class="{{ request()->routeIs('sugar-output.*') ? 'bg-brown-700' : '' }}">
                            <span>Gula Keluar</span>
                        </a>
                    </div>
                </div>
                
                <!-- Dropdown Menu untuk Biaya Pengeluaran -->
                <div class="dropdown {{ request()->routeIs('biaya-konsumsi.*') || request()->routeIs('biaya-operasional.*') ? 'active' : '' }}">
                    <div class="dropdown-toggle flex items-center py-3 px-4 hover:bg-brown-600 {{ request()->routeIs('biaya-konsumsi.*') || request()->routeIs('biaya-operasional.*') ? 'bg-brown-600' : '' }}" onclick="toggleDropdown(this)">
                        <i class="ph ph-clock-countdown mr-3 text-xl"></i>
                        <span class="flex-1">Biaya Pengeluaran</span>
                        <i class="ph ph-caret-down dropdown-arrow text-xl"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('biaya-konsumsi.index') }}" class="{{ request()->routeIs('biaya-konsumsi.*') ? 'bg-brown-700' : '' }}">
                            <span>Biaya Konsumsi</span>
                        </a>
                        <a href="{{ route('biaya-operasional.index') }}" class="{{ request()->routeIs('biaya-operasional.*') ? 'bg-brown-700' : '' }}">
                            <span>Biaya Operasional</span>
                        </a>
                    </div>
                </div>
                
                <!-- Dropdown Menu untuk Karyawan -->
                <div class="dropdown {{ request()->routeIs('karyawan.*') || request()->routeIs('gaji-karyawan.*') ? 'active' : '' }}">
                    <div class="dropdown-toggle flex items-center py-3 px-4 hover:bg-brown-600 {{ request()->routeIs('karyawan.*') || request()->routeIs('gaji-karyawan.*') ? 'bg-brown-600' : '' }}" onclick="toggleDropdown(this)">
                        <i class="ph ph-user mr-3 text-xl"></i>
                        <span class="flex-1">Karyawan</span>
                        <i class="ph ph-caret-down dropdown-arrow text-xl"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="{{ route('karyawan.index') }}" class="{{ request()->routeIs('karyawan.*') ? 'bg-brown-700' : '' }}">
                            <span>Data Karyawan</span>
                        </a>
                        <a href="{{ route('gaji-karyawan.index') }}" class="{{ request()->routeIs('gaji-karyawan.*') ? 'bg-brown-700' : '' }}">
                            <span>Gaji Karyawan</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Navbar -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-white">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" placeholder="Cari disini.." class="w-96 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <div class="absolute left-3 top-2.5">
                        <i class="ph ph-magnifying-glass text-gray-500 text-xl"></i>
                    </div>
                </div>
                
                <!-- Date and User Info -->
                <div class="flex items-center space-x-4">
                    <!-- Date -->
                    <div class="text-right">
                        <div class="text-sm font-medium" id="currentDate"></div>
                        <div class="text-sm text-gray-500" id="currentTime"></div>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="relative">
                        <div class="flex items-center space-x-2 cursor-pointer" onclick="toggleProfileDropdown()">
                            <div class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center text-white overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name=Admin&color=7F9CF5&background=EBF4FF" alt="Admin" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500">Admin</div>
                            </div>
                            <i class="ph ph-caret-down text-gray-500 transition-transform duration-200" id="profileArrow"></i>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50 hidden">
                            <div class="py-1">
                                <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ph ph-user mr-3"></i>
                                    Profile
                                </a>
                                <!-- Opsi Pengaturan dihapus -->
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="ph ph-sign-out mr-3"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- JavaScript untuk waktu realtime -->
    <script>
        function updateDateTime() {
            const now = new Date();
            
            // Format tanggal: DD Bulan YYYY
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            
            // Format waktu: HH:MM:SS
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            
            // Update elemen HTML
            document.getElementById('currentDate').textContent = dateStr;
            document.getElementById('currentTime').textContent = timeStr;
        }
        
        // Update waktu setiap 1 detik
        setInterval(updateDateTime, 1000);
        
        // Panggil sekali saat halaman dimuat
        updateDateTime();
    </script>
    
    <!-- JavaScript untuk notifikasi saat pindah halaman -->
    <script>
        // Fungsi untuk menampilkan toast notification
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            
            // Buat elemen toast
            const toast = document.createElement('div');
            toast.className = 'toast';
            
            // Tentukan ikon berdasarkan tipe notifikasi
            let iconSvg = '';
            if (type === 'success') {
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'info') {
                iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            // Isi toast
            toast.innerHTML = `
                <div class="toast-icon">${iconSvg}</div>
                <div class="toast-message">${message}</div>
                <div class="toast-close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            `;
            
            // Tambahkan toast ke container
            toastContainer.appendChild(toast);
            
            // Tampilkan toast dengan animasi
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Tambahkan event listener untuk tombol close
            const closeButton = toast.querySelector('.toast-close');
            closeButton.addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toastContainer.removeChild(toast);
                }, 300);
            });
            
            // Otomatis hilangkan toast setelah 5 detik
            setTimeout(() => {
                if (toast.parentNode === toastContainer) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode === toastContainer) {
                            toastContainer.removeChild(toast);
                        }
                    }, 300);
                }
            }, 5000);
        }
        
        // Tampilkan notifikasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada flash message dari session
            <?php if(session('success')): ?>
                showToast("<?php echo session('success'); ?>", 'success');
            <?php endif; ?>
            
            // Tampilkan notifikasi halaman dimuat
            showToast("Halaman telah dimuat", 'info');
        });
        
        // Tambahkan event listener untuk semua link internal
        document.addEventListener('click', function(e) {
            const target = e.target.closest('a');
            if (target && target.href && target.href.startsWith(window.location.origin) && !target.hasAttribute('download')) {
                showToast("Berpindah ke halaman baru...", 'info');
            }
        });
        
        // Tambahkan event listener untuk form submission
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.method.toLowerCase() !== 'get') {
                showToast("Memproses permintaan...", 'info');
            }
        });
        
        // JavaScript untuk profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const arrow = document.getElementById('profileArrow');
            
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
        
        // Tutup profile dropdown ketika klik di luar area
        document.addEventListener('click', function(event) {
            const profileSection = event.target.closest('.relative');
            const dropdown = document.getElementById('profileDropdown');
            
            if (!profileSection && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
                document.getElementById('profileArrow').classList.remove('rotate-180');
            }
        });
        
        // JavaScript untuk dropdown pelaporan gula
        function toggleDropdown(element) {
            const dropdown = element.parentElement;
            dropdown.classList.toggle('active');
        }
        
        // Tutup dropdown ketika klik di luar area dropdown
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown.active').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });
        
        // Pastikan dropdown tetap terbuka jika berada di halaman sugar-input atau sugar-output
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            if (currentPath.includes('sugar-input') || currentPath.includes('sugar-output')) {
                const dropdown = document.querySelector('.dropdown');
                if (dropdown) {
                    dropdown.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>