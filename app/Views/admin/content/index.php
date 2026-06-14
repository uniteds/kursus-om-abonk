<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Konten</h3>
        <div class="card-tools">
            <a href="/admin/content/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Konten</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3">
            <div class="input-group input-group-sm" style="width: 300px;">
                <input type="text" name="q" class="form-control" placeholder="Cari konten..." value="<?= esc($keyword ?? '') ?>">
                <div class="input-group-append"><button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Judul</th>
                        <th>Kelas</th>
                        <th>Kursus</th>
                        <th width="80">Tipe</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contents)): ?>
                        <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($contents as $c): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($c->title) ?></td>
                                <td><?= esc($c->class_name ?? '-') ?></td>
                                <td><?= esc($c->course_title ?? '-') ?></td>
                                <td>
                                    <?php
                                    $typeIcons = ['video' => 'video', 'document' => 'file-alt', 'link' => 'link'];
                                    $typeColors = ['video' => 'danger', 'document' => 'primary', 'link' => 'info'];
                                    ?>
                                    <span class="badge badge-<?= $typeColors[$c->type] ?? 'secondary' ?>">
                                        <i class="fas fa-<?= $typeIcons[$c->type] ?? 'file' ?>"></i> <?= ucfirst($c->type) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/content/edit/<?= $c->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/content/delete/<?= $c->id ?>')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
