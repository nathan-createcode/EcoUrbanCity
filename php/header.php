<style>
    :root {
        --primary-color: #1a1f4d;
        --secondary-color: #4B0082;
        --accent-color: #FFD700;
        --text-color: #333333;
        --background-color: #f5f5f5;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-color);
        background-color: var(--background-color);
        overflow-x: hidden;
    }

    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 6rem;
        background: var(--primary-color);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 30px 80px 30px 90px;
        position: sticky;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .navbar-brand img {
        width: 50px;
        height: 50px;
        border: 2px solid white;
        background-color: white;
        border-radius: 50%;
        padding: 3px;
        position: relative;
    }

    .navbar-brand span {
        color: var(--accent-color);
        font-size: 1.25rem;
        font-weight: 700;
    }

    .nav-menu {
        display: flex;
        gap: 2.5rem;
        align-items: center;
        list-style: none;
    }

    .nav-item {
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .nav-item:hover {
        color: var(--accent-color);
    }

    .nav-item.active {
        font-weight: 600;
        color: var(--accent-color);
    }

    .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #F3F4FF;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .username {
        max-width: 150px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: white;
        font-weight: 500;
        display: block;
        text-align: right;
    }

    @media (max-width: 1024px) {
        .navbar {
            padding: 1rem 2rem;
        }
    }

    @media (max-width: 768px) {
        .nav-menu {
            display: none;
        }

        .username {
            max-width: 100px;
            font-size: 0.9rem;
            text-align: center;
        }

        .navbar {
            flex-wrap: wrap;
            justify-content: center;
        }

        .user-profile {
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
        }
    }
</style>

<header class="navbar">
    <a href="/" class="navbar-brand">
        <img src="../img/logo_3-removebg-preview.png" alt="EcoUrbanCity Logo">
        <span>EcoUrbanCity</span>
    </a>

    <nav>
        <ul class="nav-menu">
            <li><a href="/beranda" class="nav-item active">Beranda</a></li>
            <li><a href="/layanan" class="nav-item">Layanan</a></li>
            <li><a href="/tentang" class="nav-item">Tentang</a></li>
            <li><a href="/kontak" class="nav-item">Kontak</a></li>
        </ul>
    </nav>

    <div class="user-profile">
        <!-- Menampilkan Nama Lengkap -->
        <span class="username">
            <?php
            // Gabungkan nama depan dan nama belakang
            $fullName = htmlspecialchars(trim(($firstName ?? 'User') . ' ' . ($lastName ?? '')));
            echo mb_strlen($fullName) > 25 ? mb_substr($fullName, 0, 22) . '...' : $fullName;
            ?>
        </span>

        <!-- Avatar Dinamis -->
        <div class="avatar">
            <?php
            // URL avatar dari database
            $avatarUrl = $userData['avatar'] ?? '';

            if ($avatarUrl) {
                // Jika URL avatar tersedia, tampilkan gambar
                echo "<img src='" . htmlspecialchars($avatarUrl) . "' alt='Avatar' />";
            } else {
                // Jika tidak ada URL avatar, tampilkan inisial nama
                $initials = strtoupper(substr($firstName ?? 'U', 0, 1) . substr($lastName ?? '', 0, 1));
                echo "<span class='avatar-initials'>" . htmlspecialchars($initials) . "</span>";
            }
            ?>
        </div>
    </div>
</header>