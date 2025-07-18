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
            <a href="{{ route('gaji-karyawan.index') }}" class="hover:text-brown-500">Gaji Karyawan</a>
            <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-brown-500 font-medium">Tambah Gaji Karyawan</span>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('gaji-karyawan.index') }}" class="text-brown-500 hover:text-brown-700 mr-4">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <h2 class="text-xl font-semibold">Tambah Gaji Karyawan</h2>
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

        <form action="{{ route('gaji-karyawan.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sak -->
                <div>
                    <label for="sak" class="block text-gray-700 mb-2">Jumlah Sak <span class="text-red-500">*</span></label>
                    <input type="number" name="sak" id="sak" value="{{ old('sak') }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    @error('sak')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hidden field untuk bobot yang akan dihitung otomatis -->
                <input type="hidden" id="bobot_kg" name="bobot_kg" value="{{ old('bobot_kg') }}">

                <!-- Gaji Per Ton -->
                <div>
                    <label for="gaji_per_ton" class="block text-gray-700 mb-2">Gaji Per Ton <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <input type="number" name="gaji_per_ton" id="gaji_per_ton" value="{{ old('gaji_per_ton', 600000) }}" step="0.01" min="0" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    </div>
                    @error('gaji_per_ton')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Pilih Karyawan -->
                <div>
                    <label for="karyawan_ids" class="block text-gray-700 mb-2">Pilih Karyawan <span class="text-red-500">*</span></label>
                    <select name="karyawan_ids[]" id="karyawan_ids" class="select2 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" multiple required>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @error('karyawan_ids')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Informasi bobot otomatis -->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Bobot akan dihitung otomatis: <span id="bobot-display" class="font-semibold">0 kg</span></p>
                    <p class="text-xs text-gray-600 mt-1">1 sak = 50 kg</p>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <a href="{{ route('gaji-karyawan.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 mr-4">Batal</a>
                <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Select2
    $('.select2').select2({
        placeholder: "Pilih karyawan",
        allowClear: true,
        // Fungsi untuk memfilter opsi yang sudah dipilih
        matcher: function(params, data) {
            // Jika tidak ada pencarian, kembalikan semua opsi yang belum dipilih
            if ($.trim(params.term) === '') {
                // Dapatkan nilai yang sudah dipilih
                const selectedValues = $('#karyawan_ids').val() || [];
                
                // Jika opsi ini sudah dipilih, jangan tampilkan di dropdown
                if (selectedValues.includes(data.id)) {
                    return null;
                }
                
                return data;
            }
            
            // Jika ada pencarian, gunakan matcher bawaan Select2
            if (typeof $.fn.select2.defaults.defaults.matcher === 'function') {
                // Dapatkan nilai yang sudah dipilih
                const selectedValues = $('#karyawan_ids').val() || [];
                
                // Jika opsi ini sudah dipilih, jangan tampilkan di dropdown
                if (selectedValues.includes(data.id)) {
                    return null;
                }
                
                // Gunakan matcher bawaan untuk pencarian
                return $.fn.select2.defaults.defaults.matcher(params, data);
            }
            
            return data;
        }
    });
    
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
    
    // Update dropdown saat pilihan berubah
    const karyawanSelect = $('#karyawan_ids');
    
    karyawanSelect.on('select2:select select2:unselect', function(e) {
        // Refresh dropdown untuk memperbarui tampilan
        $(this).select2('close');
        setTimeout(function() {
            karyawanSelect.select2('open');
        }, 0);
    });
});
</script>
@endpush
@endsection