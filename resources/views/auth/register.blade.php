<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Akun</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: #200052;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Bagian Form -->
    <form action="{{ route('register.store') }}" autocomplete="off" method="POST" class="flex flex-col items-center gap-4 bg-[#270082] p-6 rounded-[25px] w-[500px]">
        @csrf
        <h1 class="text-white text-5xl font-bold tracking-widest mb-4">Sign up</h1>

        <div class="flex w-full gap-4">
            <div class="flex flex-col gap-1 w-full">
                <label class="text-white text-lg font-medium">NIK</label>
                <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK"
                       class="w-full text-white bg-[#270082] border-2 border-[#7A0BC0] rounded-[40px] py-2 px-6 text-lg focus:outline-none focus:border-white">
            </div>
            <div class="flex flex-col gap-1 w-full">
                <label class="text-white text-lg font-medium">Nama Lengkap</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Masukkan Nama"
                       class="w-full text-white bg-[#270082] border-2 border-[#7A0BC0] rounded-[40px] py-2 px-6 text-lg focus:outline-none focus:border-white">
            </div>
        </div>

        <div class="flex flex-col gap-1 w-full">
            <label class="text-white text-lg font-medium">Password</label>
            <input type="password" name="password"  placeholder="************"
                   class="w-full text-white bg-[#270082] border-2 border-[#7A0BC0] rounded-[40px] py-2 px-6 text-lg focus:outline-none focus:border-white">
        </div>

        <div class="flex flex-col gap-1 w-full">
            <label class="text-white text-lg font-medium">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="************"
                   class="w-full text-white bg-[#270082] border-2 border-[#7A0BC0] rounded-[40px] py-2 px-6 text-lg focus:outline-none focus:border-white">
        </div>

        <div class="flex flex-col gap-1 w-full">
            <label class="text-white text-lg font-medium">Role</label>
            <select name="role" class="w-full bg-[#270082] border-2 border-[#7A0BC0] text-white rounded-[40px] py-2 px-4 focus:outline-none focus:border-white">
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role')=='staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>

        <button type="submit" class="w-[250px] h-[50px] bg-gradient-to-r from-[#7a0bc0] to-[#fa58b6] text-white text-2xl font-bold rounded-[10px] shadow-[4px_4px_40px_rgba(0,0,0,0.25)] hover:scale-105 transition-transform">
            Sign up
        </button>

        <span class="text-white text-sm mt-1">Sudah punya akun? <a href="{{ route('login') }}" class="text-[#fa58b6] underline">Login</a></span>
    </form>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: `{!! implode('\n', $errors->all()) !!}`,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#d33'
        });
    </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Register Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
                allowOutsideClick: false
            }).then(() => {
                window.location.href = "{{ route('login') }}";
            });
        </script>
    @endif


</body>
</html>
