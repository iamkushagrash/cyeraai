@extends('luckydraw-admin.layout')

@section('title', 'Deployer Overview & Analytics')
@section('header_title', 'System Overview & Live Metrics')

@section('content')
<style>
    .grid-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .grid-pools {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .pool-card {
        background: var(--bg-card);
        border: 1px solid var(--border-dim);
        border-radius: 12px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.2s;
    }

    .pool-card:hover {
        border-color: rgba(245, 166, 35, 0.4);
        transform: translateY(-2px);
    }

    .pool-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .pool-title {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: #FFF;
    }

    .pool-tag {
        font-size: 11px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
        background: rgba(245, 166, 35, 0.15);
        color: var(--gold-light);
        border: 1px solid rgba(245, 166, 35, 0.3);
    }

    .pool-progress-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 3px;
        margin: 14px 0 10px 0;
        overflow: hidden;
    }

    .pool-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #F5A623, #FFD700);
        border-radius: 3px;
        transition: width 0.3s;
    }

    .pool-stat-row {
        display: flex;
        justify-content: space-between;
        font-size: 12.5px;
        color: var(--text-sub);
        margin-bottom: 6px;
    }

    .pool-stat-row strong {
        color: #FFF;
    }

    .contract-banner-bar {
        background: linear-gradient(90deg, rgba(245, 166, 35, 0.08), rgba(0, 0, 0, 0.4));
        border: 1px solid rgba(245, 166, 35, 0.25);
        border-radius: 12px;
        padding: 18px 24px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .cb-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .cb-item .label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--gold-light);
        letter-spacing: 0.8px;
    }

    .cb-item .val {
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
        color: #FFF;
    }
</style>

<!-- Contract Addresses Banner -->
<div class="contract-banner-bar">
    <div class="cb-item">
        <span class="label"><i class="fas fa-arrow-circle-down"></i> Deposit Contract (Collections)</span>
        <span class="val">
            <a href="https://bscscan.com/address/{{ $contracts['depositContract'] }}" target="_blank" class="hash-code">
                {{ substr($contracts['depositContract'], 0, 8) }}...{{ substr($contracts['depositContract'], -6) }}
                <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
            </a>
        </span>
    </div>

    <div class="cb-item">
        <span class="label"><i class="fas fa-vault"></i> Prize Vault Contract (Payout Liquidity)</span>
        <span class="val">
            <a href="https://bscscan.com/address/{{ $contracts['vaultContract'] }}" target="_blank" class="hash-code">
                {{ substr($contracts['vaultContract'], 0, 8) }}...{{ substr($contracts['vaultContract'], -6) }}
                <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
            </a>
        </span>
    </div>

    <div class="cb-item">
        <span class="label"><i class="fas fa-wallet"></i> Whitelisted Collector Wallet</span>
        <span class="val">
            <a href="https://bscscan.com/address/{{ $contracts['collectorWallet'] }}" target="_blank" class="hash-code">
                {{ substr($contracts['collectorWallet'], 0, 8) }}...{{ substr($contracts['collectorWallet'], -6) }}
                <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
            </a>
        </span>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="{{ url('/luckydraw/admin/funds') }}" class="btn-gold" style="padding: 8px 16px; font-size: 12.5px;">
            <i class="fas fa-paper-plane"></i> Transfer Funds
        </a>
    </div>
</div>

<!-- Primary Stats Grid -->
<div class="grid-stats">
    <!-- Deposit Contract Live Balance -->
    <div class="stat-card gold-accent">
        <div class="card-head">
            <span class="card-label">Deposit Contract USDT</span>
            <div class="card-icon"><i class="fas fa-coins"></i></div>
        </div>
        <div class="card-val">${{ number_format($onChain['depositUsdt'], 2) }}</div>
        <div class="card-sub">
            <span class="badge-pill badge-green"><i class="fas fa-circle" style="font-size: 6px;"></i> On-Chain</span>
            <span>Collected tickets balance</span>
        </div>
    </div>

    <!-- Vault Contract Live Liquidity -->
    <div class="stat-card">
        <div class="card-head">
            <span class="card-label">Vault Payout Liquidity</span>
            <div class="card-icon"><i class="fas fa-vault"></i></div>
        </div>
        <div class="card-val">${{ number_format($onChain['vaultUsdt'], 2) }}</div>
        <div class="card-sub">
            <span class="badge-pill badge-gold"><i class="fas fa-trophy"></i> Prize Pool</span>
            <span>Available for winner claims</span>
        </div>
    </div>

    <!-- Total Collected Volume -->
    <div class="stat-card">
        <div class="card-head">
            <span class="card-label">Total Volume (DB)</span>
            <div class="card-icon"><i class="fas fa-chart-line"></i></div>
        </div>
        <div class="card-val">${{ number_format($metrics['totalCollectedDb'], 2) }}</div>
        <div class="card-sub">
            <span>{{ $metrics['totalTicketsAllTime'] }} tickets issued all-time</span>
        </div>
    </div>

    <!-- Royal Wallet Liability -->
    <div class="stat-card">
        <div class="card-head">
            <span class="card-label">Royal Wallet Pool</span>
            <div class="card-icon"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="card-val">${{ number_format($metrics['royalWalletBalance'], 2) }}</div>
        <div class="card-sub">
            <span>{{ $metrics['totalWinners'] }} winners, ${{ number_format($metrics['totalClaimedOnChain'], 2) }} claimed</span>
        </div>
    </div>
</div>

<!-- 4 POOLS LIVE STATUS CARDS -->
<h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; margin-bottom: 16px; color: #FFF; display: flex; align-items: center; gap: 8px;">
    <i class="fas fa-dice" style="color: var(--gold-primary);"></i> Real-Time Pool Progress (500 Slots Per Round)
</h3>

<div class="grid-pools">
    @foreach($poolStats as $p)
    @php
        $pct = round(($p['current_members'] / 500) * 100, 1);
    @endphp
    <div class="pool-card">
        <div class="pool-card-head">
            <div class="pool-title">{{ $p['name'] }}</div>
            <span class="pool-tag">${{ number_format($p['price'], 0) }} USDT</span>
        </div>

        <div class="pool-stat-row">
            <span>Active Round:</span>
            <strong>#{{ $p['current_round'] }}</strong>
        </div>
        <div class="pool-stat-row">
            <span>Slots Filled:</span>
            <strong>{{ $p['current_members'] }} / 500 ({{ $pct }}%)</strong>
        </div>
        <div class="pool-stat-row">
            <span>Slots Remaining:</span>
            <strong style="color: var(--gold-light);">{{ $p['slots_left'] }}</strong>
        </div>

        <div class="pool-progress-bar">
            <div class="pool-progress-fill" style="width: {{ $pct }}%;"></div>
        </div>

        <div class="pool-stat-row" style="margin-top: 10px; font-size: 11.5px;">
            <span>Completed Rounds:</span>
            <span>{{ $p['completed_rounds'] }}</span>
        </div>
        <div class="pool-stat-row" style="font-size: 11.5px;">
            <span>Total Tickets Sold:</span>
            <span>{{ $p['total_tickets'] }}</span>
        </div>

        <div style="margin-top: 14px; display: flex; gap: 8px;">
            <a href="{{ url('/luckydraw/admin/rounds?tier=' . $p['tier']) }}" class="btn-dim" style="flex: 1; justify-content: center; padding: 6px 12px; font-size: 12px;">
                <i class="fas fa-list"></i> View Round
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- TWO COLUMN RECENT LOGS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 20px; margin-top: 10px;">
    <!-- Recent Participants -->
    <div class="cyber-table-wrap">
        <div class="cyber-table-head">
            <h3><i class="fas fa-ticket-alt" style="color: var(--gold-primary); margin-right: 6px;"></i> Recent Ticket Purchases</h3>
            <a href="{{ url('/luckydraw/admin/rounds') }}" class="btn-dim" style="padding: 4px 10px; font-size: 11px;">View All</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Pool / Round</th>
                        <th>Wallet</th>
                        <th>Tx Hash</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentParticipants as $pt)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#{{ $pt->ticket_number }}</strong></td>
                        <td>Tier {{ $pt->pool_tier }} (R#{{ $pt->round ? $pt->round->round_number : '1' }})</td>
                        <td>
                            <span style="font-family: 'JetBrains Mono'; font-size: 11px;">
                                {{ substr($pt->wallet_address, 0, 6) }}...{{ substr($pt->wallet_address, -4) }}
                            </span>
                        </td>
                        <td>
                            @if(strpos($pt->tx_hash, '0x') === 0)
                            <a href="https://bscscan.com/tx/{{ $pt->tx_hash }}" target="_blank" class="hash-code">
                                {{ substr($pt->tx_hash, 0, 6) }}...
                                <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                            </a>
                            @else
                            <span style="font-size: 10.5px; color: var(--text-muted);">{{ substr($pt->tx_hash, 0, 10) }}</span>
                            @endif
                        </td>
                        <td style="font-size: 11px; color: var(--text-muted);">
                            {{ $pt->created_at ? $pt->created_at->diffForHumans() : 'Just now' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">No participants yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Winners -->
    <div class="cyber-table-wrap">
        <div class="cyber-table-head">
            <h3><i class="fas fa-trophy" style="color: var(--gold-primary); margin-right: 6px;"></i> Recent Winners Hall of Fame</h3>
            <a href="{{ url('/luckydraw/admin/winners') }}" class="btn-dim" style="padding: 4px 10px; font-size: 11px;">View All</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Prize</th>
                        <th>Round</th>
                        <th>Winner Wallet</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentWinners as $w)
                    <tr>
                        <td>
                            <span class="badge-pill badge-gold">Rank #{{ $w->rank }}</span>
                        </td>
                        <td><strong style="color: var(--gold-light);">${{ number_format($w->prize_amount, 2) }}</strong></td>
                        <td>Pool {{ $w->pool_tier }} (R#{{ $w->round ? $w->round->round_number : '1' }})</td>
                        <td>
                            <span style="font-family: 'JetBrains Mono'; font-size: 11px;">
                                {{ substr($w->wallet_address, 0, 6) }}...{{ substr($w->wallet_address, -4) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-pill badge-green"><i class="fas fa-check"></i> Credited</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">No completed draw rounds yet. Draws trigger instantly when 500 tickets are filled.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
