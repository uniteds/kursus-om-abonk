<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cog mr-1"></i> Pengaturan Sertifikat</h3>
            </div>
            <form action="/admin/certificate-settings/update" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <h5 class="text-muted mb-3"><i class="fas fa-image mr-1"></i> Logo Sertifikat</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="logo">Upload Logo (PNG/JPG, max 2MB)</label>
                                <input type="file" name="logo" id="logo-input" class="form-control" accept="image/*">
                                <small class="text-muted">Format: PNG, JPG, GIF, Webp, SVG. Disarankan background transparan.</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <label>Preview</label>
                            <div style="border:2px dashed #dee2e6;border-radius:8px;padding:10px;background:#f8f9fa;min-height:80px;display:flex;align-items:center;justify-content:center;">
                                <img id="logo-preview"
                                     src="<?= !empty($certSettings->logo) ? '/uploads/certificates/' . esc($certSettings->logo) : '' ?>"
                                     alt="Logo"
                                     style="max-height:60px;max-width:100%;<?= empty($certSettings->logo) ? 'display:none;' : '' ?>">
                                <span id="logo-placeholder" class="text-muted" style="font-size:.8rem;<?= !empty($certSettings->logo) ? 'display:none;' : '' ?>">Belum ada logo</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-muted mb-3 mt-4"><i class="fas fa-pen-fancy mr-1"></i> Penandatangan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="signer_name">Nama Penandatangan</label>
                                <input type="text" name="signer_name" id="signer_name" class="form-control" value="<?= esc($certSettings->signer_name) ?>" placeholder="Contoh: Budi Santoso">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="signer_title">Jabatan</label>
                                <input type="text" name="signer_title" id="signer_title" class="form-control" value="<?= esc($certSettings->signer_title) ?>" placeholder="Contoh: Kepala Platform">
                            </div>
                        </div>
                    </div>

                    <h5 class="text-muted mb-3 mt-4"><i class="fas fa-file-alt mr-1"></i> Teks Sertifikat</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificate_title">Judul Utama</label>
                                <input type="text" name="certificate_title" id="certificate_title" class="form-control" value="<?= esc($certSettings->certificate_title) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificate_subtitle">Sub Judul</label>
                                <input type="text" name="certificate_subtitle" id="certificate_subtitle" class="form-control" value="<?= esc($certSettings->certificate_subtitle) ?>">
                            </div>
                        </div>
                    </div>

                    <h5 class="text-muted mb-3 mt-4"><i class="fas fa-palette mr-1"></i> Warna</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="border_color">Warna Border</label>
                                <div class="input-group">
                                    <input type="color" name="border_color" id="border_color" class="form-control" value="<?= esc($certSettings->border_color) ?>" style="height:38px;max-width:60px;">
                                    <input type="text" class="form-control" value="<?= esc($certSettings->border_color) ?>" readonly style="background:#fff;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="accent_color">Warna Aksen</label>
                                <div class="input-group">
                                    <input type="color" name="accent_color" id="accent_color" class="form-control" value="<?= esc($certSettings->accent_color) ?>" style="height:38px;max-width:60px;">
                                    <input type="text" class="form-control" value="<?= esc($certSettings->accent_color) ?>" readonly style="background:#fff;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Pengaturan ini menentukan tampilan sertifikat yang di-generate untuk peserta.</p>
                <ul class="text-muted" style="font-size:.9rem;">
                    <li><strong>Logo</strong> — muncul di atas judul sertifikat</li>
                    <li><strong>Nama Penandatangan</strong> — muncul di bagian bawah sertifikat</li>
                    <li><strong>Jabatan</strong> — label di bawah nama penandatangan</li>
                    <li><strong>Warna</strong> — kustomisasi warna border dan aksen</li>
                </ul>
                <hr>
                <a href="/admin/courses" class="btn btn-secondary btn-block btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('logo-input').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('logo-preview').src = ev.target.result;
            document.getElementById('logo-preview').style.display = 'block';
            document.getElementById('logo-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
