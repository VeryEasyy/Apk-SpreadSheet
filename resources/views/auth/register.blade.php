<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: #200052;
        }
    </style> --}}
    {{-- Text font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Modal if got error --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="min-h-screen flex flex-col md:flex-row sm:items-center sm:justify-center p-3 sm:p-4">
    <div class="w-full flex justify-center sm:justify-start absolute top-4 sm:left-4 left-0">
        <img src="{{ asset('assets/images/logo/bando-logo.png') }}" alt="Logo" class="h-12 w-auto">
    </div>
    <div class="w-full md:w-1/2 max-w-md flex-shrink-0 mr-10">
        <div>
            <h1 class="text-2xl sm:text-4xl pt-20 font-bold text-center" style="font-family: 'Poppins';">Create an
                Account
            </h1>
            {{-- <h1 class="text-xs sm:text-sm font-light text-gray-400 text-center" style="font-family: 'Poppins';">
                Join now to want make a report in office</h1> --}}
            <p class="sm:mt-3 text-xs sm:text-sm font-light text-gray-500 text-center" style="font-family: 'Poppins';">
                <!-- small screen short text -->
                <span class="md:hidden">Join now to make a report.</span>

                <!-- medium+ screens long text -->
                <span class="hidden md:inline-block max-w-prose text-left md:text-center">
                    It is a long established fact that a reader will be distracted by the readable content of a page
                    when
                    looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal
                    distribution
                    of letters, as opposed to using 'Content here, content here', making it look like readable English.
                </span>
            </p>
        </div>
    </div>

    <!-- Bagian Form -->
    <form action="{{ route('register.store') }}" autocomplete="off" method="POST" class="sm:space-y-6">
        @csrf




        {{-- <div class="flex w-full gap-4">
            <div class="flex flex-col gap-1 w-full">
                <label class="text-white text-lg font-medium">NIK</label>
                <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK"
                       class="w-full text-white bg-[#270082] border-2 ">
            </div>
            <div class="flex flex-col gap-1 w-full">
                <label class="text-white text-lg font-medium">Nama Lengkap</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Masukkan Nama"
                       class="w-full text-white bg-[#270082] border-2 border-[#7A0BC0] rounded-[40px] py-2 px-6 text-lg focus:outline-none focus:border-white">
            </div>
        </div> --}}
        <div class="flex flex-col sm:flex-row gap-4 w-full mt-10">

            <div class="relative">
                <label for="nik" class="flex mb-1 font-semibold" style="font-family: 'Poppins';">NIK</label>
                <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                    placeholder="Masukkan NIK"
                    class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none font-light"
                    style="font-family: 'Poppins', sans-serif;">
            </div>
            <div class="relative">
                <label for="full-name" class="flex mb-1 font-semibold" style="font-family: 'Poppins';">Full Name</label>
                <input id="full-name" type="text" name="full_name" value="{{ old('full_name') }}"
                    placeholder="Masukkan Nama"
                    class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none font-light "
                    style="font-family: 'Poppins', sans-serif;">
            </div>
        </div>

        {{-- <div class="flex flex-col gap-1 w-full">
            <label class="text-lg font-medium">Password</label>
            <input type="password" name="password" placeholder="************"
                class="w-full  border-2 ">
        </div> --}}
        <div class="relative mt-5">
            <label for="password" class="flex mb-1 font-semibold" style="font-family: 'Poppins';">Password</label>
            <input id="password" type="password" name="password" placeholder="Your password"
                class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none font-light "
                style="font-family: 'Poppins', sans-serif;">
        </div>

        {{-- <div class="flex flex-col gap-1 w-full">
            <label class="text-lg font-medium">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="************"
                class="w-full  border-2 ">
        </div> --}}
        <div class="relative mt-5">
            <label for="confirm-password" class="flex mb-1 font-semibold" style="font-family: 'Poppins';">Confirm
                Password</label>
            <input id="confirm-password" type="password" name="password_confirmation" placeholder="************"
                class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none font-light "
                style="font-family: 'Poppins', sans-serif;">
        </div>

        <div class="flex flex-col gap-2 w-full mt-5">
            <label for="role" class="font-semibold" style="font-family: 'Poppins', sans-serif;">
                Role
            </label>

            <div class="relative">
                <select id="role" name="role"
                    class="block w-full appearance-none rounded-[40px] border border-gray-200 bg-white py-3 pl-4 pr-11 text-gray-700 text-sm font-normal"
                    style="font-family: 'Poppins';">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                </select>

                <!-- Chevron icon (pointer-events-none supaya klik tetap ke select) -->
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    {{-- <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true">
                        <path d="M6 8l4 4 4-4" stroke="#00128E" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg> --}}
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </div>
            </div>

            <p class="text-sm text-gray-400" style="font-family: 'Poppins', sans-serif;">
                Pilih role yang sesuai untuk akun ini.
            </p>
        </div>

        <button type="submit" class="mt-5 w-full bg-[#1a1a1a] text-white font-bold py-3 rounded-xl"
            style="font-family: 'Poppins', sans-serif;">
            Register
        </button>

        {{-- <span class="text-white text-sm mt-1">Sudah punya akun? <a href="{{ route('login') }}"
                class="text-[#fa58b6] underline">Login</a></span> --}}
        <div class="flex mt-6 gap-2 justify-center">
            <div>
                <div class="text-xs sm:text-sm font-light text-gray-400" style="font-family: 'Poppins';">Already Have An
                    Account?
                </div>
            </div>

            <div class="text-xs sm:text-sm font-bold text-[#1a1a1a]" style="font-family: 'Poppins'">
                <a href="{{ route('login') }}">
                    Sign In.
                </a>
            </div>
        </div>
    </form>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
        <script>
            Swal.fire({
                toast: true,
                icon: 'error',
                position: 'bottom-end',
                title: 'Registrasi Gagal',
                // text: `{!! implode('\n', $errors->all()) !!}`,
                // confirmButtonText: 'Mengerti',
                // confirmButtonColor: '#d33',
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
        </script>
    @endif
    

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
                    window.location.href = "{{ route('login') }}";

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
