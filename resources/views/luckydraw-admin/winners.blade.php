@extends('luckydraw-admin.layout')

@section('title', 'Winners & Prize Claims Ledger')
@section('header_title', 'Winners Hall of Fame & Prize Payouts')

@section('content')
<style>
    .filter-bar {
        background: var(--bg-card);
        border: 1px solid var(--border-dim);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-select {
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid var(--border-dim);
        padding: 8px 12px;
        border-radius: 6px;
        color: #FFF;
        font-size: 13px;
        outline: none;
    }

    .rank-badge-1 {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: #000;
        font-weight: 800;
    }

    .rank-badge-2 {
        background: linear-gradient(135deg, #E0E0E0, #9E9E9E);
        color: #000;
        font-weight: 800;
    }

    .rank-badge-3 {
        background: linear-gradient(135deg, #CD7F32, #8B4513);
        color: #FFF;
        font-weight: 800;
    }
</style>

<!-- Filter Controls -->
<form method="GET" action="{{ url('/luckydraw/admin/winners') }}" class="filter-bar">
    <div class="filter-item">
        <label class="form-label" style="margin: 0;">Pool Tier:</label>
        <select name="tier" class="filter-select" onchange="this.form.submit()">
            <option value="">All Pools</option>
            @foreach($pools as $p)
                <option value="{{ $p->pool_tier }}" {{ $tier == $p->pool_tier ? 'selected' : '' }}>
                    {{ $p->pool_name }} (${{ number_format($p->ticket_price, 0) }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="filter-item">
        <label class="form-label" style="margin: 0;">Round #:</label>
        <input type="number" name="round" value="{{ $roundNumber }}" class="filter-select" placeholder="e.g. 1" style="width: 80px;">
    </div>

    <button type="submit" class="btn-dim" style="padding: 6px 14px; font-size: 12px;">
        <i class="fas fa-filter"></i> Filter
    </button>
    <a href="{{ url('/luckydraw/admin/winners') }}" class="btn-dim" style="padding: 6px 14px; font-size: 12px;">
        Reset
    </a>
</form>

<!-- WINNERS TABLE -->
<div class="cyber-table-wrap">
    <div class="cyber-table-head">
        <h3><i class="fas fa-trophy" style="color: var(--gold-primary); margin-right: 6px;"></i> Lucky Draw Winners Records</h3>
        <span class="badge-pill badge-gold">{{ $winners->total() }} Total Winners</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="cyber-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Pool / Round</th>
                    <th>Ticket #</th>
                    <th>User ID</th>
                    <th>Winner Wallet</th>
                    <th>Prize Amount</th>
                    <th>Royal Wallet</th>
                    <th>Date Won</th>
                </tr>
            </thead>
            <tbody>
                @forelse($winners as $w)
                @php
                    $rankClass = 'badge-gold';
                    if ($w->rank == 1) $rankClass = 'rank-badge-1';
                    elseif ($w->rank == 2) $rankClass = 'rank-badge-2';
                    elseif ($w->rank == 3) $rankClass = 'rank-badge-3';
                @endphp
                <tr>
                    <td>
                        <span class="badge-pill {{ $rankClass }}">
                            @if($w->rank <= 3)<i class="fas fa-crown"></i>@endif
                            Rank #{{ $w->rank }}
                        </span>
                    </td>
                    <td>
                        Pool {{ $w->pool_tier }} • <strong>Round #{{ $w->round ? $w->round->round_number : '1' }}</strong>
                    </td>
                    <td><strong style="color: var(--gold-light);">#{{ $w->ticket_number }}</strong></td>
                    <td>UID: {{ $w->userid }}</td>
                    <td>
                        <a href="https://bscscan.com/address/{{ $w->wallet_address }}" target="_blank" class="hash-code">
                            {{ substr($w->wallet_address, 0, 8) }}...{{ substr($w->wallet_address, -6) }}
                            <i class="fas fa-external-link-alt" style="font-size: 9px;"></i>
                        </a>
                    </td>
                    <td>
                        <strong style="font-size: 14px; color: var(--gold-light);">
                            +${{ number_format($w->prize_amount, 2) }} USDT
                        </strong>
                    </td>
                    <td>
                        @if($w->credited_to_wallet)
                            <span class="badge-pill badge-green"><i class="fas fa-check"></i> Credited</span>
                        @else
                            <span class="badge-pill badge-gold">Pending</span>
                        @endif
                    </td>
                    <td style="font-size: 11px; color: var(--text-muted);">
                        {{ $w->created_at ? $w->created_at->format('Y-m-d H:i') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 36px;">
                        No winners recorded yet. When a pool round reaches 500 members, 50 winners are selected instantly and recorded here!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($winners->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border-dim);">
        {{ $winners->appends(request()->input())->links() }}
    </div>
    @endif
</div>
@endsection
