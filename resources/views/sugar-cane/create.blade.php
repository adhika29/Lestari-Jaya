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
            <a href="{{ route('sugar-cane.index') }}" class="hover:text-brown-500">Pencatatan pengiriman tebu</a>
            <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-brown-500 font-medium">Tambah Pemasukan</span>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('sugar-cane.index') }}" class="text-brown-500 hover:text-brown-700 mr-4">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <h2 class="text-xl font-semibold">Tambah Pemasukan</h2>
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

        <form action="{{ route('sugar-cane.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="nama_pengirim" class="block text-gray-700 mb-2">Nama Pengirim</label>
                    <input type="text" id="nama_pengirim" name="nama_pengirim" value="{{ old('nama_pengirim') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="jenis_tebu" class="block text-gray-700 mb-2">Jenis Tebu</label>
                    <div class="relative">
                        <select id="jenis_tebu" name="jenis_tebu" class="appearance-none w-full px-4 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                            <option value="" disabled selected>Pilih jenis tebu</option>
                            <option value="Cening (CN)" {{ old('jenis_tebu') == 'Cening (CN)' ? 'selected' : '' }}>Cening (CN)</option>
                            <option value="Bululawang (BL)" {{ old('jenis_tebu') == 'Bululawang (BL)' ? 'selected' : '' }}>Bululawang (BL)</option>
                            <option value="Baru Rakyat (BR)" {{ old('jenis_tebu') == 'Baru Rakyat (BR)' ? 'selected' : '' }}>Baru Rakyat (BR)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">    
                        </div>
                    </div>
                </div>

                <div>
                    <label for="sak" class="block text-gray-700 mb-2">Jumlah Sak</label>
                    <input type="number" id="sak" name="sak" value="{{ old('sak') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1">
                </div>

                <!-- Hidden field untuk bobot yang akan dihitung otomatis -->
                <input type="hidden" id="bobot_kg" name="bobot_kg" value="{{ old('bobot_kg') }}">
                
                <div>
                    <label for="harga_per_kg" class="block text-gray-700 mb-2">Harga (per kg)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <input type="number" id="harga_per_kg" name="harga_per_kg" value="{{ old('harga_per_kg') }}" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    </div>
                </div>
                
                <!-- Informasi bobot otomatis -->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Bobot akan dihitung otomatis: <span id="bobot-display" class="font-semibold">0 kg</span></p>
                    <p class="text-xs text-gray-600 mt-1">1 sak = 50 kg</p>
                </div>

                <div class="flex justify-center mt-6">
                    <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto calculate bobot berdasarkan sak
document.addEventListener('DOMContentLoaded', function() {
    const sakInput = document.getElementById('sak');
    const bobotInput = document.getElementById('bobot_kg');
    const bobotDisplay = document.getElementById('bobot-display');
    
    function calculateBobot() {
        const sak = parseInt(sakInput.value) || 0;
        const bobot = sak * 50; // 1 sak = 50 kg
        
        bobotInput.value = bobot;
        bobotDisplay.textContent = bobot + ' kg';
    }
    
    sakInput.addEventListener('input', calculateBobot);
    
    // Calculate on page load if there's a value
    calculateBobot();
});
</script>
@endsection