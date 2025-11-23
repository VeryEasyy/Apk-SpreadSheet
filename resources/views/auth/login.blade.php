<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-black bg-opacity-80 p-8 rounded-3xl shadow-2xl transform hover:scale-105 transition-all duration-500 max-w-md w-full">
        <h1 class="text-4xl font-extrabold text-center mb-8 text-white">Login</h1>

        {{-- Pesan success --}}
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error login --}}
        @if($errors->has('login_error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4 text-center">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        <form action="{{ route('login_process') }}" method="POST" class="space-y-6">
            @csrf

            {{-- NIK --}}
            <div class="relative">
                <input type="text"
                       name="nik"
                       placeholder="Masukkan NIK"
                       value="{{ old('nik') }}"
                       class="w-full bg-gray-800 text-white px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all duration-300 @error('nik') ring-2 ring-red-500 @enderror">
                <i class="fas fa-id-card absolute right-3 top-3 text-pink-500"></i>
                @error('nik')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password --}}
            <div class="relative">
                <input type="password"
                       name="password"
                       placeholder="Masukkan Password"
                       autocomplete="new-password"
                       class="w-full bg-gray-800 text-white px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all duration-300 @error('password') ring-2 ring-red-500 @enderror">
                <i class="fas fa-lock absolute right-3 top-3 text-pink-500"></i>
                @error('password')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold py-3 rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-400 mb-2">Atau daftar akun baru</p>
            <a href="{{ route('register') }}" class="w-full inline-block bg-gray-700 text-white py-3 rounded-lg hover:bg-gray-600 transition-all duration-300">
                Daftar Akun
            </a>
        </div>
    </div>

    <!-- Animasi ikon dekoratif -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
        <i class="fas fa-meteor text-yellow-500 text-4xl absolute animate-ping" style="top: 10%; left: 5%;"></i>
        <i class="fas fa-star text-blue-500 text-2xl absolute animate-pulse" style="top: 20%; right: 10%;"></i>
        <i class="fas fa-rocket text-red-500 text-5xl absolute float" style="bottom: 15%; left: 15%;"></i>
        <i class="fas fa-planet-ringed text-purple-500 text-6xl absolute rotate" style="top: 40%; right: 20%;"></i>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- LOGIN GAGAL --}}
    @if($errors->has('login_error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ $errors->first('login_error') }}',
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#e11d48'
        });
    </script>
    @endif

    {{-- LOGIN BERHASIL --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
                allowOutsideClick: false
            }).then(() => {
                window.location.href = "{{ route('dashboard') }}";
            });
        </script>
    @endif
</body>
</html>
