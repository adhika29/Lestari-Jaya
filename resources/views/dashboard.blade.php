@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Selamat datang di sistem manajemen pabrik gula</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sugar Cane Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="ph-fill ph-tree text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pengiriman Tebu</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\SugarCaneShipment::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('sugar-cane.index') }}" class="font-medium text-green-700 hover:text-green-900">
                        Lihat semua
                    </a>
                </div>
            </div>
        </div>

        <!-- Sugar Input Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="ph-fill ph-arrow-down-right text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Gula Masuk</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\SugarInput::sum('sak') }} Sak</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('sugar-input.index') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        Lihat semua
                    </a>
                </div>
            </div>
        </div>

        <!-- Sugar Output Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="ph-fill ph-arrow-up-right text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Gula Keluar</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\SugarOutput::sum('sak') }} Sak</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('sugar-output.index') }}" class="font-medium text-red-700 hover:text-red-900">
                        Lihat semua
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aktivitas Terbaru</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Daftar aktivitas terbaru -->
                @php
                    $recentSugarCanes = \App\Models\SugarCaneShipment::latest()->take(3)->get();
                    $recentSugarInputs = \App\Models\SugarInput::latest()->take(3)->get();
                    $recentSugarOutputs = \App\Models\SugarOutput::latest()->take(3)->get();
                @endphp

                @forelse($recentSugarCanes as $item)
                <div class="flex items-center p-3 border-b border-gray-100">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="ph-fill ph-tree text-green-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Pengiriman tebu dari {{ $item->nama_petani }}</p>
                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                @endforelse

                @forelse($recentSugarInputs as $item)
                <div class="flex items-center p-3 border-b border-gray-100">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="ph-fill ph-arrow-down-right text-blue-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Gula masuk {{ $item->sak }} sak</p>
                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                @endforelse

                @forelse($recentSugarOutputs as $item)
                <div class="flex items-center p-3 border-b border-gray-100">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="ph-fill ph-arrow-up-right text-red-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Gula keluar {{ $item->sak }} sak untuk {{ $item->nama_pembeli }}</p>
                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                @endforelse

                @if($recentSugarCanes->isEmpty() && $recentSugarInputs->isEmpty() && $recentSugarOutputs->isEmpty())
                <div class="text-center py-4 text-gray-500">
                    Belum ada aktivitas terbaru
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('sugar-cane.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                    Tambah Pengiriman Tebu
                </a>
                <a href="{{ route('sugar-input.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                    Tambah Gula Masuk
                </a>
                <a href="{{ route('sugar-output.create') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded text-center">
                    Tambah Gula Keluar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection