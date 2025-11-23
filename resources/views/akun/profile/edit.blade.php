@extends('layouts.app')

@section('title', 'Edit Profil Karyawan')

@section('content')

<h3 class="fw-bold">Edit Profil Karyawan</h3>

<div class="card p-4 shadow-sm">
    <form action="{{ route('akun.profile.update') }}" method="POST">
        @csrf

        @include('akun.profile.form')

        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>

@endsection
