@extends('layouts.user-mecha')

@section('title', 'DAO Global Turnover Pool - Cyera AI')
@section('page-title', 'DAO Governance Pool Dividends')
@section('page-icon', 'fas fa-crown')

@section('content')
<style>
    /* DAO HUD Card Container */
    .dao-hud-card {
        padding: 12px 10px;
        background: linear-gradient(160deg, #0D0F19 0%, #05070D 100%);
        border: 1px solid rgba(234, 179, 8, 0.35);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8), 0 0 20px rgba(234, 179, 8, 0.1);
    }

    .dao-card-head {
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        width: 100%;
    }

    .dao-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .dao-head-icon {
        font-size: 1.3rem;
        color: #FBBF24;
        filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.5));
        flex-shrink: 0;
    }

    .dao-head-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        width: 100%;
    }

    .dao-head-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin: 0;
    }

    .dao-head-subtitle {
        font-size: 0.65rem;
        color: #94A3B8;
        margin-top: 1px;
    }

    .dao-rate-pill {
        font-size: 0.62rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 8px;
        background: rgba(234, 179, 8, 0.15);
        border: 1px solid #EAB308;
        color: #FDE047;
        white-space: nowrap;
    }

    /* Summary Grid */
    .dao-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .dao-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .dao-summary-pill {
        background: rgba(15, 21, 35, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 6px 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .dao-summary-pill.grand-pill {
        border-top: 2px solid #EAB308;
        background: linear-gradient(180deg, rgba(234, 179, 8, 0.15) 0%, rgba(15, 21, 35, 0.85) 100%);
    }

    .dao-summary-pill.plat-pill {
        border-top: 2px solid #38BDF8;
        background: linear-gradient(180deg, rgba(56, 189, 248, 0.12) 0%, rgba(15, 21, 35, 0.85) 100%);
    }

    .dao-summary-pill.gold-pill {
        border-top: 2px solid #F59E0B;
        background: linear-gradient(180deg, rgba(245, 158, 11, 0.12) 0%, rgba(15, 21, 35, 0.85) 100%);
    }

    .dao-summary-pill.diam-pill {
        border-top: 2px solid #EC4899;
        background: linear-gradient(180deg, rgba(236, 72, 153, 0.12) 0%, rgba(15, 21, 35, 0.85) 100%);
    }

    .dao-summary-lbl {
        font-size: 0.54rem;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dao-summary-val {
        font-family: 'Outfit', sans-serif;
        font-size: 0.92rem;
        font-weight: 900;
        color: #FFFFFF;
        margin-top: 2px;
    }

    /* Qualification Section */
    .dao-qual-container {
        background: rgba(12, 17, 28, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 8px 10px;
        margin-bottom: 10px;
    }

    .dao-qual-title-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .dao-qual-title {
        font-size: 0.64rem;
        font-weight: 800;
        color: #FACD15;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .dao-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }

    @media (max-width: 768px) {
        .dao-cards-grid {
            grid-template-columns: 1fr;
        }
    }

    .dao-pool-card {
        background: rgba(18, 24, 40, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 8px 8px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 6px;
    }

    .dao-pool-card.plat { border-top: 2px solid #38BDF8; }
    .dao-pool-card.gold { border-top: 2px solid #F59E0B; }
    .dao-pool-card.diam { border-top: 2px solid #EC4899; }

    .dao-pool-card.is-active {
        background: linear-gradient(180deg, rgba(16, 185, 129, 0.12) 0%, rgba(18, 24, 40, 0.8) 100%);
        border-color: rgba(16, 185, 129, 0.4);
    }

    .dao-pool-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dao-pool-name {
        font-size: 0.72rem;
        font-weight: 800;
        color: #FFFFFF;
        text-transform: uppercase;
    }

    .dao-badge {
        font-size: 0.50rem;
        font-weight: 800;
        padding: 1.5px 5px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .dao-badge.active { background: rgba(16, 185, 129, 0.2); color: #6EE7B7; border: 1px solid #10B981; }
    .dao-badge.locked { background: rgba(239, 68, 68, 0.15); color: #FCA5A5; border: 1px solid rgba(239, 68, 68, 0.4); }

    .dao-criteria-list {
        font-size: 0.56rem;
        color: #94A3B8;
        line-height: 1.4;
    }

    .dao-criteria-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dao-criteria-item b { color: #E2E8F0; }
    .status-check { font-size: 0.60rem; }
    .status-check.ok { color: #10B981; }
    .status-check.fail { color: #EF4444; }

    /* Controls */
    .dao-controls-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
    }

    .dao-tab-group {
        display: flex;
        gap: 2px;
        background: rgba(0, 0, 0, 0.4);
        padding: 2px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .dao-tab-btn {
        background: transparent;
        border: none;
        color: #94A3B8;
        font-size: 0.58rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        cursor: pointer;
    }

    .dao-tab-btn.active {
        background: #EAB308;
        color: #000000;
        font-weight: 800;
    }

    /* Ledger Cards */
    .dao-ledger-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 5px;
    }

    .dao-item-card {
        background: linear-gradient(155deg, rgba(16, 22, 36, 0.95), rgba(9, 12, 20, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 7px 9px;
    }

    .dao-item-card.type-1 { border-left: 3px solid #38BDF8; }
    .dao-item-card.type-2 { border-left: 3px solid #F59E0B; }
    .dao-item-card.type-3 { border-left: 3px solid #EC4899; }

    .dao-item-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 3px;
    }

    .dao-tag {
        font-size: 0.54rem;
        font-weight: 800;
        padding: 1px 4px;
        border-radius: 3px;
        text-transform: uppercase;
    }

    .dao-tag.tag-1 { background: rgba(56, 189, 248, 0.18); color: #7DD3FC; border: 1px solid rgba(56, 189, 248, 0.4); }
    .dao-tag.tag-2 { background: rgba(245, 158, 11, 0.18); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.4); }
    .dao-tag.tag-3 { background: rgba(236, 72, 153, 0.18); color: #F472B6; border: 1px solid rgba(236, 72, 153, 0.4); }

    .dao-item-date { font-size: 0.58rem; color: #64748B; }

    .dao-item-amount-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
    }

    .dao-amount-usdt {
        font-family: 'Outfit', sans-serif;
        font-size: 0.90rem;
        font-weight: 800;
        color: #10B981;
    }

    .dao-amount-tokens {
        font-size: 0.64rem;
        color: #A78BFA;
        font-weight: 700;
    }

    .dao-turnover-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.54rem;
        color: #8E99A8;
        padding-top: 2px;
        margin-top: 3px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="dao-hud-card">
            <!-- Header -->
            <div class="dao-card-head">
                <div class="dao-head-left">
                    <i class="fas fa-crown dao-head-icon"></i>
                    <div style="flex:1;">
                        <div class="dao-head-title-row">
                            <span class="dao-head-title">DAO GOVERNANCE POOLS</span>
                            <span class="dao-rate-pill">9.5% TOTAL DAO TURNOVER</span>
                        </div>
                        <div class="dao-head-subtitle">Platinum (2.5%), Golden (3.0%) & Diamond (4.0%) Weekly Pools</div>
                    </div>
                </div>
            </div>

            <!-- Summary Bar -->
            <div class="dao-summary-grid">
                <div class="dao-summary-pill grand-pill">
                    <span class="dao-summary-lbl"><i class="fas fa-coins text-warning"></i> TOTAL EARNED</span>
                    <span class="dao-summary-val">${{ number_format($grandTotalUsdt, 2) }}</span>
                </div>
                <div class="dao-summary-pill plat-pill">
                    <span class="dao-summary-lbl"><i class="fas fa-gem" style="color: #38BDF8;"></i> PLATINUM 2.5%</span>
                    <span class="dao-summary-val">${{ number_format($platinumTotalUsdt, 2) }}</span>
                </div>
                <div class="dao-summary-pill gold-pill">
                    <span class="dao-summary-lbl"><i class="fas fa-award" style="color: #F59E0B;"></i> GOLDEN 3.0%</span>
                    <span class="dao-summary-val">${{ number_format($goldenTotalUsdt, 2) }}</span>
                </div>
                <div class="dao-summary-pill diam-pill">
                    <span class="dao-summary-lbl"><i class="fas fa-crown" style="color: #EC4899;"></i> DIAMOND 4.0%</span>
                    <span class="dao-summary-val">${{ number_format($diamondTotalUsdt, 2) }}</span>
                </div>
            </div>

            <!-- Live Qualification Cards -->
            @php
                $daoQualifications = $daoQualifications ?? ($userDaoDetails ?? $userDetail->getDaoDetails());
                $platQual = $daoQualifications[1] ?? null;
                $goldQual = $daoQualifications[2] ?? null;
                $diamQual = $daoQualifications[3] ?? null;
            @endphp
            <div class="dao-qual-container">
                <div class="dao-qual-title-bar">
                    <span class="dao-qual-title"><i class="fas fa-shield-alt text-warning"></i> DAO Qualification Status</span>
                    <span style="font-size: 0.52rem; color: #64748B;">Mondays at 01:55 AM Distribution</span>
                </div>

                <div class="dao-cards-grid">
                    <!-- Platinum DAO Card -->
                    @if($platQual)
                    <div class="dao-pool-card plat {{ ($platQual['is_purchased'] ?? false) ? 'is-active' : '' }}">
                        <div class="dao-pool-head">
                            <span class="dao-pool-name" style="color: #38BDF8;">Platinum DAO (2.5%)</span>
                            <span class="dao-badge {{ ($platQual['is_purchased'] ?? false) ? 'active' : 'locked' }}">
                                {{ ($platQual['is_purchased'] ?? false) ? 'PURCHASED' : 'LOCKED' }}
                            </span>
                        </div>
                        <div class="dao-criteria-list">
                            <div class="dao-criteria-item">
                                <span>Package: <b>${{ number_format($platQual['package_amount'], 0) }}</b></span>
                                <i class="fas {{ ($platQual['is_purchased'] ?? false) ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Rank Required: <b>V2 Rank</b></span>
                                <i class="fas {{ $platQual['rank_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Time Limit: <b>90 Days ({{ $platQual['days_registered'] }}d)</b></span>
                                <i class="fas {{ $platQual['days_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Member Limit: <b>{{ $platQual['current_members'] }}/{{ $platQual['max_members'] }}</b></span>
                                <i class="fas {{ $platQual['slots_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item" style="margin-top: 2px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 2px;">
                                <span>Capping: <b>3X (${{ number_format($platQual['max_capping'], 0) }})</b></span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Golden DAO Card -->
                    @if($goldQual)
                    <div class="dao-pool-card gold {{ ($goldQual['is_purchased'] ?? false) ? 'is-active' : '' }}">
                        <div class="dao-pool-head">
                            <span class="dao-pool-name" style="color: #F59E0B;">Golden DAO (3.0%)</span>
                            <span class="dao-badge {{ ($goldQual['is_purchased'] ?? false) ? 'active' : 'locked' }}">
                                {{ ($goldQual['is_purchased'] ?? false) ? 'PURCHASED' : 'LOCKED' }}
                            </span>
                        </div>
                        <div class="dao-criteria-list">
                            <div class="dao-criteria-item">
                                <span>Package: <b>${{ number_format($goldQual['package_amount'], 0) }}</b></span>
                                <i class="fas {{ ($goldQual['is_purchased'] ?? false) ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Rank Required: <b>V3 Rank</b></span>
                                <i class="fas {{ $goldQual['rank_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Time Limit: <b>90 Days ({{ $goldQual['days_registered'] }}d)</b></span>
                                <i class="fas {{ $goldQual['days_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Member Limit: <b>{{ $goldQual['current_members'] }}/{{ $goldQual['max_members'] }}</b></span>
                                <i class="fas {{ $goldQual['slots_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item" style="margin-top: 2px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 2px;">
                                <span>Capping: <b>3.5X (${{ number_format($goldQual['max_capping'], 0) }})</b></span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Diamond DAO Card -->
                    @if($diamQual)
                    <div class="dao-pool-card diam {{ ($diamQual['is_purchased'] ?? false) ? 'is-active' : '' }}">
                        <div class="dao-pool-head">
                            <span class="dao-pool-name" style="color: #EC4899;">Diamond DAO (4.0%)</span>
                            <span class="dao-badge {{ ($diamQual['is_purchased'] ?? false) ? 'active' : 'locked' }}">
                                {{ ($diamQual['is_purchased'] ?? false) ? 'PURCHASED' : 'LOCKED' }}
                            </span>
                        </div>
                        <div class="dao-criteria-list">
                            <div class="dao-criteria-item">
                                <span>Package: <b>${{ number_format($diamQual['package_amount'], 0) }}</b></span>
                                <i class="fas {{ ($diamQual['is_purchased'] ?? false) ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Rank Required: <b>V3 Rank</b></span>
                                <i class="fas {{ $diamQual['rank_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Time Limit: <b>90 Days ({{ $diamQual['days_registered'] }}d)</b></span>
                                <i class="fas {{ $diamQual['days_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item">
                                <span>Member Limit: <b>{{ $diamQual['current_members'] }}/{{ $diamQual['max_members'] }} (Top 50)</b></span>
                                <i class="fas {{ $diamQual['slots_ok'] ? 'fa-check-circle status-check ok' : 'fa-times-circle status-check fail' }}"></i>
                            </div>
                            <div class="dao-criteria-item" style="margin-top: 2px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 2px;">
                                <span>Capping: <b>4X (${{ number_format($diamQual['max_capping'], 0) }})</b></span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tab Controls -->
            <div class="dao-controls-bar">
                <div class="dao-tab-group">
                    <button class="dao-tab-btn active" onclick="filterDaoCards('all', this)">ALL DAO</button>
                    <button class="dao-tab-btn" onclick="filterDaoCards('1', this)">PLATINUM (2.5%)</button>
                    <button class="dao-tab-btn" onclick="filterDaoCards('2', this)">GOLDEN (3.0%)</button>
                    <button class="dao-tab-btn" onclick="filterDaoCards('3', this)">DIAMOND (4.0%)</button>
                </div>
            </div>

            <!-- Ledger Entries -->
            @if(count($incomes) > 0)
                <div class="dao-ledger-grid">
                    @foreach($incomes as $inc)
                        <div class="dao-item-card type-{{ $inc->dao_type }}" data-dao-type="{{ $inc->dao_type }}">
                            <div class="dao-item-head">
                                <span class="dao-tag tag-{{ $inc->dao_type }}">{{ $inc->dao_name }}</span>
                                <span class="dao-item-date"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($inc->created_at)->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="dao-item-amount-row">
                                <span class="dao-amount-usdt">+${{ number_format($inc->amount, 2) }} USDT</span>
                            </div>
                            @if($inc->distribution)
                                <div class="dao-turnover-meta">
                                    <span>Weekly Global Turnover: ${{ number_format($inc->distribution->total_weekly_business, 2) }}</span>
                                    <span>Shared with {{ $inc->distribution->qualified_members }} Members</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 16px; text-align: center; background: rgba(15,20,32,0.5); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; color: #64748B; font-size: 0.70rem;">
                    <i class="fas fa-crown" style="font-size: 1.2rem; color: #EAB308; margin-bottom: 4px; display: block;"></i>
                    No DAO Weekly Dividends Credited Yet.<br>
                    <span style="font-size: 0.60rem; color: #94A3B8;">Qualify for Platinum ($3,333 & V2), Golden ($5,555 & V3), or Diamond ($10,000 & V3) within 90 days to earn weekly turnover dividends!</span>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterDaoCards(type, btn) {
    document.querySelectorAll('.dao-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.dao-item-card');
    cards.forEach(card => {
        if (type === 'all' || card.getAttribute('data-dao-type') === type) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
