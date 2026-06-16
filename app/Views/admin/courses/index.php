<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kursus</h3>
        <div class="card-tools">
            <a href="/admin/courses/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Kursus</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3">
            <div class="input-group input-group-sm" style="width: 300px;">
                <input type="text" name="q" class="form-control" placeholder="Cari kursus..." value="<?= esc($keyword ?? '') ?>">
                <div class="input-group-append"><button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button></div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Thumbnail</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th width="120">Harga</th>
                        <th width="80">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <?php if ($course->thumbnail): ?>
                                        <img src="/uploads/thumbnails/<?= esc($course->thumbnail) ?>" alt="" width="60" height="40" style="object-fit:cover;" class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($course->title) ?></td>
                                <td><span class="badge badge-primary"><?= esc($course->category_name ?? '-') ?></span></td>
                                <td><?= $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : '<span class="badge badge-success">Gratis</span>' ?></td>
                                <td><?= $course->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>' ?></td>
                                <td>
                                    <a href="/admin/courses/edit/<?= $course->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/courses/delete/<?= $course->id ?>')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
