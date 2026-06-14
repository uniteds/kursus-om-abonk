<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Users</h3>
        <div class="card-tools">
            <a href="/admin/users/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah User</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3">
            <div class="input-group input-group-sm" style="width: 300px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama/email..." value="<?= esc($keyword ?? '') ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="100">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($user->name) ?></td>
                                <td><?= esc($user->email) ?></td>
                                <td>
                                    <?php if ($user->role === 'admin'): ?>
                                        <span class="badge badge-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Member</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user->is_active): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/users/edit/<?= $user->id ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="/admin/users/toggle/<?= $user->id ?>" class="btn btn-sm btn-<?= $user->is_active ? 'secondary' : 'success' ?>" title="<?= $user->is_active ? 'Nonaktifkan' : 'Aktifkan' ?>"><i class="fas fa-<?= $user->is_active ? 'ban' : 'check' ?>"></i></a>
                                    <button onclick="confirmDelete('/admin/users/delete/<?= $user->id ?>')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->endSection() ?>
