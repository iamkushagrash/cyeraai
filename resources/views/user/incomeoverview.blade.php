@extends('layouts.user-mecha')

@section('title', 'Income Overview - Cyera AI')
@section('page-title', 'Income Overview')
@section('page-icon', 'fas fa-chart-pie')

@section('content')
@php
    $directIncome = (float) $data['userDetail']->bonusReward()->where('status', '!=', 3)->sum('amt_usdt');
    $stakingIncome = (float) $data['userDetail']->stackingIncome()->sum('amt_usdt');
    $stakingReferralIncome = (float) $data['userDetail']->levelIncome()->where('description', 'l')->sum('amt_usdt');
    $rankIncome = (float) $data['userDetail']->rankIncome()->sum('amt_usdt');
    $poolIncome = (float) \App\PoolIncome::where('userid', $data['userDetail']->id)->sum('amt_usdt');
    
    $totalIncomeUsdt = $directIncome + $stakingIncome + $stakingReferralIncome + $rankIncome + $poolIncome;
    $totalWithdraw = !empty($data['totalwithdraw']->amount) ? round((float)$data['totalwithdraw']->amount, 2) : 0.00;
    $remainingCap = !is_null($data['userDetail']->remainingCapping()) ? round((float)$data['userDetail']->remainingCapping(), 2) : 0.00;

    $cappingStats = $data['userDetail']->getCappingTier();
    $tierMultiplier = $cappingStats['multiplier'] ?: 2;
    $tierLabel = $tierMultiplier . 'X';
    $legStats = $data['userDetail']->getLegBusiness();
    $rankQual = $data['userDetail']->getRankQualification();
@endphp

<!-- Top Summary Cards -->
<div class="mecha-stat-grid-2" style="margin-bottom: 12px;">
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>TOTAL REWARDS EARNED</span>
            <i class="fas fa-hand-holding-dollar" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold">${{ number_format($totalIncomeUsdt, 2) }}</div>
        <div class="mecha-metric-sub">Cumulative across 5 Active Streams</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>TOTAL WITHDRAWN</span>
            <i class="fas fa-money-bill-transfer" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green">${{ number_format($totalWithdraw, 2) }}</div>
        <div class="mecha-metric-sub">Settled On-Chain (BEP-20)</div>
    </div>
</div>

<!-- 5 Active Income Streams Grid -->
<div class="mecha-section-title" style="font-size: 11px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.8px; margin: 16px 0 8px 4px;">
    Active Ecosystem Reward Streams
</div>

<div class="row" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 14px;">
    <!-- 1. Daily Staking Yield -->
    <div class="mecha-metric-box" onclick="window.location.href='{{ url('/User/StakingReward') }}'" style="cursor: pointer; transition: transform 0.2s ease, border-color 0.2s ease;" onmouseover="this.style.borderColor='rgba(255,215,0,0.5)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor=''; this.style.transform=''">
        <div class="mecha-metric-lbl">
            <span style="color: #FFE082;">1. DAILY STAKING ROI</span>
            <i class="fas fa-coins" style="color: #FFE082;"></i>
        </div>
        <div class="mecha-metric-val" style="color: #FFE082;">${{ number_format($stakingIncome, 2) }}</div>
        <div class="mecha-metric-sub" style="display: flex; justify-content: space-between; align-items: center;">
            <span>0.5% Daily Accrual</span>
            <i class="fas fa-arrow-right" style="font-size: 9px; opacity: 0.7;"></i>
        </div>
    </div>

    <!-- 2. Direct Referral Bonus -->
    <div class="mecha-metric-box" onclick="window.location.href='{{ url('/User/DirectBonus') }}'" style="cursor: pointer; transition: transform 0.2s ease, border-color 0.2s ease;" onmouseover="this.style.borderColor='rgba(0,229,255,0.5)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor=''; this.style.transform=''">
        <div class="mecha-metric-lbl">
            <span style="color: #00E5FF;">2. DIRECT REFERRAL</span>
            <i class="fas fa-money-bill-wave" style="color: #00E5FF;"></i>
        </div>
        <div class="mecha-metric-val" style="color: #00E5FF;">${{ number_format($directIncome, 2) }}</div>
        <div class="mecha-metric-sub" style="display: flex; justify-content: space-between; align-items: center;">
            <span>5% Instant Sponsor Bonus</span>
            <i class="fas fa-arrow-right" style="font-size: 9px; opacity: 0.7;"></i>
        </div>
    </div>

    <!-- 3. Staking Referral Reward -->
    <div class="mecha-metric-box" onclick="window.location.href='{{ url('/User/StakingReferralReward') }}'" style="cursor: pointer; transition: transform 0.2s ease, border-color 0.2s ease;" onmouseover="this.style.borderColor='rgba(179,75,254,0.5)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor=''; this.style.transform=''">
        <div class="mecha-metric-lbl">
            <span style="color: #B34BFE;">3. STAKING REFERRAL</span>
            <i class="fas fa-users" style="color: #B34BFE;"></i>
        </div>
        <div class="mecha-metric-val" style="color: #B34BFE;">${{ number_format($stakingReferralIncome, 2) }}</div>
        <div class="mecha-metric-sub" style="display: flex; justify-content: space-between; align-items: center;">
            <span>15-Level Tree Turnover</span>
            <i class="fas fa-arrow-right" style="font-size: 9px; opacity: 0.7;"></i>
        </div>
    </div>

    <!-- 4. Global Pool (5.0%) -->
    <div class="mecha-metric-box" onclick="window.location.href='{{ url('/User/PoolIncome') }}'" style="cursor: pointer; transition: transform 0.2s ease, border-color 0.2s ease;" onmouseover="this.style.borderColor='rgba(167,139,250,0.5)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor=''; this.style.transform=''">
        <div class="mecha-metric-lbl">
            <span style="color: #A78BFA;">4. GLOBAL POOL (5%)</span>
            <i class="fas fa-layer-group" style="color: #A78BFA;"></i>
        </div>
        <div class="mecha-metric-val" style="color: #A78BFA;">${{ number_format($poolIncome, 2) }}</div>
        <div class="mecha-metric-sub" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Daily / Weekly / Monthly</span>
            <i class="fas fa-arrow-right" style="font-size: 9px; opacity: 0.7;"></i>
        </div>
    </div>

    <!-- 5. Rank Income (V1-V8) Full Width -->
    <div class="mecha-metric-box" onclick="window.location.href='{{ url('/User/RankIncome') }}'" style="grid-column: span 2; cursor: pointer; transition: transform 0.2s ease, border-color 0.2s ease;" onmouseover="this.style.borderColor='rgba(245,158,11,0.5)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor=''; this.style.transform=''">
        <div class="mecha-metric-lbl">
            <span style="color: #F59E0B;">5. RANK INCOME (V1 TO V8 LEADERSHIP)</span>
            <i class="fas fa-crown" style="color: #F59E0B;"></i>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div class="mecha-metric-val gold">${{ number_format($rankIncome, 2) }}</div>
            <div style="font-size: 11px; font-weight: 700; color: #94A3B8;">Current Rank: <span style="color: #00FF88;">{{ $rankQual['current_rank'] ?: 'None' }}</span></div>
        </div>
        <div class="mecha-metric-sub" style="display: flex; justify-content: space-between; align-items: center; margin-top: 4px;">
            <span>Weekly Leadership Turnover Dividend Pool</span>
            <i class="fas fa-arrow-right" style="font-size: 9px; opacity: 0.7;"></i>
        </div>
    </div>
</div>

<!-- Capping Donut Chart Card -->
<div class="mecha-hud-card" style="margin-bottom: 12px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-shield-halved" style="color: #FFD700;"></i>
            <div>
                <h2 class="mecha-card-title">DYNAMIC CAPPING & LIMITS</h2>
                <div class="mecha-card-subtitle">Active multiplier tier: <strong style="color: #00FF88;">{{ $tierLabel }}</strong></div>
            </div>
        </div>
        <span class="mecha-card-badge">TIER: {{ $tierLabel }}</span>
    </div>

    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px 0;">
        <div style="position: relative; width: 180px; height: 180px; margin: 0 auto;">
            <canvas id="doughnutChart"></canvas>
        </div>

        <div style="display: flex; justify-content: space-around; width: 100%; margin-top: 14px; border-top: 1px solid rgba(229, 168, 35, 0.2); padding-top: 12px;">
            <div style="text-align: center;">
                <div style="font-size: 8.5px; font-weight: 800; color: #94A3B8;">TOTAL EARNED</div>
                <div style="font-size: 13px; font-weight: 900; color: #00FF88;">${{ number_format($totalIncomeUsdt, 2) }}</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 8.5px; font-weight: 800; color: #94A3B8;">REMAINING CAPPING</div>
                <div style="font-size: 13px; font-weight: 900; color: #FFD700;">${{ number_format($remainingCap, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Leg Turnover Breakdown -->
<div class="mecha-hud-card" style="margin-bottom: 12px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-sitemap" style="color: #00E5FF;"></i>
            <div>
                <h2 class="mecha-card-title">TEAM LEG TURNOVER ANALYSIS</h2>
                <div class="mecha-card-subtitle">Power Leg & Weaker Leg Volume for Pool & Rank Qualifications</div>
            </div>
        </div>
        <a href="{{ url('/User/Treeview') }}" class="mecha-btn-outline" style="padding: 4px 10px; font-size: 9px; height: auto;">
            <i class="fas fa-diagram-project"></i> TREE VIEW
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 12px;">
        <div class="mecha-metric-box" style="background: rgba(255, 215, 0, 0.04); border-color: rgba(255, 215, 0, 0.2);">
            <div class="mecha-metric-lbl">
                <span style="color: #FFD700;"><i class="fas fa-crown"></i> POWER LEG</span>
                <span class="mecha-badge-green" style="font-size: 8px; padding: 2px 6px;">STRONG</span>
            </div>
            <div class="mecha-metric-val gold">${{ number_format($legStats['power'] ?? 0, 2) }}</div>
            <div class="mecha-metric-sub">Highest Downline Leg Volume</div>
        </div>

        <div class="mecha-metric-box" style="background: rgba(0, 255, 136, 0.04); border-color: rgba(0, 255, 136, 0.2);">
            <div class="mecha-metric-lbl">
                <span style="color: #00FF88;"><i class="fas fa-bolt"></i> WEAKER LEG</span>
                <span class="mecha-badge-cyan" style="font-size: 8px; padding: 2px 6px;">TARGET</span>
            </div>
            <div class="mecha-metric-val green">${{ number_format($legStats['weaker'] ?? 0, 2) }}</div>
            <div class="mecha-metric-sub">Combined Other Legs Volume</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Capping Donut Chart
    const ctx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Earned Reward', 'Remaining Cap'],
            datasets: [{
                data: [{{ $totalIncomeUsdt > 0 ? $totalIncomeUsdt : 1 }}, {{ $remainingCap > 0 ? $remainingCap : 1 }}],
                backgroundColor: ['#00FF88', '#FFD700'],
                borderColor: '#06080E',
                borderWidth: 3,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(6, 9, 16, 0.95)',
                    titleColor: '#FFD700',
                    bodyColor: '#FFF',
                    borderColor: '#E5A823',
                    borderWidth: 1
                }
            }
        }
    });
</script>
@endpush