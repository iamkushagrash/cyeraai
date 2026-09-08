<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cyera AI | Login</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Dashboard Theme CSS -->
    <link href="{{ asset('css/cyera-dashboard.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #020204;
            --card-bg: rgba(8, 12, 24, 0.82);
            --input-bg: rgba(4, 7, 16, 0.90);
            --gold-primary: #F5A623;
            --gold-gradient: linear-gradient(135deg, #FFD700 0%, #F5A623 50%, #D48806 100%);
            --gold-glow: rgba(245, 166, 35, 0.4);
            --cyan-accent: #00F0FF;
            --green-active: #00FF88;
            --text-primary: #FFFFFF;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;
            --border-glass: rgba(245, 166, 35, 0.28);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --radius-card: 22px;
            --radius-btn: 14px;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif !important;
            background-color: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 30px 16px;
        }

        /* Master Auth Wrapper */
        .auth-master-shell {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 22px;
            margin: auto 0;
            animation: authFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes authFadeUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Brand Header */
        .auth-brand-block {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .auth-brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 16px;
            border-radius: 40px;
            background: linear-gradient(180deg, rgba(18, 24, 40, 0.85) 0%, rgba(6, 10, 20, 0.95) 100%);
            border: 1px solid var(--border-glass);
            box-shadow: 0 0 30px rgba(245, 166, 35, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.15);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }

        .auth-brand-logo-wrap:hover {
            transform: scale(1.04) translateY(-2px);
            box-shadow: 0 0 40px rgba(245, 166, 35, 0.45);
            border-color: #FFD700;
        }

        .auth-brand-logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 14px rgba(245, 166, 35, 0.5));
        }

        .auth-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 14px;
            border-radius: 30px;
            background: rgba(0, 255, 136, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.28);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #00FF88;
            text-transform: uppercase;
        }

        .live-pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #00FF88;
            box-shadow: 0 0 10px #00FF88;
            animation: liveDotPing 1.8s ease-in-out infinite;
        }

        @keyframes liveDotPing {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.35; transform: scale(0.8); }
        }

        /* Luxury Glassmorphic Auth Card */
        .auth-luxury-card {
            background: var(--card-bg);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-radius: var(--radius-card);
            border: 1px solid var(--border-glass);
            box-shadow: 
                0 30px 60px -15px rgba(0, 0, 0, 0.9),
                0 0 40px rgba(245, 166, 35, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.15);
            padding: 34px 28px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle Top Corner Shimmer */
        .auth-luxury-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 15%;
            right: 15%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.6), transparent);
        }

        .card-header-block {
            text-align: center;
            margin-bottom: 26px;
        }

        .card-auth-heading {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF3C4 60%, var(--gold-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }

        .card-auth-subtext {
            font-size: 0.86rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        /* Input Form Items */
        .form-field-group {
            margin-bottom: 20px;
        }

        .field-label-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 600;
            color: #E2E8F0;
            margin-bottom: 8px;
            letter-spacing: 0.2px;
        }

        .field-label-text .label-icon {
            color: var(--gold-primary);
            margin-right: 6px;
        }

        .field-label-text .req-star {
            color: #F43F5E;
            font-size: 0.9rem;
        }

        .input-glass-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: var(--input-bg);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .input-glass-wrap:focus-within {
            border-color: var(--gold-primary);
            box-shadow: 0 0 22px rgba(245, 166, 35, 0.3), inset 0 0 8px rgba(245, 166, 35, 0.06);
            background: rgba(8, 14, 28, 0.95);
        }

        .input-leading-icon {
            width: 48px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-primary);
            font-size: 1.05rem;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.04);
            background: rgba(245, 166, 35, 0.04);
            transition: all 0.2s ease;
        }

        .input-glass-wrap:focus-within .input-leading-icon {
            color: #FFD700;
            background: rgba(245, 166, 35, 0.08);
        }

        .input-control-styled {
            flex: 1;
            height: 50px;
            padding: 0 16px;
            background: transparent;
            border: none;
            outline: none;
            color: #FFFFFF;
            font-family: 'Inter', sans-serif;
            font-size: 0.94rem;
            font-weight: 500;
        }

        .input-control-styled::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .eye-toggle-action {
            background: transparent;
            border: none;
            color: var(--text-muted);
            width: 46px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s ease;
            outline: none;
        }

        .eye-toggle-action:hover {
            color: #FFFFFF;
        }

        /* Error Hint */
        .error-hint-msg {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 0.78rem;
            color: #FB7185;
            font-weight: 500;
        }

        /* Alerts */
        .auth-alert-banner {
            padding: 13px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: alertFadeIn 0.3s ease;
        }

        @keyframes alertFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-alert-success {
            background: rgba(0, 255, 136, 0.12);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: #00FF88;
        }

        .auth-alert-error {
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #FB7185;
        }

        /* Aux Row */
        .auth-aux-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0 24px 0;
            font-size: 0.83rem;
        }

        .remember-label-styled {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        .remember-label-styled input[type="checkbox"] {
            accent-color: var(--gold-primary);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link-styled {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-link-styled:hover {
            color: #FFD700;
            text-decoration: underline;
        }

        /* Primary Submit Button */
        .btn-submit-gold {
            width: 100%;
            height: 52px;
            border: none;
            outline: none;
            border-radius: var(--radius-btn);
            background: var(--gold-gradient);
            color: #060912;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 10px 25px var(--gold-glow), inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-submit-gold::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transition: 0.5s;
        }

        .btn-submit-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(245, 166, 35, 0.55);
        }

        .btn-submit-gold:hover::after {
            left: 100%;
        }

        .btn-submit-gold:active {
            transform: translateY(0);
        }

        .btn-spinner-icon {
            display: none;
            width: 22px;
            height: 22px;
            border: 2.5px solid #060912;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinCircle 0.8s linear infinite;
        }

        @keyframes spinCircle {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .auth-divider-line {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 22px 0 18px 0;
            color: var(--text-muted);
            font-size: 0.76rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .auth-divider-line::before,
        .auth-divider-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-subtle);
        }

        /* Secondary Button (Create Account) */
        .btn-action-secondary {
            width: 100%;
            height: 48px;
            border-radius: var(--radius-btn);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 240, 255, 0.28);
            color: var(--cyan-accent);
            font-family: 'Outfit', sans-serif;
            font-size: 0.94rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
        }

        .btn-action-secondary:hover {
            background: rgba(0, 240, 255, 0.08);
            border-color: var(--cyan-accent);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Clean Footer */
        .auth-footer-block {
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-nav-links a:hover {
            color: var(--gold-primary);
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 12px;
            }
            .auth-luxury-card {
                padding: 26px 18px;
                border-radius: 18px;
            }
            .card-auth-heading {
                font-size: 1.38rem;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================================
         LIVE ANIMATED CYBER VIDEO CANVAS BACKGROUND (DITTO DASHBOARD)
         ============================================================ -->
    <div class="cyber-video-bg-container" aria-hidden="true">
        <div class="cyber-nebula-orb orb-1"></div>
        <div class="cyber-nebula-orb orb-2"></div>
        <div class="cyber-nebula-orb orb-3"></div>
        <div class="cyber-grid-scan-layer"></div>
        <canvas id="cyberMatrixCanvas"></canvas>
    </div>

    <!-- Main Container -->
    <div class="auth-master-shell">
        
        <!-- Brand Header -->
        <div class="auth-brand-block">
            <a href="{{ url('/') }}" class="auth-brand-logo-wrap" title="Cyera AI">
                <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="auth-brand-logo-img">
            </a>
            <div class="auth-live-pill">
                <span class="live-pulse-dot"></span>
                <span>SECURE WEB3 GATEWAY</span>
            </div>
        </div>

        <!-- Luxury Auth Card -->
        <div class="auth-luxury-card">
            
            <div class="card-header-block">
                <h1 class="card-auth-heading">Welcome Back</h1>
                <p class="card-auth-subtext">Sign in to manage your Web3 portfolio</p>
            </div>

            <!-- Session Alerts -->
            @if (session('success'))
                <div class="auth-alert-banner auth-alert-success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="auth-alert-banner auth-alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- User ID -->
                <div class="form-field-group">
                    <label class="field-label-text" for="email">
                        <span><i class="fas fa-id-badge label-icon"></i> User ID / Email</span>
                        <span class="req-star">*</span>
                    </label>
                    <div class="input-glass-wrap">
                        <div class="input-leading-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <input type="text" id="email" name="email" class="input-control-styled" placeholder="Enter your User ID" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <div class="error-hint-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-field-group">
                    <label class="field-label-text" for="password">
                        <span><i class="fas fa-lock label-icon"></i> Password</span>
                        <span class="req-star">*</span>
                    </label>
                    <div class="input-glass-wrap">
                        <div class="input-leading-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <input type="password" id="password" name="password" class="input-control-styled" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="eye-toggle-action" id="togglePassword" title="Show / Hide Password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-hint-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember & Forgot Password -->
                <div class="auth-aux-row">
                    <label class="remember-label-styled">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link-styled">Forgot Password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit-gold" id="btnSubmit">
                    <span id="btnText"><i class="fas fa-arrow-right-to-bracket"></i> Sign In to Account</span>
                    <div class="btn-spinner-icon" id="btnSpinner"></div>
                </button>

                <!-- Divider -->
                <div class="auth-divider-line">
                    <span>Don't have an account?</span>
                </div>

                <!-- Register Link -->
                <a href="{{ url('/register') }}" class="btn-action-secondary">
                    <i class="fas fa-user-plus"></i> Create New Account
                </a>

            </form>

        </div>

        <!-- Clean Footer -->
        <footer class="auth-footer-block">
            <div class="footer-nav-links">
                <a href="{{ url('/terms') }}">Terms of Service</a>
                <span>•</span>
                <a href="{{ url('/terms') }}">Privacy Policy</a>
                <span>•</span>
                <a href="https://t.me/" target="_blank">Support</a>
            </div>
            <div>&copy; 2026 CYERA AI. All Rights Reserved.</div>
        </footer>

    </div>

    <!-- ============================================================
         CYBER PARTICLES & BEAM MATRIX CANVAS SCRIPT (DITTO DASHBOARD)
         ============================================================ -->
    <script>
    (function () {
        const canvas = document.getElementById('cyberMatrixCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        let circuitBeams = [];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            initElements();
        }

        function initElements() {
            particles = [];
            circuitBeams = [];
            const count = Math.min(Math.floor((width * height) / 20000), 45);

            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2.2 + 1.0,
                    vx: (Math.random() - 0.5) * 0.45,
                    vy: (Math.random() - 0.5) * 0.45,
                    alpha: Math.random() * 0.65 + 0.35,
                    color: Math.random() > 0.45 ? '#FFD700' : (Math.random() > 0.5 ? '#00FF88' : '#00E5FF'),
                    pulsing: Math.random() * Math.PI,
                    pulseSpeed: Math.random() * 0.005 + 0.002
                });
            }

            for (let i = 0; i < 6; i++) {
                circuitBeams.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    length: Math.random() * 90 + 45,
                    speed: Math.random() * 0.4 + 0.2,
                    vertical: Math.random() > 0.45,
                    alpha: Math.random() * 0.45 + 0.25,
                    color: Math.random() > 0.4 ? 'rgba(255, 215, 0, ' : (Math.random() > 0.5 ? 'rgba(0, 255, 136, ' : 'rgba(0, 229, 255, ')
                });
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            // Connect nearest nodes with laser circuit lines
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 130) {
                        const alpha = (1 - dist / 130) * 0.35;
                        ctx.strokeStyle = `rgba(255, 215, 0, ${alpha})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            // Draw and update glowing particles
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;
                p.pulsing += p.pulseSpeed;
                if (p.y < -15) p.y = height + 15;
                if (p.x < -15) p.x = width + 15;
                if (p.x > width + 15) p.x = -15;

                const currentAlpha = Math.max(0.2, p.alpha + Math.sin(p.pulsing) * 0.3);
                ctx.save();
                ctx.globalAlpha = currentAlpha;
                ctx.shadowBlur = 14;
                ctx.shadowColor = p.color;
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }

            // Draw high-speed laser beams
            for (let i = 0; i < circuitBeams.length; i++) {
                const b = circuitBeams[i];
                ctx.save();
                ctx.lineWidth = 1.8;
                ctx.shadowBlur = 10;
                ctx.shadowColor = b.color + '0.8)';
                const grad = b.vertical
                    ? ctx.createLinearGradient(b.x, b.y - b.length, b.x, b.y)
                    : ctx.createLinearGradient(b.x - b.length, b.y, b.x, b.y);
                grad.addColorStop(0, b.color + '0)');
                grad.addColorStop(0.7, b.color + (b.alpha * 0.8) + ')');
                grad.addColorStop(1, b.color + b.alpha + ')');
                ctx.strokeStyle = grad;
                ctx.beginPath();
                if (b.vertical) {
                    ctx.moveTo(b.x, b.y - b.length);
                    ctx.lineTo(b.x, b.y);
                    b.y += b.speed;
                    if (b.y - b.length > height) {
                        b.y = 0;
                        b.x = Math.random() * width;
                    }
                } else {
                    ctx.moveTo(b.x - b.length, b.y);
                    ctx.lineTo(b.x, b.y);
                    b.x += b.speed;
                    if (b.x - b.length > width) {
                        b.x = 0;
                        b.y = Math.random() * height;
                    }
                }
                ctx.stroke();
                ctx.restore();
            }

            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', resize);
        resize();
        requestAnimationFrame(animate);
    })();

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

    // Submit Loader
    const loginForm = document.getElementById('loginForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    if (loginForm && btnSubmit) {
        loginForm.addEventListener('submit', function () {
            btnSubmit.style.pointerEvents = 'none';
            btnSubmit.style.opacity = '0.9';
            if (btnText) btnText.style.display = 'none';
            if (btnSpinner) btnSpinner.style.display = 'block';
        });
    }
    </script>
</body>
</html>