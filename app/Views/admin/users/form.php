<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $user ? 'Edit User' : 'Tambah User' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="<?= $user ? '/admin/users/update/' . $user->id : '/admin/users/store' ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="name">Nama Lengkap *</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= esc($user->name ?? old('name')) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= esc($user->email ?? old('email')) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password <?= $user ? '(Kosongkan jika tidak diubah)' : '*' ?></label>
                <input type="password" name="password" id="password" class="form-control" <?= $user ? '' : 'required' ?>>
            </div>
            <div class="form-group">
                <label for="role">Role *</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="member" <?= ($user->role ?? old('role')) === 'member' ? 'selected' : '' ?>>Member</option>
                    <option value="admin" <?= ($user->role ?? old('role')) === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= esc($user->phone ?? old('phone')) ?>">
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" <?= ($user->is_active ?? 1) ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/admin/users" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
