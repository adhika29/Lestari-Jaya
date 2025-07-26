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
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #6D4534;
        color: white;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #A0522D;
        color: white;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #6D4534;
        border-radius: 0.375rem;
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #6D4534;
        outline: none;
        box-shadow: 0 0 0 2px rgba(109, 69, 52, 0.2);
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
            <a href="{{ route('biaya-operasional.index') }}" class="hover:text-brown-500">Biaya Pengeluaran</a>
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
                <a href="{{ route('biaya-operasional.index') }}" class="mr-4">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold">Edit Data Biaya Operasional</h2>
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

        <form action="{{ route('biaya-operasional.update', $biayaOperasional) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $biayaOperasional->tanggal->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                </div>

                <div>
                    <label for="keterangan" class="block text-gray-700 mb-2">Keterangan</label>
                    <select id="keterangan" name="keterangan" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                        <option value="">Pilih atau ketik keterangan...</option>
                        @foreach($keteranganList ?? [] as $item)
                            <option value="{{ $item }}" {{ old('keterangan', $biayaOperasional->keterangan) == $item ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                        @if(!in_array(old('keterangan', $biayaOperasional->keterangan), ($keteranganList ?? collect())->toArray()))
                            <option value="{{ old('keterangan', $biayaOperasional->keterangan) }}" selected>{{ old('keterangan', $biayaOperasional->keterangan) }}</option>
                        @endif
                    </select>
                    <small class="text-gray-500 mt-1 block">Ketik untuk mencari keterangan yang sudah ada atau tambahkan keterangan baru</small>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="volume" class="block text-gray-700 mb-2">Volume</label>
                    <input type="number" id="volume" name="volume" value="{{ old('volume', (int)$biayaOperasional->volume) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="1" step="any">
                </div>

                <div>
                    <label for="satuan" class="block text-gray-700 mb-2">Satuan</label>
                    <select id="satuan" name="satuan" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                        <option value="" disabled>Pilih satuan</option>
                        <option value="Sak" {{ old('satuan', $biayaOperasional->satuan) == 'Sak' ? 'selected' : '' }}>Sak</option>
                        <option value="rit" {{ old('satuan', $biayaOperasional->satuan) == 'rit' ? 'selected' : '' }}>rit</option>
                        <option value="paket" {{ old('satuan', $biayaOperasional->satuan) == 'paket' ? 'selected' : '' }}>paket</option>
                        <option value="porsi" {{ old('satuan', $biayaOperasional->satuan) == 'porsi' ? 'selected' : '' }}>porsi</option>
                        <option value="kg" {{ old('satuan', $biayaOperasional->satuan) == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="unit" {{ old('satuan', $biayaOperasional->satuan) == 'unit' ? 'selected' : '' }}>unit</option>
                    </select>
                </div>

                <div>
                    <label for="harga" class="block text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <input type="number" id="harga" name="harga" value="{{ old('harga', (int)$biayaOperasional->harga) }}" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required min="0" step="1">
                        <div class="absolute inset-y-0 left-0 flex items-center px-3 pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden field untuk total harga yang akan dihitung otomatis -->
                <input type="hidden" id="total_harga" name="total_harga" value="{{ old('total_harga', $biayaOperasional->total_harga) }}">

                <!-- Tampilkan informasi total harga yang dihitung -->
                <div class="w-fit inline-block p-4 rounded-md border-t-4 border-brown-500" style="background-color: #EFEBEA;">
                    <p class="text-sm text-gray-700">Total harga akan dihitung otomatis: <span id="total-display" class="font-semibold">Rp 0</span></p>
                </div>

                <div class="flex justify-end mt-4">
                    <a href="{{ route('biaya-operasional.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 mr-4">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto calculate total harga berdasarkan volume dan harga
document.addEventListener('DOMContentLoaded', function() {
    const volumeInput = document.getElementById('volume');
    const hargaInput = document.getElementById('harga');
    const totalHargaInput = document.getElementById('total_harga');
    const totalDisplay = document.getElementById('total-display');

    function calculateTotal() {
        const volume = parseFloat(volumeInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const total = volume * harga;
        totalHargaInput.value = Math.round(total);
        totalDisplay.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }

    volumeInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);
    
    // Calculate on page load
    calculateTotal();
});

$(document).ready(function() {
    // Inisialisasi Select2 dengan autocomplete untuk keterangan
    $('#keterangan').select2({
        placeholder: 'Pilih atau ketik keterangan...',
        allowClear: true,
        tags: true, // Memungkinkan menambah nilai baru
        tokenSeparators: [','],
        ajax: {
            url: '{{ route("biaya-operasional.keterangan") }}',
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
            
            // Cek apakah keterangan sudah ada (case insensitive)
            var existingOptions = $('#keterangan option').map(function() {
                return $(this).val().toLowerCase();
            }).get();
            
            if (existingOptions.indexOf(term.toLowerCase()) === -1) {
                return {
                    id: term,
                    text: term,
                    newTag: true // Tandai sebagai tag baru
                };
            }
            
            return null;
        },
        templateResult: function(data) {
            if (data.loading) {
                return data.text;
            }
            
            // Jika ini adalah tag baru yang dibuat user
            if (data.newTag) {
                return $('<span style="color:rgb(249, 249, 249); font-weight: bold;">' + 
                        data.text + ' <em style="color:rgb(252, 252, 252); font-style: italic;">(Nama Baru)</em></span>');
            }
            
            // Jika ini adalah hasil pencarian yang tidak ada di database
            if (data.element && $(data.element).data('new-tag')) {
                return $('<span style="color: #6D4534; font-weight: bold;">' + 
                        data.text + ' <em style="color: #A0522D; font-style: italic;">(Nama Baru)</em></span>');
            }
            
            return data.text;
        },
        templateSelection: function(data) {
            if (data.newTag) {
                return data.text;
            }
            return data.text;
        },
        escapeMarkup: function(markup) {
            return markup; // Izinkan HTML markup
        }
    });
    
    // Event listener untuk mendeteksi ketika user mengetik nilai baru
    $('#keterangan').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.newTag) {
            // Konfirmasi untuk nilai baru
            if (!confirm('Anda akan menambahkan keterangan baru: "' + data.text + '". Lanjutkan?')) {
                $(this).val(null).trigger('change');
                return false;
            }
        }
    });
    
    // Validasi sebelum submit untuk konfirmasi keterangan baru
    $('form').on('submit', function(e) {
        var selectedValue = $('#keterangan').val();
        var existingValues = @json(collect($keteranganList ?? [])->map(function($item) { return strtolower($item); }));
        
        if (selectedValue && existingValues.includes(selectedValue.toLowerCase())) {
            // Jika keterangan sudah ada, tetap lanjutkan
            return true;
        }
        
        // Konfirmasi jika menambah keterangan baru
        if (selectedValue && !existingValues.includes(selectedValue.toLowerCase())) {
            return confirm('Anda akan menambahkan keterangan baru: "' + selectedValue + '". Lanjutkan?');
        }
    });
});
</script>
@endsection