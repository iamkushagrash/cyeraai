@extends('layouts.user-mecha')

@section('title', 'Staking Reward - Cyera AI')
@section('page-title', 'Staking Reward (Daily CPS)')
@section('page-icon', 'fas fa-coins')

@section('content')
<style>
    /* Scoped Clean HUD Container */
    .cps-hud-card {
        padding: 12px 10px;
        background: linear-gradient(160deg, #0A0D15 0%, #05070B 100%);
        border: 1px solid rgba(245, 166, 35, 0.28);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7), 0 0 16px rgba(245, 166, 35, 0.08);
    }

    /* Scoped Header - Seamless Flex Row */
    .cps-card-head {
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        width: 100%;
    }

    .cps-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .cps-head-icon {
        font-size: 1.15rem;
        color: #FFD700;
        filter: drop-shadow(0 0 6px rgba(255, 215, 0, 0.4));
        flex-shrink: 0;
    }

    .cps-head-text {
        display: flex;
        flex-direction: column;
        width: 100%;
        min-width: 0;
    }

    .cps-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        width: 100%;
    }

    .cps-head-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.85rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 0.4px;
        margin: 0;
        line-height: 1.15;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cps-rate-pill {
        font-size: 0.62rem;
        font-weight: 800;
        padding: 1.5px 6px;
        border-radius: 10px;
        background: rgba(245, 166, 35, 0.15);
        border: 1px solid #F5A623;
        color: #FFE082;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .cps-head-subtitle {
        font-size: 0.65rem;
        color: #8E99A8;
        margin-top: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Top Stats Bar — 3 In One Row (Compact) */
    .cps-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        margin-bottom: 10px;
        width: 100%;
    }

    .cps-summary-pill {
        background: rgba(14, 18, 28, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 6px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        justify-content: center;
        min-width: 0;
    }

    .cps-summary-pill.gold {
        border-top: 2px solid #FFD700;
        background: linear-gradient(180deg, rgba(245, 166, 35, 0.14) 0%, rgba(14, 18, 28, 0.85) 100%);
    }

    .cps-summary-pill.cyan {
        border-top: 2px solid #00D2FF;
        background: linear-gradient(180deg, rgba(0, 210, 255, 0.12) 0%, rgba(14, 18, 28, 0.85) 100%);
    }

    .cps-summary-pill.neutral {
        border-top: 2px solid rgba(255, 255, 255, 0.25);
    }

    .cps-summary-lbl {
        font-size: 0.58rem;
        font-weight: 800;
        color: #8E99A8;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 3px;
        line-height: 1;
    }

    .cps-summary-val {
        font-size: 0.95rem;
        font-weight: 900;
        color: #FFFFFF;
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 0.2px;
        line-height: 1.1;
        margin-top: 3px;
        white-space: nowrap;
    }

    .cps-summary-val.gold {
        color: #FFE082;
        text-shadow: 0 0 6px rgba(245, 166, 35, 0.35);
    }

    .cps-summary-val.cyan {
        color: #00D2FF;
    }

    /* Single Row Compact Toolbar */
    .cps-toolbar-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
        width: 100%;
    }

    .cps-show-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.65rem;
        font-weight: 800;
        color: #94A3B8;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .cps-select-box {
        height: 28px;
        background: #090B12;
        border: 1px solid rgba(255, 215, 0, 0.35);
        border-radius: 6px;
        color: #FFD700;
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0 18px 0 6px;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 24 24' fill='none' stroke='%23FFD700' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 5px center;
    }

    .cps-search-wrap {
        position: relative;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }

    .cps-search-wrap i {
        position: absolute;
        left: 8px;
        color: #FFD700;
        font-size: 0.7rem;
        pointer-events: none;
    }

    .cps-search-input {
        width: 100%;
        height: 28px;
        background: #090B12;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 6px;
        color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-size: 0.72rem;
        font-weight: 500;
        padding: 0 8px 0 24px;
        outline: none;
        transition: all 0.2s ease;
    }

    .cps-search-input:focus {
        border-color: #FFD700;
        background: #0E121D;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.25);
    }

    .cps-search-input::placeholder {
        color: #64748B;
        font-size: 0.68rem;
    }

    /* Cards Grid */
    .cps-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 10px;
        margin-bottom: 12px;
    }

    /* Cyber Reward Card */
    .reward-cyber-card {
        background: linear-gradient(145deg, #0D111A 0%, #06080E 100%);
        border: 1px solid rgba(245, 166, 35, 0.25);
        border-radius: 12px;
        padding: 10px 12px;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .reward-cyber-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.6), transparent);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .reward-cyber-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 215, 0, 0.55);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.9), 0 0 14px rgba(245, 166, 35, 0.18);
    }

    .reward-cyber-card:hover::before {
        opacity: 1;
    }

    /* Card Top Header */
    .card-reward-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .reward-index-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.68rem;
        font-weight: 800;
        color: #FFE082;
        background: rgba(245, 166, 35, 0.12);
        border: 1px solid rgba(245, 166, 35, 0.3);
        border-radius: 5px;
        padding: 2px 6px;
    }

    .reward-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .reward-status-pill.success {
        background: rgba(0, 255, 136, 0.12);
        border: 1px solid rgba(0, 255, 136, 0.35);
        color: #00FF88;
        box-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
    }

    .reward-status-pill.locked {
        background: rgba(255, 77, 125, 0.12);
        border: 1px solid rgba(255, 77, 125, 0.35);
        color: #FF4D7D;
    }

    /* Card Main Body */
    .card-reward-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(4, 6, 10, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 8px 10px;
    }

    .reward-amount-block {
        display: flex;
        flex-direction: column;
    }

    .reward-lbl {
        font-size: 0.62rem;
        font-weight: 800;
        color: #8E99A8;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .reward-val {
        font-size: 1.2rem;
        font-weight: 900;
        color: #FFE082;
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 0.3px;
        line-height: 1.1;
        text-shadow: 0 0 8px rgba(245, 166, 35, 0.35);
    }

    .reward-val small {
        font-size: 0.72rem;
        color: #8E99A8;
        font-weight: 700;
        margin-left: 2px;
    }

    .reward-principal-block {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
    }

    .principal-val {
        font-size: 0.92rem;
        font-weight: 800;
        color: #FFFFFF;
        font-family: 'Rajdhani', sans-serif;
    }

    /* Card Footer Info */
    .card-reward-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.7rem;
        color: #8E99A8;
        padding-top: 4px;
        border-top: 1px dashed rgba(255, 255, 255, 0.08);
    }

    .footer-date {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .footer-date i {
        color: #00D2FF;
        font-size: 0.68rem;
    }

    .footer-token-conv {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.68rem;
        color: #FFE082;
        background: rgba(245, 166, 35, 0.08);
        padding: 1px 5px;
        border-radius: 4px;
        border: 1px solid rgba(245, 166, 35, 0.2);
    }

    /* Empty State Card */
    .cps-no-records {
        grid-column: 1 / -1;
        text-align: center;
        padding: 30px 16px;
        background: rgba(10, 13, 20, 0.6);
        border: 1px dashed rgba(245, 166, 35, 0.3);
        border-radius: 12px;
        color: #8E99A8;
    }

    .cps-no-records i {
        font-size: 1.8rem;
        color: #FFD700;
        margin-bottom: 6px;
        opacity: 0.7;
    }

    .cps-no-records p {
        font-size: 0.82rem;
        font-weight: 600;
        margin: 0;
        color: #CBD5E1;
    }

    /* Footer Pagination */
    .cps-pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        flex-wrap: wrap;
    }

    .pagination-info-txt {
        font-size: 0.72rem;
        color: #8E99A8;
        font-weight: 600;
    }

    .pagination-controls-group {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .page-nav-btn {
        min-width: 28px;
        height: 28px;
        padding: 0 7px;
        background: #090B12;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 6px;
        color: #CBD5E1;
        font-size: 0.72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .page-nav-btn:hover:not(:disabled) {
        background: rgba(245, 166, 35, 0.15);
        border-color: #FFD700;
        color: #FFD700;
    }

    .page-nav-btn.active {
        background: #FFD700;
        border-color: #FFD700;
        color: #05070B;
        font-weight: 900;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.35);
    }

    .page-nav-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    /* Real-time Streaming Banner */
    .cps-streaming-banner {
        background: linear-gradient(135deg, rgba(0, 255, 136, 0.10) 0%, rgba(13, 17, 26, 0.95) 50%, rgba(245, 166, 35, 0.08) 100%);
        border: 1px solid rgba(0, 255, 136, 0.35);
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 12px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.6), inset 0 0 12px rgba(0, 255, 136, 0.05);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .streaming-banner-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }

    .streaming-badge-wrap {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.68rem;
        font-weight: 800;
        color: #00FF88;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .pulse-dot-green {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #00FF88;
        box-shadow: 0 0 8px #00FF88;
        animation: pulseGreenBreathe 1.5s infinite alternate;
    }

    @keyframes pulseGreenBreathe {
        0% { transform: scale(0.8); opacity: 0.6; }
        100% { transform: scale(1.3); opacity: 1; box-shadow: 0 0 12px #00FF88; }
    }

    .streaming-cron-countdown {
        font-family: 'Space Mono', monospace;
        font-size: 0.68rem;
        font-weight: 700;
        color: #FFE082;
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(245, 166, 35, 0.25);
        padding: 2px 6px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .streaming-banner-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 8px;
        align-items: center;
    }

    .stream-accruing-box {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .stream-accruing-lbl {
        font-size: 0.60rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }

    .stream-accruing-num {
        font-family: 'Space Mono', monospace;
        font-size: 1.15rem;
        font-weight: 900;
        color: #00FF88;
        text-shadow: 0 0 10px rgba(0, 255, 136, 0.45);
        line-height: 1.1;
        letter-spacing: 0.2px;
    }

    .stream-accruing-sub {
        font-size: 0.65rem;
        color: #FFE082;
        font-weight: 700;
        margin-top: 1px;
    }

    .stream-rate-box {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
        min-width: 0;
    }

    .stream-rate-lbl {
        font-size: 0.60rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .stream-rate-val {
        font-family: 'Outfit', sans-serif;
        font-size: 0.88rem;
        font-weight: 800;
        color: #FFFFFF;
        line-height: 1.1;
    }

    .stream-rate-val strong {
        color: #FFD700;
    }
</style>

@php
    $uid = Session::get('user.id');
    $userDetail = \App\UserDetails::where('id', $uid)->first();
    $activeDeposits = \App\StackingDeposite::where([['userid', $uid], ['status', '>', 0]])->get();
    $totalActiveStake = (float) $activeDeposits->sum('usdt');

    $userBooster = (int) ($userDetail->booster ?? 1);
    $dailyRoiRate = 0.50;
    if ($userBooster == 3) {
        $dailyRoiRate = 1.50;
    } elseif ($userBooster == 2) {
        $dailyRoiRate = 1.00;
    }

    $caiLivePrice = (float)(\App\ProfileStore::where('id', 1)->value('price') ?? 1.0);
    if ($caiLivePrice <= 0) $caiLivePrice = 1.0;

    $dailyExpectedRoiUsdt = ($totalActiveStake > 0 && ($userDetail->capping ?? 0) != 1 && ($userDetail->userstatus ?? 0) == 1) ? (($totalActiveStake * $dailyRoiRate) / 100) : 0.00;
    $dailyExpectedRoiCai = ($caiLivePrice > 0) ? ($dailyExpectedRoiUsdt / $caiLivePrice) : 0.00;

    $nowKolkata = \Carbon\Carbon::now('Asia/Kolkata');
    $cronToday = \Carbon\Carbon::today('Asia/Kolkata')->setTime(4, 30, 0);
    if ($nowKolkata->greaterThanOrEqualTo($cronToday)) {
        $lastCronMs = $cronToday->timestamp * 1000;
        $nextCronMs = $cronToday->copy()->addDay()->timestamp * 1000;
    } else {
        $lastCronMs = $cronToday->copy()->subDay()->timestamp * 1000;
        $nextCronMs = $cronToday->timestamp * 1000;
    }

    $totalCaiEarned = 0;
    $totalPrincipal = 0;
    if (isset($roiamount)) {
        foreach($roiamount as $r) {
            $totalCaiEarned += (float)($r->amount ?? 0);
            $totalPrincipal = max($totalPrincipal, (float)($r->principalusdt ?? 0));
        }
    }
    if ($totalActiveStake > 0) {
        $totalPrincipal = $totalActiveStake;
    }
    $totalLiveUsdt = $totalCaiEarned * $caiLivePrice;
@endphp

<div class="cps-hud-card">
    <!-- Header: Nested Title + Pill in 1 Clean Row -->
    <div class="cps-card-head">
        <div class="cps-head-left">
            <i class="fas fa-coins cps-head-icon"></i>
            <div class="cps-head-text">
                <div class="cps-title-row">
                    <h2 class="cps-head-title">CPS REWARDS</h2>
                    <span class="cps-rate-pill">{{ $dailyRoiRate }}% / DAY</span>
                </div>
                <div class="cps-head-subtitle">Daily staking yield accrual history</div>
            </div>
        </div>
    </div>

    <!-- Real-time Streaming Yield Accrual Banner (04:30 AM Cycle) -->
    <div class="cps-streaming-banner">
        <div class="streaming-banner-top">
            <div class="streaming-badge-wrap">
                <span class="pulse-dot-green"></span>
                <span>Live Accruing Yield (24H Cycle)</span>
            </div>
            <div class="streaming-cron-countdown" title="Next Cron Payout at 04:30 AM">
                <i class="fas fa-clock" style="color: #FFD700; font-size: 0.65rem;"></i>
                <span>Payout in: </span>
                <strong id="cronCountdownTxt">--h : --m : --s</strong>
            </div>
        </div>

        <div class="streaming-banner-grid">
            <div class="stream-accruing-box">
                <span class="stream-accruing-lbl">Accrued Since 04:30 AM</span>
                <span class="stream-accruing-num" id="liveAccruedUsdTxt">$0.0000</span>
                <span class="stream-accruing-sub">(<span id="liveAccruedCaiTxt">0.0000</span> CAI)</span>
            </div>
            <div class="stream-rate-box">
                <span class="stream-rate-lbl">24H Expected ROI</span>
                <span class="stream-rate-val"><strong>${{ number_format($dailyExpectedRoiUsdt, 2) }}</strong> <small style="color:#8E99A8; font-size:0.68rem;">/ 24h</small></span>
                <span style="font-size: 0.64rem; color: #00FF88; font-weight: 800; margin-top: 1px;">Rate: {{ $dailyRoiRate }}% Daily</span>
            </div>
        </div>
    </div>

    <!-- Summary Stats Bar — 3 In One Row (Compact) -->
    <div class="cps-summary-grid">
        <div class="cps-summary-pill gold">
            <span class="cps-summary-lbl"><i class="fas fa-wallet" style="color: #FFD700;"></i> EARNED</span>
            <span class="cps-summary-val gold">
                {{ ($totalCaiEarned < 1 && $totalCaiEarned > 0) ? number_format($totalCaiEarned, 4) : number_format($totalCaiEarned, 2) }}
                <span style="font-size: 0.65rem; color: #FFE082;">CAI</span>
            </span>
            <span style="font-size: 0.62rem; color: #00FF88; font-weight: 700; margin-top: 1px;">≈ ${{ number_format($totalLiveUsdt, 2) }}</span>
        </div>
        <div class="cps-summary-pill cyan">
            <span class="cps-summary-lbl"><i class="fas fa-layer-group" style="color: #00D2FF;"></i> STAKED</span>
            <span class="cps-summary-val cyan">${{ number_format($totalPrincipal, 2) }}</span>
            <span style="font-size: 0.62rem; color: #8E99A8; font-weight: 700; margin-top: 1px;">POOL DEPOSIT</span>
        </div>
        <div class="cps-summary-pill neutral">
            <span class="cps-summary-lbl"><i class="fas fa-receipt" style="color: #A0AEC0;"></i> PAYOUTS</span>
            <span class="cps-summary-val">{{ count($roiamount ?? []) }}</span>
            <span style="font-size: 0.62rem; color: #8E99A8; font-weight: 700; margin-top: 1px;">CYCLES</span>
        </div>
    </div>

    <!-- Cyber Toolbar: Show Records & Live Search -->
    <div class="cps-toolbar-row">
        <div class="cps-show-wrap">
            <span>SHOW</span>
            <select id="cpsPageSizeSelect" class="cps-select-box">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <div class="cps-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="cpsSearchInput" class="cps-search-input" placeholder="Search rewards...">
        </div>
    </div>

    <!-- Cards Grid Container -->
    <div class="cps-cards-grid" id="cpsCardsContainer">
        @php $idx = 1; @endphp
        @forelse($roiamount as $row)
            @php
                $st = strtolower($row->status ?? '');
                $isCredit = ($st === 'credit' || $st === 'success' || $st === 'paid' || $st === '0' || $st === '1');
                $caiAmount = (float)($row->amount ?? 0);
                $liveUsdtVal = $caiAmount * $caiLivePrice;
                $searchContent = strtolower(($row->amountusdt ?? '') . ' ' . ($row->principalusdt ?? '') . ' ' . ($row->created_at ?? '') . ' ' . ($row->status ?? '') . ' ' . ($row->amount ?? '') . ' ' . $liveUsdtVal);
            @endphp
            <div class="reward-cyber-card cps-card-item" data-search="{{ $searchContent }}">
                <!-- Card Header -->
                <div class="card-reward-head">
                    <span class="reward-index-badge">
                        <i class="fas fa-bolt"></i> #{{ $idx++ }}
                    </span>
                    <span class="reward-status-pill {{ $isCredit ? 'success' : 'locked' }}">
                        <i class="fas {{ $isCredit ? 'fa-check-circle' : 'fa-lock' }}"></i>
                        {{ strtoupper($row->status ?? 'CREDIT') }}
                    </span>
                </div>

                <!-- Card Body -->
                <div class="card-reward-body">
                    <div class="reward-amount-block">
                        <span class="reward-lbl">REWARD ACCRUED</span>
                        <span class="reward-val">
                            {{ ($caiAmount < 1 && $caiAmount > 0) ? number_format($caiAmount, 4) : number_format($caiAmount, 2) }}
                            <small style="color: #FFD700; font-weight: 800;">CAI</small>
                        </span>
                        <span style="font-size: 0.65rem; color: #00FF88; font-weight: 700; margin-top: 2px;">
                            ≈ ${{ number_format($liveUsdtVal, 2) }} <span style="font-size: 0.58rem; color: #8E99A8;">USDT</span>
                        </span>
                    </div>
                    <div class="reward-principal-block">
                        <span class="reward-lbl">STAKING POOL</span>
                        <span class="principal-val">${{ number_format((float)$row->principalusdt, 2) }}</span>
                        <span style="font-size: 0.60rem; color: #8E99A8; font-weight: 700; text-transform: uppercase;">DEPOSIT</span>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-reward-footer">
                    <div class="footer-date">
                        <i class="far fa-calendar-check"></i>
                        <span>{{ $row->created_at }}</span>
                    </div>
                    <div class="footer-token-conv" title="Live Oracle Valuation">
                        <i class="fas fa-bolt" style="font-size: 0.55rem; color: #FFD700;"></i> 1 CAI = ${{ number_format($caiLivePrice, 2) }}
                    </div>
                </div>
            </div>
        @empty
            <div class="cps-no-records" id="cpsEmptyState">
                <i class="fas fa-coins"></i>
                <p>No Staking Reward records found.</p>
            </div>
        @endforelse

        <div class="cps-no-records" id="cpsNoMatchState" style="display: none;">
            <i class="fas fa-search"></i>
            <p>No matching reward records found for your search.</p>
        </div>
    </div>

    <!-- Cyber Pagination Controls -->
    <div class="cps-pagination-row" id="cpsPaginationWrap">
        <div class="pagination-info-txt" id="cpsPaginationInfo">
            Showing 0 of 0 records
        </div>
        <div class="pagination-controls-group" id="cpsPaginationBtns">
            <!-- Rendered by JS -->
        </div>
    </div>
</div>

@if(isset($silveramount) && $silveramount > 0)
<!-- Cyber Notice Modal -->
<div class="modal" id="myModal" style="position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.85); z-index: 99999; backdrop-filter: blur(8px);">
    <div class="cps-hud-card" style="max-width: 480px; width: 92%; margin: 0 auto; max-height: 85vh; overflow-y: auto;">
        <div class="cps-card-head" style="border-bottom: 1px solid rgba(229, 168, 35, 0.3); padding-bottom: 10px; margin-bottom: 12px;">
            <div class="cps-head-left">
                <i class="fas fa-shield-halved" style="color: #FFD700;"></i>
                <div>
                    <h3 class="cps-head-title" style="font-size: 13px;">IMPORTANT NOTICE</h3>
                    <div class="cps-head-subtitle">Cyera Protocol Security Policy</div>
                </div>
            </div>
            <button type="button" class="btn-close" onclick="document.getElementById('myModal').remove()" style="background: none; border: none; color: #FFF; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <div style="font-size: 11px; color: #CBD5E1; line-height: 1.6;">
            <p>Dear All Key Leaders and Associates,</p>
            <p style="margin-top: 8px;"><strong style="color: #FFD700;">This is to inform you of an important update regarding Rewards eligibility (Silver Top-Up only).</strong></p>
            <p style="margin-top: 8px;">To further strengthen the system and ensure long-term sustainability, management has implemented a new policy to reinforce stability.</p>
            <p style="margin-top: 8px;"><strong style="color: #00FF88;">You must top up your Silver Wallet with a minimum of 30% of the Silver top-up amount from your Real Wallet to unlock ROI benefits.</strong></p>
            <p style="margin-top: 12px; text-align: right; color: #FFE082; font-weight: 700;">— Cyera AI Management</p>
        </div>
        <div style="margin-top: 16px; text-align: right;">
            <button type="button" class="mecha-btn-gold" onclick="document.getElementById('myModal').remove()" style="width: auto; padding: 6px 18px;">
                UNDERSTOOD
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('cpsSearchInput');
        const pageSizeSelect = document.getElementById('cpsPageSizeSelect');
        const cards = Array.from(document.querySelectorAll('.cps-card-item'));
        const noMatchState = document.getElementById('cpsNoMatchState');
        const paginationInfo = document.getElementById('cpsPaginationInfo');
        const paginationBtns = document.getElementById('cpsPaginationBtns');
        const paginationWrap = document.getElementById('cpsPaginationWrap');

        if (cards.length === 0) {
            if (paginationWrap) paginationWrap.style.display = 'none';
            return;
        }

        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect ? pageSizeSelect.value : 10) || 10;

        function filterAndPaginate() {
            const query = (searchInput ? searchInput.value : '').toLowerCase().trim();

            // 1. Filter cards by search query
            const matchedCards = cards.filter(card => {
                const searchData = card.getAttribute('data-search') || '';
                return searchData.includes(query);
            });

            // 2. Hide all cards first
            cards.forEach(card => card.style.display = 'none');

            // 3. Check for empty result
            if (matchedCards.length === 0) {
                if (noMatchState) noMatchState.style.display = 'block';
                if (paginationInfo) paginationInfo.innerText = 'Showing 0 of 0 records';
                renderPagination(0);
                return;
            } else {
                if (noMatchState) noMatchState.style.display = 'none';
            }

            // 4. Calculate pagination bounds
            const totalItems = matchedCards.length;
            const totalPages = Math.ceil(totalItems / pageSize) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, totalItems);

            // 5. Display active page slice
            for (let i = startIndex; i < endIndex; i++) {
                matchedCards[i].style.display = 'flex';
            }

            // 6. Update text info
            if (paginationInfo) {
                paginationInfo.innerText = `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} records`;
            }

            // 7. Render buttons
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            if (!paginationBtns) return;
            paginationBtns.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous Button
            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'page-nav-btn';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = (currentPage === 1);
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    filterAndPaginate();
                }
            };
            paginationBtns.appendChild(prevBtn);

            // Page numbers
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 6 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
                    if (p === 2 || p === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.innerText = '...';
                        dots.style.color = '#8E99A8';
                        dots.style.padding = '0 3px';
                        dots.style.fontSize = '0.75rem';
                        paginationBtns.appendChild(dots);
                    }
                    continue;
                }

                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                pageBtn.className = 'page-nav-btn' + (p === currentPage ? ' active' : '');
                pageBtn.innerText = p;
                pageBtn.onclick = () => {
                    currentPage = p;
                    filterAndPaginate();
                };
                paginationBtns.appendChild(pageBtn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'page-nav-btn';
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = (currentPage === totalPages);
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    filterAndPaginate();
                }
            };
            paginationBtns.appendChild(nextBtn);
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentPage = 1;
                filterAndPaginate();
            });
        }

        if (pageSizeSelect) {
            pageSizeSelect.addEventListener('change', function () {
                pageSize = parseInt(this.value) || 10;
                currentPage = 1;
                filterAndPaginate();
            });
        }

        // Initialize view
        filterAndPaginate();

        // Live Streaming Yield Accrual Engine (04:30 AM Cycle)
        (function initStreamingYield() {
            const dailyExpectedUsd = {{ (float)$dailyExpectedRoiUsdt }};
            const dailyExpectedCai = {{ (float)$dailyExpectedRoiCai }};
            const cycleStartMs = {{ $lastCronMs }};
            const cycleEndMs = {{ $nextCronMs }};

            const liveUsdEl = document.getElementById('liveAccruedUsdTxt');
            const liveCaiEl = document.getElementById('liveAccruedCaiTxt');
            const countdownEl = document.getElementById('cronCountdownTxt');

            function update() {
                const now = Date.now();
                const elapsedSec = Math.max(0, (now - cycleStartMs) / 1000);
                const fraction = Math.min(1.0, elapsedSec / 86400);

                const currentUsd = dailyExpectedUsd * fraction;
                const currentCai = dailyExpectedCai * fraction;

                if (liveUsdEl) {
                    liveUsdEl.innerText = '$' + currentUsd.toLocaleString(undefined, { minimumFractionDigits: 4, maximumFractionDigits: 4 });
                }
                if (liveCaiEl) {
                    liveCaiEl.innerText = currentCai.toLocaleString(undefined, { minimumFractionDigits: 4, maximumFractionDigits: 4 });
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

                requestAnimationFrame(update);
            }

            requestAnimationFrame(update);
        })();
    });
</script>
@endpush