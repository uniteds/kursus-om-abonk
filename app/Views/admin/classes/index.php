<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kelas</h3>
        <div class="card-tools">
            <a href="/admin/classes/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Kelas</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3">
            <div class="input-group input-group-sm" style="width: 300px;">
                <input type="text" name="q" class="form-control" placeholder="Cari kelas..." value="<?= esc($keyword ?? '') ?>">
                <div class="input-group-append"><button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Kelas</th>
                        <th>Kursus</th>
                        <th>Jadwal</th>
                        <th>Kuota</th>
                        <th width="80">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classes)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($classes as $cls): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($cls->name) ?></td>
                                <td><span class="badge badge-primary"><?= esc($cls->course_title ?? '-') ?></span></td>
                                <td><?= esc($cls->schedule ?? '-') ?></td>
                                <td><?= $cls->capacity ?></td>
                                <td>
                                    <?php
                                    $statusColors = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                                    $color = $statusColors[$cls->status] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $color ?>"><?= ucfirst($cls->status) ?></span>
                                </td>
                                <td>
                                    <a href="/admin/classes/view/<?= $cls->id ?>" class="btn btn-info btn-sm" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                    <a href="/admin/classes/edit/<?= $cls->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/classes/delete/<?= $cls->id ?>')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
