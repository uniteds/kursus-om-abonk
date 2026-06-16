<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $course ? 'Edit Kursus' : 'Tambah Kursus' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $course ? '/admin/courses/update/' . $course->id : '/admin/courses/store' ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="title">Judul Kursus *</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= esc($course->title ?? old('title')) ?>" required>
            </div>
            <div class="form-group">
                <label for="category_id">Kategori *</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= ($course->category_id ?? old('category_id')) == $cat->id ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="4"><?= esc($course->description ?? old('description')) ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="meetings_count">Jumlah Pertemuan</label>
                        <input type="number" name="meetings_count" id="meetings_count" class="form-control" value="<?= esc($course->meetings_count ?? old('meetings_count')) ?>" min="0" placeholder="Contoh: 12">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="meeting_duration">Durasi per Pertemuan (menit)</label>
                        <input type="number" name="meeting_duration" id="meeting_duration" class="form-control" value="<?= esc($course->meeting_duration ?? old('meeting_duration')) ?>" min="0" placeholder="Contoh: 90">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" name="price" id="price" class="form-control" value="<?= esc($course->price ?? old('price', 0)) ?>" min="0" step="1000" placeholder="0 = Gratis">
                        <small class="form-text text-muted">Isi 0 jika kursus gratis</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="curriculum">Kurikulum / Silabus</label>
                <textarea name="curriculum" id="curriculum" class="form-control" rows="6" placeholder="Tulis kurikulum atau silabus kursus di sini..."><?= esc($course->curriculum ?? old('curriculum')) ?></textarea>
                <small class="form-text text-muted">Gunakan baris baru untuk setiap topik. Contoh:&#10;1. Pengenalan Dasar&#10;2. Variabel dan Tipe Data&#10;3. Percabangan</small>
            </div>
            <div class="form-group">
                <label for="thumbnail">Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                <?php if (!empty($course->thumbnail)): ?>
                    <div class="mt-1"><img src="/uploads/thumbnails/<?= esc($course->thumbnail) ?>" alt="" width="100" class="rounded"></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" <?= ($course->is_active ?? 1) ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/admin/courses" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
