<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-certificate mr-1"></i> Sertifikat Saya</h3>
            </div>
            <div class="card-body">
                <?php if (empty($certificates)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Anda belum memiliki sertifikat.</p>
                        <p class="text-muted" style="font-size:.9rem;">Sertifikat akan tersedia setelah Anda menyelesaikan kursus.</p>
                        <a href="/member/my-courses" class="btn btn-primary mt-2"><i class="fas fa-book mr-1"></i> Lihat Kursus Saya</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>No. Sertifikat</th>
                                    <th>Kursus</th>
                                    <th>Durasi</th>
                                    <th>Tanggal Terbit</th>
                                    <th width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certificates as $idx => $cert): ?>
                                    <tr>
                                        <td><?= $idx + 1 ?></td>
                                        <td><code><?= esc($cert->certificate_number) ?></code></td>
                                        <td><strong><?= esc($cert->course_title) ?></strong></td>
                                        <td><?= esc($cert->course_duration ?? '-') ?></td>
                                        <td><?= date('d M Y', strtotime($cert->issued_at)) ?></td>
                                        <td>
                                            <a href="/member/certificate/download/<?= $cert->id ?>" class="btn btn-success btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
