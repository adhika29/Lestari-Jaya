@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="hover:text-brown-500">Dashboard</a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <a href="{{ route('sugar-output.index') }}" class="text-gray-700 hover:text-blue-600">Gula Keluar</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-500">Edit Data</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Gula Keluar</h1>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4">
            <form action="{{ route('sugar-output.update', $sugarOutput) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $sugarOutput->tanggal->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror" required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_pembeli" class="block text-sm font-medium text-gray-700 mb-2">Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" id="nama_pembeli" value="{{ old('nama_pembeli', $sugarOutput->nama_pembeli) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_pembeli') border-red-500 @enderror" required>
                        @error('nama_pembeli')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sak" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Sak</label>
                        <input type="number" name="sak" id="sak" value="{{ old('sak', $sugarOutput->sak) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sak') border-red-500 @enderror" required min="1">
                        @error('sak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden field untuk bobot -->
                    <input type="hidden" name="bobot" id="bobot" value="{{ old('bobot', $sugarOutput->bobot) }}">

                    <!-- Tampilkan informasi bobot -->
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-sm text-gray-600">Bobot: <span id="bobot-display" class="font-semibold">{{ $sugarOutput->bobot }} kg</span></p>
                        <p class="text-xs text-gray-500 mt-1">1 sak = 50 kg</p>
                    </div>

                    <div>
                        <label for="harga_per_kg" class="block text-sm font-medium text-gray-700 mb-2">Harga per Kg</label>
                        <input type="number" name="harga_per_kg" id="harga_per_kg" value="{{ old('harga_per_kg', $sugarOutput->harga_per_kg) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_per_kg') border-red-500 @enderror" required min="0">
                        @error('harga_per_kg')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_harga" class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                        <input type="number" name="total_harga" id="total_harga" value="{{ old('total_harga', $sugarOutput->total_harga) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('total_harga') border-red-500 @enderror" required min="0" readonly>
                        @error('total_harga')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('sugar-output.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto calculate bobot dan total harga
document.addEventListener('DOMContentLoaded', function() {
    const sakInput = document.getElementById('sak');
    const bobotInput = document.getElementById('bobot');
    const bobotDisplay = document.getElementById('bobot-display');
    const hargaPerKgInput = document.getElementById('harga_per_kg');
    const totalHargaInput = document.getElementById('total_harga');

    function calculateBobot() {
        const sak = parseInt(sakInput.value) || 0;
        const bobot = sak * 50; // 1 sak = 50kg
        bobotInput.value = bobot;
        bobotDisplay.textContent = bobot + ' kg';
        calculateTotal();
    }

    function calculateTotal() {
        const bobot = parseFloat(bobotInput.value) || 0;
        const hargaPerKg = parseFloat(hargaPerKgInput.value) || 0;
        const total = bobot * hargaPerKg;
        totalHargaInput.value = Math.round(total);
    }

    sakInput.addEventListener('input', calculateBobot);
    hargaPerKgInput.addEventListener('input', calculateTotal);
    
    // Calculate on page load
    calculateBobot();
});
</script>
@endsection