<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - LestariJaya</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background Circles -->
        <div class="absolute top-0 left-0 w-32 h-32 md:w-48 md:h-48 rounded-full bg-brown-500 -translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute top-20 left-20 w-24 h-24 md:w-36 md:h-36 rounded-full bg-green-300"></div>
        <div class="absolute top-10 right-10 w-16 h-16 rounded-full bg-green-300"></div>
        <div class="absolute bottom-10 left-20 w-32 h-32 rounded-full bg-green-300"></div>
        <div class="absolute bottom-20 right-0 w-24 h-24 rounded-full bg-brown-500 translate-x-1/3"></div>
        <div class="absolute bottom-0 right-1/4 w-48 h-48 rounded-full bg-brown-500 translate-y-1/2"></div>
        
        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-lg w-full max-w-md mx-4 z-10 overflow-hidden">
            <div class="border-t-8 border-brown-500 rounded-t-3xl"></div>
            <div class="p-8">
                <h1 class="text-center text-4xl font-bold mb-8">
                    <span class="text-brown-500">Lestari</span><span class="text-green-400">Jaya</span>
                </h1>
                
                <h2 class="text-2xl font-bold mb-6">Masuk</h2>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-brown-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20,4H4C2.9,4,2,4.9,2,6v12c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V6C22,4.9,21.1,4,20,4z M20,8l-8,5L4,8V6l8,5l8-5V8z"/>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500 focus:border-transparent" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-brown-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10C20,8.9,19.1,8,18,8z M9,6c0-1.66,1.34-3,3-3s3,1.34,3,3v2H9V6z M18,20H6V10h12V20z M12,17c1.1,0,2-0.9,2-2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2C10,16.1,10.9,17,12,17z"/>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500 focus:border-transparent" required>
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 password-toggle">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,4.5C7,4.5,2.73,7.61,1,12c1.73,4.39,6,7.5,11,7.5s9.27-3.11,11-7.5C21.27,7.61,17,4.5,12,4.5z M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5s5,2.24,5,5S14.76,17,12,17z M12,9c-1.66,0-3,1.34-3,3s1.34,3,3,3s3-1.34,3-3S13.66,9,12,9z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="form-checkbox h-5 w-5 text-brown-500 rounded focus:ring-brown-500 border-gray-300">
                            <span class="ml-2 text-gray-700">Ingat saya</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-brown-500 text-white py-2 px-4 rounded-md hover:bg-brown-600 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:ring-opacity-50 transition duration-200 mb-4">Masuk</button>
                    
                    <div class="text-center">
                        <p class="text-gray-600">Sistem khusus untuk admin perusahaan</p>
                    </div>
                    <!-- Hapus bagian ini -->
                    <!-- <div class="text-center">
                        <p class="text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-green-500 hover:underline">Daftar</a></p>
                    </div> -->
                </form>
            </div>
        </div>
    </div>

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
</body>
</html>