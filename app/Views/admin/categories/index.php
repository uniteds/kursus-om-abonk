<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-tools">
            <a href="/admin/categories/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Kategori</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($cat->name) ?></td>
                                <td><code><?= esc($cat->slug) ?></code></td>
                                <td><?= esc($cat->description ?? '-') ?></td>
                                <td>
                                    <a href="/admin/categories/edit/<?= $cat->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/categories/delete/<?= $cat->id ?>')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
