<li class="nav-item">
    <a href="/member/dashboard" class="nav-link <?= (uri_string() === 'member/dashboard' || uri_string() === 'member' || uri_string() === '') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>
<li class="nav-item">
    <a href="/member/courses" class="nav-link <?= strpos(uri_string(), 'member/courses') !== false && strpos(uri_string(), 'my-courses') === false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-book-open"></i>
        <p>Jelajahi Kursus</p>
    </a>
</li>
<li class="nav-item">
    <a href="/member/my-courses" class="nav-link <?= strpos(uri_string(), 'my-courses') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-graduation-cap"></i>
        <p>Kursus Saya</p>
    </a>
</li>
<li class="nav-item">
    <a href="/member/payments" class="nav-link <?= strpos(uri_string(), 'member/payments') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-money-bill-wave"></i>
        <p>Pembayaran</p>
    </a>
</li>
<li class="nav-header">AKUN</li>
<li class="nav-item">
    <a href="/member/profile" class="nav-link <?= strpos(uri_string(), 'member/profile') !== false ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user-circle"></i>
        <p>Profil</p>
    </a>
</li>
