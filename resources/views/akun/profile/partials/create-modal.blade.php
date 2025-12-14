<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-modern">
            <div class="modal-header-modern">
                <div>
                    <h5 class="modal-title-modern">Buat Profil Karyawan</h5>
                    <p class="modal-subtitle-modern">Lengkapi informasi profil Anda</p>
                </div>
                <button type="button" class="btn-close-modern" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form action="{{ route('akun.profile.store') }}" method="POST">
                @csrf
                
                <div class="modal-body-modern">
                    <div class="form-grid">
                        <div class="form-group-modern full-width">
                            <label class="form-label-modern">
                                <i class="bi bi-person"></i>
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control-modern" 
                                   name="full_name"
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
                                      placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-telephone"></i>
                                No Telepon
                            </label>
                            <input type="text" 
                                   class="form-control-modern" 
                                   name="phone"
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
                                   placeholder="Contoh: Manager">
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-tools"></i>
                                Maintenance
                            </label>
                            <select class="form-control-modern" name="maintenance">
                                <option value="">-- Pilih Maintenance --</option>
                                <option value="elektrik">Elektrik</option>
                                <option value="mekanik">Mekanik</option>
                                <option value="office">Office</option>
                            </select>
                        </div>

                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="bi bi-calendar-event"></i>
                                Tanggal Gabung
                            </label>
                            <input type="date" 
                                   class="form-control-modern" 
                                   name="join_date">
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
                        Buat Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>