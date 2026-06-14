<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="mb-0"><i class="fas fa-book-open mr-1"></i> Materi: <?= esc($class->name) ?></h4>
        <small class="text-muted">Kursus: <?= esc($class->course_title ?? '-') ?></small>
    </div>
    <div class="col-md-4 text-right">
        <a href="/admin/classes/view/<?= $class->id ?>?tab=materi" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali ke Kelas</a>
        <a href="/admin/classes/materials/create/<?= $class->id ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Materi</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($materials)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada materi</h5>
                <p class="text-muted">Mulai tambahkan materi seperti dokumen, slide, video, atau tugas untuk kelas ini.</p>
                <a href="/admin/classes/materials/create/<?= $class->id ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Materi Pertama</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th width="50">Urutan</th>
                            <th>Judul Materi</th>
                            <th width="100">Tipe</th>
                            <th width="120">File</th>
                            <th width="80">Download</th>
                            <th width="80">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materials as $idx => $m): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td class="text-center"><span class="badge badge-secondary"><?= $m->sort_order ?></span></td>
                                <td>
                                    <strong><?= esc($m->title) ?></strong>
                                    <?php if ($m->description): ?>
                                        <br><small class="text-muted"><?= esc(mb_strimwidth($m->description, 0, 100, '...')) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $typeConfig = [
                                        'document' => ['icon' => 'fas fa-file-alt', 'color' => 'primary', 'label' => 'Dokumen'],
                                        'video'    => ['icon' => 'fas fa-video', 'color' => 'danger', 'label' => 'Video'],
                                        'link'     => ['icon' => 'fas fa-link', 'color' => 'info', 'label' => 'Link'],
                                        'slide'    => ['icon' => 'fas fa-chalkboard', 'color' => 'warning', 'label' => 'Slide'],
                                        'tugas'    => ['icon' => 'fas fa-tasks', 'color' => 'success', 'label' => 'Tugas'],
                                        'other'    => ['icon' => 'fas fa-ellipsis-h', 'color' => 'secondary', 'label' => 'Lainnya'],
                                    ];
                                    $tc = $typeConfig[$m->type] ?? $typeConfig['other'];
                                    ?>
                                    <span class="badge badge-<?= $tc['color'] ?>"><i class="<?= $tc['icon'] ?>"></i> <?= $tc['label'] ?></span>
                                </td>
                                <td>
                                    <?php if ($m->type === 'link' && $m->external_url): ?>
                                        <a href="<?= esc($m->external_url) ?>" target="_blank" class="text-truncate d-inline-block" style="max-width:120px;">
                                            <i class="fas fa-external-link-alt"></i> Buka
                                        </a>
                                    <?php elseif ($m->file_path): ?>
                                        <a href="/admin/classes/materials/download/<?= $class->id ?>/<?= $m->id ?>" class="text-truncate d-inline-block" style="max-width:120px;">
                                            <i class="fas fa-download"></i> <?= esc(pathinfo($m->file_path, PATHINFO_FILENAME)) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><span class="badge badge-light"><?= $m->downloads ?? 0 ?></span></td>
                                <td>
                                    <?php if ($m->is_published): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/classes/materials/edit/<?= $class->id ?>/<?= $m->id ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('/admin/classes/materials/delete/<?= $class->id ?>/<?= $m->id ?>')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
