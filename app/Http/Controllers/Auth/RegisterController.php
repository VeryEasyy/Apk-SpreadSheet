<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Tampilkan form registrasi
    public function showForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => ['required', 'unique:users,nik', 'regex:/^[0-9\-\/\._]+$/'],
            'password' => ['required', 'min:5', 'confirmed'],
            'role' => ['required', 'in:admin,staff'],
            'full_name' => ['required', 'string'],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Buat user terlebih dahulu
        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->full_name, // simpan nama di table users
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Buat data karyawan dengan nama yang sama
        Karyawan::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'address' => null,
            'phone' => null,
            'position' => null,
            'maintenance' => null,
            'join_date' => null,
        ]);


        return redirect()->back()->with('success', 'Akun berhasil dibuat! Silakan login.');

    }

}
