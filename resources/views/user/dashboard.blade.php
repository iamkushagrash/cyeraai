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
    <link href="{{asset('css/cyera-dashboard.css')}}?v={{ time() }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Instant High-Priority Cyber SweetAlert2 Theme Overrides */
        .swal2-container,
        div:where(.swal2-container) {
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            background: rgba(2, 3, 6, 0.88) !important;
            z-index: 999999 !important;
            padding: 12px !important;
        }

        .swal2-popup,
        div:where(.swal2-container) .swal2-popup,
        .mecha-swal-popup {
            background: linear-gradient(180deg, #0D111A 0%, #040508 100%) !important;
            background-color: #080A10 !important;
            border: 1px solid rgba(245, 166, 35, 0.45) !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.95), 0 0 35px rgba(245, 166, 35, 0.2), inset 0 1px 1px rgba(255, 215, 0, 0.18) !important;
            color: #FFFFFF !important;
            font-family: 'Inter', sans-serif !important;
            padding: 22px 18px 20px 18px !important;
            width: 92% !important;
            max-width: 380px !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }

        .swal2-title,
        div:where(.swal2-container) .swal2-title,
        .mecha-swal-title {
            font-family: 'Inter', sans-serif !important;
            font-size: 1.05rem !important;
            font-weight: 800 !important;
            color: #FFD700 !important;
            letter-spacing: 0.4px !important;
            text-transform: uppercase !important;
            padding: 0 !important;
            margin: 8px 0 10px 0 !important;
            text-align: center !important;
            text-shadow: 0 0 14px rgba(255, 215, 0, 0.3) !important;
        }

        .swal2-html-container,
        div:where(.swal2-container) .swal2-html-container,
        .mecha-swal-html {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.80rem !important;
            color: #94A3B8 !important;
            line-height: 1.5 !important;
            padding: 0 !important;
            margin: 4px 0 14px 0 !important;
            width: 100% !important;
            overflow: visible !important;
        }

        .swal2-actions,
        div:where(.swal2-container) .swal2-actions,
        .mecha-swal-actions {
            display: flex !important;
            flex-direction: row !important;
            gap: 8px !important;
            width: 100% !important;
            margin: 12px 0 0 0 !important;
            padding: 0 !important;
        }

        .swal2-confirm,
        div:where(.swal2-container) .swal2-confirm,
        .mecha-swal-confirm {
            flex: 1 !important;
            min-height: 40px !important;
            background: linear-gradient(135deg, #FFD700 0%, #F5A623 100%) !important;
            color: #000000 !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 800 !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.4px !important;
            text-transform: uppercase !important;
            border-radius: 8px !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(245, 166, 35, 0.35) !important;
            cursor: pointer !important;
            outline: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
        }

        .swal2-confirm:active,
        .mecha-swal-confirm:active {
            transform: scale(0.98) !important;
        }

        .swal2-cancel,
        div:where(.swal2-container) .swal2-cancel,
        .mecha-swal-cancel {
            flex: 1 !important;
            min-height: 40px !important;
            background: rgba(255, 255, 255, 0.05) !important;
            color: #8E99A8 !important;
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.4px !important;
            text-transform: uppercase !important;
            border-radius: 8px !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            cursor: pointer !important;
            outline: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: background 0.15s ease, color 0.15s ease !important;
        }

        .swal2-cancel:hover,
        .mecha-swal-cancel:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #FFFFFF !important;
        }

        .mecha-wallet-choice-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(245, 166, 35, 0.04);
            border: 1px solid rgba(245, 166, 35, 0.22);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mecha-wallet-choice-item:hover {
            background: rgba(245, 166, 35, 0.12) !important;
            border-color: rgba(245, 166, 35, 0.55) !important;
            transform: translateY(-1px);
        }
    </style>
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

                // Dynamic Capping & Leg Stats
                $cappingStats = $data['cappingStats'] ?? $data['userDetail']->getCappingTier();
                $boosterStats = $data['boosterStats'] ?? $data['userDetail']->getBoosterStats();
                $legStats = $data['legStats'] ?? $data['userDetail']->getLegBusiness();
                $poolQualifications = $data['poolQualifications'] ?? $data['userDetail']->getPoolQualifications();

                $tierMultiplier = $cappingStats['multiplier'] ?: 2;
                $tierLabel = $tierMultiplier . 'X';

                // Active remaining capping across staking deposits
                $activeDeposits = \App\StackingDeposite::where([['userid', $uid], ['status', '>', 0]])->get();
                $isCapCompleted = false;

                if ($activeDeposits->isNotEmpty()) {
                    $totalInvested = (float) $activeDeposits->sum('usdt');
                    $maxCapping = $totalInvested > 0 ? ($totalInvested * $tierMultiplier) : 0.00;
                    $totalRemainingCapping = 0;
                    foreach ($activeDeposits as $dep) {
                        try {
                            $totalRemainingCapping += (float) \Crypt::decrypt($dep->capamount);
                        } catch (\Exception $e) {
                        }
                    }
                    $cappingConsumed = max(0, $maxCapping - $totalRemainingCapping);
                    $filledPct = ($maxCapping > 0) ? ($cappingConsumed / $maxCapping) * 100 : 0;
                    $filledPct = min(100, max(0, round($filledPct)));
                    if ($totalRemainingCapping <= 0.01 && $maxCapping > 0) {
                        $isCapCompleted = true;
                        $filledPct = 100;
                        $cappingConsumed = $maxCapping;
                    }
                } else {
                    // Check if user had previous deposits that have reached 0 capping (status 0)
                    $lastDeposit = \App\StackingDeposite::where('userid', $uid)->orderBy('id', 'desc')->first();
                    if ($lastDeposit) {
                        $totalInvested = (float) $lastDeposit->usdt;
                        $maxCapping = $totalInvested * $tierMultiplier;
                        $totalRemainingCapping = 0;
                        $cappingConsumed = $maxCapping;
                        $filledPct = 100;
                        $isCapCompleted = true;
                    } else {
                        $totalInvested = 0.00;
                        $maxCapping = 0.00;
                        $totalRemainingCapping = 0;
                        $cappingConsumed = 0.00;
                        $filledPct = 0;
                    }
                }

                // CAI Live Price & Dynamic Runtime ROI Calculations
                $caiPrice = \App\ProfileStore::getLivePrice();
                if ($caiPrice <= 0)
                    $caiPrice = 1.00;

                // Claimable CPS ROI in USDT and dynamic live CAI tokens
                $claimableUsdt = (float) \App\CpsIncome::where('userid', $uid)->where('status', 0)->sum('amt_usdt');
                if ($claimableUsdt <= 0) {
                    $claimableUsdt = (float) \App\CpsIncome::where('userid', $uid)->where('status', 0)->sum('remaining');
                }
                $claimableCai = ($caiPrice > 0) ? ($claimableUsdt / $caiPrice) : $claimableUsdt;
                $claimableUsdDynamic = $claimableUsdt;
                $claimableUsdVal = $claimableUsdt;

                // Total CPS in USDT and dynamic CAI tokens
                $cpsUsdtTotal = (float) \App\CpsIncome::where('userid', $uid)->sum('amt_usdt');
                if ($cpsUsdtTotal <= 0) {
                    $cpsUsdtTotal = (float) \App\CpsIncome::where('userid', $uid)->sum('amount');
                }
                $cpsCaiTotal = ($caiPrice > 0) ? ($cpsUsdtTotal / $caiPrice) : $cpsUsdtTotal;
                $cpsUsdDynamic = $cpsUsdtTotal;

                $readyToReleaseCai = $claimableCai + ($caiPrice > 0 ? (($directRemaining + $levelRemaining + $poolRemaining + $rankRemaining) / $caiPrice) : 0.00);

                // Real-time Streaming Daily Staking Yield Parameters (04:30 AM Cycle)
                $userBooster = (int) ($data['userDetail']->booster ?? 1);
                $dailyRoiRate = 0.50;
                if ($userBooster == 3) {
                    $dailyRoiRate = 1.50;
                } elseif ($userBooster == 2) {
                    $dailyRoiRate = 1.00;
                }
                $dailyExpectedRoiUsdt = ($totalInvested > 0 && !$isCapCompleted && $data['userDetail']->userstatus == 1) ? (($totalInvested * $dailyRoiRate) / 100) : 0.00;
                $dailyExpectedRoiCai = ($caiPrice > 0) ? ($dailyExpectedRoiUsdt / $caiPrice) : 0.00;

                $nowKolkata = \Carbon\Carbon::now('Asia/Kolkata');
                $cronToday = \Carbon\Carbon::today('Asia/Kolkata')->setTime(4, 30, 0);
                if ($nowKolkata->greaterThanOrEqualTo($cronToday)) {
                    $lastCronMs = $cronToday->timestamp * 1000;
                    $nextCronMs = $cronToday->copy()->addDay()->timestamp * 1000;
                } else {
                    $lastCronMs = $cronToday->copy()->subDay()->timestamp * 1000;
                    $nextCronMs = $cronToday->timestamp * 1000;
                }

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
                                    ${{ number_format($caiPrice, 6) }}
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

                            <!-- Right Column: Total Portfolio Value -->
                            <div class="col-right-portfolio">
                                <div class="row-port-title">
                                    <span class="txt-port-title">TOTAL PORTFOLIO</span>
                                    <i class="fas fa-eye icon-eye-gold" title="Hide/Show Balance"></i>
                                </div>
                                <div class="txt-port-big">${{ number_format($totalInvested, 2) }}</div>
                                <div class="txt-port-unit">USDT STAKED VALUE</div>
                            </div>
                        </div>

                        <!-- Dedicated Bottom Strip: 2-Tier Clean HUD (Zero Overlap) -->
                        @php
                            $isUserActive = ($data['userDetail']->userstatus == 1 || $data['userDetail']->userstate > 0 || $totalInvested > 0);
                        @endphp
                        <div class="hero-bottom-strip">
                            <!-- Top Tier: Status & Rank (Left) + Fund Wallet (Right) -->
                            <div class="hero-strip-top-row">
                                <div class="strip-item-status">
                                    <span class="strip-col-lbl"><i class="fas fa-shield-halved"
                                            style="color: #FFD700;"></i> STATUS & RANK</span>
                                    <div class="strip-val-wrap">
                                        @if($isUserActive)
                                            <span class="strip-status-pill active"><i class="fas fa-circle-check"></i>
                                                ACTIVE</span>
                                        @else
                                            <span class="strip-status-pill inactive"><i class="fas fa-circle-xmark"></i>
                                                INACTIVE</span>
                                        @endif
                                        <span class="strip-status-pill rank-pill" title="Current Rank">
                                            <i class="fas fa-crown"></i> {{ $userRankName }}
                                        </span>
                                    </div>
                                </div>

                                <div class="strip-item-fund">
                                    <span class="strip-col-lbl"><i class="fas fa-wallet" style="color: #00FF88;"></i>
                                        FUND WALLET</span>
                                    <div class="strip-val-wrap">
                                        <span
                                            class="strip-col-val fund-val">${{ number_format($fundWalletBalance, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Tier: Unmined Daily ROI (Left) + Mine & Sell CAI Action (Right) -->
                            <div class="hero-strip-bottom-row">
                                <div class="strip-item-claim-info">
                                    <span class="strip-col-lbl"><i class="fas fa-bolt" style="color: #FFD700;"></i>
                                        UNMINED DAILY ROI</span>
                                    <div class="strip-val-wrap">
                                        <span class="strip-roi-amount-cai"
                                            style="color: #FFD700; font-family: 'Space Mono', monospace;">
                                            ${{ number_format($claimableUsdt, 2) }} <span class="unit-cai"
                                                style="color: #FFF;">USDT</span>
                                        </span>
                                        <span class="strip-roi-amount-usd"
                                            style="color: #FFFFFF; font-family: 'Space Mono', monospace;">(≈
                                            {{ number_format($claimableCai, 2) }} CAI)</span>
                                    </div>
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

                    <!-- 2. WITHDRAW (Green / Mint) -->
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

                    <!-- 3. INVITE (Purple / Indigo) -->
                    <a href="{{ url('/User/DirectUsers') }}" class="action-card invite" id="btn-invite">
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

                    <!-- 4. TEAM / GENEALOGY (Cyan / Network Theme) -->
                    <a href="{{ url('/User/Treeview') }}" class="action-card claim-roi" id="btn-team-genealogy">
                        <div class="action-card-corner-glow"></div>
                        <div class="action-badge-icon">
                            <i class="fas fa-sitemap" style="color: #00D2FF;"></i>
                        </div>
                        <div class="action-content">
                            <h3>TEAM</h3>
                            <div class="action-sub-row">
                                <span class="action-sub-txt">Genealogy</span>
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
                    <div
                        style="background: rgba(8, 9, 15, 0.9); backdrop-filter: blur(16px); border: 1px solid rgba(245, 166, 35, 0.35); border-radius: 12px; padding: 5px 6px 5px 12px; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.06);">
                        <i class="fas fa-link" style="color: #FFD700; font-size: 0.85rem; flex-shrink: 0;"></i>
                        <input type="text" id="dashRefInput" value="{{ $dashReferralUrl }}" readonly
                            style="flex: 1; min-width: 0; background: transparent; border: none; outline: none; color: #FFD700; font-family: 'Space Mono', monospace; font-size: 0.80rem; font-weight: 600; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; padding: 0;">
                        <button type="button" onclick="copyDashRefLink()" class="btn-solid-gold-claim"
                            style="height: 32px; padding: 0 12px; font-size: 0.75rem; border-radius: 8px; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; flex-shrink: 0; cursor: pointer; border: none;">
                            <i class="fas fa-copy"></i> <span id="dashCopyBtnText">COPY</span>
                        </button>
                    </div>
                </div>
                <!-- 2. Real-Time Streaming Daily Yield Accrual (Live Ticking since 04:30 AM) -->
                <div class="hud-live-yield-standalone" onclick="window.location.href='{{ url('/User/RoiIncome') }}'">
                    <div class="live-yield-header-bar">
                        <div class="live-yield-title-wrap">
                            <span class="pulse-green-dot"></span>
                            <span class="live-yield-title-txt" style="font-size: 0.72rem; font-weight: 800; color: #00FF88; letter-spacing: 0.5px;">LIVE ACCRUING YIELD (TODAY)</span>
                            <span class="live-yield-rate-tag" style="font-size: 0.65rem; font-weight: 800; color: #FFE082; background: rgba(245, 166, 35, 0.15); border: 1px solid rgba(245, 166, 35, 0.4); padding: 2px 6px; border-radius: 4px;">{{ $dailyRoiRate }}% / DAY</span>
                        </div>
                        <div class="live-yield-cron-timer" title="Next Cron Payout at 04:30 AM" style="background: rgba(0, 0, 0, 0.75); border: 1px solid rgba(245, 166, 35, 0.35); padding: 4px 10px; border-radius: 7px; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-clock" style="color: #FFD700; font-size: 11px;"></i>
                            <span style="font-size: 0.70rem; color: #8E99A8; font-weight: 600;">Next Credit:</span>
                            <strong id="dashCronCountdownTxt" style="font-size: 0.88rem; font-family: 'Space Mono', monospace; color: #FFD700; font-weight: 800; letter-spacing: 0.5px;">--h : --m : --s</strong>
                        </div>
                    </div>

                    <div class="live-yield-grid-row">
                        <div class="live-yield-col-accrued">
                            <div class="live-yield-val-wrap">
                                <span class="live-yield-val-usd" id="dashLiveAccruedUsd">$0.0000</span>
                                <span class="live-yield-val-cai">(<span id="dashLiveAccruedCai">0.0000</span>
                                    CAI)</span>
                            </div>
                        </div>
                        <div class="live-yield-col-target">
                            <span class="live-yield-lbl">24H Expected ROI</span>
                            <div class="live-yield-target-val">
                                <strong>${{ number_format($dailyExpectedRoiUsdt, 2) }}</strong> <small>USDT</small>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    // Dynamic Mining & Protocol Holdings Calculations for 1-Click Engine
                    $unminedRoiUsdt = (float) \App\CpsIncome::where('userid', $uid)->where('status', 0)->sum('amt_usdt');
                    $unminedRoiCai = ($caiPrice > 0) ? ($unminedRoiUsdt / $caiPrice) : 0;
                    $protocolHoldingCai = (float) ($data['userDetail']->cai_balance ?? 0);
                    $protocolHoldingUsdt = $protocolHoldingCai * $caiPrice;
                    $activeDynamicCapping = $totalRemainingCapping;
                    $eligibleSellUsdt = min($protocolHoldingUsdt, $activeDynamicCapping);
                    $eligibleSellCai = ($caiPrice > 0 && $eligibleSellUsdt > 0) ? min($protocolHoldingCai, round($eligibleSellUsdt / $caiPrice, 6)) : 0;
                    $excessReserveCai = max(0, round($protocolHoldingCai - $eligibleSellCai, 6));

                    $miningEngineContract = env('CYERA_MINING_ENGINE_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217');
                    $pancakePairAddress = env('PANCAKESWAP_PAIR_ADDRESS', '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c');
                @endphp

                <!-- ============================================================
                     3. DECENTRALIZED CAI MINING & DEX SWAP (PURE GOLDEN & BLACK THEME)
                     ============================================================ -->
                <div class="hud-mining-engine-section" style="margin: 0 8px 12px 8px;">
                    <div
                        style="background: linear-gradient(180deg, rgba(8, 10, 16, 0.98) 0%, rgba(3, 4, 8, 1) 100%); border: 1px solid rgba(245, 166, 35, 0.35); border-radius: 12px; padding: 10px 12px; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.65), inset 0 1px 1px rgba(255, 215, 0, 0.08); position: relative; font-family: 'Inter', sans-serif;">

                        <!-- Clean Section Header (Zero Overlap, Fully Responsive) -->
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 8px; padding-bottom: 7px; border-bottom: 1px solid rgba(245, 166, 35, 0.15);">
                            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1;">
                                <div
                                    style="width: 26px; height: 26px; border-radius: 6px; background: rgba(255, 215, 0, 0.12); border: 1px solid rgba(255, 215, 0, 0.35); color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div style="min-width: 0; flex: 1;">
                                    <div
                                        style="font-size: 0.74rem; font-weight: 800; color: #FFFFFF; letter-spacing: 0.3px; line-height: 1.2; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                        CAI MINING &amp; DEX SWAP
                                    </div>
                                    <div
                                        style="font-size: 0.60rem; color: #8E99A8; font-weight: 500; display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                                        <span>1 CAI = <strong id="dashLiveHeaderPrice"
                                                style="color: #FFD700; font-family: 'Space Mono', monospace;">${{ number_format($caiPrice, 6) }}</strong></span>
                                        <span
                                            style="font-family: 'Space Mono', monospace; font-size: 0.50rem; padding: 0 3px; border-radius: 2px; background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3); font-weight: 700;">LIVE</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ url('/User/MiningHistory') }}"
                                style="height: 24px; padding: 0 8px; font-size: 0.65rem; border-radius: 6px; background: rgba(245, 166, 35, 0.12); border: 1px solid rgba(245, 166, 35, 0.4); color: #FFD700; font-weight: 800; font-family: 'Inter', sans-serif; text-decoration: none; display: inline-flex; align-items: center; flex-shrink: 0; white-space: nowrap;">
                                History →
                            </a>
                        </div>

                        <!-- 2-Card Direct Grid -->
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 8px;">

                            <!-- STEP 1: 1-CLICK MINE ALL CAI -->
                            <div
                                style="background: rgba(245, 166, 35, 0.03); border: 1px solid rgba(245, 166, 35, 0.22); border-radius: 9px; padding: 9px 11px; display: flex; flex-direction: column; justify-content: space-between; gap: 7px;">
                                <div>
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                        <span
                                            style="font-size: 0.68rem; font-weight: 800; color: #FFD700; text-transform: uppercase; letter-spacing: 0.3px; font-family: 'Inter', sans-serif;">
                                            STEP 1: MINE TO PROTOCOL
                                        </span>
                                        <span
                                            style="font-size: 0.58rem; color: #00FF88; background: rgba(0, 255, 136, 0.1); padding: 1px 4px; border-radius: 3px; border: 1px solid rgba(0, 255, 136, 0.3); font-weight: 700; font-family: 'Inter', sans-serif;">
                                            0% Cap Minus
                                        </span>
                                    </div>

                                    <div
                                        style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 3px;">
                                        <span style="font-size: 0.68rem; color: #8E99A8; font-weight: 500;">Unmined ROI:</span>
                                        <span
                                            style="font-size: 0.88rem; font-weight: 800; color: #FFD700; font-family: 'Space Mono', monospace;"
                                            id="dashUnminedUsdtTxt">
                                            ${{ number_format($unminedRoiUsdt, 2) }} <small
                                                style="font-size: 0.62rem; color: #FFFFFF; font-weight: 700;">USDT</small>
                                        </span>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                        <span style="font-size: 0.66rem; color: #8E99A8; font-weight: 500;">Yield in CAI:</span>
                                        <span
                                            style="font-size: 0.74rem; font-weight: 700; color: #FFE082; font-family: 'Space Mono', monospace;"
                                            id="dashUnminedCaiYieldTxt">
                                            ≈ {{ number_format($unminedRoiCai, 4) }} CAI
                                        </span>
                                    </div>
                                </div>

                                <button type="button" onclick="executeDashAutoMine()" id="btnDashMineAll"
                                    style="width: 100%; height: 32px; background: linear-gradient(135deg, #FFD700 0%, #F5A623 100%); color: #000000; font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.72rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; box-shadow: 0 3px 10px rgba(245, 166, 35, 0.25); text-transform: uppercase; letter-spacing: 0.3px;"
                                    @if($unminedRoiUsdt <= 0) disabled
                                        style="opacity: 0.4; cursor: not-allowed; width: 100%; height: 32px; background: rgba(255, 255, 255, 0.05); color: #666; border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.72rem; border-radius: 6px; font-family: 'Inter', sans-serif;"
                                    @endif>
                                    <span id="btnDashMineAllTxt">MINE ALL
                                        (${{ number_format($unminedRoiUsdt, 2) }})</span>
                                </button>
                            </div>

                            <!-- STEP 2: 1-CLICK SELL ON PANCAKESWAP DEX -->
                            <div
                                style="background: rgba(245, 166, 35, 0.03); border: 1px solid rgba(245, 166, 35, 0.22); border-radius: 9px; padding: 9px 11px; display: flex; flex-direction: column; justify-content: space-between; gap: 7px;">
                                <div>
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                        <span
                                            style="font-size: 0.68rem; font-weight: 800; color: #FFD700; text-transform: uppercase; letter-spacing: 0.3px; font-family: 'Inter', sans-serif;">
                                            STEP 2: SELL ON DEX
                                        </span>
                                        <span
                                            style="font-size: 0.58rem; color: #FFD700; background: rgba(255, 215, 0, 0.1); padding: 1px 4px; border-radius: 3px; border: 1px solid rgba(255, 215, 0, 0.3); font-weight: 700; font-family: 'Inter', sans-serif;">
                                            Capping Guard
                                        </span>
                                    </div>

                                    <div
                                        style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 3px;">
                                        <span style="font-size: 0.68rem; color: #8E99A8; font-weight: 500;">Protocol CAI:</span>
                                        <span
                                            style="font-size: 0.88rem; font-weight: 800; color: #FFD700; font-family: 'Space Mono', monospace;"
                                            id="dashProtocolCaiTxt">
                                            {{ number_format($protocolHoldingCai, 4) }} <small
                                                style="font-size: 0.62rem; color: #FFFFFF; font-weight: 700;">CAI</small>
                                            <small
                                                style="font-size: 0.66rem; color: #8E99A8;">(${{ number_format($protocolHoldingUsdt, 2) }})</small>
                                        </span>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 3px;">
                                        <span style="font-size: 0.66rem; color: #8E99A8; font-weight: 500;">Max Sellable:</span>
                                        <span
                                            style="font-size: 0.74rem; font-weight: 700; color: #00FF88; font-family: 'Space Mono', monospace;"
                                            id="dashSellableLimitTxt">
                                            ${{ number_format($eligibleSellUsdt, 2) }} USDT <small
                                                style="color: #8E99A8;">({{ number_format($eligibleSellCai, 2) }} CAI)</small>
                                        </span>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                        <span style="font-size: 0.66rem; color: #8E99A8; font-weight: 500;">Est. Wallet Receive:</span>
                                        <span
                                            style="font-size: 0.78rem; font-weight: 800; color: #00FF88; font-family: 'Space Mono', monospace;"
                                            id="dashEstWalletUsdtTxt">
                                            ≈ ${{ number_format($eligibleSellUsdt, 2) }} <small style="font-size: 0.60rem; color: #A0AEC0;">USDT</small>
                                        </span>
                                    </div>
                                </div>

                                <button type="button" onclick="executeDashAutoSellOnDex()" id="btnDashSellAll"
                                    style="width: 100%; height: 32px; background: linear-gradient(135deg, #F5A623 0%, #D97706 100%); color: #000000; font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.72rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; box-shadow: 0 3px 10px rgba(245, 166, 35, 0.25); text-transform: uppercase; letter-spacing: 0.3px;"
                                    @if($eligibleSellUsdt <= 0) disabled
                                        style="opacity: 0.4; cursor: not-allowed; width: 100%; height: 32px; background: rgba(255, 255, 255, 0.05); color: #666; border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.72rem; border-radius: 6px; font-family: 'Inter', sans-serif;"
                                    @endif>
                                    <span id="btnDashSellAllTxt">SELL ALL ELIGIBLE
                                        (${{ number_format($eligibleSellUsdt, 2) }})</span>
                                </button>
                            </div>

                        </div>

                        @if($excessReserveCai > 0)
                            <div
                                style="margin-top: 7px; background: rgba(245, 166, 35, 0.06); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 7px; padding: 5px 8px; font-size: 0.64rem; color: #FFE082; font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 6px; line-height: 1.35;">
                                <i class="fas fa-shield-halved"
                                    style="flex-shrink: 0; color: #FFD700; font-size: 10px;"></i>
                                <span><strong>Capping Hold:</strong> {{ number_format($excessReserveCai, 4) }} CAI
                                    (~${{ number_format($excessReserveCai * $caiPrice, 2) }}) exceeds capping limit and
                                    remains safely held in protocol.</span>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- ============================================================
                 3A. GLOBAL TURNOVER POOLS — SECTION DIVIDER & 2X2 STANDALONE CARDS
                 ============================================================ -->
                @php
                    $dQual = $poolQualifications['daily'];
                    $wQual = $poolQualifications['weekly'];
                    $mQual = $poolQualifications['monthly'];

                    // Global Eligible Achievers Counts & Real-time Pools Calculation
                    // 1. Daily Pool: Active sponsors (self >= $100) of members who staked TODAY
                    $todayDeposits = \App\StackingDeposite::where('status', 1)
                        ->where('created_at', '>=', \Carbon\Carbon::today())
                        ->get();

                    $dailySponsorMap = [];
                    foreach ($todayDeposits as $dep) {
                        $stakedUser = \App\UserDetails::where('id', $dep->userid)->orWhere('userid', $dep->userid)->first();
                        if ($stakedUser && $stakedUser->sponsorid > 0) {
                            $sponsor = \App\UserDetails::where('id', $stakedUser->sponsorid)->orWhere('userid', $stakedUser->sponsorid)->first();
                            if ($sponsor && $sponsor->userstatus == 1 && $sponsor->capping != 1 && (float) $sponsor->current_self_investment >= 100) {
                                $dailySponsorMap[$sponsor->id] = true;
                            }
                        }
                    }
                    $globalDailyEligible = count($dailySponsorMap);
                    $todayDepositsVolume = (float) $todayDeposits->sum('usdt');
                    $todayDailyPoolFund = ($todayDepositsVolume * 1.5) / 100;
                    $todayDailyPerUserShare = ($globalDailyEligible > 0 && $todayDailyPoolFund > 0) ? ($todayDailyPoolFund / $globalDailyEligible) : 0;

                    // 2. Weekly Pool (1.5%)
                    $globalWeeklyEligible = \App\UserDetails::where('userstatus', 1)->where('capping', '!=', 1)->where('current_self_investment', '>=', 100)->where('active_direct', '>=', 5)->count();
                    $thisWeekDepositsVolume = (float) \App\StackingDeposite::where('status', 1)
                        ->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())
                        ->sum('usdt');
                    $thisWeekPoolFund = ($thisWeekDepositsVolume * 1.5) / 100;
                    $thisWeekPerUserShare = ($globalWeeklyEligible > 0 && $thisWeekPoolFund > 0) ? ($thisWeekPoolFund / $globalWeeklyEligible) : 0;

                    // 3. Monthly Directs Pool (2.0%)
                    $globalMonthlyDirEligible = \App\UserDetails::where('userstatus', 1)->where('capping', '!=', 1)->where('current_self_investment', '>=', 100)->where('active_direct', '>=', 15)->count();
                    $thisMonthDepositsVolume = (float) \App\StackingDeposite::where('status', 1)
                        ->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())
                        ->sum('usdt');
                    $thisMonthDirPoolFund = ($thisMonthDepositsVolume * 2.0) / 100;
                    $thisMonthDirPerUserShare = ($globalMonthlyDirEligible > 0 && $thisMonthDirPoolFund > 0) ? ($thisMonthDirPoolFund / $globalMonthlyDirEligible) : 0;

                    // 4. Monthly Team Volume Pool (2.0%)
                    $globalMonthlyVolEligible = \App\UserDetails::where('userstatus', 1)->where('capping', '!=', 1)->where('current_self_investment', '>=', 100)->where('total_investment', '>=', 10000)->count();
                    $thisMonthVolPoolFund = ($thisMonthDepositsVolume * 2.0) / 100;
                    $thisMonthVolPerUserShare = ($globalMonthlyVolEligible > 0 && $thisMonthVolPoolFund > 0) ? ($thisMonthVolPoolFund / $globalMonthlyVolEligible) : 0;

                    // Pool Distribution Countdown Timestamps (Milliseconds)
                    $dailyPoolEndMs = \Carbon\Carbon::now()->endOfDay()->timestamp * 1000;
                    $weeklyPoolEndMs = \Carbon\Carbon::now()->endOfWeek()->timestamp * 1000;
                    $monthlyPoolEndMs = \Carbon\Carbon::now()->endOfMonth()->timestamp * 1000;

                    $legsDone = ($mQual['current_power'] >= 5000 && $mQual['current_weaker'] >= 5000);
                @endphp

                <!-- 1. Central Section Header with Left and Right Lines -->
                <div class="hud-section-divider-bar">
                    <div class="hud-divider-line left"></div>
                    <div class="hud-divider-title-chip" onclick="window.location.href='{{ url('/User/PoolIncome') }}'"
                        style="cursor: pointer;">
                        <div class="hud-divider-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <span class="hud-divider-title-text">GLOBAL TURNOVER POOLS</span>
                        <span class="hud-divider-badge">5.0% PROTOCOL</span>
                    </div>
                    <div class="hud-divider-line right"></div>
                </div>

                <!-- 2. Standalone 2x2 Grid of Pool Cards -->
                <div class="pool-grid-2x2">
                    <!-- Card 1: Daily Pool (1.5%) -->
                    <div class="pool-standalone-card-2x2 {{ $dQual['is_qualified'] ? 'qualified' : '' }}"
                        onclick="window.location.href='{{ url('/User/PoolIncome') }}'">
                        <!-- 1. Top Bar: Icon + Status Badge -->
                        <div class="pool-card-top-bar">
                            <div class="pool-card-icon-bubble">
                                <i class="fas fa-sun"></i>
                            </div>
                            <span class="pool-card-status-badge {{ $dQual['is_qualified'] ? 'qualified' : 'locked' }}">
                                {{ $dQual['is_qualified'] ? 'QUALIFIED' : 'LOCKED' }}
                            </span>
                        </div>

                        <!-- 2. Full Un-truncated Heading & Subtitle -->
                        <div class="pool-card-heading-box">
                            <div class="pool-card-main-title">DAILY POOL</div>
                            <div class="pool-card-dividend-pill">1.5% DAILY DIVIDEND</div>
                        </div>

                        <!-- 3. Telemetry: Pool Fund, Est Share, Eligible, Countdown -->
                        <div class="pool-card-telemetry-box">
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl fund">
                                    <i class="fas fa-coins" style="color: #FFD700;"></i> Pool Fund
                                </span>
                                <span class="pool-telemetry-val fund">
                                    ${{ number_format($todayDailyPoolFund, 2) }}
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl share">
                                    <i class="fas fa-hand-holding-dollar" style="color: #00FF88;"></i> Est. Share
                                </span>
                                <span class="pool-telemetry-val share">
                                    ${{ number_format($todayDailyPerUserShare, 2) }} <small
                                        style="font-size:7px; color:#A0AEC0;">/usr</small>
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl eligible">
                                    <i class="fas fa-users" style="color: #00D2FF;"></i> Eligible
                                </span>
                                <span class="pool-telemetry-val eligible">
                                    <strong>{{ $globalDailyEligible }}</strong> Users
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl timer">
                                    <i class="fas fa-clock" style="color: #FFB300;"></i> Ends In
                                </span>
                                <span class="pool-telemetry-val timer pool-countdown-val"
                                    data-pool-timer="{{ $dailyPoolEndMs }}">
                                    --h : --m : --s
                                </span>
                            </div>
                        </div>

                        <!-- 4. Target Progress & Status -->
                        <div class="pool-card-body-block">
                            <div class="pool-card-val-row"
                                style="display: flex; align-items: baseline; justify-content: space-between;">
                                <span class="pool-card-val-text">${{ number_format($dQual['current_self'], 0) }}</span>
                                <span class="pool-card-target-text">/ $100 Self</span>
                            </div>
                            <div class="pool-card-progress-track">
                                <div class="pool-card-progress-bar-fill"
                                    style="width: {{ $dQual['progress_pct'] }}%; background: linear-gradient(90deg, #F5A623, #00FF88);">
                                </div>
                            </div>
                            @if($dQual['is_qualified'])
                                <div class="pool-card-status-note qualified">
                                    <i class="fas fa-circle-check"></i> Earning Daily 1.5%
                                </div>
                            @elseif($dQual['current_self'] < 100)
                                <div class="pool-card-status-note">
                                    <i class="fas fa-lock"></i> Need
                                    <strong>${{ number_format(max(0, 100 - $dQual['current_self']), 0) }}</strong> Self
                                </div>
                            @else
                                <div class="pool-card-status-note">
                                    <i class="fas fa-user-plus"></i> Need <strong>1 Direct</strong> ($100+)
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card 2: Weekly Pool (1.5%) -->
                    <div class="pool-standalone-card-2x2 {{ $wQual['is_qualified'] ? 'qualified' : '' }}"
                        onclick="window.location.href='{{ url('/User/PoolIncome') }}'">
                        <!-- 1. Top Bar -->
                        <div class="pool-card-top-bar">
                            <div class="pool-card-icon-bubble">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <span class="pool-card-status-badge {{ $wQual['is_qualified'] ? 'qualified' : 'locked' }}">
                                {{ $wQual['is_qualified'] ? 'QUALIFIED' : 'LOCKED' }}
                            </span>
                        </div>

                        <!-- 2. Full Heading -->
                        <div class="pool-card-heading-box">
                            <div class="pool-card-main-title">WEEKLY POOL</div>
                            <div class="pool-card-dividend-pill">1.5% PROTOCOL POOL</div>
                        </div>

                        <!-- 3. Telemetry: Pool Fund, Est Share, Eligible, Countdown -->
                        <div class="pool-card-telemetry-box">
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl fund">
                                    <i class="fas fa-coins" style="color: #FFD700;"></i> Pool Fund
                                </span>
                                <span class="pool-telemetry-val fund">
                                    ${{ number_format($thisWeekPoolFund, 2) }}
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl share">
                                    <i class="fas fa-hand-holding-dollar" style="color: #00FF88;"></i> Est. Share
                                </span>
                                <span class="pool-telemetry-val share">
                                    ${{ number_format($thisWeekPerUserShare, 2) }} <small
                                        style="font-size:7px; color:#A0AEC0;">/usr</small>
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl eligible">
                                    <i class="fas fa-users" style="color: #00D2FF;"></i> Eligible
                                </span>
                                <span class="pool-telemetry-val eligible">
                                    <strong>{{ $globalWeeklyEligible }}</strong> Users
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl timer">
                                    <i class="fas fa-clock" style="color: #FFB300;"></i> Ends In
                                </span>
                                <span class="pool-telemetry-val timer pool-countdown-val"
                                    data-pool-timer="{{ $weeklyPoolEndMs }}">
                                    --d : --h : --m
                                </span>
                            </div>
                        </div>

                        <!-- 4. Body -->
                        <div class="pool-card-body-block">
                            <div class="pool-card-val-row"
                                style="display: flex; align-items: baseline; justify-content: space-between;">
                                <span class="pool-card-val-text">{{ $wQual['current_directs'] }}</span>
                                <span class="pool-card-target-text">/ 5 Directs ($100+)</span>
                            </div>
                            <div class="pool-card-progress-track">
                                <div class="pool-card-progress-bar-fill"
                                    style="width: {{ $wQual['progress_pct'] }}%; background: linear-gradient(90deg, #F5A623, #00FF88);">
                                </div>
                            </div>
                            @if($wQual['is_qualified'])
                                <div class="pool-card-status-note qualified">
                                    <i class="fas fa-circle-check"></i> Earning Weekly 1.5%
                                </div>
                            @else
                                <div class="pool-card-status-note">
                                    <i class="fas fa-lock"></i> Need
                                    <strong>{{ max(0, 5 - $wQual['current_directs']) }}</strong> Directs
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card 3: Monthly Pool Directs (2.0%) -->
                    <div class="pool-standalone-card-2x2 {{ $mQual['is_qualified'] ? 'qualified' : '' }}"
                        onclick="window.location.href='{{ url('/User/PoolIncome') }}'">
                        <!-- 1. Top Bar -->
                        <div class="pool-card-top-bar">
                            <div class="pool-card-icon-bubble">
                                <i class="fas fa-gem"></i>
                            </div>
                            <span class="pool-card-status-badge {{ $mQual['is_qualified'] ? 'qualified' : 'locked' }}">
                                {{ $mQual['is_qualified'] ? 'QUALIFIED' : 'LOCKED' }}
                            </span>
                        </div>

                        <!-- 2. Full Heading -->
                        <div class="pool-card-heading-box">
                            <div class="pool-card-main-title">MONTHLY (DIR)</div>
                            <div class="pool-card-dividend-pill">2.0% DIRECTS POOL</div>
                        </div>

                        <!-- 3. Telemetry: Pool Fund, Est Share, Eligible, Countdown -->
                        <div class="pool-card-telemetry-box">
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl fund">
                                    <i class="fas fa-coins" style="color: #FFD700;"></i> Pool Fund
                                </span>
                                <span class="pool-telemetry-val fund">
                                    ${{ number_format($thisMonthDirPoolFund, 2) }}
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl share">
                                    <i class="fas fa-hand-holding-dollar" style="color: #00FF88;"></i> Est. Share
                                </span>
                                <span class="pool-telemetry-val share">
                                    ${{ number_format($thisMonthDirPerUserShare, 2) }} <small
                                        style="font-size:7px; color:#A0AEC0;">/usr</small>
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl eligible">
                                    <i class="fas fa-users" style="color: #00D2FF;"></i> Eligible
                                </span>
                                <span class="pool-telemetry-val eligible">
                                    <strong>{{ $globalMonthlyDirEligible }}</strong> Users
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl timer">
                                    <i class="fas fa-clock" style="color: #FFB300;"></i> Ends In
                                </span>
                                <span class="pool-telemetry-val timer pool-countdown-val"
                                    data-pool-timer="{{ $monthlyPoolEndMs }}">
                                    --d : --h : --m
                                </span>
                            </div>
                        </div>

                        <!-- 4. Body -->
                        <div class="pool-card-body-block">
                            <div class="pool-card-val-row"
                                style="display: flex; align-items: baseline; justify-content: space-between;">
                                <span class="pool-card-val-text">{{ $mQual['current_directs'] }}</span>
                                <span class="pool-card-target-text">/ 15 Directs ($100+)</span>
                            </div>
                            <div class="pool-card-progress-track">
                                <div class="pool-card-progress-bar-fill"
                                    style="width: {{ min(100, round(($mQual['current_directs'] / 15) * 100)) }}%; background: linear-gradient(90deg, #F5A623, #00FF88);">
                                </div>
                            </div>
                            @if($mQual['current_directs'] >= 15)
                                <div class="pool-card-status-note qualified">
                                    <i class="fas fa-circle-check"></i> 15 Directs Done
                                </div>
                            @else
                                <div class="pool-card-status-note">
                                    <i class="fas fa-lock"></i> Need
                                    <strong>{{ max(0, 15 - $mQual['current_directs']) }}</strong> Directs
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card 4: Monthly Pool Volume (2.0%) -->
                    <div class="pool-standalone-card-2x2 {{ $legsDone ? 'qualified' : '' }}"
                        onclick="window.location.href='{{ url('/User/PoolIncome') }}'">
                        <!-- 1. Top Bar -->
                        <div class="pool-card-top-bar">
                            <div class="pool-card-icon-bubble">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="pool-card-status-badge {{ $legsDone ? 'qualified' : 'locked' }}">
                                {{ $legsDone ? 'QUALIFIED' : 'LOCKED' }}
                            </span>
                        </div>

                        <!-- 2. Full Heading -->
                        <div class="pool-card-heading-box">
                            <div class="pool-card-main-title">MONTHLY (VOL)</div>
                            <div class="pool-card-dividend-pill">2.0% TEAM VOLUME</div>
                        </div>

                        <!-- 3. Telemetry: Pool Fund, Est Share, Eligible, Countdown -->
                        <div class="pool-card-telemetry-box">
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl fund">
                                    <i class="fas fa-coins" style="color: #FFD700;"></i> Pool Fund
                                </span>
                                <span class="pool-telemetry-val fund">
                                    ${{ number_format($thisMonthVolPoolFund, 2) }}
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl share">
                                    <i class="fas fa-hand-holding-dollar" style="color: #00FF88;"></i> Est. Share
                                </span>
                                <span class="pool-telemetry-val share">
                                    ${{ number_format($thisMonthVolPerUserShare, 2) }} <small
                                        style="font-size:7px; color:#A0AEC0;">/usr</small>
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl eligible">
                                    <i class="fas fa-users" style="color: #00D2FF;"></i> Eligible
                                </span>
                                <span class="pool-telemetry-val eligible">
                                    <strong>{{ $globalMonthlyVolEligible }}</strong> Users
                                </span>
                            </div>
                            <div class="pool-telemetry-row-item">
                                <span class="pool-telemetry-lbl timer">
                                    <i class="fas fa-clock" style="color: #FFB300;"></i> Ends In
                                </span>
                                <span class="pool-telemetry-val timer pool-countdown-val"
                                    data-pool-timer="{{ $monthlyPoolEndMs }}">
                                    --d : --h : --m
                                </span>
                            </div>
                        </div>

                        <!-- 4. Body -->
                        <div class="pool-card-body-block">
                            <div class="pool-card-val-row"
                                style="display: flex; align-items: baseline; justify-content: space-between;">
                                <span class="pool-card-val-text">${{ number_format($mQual['current_power'], 0) }}</span>
                                <span class="pool-card-target-text">/ $5K Power</span>
                            </div>
                            <div class="pool-card-progress-track">
                                <div class="pool-card-progress-bar-fill"
                                    style="width: {{ min(100, round((min($mQual['current_power'] / 5000, $mQual['current_weaker'] / 5000)) * 100)) }}%; background: linear-gradient(90deg, #F5A623, #00FF88);">
                                </div>
                            </div>
                            @if($legsDone)
                                <div class="pool-card-status-note qualified">
                                    <i class="fas fa-circle-check"></i> Volume Done ($5K/$5K)
                                </div>
                            @else
                                <div class="pool-card-status-note">
                                    <i class="fas fa-lock"></i> Weak:
                                    <strong>${{ number_format($mQual['current_weaker'], 0) }}</strong> / $5K
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ============================================================
                 3B. REWARDS & EARNINGS — 4 WORKING INCOME STREAMS
                 ============================================================ -->
                @php
                    $workingIncomeSum = (float) ($directIncome + $levelIncomeTotal + $totalPoolIncome + $totalRankIncome);
                @endphp
                <!-- 1. Central Section Header with Left & Right Lines -->
                <div class="hud-section-divider-bar">
                    <div class="hud-divider-line left"></div>
                    <div class="hud-divider-title-chip">
                        <div class="hud-divider-icon">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                        <span class="hud-divider-title-text">WORKING REWARDS</span>
                        <span class="hud-divider-badge">${{ number_format($workingIncomeSum, 2) }} TOTAL</span>
                    </div>
                    <div class="hud-divider-line right"></div>
                </div>

                <!-- 2. Standalone 4 Working Streams Grid (2x2) -->
                <div class="incomes-standalone-grid">
                    <!-- 1. Direct Referral Bonus -->
                    <a href="{{ url('/User/DirectBonus') }}" class="income-stream-standalone-card stream-direct">
                        <div class="stream-card-top">
                            <div class="stream-icon-bubble icon-gold">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <span class="stream-status-pill pill-gold">5% DIRECT</span>
                        </div>
                        <div class="stream-card-heading">
                            <div class="stream-card-title">DIRECT BONUS</div>
                            <div class="stream-card-sub">Direct Sponsor Reward</div>
                        </div>
                        <div class="stream-card-body">
                            <div class="stream-card-val-row">
                                <span class="stream-card-val val-gold">${{ number_format($directIncome, 2) }}</span>
                                <span class="stream-card-usd">USDT</span>
                            </div>
                        </div>
                    </a>

                    <!-- 2. Level Income -->
                    <a href="{{ url('/User/StakingReferralReward') }}"
                        class="income-stream-standalone-card stream-level">
                        <div class="stream-card-top">
                            <div class="stream-icon-bubble icon-purple">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <span class="stream-status-pill pill-purple">15 LEVELS</span>
                        </div>
                        <div class="stream-card-heading">
                            <div class="stream-card-title">LEVEL INCOME</div>
                            <div class="stream-card-sub">Team Unilevel Bonus</div>
                        </div>
                        <div class="stream-card-body">
                            <div class="stream-card-val-row">
                                <span
                                    class="stream-card-val val-purple">${{ number_format($levelIncomeTotal, 2) }}</span>
                                <span class="stream-card-usd">USDT</span>
                            </div>
                        </div>
                    </a>

                    <!-- 3. Global Pool Income -->
                    <a href="{{ url('/User/PoolIncome') }}" class="income-stream-standalone-card stream-pool">
                        <div class="stream-card-top">
                            <div class="stream-icon-bubble icon-green">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="stream-status-pill pill-green">5% PROTOCOL</span>
                        </div>
                        <div class="stream-card-heading">
                            <div class="stream-card-title">GLOBAL POOL</div>
                            <div class="stream-card-sub">Daily, Weekly &amp; Monthly</div>
                        </div>
                        <div class="stream-card-body">
                            <div class="stream-card-val-row">
                                <span class="stream-card-val val-green">${{ number_format($totalPoolIncome, 2) }}</span>
                                <span class="stream-card-usd">USDT</span>
                            </div>
                        </div>
                    </a>

                    <!-- 4. Rank & Milestone Reward -->
                    <a href="{{ url('/User/RankIncome') }}"
                        class="income-stream-standalone-card stream-rank">
                        <div class="stream-card-top">
                            <div class="stream-icon-bubble icon-amber">
                                <i class="fas fa-crown"></i>
                            </div>
                            <span class="stream-status-pill pill-amber">V1 - V8 RANKS</span>
                        </div>
                        <div class="stream-card-heading">
                            <div class="stream-card-title">RANK &amp; MILESTONES</div>
                            <div class="stream-card-sub">Weekly Leadership &amp; Turnover Rewards</div>
                        </div>
                        <div class="stream-card-body">
                            <div class="stream-card-val-row">
                                <span class="stream-card-val val-amber">${{ number_format($totalRankIncome, 2) }}</span>
                                <span class="stream-card-usd">USDT</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- ============================================================
             4. DUAL BOOSTER ACCELERATOR HUBS (1.0% & 1.5% DAILY)
             ============================================================ -->
                <div class="booster-hub-wrap">
                    <div class="section-bar-header"
                        style="margin-bottom: 6px; padding: 0; justify-content: center; text-align: center;">
                        <div class="sec-title-txt"
                            style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 11.5px; font-weight: 800; letter-spacing: 0.6px;">
                            <i class="fas fa-bolt-lightning" style="color: #FFD700; font-size: 10.5px;"></i> STAKING
                            BOOSTER
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
                                        <span class="booster-status-pill inactive"><i class="fas fa-xmark"></i>
                                            INACTIVE</span>
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
                                        <span class="booster-status-pill inactive"><i class="fas fa-xmark"></i>
                                            INACTIVE</span>
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
                                        <span class="cap-meta-lbl"><i class="fas fa-arrow-trend-up"></i> CAPPING
                                            USED</span>
                                        <span class="cap-meta-val"><strong id="cappingEarnedTxt"
                                                data-target="{{ $cappingConsumed }}">$0</strong>
                                            <small>USDT</small></span>
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
                                        <span
                                            class="leg-stat-val gold">${{ number_format($legStats['power_leg'], 2) }}</span>
                                        <span class="leg-unit">USDT</span>
                                    </div>
                                </div>
                                <div class="leg-stat-box weaker-leg">
                                    <div class="leg-stat-top">
                                        <span class="leg-stat-lbl"><i class="fas fa-bolt-lightning"></i> WEAKER
                                            LEG</span>
                                        <span class="leg-badge green">TARGET</span>
                                    </div>
                                    <div class="leg-val-wrap">
                                        <span
                                            class="leg-stat-val green">${{ number_format($legStats['weaker_leg'], 2) }}</span>
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
                                            <span><strong>${{ number_format($nr['power_leg_turnover'], 0) }}</strong> Leg
                                                Vol</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                                        <div class="capping-main-title" style="color: #FDE68A;">RANK INCOME (V1 TO V8)
                                        </div>
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
                                        <span class="pool-box-lbl"><i class="fas fa-bolt" style="color: #F59E0B;"></i>
                                            POWER
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
                                            <span class="pool-box-target">/
                                                ${{ number_format($rankQual['next_pl_req'], 0) }}
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
                                            <span class="pool-box-target">/
                                                ${{ number_format($rankQual['next_wl_req'], 0) }}
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
                        <div class="team-stat-tile team-gold"
                            onclick="window.location.href='{{ url('/User/DirectTeam') }}'">
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
                        <div class="team-stat-tile team-cyan"
                            onclick="window.location.href='{{ url('/User/AllTeam') }}'">
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
                        <div class="team-stat-tile team-gold"
                            onclick="window.location.href='{{ url('/User/TeamSummary') }}'">
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
                        <div class="team-stat-tile team-purple"
                            onclick="window.location.href='{{ url('/User/TeamSummary') }}'">
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
                                        <span class="{{ $act->status_cls }}">{{ $act->status }} <i
                                                class="fas fa-circle-check" style="font-size:8px;"></i></span>
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
            initPoolCountdowns();
            initRealtimeStreamingYield();
            initLiveMiningStatsPoller();
        });

        /* ============================================================
           REAL-TIME STREAMING YIELD ACCRUAL ENGINE (04:30 AM CRON CYCLE)
           ============================================================ */
        function initRealtimeStreamingYield() {
            const dailyExpectedCai = {{ (float) $dailyExpectedRoiCai }};
            const dailyExpectedUsd = {{ (float) $dailyExpectedRoiUsdt }};
            const cycleStartMs = {{ $lastCronMs }};
            const cycleEndMs = {{ $nextCronMs }};

            const liveUsdEl = document.getElementById('dashLiveAccruedUsd');
            const liveCaiEl = document.getElementById('dashLiveAccruedCai');
            const countdownEl = document.getElementById('dashCronCountdownTxt');

            if (!liveUsdEl && !liveCaiEl) return;

            function updateStream() {
                const now = Date.now();
                const elapsedSec = Math.max(0, (now - cycleStartMs) / 1000);
                const fraction = Math.min(1.0, elapsedSec / 86400);

                const currentAccruedUsd = dailyExpectedUsd * fraction;
                const currentAccruedCai = dailyExpectedCai * fraction;

                if (liveUsdEl) {
                    liveUsdEl.innerText = '$' + currentAccruedUsd.toLocaleString(undefined, { minimumFractionDigits: 4, maximumFractionDigits: 4 });
                }
                if (liveCaiEl) {
                    liveCaiEl.innerText = currentAccruedCai.toLocaleString(undefined, { minimumFractionDigits: 4, maximumFractionDigits: 4 });
                }
                if (countdownEl) {
                    const diff = Math.max(0, cycleEndMs - now);
                    const totalSec = Math.floor(diff / 1000);
                    const hours = Math.floor((totalSec % 86400) / 3600);
                    const mins = Math.floor((totalSec % 3600) / 60);
                    const secs = totalSec % 60;
                    const pad = (n) => String(n).padStart(2, '0');
                    countdownEl.innerText = `${pad(hours)}h : ${pad(mins)}m : ${pad(secs)}s`;
                }

                requestAnimationFrame(updateStream);
            }

            requestAnimationFrame(updateStream);
        }

        /* ============================================================
           REAL-TIME LIVE POOL COUNTDOWN TIMERS ENGINE
           ============================================================ */
        function initPoolCountdowns() {
            const timerEls = document.querySelectorAll('[data-pool-timer]');
            if (!timerEls.length) return;

            function updateTimers() {
                const now = Date.now();
                timerEls.forEach(el => {
                    const targetMs = parseInt(el.getAttribute('data-pool-timer'), 10);
                    if (!targetMs) return;

                    const diff = Math.max(0, targetMs - now);
                    const totalSeconds = Math.floor(diff / 1000);
                    const days = Math.floor(totalSeconds / 86400);
                    const hours = Math.floor((totalSeconds % 86400) / 3600);
                    const minutes = Math.floor((totalSeconds % 3600) / 60);
                    const seconds = totalSeconds % 60;

                    const pad = (n) => String(n).padStart(2, '0');

                    if (days > 0) {
                        el.innerText = `${days}d ${pad(hours)}h ${pad(minutes)}m`;
                    } else {
                        el.innerText = `${pad(hours)}h ${pad(minutes)}m ${pad(seconds)}s`;
                    }
                });
            }

            updateTimers();
            setInterval(updateTimers, 1000);
        }

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
                setTimeout(function () {
                    if (btnText) btnText.innerText = 'COPY LINK';
                }, 2000);
            }
        }

        /* ============================================================
           DASHBOARD 1-CLICK AUTO MINE & DEX SELL ENGINE HANDLERS
           ============================================================ */
        let DASH_UNMINED_USDT = {{ (float) $unminedRoiUsdt }};
        let DASH_CAI_PRICE = {{ (float) $caiPrice }};
        let DASH_ELIGIBLE_SELL_CAI = {{ (float) $eligibleSellCai }};
        let DASH_ELIGIBLE_SELL_USDT = {{ (float) $eligibleSellUsdt }};
        let DASH_PROTOCOL_HOLDING_CAI = {{ (float) $protocolHoldingCai }};
        const DASH_MINING_ENGINE = "{{ $miningEngineContract }}";

        const DASH_MINING_ABI = [
            "function sellPortfolio(uint256 caiAmount, uint256 minUsdtOut, uint256 nonce, uint256 expiry, bytes calldata signature) external returns (uint256)"
        ];

        /* Real-Time Poller for DEX Price & Dynamic Metrics */
        async function fetchLiveMiningStats() {
            try {
                const resp = await fetch('{{ url("/User/Mining/Stats") }}');
                const json = await resp.json();
                if (json.status === 'success' && json.data) {
                    const d = json.data;
                    DASH_UNMINED_USDT = parseFloat(d.unmined_usdt) || 0;
                    DASH_CAI_PRICE = parseFloat(d.live_cai_price) || 1.0;
                    DASH_ELIGIBLE_SELL_CAI = parseFloat(d.max_sellable_cai) || 0;
                    DASH_ELIGIBLE_SELL_USDT = parseFloat(d.max_sellable_usdt) || 0;
                    DASH_PROTOCOL_HOLDING_CAI = parseFloat(d.holding_cai_balance) || 0;

                    // 1. Update Header Live Price Badge
                    const hpEl = document.getElementById('dashLiveHeaderPrice');
                    if (hpEl) hpEl.innerText = '$' + DASH_CAI_PRICE.toFixed(6);

                    // 2. Update Step 1 (Mine to Protocol)
                    const uUsdtEl = document.getElementById('dashUnminedUsdtTxt');
                    if (uUsdtEl) uUsdtEl.innerHTML = '$' + DASH_UNMINED_USDT.toFixed(2) + ' <small style="font-size: 0.62rem; color: #FFFFFF; font-weight: 700;">USDT</small>';

                    const uCaiEl = document.getElementById('dashUnminedCaiYieldTxt');
                    if (uCaiEl) uCaiEl.innerText = '≈ ' + (parseFloat(d.estimated_cai_to_mine) || 0).toFixed(4) + ' CAI';

                    const btnMine = document.getElementById('btnDashMineAll');
                    const btnMineTxt = document.getElementById('btnDashMineAllTxt');
                    if (btnMine && btnMineTxt) {
                        if (DASH_UNMINED_USDT > 0) {
                            btnMine.disabled = false;
                            btnMine.style.opacity = '1';
                            btnMine.style.cursor = 'pointer';
                            btnMineTxt.innerText = 'MINE ALL ($' + DASH_UNMINED_USDT.toFixed(2) + ')';
                        } else {
                            btnMine.disabled = true;
                            btnMine.style.opacity = '0.4';
                            btnMine.style.cursor = 'not-allowed';
                            btnMineTxt.innerText = 'MINE ALL ($0.00)';
                        }
                    }

                    // 3. Update Step 2 (Sell on DEX)
                    const pHoldEl = document.getElementById('dashProtocolCaiTxt');
                    if (pHoldEl) {
                        pHoldEl.innerHTML = DASH_PROTOCOL_HOLDING_CAI.toFixed(4) + ' <small style="font-size: 0.62rem; color: #FFFFFF; font-weight: 700;">CAI</small> <small style="font-size: 0.66rem; color: #8E99A8;">($' + (parseFloat(d.holding_value_usdt) || 0).toFixed(2) + ')</small>';
                    }

                    const sLimEl = document.getElementById('dashSellableLimitTxt');
                    if (sLimEl) {
                        sLimEl.innerHTML = '$' + DASH_ELIGIBLE_SELL_USDT.toFixed(2) + ' USDT <small style="color: #8E99A8;">(' + DASH_ELIGIBLE_SELL_CAI.toFixed(2) + ' CAI)</small>';
                    }

                    const wRecEl = document.getElementById('dashEstWalletUsdtTxt');
                    if (wRecEl) {
                        wRecEl.innerHTML = '≈ $' + DASH_ELIGIBLE_SELL_USDT.toFixed(2) + ' <small style="font-size: 0.60rem; color: #A0AEC0;">USDT</small>';
                    }

                    const btnSell = document.getElementById('btnDashSellAll');
                    const btnSellTxt = document.getElementById('btnDashSellAllTxt');
                    if (btnSell && btnSellTxt) {
                        if (DASH_ELIGIBLE_SELL_USDT > 0) {
                            btnSell.disabled = false;
                            btnSell.style.opacity = '1';
                            btnSell.style.cursor = 'pointer';
                            btnSellTxt.innerText = 'SELL ALL ELIGIBLE ($' + DASH_ELIGIBLE_SELL_USDT.toFixed(2) + ')';
                        } else {
                            btnSell.disabled = true;
                            btnSell.style.opacity = '0.4';
                            btnSell.style.cursor = 'not-allowed';
                            btnSellTxt.innerText = 'SELL ALL ELIGIBLE ($0.00)';
                        }
                    }
                }
            } catch (e) {
                // Silently handle background poller
            }
        }

        function initLiveMiningStatsPoller() {
            fetchLiveMiningStats();
            setInterval(fetchLiveMiningStats, 7000);
        }

        /* 1-Click Mine All Accumulated ROI */
        async function executeDashAutoMine() {
            const btn = document.getElementById('btnDashMineAll');
            const btnTxt = document.getElementById('btnDashMineAllTxt');

            const unminedUsdt = typeof DASH_UNMINED_USDT !== 'undefined' ? DASH_UNMINED_USDT : 0;
            const livePrice = typeof DASH_CAI_PRICE !== 'undefined' && DASH_CAI_PRICE > 0 ? DASH_CAI_PRICE : 1.0;
            const estCai = unminedUsdt > 0 ? (unminedUsdt / livePrice).toFixed(4) : '0.0000';

            const result = await Swal.fire({
                title: 'MINE ALL ROI TO CAI',
                html: `
                    <div style="width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 50%; background: radial-gradient(circle, rgba(255, 215, 0, 0.22) 0%, rgba(245, 166, 35, 0.04) 70%); border: 1px solid rgba(255, 215, 0, 0.45); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 18px rgba(255, 215, 0, 0.25);">
                        <i class="fas fa-coins" style="color: #FFD700; font-size: 20px;"></i>
                    </div>
                    <div style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left; font-family: 'Inter', sans-serif;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; font-size: 0.78rem;">
                            <span style="color: #8C9BAE;">Unmined Daily ROI:</span>
                            <strong style="color: #FFD700; font-family: 'Space Mono', monospace; font-size: 0.85rem;">$${unminedUsdt.toFixed(2)} USDT</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; font-size: 0.78rem;">
                            <span style="color: #8C9BAE;">Live DEX Rate:</span>
                            <strong style="color: #FFE082; font-family: 'Space Mono', monospace; font-size: 0.80rem;">1 CAI = $${livePrice.toFixed(6)}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.80rem; border-top: 1px solid rgba(245, 166, 35, 0.18); padding-top: 7px;">
                            <span style="color: #CBD5E1; font-weight: 600;">Estimated CAI Yield:</span>
                            <strong style="color: #00FF88; font-family: 'Space Mono', monospace; font-size: 0.88rem;">≈ ${estCai} CAI</strong>
                        </div>
                    </div>
                    <div style="font-size: 0.72rem; color: #00FF88; background: rgba(0, 255, 136, 0.08); border: 1px solid rgba(0, 255, 136, 0.25); border-radius: 8px; padding: 8px 12px; text-align: center; font-weight: 600; line-height: 1.4;">
                        ✓ 0% Deposit Capping is deducted during mining.
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'CONFIRM MINE',
                cancelButtonText: 'CANCEL',
                buttonsStyling: false,
                background: '#080A10',
                customClass: {
                    popup: 'mecha-swal-popup',
                    title: 'mecha-swal-title',
                    htmlContainer: 'mecha-swal-html',
                    actions: 'mecha-swal-actions',
                    confirmButton: 'mecha-swal-confirm',
                    cancelButton: 'mecha-swal-cancel'
                }
            });

            if (!result.isConfirmed) return;

            try {
                if (btn) btn.disabled = true;
                if (btnTxt) btnTxt.innerText = 'MINING IN PROGRESS...';

                const resp = await fetch('{{ url("/User/Mining/MineCAI") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await resp.json();

                if (data.status === 'success') {
                    await Swal.fire({
                        title: 'MINING SUCCESSFUL!',
                        html: `
                            <div style="width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 50%; background: radial-gradient(circle, rgba(0, 255, 136, 0.22) 0%, rgba(0, 255, 136, 0.04) 70%); border: 1px solid rgba(0, 255, 136, 0.45); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 18px rgba(0, 255, 136, 0.25);">
                                <i class="fas fa-check" style="color: #00FF88; font-size: 20px;"></i>
                            </div>
                            <div style="font-size: 0.82rem; color: #CBD5E1; margin-bottom: 12px; text-align: center;">${data.message}</div>
                            <div style="background: rgba(0, 255, 136, 0.08); border: 1px solid rgba(0, 255, 136, 0.25); border-radius: 10px; padding: 10px 14px; font-family: 'Space Mono', monospace; color: #00FF88; font-weight: 700; font-size: 0.88rem; text-align: center;">
                                New Balance: ${data.data.new_cai_balance} CAI
                            </div>
                        `,
                        confirmButtonText: 'GREAT',
                        buttonsStyling: false,
                        background: '#080A10',
                        customClass: {
                            popup: 'mecha-swal-popup',
                            title: 'mecha-swal-title',
                            htmlContainer: 'mecha-swal-html',
                            actions: 'mecha-swal-actions',
                            confirmButton: 'mecha-swal-confirm'
                        }
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        title: 'MINING FAILED',
                        html: `<div style="font-size: 0.80rem; color: #EF4444; margin-bottom: 8px;">${data.message || 'Error executing mining.'}</div>`,
                        confirmButtonText: 'CLOSE',
                        buttonsStyling: false,
                        background: '#080A10',
                        customClass: {
                            popup: 'mecha-swal-popup',
                            title: 'mecha-swal-title',
                            htmlContainer: 'mecha-swal-html',
                            actions: 'mecha-swal-actions',
                            confirmButton: 'mecha-swal-confirm'
                        }
                    });
                    if (btn) btn.disabled = false;
                    if (btnTxt) btnTxt.innerText = 'MINE ALL (${{ number_format($unminedRoiUsdt, 2) }})';
                }
            } catch (e) {
                Swal.fire({
                    title: 'ERROR',
                    html: `<div style="font-size: 0.80rem; color: #EF4444; margin-bottom: 8px;">${e.message || 'Network exception occurred.'}</div>`,
                    confirmButtonText: 'CLOSE',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        confirmButton: 'mecha-swal-confirm'
                    }
                });
                if (btn) btn.disabled = false;
                if (btnTxt) btnTxt.innerText = 'MINE ALL (${{ number_format($unminedRoiUsdt, 2) }})';
            }
        }

        /* ============================================================
           EIP-6963 & MULTI-EXTENSION WEB3 PROVIDER RESOLVER
           Strict MetaMask Priority & Phantom Override Prevention
           ============================================================ */
        const EIP6963_MAP = new Map();
        if (typeof window !== 'undefined') {
            window.addEventListener("eip6963:announceProvider", (event) => {
                if (!event || !event.detail) return;
                const { info, provider } = event.detail;
                const rdns = (info.rdns || info.name || '').toLowerCase();
                EIP6963_MAP.set(rdns, provider);
                if (rdns.includes('metamask') || rdns === 'io.metamask') EIP6963_MAP.set('metamask', provider);
                if (rdns.includes('trust') || rdns === 'com.trustwallet.app') EIP6963_MAP.set('trust', provider);
                if (rdns.includes('binance') || rdns === 'com.binance') EIP6963_MAP.set('binance', provider);
                if (rdns.includes('okx') || rdns === 'com.okx.wallet') EIP6963_MAP.set('okx', provider);
            });
            try {
                window.dispatchEvent(new Event("eip6963:requestProvider"));
            } catch (e) {}
        }

        function getPureMetaMaskProvider() {
            // 1. EIP-6963 Pure MetaMask Announcement
            if (EIP6963_MAP.has('metamask')) return EIP6963_MAP.get('metamask');
            if (EIP6963_MAP.has('io.metamask')) return EIP6963_MAP.get('io.metamask');

            // 2. window.ethereum.providers array (strictly exclude Phantom, Brave, TokenPocket)
            if (typeof window.ethereum !== 'undefined') {
                if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                    const mm = window.ethereum.providers.find(p => p.isMetaMask && !p.isPhantom && !p.isBraveWallet && !p.isTokenPocket);
                    if (mm) return mm;
                }
                // 3. Direct window.ethereum only if NOT Phantom
                if (window.ethereum.isMetaMask && !window.ethereum.isPhantom) {
                    return window.ethereum;
                }
                // 4. Detected array
                if (window.ethereum.detected && Array.isArray(window.ethereum.detected)) {
                    const mm = window.ethereum.detected.find(p => p.isMetaMask && !p.isPhantom);
                    if (mm) return mm;
                }
            }
            return null;
        }

        function getPureTrustProvider() {
            if (EIP6963_MAP.has('trust')) return EIP6963_MAP.get('trust');
            if (window.trustwallet) return window.trustwallet;
            if (typeof window.ethereum !== 'undefined') {
                if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                    const t = window.ethereum.providers.find(p => (p.isTrust || p.isTrustWallet) && !p.isPhantom);
                    if (t) return t;
                }
                if (window.ethereum.isTrust || window.ethereum.isTrustWallet) return window.ethereum;
            }
            return null;
        }

        function getPureBinanceProvider() {
            if (EIP6963_MAP.has('binance')) return EIP6963_MAP.get('binance');
            if (window.BinanceChain) return window.BinanceChain;
            if (window.binance) return window.binance;
            if (typeof window.ethereum !== 'undefined' && window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                const b = window.ethereum.providers.find(p => p.isBinance);
                if (b) return b;
            }
            return null;
        }

        function getPureOkxProvider() {
            if (EIP6963_MAP.has('okx')) return EIP6963_MAP.get('okx');
            if (window.okxwallet) return window.okxwallet;
            if (typeof window.ethereum !== 'undefined' && window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                const o = window.ethereum.providers.find(p => p.isOkxWallet);
                if (o) return o;
            }
            return null;
        }

        /* Helper to resolve Web3 Provider (Seamless on Mobile, Extension Picker on Desktop) */
        async function resolveWeb3Provider() {
            const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent);

            // 1. MOBILE DAPP BROWSER: Directly use active injected provider without popup
            if (isMobile) {
                const mobileProvider = getPureMetaMaskProvider() || window.trustwallet || window.safepal || window.okxwallet || window.binance || (window.bitkeep && window.bitkeep.ethereum) || window.ethereum;
                if (mobileProvider) return mobileProvider;

                Swal.fire({
                    title: 'DAPP BROWSER REQUIRED',
                    html: `
                        <div style="font-size: 0.80rem; color: #94A3B8; margin-bottom: 8px; text-align: center;">
                            No Web3 wallet detected.
                        </div>
                        <div style="font-size: 0.74rem; color: #8C9BAE; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 8px 10px; text-align: center; line-height: 1.4;">
                            Please open this website inside your mobile wallet app (MetaMask, Trust Wallet, Bitget, or OKX DApp Browser).
                        </div>
                    `,
                    confirmButtonText: 'UNDERSTOOD',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        confirmButton: 'mecha-swal-confirm'
                    }
                });
                return null;
            }

            // 2. DESKTOP / WEB BROWSER: Check installed extensions
            const hasMetaMask = !!getPureMetaMaskProvider();
            const hasTrust = !!getPureTrustProvider();
            const hasBinance = !!getPureBinanceProvider();
            const hasOkx = !!getPureOkxProvider();
            const hasGeneric = !!(window.ethereum);

            // Show Desktop Web3 Wallet Selector Modal
            const walletChoice = await new Promise((resolve) => {
                Swal.fire({
                    title: 'SELECT WEB3 WALLET',
                    html: `
                        <div style="font-size: 0.76rem; color: #8E99A8; margin-bottom: 12px; text-align: center;">
                            Choose your connected browser wallet extension to sign and execute DEX swap.
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px; text-align: left;">
                            
                            <!-- 1. METAMASK (Priority) -->
                            <div onclick="window.__selectWeb3Choice('metamask')" class="mecha-wallet-choice-item" style="border: 1px solid ${hasMetaMask ? 'rgba(255, 215, 0, 0.4)' : 'rgba(245, 166, 35, 0.22)'};">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(246, 133, 27, 0.15); border: 1px solid rgba(246, 133, 27, 0.35); display: flex; align-items: center; justify-content: center; font-size: 14px; color: #F6851B; flex-shrink: 0;">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.84rem; font-weight: 800; color: #FFD700; display: flex; align-items: center; gap: 6px;">
                                            MetaMask
                                            <span style="font-size: 0.52rem; padding: 1px 4px; border-radius: 3px; background: rgba(255, 215, 0, 0.15); color: #FFD700; border: 1px solid rgba(255, 215, 0, 0.35); font-weight: 800;">PRIORITY</span>
                                        </div>
                                        <div style="font-size: 0.60rem; color: #8E99A8;">MetaMask Browser Extension</div>
                                    </div>
                                </div>
                                <span style="font-size: 0.58rem; padding: 2px 6px; border-radius: 4px; font-weight: 700; ${hasMetaMask ? 'background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3);' : 'background: rgba(255, 255, 255, 0.05); color: #64748B;'}">
                                    ${hasMetaMask ? 'DETECTED' : 'EXTENSION'}
                                </span>
                            </div>

                            <!-- 2. TRUST WALLET -->
                            <div onclick="window.__selectWeb3Choice('trust')" class="mecha-wallet-choice-item">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(5, 0, 255, 0.15); border: 1px solid rgba(5, 0, 255, 0.35); display: flex; align-items: center; justify-content: center; font-size: 13px; color: #3375BB; flex-shrink: 0;">
                                        <i class="fas fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.82rem; font-weight: 800; color: #FFFFFF;">Trust Wallet</div>
                                        <div style="font-size: 0.60rem; color: #8E99A8;">Web3 Extension</div>
                                    </div>
                                </div>
                                <span style="font-size: 0.58rem; padding: 2px 6px; border-radius: 4px; font-weight: 700; ${hasTrust ? 'background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3);' : 'background: rgba(255, 255, 255, 0.05); color: #64748B;'}">
                                    ${hasTrust ? 'DETECTED' : 'EXTENSION'}
                                </span>
                            </div>

                            <!-- 3. BINANCE WALLET -->
                            <div onclick="window.__selectWeb3Choice('binance')" class="mecha-wallet-choice-item">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(243, 186, 47, 0.15); border: 1px solid rgba(243, 186, 47, 0.35); display: flex; align-items: center; justify-content: center; font-size: 13px; color: #F3BA2F; flex-shrink: 0;">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.82rem; font-weight: 800; color: #FFFFFF;">Binance Wallet</div>
                                        <div style="font-size: 0.60rem; color: #8E99A8;">BNB Chain & Web3</div>
                                    </div>
                                </div>
                                <span style="font-size: 0.58rem; padding: 2px 6px; border-radius: 4px; font-weight: 700; ${hasBinance ? 'background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3);' : 'background: rgba(255, 255, 255, 0.05); color: #64748B;'}">
                                    ${hasBinance ? 'DETECTED' : 'EXTENSION'}
                                </span>
                            </div>

                            <!-- 4. OKX WALLET -->
                            <div onclick="window.__selectWeb3Choice('okx')" class="mecha-wallet-choice-item">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; font-size: 13px; color: #FFFFFF; flex-shrink: 0;">
                                        <i class="fas fa-globe"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.82rem; font-weight: 800; color: #FFFFFF;">OKX Wallet</div>
                                        <div style="font-size: 0.60rem; color: #8E99A8;">Web3 Portal</div>
                                    </div>
                                </div>
                                <span style="font-size: 0.58rem; padding: 2px 6px; border-radius: 4px; font-weight: 700; ${hasOkx ? 'background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3);' : 'background: rgba(255, 255, 255, 0.05); color: #64748B;'}">
                                    ${hasOkx ? 'DETECTED' : 'EXTENSION'}
                                </span>
                            </div>

                            <!-- 5. DEFAULT INJECTED / OTHER -->
                            <div onclick="window.__selectWeb3Choice('injected')" class="mecha-wallet-choice-item">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255, 215, 0, 0.15); border: 1px solid rgba(255, 215, 0, 0.35); color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0;">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.82rem; font-weight: 800; color: #FFFFFF;">Other Web3 Wallet</div>
                                        <div style="font-size: 0.60rem; color: #8E99A8;">Injected Browser Provider</div>
                                    </div>
                                </div>
                                <span style="font-size: 0.58rem; padding: 2px 6px; border-radius: 4px; font-weight: 700; ${hasGeneric ? 'background: rgba(0, 255, 136, 0.12); color: #00FF88; border: 1px solid rgba(0, 255, 136, 0.3);' : 'background: rgba(255, 255, 255, 0.05); color: #64748B;'}">
                                    ${hasGeneric ? 'READY' : 'OTHER'}
                                </span>
                            </div>

                        </div>
                    `,
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'CANCEL',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        cancelButton: 'mecha-swal-cancel'
                    },
                    didOpen: () => {
                        window.__selectWeb3Choice = (choice) => {
                            Swal.close();
                            resolve(choice);
                        };
                    },
                    willClose: () => {
                        delete window.__selectWeb3Choice;
                        resolve(null);
                    }
                });
            });

            if (!walletChoice) return null;

            // Resolve exact provider instance (Strictly avoiding Phantom)
            if (walletChoice === 'metamask') {
                const mm = getPureMetaMaskProvider();
                if (mm) return mm;

                Swal.fire({
                    title: 'METAMASK EXTENSION REQUIRED',
                    html: `
                        <div style="font-size: 0.80rem; color: #CBD5E1; margin-bottom: 10px; text-align: center;">
                            MetaMask extension was not detected or is being overridden.
                        </div>
                        <div style="font-size: 0.74rem; color: #8C9BAE; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 10px; text-align: center; line-height: 1.4; margin-bottom: 12px;">
                            Please make sure the MetaMask extension is active in your browser.
                        </div>
                        <div style="text-align: center;">
                            <a href="https://metamask.io/download/" target="_blank" style="color: #FFD700; font-weight: 700; text-decoration: underline; font-size: 0.78rem;">
                                Download MetaMask Extension →
                            </a>
                        </div>
                    `,
                    confirmButtonText: 'CLOSE',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        confirmButton: 'mecha-swal-confirm'
                    }
                });
                return null;
            } else if (walletChoice === 'trust') {
                const t = getPureTrustProvider();
                if (t) return t;
                return window.ethereum && !window.ethereum.isPhantom ? window.ethereum : null;
            } else if (walletChoice === 'binance') {
                const b = getPureBinanceProvider();
                if (b) return b;
                return window.ethereum && !window.ethereum.isPhantom ? window.ethereum : null;
            } else if (walletChoice === 'okx') {
                const o = getPureOkxProvider();
                if (o) return o;
                return window.ethereum && !window.ethereum.isPhantom ? window.ethereum : null;
            } else {
                return getPureMetaMaskProvider() || (window.ethereum && !window.ethereum.isPhantom ? window.ethereum : window.ethereum);
            }
        }

        /* 1-Click Sell All Eligible CAI on DEX */
        async function executeDashAutoSellOnDex() {
            if (DASH_ELIGIBLE_SELL_CAI <= 0 || DASH_ELIGIBLE_SELL_USDT <= 0) {
                Swal.fire({
                    title: 'NO SELLABLE BALANCE',
                    html: `
                        <div style="font-size: 0.80rem; color: #94A3B8; margin-bottom: 8px; text-align: center;">
                            You currently have <strong style="color:#FFD700; font-family:'Space Mono', monospace;">$0.00</strong> eligible sell balance.
                        </div>
                        <div style="font-size: 0.74rem; color: #8C9BAE; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 8px 10px; text-align: center;">
                            Please check your protocol CAI holdings or active capping limit.
                        </div>
                    `,
                    confirmButtonText: 'UNDERSTOOD',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        confirmButton: 'mecha-swal-confirm'
                    }
                });
                return;
            }

            const btn = document.getElementById('btnDashSellAll');
            const btnTxt = document.getElementById('btnDashSellAllTxt');

            const result = await Swal.fire({
                title: 'SELL ON PANCAKESWAP',
                html: `
                    <div style="width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 50%; background: radial-gradient(circle, rgba(245, 166, 35, 0.22) 0%, rgba(217, 119, 6, 0.04) 70%); border: 1px solid rgba(245, 166, 35, 0.45); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 18px rgba(245, 166, 35, 0.25);">
                        <i class="fas fa-arrow-right-arrow-left" style="color: #FFD700; font-size: 19px;"></i>
                    </div>
                    <div style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; text-align: left; font-family: 'Inter', sans-serif;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; font-size: 0.78rem;">
                            <span style="color: #8C9BAE;">CAI to Sell:</span>
                            <strong style="color: #FFD700; font-family: 'Space Mono', monospace; font-size: 0.85rem;">${DASH_ELIGIBLE_SELL_CAI} CAI</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 7px; font-size: 0.78rem;">
                            <span style="color: #8C9BAE;">Gross USDT Received:</span>
                            <strong style="color: #00FF88; font-family: 'Space Mono', monospace; font-size: 0.85rem;">$${DASH_ELIGIBLE_SELL_USDT.toFixed(2)} USDT</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.80rem; border-top: 1px solid rgba(245, 166, 35, 0.18); padding-top: 7px;">
                            <span style="color: #8C9BAE;">Capping Deduction:</span>
                            <strong style="color: #F5A623; font-family: 'Space Mono', monospace; font-size: 0.85rem;">-$${DASH_ELIGIBLE_SELL_USDT.toFixed(2)}</strong>
                        </div>
                    </div>
                    <div style="font-size: 0.72rem; color: #CBD5E1; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 8px 12px; line-height: 1.4; text-align: center;">
                        ✓ Live USDT transferred straight to your connected Web3 wallet.
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'EXECUTE SWAP',
                cancelButtonText: 'CANCEL',
                buttonsStyling: false,
                background: '#080A10',
                customClass: {
                    popup: 'mecha-swal-popup',
                    title: 'mecha-swal-title',
                    htmlContainer: 'mecha-swal-html',
                    actions: 'mecha-swal-actions',
                    confirmButton: 'mecha-swal-confirm',
                    cancelButton: 'mecha-swal-cancel'
                }
            });

            if (!result.isConfirmed) return;

            // 1. Resolve Web3 Provider (Direct on mobile DApp, Selector on desktop Web)
            const chosenRawProvider = await resolveWeb3Provider();
            if (!chosenRawProvider) {
                if (btn) btn.disabled = false;
                if (btnTxt) btnTxt.innerText = 'SELL ALL ELIGIBLE (${{ number_format($eligibleSellUsdt, 2) }})';
                return;
            }

            try {
                if (btn) btn.disabled = true;
                if (btnTxt) btnTxt.innerText = 'CONNECTING WALLET...';

                const provider = new ethers.providers.Web3Provider(chosenRawProvider);
                await provider.send("eth_requestAccounts", []);
                const signer = provider.getSigner();

                // 2. Verify Network (BSC Mainnet: 56)
                const network = await provider.getNetwork();
                if (network.chainId !== 56) {
                    try {
                        await chosenRawProvider.request({
                            method: 'wallet_switchEthereumChain',
                            params: [{ chainId: '0x38' }],
                        });
                    } catch (switchError) {
                        Swal.fire({
                            title: 'SWITCH TO BSC',
                            html: '<div style="font-size: 0.80rem; color: #CBD5E1; text-align: center;">Please switch your wallet network to BNB Smart Chain (BSC Mainnet).</div>',
                            confirmButtonText: 'OK',
                            buttonsStyling: false,
                            background: '#080A10',
                            customClass: {
                                popup: 'mecha-swal-popup',
                                title: 'mecha-swal-title',
                                htmlContainer: 'mecha-swal-html',
                                actions: 'mecha-swal-actions',
                                confirmButton: 'mecha-swal-confirm'
                            }
                        });
                        if (btn) btn.disabled = false;
                        if (btnTxt) btnTxt.innerText = 'SELL ALL ELIGIBLE (${{ number_format($eligibleSellUsdt, 2) }})';
                        return;
                    }
                }

                if (btnTxt) btnTxt.innerText = 'REQUESTING SIGNATURE...';

                // 3. Request EIP-712 Signature from Backend
                const userAccount = await signer.getAddress();
                const sigResp = await fetch('{{ url("/User/Mining/RequestSellSignature") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cai_amount: DASH_ELIGIBLE_SELL_CAI,
                        wallet_address: userAccount
                    })
                });

                const sigData = await sigResp.json();
                if (sigData.status !== 'success') {
                    throw new Error(sigData.message || 'Signature authorization failed.');
                }

                const d = sigData.data;

                // 4. Execute Web3 Transaction on CyeraMiningEngine
                if (btnTxt) btnTxt.innerText = 'CONFIRM IN WALLET...';

                const miningContract = new ethers.Contract(DASH_MINING_ENGINE, DASH_MINING_ABI, signer);

                const tx = await miningContract.sellPortfolio(
                    d.caiAmountWei,
                    d.minUsdtOutWei,
                    d.nonce,
                    d.expiry,
                    d.signature,
                    { gasLimit: 500000 }
                );

                if (btnTxt) btnTxt.innerText = 'WAITING CONFIRMATION...';

                const receipt = await tx.wait(1);

                if (btnTxt) btnTxt.innerText = 'FINALIZING SETTLEMENT...';

                // 5. Confirm to Backend (Deduct Capping FIFO & Log Audit Table)
                const confirmResp = await fetch('{{ url("/User/Mining/ConfirmSell") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tx_hash: receipt.transactionHash,
                        cai_amount: d.caiAmount,
                        usdt_received: d.estimatedCappingUse
                    })
                });

                const confirmData = await confirmResp.json();

                await Swal.fire({
                    title: 'PORTFOLIO SOLD ON DEX!',
                    html: `
                        <div style="width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 50%; background: radial-gradient(circle, rgba(0, 255, 136, 0.22) 0%, rgba(0, 255, 136, 0.04) 70%); border: 1px solid rgba(0, 255, 136, 0.45); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 18px rgba(0, 255, 136, 0.25);">
                            <i class="fas fa-check" style="color: #00FF88; font-size: 20px;"></i>
                        </div>
                        <div style="font-size: 0.82rem; color: #CBD5E1; margin-bottom: 12px; text-align: center; font-family: 'Inter', sans-serif;">
                            Successfully swapped <strong style="color:#FFD700; font-family:'Space Mono', monospace;">${d.caiAmount} CAI</strong> on PancakeSwap!
                        </div>
                        <div style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 10px; padding: 10px 12px; margin-bottom: 12px; text-align: left; font-size: 0.78rem;">
                            <div style="color: #00FF88; margin-bottom: 5px;">✓ Delivered $${d.estimatedCappingUse.toFixed(2)} USDT directly to wallet.</div>
                            <div style="color: #FFD700;">✓ Deducted $${d.estimatedCappingUse.toFixed(2)} from active deposit capping.</div>
                        </div>
                        <div style="text-align: center;">
                            <a href="https://bscscan.com/tx/${receipt.transactionHash}" target="_blank" style="color: #FFD700; font-family: 'Space Mono', monospace; font-size: 0.75rem; text-decoration: underline;">
                                View on BscScan <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                            </a>
                        </div>
                    `,
                    confirmButtonText: 'DONE',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        confirmButton: 'mecha-swal-confirm'
                    }
                });

                window.location.reload();

            } catch (e) {
                console.error(e);
                let errMsg = e.reason || e.data?.message || e.message || 'Swap transaction rejected.';
                Swal.fire({
                    title: 'SWAP FAILED',
                    html: `<div style="font-size: 0.80rem; color: #EF4444; margin-bottom: 8px; text-align: center;">${errMsg}</div>`,
                    confirmButtonText: 'CLOSE',
                    buttonsStyling: false,
                    background: '#080A10',
                    customClass: {
                        popup: 'mecha-swal-popup',
                        title: 'mecha-swal-title',
                        htmlContainer: 'mecha-swal-html',
                        actions: 'mecha-swal-actions',
                        confirmButton: 'mecha-swal-confirm'
                    }
                });
                if (btn) btn.disabled = false;
                if (btnTxt) btnTxt.innerText = 'SELL ALL ELIGIBLE (${{ number_format($eligibleSellUsdt, 2) }})';
            }
        }
    </script>

</body>

</html>