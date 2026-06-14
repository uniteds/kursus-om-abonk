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

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Judul Pengumuman *</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= esc($announcement->title ?? old('title')) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="body">Isi Pengumuman *</label>
                        <textarea name="body" id="body" class="form-control tinymce-editor"><?= esc($announcement->body ?? old('body')) ?></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog"></i> Pengaturan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="type">Tipe Pengumuman *</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="umum" <?= ($announcement->type ?? old('type')) === 'umum' ? 'selected' : '' ?>>📢 Umum</option>
                                    <option value="kelas" <?= ($announcement->type ?? old('type')) === 'kelas' ? 'selected' : '' ?>>🏫 Kelas</option>
                                    <option value="diskon" <?= ($announcement->type ?? old('type')) === 'diskon' ? 'selected' : '' ?>>🏷️ Diskon</option>
                                    <option value="event" <?= ($announcement->type ?? old('type')) === 'event' ? 'selected' : '' ?>>📅 Event</option>
                                    <option value="lainnya" <?= ($announcement->type ?? old('type')) === 'lainnya' ? 'selected' : '' ?>>📌 Lainnya</option>
                                </select>
                            </div>

                            <div class="form-group" id="class_select_group" style="display:none;">
                                <label for="class_id">Kelas *</label>
                                <select name="class_id" id="class_id" class="form-control">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($classes as $cls): ?>
                                        <option value="<?= $cls->id ?>" <?= ($announcement->class_id ?? old('class_id')) == $cls->id ? 'selected' : '' ?>><?= esc($cls->course_title ?? 'Kursus') ?> - <?= esc($cls->name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="target">Target</label>
                                <select name="target" id="target" class="form-control">
                                    <option value="semua" <?= ($announcement->target ?? old('target')) === 'semua' ? 'selected' : '' ?>>Semua (Publik + Member)</option>
                                    <option value="member" <?= ($announcement->target ?? old('target')) === 'member' ? 'selected' : '' ?>>Member Saja</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="icon">Icon (FontAwesome)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="<?= esc($announcement->icon ?? 'fas fa-bullhorn') ?>" id="icon_preview"></i></span>
                                    <input type="text" name="icon" id="icon" class="form-control" value="<?= esc($announcement->icon ?? 'fas fa-bullhorn') ?>" placeholder="fas fa-bullhorn">
                                </div>
                                <small class="text-muted">Contoh: <code>fas fa-bullhorn</code>, <code>fas fa-tags</code>, <code>fas fa-calendar</code>, <code>fas fa-gift</code></small>
                            </div>

                            <div class="form-group">
                                <label for="color">Warna Badge</label>
                                <select name="color" id="color" class="form-control">
                                    <?php
                                    $colors = [
                                        'primary'   => 'Biru (Primary)',
                                        'success'   => 'Hijau (Success)',
                                        'danger'    => 'Merah (Danger)',
                                        'warning'   => 'Kuning (Warning)',
                                        'info'      => 'Biru Muda (Info)',
                                        'secondary' => 'Abu-abu (Secondary)',
                                        'dark'      => 'Hitam (Dark)',
                                    ];
                                    foreach ($colors as $val => $label):
                                    ?>
                                        <option value="<?= $val ?>" <?= ($announcement->color ?? old('color', 'primary')) === $val ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" <?= ($announcement->is_active ?? old('is_active', 1)) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="is_active">Aktifkan</label>
                                </div>
                            </div>

                            <?php if (!empty($announcement->published_at)): ?>
                                <div class="text-muted"><small>Dipublikasikan: <?= date('d M Y H:i', strtotime($announcement->published_at)) ?></small></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Simpan</button>
                        <a href="/admin/announcements" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.4/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '.tinymce-editor',
    height: 400,
    menubar: true,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic underline strikethrough | forecolor backcolor | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | ' +
        'link image media | removeformat | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; line-height: 1.6; }',
    branding: false,
    promotion: false,
    language: 'id',
    language_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.4/langs6/id.js'
});

// Toggle class select
function toggleClassSelect() {
    const type = document.getElementById('type').value;
    document.getElementById('class_select_group').style.display = type === 'kelas' ? 'block' : 'none';
}
document.getElementById('type').addEventListener('change', toggleClassSelect);
toggleClassSelect();

// Icon preview
document.getElementById('icon').addEventListener('input', function() {
    document.getElementById('icon_preview').className = this.value || 'fas fa-bullhorn';
});
</script>
<?= $this->endSection() ?>
