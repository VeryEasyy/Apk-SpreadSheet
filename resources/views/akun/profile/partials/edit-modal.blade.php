<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-modern">
            <div class="modal-header-modern">
                <div>
                    <h5 class="modal-title-modern">Edit Profil Karyawan</h5>
                    <p class="modal-subtitle-modern">Perbarui informasi profil Anda</p>
                </div>
                <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form action="{{ route('akun.profile.update') }}" method="POST">
                @csrf
                
                <div class="modal-body-modern">
                    <div class="form-grid">
                        <div class="form-group-modern full-width">
                            <label class="form-label-modern">
                                <i class="bi bi-person"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   class="form-control-modern" 
                                   name="full_name"
                                   value="{{ $karyawan->full_name }}" 
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>

                        <div class="form-group-modern full-width">
                            <label class="form-label-modern">
                                <i class="bi bi-geo-alt"></i>
                                Alamat
                            </label>
                            <textarea class="form-control-modern" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap">{{ $karyawan->address }}</textarea>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-telephone"></i>
                                No Telepon
                            </label>
                            <input type="text" 
                                   class="form-control-modern" 
                                   name="phone"
                                   value="{{ $karyawan->phone }}"
                                   placeholder="08xx-xxxx-xxxx">
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-briefcase"></i>
                                Posisi
                            </label>
                            <input type="text" 
                                   class="form-control-modern" 
                                   name="position"
                                   value="{{ $karyawan->position }}"
                                   placeholder="Contoh: Manager">
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-tools"></i>
                                Maintenance
                            </label>
                            <select class="form-control-modern" name="maintenance">
                                <option value="">-- Pilih Maintenance --</option>
                                <option value="elektrik" {{ $karyawan->maintenance == 'elektrik' ? 'selected' : '' }}>Elektrik</option>
                                <option value="mekanik" {{ $karyawan->maintenance == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                                <option value="office" {{ $karyawan->maintenance == 'office' ? 'selected' : '' }}>Office</option>
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-calendar-event"></i>
                                Tanggal Gabung
                            </label>
                            <input type="date" 
                                   class="form-control-modern" 
                                   name="join_date"
                                   value="{{ $karyawan && $karyawan->join_date ? \Carbon\Carbon::parse($karyawan->join_date)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>

                <div class="modal-footer-modern">
                    <button type="button" class="btn-secondary-modern" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn-primary-modern">
                        <i class="bi bi-check-circle"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>