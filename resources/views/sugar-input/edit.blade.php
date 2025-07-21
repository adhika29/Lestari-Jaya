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
            <a href="{{ route('sugar-input.index') }}" class="hover:text-brown-500">Pelaporan Gula</a>
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
                <a href="{{ route('sugar-input.index') }}" class="mr-4">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold">Edit Data Gula Masuk</h2>
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

        <form action="{{ route('sugar-input.update', $sugarInput->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $sugarInput->tanggal->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="sak" class="block text-gray-700 mb-2">Jumlah Sak</label>
                    <input type="number" id="sak" name="sak" value="{{ old('sak', $sugarInput->sak) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1">
                </div>

                <!-- Hidden field untuk bobot -->
                <input type="hidden" id="bobot" name="bobot" value="{{ old('bobot', $sugarInput->bobot) }}">
                
                <!-- Informasi bobot otomatis -->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Bobot akan dihitung otomatis: <span id="bobot-display" class="font-semibold">{{ $sugarInput->bobot }} kg</span></p>
                    <p class="text-xs text-gray-600 mt-1">1 sak = 50 kg</p>
                </div>

                <div class="flex justify-end mt-4">
                    <a href="{{ route('sugar-input.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 mr-4">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto calculate bobot berdasarkan sak
document.addEventListener('DOMContentLoaded', function() {
    const sakInput = document.getElementById('sak');
    const bobotInput = document.getElementById('bobot');
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