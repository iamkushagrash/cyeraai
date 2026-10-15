@extends('luckydraw-admin.layout')

@section('title', 'Pools & Rounds Explorer')
@section('header_title', 'Pools, Rounds & Participant Tickets')

@section('content')
<style>
    .pool-tabs-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--border-dim);
        padding-bottom: 14px;
        overflow-x: auto;
    }

    .pool-tab-btn {
        background: var(--bg-card);
        border: 1px solid var(--border-dim);
        color: var(--text-sub);
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .pool-tab-btn:hover {
        color: #FFF;
        border-color: rgba(245, 166, 35, 0.4);
    }

    .pool-tab-btn.active {
        background: linear-gradient(135deg, rgba(245, 166, 35, 0.2), rgba(245, 166, 35, 0.05));
        border-color: var(--gold-primary);
        color: var(--gold-light);
    }

    .round-detail-header {
        background: var(--bg-card);
        border: 1px solid var(--border-dim);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .slots-pill-group {
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .slot-counter-badge {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: #FFF;
    }
</style>

<!-- POOL TABS -->
<div class="pool-tabs-bar">
    @foreach($pools as $p)
    <a href="{{ url('/luckydraw/admin/rounds?tier=' . $p->pool_tier) }}" class="pool-tab-btn {{ $tier == $p->pool_tier ? 'active' : '' }}">
        <i class="fas fa-dice-{{ $p->pool_tier == 1 ? 'd6' : ($p->pool_tier == 2 ? 'd20' : 'box') }}"></i>
        <span>{{ $p->pool_name }} (${{ number_format($p->ticket_price, 0) }} USDT)</span>
    </a>
    @endforeach
</div>

@if($selectedRound)
<!-- SELECTED ROUND STATUS BANNER -->
<div class="round-detail-header">
    <div>
        <div style="font-size: 11px; font-weight: 700; color: var(--gold-light); text-transform: uppercase; letter-spacing: 0.8px;">
            Tier {{ $selectedRound->pool_tier }} • Selected Round
        </div>
        <h2 style="font-family: 'Outfit'; font-size: 22px; font-weight: 800; color: #FFF; margin-top: 4px;">
            Round #{{ $selectedRound->round_number }}
            @if($selectedRound->status == 0)
                <span class="badge-pill badge-green" style="font-size: 11px; margin-left: 8px;">Active</span>
            @else
                <span class="badge-pill badge-gold" style="font-size: 11px; margin-left: 8px;">Drawn / Completed</span>
            @endif
        </h2>
    </div>

    <div class="slots-pill-group">
        <div>
            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Slots Filled</div>
            <div class="slot-counter-badge" style="color: var(--gold-light);">
                {{ $selectedRound->current_members }} <span style="font-size: 14px; color: var(--text-muted);">/ 500</span>
            </div>
        </div>
        <div style="border-left: 1px solid var(--border-dim); padding-left: 14px;">
            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Remaining</div>
            <div class="slot-counter-badge" style="color: #FFF;">
                {{ max(0, 500 - $selectedRound->current_members) }}
            </div>
        </div>
        <div style="border-left: 1px solid var(--border-dim); padding-left: 14px;">
            <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Total Collected</div>
            <div class="slot-counter-badge" style="color: var(--green-live);">
                ${{ number_format($selectedRound->total_collected, 2) }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- TWO COLUMN LAYOUT: ROUNDS LIST & PARTICIPANTS TABLE -->
<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px;">
    <!-- Left Column: Rounds List for this Pool Tier -->
    <div class="cyber-table-wrap">
        <div class="cyber-table-head">
            <h3><i class="fas fa-history" style="color: var(--gold-primary); margin-right: 6px;"></i> Rounds History</h3>
        </div>
        <div style="max-height: 600px; overflow-y: auto;">
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>Round</th>
                        <th>Status</th>
                        <th>Slots</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rounds as $r)
                    <tr style="{{ ($selectedRound && $selectedRound->id == $r->id) ? 'background: rgba(245, 166, 35, 0.08);' : '' }}">
                        <td><strong>#{{ $r->round_number }}</strong></td>
                        <td>
                            @if($r->status == 0)
                                <span class="badge-pill badge-green" style="font-size: 9.5px;">Active</span>
                            @else
                                <span class="badge-pill badge-gold" style="font-size: 9.5px;">Drawn</span>
                            @endif
                        </td>
                        <td>{{ $r->current_members }}/500</td>
                        <td>
                            <a href="{{ url('/luckydraw/admin/rounds?tier=' . $tier . '&round_id=' . $r->id) }}" class="quick-tag-btn">
                                Select
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">No rounds created.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Participant Tickets for Selected Round -->
    <div class="cyber-table-wrap">
        <div class="cyber-table-head">
            <h3>
                <i class="fas fa-ticket-alt" style="color: var(--gold-primary); margin-right: 6px;"></i>
                Participant Tickets in Round #{{ $selectedRound ? $selectedRound->round_number : '1' }}
            </h3>
            <span class="badge-pill badge-gold">{{ count($participants) }} Participants</span>
        </div>
        <div style="overflow-x: auto; max-height: 600px;">
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>User ID</th>
                        <th>Participant Wallet</th>
                        <th>Amount</th>
                        <th>Tx Hash</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $pt)
                    <tr>
                        <td>
                            <span class="badge-pill badge-gold" style="font-family: 'JetBrains Mono'; font-size: 12px;">
                                #{{ $pt->ticket_number }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #FFF;">UID: {{ $pt->userid }}</span>
                        </td>
                        <td>
                            <a href="https://bscscan.com/address/{{ $pt->wallet_address }}" target="_blank" class="hash-code">
                                {{ substr($pt->wallet_address, 0, 8) }}...{{ substr($pt->wallet_address, -6) }}
                                <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                            </a>
                        </td>
                        <td><strong style="color: #FFF;">${{ number_format($pt->amount_paid, 2) }} USDT</strong></td>
                        <td>
                            @if(strpos($pt->tx_hash, '0x') === 0)
                            <a href="https://bscscan.com/tx/{{ $pt->tx_hash }}" target="_blank" class="hash-code">
                                {{ substr($pt->tx_hash, 0, 8) }}...
                                <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                            </a>
                            @else
                            <span style="color: var(--text-muted); font-size: 11px;">{{ substr($pt->tx_hash, 0, 12) }}</span>
                            @endif
                        </td>
                        <td style="font-size: 11.5px; color: var(--text-muted);">
                            {{ $pt->created_at ? $pt->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 36px;">
                            No tickets bought in this round yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
