<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Site</h3>
    </div>
    <div class="card-body">
        <form action="/admin/settings/update" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="site_name">Nama Site</label>
                <input type="text" name="site_name" id="site_name" class="form-control" value="<?= esc($settings['site_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="site_description">Deskripsi Site <small class="text-muted">(untuk SEO / meta description)</small></label>
                <textarea name="site_description" id="site_description" class="form-control" rows="2"><?= esc($settings['site_description'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="site_tagline">Tagline / Deskripsi Singkat <small class="text-muted">(ditampilkan di hero section landing page)</small></label>
                <textarea name="site_tagline" id="site_tagline" class="form-control" rows="2"><?= esc($settings['site_tagline'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="site_footer_about">Info Footer <small class="text-muted">(ditampilkan di footer — email, WhatsApp, alamat, dsb)</small></label>
                <textarea name="site_footer_about" id="site_footer_about" class="form-control" rows="3"><?= esc($settings['site_footer_about'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="site_logo_file">Logo Site</label>
                <input type="file" name="site_logo_file" id="site_logo_file" class="form-control" accept="image/*">
                <?php if (!empty($settings['site_logo'])): ?>
                    <div class="mt-1"><img src="/uploads/thumbnails/<?= esc($settings['site_logo']) ?>" alt="Logo" width="100" class="rounded"></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="site_footer">Footer Text</label>
                <input type="text" name="site_footer" id="site_footer" class="form-control" value="<?= esc($settings['site_footer'] ?? '') ?>">
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
