@extends('layouts.user-mecha')

@section('title', 'Rank Income (V1 to V8) - Cyera AI')
@section('page-title', 'Weekly Rank Income')
@section('page-icon', 'fas fa-crown')

@section('content')
<style>
    /* Scoped Dark Mecha Styling for Rank Income */
    .rank-hud-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .rank-hud-card {
        padding: 16px 18px;
        background: linear-gradient(160deg, #0A0D15 0%, #05070B 100%);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7), 0 0 16px rgba(245, 158, 11, 0.08);
    }

    .rank-head-title-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .rank-title-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rank-title-icon {
        font-size: 1.5rem;
        color: #F59E0B;
        filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.5));
    }

    .rank-title-text h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: #FFFFFF;
        margin: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .rank-title-text span {
        font-size: 0.75rem;
        color: #94A3B8;
    }

    .rank-rule-badge {
        font-size: 0.70rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid #F59E0B;
        color: #FDE68A;
        letter-spacing: 0.5px;
    }

    /* Summary Bar */
    .rank-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    @media (max-width: 991px) {
        .rank-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .rank-summary-grid {
            grid-template-columns: 1fr;
        }
    }

    .rank-summary-card {
        background: rgba(14, 18, 28, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rank-summary-card.gold-card {
        border-color: rgba(245, 158, 11, 0.4);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(14, 18, 28, 0.9) 100%);
    }

    .rank-card-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(245, 158, 11, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #F59E0B;
        flex-shrink: 0;
    }

    .rank-card-content {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .rank-card-lbl {
        font-size: 0.70rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rank-card-val {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: #FFFFFF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .rank-card-val.gold {
        color: #F59E0B;
        text-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
    }

    /* Notice Banner */
    .rank-rule-alert {
        background: linear-gradient(90deg, rgba(245, 158, 11, 0.12) 0%, rgba(10, 13, 21, 0.8) 100%);
        border-left: 4px solid #F59E0B;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rank-rule-alert i {
        font-size: 1.2rem;
        color: #F59E0B;
        flex-shrink: 0;
    }

    .rank-rule-alert-text {
        font-size: 0.78rem;
        color: #E2E8F0;
        line-height: 1.4;
    }

    .rank-rule-alert-text strong {
        color: #F59E0B;
    }

    /* V1 - V8 Tier Grid */
    .rank-matrix-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 16px;
    }

    @media (max-width: 1200px) {
        .rank-matrix-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .rank-matrix-grid {
            grid-template-columns: 1fr;
        }
    }

    .rank-tier-box {
        background: rgba(13, 17, 26, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .rank-tier-box:hover {
        transform: translateY(-2px);
        border-color: rgba(245, 158, 11, 0.4);
    }

    .rank-tier-box.active-tier {
        border: 1.5px solid #F59E0B;
        background: linear-gradient(170deg, rgba(245, 158, 11, 0.12) 0%, rgba(13, 17, 26, 0.95) 100%);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.18);
    }

    .rank-tier-box.achieved-tier {
        border-color: rgba(16, 185, 129, 0.4);
    }

    .tier-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .tier-badge {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 900;
        padding: 4px 12px;
        border-radius: 8px;
        background: rgba(245, 158, 11, 0.18);
        border: 1px solid #F59E0B;
        color: #F59E0B;
        letter-spacing: 1px;
    }

    .tier-status-pill {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 12px;
        text-transform: uppercase;
    }

    .tier-status-pill.active {
        background: rgba(245, 158, 11, 0.2);
        border: 1px solid #F59E0B;
        color: #F59E0B;
    }

    .tier-status-pill.achieved {
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid #10B981;
        color: #10B981;
    }

    .tier-status-pill.locked {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #64748B;
    }

    .tier-payout {
        margin-bottom: 12px;
    }

    .tier-payout-lbl {
        font-size: 0.65rem;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tier-payout-val {
        font-family: 'Outfit', sans-serif;
        font-size: 1.35rem;
        font-weight: 900;
        color: #F59E0B;
    }

    .tier-payout-val span {
        font-size: 0.80rem;
        font-weight: 600;
        color: #CBD5E1;
    }

    .tier-requirements {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        padding: 8px 10px;
    }

    .req-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.72rem;
    }

    .req-lbl {
        color: #94A3B8;
    }

    .req-val {
        font-weight: 700;
        color: #FFFFFF;
    }

    .tier-progress-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .prog-label-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.65rem;
        font-weight: 700;
        color: #94A3B8;
    }

    .mecha-prog-bar-container {
        width: 100%;
        height: 6px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 4px;
        overflow: hidden;
    }

    .mecha-prog-fill {
        height: 100%;
        border-radius: 4px;
        background: linear-gradient(90deg, #F59E0B 0%, #FBBF24 100%);
        transition: width 0.4s ease;
    }

    /* History Table */
    .mecha-table-wrap {
        overflow-x: auto;
    }

    .mecha-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .mecha-date-inputs {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .mecha-input-sm {
        background: #0B0F17;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        color: #FFFFFF;
        padding: 5px 10px;
        font-size: 0.75rem;
    }

    .mecha-btn-sm {
        background: #F59E0B;
        color: #000000;
        font-weight: 700;
        border: none;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: opacity 0.2s ease;
    }

    .mecha-btn-sm:hover {
        opacity: 0.9;
    }
</style>

<div class="rank-hud-container">
    <!-- Top HUD Summary -->
    <div class="rank-hud-card">
        <div class="rank-head-title-wrap">
            <div class="rank-title-left">
                <i class="fas fa-crown rank-title-icon"></i>
                <div class="rank-title-text">
                    <h2>RANK INCOME OVERVIEW (V1 TO V8)</h2>
                    <span>Weekly Leadership & Turnover Rewards</span>
                </div>
            </div>
            <span class="rank-rule-badge"><i class="fas fa-shield-alt mr-1"></i> NON-CUMULATIVE PAYOUT</span>
        </div>

        <!-- Metric Grid -->
        <div class="rank-summary-grid">
            <div class="rank-summary-card gold-card">
                <div class="rank-card-icon-wrap">
                    <i class="fas fa-medal"></i>
                </div>
                <div class="rank-card-content">
                    <span class="rank-card-lbl">Current Active Rank</span>
                    <span class="rank-card-val gold">{{ $rankQualification['current_rank'] !== 'None' ? $rankQualification['current_rank'] : 'No Rank' }}</span>
                </div>
            </div>

            <div class="rank-summary-card">
                <div class="rank-card-icon-wrap">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="rank-card-content">
                    <span class="rank-card-lbl">Weekly Reward Rate</span>
                    <span class="rank-card-val">${{ number_format($rankQualification['weekly_reward'], 2) }} <small style="font-size:0.75rem; color:#94A3B8;">/wk</small></span>
                </div>
            </div>

            <div class="rank-summary-card">
                <div class="rank-card-icon-wrap">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="rank-card-content">
                    <span class="rank-card-lbl">Power Leg (PL) Business</span>
                    <span class="rank-card-val">${{ number_format($rankQualification['power_leg'], 2) }}</span>
                </div>
            </div>

            <div class="rank-summary-card">
                <div class="rank-card-icon-wrap">
                    <i class="fas fa-code-branch"></i>
                </div>
                <div class="rank-card-content">
                    <span class="rank-card-lbl">Weaker Leg (WL) Business</span>
                    <span class="rank-card-val">${{ number_format($rankQualification['weaker_leg'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Rule Alert -->
        <div class="rank-rule-alert">
            <i class="fas fa-info-circle"></i>
            <div class="rank-rule-alert-text">
                <strong>Rank Income Rules:</strong> Rank rewards are <strong>non-cumulative</strong>. Only the highest active eligible rank reward applies every week. When you unlock a higher rank (e.g., V2), previous rank payouts (V1) stop automatically.
            </div>
        </div>
    </div>

    <!-- V1 to V8 Matrix Cards -->
    <div class="rank-hud-card">
        <div class="rank-head-title-wrap">
            <div class="rank-title-left">
                <i class="fas fa-cubes" style="color: #F59E0B; font-size: 1.3rem;"></i>
                <div class="rank-title-text">
                    <h2>RANK QUALIFICATION TIERS</h2>
                    <span>Track your Power Leg & Weaker Leg progress to unlock next weekly rewards</span>
                </div>
            </div>
        </div>

        <div class="rank-matrix-grid">
            @foreach($rankQualification['matrix'] as $tier)
                @php
                    $isCurrent = ($rankQualification['current_rank'] === $tier['rank_name']);
                    $isAchieved = $tier['is_achieved'];
                @endphp
                <div class="rank-tier-box {{ $isCurrent ? 'active-tier' : ($isAchieved ? 'achieved-tier' : '') }}">
                    <div>
                        <div class="tier-header">
                            <span class="tier-badge">{{ $tier['rank_name'] }}</span>
                            @if($isCurrent)
                                <span class="tier-status-pill active"><i class="fas fa-check-circle"></i> ACTIVE & EARNING</span>
                            @elseif($isAchieved)
                                <span class="tier-status-pill achieved"><i class="fas fa-check"></i> QUALIFIED</span>
                            @else
                                <span class="tier-status-pill locked"><i class="fas fa-lock"></i> LOCKED</span>
                            @endif
                        </div>

                        <div class="tier-payout">
                            <div class="tier-payout-lbl">Rank Weekly Income</div>
                            <div class="tier-payout-val">${{ number_format($tier['weekly_reward'], 0) }} <span>/ week</span></div>
                        </div>

                        <div class="tier-requirements">
                            <div class="req-row">
                                <span class="req-lbl"><i class="fas fa-bolt mr-1" style="color:#F59E0B;"></i> Power Leg (PL)</span>
                                <span class="req-val">${{ number_format($tier['power_leg'], 0) }}</span>
                            </div>
                            <div class="req-row">
                                <span class="req-lbl"><i class="fas fa-code-branch mr-1" style="color:#38BDF8;"></i> Weaker Leg (WL)</span>
                                <span class="req-val">${{ number_format($tier['weaker_leg'], 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="tier-progress-wrap">
                        <div class="prog-label-row">
                            <span>PL Progress (${{ number_format($rankQualification['power_leg'], 0) }} / ${{ number_format($tier['power_leg'], 0) }})</span>
                            <span>{{ $tier['pl_progress'] }}%</span>
                        </div>
                        <div class="mecha-prog-bar-container">
                            <div class="mecha-prog-fill" style="width: {{ $tier['pl_progress'] }}%;"></div>
                        </div>

                        <div class="prog-label-row" style="margin-top: 4px;">
                            <span>WL Progress (${{ number_format($rankQualification['weaker_leg'], 0) }} / ${{ number_format($tier['weaker_leg'], 0) }})</span>
                            <span>{{ $tier['wl_progress'] }}%</span>
                        </div>
                        <div class="mecha-prog-bar-container">
                            <div class="mecha-prog-fill" style="width: {{ $tier['wl_progress'] }}%; background: linear-gradient(90deg, #38BDF8 0%, #0284C7 100%);"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Distribution History Table -->
    <div class="rank-hud-card">
        <div class="rank-head-title-wrap">
            <div class="rank-title-left">
                <i class="fas fa-history" style="color: #F59E0B; font-size: 1.3rem;"></i>
                <div class="rank-title-text">
                    <h2>RANK INCOME PAYOUT HISTORY</h2>
                    <span>Total Earned: ${{ number_format($totalEarnedUsdt, 2) }} USDT</span>
                </div>
            </div>
        </div>

        <div class="mecha-filter-bar">
            <form action="{{ url('/User/RankIncome') }}" method="GET" class="mecha-date-inputs">
                <input type="date" name="fromdate" value="{{ request('fromdate') }}" class="mecha-input-sm">
                <span style="color:#94A3B8;">to</span>
                <input type="date" name="todate" value="{{ request('todate') }}" class="mecha-input-sm">
                <button type="submit" class="mecha-btn-sm"><i class="fas fa-filter mr-1"></i> Filter</button>
                <a href="{{ url('/User/RankIncome') }}" class="mecha-btn-sm" style="background:#334155; color:#FFFFFF; text-decoration:none;">Reset</a>
            </form>
        </div>

        <div class="mecha-table-wrap">
            <table class="mecha-cyber-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DISTRIBUTION DATE</th>
                        <th>RANK</th>
                        <th>WEEKLY PAYOUT ($)</th>
                        <th>PL SNAPSHOT ($)</th>
                        <th>WL SNAPSHOT ($)</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse($history as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="col-time">{{ $item->distributed_date ?? $item->created_at }}</td>
                            <td><span class="tier-badge" style="font-size:0.8rem; padding:2px 8px;">{{ $item->rank_name }}</span></td>
                            <td class="col-amount gold" style="font-weight:800; color:#F59E0B;">${{ number_format($item->amt_usdt, 2) }}</td>
                            <td>${{ number_format($item->power_leg_business, 2) }}</td>
                            <td>${{ number_format($item->weaker_leg_business, 2) }}</td>
                            <td>
                                @if($item->status == 0)
                                    <span class="mecha-badge-green">CREDITED</span>
                                @else
                                    <span class="mecha-badge-yellow">PROCESSED</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:20px; color:#94A3B8;">
                                <i class="fas fa-info-circle mr-2"></i> No rank income distribution records found yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
