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

        <!-- Materi -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-book-open mr-1"></i> Materi Kelas</h3>
                <div class="card-tools">
                    <span class="badge badge-primary"><?= count($materials) ?> Materi</span>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($materials)): ?>
                    <p class="text-muted text-center py-4">Belum ada materi untuk kelas ini.</p>
                <?php else: ?>
                    <?php
                    $typeConfig = [
                        'document' => ['icon' => 'fas fa-file-alt', 'color' => 'primary', 'label' => 'Dokumen'],
                        'video'    => ['icon' => 'fas fa-video', 'color' => 'danger', 'label' => 'Video'],
                        'link'     => ['icon' => 'fas fa-link', 'color' => 'info', 'label' => 'Link'],
                        'slide'    => ['icon' => 'fas fa-chalkboard', 'color' => 'warning', 'label' => 'Slide'],
                        'tugas'    => ['icon' => 'fas fa-tasks', 'color' => 'success', 'label' => 'Tugas'],
                        'other'    => ['icon' => 'fas fa-ellipsis-h', 'color' => 'secondary', 'label' => 'Lainnya'],
                    ];
                    ?>
                    <?php foreach ($materials as $idx => $m): ?>
                        <?php $tc = $typeConfig[$m->type] ?? $typeConfig['other']; ?>
                        <div class="list-group-item list-group-item-action" style="border-left:3px solid var(--<?= $tc['color'] ?>-color, #6c757d);">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <span class="badge badge-<?= $tc['color'] ?> mr-2">
                                        <i class="<?= $tc['icon'] ?>"></i> <?= $tc['label'] ?>
                                    </span>
                                    <strong><?= esc($m->title) ?></strong>
                                    <?php if ($m->description): ?>
                                        <br><small class="text-muted"><?= esc(mb_strimwidth($m->description, 0, 120, '...')) ?></small>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if ($m->type === 'link' && $m->external_url): ?>
                                        <a href="<?= esc($m->external_url) ?>" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-external-link-alt"></i> Buka Link</a>
                                    <?php elseif ($m->file_path): ?>
                                        <a href="/member/class-materials/download/<?= $class->id ?>/<?= $m->id ?>" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Download</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                            <p class="mb-1 text-muted" style="font-size:0.85rem;"><?= $a->body ?></p>
                            <small class="text-muted"><i class="fas fa-clock"></i> <?= date('d M Y H:i', strtotime($a->created_at ?? 'now')) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
