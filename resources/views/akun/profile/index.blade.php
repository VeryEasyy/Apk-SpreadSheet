@extends('layouts.app')

@section('title', 'Profil Karyawan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
@endpush

@section('content')
<div class="profile-header">
    <div>
        <h1 class="profile-title">Profil Karyawan</h1>
        <p class="profile-subtitle">Informasi lengkap mengenai data diri karyawan</p>
    </div>
</div>

@if ($karyawan)
    {{-- Profile Card --}}
    <div class="profile-card">
        <div class="profile-banner">
            <div class="banner-gradient"></div>
        </div>
        
        <div class="profile-content">
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    {{ strtoupper(substr($karyawan->full_name, 0, 2)) }}
                </div>
                <div class="profile-basic-info">
                    <h2 class="profile-name">{{ $karyawan->full_name }}</h2>
                    <p class="profile-position">
                        <i class="bi bi-briefcase"></i>
                        {{ $karyawan->position ?? 'Posisi tidak diatur' }}
                    </p>
                    <span class="profile-badge">
                        <i class="bi bi-calendar-check"></i>
                        Bergabung {{ $karyawan->join_date ? \Carbon\Carbon::parse($karyawan->join_date)->format('d M Y') : '-' }}
                    </span>
                </div>
                <button class="btn-edit-profile" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="bi bi-pencil"></i>
                    Edit Profil
                </button>
            </div>

            <div class="profile-details-grid">
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="detail-content">
                        <label>Nama Lengkap</label>
                        <p>{{ $karyawan->full_name }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="detail-content">
                        <label>No Telepon</label>
                        <p>{{ $karyawan->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="detail-content">
                        <label>Alamat</label>
                        <p>{{ $karyawan->address ?? '-' }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div class="detail-content">
                        <label>Posisi</label>
                        <p>{{ $karyawan->position ?? '-' }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="detail-content">
                        <label>Maintenance</label>
                        <p class="text-capitalize">{{ $karyawan->maintenance ?? '-' }}</p>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="detail-content">
                        <label>Tanggal Gabung</label>
                        <p>{{ $karyawan->join_date ? \Carbon\Carbon::parse($karyawan->join_date)->format('d M Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    @include('akun.profile.partials.edit-modal')
@else
    {{-- Empty State --}}
    <div class="empty-state-card">
        <div class="empty-state-content">
            <div class="empty-state-icon">
                <i class="bi bi-person-x"></i>
            </div>
            <h3>Profil Belum Dibuat</h3>
            <p>Silakan buat profil Anda untuk melengkapi informasi karyawan</p>
            <button class="btn-create-profile" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg"></i>
                Buat Profil Sekarang
            </button>
        </div>
    </div>

    {{-- Create Modal --}}
    @include('akun.profile.partials.create-modal')
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/pages/profile.js') }}"></script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        position: 'top-end'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        position: 'top-end'
    });
</script>
@endif
@endpush