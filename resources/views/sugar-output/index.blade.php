@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gula Keluar</h1>
    </div>

    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="text-gray-600">Pelaporan Gula</span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Gula Keluar</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Sak Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-brown-500">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold mb-2">Total Sak</h2>
                    <div class="text-4xl font-bold">{{ number_format($sugarOutputs->sum('sak')) }}</div>
                    {{-- Bagian data keseluruhan disembunyikan --}}
                    {{--
                    <div class="mt-2 text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full inline-block">
                        Data keseluruhan
                    </div>
                    --}}
                </div>
                <div class="w-12 h-12 bg-brown-100 rounded-full flex items-center justify-center">
                    <i class="ph ph-package text-brown-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Bobot Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold mb-2">Total Bobot</h2>
                    <div class="text-4xl font-bold">{{ number_format($sugarOutputs->sum('bobot')) }} kg</div>
                    {{-- Bagian data keseluruhan disembunyikan --}}
                    {{--
                    <div class="mt-2 text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full inline-block">
                        Data keseluruhan
                    </div>
                    --}}
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="ph ph-scales text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Harga Card -->
        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold mb-2">Total Harga</h2>
                    <div class="text-4xl font-bold">Rp{{ number_format($sugarOutputs->sum('total_harga')) }}</div>
                    {{-- Bagian data keseluruhan disembunyikan --}}
                    {{--
                    <div class="mt-2 text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full inline-block">
                        Data keseluruhan
                    </div>
                    --}}
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="ph ph-money text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Data Sak Keluar Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Gula Keluar</h3>
            <div class="h-64">
                <canvas id="sakChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Data Pembeli Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Pembeli</h3>
            <div class="h-64">
                <canvas id="pembeliChart" width="400" height="200"></canvas>
            </div>
            <!-- Labels dengan warna -->
            <div class="mt-4 flex flex-wrap justify-center gap-4">
                @foreach($pembeliChartData as $index => $pembeli)
                    @php
                        $colors = [
                            'rgba(95, 158, 160, 0.8)',   // Cadet Blue (biru kehijauan muted)
                            'rgba(188, 143, 143, 0.8)',  // Rosy Brown (coklat kemerahan soft)
                            'rgba(119, 136, 153, 0.8)',  // Light Slate Gray (abu-abu biru)
                            'rgba(205, 192, 176, 0.8)',  // Tan (coklat krem)
                            'rgba(147, 112, 219, 0.8)',  // Medium Purple (ungu muted)
                            'rgba(112, 128, 144, 0.8)',  // Slate Gray (abu-abu gelap soft)
                            'rgba(160, 82, 45, 0.8)',    // Saddle Brown (coklat tanah)
                            'rgba(106, 90, 205, 0.8)',   // Slate Blue (biru ungu muted)
                            'rgba(139, 69, 19, 0.8)',    // Saddle Brown (coklat gelap soft)
                            'rgba(72, 61, 139, 0.8)'     // Dark Slate Blue (biru gelap muted)
                        ];
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="flex items-center">
                        <div class="w-4 h-4 mr-2" style="background-color: {{ $color }};"></div>
                        <span class="text-sm">{{ $pembeli->nama_pembeli }}: {{ number_format($pembeli->total_sak) }} sak</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gula Keluar Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Gula Keluar</h2>
            <a href="{{ route('sugar-output.create') }}" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="ph ph-plus mr-2"></i>
                Tambah Gula Keluar
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="overflow-x-auto">
            <div class="flex items-center mb-6 min-w-max gap-4 px-2">
                <div class="relative flex-shrink-0">
                    <form action="{{ route('sugar-output.index') }}" method="GET" class="flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Temukan data disini" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500 w-80">
                        <div class="absolute left-3 top-2.5">
                            <i class="ph ph-magnifying-glass text-gray-500 text-lg"></i>
                        </div>
                    </form>
                </div>

                <form action="{{ route('sugar-output.index') }}" method="GET" class="flex items-center space-x-4 flex-shrink-0">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="bulan" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brown-500 w-32">
                        <option value="">Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>

                    <select name="tahun" class="border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-brown-500 w-24">
                        <option value="">Tahun</option>
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <button type="button" id="openFilterModal" class="bg-brown-500 text-white px-4 py-2 rounded-lg flex items-center hover:bg-brown-600 whitespace-nowrap">
                        <span>Filter</span>
                        <i class="ph-fill ph-funnel ml-2 text-lg"></i>
                    </button>
                </form>

                <div class="flex space-x-2 flex-shrink-0">
                    <a href="{{ route('sugar-output.export-pdf', request()->query()) }}" class="border border-red-500 text-red-500 px-4 py-2 rounded-lg flex items-center hover:bg-red-50 whitespace-nowrap">
                        <i class="ph-fill ph-file-pdf mr-2 text-lg"></i>
                        Ekspor PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Nama Pembeli</th>
                        <th class="py-3 px-4 text-left">Sak</th>
                        <th class="py-3 px-4 text-left">Bobot (Kg)</th>
                        <th class="py-3 px-4 text-left">Harga (Per KG)</th>
                        <th class="py-3 px-4 text-left">Total Harga</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sugarOutputs as $index => $output)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $sugarOutputs->firstItem() + $index }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($output->tanggal)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ $output->nama_pembeli }}</td>
                        <td class="py-3 px-4">{{ number_format($output->sak) }}</td>
                        <td class="py-3 px-4">{{ number_format($output->bobot) }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($output->harga_per_kg) }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($output->total_harga) }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('sugar-output.edit', $output->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph-fill ph-pencil-simple text-xl"></i>
                                </a>
                                <form action="{{ route('sugar-output.destroy', $output->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="ph-fill ph-trash text-xl"></i>

                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-500">Tidak ada data gula keluar</td>
                    </tr>
                    @endforelse
                    
                    <!-- Total Harga Keseluruhan sebagai baris terakhir -->
                    <tr class="bg-brown-100 border-t-2 border-brown-300">
                        <td class="py-3 px-4 font-bold" colspan="6">Total Harga Keseluruhan:</td>
                        <td class="py-3 px-4 font-bold">Rp{{ number_format($totalHarga ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-4"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-gray-600">
                Menampilkan {{ $sugarOutputs->firstItem() ?? 0 }} sampai {{ $sugarOutputs->lastItem() ?? 0 }} dari {{ $sugarOutputs->total() }} data
            </div>
            <div class="flex items-center space-x-2">
                {{ $sugarOutputs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data dari database untuk chart sak keluar
const sakChartData = @json($sakChartData ?? []);
const sakLabels = sakChartData.map(item => {
    const date = new Date(item.tanggal);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
});
const sakValues = sakChartData.map(item => item.sak);

const sakData = {
    labels: sakLabels,
    datasets: [{
        label: 'Jumlah Sak: {{ number_format($totalSak) }} total',
        data: sakValues,
        borderColor: 'rgba(161, 204, 165, 1)',
        backgroundColor: 'rgba(161, 204, 165, 0.2)',
        tension: 0.4,
        fill: true
    }]
};

// Data dari database untuk chart pembeli
const pembeliChartData = @json($pembeliChartData ?? []);
const pembeliLabels = pembeliChartData.map(item => item.nama_pembeli);
const pembeliValues = pembeliChartData.map(item => item.total_sak);

const pembeliData = {
    labels: pembeliLabels,
    datasets: [{
        data: pembeliValues,
        backgroundColor: [
                'rgba(95, 158, 160, 0.8)',   // Cadet Blue (biru kehijauan muted)
                'rgba(188, 143, 143, 0.8)',  // Rosy Brown (coklat kemerahan soft)
                'rgba(119, 136, 153, 0.8)',  // Light Slate Gray (abu-abu biru)
                'rgba(205, 192, 176, 0.8)',  // Tan (coklat krem)
                'rgba(147, 112, 219, 0.8)',  // Medium Purple (ungu muted)
                'rgba(112, 128, 144, 0.8)',  // Slate Gray (abu-abu gelap soft)
                'rgba(160, 82, 45, 0.8)',    // Saddle Brown (coklat tanah)
                'rgba(106, 90, 205, 0.8)',   // Slate Blue (biru ungu muted)
                'rgba(139, 69, 19, 0.8)',    // Saddle Brown (coklat gelap soft)
                'rgba(72, 61, 139, 0.8)'     // Dark Slate Blue (biru gelap muted)
        ]
    }]
};

// Sak Chart (Line Chart)
const sakCtx = document.getElementById('sakChart').getContext('2d');
new Chart(sakCtx, {
    type: 'line',
    data: sakData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }
});

// Pembeli Chart (Bar Chart)
const pembeliCtx = document.getElementById('pembeliChart').getContext('2d');
new Chart(pembeliCtx, {
    type: 'bar',
    data: pembeliData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

<!-- Filter Modal -->
<div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Filters</h2>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('sugar-output.index') }}" class="text-brown-600 hover:text-brown-800 font-medium">Reset Filter</a>
                    <button type="button" id="closeFilterModal" class="text-gray-500 hover:text-gray-700">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form action="{{ route('sugar-output.index') }}" method="GET">
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
                            <label for="tanggal_dari" class="block text-gray-700 mb-1">Tanggal Awal</label>
                            <div class="relative">
                                <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">                                    
                            </div>
                        </div>
                        <div>
                            <label for="tanggal_sampai" class="block text-gray-700 mb-1">Tanggal Akhir</label>
                            <div class="relative">
                                <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">    
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pembeli Filter -->
                <div class="mb-4 border-b border-gray-200 pb-4">
                    <div class="flex justify-between items-center mb-2 cursor-pointer" id="pembeliHeader">
                        <h3 class="text-lg font-semibold text-gray-800">Nama Pembeli</h3>
                        <svg class="w-5 h-5 text-gray-700 transform transition-transform duration-200" id="pembeliIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="relative" id="pembeliContent">
                        <select id="pembeli" name="pembeli[]" multiple class="select2-pembeli w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                            @foreach($pembeliData as $pembeli)
                                <option value="{{ $pembeli->nama_pembeli }}" {{ in_array($pembeli->nama_pembeli, (array)request('pembeli', [])) ? 'selected' : '' }}>{{ $pembeli->nama_pembeli }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelFilterBtn" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-brown-500 text-white rounded-lg hover:bg-brown-600">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk Filter Modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Select2 untuk dropdown pembeli
        $('.select2-pembeli').select2({
            placeholder: "Pilih pembeli...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filterModal')
        });
        
        const filterModal = document.getElementById('filterModal');
        const openFilterModal = document.getElementById('openFilterModal');
        const closeFilterModal = document.getElementById('closeFilterModal');
        const cancelFilterBtn = document.getElementById('cancelFilterBtn');
    
        if (openFilterModal && filterModal) {
            openFilterModal.addEventListener('click', function() {
                filterModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Mencegah scroll pada body
                
                // Reinisialisasi Select2 saat modal dibuka
                $('.select2-pembeli').select2({
                    placeholder: "Pilih pembeli...",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#filterModal')
                });
            });
        }

        if (closeFilterModal && filterModal) {
            closeFilterModal.addEventListener('click', function() {
                filterModal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Mengaktifkan kembali scroll pada body
            });
        }

        if (cancelFilterBtn && filterModal) {
            cancelFilterBtn.addEventListener('click', function() {
                filterModal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Mengaktifkan kembali scroll pada body
            });
        }

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

        if (tanggalHeader && tanggalContent && tanggalIcon) {
            tanggalHeader.addEventListener('click', function() {
                tanggalContent.classList.toggle('hidden');
                tanggalIcon.classList.toggle('rotate-180');
            });
        }
        
        // Toggle dropdown untuk Pembeli
        const pembeliHeader = document.getElementById('pembeliHeader');
        const pembeliContent = document.getElementById('pembeliContent');
        const pembeliIcon = document.getElementById('pembeliIcon');

        if (pembeliHeader && pembeliContent && pembeliIcon) {
            pembeliHeader.addEventListener('click', function() {
                pembeliContent.classList.toggle('hidden');
                pembeliIcon.classList.toggle('rotate-180');
            });
        }

        // Secara default, konten filter tertutup
        if (tanggalContent) {
            tanggalContent.classList.add('hidden');
        }
        
        if (pembeliContent) {
            pembeliContent.classList.add('hidden');
        }

        // Smooth scrolling implementation
        document.documentElement.style.scrollBehavior = 'smooth';

        // Smooth scroll untuk pagination links
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                setTimeout(() => {
                    const container = document.querySelector('.container');
                    if (container) {
                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 100);
            });
        });

        // Smooth scroll untuk tombol "Tambah Gula Keluar"
        const addButton = document.querySelector('a[href*="create"]');
        if (addButton) {
            addButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Smooth scroll untuk form submission
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });
    });
</script>
@endsection