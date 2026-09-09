@extends('layouts.user-mecha')

@section('title', 'Total Team - Cyera AI')
@section('page-title', 'Total Network Team')
@section('page-icon', 'fas fa-sitemap')

@section('content')
<style>
    /* Compact Header */
    .mecha-hud-card {
        padding: 16px 14px;
        background: #060609;
        border: 1px solid rgba(245, 166, 35, 0.22);
        border-radius: 16px;
    }

    .mecha-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        flex-wrap: wrap;
    }

    .mecha-card-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mecha-card-title-wrap i {
        font-size: 1.1rem;
        color: #FFD700;
    }

    .mecha-card-title {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 0.5px;
        margin: 0;
        line-height: 1.2;
    }

    .mecha-card-subtitle {
        font-size: 0.72rem;
        color: #64748B;
        margin-top: 2px;
    }

    /* Single Row Compact Toolbar */
    .cyber-toolbar-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .toolbar-show-records {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #94A3B8;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .toolbar-select-styled {
        height: 32px;
        background: #090B12;
        border: 1px solid rgba(255, 215, 0, 0.35);
        border-radius: 8px;
        color: #FFD700;
        font-family: 'Outfit', sans-serif;
        font-size: 0.82rem;
        font-weight: 800;
        padding: 0 24px 0 10px;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%23FFD700' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
    }

    .toolbar-search-box {
        position: relative;
        display: flex;
        align-items: center;
        flex: 1;
        max-width: 260px;
    }

    .toolbar-search-box i {
        position: absolute;
        left: 10px;
        color: #FFD700;
        font-size: 0.78rem;
        pointer-events: none;
    }

    .toolbar-search-input {
        width: 100%;
        height: 32px;
        background: #090B12;
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 8px;
        color: #FFFFFF;
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        font-weight: 500;
        padding: 0 10px 0 30px;
        outline: none;
        transition: all 0.2s ease;
    }

    .toolbar-search-input:focus {
        border-color: #FFD700;
        background: #0E121D;
        box-shadow: 0 0 12px rgba(255, 215, 0, 0.25);
    }

    .toolbar-search-input::placeholder {
        color: #64748B;
        font-size: 0.75rem;
    }

    /* Cards Grid */
    .direct-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    /* Compact Cyber Card */
    .member-cyber-card {
        background: linear-gradient(145deg, #0B0E17 0%, #05070B 100%);
        border: 1px solid rgba(245, 166, 35, 0.25);
        border-radius: 14px;
        padding: 12px 14px;
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.8);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .member-cyber-card::before {
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

    .member-cyber-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 215, 0, 0.55);
        box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.95), 0 0 16px rgba(245, 166, 35, 0.2);
    }

    .member-cyber-card:hover::before {
        opacity: 1;
    }

    /* Card Top Header */
    .card-member-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .head-user-block {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .member-avatar-orb {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1A1F2C, #0E121B);
        border: 1.5px solid rgba(255, 215, 0, 0.45);
        box-shadow: 0 0 10px rgba(245, 166, 35, 0.25);
        color: #FFD700;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }

    .avatar-status-dot {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        border: 1.5px solid #05070B;
    }

    .dot-active {
        background: #00FF88;
        box-shadow: 0 0 6px #00FF88;
    }

    .dot-inactive {
        background: #F43F5E;
        box-shadow: 0 0 6px #F43F5E;
    }

    .member-name-stack {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .member-full-name {
        font-family: 'Outfit', sans-serif;
        font-size: 0.92rem;
        font-weight: 800;
        color: #FFFFFF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }

    .member-id-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        font-weight: 700;
        color: #00E5FF;
        background: rgba(0, 229, 255, 0.08);
        border: 1px solid rgba(0, 229, 255, 0.3);
        padding: 1.5px 6px;
        border-radius: 5px;
        width: fit-content;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .member-id-pill:hover {
        background: rgba(0, 229, 255, 0.18);
        color: #FFFFFF;
        border-color: #00E5FF;
    }

    .member-id-pill i {
        font-size: 9px;
        opacity: 0.8;
    }

    .member-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.66rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .badge-active {
        background: rgba(0, 255, 136, 0.10);
        border: 1px solid rgba(0, 255, 136, 0.4);
        color: #00FF88;
    }

    .badge-inactive {
        background: rgba(244, 63, 94, 0.10);
        border: 1px solid rgba(244, 63, 94, 0.4);
        color: #FB7185;
    }

    /* 2x2 Stats Grid - Compact */
    .card-stats-2x2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        background: rgba(14, 18, 28, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 8px 10px;
    }

    .stat-item-cell {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .stat-cell-lbl {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.62rem;
        font-weight: 800;
        color: #7D8CA3;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-cell-lbl i {
        font-size: 0.64rem;
    }

    .stat-cell-val {
        font-family: 'Outfit', sans-serif;
        font-size: 0.86rem;
        font-weight: 800;
        letter-spacing: 0.1px;
    }

    .val-green { color: #00FF88; }
    .val-cyan { color: #00E5FF; }
    .val-gold { color: #FFD700; }
    .val-silver { color: #E2E8F0; font-size: 0.78rem; font-family: 'JetBrains Mono', monospace; }

    /* Card Footer Meta - Compact */
    .card-meta-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 6px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.70rem;
    }

    .card-index-tag {
        color: #FFD700;
        font-weight: 800;
        font-size: 0.74rem;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .card-member-level-tag {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.70rem;
        font-weight: 800;
        color: #00E5FF;
        background: rgba(0, 229, 255, 0.10);
        border: 1px solid rgba(0, 229, 255, 0.25);
        padding: 1px 6px;
        border-radius: 5px;
    }

    /* Bottom Pagination Bar - Compact */
    .mecha-pagination-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        flex-wrap: wrap;
    }

    .pagination-info-txt {
        font-size: 0.76rem;
        color: #94A3B8;
        font-weight: 600;
    }

    .pagination-btns-wrap {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .page-nav-btn {
        min-width: 32px;
        height: 32px;
        padding: 0 10px;
        border-radius: 8px;
        background: #0D111A;
        border: 1px solid rgba(255, 255, 255, 0.10);
        color: #FFFFFF !important;
        font-family: 'Outfit', 'Inter', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: all 0.2s ease;
        outline: none;
    }

    .page-nav-btn:hover:not(:disabled) {
        border-color: #FFD700;
        color: #FFD700 !important;
        background: rgba(245, 166, 35, 0.15);
    }

    .page-nav-btn.active {
        background: linear-gradient(135deg, #FFD700, #F5A623) !important;
        border-color: #FFD700 !important;
        color: #000000 !important;
        font-weight: 900 !important;
        box-shadow: 0 0 12px rgba(255, 215, 0, 0.45) !important;
    }

    .page-nav-btn:disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }

    /* Empty State */
    .no-records-card {
        grid-column: 1 / -1;
        text-align: center;
        padding: 36px 16px;
        background: #06080F;
        border: 1px dashed rgba(245, 166, 35, 0.3);
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .no-records-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: rgba(245, 166, 35, 0.10);
        border: 1px solid rgba(245, 166, 35, 0.3);
        color: #FFD700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .no-records-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        color: #FFFFFF;
    }

    .no-records-sub {
        font-size: 0.78rem;
        color: #94A3B8;
        max-width: 360px;
    }

    @media (max-width: 480px) {
        .mecha-hud-card {
            padding: 12px 10px;
        }
        .cyber-toolbar-wrap {
            gap: 8px;
        }
        .toolbar-search-box {
            max-width: 100%;
        }
    }
</style>

<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-network-wired"></i>
            <div>
                <h2 class="mecha-card-title">UNILEVEL DOWNLINE DIRECTORY</h2>
                <div class="mecha-card-subtitle">Complete team structure filtered by depth levels</div>
            </div>
        </div>
        
        <!-- Level Filter Dropdown -->
        <form method="get" style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 10px; color: #8C9BAE; font-weight: 800; text-transform: uppercase;">LEVEL:</span>
            <div style="width: 110px;">
                <select name="level" class="mecha-select-control" style="height: 30px; font-size: 11px; padding: 0 8px;" onchange="this.form.submit()">
                    @for($lvl = 1; $lvl <= 101; $lvl++)
                        <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>
                            Level {{ $lvl }}
                        </option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <!-- Controls Toolbar (Single Compact Row) -->
    <div class="cyber-toolbar-wrap">
        <div class="toolbar-show-records">
            <span>SHOW</span>
            <select id="totalPageSizeSelect" class="toolbar-select-styled">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>RECORDS</span>
        </div>

        <div class="toolbar-search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="totalMemberSearchInput" class="toolbar-search-input" placeholder="Search downline by User ID, wallet, level...">
        </div>
    </div>

    <!-- Members Cards Grid -->
    <div class="direct-cards-grid" id="totalMembersCardsContainer">
        @php $i = 1; @endphp
        @forelse($totaldown as $row)
            @php
                $st = strtolower($row->status ?? '');
                $isActive = ($st == '1' || str_contains($st, 'active') || str_contains($st, 'paid'));
                $stakedAmount = (float)($row->current ?? $row->shares ?? 0);
                
                $levelPerc = 0;
                if (method_exists($row, 'levelStatus')) {
                    $levelPerc = $row->levelStatus() >= ($row->leveluser ?? 0) ? round($row->levelStatus()) : round($row->leveluser ?? 0);
                } elseif (method_exists($row, 'userDetails') && $row->userDetails() && $row->userDetails()->first() && method_exists($row->userDetails()->first(), 'levelStatus')) {
                    $ud = $row->userDetails()->first();
                    $levelPerc = $ud->levelStatus() >= ($row->leveluser ?? 0) ? round($ud->levelStatus()) : round($row->leveluser ?? 0);
                } else {
                    $levelPerc = round($row->leveluser ?? 0);
                }
                
                $mWallet = $row->walletaddress ?? $row->bep20address ?? '';
                $searchContent = strtolower(($row->userid ?? '').' '.($mWallet ?? '').' '.($row->doj ?? '').' '.($isActive ? 'active' : 'inactive').' '.$stakedAmount.' level '.($row->level ?? ''));
            @endphp

            <div class="member-cyber-card" data-search="{{ $searchContent }}">
                <!-- Card Header -->
                <div class="card-member-head">
                    <div class="head-user-block">
                        <div class="member-avatar-orb">
                            <i class="fas fa-user" style="font-size: 0.85rem;"></i>
                            <span class="avatar-status-dot {{ $isActive ? 'dot-active' : 'dot-inactive' }}"></span>
                        </div>
                        <div class="member-name-stack">
                            <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                                <div class="member-id-pill" onclick="copyUserId('{{ $row->userid }}')" title="Click to copy User ID" style="font-size: 0.82rem; font-weight: 800; color: #FFD700; padding: 3px 8px;">
                                    <i class="fas fa-id-badge" style="font-size: 9px;"></i>
                                    <span>{{ $row->userid }}</span>
                                    <i class="fas fa-copy" style="font-size: 9px;"></i>
                                </div>
                                @if(!empty($mWallet))
                                <div class="member-id-pill" onclick="copyUserId('{{ $mWallet }}')" title="BEP-20 Wallet: {{ $mWallet }} (Click to copy)" style="background: rgba(0, 255, 136, 0.08); border-color: rgba(0, 255, 136, 0.3); color: #00FF88;">
                                    <i class="fas fa-wallet" style="font-size: 8px;"></i>
                                    <span style="font-family: monospace;">{{ substr($mWallet, 0, 6) }}...{{ substr($mWallet, -4) }}</span>
                                    <i class="fas fa-copy" style="font-size: 8px;"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="member-status-badge {{ $isActive ? 'badge-active' : 'badge-inactive' }}">
                        <i class="fas {{ $isActive ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                        <span>{{ $isActive ? 'ACTIVE' : 'INACTIVE' }}</span>
                    </div>
                </div>

                <!-- 2x2 Stats Grid - Compact -->
                <div class="card-stats-2x2">
                    <div class="stat-item-cell">
                        <span class="stat-cell-lbl"><i class="fas fa-coins" style="color:#00FF88;"></i> STAKED PKG</span>
                        <span class="stat-cell-val val-green">${{ number_format($stakedAmount, 2) }}</span>
                    </div>

                    <div class="stat-item-cell">
                        <span class="stat-cell-lbl"><i class="fas fa-layer-group" style="color:#FFD700;"></i> UNLOCK</span>
                        <span class="stat-cell-val val-gold">{{ $levelPerc }}%</span>
                    </div>

                    <div class="stat-item-cell">
                        <span class="stat-cell-lbl"><i class="fas fa-calendar-day" style="color:#8C9BAE;"></i> JOINED</span>
                        <span class="stat-cell-val val-silver">{{ $row->doj ? date('Y-m-d', strtotime($row->doj)) : 'N/A' }}</span>
                    </div>

                    <div class="stat-item-cell">
                        <span class="stat-cell-lbl"><i class="fas fa-sitemap" style="color:#00E5FF;"></i> DEPTH</span>
                        <span class="stat-cell-val val-cyan">Level {{ $row->level }}</span>
                    </div>
                </div>

                <!-- Card Bottom Meta -->
                <div class="card-meta-foot">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="card-index-tag">
                            <i class="fas fa-hashtag"></i>{{ $i }} Member
                        </span>
                        <span class="card-member-level-tag">L{{ $row->level }}</span>
                    </div>
                    @php
                        $spWallet = $row->sponsor_wallet ?? '';
                    @endphp
                    @if(!empty($spWallet))
                    <span style="font-size: 0.70rem; color: #94A3B8; display: flex; align-items: center; gap: 4px;" title="Sponsor Wallet: {{ $spWallet }}">
                        <i class="fas fa-user-tag" style="color: #FFD700;"></i> Sponsor:
                        <span class="member-id-pill" onclick="copyUserId('{{ $spWallet }}')" style="font-size: 0.65rem; padding: 1px 5px; height: auto; background: rgba(245, 166, 35, 0.1); border-color: rgba(245, 166, 35, 0.3); color: #FFD700;">
                            <i class="fas fa-wallet" style="font-size: 7px;"></i>
                            <span style="font-family: monospace;">{{ substr($spWallet, 0, 6) }}...{{ substr($spWallet, -4) }}</span>
                            <i class="fas fa-copy" style="font-size: 7px;"></i>
                        </span>
                    </span>
                    @else
                    <span style="font-size: 0.70rem; color: #8C9BAE;">
                        {{ $row->doj ? date('Y-m-d', strtotime($row->doj)) : 'N/A' }}
                    </span>
                    @endif
                </div>
            </div>
            @php $i++; @endphp
        @empty
            <div class="no-records-card">
                <div class="no-records-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div class="no-records-title">No Downline Members Found</div>
                <div class="no-records-sub">There are currently no network team members found for Level {{ request('level', 1) }}.</div>
            </div>
        @endforelse
    </div>

    <!-- Empty search result card (hidden by default) -->
    <div id="totalNoMatchSearchCard" class="no-records-card" style="display: none; margin-bottom: 14px;">
        <div class="no-records-icon" style="color: #64748B; background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
            <i class="fas fa-search"></i>
        </div>
        <div class="no-records-title">No Matching Team Members</div>
        <div class="no-records-sub">We couldn't find any team members matching your search query. Try another keyword.</div>
    </div>

    <!-- Bottom Pagination Bar -->
    <div class="mecha-pagination-bar" id="totalPaginationBar">
        <div class="pagination-info-txt" id="totalPaginationInfoTxt">
            Showing 0 to 0 of 0 entries
        </div>
        <div class="pagination-btns-wrap" id="totalPaginationBtnsWrap">
            <!-- Dynamically generated buttons -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyUserId(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("User ID Copied: " + text);
            });
        } else {
            const input = document.createElement('input');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showToast("User ID Copied: " + text);
        }
    }

    function showToast(msg) {
        const toast = document.createElement('div');
        toast.innerText = msg;
        toast.style.position = 'fixed';
        toast.style.bottom = '24px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.background = '#090B12';
        toast.style.border = '1px solid #FFD700';
        toast.style.color = '#FFD700';
        toast.style.padding = '8px 18px';
        toast.style.borderRadius = '8px';
        toast.style.fontSize = '0.80rem';
        toast.style.fontWeight = '800';
        toast.style.boxShadow = '0 10px 30px rgba(0,0,0,0.9), 0 0 15px rgba(255,215,0,0.3)';
        toast.style.zIndex = '99999';
        document.body.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 2000);
    }

    // Reactive Live Search & Pagination Controller for Total Team
    document.addEventListener('DOMContentLoaded', function () {
        const allCardElements = Array.from(document.querySelectorAll('.member-cyber-card'));
        const searchInput = document.getElementById('totalMemberSearchInput');
        const pageSizeSelect = document.getElementById('totalPageSizeSelect');
        const noMatchCard = document.getElementById('totalNoMatchSearchCard');
        const paginationInfo = document.getElementById('totalPaginationInfoTxt');
        const paginationBtns = document.getElementById('totalPaginationBtnsWrap');
        const paginationBar = document.getElementById('totalPaginationBar');

        if (allCardElements.length === 0) {
            if (paginationBar) paginationBar.style.display = 'none';
            return;
        }

        let currentPage = 1;
        let pageSize = parseInt(pageSizeSelect.value) || 10;
        let activeCards = [...allCardElements];

        function filterAndPaginate() {
            const query = (searchInput.value || '').trim().toLowerCase();

            // 1. Filter
            if (query === '') {
                activeCards = [...allCardElements];
            } else {
                activeCards = allCardElements.filter(card => {
                    const searchData = card.getAttribute('data-search') || '';
                    return searchData.includes(query);
                });
            }

            // 2. Hide all cards first
            allCardElements.forEach(c => c.style.display = 'none');

            // 3. Check for empty match
            if (activeCards.length === 0) {
                if (noMatchCard) noMatchCard.style.display = 'flex';
                if (paginationInfo) paginationInfo.innerText = 'Showing 0 to 0 of 0 entries';
                if (paginationBtns) paginationBtns.innerHTML = '';
                return;
            } else {
                if (noMatchCard) noMatchCard.style.display = 'none';
            }

            // 4. Calculate pagination
            const totalItems = activeCards.length;
            const totalPages = Math.ceil(totalItems / pageSize) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, totalItems);

            // 5. Show visible page slice
            for (let i = startIndex; i < endIndex; i++) {
                activeCards[i].style.display = 'flex';
            }

            // 6. Update text info
            if (paginationInfo) {
                paginationInfo.innerText = `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;
            }

            // 7. Render pagination buttons
            renderPaginationButtons(totalPages);
        }

        function renderPaginationButtons(totalPages) {
            if (!paginationBtns) return;
            paginationBtns.innerHTML = '';

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
                        dots.style.color = '#8C9BAE';
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
        searchInput.addEventListener('input', function () {
            currentPage = 1;
            filterAndPaginate();
        });

        pageSizeSelect.addEventListener('change', function () {
            pageSize = parseInt(this.value) || 10;
            currentPage = 1;
            filterAndPaginate();
        });

        // Initial render
        filterAndPaginate();
    });
</script>
@endpush