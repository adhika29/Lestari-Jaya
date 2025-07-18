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
            <a href="{{ route('biaya-konsumsi.index') }}" class="hover:text-brown-500">Biaya Pengeluaran</a>
            <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-brown-500 font-medium">Tambah Biaya Konsumsi</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center mb-6">
            <a href="{{ route('biaya-konsumsi.index') }}" class="text-brown-500 hover:text-brown-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-semibold">Tambah Biaya Konsumsi</h2>
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

    <form action="{{ route('biaya-konsumsi.store') }}" method="POST">
        @csrf
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="keterangan" class="block text-gray-700 mb-2">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="volume" class="block text-gray-700 mb-2">Volume</label>
                    <input type="number" id="volume" name="volume" value="{{ old('volume') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1" step="any">
                </div>

                <div>
                    <label for="satuan" class="block text-gray-700 mb-2">Satuan</label>
                    <select id="satuan" name="satuan" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                        <option value="" disabled {{ old('satuan') ? '' : 'selected' }}>Pilih satuan</option>
                        <option value="Sak" {{ old('satuan') == 'Sak' ? 'selected' : '' }}>Sak</option>
                        <option value="rit" {{ old('satuan') == 'rit' ? 'selected' : '' }}>rit</option>
                        <option value="paket" {{ old('satuan') == 'paket' ? 'selected' : '' }}>paket</option>
                        <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>unit</option>
                    </select>
                </div>

                <div>
                    <label for="harga" class="block text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <input type="number" id="harga" name="harga" value="{{ old('harga') }}" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="0" step="1">
                        <div class="absolute inset-y-0 left-0 flex items-center px-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden field untuk total harga yang akan dihitung otomatis -->
                <input type="hidden" id="total_harga" name="total_harga" value="{{ old('total_harga') }}">

                <!-- Tampilkan informasi total harga yang dihitung -->
                <div class="inline-block p-4 rounded-md border-t-4 border-brown-500" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Total harga akan dihitung otomatis: <span id="total-display" class="font-semibold">Rp 0</span></p>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('biaya-konsumsi.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 mr-4">Batal</a>
                <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Simpan</button>
            </div>
        </div>
    </form>
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
        totalHargaInput.value = total;
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    volumeInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);
    
    // Calculate on page load
    calculateTotal();
});
</script>
@endsection