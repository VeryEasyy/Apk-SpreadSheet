<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    public function LoginForm()
    {
        return view('auth.login');
    }


    // proses login
    public function login(Request $request)
    {
        // validasi input
        $request->validate([
            'nik' => [
                'required',
                'regex:/^[0-9\-\/\._]+$/'
            ],

            'password' => 'required|string'
        ]);

        // proses login
        $credenntials = $request->only('nik', 'password');

        if(Auth::attempt($credenntials)){
            $request->session()->regenerate();

        return redirect()->route('login')
            ->with('success', 'Login Berhasil');
        }

        return back()->withErrors([
            'login_error' => 'NIK atau Password Salah'
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda Telah Berhasil Logout');

    }
}
