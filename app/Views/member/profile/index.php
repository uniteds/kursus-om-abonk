<?= $this->extend('templates/base') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= session()->getFlashdata('errors') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center mb-3">
                    <div class="profile-avatar-wrapper">
                        <img id="avatar-preview"
                             src="<?= !empty($user->avatar) ? '/uploads/avatars/' . esc($user->avatar) : '/assets/img/default-avatar.png' ?>"
                             alt="<?= esc($user->name) ?>"
                             class="profile-user-img img-fluid img-circle"
                             style="width:128px;height:128px;object-fit:cover;border:3px solid #dee2e6;">
                        <label for="avatar-input" class="profile-avatar-edit" title="Ubah Foto">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                </div>

                <h3 class="profile-username text-center"><?= esc($user->name) ?></h3>
                <p class="text-muted text-center">
                    <span class="badge badge-info"><?= ucfirst($user->role) ?></span>
                    <?= $user->is_active ? '<span class="badge badge-success ml-1">Aktif</span>' : '<span class="badge badge-secondary ml-1">Nonaktif</span>' ?>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right text-muted"><?= esc($user->email) ?></a>
                    </li>
                    <?php if (!empty($user->whatsapp)): ?>
                    <li class="list-group-item">
                        <b>WhatsApp</b>
                        <a class="float-right text-muted" href="https://wa.me/<?= esc($user->whatsapp) ?>" target="_blank">
                            <?= esc($user->whatsapp) ?> <i class="fab fa-whatsapp text-success ml-1"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($user->phone)): ?>
                    <li class="list-group-item">
                        <b>Telepon</b> <a class="float-right text-muted"><?= esc($user->phone) ?></a>
                    </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <b>Bergabung</b> <a class="float-right text-muted"><?= date('d M Y', strtotime($user->created_at ?? 'now')) ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <?php if (!empty($user->bio)): ?>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user mr-1"></i> Tentang Saya</h3>
            </div>
            <div class="card-body">
                <p class="text-muted"><?= nl2br(esc($user->bio)) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#edit-profile" data-toggle="tab"><i class="fas fa-edit mr-1"></i> Edit Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#edit-password" data-toggle="tab"><i class="fas fa-lock mr-1"></i> Ubah Password</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="edit-profile">
                        <form action="/member/profile/update" method="POST" enctype="multipart/form-data" id="profile-form">
                            <?= csrf_field() ?>
                            <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*">

                            <h5 class="text-muted mb-3"><i class="fas fa-id-card mr-1"></i> Data Diri</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" value="<?= esc($user->name) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" value="<?= esc($user->email) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_of_birth">Tanggal Lahir</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?= esc($user->date_of_birth ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">No. Telepon</label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="<?= esc($user->phone ?? '') ?>" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-muted mb-3 mt-4"><i class="fab fa-whatsapp mr-1 text-success"></i> WhatsApp</h5>
                            <div class="form-group">
                                <label for="whatsapp">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                    </div>
                                    <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="<?= esc($user->whatsapp ?? '') ?>" placeholder="08xxxxxxxxxx atau 628xxxxxxxxxx">
                                </div>
                                <small class="text-muted">Gunakan format: <code>08xxx</code> atau <code>628xxx</code>. Nomor ini akan digunakan untuk notifikasi via WhatsApp.</small>
                            </div>

                            <h5 class="text-muted mb-3 mt-4"><i class="fas fa-map-marker-alt mr-1"></i> Alamat & Lainnya</h5>
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea name="address" id="address" class="form-control" rows="2" placeholder="Alamat lengkap..."><?= esc($user->address ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="bio">Bio / Tentang Saya</label>
                                <textarea name="bio" id="bio" class="form-control" rows="3" placeholder="Ceritakan sedikit tentang diri Anda..."><?= esc($user->bio ?? '') ?></textarea>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="edit-password">
                        <form action="/member/profile/update" method="POST">
                            <?= csrf_field() ?>
                            <input type="hidden" name="name" value="<?= esc($user->name) ?>">
                            <input type="hidden" name="email" value="<?= esc($user->email) ?>">

                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" name="password" id="password" class="form-control" minlength="6">
                                <small class="text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah.</small>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-warning"><i class="fas fa-key mr-1"></i> Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar-wrapper {
    position: relative;
    display: inline-block;
}
.profile-avatar-edit {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #007bff;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,.2);
    transition: background .2s;
}
.profile-avatar-edit:hover {
    background: #0056b3;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('avatar-input').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('avatar-preview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
<?= $this->endSection() ?>
