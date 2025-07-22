@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ route('dashboard') }}" class="hover:text-brown-500">Dashboard</a>
            <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-brown-500 font-medium">Pelaporan Gula</span>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Chart Gula Masuk Card -->
        <div class="bg-white p-6 rounded-lg shadow-md md:col-span-2">
            <h2 class="text-xl font-semibold mb-6">Gula Masuk</h2>
            <div class="h-64">
                <canvas id="sugarInputChart"></canvas>
            </div>
        </div>

        <!-- Stats Cards Container -->
        <div class="flex flex-col justify-between h-full space-y-6">
            <!-- Total Sak Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-brown-500 flex-1">
                <div class="flex justify-between items-start h-full">
                    <div class="flex flex-col justify-center">
                        <h2 class="text-xl font-semibold mb-2">Total Sak</h2>
                        <div class="text-4xl font-bold">{{ number_format($totalSak) }}</div>
                        {{-- Bagian perubahan dari minggu lalu disembunyikan --}}
                        {{--
                        <div class="mt-2 {{ $sakChangeClass }} px-3 py-1 rounded-full inline-block">
                            {{ $sakChangeText }}% dari minggu lalu
                        </div>
                        --}}
                    </div>
                    <div class="w-12 h-12 bg-brown-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-package text-brown-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Bobot Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500 flex-1">
                <div class="flex justify-between items-start h-full">
                    <div class="flex flex-col justify-center">
                        <h2 class="text-xl font-semibold mb-2">Total Bobot</h2>
                        <div class="text-4xl font-bold">{{ number_format($totalBobot) }} kg</div>
                        {{-- Bagian perubahan dari minggu lalu disembunyikan --}}
                        {{--
                        <div class="mt-2 {{ $bobotChangeClass }} px-3 py-1 rounded-full inline-block">
                            {{ $bobotChangeText }}% dari minggu lalu
                        </div>
                        --}}
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-arrow-down-right text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gula Masuk Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold flex items-center">
                Gula Masuk
            </h2>
            <a href="{{ route('sugar-input.create') }}" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="ph ph-plus mr-2"></i>
                Tambah Gula Masuk
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="flex items-center mb-6">
            <div class="relative mr-4">
                <input type="text" placeholder="Temukan data disini" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500 w-80">
                <div class="absolute left-3 top-2.5">
                    <i class="ph ph-magnifying-glass text-gray-500"></i>
                </div>
            </div>

            <form action="{{ route('sugar-input.index') }}" method="GET" id="monthYearFilterForm" class="flex items-center space-x-4">
                <select name="bulan" id="bulanSelect" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brown-500" onchange="document.getElementById('monthYearFilterForm').submit()">
                    <option value="">Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>

                <select name="tahun" id="tahunSelect" class="border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-brown-500" onchange="document.getElementById('monthYearFilterForm').submit()">
                    <option value="">Tahun</option>
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                
                <!-- Filter Button untuk Modal -->
                <button type="button" id="openFilterModal" class="bg-brown-500 text-white px-4 py-2 rounded-lg flex items-center hover:bg-brown-600">
                    <span>Filter</span>
                    <i class="ph-fill ph-funnel ml-2"></i>
                </button>
            </form>

            <div class="ml-auto flex space-x-2">
                <a href="{{ route('sugar-input.export-pdf', request()->query()) }}" class="border border-red-500 text-red-500 px-4 py-2 rounded-lg flex items-center hover:bg-red-50">
                    <i class="ph-fill ph-file-pdf mr-2"></i>
                    Ekspor PDF
                </a>
                <!-- Tombol ekspor Excel dihapus -->
            </div>
        </div>
        
        <!-- Filter Modal (tambahkan di bagian bawah) -->
        <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Filter Data</h3>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('sugar-input.index') }}" class="text-brown-600 hover:text-brown-800 font-medium">Reset Filter</a>
                            <!-- Ikon X dihilangkan -->
                        </div>
                    </div>
                    
                    <form action="{{ route('sugar-input.index') }}" method="GET">
                        <!-- Tanggal Filter -->
                        <div class="mb-4 border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-center mb-2 cursor-pointer" id="tanggalHeader">
                                <h3 class="text-lg font-semibold text-gray-800">Tanggal</h3>
                                <i class="ph ph-caret-down text-gray-700"></i>
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
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Sak</th>
                        <th class="py-3 px-4 text-left">Bobot</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inputs as $index => $input)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $input->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ number_format($input->sak) }}</td>
                        <td class="py-3 px-4">{{ number_format($input->bobot) }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('sugar-input.edit', $input->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph-fill ph-pencil-simple text-xl"></i>
                                </a>
                                <form action="{{ route('sugar-input.destroy', $input->id) }}" method="POST" class="inline">
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
                        <td colspan="5" class="py-6 text-center text-gray-500">Tidak ada data gula masuk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-gray-600">
                Menampilkan {{ $inputs->firstItem() ?? 0 }} sampai {{ $inputs->lastItem() ?? 0 }} dari {{ $inputs->total() }} data
            </div>
            <div class="flex items-center space-x-2">
                {{ $inputs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart gula masuk
        const chartData = @json($chartData);
        
        // Debugging - tampilkan data di console
        console.log('Chart Data:', chartData);
        
        // Cek apakah ada data chart
        if (chartData && chartData.length > 0) {
            const labels = chartData.map(item => item.tanggal);
            const values = chartData.map(item => item.sak);
            
            console.log('Labels:', labels);
            console.log('Values:', values);

            // Chart Gula Masuk
            const ctx = document.getElementById('sugarInputChart');
            if (!ctx) {
                console.error('Canvas element not found!');
                return;
            }
            
            try {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Sak',
                            data: values,
                            backgroundColor: 'rgba(109, 69, 52, 0.2)',
                            borderColor: 'rgba(109, 69, 52, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(109, 69, 52, 1)',
                            pointRadius: 4,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.2)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                console.log('Chart created successfully');
            } catch (error) {
                console.error('Error creating chart:', error);
            }
        } else {
            // Tampilkan pesan jika tidak ada data
            const chartElement = document.getElementById('sugarInputChart');
            if (chartElement) {
                chartElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Tidak ada data untuk ditampilkan</div>';
                console.log('No data available for chart');
            }
        }
    });
</script>
<script>
    // Modal Filter
    const filterModal = document.getElementById('filterModal');
    const openFilterModal = document.getElementById('openFilterModal');
    const closeFilterModal = document.getElementById('closeFilterModal');
    const cancelFilterBtn = document.getElementById('cancelFilterBtn');

    if (openFilterModal && filterModal) {
        openFilterModal.addEventListener('click', () => {
            filterModal.classList.remove('hidden');
        });
    }

    if (closeFilterModal && filterModal) {
        closeFilterModal.addEventListener('click', () => {
            filterModal.classList.add('hidden');
        });
    }

    if (cancelFilterBtn && filterModal) {
        cancelFilterBtn.addEventListener('click', () => {
            filterModal.classList.add('hidden');
        });
    }

    // Tanggal Accordion
    const tanggalHeader = document.getElementById('tanggalHeader');
    const tanggalContent = document.getElementById('tanggalContent');
    const tanggalIcon = document.querySelector('#tanggalHeader svg');

    if (tanggalHeader && tanggalContent) {
        tanggalHeader.addEventListener('click', () => {
            tanggalContent.classList.toggle('hidden');
            tanggalIcon.classList.toggle('rotate-180');
        });
    }
</script>
@endsection