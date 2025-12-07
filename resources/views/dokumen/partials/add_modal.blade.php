<div class="modal fade" id="tambahLaporanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern">Tambah Laporan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dokumen.laporan.store') }}" method="POST">
                @csrf
                <div class="modal-body modal-body-modern">
                    <div class="mb-3">
                        <label class="form-label-modern">Judul Laporan</label>
                        <input type="text" name="title" class="form-control form-control-modern" 
                               placeholder="Masukkan judul laporan..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Deskripsi</label>
                        <textarea name="description" class="form-control form-control-modern" rows="4"
                                  placeholder="Masukkan deskripsi laporan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Status</label>
                        <select class="form-control form-control-modern" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-add">
                        <i class="bi bi-check-lg"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>