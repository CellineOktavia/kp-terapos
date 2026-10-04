<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SPARTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --sparta-blue: #2563EB;
            --sparta-blue-dark: #1d4ed8;
            --sparta-blue-light: #EFF6FF;
            --sparta-teal: #059669;
            --sparta-teal-dark: #047857;
            --sparta-teal-light: #ECFDF5;
            --sparta-gray: #F8FAFC;
            --sparta-border: #E2E8F0;
            --sparta-text: #1E293B;
            --sparta-muted: #64748B;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--sparta-gray);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background blobs */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        .bg-blob-1 {
            width: 500px;
            height: 500px;
            background: var(--sparta-blue);
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .bg-blob-2 {
            width: 400px;
            height: 400px;
            background: var(--sparta-teal);
            bottom: -120px;
            right: -120px;
            animation-delay: 4s;
        }

        .bg-blob-3 {
            width: 300px;
            height: 300px;
            background: var(--sparta-blue);
            top: 50%;
            right: 10%;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-30px) scale(1.05);
            }
        }

        /* Grid pattern overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, #CBD5E1 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.35;
            z-index: 0;
        }

        /* Main wrapper */
        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            padding: 1rem;
        }

        /* Logo bar */
        .brand-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.75rem;
            animation: slideDown 0.5s cubic-bezier(.16, 1, .3, 1) both;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--sparta-blue) 0%, var(--sparta-teal) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--sparta-text);
            letter-spacing: -0.5px;
        }

        /* Card */
        .auth-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--sparta-border);
            padding: 2.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .05), 0 20px 50px -10px rgba(37, 99, 235, .08);
            animation: slideUp 0.55s cubic-bezier(.16, 1, .3, 1) 0.1s both;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-heading {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--sparta-text);
            letter-spacing: -0.5px;
            margin-bottom: 0.35rem;
        }

        .auth-sub {
            font-size: 0.875rem;
            color: var(--sparta-muted);
            margin-bottom: 1.75rem;
        }

        /* Alert */
        .alert-sparta {
            border-radius: 10px;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            border: none;
            margin-bottom: 1.25rem;
        }

        .alert-sparta-danger {
            background: #FEF2F2;
            color: #991B1B;
        }

        .alert-sparta-success {
            background: var(--sparta-teal-light);
            color: var(--sparta-teal-dark);
        }

        /* Form labels */
        .form-label-sparta {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--sparta-text);
            margin-bottom: 0.45rem;
            display: block;
        }

        /* Input group */
        .input-wrap {
            position: relative;
            margin-bottom: 1.1rem;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 24px;
            transform: translateY(-50%);
            color: var(--sparta-muted);
            font-size: 1rem;
            pointer-events: none;
            transition: color .2s;
        }

        .form-control-sparta {
            width: 100%;
            height: 48px;
            padding: 0 14px 0 42px;
            border: 1.5px solid var(--sparta-border);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--sparta-text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .form-control-sparta:focus {
            border-color: var(--sparta-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .form-control-sparta:focus+.input-icon,
        .input-icon.active {
            color: var(--sparta-blue);
        }

        .form-control-sparta.is-invalid {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .1);
        }

        .invalid-msg {
            font-size: 0.78rem;
            color: #EF4444;
            margin-top: 0.3rem;
            display: block;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 13px;
            top: 24px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--sparta-muted);
            font-size: 1rem;
            padding: 4px;
            border-radius: 6px;
            transition: color .2s, background .2s;
            line-height: 1;
        }

        .pw-toggle:hover {
            color: var(--sparta-blue);
            background: var(--sparta-blue-light);
        }

        /* Remember + forgot row */
        .row-extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
            margin-top: -0.25rem;
        }

        .check-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .check-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--sparta-blue);
            cursor: pointer;
        }

        .check-label {
            font-size: 0.83rem;
            color: var(--sparta-muted);
        }

        .forgot-link {
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--sparta-blue);
            text-decoration: none;
            transition: color .2s;
        }

        .forgot-link:hover {
            color: var(--sparta-blue-dark);
            text-decoration: underline;
        }

        /* Submit button */
        .btn-sparta {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #fff;
            background: linear-gradient(135deg, var(--sparta-blue) 0%, #3B82F6 100%);
            cursor: pointer;
            transition: transform .18s, box-shadow .18s, filter .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .35);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.1px;
        }

        .btn-sparta::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255, 255, 255, .15));
        }

        .btn-sparta:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, .4);
        }

        .btn-sparta:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(37, 99, 235, .3);
        }

        .btn-sparta:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.4rem 0;
            color: var(--sparta-muted);
            font-size: 0.78rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--sparta-border);
        }

        /* Register link */
        .register-cta {
            text-align: center;
            font-size: 0.85rem;
            color: var(--sparta-muted);
        }

        .register-cta a {
            color: var(--sparta-teal);
            font-weight: 700;
            text-decoration: none;
            transition: color .2s;
        }

        .register-cta a:hover {
            color: var(--sparta-teal-dark);
        }

        /* Footer note */
        .auth-footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: .75rem;
            color: var(--sparta-muted);
            opacity: .85;
        }

        /* Loading spinner inside button */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .back-home-wrapper {

            margin-top: 1rem;
            margin-bottom: .5rem;

            text-align: center;
        }

        .back-home-btn {

            display: inline-flex;

            align-items: center;
            gap: .5rem;

            padding: .75rem 1rem;

            border-radius: 12px;

            text-decoration: none;

            color: var(--sparta-muted);

            font-size: .85rem;
            font-weight: 600;

            transition: all .25s ease;
        }

        .back-home-btn:hover {

            background: var(--sparta-blue-light);

            color: var(--sparta-blue);

            transform: translateX(-3px);
        }

        .back-home-btn i {

            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
            }

            .auth-heading {
                font-size: 1.35rem;
            }

            /* Legal Notice */

            .legal-notice {
                margin-top: 1.25rem;
                padding-top: 1rem;

                border-top: 1px solid var(--sparta-border);

                display: flex;
                align-items: flex-start;
                gap: .65rem;

                font-size: .78rem;
                line-height: 1.6;

                color: var(--sparta-muted);
            }

            .legal-icon {
                color: var(--sparta-blue);
                margin-top: 2px;
                flex-shrink: 0;
            }

            .legal-link {
                text-decoration: none;
                font-weight: 600;
                transition: .2s ease;
            }

            .privacy-link {
                color: var(--sparta-blue);
            }

            .privacy-link:hover {
                color: var(--sparta-blue-dark);
                text-decoration: underline;
            }

            .terms-link {
                color: var(--sparta-teal);
            }

            .terms-link:hover {
                color: var(--sparta-teal-dark);
                text-decoration: underline;
            }
        }
    </style>
</head>

<body>

    <!-- BG decorations -->
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>
    <div class="bg-blob bg-blob-3"></div>

    <div class="auth-wrapper">

        <!-- Brand -->
        <div class="brand-bar">
            <div class="brand-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <span class="brand-name">SPARTA</span>
        </div>

        <!-- Card -->
        <div class="auth-card">
            <h1 class="auth-heading">Selamat Datang 👋</h1>
            <p class="auth-sub">Masuk ke akun Anda untuk melanjutkan</p>

            {{-- Session status --}}
            @if (session('status'))
                <div class="alert-sparta alert-sparta-success">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert-sparta alert-sparta-danger">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <label class="form-label-sparta" for="email">Alamat Email</label>
                <div class="input-wrap">
                    <input id="email" type="email" name="email"
                        class="form-control-sparta {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email"
                        autofocus>
                    <i class="bi bi-envelope input-icon"></i>
                    @error('email')
                        <span class="invalid-msg"><i class="bi bi-x-circle me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <label class="form-label-sparta" for="password">Kata Sandi</label>
                <div class="input-wrap" style="margin-bottom: 0.5rem;">
                    <input id="password" type="password" name="password"
                        class="form-control-sparta {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan kata sandi" required autocomplete="current-password"
                        style="padding-right: 44px;">
                    <i class="bi bi-lock input-icon"></i>
                    <button type="button" class="pw-toggle" id="pwToggle" aria-label="Tampilkan kata sandi">
                        <i class="bi bi-eye" id="pwIcon"></i>
                    </button>
                    @error('password')
                        <span class="invalid-msg"><i class="bi bi-x-circle me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="row-extras">
                    <label class="check-wrap">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="check-label">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa sandi?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-sparta" id="loginBtn">
                    <span class="spinner" id="loginSpinner"></span>
                    <i class="bi bi-box-arrow-in-right" id="loginIcon"></i>
                    <span id="loginText">Masuk</span>
                </button>
            </form>

            <div class="back-home-wrapper">

                <a href="{{ route('landing-page') }}" class="back-home-btn">

                    <i class="bi bi-arrow-left-circle"></i>

                    Kembali ke Landing Page

                </a>

            </div>

            <div class="legal-notice py-3">
                <i class="bi bi-shield-check legal-icon"></i>
                <span>
                    Dengan masuk, Anda menyetujui
                    <a href="{{ route('privasi.kebijakan') }}" class="legal-link privacy-link">
                        Kebijakan Privasi
                    </a>
                    dan
                    <a href="{{ route('privasi.terms') }}" class="legal-link terms-link">
                        Syarat & Ketentuan
                    </a>
                </span>
            </div>
        </div>

        <p class="auth-footer">
            © {{ date('Y') }} SPARTA • Richie Motor
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password toggle
        const pwToggle = document.getElementById('pwToggle');
        const pwInput = document.getElementById('password');
        const pwIcon = document.getElementById('pwIcon');
        pwToggle.addEventListener('click', () => {
            const isText = pwInput.type === 'text';
            pwInput.type = isText ? 'password' : 'text';
            pwIcon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
        });

        // Focus icon accent
        document.querySelectorAll('.form-control-sparta').forEach(input => {
            const icon = input.parentElement.querySelector('.input-icon');
            if (!icon) return;
            input.addEventListener('focus', () => icon.style.color = 'var(--sparta-blue)');
            input.addEventListener('blur', () => icon.style.color = '');
        });

        // Loading state on submit
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', () => {
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('loginSpinner');
            const icon = document.getElementById('loginIcon');
            const text = document.getElementById('loginText');
            btn.disabled = true;
            spinner.style.display = 'block';
            icon.style.display = 'none';
            text.textContent = 'Memproses...';
        });
    </script>
</body>

</html>
