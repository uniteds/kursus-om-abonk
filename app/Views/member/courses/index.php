<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="form-inline">
            <div class="input-group" style="width: 400px;">
                <input type="text" name="q" class="form-control" placeholder="Cari kursus..." value="<?= esc($keyword ?? '') ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <?php if (empty($courses)): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada kursus yang tersedia.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($courses as $course): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if ($course->thumbnail): ?>
                        <img src="/uploads/thumbnails/<?= esc($course->thumbnail) ?>" class="card-img-top" alt="<?= esc($course->title) ?>" style="height:200px;object-fit:cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="fas fa-graduation-cap fa-3x text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge badge-primary mb-2"><?= esc($course->category_name ?? 'Umum') ?></span>
                        <h5 class="card-title"><?= esc($course->title) ?></h5>
                        <p class="card-text text-muted" style="font-size:0.9rem;">
                            <?= esc(mb_strimwidth($course->description ?? '', 0, 100, '...')) ?>
                        </p>
                        <p class="mb-0">
                            <?php if ($course->price > 0): ?>
                                <span class="text-primary font-weight-bold">Rp <?= number_format($course->price, 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="badge badge-success">Gratis</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="/member/courses/view/<?= $course->id ?>" class="btn btn-primary btn-block"><i class="fas fa-info-circle"></i> Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-center">
    <?= $pager->links() ?>
</div>

<?= $this->endSection() ?>
