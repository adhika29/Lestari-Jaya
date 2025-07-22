@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="text-gray-600">Biaya Pengeluaran</span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Biaya Operasional</span>
        </div>
    </div>

    <!-- Biaya Operasional Table Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Biaya Operasional</h2>
            <a href="{{ route('biaya-operasional.create') }}" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="ph ph-plus mr-2"></i>
                Tambah Biaya Operasional
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="flex items-center mb-6">
            <div class="relative mr-4">
                <form action="{{ route('biaya-operasional.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Temukan data disini" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500 w-80">
                    <div class="absolute left-3 top-2.5">
                        <i class="ph ph-magnifying-glass text-gray-500 text-lg"></i>
                    </div>
                </form>
            </div>

            <form action="{{ route('biaya-operasional.index') }}" method="GET" class="flex items-center space-x-4">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="bulan" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <option value="">Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>

                <select name="tahun" class="border border-gray-300 rounded-lg py-2 focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <option value="">Tahun</option>
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>

                <button type="button" id="openFilterModal" class="bg-brown-500 text-white px-4 py-2 rounded-lg flex items-center hover:bg-brown-600">
                    <span>Filter</span>
                    <i class="ph-fill ph-funnel ml-2 text-lg"></i>
                </button>
            </form>

            <div class="ml-auto flex space-x-2">
                <a href="{{ route('biaya-operasional.export-pdf', request()->query()) }}" class="border border-red-500 text-red-500 px-4 py-2 rounded-lg flex items-center hover:bg-red-50">
                    <i class="ph-fill ph-file-pdf mr-2 text-lg"></i>
                    Ekspor PDF
                </a>
                <!-- Tombol ekspor Excel dihapus -->
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
                    @forelse($biayaOperasional as $index => $biaya)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $biayaOperasional->firstItem() + $index }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($biaya->tanggal)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">{{ $biaya->keterangan }}</td>
                        <td class="py-3 px-4">{{ $biaya->volume }}</td>
                        <td class="py-3 px-4">{{ $biaya->satuan }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($biaya->harga) }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($biaya->total_harga) }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('biaya-operasional.edit', $biaya->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph-fill ph-pencil-simple text-xl"></i>
                                </a>
                                <form action="{{ route('biaya-operasional.destroy', $biaya->id) }}" method="POST" class="inline">
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
                        <td colspan="8" class="py-6 text-center text-gray-500">Tidak ada data biaya operasional</td>
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
                Menampilkan {{ $biayaOperasional->firstItem() ?? 0 }} sampai {{ $biayaOperasional->lastItem() ?? 0 }} dari {{ $biayaOperasional->total() }} data
            </div>
            <div class="flex items-center space-x-2">
                {{ $biayaOperasional->appends(request()->query())->links() }}
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
                    <a href="{{ route('biaya-operasional.index') }}" class="text-brown-600 hover:text-brown-800 font-medium">Reset Filter</a>
                    <button type="button" id="closeFilterModal" class="text-gray-500 hover:text-gray-700">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form action="{{ route('biaya-operasional.index') }}" method="GET">
                <!-- Hapus kode JavaScript berikut: -->
                <!-- Sampai di sini -->
                <!-- Tombol export dihapus -->
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
        // Inisialisasi Select2 untuk dropdown keterangan
        $('.select2-keterangan').select2({
            placeholder: "Pilih keterangan...",
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
        
        // Toggle dropdown untuk Keterangan
        const keteranganHeader = document.getElementById('keteranganHeader');
        const keteranganContent = document.getElementById('keteranganContent');
        const keteranganIcon = document.getElementById('keteranganIcon');

        if (keteranganHeader && keteranganContent && keteranganIcon) {
            keteranganHeader.addEventListener('click', function() {
                keteranganContent.classList.toggle('hidden');
                keteranganIcon.classList.toggle('rotate-180');
            });
        }

        // Secara default, konten filter tertutup
        if (tanggalContent) {
            tanggalContent.classList.add('hidden');
        }
        if (keteranganContent) {
            keteranganContent.classList.add('hidden');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when bulan or tahun is changed
        const bulanSelect = document.querySelector('select[name="bulan"]');
        const tahunSelect = document.querySelector('select[name="tahun"]');
        const filterForm = document.querySelector('form.flex.items-center.space-x-4');
        
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
    });
</script>
@endsection