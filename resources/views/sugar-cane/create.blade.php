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
                    <select id="nama_pengirim" name="nama_pengirim" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                        <option value="">Pilih atau ketik nama pengirim...</option>
                        @foreach($existingSenders as $sender)
                            <option value="{{ $sender }}" {{ old('nama_pengirim') == $sender ? 'selected' : '' }}>{{ $sender }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500 mt-1 block">Ketik untuk mencari nama yang sudah ada atau tambahkan nama baru</small>
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

                <!-- Hapus field jumlah sak dan ganti dengan bobot -->
                <div>
                    <label for="bobot_kg" class="block text-gray-700 mb-2">Bobot</label>
                    <div class="relative">
                        <input type="number" id="bobot_kg" name="bobot_kg" value="{{ old('bobot_kg') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1">
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <span class="text-gray-500">kg</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="harga_per_kg" class="block text-gray-700 mb-2">Harga (per kg)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <input type="number" id="harga_per_kg" name="harga_per_kg" value="{{ old('harga_per_kg') }}" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    </div>
                </div>
                
                <!-- Hapus informasi bobot otomatis -->

                <!-- Tombol tambah yang di atas dihapus -->
                
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('sugar-cane.index') }}" class="px-6 py-2 bg-white text-brown-500 border-2 border-brown-500 rounded-md hover:bg-brown-50">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 dengan autocomplete
    $('#nama_pengirim').select2({
        placeholder: 'Pilih atau ketik nama pengirim...',
        allowClear: true,
        tags: true, // Memungkinkan menambah nilai baru
        tokenSeparators: [','],
        ajax: {
            url: '{{ route("sugar-cane.senders") }}',
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
            var existingOptions = $('#nama_pengirim option').map(function() {
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
        var selectedValue = $('#nama_pengirim').val();
        var existingValues = @json($existingSenders->map(function($item) { return strtolower($item); }));
        
        if (selectedValue && existingValues.includes(selectedValue.toLowerCase())) {
            // Jika nama sudah ada, tetap lanjutkan (tidak perlu mencegah)
            return true;
        }
        
        // Konfirmasi jika menambah nama baru
        if (selectedValue && !existingValues.includes(selectedValue.toLowerCase())) {
            return confirm('Anda akan menambahkan nama pengirim baru: "' + selectedValue + '". Lanjutkan?');
        }
    });
});
</script>
@endsection