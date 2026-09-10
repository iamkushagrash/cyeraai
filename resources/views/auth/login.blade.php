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
            --bg-body: #000000;
            --card-bg: #060609;
            --card-border: rgba(245, 166, 35, 0.32);
            --input-bg: #09090d;
            --gold-primary: #F5A623;
            --gold-gradient: linear-gradient(135deg, #FFD700 0%, #F5A623 50%, #D48806 100%);
            --gold-glow: rgba(245, 166, 35, 0.45);
            --cyan-accent: #FFD700;
            --green-active: #00FF88;
            --text-primary: #FFFFFF;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --radius-card: 24px;
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
            overflow-y: auto;
            position: relative;
            padding: 24px 16px;
        }

        /* Master Container */
        .auth-master-shell {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin: auto;
            animation: authFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes authFadeUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Luxury Glassmorphic Card Container with Logo Inside */
        .auth-card-unified {
            background: #060609;
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border-radius: 20px;
            border: 1px solid var(--card-border);
            box-shadow: 
                0 32px 64px -16px rgba(0, 0, 0, 0.98),
                0 0 35px rgba(245, 166, 35, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.12);
            padding: 24px 20px 20px 20px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle Top Edge Shimmer Ray */
        .auth-card-unified::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.9), rgba(245, 166, 35, 0.8), transparent);
        }

        /* Inside Card Header Block */
        .card-brand-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 16px;
            position: relative;
        }

        .inside-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .inside-logo-wrap:hover {
            transform: scale(1.05);
        }

        .inside-logo-img {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 16px rgba(245, 166, 35, 0.5));
        }

        .inside-live-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.32);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #FFD700;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .live-dot-pulse {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #FFD700;
            box-shadow: 0 0 8px #FFD700;
            animation: liveDotPing 1.8s ease-in-out infinite;
        }

        @keyframes liveDotPing {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.35; transform: scale(0.8); }
        }

        .card-auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF3C4 60%, var(--gold-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2px;
        }

        .card-auth-subtitle {
            font-size: 0.78rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        /* Form Fields */
        .form-field-group {
            margin-bottom: 20px;
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 600;
            color: #E2E8F0;
            margin-bottom: 8px;
            letter-spacing: 0.2px;
        }

        .field-label .label-icon {
            color: var(--gold-primary);
            margin-right: 6px;
        }

        .field-label .req-star {
            color: #F43F5E;
            font-size: 0.9rem;
        }

        .input-glass-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: #09090d;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .input-glass-wrap:focus-within {
            border-color: var(--gold-primary);
            box-shadow: 0 0 24px rgba(245, 166, 35, 0.32), inset 0 0 8px rgba(245, 166, 35, 0.08);
            background: #0d0d12;
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

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
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
            height: 44px;
            border: none;
            outline: none;
            border-radius: 12px;
            background: var(--gold-gradient);
            color: #060912;
            font-family: 'Outfit', sans-serif;
            font-size: 0.94rem;
            font-weight: 800;
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 8px 20px var(--gold-glow), inset 0 1px 1px rgba(255, 255, 255, 0.6);
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }

        .btn-submit-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(245, 166, 35, 0.58);
        }

        .btn-submit-gold:hover::after {
            left: 100%;
        }

        .btn-submit-gold:active {
            transform: translateY(0);
        }

        .btn-spinner-icon {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #060912;
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
            gap: 12px;
            margin: 16px 0 14px 0;
            color: var(--text-muted);
            font-size: 0.72rem;
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
            height: 40px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(245, 166, 35, 0.35);
            color: #FFD700;
            font-family: 'Outfit', sans-serif;
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all 0.25s ease;
        }

        .btn-action-secondary:hover {
            background: rgba(245, 166, 35, 0.08);
            border-color: var(--gold-primary);
            box-shadow: 0 0 22px rgba(245, 166, 35, 0.25);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Clean Footer */
        .auth-footer-block {
            text-align: center;
            font-size: 0.70rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .footer-nav-links {
            display: flex;
            justify-content: center;
            gap: 14px;
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
                padding: 14px 10px;
            }
            .auth-card-unified {
                padding: 22px 18px 18px 18px;
                border-radius: 18px;
            }
            .card-auth-title {
                font-size: 1.25rem;
            }
            .inside-logo-img {
                height: 34px;
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
        
        <!-- Luxury Unified Auth Card (With Logo Inside) -->
        <div class="auth-card-unified">
            
            <!-- Inside Card Brand Header -->
            <div class="card-brand-header">
                <a href="{{ url('/') }}" class="inside-logo-wrap" title="Cyera AI">
                    <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="inside-logo-img">
                </a>
                <div class="inside-live-badge">
                    <span class="live-dot-pulse"></span>
                    <span>BSC MAINNET DAPP GATEWAY</span>
                </div>
                <h1 class="card-auth-title">Cyera AI Portal</h1>
                <p class="card-auth-subtitle">Connect your Web3 BEP-20 Wallet to enter dApp</p>
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

            <div id="web3Alert" class="auth-alert-banner auth-alert-error" style="display: none;">
                <i class="fas fa-circle-exclamation"></i>
                <span id="web3AlertText"></span>
            </div>

            <!-- ============================================================
                 1. PRIMARY WEB3 CONNECT SECTION
                 ============================================================ -->
            <div id="web3ConnectSection">
                <button type="button" class="btn-submit-gold" id="btnConnectWeb3" style="font-size: 0.94rem; padding: 0 16px; margin-bottom: 10px;">
                    <i class="fas fa-wallet" style="font-size: 1.05rem;"></i>
                    <span id="web3BtnText">Connect MetaMask / TrustWallet</span>
                    <div class="btn-spinner-icon" id="web3Spinner" style="display: none;"></div>
                </button>

                <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 14px; color: var(--text-muted); font-size: 0.74rem;">
                    <span><i class="fab fa-ethereum" style="color: #F5A623;"></i> BNB Chain (BEP-20)</span>
                    <span>•</span>
                    <span><i class="fas fa-shield-halved" style="color: #00FF88;"></i> Cryptographically Verified</span>
                </div>

                <!-- Create Account Link -->
                <a href="{{ url('/register') }}{{ request('ref') ? '?ref='.request('ref') : '' }}" class="btn-action-secondary">
                    <i class="fas fa-user-plus"></i> Create New Account / Register
                </a>
            </div>

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

            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 130) {
                        const alpha = (1 - dist / 130) * 0.35;
                        ctx.strokeStyle = `rgba(245, 166, 35, ${alpha})`;
                        ctx.lineWidth = 0.6;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0) p.x = width;
                if (p.x > width) p.x = 0;
                if (p.y < 0) p.y = height;
                if (p.y > height) p.y = 0;

                p.pulsing += p.pulseSpeed;
                const dynamicAlpha = p.alpha * (0.7 + 0.3 * Math.sin(p.pulsing));

                ctx.save();
                ctx.globalAlpha = dynamicAlpha;
                ctx.fillStyle = p.color;
                ctx.shadowColor = p.color;
                ctx.shadowBlur = 8;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            });

            circuitBeams.forEach(b => {
                if (b.vertical) {
                    b.y += b.speed;
                    if (b.y > height + b.length) {
                        b.y = -b.length;
                        b.x = Math.random() * width;
                    }
                    const grad = ctx.createLinearGradient(b.x, b.y - b.length, b.x, b.y);
                    grad.addColorStop(0, b.color + '0)');
                    grad.addColorStop(1, b.color + b.alpha + ')');
                    ctx.strokeStyle = grad;
                    ctx.lineWidth = 1.2;
                    ctx.beginPath();
                    ctx.moveTo(b.x, b.y - b.length);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                } else {
                    b.x += b.speed;
                    if (b.x > width + b.length) {
                        b.x = -b.length;
                        b.y = Math.random() * height;
                    }
                    const grad = ctx.createLinearGradient(b.x - b.length, b.y, b.x, b.y);
                    grad.addColorStop(0, b.color + '0)');
                    grad.addColorStop(1, b.color + b.alpha + ')');
                    ctx.strokeStyle = grad;
                    ctx.lineWidth = 1.2;
                    ctx.beginPath();
                    ctx.moveTo(b.x - b.length, b.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            });

            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', resize);
        resize();
        animate();
    })();

    // ============================================================
    // WEB3 DAPP METAMASK & TRUSTWALLET CRYPTOGRAPHIC AUTHENTICATION
    // ============================================================
    let detectedWalletAddress = null;
    const btnConnectWeb3 = document.getElementById('btnConnectWeb3');
    const web3BtnText = document.getElementById('web3BtnText');
    const web3Spinner = document.getElementById('web3Spinner');
    const web3Alert = document.getElementById('web3Alert');
    const web3AlertText = document.getElementById('web3AlertText');

    function getMetaMaskProvider() {
        if (typeof window.ethereum !== 'undefined') {
            if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                const mm = window.ethereum.providers.find(p => p.isMetaMask && !p.isPhantom);
                if (mm) return mm;
                const known = window.ethereum.providers.find(p => p.isSafePal || p.isTrust || p.isTrustWallet || p.isBinance || p.isOkxWallet || p.isTokenPocket || p.isBitKeep);
                if (known) return known;
                return window.ethereum.providers[0];
            }
            return window.ethereum;
        }
        if (window.safepal) return window.safepal;
        if (window.phantom && window.phantom.ethereum) return window.phantom.ethereum;
        if (window.trustwallet) return window.trustwallet;
        if (window.okxwallet) return window.okxwallet;
        if (window.tokenpocket) return window.tokenpocket;
        if (window.binance) return window.binance;
        if (window.bitkeep && window.bitkeep.ethereum) return window.bitkeep.ethereum;
        return null;
    }

    function showWeb3Error(msg) {
        if (web3Alert && web3AlertText) {
            web3AlertText.innerText = msg;
            web3Alert.style.display = 'flex';
        }
    }

    function setWeb3Loading(isLoading, text = 'Connecting...') {
        if (!btnConnectWeb3) return;
        if (isLoading) {
            btnConnectWeb3.style.pointerEvents = 'none';
            btnConnectWeb3.style.opacity = '0.85';
            if (web3BtnText) web3BtnText.innerText = text;
            if (web3Spinner) web3Spinner.style.display = 'block';
        } else {
            btnConnectWeb3.style.pointerEvents = 'auto';
            btnConnectWeb3.style.opacity = '1';
            if (web3BtnText) web3BtnText.innerText = 'Connect MetaMask / TrustWallet';
            if (web3Spinner) web3Spinner.style.display = 'none';
        }
    }

    async function triggerWeb3Login(address, provider) {
        detectedWalletAddress = address;
        setWeb3Loading(true, 'Requesting Security Challenge...');

        try {
            // Check / Switch to BSC Mainnet (Chain ID 56 / 0x38)
            const currentChain = await provider.request({ method: 'eth_chainId' });
            if (currentChain !== '0x38') {
                try {
                    await provider.request({
                        method: 'wallet_switchEthereumChain',
                        params: [{ chainId: '0x38' }],
                    });
                } catch (switchError) {
                    if (switchError.code === 4902) {
                        await provider.request({
                            method: 'wallet_addEthereumChain',
                            params: [{
                                chainId: '0x38',
                                chainName: 'BNB Smart Chain Mainnet',
                                nativeCurrency: { name: 'BNB', symbol: 'BNB', decimals: 18 },
                                rpcUrls: ['https://bsc-dataseed.binance.org/'],
                                blockExplorerUrls: ['https://bscscan.com/']
                            }],
                        });
                    }
                }
            }

            // 1. Fetch Nonce Challenge from Server
            const nonceResponse = await fetch("{{ url('/auth/web3-nonce') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    address: detectedWalletAddress
                })
            });

            const nonceData = await nonceResponse.json();
            if (nonceData.status !== 'success' || !nonceData.message) {
                throw new Error(nonceData.message || 'Failed to generate security nonce.');
            }

            // 2. Request Cryptographic Signature in Wallet Popup
            setWeb3Loading(true, 'Please sign message in wallet...');
            let signature = null;
            try {
                signature = await provider.request({
                    method: 'personal_sign',
                    params: [nonceData.message, detectedWalletAddress]
                });
            } catch (signErr) {
                setWeb3Loading(false);
                showWeb3Error('Signature rejected in wallet. Authentication aborted.');
                return;
            }

            setWeb3Loading(true, 'Verifying Cryptographic Proof...');

            // Check for referral sponsor in URL
            const urlParams = new URLSearchParams(window.location.search);
            const sponsorRef = urlParams.get('ref') || '';

            // 3. Send Signature + Message to Backend for Strict On-Chain Verification
            const response = await fetch("{{ url('/auth/web3-login') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    address: detectedWalletAddress,
                    signature: signature,
                    message: nonceData.message,
                    sponsor: sponsorRef
                })
            });

            const data = await response.json();

            if (data.status === 'success') {
                setWeb3Loading(true, 'Access Granted! Entering...');
                window.location.href = data.redirect;
            } else if (data.status === 'not_registered') {
                setWeb3Loading(true, 'Redirecting to Registration...');
                const regUrl = "{{ url('/register') }}?wallet=" + encodeURIComponent(detectedWalletAddress) + (sponsorRef ? "&ref=" + encodeURIComponent(sponsorRef) : "");
                window.location.href = regUrl;
            } else {
                setWeb3Loading(false);
                showWeb3Error(data.message || 'Authentication failed.');
            }

        } catch (err) {
            setWeb3Loading(false);
            showWeb3Error(err.message || 'Verification cancelled or failed.');
        }
    }

    if (btnConnectWeb3) {
        btnConnectWeb3.addEventListener('click', async function () {
            if (web3Alert) web3Alert.style.display = 'none';

            const provider = getMetaMaskProvider();
            if (!provider) {
                showWeb3Error('MetaMask / Web3 Wallet not found! Please install MetaMask or open inside TrustWallet dApp Browser.');
                return;
            }

            setWeb3Loading(true, 'Requesting Wallet...');

            try {
                const accounts = await provider.request({ method: 'eth_requestAccounts' });
                if (!accounts || accounts.length === 0) {
                    throw new Error('No account selected');
                }

                await triggerWeb3Login(accounts[0], provider);

            } catch (err) {
                setWeb3Loading(false);
                showWeb3Error(err.message || 'Connection cancelled or failed.');
            }
        });
    }

    // Auto-detect connected wallet & listen for live account changes on login page
    (function initLoginWeb3Watcher() {
        const provider = getMetaMaskProvider();
        if (!provider) return;

        async function checkConnectedAccount() {
            try {
                const accounts = await provider.request({ method: 'eth_accounts' });
                if (accounts && accounts.length > 0) {
                    detectedWalletAddress = accounts[0];
                    const shortAddr = detectedWalletAddress.substring(0, 6) + '...' + detectedWalletAddress.substring(detectedWalletAddress.length - 4);
                    if (web3BtnText && !btnConnectWeb3.style.pointerEvents.includes('none')) {
                        web3BtnText.innerText = 'Connect: ' + shortAddr;
                    }

                    // If auto-connect parameter is present in URL
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('auto_connect') === '1') {
                        triggerWeb3Login(detectedWalletAddress, provider);
                    }
                }
            } catch (e) {}
        }

        checkConnectedAccount();

        if (typeof provider.on === 'function') {
            provider.on('accountsChanged', function (accounts) {
                if (accounts && accounts.length > 0) {
                    detectedWalletAddress = accounts[0];
                    const shortAddr = detectedWalletAddress.substring(0, 6) + '...' + detectedWalletAddress.substring(detectedWalletAddress.length - 4);
                    if (web3BtnText) {
                        web3BtnText.innerText = 'Connect: ' + shortAddr;
                    }
                    if (web3Alert) web3Alert.style.display = 'none';
                } else {
                    detectedWalletAddress = null;
                    if (web3BtnText) {
                        web3BtnText.innerText = 'Connect MetaMask / TrustWallet';
                    }
                }
            });

            provider.on('chainChanged', function () {
                window.location.reload();
            });
        }
    })();
    </script>
</body>
</html>