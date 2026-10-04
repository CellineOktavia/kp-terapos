<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | SPARTA</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --text: #64748b;
            --dark: #0f172a;
            --bg: #f8fafc;
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
        }

        /* Scroll Progress */

        #progressBar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--primary);
            z-index: 9999;
        }

        /* Hero */

        .terms-hero {
            background: linear-gradient(135deg,
                    #1e3a8a,
                    #2563eb);

            color: white;
            padding: 90px 0;
        }

        .hero-icon {
            width: 90px;
            height: 90px;
            margin: auto;
            border-radius: 50%;

            background: rgba(255, 255, 255, .15);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 40px;
        }

        .terms-hero h1 {
            margin-top: 20px;
            font-weight: 800;
        }

        .terms-hero p {
            opacity: .9;
            max-width: 650px;
            margin: auto;
        }

        .update-badge {
            display: inline-block;
            margin-top: 20px;

            background: white;
            color: var(--primary);

            padding: 10px 18px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
        }

        /* Sidebar */

        .terms-sidebar {
            position: sticky;
            top: 100px;

            background: white;
            border-radius: 20px;

            padding: 25px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .05);
        }

        .terms-sidebar h6 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .terms-sidebar a {
            display: block;
            text-decoration: none;
            color: var(--text);
            margin-bottom: 10px;
            transition: .3s;
        }

        .terms-sidebar a:hover {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* Content */

        .terms-card {
            background: white;
            border-radius: 25px;

            padding: 40px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .05);
        }

        .terms-content h2 {
            font-size: 26px;
            font-weight: 700;
            margin-top: 40px;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .terms-content h2:first-child {
            margin-top: 0;
        }

        .terms-content p {
            color: var(--text);
            line-height: 1.9;
            margin-bottom: 15px;
        }

        .terms-content ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .terms-content li {
            color: var(--text);
            line-height: 1.9;
            margin-bottom: 8px;
        }

        /* Back Button */

        .btn-back {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: .3s;
        }

        .btn-back:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
        }

        /* Footer */

        .footer {
            padding: 40px 0;
            text-align: center;
            color: #94a3b8;
        }

        @media(max-width:991px) {

            .terms-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 25px;
            }

        }
    </style>
</head>

<body>

    <div id="progressBar"></div>

    <!-- HERO -->

    <section class="terms-hero">

        <div class="container text-center">

            <div class="hero-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <h1>Terms & Conditions</h1>

            <p>
                Ketentuan penggunaan aplikasi SPARTA yang dikelola oleh Richie Motor.
                Harap membaca seluruh syarat dan ketentuan sebelum menggunakan layanan.
            </p>

            <span class="update-badge">
                Last Updated: 18 June 2026
            </span>

        </div>

    </section>

    <!-- CONTENT -->

    <section class="py-5">

        <div class="container">

            <div class="row g-4">

                <!-- Sidebar -->

                <div class="col-lg-3">

                    <div class="terms-sidebar">

                        <h6>Daftar Isi</h6>

                        <a href="#license">License</a>
                        <a href="#property">Intellectual Property</a>
                        <a href="#termination">Termination</a>
                        <a href="#content">User Content</a>
                        <a href="#liability">Limitation of Liability</a>
                        <a href="#law">Governing Law</a>
                        <a href="#contact">Contact</a>

                    </div>

                </div>

                <!-- Terms -->

                <div class="col-lg-9">

                    <div class="terms-card">

                        <div class="terms-content">

                            <h2 id="license">
                                <i class="bi bi-key-fill text-primary me-2"></i>
                                License to Use the Application
                            </h2>

                            <p>
                                Subject to your compliance with these Terms,
                                the Service Provider grants you a limited,
                                non-exclusive, non-transferable and revocable
                                license to use the Application.
                            </p>

                            <h2 id="property">
                                <i class="bi bi-shield-lock-fill text-primary me-2"></i>
                                Intellectual Property
                            </h2>

                            <p>
                                All intellectual property rights including code,
                                designs, trademarks, logos and branding remain
                                the property of Richie Motor.
                            </p>

                            <h2 id="termination">
                                <i class="bi bi-x-circle-fill text-primary me-2"></i>
                                Termination
                            </h2>

                            <p>
                                The Service Provider may suspend or terminate
                                access if these Terms are violated.
                            </p>

                            <h2 id="content">
                                <i class="bi bi-file-text-fill text-primary me-2"></i>
                                User Generated Content
                            </h2>

                            <ul>
                                <li>No illegal content</li>
                                <li>No spam or malware</li>
                                <li>No harassment or hate speech</li>
                                <li>No misleading information</li>
                            </ul>

                            <h2 id="liability">
                                <i class="bi bi-exclamation-triangle-fill text-primary me-2"></i>
                                Limitation of Liability
                            </h2>

                            <p>
                                To the fullest extent permitted by law,
                                the Service Provider shall not be liable
                                for indirect or consequential damages.
                            </p>

                            <h2 id="law">
                                <i class="bi bi-bank2 text-primary me-2"></i>
                                Governing Law
                            </h2>

                            <p>
                                These Terms shall be governed by applicable laws
                                in the jurisdiction where the Service Provider
                                operates.
                            </p>

                            <h2 id="contact">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                Contact Us
                            </h2>

                            <p>
                                Richie Motor<br>
                                Phone: 06987234690<br>
                                Address: Jl. Soekarno-Hatta, Palembang
                            </p>

                            <div class="text-center mt-5">

                                <a href="{{ url('/login') }}" class="btn-back">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Kembali ke Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->

    <footer class="footer">
        © {{ date('Y') }} SPARTA - Richie Motor. All Rights Reserved.
    </footer>

    <!-- Bootstrap -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Progress Bar -->

    <script>
        window.addEventListener('scroll', () => {

            const winScroll =
                document.documentElement.scrollTop;

            const height =
                document.documentElement.scrollHeight -
                document.documentElement.clientHeight;

            const scrolled =
                (winScroll / height) * 100;

            document.getElementById('progressBar')
                .style.width = scrolled + '%';

        });
    </script>

</body>

</html>
