<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SILA-PTK</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('template/assets/images/logos/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/styles.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0f1f3d;
            --blue:   #1a3a6e;
            --accent: #3b82f6;
            --gold:   #f59e0b;
            --light:  #f8fafc;
            --text:   #1e293b;
            --muted:  #64748b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--light);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .panel-left {
            width: 50%;
            background: var(--navy);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* Geometric background */
        /* .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 110% 10%, rgba(59,130,246,.25) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at -10% 90%, rgba(245,158,11,.12) 0%, transparent 55%);
        }

        .geo-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 48px 48px;
        } */

        /* Floating shapes
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(0px);
            opacity: .08;
        }
        .shape-1 {
            width: 320px; height: 320px;
            border: 2px solid #3b82f6;
            top: -80px; right: -80px;
            border-radius: 50%;
            animation: spin 30s linear infinite;
        }
        .shape-2 {
            width: 200px; height: 200px;
            border: 1.5px solid #f59e0b;
            bottom: 60px; left: -60px;
            border-radius: 50%;
            opacity: .12;
            animation: spin 20s linear infinite reverse;
        }
        .shape-3 {
            width: 100px; height: 100px;
            background: var(--accent);
            bottom: 180px; right: 60px;
            opacity: .15;
            border-radius: 20px;
            transform: rotate(30deg);
            animation: float 8s ease-in-out infinite;
        }
        .shape-4 {
            width: 60px; height: 60px;
            background: var(--gold);
            top: 200px; left: 40px;
            opacity: .18;
            border-radius: 12px;
            transform: rotate(15deg);
            animation: float 6s ease-in-out infinite reverse;
        } */

        @keyframes spin   { to { transform: rotate(360deg); } }
        @keyframes float  { 0%,100% { transform: translateY(0) rotate(30deg); } 50% { transform: translateY(-18px) rotate(30deg); } }

        /* Content left */
        .left-brand {
            position: relative;
            z-index: 2;
            animation: fadeUp .7s ease both;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 999px;
            padding: .35rem 1rem;
            color: rgba(255,255,255,.7);
            font-size: .75rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 2rem;
        }
        .brand-badge span.dot {
            width: 6px; height: 6px;
            background: var(--gold);
            border-radius: 50%;
            display: inline-block;
        }
        .left-brand h1 {
            font-family: 'DM Serif Display', serif;
            color: #fff;
            font-size: 2.6rem;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .left-brand h1 em {
            font-style: normal;
            color: var(--gold);
        }
        .left-brand p {
            color: rgba(255,255,255,.55);
            font-size: .9rem;
            line-height: 1.7;
            max-width: 320px;
        }

        /* Stats row */
        .stats-row {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 1.5rem;
            animation: fadeUp .7s .2s ease both;
        }
        .stat-item {
            flex: 1;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 14px;
            padding: 1rem 1.2rem;
            backdrop-filter: blur(8px);
        }
        .stat-item .val {
            font-family: 'DM Serif Display', serif;
            color: #fff;
            font-size: 1.8rem;
            line-height: 1;
            margin-bottom: .2rem;
        }
        .stat-item .val span { color: var(--gold); }
        .stat-item .lbl {
            color: rgba(255,255,255,.45);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .07em;
        }

        /* ── RIGHT PANEL ── */
        .panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--light);
            position: relative;
        }

        .panel-right::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--gold));
        }

        .form-box {
            width: 100%;
            max-width: 400px;
            animation: fadeUp .6s .1s ease both;
        }

        .form-box .top-logo {
            margin-bottom: 2.5rem;
            text-align: center;
        }
        .form-box .top-logo img { height: 36px; }

        .form-box h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.9rem;
            color: var(--text);
            margin-bottom: .4rem;
        }
        .form-box .subtitle {
            color: var(--muted);
            font-size: .875rem;
            margin-bottom: 2.2rem;
        }

        /* Inputs */
        .field {
            margin-bottom: 1.25rem;
        }
        .field label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: .03em;
            text-transform: uppercase;
            margin-bottom: .5rem;
        }
        .input-wrap {
            position: relative;
        }
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
        .input-wrap input:focus + .ico,
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

        /* Error */
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: .75rem 1rem;
            margin-bottom: 1.25rem;
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
            margin-bottom: 1.75rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
            font-size: .85rem;
            color: var(--muted);
            user-select: none;
        }
        .remember-label input[type=checkbox] {
            width: 16px; height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .forgot-link {
            font-size: .85rem;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* Submit */
        .btn-signin {
            width: 100%;
            padding: .85rem;
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
            box-shadow: 0 8px 24px rgba(15,31,61,.25);
            transform: translateY(-1px);
        }
        .btn-signin:active { transform: translateY(0); }

        /* Footer */
        .form-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: .78rem;
            color: var(--muted);
        }
        .form-footer a { color: var(--accent); font-weight: 600; text-decoration: none; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Responsive: hide left panel on small screens */
        @media (max-width: 768px) {
            .panel-left { display: none; }
            .panel-right::before { height: 3px; }
        }
    </style>
</head>
<body>

    <!-- LEFT PANEL -->
    <div class="panel-left">
        <div class="geo-grid"></div>
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>

        <div class="left-brand">
            <div class="brand-badge">
                <span class="dot"></span>
                SI-PASET
            </div>
            <h1>Sistem Informasi <em>Pengajuan Pemusnahan Aset</em><br>Dinas Pendidikan <br>Kabupaten Ketapang</h1>
            <p>Pengajuan Pemusnahan Aset</p>
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <div class="val">PTK<span>.</span></div>
                <div class="lbl">Manajemen Data</div>
            </div>
            <div class="stat-item">
                <div class="val">360<span>°</span></div>
                <div class="lbl">Data Terintegrasi</div>
            </div>
            <div class="stat-item">
                <div class="val">1<span>x</span></div>
                <div class="lbl">Akses Terpusat</div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="panel-right">
        <div class="form-box">

            <h2>Selamat Datang</h2>
            <p class="subtitle">Sistem Informasil Pengajuan Pemusnahan Aset Dinas Pendidikan</p>

            @if ($errors->any() || session('error'))
            <div class="error-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() ?? session('error') ?? 'Login gagal. Periksa kembali User ID dan password.' }}
            </div>
            @endif

            <form action="{{ route('login-proses') }}" method="POST">
                @csrf

                <!-- User ID -->
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

                <!-- Password -->
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

                <!-- Options -->
                <div class="options-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" checked>
                        Ingat perangkat ini
                    </label>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-signin">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Masuk ke Sistem
                </button>
            </form>

            <div class="form-footer">
                &copy; {{ date('Y') }} Sistem Pengajuan Pemusnahan Aset &mdash; Hak cipta dilindungi
            </div>
        </div>
    </div>

    <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Toggle show/hide password
        const toggleBtn = document.getElementById('togglePass');
        const passInput = document.getElementById('password');
        const eyeIcon  = document.getElementById('eyeIcon');

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
