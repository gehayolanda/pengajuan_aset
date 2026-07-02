<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SI-PPASET</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('template/assets/images/logos/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/styles.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #7f2600;
            --blue:   #c45219;
            --accent: #3b82f6;
            --gold:   #f59e0b;
            --light:  #f8fafc;
            --text:   #1e293b;
            --muted:  #64748b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Background radial glow */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 20% 20%, rgba(59,130,246,.2) 0%, transparent 55%),
                radial-gradient(ellipse 60% 60% at 80% 80%, rgba(245,158,11,.1) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Grid pattern */
        .geo-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 52px 52px;
            pointer-events: none;
        }

        /* Floating shapes */
        .shape { position: fixed; pointer-events: none; }
        .shape-1 {
            width: 360px; height: 360px;
            border: 2px solid rgba(59,130,246,.15);
            border-radius: 50%;
            top: -100px; right: -100px;
            animation: spin 35s linear infinite;
        }
        .shape-2 {
            width: 240px; height: 240px;
            border: 1.5px solid rgba(245,158,11,.12);
            border-radius: 50%;
            bottom: -70px; left: -70px;
            animation: spin 25s linear infinite reverse;
        }
        .shape-3 {
            width: 80px; height: 80px;
            background: rgba(59,130,246,.1);
            border-radius: 18px;
            top: 15%; left: 6%;
            animation: float 9s ease-in-out infinite;
        }
        .shape-4 {
            width: 50px; height: 50px;
            background: rgba(245,158,11,.13);
            border-radius: 12px;
            bottom: 20%; right: 8%;
            animation: float 7s ease-in-out infinite reverse;
        }

        @keyframes spin  { to { transform: rotate(360deg); } }
        @keyframes float { 0%,100% { transform: translateY(0) rotate(20deg); } 50% { transform: translateY(-16px) rotate(20deg); } }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── WRAPPER ── */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            animation: fadeUp .65s ease both;
        }

        /* ── WELCOME TEXT (di atas form) ── */
        .welcome-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 999px;
            padding: .35rem 1rem;
            color: rgba(255,255,255,.7);
            font-size: .72rem;
            letter-spacing: .07em;
            text-transform: uppercase;
            margin-bottom: .9rem;
        }
        .brand-badge .dot {
            width: 6px; height: 6px;
            background: var(--gold);
            border-radius: 50%;
            display: inline-block;
        }

        .welcome-header h1 {
            font-family: 'DM Serif Display', serif;
            color: #fff;
            font-size: 2rem;
            line-height: 1.3;
            margin-bottom: .45rem;
        }
        .welcome-header h1 em {
            font-style: normal;
            color: var(--gold);
        }
        .welcome-header .welcome-sub {
            color: rgba(255,255,255,.5);
            font-size: .85rem;
        }

        /* ── LOGIN CARD ── */
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2.25rem;
            box-shadow: 0 28px 70px rgba(0,0,0,.4);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--gold));
        }

        .card-heading {
            margin-bottom: 1.75rem;
        }
        .card-heading h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: var(--text);
            margin-bottom: .3rem;
        }
        .card-heading .subtitle {
            color: var(--muted);
            font-size: .855rem;
        }

        /* Inputs */
        .field { margin-bottom: 1.2rem; }
        .field label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: .45rem;
        }
        .input-wrap { position: relative; }
        .input-wrap .ico {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            display: flex;
            align-items: center;
            pointer-events: none;
            transition: color .2s;
        }
        .input-wrap input {
            width: 100%;
            padding: .8rem 1rem .8rem 2.75rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            color: var(--text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .input-wrap input::placeholder { color: #b0bec5; }
        .input-wrap input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }
        .input-wrap:focus-within .ico { color: var(--accent); }

        /* Toggle password */
        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 0;
            display: flex;
            align-items: center;
            transition: color .2s;
        }
        .toggle-pass:hover { color: var(--accent); }

        /* Error box */
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: .75rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .85rem;
            color: #dc2626;
        }
        .error-box svg { flex-shrink: 0; }

        /* Options row */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.6rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
            font-size: .845rem;
            color: var(--muted);
            user-select: none;
        }
        .remember-label input[type=checkbox] {
            width: 16px; height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .forgot-link {
            font-size: .845rem;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* Submit button */
        .btn-signin {
            width: 100%;
            padding: .875rem;
            background: var(--navy);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            transition: background .2s, transform .15s, box-shadow .2s;
            letter-spacing: .02em;
        }
        .btn-signin:hover {
            background: var(--blue);
            box-shadow: 0 8px 24px rgba(15,31,61,.3);
            transform: translateY(-1px);
        }
        .btn-signin:active { transform: translateY(0); }

        /* Footer */
        .form-footer {
            text-align: center;
            margin-top: 1.6rem;
            font-size: .76rem;
            color: var(--muted);
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem; }
            .welcome-header h1 { font-size: 1.7rem; }
        }
    </style>
</head>
<body>

    <div class="geo-grid"></div>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>

    <div class="login-wrapper">

        <!-- Tulisan Selamat Datang di atas form -->
        <div class="welcome-header">
            <div class="brand-badge">
                <span class="dot"></span>
                SI-PPASET
            </div>
            <h1>Selamat Datang di<br><em>Sistem Informasi Pengajuan Pemusnahan Aset</em></h1>
            <p class="welcome-sub">Dinas Pendidikan Kabupaten Ketapang</p>
        </div>

        <!-- Form login di tengah -->
        <div class="login-card">

            <div class="card-heading">
                <h2>Masuk ke Akun</h2>
                <p class="subtitle">Masukkan User ID dan password Anda untuk melanjutkan</p>
            </div>

            @if ($errors->any() || session('error'))
            <div class="error-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() ?? session('error') ?? 'Login gagal. Periksa kembali User ID dan password.' }}
            </div>
            @endif

            <form action="{{ route('login-proses') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="login_id">User ID</label>
                    <div class="input-wrap">
                        <input
                            type="text"
                            id="login_id"
                            name="login_id"
                            value="{{ old('login_id') }}"
                            placeholder="Masukkan User ID Anda"
                            autocomplete="username"
                        >
                        <span class="ico">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                        >
                        <span class="ico">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <button type="button" class="toggle-pass" id="togglePass" title="Tampilkan password">
                            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="options-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" checked>
                        Ingat perangkat ini
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn-signin">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Login
                </button>
            </form>

            <div class="form-footer">
                &copy; {{ date('Y') }} Sistem Data SI-PPASET &mdash; Hak cipta dilindungi
            </div>
        </div>

    </div>

    <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const toggleBtn = document.getElementById('togglePass');
        const passInput = document.getElementById('password');
        const eyeIcon   = document.getElementById('eyeIcon');

        const eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

        toggleBtn.addEventListener('click', () => {
            const isPass = passInput.type === 'password';
            passInput.type = isPass ? 'text' : 'password';
            eyeIcon.innerHTML = isPass ? eyeClosed : eyeOpen;
        });
    </script>
</body>
</html>
