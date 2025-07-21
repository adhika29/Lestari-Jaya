@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('sugar-output.index') }}" class="mr-4">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Gula Keluar</h1>
        </div>

        <form action="{{ route('sugar-output.store') }}" method="POST">
            @csrf
            
            <!-- Tanggal -->
            <div class="mb-4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" 
                       id="tanggal" 
                       name="tanggal" 
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror" 
                       required>
                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Pembeli dengan Search dan Add New -->
            <div class="mb-4">
                <label for="nama_pembeli" class="block text-sm font-medium text-gray-700 mb-2">Nama Pembeli</label>
                <div class="relative">
                    <input type="text" 
                           id="nama_pembeli" 
                           name="nama_pembeli" 
                           value="{{ old('nama_pembeli') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_pembeli') border-red-500 @enderror" 
                           placeholder="Ketik nama pembeli atau pilih dari daftar"
                           autocomplete="off"
                           required>
                    
                    <!-- Dropdown untuk suggestions -->
                    <div id="pembeli-dropdown" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 hidden max-h-60 overflow-y-auto">
                        <div id="pembeli-list"></div>
                        <div id="add-new-pembeli" class="px-3 py-2 text-blue-600 hover:bg-blue-50 cursor-pointer border-t border-gray-200 hidden">
                            <i class="fas fa-plus mr-2"></i>Tambah pembeli baru: "<span id="new-pembeli-text"></span>"
                        </div>
                    </div>
                </div>
                @error('nama_pembeli')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jumlah Sak -->
            <div class="mb-4">
                <label for="sak" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Sak</label>
                <input type="number" 
                       id="sak" 
                       name="sak" 
                       value="{{ old('sak') }}"
                       min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sak') border-red-500 @enderror" 
                       required>
                @error('sak')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga per Kg -->
            <div class="mb-4">
                <label for="harga_per_kg" class="block text-sm font-medium text-gray-700 mb-2">Harga per Kg</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                    <input type="number" 
                           id="harga_per_kg" 
                           name="harga_per_kg" 
                           value="{{ old('harga_per_kg') }}"
                           min="1"
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_per_kg') border-red-500 @enderror" 
                           required>
                </div>
                @error('harga_per_kg')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Bobot -->
            <div class="mb-6 p-4 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-600">
                    <strong>Bobot akan dihitung otomatis:</strong> <span id="bobot-display">0 kg</span><br>
                    <span class="text-xs">1 sak = 50 kg</span>
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('sugar-output.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Data pembeli yang sudah ada
const pembeliData = @json($pembeliData ?? []);

// Elements
const namaPembeliInput = document.getElementById('nama_pembeli');
const pembeliDropdown = document.getElementById('pembeli-dropdown');
const pembeliList = document.getElementById('pembeli-list');
const addNewPembeli = document.getElementById('add-new-pembeli');
const newPembeliText = document.getElementById('new-pembeli-text');
const sakInput = document.getElementById('sak');
const bobotDisplay = document.getElementById('bobot-display');

// Fungsi untuk menampilkan dropdown
function showDropdown() {
    pembeliDropdown.classList.remove('hidden');
}

// Fungsi untuk menyembunyikan dropdown
function hideDropdown() {
    setTimeout(() => {
        pembeliDropdown.classList.add('hidden');
    }, 200);
}

// Fungsi untuk filter dan tampilkan pembeli
function filterPembeli(searchTerm) {
    pembeliList.innerHTML = '';
    
    if (searchTerm.length === 0) {
        // Tampilkan semua pembeli jika tidak ada search term
        pembeliData.forEach(pembeli => {
            const item = createPembeliItem(pembeli);
            pembeliList.appendChild(item);
        });
        addNewPembeli.classList.add('hidden');
    } else {
        // Filter pembeli berdasarkan search term
        const filteredPembeli = pembeliData.filter(pembeli => 
            pembeli.toLowerCase().includes(searchTerm.toLowerCase())
        );
        
        filteredPembeli.forEach(pembeli => {
            const item = createPembeliItem(pembeli);
            pembeliList.appendChild(item);
        });
        
        // Tampilkan opsi "Tambah baru" jika tidak ada yang cocok
        if (filteredPembeli.length === 0 || !pembeliData.includes(searchTerm)) {
            newPembeliText.textContent = searchTerm;
            addNewPembeli.classList.remove('hidden');
        } else {
            addNewPembeli.classList.add('hidden');
        }
    }
}

// Fungsi untuk membuat item pembeli
function createPembeliItem(pembeli) {
    const div = document.createElement('div');
    div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer';
    div.textContent = pembeli;
    div.onclick = () => {
        namaPembeliInput.value = pembeli;
        hideDropdown();
    };
    return div;
}

// Event listeners untuk nama pembeli
namaPembeliInput.addEventListener('focus', () => {
    filterPembeli(namaPembeliInput.value);
    showDropdown();
});

namaPembeliInput.addEventListener('input', (e) => {
    filterPembeli(e.target.value);
    showDropdown();
});

namaPembeliInput.addEventListener('blur', hideDropdown);

// Event listener untuk "Tambah baru"
addNewPembeli.addEventListener('click', () => {
    namaPembeliInput.value = newPembeliText.textContent;
    hideDropdown();
});

// Fungsi untuk menghitung bobot
function calculateBobot() {
    const sak = parseInt(sakInput.value) || 0;
    const bobot = sak * 50;
    bobotDisplay.textContent = bobot + ' kg';
}

// Event listener untuk perhitungan bobot
sakInput.addEventListener('input', calculateBobot);

// Hitung bobot saat halaman dimuat
calculateBobot();

// Hide dropdown saat klik di luar
document.addEventListener('click', (e) => {
    if (!namaPembeliInput.contains(e.target) && !pembeliDropdown.contains(e.target)) {
        pembeliDropdown.classList.add('hidden');
    }
});
</script>
@endsection