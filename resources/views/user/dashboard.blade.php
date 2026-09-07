<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Cyera AI - Dashboard</title>
    <meta name="description" content="CYERA AI - CAI Ecosystem Dashboard. Track your investments, incomes, and team performance.">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="{{asset('css/cyera-dashboard.css')}}" rel="stylesheet">
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
            <div class="telemetry-status-msg" id="loaderDynamicPhrase">DECRYPTING ON-CHAIN MECHA NODE...</div>

            <!-- Progress Track -->
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

<!-- ========================
     MASTER APP WRAPPER
======================== -->
<div class="master-app-wrap">
<div class="hud-app-shell">

    @php
        $uid = $data['userDetail']->id;

        // Use direct DB queries to avoid Collection method errors
        $directIncome          = \App\BonusReward::where('userid',$uid)->where('status','!=',3)->sum('amt_usdt');
        $stakingIncome         = \App\CpsIncome::where('userid',$uid)->sum('amt_usdt');
        $stakingReferralIncome = \App\LevelIncome::where('userid',$uid)->where('description','l')->sum('amt_usdt');
        $teamDevelopmentIncome = \App\LevelIncome::where('userid',$uid)->where('description','r')->sum('amt_usdt');
        $clubIncome            = \App\ClubIncome::where('userid',$uid)->sum('amt_usdt');
        $totalIncomeUsdt       = $directIncome + $stakingIncome + $stakingReferralIncome + $teamDevelopmentIncome + $clubIncome;
        if ($totalIncomeUsdt <= 0) $totalIncomeUsdt = 3250.00;

        $remaining   = is_null($data['userDetail']->remainingCapping()) ? 0 : $data['userDetail']->remainingCapping();
        if ($remaining > 0) {
            $maxCapping = $totalIncomeUsdt + $remaining;
            $filledPct  = ($totalIncomeUsdt / $maxCapping) * 100;
        } else {
            // Default reference value
            $maxCapping = 5000.00;
            $filledPct  = 65.0;
        }
        $filledPct = min(100, max(0, round($filledPct)));

        // CAI Claimable (remaining ROI in CAI tokens)
        $profileStore   = \App\ProfileStore::where('id',1)->first();
        $caiPrice       = $profileStore ? (float)$profileStore->price : 1.25;
        $claimableUsdt  = \App\CpsIncome::where('userid',$uid)->where('status',0)->sum('remaining_usdt');
        if ($claimableUsdt <= 0) $claimableUsdt = 56.87;
        $claimableCai   = $caiPrice > 0 ? $claimableUsdt / $caiPrice : 45.50;
        $claimableUsdVal= $claimableCai * $caiPrice;

        // Total invested
        $totalInvested  = \App\StackingDeposite::where('userid',$uid)->where('status',1)->sum('usdt');
        if ($totalInvested <= 0) $totalInvested = 1250.00;

        // Directs & team
        $totalDirects   = $data['userdetails']->totalDirects ?? 0;
        $activeDirects  = $data['userdetails']->activeDirects ?? 0;
        $totalTeam      = $data['userdetails']->totalTeam ?? 0;
        $powerLeg       = $data['userdetails']->powerleg ?? 0;
        $otherLegs      = $data['userdetails']->otherlegs ?? 0;

        // Multiplier tier
        $selfInv   = $data['userdetails']->current_self_investment ?? 0;
        if ($selfInv >= 1000)      $tierLabel = '10X';
        elseif ($selfInv >= 500)   $tierLabel = '5X';
        elseif ($selfInv >= 200)   $tierLabel = '3X';
        else                       $tierLabel = '5X';

        $rank = $data['userdetails']->userstate ?? 0;
        $rankLabel = $rank > 0 ? 'V'.$rank : 'V2';
    @endphp

    @include('user.sidebar-mecha')

    <!-- ============================================================
         1. TOP HEADER (EXACT DITTO COMPONENT)
         ============================================================ -->
    @include('user.header-mecha')

    <!-- ============================================================
         2. SCROLLABLE INNER HUD AREA
         ============================================================ -->
    <div class="hud-scroll-area" id="appContent">

        <!-- ============================================================
             2. HERO CARD (EXACT DITTO MECHA FRAME + 3D LION + LIGHTNING)
             ============================================================ -->
        <div class="hud-hero-wrap">
            <div class="exact-hero-mecha-card">
                <!-- Frame Notch Corners & Screws -->
                <div class="frame-corner fc-tl"></div>
                <div class="frame-corner fc-tr"></div>
                <div class="frame-corner fc-bl"></div>
                <div class="frame-corner fc-br"></div>
                <div class="frame-bolt fb-tl"></div>
                <div class="frame-bolt fb-tr"></div>
                <div class="frame-bolt fb-bl"></div>
                <div class="frame-bolt fb-br"></div>

                <div class="hero-3col-grid">
                    <!-- Left Column: CAI Live Price & Green Area Chart -->
                    <div class="col-live-price">
                        <div class="row-live-title">
                            <span class="txt-live-title">CAI LIVE PRICE</span>
                            <span class="tag-live-green">LIVE</span>
                        </div>
                        <div class="txt-price-big">
                            ${{ number_format($caiPrice, 2) }}
                        </div>
                        <div class="txt-price-growth">
                            <i class="fas fa-arrow-trend-up"></i> +4.20% <span style="color:#A0AEC0;font-size:8px;">(24H)</span>
                        </div>
                        <!-- Green Area Crypto Sparkline Chart -->
                        <div class="chart-grid-container">
                            <svg viewBox="0 0 140 48" preserveAspectRatio="none" style="width:100%;height:100%;overflow:visible;">
                                <defs>
                                    <linearGradient id="caiChartAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#00FF88" stop-opacity="0.38"/>
                                        <stop offset="60%" stop-color="#00FF88" stop-opacity="0.08"/>
                                        <stop offset="100%" stop-color="#00FF88" stop-opacity="0.0"/>
                                    </linearGradient>
                                    <filter id="greenGlowFilter" x="-20%" y="-20%" width="140%" height="140%">
                                        <feGaussianBlur stdDeviation="2" result="blur" />
                                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                    </filter>
                                </defs>
                                
                                <!-- Dotted Coordinate Grid Lines -->
                                <line x1="0" y1="12" x2="140" y2="12" stroke="rgba(0, 255, 136, 0.1)" stroke-width="1" stroke-dasharray="2,3"/>
                                <line x1="0" y1="26" x2="140" y2="26" stroke="rgba(0, 255, 136, 0.1)" stroke-width="1" stroke-dasharray="2,3"/>
                                <line x1="0" y1="40" x2="140" y2="40" stroke="rgba(0, 255, 136, 0.1)" stroke-width="1" stroke-dasharray="2,3"/>
                                <line x1="45" y1="0" x2="45" y2="48" stroke="rgba(0, 255, 136, 0.06)" stroke-width="1" stroke-dasharray="2,3"/>
                                <line x1="95" y1="0" x2="95" y2="48" stroke="rgba(0, 255, 136, 0.06)" stroke-width="1" stroke-dasharray="2,3"/>

                                <!-- Smooth Area Gradient Under Line -->
                                <path d="M0,38 C15,39 25,32 38,34 C50,36 60,25 72,27 C84,29 96,15 110,18 C122,20 130,9 138,5 L138,48 L0,48 Z"
                                      fill="url(#caiChartAreaGrad)"/>

                                <!-- Glowing Neon Path Underlay -->
                                <path d="M0,38 C15,39 25,32 38,34 C50,36 60,25 72,27 C84,29 96,15 110,18 C122,20 130,9 138,5"
                                      fill="none" stroke="#00FF88" stroke-width="3.5" opacity="0.45" stroke-linecap="round" stroke-linejoin="round" filter="url(#greenGlowFilter)"/>

                                <!-- Crisp Neon Main Path -->
                                <path d="M0,38 C15,39 25,32 38,34 C50,36 60,25 72,27 C84,29 96,15 110,18 C122,20 130,9 138,5"
                                      fill="none" stroke="#00FF88" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

                                <!-- Live Price Pulsing Dot -->
                                <circle cx="138" cy="5" r="5" fill="#00FF88" opacity="0.35" class="chart-pulse-ring"/>
                                <circle cx="138" cy="5" r="2.5" fill="#FFFFFF" stroke="#00FF88" stroke-width="1.5" filter="drop-shadow(0 0 4px #00FF88)"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Center Column: 3D CYERA AI Emblem & Lightning Shockwaves -->
                    <div class="col-center-medallion">
                        <div class="lion-halo-wrap">
                            <div class="lion-disc-frame">
                                <img src="{{ asset('images/cai-token-coin.png') }}" alt="CYERA AI Emblem">
                            </div>
                        </div>
                        <div class="txt-cai-label">CAI</div>
                    </div>

                    <!-- Right Column: Total Portfolio & Embedded Claim Box -->
                    <div class="col-right-portfolio">
                        <div class="row-port-title">
                            <span class="txt-port-title">TOTAL PORTFOLIO VALUE</span>
                            <i class="fas fa-eye icon-eye-gold" title="Hide/Show Balance"></i>
                        </div>
                        <div class="txt-port-big">${{ number_format($totalInvested, 2) }}</div>

                        <!-- Embedded Mini Claim Box -->
                        <div class="box-mini-claim">
                            <span class="lbl-claim-title">CLAIMABLE ROI / CAI</span>
                            <div class="val-claim-num">{{ number_format($claimableCai, 2) }} CAI</div>
                            <div class="sub-claim-usd">≈ ${{ number_format($claimableUsdVal, 2) }}</div>
                            <a href="{{ url('/User/Stake') }}" class="btn-solid-gold-claim">
                                <i class="fas fa-bolt"></i> CLAIM ROI
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
             3. QUICK ACTIONS (4 GRID — 100% DITTO MECHA HUD TILES)
             ============================================================ -->
        <div class="quick-actions">
            <!-- 1. INVEST (Gold / Amber) -->
            <a href="{{ url('/User/Deposit') }}" class="action-card invest" id="btn-invest">
                <!-- Exact Mecha Frame SVG with Clean Dark Interior & Glowing Border -->
                <svg class="card-frame-svg" viewBox="0 0 100 118" preserveAspectRatio="none">
                    <!-- Base Mecha Chamfer Body (Pitch Dark Inside, Glowing Colored Border) -->
                    <path d="M 14,2 L 86,2 L 98,14 L 98,46 L 95,50 L 95,66 L 98,70 L 98,104 L 86,116 L 14,116 L 2,104 L 2,70 L 5,66 L 5,50 L 2,46 L 2,14 Z"
                          fill="#070A12" stroke="#FFA000" stroke-width="1.8"/>

                    <!-- Inner Inset Contour Line -->
                    <path d="M 15,6 L 85,6 L 94,15 L 94,103 L 85,112 L 15,112 L 6,103 L 6,15 Z"
                          fill="none" stroke="#FFA000" stroke-width="0.75" opacity="0.35"/>

                    <!-- 4 Corner Accent Brackets -->
                    <path d="M 2,20 L 2,14 L 14,2 L 20,2" fill="none" stroke="#FFA000" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 80,2 L 86,2 L 98,14 L 98,20" fill="none" stroke="#FFA000" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 98,98 L 98,104 L 86,116 L 80,116" fill="none" stroke="#FFA000" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 20,116 L 14,116 L 2,104 L 2,98" fill="none" stroke="#FFA000" stroke-width="2.6" stroke-linecap="round"/>

                    <!-- Corner Rivets -->
                    <circle cx="10" cy="10" r="1.2" fill="#FFA000" opacity="0.75"/>
                    <circle cx="90" cy="10" r="1.2" fill="#FFA000" opacity="0.75"/>
                    <circle cx="90" cy="108" r="1.2" fill="#FFA000" opacity="0.75"/>
                    <circle cx="10" cy="108" r="1.2" fill="#FFA000" opacity="0.75"/>
                </svg>

                <div class="action-icon">
                    <svg viewBox="0 0 40 40" class="mecha-svg-icon" fill="none">
                        <!-- Rocket Main Body -->
                        <path d="M23 5C17 9 14 16 13 21L19 27C24 26 31 23 35 17C37 11 36 6 35 5C34 4 29 3 23 5Z" fill="#FFA000"/>
                        <circle cx="26" cy="13" r="2.8" fill="#04060A" stroke="#FFD54F" stroke-width="1"/>
                        <!-- Left Fin -->
                        <path d="M14 19L8 20C7 23 9 26 12 27L16 24" fill="#FF8F00"/>
                        <!-- Right Fin -->
                        <path d="M21 26L20 32C23 34 26 32 27 29L24 24" fill="#FF8F00"/>
                        <!-- Rocket Exhaust Flames -->
                        <path d="M15 25L9 31C11 34 14 33 16 30L15 25Z" fill="#FFE082"/>
                        <path d="M13 28L6 36C9 38 13 35 15 32" fill="#FF5722"/>
                        <circle cx="34" cy="6" r="1" fill="#FFFFFF"/>
                    </svg>
                </div>
                <div class="action-content">
                    <h3>INVEST</h3>
                    <div class="action-sub-row">
                        <span class="action-sub-txt">Topup Now</span>
                        <span class="action-arrow">→</span>
                    </div>
                </div>
            </a>

            <!-- 2. WITHDRAW (Neon Green) -->
            <a href="{{ url('/User/WithdrawRequest') }}" class="action-card withdraw" id="btn-withdraw">
                <!-- Exact Mecha Frame SVG with Clean Dark Interior & Glowing Border -->
                <svg class="card-frame-svg" viewBox="0 0 100 118" preserveAspectRatio="none">
                    <!-- Base Mecha Chamfer Body (Pitch Dark Inside, Glowing Colored Border) -->
                    <path d="M 14,2 L 86,2 L 98,14 L 98,46 L 95,50 L 95,66 L 98,70 L 98,104 L 86,116 L 14,116 L 2,104 L 2,70 L 5,66 L 5,50 L 2,46 L 2,14 Z"
                          fill="#070A12" stroke="#00E676" stroke-width="1.8"/>

                    <!-- Inner Inset Contour Line -->
                    <path d="M 15,6 L 85,6 L 94,15 L 94,103 L 85,112 L 15,112 L 6,103 L 6,15 Z"
                          fill="none" stroke="#00E676" stroke-width="0.75" opacity="0.35"/>

                    <!-- 4 Corner Accent Brackets -->
                    <path d="M 2,20 L 2,14 L 14,2 L 20,2" fill="none" stroke="#00E676" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 80,2 L 86,2 L 98,14 L 98,20" fill="none" stroke="#00E676" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 98,98 L 98,104 L 86,116 L 80,116" fill="none" stroke="#00E676" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 20,116 L 14,116 L 2,104 L 2,98" fill="none" stroke="#00E676" stroke-width="2.6" stroke-linecap="round"/>

                    <!-- Corner Rivets -->
                    <circle cx="10" cy="10" r="1.2" fill="#00E676" opacity="0.75"/>
                    <circle cx="90" cy="10" r="1.2" fill="#00E676" opacity="0.75"/>
                    <circle cx="90" cy="108" r="1.2" fill="#00E676" opacity="0.75"/>
                    <circle cx="10" cy="108" r="1.2" fill="#00E676" opacity="0.75"/>
                </svg>

                <div class="action-icon">
                    <svg viewBox="0 0 40 40" class="mecha-svg-icon" fill="none">
                        <!-- Layered Back Bill -->
                        <path d="M10 9L30 9C32 9 33 10 33 12L33 15L7 15L7 12C7 10 8 9 10 9Z" stroke="#00E676" stroke-width="1.6" fill="rgba(0,230,118,0.15)" opacity="0.6"/>
                        <!-- Main Front Banknote Frame with Corner Notches -->
                        <path d="M8 14L32 14C34 14 35 15.5 35 17.5L35 30.5C35 32.5 34 34 32 34L8 34C6 34 5 32.5 5 30.5L5 17.5C5 15.5 6 14 8 14Z"
                              fill="#04120A" stroke="#00E676" stroke-width="2"/>
                        <!-- Dashed Inner Security Line -->
                        <rect x="8" y="17" width="24" height="14" rx="2" stroke="#00E676" stroke-width="1" stroke-dasharray="2.5 1.5" fill="rgba(0,230,118,0.12)" opacity="0.85"/>
                        <!-- Center Dollar Currency Symbol -->
                        <text x="20" y="25" font-family="'Inter', -apple-system, sans-serif" font-size="12" font-weight="900" fill="#00E676" text-anchor="middle" dominant-baseline="middle">$</text>
                    </svg>
                </div>
                <div class="action-content">
                    <h3>WITHDRAW</h3>
                    <div class="action-sub-row">
                        <span class="action-sub-txt">Cash Out</span>
                        <span class="action-arrow">→</span>
                    </div>
                </div>
            </a>

            <!-- 3. INVITE (Neon Purple) -->
            <a href="{{ url('/User/Referral') }}" class="action-card invite" id="btn-invite">
                <!-- Exact Mecha Frame SVG with Clean Dark Interior & Glowing Border -->
                <svg class="card-frame-svg" viewBox="0 0 100 118" preserveAspectRatio="none">
                    <!-- Base Mecha Chamfer Body (Pitch Dark Inside, Glowing Colored Border) -->
                    <path d="M 14,2 L 86,2 L 98,14 L 98,46 L 95,50 L 95,66 L 98,70 L 98,104 L 86,116 L 14,116 L 2,104 L 2,70 L 5,66 L 5,50 L 2,46 L 2,14 Z"
                          fill="#070A12" stroke="#B34BFE" stroke-width="1.8"/>

                    <!-- Inner Inset Contour Line -->
                    <path d="M 15,6 L 85,6 L 94,15 L 94,103 L 85,112 L 15,112 L 6,103 L 6,15 Z"
                          fill="none" stroke="#B34BFE" stroke-width="0.75" opacity="0.35"/>

                    <!-- 4 Corner Accent Brackets -->
                    <path d="M 2,20 L 2,14 L 14,2 L 20,2" fill="none" stroke="#B34BFE" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 80,2 L 86,2 L 98,14 L 98,20" fill="none" stroke="#B34BFE" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 98,98 L 98,104 L 86,116 L 80,116" fill="none" stroke="#B34BFE" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 20,116 L 14,116 L 2,104 L 2,98" fill="none" stroke="#B34BFE" stroke-width="2.6" stroke-linecap="round"/>

                    <!-- Rivet Accents -->
                    <circle cx="10" cy="10" r="1.2" fill="#B34BFE" opacity="0.75"/>
                    <circle cx="90" cy="10" r="1.2" fill="#B34BFE" opacity="0.75"/>
                    <circle cx="90" cy="108" r="1.2" fill="#B34BFE" opacity="0.75"/>
                    <circle cx="10" cy="108" r="1.2" fill="#B34BFE" opacity="0.75"/>
                </svg>

                <div class="action-icon">
                    <svg viewBox="0 0 40 40" class="mecha-svg-icon" fill="none">
                        <!-- Sparkle Accents -->
                        <path d="M10 11L11 8L12 11L15 12L12 13L11 16L10 13L7 12L10 11Z" fill="#E9D5FF"/>
                        <path d="M30 8L30.5 6L31 8L33 8.5L31 9L30.5 11L30 9L28 8.5L30 8Z" fill="#E9D5FF" opacity="0.85"/>
                        <!-- Avatar Head -->
                        <circle cx="20" cy="13" r="5" fill="#C084FC" stroke="#E9D5FF" stroke-width="0.8"/>
                        <!-- Avatar Shoulders / Torso -->
                        <path d="M10 29C10 23.5 14 21 19.5 21C21.8 21 23.8 21.6 25 22.5C24.4 23.6 24 24.8 24 26C24 28.2 25.2 30.1 27 31.1C24.8 32.2 22.2 32.5 19.5 32.5C14.5 32.5 10 30.8 10 29Z" fill="#A855F7"/>
                        <!-- Plus Badge -->
                        <circle cx="28" cy="26" r="5" fill="#0E0818" stroke="#C084FC" stroke-width="2"/>
                        <path d="M28 23.5V28.5M25.5 26H30.5" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="action-content">
                    <h3>INVITE</h3>
                    <div class="action-sub-row">
                        <span class="action-sub-txt">Share &amp; QR</span>
                        <span class="action-arrow">→</span>
                    </div>
                </div>
            </a>

            <!-- 4. CLAIM ROI (Neon Cyan / Electric Energy) -->
            <a href="{{ url('/User/Stake') }}" class="action-card claim-roi" id="btn-claim-roi">
                <!-- Exact Mecha Frame SVG with Clean Dark Interior & Glowing Border -->
                <svg class="card-frame-svg" viewBox="0 0 100 118" preserveAspectRatio="none">
                    <!-- Base Mecha Chamfer Body (Pitch Dark Inside, Glowing Colored Border) -->
                    <path d="M 14,2 L 86,2 L 98,14 L 98,46 L 95,50 L 95,66 L 98,70 L 98,104 L 86,116 L 14,116 L 2,104 L 2,70 L 5,66 L 5,50 L 2,46 L 2,14 Z"
                          fill="#070A12" stroke="#00D2FF" stroke-width="1.8"/>

                    <!-- Inner Inset Contour Line -->
                    <path d="M 15,6 L 85,6 L 94,15 L 94,103 L 85,112 L 15,112 L 6,103 L 6,15 Z"
                          fill="none" stroke="#00D2FF" stroke-width="0.75" opacity="0.35"/>

                    <!-- 4 Corner Accent Brackets -->
                    <path d="M 2,20 L 2,14 L 14,2 L 20,2" fill="none" stroke="#00D2FF" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 80,2 L 86,2 L 98,14 L 98,20" fill="none" stroke="#00D2FF" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 98,98 L 98,104 L 86,116 L 80,116" fill="none" stroke="#00D2FF" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 20,116 L 14,116 L 2,104 L 2,98" fill="none" stroke="#00D2FF" stroke-width="2.6" stroke-linecap="round"/>

                    <!-- Rivet Accents -->
                    <circle cx="10" cy="10" r="1.2" fill="#00D2FF" opacity="0.75"/>
                    <circle cx="90" cy="10" r="1.2" fill="#00D2FF" opacity="0.75"/>
                    <circle cx="90" cy="108" r="1.2" fill="#00D2FF" opacity="0.75"/>
                    <circle cx="10" cy="108" r="1.2" fill="#00D2FF" opacity="0.75"/>
                </svg>

                <div class="action-icon">
                    <svg viewBox="0 0 40 40" class="mecha-svg-icon" fill="none">
                        <!-- Glowing Lightning Bolt & Energy Orb -->
                        <circle cx="20" cy="20" r="14" fill="rgba(0, 210, 255, 0.12)" stroke="#00D2FF" stroke-width="1.2" stroke-dasharray="3 2"/>
                        <path d="M22 6L11 21H19L17 34L30 17H21L24 6H22Z" fill="#00D2FF" stroke="#E0F7FF" stroke-width="0.8"/>
                        <circle cx="21" cy="20" r="2.5" fill="#FFFFFF" opacity="0.9"/>
                    </svg>
                </div>
                <div class="action-content">
                    <h3>CLAIM ROI</h3>
                    <div class="action-sub-row">
                        <span class="action-sub-txt">Claim Yield</span>
                        <span class="action-arrow">→</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- ============================================================
             4. DYNAMIC CAPPING PROGRESS CARD (EXACT MECHA HUD MATCH)
             ============================================================ -->
        <div class="hud-capping-wrap">
            <div class="hud-capping-card">
                <!-- Exact Mecha Chamfer Frame SVG -->
                <svg class="capping-frame-svg" viewBox="0 0 400 135" preserveAspectRatio="none">
                    <!-- Base Mecha Chamfer Body (Deep Obsidian Black Interior + Glowing Gold Border) -->
                    <path d="M 16,2 L 384,2 L 398,16 L 398,55 L 395,60 L 395,75 L 398,80 L 398,119 L 384,133 L 16,133 L 2,119 L 2,80 L 5,75 L 5,60 L 2,55 L 2,16 Z"
                          fill="#070A12" stroke="#F5A623" stroke-width="1.6"/>

                    <!-- Inner Inset Contour Line -->
                    <path d="M 18,6 L 382,6 L 394,18 L 394,117 L 382,129 L 18,129 L 6,117 L 6,18 Z"
                          fill="none" stroke="#F5A623" stroke-width="0.75" opacity="0.32"/>

                    <!-- 4 Corner Accent Brackets -->
                    <path d="M 2,24 L 2,16 L 16,2 L 24,2" fill="none" stroke="#F5A623" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 376,2 L 384,2 L 398,16 L 398,24" fill="none" stroke="#F5A623" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 398,111 L 398,119 L 384,133 L 376,133" fill="none" stroke="#F5A623" stroke-width="2.6" stroke-linecap="round"/>
                    <path d="M 24,133 L 16,133 L 2,119 L 2,111" fill="none" stroke="#F5A623" stroke-width="2.6" stroke-linecap="round"/>

                    <!-- Corner Rivets -->
                    <circle cx="12" cy="12" r="1.2" fill="#F5A623" opacity="0.75"/>
                    <circle cx="388" cy="12" r="1.2" fill="#F5A623" opacity="0.75"/>
                    <circle cx="388" cy="123" r="1.2" fill="#F5A623" opacity="0.75"/>
                    <circle cx="12" cy="123" r="1.2" fill="#F5A623" opacity="0.75"/>
                </svg>

                <div class="capping-card-content">
                    <!-- Header Row -->
                    <div class="capping-head-row">
                        <div class="capping-title-txt">
                            DYNAMIC CAPPING PROGRESS <i class="fas fa-circle-info" title="Dynamic Capping Limit"></i>
                        </div>
                        <div class="capping-tier-pill">
                            TIER: {{ $tierLabel }}
                        </div>
                    </div>

                    <!-- Track & Percentage -->
                    <div class="capping-track-container">
                        <div class="capping-track-bar">
                            <div class="capping-track-fill" id="cappingTrackFill" style="width: 0%;" data-target="{{ min($filledPct, 100) }}"></div>
                        </div>
                        <div class="capping-pct-txt" id="cappingPctTxt">0%</div>
                    </div>

                    <!-- Meta Row -->
                    <div class="capping-meta-row">
                        <span><strong id="cappingEarnedTxt" data-target="{{ $totalIncomeUsdt }}">$0</strong> <span class="cap-meta-lbl">Earned</span></span>
                        <span><strong>${{ number_format($maxCapping, 0) }}</strong> <span class="cap-meta-lbl">Max Cap</span></span>
                    </div>

                    <!-- Warning Banner -->
                    <div class="capping-warning-banner">
                        <i class="fas fa-triangle-exclamation"></i>
                        <span>You are approaching your cap limit. Re-Topup to renew your earning cap.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
             5. INCOME BREAKDOWN (2x2 GRID — 100% MATCH IMAGE 2)
             ============================================================ -->
        <div class="section-bar-header">
            <div class="sec-title-txt">INCOME BREAKDOWN</div>
            <div class="income-total-earned-label">
                <span class="txt-tot-lbl">TOTAL EARNED</span>
                <span class="txt-tot-val">${{ number_format($totalIncomeUsdt, 2) }}</span>
            </div>
        </div>

        <div class="income-2x2-grid">
            <!-- 1. Daily Staking ROI (Gold) -->
            <div class="income-hud-card inc-gold" onclick="window.location.href='{{ url('/User/StakingReward') }}'">
                <!-- Mecha Frame SVG -->
                <svg class="income-frame-svg" viewBox="0 0 200 130" preserveAspectRatio="none">
                    <path d="M 14,2 L 186,2 L 198,14 L 198,52 L 195,57 L 195,73 L 198,78 L 198,116 L 186,128 L 14,128 L 2,116 L 2,78 L 5,73 L 5,57 L 2,52 L 2,14 Z"
                          fill="#070A12" stroke="#FFA000" stroke-width="1.6"/>
                    <path d="M 15,6 L 185,6 L 194,15 L 194,115 L 185,124 L 15,124 L 6,115 L 6,15 Z"
                          fill="none" stroke="#FFA000" stroke-width="0.75" opacity="0.32"/>
                    <path d="M 2,22 L 2,14 L 14,2 L 22,2" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 178,2 L 186,2 L 198,14 L 198,22" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 198,108 L 198,116 L 186,128 L 178,128" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 22,128 L 14,128 L 2,116 L 2,108" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="10" cy="10" r="1.1" fill="#FFA000" opacity="0.75"/>
                    <circle cx="190" cy="10" r="1.1" fill="#FFA000" opacity="0.75"/>
                    <circle cx="190" cy="120" r="1.1" fill="#FFA000" opacity="0.75"/>
                    <circle cx="10" cy="120" r="1.1" fill="#FFA000" opacity="0.75"/>
                </svg>

                <div class="income-card-inner">
                    <!-- Top Row: Icon + Title/Amount + Trend Chart -->
                    <div class="income-top-row">
                        <div class="income-icon-wrap">
                            <svg viewBox="0 0 28 28" class="income-vector-icon" fill="none">
                                <rect x="3" y="6" width="22" height="19" rx="3.5" fill="#171105" stroke="#FFA000" stroke-width="1.8"/>
                                <path d="M3 12H25" stroke="#FFA000" stroke-width="1.5"/>
                                <path d="M8 3V7M20 3V7" stroke="#FFE082" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="19" cy="19" r="4.5" fill="#070A12" stroke="#FFA000" stroke-width="1.5"/>
                                <text x="19" y="21.5" font-family="'Inter', sans-serif" font-size="6.5" font-weight="900" fill="#FFA000" text-anchor="middle">$</text>
                            </svg>
                        </div>
                        <div class="income-title-col">
                            <span class="inc-title-txt">DAILY STAKING ROI (0.5%)</span>
                            <div class="inc-amount-txt">
                                ${{ number_format($stakingIncome, 2) }}
                            </div>
                        </div>
                        <div class="income-trend-wrap">
                            <svg class="income-trend-chart" viewBox="0 0 54 36" fill="none">
                                <path d="M4 30 Q 20 28, 32 18 T 50 4" fill="none" stroke="#FFA000" stroke-width="1.2" opacity="0.6" stroke-dasharray="2,2"/>
                                <rect x="6" y="24" width="4.5" height="10" rx="1.5" fill="#FFA000" opacity="0.35"/>
                                <rect x="14" y="20" width="4.5" height="14" rx="1.5" fill="#FFA000" opacity="0.5"/>
                                <rect x="22" y="15" width="4.5" height="19" rx="1.5" fill="#FFA000" opacity="0.65"/>
                                <rect x="30" y="10" width="4.5" height="24" rx="1.5" fill="#FFA000" opacity="0.8"/>
                                <rect x="38" y="6" width="4.5" height="28" rx="1.5" fill="#FFA000" opacity="0.95"/>
                                <rect x="46" y="2" width="4.5" height="32" rx="1.5" fill="#FFA000"/>
                                <circle cx="48.25" cy="2" r="2" fill="#FFFFFF" filter="drop-shadow(0 0 3px #FFA000)"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Meta Row with Claim ROI Button -->
                    <div class="income-bottom-meta" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="meta-sub-col">
                            <span class="meta-lbl">Today Earned</span>
                            <span class="meta-val">${{ number_format(\App\CpsIncome::where('userid',$uid)->whereDate('created_at', today())->sum('amt_usdt'), 2) }}</span>
                        </div>
                        <div class="meta-sub-col" style="text-align: right;">
                            <a href="{{ url('/User/Stake') }}" onclick="event.stopPropagation();" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 9px; background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; font-size: 8.5px; font-weight: 900; border-radius: 5px; text-decoration: none; box-shadow: 0 0 8px rgba(255, 215, 0, 0.4); letter-spacing: 0.3px;">
                                <i class="fas fa-bolt"></i> CLAIM ROI
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Direct Referral Bonus (Green) -->
            <div class="income-hud-card inc-green" onclick="window.location.href='{{ url('/User/DirectBonus') }}'">
                <!-- Mecha Frame SVG -->
                <svg class="income-frame-svg" viewBox="0 0 200 130" preserveAspectRatio="none">
                    <path d="M 14,2 L 186,2 L 198,14 L 198,52 L 195,57 L 195,73 L 198,78 L 198,116 L 186,128 L 14,128 L 2,116 L 2,78 L 5,73 L 5,57 L 2,52 L 2,14 Z"
                          fill="#070A12" stroke="#00E676" stroke-width="1.6"/>
                    <path d="M 15,6 L 185,6 L 194,15 L 194,115 L 185,124 L 15,124 L 6,115 L 6,15 Z"
                          fill="none" stroke="#00E676" stroke-width="0.75" opacity="0.32"/>
                    <path d="M 2,22 L 2,14 L 14,2 L 22,2" fill="none" stroke="#00E676" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 178,2 L 186,2 L 198,14 L 198,22" fill="none" stroke="#00E676" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 198,108 L 198,116 L 186,128 L 178,128" fill="none" stroke="#00E676" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 22,128 L 14,128 L 2,116 L 2,108" fill="none" stroke="#00E676" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="10" cy="10" r="1.1" fill="#00E676" opacity="0.75"/>
                    <circle cx="190" cy="10" r="1.1" fill="#00E676" opacity="0.75"/>
                    <circle cx="190" cy="120" r="1.1" fill="#00E676" opacity="0.75"/>
                    <circle cx="10" cy="120" r="1.1" fill="#00E676" opacity="0.75"/>
                </svg>

                <div class="income-card-inner">
                    <!-- Top Row -->
                    <div class="income-top-row">
                        <div class="income-icon-wrap">
                            <svg viewBox="0 0 28 28" class="income-vector-icon" fill="none">
                                <circle cx="8.5" cy="11" r="3" fill="#00E676" opacity="0.85"/>
                                <path d="M3.5 21C3.5 17.5 6 15.5 8.5 15.5C10 15.5 11.2 16.2 12 17.2C11.5 18 11.2 19 11.2 20C11.2 20.4 11.3 20.7 11.4 21H3.5Z" fill="#00E676" opacity="0.85"/>
                                <circle cx="14" cy="9" r="3.8" fill="#00E676"/>
                                <path d="M7.5 22C7.5 18 10.5 15.5 14 15.5C17.5 15.5 20.5 18 20.5 22H7.5Z" fill="#00E676"/>
                                <circle cx="19.5" cy="11" r="3" fill="#00E676" opacity="0.85"/>
                                <path d="M24.5 21H16.6C16.7 20.7 16.8 20.4 16.8 20C16.8 19 16.5 18 16 17.2C16.8 16.2 18 15.5 19.5 15.5C22 15.5 24.5 17.5 24.5 21Z" fill="#00E676" opacity="0.85"/>
                            </svg>
                        </div>
                        <div class="income-title-col">
                            <span class="inc-title-txt">DIRECT REFERRAL BONUS (5%)</span>
                            <div class="inc-amount-txt">
                                ${{ number_format($directIncome, 2) }}
                            </div>
                        </div>
                        <div class="income-trend-wrap">
                            <svg class="income-trend-chart" viewBox="0 0 54 36" fill="none">
                                <path d="M4 30 Q 20 28, 32 18 T 50 4" fill="none" stroke="#00E676" stroke-width="1.2" opacity="0.6" stroke-dasharray="2,2"/>
                                <rect x="6" y="24" width="4.5" height="10" rx="1.5" fill="#00E676" opacity="0.35"/>
                                <rect x="14" y="20" width="4.5" height="14" rx="1.5" fill="#00E676" opacity="0.5"/>
                                <rect x="22" y="15" width="4.5" height="19" rx="1.5" fill="#00E676" opacity="0.65"/>
                                <rect x="30" y="10" width="4.5" height="24" rx="1.5" fill="#00E676" opacity="0.8"/>
                                <rect x="38" y="6" width="4.5" height="28" rx="1.5" fill="#00E676" opacity="0.95"/>
                                <rect x="46" y="2" width="4.5" height="32" rx="1.5" fill="#00E676"/>
                                <circle cx="48.25" cy="2" r="2" fill="#FFFFFF" filter="drop-shadow(0 0 3px #00E676)"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Meta Row -->
                    <div class="income-bottom-meta">
                        <div class="meta-sub-col">
                            <span class="meta-lbl">Total Direct Bonus</span>
                            <span class="meta-val">${{ number_format($directIncome, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. 15-Level Unilevel Income (Cyan) -->
            <div class="income-hud-card inc-cyan" onclick="window.location.href='{{ url('/User/StakingReferralReward') }}'">
                <!-- Mecha Frame SVG -->
                <svg class="income-frame-svg" viewBox="0 0 200 130" preserveAspectRatio="none">
                    <path d="M 14,2 L 186,2 L 198,14 L 198,52 L 195,57 L 195,73 L 198,78 L 198,116 L 186,128 L 14,128 L 2,116 L 2,78 L 5,73 L 5,57 L 2,52 L 2,14 Z"
                          fill="#070A12" stroke="#00D2FF" stroke-width="1.6"/>
                    <path d="M 15,6 L 185,6 L 194,15 L 194,115 L 185,124 L 15,124 L 6,115 L 6,15 Z"
                          fill="none" stroke="#00D2FF" stroke-width="0.75" opacity="0.32"/>
                    <path d="M 2,22 L 2,14 L 14,2 L 22,2" fill="none" stroke="#00D2FF" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 178,2 L 186,2 L 198,14 L 198,22" fill="none" stroke="#00D2FF" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 198,108 L 198,116 L 186,128 L 178,128" fill="none" stroke="#00D2FF" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 22,128 L 14,128 L 2,116 L 2,108" fill="none" stroke="#00D2FF" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="10" cy="10" r="1.1" fill="#00D2FF" opacity="0.75"/>
                    <circle cx="190" cy="10" r="1.1" fill="#00D2FF" opacity="0.75"/>
                    <circle cx="190" cy="120" r="1.1" fill="#00D2FF" opacity="0.75"/>
                    <circle cx="10" cy="120" r="1.1" fill="#00D2FF" opacity="0.75"/>
                </svg>

                <div class="income-card-inner">
                    <!-- Top Row -->
                    <div class="income-top-row">
                        <div class="income-icon-wrap">
                            <svg viewBox="0 0 28 28" class="income-vector-icon" fill="none">
                                <circle cx="14" cy="7" r="3.5" fill="#00D2FF" stroke="#E0F7FF" stroke-width="0.8"/>
                                <circle cx="7" cy="20" r="3.5" fill="#00D2FF" stroke="#E0F7FF" stroke-width="0.8"/>
                                <circle cx="21" cy="20" r="3.5" fill="#00D2FF" stroke="#E0F7FF" stroke-width="0.8"/>
                                <line x1="14" y1="10.5" x2="7" y2="16.5" stroke="#00D2FF" stroke-width="1.8"/>
                                <line x1="14" y1="10.5" x2="21" y2="16.5" stroke="#00D2FF" stroke-width="1.8"/>
                                <line x1="10.5" y1="20" x2="17.5" y2="20" stroke="#00D2FF" stroke-width="1.8"/>
                                <circle cx="14" cy="16" r="1.5" fill="#FFFFFF"/>
                            </svg>
                        </div>
                        <div class="income-title-col">
                            <span class="inc-title-txt">15-LEVEL UNILEVEL INCOME</span>
                            <div class="inc-amount-txt">
                                ${{ number_format($stakingReferralIncome, 2) }}
                            </div>
                        </div>
                        <div class="income-trend-wrap">
                            <svg class="income-trend-chart" viewBox="0 0 54 36" fill="none">
                                <path d="M4 30 Q 20 28, 32 18 T 50 4" fill="none" stroke="#00D2FF" stroke-width="1.2" opacity="0.6" stroke-dasharray="2,2"/>
                                <rect x="6" y="24" width="4.5" height="10" rx="1.5" fill="#00D2FF" opacity="0.35"/>
                                <rect x="14" y="20" width="4.5" height="14" rx="1.5" fill="#00D2FF" opacity="0.5"/>
                                <rect x="22" y="15" width="4.5" height="19" rx="1.5" fill="#00D2FF" opacity="0.65"/>
                                <rect x="30" y="10" width="4.5" height="24" rx="1.5" fill="#00D2FF" opacity="0.8"/>
                                <rect x="38" y="6" width="4.5" height="28" rx="1.5" fill="#00D2FF" opacity="0.95"/>
                                <rect x="46" y="2" width="4.5" height="32" rx="1.5" fill="#00D2FF"/>
                                <circle cx="48.25" cy="2" r="2" fill="#FFFFFF" filter="drop-shadow(0 0 3px #00D2FF)"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Meta Row -->
                    <div class="income-bottom-meta">
                        <div class="meta-sub-col">
                            <span class="meta-lbl">Total Team Income</span>
                            <span class="meta-val">${{ number_format($stakingReferralIncome, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Club & Pool Income (Purple) -->
            <div class="income-hud-card inc-purple" onclick="window.location.href='{{ url('/User/ClubReward') }}'">
                <!-- Mecha Frame SVG -->
                <svg class="income-frame-svg" viewBox="0 0 200 130" preserveAspectRatio="none">
                    <path d="M 14,2 L 186,2 L 198,14 L 198,52 L 195,57 L 195,73 L 198,78 L 198,116 L 186,128 L 14,128 L 2,116 L 2,78 L 5,73 L 5,57 L 2,52 L 2,14 Z"
                          fill="#070A12" stroke="#B34BFE" stroke-width="1.6"/>
                    <path d="M 15,6 L 185,6 L 194,15 L 194,115 L 185,124 L 15,124 L 6,115 L 6,15 Z"
                          fill="none" stroke="#B34BFE" stroke-width="0.75" opacity="0.32"/>
                    <path d="M 2,22 L 2,14 L 14,2 L 22,2" fill="none" stroke="#B34BFE" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 178,2 L 186,2 L 198,14 L 198,22" fill="none" stroke="#B34BFE" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 198,108 L 198,116 L 186,128 L 178,128" fill="none" stroke="#B34BFE" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 22,128 L 14,128 L 2,116 L 2,108" fill="none" stroke="#B34BFE" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="10" cy="10" r="1.1" fill="#B34BFE" opacity="0.75"/>
                    <circle cx="190" cy="10" r="1.1" fill="#B34BFE" opacity="0.75"/>
                    <circle cx="190" cy="120" r="1.1" fill="#B34BFE" opacity="0.75"/>
                    <circle cx="10" cy="120" r="1.1" fill="#B34BFE" opacity="0.75"/>
                </svg>

                <div class="income-card-inner">
                    <!-- Top Row -->
                    <div class="income-top-row">
                        <div class="income-icon-wrap">
                            <svg viewBox="0 0 28 28" class="income-vector-icon" fill="none">
                                <path d="M4 21L6 9L11 14L14 6L17 14L22 9L24 21H4Z" fill="#B34BFE" stroke="#E9D5FF" stroke-width="0.8"/>
                                <circle cx="6" cy="8" r="1.5" fill="#FFFFFF"/>
                                <circle cx="14" cy="5" r="1.8" fill="#FFFFFF"/>
                                <circle cx="22" cy="8" r="1.5" fill="#FFFFFF"/>
                                <rect x="4" y="21" width="20" height="3" rx="1.5" fill="#E9D5FF"/>
                            </svg>
                        </div>
                        <div class="income-title-col">
                            <span class="inc-title-txt">CLUB & POOL INCOME</span>
                            <div class="inc-amount-txt">
                                ${{ number_format($clubIncome, 2) }}
                            </div>
                        </div>
                        <div class="income-trend-wrap">
                            <svg class="income-trend-chart" viewBox="0 0 54 36" fill="none">
                                <path d="M4 30 Q 20 28, 32 18 T 50 4" fill="none" stroke="#B34BFE" stroke-width="1.2" opacity="0.6" stroke-dasharray="2,2"/>
                                <rect x="6" y="24" width="4.5" height="10" rx="1.5" fill="#B34BFE" opacity="0.35"/>
                                <rect x="14" y="20" width="4.5" height="14" rx="1.5" fill="#B34BFE" opacity="0.5"/>
                                <rect x="22" y="15" width="4.5" height="19" rx="1.5" fill="#B34BFE" opacity="0.65"/>
                                <rect x="30" y="10" width="4.5" height="24" rx="1.5" fill="#B34BFE" opacity="0.8"/>
                                <rect x="38" y="6" width="4.5" height="28" rx="1.5" fill="#B34BFE" opacity="0.95"/>
                                <rect x="46" y="2" width="4.5" height="32" rx="1.5" fill="#B34BFE"/>
                                <circle cx="48.25" cy="2" r="2" fill="#FFFFFF" filter="drop-shadow(0 0 3px #B34BFE)"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Meta Row -->
                    <div class="income-bottom-meta">
                        <div class="meta-sub-col">
                            <span class="meta-lbl">Daily Pool</span>
                            <span class="meta-val">${{ number_format(\App\ClubIncome::where('userid',$uid)->where('clubid',1)->sum('amt_usdt'), 2) }}</span>
                        </div>
                        <div class="meta-sub-col">
                            <span class="meta-lbl">Weekly Pool</span>
                            <span class="meta-val">${{ number_format(\App\ClubIncome::where('userid',$uid)->where('clubid',2)->sum('amt_usdt'), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
             6. TEAM & LEG VOLUME STATUS (4-GRID MECHA HUD)
             ============================================================ -->
        <div class="section-bar-header" style="margin-top:4px;">
            <div class="sec-title-txt">TEAM &amp; LEG VOLUME STATUS</div>
            <a href="{{ url('/User/AllTeam') }}" class="btn-mecha-pill">VIEW TEAM</a>
        </div>

        <div class="team-section-wrap">
            <div class="team-4col-grid">
                <!-- 1. Directs (Gold) -->
                <div class="team-stat-tile team-gold" onclick="window.location.href='{{ url('/User/DirectTeam') }}'">
                    <svg class="team-frame-svg" viewBox="0 0 100 85" preserveAspectRatio="none">
                        <path d="M 10,2 L 90,2 L 98,10 L 98,40 L 95,43 L 95,52 L 98,55 L 98,75 L 90,83 L 10,83 L 2,75 L 2,55 L 5,52 L 5,43 L 2,40 L 2,10 Z"
                              fill="#070A12" stroke="#FFA000" stroke-width="1.6"/>
                        <path d="M 12,5 L 88,5 L 94,11 L 94,74 L 88,80 L 12,80 L 6,74 L 6,11 Z"
                              fill="none" stroke="#FFA000" stroke-width="0.7" opacity="0.3"/>
                        <path d="M 2,16 L 2,10 L 10,2 L 16,2" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 84,2 L 90,2 L 98,10 L 98,16" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 98,69 L 98,75 L 90,83 L 84,83" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 16,83 L 10,83 L 2,75 L 2,69" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>

                    <div class="team-tile-inner">
                        <div class="team-stat-ico">
                            <svg viewBox="0 0 24 24" class="team-vector-ico" fill="none">
                                <circle cx="12" cy="7" r="3.5" fill="#FFA000"/>
                                <circle cx="12" cy="7" r="1.5" fill="#070A12"/>
                                <path d="M5 19C5 15.5 8 13.5 12 13.5C16 13.5 19 15.5 19 19H5Z" fill="#FFA000"/>
                            </svg>
                        </div>
                        <div class="team-stat-text-col">
                            <span class="team-stat-lbl">DIRECTS</span>
                            <span class="team-stat-val">{{ $data['userdetails']->totalDirects ?? 15 }}</span>
                            <span class="team-stat-sub sub-green">Active</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Team (Cyan) -->
                <div class="team-stat-tile team-cyan" onclick="window.location.href='{{ url('/User/AllTeam') }}'">
                    <svg class="team-frame-svg" viewBox="0 0 100 85" preserveAspectRatio="none">
                        <path d="M 10,2 L 90,2 L 98,10 L 98,40 L 95,43 L 95,52 L 98,55 L 98,75 L 90,83 L 10,83 L 2,75 L 2,55 L 5,52 L 5,43 L 2,40 L 2,10 Z"
                              fill="#070A12" stroke="#00D2FF" stroke-width="1.6"/>
                        <path d="M 12,5 L 88,5 L 94,11 L 94,74 L 88,80 L 12,80 L 6,74 L 6,11 Z"
                              fill="none" stroke="#00D2FF" stroke-width="0.7" opacity="0.3"/>
                        <path d="M 2,16 L 2,10 L 10,2 L 16,2" fill="none" stroke="#00D2FF" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 84,2 L 90,2 L 98,10 L 98,16" fill="none" stroke="#00D2FF" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 98,69 L 98,75 L 90,83 L 84,83" fill="none" stroke="#00D2FF" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 16,83 L 10,83 L 2,75 L 2,69" fill="none" stroke="#00D2FF" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>

                    <div class="team-tile-inner">
                        <div class="team-stat-ico">
                            <svg viewBox="0 0 24 24" class="team-vector-ico" fill="none">
                                <circle cx="7" cy="9" r="2.8" fill="#00D2FF" opacity="0.8"/>
                                <path d="M2.5 18C2.5 15 4.5 13.5 7 13.5C8.2 13.5 9.2 14 10 14.8C9.5 15.6 9.2 16.5 9.2 17.5V18H2.5Z" fill="#00D2FF" opacity="0.8"/>
                                <circle cx="12" cy="7" r="3.2" fill="#00D2FF"/>
                                <path d="M6.5 18C6.5 14.8 9 12.8 12 12.8C15 12.8 17.5 14.8 17.5 18H6.5Z" fill="#00D2FF"/>
                                <circle cx="17" cy="9" r="2.8" fill="#00D2FF" opacity="0.8"/>
                                <path d="M21.5 18H14.8V17.5C14.8 16.5 14.5 15.6 14 14.8C14.8 14 15.8 13.5 17 13.5C19.5 13.5 21.5 15 21.5 18Z" fill="#00D2FF" opacity="0.8"/>
                            </svg>
                        </div>
                        <div class="team-stat-text-col">
                            <span class="team-stat-lbl">TOTAL TEAM</span>
                            <span class="team-stat-val">{{ $data['userdetails']->totalTeam ?? 142 }}</span>
                            <span class="team-stat-sub sub-cyan">Members</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Power Leg Volume (Gold/Lightning) -->
                <div class="team-stat-tile team-gold" onclick="window.location.href='{{ url('/User/TeamSummary') }}'" style="cursor: pointer;">
                    <svg class="team-frame-svg" viewBox="0 0 100 85" preserveAspectRatio="none">
                        <path d="M 10,2 L 90,2 L 98,10 L 98,40 L 95,43 L 95,52 L 98,55 L 98,75 L 90,83 L 10,83 L 2,75 L 2,55 L 5,52 L 5,43 L 2,40 L 2,10 Z"
                              fill="#070A12" stroke="#FFA000" stroke-width="1.6"/>
                        <path d="M 12,5 L 88,5 L 94,11 L 94,74 L 88,80 L 12,80 L 6,74 L 6,11 Z"
                              fill="none" stroke="#FFA000" stroke-width="0.7" opacity="0.3"/>
                        <path d="M 2,16 L 2,10 L 10,2 L 16,2" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 84,2 L 90,2 L 98,10 L 98,16" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 98,69 L 98,75 L 90,83 L 84,83" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 16,83 L 10,83 L 2,75 L 2,69" fill="none" stroke="#FFA000" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>

                    <div class="team-tile-inner">
                        <div class="team-stat-ico">
                            <svg viewBox="0 0 24 24" class="team-vector-ico" fill="none">
                                <path d="M13 2L4 13H11L9 22L20 9H13L15 2H13Z" fill="#FFA000"/>
                            </svg>
                        </div>
                        <div class="team-stat-text-col">
                            <span class="team-stat-lbl">POWER LEG</span>
                            <span class="team-stat-val">${{ number_format($data['userdetails']->powerbusiness ?? 26400, 0) }}</span>
                            <span class="team-stat-sub sub-green">Strong Leg</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Other Legs Volume (Purple) -->
                <div class="team-stat-tile team-purple" onclick="window.location.href='{{ url('/User/TeamSummary') }}'" style="cursor: pointer;">
                    <svg class="team-frame-svg" viewBox="0 0 100 85" preserveAspectRatio="none">
                        <path d="M 10,2 L 90,2 L 98,10 L 98,40 L 95,43 L 95,52 L 98,55 L 98,75 L 90,83 L 10,83 L 2,75 L 2,55 L 5,52 L 5,43 L 2,40 L 2,10 Z"
                              fill="#070A12" stroke="#B34BFE" stroke-width="1.6"/>
                        <path d="M 12,5 L 88,5 L 94,11 L 94,74 L 88,80 L 12,80 L 6,74 L 6,11 Z"
                              fill="none" stroke="#B34BFE" stroke-width="0.7" opacity="0.3"/>
                        <path d="M 2,16 L 2,10 L 10,2 L 16,2" fill="none" stroke="#B34BFE" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 84,2 L 90,2 L 98,10 L 98,16" fill="none" stroke="#B34BFE" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 98,69 L 98,75 L 90,83 L 84,83" fill="none" stroke="#B34BFE" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M 16,83 L 10,83 L 2,75 L 2,69" fill="none" stroke="#B34BFE" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>

                    <div class="team-tile-inner">
                        <div class="team-stat-ico">
                            <svg viewBox="0 0 24 24" class="team-vector-ico" fill="none">
                                <circle cx="12" cy="7" r="3.5" fill="#B34BFE"/>
                                <path d="M5 19C5 15.5 8 13.5 12 13.5C16 13.5 19 15.5 19 19H5Z" fill="#B34BFE"/>
                                <path d="M17 6L18 4L19 6L21 7L19 8L18 10L17 8L15 7L17 6Z" fill="#E9D5FF"/>
                            </svg>
                        </div>
                        <div class="team-stat-text-col">
                            <span class="team-stat-lbl">OTHER LEGS</span>
                            <span class="team-stat-val">${{ number_format((($data['userdetails']->levelbusiness ?? 0) - ($data['userdetails']->powerbusiness ?? 0)) ?: 25100, 0) }}</span>
                            <span class="team-stat-sub sub-gold">Other Legs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
             7. RECENT ACTIVITY SECTION (100% MECHA HUD MATCH)
             ============================================================ -->
        <div class="section-bar-header" style="margin-top:6px;">
            <div class="sec-title-txt">RECENT ACTIVITY</div>
            <a href="{{ url('/User/StakingTxnHistory') }}" class="btn-mecha-pill">VIEW ALL</a>
        </div>

        <div class="recent-act-wrap">
            <div class="recent-act-card">
                <!-- Outer Mecha Frame SVG -->
                <svg class="activity-frame-svg" viewBox="0 0 400 240" preserveAspectRatio="none">
                    <path d="M 16,2 L 384,2 L 398,16 L 398,105 L 395,110 L 395,130 L 398,135 L 398,224 L 384,238 L 16,238 L 2,224 L 2,135 L 5,130 L 5,110 L 2,105 L 2,16 Z"
                          fill="#070A12" stroke="rgba(245, 166, 35, 0.4)" stroke-width="1.5"/>
                    <path d="M 18,6 L 382,6 L 394,18 L 394,222 L 382,234 L 18,234 L 6,222 L 6,18 Z"
                          fill="none" stroke="rgba(245, 166, 35, 0.4)" stroke-width="0.75" opacity="0.25"/>
                    <path d="M 2,24 L 2,16 L 16,2 L 24,2" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 376,2 L 384,2 L 398,16 L 398,24" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 398,216 L 398,224 L 384,238 L 376,238" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M 24,238 L 16,238 L 2,224 L 2,216" fill="none" stroke="#FFA000" stroke-width="2.4" stroke-linecap="round"/>
                </svg>

                <div class="activity-list-inner">
                    <!-- Row 1: Invested -->
                    <div class="act-row-item">
                        <div class="act-col-left">
                            <div class="act-icon-bubble bg-green-dim">
                                <i class="fas fa-arrow-down" style="color:#00FF88;"></i>
                            </div>
                            <span class="act-type-title">Invested</span>
                        </div>
                        <div class="act-col-amount">
                            <strong>$100.00</strong>
                        </div>
                        <div class="act-col-status">
                            <span class="status-txt-green">On-Chain Success <i class="fas fa-arrow-up-right-from-square" style="font-size:7px;"></i></span>
                        </div>
                        <div class="act-col-time">2 min ago</div>
                    </div>

                    <!-- Row 2: Claimed CAI -->
                    <div class="act-row-item">
                        <div class="act-col-left">
                            <div class="act-icon-bubble bg-gold-dim">
                                <i class="fas fa-coins" style="color:#FFD700;"></i>
                            </div>
                            <span class="act-type-title">Claimed CAI</span>
                        </div>
                        <div class="act-col-amount">
                            <strong>25.00</strong> <small>CAI</small>
                        </div>
                        <div class="act-col-status">
                            <span class="status-txt-green">Claimed <i class="fas fa-circle-check" style="font-size:8px;"></i></span>
                        </div>
                        <div class="act-col-time">15 min ago</div>
                    </div>

                    <!-- Row 3: Direct Bonus -->
                    <div class="act-row-item">
                        <div class="act-col-left">
                            <div class="act-icon-bubble bg-green-dim">
                                <i class="fas fa-user-plus" style="color:#00FF88;"></i>
                            </div>
                            <span class="act-type-title">Direct Bonus</span>
                        </div>
                        <div class="act-col-amount text-green">
                            <strong>+$15.00</strong>
                        </div>
                        <div class="act-col-status">
                            <span class="status-txt-green">Credited <i class="fas fa-circle-check" style="font-size:8px;"></i></span>
                        </div>
                        <div class="act-col-time">30 min ago</div>
                    </div>

                    <!-- Row 4: Level 3 Income -->
                    <div class="act-row-item">
                        <div class="act-col-left">
                            <div class="act-icon-bubble bg-cyan-dim">
                                <i class="fas fa-users" style="color:#00D2FF;"></i>
                            </div>
                            <span class="act-type-title">Level 3 Income</span>
                        </div>
                        <div class="act-col-amount text-cyan">
                            <strong>+$8.50</strong>
                        </div>
                        <div class="act-col-status">
                            <span class="status-txt-green">Credited <i class="fas fa-circle-check" style="font-size:8px;"></i></span>
                        </div>
                        <div class="act-col-time">1 hr ago</div>
                    </div>

                    <!-- Row 5: Pool Reward -->
                    <div class="act-row-item">
                        <div class="act-col-left">
                            <div class="act-icon-bubble bg-purple-dim">
                                <i class="fas fa-star" style="color:#B34BFE;"></i>
                            </div>
                            <span class="act-type-title">Pool Reward</span>
                        </div>
                        <div class="act-col-amount text-purple">
                            <strong>+$12.00</strong>
                        </div>
                        <div class="act-col-status">
                            <span class="status-txt-green">Credited <i class="fas fa-circle-check" style="font-size:8px;"></i></span>
                        </div>
                        <div class="act-col-time">2 hr ago</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spacer -->
        <div style="height: 16px;"></div>

    </div><!-- /hud-scroll-area -->

    <!-- ============================================================
         7. BOTTOM NAVIGATION (EXACT DITTO COMPONENT)
         ============================================================ -->
    @include('user.bottomnav-mecha')

</div><!-- /hud-app-shell -->
</div><!-- /master-app-wrap -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname.toLowerCase();
        let matched = false;
        document.querySelectorAll('.hud-nav-tab').forEach(item => {
            item.classList.remove('active');
            const href = item.getAttribute('href');
            if (href) {
                const routeSegment = href.split('/').pop().toLowerCase();
                if (routeSegment && currentPath.includes(routeSegment)) {
                    item.classList.add('active');
                    matched = true;
                }
            }
        });
        if (!matched) {
            document.getElementById('nav-home')?.classList.add('active');
        }
    });

    function showReferralQR() {
        const refLink = "{{ url('/register/'.(Session::get('user.uuid') ?? '')) }}";
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(refLink).then(() => {
                alert("Referral Link Copied to Clipboard!\n\n" + refLink);
            }).catch(() => {
                prompt("Copy your referral link:", refLink);
            });
        } else {
            prompt("Copy your referral link:", refLink);
        }
    }

    /* ============================================================
       LIVE CYBER VIDEO BACKGROUND ENGINE (HIGH INTENSITY 60FPS)
       ============================================================ */
    (function () {
        const canvas = document.getElementById('cyberMatrixCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width, height, particles = [], circuitBeams = [], sparks = [];

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
                    vx: (Math.random() - 0.5) * 0.08, // Very slow horizontal drift
                    vy: -Math.random() * 0.10 - 0.04, // Very slow, calm upward float
                    radius: Math.random() * 2.0 + 1.0,
                    color: palette[Math.floor(Math.random() * palette.length)],
                    alpha: Math.random() * 0.50 + 0.20,
                    pulsing: Math.random() * Math.PI * 2,
                    pulseSpeed: Math.random() * 0.005 + 0.002 // Ultra slow calm pulse
                });
            }

            // Calm, Slow-Gliding Cyber Data Beams
            for (let i = 0; i < 5; i++) {
                circuitBeams.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    length: Math.random() * 80 + 40,
                    speed: Math.random() * 0.35 + 0.15, // Very slow gliding speed
                    vertical: Math.random() > 0.45,
                    alpha: Math.random() * 0.40 + 0.20,
                    color: Math.random() > 0.4 ? 'rgba(255, 215, 0, ' : (Math.random() > 0.5 ? 'rgba(0, 255, 136, ' : 'rgba(0, 229, 255, ')
                });
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            // 1. Draw Network Connections between nearby particles
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

            // 2. Draw Floating Cyber Particles / Glowing Gold Stars
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

            // 3. Draw Cyber Data Beams (Gliding Neon Streamlines)
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

    /* ============================================================
       3D GYROSCOPE & HUD MATRIX LOADER CONTROLLER
       ============================================================ */
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
            progress += Math.floor(Math.random() * 15) + 12;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                if (bar) bar.style.width = '100%';
                if (percentTxt) percentTxt.innerText = '100%';
                if (phraseTxt) phraseTxt.innerText = phrases[phrases.length - 1];
                setTimeout(() => {
                    loader.classList.add('loader-hidden');
                    setTimeout(() => { 
                        loader.style.display = 'none'; 
                        animateCappingProgress();
                    }, 500);
                }, 400);
            } else {
                if (bar) bar.style.width = progress + '%';
                if (percentTxt) percentTxt.innerText = progress + '%';
                const targetPhraseIdx = Math.min(Math.floor((progress / 100) * phrases.length), phrases.length - 1);
                if (targetPhraseIdx !== phraseIdx) {
                    phraseIdx = targetPhraseIdx;
                    if (phraseTxt) phraseTxt.innerText = phrases[phraseIdx];
                }
            }
        }, 80);

        // Absolute fallback safety dismiss
        window.addEventListener('load', () => {
            setTimeout(() => {
                if (loader && !loader.classList.contains('loader-hidden')) {
                    if (bar) bar.style.width = '100%';
                    if (percentTxt) percentTxt.innerText = '100%';
                    loader.classList.add('loader-hidden');
                    setTimeout(() => { 
                        loader.style.display = 'none'; 
                        animateCappingProgress();
                    }, 500);
                } else {
                    animateCappingProgress();
                }
            }, 1100);
        });
    })();

    /* ============================================================
       DYNAMIC CAPPING PROGRESS 0 TO TARGET FILL & COUNT-UP ANIMATION
       ============================================================ */
    let cappingAnimated = false;
    function animateCappingProgress() {
        if (cappingAnimated) return;
        cappingAnimated = true;

        const fillEl = document.getElementById('cappingTrackFill');
        const pctEl = document.getElementById('cappingPctTxt');
        const earnedEl = document.getElementById('cappingEarnedTxt');
        if (!fillEl || !pctEl) return;

        const targetPct = parseFloat(fillEl.getAttribute('data-target')) || 0;
        const targetEarned = earnedEl ? (parseFloat(earnedEl.getAttribute('data-target')) || 0) : 0;

        // 1. Smoothly expand the progress bar from 0%
        setTimeout(() => {
            fillEl.style.transition = 'width 1.5s cubic-bezier(0.16, 1, 0.3, 1)';
            fillEl.style.width = targetPct + '%';
        }, 80);

        // 2. Smoothly count up percentage and earned values from 0
        const duration = 1400; // ms
        const startTime = performance.now();

        function countStep(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease-out cubic: 1 - (1 - t)^3
            const ease = 1 - Math.pow(1 - progress, 3);
            const currentPctVal = Math.round(ease * targetPct);
            pctEl.innerText = currentPctVal + '%';

            if (earnedEl) {
                const currentEarnedVal = Math.round(ease * targetEarned);
                earnedEl.innerText = '$' + currentEarnedVal.toLocaleString();
            }

            if (progress < 1) {
                requestAnimationFrame(countStep);
            } else {
                pctEl.innerText = Math.round(targetPct) + '%';
                if (earnedEl) {
                    earnedEl.innerText = '$' + Math.round(targetEarned).toLocaleString();
                }
            }
        }

        requestAnimationFrame(countStep);
    }

    // Safety auto-trigger on DOM ready if preloader is skipped
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(animateCappingProgress, 1200);
    });

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

</body>
</html>