<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
       // Halaman profil
    public function index()
    {
        $karyawan = Karyawan::where('user_id', Auth::id())->first();

        return view('akun.profile.index', compact('karyawan'));
    }

    // Form tambah profil
    public function create()
    {
        return view('akun.profile.create');
    }

    // Simpan data profil baru
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'full_name' => 'required|string',
    //         'address' => 'nullable|string',
    //         'phone' => 'nullable|string',
    //         'position' => 'nullable|string',
    //         'maintenance' => 'nullable|in:elektrik,mekanik,office',
    //         'join_date' => 'nullable|date'
    //     ]);

    //     Karyawan::create([
    //         'user_id' => Auth::id(),
    //         'full_name' => $request->full_name,
    //         'address' => $request->address,
    //         'phone' => $request->phone,
    //         'position' => $request->position,
    //         'maintenance' => $request->maintenance,
    //         'join_date' => $request->nik
    //     ]);

    //     return redirect()->route('akun.profile.create   ')
    //         ->with('success', 'Profil karyawan berhasil dibuat.');
    // }

    // Form edit profil
    public function edit()
    {
        $karyawan = Karyawan::where('user_id', Auth::id())->first();

        return view('akun.profile.edit', compact('karyawan'));
    }

    // Update profil
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
            'maintenance' => 'nullable|in:elektrik,mekanik,office',
            'join_date' => 'nullable|date'
        ]);

        $karyawan = Karyawan::where('user_id', Auth::id())->first();

        $karyawan->update([
            'full_name' => $request->full_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'position' => $request->position,
            'maintenance' => $request->maintenance,
            'join_date' => $request->join_date
        ]);

        return redirect()->route('akun.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

}
