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

    <!-- Pintas Cepat -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pintas Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- Tambah Pengiriman Tebu -->
                <a href="{{ route('sugar-cane.create') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mb-2">
                        <i class="ph-fill ph-plus text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 text-center">Tambah Tebu</span>
                </a>

                <!-- Tambah Gula Masuk -->
                <a href="{{ route('sugar-input.create') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                        <i class="ph-fill ph-arrow-down text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 text-center">Gula Masuk</span>
                </a>

                <!-- Tambah Gula Keluar -->
                <a href="{{ route('sugar-output.create') }}" class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mb-2">
                        <i class="ph-fill ph-arrow-up text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 text-center">Gula Keluar</span>
                </a>

                <!-- Tambah Biaya Konsumsi -->
                <a href="{{ route('biaya-konsumsi.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-2">
                        <i class="ph-fill ph-shopping-cart text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 text-center">Biaya Konsumsi</span>
                </a>

                <!-- Tambah Biaya Operasional -->
                <a href="{{ route('biaya-operasional.create') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mb-2">
                        <i class="ph-fill ph-gear text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 text-center">Biaya Operasional</span>
                </a>

                <!-- Tambah Gaji Karyawan -->
                <a href="{{ route('gaji-karyawan.create') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mb-2">
                        <i class="ph-fill ph-money text-white text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 text-center">Tambah Gaji Karyawan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aktivitas Terbaru</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-80 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-center p-3 border-b border-gray-100">
                    @php
                        $iconColorClass = match($activity['color']) {
                            'green' => 'text-green-500',
                            'blue' => 'text-blue-500', 
                            'red' => 'text-red-500',
                            'yellow' => 'text-yellow-500',
                            'purple' => 'text-purple-500',
                            'indigo' => 'text-indigo-500',
                            'orange' => 'text-orange-500',
                            default => 'text-gray-500'
                        };
                        $bgColorClass = match($activity['color']) {
                            'green' => 'bg-green-100',
                            'blue' => 'bg-blue-100',
                            'red' => 'bg-red-100', 
                            'yellow' => 'bg-yellow-100',
                            'purple' => 'bg-purple-100',
                            'indigo' => 'bg-indigo-100',
                            'orange' => 'bg-orange-100',
                            default => 'bg-gray-100'
                        };
                    @endphp
                    <div class="w-8 h-8 {{ $bgColorClass }} rounded-full flex items-center justify-center mr-4">
                        <i class="{{ $activity['icon'] }} {{ $iconColorClass }} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ $activity['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    Belum ada aktivitas terbaru
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection