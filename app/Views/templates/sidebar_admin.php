<li class="nav-item">
    <a href="/admin/dashboard" class="nav-link <?= (uri_string() === 'admin/dashboard' || uri_string() === 'admin' || uri_string() === '') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/users" class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-users"></i>
        <p>Users</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/categories" class="nav-link <?= strpos(uri_string(), 'admin/categories') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-tags"></i>
        <p>Kategori</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/courses" class="nav-link <?= strpos(uri_string(), 'admin/courses') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-book"></i>
        <p>Kursus</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/classes" class="nav-link <?= strpos(uri_string(), 'admin/classes') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-chalkboard"></i>
        <p>Kelas</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/content" class="nav-link <?= strpos(uri_string(), 'admin/content') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-file-alt"></i>
        <p>Konten</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/enrollments" class="nav-link <?= strpos(uri_string(), 'admin/enrollments') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user-check"></i>
        <p>Enrollment</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/payments" class="nav-link <?= strpos(uri_string(), 'admin/payments') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-money-bill-wave"></i>
        <p>Pembayaran</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/announcements" class="nav-link <?= strpos(uri_string(), 'admin/announcements') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-bullhorn"></i>
        <p>Pengumuman</p>
    </a>
</li>
<li class="nav-header">PENGATURAN</li>
<li class="nav-item">
    <a href="/admin/settings" class="nav-link <?= strpos(uri_string(), 'admin/settings') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-cog"></i>
        <p>Site Settings</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/certificate-settings" class="nav-link <?= strpos(uri_string(), 'admin/certificate-settings') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-certificate"></i>
        <p>Sertifikat</p>
    </a>
</li>
<li class="nav-item">
    <a href="/admin/profile" class="nav-link <?= strpos(uri_string(), 'admin/profile') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user-circle"></i>
        <p>Profil</p>
    </a>
</li>
