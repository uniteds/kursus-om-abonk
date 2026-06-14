<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $content ? 'Edit Konten' : 'Tambah Konten' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $content ? '/admin/content/update/' . $content->id : '/admin/content/store' ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="class_id">Kelas *</label>
                <select name="class_id" id="class_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $cls): ?>
                        <option value="<?= $cls->id ?>" <?= ($content->class_id ?? old('class_id')) == $cls->id ? 'selected' : '' ?>><?= esc($cls->course_title ?? 'Kursus #' . $cls->course_id) ?> - <?= esc($cls->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Judul Konten *</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= esc($content->title ?? old('title')) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?= esc($content->description ?? old('description')) ?></textarea>
            </div>
            <div class="form-group">
                <label for="type">Tipe Konten *</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="document" <?= ($content->type ?? old('type')) === 'document' ? 'selected' : '' ?>>Document</option>
                    <option value="video" <?= ($content->type ?? '') === 'video' ? 'selected' : '' ?>>Video</option>
                    <option value="link" <?= ($content->type ?? '') === 'link' ? 'selected' : '' ?>>Link</option>
                </select>
            </div>
            <div class="form-group" id="file_upload">
                <label for="file">File</label>
                <input type="file" name="file" id="file" class="form-control">
                <?php if (!empty($content->file_path) && $content->type !== 'link'): ?>
                    <div class="mt-1"><small class="text-muted">File saat ini: <?= esc($content->file_path) ?></small></div>
                <?php endif; ?>
            </div>
            <div class="form-group" id="file_link" style="display:none;">
                <label for="file_path">URL Link</label>
                <input type="url" name="file_path" id="file_path" class="form-control" value="<?= esc($content->file_path ?? old('file_path')) ?>" placeholder="https://...">
            </div>
            <div class="form-group">
                <label for="sort_order">Urutan</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control" value="<?= esc($content->sort_order ?? 0) ?>">
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/admin/content" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('type').addEventListener('change', function() {
    const isLink = this.value === 'link';
    document.getElementById('file_upload').style.display = isLink ? 'none' : 'block';
    document.getElementById('file_link').style.display = isLink ? 'block' : 'none';
});
document.getElementById('type').dispatchEvent(new Event('change'));
</script>
<?= $this->endSection() ?>
