<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Cyera AI - Dashboard</title>
    <meta name="description"
        content="CYERA AI - CAI Ecosystem Dashboard. Track your investments, incomes, and team performance.">
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
                    <img src="{{ asset('images/cai-token-coin.png') }}" alt="CYERA AI" class="loader-coin-img"
                        onerror="this.src='{{ asset('icon.png') }}'">
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
                    <span class="meta-item"><i class="fas fa-circle"
                            style="color: #00FF88; font-size: 7px; margin-right: 4px;"></i> NODE ONLINE</span>
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

                // Direct database calculations for live user data
                // 1. CPS Income (Daily Staking Yield)
                $cpsIncome = (float) \App\CpsIncome::where('userid', $uid)->sum('amt_usdt');
                $cpsRemaining = (float) \App\CpsIncome::where('userid', $uid)->where('status', 0)->sum('remaining_usdt');
                $stakingIncome = $cpsIncome;
                $stakingRemaining = $cpsRemaining;

                // 2. Direct Income (Referral Bonus)
                $directIncome = (float) \App\BonusReward::where('userid', $uid)->where('status', '!=', 3)->sum('amt_usdt');
                $directRemaining = (float) \App\BonusReward::where('userid', $uid)->where('status', '!=', 3)->sum('remaining_usdt');

                // 3. Level Income (Staking Referral + Team Development)
                $stakingReferralIncome = (float) \App\LevelIncome::where('userid', $uid)->where('description', 'l')->sum('amt_usdt');
                $teamDevelopmentIncome = (float) \App\LevelIncome::where('userid', $uid)->where('description', 'r')->sum('amt_usdt');
                $levelIncomeTotal = (float) \App\LevelIncome::where('userid', $uid)->sum('amt_usdt');
                if ($levelIncomeTotal == 0) {
                    $levelIncomeTotal = $stakingReferralIncome + $teamDevelopmentIncome;
                }
                $levelRemaining = (float) \App\LevelIncome::where('userid', $uid)->where('status', 0)->sum('remaining_usdt');

                // 4. Pool / Club Income (Global Turnover Pools & Club Dividends)
                $clubIncome = (float) \App\ClubIncome::where('userid', $uid)->sum('amt_usdt');
                $poolIncome = (float) \App\PoolIncome::where('userid', $uid)->sum('amt_usdt');
                $totalPoolIncome = $clubIncome + $poolIncome;
                $poolRemaining = (float) \App\ClubIncome::where('userid', $uid)->where('status', 0)->sum('remaining_usdt');

                // 5. Rank & Lifetime / Achievement Income
                $achievementIncome = (float) \App\AchievementIncome::where('userid', $uid)->sum('amount');
                $rankIncome = (float) \App\RankIncome::where('userid', $uid)->sum('amt_usdt');
                $totalRankIncome = $achievementIncome + $rankIncome;
                $rankRemaining = (float) \App\AchievementIncome::where('userid', $uid)->where('status', 0)->sum('remaining');

                // Combined Totals (All 5 Incomes)
                $totalIncomeUsdt = $cpsIncome + $directIncome + $levelIncomeTotal + $totalPoolIncome + $totalRankIncome;
                $readyToReleaseUsdt = $cpsRemaining + $directRemaining + $levelRemaining + $poolRemaining + $rankRemaining;

                // Total Invested by user
                $totalInvested = (float) \App\StackingDeposite::where('userid', $uid)->where('status', 1)->sum('usdt');

                // Dynamic Capping & Leg Stats
                $cappingStats = $data['cappingStats'] ?? $data['userDetail']->getCappingTier();
                $boosterStats = $data['boosterStats'] ?? $data['userDetail']->getBoosterStats();
                $legStats = $data['legStats'] ?? $data['userDetail']->getLegBusiness();
                $poolQualifications = $data['poolQualifications'] ?? $data['userDetail']->getPoolQualifications();

                $tierMultiplier = $cappingStats['multiplier'] ?: 2;

                $tierLabel = $tierMultiplier . 'X';
                $maxCapping = $totalInvested > 0 ? ($totalInvested * $tierMultiplier) : 0.00;
                $filledPct = ($maxCapping > 0) ? ($totalIncomeUsdt / $maxCapping) * 100 : 0;
                $filledPct = min(100, max(0, round($filledPct)));

                // CAI Live Price & Dynamic Runtime ROI Calculations
                $profileStore = \App\ProfileStore::where('id', 1)->first();
                $caiPrice = $profileStore ? (float) $profileStore->price : 1.00;
                if ($caiPrice <= 0)
                    $caiPrice = 1.00;

                // Claimable CPS ROI in CAI tokens and dynamic live USD value
                $claimableCai = (float) \App\CpsIncome::where('userid', $uid)->where('status', 0)->sum('remaining');
                $claimableUsdtRaw = (float) \App\CpsIncome::where('userid', $uid)->where('status', 0)->sum('remaining_usdt');
                if ($claimableCai <= 0 && $claimableUsdtRaw > 0) {
                    $claimableCai = $claimableUsdtRaw;
                }
                $claimableUsdDynamic = $claimableCai * $caiPrice;
                $claimableUsdt = $claimableUsdDynamic;
                $claimableUsdVal = $claimableUsdDynamic;

                // Total CPS in CAI tokens and dynamic USD
                $cpsCaiTotal = (float) \App\CpsIncome::where('userid', $uid)->sum('amount');
                $cpsUsdtRaw = (float) \App\CpsIncome::where('userid', $uid)->sum('amt_usdt');
                if ($cpsCaiTotal <= 0 && $cpsUsdtRaw > 0) {
                    $cpsCaiTotal = $cpsUsdtRaw;
                }
                $cpsUsdDynamic = $cpsCaiTotal * $caiPrice;

                $readyToReleaseCai = $claimableCai + ($caiPrice > 0 ? (($directRemaining + $levelRemaining + $poolRemaining + $rankRemaining) / $caiPrice) : 0.00);

                // Fund Wallet Balance (Account Deposit)
                $fundWalletDeposit = \App\AccountDeposit::where('userid', $uid)->first();
                $fundWalletBalance = 0.00;
                if ($fundWalletDeposit) {
                    try {
                        $fundWalletBalance = (float) \Illuminate\Support\Facades\Crypt::decrypt($fundWalletDeposit->amount);
                    } catch (\Exception $e) {
                        $fundWalletBalance = (float) $fundWalletDeposit->amount;
                    }
                }

                // Directs & Team Data
                $sponsorUserId = $data['userDetail']->userid;
                $totalDirects = \App\UserDetails::where('sponsorid', $sponsorUserId)->count();
                $activeDirects = \App\UserDetails::where('sponsorid', $sponsorUserId)->where('userstatus', 1)->count();
                $totalTeam = (int) ($data['userDetail']->total_downline ?? 0);
                $activeTeam = (int) ($data['userDetail']->active_downline ?? 0);

                // Power Leg and Weaker Legs Volume
                $powerLeg = (float) ($legStats['power'] ?? $legStats['power_leg'] ?? 0);
                $weakerLeg = (float) ($legStats['weaker'] ?? $legStats['weaker_leg'] ?? 0);
                $otherLegs = $weakerLeg;

                $rankQual = $data['userDetail']->getRankQualification();
                $userRankLevel = (int) ($data['userDetail']->rank_level ?? 0);
                $userRankName = ($data['userDetail']->rank_name && $data['userDetail']->rank_name !== 'None')
                    ? $data['userDetail']->rank_name
                    : ($rankQual['current_rank'] !== 'None' ? $rankQual['current_rank'] : 'NO RANK');
                $rankLabel = $userRankName;
                $totalRankEarned = (float) \App\RankIncome::where('userid', $uid)->sum('amt_usdt');

                // Recent Activity Dynamic Query
                $recentTxns = collect();

                // 1. Deposits
                $deposits = \App\StackingDeposite::where('userid', $uid)
                    ->orderBy('id', 'desc')->take(3)->get()->map(function ($item) {
                        return (object) [
                            'type' => 'Invested',
                            'icon' => 'fa-arrow-down',
                            'color_type' => 'green',
                            'amount' => '$' . number_format($item->usdt, 2),
                            'status' => $item->status == 1 ? 'On-Chain Success' : 'Pending',
                            'status_cls' => 'status-txt-green',
                            'time' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : 'Recently',
                            'timestamp' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->timestamp : 0
                        ];
                    });
                $recentTxns = $recentTxns->concat($deposits);

                // 2. Withdrawals
                $withdrawals = \App\TransactionDetail::where('userid', $uid)->where('txntype', 1)
                    ->orderBy('id', 'desc')->take(3)->get()->map(function ($item) {
                        return (object) [
                            'type' => 'Withdrawal',
                            'icon' => 'fa-arrow-up',
                            'color_type' => 'gold',
                            'amount' => '$' . number_format($item->amountusdt, 2),
                            'status' => $item->paymentstatus == 2 ? 'Completed' : ($item->paymentstatus == 1 ? 'Pending' : 'Rejected'),
                            'status_cls' => $item->paymentstatus == 2 ? 'status-txt-green' : 'status-txt-gold',
                            'time' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : 'Recently',
                            'timestamp' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->timestamp : 0
                        ];
                    });
                $recentTxns = $recentTxns->concat($withdrawals);

                // 3. Direct Bonuses
                $bonuses = \App\BonusReward::where('userid', $uid)->where('status', '!=', 3)
                    ->orderBy('id', 'desc')->take(3)->get()->map(function ($item) {
                        return (object) [
                            'type' => 'Direct Bonus',
                            'icon' => 'fa-user-plus',
                            'color_type' => 'green',
                            'amount' => '+$' . number_format($item->amt_usdt, 2),
                            'status' => 'Credited',
                            'status_cls' => 'status-txt-green',
                            'time' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : 'Recently',
                            'timestamp' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->timestamp : 0
                        ];
                    });
                $recentTxns = $recentTxns->concat($bonuses);

                // 4. Staking / Level Incomes
                $levelIncomes = \App\LevelIncome::where('userid', $uid)
                    ->orderBy('id', 'desc')->take(3)->get()->map(function ($item) {
                        return (object) [
                            'type' => 'Level Income',
                            'icon' => 'fa-users',
                            'color_type' => 'cyan',
                            'amount' => '+$' . number_format($item->amt_usdt, 2),
                            'status' => 'Credited',
                            'status_cls' => 'status-txt-green',
                            'time' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : 'Recently',
                            'timestamp' => $item->created_at ? \Carbon\Carbon::parse($item->created_at)->timestamp : 0
                        ];
                    });
                $recentTxns = $recentTxns->concat($levelIncomes);

                $recentActivities = $recentTxns->sortByDesc('timestamp')->take(5);
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
                                    <i class="fas fa-arrow-trend-up"></i> +4.20% <span
                                        style="color:#A0AEC0;font-size:7.5px;">(24H)</span>
                                </div>
                                <!-- Green Area Crypto Sparkline Chart -->
                                <div class="chart-grid-container">
                                    <svg viewBox="0 0 120 32" preserveAspectRatio="none"
                                        style="width:100%;height:100%;overflow:visible;">
                                        <defs>
                                            <linearGradient id="caiChartAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#00FF88" stop-opacity="0.35" />
                                                <stop offset="100%" stop-color="#00FF88" stop-opacity="0.0" />
                                            </linearGradient>
                                        </defs>
                                        <path
                                            d="M0,24 C15,25 25,20 38,21 C50,22 60,15 72,17 C84,19 96,8 108,11 C114,13 117,5 120,3 L120,32 L0,32 Z"
                                            fill="url(#caiChartAreaGrad)" />
                                        <path
                                            d="M0,24 C15,25 25,20 38,21 C50,22 60,15 72,17 C84,19 96,8 108,11 C114,13 117,5 120,3"
                                            fill="none" stroke="#00FF88" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <circle cx="120" cy="3" r="2.5" fill="#FFFFFF" stroke="#00FF88"
                                            stroke-width="1.5" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Center Column: 3D CYERA AI Emblem -->
                            <div class="col-center-medallion">
                                <div class="lion-halo-wrap">
                                    <div class="lion-disc-frame">
                                        <img src="{{ asset('images/cai-token-coin.png') }}" alt="CYERA AI Emblem">
                                    </div>
                                </div>

                            </div>

                            <!-- Right Column: Total Portfolio Value + Total Income + Ready To Release -->
                            <div class="col-right-portfolio">
                                <div class="row-port-title">
                                    <span class="txt-port-title">TOTAL PORTFOLIO</span>
                                    <i class="fas fa-eye icon-eye-gold" title="Hide/Show Balance"></i>
                                </div>
                                <div class="txt-port-big">${{ number_format($totalInvested, 2) }}</div>
                                <div class="txt-port-unit">USDT STAKED VALUE</div>

                                <!-- Sub-stats below Total Portfolio -->
                                <div class="hero-port-substats">
                                    <div class="port-substat-row">
                                        <span class="substat-lbl"><i class="fas fa-arrow-trend-up" style="color: #FFD700; font-size: 7px;"></i> TOTAL INCOME:</span>
                                        <span class="substat-val gold">${{ number_format($totalIncomeUsdt, 2) }}</span>
                                    </div>
                                    <div class="port-substat-row">
                                        <span class="substat-lbl"><i class="fas fa-bolt" style="color: #00FF88; font-size: 7px;"></i> READY TO CLAIM:</span>
                                        <span class="substat-val gold">{{ number_format($claimableCai, 2) }} CAI <small style="color: #00FF88; font-weight: 700; font-size: 8.5px;">(${{ number_format($claimableUsdDynamic, 2) }})</small></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dedicated Bottom Strip: 4 Equal Grid Columns (Never Overlaps) -->
                        @php
                            $isUserActive = ($data['userDetail']->userstatus == 1 || $data['userDetail']->userstate > 0 || $totalInvested > 0);
                        @endphp
                        <div class="hero-bottom-strip">
                            <!-- 1. Status & Rank -->
                            <div class="strip-col-item">
                                <span class="strip-col-lbl"><i class="fas fa-shield-halved" style="color: #FFD700;"></i>
                                    STATUS & RANK</span>
                                <div class="strip-val-wrap"
                                    style="display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                                    @if($isUserActive)
                                        <span class="strip-status-pill active"><i class="fas fa-circle-check"></i>
                                            ACTIVE</span>
                                    @else
                                        <span class="strip-status-pill inactive"><i class="fas fa-circle-xmark"></i>
                                            INACTIVE</span>
                                    @endif
                                    <span class="strip-status-pill"
                                        style="background: rgba(245, 158, 11, 0.2); border: 1px solid #F59E0B; color: #FDE68A; font-weight: 800;"
                                        title="Current Rank">
                                        <i class="fas fa-crown" style="color: #F59E0B;"></i> {{ $userRankName }}
                                    </span>
                                </div>
                            </div>

                            <!-- 2. Fund Wallet -->
                            <div class="strip-col-item">
                                <span class="strip-col-lbl"><i class="fas fa-wallet" style="color: #00FF88;"></i> FUND
                                    WALLET</span>
                                <div class="strip-val-wrap">
                                    <span
                                        class="strip-col-val fund-val">${{ number_format($fundWalletBalance, 2) }}</span>
                                </div>
                            </div>

                            <!-- 3. Ready to Claim ROI (Staking Yield) -->
                            <div class="strip-col-item">
                                <span class="strip-col-lbl"><i class="fas fa-bolt" style="color: #FFD700;"></i>
                                    READY TO CLAIM ROI</span>
                                <div class="strip-val-wrap">
                                    <span class="strip-col-val roi-val" style="color: #FFD700; font-size: 11px; font-weight: 900; font-family: 'Space Mono', monospace;">
                                        {{ number_format($claimableCai, 2) }} <span style="font-size: 9.5px; color: #FFD700;">CAI</span>
                                        <span style="color: #00FF88; font-size: 9.5px; font-weight: 800; margin-left: 2px;">(${{ number_format($claimableUsdDynamic, 2) }})</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 4. Claim Action Button -->
                            <div class="strip-col-item strip-col-action">
                                <a href="{{ url('/User/Stake') }}" class="btn-solid-gold-claim">
                                    <i class="fas fa-bolt"></i> CLAIM ROI
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================
             3. QUICK ACTIONS (4 GRID — 100% DITTO MECHA HUD TILES)
             ============================================================ -->
                <div class="quick-actions">
                    <!-- 1. INVEST (Gold / Amber) -->
                <a href="{{ url('/User/Stake') }}" class="action-card invest" id="btn-invest">
                    <div class="action-card-corner-glow"></div>
                    <div class="action-badge-icon">
                        <i class="fas fa-rocket"></i>
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
                    <div class="action-card-corner-glow"></div>
                    <div class="action-badge-icon">
                        <i class="fas fa-money-bill-wave"></i>
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
                    <div class="action-card-corner-glow"></div>
                    <div class="action-badge-icon">
                        <i class="fas fa-user-plus"></i>
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
                    <div class="action-card-corner-glow"></div>
                    <div class="action-badge-icon">
                        <i class="fas fa-bolt"></i>
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
                 3A. ULTRA-COMPACT 1-LINE REFERRAL LINK BAR
                 ============================================================ -->
            @php
                $dashUserUuid = Session::get('user.userid') ?? ($data['userDetail']->user()->uuid ?? 'CAI000001');
                $dashReferralUrl = url('/register/' . $dashUserUuid);
            @endphp
            <div class="hud-referral-compact-line" style="margin-bottom: 20px;">
                <div style="background: rgba(8, 9, 15, 0.9); backdrop-filter: blur(16px); border: 1px solid rgba(245, 166, 35, 0.35); border-radius: 12px; padding: 5px 6px 5px 12px; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.06);">
                    <i class="fas fa-link" style="color: #FFD700; font-size: 0.85rem; flex-shrink: 0;"></i>
                    <input type="text" id="dashRefInput" value="{{ $dashReferralUrl }}" readonly style="flex: 1; min-width: 0; background: transparent; border: none; outline: none; color: #FFD700; font-family: 'Space Mono', monospace; font-size: 0.80rem; font-weight: 600; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; padding: 0;">
                    <button type="button" onclick="copyDashRefLink()" class="btn-solid-gold-claim" style="height: 32px; padding: 0 12px; font-size: 0.75rem; border-radius: 8px; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; flex-shrink: 0; cursor: pointer; border: none;">
                        <i class="fas fa-copy"></i> <span id="dashCopyBtnText">COPY</span>
                    </button>
                </div>
            </div>

            <!-- ============================================================
                 3B. TOTAL INCOME & 5 REWARDS HUBS (CPS, DIRECT, LEVEL, POOL, RANK)
                 ============================================================ -->
            <div class="hud-incomes-matrix-wrap">
                <div class="hud-incomes-matrix-card">
                    <!-- Header -->
                    <div class="incomes-matrix-header">
                        <div class="matrix-header-left">
                            <div class="matrix-badge-icon">
                                <i class="fas fa-sack-dollar"></i>
                            </div>
                            <div>
                                <div class="matrix-main-title">TOTAL INCOME &amp; REWARD HUBS</div>
                                <div class="matrix-sub-title">5-Stream Automated Yield &amp; Distribution Protocols</div>
                            </div>
                        </div>
                        <div class="matrix-total-pill">
                            <span class="matrix-pulse-dot"></span>
                            <span class="matrix-total-lbl">TOTAL EARNED:</span>
                            <span class="matrix-total-val">${{ number_format($totalIncomeUsdt, 2) }} USDT</span>
                        </div>
                    </div>

                    <!-- Top Dual Summary Banner: Total Income & Ready To Release -->
                    <div class="incomes-dual-summary-banner">
                        <div class="summary-banner-box earned-box">
                            <div class="banner-box-left">
                                <span class="banner-lbl"><i class="fas fa-chart-line"></i> TOTAL LIFETIME EARNED</span>
                                <span class="banner-val">${{ number_format($totalIncomeUsdt, 2) }} <small>USDT</small></span>
                            </div>
                            <div class="banner-box-icon"><i class="fas fa-wallet"></i></div>
                        </div>
                        <div class="summary-banner-box release-box">
                            <div class="banner-box-left">
                                <span class="banner-lbl"><i class="fas fa-bolt-lightning"></i> READY TO CLAIM ROI</span>
                                <span class="banner-val val-gold">{{ number_format($claimableCai, 2) }} <small style="color:#FFD700;">CAI</small> <span style="color: #00FF88; font-size: 11px; font-weight: 800;">(${{ number_format($claimableUsdDynamic, 2) }})</span></span>
                            </div>
                            <a href="{{ url('/User/WithdrawRequest') }}" class="banner-claim-btn"><i class="fas fa-bolt"></i> CLAIM</a>
                        </div>
                    </div>

                    <!-- 5-Stream Cyber Grid -->
                    <div class="incomes-5stream-grid">
                        <!-- 1. CPS Income -->
                        <a href="{{ url('/User/StakingReward') }}" class="income-stream-card stream-cps">
                            <div class="stream-corner-glow"></div>
                            <div class="stream-top-row">
                                <div class="stream-icon-box icon-cyan">
                                    <i class="fas fa-bolt-lightning"></i>
                                </div>
                                <span class="stream-tag-pill">DAILY CPS</span>
                            </div>
                            <div class="stream-amount-wrap">
                                <span class="stream-lbl">CPS INCOME</span>
                                <span class="stream-amt val-cyan" style="font-size: 11.5px;">{{ number_format($cpsCaiTotal, 2) }} <small style="font-size: 8.5px;">CAI</small> <span style="color: #00FF88; font-size: 9px; font-weight: 800;">(${{ number_format($cpsUsdDynamic, 2) }})</span></span>
                            </div>
                            <div class="stream-footer-row">
                                <span class="stream-sub">Daily Yield &amp; Boosters</span>
                                <span class="stream-arrow">→</span>
                            </div>
                        </a>

                        <!-- 2. Direct Bonus -->
                        <a href="{{ url('/User/DirectBonus') }}" class="income-stream-card stream-direct">
                            <div class="stream-corner-glow"></div>
                            <div class="stream-top-row">
                                <div class="stream-icon-box icon-gold">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <span class="stream-tag-pill">5% DIRECT</span>
                            </div>
                            <div class="stream-amount-wrap">
                                <span class="stream-lbl">DIRECT BONUS</span>
                                <span class="stream-amt val-gold">${{ number_format($directIncome, 2) }}</span>
                            </div>
                            <div class="stream-footer-row">
                                <span class="stream-sub">Direct Referral Reward</span>
                                <span class="stream-arrow">→</span>
                            </div>
                        </a>

                        <!-- 3. Level Income -->
                        <a href="{{ url('/User/StakingReferralReward') }}" class="income-stream-card stream-level">
                            <div class="stream-corner-glow"></div>
                            <div class="stream-top-row">
                                <div class="stream-icon-box icon-purple">
                                    <i class="fas fa-network-wired"></i>
                                </div>
                                <span class="stream-tag-pill">TEAM LEVEL</span>
                            </div>
                            <div class="stream-amount-wrap">
                                <span class="stream-lbl">LEVEL INCOME</span>
                                <span class="stream-amt val-purple">${{ number_format($levelIncomeTotal, 2) }}</span>
                            </div>
                            <div class="stream-footer-row">
                                <span class="stream-sub">Multi-Tier Dev Bonus</span>
                                <span class="stream-arrow">→</span>
                            </div>
                        </a>

                        <!-- 4. Pool Income -->
                        <a href="{{ url('/User/PoolIncome') }}" class="income-stream-card stream-pool">
                            <div class="stream-corner-glow"></div>
                            <div class="stream-top-row">
                                <div class="stream-icon-box icon-cyan">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <span class="stream-tag-pill">GLOBAL POOL</span>
                            </div>
                            <div class="stream-amount-wrap">
                                <span class="stream-lbl">POOL INCOME</span>
                                <span class="stream-amt val-cyan">${{ number_format($totalPoolIncome, 2) }}</span>
                            </div>
                            <div class="stream-footer-row">
                                <span class="stream-sub">5% Turnover Dividends</span>
                                <span class="stream-arrow">→</span>
                            </div>
                        </a>

                        <!-- 5. Rank Income -->
                        <a href="{{ url('/User/RankIncome') }}" class="income-stream-card stream-rank">
                            <div class="stream-corner-glow"></div>
                            <div class="stream-top-row">
                                <div class="stream-icon-box icon-amber">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <span class="stream-tag-pill">RANK BONUS</span>
                            </div>
                            <div class="stream-amount-wrap">
                                <span class="stream-lbl">RANK REWARD</span>
                                <span class="stream-amt val-amber">${{ number_format($totalRankIncome, 2) }}</span>
                            </div>
                            <div class="stream-footer-row">
                                <span class="stream-sub">Milestone Achievements</span>
                                <span class="stream-arrow">→</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        <!-- ============================================================
             4. DUAL BOOSTER ACCELERATOR HUBS (1.0% & 1.5% DAILY)
             ============================================================ -->
        <div class="booster-hub-wrap">
            <div class="section-bar-header"
                style="margin-bottom: 6px; padding: 0; justify-content: center; text-align: center;">
                <div class="sec-title-txt"
                    style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 11.5px; font-weight: 800; letter-spacing: 0.6px;">
                    <i class="fas fa-bolt-lightning" style="color: #FFD700; font-size: 10.5px;"></i> STAKING BOOSTER
                    HUBS
                </div>
            </div>

            <div class="booster-cards-grid">
                <!-- BOOSTER 1 CARD: 1.0% DAILY -->
                @php
                    $b1 = $boosterStats['booster1'];
                    $b1Active = $b1['is_active'];
                    $b1Achieved = $b1['qualified_directs'] >= $b1['req_directs'];
                @endphp
                <div class="booster-card {{ $b1Active ? 'booster-active' : '' }}">
                    <div class="booster-card-corner-glow"></div>
                    <div class="booster-top-head">
                        <div class="booster-badge-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="booster-title-wrap">
                            <div class="booster-name">BOOSTER 1</div>
                            <div class="booster-req-sub">5 Directs ($100+) in 7D</div>
                        </div>
                    </div>

                    <!-- Rate Highlight (Column layout: Rate on left, Status on right) -->
                    <div class="booster-rate-highlight">
                        <div class="rate-info-col">
                            <span class="rate-num">1.0%</span>
                            <span class="rate-unit">DAILY CPS</span>
                        </div>
                        <div class="rate-status-col">
                            @if($b1Active)
                                <span class="booster-status-pill active"><i class="fas fa-check"></i> ACTIVE</span>
                            @else
                                <span class="booster-status-pill inactive"><i class="fas fa-xmark"></i> INACTIVE</span>
                            @endif
                        </div>
                    </div>

                    <div class="booster-progress-wrap">
                        <div class="booster-progress-labels">
                            <span>Directs</span>
                            <span style="color: #FFFFFF;">{{ $b1['qualified_directs'] }} /
                                {{ $b1['req_directs'] }}</span>
                        </div>
                        <div class="booster-progress-track">
                            <div class="booster-progress-bar" style="width: {{ $b1['progress_pct'] }}%;"></div>
                        </div>
                    </div>

                    <div class="booster-footer-row">
                        <div class="booster-timer-chip booster-countdown-live"
                            data-expiry="{{ $boosterStats['expiry_timestamp'] ?? 0 }}"
                            data-expired="{{ $boosterStats['is_expired'] ? '1' : '0' }}">
                            <i class="far fa-clock" style="color: #FFD700;"></i>
                            <span class="timer-display-txt">{{ $boosterStats['time_left_human'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- BOOSTER 2 CARD: 1.5% DAILY -->
                @php
                    $b2 = $boosterStats['booster2'];
                    $b2Active = $b2['is_active'];
                    $b2Achieved = $b2['qualified_directs'] >= $b2['req_directs'];
                @endphp
                <div class="booster-card {{ $b2Active ? 'booster-super' : '' }}">
                    <div class="booster-card-corner-glow"></div>
                    <div class="booster-top-head">
                        <div class="booster-badge-icon" style="color: #FFD700;">
                            <i class="fas fa-fire-flame-curved"></i>
                        </div>
                        <div class="booster-title-wrap">
                            <div class="booster-name">BOOSTER 2</div>
                            <div class="booster-req-sub">15 Directs ($100+) in 7D</div>
                        </div>
                    </div>

                    <!-- Rate Highlight (Column layout: Rate on left, Status on right) -->
                    <div class="booster-rate-highlight super">
                        <div class="rate-info-col">
                            <span class="rate-num">1.5%</span>
                            <span class="rate-unit">DAILY CPS</span>
                        </div>
                        <div class="rate-status-col">
                            @if($b2Active)
                                <span class="booster-status-pill active"><i class="fas fa-check"></i> ACTIVE</span>
                            @else
                                <span class="booster-status-pill inactive"><i class="fas fa-xmark"></i> INACTIVE</span>
                            @endif
                        </div>
                    </div>

                    <div class="booster-progress-wrap">
                        <div class="booster-progress-labels">
                            <span>Directs</span>
                            <span style="color: #FFFFFF;">{{ $b2['qualified_directs'] }} /
                                {{ $b2['req_directs'] }}</span>
                        </div>
                        <div class="booster-progress-track">
                            <div class="booster-progress-bar" style="width: {{ $b2['progress_pct'] }}%;"></div>
                        </div>
                    </div>

                    <div class="booster-footer-row">
                        <div class="booster-timer-chip booster-countdown-live"
                            data-expiry="{{ $boosterStats['expiry_timestamp'] ?? 0 }}"
                            data-expired="{{ $boosterStats['is_expired'] ? '1' : '0' }}">
                            <i class="far fa-clock" style="color: #FFD700;"></i>
                            <span class="timer-display-txt">{{ $boosterStats['time_left_human'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================
                     5. DYNAMIC CAPPING & LEG TURNOVER HUD
                     ============================================================ -->
        <div class="hud-capping-wrap">
            <div class="hud-capping-card">
                <!-- Corner Tech Accents -->
                <span class="cap-corner-bracket tl"></span>
                <span class="cap-corner-bracket tr"></span>
                <span class="cap-corner-bracket bl"></span>
                <span class="cap-corner-bracket br"></span>

                <div class="capping-card-content">
                    <!-- Header Row -->
                    <div class="capping-head-row">
                        <div class="capping-title-group">
                            <div class="capping-badge-icon">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <div>
                                <div class="capping-main-title">DYNAMIC CAPPING</div>
                                <div class="capping-sub-title">2X – 5X EARNING MULTIPLIER</div>
                            </div>
                        </div>
                        <div class="capping-tier-pill">
                            <span class="cap-pulse-dot"></span>
                            <span>ACTIVE: {{ $tierLabel }}</span>
                        </div>
                    </div>

                    <!-- Progress & Cap Limits Card -->
                    <div class="capping-progress-section">
                        <div class="capping-meta-row">
                            <div class="cap-meta-item earned">
                                <span class="cap-meta-lbl"><i class="fas fa-arrow-trend-up"></i> TOTAL EARNED</span>
                                <span class="cap-meta-val"><strong id="cappingEarnedTxt"
                                        data-target="{{ $totalIncomeUsdt }}">$0</strong> <small>USDT</small></span>
                            </div>
                            <div class="capping-pct-center">
                                <span class="capping-pct-txt" id="cappingPctTxt">0%</span>
                                <span class="cap-pct-sub">FILLED</span>
                            </div>
                            <div class="cap-meta-item maxcap">
                                <span class="cap-meta-lbl"><i class="fas fa-bullseye"></i> MAX CAP
                                    ({{ $tierLabel }})</span>
                                <span class="cap-meta-val"><strong>${{ number_format($maxCapping, 0) }}</strong>
                                    <small>USDT</small></span>
                            </div>
                        </div>

                        <!-- Futuristic Glowing Track Bar -->
                        <div class="capping-track-container">
                            <div class="capping-track-bar">
                                <div class="capping-track-fill" id="cappingTrackFill" style="width: 0%;"
                                    data-target="{{ min($filledPct, 100) }}">
                                    <span class="track-laser-glow"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leg Turnover Breakdown Grid -->
                    <div class="leg-stats-grid">
                        <div class="leg-stat-box power-leg">
                            <div class="leg-stat-top">
                                <span class="leg-stat-lbl"><i class="fas fa-crown"></i> POWER LEG</span>
                                <span class="leg-badge gold">STRONG</span>
                            </div>
                            <div class="leg-val-wrap">
                                <span class="leg-stat-val gold">${{ number_format($legStats['power_leg'], 2) }}</span>
                                <span class="leg-unit">USDT</span>
                            </div>
                        </div>
                        <div class="leg-stat-box weaker-leg">
                            <div class="leg-stat-top">
                                <span class="leg-stat-lbl"><i class="fas fa-bolt-lightning"></i> WEAKER LEG</span>
                                <span class="leg-badge green">TARGET</span>
                            </div>
                            <div class="leg-val-wrap">
                                <span class="leg-stat-val green">${{ number_format($legStats['weaker_leg'], 2) }}</span>
                                <span class="leg-unit">USDT</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Tier Upgrade Target (If applicable) -->
                    @if(!empty($cappingStats['next_tier']) && !empty($cappingStats['next_requirements']))
                        @php $nr = $cappingStats['next_requirements']; @endphp
                        <div class="capping-next-tier-note">
                            <div class="target-head">
                                <i class="fas fa-angles-up"></i>
                                <span>UPGRADE TO <strong>{{ $cappingStats['next_tier'] }}</strong></span>
                            </div>
                            <div class="target-chips-wrap">
                                <div class="target-chip">
                                    <i class="fas fa-wallet"></i>
                                    <span>Self <strong>${{ $nr['self_deposit'] }}</strong></span>
                                </div>
                                <div class="target-chip">
                                    <i class="fas fa-users"></i>
                                    <span><strong>{{ $nr['directs_count'] }}</strong> Directs
                                        (${{ $nr['direct_min_amount'] }}+)</span>
                                </div>
                                <div class="target-chip">
                                    <i class="fas fa-chart-line"></i>
                                    <span><strong>${{ number_format($nr['power_leg_turnover'], 0) }}</strong> Leg Vol</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ============================================================
                     5B. GLOBAL TURNOVER POOLS HUD (2x2 CARD GRID)
                     ============================================================ -->
        @php
            $dQual = $poolQualifications['daily'];
            $wQual = $poolQualifications['weekly'];
            $mQual = $poolQualifications['monthly'];

            $activePoolLabel = 'NO ACTIVE POOL';
            $activePoolClass = 'locked';
            if ($mQual['is_qualified']) {
                $activePoolLabel = 'ACTIVE: MONTHLY POOL (2.0%)';
                $activePoolClass = 'monthly';
            } elseif ($wQual['is_qualified']) {
                $activePoolLabel = 'ACTIVE: WEEKLY POOL (1.5%)';
                $activePoolClass = 'weekly';
            } elseif ($dQual['is_qualified']) {
                $activePoolLabel = 'ACTIVE: DAILY POOL (1.5%)';
                $activePoolClass = 'daily';
            }

            // Total Pool Earnings
            $totalPoolEarned = (float) \App\PoolIncome::where('userid', $uid)->sum('amt_usdt');
        @endphp
        <div class="hud-capping-wrap hud-pool-wrap">
            <div class="hud-capping-card hud-pool-card" onclick="window.location.href='{{ url('/User/PoolIncome') }}'"
                style="cursor: pointer;">
                <!-- Corner Tech Accents -->
                <span class="cap-corner-bracket tl"></span>
                <span class="cap-corner-bracket tr"></span>
                <span class="cap-corner-bracket bl"></span>
                <span class="cap-corner-bracket br"></span>

                <div class="capping-card-content">
                    <!-- Header Row -->
                    <div class="capping-head-row">
                        <div class="capping-title-group">
                            <div class="capping-badge-icon pool-badge-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <div class="capping-main-title" style="color: #DDD6FE;">GLOBAL TURNOVER POOLS</div>
                                <div class="capping-sub-title">5.0% COMPANY DIVIDEND</div>
                            </div>
                        </div>
                        <div class="capping-tier-pill pool-status-pill {{ $activePoolClass }}">
                            <span class="cap-pulse-dot"
                                style="{{ $activePoolClass == 'locked' ? 'background: #EF4444; box-shadow: 0 0 6px #EF4444;' : '' }}"></span>
                            <span>{{ $activePoolLabel }}</span>
                        </div>
                    </div>

                    <!-- 2x2 POOL QUALIFICATION GRID -->
                    <div class="pool-hud-2x2-grid">
                        <!-- 1. Daily Pool (1.5%) -->
                        <div class="pool-grid-box {{ $dQual['is_qualified'] ? 'qualified' : '' }}">
                            <div class="pool-box-top">
                                <span class="pool-box-lbl"><i class="fas fa-sun" style="color: #60A5FA;"></i> DAILY
                                    POOL</span>
                                <span
                                    class="pool-box-badge {{ $dQual['is_qualified'] ? 'badge-active' : 'badge-locked' }}">
                                    {{ $dQual['is_qualified'] ? 'QUALIFIED' : 'LOCKED' }}
                                </span>
                            </div>
                            <div class="pool-box-val-row">
                                <span class="pool-box-val">${{ number_format($dQual['current_self'], 0) }}</span>
                                <span class="pool-box-target">/ $100 Self</span>
                            </div>
                            <div class="pool-box-progress">
                                <div class="pool-box-progress-fill"
                                    style="width: {{ $dQual['progress_pct'] }}%; background: #3B82F6;"></div>
                            </div>
                            <div class="pool-box-note">
                                @if($dQual['is_qualified'])
                                    <span class="text-green"><i class="fas fa-check"></i> Earning Daily 1.5%</span>
                                @else
                                    <span>Need
                                        <strong>${{ number_format(max(0, 100 - $dQual['current_self']), 0) }}</strong>
                                        Self</span>
                                @endif
                            </div>
                        </div>

                        <!-- 2. Weekly Pool (1.5%) -->
                        <div class="pool-grid-box {{ $wQual['is_qualified'] ? 'qualified' : '' }}">
                            <div class="pool-box-top">
                                <span class="pool-box-lbl"><i class="fas fa-calendar-week" style="color: #34D399;"></i>
                                    WEEKLY POOL</span>
                                <span
                                    class="pool-box-badge {{ $wQual['is_qualified'] ? 'badge-active' : 'badge-locked' }}">
                                    {{ $wQual['is_qualified'] ? 'QUALIFIED' : 'LOCKED' }}
                                </span>
                            </div>
                            <div class="pool-box-val-row">
                                <span class="pool-box-val">{{ $wQual['current_directs'] }}</span>
                                <span class="pool-box-target">/ 5 Directs ($100+)</span>
                            </div>
                            <div class="pool-box-progress">
                                <div class="pool-box-progress-fill"
                                    style="width: {{ $wQual['progress_pct'] }}%; background: #10B981;"></div>
                            </div>
                            <div class="pool-box-note">
                                @if($wQual['is_qualified'])
                                    <span class="text-green"><i class="fas fa-check"></i> Earning Weekly 1.5%</span>
                                @else
                                    <span>Need <strong>{{ max(0, 5 - $wQual['current_directs']) }}</strong> Directs</span>
                                @endif
                            </div>
                        </div>

                        <!-- 3. Monthly Pool Directs (2.0%) -->
                        <div class="pool-grid-box {{ $mQual['is_qualified'] ? 'qualified' : '' }}">
                            <div class="pool-box-top">
                                <span class="pool-box-lbl"><i class="fas fa-gem" style="color: #FBBF24;"></i> MONTHLY
                                    (DIR)</span>
                                <span
                                    class="pool-box-badge {{ $mQual['is_qualified'] ? 'badge-active' : 'badge-locked' }}">
                                    {{ $mQual['is_qualified'] ? 'QUALIFIED' : 'LOCKED' }}
                                </span>
                            </div>
                            <div class="pool-box-val-row">
                                <span class="pool-box-val">{{ $mQual['current_directs'] }}</span>
                                <span class="pool-box-target">/ 15 Directs ($100+)</span>
                            </div>
                            <div class="pool-box-progress">
                                <div class="pool-box-progress-fill"
                                    style="width: {{ min(100, round(($mQual['current_directs'] / 15) * 100)) }}%; background: #F59E0B;">
                                </div>
                            </div>
                            <div class="pool-box-note">
                                @if($mQual['current_directs'] >= 15)
                                    <span class="text-green"><i class="fas fa-check"></i> Directs Target Done</span>
                                @else
                                    <span>Need <strong>{{ max(0, 15 - $mQual['current_directs']) }}</strong> Directs</span>
                                @endif
                            </div>
                        </div>

                        <!-- 4. Monthly Pool Leg Volume (2.0%) -->
                        @php
                            $legsDone = ($mQual['current_power'] >= 5000 && $mQual['current_weaker'] >= 5000);
                        @endphp
                        <div class="pool-grid-box {{ $mQual['is_qualified'] ? 'qualified' : '' }}">
                            <div class="pool-box-top">
                                <span class="pool-box-lbl"><i class="fas fa-chart-line" style="color: #A78BFA;"></i>
                                    MONTHLY (VOL)</span>
                                <span class="pool-box-badge {{ $legsDone ? 'badge-active' : 'badge-locked' }}">
                                    {{ $legsDone ? 'QUALIFIED' : 'LOCKED' }}
                                </span>
                            </div>
                            <div class="pool-box-val-row">
                                <span class="pool-box-val">${{ number_format($mQual['current_power'], 0) }}</span>
                                <span class="pool-box-target">/ $5K Power</span>
                            </div>
                            <div class="pool-box-progress">
                                <div class="pool-box-progress-fill"
                                    style="width: {{ min(100, round((min($mQual['current_power'] / 5000, $mQual['current_weaker'] / 5000)) * 100)) }}%; background: #8B5CF6;">
                                </div>
                            </div>
                            <div class="pool-box-note">
                                @if($legsDone)
                                    <span class="text-green"><i class="fas fa-check"></i> Leg Volume Done</span>
                                @else
                                    <span>Weak: ${{ number_format($mQual['current_weaker'], 0) }}/$5K</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Next Pool Target Strip -->
                    <div class="capping-next-tier-note"
                        style="border-color: rgba(139, 92, 246, 0.3); background: rgba(139, 92, 246, 0.06);">
                        <div class="target-head" style="color: #DDD6FE;">
                            <i class="fas fa-angles-up" style="color: #A78BFA;"></i>
                            @if(!$dQual['is_qualified'])
                                <span>TARGET: UNLOCK <strong>DAILY POOL (1.5%)</strong></span>
                            @elseif(!$wQual['is_qualified'])
                                <span>TARGET: UPGRADE TO <strong>WEEKLY POOL (1.5%)</strong></span>
                            @elseif(!$mQual['is_qualified'])
                                <span>TARGET: UPGRADE TO <strong>MONTHLY POOL (2.0%)</strong></span>
                            @else
                                <span>ALL <strong>3 GLOBAL POOLS ACTIVE!</strong></span>
                            @endif
                        </div>
                        <div class="target-chips-wrap">
                            @if(!$dQual['is_qualified'])
                                <div class="target-chip">
                                    <i class="fas fa-wallet" style="color: #60A5FA;"></i>
                                    <span>Deposit
                                        <strong>${{ number_format(max(0, 100 - $dQual['current_self']), 0) }}</strong></span>
                                </div>
                            @elseif(!$wQual['is_qualified'])
                                <div class="target-chip">
                                    <i class="fas fa-user-plus" style="color: #34D399;"></i>
                                    <span><strong>{{ max(0, 5 - $wQual['current_directs']) }}</strong> Directs
                                        (${{ 100 }}+)</span>
                                </div>
                            @elseif(!$mQual['is_qualified'])
                                <div class="target-chip">
                                    <i class="fas fa-user-plus" style="color: #FBBF24;"></i>
                                    <span><strong>{{ max(0, 15 - $mQual['current_directs']) }}</strong> Directs</span>
                                </div>
                                <div class="target-chip">
                                    <i class="fas fa-bolt" style="color: #A78BFA;"></i>
                                    <span>Power:
                                        <strong>${{ number_format(max(0, 5000 - $mQual['current_power']), 0) }}</strong></span>
                                </div>
                                <div class="target-chip">
                                    <i class="fas fa-bolt" style="color: #A78BFA;"></i>
                                    <span>Weaker:
                                        <strong>${{ number_format(max(0, 5000 - $mQual['current_weaker']), 0) }}</strong></span>
                                </div>
                            @else
                                <div class="target-chip">
                                    <i class="fas fa-trophy text-warning"></i>
                                    <span>Total Pool Earned:
                                        <strong>${{ number_format($totalPoolEarned, 2) }}</strong></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- ============================================================
                     5C. RANK INCOME & QUALIFICATION HUD (V1 TO V8)
                     ============================================================ -->
            @php
                $isRankActive = ($userRankLevel > 0);
                $rankPillLabel = $isRankActive ? "ACTIVE: {$userRankName} (\${$rankQual['weekly_reward']}/WK)" : "NO ACTIVE RANK";
            @endphp
            <div class="hud-capping-wrap hud-rank-wrap" style="margin-top: 10px;">
                <div class="hud-capping-card hud-rank-card"
                    onclick="window.location.href='{{ url('/User/RankIncome') }}'"
                    style="cursor: pointer; border-color: rgba(245, 158, 11, 0.4); box-shadow: 0 0 20px rgba(245, 158, 11, 0.08);">
                    <!-- Corner Tech Accents -->
                    <span class="cap-corner-bracket tl" style="border-color: #F59E0B;"></span>
                    <span class="cap-corner-bracket tr" style="border-color: #F59E0B;"></span>
                    <span class="cap-corner-bracket bl" style="border-color: #F59E0B;"></span>
                    <span class="cap-corner-bracket br" style="border-color: #F59E0B;"></span>

                    <div class="capping-card-content">
                        <!-- Header Row -->
                        <div class="capping-head-row">
                            <div class="capping-title-group">
                                <div class="capping-badge-icon"
                                    style="background: rgba(245, 158, 11, 0.15); border: 1px solid #F59E0B; color: #F59E0B;">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div>
                                    <div class="capping-main-title" style="color: #FDE68A;">RANK INCOME (V1 TO V8)</div>
                                    <div class="capping-sub-title">WEEKLY LEADERSHIP &amp; TURNOVER REWARDS</div>
                                </div>
                            </div>
                            <div class="capping-tier-pill"
                                style="{{ $isRankActive ? 'background: rgba(245, 158, 11, 0.2); border: 1px solid #F59E0B; color: #FDE68A;' : 'background: rgba(239, 68, 68, 0.15); border: 1px solid #EF4444; color: #FCA5A5;' }}">
                                <span class="cap-pulse-dot"
                                    style="{{ $isRankActive ? 'background: #F59E0B; box-shadow: 0 0 6px #F59E0B;' : 'background: #EF4444; box-shadow: 0 0 6px #EF4444;' }}"></span>
                                <span>{{ $rankPillLabel }}</span>
                            </div>
                        </div>

                        <!-- Dual Leg Qualification Status Grid -->
                        <div class="pool-hud-2x2-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <!-- Power Leg (PL) Status -->
                            <div
                                class="pool-grid-box {{ ($rankQual['next_rank'] && $rankQual['power_leg'] >= $rankQual['next_pl_req']) ? 'qualified' : '' }}">
                                <div class="pool-box-top">
                                    <span class="pool-box-lbl"><i class="fas fa-bolt" style="color: #F59E0B;"></i> POWER
                                        LEG (PL)</span>
                                    <span
                                        class="pool-box-badge {{ ($rankQual['next_rank'] && $rankQual['power_leg'] >= $rankQual['next_pl_req']) ? 'badge-active' : 'badge-locked' }}">
                                        {{ ($rankQual['next_rank'] && $rankQual['power_leg'] >= $rankQual['next_pl_req']) ? 'QUALIFIED' : 'IN PROGRESS' }}
                                    </span>
                                </div>
                                <div class="pool-box-val-row">
                                    <span class="pool-box-val"
                                        style="color: #F59E0B;">${{ number_format($rankQual['power_leg'], 0) }}</span>
                                    @if($rankQual['next_rank'])
                                        <span class="pool-box-target">/ ${{ number_format($rankQual['next_pl_req'], 0) }}
                                            target</span>
                                    @else
                                        <span class="pool-box-target">/ MAX V8 REACHED</span>
                                    @endif
                                </div>
                                <div class="pool-box-progress">
                                    <div class="pool-box-progress-fill"
                                        style="width: {{ $rankQual['next_pl_progress'] }}%; background: linear-gradient(90deg, #F59E0B, #FBBF24);">
                                    </div>
                                </div>
                                <div class="pool-box-note">
                                    @if($rankQual['next_rank'])
                                        @if($rankQual['next_pl_needed'] <= 0)
                                            <span class="text-green"><i class="fas fa-check"></i> Power Leg Target
                                                Achieved</span>
                                        @else
                                            <span>Need <strong>${{ number_format($rankQual['next_pl_needed'], 0) }}</strong>
                                                More PL Volume</span>
                                        @endif
                                    @else
                                        <span class="text-green"><i class="fas fa-crown"></i> Highest Rank V8
                                            Achieved!</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Weaker Leg (WL) Status -->
                            <div
                                class="pool-grid-box {{ ($rankQual['next_rank'] && $rankQual['weaker_leg'] >= $rankQual['next_wl_req']) ? 'qualified' : '' }}">
                                <div class="pool-box-top">
                                    <span class="pool-box-lbl"><i class="fas fa-code-branch"
                                            style="color: #38BDF8;"></i> WEAKER LEG (WL)</span>
                                    <span
                                        class="pool-box-badge {{ ($rankQual['next_rank'] && $rankQual['weaker_leg'] >= $rankQual['next_wl_req']) ? 'badge-active' : 'badge-locked' }}">
                                        {{ ($rankQual['next_rank'] && $rankQual['weaker_leg'] >= $rankQual['next_wl_req']) ? 'QUALIFIED' : 'IN PROGRESS' }}
                                    </span>
                                </div>
                                <div class="pool-box-val-row">
                                    <span class="pool-box-val"
                                        style="color: #38BDF8;">${{ number_format($rankQual['weaker_leg'], 0) }}</span>
                                    @if($rankQual['next_rank'])
                                        <span class="pool-box-target">/ ${{ number_format($rankQual['next_wl_req'], 0) }}
                                            target</span>
                                    @else
                                        <span class="pool-box-target">/ MAX V8 REACHED</span>
                                    @endif
                                </div>
                                <div class="pool-box-progress">
                                    <div class="pool-box-progress-fill"
                                        style="width: {{ $rankQual['next_wl_progress'] }}%; background: linear-gradient(90deg, #38BDF8, #0284C7);">
                                    </div>
                                </div>
                                <div class="pool-box-note">
                                    @if($rankQual['next_rank'])
                                        @if($rankQual['next_wl_needed'] <= 0)
                                            <span class="text-green"><i class="fas fa-check"></i> Weaker Leg Target
                                                Achieved</span>
                                        @else
                                            <span>Need <strong>${{ number_format($rankQual['next_wl_needed'], 0) }}</strong>
                                                More WL Volume</span>
                                        @endif
                                    @else
                                        <span class="text-green"><i class="fas fa-crown"></i> Highest Rank V8
                                            Achieved!</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Next Rank Target & Deficit Strip -->
                        <div class="capping-next-tier-note"
                            style="border-color: rgba(245, 158, 11, 0.3); background: rgba(245, 158, 11, 0.06);">
                            <div class="target-head" style="color: #FDE68A;">
                                <i class="fas fa-angles-up" style="color: #F59E0B;"></i>
                                @if($rankQual['next_rank'])
                                    <span>NEXT RANK TARGET: <strong>{{ $rankQual['next_rank']->rank_name }}
                                            (${{ number_format($rankQual['next_rank']->weekly_reward, 0) }}/WK)</strong></span>
                                @else
                                    <span>MAXIMUM RANK REACHED: <strong>V8 ($2,000/WK)</strong></span>
                                @endif
                            </div>
                            <div class="target-chips-wrap">
                                @if($rankQual['next_rank'])
                                    <div class="target-chip">
                                        <i class="fas fa-bolt" style="color: #F59E0B;"></i>
                                        <span>PL Deficit:
                                            <strong>${{ number_format($rankQual['next_pl_needed'], 0) }}</strong></span>
                                    </div>
                                    <div class="target-chip">
                                        <i class="fas fa-code-branch" style="color: #38BDF8;"></i>
                                        <span>WL Deficit:
                                            <strong>${{ number_format($rankQual['next_wl_needed'], 0) }}</strong></span>
                                    </div>
                                    <div class="target-chip">
                                        <i class="fas fa-gift" style="color: #10B981;"></i>
                                        <span>Weekly Reward:
                                            <strong>${{ number_format($rankQual['next_rank']->weekly_reward, 0) }}/wk</strong></span>
                                    </div>
                                @else
                                    <div class="target-chip">
                                        <i class="fas fa-crown" style="color: #FFD700;"></i>
                                        <span>Earning Maximum: <strong>$2,000 / Week</strong></span>
                                    </div>
                                @endif
                                <div class="target-chip"
                                    style="margin-left: auto; border-color: rgba(245, 158, 11, 0.4);">
                                    <span style="color: #F59E0B; font-weight: 800;">View V1-V8 Full Matrix →</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- ============================================================
             6. TEAM & LEG VOLUME STATUS (4-GRID BOOSTER HUD)
             ============================================================ -->
            <div class="section-bar-header" style="margin-top:4px;">
                <div class="sec-title-txt">TEAM &amp; LEG VOLUME STATUS</div>
                <a href="{{ url('/User/AllTeam') }}" class="btn-mecha-pill">VIEW TEAM</a>
            </div>

            <div class="team-section-wrap">
                <div class="team-4col-grid">
                    <!-- 1. Directs (Gold) -->
                    <div class="team-stat-tile team-gold" onclick="window.location.href='{{ url('/User/DirectTeam') }}'">
                        <div class="team-card-corner-glow"></div>
                        <div class="team-tile-inner">
                            <div class="team-stat-ico">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="team-stat-text-col">
                                <span class="team-stat-lbl">TOTAL DIRECTS</span>
                                <span class="team-stat-val">{{ $totalDirects }}</span>
                                <span class="team-stat-sub sub-green">{{ $activeDirects }} Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Total Team (Cyan) -->
                    <div class="team-stat-tile team-cyan" onclick="window.location.href='{{ url('/User/AllTeam') }}'">
                        <div class="team-card-corner-glow"></div>
                        <div class="team-tile-inner">
                            <div class="team-stat-ico">
                                <i class="fas fa-users-gear"></i>
                            </div>
                            <div class="team-stat-text-col">
                                <span class="team-stat-lbl">TOTAL TEAM</span>
                                <span class="team-stat-val">{{ $totalTeam }}</span>
                                <span class="team-stat-sub sub-cyan">{{ $activeTeam }} Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Power Leg Volume (Gold/Lightning) -->
                    <div class="team-stat-tile team-gold" onclick="window.location.href='{{ url('/User/TeamSummary') }}'">
                        <div class="team-card-corner-glow"></div>
                        <div class="team-tile-inner">
                            <div class="team-stat-ico">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="team-stat-text-col">
                                <span class="team-stat-lbl">POWER LEG</span>
                                <span class="team-stat-val">${{ number_format($powerLeg, 2) }}</span>
                                <span class="team-stat-sub sub-green">Strong Leg</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Other Legs Volume (Purple) -->
                    <div class="team-stat-tile team-purple" onclick="window.location.href='{{ url('/User/TeamSummary') }}'">
                        <div class="team-card-corner-glow"></div>
                        <div class="team-tile-inner">
                            <div class="team-stat-ico">
                                <i class="fas fa-code-branch"></i>
                            </div>
                            <div class="team-stat-text-col">
                                <span class="team-stat-lbl">OTHER LEGS</span>
                                <span class="team-stat-val">${{ number_format($weakerLeg, 2) }}</span>
                                <span class="team-stat-sub sub-gold">Other Legs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================
             7. RECENT ACTIVITY SECTION (SLEEK BOOSTER HUD)
             ============================================================ -->
            <div class="section-bar-header" style="margin-top:6px;">
                <div class="sec-title-txt">RECENT ACTIVITY</div>
                <a href="{{ url('/User/StakingTxnHistory') }}" class="btn-mecha-pill">VIEW ALL</a>
            </div>

            <div class="recent-act-wrap">
                <div class="recent-act-card">
                    <div class="recent-card-corner-glow"></div>
                    <div class="activity-list-inner">
                        @forelse($recentActivities as $act)
                            <div class="act-row-item">
                                <div class="act-col-left">
                                    <div class="act-icon-bubble bg-{{ $act->color_type }}-dim">
                                        <i class="fas {{ $act->icon }}"
                                            style="color: @if($act->color_type == 'green') #00FF88 @elseif($act->color_type == 'gold') #FFD700 @elseif($act->color_type == 'cyan') #00D2FF @else #B34BFE @endif;"></i>
                                    </div>
                                    <span class="act-type-title">{{ $act->type }}</span>
                                </div>
                                <div
                                    class="act-col-amount @if($act->color_type == 'green') text-green @elseif($act->color_type == 'cyan') text-cyan @elseif($act->color_type == 'gold') text-gold @endif">
                                    <strong>{{ $act->amount }}</strong>
                                </div>
                                <div class="act-col-status">
                                    <span class="{{ $act->status_cls }}">{{ $act->status }} <i class="fas fa-circle-check"
                                            style="font-size:8px;"></i></span>
                                </div>
                                <div class="act-col-time">{{ $act->time }}</div>
                            </div>
                        @empty
                            <div
                                style="text-align: center; padding: 28px 16px; color: var(--text-muted); font-size: 0.85rem;">
                                <i class="fas fa-receipt"
                                    style="font-size: 24px; margin-bottom: 8px; color: var(--gold-primary); opacity: 0.6; display: block;"></i>
                                No recent activity found. Topup or stake to generate rewards!
                            </div>
                        @endforelse
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

        function copyDashRefLink() {
            const copyInput = document.getElementById('dashRefInput');
            if (!copyInput) return;
            const refLink = copyInput.value;
            const btnText = document.getElementById('dashCopyBtnText');
            
            function onCopied() {
                if (btnText) {
                    btnText.innerText = 'COPIED!';
                    setTimeout(() => { btnText.innerText = 'COPY'; }, 2000);
                }
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(refLink).then(onCopied).catch(() => {
                    copyInput.select();
                    document.execCommand('copy');
                    onCopied();
                });
            } else {
                copyInput.select();
                copyInput.setSelectionRange(0, 99999);
                document.execCommand('copy');
                onCopied();
            }
        }

        function showReferralQR() {
            const refLink = "{{ url('/register/' . (Session::get('user.uuid') ?? '')) }}";
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
            initBoosterCountdown();
        });

        /* ============================================================
           REAL-TIME LIVE BOOSTER COUNTDOWN ENGINE
           ============================================================ */
        function initBoosterCountdown() {
            const timerChips = document.querySelectorAll('.booster-countdown-live');
            if (!timerChips.length) return;

            function updateTimers() {
                const now = Date.now();
                timerChips.forEach(chip => {
                    const expiry = parseInt(chip.getAttribute('data-expiry') || '0', 10);
                    const isExpiredAttr = chip.getAttribute('data-expired') === '1';
                    const displayEl = chip.querySelector('.timer-display-txt');
                    if (!displayEl) return;

                    if (!expiry || isExpiredAttr || expiry <= now) {
                        displayEl.innerText = 'Window Closed';
                        chip.classList.add('expired');
                        return;
                    }

                    const diff = Math.max(0, expiry - now);
                    const totalSeconds = Math.floor(diff / 1000);
                    const days = Math.floor(totalSeconds / 86400);
                    const hours = Math.floor((totalSeconds % 86400) / 3600);
                    const minutes = Math.floor((totalSeconds % 3600) / 60);
                    const seconds = totalSeconds % 60;

                    const pad = (n) => String(n).padStart(2, '0');
                    displayEl.innerText = `${pad(days)}d : ${pad(hours)}h : ${pad(minutes)}m : ${pad(seconds)}s`;
                });
            }

            updateTimers();
            setInterval(updateTimers, 1000);
        }

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

        function copyDashRefLink() {
            const input = document.getElementById('dashRefInput');
            const btnText = document.getElementById('dashCopyBtnText');
            if (input) {
                input.select();
                input.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(input.value);
                if (btnText) btnText.innerText = 'COPIED!';
                setTimeout(function() {
                    if (btnText) btnText.innerText = 'COPY LINK';
                }, 2000);
            }
        }
    </script>

</body>

</html>