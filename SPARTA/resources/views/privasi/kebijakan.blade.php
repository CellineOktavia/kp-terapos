<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | SPARTA</title>

    
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

        #progressBar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--primary);
            z-index: 9999;
        }

        .privacy-hero {
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

        .privacy-hero h1 {
            margin-top: 20px;
            font-weight: 800;
        }

        .privacy-hero p {
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

        .privacy-sidebar {
            position: sticky;
            top: 100px;

            background: white;
            border-radius: 20px;
            padding: 25px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .05);
        }

        .privacy-sidebar h6 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .privacy-sidebar a {
            display: block;
            text-decoration: none;
            color: var(--text);
            margin-bottom: 10px;
            transition: .3s;
        }

        .privacy-sidebar a:hover {
            color: var(--primary);
            transform: translateX(4px);
        }

        .privacy-card {
            background: white;
            border-radius: 25px;
            padding: 40px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .05);
        }

        .privacy-content h2 {
            font-size: 26px;
            font-weight: 700;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .privacy-content h2:first-child {
            margin-top: 0;
        }

        .privacy-content p {
            color: var(--text);
            line-height: 1.9;
            margin-bottom: 15px;
        }

        .privacy-content ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .privacy-content li {
            color: var(--text);
            line-height: 1.9;
            margin-bottom: 8px;
        }

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

        .footer {
            padding: 40px 0;
            text-align: center;
            color: #94a3b8;
        }

        @media(max-width:991px) {

            .privacy-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 25px;
            }

        }
    </style>
    

</head>

<body>

    <div id="progressBar"></div>

    <section class="privacy-hero">

        
        <div class="container text-center">

            <div class="hero-icon">
                <i class="bi bi-shield-lock"></i>
            </div>

            <h1>Privacy Policy</h1>

            <p>
                Kebijakan privasi SPARTA menjelaskan bagaimana Richie Motor
                mengumpulkan, menggunakan, menyimpan, dan melindungi data pengguna.
            </p>

            <span class="update-badge">
                Last Updated: 18 June 2026
            </span>

        </div>
        

    </section>

    <section class="py-5">

        
        <div class="container">

            <div class="row g-4">

                <div class="col-lg-3">

                    <div class="privacy-sidebar">

                        <h6>Daftar Isi</h6>

                        <a href="#collection">Information Collection</a>
                        <a href="#cookies">Cookies</a>
                        <a href="#rights">Your Rights</a>
                        <a href="#ai">Artificial Intelligence</a>
                        <a href="#thirdparty">Third Party Access</a>
                        <a href="#retention">Data Retention</a>
                        <a href="#security">Security</a>
                        <a href="#contact">Contact</a>

                    </div>

                </div>

                <div class="col-lg-9">

                    <div class="privacy-card">

                        <div class="privacy-content">

                            <h2 id="collection">
                                <i class="bi bi-database-fill text-primary me-2"></i>
                                Information Collection & Use
                            </h2>

                            <p>
                                The Application collects information when you
                                access and use SPARTA.
                            </p>

                            <ul>
                                <li>IP Address</li>
                                <li>Visited Pages</li>
                                <li>Visit Time</li>
                                <li>Operating System</li>
                            </ul>

                            <h2 id="cookies">
                                <i class="bi bi-cookie text-primary me-2"></i>
                                Cookies & Tracking Technologies
                            </h2>

                            <p>
                                The Application may use cookies and similar
                                technologies to improve user experience,
                                analytics, and functionality.
                            </p>

                            <h2 id="rights">
                                <i class="bi bi-person-check-fill text-primary me-2"></i>
                                Your Rights
                            </h2>

                            <p>
                                You may request access, correction,
                                or deletion of your personal information.
                            </p>

                            <h2 id="ai">
                                <i class="bi bi-cpu-fill text-primary me-2"></i>
                                Artificial Intelligence
                            </h2>

                            <p>
                                SPARTA may use AI technologies to provide
                                recommendations and enhance user experience.
                            </p>

                            <h2 id="thirdparty">
                                <i class="bi bi-share-fill text-primary me-2"></i>
                                Third Party Access
                            </h2>

                            <p>
                                Aggregated and anonymized information may be
                                shared with trusted third-party services.
                            </p>

                            <h2 id="retention">
                                <i class="bi bi-clock-history text-primary me-2"></i>
                                Data Retention Policy
                            </h2>

                            <p>
                                Personal data is retained only as long as
                                necessary to fulfill the purposes described
                                in this Privacy Policy.
                            </p>

                            <h2 id="security">
                                <i class="bi bi-shield-fill-check text-primary me-2"></i>
                                Security
                            </h2>

                            <p>
                                Richie Motor implements physical,
                                electronic, and procedural safeguards
                                to protect your information.
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

    <footer class="footer">
        © {{ date('Y') }} SPARTA - Richie Motor. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
