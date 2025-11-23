@extends('layouts.app')

@section('title', 'Dokumen Laporan')

@section('content')
<h3 class="fw-bold mb-2">Dokumen Laporan</h3>
<p class="text-muted mb-4">Daftar laporan yang tersedia di sistem</p>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-semibold">Daftar Laporan</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahLaporanModal">
                <span class="material-icons me-1" style="font-size:18px;">add</span> Tambah Laporan
            </button>
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
                        <th>Edited</th>
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
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $item->id }}">
                                <span class="material-icons" style="font-size:16px">edit</span>
                            </button>

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
                            @if(auth()->user()->role === 'admin')
                                {{-- MODE EDIT (ADMIN) --}}
                                <a href="{{ route('dokumen.laporan.sheet', $item->id) }}"
                                class="btn btn-success btn-sm me-1">
                                    <span class="material-icons align-middle" style="font-size:16px">
                                        grid_on
                                    </span>
                                    Edit
                                </a>
                            @endif

                            {{-- MODE VIEW (ADMIN & STAFF) --}}
                            <a href="{{ route('dokumen.laporan.sheet.view', $item->id) }}"
                            class="btn btn-secondary btn-sm">
                                <span class="material-icons align-middle" style="font-size:16px">
                                    visibility
                                </span>
                                View
                            </a>
                        </td>

                        @php
                            $lastSheet = $item->sheets->first();
                            $lastCell = $lastSheet?->cells
                                            ->sortByDesc('updated_at')
                                            ->first();
                        @endphp

                        <td class="text-center">
                            @if($lastCell && $lastCell->updatedBy)
                                <span class="badge bg-info text-dark">
                                    {{ $lastCell->updatedBy->name }}
                                </span>
                            @else
                                <span class="text-muted">Belum diedit</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($lastCell)
                                {{ $lastCell->updated_at->format('d M Y H:i') }}
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
                            <option value="archived">Archived</option>
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
</div>


{{-- SweetAlert Success --}}
@if(session()->has('success'))
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "{{ route('dokumen.laporan') }}";
        });
    });
    </script>
@endif

{{-- SweetAlert Error --}}
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });

        setTimeout(() => {
            window.location.href = "{{ route('dokumen.laporan') }}";
        }, 3000);
    </script>
@endif



<script>
    function hapusLaporan(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection
