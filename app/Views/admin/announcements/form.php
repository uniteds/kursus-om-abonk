<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $announcement ? 'Edit Pengumuman' : 'Tambah Pengumuman' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $announcement ? '/admin/announcements/update/' . $announcement->id : '/admin/announcements/store' ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="class_id">Kelas *</label>
                <select name="class_id" id="class_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($classes as $cls): ?>
                        <option value="<?= $cls->id ?>" <?= ($announcement->class_id ?? old('class_id')) == $cls->id ? 'selected' : '' ?>><?= esc($cls->course_title ?? 'Kursus') ?> - <?= esc($cls->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Judul Pengumuman *</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= esc($announcement->title ?? old('title')) ?>" required>
            </div>
            <div class="form-group">
                <label for="body">Isi Pengumuman *</label>
                <textarea name="body" id="body" class="form-control" rows="6" required><?= esc($announcement->body ?? old('body')) ?></textarea>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/admin/announcements" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
