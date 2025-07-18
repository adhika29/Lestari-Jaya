@extends('layouts.app')

@section('content')
<!-- Tambahkan jQuery dan Select2 CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="text-gray-600">Karyawan</span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Gaji Karyawan</span>
        </div>
    </div>

    <!-- Gaji Karyawan Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Gaji Karyawan</h2>
            <a href="{{ route('gaji-karyawan.create') }}" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="ph ph-plus mr-2"></i>
                Tambah Gaji Karyawan
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <form action="{{ route('gaji-karyawan.index') }}" method="GET" id="searchForm">
                            <input type="text" name="search" placeholder="Temukan data disini" value="{{ request('search') }}" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500 w-80">
                            <div class="absolute left-3 top-2.5">
                                <i class="ph ph-magnifying-glass text-gray-500"></i>
                            </div>
                            <button type="submit" hidden></button>
                        </form>
                    </div>

                    <form action="{{ route('gaji-karyawan.index') }}" method="GET" id="monthYearFilterForm" class="flex items-center space-x-4">
                        <select name="bulan" id="bulanSelect" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brown-500">
                            <option value="">Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>

                        <select name="tahun" id="tahunSelect" class="border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-brown-500">
                            <option value="">Tahun</option>
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        
                        <!-- Filter Button untuk Modal -->
                        <button type="button" id="openFilterModal" class="bg-brown-500 text-white px-4 py-2 rounded-lg flex items-center hover:bg-brown-600" onclick="event.stopPropagation()">
                            <span>Filter</span>
                            <i class="ph-fill ph-funnel ml-2"></i>
                        </button>
                        
                        <!-- Tambahkan tombol submit terpisah -->
                        <button type="submit" id="submitMonthYear" class="hidden">Submit</button>
                    </form>
                </div>
                
                <div class="flex space-x-2">
                    <a href="#" class="border border-red-500 text-red-500 px-4 py-2 rounded-lg flex items-center hover:bg-red-50">
                        <i class="ph-fill ph-file-pdf mr-2"></i>
                        Ekspor PDF
                    </a>
                    <a href="#" class="border border-green-500 text-green-500 px-4 py-2 rounded-lg flex items-center hover:bg-green-50">
                        <i class="ph-fill ph-file-xls mr-2"></i>
                        Ekspor Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Modal -->
        <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Filters</h2>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('gaji-karyawan.index') }}" class="text-brown-600 hover:text-brown-800 font-medium">Reset Filter</a>
                        </div>
                    </div>
                    
                    <form action="{{ route('gaji-karyawan.index') }}" method="GET">
                        <!-- Tanggal Filter -->
                        <div class="mb-4 border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-center mb-2 cursor-pointer" id="tanggalHeader">
                                <h3 class="text-lg font-semibold text-gray-800">Tanggal</h3>
                                <svg class="w-5 h-5 text-gray-700 transform transition-transform duration-200" id="tanggalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="tanggalContent">
                                <div>
                                    <label for="tanggal_awal" class="block text-gray-700 mb-1">Tanggal Awal</label>
                                    <div class="relative">
                                        <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">                                    
                                    </div>
                                </div>
                                <div>
                                    <label for="tanggal_akhir" class="block text-gray-700 mb-1">Tanggal Akhir</label>
                                    <div class="relative">
                                        <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">    
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Karyawan Filter -->
                        <div class="mb-4 border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-center mb-2 cursor-pointer" id="karyawanHeader">
                                <h3 class="text-lg font-semibold text-gray-800">Karyawan</h3>
                                <svg class="w-5 h-5 text-gray-700 transform transition-transform duration-200" id="karyawanIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="relative" id="karyawanContent">
                                <select id="karyawan" name="karyawan[]" multiple class="select2-karyawan w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                                    @foreach(\App\Models\Karyawan::where('status_aktif', true)->orderBy('nama')->get() as $karyawan)
                                        <option value="{{ $karyawan->id }}" {{ in_array($karyawan->id, (array)request('karyawan', [])) ? 'selected' : '' }}>{{ $karyawan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Filter Buttons -->
                        <div class="flex justify-end space-x-4 mt-6">
                            <button type="button" id="cancelFilterBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-brown-600 text-white rounded-lg hover:bg-brown-700">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Sak</th>
                        <th class="py-3 px-4 text-left">Bobot (kg)</th>
                        <th class="py-3 px-4 text-left">Jumlah Gula (ton)</th>
                        <th class="py-3 px-4 text-left">Gaji (per ton)</th>
                        <th class="py-3 px-4 text-left">Total Gaji</th>
                        <th class="py-3 px-4 text-left">Karyawan</th>
                        <th class="py-3 px-4 text-left">Jumlah Karyawan</th>
                        <th class="py-3 px-4 text-left">Gaji per Karyawan</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($gajiKaryawan as $index => $gaji)
                    <tr>
                        <td class="py-3 px-4">{{ $gajiKaryawan->firstItem() + $index }}</td>
                        <td class="py-3 px-4">{{ $gaji->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ $gaji->sak }}</td>
                        <td class="py-3 px-4">{{ number_format($gaji->bobot_kg, 0, ',', '.') }} kg</td>
                        <td class="py-3 px-4">{{ number_format($gaji->jumlah_gula_ton, 2, ',', '.') }} ton</td>
                        <td class="py-3 px-4">Rp{{ number_format($gaji->gaji_per_ton, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            <div class="max-h-20 overflow-y-auto">
                                @foreach($gaji->karyawan as $k)
                                    <div class="mb-1">{{ $k->nama }}</div>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-4">{{ $gaji->jumlah_karyawan }}</td>
                        <td class="py-3 px-4 font-semibold">Rp{{ number_format($gaji->gaji_per_karyawan, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('gaji-karyawan.edit', $gaji->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph-fill ph-pencil-simple"></i>
                                </a>
                                <form action="{{ route('gaji-karyawan.destroy', $gaji->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="ph-fill ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-6 text-center text-gray-500">Tidak ada data gaji karyawan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-gray-600">
                Menampilkan {{ $gajiKaryawan->firstItem() ?? 0 }} sampai {{ $gajiKaryawan->lastItem() ?? 0 }} dari {{ $gajiKaryawan->total() }} data
            </div>
            <div class="flex">
                {{ $gajiKaryawan->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk form pencarian
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchForm').submit();
            }
        });
        
        // Tambahkan event listener untuk dropdown bulan dan tahun
        const bulanSelect = document.getElementById('bulanSelect');
        const tahunSelect = document.getElementById('tahunSelect');
        
        bulanSelect.addEventListener('change', function() {
            document.getElementById('submitMonthYear').click();
        });
        
        tahunSelect.addEventListener('change', function() {
            document.getElementById('submitMonthYear').click();
        });
        
        // Inisialisasi Select2 untuk dropdown karyawan
        $('.select2-karyawan').select2({
            placeholder: "Pilih karyawan...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filterModal')
        });
        
        // Modal Filter Script
        const openFilterBtn = document.getElementById('openFilterModal');
        const filterModal = document.getElementById('filterModal');
        const cancelFilterBtn = document.getElementById('cancelFilterBtn');
        
        // Buka modal saat tombol filter diklik
        openFilterBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            console.log('Filter button clicked'); // Debugging
            
            // Pastikan modal ditampilkan dengan benar
            filterModal.style.display = 'flex';
            filterModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Mencegah scroll pada body
            
            // Reinisialisasi Select2 saat modal dibuka
            $('.select2-karyawan').select2({
                placeholder: "Pilih karyawan...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#filterModal')
            });
        });
        
        // Tutup modal saat tombol Batal diklik
        cancelFilterBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Tambahkan ini
            event.stopPropagation(); // Tambahkan ini
            filterModal.classList.add('hidden');
            filterModal.style.display = 'none'; // Tambahkan ini
            document.body.style.overflow = 'auto'; // Mengaktifkan kembali scroll pada body
        });
        
        // Tutup modal saat mengklik di luar modal
        filterModal.addEventListener('click', function(e) {
            if (e.target === filterModal) {
                filterModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Toggle dropdown untuk Tanggal
        const tanggalHeader = document.getElementById('tanggalHeader');
        const tanggalContent = document.getElementById('tanggalContent');
        const tanggalIcon = document.getElementById('tanggalIcon');
        
        tanggalHeader.addEventListener('click', function() {
            tanggalContent.classList.toggle('hidden');
            tanggalIcon.classList.toggle('rotate-180');
        });
        
        // Toggle dropdown untuk Karyawan
        const karyawanHeader = document.getElementById('karyawanHeader');
        const karyawanContent = document.getElementById('karyawanContent');
        const karyawanIcon = document.getElementById('karyawanIcon');
        
        karyawanHeader.addEventListener('click', function() {
            karyawanContent.classList.toggle('hidden');
            karyawanIcon.classList.toggle('rotate-180');
        });

        // Secara default, konten filter tertutup
        if (tanggalContent) {
            tanggalContent.classList.add('hidden');
        }
        if (karyawanContent) {
            karyawanContent.classList.add('hidden');
        }

    });
</script>
@endsection