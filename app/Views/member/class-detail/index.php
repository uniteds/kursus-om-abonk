<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> <?= esc($class->name) ?></h3>
                <div class="card-tools">
                    <a href="/member/my-courses" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Kursus:</strong> <?= esc($class->course_title ?? '-') ?></p>
                        <p class="mb-1"><strong>Jadwal:</strong> <?= esc($class->schedule ?? '-') ?></p>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $statusColors = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
                        ?>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge badge-<?= $statusColors[$class->status] ?? 'secondary' ?>"><?= ucfirst($class->status) ?></span></p>
                        <p class="mb-1"><strong>Kuota:</strong> <?= $class->capacity ?> orang</p>
                    </div>
                </div>
                <?php if ($class->description): ?>
                    <div class="mt-3"><?= nl2br(esc($class->description)) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Konten -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt mr-1"></i> Materi Kelas</h3>
            </div>
            <div class="card-body">
                <?php if (empty($contents)): ?>
                    <p class="text-muted text-center">Belum ada materi.</p>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($contents as $c): ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <?php
                                        $typeIcons = ['video' => 'video', 'document' => 'file-alt', 'link' => 'link'];
                                        $typeColors = ['video' => 'danger', 'document' => 'primary', 'link' => 'info'];
                                        ?>
                                        <span class="badge badge-<?= $typeColors[$c->type] ?? 'secondary' ?> mr-2">
                                            <i class="fas fa-<?= $typeIcons[$c->type] ?? 'file' ?>"></i> <?= ucfirst($c->type) ?>
                                        </span>
                                        <strong><?= esc($c->title) ?></strong>
                                    </div>
                                    <div>
                                        <?php if ($c->type === 'link' && $c->file_path): ?>
                                            <a href="<?= esc($c->file_path) ?>" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-external-link-alt"></i> Buka</a>
                                        <?php elseif ($c->file_path): ?>
                                            <a href="/uploads/files/<?= esc($c->file_path) ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Download</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($c->description): ?>
                                    <small class="text-muted mt-1 d-block"><?= esc(mb_strimwidth($c->description, 0, 150, '...')) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar: Pengumuman -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bullhorn mr-1"></i> Pengumuman</h3>
            </div>
            <div class="card-body">
                <?php if (empty($announcements)): ?>
                    <p class="text-muted text-center">Belum ada pengumuman.</p>
                <?php else: ?>
                    <?php foreach ($announcements as $a): ?>
                        <div class="border-bottom pb-2 mb-2">
                            <strong><?= esc($a->title) ?></strong>
                            <p class="mb-1 text-muted" style="font-size:0.85rem;"><?= nl2br(esc($a->body)) ?></p>
                            <small class="text-muted"><i class="fas fa-clock"></i> <?= date('d M Y H:i', strtotime($a->created_at ?? 'now')) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
