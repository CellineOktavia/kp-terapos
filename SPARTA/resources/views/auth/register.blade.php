<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — SPARTA</title>
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
            overflow-x: hidden;
            position: relative;
            padding: 1.5rem 0;
        }

        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.11;
            animation: float 9s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        .bg-blob-1 {
            width: 500px;
            height: 500px;
            background: var(--sparta-teal);
            top: -150px;
            right: -100px;
            animation-delay: 0s;
        }

        .bg-blob-2 {
            width: 420px;
            height: 420px;
            background: var(--sparta-blue);
            bottom: -120px;
            left: -120px;
            animation-delay: 4.5s;
        }

        .bg-blob-3 {
            width: 260px;
            height: 260px;
            background: var(--sparta-teal);
            top: 40%;
            left: 8%;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-28px) scale(1.06);
            }
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, #CBD5E1 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.35;
            z-index: 0;
        }

        .auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            padding: 1rem;
        }

        .brand-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.75rem;
            animation: slideDown .5s cubic-bezier(.16, 1, .3, 1) both;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--sparta-teal) 0%, var(--sparta-blue) 100%);
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

        .auth-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--sparta-border);
            padding: 2.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .05), 0 20px 50px -10px rgba(5, 150, 105, .08);
            animation: slideUp .55s cubic-bezier(.16, 1, .3, 1) .1s both;
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

        /* Step indicator */
        .step-bar {
            display: flex;
            align-items: center;
            margin-bottom: 1.75rem;
            gap: 0;
        }

        .step-item {
            display: flex;
            align-items: center;
            flex: 1;
            gap: 8px;
        }

        .step-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid var(--sparta-border);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--sparta-muted);
            transition: all .3s;
            flex-shrink: 0;
        }

        .step-dot.active {
            border-color: var(--sparta-teal);
            background: var(--sparta-teal);
            color: #fff;
        }

        .step-dot.done {
            border-color: var(--sparta-teal);
            background: var(--sparta-teal-light);
            color: var(--sparta-teal);
        }

        .step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--sparta-muted);
            transition: color .3s;
        }

        .step-label.active {
            color: var(--sparta-teal);
        }

        .step-line {
            flex: 1;
            height: 1.5px;
            background: var(--sparta-border);
            margin: 0 8px;
            border-radius: 2px;
            transition: background .3s;
        }

        .step-line.done {
            background: var(--sparta-teal);
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

        /* Form */
        .form-label-sparta {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--sparta-text);
            margin-bottom: 0.45rem;
            display: block;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 1.1rem;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
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
            border-color: var(--sparta-teal);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, .12);
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

        .pw-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
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
            color: var(--sparta-teal);
            background: var(--sparta-teal-light);
        }

        /* Row 2 col */
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        @media (max-width: 460px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Password strength */
        .pw-strength-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            margin-top: 6px;
            margin-bottom: 4px;
        }

        .pw-segment {
            height: 4px;
            border-radius: 4px;
            background: var(--sparta-border);
            transition: background .3s;
        }

        .pw-strength-label {
            font-size: 0.75rem;
            color: var(--sparta-muted);
        }

        /* Terms checkbox */
        .terms-wrap {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 1.5rem;
            padding: 14px;
            background: var(--sparta-gray);
            border-radius: 10px;
            border: 1.5px solid var(--sparta-border);
            transition: border-color .2s;
        }

        .terms-wrap:hover {
            border-color: var(--sparta-teal);
        }

        .terms-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            accent-color: var(--sparta-teal);
            margin-top: 2px;
            cursor: pointer;
        }

        .terms-text {
            font-size: 0.82rem;
            color: var(--sparta-muted);
            line-height: 1.5;
        }

        .terms-text a {
            color: var(--sparta-teal);
            font-weight: 600;
            text-decoration: none;
        }

        .terms-text a:hover {
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
            background: linear-gradient(135deg, var(--sparta-teal) 0%, #10B981 100%);
            cursor: pointer;
            transition: transform .18s, box-shadow .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(5, 150, 105, .35);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.1px;
        }

        .btn-sparta::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255, 255, 255, .12));
        }

        .btn-sparta:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, .4);
        }

        .btn-sparta:active {
            transform: translateY(0);
        }

        .btn-sparta:disabled {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

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

        .login-cta {
            text-align: center;
            font-size: 0.85rem;
            color: var(--sparta-muted);
        }

        .login-cta a {
            color: var(--sparta-blue);
            font-weight: 700;
            text-decoration: none;
        }

        .login-cta a:hover {
            color: var(--sparta-blue-dark);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.78rem;
            color: var(--sparta-muted);
            animation: slideDown .6s cubic-bezier(.16, 1, .3, 1) .25s both;
        }

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

        /* Benefits list */
        .benefits {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 1.75rem;
        }

        .benefit-pill {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: var(--sparta-teal-light);
            color: var(--sparta-teal-dark);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
            }

            .auth-heading {
                font-size: 1.35rem;
            }
        }
    </style>
</head>

<body>

    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>
    <div class="bg-blob bg-blob-3"></div>

    <div class="auth-wrapper">

        <div class="brand-bar">
            <div class="brand-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <span class="brand-name">SPARTA</span>
        </div>

        <div class="auth-card">
            <h1 class="auth-heading">Buat Akun Baru</h1>
            <p class="auth-sub">Bergabunglah dan kelola barang &amp; supplier dengan mudah</p>

            <!-- Benefits -->
            <div class="benefits">
                <span class="benefit-pill"><i class="bi bi-box-seam"></i> 245+ Barang</span>
                <span class="benefit-pill"><i class="bi bi-people"></i> 32+ Supplier</span>
                <span class="benefit-pill"><i class="bi bi-graph-up"></i> Laporan Real-time</span>
            </div>

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="alert-sparta alert-sparta-danger">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name row -->
                <div class="form-row-2">
                    <div>
                        <label class="form-label-sparta" for="name">Nama Lengkap</label>
                        <div class="input-wrap" style="margin-bottom: 0;">
                            <input id="name" type="text" name="name"
                                class="form-control-sparta {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                value="{{ old('name') }}" placeholder="Nama Anda" required autocomplete="name"
                                autofocus>
                            <i class="bi bi-person input-icon"></i>
                        </div>
                        @error('name')
                            <span class="invalid-msg"><i class="bi bi-x-circle me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label-sparta" for="username">Username</label>
                        <div class="input-wrap" style="margin-bottom: 0;">
                            <input id="username" type="text" name="username"
                                class="form-control-sparta {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                value="{{ old('username') }}" placeholder="username_anda" autocomplete="username">
                            <i class="bi bi-at input-icon"></i>
                        </div>
                        @error('username')
                            <span class="invalid-msg"><i class="bi bi-x-circle me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom: 1.1rem;"></div>

                {{-- Email --}}
                <label class="form-label-sparta" for="email">Alamat Email</label>
                <div class="input-wrap">
                    <input id="email" type="email" name="email"
                        class="form-control-sparta {{ $errors->has('gmail') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}" placeholder="nama@gmail.com" required autocomplete="email">
                    <i class="bi bi-envelope input-icon"></i>
                    @error('email')
                        <span class="invalid-msg"><i class="bi bi-x-circle me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <label class="form-label-sparta" for="password">Kata Sandi</label>
                <div class="input-wrap" style="margin-bottom: 4px;">
                    <input id="password" type="password" name="password"
                        class="form-control-sparta {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Min. 8 karakter" required autocomplete="new-password" style="padding-right: 44px;">
                    <i class="bi bi-lock input-icon"></i>
                    <button type="button" class="pw-toggle" id="pwToggle1" aria-label="Tampilkan kata sandi">
                        <i class="bi bi-eye" id="pwIcon1"></i>
                    </button>
                </div>
                <!-- Strength bar -->
                <div class="pw-strength-bar" id="strengthBar">
                    <div class="pw-segment" id="s1"></div>
                    <div class="pw-segment" id="s2"></div>
                    <div class="pw-segment" id="s3"></div>
                    <div class="pw-segment" id="s4"></div>
                </div>
                <p class="pw-strength-label" id="strengthLabel">Ketik kata sandi untuk melihat kekuatannya</p>
                @error('password')
                    <span class="invalid-msg"><i class="bi bi-x-circle me-1"></i>{{ $message }}</span>
                @enderror
                <div style="margin-bottom: 1rem;"></div>

                {{-- Confirm Password --}}
                <label class="form-label-sparta" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <div class="input-wrap">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control-sparta" placeholder="Ulangi kata sandi" required
                        autocomplete="new-password" style="padding-right: 44px;">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <button type="button" class="pw-toggle" id="pwToggle2" aria-label="Tampilkan konfirmasi">
                        <i class="bi bi-eye" id="pwIcon2"></i>
                    </button>
                    <span class="invalid-msg" id="matchMsg" style="display:none;">
                        <i class="bi bi-x-circle me-1"></i>Kata sandi tidak cocok
                    </span>
                </div>

                {{-- Terms --}}
                <div class="terms-wrap">
                    <input type="checkbox" id="terms" name="terms" required
                        {{ old('terms') ? 'checked' : '' }}>
                    <span class="terms-text">
                        Dengan mendaftar, saya menyetujui
                        <a href="#">Syarat &amp; Ketentuan</a>
                        dan
                        <a href="#">Kebijakan Privasi</a>
                        SPARTA.
                    </span>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-sparta" id="regBtn">
                    <span class="spinner" id="regSpinner"></span>
                    <i class="bi bi-person-plus-fill" id="regIcon"></i>
                    <span id="regText">Buat Akun</span>
                </button>
            </form>

            <div class="divider">sudah punya akun?</div>

            <div class="login-cta">
                <a href="{{ route('login') }}">&larr; Masuk ke akun Anda</a>
            </div>
        </div>

        <p class="auth-footer">
            &copy; {{ date('Y') }} SPARTA — Sistem CRUD Barang &amp; Supplier
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password toggle helpers
        function makePwToggle(toggleId, iconId, inputId) {
            document.getElementById(toggleId).addEventListener('click', () => {
                const inp = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }
        makePwToggle('pwToggle1', 'pwIcon1', 'password');
        makePwToggle('pwToggle2', 'pwIcon2', 'password_confirmation');

        // Focus icon accent
        document.querySelectorAll('.form-control-sparta').forEach(input => {
            const icon = input.parentElement.querySelector('.input-icon');
            if (!icon) return;
            input.addEventListener('focus', () => icon.style.color = 'var(--sparta-teal)');
            input.addEventListener('blur', () => icon.style.color = '');
        });

        // Password strength meter
        const pwInput = document.getElementById('password');
        const segs = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document
            .getElementById('s4')
        ];
        const lbl = document.getElementById('strengthLabel');
        const colors = ['#EF4444', '#F59E0B', '#3B82F6', '#059669'];
        const labels = ['Sangat Lemah', 'Lemah', 'Cukup Kuat', 'Sangat Kuat'];

        function calcStrength(pw) {
            let score = 0;
            if (pw.length >= 8) score++;
            if (/[A-Z]/.test(pw)) score++;
            if (/[0-9]/.test(pw)) score++;
            if (/[^A-Za-z0-9]/.test(pw)) score++;
            return score;
        }

        pwInput.addEventListener('input', () => {
            const pw = pwInput.value;
            const score = pw.length ? calcStrength(pw) : 0;
            segs.forEach((s, i) => {
                s.style.background = i < score ? colors[Math.min(score - 1, 3)] : 'var(--sparta-border)';
            });
            lbl.textContent = pw.length ? labels[Math.min(score - 1, 3)] :
                'Ketik kata sandi untuk melihat kekuatannya';
            lbl.style.color = pw.length ? colors[Math.min(score - 1, 3)] : 'var(--sparta-muted)';
        });

        // Password match check
        const pwConf = document.getElementById('password_confirmation');
        const matchMsg = document.getElementById('matchMsg');

        function checkMatch() {
            if (!pwConf.value) {
                matchMsg.style.display = 'none';
                return;
            }
            const match = pwInput.value === pwConf.value;
            matchMsg.style.display = match ? 'none' : 'block';
            pwConf.style.borderColor = match ? '' : '#EF4444';
        }
        pwConf.addEventListener('input', checkMatch);
        pwInput.addEventListener('input', checkMatch);

        // Loading state on submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (pwInput.value !== pwConf.value) {
                e.preventDefault();
                return;
            }
            const btn = document.getElementById('regBtn');
            const spinner = document.getElementById('regSpinner');
            const icon = document.getElementById('regIcon');
            const text = document.getElementById('regText');
            btn.disabled = true;
            spinner.style.display = 'block';
            icon.style.display = 'none';
            text.textContent = 'Membuat akun...';
        });
    </script>
</body>

</html>
