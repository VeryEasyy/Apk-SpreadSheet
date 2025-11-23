@extends('layouts.app')

@section('title', 'Profil Karyawan')

@section('content')
<h3 class="fw-bold mb-2">Profil Karyawan</h3>
<p class="text-muted mb-4">Informasi lengkap mengenai data diri karyawan.</p>

<div class="card shadow-sm border-0">
    <div class="card-body">

        @if ($karyawan)

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Nama Lengkap</div>
            <div class="col-md-9">{{ $karyawan->full_name }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Alamat</div>
            <div class="col-md-9">{{ $karyawan->address ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">No Telp</div>
            <div class="col-md-9">{{ $karyawan->phone ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Posisi</div>
            <div class="col-md-9">{{ $karyawan->position ?? '-' }}</div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 fw-bold">Maintenance</div>
            <div class="col-md-9 text-capitalize">{{ $karyawan->maintenance ?? '-' }}</div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 fw-bold">Tanggal Gabung</div>
            <div class="col-md-9">{{ $karyawan->join_date ?? '-' }}</div>
        </div>

        <!-- BUTTON EDIT -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
            <i class="material-icons align-middle me-1">edit</i>
            Edit Profil
        </button>


        @else

        <div class="text-center">
            <p class="text-muted">Profil belum dibuat.</p>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="material-icons align-middle me-1">add</i>
                Buat Profil
            </button>
        </div>

        @endif

    </div>
</div>

@if($karyawan)
<!-- ========================================================= -->
<!-- ===================== MODAL EDIT ========================= -->
<!-- ========================================================= -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form action="{{ route('akun.profile.update') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Edit Profil Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body row g-3">
            {{-- sweet alert berhasil dan gagal --}}
            @if (session('success'))
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 3000
                });

                setTimeout(() => {
                    window.location.href = "{{ route('akun.profile') }}";
                }, 3000);
            });
            </script>
            @endif

            <div class="col-md-12">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="full_name"
                       value="{{ $karyawan->full_name }}" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Alamat</label>
                <textarea class="form-control" name="address" rows="2">{{ $karyawan->address }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">No Telp</label>
                <input type="text" class="form-control" name="phone"
                       value="{{ $karyawan->phone }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Posisi</label>
                <input type="text" class="form-control" name="position"
                       value="{{ $karyawan->position }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Maintenance</label>
                <select class="form-select" name="maintenance">
                    <option value="">-- Pilih --</option>
                    <option value="elektrik" {{ $karyawan->maintenance == 'elektrik' ? 'selected' : '' }}>Elektrik</option>
                    <option value="mekanik" {{ $karyawan->maintenance == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                    <option value="office" {{ $karyawan->maintenance == 'office' ? 'selected' : '' }}>Office</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tanggal Gabung</label>
                <input 
                    type="date" 
                    class="form-control" 
                    name="join_date"
                    value="{{ $karyawan && strtotime($karyawan->join_date) ? date('Y-m-d', strtotime($karyawan->join_date)) : '' }}">
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>

      </form>

    </div>
  </div>
</div>
@endif





@endsection
