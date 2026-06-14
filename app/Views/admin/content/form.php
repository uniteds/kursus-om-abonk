<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $content ? 'Edit Artikel' : 'Tambah Artikel' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $content ? '/admin/content/update/' . $content->id : '/admin/content/store' ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Judul Artikel *</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= esc($content->title ?? old('title')) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="excerpt">Ringkasan</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="3" placeholder="Ringkasan singkat artikel..."><?= esc($content->excerpt ?? old('excerpt')) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="body">Konten Artikel *</label>
                        <textarea name="body" id="body" class="form-control tinymce-editor"><?= esc($content->body ?? old('body')) ?></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-cog"></i> Pengaturan</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category">Kategori *</label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="artikel" <?= ($content->category ?? old('category')) === 'artikel' ? 'selected' : '' ?>>Artikel</option>
                                    <option value="tutorial" <?= ($content->category ?? old('category')) === 'tutorial' ? 'selected' : '' ?>>Tutorial</option>
                                    <option value="berita" <?= ($content->category ?? old('category')) === 'berita' ? 'selected' : '' ?>>Berita</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Meta Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Untuk SEO (opsional)"><?= esc($content->description ?? old('description')) ?></textarea>
                                <small class="text-muted">Maksimal 160 karakter untuk SEO.</small>
                            </div>

                            <div class="form-group">
                                <label for="thumbnail">Thumbnail</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                                <?php if (!empty($content->thumbnail)): ?>
                                    <div class="mt-2">
                                        <img src="/uploads/thumbnails/<?= esc($content->thumbnail) ?>" class="img-fluid rounded" style="max-height:150px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_published" id="is_published" class="custom-control-input" value="1" <?= ($content->is_published ?? old('is_published')) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="is_published">Publikasikan</label>
                                </div>
                            </div>

                            <?php if (!empty($content->published_at)): ?>
                                <div class="text-muted"><small>Dipublikasikan: <?= date('d M Y H:i', strtotime($content->published_at)) ?></small></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Simpan Artikel</button>
                        <a href="/admin/content" class="btn btn-secondary btn-block"><i class="fas fa-arrow-left"></i> Kembali</a>
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
    height: 500,
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
    automatic_uploads: false,
    images_upload_handler: function (blobInfo, success, failure) {
        failure('Upload gambar tidak didukung. Gunakan URL gambar.');
    },
    branding: false,
    promotion: false,
    language: 'id',
    language_url: 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.4/langs6/id.js',
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});
</script>
<?= $this->endSection() ?>
