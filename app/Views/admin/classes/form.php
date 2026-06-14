<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $class ? 'Edit Kelas' : 'Tambah Kelas' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $class ? '/admin/classes/update/' . $class->id : '/admin/classes/store' ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="course_id">Kursus *</label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="">-- Pilih Kursus --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course->id ?>" <?= ($class->course_id ?? old('course_id')) == $course->id ? 'selected' : '' ?>><?= esc($course->title) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Nama Kelas *</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= esc($class->name ?? old('name')) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?= esc($class->description ?? old('description')) ?></textarea>
            </div>
            <div class="form-group">
                <label for="schedule">Jadwal</label>
                <input type="text" name="schedule" id="schedule" class="form-control" value="<?= esc($class->schedule ?? old('schedule')) ?>" placeholder="Contoh: Senin-Rabu, 19:00-21:00">
            </div>
            <div class="form-group">
                <label for="capacity">Kuota</label>
                <input type="number" name="capacity" id="capacity" class="form-control" value="<?= esc($class->capacity ?? 30) ?>" min="1">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="upcoming" <?= ($class->status ?? 'upcoming') === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                    <option value="ongoing" <?= ($class->status ?? '') === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                    <option value="completed" <?= ($class->status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/admin/classes" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
