<header class="nav-container">
    <nav>
        <ul>
            <li><a href="index.php">Beranda</a></li>
            <li><a href="#">Layanan</a></li>
            <li><a href="#">Tentang</a></li>
            <li><a href="#">Kontak</a></li>
        </ul>
    </nav>
    <div class="user-info">
        <?php if (isset($userData)): ?>
            <span>Selamat datang, <?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</header>