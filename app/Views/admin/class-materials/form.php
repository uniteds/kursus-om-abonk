<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-0"><i class="fas fa-book-open mr-1"></i> <?= $material ? 'Edit Materi' : 'Tambah Materi' ?></h4>
        <small class="text-muted">Kelas: <?= esc($class->name) ?> (<?= esc($class->course_title ?? '-') ?>)</small>
    </div>
    <div class="col-md-4 text-right">
        <a href="/admin/classes/materials/<?= $class->id ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $material ? '/admin/classes/materials/update/' . $class->id . '/' . $material->id : '/admin/classes/materials/store/' . $class->id ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Judul Materi *</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= esc($material->title ?? old('title')) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Penjelasan singkat tentang materi ini..."><?= esc($material->description ?? old('description')) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="type">Tipe Materi *</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="document" <?= ($material->type ?? old('type')) === 'document' ? 'selected' : '' ?>>📄 Dokumen (PDF, Word, dll)</option>
                            <option value="slide" <?= ($material->type ?? old('type')) === 'slide' ? 'selected' : '' ?>>📊 Slide Presentasi</option>
                            <option value="video" <?= ($material->type ?? old('type')) === 'video' ? 'selected' : '' ?>>🎬 Video</option>
                            <option value="tugas" <?= ($material->type ?? old('type')) === 'tugas' ? 'selected' : '' ?>>📝 Tugas</option>
                            <option value="link" <?= ($material->type ?? old('type')) === 'link' ? 'selected' : '' ?>>🔗 Link Eksternal</option>
                            <option value="other" <?= ($material->type ?? old('type')) === 'other' ? 'selected' : '' ?>>📁 Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group" id="file_upload_group">
                        <label for="file">File Upload</label>
                        <div class="custom-file">
                            <input type="file" name="file" id="file" class="custom-file-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.mp4,.avi,.mov,.jpg,.jpeg,.png">
                            <label class="custom-file-label" for="file">Pilih file...</label>
                        </div>
                        <small class="text-muted">Maks 50MB. Format: PDF, DOC, PPTX, XLSX, ZIP, MP4, gambar.</small>
                        <?php if (!empty($material->file_path)): ?>
                            <div class="mt-2 p-2 bg-light rounded">
                                <i class="fas fa-file"></i> File saat ini: <strong><?= esc($material->file_path) ?></strong>
                                <a href="/uploads/materials/<?= esc($material->file_path) ?>" target="_blank" class="ml-2"><i class="fas fa-eye"></i> Lihat</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" id="url_group" style="display:none;">
                        <label for="external_url">URL Link *</label>
                        <input type="url" name="external_url" id="external_url" class="form-control" value="<?= esc($material->external_url ?? old('external_url')) ?>" placeholder="https://drive.google.com/...">
                        <small class="text-muted">Masukkan URL Google Drive, YouTube, atau link lainnya.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog"></i> Pengaturan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="sort_order">Urutan Tampil</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control" value="<?= esc($material->sort_order ?? $nextOrder) ?>" min="0">
                                <small class="text-muted">Urutan materi ditampilkan (0 = paling atas).</small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_published" id="is_published" class="custom-control-input" value="1" <?= ($material->is_published ?? 1) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="is_published">Tampilkan ke Siswa</label>
                                </div>
                                <small class="text-muted">Centang agar siswa bisa melihat materi ini.</small>
                            </div>

                            <?php if (!empty($material->downloads)): ?>
                                <div class="text-muted mt-2"><i class="fas fa-download"></i> <?= $material->downloads ?> kali didownload</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Simpan Materi</button>
                        <a href="/admin/classes/materials/<?= $class->id ?>" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('type').addEventListener('change', function() {
    const isLink = this.value === 'link';
    document.getElementById('file_upload_group').style.display = isLink ? 'none' : 'block';
    document.getElementById('url_group').style.display = isLink ? 'block' : 'none';
});
document.getElementById('type').dispatchEvent(new Event('change'));

// File input label update
document.querySelector('.custom-file-input').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
    this.nextElementSibling.textContent = fileName;
});
</script>
<?= $this->endSection() ?>
