@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Biaya Konsumsi</h1>
    </div>

    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="text-gray-600">Biaya Pengeluaran</span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Biaya Konsumsi</span>
        </div>
    </div>

    <!-- Chart Section - Kotak terpisah seperti pada gambar -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Kotak Chart Pengirim -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Biaya</h2>
            <div class="h-64">
                <canvas id="biayaPerTanggalChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm font-medium">Total Biaya: <span id="totalBiayaTanggal">Rp0</span></p>
            </div>
        </div>
        
        <!-- Kotak Chart Keterangan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Keterangan</h2>
            <div class="h-64">
                <canvas id="biayaPerKeteranganChart"></canvas>
            </div>
            <!-- Legenda dengan warna dan nilai -->
            <div class="mt-4 flex justify-center flex-wrap gap-4" id="legendContainer">
                <!-- Legenda akan dibuat secara dinamis oleh JavaScript -->
            </div>
        </div>
    </div>

    <!-- Biaya Konsumsi Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Biaya Konsumsi</h2>
            <a href="{{ route('biaya-konsumsi.create') }}" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="ph ph-plus mr-2"></i>
                Tambah Biaya Konsumsi
            </a>
        </div>

        <!-- Search and Filter - Form Terintegrasi -->
        <div class="flex items-center mb-6">
            <form action="{{ route('biaya-konsumsi.index') }}" method="GET" class="flex items-center space-x-4 w-full" id="filterForm">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Temukan data disini" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500 w-80">
                    <div class="absolute left-3 top-2.5">
                        <i class="ph ph-magnifying-glass text-gray-500 text-lg"></i>
                    </div>
                </div>

                <!-- Filter Bulan -->
                <select name="bulan" id="bulanSelect" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <option value="">Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>

                <!-- Filter Tahun -->
                <select name="tahun" id="tahunSelect" class="border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <option value="">Tahun</option>
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>

                <!-- Tombol Filter Lanjutan -->
                <button type="button" id="openFilterModal" class="bg-brown-500 text-white px-4 py-2 rounded-lg flex items-center hover:bg-brown-600">
                    <span>Filter</span>
                    <i class="ph-fill ph-funnel ml-2 text-lg"></i>
                </button>
            </form>

            <div class="ml-auto flex space-x-2">
                <a href="{{ route('biaya-konsumsi.export-pdf', request()->query()) }}" class="border border-red-500 text-red-500 px-4 py-2 rounded-lg flex items-center hover:bg-red-50">
                    <i class="ph-fill ph-file-pdf mr-2 text-lg"></i>
                    Ekspor PDF
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Keterangan</th>
                        <th class="py-3 px-4 text-left">Volume</th>
                        <th class="py-3 px-4 text-left">Satuan</th>
                        <th class="py-3 px-4 text-left">Harga</th>
                        <th class="py-3 px-4 text-left">Total Harga</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biayaKonsumsi as $index => $biaya)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $biayaKonsumsi->firstItem() + $index }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($biaya->tanggal)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ $biaya->keterangan }}</td>
                        <td class="py-3 px-4">{{ $biaya->volume }}</td>
                        <td class="py-3 px-4">{{ $biaya->satuan }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($biaya->harga) }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($biaya->total_harga) }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('biaya-konsumsi.edit', $biaya->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph-fill ph-pencil-simple text-xl"></i>
                                </a>
                                <form action="{{ route('biaya-konsumsi.destroy', $biaya->id) }}" method="POST" class="inline">
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
                        <td colspan="8" class="py-6 text-center text-gray-500">Tidak ada data biaya konsumsi</td>
                    </tr>
                    @endforelse
                    <!-- Total Harga Keseluruhan -->
                    <tr class="bg-brown-100 border-t-2 border-brown-300">
                        <td colspan="6" class="py-3 px-4 font-bold">Total Harga Keseluruhan:</td>
                        <td class="py-3 px-4 font-bold">Rp{{ number_format($totalKeseluruhanHarga ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-4"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-gray-600">
                Menampilkan {{ $biayaKonsumsi->firstItem() ?? 0 }} sampai {{ $biayaKonsumsi->lastItem() ?? 0 }} dari {{ $biayaKonsumsi->total() }} data
            </div>
            <div class="flex items-center space-x-2">
                {{ $biayaKonsumsi->appends(request()->query())->links() }}
            </div>
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
                    <a href="{{ route('biaya-konsumsi.index') }}" class="text-brown-600 hover:text-brown-800 font-medium">Reset Filter</a>
                    <button type="button" id="closeFilterModal" class="text-gray-500 hover:text-gray-700">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form action="{{ route('biaya-konsumsi.index') }}" method="GET">
                <!-- Hidden inputs untuk mempertahankan filter yang sudah ada -->
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                
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
                
                <!-- Keterangan Filter -->
                <div class="mb-4 border-b border-gray-200 pb-4">
                    <div class="flex justify-between items-center mb-2 cursor-pointer" id="keteranganHeader">
                        <h3 class="text-lg font-semibold text-gray-800">Keterangan</h3>
                        <svg class="w-5 h-5 text-gray-700 transform transition-transform duration-200" id="keteranganIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div id="keteranganContent">
                        <div>
                            <label for="keterangan" class="block text-gray-700 mb-1">Pilih Keterangan</label>
                            <div class="relative">
                                <select id="keterangan" name="keterangan[]" multiple class="select2-keterangan w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                                    @foreach($keteranganList as $keterangan)
                                        <option value="{{ $keterangan }}" {{ in_array($keterangan, (array)request('keterangan', [])) ? 'selected' : '' }}>{{ $keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol export dihapus -->
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Data
        const chartDataTanggal = @json($chartDataTanggal);
        const chartDataKeterangan = @json($chartDataKeterangan);

        // Hitung total biaya untuk tanggal
        let totalBiayaTanggal = 0;
        chartDataTanggal.forEach(item => {
            totalBiayaTanggal += parseFloat(item.total);
        });
        document.getElementById('totalBiayaTanggal').textContent = 'Rp' + totalBiayaTanggal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Line Chart - Tren Biaya per Tanggal
        const ctxTanggal = document.getElementById('biayaPerTanggalChart').getContext('2d');
        new Chart(ctxTanggal, {
            type: 'line',
            data: {
                labels: chartDataTanggal.map(item => item.tanggal_formatted),
                datasets: [{
                    label: 'Tren Biaya (Rp)',
                    data: chartDataTanggal.map(item => item.total),
                    backgroundColor: 'rgba(161, 204, 165, 0.2)',
                    borderColor: 'rgba(161, 204, 165, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(161, 204, 165, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Pie Chart - Biaya per Keterangan
        const ctxKeterangan = document.getElementById('biayaPerKeteranganChart').getContext('2d');

        // Warna yang digunakan untuk chart - warna soft netral
        const chartColors = [
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

        new Chart(ctxKeterangan, {
            type: 'pie',
            data: {
                labels: chartDataKeterangan.map(item => item.keterangan),
                datasets: [{
                    data: chartDataKeterangan.map(item => item.total),
                    backgroundColor: chartColors,
                    borderColor: chartColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Menyembunyikan legenda bawaan Chart.js
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp' + context.parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
        
        // Buat legenda dinamis
        const legendContainer = document.getElementById('legendContainer');
        if (legendContainer) {
            legendContainer.innerHTML = ''; // Bersihkan legenda yang ada
            
            chartDataKeterangan.forEach((item, index) => {
                const legendItem = document.createElement('div');
                legendItem.className = 'flex items-center';
                
                const colorBox = document.createElement('div');
                colorBox.className = 'w-4 h-4 mr-2';
                colorBox.style.backgroundColor = chartColors[index % chartColors.length];
                
                const label = document.createElement('span');
                label.className = 'text-sm';
                label.textContent = `${item.keterangan} : Rp${item.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
                
                legendItem.appendChild(colorBox);
                legendItem.appendChild(label);
                legendContainer.appendChild(legendItem);
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when bulan or tahun is changed
        const bulanSelect = document.getElementById('bulanSelect');
        const tahunSelect = document.getElementById('tahunSelect');
        const filterForm = document.getElementById('filterForm');
        
        if (bulanSelect && filterForm) {
            bulanSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        if (tahunSelect && filterForm) {
            tahunSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }
        
        // Auto-submit form when search input changes (optional - dengan delay)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 500); // Submit setelah 500ms tidak ada input
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Select2 untuk dropdown keterangan
        $('.select2-keterangan').select2({
            placeholder: "Pilih keterangan...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filterModal')
        });
        
        // Modal Filter
        const filterModal = document.getElementById('filterModal');
        const openFilterModal = document.getElementById('openFilterModal');
        const closeFilterModal = document.getElementById('closeFilterModal');
        const cancelFilterBtn = document.getElementById('cancelFilterBtn');
    
        if (openFilterModal && filterModal) {
            openFilterModal.addEventListener('click', function() {
                filterModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Mencegah scroll pada body
                
                // Reinisialisasi Select2 saat modal dibuka
                $('.select2-keterangan').select2({
                    placeholder: "Pilih keterangan...",
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

        // Tanggal Accordion
        const tanggalHeader = document.getElementById('tanggalHeader');
        const tanggalContent = document.getElementById('tanggalContent');
        const tanggalIcon = document.getElementById('tanggalIcon');

        if (tanggalHeader && tanggalContent) {
            tanggalHeader.addEventListener('click', function() {
                tanggalContent.classList.toggle('hidden');
                tanggalIcon.classList.toggle('rotate-180');
            });
        }

        // Keterangan Accordion
        const keteranganHeader = document.getElementById('keteranganHeader');
        const keteranganContent = document.getElementById('keteranganContent');
        const keteranganIcon = document.getElementById('keteranganIcon');

        if (keteranganHeader && keteranganContent) {
            keteranganHeader.addEventListener('click', function() {
                keteranganContent.classList.toggle('hidden');
                keteranganIcon.classList.toggle('rotate-180');
            });
        }
    });
</script>
@endsection