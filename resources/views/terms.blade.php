<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cyera AI | Terms & Conditions</title>
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
            --card-bg: rgba(10, 15, 28, 0.90);
            --card-border: rgba(245, 166, 35, 0.32);
            --gold-primary: #F5A623;
            --gold-gradient: linear-gradient(135deg, #FFD700 0%, #F5A623 50%, #D48806 100%);
            --gold-glow: rgba(245, 166, 35, 0.4);
            --cyan-accent: #00F0FF;
            --green-active: #00FF88;
            --text-primary: #FFFFFF;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;
            --border-glass: rgba(245, 166, 35, 0.22);
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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow-x: hidden;
            position: relative;
            padding: 40px 16px 60px 16px;
        }

        /* Master Container */
        .terms-master-shell {
            width: 100%;
            max-width: 900px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin: 0 auto;
            animation: termsFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes termsFadeUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Luxury Card Container */
        .terms-luxury-card {
            background: var(--card-bg);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border-radius: var(--radius-card);
            border: 1px solid var(--card-border);
            box-shadow: 
                0 32px 64px -16px rgba(0, 0, 0, 0.95),
                0 0 45px rgba(245, 166, 35, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.18);
            padding: 42px 36px;
            position: relative;
            overflow: hidden;
        }

        .terms-luxury-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.8), rgba(0, 240, 255, 0.6), transparent);
        }

        /* Header Block */
        .terms-header-block {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-subtle);
            padding-bottom: 24px;
        }

        .terms-logo-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .terms-logo-link:hover {
            transform: scale(1.05);
        }

        .terms-logo-img {
            height: 48px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 16px rgba(245, 166, 35, 0.5));
        }

        .terms-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            background: rgba(0, 240, 255, 0.1);
            border: 1px solid rgba(0, 240, 255, 0.28);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--cyan-accent);
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .terms-main-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF3C4 60%, var(--gold-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }

        .terms-meta-date {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Terms Content Container */
        .terms-body-content {
            color: #CBD5E1;
            font-size: 0.94rem;
            line-height: 1.75;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .terms-section {
            background: rgba(6, 10, 20, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 24px;
            transition: border-color 0.25s ease;
        }

        .terms-section:hover {
            border-color: rgba(245, 166, 35, 0.25);
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #FFD700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            letter-spacing: 0.2px;
        }

        .section-title .sec-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(245, 166, 35, 0.15);
            border: 1px solid rgba(245, 166, 35, 0.35);
            color: var(--gold-primary);
            font-size: 0.85rem;
            font-weight: 800;
        }

        .terms-body-content p {
            margin-bottom: 10px;
        }

        .terms-body-content p:last-child {
            margin-bottom: 0;
        }

        .terms-body-content strong {
            color: #FFFFFF;
            font-weight: 600;
        }

        .terms-body-content ul {
            padding-left: 22px;
            margin-top: 8px;
            margin-bottom: 8px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .terms-body-content li {
            color: #94A3B8;
        }

        .terms-body-content li strong {
            color: #E2E8F0;
        }

        /* Highlight Notice Box */
        .notice-highlight-box {
            background: rgba(245, 166, 35, 0.08);
            border: 1px solid rgba(245, 166, 35, 0.3);
            border-radius: 12px;
            padding: 16px 20px;
            color: #FFE082;
            font-size: 0.90rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .notice-highlight-box i {
            color: var(--gold-primary);
            font-size: 1.2rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        /* Action Buttons */
        .terms-action-bar {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-subtle);
            flex-wrap: wrap;
        }

        .btn-gold-action {
            height: 48px;
            padding: 0 28px;
            border-radius: var(--radius-btn);
            background: var(--gold-gradient);
            color: #060912;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 10px 24px var(--gold-glow);
            transition: all 0.3s ease;
        }

        .btn-gold-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(245, 166, 35, 0.55);
        }

        .btn-cyan-action {
            height: 48px;
            padding: 0 28px;
            border-radius: var(--radius-btn);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 240, 255, 0.28);
            color: var(--cyan-accent);
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-cyan-action:hover {
            background: rgba(0, 240, 255, 0.08);
            border-color: var(--cyan-accent);
            color: #FFFFFF;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
            transform: translateY(-1px);
        }

        /* Footer */
        .terms-footer-block {
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-nav-row {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-nav-row a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-nav-row a:hover {
            color: var(--gold-primary);
        }

        @media (max-width: 768px) {
            .terms-luxury-card {
                padding: 30px 20px;
                border-radius: 20px;
            }
            .terms-main-title {
                font-size: 1.6rem;
            }
            .terms-section {
                padding: 18px 16px;
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

    <!-- Main Container Shell -->
    <div class="terms-master-shell">
        
        <!-- Luxury Terms Card -->
        <div class="terms-luxury-card">
            
            <!-- Header Block -->
            <div class="terms-header-block">
                <a href="{{ url('/') }}" class="terms-logo-link" title="Cyera AI">
                    <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="terms-logo-img">
                </a>
                <div class="terms-badge">
                    <i class="fas fa-shield-halved"></i>
                    <span>CAI PROTOCOL USER AGREEMENT</span>
                </div>
                <h1 class="terms-main-title">Terms & Conditions</h1>
                <div class="terms-meta-date">Last Updated: September 2026 • Version 2.4 (CAI Token Standard)</div>
            </div>

            <!-- Important Notice Banner -->
            <div class="notice-highlight-box">
                <i class="fas fa-triangle-exclamation"></i>
                <div>
                    <strong>Decentralized Smart Contract Disclaimer:</strong> Cyera AI operates as an autonomous decentralized AI & Web3 tokenized ecosystem powered by the <strong>CAI Token</strong>. Staking, yield release, dynamic capping, and automated vaults execute purely on-chain via algorithmic protocol rules.
                </div>
            </div>

            <br>

            <!-- Terms Body Content -->
            <div class="terms-body-content">

                <!-- Section 1 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">1</span>
                        <span>Scope of the CAI Protocol & Ecosystem</span>
                    </div>
                    <p>
                        Cyera AI (<strong>“CAI”</strong>, <strong>“Platform”</strong>, <strong>“We”</strong>, <strong>“Protocol”</strong>) is a decentralized Web3 AI compute infrastructure, automated arbitrage mechanism, and tokenized staking system.
                    </p>
                    <p>
                        The native utility token <strong>CAI</strong> powers all network fee settlements, staking yield rewards, governance weight, and ecosystem service access across the Cyera ecosystem.
                    </p>
                </div>

                <!-- Section 2 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">2</span>
                        <span>Digital Acceptance & Binding Agreement</span>
                    </div>
                    <p>
                        By checking the agreement box during registration, connecting your Web3 wallet, depositing USDT/CAI, staking assets, or activating node contracts, you explicitly agree and enter into a legally binding digital contract.
                    </p>
                    <ul>
                        <li>Checking <strong>“I agree to Cyera AI Terms & Conditions”</strong> constitutes valid cryptographic & legal consent.</li>
                        <li>Platform interaction without formal dispute within 24 hours of protocol updates constitutes continuous acceptance of amended terms.</li>
                    </ul>
                </div>

                <!-- Section 3 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">3</span>
                        <span>CAI Staking & 5X Dynamic Capping Mechanism</span>
                    </div>
                    <p>
                        The Cyera AI platform utilizes an algorithmic <strong>Dynamic Capping Protocol</strong> designed for long-term ecosystem sustainability and deflationary liquidity protection:
                    </p>
                    <ul>
                        <li><strong>Maximum Earnings Ceiling (5X Capping):</strong> Total earnings across Daily ROI, Direct Referrals, Level Bonuses, and Team Incentives are capped up to a maximum of <strong>500% (5X)</strong> of the user's active staking package tier.</li>
                        <li><strong>Capping Fulfillment:</strong> Once cumulative earnings reach 100% of the 5X maximum limit, further income generation automatically pauses until package re-staking or tier re-activation occurs.</li>
                        <li><strong>Income Release Schedule:</strong> Daily staking yields and staking bonuses are credited to users' claimable balance as governed by protocol parameters and live valuation metrics.</li>
                    </ul>
                </div>

                <!-- Section 4 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">4</span>
                        <span>Decentralized Vaults, Deposits & Withdrawals</span>
                    </div>
                    <ul>
                        <li><strong>Deposit Verification:</strong> Users are responsible for confirming correct smart contract addresses, token standards (USDT BEP-20 / TRC-20 / CAI), and transaction hashes during deposits.</li>
                        <li><strong>Automated Vault Execution:</strong> Withdrawal requests are processed through decentralized liquidity vaults. Minimum withdrawal amounts, network gas fees, and dynamic slippage parameters are calculated transparently at the time of claim.</li>
                        <li><strong>Self-Custodial Security:</strong> Users bear 100% sole responsibility for safeguarding their private keys, seed phrases, and account credentials. Cyera AI administrators will never request your private keys.</li>
                    </ul>
                </div>

                <!-- Section 5 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">5</span>
                        <span>Community Referrals & Multi-Tier Incentives</span>
                    </div>
                    <p>
                        Cyera AI offers multi-level decentralized network incentives to reward community builders and ecosystem expansion:
                    </p>
                    <ul>
                        <li><strong>Direct Referral Bonus:</strong> Automatically calculated and distributed upon confirmed staking by personally invited downline members.</li>
                        <li><strong>Level Bonus Distribution:</strong> Unlocks incrementally across community network levels based on user's active package tier and direct sponsor criteria.</li>
                        <li><strong>Anti-Abuse & Wash Trading Prevention:</strong> Generating artificial sponsor chains, multi-account spamming, or manipulating volume metrics is strictly monitored by algorithmic integrity filters.</li>
                    </ul>
                </div>

                <!-- Section 6 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">6</span>
                        <span>Risk Disclosure & Volatility Notice</span>
                    </div>
                    <p>
                        Digital assets, decentralized finance (DeFi), and AI-tokenized algorithms carry inherent market volatility and technological risks:
                    </p>
                    <ul>
                        <li>Cryptocurrency valuations and token price indices fluctuate in real time depending on open market liquidity.</li>
                        <li>Historical staking yield performance does not constitute a guaranteed promise of future token valuations.</li>
                        <li>Users should only stake and commit capital they are fully prepared to allocate within decentralized market conditions.</li>
                    </ul>
                </div>

                <!-- Section 7 -->
                <div class="terms-section">
                    <div class="section-title">
                        <span class="sec-num">7</span>
                        <span>Official Communication & Support</span>
                    </div>
                    <p>
                        For technical assistance, node inquiry, or legal correspondence regarding the CAI protocol:
                    </p>
                    <ul>
                        <li>📧 <strong>Official Support:</strong> support@cyera.ai</li>
                        <li>📧 <strong>Compliance & Legal:</strong> legal@cyera.ai</li>
                        <li>🌐 <strong>Web3 Portal:</strong> <a href="{{ url('/') }}" style="color:var(--cyan-accent); text-decoration:none;">https://cyera.ai</a></li>
                    </ul>
                </div>

            </div>

            <!-- Action Bar -->
            <div class="terms-action-bar">
                <a href="{{ url('/register') }}" class="btn-gold-action">
                    <i class="fas fa-user-plus"></i> Proceed to Registration
                </a>
                <a href="{{ url('/login') }}" class="btn-cyan-action">
                    <i class="fas fa-arrow-right-to-bracket"></i> Sign In to Account
                </a>
            </div>

        </div>

        <!-- Footer -->
        <footer class="terms-footer-block">
            <div class="footer-nav-row">
                <a href="{{ url('/login') }}">Login</a>
                <span>•</span>
                <a href="{{ url('/register') }}">Register</a>
                <span>•</span>
                <a href="https://t.me/" target="_blank">Telegram Support</a>
            </div>
            <div>&copy; 2026 CYERA AI (CAI PROTOCOL). All Rights Reserved.</div>
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
    </script>
</body>
</html>
