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
            <a href="{{ route('sugar-output.index') }}" class="hover:text-brown-500">Pelaporan Gula</a>
            <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-brown-500 font-medium">Tambah Gula Keluar</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center mb-6">
            <a href="{{ route('sugar-output.index') }}" class="text-brown-500 hover:text-brown-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-semibold">Tambah Gula Keluar</h2>
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

    <form action="{{ route('sugar-output.store') }}" method="POST">
        @csrf
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="space-y-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="nama_pembeli" class="block text-gray-700 mb-2">Nama Pembeli</label>
                    <input type="text" id="nama_pembeli" name="nama_pembeli" value="{{ old('nama_pembeli') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="sak" class="block text-gray-700 mb-2">Jumlah Sak</label>
                    <input type="number" id="sak" name="sak" value="{{ old('sak') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1">
                </div>

                <!-- Hidden field untuk bobot yang akan dihitung otomatis -->
                <input type="hidden" id="bobot" name="bobot" value="{{ old('bobot') }}">

                <div>
                    <label for="harga_per_kg" class="block text-gray-700 mb-2">Harga per Kg</label>
                    <div class="relative">
                        <input type="number" id="harga_per_kg" name="harga_per_kg" value="{{ old('harga_per_kg') }}" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="0" step="1">
                        <div class="absolute inset-y-0 left-0 flex items-center px-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                    </div>
                </div>

                <!-- Tampilkan informasi bobot yang dihitung -->
                <div class="inline-block p-4 rounded-md border-t-4 border-brown-500" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Bobot akan dihitung otomatis: <span id="bobot-display" class="font-semibold">0 kg</span></p>
                    <p class="text-xs text-gray-600 mt-1">1 sak = 50 kg</p>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('sugar-output.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 mr-4">Batal</a>
                <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Simpan</button>
            </div>
        </div>
    </form>
</div>

<script>
// Auto calculate bobot berdasarkan sak
document.addEventListener('DOMContentLoaded', function() {
    const sakInput = document.getElementById('sak');
    const bobotInput = document.getElementById('bobot');
    const bobotDisplay = document.getElementById('bobot-display');

    function calculateBobot() {
        const sak = parseInt(sakInput.value) || 0;
        const bobot = sak * 50; // 1 sak = 50kg
        bobotInput.value = bobot;
        bobotDisplay.textContent = bobot + ' kg';
    }

    sakInput.addEventListener('input', calculateBobot);
    
    // Calculate on page load
    calculateBobot();
});
</script>
@endsection