@extends('layouts.user-mecha')

@section('title', 'Income Overview - Cyera AI')
@section('page-title', 'Income Overview')
@section('page-icon', 'fas fa-chart-pie')

@section('content')
@php
    $directIncome = $data['userDetail']->bonusReward()->where('status','!=',3)->sum('amt_usdt');
    $stakingIncome = $data['userDetail']->stackingIncome()->sum('amt_usdt');
    $stakingReferralIncome = $data['userDetail']->levelIncome()->where('description','l')->sum('amt_usdt');
    $teamDevelopmentIncome = $data['userDetail']->levelIncome()->where('description','r')->sum('amt_usdt');
    $clubIncome = $data['userDetail']->clubIncome()->sum('amt_usdt');
    $lifetimeIncome = $data['userDetail']->lifetimeIncome()->sum('amount');
    $totalIncomeUsdt = $directIncome + $stakingIncome + $stakingReferralIncome + $teamDevelopmentIncome + $clubIncome;
    $totalWithdraw = !empty($data['totalwithdraw']->amount) ? round((float)$data['totalwithdraw']->amount, 2) : 0.00;
    $remainingCap = !is_null($data['userDetail']->remainingCapping()) ? round((float)$data['userDetail']->remainingCapping(), 2) : 0.00;
@endphp

<!-- Income Streams Grid -->
<div class="mecha-stat-grid-2" style="margin-bottom: 12px;">
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>TOTAL EARNINGS</span>
            <i class="fas fa-hand-holding-dollar" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold">${{ number_format($totalIncomeUsdt, 2) }}</div>
        <div class="mecha-metric-sub">Cumulative All Incomes</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>TOTAL WITHDRAWN</span>
            <i class="fas fa-money-bill-transfer" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green">${{ number_format($totalWithdraw, 2) }}</div>
        <div class="mecha-metric-sub">Settled On-Chain</div>
    </div>
</div>

<div class="row" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 12px;">
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>STAKING REWARD</span>
            <i class="fas fa-coins" style="color: #FFE082;"></i>
        </div>
        <div class="mecha-metric-val">${{ number_format($stakingIncome, 2) }}</div>
        <div class="mecha-metric-sub">Daily Yield</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>DIRECT BONUS</span>
            <i class="fas fa-money-bill-wave" style="color: #00E5FF;"></i>
        </div>
        <div class="mecha-metric-val">${{ number_format($directIncome, 2) }}</div>
        <div class="mecha-metric-sub">Sponsor Bonus</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>STAKING REFERRAL</span>
            <i class="fas fa-users" style="color: #B34BFE;"></i>
        </div>
        <div class="mecha-metric-val">${{ number_format($stakingReferralIncome, 2) }}</div>
        <div class="mecha-metric-sub">Downline ROI Share</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>TEAM DEVELOPMENT</span>
            <i class="fas fa-chart-line" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val">${{ number_format($teamDevelopmentIncome, 2) }}</div>
        <div class="mecha-metric-sub">Matching Rewards</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>CLUB REWARD</span>
            <i class="fas fa-trophy" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold">${{ number_format($clubIncome, 2) }}</div>
        <div class="mecha-metric-sub">Pool Dividend</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>ACHIEVEMENT</span>
            <i class="fas fa-award" style="color: #FF8C00;"></i>
        </div>
        <div class="mecha-metric-val">${{ number_format($lifetimeIncome, 2) }}</div>
        <div class="mecha-metric-sub">Lifetime Rank Bonus</div>
    </div>
</div>

<!-- Booster Status Pill -->
<div class="mecha-hud-card" style="margin-bottom: 12px; padding: 12px 14px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="mecha-page-icon-box" style="width: 36px; height: 36px; font-size: 15px;">
                <i class="fas fa-rocket"></i>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 800; color: #FFF;">2X BOOSTER ACCELERATOR</div>
                <div style="font-size: 9px; color: #94A3B8;">Double yield booster tier status</div>
            </div>
        </div>
        @if($data['userDetail']->booster == 2)
            <span class="mecha-badge-green"><i class="fas fa-bolt"></i> ACTIVE</span>
        @else
            <span class="mecha-badge-red"><i class="fas fa-circle-xmark"></i> INACTIVE</span>
        @endif
    </div>
</div>

<!-- Capping Donut Chart Card -->
<div class="mecha-hud-card" style="margin-bottom: 12px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-circle-notch"></i>
            <div>
                <h2 class="mecha-card-title">CAPPING STATUS & LIMITS</h2>
                <div class="mecha-card-subtitle">Maximum potential income vs earned reward</div>
            </div>
        </div>
        <span class="mecha-card-badge">LIVE METRICS</span>
    </div>

    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px 0;">
        <div style="position: relative; width: 200px; height: 200px; margin: 0 auto;">
            <canvas id="doughnutChart"></canvas>
        </div>

        <div style="display: flex; justify-content: space-around; width: 100%; margin-top: 16px; border-top: 1px solid rgba(229, 168, 35, 0.2); padding-top: 12px;">
            <div style="text-align: center;">
                <div style="font-size: 8.5px; font-weight: 800; color: #94A3B8;">EARNED REWARD</div>
                <div style="font-size: 13px; font-weight: 900; color: #00FF88;">${{ number_format($totalIncomeUsdt, 2) }}</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 8.5px; font-weight: 800; color: #94A3B8;">REMAINING CAPPING</div>
                <div style="font-size: 13px; font-weight: 900; color: #FFD700;">${{ number_format($remainingCap, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Club Rewards Progress Card -->
<div class="mecha-hud-card" style="margin-bottom: 12px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-trophy"></i>
            <div>
                <h2 class="mecha-card-title">CLUB REWARD QUALIFICATION</h2>
                <div class="mecha-card-subtitle">
                    Achieved: <span style="color: #00FF88; font-weight: 700;">{{ !is_null($data['userDetail']->clubBusiness()['achieved']) ? $data['userDetail']->clubBusiness()['achieved']->clubname . ' ($' . round($data['userDetail']->clubBusiness()['achieved']->business_min) . ')' : 'Not Achieved' }}</span>
                </div>
            </div>
        </div>
        <span class="mecha-card-badge">NEXT: {{ $data['userDetail']->clubBusiness()['next']->clubname ?? 'Diamond' }}</span>
    </div>

    <!-- Power Leg Progress -->
    <div style="margin-top: 10px;">
        <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 800; margin-bottom: 5px;">
            <span style="color: #FFE082;">POWER LEG (${{ number_format($data['userDetail']->clubBusiness()['first'], 2) }})</span>
            <span id="goldLabel" style="color: #FFD700;">0%</span>
        </div>
        <div style="height: 8px; background: rgba(255,255,255,0.08); border-radius: 4px; overflow: hidden; border: 1px solid rgba(255, 215, 0, 0.25);">
            <div id="goldProgressFill" style="width: 0%; height: 100%; background: linear-gradient(90deg, #FFD700, #FFA500); box-shadow: 0 0 8px #FFD700; transition: width 0.8s ease;"></div>
        </div>
    </div>

    <!-- Other Legs Progress -->
    <div style="margin-top: 14px;">
        <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 800; margin-bottom: 5px;">
            <span style="color: #00E5FF;">OTHER LEGS (${{ number_format($data['userDetail']->clubBusiness()['rest'], 2) }})</span>
            <span id="blueLabel" style="color: #00E5FF;">0%</span>
        </div>
        <div style="height: 8px; background: rgba(255,255,255,0.08); border-radius: 4px; overflow: hidden; border: 1px solid rgba(0, 229, 255, 0.25);">
            <div id="blueProgressFill" style="width: 0%; height: 100%; background: linear-gradient(90deg, #00FF88, #00E5FF); box-shadow: 0 0 8px #00E5FF; transition: width 0.8s ease;"></div>
        </div>
    </div>
</div>

<!-- Lifetime Achievement Leg Breakdown -->
<div class="mecha-hud-card" style="margin-bottom: 12px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-sitemap"></i>
            <div>
                <h2 class="mecha-card-title">LIFETIME REWARD LEG ANALYSIS</h2>
                <div class="mecha-card-subtitle">
                    Achieved: <span style="color: #00FF88; font-weight: 700;">{{ (!is_null($data['userDetail']->lifetimeAchievementBusiness()['achieved']) && !is_null($data['userDetail']->lifetimeAchievementBusiness()['achieved']->last())) ? $data['userDetail']->lifetimeAchievementBusiness()['achieved']->last()->rewardname : 'None' }}</span>
                </div>
            </div>
        </div>
        <a href="{{ url('/User/Treeview') }}" class="mecha-btn-outline" style="padding: 4px 10px; font-size: 9px; height: auto;">
            <i class="fas fa-diagram-project"></i> TREE VIEW
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; margin: 12px 0;">
        <div class="stat-mini-box" style="padding: 8px 4px;">
            <div class="stat-mini-lbl" style="color: #00FF88;">POWER LEG</div>
            <div class="stat-mini-val" style="color: #00FF88;">${{ number_format($data['userDetail']->lifetimeAchievementBusiness()['first'], 2) }}</div>
            <div class="stat-mini-sub">40% Target</div>
        </div>
        <div class="stat-mini-box" style="padding: 8px 4px;">
            <div class="stat-mini-lbl" style="color: #FFD700;">SECOND LEG</div>
            <div class="stat-mini-val" style="color: #FFD700;">${{ number_format($data['userDetail']->lifetimeAchievementBusiness()['second'], 2) }}</div>
            <div class="stat-mini-sub">30% Target</div>
        </div>
        <div class="stat-mini-box" style="padding: 8px 4px;">
            <div class="stat-mini-lbl" style="color: #00E5FF;">REST LEGS</div>
            <div class="stat-mini-val" style="color: #00E5FF;">${{ number_format($data['userDetail']->lifetimeAchievementBusiness()['rest'], 2) }}</div>
            <div class="stat-mini-sub">30% Target</div>
        </div>
    </div>

    <!-- Lifetime Progress Bars -->
    <div style="margin-top: 8px;">
        <div style="display: flex; justify-content: space-between; font-size: 9.5px; font-weight: 800; margin-bottom: 4px;">
            <span style="color: #00FF88;">Leg 1 (Power)</span>
            <span id="goldLabel1" style="color: #00FF88;">0%</span>
        </div>
        <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; margin-bottom: 8px;">
            <div id="goldProgressFill1" style="width: 0%; height: 100%; background: #00FF88; transition: width 0.8s ease;"></div>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 9.5px; font-weight: 800; margin-bottom: 4px;">
            <span style="color: #FFD700;">Leg 2 (Second)</span>
            <span id="blueLabel1" style="color: #FFD700;">0%</span>
        </div>
        <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; margin-bottom: 8px;">
            <div id="blueProgressFill1" style="width: 0%; height: 100%; background: #FFD700; transition: width 0.8s ease;"></div>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 9.5px; font-weight: 800; margin-bottom: 4px;">
            <span style="color: #00E5FF;">Leg 3+ (Rest)</span>
            <span id="blueLabel2" style="color: #00E5FF;">0%</span>
        </div>
        <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden;">
            <div id="blueProgressFill2" style="width: 0%; height: 100%; background: #00E5FF; transition: width 0.8s ease;"></div>
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

    // Club Rewards Progress Calculations
    const total1 = {{ (float)(!is_null($data['userDetail']->clubBusiness()['next']->business_min) ? $data['userDetail']->clubBusiness()['next']->business_min : 1000) }};
    const current1 = {{ (float)$data['userDetail']->clubBusiness()['first'] }};
    const current2 = {{ (float)$data['userDetail']->clubBusiness()['rest'] }};

    const percent1 = Math.min(100, Math.max(0, (current1 / (total1 * 0.4)) * 100));
    const percent2 = Math.min(100, Math.max(0, (current2 / (total1 * 0.6)) * 100));

    document.getElementById('goldProgressFill').style.width = percent1.toFixed(1) + '%';
    document.getElementById('goldLabel').textContent = percent1.toFixed(1) + '%';
    document.getElementById('blueProgressFill').style.width = percent2.toFixed(1) + '%';
    document.getElementById('blueLabel').textContent = percent2.toFixed(1) + '%';

    // Lifetime Rewards Calculations
    const totalLifetime = {{ (float)(!is_null($data['userDetail']->lifetimeAchievementBusiness()['next']->business_min) ? $data['userDetail']->lifetimeAchievementBusiness()['next']->business_min : 5000) }};
    const current11 = {{ (float)$data['userDetail']->lifetimeAchievementBusiness()['first'] }};
    const current22 = {{ (float)$data['userDetail']->lifetimeAchievementBusiness()['second'] }};
    const current33 = {{ (float)$data['userDetail']->lifetimeAchievementBusiness()['rest'] }};

    const p11 = Math.min(100, Math.max(0, (current11 / (totalLifetime * 0.4)) * 100));
    const p22 = Math.min(100, Math.max(0, (current22 / (totalLifetime * 0.3)) * 100));
    const p33 = Math.min(100, Math.max(0, (current33 / (totalLifetime * 0.3)) * 100));

    document.getElementById('goldProgressFill1').style.width = p11.toFixed(1) + '%';
    document.getElementById('goldLabel1').textContent = p11.toFixed(1) + '%';
    document.getElementById('blueProgressFill1').style.width = p22.toFixed(1) + '%';
    document.getElementById('blueLabel1').textContent = p22.toFixed(1) + '%';
    document.getElementById('blueProgressFill2').style.width = p33.toFixed(1) + '%';
    document.getElementById('blueLabel2').textContent = p33.toFixed(1) + '%';
</script>
@endpush