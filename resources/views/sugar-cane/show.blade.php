@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pengiriman Tebu</h1>
        <a href="{{ route('sugar-cane.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold mb-4">Informasi Pengiriman</h2>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Tanggal Pengiriman</p>
                    <p class="font-medium">{{ date('d-m-Y', strtotime($sugarCane->tanggal)) }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Nama Pengirim</p>
                    <p class="font-medium">{{ $sugarCane->nama_pengirim }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Jenis Tebu</p>
                    <p class="font-medium">{{ $sugarCane->jenis_tebu }}</p>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold mb-4">Informasi Harga</h2>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Bobot (KG)</p>
                    <p class="font-medium">{{ number_format($sugarCane->bobot_kg) }} KG</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Harga Per KG</p>
                    <p class="font-medium">Rp{{ number_format($sugarCane->harga_per_kg) }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Total Harga</p>
                    <p class="font-medium text-lg text-green-600">Rp{{ number_format($sugarCane->total_harga) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex space-x-4">
            <a href="{{ route('sugar-cane.edit', $sugarCane->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Edit Data</a>
            <form action="{{ route('sugar-cane.destroy', $sugarCane->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus Data</button>
            </form>
        </div>
    </div>
</div>
@endsection