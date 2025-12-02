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

    {{-- Text font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Modal if got error --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="p-8 rounded-3xl shadow-2xl transform max-w-md w-full">
        <h1 class="text-2xl sm:text-4xl font-bold text-center mb-2" style="font-family: 'Poppins';">
            Welcome Back</h1>
        <h1 class="text-xs sm:text-sm font-light text-gray-400 text-center mb-8" style="font-family: 'Poppins';">
            Enter your NIK and Password to access your account</h1>

        {{-- Pesan success --}}
        {{-- @if (session('success'))
            <div class="bg-green-500 p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif --}}

        {{-- Pesan error login
        @if ($errors->has('login_error'))
            <div class="bg-red-300 p-3 rounded mb-4 text-center">
                {{ $errors->first('login_error') }}
            </div>
        @endif --}}

        <form action="{{ route('login_process') }}" method="POST" class="space-y-6">
            @csrf

            {{-- NIK --}}
            <div class="relative">
                <input type="text" name="nik" placeholder="Enter your NIK" value="{{ old('nik') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none font-light text-gray-400"
                    style="font-family: 'Poppins', sans-serif;">
                @error('nik')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password --}}
            <div class="relative">
                <input type="password" name="password" placeholder="Enter your password" autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none font-normal text-gray-400"
                    style="font-family: 'Poppins', sans-serif;">
                @error('password')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>


            <button type="submit"
                class="w-full bg-[#00128E] text-white font-bold py-3 rounded-lg 
           transition-all duration-300"
                style="font-family: 'Poppins', sans-serif;">
                Log In
            </button>

        </form>

        {{-- <div class="mt-6 text-center">
            <p class="text-gray-400 mb-2">Atau daftar akun baru</p>
            <a href="{{ route('register') }}" class="w-full inline-block py-3 rounded-lg">
                Daftar Akun
            </a>
        </div> --}}
        <div class="flex mt-6 gap-0 sm:gap-2 justify-center">
            <div>
                <div class="text-xs sm:text-sm font-light text-gray-400" style="font-family: 'Poppins';">Don't Have An
                    Account?
                </div>
            </div>

            <div class="text-xs sm:text-sm font-bold text-[#00128E]" style="font-family: 'Poppins'">
                <a href="{{ route('register') }}">
                    Register Now.
                </a>
            </div>
        </div>

    </div>

    <!-- Animasi ikon dekoratif -->
    {{-- <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
        <i class="fas fa-meteor text-yellow-500 text-4xl absolute animate-ping" style="top: 10%; left: 5%;"></i>
        <i class="fas fa-star text-blue-500 text-2xl absolute animate-pulse" style="top: 20%; right: 10%;"></i>
        <i class="fas fa-rocket text-red-500 text-5xl absolute float" style="bottom: 15%; left: 15%;"></i>
        <i class="fas fa-planet-ringed text-purple-500 text-6xl absolute rotate" style="top: 40%; right: 20%;"></i>
    </div> --}}

    <!-- SweetAlert -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    {{-- LOGIN GAGAL --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Pesan error login --}}
    @if ($errors->has('login_error'))
        {{-- Blade: encode pesan agar aman untuk JS --}}
        @php
            $loginErrorMessage = $errors->first('login_error');
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'error',
                    title: {!! json_encode($loginErrorMessage) !!},
                    showConfirmButton: false,
                    showCloseButton: true,

                    timer: null,

                    customClass: {
                        popup: 'rounded-lg shadow-lg p-3'
                    },
                    didOpen: (toast) => {
                        toast.style.fontFamily = 'Poppins, sans-serif';
                    }
                });
            });
        </script>
    @endif

    {{-- LOGIN BERHASIL --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Please wait…',
                    html: '<div class="custom-spinner" aria-hidden="true"></div>',
                    background: 'transparent', 
                    // backdrop: 'rgba(0,0,0,0)',
                    color: '#fff', 
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,

                    customClass: {
                        title: 'swal-title-poppins',
                    },
                    // didOpen: () => {
                    //     Swal.hideLoading();
                    // },
                });

                
                setTimeout(function() {
                    window.location.href = "{{ route('dashboard') }}";

                }, 2000);
            });
        </script>
        <style>
            /* Font Poppins khusus untuk title */
            .swal-title-poppins {
                font-family: 'Poppins', sans-serif !important;
                font-weight: 400;
                font-size: 14px;
                /* color: #000; */
            }

            .custom-spinner {
                border: 4px solid #e0e0e0;
                
                border-top: 4px solid #00128E;
                
                border-radius: 50%;
                width: 35px;
                height: 35px;
                margin: 10px auto;
                animation: spin 0.9s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endif

</body>

</html>
