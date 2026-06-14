<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Artikel</h3>
        <div class="card-tools">
            <a href="/admin/content/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Artikel</a>
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
                <input type="text" name="q" class="form-control" placeholder="Cari artikel..." value="<?= esc($keyword ?? '') ?>">
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
                        <th width="100">Kategori</th>
                        <th width="80">Status</th>
                        <th width="80">Views</th>
                        <th width="120">Tanggal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contents)): ?>
                        <tr><td colspan="7" class="text-center">Tidak ada artikel.</td></tr>
                    <?php else: ?>
                        <?php $i = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($contents as $c): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <strong><?= esc($c->title) ?></strong>
                                    <?php if (!empty($c->excerpt)): ?>
                                        <br><small class="text-muted"><?= esc(substr($c->excerpt, 0, 80)) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $catColors = ['berita' => 'danger', 'tutorial' => 'info', 'artikel' => 'primary'];
                                    ?>
                                    <span class="badge badge-<?= $catColors[$c->category] ?? 'secondary' ?>">
                                        <?= ucfirst($c->category) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($c->is_published): ?>
                                        <span class="badge badge-success">Publish</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= number_format($c->views ?? 0) ?></td>
                                <td><small><?= date('d M Y', strtotime($c->created_at)) ?></small></td>
                                <td>
                                    <?php if ($c->is_published): ?>
                                        <a href="/artikel/<?= esc($c->slug) ?>" class="btn btn-info btn-sm" target="_blank" title="Lihat"><i class="fas fa-eye"></i></a>
                                    <?php endif; ?>
                                    <a href="/admin/content/edit/<?= $c->id ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/content/delete/<?= $c->id ?>')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
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
