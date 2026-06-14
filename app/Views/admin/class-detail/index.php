<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php
$statusColors = ['upcoming' => 'warning', 'ongoing' => 'success', 'completed' => 'secondary'];
$statusLabels = ['upcoming' => 'Akan Datang', 'ongoing' => 'Berlangsung', 'completed' => 'Selesai'];
$color = $statusColors[$class->status] ?? 'secondary';
$colorLabel = $statusLabels[$class->status] ?? ucfirst($class->status);
    $occupied = count($enrollments);
    $fillPercent = $class->capacity > 0 ? round(($occupied / $class->capacity) * 100) : 0;
    $materialCount = count($materials);
?>

<div class="row">
    <div class="col-md-6">
        <h4 class="mb-0"><i class="fas fa-chalkboard mr-1"></i> <?= esc($class->name) ?></h4>
        <small class="text-muted">Kursus: <?= esc($class->course_title ?? '-') ?></small>
    </div>
    <div class="col-md-6 text-right">
        <a href="/admin/classes" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="/admin/classes/edit/<?= $class->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
    </div>
</div>

<!-- Info Cards -->
<div class="row mt-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-<?= $color ?>">
            <div class="inner">
                <h3><?= $occupied ?>/<?= $class->capacity ?></h3>
                <p>Terdaftar / Kuota</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="small-box-footer" style="background:rgba(0,0,0,.1);padding:.4rem .8rem;">
                <div class="progress progress-sm m-0" style="height:6px;">
                    <div class="progress-bar bg-white" style="width:<?= $fillPercent ?>%"></div>
                </div>
                <small><?= $fillPercent ?>% terisi</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $pendingCount ?></h3>
                <p>Menunggu</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="#siswa" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $approvedCount ?></h3>
                <p>Disetujui</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="#siswa" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $materialCount ?></h3>
                <p>Materi</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="#materi" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="card mt-3">
    <div class="card-header p-0">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'info' ? 'active' : '' ?>" href="?tab=info">
                    <i class="fas fa-info-circle"></i> Info Kelas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'materi' ? 'active' : '' ?>" href="?tab=materi">
                    <i class="fas fa-book"></i> Materi <span class="badge badge-primary ml-1"><?= $materialCount ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'siswa' ? 'active' : '' ?>" href="?tab=siswa">
                    <i class="fas fa-users"></i> Siswa <span class="badge badge-warning ml-1"><?= $pendingCount ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'pengumuman' ? 'active' : '' ?>" href="?tab=pengumuman">
                    <i class="fas fa-bullhorn"></i> Pengumuman <span class="badge badge-info ml-1"><?= count($announcements) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'aktivitas' ? 'active' : '' ?>" href="?tab=aktivitas">
                    <i class="fas fa-history"></i> Aktivitas
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">

        <!-- TAB: INFO KELAS -->
        <?php if ($tab === 'info'): ?>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr><th width="150">Kursus</th><td><?= esc($class->course_title ?? '-') ?></td></tr>
                        <tr><th>Nama Kelas</th><td><strong><?= esc($class->name) ?></strong></td></tr>
                        <tr><th>Jadwal</th><td><?= esc($class->schedule ?? '-') ?></td></tr>
                        <tr><th>Kuota</th><td><?= $class->capacity ?> orang</td></tr>
                        <tr><th>Status</th><td><span class="badge badge-<?= $color ?>"><?= $colorLabel ?></span></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5><i class="fas fa-chart-pie"></i> Statistik Kelas</h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending</span>
                                    <span class="info-box-number"><?= $pendingCount ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Approved</span>
                                    <span class="info-box-number"><?= $approvedCount ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rejected</span>
                                    <span class="info-box-number"><?= $rejectedCount ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-3"><i class="fas fa-align-left"></i> Deskripsi</h5>
                    <?php if ($class->description): ?>
                        <div class="border p-3 rounded" style="background:#f8f9fa;"><?= nl2br(esc($class->description)) ?></div>
                    <?php else: ?>
                        <p class="text-muted">Tidak ada deskripsi.</p>
                    <?php endif; ?>
                </div>
            </div>

        <!-- TAB: MATERI -->
        <?php elseif ($tab === 'materi'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-book mr-1"></i> Materi Kelas</h5>
                <a href="/admin/classes/materials/<?= $class->id ?>" class="btn btn-primary btn-sm"><i class="fas fa-cog"></i> Kelola Materi</a>
            </div>
            <?php if (empty($materials)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada materi untuk kelas ini.</p>
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
                                <th width="80">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $idx => $m): ?>
                                <tr>
                                    <td><?= $idx + 1 ?></td>
                                    <td class="text-center"><span class="badge badge-secondary"><?= $m->sort_order ?></span></td>
                                    <td><strong><?= esc($m->title) ?></strong></td>
                                    <td>
                                        <?php
                                        $tc = [
                                            'document' => ['icon' => 'fas fa-file-alt', 'color' => 'primary', 'label' => 'Dokumen'],
                                            'video'    => ['icon' => 'fas fa-video', 'color' => 'danger', 'label' => 'Video'],
                                            'link'     => ['icon' => 'fas fa-link', 'color' => 'info', 'label' => 'Link'],
                                            'slide'    => ['icon' => 'fas fa-chalkboard', 'color' => 'warning', 'label' => 'Slide'],
                                            'tugas'    => ['icon' => 'fas fa-tasks', 'color' => 'success', 'label' => 'Tugas'],
                                            'other'    => ['icon' => 'fas fa-ellipsis-h', 'color' => 'secondary', 'label' => 'Lainnya'],
                                        ];
                                        $t = $tc[$m->type] ?? $tc['other'];
                                        ?>
                                        <span class="badge badge-<?= $t['color'] ?>"><i class="<?= $t['icon'] ?>"></i> <?= $t['label'] ?></span>
                                    </td>
                                    <td>
                                        <?php if ($m->type === 'link' && $m->external_url): ?>
                                            <a href="<?= esc($m->external_url) ?>" target="_blank"><i class="fas fa-external-link-alt"></i> Buka</a>
                                        <?php elseif ($m->file_path): ?>
                                            <a href="/admin/classes/materials/download/<?= $class->id ?>/<?= $m->id ?>"><i class="fas fa-download"></i> File</a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($m->is_published): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Draft</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <!-- TAB: SISWA -->
        <?php elseif ($tab === 'siswa'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-users mr-1"></i> Siswa Terdaftar</h5>
                <div>
                    <span class="badge badge-warning mr-1">Pending: <?= $pendingCount ?></span>
                    <span class="badge badge-success mr-1">Approved: <?= $approvedCount ?></span>
                    <span class="badge badge-danger">Rejected: <?= $rejectedCount ?></span>
                </div>
            </div>
            <?php if (empty($enrollments)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada siswa terdaftar di kelas ini.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th width="100">Status</th>
                                <th width="130">Tanggal Daftar</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $idx => $e): ?>
                                <?php
                                $eStatusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'completed' => 'info'];
                                ?>
                                <tr>
                                    <td><?= $idx + 1 ?></td>
                                    <td><strong><?= esc($e->user_name ?? '-') ?></strong></td>
                                    <td><?= esc($e->user_email ?? '-') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $eStatusColors[$e->status] ?? 'secondary' ?>">
                                            <?= ucfirst($e->status) ?>
                                        </span>
                                    </td>
                                    <td><small><?= date('d M Y H:i', strtotime($e->enrolled_at ?? $e->created_at)) ?></small></td>
                                    <td>
                                        <?php if ($e->status === 'pending'): ?>
                                            <a href="/admin/classes/approve-enrollment/<?= $class->id ?>/<?= $e->id ?>" class="btn btn-success btn-sm" title="Setujui"><i class="fas fa-check"></i></a>
                                            <a href="/admin/classes/reject-enrollment/<?= $class->id ?>/<?= $e->id ?>" class="btn btn-danger btn-sm" title="Tolak"><i class="fas fa-times"></i></a>
                                        <?php elseif ($e->status === 'approved'): ?>
                                            <a href="/admin/classes/reject-enrollment/<?= $class->id ?>/<?= $e->id ?>" class="btn btn-warning btn-sm" title="Batalkan"><i class="fas fa-ban"></i></a>
                                        <?php elseif ($e->status === 'rejected'): ?>
                                            <a href="/admin/classes/approve-enrollment/<?= $class->id ?>/<?= $e->id ?>" class="btn btn-success btn-sm" title="Setujui"><i class="fas fa-check"></i></a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <!-- TAB: PENGUMUMAN -->
        <?php elseif ($tab === 'pengumuman'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-bullhorn mr-1"></i> Pengumuman Kelas</h5>
                <a href="/admin/announcements/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pengumuman</a>
            </div>
            <?php if (empty($announcements)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pengumuman untuk kelas ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $a): ?>
                    <div class="card card-outline card-info mb-2">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?= esc($a->title) ?></strong>
                                    <div class="text-muted" style="font-size:.85rem;"><?= $a->body ?></div>
                                </div>
                                <div class="text-nowrap ml-3">
                                    <small class="text-muted"><i class="fas fa-clock"></i> <?= date('d M Y', strtotime($a->created_at)) ?></small>
                                    <a href="/admin/announcements/edit/<?= $a->id ?>" class="btn btn-warning btn-sm ml-1"><i class="fas fa-edit"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <!-- TAB: AKTIVITAS -->
        <?php elseif ($tab === 'aktivitas'): ?>
            <h5><i class="fas fa-history mr-1"></i> Aktivitas Terbaru</h5>
            <?php if (empty($enrollments)): ?>
                <p class="text-muted text-center py-3">Belum ada aktivitas.</p>
            <?php else: ?>
                <div class="timeline timeline-inverse">
                    <?php foreach (array_slice($enrollments, 0, 20) as $e): ?>
                        <?php
                        $timelineIcons = [
                            'pending'   => 'fas fa-hourglass-half text-warning',
                            'approved'  => 'fas fa-check-circle text-success',
                            'rejected'  => 'fas fa-times-circle text-danger',
                            'completed' => 'fas fa-flag-checkered text-info',
                        ];
                        $timelineLabels = [
                            'pending'   => 'mendaftar (menunggu persetujuan)',
                            'approved'  => 'disetujui masuk kelas',
                            'rejected'  => 'pendaftaran ditolak',
                            'completed' => 'menyelesaikan kelas',
                        ];
                        $icon = $timelineIcons[$e->status] ?? 'fas fa-circle text-secondary';
                        $label = $timelineLabels[$e->status] ?? $e->status;
                        ?>
                        <div class="time-label">
                            <span class="bg <?= $e->status === 'approved' ? 'bg-success' : ($e->status === 'pending' ? 'bg-warning' : ($e->status === 'rejected' ? 'bg-danger' : 'bg-info')) ?>">
                                <?= date('d M Y', strtotime($e->enrolled_at ?? $e->created_at)) ?>
                            </span>
                        </div>
                        <div>
                            <i class="<?= $icon ?>"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> <?= date('H:i', strtotime($e->enrolled_at ?? $e->created_at)) ?></span>
                                <h3 class="timeline-header" style="font-size:.9rem;"><?= esc($e->user_name ?? 'Siswa #' . $e->user_id) ?> <?= $label ?></h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>
