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
            <span class="text-gray-800 font-medium">Edit Karyawan</span>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center mb-6">
            <a href="{{ route('karyawan.index') }}" class="text-brown-500 hover:text-brown-700 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-semibold">Edit Data Karyawan</h2>
        </div>
        <hr class="border-t border-gray-300 mb-6">

        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Nama Karyawan -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Karyawan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $karyawan->nama) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- No Telepon -->
                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $karyawan->telepon) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                    @error('telepon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status Aktif -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="radio" name="status_aktif" id="status_aktif_1" value="1" {{ old('status_aktif', $karyawan->status_aktif) == 1 ? 'checked' : '' }} class="h-4 w-4 text-brown-500 focus:ring-brown-500 border-gray-300">
                            <label for="status_aktif_1" class="ml-2 block text-sm text-gray-700">Aktif</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="status_aktif" id="status_aktif_0" value="0" {{ old('status_aktif', $karyawan->status_aktif) == 0 ? 'checked' : '' }} class="h-4 w-4 text-brown-500 focus:ring-brown-500 border-gray-300">
                            <label for="status_aktif_0" class="ml-2 block text-sm text-gray-700">Tidak Aktif</label>
                        </div>
                    </div>
                    @error('status_aktif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('karyawan.index') }}" class="border border-brown-500 text-brown-500 hover:text-brown-700 hover:border-brown-700 px-4 py-2 rounded-lg mr-4">Batal</a>
                <button type="submit" class="bg-brown-500 hover:bg-brown-600 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection