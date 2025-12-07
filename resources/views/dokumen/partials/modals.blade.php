<div class="modal fade" id="viewModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern">Detail Laporan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <div class="mb-3">
                    <label class="form-label-modern">Judul</label>
                    <p class="text-muted">{{ $report->title }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">Status</label>
                    <p>
                        @include('dokumen.partials.status_badge', ['status' => $report->status])
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">Pembuat</label>
                    <p class="text-muted">{{ $report->owner->name ?? 'Tidak diketahui' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">Tanggal Dibuat</label>
                    <p class="text-muted">{{ $report->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">Deskripsi</label>
                    <div class="border p-3 rounded bg-light">
                        {{ $report->description ?? 'Tidak ada deskripsi' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern">Edit Laporan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dokumen.laporan.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body modal-body-modern">
                    <div class="mb-3">
                        <label class="form-label-modern">Judul Laporan</label>
                        <input type="text" name="title" value="{{ $report->title }}" 
                               class="form-control form-control-modern" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Deskripsi</label>
                        <textarea name="description" class="form-control form-control-modern" 
                                  rows="4">{{ $report->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Status</label>
                        <select class="form-control form-control-modern" name="status">
                            <option value="draft" {{ $report->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $report->status == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ $report->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>