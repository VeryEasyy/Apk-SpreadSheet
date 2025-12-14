@extends('layouts.app')

@section('title', 'Dokumen Laporan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/report_list.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Dokumen Laporan</h1>
    <p class="page-subtitle">Kelola dan pantau semua laporan dalam sistem</p>
</div>

<div class="modern-card">
    <div class="card-header-modern">
        <h2 class="card-title-modern">Daftar Laporan</h2>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#tambahLaporanModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Laporan
        </button>
    </div>

    <div class="modern-table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th class="row-number">No</th>
                    <th>Judul Laporan</th>
                    <th>Pembuat</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status</th>
                    <th>Terakhir Diedit</th>
                    <th>Editor</th>
                    <th style="width: 80px; text-align: center;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($laporan as $item)
                    @include('dokumen.partials.report_row', ['report' => $item, 'index' => $loop->iteration])
                    @include('dokumen.partials.modals', ['report' => $item])
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5>Belum Ada Laporan</h5>
                                <p>Klik tombol "Tambah Laporan" untuk membuat laporan baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@include('dokumen.partials.add_modal')
@endsection

@push('scripts')
<script src="{{ asset('js/pages/report_list.js') }}"></script>
@endpush