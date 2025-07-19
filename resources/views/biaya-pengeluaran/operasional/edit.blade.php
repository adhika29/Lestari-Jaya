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
            <a href="{{ route('biaya-operasional.index') }}" class="hover:text-brown-500">Biaya Pengeluaran</a>
            <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-brown-500 font-medium">Edit Data</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('biaya-operasional.index') }}" class="mr-4">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold">Edit Data Biaya Operasional</h2>
            </div>
            <hr class="border-t border-gray-300">
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('biaya-operasional.update', $biayaOperasional) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $biayaOperasional->tanggal->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="keterangan" class="block text-gray-700 mb-2">Keterangan</label>
                    <div class="relative">
                        <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan', $biayaOperasional->keterangan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required autocomplete="off">
                        <div id="keterangan-dropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto">
                            <div class="p-2 border-b border-gray-200">
                                <input type="text" id="keterangan-search" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" placeholder="Cari keterangan...">
                            </div>
                            <div id="keterangan-list">
                                @foreach($keteranganList ?? [] as $item)
                                    <div class="keterangan-item p-2 hover:bg-gray-100 cursor-pointer">{{ $item }}</div>
                                @endforeach
                            </div>
                            <div class="p-2 border-t border-gray-200">
                                <button type="button" id="add-new-keterangan" class="w-full px-3 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Keterangan Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="volume" class="block text-gray-700 mb-2">Volume</label>
                    <input type="number" id="volume" name="volume" value="{{ old('volume', (int)$biayaOperasional->volume) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1" step="any">
                </div>

                <div>
                    <label for="satuan" class="block text-gray-700 mb-2">Satuan</label>
                    <select id="satuan" name="satuan" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                        <option value="" disabled>Pilih satuan</option>
                        <option value="Sak" {{ old('satuan', $biayaOperasional->satuan) == 'Sak' ? 'selected' : '' }}>Sak</option>
                        <option value="rit" {{ old('satuan', $biayaOperasional->satuan) == 'rit' ? 'selected' : '' }}>rit</option>
                        <option value="paket" {{ old('satuan', $biayaOperasional->satuan) == 'paket' ? 'selected' : '' }}>paket</option>
                        <option value="porsi" {{ old('satuan', $biayaOperasional->satuan) == 'porsi' ? 'selected' : '' }}>porsi</option>
                        <option value="kg" {{ old('satuan', $biayaOperasional->satuan) == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="unit" {{ old('satuan', $biayaOperasional->satuan) == 'unit' ? 'selected' : '' }}>unit</option>
                    </select>
                </div>

                <div>
                    <label for="harga" class="block text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <input type="number" id="harga" name="harga" value="{{ old('harga', (int)$biayaOperasional->harga) }}" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="0" step="1">
                        <div class="absolute inset-y-0 left-0 flex items-center px-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden field untuk total harga yang akan dihitung otomatis -->
                <input type="hidden" id="total_harga" name="total_harga" value="{{ old('total_harga', $biayaOperasional->total_harga) }}">

                <!-- Tampilkan informasi total harga yang dihitung -->
                <div class="w-fit inline-block p-4 rounded-md border-t-4 border-brown-500" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Total harga akan dihitung otomatis: <span id="total-display" class="font-semibold">Rp 0</span></p>
                </div>

                <div class="flex justify-end mt-4">
                    <a href="{{ route('biaya-operasional.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 mr-4">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto calculate total harga berdasarkan volume dan harga
document.addEventListener('DOMContentLoaded', function() {
    const volumeInput = document.getElementById('volume');
    const hargaInput = document.getElementById('harga');
    const totalHargaInput = document.getElementById('total_harga');
    const totalDisplay = document.getElementById('total-display');

    function calculateTotal() {
        const volume = parseFloat(volumeInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const total = volume * harga;
        totalHargaInput.value = Math.round(total);
        totalDisplay.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }

    volumeInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);
    
    // Calculate on page load
    calculateTotal();
    
    // Keterangan dropdown functionality
    const keteranganInput = document.getElementById('keterangan');
    const keteranganDropdown = document.getElementById('keterangan-dropdown');
    const keteranganSearch = document.getElementById('keterangan-search');
    const keteranganItems = document.querySelectorAll('.keterangan-item');
    const addNewKeteranganBtn = document.getElementById('add-new-keterangan');
    
    // Show dropdown when input is focused
    keteranganInput.addEventListener('focus', function() {
        keteranganDropdown.classList.remove('hidden');
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!keteranganInput.contains(e.target) && !keteranganDropdown.contains(e.target)) {
            keteranganDropdown.classList.add('hidden');
        }
    });
    
    // Filter items when searching
    if (keteranganSearch) {
        keteranganSearch.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const keteranganList = document.getElementById('keterangan-list');
            const items = keteranganList.querySelectorAll('.keterangan-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    }
    
    // Select item when clicked
    keteranganItems.forEach(item => {
        item.addEventListener('click', function() {
            keteranganInput.value = this.textContent;
            keteranganDropdown.classList.add('hidden');
        });
    });
    
    // Add new keterangan
    if (addNewKeteranganBtn) {
        addNewKeteranganBtn.addEventListener('click', function() {
            const newValue = keteranganSearch.value.trim();
            if (newValue) {
                keteranganInput.value = newValue;
                keteranganDropdown.classList.add('hidden');
            } else {
                // If search is empty, use what's in the main input
                const mainInputValue = keteranganInput.value.trim();
                if (mainInputValue) {
                    // Keep the current value and just close the dropdown
                    keteranganDropdown.classList.add('hidden');
                } else {
                    // Focus on search to indicate user should type something
                    keteranganSearch.focus();
                }
            }
        });
    }
});
</script>
@endsection