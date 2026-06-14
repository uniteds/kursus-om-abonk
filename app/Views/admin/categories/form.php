<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $category ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $category ? '/admin/categories/update/' . $category->id : '/admin/categories/store' ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="name">Nama Kategori *</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= esc($category->name ?? old('name')) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?= esc($category->description ?? old('description')) ?></textarea>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/admin/categories" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
