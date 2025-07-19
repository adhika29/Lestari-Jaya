@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="text-gray-600">Karyawan</span>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-800 font-medium">Edit Gaji Karyawan</span>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center mb-6">
            <a href="{{ route('gaji-karyawan.index') }}" class="text-brown-500 hover:text-brown-700 mr-4">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-semibold">Edit Data Gaji Karyawan</h2>
        </div>
        <hr class="border-t border-gray-300 mb-6">

        <form action="{{ route('gaji-karyawan.update', $gajiKaryawan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $gajiKaryawan->tanggal->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sak -->
                <div>
                    <label for="sak" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Sak <span class="text-red-500">*</span></label>
                    <input type="number" name="sak" id="sak" value="{{ old('sak', $gajiKaryawan->sak) }}" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500" required>
                    @error('sak')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hidden field untuk bobot yang akan dihitung otomatis -->
                <input type="hidden" name="bobot_kg" id="bobot_kg" value="{{ old('bobot_kg', $gajiKaryawan->bobot_kg) }}">

                <!-- Gaji Per Ton -->
                <div>
                    <label for="gaji_per_ton" class="block text-sm font-medium text-gray-700 mb-1">Gaji Per Ton <span class="text-red-500">*</span></label>
                    <input type="number" name="gaji_per_ton" id="gaji_per_ton" value="{{ old('gaji_per_ton', $gajiKaryawan->gaji_per_ton) }}" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500" required>
                    @error('gaji_per_ton')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Informasi Karyawan Aktif -->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Karyawan aktif yang akan menerima gaji: <span class="font-semibold">{{ \App\Models\Karyawan::where('status_aktif', true)->count() }} orang</span></p>
                    <p class="text-xs text-gray-600 mt-1">Data karyawan diambil otomatis dari karyawan dengan status aktif</p>
                </div>
                
                <!-- Informasi bobot otomatis -->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Bobot akan dihitung otomatis: <span id="bobot-display" class="font-semibold">{{ number_format($gajiKaryawan->bobot_kg, 0, ',', '.') }} kg</span></p>
                    <p class="text-xs text-gray-600 mt-1">1 sak = 50 kg</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('gaji-karyawan.index') }}" class="border border-brown-500 text-brown-500 hover:text-brown-700 hover:border-brown-700 px-4 py-2 rounded-lg mr-4">Batal</a>
                <button type="submit" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hitung bobot otomatis
    const sakInput = document.getElementById('sak');
    const bobotInput = document.getElementById('bobot_kg');
    const bobotDisplay = document.getElementById('bobot-display');
    
    function updateBobot() {
        const sak = parseInt(sakInput.value) || 0;
        const bobot = sak * 50; // 1 sak = 50 kg
        bobotInput.value = bobot;
        bobotDisplay.textContent = bobot.toLocaleString() + ' kg';
    }
    
    sakInput.addEventListener('input', updateBobot);
});
</script>
@endpush