<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengumuman</h3>
        <div class="card-tools">
            <a href="/admin/announcements/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pengumuman</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Judul</th>
                        <th>Kelas</th>
                        <th>Kursus</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($announcements)): ?>
                        <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($announcements as $a): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($a->title) ?></td>
                                <td><?= esc($a->class_name ?? '-') ?></td>
                                <td><?= esc($a->course_title ?? '-') ?></td>
                                <td>
                                    <a href="/admin/announcements/edit/<?= $a->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/announcements/delete/<?= $a->id ?>')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
