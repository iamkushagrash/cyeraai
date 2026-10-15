@extends('layouts.user-mecha')

@section('title', 'Royal Jackpot - Cyera AI')

@section('page-header')
<!-- ============================================================
     ROYAL JACKPOT SLEEK TOP BAR (CLEAN & NON-CLUTTERED)
     ============================================================ -->
<div class="rj-clean-topbar">
    <div class="rj-tb-left">
        <div class="rj-tb-icon">
            <i class="fas fa-crown"></i>
        </div>
        <div>
            <div class="rj-tb-crumbs">
                <a href="{{ url('/User/Dashboard') }}">Dashboard</a> / <span class="active">Royal Jackpot</span>
            </div>
            <h1 class="rj-tb-title">ROYAL JACKPOT</h1>
        </div>
    </div>

    <div class="rj-tb-right">
        <!-- Compact Royal Wallet Capsule -->
        <div class="rj-wallet-pill">
            <i class="fas fa-wallet" style="color: #F5A623; font-size: 13px;"></i>
            <div class="rj-wp-content">
                <span class="rj-wp-lbl">Royal Wallet</span>
                <span class="rj-wp-val">${{ number_format($royalWalletBalance, 2) }}</span>
            </div>
            <button type="button" onclick="openClaimModal()" class="rj-wp-btn" {{ $royalWalletBalance < 1.0 ? 'disabled' : '' }}>
                Claim
            </button>
        </div>

        <button type="button" onclick="refreshStats()" class="rj-btn-ghost" title="Refresh">
            <i class="fas fa-rotate"></i>
        </button>
    </div>
</div>
@endsection

@section('content')
<style>
/* ============================================================
   GLOBAL STYLING: PURE BLACK LUXURY MECHA (INTER SANS-SERIF)
   ============================================================ */
body, .master-app-wrap, .hud-app-shell {
    background: #000000 !important;
}
.cyber-video-bg-container {
    background: #000000 !important;
}
.cyber-nebula-orb {
    display: none !important;
}
#cyberMatrixCanvas {
    display: none !important;
}
.hud-scroll-area {
    background: #000000 !important;
    padding-top: 4px !important;
}
.mecha-inner-body {
    padding: 0 8px !important;
    gap: 12px !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
}

/* 1. Sleek Topbar */
.rj-clean-topbar {
    margin: 4px 8px 8px 8px;
    padding: 10px 14px;
    background: #080A0E;
    border: 1px solid rgba(245, 166, 35, 0.25);
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.9);
}

.rj-tb-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.rj-tb-icon {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, rgba(245, 166, 35, 0.2) 0%, #000000 100%);
    border: 1px solid rgba(245, 166, 35, 0.45);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFD700;
    font-size: 16px;
    box-shadow: 0 0 10px rgba(245, 166, 35, 0.2);
    flex-shrink: 0;
}

.rj-tb-crumbs {
    font-size: 9px;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.rj-tb-crumbs a {
    color: #94A3B8;
    text-decoration: none;
}
.rj-tb-crumbs .active {
    color: #F5A623;
}

.rj-tb-title {
    font-size: 15px;
    font-weight: 900;
    letter-spacing: 0.8px;
    margin: 0;
    color: #FFFFFF;
    line-height: 1;
}

.rj-tb-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rj-wallet-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #000000;
    border: 1px solid rgba(245, 166, 35, 0.35);
    border-radius: 8px;
    padding: 4px 10px;
}

.rj-wp-content {
    display: flex;
    flex-direction: column;
}

.rj-wp-lbl {
    font-size: 8px;
    color: #94A3B8;
    text-transform: uppercase;
    font-weight: 600;
    line-height: 1;
}

.rj-wp-val {
    font-size: 13px;
    font-weight: 800;
    color: #00FF88;
    line-height: 1.2;
}

.rj-wp-btn {
    height: 24px;
    padding: 0 10px;
    background: linear-gradient(135deg, #F5A623 0%, #D97706 100%);
    border: none;
    border-radius: 5px;
    color: #000000;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
    text-transform: uppercase;
    transition: all 0.15s ease;
}

.rj-wp-btn:hover:not(:disabled) {
    filter: brightness(1.15);
}

.rj-wp-btn:disabled {
    background: rgba(255, 255, 255, 0.08);
    color: #64748B;
    cursor: not-allowed;
}

.rj-btn-ghost {
    height: 30px;
    width: 30px;
    background: #000000;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 6px;
    color: #CBD5E1;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}

.rj-btn-ghost:hover {
    border-color: #F5A623;
    color: #F5A623;
}

/* ============================================================
   2. CLEAN 2X2 POOL CARDS (NO CLUTTER, MODERN MECHA)
   ============================================================ */
.rj-cards-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 12px;
    margin-bottom: 12px;
}

@media (max-width: 580px) {
    .rj-cards-grid {
        gap: 8px;
    }
}

.rj-card {
    background: #07090E;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 14px 14px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.85);
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.rj-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.95);
}

.rj-card.tier-1 { border-top: 3px solid #CD7F32; }
.rj-card.tier-1:hover { border-color: rgba(205, 127, 50, 0.5); }

.rj-card.tier-2 { border-top: 3px solid #E2E8F0; }
.rj-card.tier-2:hover { border-color: rgba(226, 232, 240, 0.5); }

.rj-card.tier-3 { border-top: 3px solid #FFD700; }
.rj-card.tier-3:hover { border-color: rgba(255, 215, 0, 0.5); }

.rj-card.tier-4 { border-top: 3px solid #F59E0B; }
.rj-card.tier-4:hover { border-color: rgba(245, 158, 11, 0.5); }

/* Card Top Pill Row */
.rj-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.rj-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 5px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.rj-badge.bronze { background: rgba(205, 127, 50, 0.15); color: #CD7F32; border: 1px solid rgba(205, 127, 50, 0.35); }
.rj-badge.silver { background: rgba(226, 232, 240, 0.15); color: #E2E8F0; border: 1px solid rgba(226, 232, 240, 0.35); }
.rj-badge.gold   { background: rgba(255, 215, 0, 0.15); color: #FFD700; border: 1px solid rgba(255, 215, 0, 0.35); }
.rj-badge.crown  { background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.35); }

.rj-live-tag {
    font-size: 10px;
    font-weight: 600;
    color: #94A3B8;
    display: flex;
    align-items: center;
    gap: 4px;
}

.rj-live-tag .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #00FF88;
    box-shadow: 0 0 6px #00FF88;
}

/* Card Hero: Price & Title */
.rj-card-hero {
    margin-bottom: 12px;
}

.rj-pool-name {
    font-size: 12px;
    font-weight: 600;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.rj-pool-price {
    font-size: 26px;
    font-weight: 900;
    color: #FFFFFF;
    line-height: 1;
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.rj-pool-price small {
    font-size: 11px;
    font-weight: 600;
    color: #64748B;
}

/* Prize Strip */
.rj-prize-strip {
    background: #000000;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    padding: 7px 10px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.rj-ps-lbl {
    font-size: 9.5px;
    color: #94A3B8;
    font-weight: 500;
}

.rj-ps-val {
    font-size: 12px;
    font-weight: 800;
    color: #00FF88;
}

/* Progress Block */
.rj-prog-block {
    margin-bottom: 14px;
}

.rj-prog-meta {
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: #94A3B8;
    margin-bottom: 5px;
    font-weight: 500;
}

.rj-prog-meta strong {
    color: #FFFFFF;
    font-weight: 700;
}

.rj-track {
    width: 100%;
    height: 5px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 4px;
    overflow: hidden;
}

.rj-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.4s ease;
}

.rj-fill.bronze-grad { background: linear-gradient(90deg, #A75D28, #CD7F32); }
.rj-fill.silver-grad { background: linear-gradient(90deg, #94A3B8, #E2E8F0); }
.rj-fill.gold-grad   { background: linear-gradient(90deg, #F59E0B, #FFD700); }
.rj-fill.crown-grad  { background: linear-gradient(90deg, #D97706, #FDE68A); }

/* Card Action Button */
.rj-cta-btn {
    width: 100%;
    height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, #F5A623 0%, #D97706 100%);
    color: #000000;
    font-size: 11.5px;
    font-weight: 800;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.15s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
}

.rj-cta-btn:hover {
    filter: brightness(1.15);
    box-shadow: 0 0 14px rgba(245, 166, 35, 0.4);
}

/* ============================================================
   3. CLEAN TABS CONTAINER (MY TICKETS, WINNERS, ROUNDS)
   ============================================================ */
.rj-tabs-wrap {
    background: #07090E;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.9);
}

.rj-tab-nav {
    display: flex;
    background: #000000;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding: 0 6px;
    overflow-x: auto;
    scrollbar-width: none;
}
.rj-tab-nav::-webkit-scrollbar {
    display: none;
}

.rj-tab-btn {
    background: transparent;
    border: none;
    color: #94A3B8;
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s;
}

.rj-tab-btn.active {
    color: #F5A623;
    border-bottom-color: #F5A623;
    background: rgba(245, 166, 35, 0.06);
}

.rj-table-scroll {
    overflow-x: auto;
    max-height: 280px;
}

.rj-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
}

.rj-table th {
    padding: 8px 12px;
    font-size: 9.5px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    text-align: left;
    background: #000000;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    position: sticky;
    top: 0;
    z-index: 2;
}

.rj-table td {
    padding: 8px 12px;
    color: #CBD5E1;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.rj-table tr:hover td {
    background: rgba(255, 255, 255, 0.02);
}

/* ============================================================
   4. MODALS (DETAIL, 50 WINNERS & JOINING)
   ============================================================ */
.rj-modal-backdrop {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.94);
    backdrop-filter: blur(6px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 14px;
}

.rj-modal-backdrop.active {
    display: flex;
}

.rj-modal-card {
    background: #090C12;
    border: 1px solid rgba(245, 166, 35, 0.35);
    border-radius: 12px;
    max-width: 480px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 16px 18px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.95);
}

.rj-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 10px;
    margin-bottom: 14px;
}

.rj-modal-title {
    font-size: 15px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0;
}

.rj-modal-close {
    background: transparent;
    border: none;
    color: #94A3B8;
    font-size: 16px;
    cursor: pointer;
}

/* Quick Metrics Row in Modal */
.rj-modal-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    background: #000000;
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 14px;
    text-align: center;
}

.rj-mm-item {
    display: flex;
    flex-direction: column;
}

.rj-mm-lbl {
    font-size: 9px;
    color: #64748B;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 2px;
}

.rj-mm-val {
    font-size: 14px;
    font-weight: 800;
}

/* Condition Card Inside Modal */
.rj-condition-banner {
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 14px;
    font-size: 11px;
}

.rj-cond-ok {
    background: rgba(16, 185, 129, 0.12);
    border: 1px solid rgba(16, 185, 129, 0.35);
    color: #A7F3D0;
}

.rj-cond-warn {
    background: rgba(239, 68, 68, 0.12);
    border: 1px solid rgba(239, 68, 68, 0.35);
    color: #FECACA;
}
</style>

<!-- ============================================================
     1. THE 4 ROYAL JACKPOT POOLS (2X2 CLEAN GRID)
     ============================================================ -->
<div class="rj-cards-grid">
    @foreach($pools as $p)
    @php
        $tierClass = 'tier-' . $p->pool_tier;
        if ($p->pool_tier == 1) {
            $badgeColorClass = 'bronze';
            $barClass = 'bronze-grad';
        } elseif ($p->pool_tier == 2) {
            $badgeColorClass = 'silver';
            $barClass = 'silver-grad';
        } elseif ($p->pool_tier == 3) {
            $badgeColorClass = 'gold';
            $barClass = 'gold-grad';
        } else {
            $badgeColorClass = 'crown';
            $barClass = 'crown-grad';
        }
    @endphp
    <div class="rj-card {{ $tierClass }}" onclick="openPoolDetailsModal({{ $p->pool_tier }})">
        <div>
            <!-- Head: Badge & Round -->
            <div class="rj-card-head">
                <span class="rj-badge {{ $badgeColorClass }}">Pool #{{ $p->pool_tier }}</span>
                <span class="rj-live-tag"><span class="dot"></span> Round #{{ $p->activeRound->round_number }}</span>
            </div>

            <!-- Hero: Name & Entry Price -->
            <div class="rj-card-hero">
                <div class="rj-pool-name">{{ $p->pool_name }}</div>
                <div class="rj-pool-price">
                    ${{ number_format($p->ticket_price, 0) }}
                    <small>USDT</small>
                </div>
            </div>

            <!-- Prize Pool Highlight Strip -->
            <div class="rj-prize-strip">
                <span class="rj-ps-lbl">Total Prize Pool</span>
                <span class="rj-ps-val">${{ number_format($p->total_prize_pool, 0) }} USDT</span>
            </div>

            <!-- Progress Bar -->
            <div class="rj-prog-block">
                <div class="rj-prog-meta">
                    <span>Slots Filled</span>
                    <strong id="slots-text-{{ $p->pool_tier }}">{{ $p->slotsFilled }} / 500</strong>
                </div>
                <div class="rj-track">
                    <div class="rj-fill {{ $barClass }}" id="progress-bar-{{ $p->pool_tier }}" style="width: {{ max(2, $p->percentageFilled) }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <button type="button" class="rj-cta-btn">
            View & Join <i class="fas fa-arrow-right"></i>
        </button>
    </div>
    @endforeach
</div>

<!-- ============================================================
     2. BOTTOM TABS: MY TICKETS, HALL OF FAME, COMPLETED ROUNDS
     ============================================================ -->
<div class="rj-tabs-wrap">
    <div class="rj-tab-nav">
        <button class="rj-tab-btn active" onclick="switchTab('my-tickets', this)">
            <i class="fas fa-ticket"></i> My Tickets ({{ $myEntries->total() ?? 0 }})
        </button>
        <button class="rj-tab-btn" onclick="switchTab('hall-of-fame', this)">
            <i class="fas fa-trophy"></i> Hall of Fame
        </button>
        <button class="rj-tab-btn" onclick="switchTab('rounds-history', this)">
            <i class="fas fa-clock-rotate-left"></i> Completed Rounds
        </button>
    </div>

    <!-- Tab 1: My Tickets -->
    <div id="tab-my-tickets" class="rj-tab-pane">
        @if($myEntries->count() > 0)
        <div class="rj-table-scroll">
            <table class="rj-table">
                <thead>
                    <tr>
                        <th>TICKET #</th>
                        <th>POOL</th>
                        <th>ROUND</th>
                        <th>ENTRY</th>
                        <th>STATUS</th>
                        <th>PURCHASED</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myEntries as $entry)
                    <tr>
                        <td><strong style="color: #FFD700; font-family: monospace;">#{{ $entry->ticket_number }}</strong></td>
                        <td>Pool #{{ $entry->pool_tier }} (${{ number_format($entry->amount_paid, 0) }})</td>
                        <td>Round #{{ $entry->round->round_number ?? '-' }}</td>
                        <td style="color: #00FF88; font-weight: 700;">${{ number_format($entry->amount_paid, 2) }}</td>
                        <td>
                            @if(($entry->round->status ?? 0) == 1)
                                @php
                                    $win = \App\LuckyDrawWinner::where('round_id', $entry->round_id)->where('ticket_number', $entry->ticket_number)->first();
                                @endphp
                                @if($win)
                                    <span style="color: #00FF88; font-weight: 800;">🏆 Rank #{{ $win->rank }} (${{ number_format($win->prize_amount, 0) }})</span>
                                @else
                                    <span style="color: #94A3B8;">Completed</span>
                                @endif
                            @else
                                <span style="color: #F5A623; font-weight: 600;"><i class="fas fa-spinner fa-spin"></i> Active Round</span>
                            @endif
                        </td>
                        <td style="font-size: 10px; color: #94A3B8;">{{ $entry->created_at->format('d M, h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 8px 12px;">
            {{ $myEntries->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 24px 12px; color: #64748B; font-size: 12px;">
            <i class="fas fa-ticket" style="font-size: 24px; opacity: 0.3; margin-bottom: 6px; display: block;"></i>
            No Royal Jackpot tickets purchased yet. Click any pool above to enter!
        </div>
        @endif
    </div>

    <!-- Tab 2: Hall of Fame -->
    <div id="tab-hall-of-fame" class="rj-tab-pane" style="display: none;">
        @if($recentWinners->count() > 0)
        <div class="rj-table-scroll">
            <table class="rj-table">
                <thead>
                    <tr>
                        <th>RANK</th>
                        <th>WALLET</th>
                        <th>TICKET #</th>
                        <th>POOL</th>
                        <th>ROUND</th>
                        <th>PRIZE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentWinners as $w)
                    <tr>
                        <td>
                            @if($w->rank == 1) <strong style="color: #FFD700;">🥇 1st Place</strong>
                            @elseif($w->rank == 2) <strong style="color: #E2E8F0;">🥈 2nd Place</strong>
                            @elseif($w->rank == 3) <strong style="color: #CD7F32;">🥉 3rd Place</strong>
                            @else <span style="font-weight: 700;">#{{ $w->rank }}</span>
                            @endif
                        </td>
                        <td style="font-family: monospace;">{{ substr($w->wallet_address, 0, 6) }}...{{ substr($w->wallet_address, -4) }}</td>
                        <td><strong style="color: #FFD700; font-family: monospace;">#{{ $w->ticket_number }}</strong></td>
                        <td>Pool #{{ $w->pool_tier }}</td>
                        <td>Round #{{ $w->round->round_number ?? '-' }}</td>
                        <td style="color: #00FF88; font-weight: 800;">+${{ number_format($w->prize_amount, 0) }} USDT</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 24px 12px; color: #64748B; font-size: 12px;">
            <i class="fas fa-trophy" style="font-size: 24px; opacity: 0.3; margin-bottom: 6px; display: block;"></i>
            Inaugural Round #1 is in progress. The 50 lucky winners will appear here once 500 members join!
        </div>
        @endif
    </div>

    <!-- Tab 3: Completed Rounds -->
    <div id="tab-rounds-history" class="rj-tab-pane" style="display: none;">
        @if($completedRounds->count() > 0)
        <div class="rj-table-scroll">
            <table class="rj-table">
                <thead>
                    <tr>
                        <th>ROUND</th>
                        <th>POOL</th>
                        <th>MEMBERS</th>
                        <th>TOTAL COLLECTED</th>
                        <th>DRAWN AT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedRounds as $rnd)
                    <tr>
                        <td><strong style="color: #FFD700;">Round #{{ $rnd->round_number }}</strong></td>
                        <td>Pool #{{ $rnd->pool_tier }} (${{ number_format($rnd->ticket_price, 0) }})</td>
                        <td>500 / 500</td>
                        <td style="color: #00FF88; font-weight: 700;">${{ number_format($rnd->total_collected, 2) }}</td>
                        <td style="font-size: 10px; color: #94A3B8;">{{ $rnd->drawn_at ? $rnd->drawn_at->format('d M, h:i A') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 24px 12px; color: #64748B; font-size: 12px;">
            <i class="fas fa-clock-rotate-left" style="font-size: 24px; opacity: 0.3; margin-bottom: 6px; display: block;"></i>
            Round #1 is currently collecting entries.
        </div>
        @endif
    </div>
</div>

<!-- ============================================================
     3. POOL DETAILS & JOINING MODAL
     ============================================================ -->
<div id="poolDetailsModal" class="rj-modal-backdrop">
    <div class="rj-modal-card">
        <!-- Header -->
        <div class="rj-modal-header">
            <div>
                <h3 id="mdlPoolTitle" class="rj-modal-title">Royal Starter — Pool #1</h3>
                <span id="mdlRoundTag" style="font-size: 10px; color: #94A3B8;">Round #1 • 500 Members Instant Draw</span>
            </div>
            <button type="button" onclick="closePoolDetailsModal()" class="rj-modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Metrics Row -->
        <div class="rj-modal-metrics">
            <div class="rj-mm-item">
                <span class="rj-mm-lbl">Ticket Price</span>
                <span class="rj-mm-val" id="mdlTicketPrice" style="color: #FFFFFF;">$10</span>
            </div>
            <div class="rj-mm-item">
                <span class="rj-mm-lbl">Total Prize</span>
                <span class="rj-mm-val" id="mdlPrizePool" style="color: #00FF88;">$3,075</span>
            </div>
            <div class="rj-mm-item">
                <span class="rj-mm-lbl">1st Place</span>
                <span class="rj-mm-val" id="mdlFirstPrize" style="color: #FFD700;">$500</span>
            </div>
        </div>

        <!-- Progress inside modal -->
        <div style="background: #000000; border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 8px; padding: 10px; margin-bottom: 14px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                <span style="color: #94A3B8;">Slots Progress</span>
                <strong style="color: #FFFFFF;" id="mdlSlotsText">0 / 500</strong>
            </div>
            <div style="width: 100%; height: 6px; background: rgba(255, 255, 255, 0.08); border-radius: 4px; overflow: hidden; margin-bottom: 4px;">
                <div id="mdlProgressBar" style="height: 100%; width: 2%; background: linear-gradient(90deg, #F5A623, #FFD700); border-radius: 4px;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 10px; color: #64748B;">
                <span id="mdlRemText">500 slots left</span>
                <span>My Tickets: <strong style="color: #FFD700;" id="mdlMyTickets">0</strong></span>
            </div>
        </div>

        <!-- Staking Condition Box -->
        <div id="mdlConditionBox" class="rj-condition-banner">
            <!-- Loaded dynamically by JS -->
        </div>

        <!-- 50 Winners Prize Distribution Table -->
        <div style="margin-bottom: 14px;">
            <div style="font-size: 11px; font-weight: 700; color: #FFD700; margin-bottom: 8px; display: flex; justify-content: space-between;">
                <span><i class="fas fa-trophy"></i> 50 Winners Prize Structure</span>
                <span style="color: #94A3B8; font-weight: 500; font-size: 10px;">Instant Auto-Credit</span>
            </div>
            <div id="mdlPrizesContainer" style="max-height: 190px; overflow-y: auto; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px;">
                <!-- Loaded dynamically by JS -->
            </div>
        </div>

        <!-- Footer Actions -->
        <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
            <button type="button" onclick="closePoolDetailsModal()" class="rj-btn-ghost" style="width: auto; padding: 0 14px; font-size: 11px; height: 36px;">
                Close
            </button>
            <div id="mdlActionBtnContainer" style="flex: 1;">
                <!-- Dynamic Enter or Stake button -->
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     4. MODAL: ROYAL WALLET CLAIM
     ============================================================ -->
<div id="claimModal" class="rj-modal-backdrop">
    <div class="rj-modal-card">
        <div class="rj-modal-header">
            <h3 class="rj-modal-title" style="color: #00FF88;">
                <i class="fas fa-arrow-up-right-from-square"></i> Claim Royal Wallet
            </h3>
            <button type="button" onclick="closeClaimModal()" class="rj-modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="background: #000000; border-radius: 8px; padding: 10px 12px; margin-bottom: 12px; border: 1px solid rgba(245, 166, 35, 0.25);">
            <div style="font-size: 9.5px; color: #94A3B8; text-transform: uppercase;">Available Royal Balance</div>
            <div style="font-size: 20px; font-weight: 900; color: #00FF88;">
                ${{ number_format($royalWalletBalance, 2) }} <small style="font-size: 11px; color: #A7F3D0;">USDT</small>
            </div>
        </div>

        <div style="margin-bottom: 12px;">
            <label style="font-size: 11px; color: #CBD5E1; display: block; margin-bottom: 4px; font-weight: 600;">Withdraw Amount (USDT):</label>
            <div style="display: flex; gap: 6px;">
                <input type="number" id="claimAmountInput" value="{{ $royalWalletBalance }}" max="{{ $royalWalletBalance }}" min="1.00" step="0.01" style="flex: 1; height: 34px; background: #000000; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 6px; padding: 0 10px; color: #FFFFFF; font-size: 12px;">
                <button type="button" onclick="document.getElementById('claimAmountInput').value = '{{ $royalWalletBalance }}'" class="rj-btn-ghost" style="width: auto; padding: 0 12px; color: #FFD700; height: 34px;">
                    MAX
                </button>
            </div>
            <small style="font-size: 10px; color: #64748B; margin-top: 4px; display: block;">Transferred directly to your connected Web3 wallet on BSC.</small>
        </div>

        <div id="claimStatusBox" style="display: none; padding: 8px 10px; border-radius: 6px; margin-bottom: 12px; font-size: 11px;"></div>

        <div style="display: flex; gap: 8px; justify-content: flex-end;">
            <button type="button" onclick="closeClaimModal()" class="rj-btn-ghost" style="width: auto; padding: 0 12px; height: 32px; font-size: 11px;">
                Cancel
            </button>
            <button type="button" id="btnExecuteClaim" onclick="executeClaimWeb3()" class="rj-cta-btn" style="width: auto; padding: 0 16px; height: 32px;">
                Confirm Withdrawal
            </button>
        </div>
    </div>
</div>

<!-- ============================================================
     5. JAVASCRIPT LOGIC
     ============================================================ -->
<script src="https://cdn.jsdelivr.net/npm/ethers@6.13.2/dist/ethers.umd.min.js"></script>
<script>
const CONTRACT_DEPOSIT = "{{ $contracts['depositContract'] }}";
const CONTRACT_VAULT   = "{{ $contracts['vaultContract'] }}";
const CONTRACT_USDT    = "{{ $contracts['usdtToken'] }}";
const CHAIN_ID         = {{ $contracts['chainId'] }};
const IS_DEMO_MODE     = {{ $contracts['demoMode'] ? 'true' : 'false' }};
const CSRF_TOKEN       = "{{ csrf_token() }}";

const IS_ELIGIBLE      = {{ $eligibility['is_eligible'] ? 'true' : 'false' }};
const STAKED_AMOUNT    = {{ $eligibility['staked_amount'] }};

const POOLS_DATA = {
    1: { 
        name: 'Royal Starter', 
        tier: 1, 
        ticketPrice: 10, 
        pool: 3075, 
        p1: 500, p2: 300, p3: 200, p4_10: 100, p11_25: 50, p26_50: 25
    },
    2: { 
        name: 'Royal Classic', 
        tier: 2, 
        ticketPrice: 25, 
        pool: 6400, 
        p1: 1000, p2: 750, p3: 500, p4_10: 200, p11_25: 100, p26_50: 50
    },
    3: { 
        name: 'Royal Elite', 
        tier: 3, 
        ticketPrice: 50, 
        pool: 13500, 
        p1: 2000, p2: 1500, p3: 1000, p4_10: 500, p11_25: 200, p26_50: 100
    },
    4: { 
        name: 'Royal Diamond', 
        tier: 4, 
        ticketPrice: 100, 
        pool: 29500, 
        p1: 5000, p2: 3000, p3: 2000, p4_10: 1000, p11_25: 500, p26_50: 200
    }
};

function switchTab(tabId, btn) {
    document.querySelectorAll('.rj-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.rj-tab-pane').forEach(c => c.style.display = 'none');
    btn.classList.add('active');
    document.getElementById('tab-' + tabId).style.display = 'block';
}

// Open Pool Details & Joining Modal
function openPoolDetailsModal(tier) {
    const p = POOLS_DATA[tier];
    if (!p) return;

    // Slots & Stats from DOM
    const slotsTxt = document.getElementById('slots-text-' + tier) ? document.getElementById('slots-text-' + tier).innerText : '0 / 500';
    const slotsPart = parseInt(slotsTxt) || 0;
    const pctVal = Math.round((slotsPart / 500) * 100);

    // Title & Metric values
    document.getElementById('mdlPoolTitle').innerText = `${p.name} — Pool #${p.tier}`;
    document.getElementById('mdlTicketPrice').innerText = `$${p.ticketPrice} USDT`;
    document.getElementById('mdlPrizePool').innerText = `$${p.pool.toLocaleString()} USDT`;
    document.getElementById('mdlFirstPrize').innerText = `$${p.p1.toLocaleString()} USDT`;

    document.getElementById('mdlSlotsText').innerText = slotsTxt;
    document.getElementById('mdlRemText').innerText = `${500 - slotsPart} slots left`;
    document.getElementById('mdlProgressBar').style.width = Math.max(2, pctVal) + '%';
    
    // Condition Box & CTA Button
    const condBox = document.getElementById('mdlConditionBox');
    const actionBtnBox = document.getElementById('mdlActionBtnContainer');

    if (IS_ELIGIBLE) {
        condBox.className = 'rj-condition-banner rj-cond-ok';
        condBox.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-circle-check" style="font-size: 14px; color: #10B981;"></i>
                <div>
                    <strong>Staking Verified:</strong> You have <strong>$${STAKED_AMOUNT.toFixed(2)} USDT</strong> active staking and are eligible to enter.
                </div>
            </div>`;

        actionBtnBox.innerHTML = `
            <button type="button" onclick="enterPool(${p.tier}, ${p.ticketPrice})" class="rj-cta-btn" id="mdlBtnEnter" style="height: 36px; font-size: 12px;">
                <i class="fas fa-ticket"></i> Buy Ticket ($${p.ticketPrice} USDT)
            </button>`;
    } else {
        condBox.className = 'rj-condition-banner rj-cond-warn';
        condBox.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 8px;">
                <i class="fas fa-lock" style="font-size: 14px; color: #EF4444; margin-top: 1px;"></i>
                <div>
                    <strong>$100+ Staking Required:</strong> You need an active staking of <strong>$100 USDT or above</strong> to participate. (Your active staking: <strong>$${STAKED_AMOUNT.toFixed(2)} USDT</strong>).
                </div>
            </div>`;

        actionBtnBox.innerHTML = `
            <a href="{{ url('/User/Stake') }}" class="rj-cta-btn" style="text-decoration: none; height: 36px; font-size: 12px; background: #EF4444; color: #FFFFFF;">
                <i class="fas fa-plus"></i> Stake $100+ to Unlock
            </a>`;
    }

    // 50 Winners Table
    let tableHtml = `
    <table class="rj-table" style="font-size: 11px;">
        <thead>
            <tr>
                <th>RANK</th>
                <th>WINNERS</th>
                <th>PRIZE / EACH</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong style="color: #FFD700;">🥇 1st Place</strong></td>
                <td>1 Winner</td>
                <td style="color: #00FF88; font-weight: 700;">$${p.p1.toLocaleString()} USDT</td>
                <td>$${p.p1.toLocaleString()}</td>
            </tr>
            <tr>
                <td><strong style="color: #E2E8F0;">🥈 2nd Place</strong></td>
                <td>1 Winner</td>
                <td style="color: #00FF88; font-weight: 700;">$${p.p2.toLocaleString()} USDT</td>
                <td>$${p.p2.toLocaleString()}</td>
            </tr>
            <tr>
                <td><strong style="color: #CD7F32;">🥉 3rd Place</strong></td>
                <td>1 Winner</td>
                <td style="color: #00FF88; font-weight: 700;">$${p.p3.toLocaleString()} USDT</td>
                <td>$${p.p3.toLocaleString()}</td>
            </tr>
            <tr>
                <td><strong>4th - 10th Place</strong></td>
                <td>7 Winners</td>
                <td style="color: #00FF88; font-weight: 700;">$${p.p4_10.toLocaleString()} USDT</td>
                <td>$${(p.p4_10 * 7).toLocaleString()}</td>
            </tr>
            <tr>
                <td><strong>11th - 25th Place</strong></td>
                <td>15 Winners</td>
                <td style="color: #00FF88; font-weight: 700;">$${p.p11_25.toLocaleString()} USDT</td>
                <td>$${(p.p11_25 * 15).toLocaleString()}</td>
            </tr>
            <tr>
                <td><strong>26th - 50th Place</strong></td>
                <td>25 Winners</td>
                <td style="color: #00FF88; font-weight: 700;">$${p.p26_50.toLocaleString()} USDT</td>
                <td>$${(p.p26_50 * 25).toLocaleString()}</td>
            </tr>
            <tr style="background: rgba(245, 166, 35, 0.1);">
                <td colspan="3"><strong style="color: #FFD700;">TOTAL PRIZE POOL</strong></td>
                <td><strong style="color: #FFD700;">$${p.pool.toLocaleString()} USDT</strong></td>
            </tr>
        </tbody>
    </table>`;

    document.getElementById('mdlPrizesContainer').innerHTML = tableHtml;
    document.getElementById('poolDetailsModal').classList.add('active');
}

function closePoolDetailsModal() {
    document.getElementById('poolDetailsModal').classList.remove('active');
}

function openClaimModal() {
    document.getElementById('claimModal').classList.add('active');
}

function closeClaimModal() {
    document.getElementById('claimModal').classList.remove('active');
}

// Enter Pool Function
async function enterPool(tier, price) {
    const btn = document.getElementById('mdlBtnEnter');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    }

    let txHash = null;
    let walletAddress = null;

    try {
        if (window.ethereum) {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            walletAddress = await signer.getAddress();

            if (!IS_DEMO_MODE) {
                const usdtAbi = [
                    "function approve(address spender, uint256 amount) public returns (bool)",
                    "function allowance(address owner, address spender) public view returns (uint256)"
                ];
                const depositAbi = [
                    "function depositJackpot(uint256 poolTier, uint256 roundId, uint256 amountUsdt) external"
                ];

                const usdt = new ethers.Contract(CONTRACT_USDT, usdtAbi, signer);
                const amountWei = ethers.parseUnits(price.toString(), 18);

                const allowance = await usdt.allowance(walletAddress, CONTRACT_DEPOSIT);
                if (allowance < amountWei) {
                    if (btn) btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Approving USDT...';
                    const appTx = await usdt.approve(CONTRACT_DEPOSIT, ethers.MaxUint256);
                    await appTx.wait(1);
                }

                if (btn) btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming on BSC...';
                const depositContract = new ethers.Contract(CONTRACT_DEPOSIT, depositAbi, signer);
                const tx = await depositContract.depositJackpot(tier, 1, amountWei);
                const receipt = await tx.wait(1);
                txHash = receipt.hash;
            } else {
                txHash = 'demo_' + Date.now() + '_' + Math.floor(Math.random() * 9000 + 1000);
            }
        } else {
            txHash = 'web_' + Date.now() + '_' + Math.floor(Math.random() * 9000 + 1000);
        }

        const res = await fetch("{{ url('/User/RoyalJackpot/Enter') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                pool_tier: tier,
                wallet_address: walletAddress,
                tx_hash: txHash
            })
        });

        const data = await res.json();
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to enter Royal Jackpot.');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-ticket"></i> Buy Ticket ($${price} USDT)`;
            }
        }

    } catch (err) {
        console.error("Enter Jackpot error:", err);
        alert(err.message || 'Transaction cancelled or failed.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-ticket"></i> Buy Ticket ($${price} USDT)`;
        }
    }
}

// Execute Claim from Royal Wallet
async function executeClaimWeb3() {
    const amount = parseFloat(document.getElementById('claimAmountInput').value);
    const statusBox = document.getElementById('claimStatusBox');
    const btn = document.getElementById('btnExecuteClaim');

    if (!amount || amount < 1.0) {
        alert('Minimum claim amount is $1.00 USDT');
        return;
    }

    if (!window.ethereum) {
        alert('Please connect MetaMask or TrustWallet to withdraw your Royal Wallet balance.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authorizing...';
    statusBox.style.display = 'block';
    statusBox.style.background = 'rgba(245, 166, 35, 0.12)';
    statusBox.style.color = '#FDE68A';
    statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authorizing withdrawal signature...';

    try {
        const provider = new ethers.BrowserProvider(window.ethereum);
        const signer = await provider.getSigner();
        const userAddress = await signer.getAddress();

        const sigRes = await fetch("{{ url('/User/RoyalJackpot/RequestClaim') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                amount: amount,
                wallet_address: userAddress
            })
        });

        const sigData = await sigRes.json();
        if (sigData.status !== 'success') {
            throw new Error(sigData.message || 'Signature authorization rejected.');
        }

        const auth = sigData.data;
        statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting to RoyalJackpotVault on BSC...';

        let claimTxHash = 'claim_' + Date.now();
        if (!IS_DEMO_MODE) {
            const vaultAbi = [
                "function claimPrize(uint256 amount, uint256 claimId, uint256 expiry, bytes calldata signature) external"
            ];
            const vault = new ethers.Contract(CONTRACT_VAULT, vaultAbi, signer);
            const tx = await vault.claimPrize(auth.amountWei, auth.claimId, auth.expiry, auth.signature);
            const receipt = await tx.wait(1);
            claimTxHash = receipt.hash;
        }

        statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finalizing ledger balance...';
        const confRes = await fetch("{{ url('/User/RoyalJackpot/ConfirmClaim') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                amount: amount,
                claim_id: auth.claimId,
                tx_hash: claimTxHash
            })
        });

        const confData = await confRes.json();
        statusBox.style.background = 'rgba(16, 185, 129, 0.2)';
        statusBox.style.color = '#6EE7B7';
        statusBox.innerHTML = '<i class="fas fa-check-circle"></i> ' + (confData.message || 'Claim completed successfully!');

        setTimeout(() => {
            location.reload();
        }, 1200);

    } catch (err) {
        console.error("Claim error:", err);
        statusBox.style.background = 'rgba(239, 68, 68, 0.2)';
        statusBox.style.color = '#FCA5A5';
        statusBox.innerHTML = '<i class="fas fa-times-circle"></i> ' + (err.message || 'Withdrawal failed.');
        btn.disabled = false;
        btn.innerHTML = 'Confirm Withdrawal';
    }
}

async function refreshStats() {
    try {
        const res = await fetch("{{ url('/User/RoyalJackpot/Stats') }}");
        const json = await res.json();
        if (json.status === 'success' && json.pools) {
            for (const [tier, p] of Object.entries(json.pools)) {
                const slotsText = document.getElementById('slots-text-' + tier);
                const progressBar = document.getElementById('progress-bar-' + tier);

                if (slotsText) slotsText.innerText = `${p.slots_filled} / 500`;
                if (progressBar) progressBar.style.width = Math.max(2, p.percentage) + '%';
            }
        }
    } catch (e) {}
}
setInterval(refreshStats, 10000);
</script>
@endsection
