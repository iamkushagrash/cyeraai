@extends('layouts.user-mecha')

@section('title', 'Global Pool Income - Cyera AI')
@section('page-title', 'Global Turnover Pool Rewards')
@section('page-icon', 'fas fa-layer-group')

@section('content')
<style>
    /* Scoped Clean HUD Container */
    .pool-hud-card {
        padding: 10px 8px;
        background: linear-gradient(160deg, #0A0D15 0%, #05070B 100%);
        border: 1px solid rgba(139, 92, 246, 0.3);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7), 0 0 16px rgba(139, 92, 246, 0.08);
    }

    /* Header */
    .pool-card-head {
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        width: 100%;
    }

    .pool-head-left {
        display: flex;
        align-items: center;
        gap: 6px;
        width: 100%;
    }

    .pool-head-icon {
        font-size: 1.1rem;
        color: #A78BFA;
        filter: drop-shadow(0 0 6px rgba(167, 139, 250, 0.4));
        flex-shrink: 0;
    }

    .pool-head-text {
        display: flex;
        flex-direction: column;
        width: 100%;
        min-width: 0;
    }

    .pool-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        width: 100%;
    }

    .pool-head-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.85rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 0.4px;
        margin: 0;
        line-height: 1.15;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .pool-rate-pill {
        font-size: 0.60rem;
        font-weight: 800;
        padding: 1.5px 6px;
        border-radius: 8px;
        background: rgba(139, 92, 246, 0.15);
        border: 1px solid #8B5CF6;
        color: #DDD6FE;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .pool-head-subtitle {
        font-size: 0.62rem;
        color: #8E99A8;
        margin-top: 1px;
        white-space: nowrap;
    }

    /* Top Stats Bar — 3 In One Row (Always 1 Single Row) */
    .pool-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 5px;
        margin-bottom: 8px;
        width: 100%;
    }

    .pool-summary-pill {
        background: rgba(14, 18, 28, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 5px 3px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        justify-content: center;
        min-width: 0;
    }

    .pool-summary-pill.daily-pill {
        border-top: 2px solid #3B82F6;
        background: linear-gradient(180deg, rgba(59, 130, 246, 0.14) 0%, rgba(14, 18, 28, 0.85) 100%);
    }
    .pool-summary-pill.weekly-pill {
        border-top: 2px solid #10B981;
        background: linear-gradient(180deg, rgba(16, 185, 129, 0.14) 0%, rgba(14, 18, 28, 0.85) 100%);
    }
    .pool-summary-pill.monthly-pill {
        border-top: 2px solid #F59E0B;
        background: linear-gradient(180deg, rgba(245, 158, 11, 0.14) 0%, rgba(14, 18, 28, 0.85) 100%);
    }

    .pool-summary-lbl {
        font-size: 0.54rem;
        font-weight: 800;
        color: #8E99A8;
        letter-spacing: 0.2px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 2px;
        line-height: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pool-summary-val {
        font-family: 'Outfit', sans-serif;
        font-size: 0.90rem;
        font-weight: 900;
        color: #FFFFFF;
        line-height: 1.15;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Ultra Compact Qualification Box */
    .pool-qual-box {
        background: rgba(12, 16, 26, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 6px 8px;
        margin-bottom: 8px;
    }

    .pool-qual-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .pool-qual-head-txt {
        font-size: 0.58rem;
        font-weight: 800;
        color: #94A3B8;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .pool-qual-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 4px;
        width: 100%;
    }

    .qual-mini-card {
        background: rgba(18, 24, 38, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 6px;
        padding: 5px 4px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .qual-mini-card.qualified {
        border-color: rgba(16, 185, 129, 0.4);
        background: linear-gradient(180deg, rgba(16, 185, 129, 0.08) 0%, rgba(18, 24, 38, 0.7) 100%);
    }

    .qual-mini-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2px;
        min-width: 0;
    }

    .qual-mini-title {
        font-size: 0.56rem;
        font-weight: 800;
        color: #FFFFFF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .qual-mini-badge {
        font-size: 0.48rem;
        font-weight: 800;
        padding: 1px 3px;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        flex-shrink: 0;
        line-height: 1;
    }

    .qual-mini-badge.badge-active {
        background: rgba(16, 185, 129, 0.2);
        color: #6EE7B7;
        border: 1px solid #10B981;
    }

    .qual-mini-badge.badge-locked {
        background: rgba(239, 68, 68, 0.15);
        color: #FCA5A5;
        border: 1px solid rgba(239, 68, 68, 0.4);
    }

    .qual-mini-req {
        font-size: 0.52rem;
        color: #94A3B8;
        line-height: 1.15;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .qual-mini-req b {
        color: #E2E8F0;
    }

    .qual-mini-progress {
        height: 2.5px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 1px;
    }

    .qual-mini-progress-fill {
        height: 100%;
        border-radius: 2px;
    }

    /* Controls: Inline Tabs & Search */
    .pool-controls-bar {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 6px;
    }

    .pool-tab-btn-group {
        display: flex;
        gap: 2px;
        background: rgba(0, 0, 0, 0.4);
        padding: 2px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        flex-shrink: 0;
    }

    .pool-tab-btn {
        background: transparent;
        border: none;
        color: #94A3B8;
        font-size: 0.56rem;
        font-weight: 700;
        padding: 3px 6px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .pool-tab-btn.active {
        background: #8B5CF6;
        color: #FFFFFF;
        box-shadow: 0 0 6px rgba(139, 92, 246, 0.35);
    }

    .pool-search-box {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .pool-search-input {
        width: 100%;
        background: rgba(10, 14, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        padding: 3px 5px 3px 20px;
        color: #FFFFFF;
        font-size: 0.62rem;
        outline: none;
    }

    .pool-search-icon {
        position: absolute;
        left: 6px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748B;
        font-size: 0.58rem;
    }

    /* Ledger Cards */
    .pool-ledger-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 5px;
    }

    .pool-item-card {
        background: linear-gradient(155deg, rgba(16, 22, 36, 0.95), rgba(9, 12, 20, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 7px 9px;
        transition: all 0.2s ease;
    }

    .pool-item-card.type-daily { border-left: 3px solid #3B82F6; }
    .pool-item-card.type-weekly { border-left: 3px solid #10B981; }
    .pool-item-card.type-monthly { border-left: 3px solid #F59E0B; }

    .pool-item-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 3px;
    }

    .pool-tag {
        font-size: 0.54rem;
        font-weight: 800;
        padding: 1px 4px;
        border-radius: 3px;
        text-transform: uppercase;
    }

    .pool-tag.tag-daily { background: rgba(59, 130, 246, 0.18); color: #93C5FD; border: 1px solid rgba(59, 130, 246, 0.4); }
    .pool-tag.tag-weekly { background: rgba(16, 185, 129, 0.18); color: #6EE7B7; border: 1px solid rgba(16, 185, 129, 0.4); }
    .pool-tag.tag-monthly { background: rgba(245, 158, 11, 0.18); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.4); }

    .pool-item-date {
        font-size: 0.58rem;
        color: #64748B;
    }

    .pool-item-amount-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .pool-amount-usdt {
        font-family: 'Outfit', sans-serif;
        font-size: 0.90rem;
        font-weight: 800;
        color: #10B981;
    }

    .pool-amount-tokens {
        font-size: 0.64rem;
        color: #A78BFA;
        font-weight: 700;
    }

    .pool-turnover-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.54rem;
        color: #8E99A8;
        padding-top: 2px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .no-data-card {
        padding: 18px;
        text-align: center;
        background: rgba(15, 20, 32, 0.5);
        border: 1px dashed rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #64748B;
        font-size: 0.70rem;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="pool-hud-card">
            <!-- Head Bar -->
            <div class="pool-card-head">
                <div class="pool-head-left">
                    <i class="fas fa-layer-group pool-head-icon"></i>
                    <div class="pool-head-text">
                        <div class="pool-title-row">
                            <span class="pool-head-title">POOL REWARDS</span>
                            <span class="pool-rate-pill">5.0% POOL</span>
                        </div>
                        <span class="pool-head-subtitle">Daily (1.5%), Weekly (1.5%) & Monthly (2.0%) Turnover</span>
                    </div>
                </div>
            </div>

            <!-- Top Summary Row: 3 in 1 Row (Compact) -->
            <div class="pool-summary-grid">
                <!-- Daily -->
                <div class="pool-summary-pill daily-pill">
                    <span class="pool-summary-lbl"><i class="fas fa-sun" style="color: #60A5FA;"></i> DAILY 1.5%</span>
                    <span class="pool-summary-val">${{ number_format($dailyTotalUsdt, 2) }}</span>
                </div>
                <!-- Weekly -->
                <div class="pool-summary-pill weekly-pill">
                    <span class="pool-summary-lbl"><i class="fas fa-calendar-week" style="color: #34D399;"></i> WEEKLY 1.5%</span>
                    <span class="pool-summary-val">${{ number_format($weeklyTotalUsdt, 2) }}</span>
                </div>
                <!-- Monthly -->
                <div class="pool-summary-pill monthly-pill">
                    <span class="pool-summary-lbl"><i class="fas fa-gem" style="color: #FBBF24;"></i> MONTHLY 2%</span>
                    <span class="pool-summary-val">${{ number_format($monthlyTotalUsdt, 2) }}</span>
                </div>
            </div>

            <!-- Compact Live Qualification Hub (Fits 100% Mobile Width) -->
            @php 
                $dQual = $poolQualifications['daily']; 
                $wQual = $poolQualifications['weekly']; 
                $mQual = $poolQualifications['monthly']; 
            @endphp
            <div class="pool-qual-box">
                <div class="pool-qual-head">
                    <span class="pool-qual-head-txt"><i class="fas fa-shield-alt text-warning"></i> Qualification Status</span>
                    <span style="font-size: 0.52rem; color: #64748B;">Live Sync</span>
                </div>
                <div class="pool-qual-grid">
                    <!-- Daily Mini -->
                    <div class="qual-mini-card {{ $dQual['is_qualified'] ? 'qualified' : '' }}">
                        <div class="qual-mini-head">
                            <span class="qual-mini-title">Daily</span>
                            <span class="qual-mini-badge {{ $dQual['is_qualified'] ? 'badge-active' : 'badge-locked' }}">
                                {{ $dQual['is_qualified'] ? 'ACTIVE' : 'LOCK' }}
                            </span>
                        </div>
                        <div class="qual-mini-req">Self: <b>${{ number_format($dQual['current_self'], 0) }}/100</b></div>
                        <div class="qual-mini-progress">
                            <div class="qual-mini-progress-fill" style="width: {{ $dQual['progress_pct'] }}%; background: #3B82F6;"></div>
                        </div>
                    </div>

                    <!-- Weekly Mini -->
                    <div class="qual-mini-card {{ $wQual['is_qualified'] ? 'qualified' : '' }}">
                        <div class="qual-mini-head">
                            <span class="qual-mini-title">Weekly</span>
                            <span class="qual-mini-badge {{ $wQual['is_qualified'] ? 'badge-active' : 'badge-locked' }}">
                                {{ $wQual['is_qualified'] ? 'ACTIVE' : 'LOCK' }}
                            </span>
                        </div>
                        <div class="qual-mini-req">5 Dir: <b>{{ $wQual['current_directs'] }}/5</b></div>
                        <div class="qual-mini-progress">
                            <div class="qual-mini-progress-fill" style="width: {{ $wQual['progress_pct'] }}%; background: #10B981;"></div>
                        </div>
                    </div>

                    <!-- Monthly Mini -->
                    <div class="qual-mini-card {{ $mQual['is_qualified'] ? 'qualified' : '' }}">
                        <div class="qual-mini-head">
                            <span class="qual-mini-title">Monthly</span>
                            <span class="qual-mini-badge {{ $mQual['is_qualified'] ? 'badge-active' : 'badge-locked' }}">
                                {{ $mQual['is_qualified'] ? 'ACTIVE' : 'LOCK' }}
                            </span>
                        </div>
                        <div class="qual-mini-req">15 Dir: <b>{{ $mQual['current_directs'] }}/15</b></div>
                        <div class="qual-mini-progress">
                            <div class="qual-mini-progress-fill" style="width: {{ $mQual['progress_pct'] }}%; background: #F59E0B;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls: Single Row Tabs & Search -->
            <div class="pool-controls-bar">
                <div class="pool-tab-btn-group">
                    <button class="pool-tab-btn active" onclick="filterPoolCards('all', this)">ALL</button>
                    <button class="pool-tab-btn" onclick="filterPoolCards('1', this)">DAILY</button>
                    <button class="pool-tab-btn" onclick="filterPoolCards('2', this)">WEEKLY</button>
                    <button class="pool-tab-btn" onclick="filterPoolCards('3', this)">MONTHLY</button>
                </div>
                <div class="pool-search-box">
                    <i class="fas fa-search pool-search-icon"></i>
                    <input type="text" id="poolSearchInput" class="pool-search-input" placeholder="Search..." onkeyup="searchPoolCards()">
                </div>
            </div>

            <!-- Pool Earnings Ledger -->
            @if(count($incomes) > 0)
                <div class="pool-ledger-grid" id="poolLedgerGrid">
                    @foreach($incomes as $inc)
                        @php
                            $typeClass = $inc->pool_type == 1 ? 'type-daily' : ($inc->pool_type == 2 ? 'type-weekly' : 'type-monthly');
                            $tagClass = $inc->pool_type == 1 ? 'tag-daily' : ($inc->pool_type == 2 ? 'tag-weekly' : 'tag-monthly');
                            $typeName = $inc->pool_type == 1 ? 'Daily Pool' : ($inc->pool_type == 2 ? 'Weekly Pool' : 'Monthly Pool');
                        @endphp
                        <div class="pool-item-card {{ $typeClass }}" data-pool-type="{{ $inc->pool_type }}">
                            <div class="pool-item-head">
                                <span class="pool-tag {{ $tagClass }}">{{ $typeName }}</span>
                                <span class="pool-item-date"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($inc->created_at)->format('d M, h:i A') }}</span>
                            </div>
                            <div class="pool-item-amount-row">
                                <span class="pool-amount-usdt">+${{ number_format($inc->amt_usdt, 2) }}</span>
                                <span class="pool-amount-tokens">{{ number_format($inc->amount, 2) }} CAI</span>
                            </div>
                            @if($inc->distribution)
                                <div class="pool-turnover-meta">
                                    <span>Turnover: ${{ number_format($inc->distribution->total_turnover, 0) }}</span>
                                    <span>Shared with {{ $inc->distribution->eligible_users_count }} Members</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data-card">
                    <i class="fas fa-layer-group" style="font-size: 1.3rem; color: #475569; margin-bottom: 4px; display: block;"></i>
                    No pool dividends credited yet.<br>
                    <span style="font-size: 0.62rem; color: #94A3B8;">Qualify for Daily, Weekly or Monthly pools to earn global turnover dividends!</span>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterPoolCards(type, btn) {
    document.querySelectorAll('.pool-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.pool-item-card');
    cards.forEach(card => {
        if (type === 'all' || card.getAttribute('data-pool-type') === type) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function searchPoolCards() {
    const query = document.getElementById('poolSearchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.pool-item-card');
    cards.forEach(card => {
        const text = card.innerText.toLowerCase();
        if (text.includes(query)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
