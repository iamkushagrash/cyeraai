@extends('layouts.user-mecha')

@section('title', 'Staking ROI Withdrawal History - Cyera AI')
@section('page-title', 'Staking ROI Withdrawal History')
@section('page-icon', 'fas fa-bolt-lightning')

@section('content')
<style>
    .roi-history-card-wrap {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 12px;
    }

    .roi-txn-card {
        background: linear-gradient(160deg, #0A0D15 0%, #05070B 100%);
        border: 1px solid rgba(245, 166, 35, 0.22);
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6), 0 0 14px rgba(245, 166, 35, 0.05);
        transition: transform 0.2s ease, border-color 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .roi-txn-card:hover {
        border-color: rgba(245, 166, 35, 0.5);
        transform: translateY(-2px);
    }

    .roi-txn-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, #FFD700 0%, #F5A623 100%);
    }

    .txn-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 8px;
        margin-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .txn-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .txn-idx-badge {
        font-family: monospace;
        font-size: 10px;
        font-weight: 800;
        color: #FFD700;
        background: rgba(245, 166, 35, 0.12);
        border: 1px solid rgba(245, 166, 35, 0.3);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .txn-time-txt {
        font-size: 10.5px;
        color: #8C9BAE;
        font-weight: 500;
    }

    .txn-main-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(245, 166, 35, 0.04);
        border: 1px solid rgba(245, 166, 35, 0.15);
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 10px;
    }

    .txn-primary-metric {
        display: flex;
        flex-direction: column;
    }

    .txn-metric-lbl {
        font-size: 8.5px;
        font-weight: 800;
        color: #8C9BAE;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .txn-metric-val {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 900;
        color: #00FF88;
        line-height: 1.2;
    }

    .txn-cai-badge {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
    }

    .txn-cai-val {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: #FFD700;
    }

    .txn-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }

    .txn-meta-item {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 6px 8px;
        display: flex;
        flex-direction: column;
    }

    .txn-meta-item .lbl {
        font-size: 8px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
    }

    .txn-meta-item .val {
        font-size: 10.5px;
        font-weight: 700;
        color: #E2E8F0;
        margin-top: 1px;
    }

    /* Live Search & Filter Bar */
    .history-filter-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .search-input-wrap {
        position: relative;
        flex: 1;
    }

    .search-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748B;
        font-size: 11px;
    }

    .search-input-wrap input {
        width: 100%;
        height: 38px;
        background: #090C14;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 0 12px 0 32px;
        color: #FFF;
        font-size: 11px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .search-input-wrap input:focus {
        border-color: #FFD700;
        box-shadow: 0 0 10px rgba(245, 166, 35, 0.2);
    }
</style>

<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-bolt" style="color: #FFD700;"></i>
            <div>
                <h2 class="mecha-card-title">STAKING ROI PAYOUT TRANSACTIONS</h2>
                <div class="mecha-card-subtitle">Complete ledger of all CPS Staking Yield redemptions</div>
            </div>
        </div>
        <span class="mecha-card-badge" style="background: rgba(255, 215, 0, 0.15); color: #FFD700; border-color: rgba(255, 215, 0, 0.4);">
            {{ count($history) }} TOTAL
        </span>
    </div>

    <!-- Live Filter Bar -->
    <div class="history-filter-bar">
        <div class="search-input-wrap">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" id="roiSearchInput" placeholder="Search by amount, status, date..." onkeyup="filterRoiCards()">
        </div>
        <div style="font-size: 10px; color: #8C9BAE; font-weight: 700; white-space: nowrap;">
            <span id="visibleCardCount">{{ count($history) }}</span> / {{ count($history) }}
        </div>
    </div>

    <!-- Card Grid Container -->
    <div class="roi-history-card-wrap" id="roiCardsContainer">
        @php $i = 1; @endphp
        @forelse($history as $row)
            @php
                $statusStr = strtolower($row->status ?? '');
                $isPaid = in_array($statusStr, ['success', 'paid', '1', 'completed', 'confirmed']);
                $isPending = in_array($statusStr, ['pending', 'processing', '0']) || str_contains($statusStr, 'pending');
            @endphp
            <div class="roi-txn-card" data-search="{{ strtolower($row->amountsftc . ' ' . $row->amountusdt . ' ' . $row->net_amount . ' ' . $row->currency . ' ' . $row->created_at . ' ' . ($isPaid ? 'paid' : ($isPending ? 'pending' : 'failed'))) }}">
                <!-- Card Header -->
                <div class="txn-card-head">
                    <div class="txn-head-left">
                        <span class="txn-idx-badge">#{{ $i++ }}</span>
                        <span class="txn-time-txt"><i class="far fa-clock" style="font-size: 9.5px; margin-right: 3px;"></i> {{ $row->created_at }}</span>
                    </div>
                    <div>
                        @if($isPaid)
                            <span class="mecha-badge-green"><i class="fas fa-circle-check"></i> PAID</span>
                        @elseif($isPending)
                            <span class="mecha-badge-yellow"><i class="fas fa-clock"></i> PENDING</span>
                        @else
                            <span class="mecha-badge-red"><i class="fas fa-circle-xmark"></i> {{ strtoupper($row->status) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Main Highlight Banner (Net Payout & CAI Burned) -->
                <div class="txn-main-banner">
                    <div class="txn-primary-metric">
                        <span class="txn-metric-lbl">Net USDT Payable</span>
                        <span class="txn-metric-val">${{ number_format((float)$row->net_amount, 2) }}</span>
                    </div>
                    <div class="txn-cai-badge">
                        <span class="txn-metric-lbl">CAI Burned</span>
                        <span class="txn-cai-val">{{ number_format((float)$row->amountsftc, 4) }} <small style="font-size: 10px; color: #FFE082;">CAI</small></span>
                    </div>
                </div>

                <!-- 3 Meta Items Grid (Gross, Fee, Network) -->
                <div class="txn-meta-grid">
                    <div class="txn-meta-item">
                        <span class="lbl">Gross ($)</span>
                        <span class="val">${{ number_format((float)$row->amountusdt, 2) }}</span>
                    </div>
                    <div class="txn-meta-item">
                        <span class="lbl">Fee (10%)</span>
                        <span class="val" style="color: #FF4D7D;">-${{ number_format((float)$row->deduction, 2) }}</span>
                    </div>
                    <div class="txn-meta-item">
                        <span class="lbl">Network</span>
                        <span class="val" style="color: #00E5FF; text-transform: uppercase;">{{ $row->currency ?: 'USDT' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 40px 16px; background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.08); border-radius: 14px;">
                <i class="fas fa-receipt" style="font-size: 32px; color: rgba(255, 215, 0, 0.3); margin-bottom: 10px;"></i>
                <div style="font-size: 13px; font-weight: 700; color: #E2E8F0;">No Staking ROI Claims Yet</div>
                <div style="font-size: 10.5px; color: #64748B; margin-top: 4px;">Your daily yield redemption history will appear here once submitted.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterRoiCards() {
        const query = document.getElementById('roiSearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#roiCardsContainer .roi-txn-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const searchData = card.getAttribute('data-search') || '';
            if (!query || searchData.includes(query)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const countEl = document.getElementById('visibleCardCount');
        if (countEl) countEl.innerText = visibleCount;
    }
</script>
@endpush
