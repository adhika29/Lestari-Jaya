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
                    <a href="{{ route('biaya-operasional.index') }}" class="text-gray-700 hover:text-blue-600">Biaya Operasional</a>
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
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Biaya Operasional</h1>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4">
            <form action="{{ route('biaya-operasional.update', $biayaOperasional) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $biayaOperasional->tanggal->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror" required>
                        @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $biayaOperasional->keterangan) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('keterangan') border-red-500 @enderror" required>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="volume" class="block text-sm font-medium text-gray-700 mb-2">Volume</label>
                        <input type="number" name="volume" id="volume" value="{{ old('volume', $biayaOperasional->volume) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('volume') border-red-500 @enderror" required min="1" step="any">
                        @error('volume')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                        <select name="satuan" id="satuan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('satuan') border-red-500 @enderror" required>
                            <option value="" disabled>Pilih satuan</option>
                            <option value="Sak" {{ old('satuan', $biayaOperasional->satuan) == 'Sak' ? 'selected' : '' }}>Sak</option>
                            <option value="rit" {{ old('satuan', $biayaOperasional->satuan) == 'rit' ? 'selected' : '' }}>rit</option>
                            <option value="paket" {{ old('satuan', $biayaOperasional->satuan) == 'paket' ? 'selected' : '' }}>paket</option>
                            <option value="kg" {{ old('satuan', $biayaOperasional->satuan) == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="unit" {{ old('satuan', $biayaOperasional->satuan) == 'unit' ? 'selected' : '' }}>unit</option>
                        </select>
                        @error('satuan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $biayaOperasional->harga) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga') border-red-500 @enderror" required min="0">
                        @error('harga')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_harga" class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                        <input type="number" name="total_harga" id="total_harga" value="{{ old('total_harga', $biayaOperasional->total_harga) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('total_harga') border-red-500 @enderror" required min="0" readonly>
                        @error('total_harga')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('biaya-operasional.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
// Auto calculate total harga
document.addEventListener('DOMContentLoaded', function() {
    const volumeInput = document.getElementById('volume');
    const hargaInput = document.getElementById('harga');
    const totalHargaInput = document.getElementById('total_harga');

    function calculateTotal() {
        const volume = parseFloat(volumeInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const total = volume * harga;
        totalHargaInput.value = Math.round(total);
    }

    volumeInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);
    
    // Calculate on page load
    calculateTotal();
});
</script>
@endsection