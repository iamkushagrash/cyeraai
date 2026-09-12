<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>@yield('title', 'Cyera AI - Web3 Portal')</title>
    <meta name="description" content="CYERA AI - Next-Gen Decentralized Mecha Web3 Ecosystem">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/cyera-dashboard.css') }}?v={{ time() }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    @stack('styles')
</head>
<body>

<!-- ============================================================
     PRELOADER: MINIMALIST LUXURY CYBER AI TOKEN LOADER
     ============================================================ -->
<div id="cyeraAppLoader" class="cyera-loader-overlay">
    <div class="cyera-loader-content">
        <!-- Clean Central Token Stage with Single Smooth Orbit -->
        <div class="clean-loader-stage">
            <div class="loader-halo-glow"></div>
            <div class="loader-spinner-ring"></div>
            <div class="loader-coin-box">
                <img src="{{ asset('images/cai-token-coin.png') }}" alt="CYERA AI" class="loader-coin-img" onerror="this.src='{{ asset('icon.png') }}'">
            </div>
        </div>

        <!-- Sleek Telemetry Status -->
        <div class="loader-telemetry-panel">
            <div class="telemetry-brand">CYERA AI PROTOCOL</div>
            <div class="telemetry-status-msg" id="loaderDynamicPhrase">INITIALIZING QUANTUM CORE...</div>
            <div class="telemetry-progress-track">
                <div class="telemetry-progress-fill" id="loaderProgressBar"></div>
            </div>
            <div class="telemetry-meta-row">
                <span class="meta-item"><i class="fas fa-circle" style="color: #00FF88; font-size: 7px; margin-right: 4px;"></i> NODE ONLINE</span>
                <span class="meta-item meta-pct" id="loaderPercentVal">0%</span>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     LIVE ANIMATED CYBER VIDEO CANVAS BACKGROUND
     ============================================================ -->
<div class="cyber-video-bg-container" aria-hidden="true">
    <div class="cyber-nebula-orb orb-1"></div>
    <div class="cyber-nebula-orb orb-2"></div>
    <div class="cyber-nebula-orb orb-3"></div>
    <div class="cyber-grid-scan-layer"></div>
    <canvas id="cyberMatrixCanvas"></canvas>
</div>

<!-- ============================================================
     MASTER APP WRAPPER (100% IDENTICAL TO DASHBOARD)
     ============================================================ -->
<div class="master-app-wrap">
    <div class="hud-app-shell">

        <!-- DITTO MECHA SIDEBAR -->
        @include('user.sidebar-mecha')

        <!-- 1. TOP HEADER -->
        @include('user.header-mecha')

        <!-- 2. SCROLLABLE INNER HUD AREA -->
        <div class="hud-scroll-area">

            <!-- PAGE TITLE / BREADCRUMB CARD -->
            @hasSection('page-header')
                @yield('page-header')
            @else
                <div class="mecha-page-title-card">
                    <div class="mecha-page-title-left">
                        <div class="mecha-page-icon-box">
                            <i class="@yield('page-icon', 'fas fa-layer-group')"></i>
                        </div>
                        <div class="mecha-page-text-stack">
                            <div class="mecha-page-breadcrumb">
                                <a href="{{ url('/User/Dashboard') }}"><i class="fas fa-home"></i> HOME</a>
                                <span class="sep">/</span>
                                <span class="active">@yield('page-title', 'PAGE')</span>
                            </div>
                            <h1 class="mecha-page-title">@yield('page-title', 'Cyera Protocol')</h1>
                        </div>
                    </div>
                    <div class="mecha-page-title-right">
                        @yield('page-actions')
                    </div>
                </div>
            @endif

            <!-- FLASH NOTIFICATIONS -->
            @if(session('success'))
                <div class="mecha-alert mecha-alert-success" style="margin: 0 12px 12px 12px;">
                    <div class="alert-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="alert-content">
                        <span class="alert-title">SUCCESS</span>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            @if(session('warning'))
                <div class="mecha-alert mecha-alert-danger" style="margin: 0 12px 12px 12px;">
                    <div class="alert-icon"><i class="fas fa-triangle-exclamation"></i></div>
                    <div class="alert-content">
                        <span class="alert-title">SYSTEM ALERT</span>
                        <p>{{ session('warning') }}</p>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mecha-alert mecha-alert-danger" style="margin: 0 12px 12px 12px;">
                    <div class="alert-icon"><i class="fas fa-triangle-exclamation"></i></div>
                    <div class="alert-content">
                        <span class="alert-title">SYSTEM ALERT</span>
                        <p>{{ session('error') }}</p>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            @if(isset($errors) && count($errors) > 0)
                <div class="mecha-alert mecha-alert-danger" style="margin: 0 12px 12px 12px;">
                    <div class="alert-icon"><i class="fas fa-triangle-exclamation"></i></div>
                    <div class="alert-content">
                        <span class="alert-title">VALIDATION ERROR</span>
                        <ul style="margin: 0; padding-left: 16px; font-size: 11px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            <!-- INNER MAIN BODY CONTENT -->
            <main class="mecha-inner-body">
                @yield('content')
            </main>

            <!-- Spacer for bottom dock -->
            <div style="height: 24px;"></div>
        </div><!-- /hud-scroll-area -->

        <!-- 3. BOTTOM NAVIGATION (EXACT DITTO) -->
        @include('user.bottomnav-mecha')

    </div><!-- /hud-app-shell -->
</div><!-- /master-app-wrap -->

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- Live Cyber Canvas Engine -->
<script>
    (function () {
        const canvas = document.getElementById('cyberMatrixCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width, height, particles = [], circuitBeams = [];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            initParticles();
        }

        function initParticles() {
            particles = [];
            circuitBeams = [];
            const count = Math.min(Math.floor(width / 16), 85);
            const palette = ['#FFD700', '#FFE082', '#00FF88', '#FFB300', '#00E5FF'];

            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.08,
                    vy: -Math.random() * 0.10 - 0.04,
                    radius: Math.random() * 2.0 + 1.0,
                    color: palette[Math.floor(Math.random() * palette.length)],
                    alpha: Math.random() * 0.50 + 0.20,
                    pulsing: Math.random() * Math.PI * 2,
                    pulseSpeed: Math.random() * 0.005 + 0.002
                });
            }

            for (let i = 0; i < 5; i++) {
                circuitBeams.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    length: Math.random() * 80 + 40,
                    speed: Math.random() * 0.35 + 0.15,
                    vertical: Math.random() > 0.45,
                    alpha: Math.random() * 0.40 + 0.20,
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
                        const alpha = (1 - dist / 130) * 0.32;
                        ctx.strokeStyle = `rgba(255, 215, 0, ${alpha})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

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

            for (let i = 0; i < circuitBeams.length; i++) {
                const b = circuitBeams[i];
                ctx.save();
                ctx.lineWidth = 1.6;
                ctx.shadowBlur = 8;
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

    /* 3D Gyroscope Loader Controller */
    (function () {
        const loader = document.getElementById('cyeraAppLoader');
        const bar = document.getElementById('loaderProgressBar');
        const percentTxt = document.getElementById('loaderPercentVal');
        const phraseTxt = document.getElementById('loaderDynamicPhrase');
        if (!loader) return;

        const phrases = [
            "INITIALIZING QUANTUM CORE...",
            "CALIBRATING 3D GYROSCOPE...",
            "SCANNING MECHA PROTOCOL...",
            "DECENTRALIZED ACCESS GRANTED"
        ];

        let progress = 0;
        let phraseIdx = 0;

        const interval = setInterval(() => {
            progress += Math.floor(Math.random() * 18) + 14;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                if (bar) bar.style.width = '100%';
                if (percentTxt) percentTxt.innerText = '100%';
                if (phraseTxt) phraseTxt.innerText = phrases[phrases.length - 1];
                setTimeout(() => {
                    loader.classList.add('loader-hidden');
                    setTimeout(() => { loader.style.display = 'none'; }, 500);
                }, 300);
            } else {
                if (bar) bar.style.width = progress + '%';
                if (percentTxt) percentTxt.innerText = progress + '%';
                const targetPhraseIdx = Math.min(Math.floor((progress / 100) * phrases.length), phrases.length - 1);
                if (targetPhraseIdx !== phraseIdx) {
                    phraseIdx = targetPhraseIdx;
                    if (phraseTxt) phraseTxt.innerText = phrases[phraseIdx];
                }
            }
        }, 60);

        window.addEventListener('load', () => {
            setTimeout(() => {
                if (loader && !loader.classList.contains('loader-hidden')) {
                    if (bar) bar.style.width = '100%';
                    if (percentTxt) percentTxt.innerText = '100%';
                    loader.classList.add('loader-hidden');
                    setTimeout(() => { loader.style.display = 'none'; }, 500);
                }
            }, 800);
        });
    })();

    /* ============================================================
       SIDEBAR DRAWER & SUBMENU CONTROLLER
       ============================================================ */
    function openMechaSidebar() {
        const sidebar = document.getElementById('mechaSidebar');
        const backdrop = document.getElementById('mechaBackdrop');
        if (sidebar) sidebar.classList.add('active');
        if (backdrop) backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMechaSidebar() {
        const sidebar = document.getElementById('mechaSidebar');
        const backdrop = document.getElementById('mechaBackdrop');
        if (sidebar) sidebar.classList.remove('active');
        if (backdrop) backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    function toggleMechaSubmenu(elem) {
        const parent = elem.closest('.mecha-nav-has-sub');
        if (parent) {
            parent.classList.toggle('open');
        }
    }
</script>

@stack('scripts')

</body>
</html>
