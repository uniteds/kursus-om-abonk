<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Profil Saya</h3>
    </div>
    <div class="card-body">
        <form action="/admin/profile/update" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="name">Nama Lengkap *</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= esc($user->name) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= esc($user->email) ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= esc($user->phone ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="avatar">Foto Profil</label>
                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                <?php if (!empty($user->avatar)): ?>
                    <div class="mt-1"><img src="/uploads/avatars/<?= esc($user->avatar) ?>" alt="" width="80" class="rounded-circle"></div>
                <?php endif; ?>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
