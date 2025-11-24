<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Dokument\LaporanController;


Route::get('/', function () {
    return redirect()->route('login');
});

// login
Route::get('/login', [LoginController::class, 'LoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login_process');
Route::post('/logout', [LoginController::class, 'Logout'])->name('logout');

// register
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');


//akun profil
Route::middleware('auth')->group(function () {
    
    // dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Halaman profil
    Route::get('/akun/profile', [KaryawanController::class, 'index'])->name('akun.profile');

    // Form tambah profil
    // Route::get('/akun/profile/create', [KaryawanController::class, 'create'])->name('akun.profile.create');

    // // Proses simpan profil
    // Route::post('/akun/profile/store', [KaryawanController::class, 'store'])->name('akun.profile.store');

    // Form edit profil
    Route::get('/akun/profile/edit', [KaryawanController::class, 'edit'])->name('akun.profile.edit');

    // Proses update profil
    Route::post('/akun/profile/update', [KaryawanController::class, 'update'])->name('akun.profile.update');

    //Halaman laporan 
    Route::get('/dokumen/laporan', [LaporanController::class, 'index'])->name('dokumen.laporan');
    Route::post('/dokumen/laporan', [LaporanController::class, 'store'])->name('dokumen.laporan.store');
    Route::post('/dokumen/laporan/update/{id}', [LaporanController::class, 'update'])->name('dokumen.laporan.update');
    Route::delete('/dokumen/laporan/{id}', [LaporanController::class, 'delete'])->name('dokumen.laporan.delete');

    // spreadsheet
    Route::get('/dokumen/laporan/spreadsheet', [LaporanController::class, 'index'])->name('dokumen.laporan.spreadsheet');
    Route::get('/dokumen/laporan/{id}/sheet/edit', [LaporanController::class, 'sheet'])
    ->name('dokumen.laporan.sheet');
    
    Route::get('/dokumen/laporan/{id}/sheet/view', [LaporanController::class, 'viewSheet'])
    ->name('dokumen.laporan.sheet.view');

    Route::get('/dokumen/laporan/{id}/sheet/pdf', [LaporanController::class, 'exportPdf'])
    ->name('dokumen.laporan.sheet.pdf');

    Route::post('/laporan/cell/update', [LaporanController::class, 'updateCell'])
    ->name('laporan.cell.update');




});