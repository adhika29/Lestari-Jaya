@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="bg-white p-3 rounded-lg shadow-sm mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="text-gray-600">Profil</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Informasi Profil</h2>
            <div class="flex items-center mb-6">
                <div class="w-20 h-20 rounded-full bg-pink-500 flex items-center justify-center text-white overflow-hidden mr-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="text-lg font-medium">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <input type="text" id="role" value="Admin" class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" disabled>
                        <p class="text-gray-500 text-xs mt-1">Role tidak dapat diubah</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown-500 transition duration-200 transform hover:-translate-y-1 hover:shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Ubah Password</h2>
            <form action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 mb-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 pr-10">
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 password-toggle">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,4.5C7,4.5,2.73,7.61,1,12c1.73,4.39,6,7.5,11,7.5s9.27-3.11,11-7.5C21.27,7.61,17,4.5,12,4.5z M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5s5,2.24,5,5S14.76,17,12,17z M12,9c-1.66,0-3,1.34-3,3s1.34,3,3,3s3-1.34,3-3S13.66,9,12,9z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 pr-10">
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 password-toggle">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,4.5C7,4.5,2.73,7.61,1,12c1.73,4.39,6,7.5,11,7.5s9.27-3.11,11-7.5C21.27,7.61,17,4.5,12,4.5z M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5s5,2.24,5,5S14.76,17,12,17z M12,9c-1.66,0-3,1.34-3,3s1.34,3,3,3s3-1.34,3-3S13.66,9,12,9z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 pr-10">
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 password-toggle">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,4.5C7,4.5,2.73,7.61,1,12c1.73,4.39,6,7.5,11,7.5s9.27-3.11,11-7.5C21.27,7.61,17,4.5,12,4.5z M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5s5,2.24,5,5S14.76,17,12,17z M12,9c-1.66,0-3,1.34-3,3s1.34,3,3,3s3-1.34,3-3S13.66,9,12,9z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-brown-500 text-white rounded-md hover:bg-brown-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown-500 transition duration-200 transform hover:-translate-y-1 hover:shadow-lg">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.password-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                // Toggle icon
                const svg = this.querySelector('svg');
                if (type === 'text') {
                    svg.innerHTML = '<path d="M12,7c-2.76,0-5,2.24-5,5s2.24,5,5,5s5-2.24,5-5S14.76,7,12,7L12,7z M2,4.27l2.28,2.28C3.07,7.83,2.17,9.33,1.55,11c1.69,4.05,5.07,7,9.45,7c1.4,0,2.73-0.29,4-0.78l2.28,2.28l1.27-1.27L3.27,3L2,4.27z M7.53,9.8l1.55,1.55c-0.05,0.21-0.08,0.43-0.08,0.65c0,1.66,1.34,3,3,3c0.22,0,0.44-0.03,0.65-0.08l1.55,1.55c-0.67,0.33-1.41,0.53-2.2,0.53c-2.76,0-5-2.24-5-5C7,11.21,7.2,10.47,7.53,9.8z M16.84,13.41c0.25-0.76,0.4-1.56,0.4-2.41c0-0.79-0.15-1.56-0.4-2.28c-0.25-0.71-0.59-1.4-1.11-2.04c-1.33-1.63-3.01-2.95-4.97-3.56C10.19,3.05,9.62,3,9,3L9,3c-1.17,0-2.39,0.26-3.56,0.77l1.34,1.34C7.42,4.93,8.15,4.75,8.89,4.75c0.51,0,1.01,0.06,1.5,0.17c1.95,0.42,3.13,1.47,4.43,3.08c0.4,0.5,0.65,1.03,0.86,1.59c0.21,0.56,0.35,1.15,0.35,1.75c0,0.6-0.14,1.19-0.35,1.75c-0.21,0.56-0.46,1.09-0.86,1.59c-0.86,1.06-1.86,1.84-3.07,2.47v-0.11c0-1.39-0.61-2.62-1.57-3.45L16.84,13.41z"/>';
                } else {
                    svg.innerHTML = '<path d="M12,4.5C7,4.5,2.73,7.61,1,12c1.73,4.39,6,7.5,11,7.5s9.27-3.11,11-7.5C21.27,7.61,17,4.5,12,4.5z M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5s5,2.24,5,5S14.76,17,12,17z M12,9c-1.66,0-3,1.34-3,3s1.34,3,3,3s3-1.34,3-3S13.66,9,12,9z"/>';
                }
            });
        });
    });
</script>