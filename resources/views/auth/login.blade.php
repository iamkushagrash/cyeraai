<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cyera AI | Secure Web3 Terminal</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@500;600;700;800;900&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #020204;
            --bg-card: rgba(8, 11, 20, 0.85);
            --bg-card-inner: rgba(4, 6, 12, 0.92);
            --gold-pure: #FFD700;
            --gold-main: #F5A623;
            --gold-light: #FFE082;
            --gold-border: rgba(245, 166, 35, 0.35);
            --gold-glow: rgba(245, 166, 35, 0.25);
            --cyan-neon: #00F0FF;
            --cyan-glow: rgba(0, 240, 255, 0.25);
            --green-neon: #00FF9D;
            --text-white: #FFFFFF;
            --text-muted: #8E99A8;
            --text-dim: #5A6475;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --card-radius: 16px;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-white);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 24px 16px;
        }

        /* Ambient Nebula Glows */
        .ambient-glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.6;
        }

        .glow-gold {
            top: -10%;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 350px;
            background: radial-gradient(circle, rgba(245, 166, 35, 0.25) 0%, rgba(255, 215, 0, 0.1) 50%, transparent 80%);
        }

        .glow-cyan {
            bottom: -10%;
            right: 10%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(0, 240, 255, 0.15) 0%, rgba(0, 255, 157, 0.08) 50%, transparent 80%);
        }

        /* Matrix Grid & Scanlines */
        .cyber-grid-overlay {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(245, 166, 35, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245, 166, 35, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 1;
        }

        .scanlines {
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%);
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
        }

        /* Main Shell Container */
        .auth-shell {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Brand Header */
        .auth-brand-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .brand-logo-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            border-radius: 40px;
            background: linear-gradient(180deg, rgba(20, 26, 40, 0.9) 0%, rgba(8, 11, 20, 0.95) 100%);
            border: 1px solid var(--gold-border);
            box-shadow: 0 0 30px rgba(245, 166, 35, 0.2), inset 0 1px 1px rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .brand-logo-wrap:hover {
            border-color: var(--gold-pure);
            box-shadow: 0 0 35px rgba(245, 166, 35, 0.35);
            transform: translateY(-1px);
        }

        .brand-logo-img {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 10px rgba(245, 166, 35, 0.4));
        }

        .brand-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(0, 255, 157, 0.08);
            border: 1px solid rgba(0, 255, 157, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--green-neon);
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green-neon);
            box-shadow: 0 0 8px var(--green-neon);
            animation: livePing 1.8s ease-in-out infinite;
        }

        @keyframes livePing {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* Mecha Auth Card */
        .mecha-auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--card-radius);
            border: 1px solid var(--gold-border);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.85),
                0 0 30px rgba(245, 166, 35, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            padding: 30px 24px;
        }

        /* Mecha Corner Bracket Accents */
        .mecha-auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 24px;
            height: 24px;
            border-top: 2px solid var(--gold-pure);
            border-left: 2px solid var(--gold-pure);
            border-top-left-radius: var(--card-radius);
            pointer-events: none;
        }

        .mecha-auth-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 24px;
            height: 24px;
            border-bottom: 2px solid var(--cyan-neon);
            border-right: 2px solid var(--cyan-neon);
            border-bottom-right-radius: var(--card-radius);
            pointer-events: none;
        }

        /* Card Top Header */
        .card-auth-header {
            text-align: center;
            margin-bottom: 24px;
            position: relative;
        }

        .card-auth-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            background: linear-gradient(135deg, #FFFFFF 0%, var(--gold-light) 60%, var(--gold-pure) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .card-auth-subtitle {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            letter-spacing: 0.8px;
        }

        /* Cyber Form Group */
        .cyber-form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .cyber-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--gold-light);
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 8px;
        }

        .cyber-label span.req {
            color: #FF4D7D;
        }

        .cyber-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: var(--bg-card-inner);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .cyber-input-wrap:focus-within {
            border-color: var(--gold-main);
            box-shadow: 0 0 16px rgba(245, 166, 35, 0.25), inset 0 0 10px rgba(245, 166, 35, 0.05);
            background: rgba(8, 12, 22, 0.95);
        }

        .cyber-input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 48px;
            color: var(--gold-main);
            font-size: 1rem;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(245, 166, 35, 0.04);
            transition: color 0.3s ease;
        }

        .cyber-input-wrap:focus-within .cyber-input-icon {
            color: var(--gold-pure);
            background: rgba(245, 166, 35, 0.08);
        }

        .cyber-input {
            flex: 1;
            height: 48px;
            padding: 0 14px;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-white);
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            font-weight: 500;
        }

        .cyber-input::placeholder {
            color: var(--text-dim);
            font-size: 0.85rem;
        }

        /* Password Toggle Button */
        .pwd-toggle-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            width: 44px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            outline: none;
        }

        .pwd-toggle-btn:hover {
            color: var(--gold-light);
        }

        /* Validation Error */
        .cyber-error-msg {
            display: block;
            margin-top: 5px;
            font-size: 0.75rem;
            color: #FF5A79;
            font-weight: 600;
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.5px;
        }

        /* Alert Messages */
        .cyber-alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: alertSlide 0.3s ease;
        }

        @keyframes alertSlide {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cyber-alert-success {
            background: rgba(0, 255, 157, 0.1);
            border: 1px solid rgba(0, 255, 157, 0.3);
            color: var(--green-neon);
        }

        .cyber-alert-error {
            background: rgba(255, 77, 125, 0.1);
            border: 1px solid rgba(255, 77, 125, 0.3);
            color: #FF4D7D;
        }

        /* Form Auxiliary (Remember & Forgot) */
        .form-aux-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 22px 0;
            font-size: 0.8rem;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .remember-wrap input[type="checkbox"] {
            accent-color: var(--gold-main);
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-mecha-submit {
            width: 100%;
            height: 52px;
            border: none;
            outline: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #F5A623 0%, #FFD700 50%, #D48806 100%);
            color: #05060A;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(245, 166, 35, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-mecha-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }

        .btn-mecha-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(245, 166, 35, 0.55);
        }

        .btn-mecha-submit:hover::after {
            left: 100%;
        }

        .btn-mecha-submit:active {
            transform: translateY(0);
        }

        .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0 18px 0;
            color: var(--text-dim);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-subtle);
        }

        /* Register Secondary Button */
        .btn-mecha-secondary {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 240, 255, 0.3);
            color: var(--cyan-neon);
            font-family: 'Orbitron', sans-serif;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-mecha-secondary:hover {
            background: rgba(0, 240, 255, 0.1);
            border-color: var(--cyan-neon);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.25);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Bottom Footer */
        .auth-footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-dim);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-nav {
            display: flex;
            justify-content: center;
            gap: 18px;
        }

        .footer-nav a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-nav a:hover {
            color: var(--gold-light);
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
            }
            .mecha-auth-card {
                padding: 24px 18px;
            }
            .card-auth-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Background Elements -->
    <div class="ambient-glow glow-gold"></div>
    <div class="ambient-glow glow-cyan"></div>
    <div class="cyber-grid-overlay"></div>
    <div class="scanlines"></div>

    <!-- Main Shell -->
    <div class="auth-shell">
        
        <!-- Brand Header -->
        <div class="auth-brand-header">
            <a href="{{ url('/') }}" class="brand-logo-wrap" title="Cyera AI">
                <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="brand-logo-img">
            </a>
            <div class="brand-live-pill">
                <span class="live-dot"></span>
                <span>SECURE WEB3 GATEWAY</span>
            </div>
        </div>

        <!-- Auth Card -->
        <div class="mecha-auth-card">
            
            <div class="card-auth-header">
                <h1 class="card-auth-title">User Authentication</h1>
                <p class="card-auth-subtitle">>_ Enter credentials to access node</p>
            </div>

            <!-- Session Alerts -->
            @if (session('success'))
                <div class="cyber-alert cyber-alert-success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="cyber-alert cyber-alert-error">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- User ID -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="email">
                        <span><i class="fas fa-id-badge"></i> User ID / Email</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-user-astronaut"></i>
                        </div>
                        <input type="text" id="email" name="email" class="cyber-input" placeholder="Enter your User ID" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="password">
                        <span><i class="fas fa-shield-halved"></i> Password</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <input type="password" id="password" name="password" class="cyber-input" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="pwd-toggle-btn" id="togglePassword" title="Show / Hide Password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Aux: Remember & Forgot -->
                <div class="form-aux-row">
                    <label class="remember-wrap">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember session</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-mecha-submit" id="btnSubmit">
                    <span id="btnText"><i class="fas fa-right-to-bracket"></i> AUTHORIZE & ENTER</span>
                    <div class="btn-spinner" id="btnSpinner"></div>
                </button>

                <!-- Divider -->
                <div class="auth-divider">
                    <span>New to Cyera AI?</span>
                </div>

                <!-- Create Account Link -->
                <a href="{{ url('/register') }}" class="btn-mecha-secondary">
                    <i class="fas fa-user-plus"></i> CREATE NEW ACCOUNT
                </a>

            </form>

        </div>

        <!-- Bottom Footer -->
        <footer class="auth-footer">
            <div class="footer-nav">
                <a href="{{ url('/terms') }}">Terms of Service</a>
                <span>•</span>
                <a href="{{ url('/terms') }}">Privacy Policy</a>
                <span>•</span>
                <a href="https://t.me/" target="_blank">Support</a>
            </div>
            <div>&copy; 2026 CYERA AI. DECENTRALIZED MECHA PROTOCOL.</div>
        </footer>

    </div>

    <!-- Password Toggle & Form Submit Animation Script -->
    <script>
        // Password Visibility Toggle
        const toggleBtn = document.getElementById('togglePassword');
        const pwdInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (toggleBtn && pwdInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = pwdInput.getAttribute('type') === 'password';
                pwdInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('fa-eye', !isPassword);
                toggleIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }

        // Submit Button Loading State
        const loginForm = document.getElementById('loginForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        if (loginForm && btnSubmit) {
            loginForm.addEventListener('submit', function () {
                btnSubmit.style.pointerEvents = 'none';
                btnSubmit.style.opacity = '0.85';
                if (btnText) btnText.style.display = 'none';
                if (btnSpinner) btnSpinner.style.display = 'block';
            });
        }
    </script>
</body>
</html>