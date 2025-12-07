@extends('layouts.app')

@section('title', 'Dokumen Laporan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/report_list.css') }}">
@endpush

@section('content')
<<<<<<< HEAD
<h3 class="fw-bold mb-2">Dokumen Laporan</h3>
<p class="text-muted mb-4">Daftar laporan yang tersedia di sistem</p>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">

            <h5 class="fw-semibold">Daftar Laporan</h5>

            <div class="d-flex gap-2">

                {{-- SEARCH --}}
                <form action="{{ route('dokumen.laporan') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari judul..." value="{{ request('search') }}">
                </form>

                {{-- FILTER STATUS --}}
                <form action="{{ route('dokumen.laporan') }}" method="GET">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                        <option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
                        <option value="archived" {{ request('status')=='archived'?'selected':'' }}>Archived</option>
                    </select>
                </form>

                {{-- BUTTON TAMBAH --}}
                @if(auth()->user()->role === 'admin')
                    <button class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahLaporanModal">
                        <span class="material-icons" style="font-size:16px">add</span>
                        Tambah
                    </button>
                @endif

                {{-- RESET --}}
                <a href="{{ route('dokumen.laporan') }}" class="btn btn-secondary btn-sm">Reset</a>

            </div>

        </div>


        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Judul</th>
                        <th>Pembuat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                        <th width="15%" class="text-center">Spreadsheet</th>
                        <th>Keterangan</th>
                        <th>waktu & tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($laporan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>
                            {{ $item->owner->name ?? 'Tidak diketahui' }}
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            @if ($item->status == 'published')
                                <span class="badge bg-success">Published</span>
                            @elseif ($item->status == 'archived')
                                <span class="badge bg-danger">Archived</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>

                        <td class="text-center">

                            {{-- VIEW --}}
                            <button class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#viewModal{{ $item->id }}">
                                <span class="material-icons" style="font-size:16px">visibility</span>
                            </button>

                            {{-- EDIT --}}
                            @if ($item->status !== 'published')
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $item->id }}">
                                    <span class="material-icons" style="font-size:16px">edit</span>
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled hidden
                                    title="Laporan yang sudah dipublikasikan tidak dapat diedit">
                                    <span class="material-icons" style="font-size:16px">edit</span>
                                </button>
                            @endif
                          

                            {{-- DELETE --}}
                            <button class="btn btn-danger btn-sm" onclick="hapusLaporan({{ $item->id }})">
                                <span class="material-icons" style="font-size:16px">delete</span>
                            </button>

                            <form id="delete-form-{{ $item->id }}"
                                  action="{{ route('dokumen.laporan.delete', $item->id) }}"
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>

                        </td>
                        <td class="text-center">
                            {{-- MODE EDIT (ADMIN) --}}
                            @if(auth()->user()->role === 'admin')
                                @if ($item->status !== 'published')
                                  <a href="{{ route('dokumen.laporan.sheet', $item->id) }}"
                                    class="btn btn-success btn-sm me-1">
                                        <span class="material-icons align-middle" style="font-size:16px">
                                            grid_on
                                        </span>
                                        Edit
                                </a>
                                @else
                                  <a href="{{ route('dokumen.laporan.sheet', $item->id) }}"
                                    class="btn btn-success btn-sm me-1" hidden>
                                        <span class="material-icons align-middle" style="font-size:16px">
                                            grid_on
                                        </span>
                                        Edit
                                </a>
                                @endif
                              
                            @endif

                            {{-- MODE VIEW (ADMIN & STAFF) --}}
                            <button class="btn btn-secondary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#viewSheetModal{{ $item->id }}">
                                <span class="material-icons" style="font-size:16px">visibility</span>
                                View
                            </button>
                        </td>

                        @php
                            $lastLog = $item->logs->sortByDesc('created_at')->first();

                        @endphp

                        <td class="text-center">
                           @if($lastLog)
                                <span class="badge bg-info text-dark">
                                    {{ $lastLog->editor->name }}
                                    mengubah sel <strong>{{ $lastLog->cell }}</strong><br>
                                    dari "<em>{{ $lastLog->old_value ?? '-' }}</em>"
                                    menjadi "<em>{{ $lastLog->new_value ?? '-' }}</em>"
                                </span>
                            @else
                                <span class="text-muted">Tidak ada perubahan</span>
                            @endif

                        </td>

                        <td class="text-center">
                            @if($lastLog)
                                {{ \Carbon\Carbon::parse($lastLog->created_at)->format('H:i / d M Y ') }}  
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>




                    </tr>

                    {{-- ================= MODAL VIEW ================= --}}
                    <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Detail Laporan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <p><strong>Judul:</strong> {{ $item->title }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                                    <p><strong>Tanggal:</strong> {{ $item->created_at->format('d M Y') }}</p>

                                    <hr>

                                    <p><strong>Deskripsi :</strong></p>
                                    <div class="border p-3 rounded bg-light">
                                        {{ $item->description ?? '-' }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ================= MODAL EDIT ================= --}}
                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Laporan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('dokumen.laporan.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="form-label">Judul</label>
                                            <input type="text" name="title"
                                                value="{{ $item->title }}"
                                                class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="description"
                                                class="form-control"
                                                rows="4">{{ $item->description }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ $item->status == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ $item->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-warning">Update</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewSheetModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content border-0 shadow">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Pilih Mode Tampilan</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body text-center">

                                    <a href="{{ route('dokumen.laporan.sheet.view', $item->id) }}"
                                    class="btn btn-primary w-100 mb-3">
                                        <span class="material-icons align-middle">grid_on</span>
                                        View Spreadsheet
                                    </a>

                                    <a href="{{ route('dokumen.laporan.sheet.pdf', $item->id) }}"
                                    class="btn btn-danger w-100" target="_blank">
                                        <span class="material-icons align-middle">picture_as_pdf</span>
                                        View PDF
                                    </a>

                                </div>

                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Data laporan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
@if(auth()->user()->role === 'admin')
    <div class="modal fade" id="tambahLaporanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('dokumen.laporan.store') }}" method="POST">
                    @csrf

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Judul Laporan</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                {{-- <option value="archived">Archived</option> --}}
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
=======
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
>>>>>>> 84223df70e77d9d915ab6cad0cd77aea609c63cc
    </div>
@endif



@include('dokumen.partials.add_modal')
@endsection

@push('scripts')
<script src="{{ asset('js/pages/report_list.js') }}"></script>
@endpush