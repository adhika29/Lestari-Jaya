@extends('layouts.app')

@section('content')
<!-- Tambahkan jQuery dan Select2 CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Custom CSS untuk menyesuaikan tema brown -->
<style>
    .select2-container--default .select2-selection--single {
        background-color: white;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        height: 42px;
        padding: 8px 12px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #374151;
        line-height: 26px;
        padding-left: 0;
        padding-right: 20px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 8px;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #6D4534;
        outline: none;
        box-shadow: 0 0 0 2px rgba(109, 69, 52, 0.2);
    }
    
    .select2-dropdown {
        border: 1px solid #6D4534;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #6D4534;
        color: white;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #E6D7D0;
        color: #6D4534;
    }
    
    .select2-container--default .select2-results__option {
        padding: 8px 12px;
    }
    
    .select2-container--default .select2-results__option:hover {
        background-color: #f3f4f6;
    }
    
    /* Styling untuk tag baru - tanpa background putih */
    .select2-results__option--new-tag {
        color: #8B4513 !important; /* Warna coklat sedang */
        font-weight: bold;
        background-color: transparent !important; /* Hilangkan background putih */
        border-left: 3px solid #6D4534; /* Border kiri coklat */
        padding-left: 12px; /* Tambah padding untuk border */
    }
    
    .select2-container {
        width: 100% !important;
    }
</style>

<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('sugar-output.index') }}" class="mr-4">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Gula Keluar</h1>
        </div>

        <form action="{{ route('sugar-output.update', $sugarOutput) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Tanggal -->
            <div class="mb-4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" 
                       id="tanggal" 
                       name="tanggal" 
                       value="{{ old('tanggal', $sugarOutput->tanggal) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500 @error('tanggal') border-red-500 @enderror" 
                       required>
                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Pembeli dengan Select2 -->
            <div class="mb-4">
                <label for="nama_pembeli" class="block text-sm font-medium text-gray-700 mb-2">Nama Pembeli</label>
                <select id="nama_pembeli" name="nama_pembeli" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    <option value="">Pilih atau ketik nama pembeli...</option>
                    @foreach($pembeliData as $pembeli)
                        <option value="{{ $pembeli }}" {{ old('nama_pembeli', $sugarOutput->nama_pembeli) == $pembeli ? 'selected' : '' }}>{{ $pembeli }}</option>
                    @endforeach
                    @if(!in_array(old('nama_pembeli', $sugarOutput->nama_pembeli), $pembeliData))
                        <option value="{{ old('nama_pembeli', $sugarOutput->nama_pembeli) }}" selected>{{ old('nama_pembeli', $sugarOutput->nama_pembeli) }}</option>
                    @endif
                </select>
                <small class="text-gray-500 mt-1 block">Ketik untuk mencari nama yang sudah ada atau tambahkan nama baru</small>
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
                       value="{{ old('sak', $sugarOutput->sak) }}"
                       min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500 @error('sak') border-red-500 @enderror" 
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
                           value="{{ old('harga_per_kg', $sugarOutput->harga_per_kg) }}"
                           min="1"
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500 @error('harga_per_kg') border-red-500 @enderror" 
                           required>
                </div>
                @error('harga_per_kg')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informasi Total Harga-->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg mb-4" style="background-color: #EFEBEA;">
                    <strong>Total Harga:</strong> <span id="total-harga-display">Rp {{ number_format($sugarOutput->total_harga, 0, ',', '.') }}</span><br>
                </div>

            <!-- Informasi bobot otomatis -->
                <div class="w-fit border-t-4 border-brown-500 p-4 rounded-lg mb-6" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Bobot akan dihitung otomatis: <span id="bobot-display" class="font-semibold">0 kg</span></p>
                    <p class="text-xs text-gray-600 mt-1">1 sak = 50 kg</p>
                </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('sugar-input.index') }}" 
                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 transition duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 dengan autocomplete
    $('#nama_pembeli').select2({
        placeholder: 'Pilih atau ketik nama pembeli...',
        allowClear: true,
        tags: true, // Memungkinkan menambah nilai baru
        tokenSeparators: [','],
        ajax: {
            url: '{{ route("sugar-output.buyers") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // parameter pencarian
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item,
                            text: item
                        };
                    })
                };
            },
            cache: true
        },
        createTag: function (params) {
            var term = $.trim(params.term);
            
            if (term === '') {
                return null;
            }
            
            // Cek apakah nama sudah ada (case insensitive)
            var existingOptions = $('#nama_pembeli option').map(function() {
                return $(this).val().toLowerCase();
            }).get();
            
            if (existingOptions.indexOf(term.toLowerCase()) !== -1) {
                return null; // Jangan buat tag baru jika sudah ada
            }
            
            return {
                id: term,
                text: term + ' (Nama Baru)',
                newTag: true
            };
        },
        templateResult: function(data) {
            if (data.newTag) {
                return $('<span class="select2-results__option--new-tag">' + data.text + '</span>');
            }
            return data.text;
        }
    });
    
    // Validasi sebelum submit untuk mencegah duplikasi
    $('form').on('submit', function(e) {
        var selectedValue = $('#nama_pembeli').val();
        var existingValues = @json(collect($pembeliData ?? [])->map(function($item) { return strtolower($item); }));
        
        if (selectedValue && existingValues.includes(selectedValue.toLowerCase())) {
            // Jika nama sudah ada, tetap lanjutkan (tidak perlu mencegah)
            return true;
        }
        
        // Konfirmasi jika menambah nama baru
        if (selectedValue && !existingValues.includes(selectedValue.toLowerCase())) {
            return confirm('Anda akan menambahkan nama pembeli baru: "' + selectedValue + '". Lanjutkan?');
        }
    });
    
    // Fungsi untuk menghitung bobot dan total harga
    function calculateValues() {
        const sak = parseInt($('#sak').val()) || 0;
        const hargaPerKg = parseInt($('#harga_per_kg').val()) || 0;
        const bobot = sak * 50;
        const totalHarga = bobot * hargaPerKg;
        
        $('#bobot-display').text(bobot + ' kg');
        $('#total-harga-display').text('Rp ' + totalHarga.toLocaleString('id-ID'));
    }
    
    // Event listeners untuk perhitungan
    $('#sak, #harga_per_kg').on('input', calculateValues);
    
    // Hitung nilai saat halaman dimuat
    calculateValues();
});
</script>
@endsection