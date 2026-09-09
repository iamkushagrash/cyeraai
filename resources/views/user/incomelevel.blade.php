@extends('layouts.user-mecha')

@section('title', 'Staking Referral Reward - Cyera AI')
@section('page-title', 'Staking Referral Reward')
@section('page-icon', 'fas fa-users-rays')

@section('content')
<style>
    /* Scoped Clean HUD Container */
    .referral-hud-card {
        padding: 12px 10px;
        background: linear-gradient(160deg, #0A0D15 0%, #05070B 100%);
        border: 1px solid rgba(245, 166, 35, 0.28);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7), 0 0 16px rgba(245, 166, 35, 0.08);
    }

    /* Header */
    .referral-card-head {
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        width: 100%;
    }

    .referral-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .referral-head-icon {
        font-size: 1.15rem;
        color: #FFD700;
        filter: drop-shadow(0 0 6px rgba(255, 215, 0, 0.4));
        flex-shrink: 0;
    }

    .referral-head-text {
        display: flex;
        flex-direction: column;
        width: 100%;
        min-width: 0;
    }

    .referral-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        width: 100%;
    }

    .referral-head-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.85rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 0.4px;
        margin: 0;
        line-height: 1.15;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .referral-rate-pill {
        font-size: 0.62rem;
        font-weight: 800;
        padding: 1.5px 6px;
        border-radius: 10px;
        background: rgba(245, 166, 35, 0.15);
        border: 1px solid #F5A623;
        color: #FFE082;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .referral-head-subtitle {
        font-size: 0.65rem;
        color: #8E99A8;
        margin-top: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Top Stats Bar — 3 In One Row (Compact) */
    .referral-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        margin-bottom: 10px;
        width: 100%;
    }

    .referral-summary-pill {
        background: rgba(14, 18, 28, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 6px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        justify-content: center;
        min-width: 0;
    }

    .referral-summary-pill.gold {
        border-top: 2px solid #FFD700;
        background: linear-gradient(180deg, rgba(245, 166, 35, 0.14) 0%, rgba(14, 18, 28, 0.85) 100%);
    }

    .referral-summary-pill.cyan {
        border-top: 2px solid #00D2FF;
        background: linear-gradient(180deg, rgba(0, 210, 255, 0.12) 0%, rgba(14, 18, 28, 0.85) 100%);
    }

    .referral-summary-pill.green {
        border-top: 2px solid #00FF88;
        background: linear-gradient(180deg, rgba(0, 255, 136, 0.12) 0%, rgba(14, 18, 28, 0.85) 100%);
    }

    .referral-summary-lbl {
        font-size: 0.58rem;
        font-weight: 800;
        color: #8E99A8;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 3px;
        line-height: 1;
    }

    .referral-summary-val {
        font-size: 0.95rem;
        font-weight: 900;
        color: #FFFFFF;
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 0.2px;
        line-height: 1.1;
        margin-top: 3px;
        white-space: nowrap;
    }

    .referral-summary-val.gold {
        color: #FFE082;
        text-shadow: 0 0 6px rgba(245, 166, 35, 0.35);
    }

    .referral-summary-val.cyan {
        color: #00D2FF;
    }

    .referral-summary-val.green {
        color: #00FF88;
    }

    /* Single Row Compact Toolbar */
    .referral-toolbar-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
        width: 100%;
    }

    .referral-show-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.65rem;
        font-weight: 800;
        color: #94A3B8;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .referral-select-box {
        height: 28px;
        background: #090B12;
        border: 1px solid rgba(255, 215, 0, 0.35);
        border-radius: 6px;
        color: #FFD700;
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0 18px 0 6px;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 24 24' fill='none' stroke='%23FFD700' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 5px center;
    }

    .referral-search-wrap {
        position: relative;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }

    .referral-search-wrap i {
        position: absolute;
        left: 8px;
        color: #FFD700;
        font-size: 0.7rem;
        pointer-events: none;
    }

    .referral-search-input {
        width: 100%;
        height: 28px;
        background: #090B12;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 6px;
        color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-size: 0.72rem;
        font-weight: 500;
        padding: 0 8px 0 24px;
        outline: none;
        transition: all 0.2s ease;
    }

    .referral-search-input:focus {
        border-color: #FFD700;
        background: #0E121D;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.25);
    }

    .referral-search-input::placeholder {
        color: #64748B;
        font-size: 0.68rem;
    }

    /* Cards Grid */
    .referral-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 10px;
        margin-bottom: 12px;
    }

    /* Cyber Reward Card */
    .reward-cyber-card {
        background: linear-gradient(145deg, #0D111A 0%, #06080E 100%);
        border: 1px solid rgba(245, 166, 35, 0.25);
        border-radius: 12px;
        padding: 10px 12px;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .reward-cyber-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.6), transparent);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .reward-cyber-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 215, 0, 0.55);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.9), 0 0 14px rgba(245, 166, 35, 0.18);
    }

    .reward-cyber-card:hover::before {
        opacity: 1;
    }

    /* Card Top Header */
    .card-reward-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .reward-index-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.68rem;
        font-weight: 800;
        color: #FFE082;
        background: rgba(245, 166, 35, 0.12);
        border: 1px solid rgba(245, 166, 35, 0.3);
        border-radius: 5px;
        padding: 2px 6px;
    }

    .reward-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        background: rgba(0, 255, 136, 0.12);
        border: 1px solid rgba(0, 255, 136, 0.35);
        color: #00FF88;
        box-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
    }

    /* Card Main Body */
    .card-reward-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(4, 6, 10, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 8px 10px;
    }

    .reward-amount-block {
        display: flex;
        flex-direction: column;
    }

    .reward-lbl {
        font-size: 0.62rem;
        font-weight: 800;
        color: #8E99A8;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .reward-val {
        font-size: 1.2rem;
        font-weight: 900;
        color: #FFE082;
        font-family: 'Rajdhani', sans-serif;
        letter-spacing: 0.3px;
        line-height: 1.1;
        text-shadow: 0 0 8px rgba(245, 166, 35, 0.35);
    }

    .reward-val small {
        font-size: 0.72rem;
        color: #8E99A8;
        font-weight: 700;
        margin-left: 2px;
    }

    .reward-action-block {
        display: flex;
        align-items: center;
    }

    .breakdown-cyber-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: linear-gradient(135deg, rgba(0, 210, 255, 0.15) 0%, rgba(0, 150, 255, 0.08) 100%);
        border: 1px solid rgba(0, 210, 255, 0.4);
        border-radius: 6px;
        color: #00D2FF;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .breakdown-cyber-btn:hover {
        background: rgba(0, 210, 255, 0.28);
        border-color: #00D2FF;
        color: #FFFFFF;
        box-shadow: 0 0 10px rgba(0, 210, 255, 0.35);
        transform: translateY(-1px);
    }

    /* Card Footer Info */
    .card-reward-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.7rem;
        color: #8E99A8;
        padding-top: 4px;
        border-top: 1px dashed rgba(255, 255, 255, 0.08);
    }

    .footer-date {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .footer-date i {
        color: #00D2FF;
        font-size: 0.68rem;
    }

    .footer-tag {
        font-size: 0.66rem;
        color: #00FF88;
        background: rgba(0, 255, 136, 0.08);
        padding: 1px 5px;
        border-radius: 4px;
        border: 1px solid rgba(0, 255, 136, 0.2);
    }

    /* Empty State Card */
    .referral-no-records {
        grid-column: 1 / -1;
        text-align: center;
        padding: 30px 16px;
        background: rgba(10, 13, 20, 0.6);
        border: 1px dashed rgba(245, 166, 35, 0.3);
        border-radius: 12px;
        color: #8E99A8;
    }

    .referral-no-records i {
        font-size: 1.8rem;
        color: #FFD700;
        margin-bottom: 6px;
        opacity: 0.7;
    }

    .referral-no-records p {
        font-size: 0.82rem;
        font-weight: 600;
        margin: 0;
        color: #CBD5E1;
    }

    /* Footer Pagination */
    .referral-pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        flex-wrap: wrap;
    }

    .pagination-info-txt {
        font-size: 0.72rem;
        color: #8E99A8;
        font-weight: 600;
    }

    .pagination-controls-group {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .page-nav-btn {
        min-width: 28px;
        height: 28px;
        padding: 0 7px;
        background: #090B12;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 6px;
        color: #CBD5E1;
        font-size: 0.72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .page-nav-btn:hover:not(:disabled) {
        background: rgba(245, 166, 35, 0.15);
        border-color: #FFD700;
        color: #FFD700;
    }

    .page-nav-btn.active {
        background: #FFD700;
        border-color: #FFD700;
        color: #05070B;
        font-weight: 900;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.35);
    }

    .page-nav-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }
</style>

@php
    $totalRefEarned = 0;
    if (isset($referral)) {
        foreach($referral as $rf) {
            $totalRefEarned += (float)($rf->amountusdt ?? 0);
        }
    }
@endphp

<div class="referral-hud-card">
    <!-- Header: Nested Title + Pill in 1 Clean Row -->
    <div class="referral-card-head">
        <div class="referral-head-left">
            <i class="fas fa-users-rays referral-head-icon"></i>
            <div class="referral-head-text">
                <div class="referral-title-row">
                    <h2 class="referral-head-title">STAKING REFERRAL REWARDS</h2>
                    <span class="referral-rate-pill">LEVEL BONUS</span>
                </div>
                <div class="referral-head-subtitle">Daily referral bonus accumulated from downlines</div>
            </div>
        </div>
    </div>

    <!-- Summary Stats Bar — 3 In One Row (Compact) -->
    <div class="referral-summary-grid">
        <div class="referral-summary-pill gold">
            <span class="referral-summary-lbl"><i class="fas fa-wallet" style="color: #FFD700;"></i> EARNED</span>
            <span class="referral-summary-val gold">${{ number_format($totalRefEarned, 3) }}</span>
        </div>
        <div class="referral-summary-pill cyan">
            <span class="referral-summary-lbl"><i class="fas fa-calendar-days" style="color: #00D2FF;"></i> DAYS</span>
            <span class="referral-summary-val cyan">{{ count($referral ?? []) }}</span>
        </div>
        <div class="referral-summary-pill green">
            <span class="referral-summary-lbl"><i class="fas fa-layer-group" style="color: #00FF88;"></i> DISTRIBUTION</span>
            <span class="referral-summary-val green">DAILY</span>
        </div>
    </div>

    <!-- Cyber Toolbar: Show Records & Live Search -->
    <div class="referral-toolbar-row">
        <div class="referral-show-wrap">
            <span>SHOW</span>
            <select id="referralPageSizeSelect" class="referral-select-box">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <div class="referral-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="referralSearchInput" class="referral-search-input" placeholder="Search by date, amount...">
        </div>
    </div>

    <!-- Cards Grid Container -->
    <div class="referral-cards-grid" id="referralCardsContainer">
        @php $idx = 1; @endphp
        @forelse($referral as $row)
            @php
                $searchContent = strtolower(($row->amountusdt ?? '') . ' ' . ($row->txndate ?? ''));
            @endphp
            <div class="reward-cyber-card referral-card-item" data-search="{{ $searchContent }}">
                <!-- Card Header -->
                <div class="card-reward-head">
                    <span class="reward-index-badge">
                        <i class="fas fa-calendar-day"></i> #{{ $idx++ }}
                    </span>
                    <span class="reward-status-pill">
                        <i class="fas fa-check-circle"></i> CREDIT
                    </span>
                </div>

                <!-- Card Body -->
                <div class="card-reward-body">
                    <div class="reward-amount-block">
                        <span class="reward-lbl">DAILY REFERRAL BONUS</span>
                        <span class="reward-val">
                            ${{ number_format((float)$row->amountusdt, 3) }}
                            <small>USDT</small>
                        </span>
                    </div>
                    <div class="reward-action-block">
                        <a href="{{ url('/User/StakingReferralReward/' . $row->txndate) }}" class="breakdown-cyber-btn">
                            <i class="fas fa-list-ul"></i> BREAKDOWN <i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i>
                        </a>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-reward-footer">
                    <div class="footer-date">
                        <i class="far fa-calendar-check"></i>
                        <span>{{ $row->txndate }}</span>
                    </div>
                    <div class="footer-tag">
                        <i class="fas fa-shield-halved"></i> Daily Audit
                    </div>
                </div>
            </div>
        @empty
            <div class="referral-no-records" id="referralEmptyState">
                <i class="fas fa-users-rays"></i>
                <p>No Staking Referral Reward records found.</p>
            </div>
        @endforelse

        <div class="referral-no-records" id="referralNoMatchState" style="display: none;">
            <i class="fas fa-search"></i>
            <p>No matching referral records found for your search.</p>
        </div>
    </div>

    <!-- Cyber Pagination Controls -->
    <div class="referral-pagination-row" id="referralPaginationWrap">
        <div class="pagination-info-txt" id="referralPaginationInfo">
            Showing 0 of 0 records
        </div>
        <div class="pagination-controls-group" id="referralPaginationBtns">
            <!-- Rendered by JS -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('referralSearchInput');
        const pageSizeSelect = document.getElementById('referralPageSizeSelect');
        const cards = Array.from(document.querySelectorAll('.referral-card-item'));
        const noMatchState = document.getElementById('referralNoMatchState');
        const paginationInfo = document.getElementById('referralPaginationInfo');
        const paginationBtns = document.getElementById('referralPaginationBtns');
        const paginationWrap = document.getElementById('referralPaginationWrap');

        if (cards.length === 0) {
            if (paginationWrap) paginationWrap.style.display = 'none';
            return;
        }

        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect ? pageSizeSelect.value : 10) || 10;

        function filterAndPaginate() {
            const query = (searchInput ? searchInput.value : '').toLowerCase().trim();

            // 1. Filter cards by search query
            const matchedCards = cards.filter(card => {
                const searchData = card.getAttribute('data-search') || '';
                return searchData.includes(query);
            });

            // 2. Hide all cards first
            cards.forEach(card => card.style.display = 'none');

            // 3. Check for empty result
            if (matchedCards.length === 0) {
                if (noMatchState) noMatchState.style.display = 'block';
                if (paginationInfo) paginationInfo.innerText = 'Showing 0 of 0 records';
                renderPagination(0);
                return;
            } else {
                if (noMatchState) noMatchState.style.display = 'none';
            }

            // 4. Calculate pagination bounds
            const totalItems = matchedCards.length;
            const totalPages = Math.ceil(totalItems / pageSize) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, totalItems);

            // 5. Display active page slice
            for (let i = startIndex; i < endIndex; i++) {
                matchedCards[i].style.display = 'flex';
            }

            // 6. Update text info
            if (paginationInfo) {
                paginationInfo.innerText = `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} records`;
            }

            // 7. Render buttons
            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            if (!paginationBtns) return;
            paginationBtns.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous Button
            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'page-nav-btn';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = (currentPage === 1);
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    filterAndPaginate();
                }
            };
            paginationBtns.appendChild(prevBtn);

            // Page numbers
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 6 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
                    if (p === 2 || p === totalPages - 1) {
                        const dots = document.createElement('span');
                        dots.innerText = '...';
                        dots.style.color = '#8E99A8';
                        dots.style.padding = '0 3px';
                        dots.style.fontSize = '0.75rem';
                        paginationBtns.appendChild(dots);
                    }
                    continue;
                }

                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                pageBtn.className = 'page-nav-btn' + (p === currentPage ? ' active' : '');
                pageBtn.innerText = p;
                pageBtn.onclick = () => {
                    currentPage = p;
                    filterAndPaginate();
                };
                paginationBtns.appendChild(pageBtn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'page-nav-btn';
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = (currentPage === totalPages);
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    filterAndPaginate();
                }
            };
            paginationBtns.appendChild(nextBtn);
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentPage = 1;
                filterAndPaginate();
            });
        }

        if (pageSizeSelect) {
            pageSizeSelect.addEventListener('change', function () {
                pageSize = parseInt(this.value) || 10;
                currentPage = 1;
                filterAndPaginate();
            });
        }

        // Initialize view
        filterAndPaginate();
    });
</script>
@endpush