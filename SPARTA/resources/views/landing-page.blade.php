<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SPARTA - Sistem Manajemen Sparepart Richie Motor">

    <title>SPARTA</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* =========================
    GLOBAL
    ========================= */

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #60a5fa;
            --success: #10b981;
            --dark: #0f172a;
            --text: #64748b;
            --bg: #f8fbff;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--dark);
            overflow-x: hidden;
            position: relative;
        }

        /* =========================
    FLOATING BACKGROUND
    ========================= */

        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: .25;
        }

        .blob1 {
            width: 350px;
            height: 350px;
            background: #2563eb;
            top: 80px;
            right: -100px;
            animation: floatBlob 10s ease-in-out infinite;
        }

        .blob2 {
            width: 300px;
            height: 300px;
            background: #60a5fa;
            left: -120px;
            bottom: 120px;
            animation: floatBlob 12s ease-in-out infinite;
        }

        .blob3 {
            width: 250px;
            height: 250px;
            background: #93c5fd;
            top: 50%;
            left: 40%;
            animation: floatBlob 14s ease-in-out infinite;
        }

        @keyframes floatBlob {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-30px);
            }
        }

        /* =========================
    NAVBAR
    ========================= */

        .navbar {
            background: rgba(255, 255, 255, .75);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255, 255, 255, .5);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary) !important;
        }

        .nav-link {
            color: var(--text) !important;
            font-weight: 600;
            position: relative;
            transition: .3s;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 3px;
            background: var(--primary);
            border-radius: 20px;
            transition: .3s;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .btn-login {
            background: var(--primary);
            color: white !important;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: .3s;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        /* =========================
    HERO
    ========================= */

        .hero {
            padding: 120px 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #dbeafe;
            color: var(--primary);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 60px;
            font-weight: 800;
            line-height: 1.1;
        }

        .hero-title span {
            color: var(--primary);
        }

        .hero-text {
            font-size: 18px;
            color: var(--text);
            margin-top: 20px;
            line-height: 1.8;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: .3s;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-4px);
        }

        .dashboard-preview {
            background: white;
            border-radius: 28px;
            padding: 25px;
            box-shadow:
                0 25px 60px rgba(37, 99, 235, .15);
            transition: .4s;
        }

        .dashboard-preview:hover {
            transform: translateY(-8px);
        }

        /* =========================
    COUNTER SECTION
    ========================= */

        .counter-section {
            padding: 70px 0;
        }

        .counter-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .05);
            transition: .3s;
        }

        .counter-card:hover {
            transform: translateY(-10px);
        }

        .counter {
            font-size: 48px;
            font-weight: 800;
            color: var(--primary);
        }

        .counter-label {
            color: var(--text);
            margin-top: 10px;
        }

        /* =========================
    SECTION TITLE
    ========================= */

        .section-badge {
            background: #dbeafe;
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
        }

        .section-title {
            font-size: 42px;
            font-weight: 800;
            margin-top: 15px;
        }

        .section-title span {
            color: var(--primary);
        }

        .section-text {
            color: var(--text);
            line-height: 1.9;
            margin-top: 15px;
        }

        /* =========================
    ABOUT
    ========================= */

        .about-section {
            padding: 120px 0;
        }

        .about-image {
            background:
                linear-gradient(135deg,
                    #2563eb,
                    #60a5fa);

            height: 400px;
            border-radius: 30px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-image i {
            font-size: 140px;
            color: white;
        }

        .mini-stat {
            background: white;
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
        }

        .mini-stat h3 {
            color: var(--primary);
            font-weight: 800;
        }

        /* =========================
    FEATURES
    ========================= */

        .features-section {
            padding: 120px 0;
        }

        .feature-box {
            background: white;
            border-radius: 24px;
            padding: 35px 25px;
            text-align: center;
            height: 100%;
            transition: .4s;
            border: 1px solid #e2e8f0;
        }

        .feature-box:hover {
            transform: translateY(-12px);
            box-shadow:
                0 20px 40px rgba(37, 99, 235, .15);
        }

        .icon-box {
            width: 75px;
            height: 75px;
            margin: auto;
            border-radius: 20px;
            background: #eff6ff;
            color: var(--primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 32px;
            transition: .4s;
        }

        .feature-box:hover .icon-box {
            background: var(--primary);
            color: white;
            transform: rotate(8deg);
        }

        .feature-box h4 {
            margin-top: 20px;
            font-size: 18px;
            font-weight: 700;
        }

        .feature-box p {
            color: var(--text);
            margin-top: 10px;
        }

        /* =========================
    PREVIEW SECTION
    ========================= */

        .preview-section {
            padding: 120px 0;
        }

        .system-preview {
            background: white;
            padding: 20px;
            border-radius: 30px;
            box-shadow:
                0 20px 50px rgba(37, 99, 235, .12);
        }

        .system-preview img {
            border-radius: 20px;
        }

        /* =========================
    CONTACT
    ========================= */

        .contact-section {
            padding: 120px 0;
        }

        .contact-card {
            background:
                linear-gradient(135deg,
                    #1e3a8a,
                    #2563eb);

            padding: 60px;
            border-radius: 30px;
            color: white;
        }

        .contact-info {
            margin-top: 15px;
            font-size: 17px;
        }

        .contact-info i {
            margin-right: 10px;
        }

        .btn-contact {
            background: white;
            color: var(--primary);
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 15px;
            font-weight: 700;
            display: inline-block;
            transition: .3s;
        }

        .btn-contact:hover {
            transform: translateY(-4px);
        }

        /* =========================
    CTA
    ========================= */

        .cta-section {
            padding: 120px 0;
            text-align: center;
        }

        .cta-box {
            background: white;
            border-radius: 30px;
            padding: 60px;
            box-shadow:
                0 20px 50px rgba(0, 0, 0, .06);
        }

        /* =========================
    FOOTER
    ========================= */

        .footer {
            padding: 40px 0;
            text-align: center;
            color: var(--text);
        }

        /* =========================
    WHATSAPP FLOAT
    ========================= */

        .whatsapp-float {
            position: fixed;
            right: 25px;
            bottom: 25px;

            width: 60px;
            height: 60px;

            border-radius: 50%;
            background: #25d366;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 30px;

            text-decoration: none;

            z-index: 999;

            box-shadow:
                0 10px 25px rgba(37, 211, 102, .4);
        }

        .whatsapp-float:hover {
            color: white;
            transform: scale(1.1);
        }

        /* =========================
    BACK TO TOP
    ========================= */

        #backToTop {
            position: fixed;

            bottom: 100px;
            right: 25px;

            width: 55px;
            height: 55px;

            border: none;
            border-radius: 50%;

            background: var(--primary);
            color: white;

            display: none;
            align-items: center;
            justify-content: center;

            font-size: 20px;
            cursor: pointer;

            z-index: 999;
        }

        #backToTop:hover {
            background: var(--primary-dark);
        }

        /* =========================
    SCROLL REVEAL
    ========================= */

        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all .8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* =========================
    RESPONSIVE
    ========================= */

        @media(max-width:991px) {

            .hero {
                text-align: center;
                padding: 80px 0;
            }

            .hero-title {
                font-size: 42px;
            }

            .section-title {
                font-size: 34px;
            }

            .contact-card {
                padding: 40px;
            }

        }

        @media(max-width:768px) {

            .hero-title {
                font-size: 34px;
            }

            .counter {
                font-size: 36px;
            }

            .section-title {
                font-size: 30px;
            }

            .contact-card {
                text-align: center;
            }

            /* =========================
    FOOTER
    ========================= */

            .footer {
                margin-top: 100px;
                background: #ffffff;
                border-top: 1px solid #e2e8f0;
                padding: 70px 0 25px;
            }

            .footer-brand {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 24px;
                font-weight: 800;
                color: var(--primary);
            }

            .footer-brand i {
                font-size: 26px;
            }

            .footer-desc {
                margin-top: 15px;
                color: var(--text);
                line-height: 1.8;
                max-width: 350px;
            }

            .footer-title {
                font-weight: 700;
                margin-bottom: 20px;
                color: var(--dark);
            }

            .footer-links {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .footer-links a {
                color: var(--text);
                text-decoration: none;
                transition: .3s;
            }

            .footer-links a:hover {
                color: var(--primary);
                transform: translateX(3px);
            }

            .footer-contact {
                color: var(--text);
                margin-bottom: 10px;
            }

            .footer-contact i {
                color: var(--primary);
                margin-right: 8px;
            }

            .footer-divider {
                margin: 35px 0 20px;
                color: #e2e8f0;
            }

            .footer-bottom {
                text-align: center;
                color: #94a3b8;
                font-size: 14px;
            }
    </style>
</head>

<body>

    <!-- Floating Background -->
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="blob blob3"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-box-seam"></i>
                SPARTA
            </a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-4">
                    <li class="nav-item">
                        <a href="#tentang" class="nav-link">
                            Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#fitur" class="nav-link">
                            Fitur
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#kontak" class="nav-link">
                            Kontak
                        </a>
                    </li>
                </ul>
                <a href="{{ route('login') }}" class="btn-login">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-badge">
                        <i class="bi bi-stars"></i>
                        Sistem Manajemen Sparepart
                    </div>
                    <h1 class="hero-title">
                        <span id="typing"></span>
                        <br>
                        Kelola Bisnis Sparepart
                        Lebih Mudah
                    </h1>
                    <p class="hero-text">

                        Sistem informasi untuk membantu
                        Richie Motor mengelola barang,
                        supplier, pelanggan dan statistik
                        penjualan secara cepat dan
                        terorganisir.
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn-primary-custom">
                            <i class="bi bi-rocket-takeoff me-2"></i>
                            Mulai Sekarang
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="dashboard-preview">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="counter-card">
                                    <div class="counter" data-target="245">
                                        0
                                    </div>
                                    <div class="counter-label">
                                        Barang
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="counter-card">
                                    <div class="counter" data-target="32">
                                        0
                                    </div>
                                    <div class="counter-label">
                                        Supplier
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COUNTER -->
    <section class="counter-section reveal">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="counter-card">
                        <div class="counter" data-target="245">
                            0
                        </div>
                        <div class="counter-label">
                            Total Barang
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="counter-card">
                        <div class="counter" data-target="32">
                            0
                        </div>
                        <div class="counter-label">
                            Supplier
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="counter-card">
                        <div class="counter" data-target="156">
                            0
                        </div>
                        <div class="counter-label">
                            Pelanggan
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="counter-card">
                        <div class="counter" data-target="1320">
                            0
                        </div>

                        <div class="counter-label">
                            Penjualan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="about-section reveal">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-image">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="section-badge">
                        Tentang Kami
                    </span>

                    <h2 class="section-title">
                        Richie Motor &
                        <span>SPARTA</span>
                    </h2>
                    <p class="section-text">
                        Richie Motor merupakan usaha
                        yang bergerak di bidang
                        penjualan sparepart kendaraan.
                    </p>
                    <p class="section-text">
                        SPARTA dikembangkan untuk
                        membantu pengelolaan barang,
                        supplier, pelanggan, dan
                        statistik penjualan secara
                        real-time sehingga proses bisnis
                        menjadi lebih cepat, akurat,
                        dan terorganisir.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR -->
    <section id="fitur" class="features-section reveal">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">
                    Fitur SPARTA
                </span>
                <h2 class="section-title">
                    Semua Yang Anda
                    Butuhkan
                </h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h4>Manajemen Barang</h4>
                        <p>
                            Kelola stok dan data barang.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4>Supplier</h4>
                        <p>
                            Kelola supplier dengan mudah.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Pelanggan</h4>
                        <p>
                            Simpan data pelanggan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h4>Penjualan</h4>
                        <p>
                            Catat transaksi dengan cepat.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h4>Statistik</h4>
                        <p>
                            Pantau performa penjualan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h4>Laporan</h4>
                        <p>
                            Cetak laporan dengan mudah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KONTAK -->
    <section id="kontak" class="contact-section reveal">
        <div class="container">
            <div class="contact-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="section-badge">
                            Hubungi Kami
                        </span>
                        <h2 class="section-title text-white">
                            Richie Motor
                        </h2>
                        <p class="contact-info">
                            <i class="bi bi-telephone-fill"></i>
                            06987234690
                        </p>

                        <p class="contact-info">
                            <i class="bi bi-geo-alt-fill"></i>
                            Jl. Soekarno-Hatta,
                            Palembang
                        </p>
                    </div>

                    <div class="col-lg-4 text-lg-end">
                        <a href="#" class="btn-contact">
                            <i class="bi bi-whatsapp"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section reveal">
        <div class="container">
            <div class="cta-box">
                <h2 class="section-title">
                    Siap Menggunakan SPARTA?
                </h2>

                <p class="section-text">
                    Kelola sparepart dan supplier
                    dengan lebih cepat dan efisien.
                </p>

                <a href="{{ route('login') }}" class="btn-primary-custom mt-3">
                    Login Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- WHATSAPP -->
    <a href="#" class="whatsapp-float">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- BACK TO TOP -->
    <button id="backToTop">
        <i class="bi bi-arrow-up"></i>
    </button>

</body>

<footer class="footer">

    <div class="container">

        <div class="row align-items-center g-4">

            <div class="col-lg-4">

                <div class="footer-brand">

                    <i class="bi bi-box-seam"></i>

                    <span>SPARTA</span>

                </div>

                <p class="footer-desc">

                    Sistem manajemen sparepart yang membantu
                    Richie Motor mengelola barang, supplier,
                    pelanggan, dan statistik penjualan secara
                    lebih cepat dan terorganisir.

                </p>

            </div>

            <div class="col-lg-4 text-center">

                <h6 class="footer-title">
                    Navigasi
                </h6>

                <div class="footer-links">

                    <a href="#tentang">
                        Tentang
                    </a>

                    <a href="#fitur">
                        Fitur
                    </a>

                    <a href="#kontak">
                        Kontak
                    </a>

                </div>

            </div>

            <div class="col-lg-4 text-lg-end">

                <h6 class="footer-title">
                    Richie Motor
                </h6>

                <p class="footer-contact">

                    <i class="bi bi-telephone-fill"></i>
                    06987234690

                </p>

                <p class="footer-contact">

                    <i class="bi bi-geo-alt-fill"></i>
                    Jl. Soekarno-Hatta, Palembang

                </p>

            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">

            © {{ date('Y') }} SPARTA - Richie Motor.
            All Rights Reserved.

        </div>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        /* =========================
        TYPING EFFECT
        ========================= */

        const typingElement = document.getElementById("typing");
        if (typingElement) {
            const text = "SPARTA";
            let index = 0;

            function typeEffect() {
                if (index < text.length) {
                    typingElement.innerHTML += text.charAt(index);
                    index++;
                    setTimeout(typeEffect, 150);
                }
            }
            typeEffect();
        } /*=========================COUNTER ANIMATION=========================*/
        const
            counters = document.querySelectorAll(".counter");

        function startCounters() {
            counters.forEach(counter => {
                const target = Number(counter.dataset.target);
                const updateCounter = () => {
                    const current = Number(counter.innerText);
                    const increment = Math.ceil(target / 80);

                    if (current < target) {
                        counter.innerText = Math.min(current + increment, target);
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCounter();
            });
        }
        const counterObserver = new
        IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startCounters();
                    counterObserver.disconnect();
                }
            });
        });
        document.querySelectorAll(".counter-card")
            .forEach(card => {
                counterObserver.observe(card);
            });

        /* =========================
        SCROLL REVEAL
        ========================= */
        const reveals = document.querySelectorAll(".reveal");

        function revealElements() {
            reveals.forEach(element => {
                const top =
                    element.getBoundingClientRect().top;
                const windowHeight =
                    window.innerHeight;
                if (top < windowHeight - 100) {
                    element.classList.add("active");
                }
            });
        }
        revealElements();
        window.addEventListener("scroll", revealElements);
        /*=========================ACTIVE
                   NAVBAR=========================*/
        const sections = document.querySelectorAll("section[id]");
        const
            navLinks = document.querySelectorAll(".nav-link");

        function activeMenu() {
            let current = "";
            sections.forEach(section => {

                const sectionTop =
                    section.offsetTop - 150;

                const sectionHeight =
                    section.offsetHeight;

                if (
                    window.scrollY >= sectionTop &&
                    window.scrollY < sectionTop + sectionHeight) {
                    current = section.getAttribute("id");
                }
            });
            navLinks.forEach(link => {
                link.classList.remove("active");
                if (
                    link.getAttribute("href") ===
                    "#" + current
                ) {

                    link.classList.add("active");
                }
            });
        }
        window.addEventListener(
            "scroll",
            activeMenu
        );

        /* =========================
        NAVBAR SHRINK
        ========================= */
        const navbar =
            document.querySelector(".navbar");

        function shrinkNavbar() {
            if (!navbar) return;
            if (window.scrollY > 80) {
                navbar.classList.add(
                    "navbar-scrolled"
                );
            } else {
                navbar.classList.remove(
                    "navbar-scrolled"
                );
            }
        }
        shrinkNavbar();
        window.addEventListener(
            "scroll",
            shrinkNavbar
        );

        /* =========================
        BACK TO TOP
        ========================= */

        const backToTop =
            document.getElementById(
                "backToTop"
            );
        if (backToTop) {
            window.addEventListener(
                "scroll",
                () => {
                    if (
                        window.scrollY > 500
                    ) {
                        backToTop.style.display =
                            "flex";
                    } else {
                        backToTop.style.display =
                            "none";
                    }
                }
            );

            backToTop.addEventListener(
                "click",
                () => {
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                }
            );
        }

        /* =========================
        DASHBOARD 3D EFFECT
        ========================= */

        const preview =
            document.querySelector(
                ".dashboard-preview"
            );
        if (preview) {
            preview.addEventListener(
                "mousemove",
                (e) => {
                    const rect =
                        preview.getBoundingClientRect();
                    const x =
                        e.clientX - rect.left;
                    const y =
                        e.clientY - rect.top;
                    const rotateY =
                        ((x / rect.width) - 0.5) * 12;
                    const rotateX =
                        ((y / rect.height) - 0.5) * -12;
                    preview.style.transform =
                        `perspective(1000px)
                rotateX(${rotateX}deg)
                rotateY(${rotateY}deg)
                translateY(-5px)`;
                }
            );

            preview.addEventListener(
                "mouseleave",
                () => {
                    preview.style.transform =
                        "perspective(1000px) rotateX(0deg) rotateY(0deg)";
                }
            );
        }

        /* =========================
        PARALLAX BLOBS
        ========================= */
        const blobs =
            document.querySelectorAll(".blob");
        window.addEventListener(
            "mousemove",
            (e) => {
                const x =
                    e.clientX /
                    window.innerWidth;
                const y =
                    e.clientY /
                    window.innerHeight;
                blobs.forEach((blob, index) => {
                    const speed =
                        (index + 1) * 15;
                    blob.style.transform =
                        `translate(
                ${x * speed}px,
                ${y * speed}px
                )`;
                });
            }
        );

        /* =========================
        SMOOTH ANCHOR
        ========================= */
        document
            .querySelectorAll(
                'a[href^="#"]'
            )
            .forEach(anchor => {
                anchor.addEventListener(
                    "click",
                    function(e) {
                        const target =
                            document.querySelector(
                                this.getAttribute("href")
                            );
                        if (!target) return;
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: "smooth"
                        });
                    }
                );
            });
    });

    /* =========================
    PAGE FADE IN
    ========================= */

    window.addEventListener("load", () => {

        document.body.classList.add(
            "loaded"
        );

    });
</script>

</html>
