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

    .footer {
        background-color: var(--primary-color);
        padding: 4rem 6rem 2rem;
        border-radius: 1rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: white;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 4rem;
        margin-bottom: 3rem;
    }

    .footer-title {
        color: var(--accent-color);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .footer-text {
        color: #ffffff;
        font-size: 1.125rem;
        line-height: 1.6;
        max-width: 400px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 1rem;
    }

    .footer-links a {
        color: #ffffff;
        text-decoration: none;
        font-size: 1.125rem;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: var(--accent-color);
    }

    .footer-contact {
        color: #ffffff;
        font-size: 1.125rem;
        line-height: 1.8;
    }

    .footer-divider {
        width: 100%;
        height: 1px;
        background-color: rgba(255, 255, 255, 0.1);
        margin: 2rem 0;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .copyright {
        color: #ffffff;
        font-size: 1rem;
    }

    .social-links {
        display: flex;
        gap: 1rem;
    }

    .social-link {
        color: var(--primary-color);
        text-decoration: none;
        background: #ffffff;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .social-link:hover {
        transform: translateY(-3px);
        background-color: var(--accent-color);
    }

    @media (max-width: 1024px) {
        .footer {
            padding: 3rem 2rem 1.5rem;
        }

        .footer-grid {
            gap: 2rem;
        }
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-text {
            max-width: 100%;
        }

        .footer-bottom {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .social-links {
            justify-content: center;
            margin-top: 1rem;
        }
    }
</style>

<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<footer class="footer" id="footer">
    <div class="footer-grid">
        <div>
            <h3 class="footer-title">Tentang EcoUrbanCity</h3>
            <p class="footer-text">
                Inisiatif untuk menciptakan kota yang lebih cerdas, berkelanjutan, dan nyaman bagi seluruh warga.
            </p>
        </div>

        <div>
            <h3 class="footer-title">Layanan</h3>
            <ul class="footer-links">
                <li><a href="/monitoring">Monitoring Lalu Lintas</a></li>
                <li><a href="/kualitas-udara">Kualitas Udara</a></li>
                <li><a href="/laporan">Laporan Infrastruktur</a></li>
                <li><a href="/event">Event Sosial</a></li>
            </ul>
        </div>

        <div>
            <h3 class="footer-title">Kontak</h3>
            <div class="footer-contact">
                <p>Email: info@smartcity.id</p>
                <p>Telepon: (021) 1234-5678</p>
                <p>Alamat: Jl. Smart City No. 1</p>
            </div>
        </div>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-bottom">
        <p class="copyright">&copy; 2024 Smart City. All rights reserved.</p>
        <div class="social-links">
            <a href="#" class="social-link" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link" aria-label="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-link" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </div>
</footer>