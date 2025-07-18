@extends('layouts.app')

@section('content')
<!-- Tambahkan jQuery dan Select2 CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto px-4 py-8">
       <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pengirim Chart Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Pengirim</h2>
            <div class="h-48" id="pengirimChart"></div>
        </div>

        <!-- Jenis Tebu Chart Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Jenis Tebu</h2>
            <div class="h-48" id="jenisTebuChart"></div>
        </div>

        <!-- Rata-rata Cards Container -->
        <div class="flex flex-col space-y-6">
            <!-- Rata-rata Bobot Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-brown-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Rata rata bobot</h2>
                        <div class="text-4xl font-bold">{{ number_format(7900, 0) }} kg</div>
                        <div class="mt-2 bg-green-100 text-green-800 px-3 py-1 rounded-full inline-block">
                            @php
                                // Hitung rata-rata bobot minggu ini
                                $currentWeekAvg = $shipments->where('tanggal', '>=', now()->startOfWeek())->avg('bobot_kg') ?: 0;
                                
                                // Hitung rata-rata bobot minggu lalu
                                $lastWeekAvg = $shipments->where('tanggal', '>=', now()->subWeek()->startOfWeek())
                                                        ->where('tanggal', '<=', now()->subWeek()->endOfWeek())
                                                        ->avg('bobot_kg') ?: 0;
                                
                                // Hitung perubahan
                                $change = $lastWeekAvg > 0 ? (($currentWeekAvg - $lastWeekAvg) / $lastWeekAvg) * 100 : 0;
                                $changeText = $change >= 0 ? '+ ' . number_format(abs($change), 1) : '- ' . number_format(abs($change), 1);
                            @endphp
                            {{ $changeText }} dari minggu lalu
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-brown-100 rounded-full flex items-center justify-center">
                        <i class="ph ph-arrow-circle-down text-brown-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Harga Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Rata rata harga tebu</h2>
                        <div class="text-4xl font-bold">Rp{{ number_format(820, 0) }}</div>
                        <div class="mt-2 bg-green-100 text-green-800 px-3 py-1 rounded-full inline-block">
                            @php
                                // Hitung rata-rata harga minggu ini
                                $currentWeekPriceAvg = $shipments->where('tanggal', '>=', now()->startOfWeek())->avg('harga_per_kg') ?: 0;
                                
                                // Hitung rata-rata harga minggu lalu
                                $lastWeekPriceAvg = $shipments->where('tanggal', '>=', now()->subWeek()->startOfWeek())
                                                            ->where('tanggal', '<=', now()->subWeek()->endOfWeek())
                                                            ->avg('harga_per_kg') ?: 0;
                                
                                // Hitung perubahan
                                $priceChange = $lastWeekPriceAvg > 0 ? (($currentWeekPriceAvg - $lastWeekPriceAvg) / $lastWeekPriceAvg) * 100 : 0;
                                $priceChangeText = $priceChange >= 0 ? '+ ' . number_format(abs($priceChange), 1) : '- ' . number_format(abs($priceChange), 1);
                            @endphp
                            {{ $priceChangeText }} dari minggu lalu
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ph ph-arrow-circle-down text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pencatatan Pengiriman Tebu Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Pencatatan pengiriman tebu</h2>
            <a href="{{ route('sugar-cane.create') }}" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="ph ph-plus mr-2"></i>
                Tambah Pemasukan
            </a>
        </div>      

        <!-- Search and Filter -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Temukan data disini" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500 w-80">
                        <div class="absolute left-3 top-2.5">
                            <i class="ph ph-magnifying-glass text-gray-500"></i>
                        </div>
                    </div>

                    <form action="{{ route('sugar-cane.index') }}" method="GET" id="monthYearFilterForm" class="flex items-center space-x-4">
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
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('sugar-cane.export-pdf', request()->query()) }}" class="border border-red-500 text-red-500 px-4 py-2 rounded-lg flex items-center hover:bg-red-50">
                        <i class="ph-fill ph-file-pdf mr-2"></i>
                        Ekspor PDF
                    </a>
                    <!-- Tombol Export Excel dihapus -->
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
                            <a href="{{ route('sugar-cane.index') }}" class="text-brown-600 hover:text-brown-800 font-medium">Reset Filter</a>
                            <!-- Ikon X dihilangkan -->
                        </div>
                    </div>
                    
                    <form action="{{ route('sugar-cane.index') }}" method="GET">
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
                        
                        <!-- Jenis Tebu Filter -->
                        <div class="mb-4 border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-center mb-2 cursor-pointer" id="jenisTebuHeader">
                                <h3 class="text-lg font-semibold text-gray-800">Jenis Tebu</h3>
                                <svg class="w-5 h-5 text-gray-700 transform transition-transform duration-200" id="jenisTebuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="jenisTebuContent">
                                <div class="flex items-center">
                                    <input type="checkbox" id="jenis_cn" name="jenis_tebu[]" value="Cening (CN)" {{ in_array('Cening (CN)', (array)request('jenis_tebu', [])) ? 'checked' : '' }} class="h-5 w-5 text-brown-600 border-gray-300 rounded focus:ring-brown-500">
                                    <label for="jenis_cn" class="ml-2 text-gray-700">Cening (CN)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="jenis_bl" name="jenis_tebu[]" value="Bululawang (BL)" {{ in_array('Bululawang (BL)', (array)request('jenis_tebu', [])) ? 'checked' : '' }} class="h-5 w-5 text-brown-600 border-gray-300 rounded focus:ring-brown-500">
                                    <label for="jenis_bl" class="ml-2 text-gray-700">Bululawang (BL)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="jenis_br" name="jenis_tebu[]" value="Baru Rakyat (BR)" {{ in_array('Baru Rakyat (BR)', (array)request('jenis_tebu', [])) ? 'checked' : '' }} class="h-5 w-5 text-brown-600 border-gray-300 rounded focus:ring-brown-500">
                                    <label for="jenis_br" class="ml-2 text-gray-700">Baru Rakyat (BR)</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pengirim Filter -->
                        <div class="mb-4 border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-center mb-2 cursor-pointer" id="pengirimHeader">
                                <h3 class="text-lg font-semibold text-gray-800">Pengirim</h3>
                                <svg class="w-5 h-5 text-gray-700 transform transition-transform duration-200" id="pengirimIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="relative" id="pengirimContent">
                                <select id="pengirim" name="pengirim[]" multiple class="select2-pengirim w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                                    @foreach($pengirimData as $pengirim)
                                        <option value="{{ $pengirim->nama_pengirim }}" {{ in_array($pengirim->nama_pengirim, (array)request('pengirim', [])) ? 'selected' : '' }}>{{ $pengirim->nama_pengirim }}</option>
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
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Nama Pengirim</th>
                        <th class="py-3 px-4 text-left">Jenis Tebu</th>
                        <th class="py-3 px-4 text-left">Bobot (KG)</th>
                        <th class="py-3 px-4 text-left">Harga (Per KG)</th>
                        <th class="py-3 px-4 text-left">Total Harga</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $index => $shipment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $shipment->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ $shipment->nama_pengirim }}</td>
                        <td class="py-3 px-4">{{ $shipment->jenis_tebu }}</td>
                        <td class="py-3 px-4">{{ number_format($shipment->bobot_kg) }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($shipment->harga_per_kg) }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($shipment->total_harga) }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('sugar-cane.edit', $shipment->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph-fill ph-pencil-simple text-xl"></i>
                                </a>
                                <form action="{{ route('sugar-cane.destroy', $shipment->id) }}" method="POST" class="inline">
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
                        <td colspan="8" class="py-6 text-center text-gray-500">Tidak ada data pengiriman tebu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-gray-600">
                Menampilkan {{ $shipments->firstItem() ?? 0 }} sampai {{ $shipments->lastItem() ?? 0 }} dari {{ $shipments->total() }} data
            </div>
            <div class="flex items-center space-x-2">
                {{ $shipments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart pengirim
        const pengirimData = @json($pengirimData);
        
        // Cek apakah ada data pengirim
        if (pengirimData && pengirimData.length > 0) {
            const pengirimLabels = pengirimData.map(item => item.nama_pengirim);
            const pengirimValues = pengirimData.map(item => item.total);
            const pengirimColors = [
                '#4F86F7', // Biru
                '#FF6B6B', // Merah
                '#4CAF50', // Hijau
                '#FFA500', // Oranye
                '#9C27B0'  // Ungu
            ];

            // Chart Pengirim
            const pengirimCtx = document.getElementById('pengirimChart').getContext('2d');
            new Chart(pengirimCtx, {
                type: 'pie',
                data: {
                    labels: pengirimLabels,
                    datasets: [{
                        data: pengirimValues,
                        backgroundColor: pengirimColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        } else {
            // Tampilkan pesan jika tidak ada data
            document.getElementById('pengirimChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Tidak ada data pengirim</div>';
        }

        // Data untuk chart jenis tebu
        const jenisTebuData = @json($jenisTebuData);
        
        // Cek apakah ada data jenis tebu
        if (jenisTebuData && jenisTebuData.length > 0) {
            const jenisTebuLabels = jenisTebuData.map(item => item.jenis_tebu);
            const jenisTebuValues = jenisTebuData.map(item => item.total);
            const jenisTebuColors = [
                '#36A2EB', // Biru
                '#FF6384', // Merah
                '#4BC0C0', // Hijau
                '#FFCE56', // Kuning
                '#9966FF'  // Ungu
            ];

            // Chart Jenis Tebu
            const jenisTebuCtx = document.getElementById('jenisTebuChart').getContext('2d');
            new Chart(jenisTebuCtx, {
                type: 'pie',
                data: {
                    labels: jenisTebuLabels,
                    datasets: [{
                        data: jenisTebuValues,
                        backgroundColor: jenisTebuColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        } else {
            // Tampilkan pesan jika tidak ada data
            document.getElementById('jenisTebuChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Tidak ada data jenis tebu</div>';
        }
    });
</script>
<!-- Modal Filter Script -->
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk dropdown pengirim
        $('.select2-pengirim').select2({
            placeholder: "Pilih pengirim...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#filterModal')
        });
        
        const openFilterBtn = document.getElementById('openFilterModal');
        const filterModal = document.getElementById('filterModal');
        const cancelFilterBtn = document.getElementById('cancelFilterBtn');
        
        // Buka modal saat tombol filter diklik
        openFilterBtn.addEventListener('click', function() {
            console.log('Filter button clicked'); // Debugging
            filterModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Mencegah scroll pada body
            
            // Reinisialisasi Select2 saat modal dibuka
            $('.select2-pengirim').select2({
                placeholder: "Pilih pengirim...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#filterModal')
            });
        });
        
        // Tutup modal saat tombol Batal diklik
        cancelFilterBtn.addEventListener('click', function() {
            filterModal.classList.add('hidden');
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
        
        // Toggle dropdown untuk Jenis Tebu
        const jenisTebuHeader = document.getElementById('jenisTebuHeader');
        const jenisTebuContent = document.getElementById('jenisTebuContent');
        const jenisTebuIcon = document.getElementById('jenisTebuIcon');
        
        jenisTebuHeader.addEventListener('click', function() {
            jenisTebuContent.classList.toggle('hidden');
            jenisTebuIcon.classList.toggle('rotate-180');
        });
        
        // Toggle dropdown untuk Pengirim
        const pengirimHeader = document.getElementById('pengirimHeader');
        const pengirimContent = document.getElementById('pengirimContent');
        const pengirimIcon = document.getElementById('pengirimIcon');
        
        pengirimHeader.addEventListener('click', function() {
            pengirimContent.classList.toggle('hidden');
            pengirimIcon.classList.toggle('rotate-180');
        });

        // Secara default, semua konten filter tertutup
        tanggalContent.classList.add('hidden');
        jenisTebuContent.classList.add('hidden');
        pengirimContent.classList.add('hidden');
    });
</script>
@endsection