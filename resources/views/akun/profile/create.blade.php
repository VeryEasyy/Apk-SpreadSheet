@extends('layouts.app')

@section('title', 'Buat Profil Karyawan')

@section('content')

<h3 class="fw-bold">Buat Profil Karyawan</h3>

<div class="card p-4 shadow-sm">
    <form action="{{ route('akun.profile.store') }}" method="POST">
        @csrf

        @include('akun.profile.form')
        
        <button class="btn btn-success mt-3">Simpan</button>
    </form>
</div>

@endsection
