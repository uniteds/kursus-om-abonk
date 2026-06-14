<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengumuman</h3>
        <div class="card-tools">
            <a href="/admin/announcements/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pengumuman</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form method="GET" class="form-inline mb-3">
            <div class="input-group input-group-sm" style="width: 300px;">
                <input type="text" name="q" class="form-control" placeholder="Cari pengumuman..." value="<?= esc($keyword ?? '') ?>">
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
                        <th>Judul</th>
                        <th width="100">Tipe</th>
                        <th width="100">Target</th>
                        <th width="80">Status</th>
                        <th width="120">Tanggal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($announcements)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada pengumuman.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($announcements as $a): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <strong><i class="<?= esc($a->icon ?? 'fas fa-bullhorn') ?> mr-1"></i> <?= esc($a->title) ?></strong>
                                    <?php if ($a->type === 'kelas' && !empty($a->class_name)): ?>
                                        <br><small class="text-muted">Kelas: <?= esc($a->class_name) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $typeConfig = [
                                        'umum'    => ['label' => 'Umum', 'color' => 'primary', 'icon' => 'fas fa-globe'],
                                        'kelas'   => ['label' => 'Kelas', 'color' => 'info', 'icon' => 'fas fa-chalkboard'],
                                        'diskon'  => ['label' => 'Diskon', 'color' => 'success', 'icon' => 'fas fa-tags'],
                                        'event'   => ['label' => 'Event', 'color' => 'warning', 'icon' => 'fas fa-calendar-star'],
                                        'lainnya' => ['label' => 'Lainnya', 'color' => 'secondary', 'icon' => 'fas fa-ellipsis-h'],
                                    ];
                                    $tc = $typeConfig[$a->type] ?? $typeConfig['lainnya'];
                                    ?>
                                    <span class="badge badge-<?= $tc['color'] ?>"><i class="<?= $tc['icon'] ?>"></i> <?= $tc['label'] ?></span>
                                </td>
                                <td>
                                    <?php if ($a->target === 'semua'): ?>
                                        <span class="badge badge-dark"><i class="fas fa-users"></i> Semua</span>
                                    <?php else: ?>
                                        <span class="badge badge-info"><i class="fas fa-user"></i> Member</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($a->is_active): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?= date('d M Y', strtotime($a->published_at ?? $a->created_at)) ?></small></td>
                                <td>
                                    <a href="/admin/announcements/edit/<?= $a->id ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/announcements/delete/<?= $a->id ?>')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
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
