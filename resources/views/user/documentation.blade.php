@extends('layouts.user-mecha')

@section('title', 'Documentation - Cyera AI')
@section('page-title', 'Protocol Documentation')
@section('page-icon', 'fas fa-file-pdf')

@section('content')
<div class="row" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px;">
    <!-- 1. Company Document -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-building" style="color: #FFD700;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 13px;">COMPANY OVERVIEW</h2>
                    <div class="mecha-card-subtitle">Corporate mission & global compliance</div>
                </div>
            </div>
            <span class="mecha-badge-yellow">PDF</span>
        </div>
        <p style="font-size: 11px; color: #94A3B8; margin: 10px 0 16px;">Comprehensive overview of Cyera AI's governance, core founders, and organizational infrastructure.</p>
        <a href="{{ asset('main/assets/documentation/companydocument.pdf') }}" download class="mecha-btn-gold" style="height: 38px; font-size: 10px;">
            <i class="fas fa-download"></i> DOWNLOAD PDF
        </a>
    </div>

    <!-- 2. One Paper -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-file-lines" style="color: #00FF88;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 13px;">ONE PAGER SUMMARY</h2>
                    <div class="mecha-card-subtitle">Executive summary & tokenomics</div>
                </div>
            </div>
            <span class="mecha-badge-green">PDF</span>
        </div>
        <p style="font-size: 11px; color: #94A3B8; margin: 10px 0 16px;">Quick single-sheet brief outlining CAI token utility, reward mechanics, and smart contract specs.</p>
        <a href="{{ asset('main/assets/documentation/onepaper.pdf') }}" download class="mecha-btn-gold" style="height: 38px; font-size: 10px;">
            <i class="fas fa-download"></i> DOWNLOAD PDF
        </a>
    </div>

    <!-- 3. White Paper -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-book" style="color: #00E5FF;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 13px;">OFFICIAL WHITEPAPER</h2>
                    <div class="mecha-card-subtitle">In-depth protocol architecture</div>
                </div>
            </div>
            <span class="mecha-card-badge">TECHNICAL</span>
        </div>
        <p style="font-size: 11px; color: #94A3B8; margin: 10px 0 16px;">Detailed mathematical, cryptographic, and algorithmic foundation powering the Cyera ecosystem.</p>
        <a href="{{ asset('main/assets/documentation/whitepaper.pdf') }}" download class="mecha-btn-gold" style="height: 38px; font-size: 10px;">
            <i class="fas fa-download"></i> DOWNLOAD PDF
        </a>
    </div>

    <!-- 4. Terms To Sale -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-scale-balanced" style="color: #FFB300;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 13px;">TERMS OF PARTICIPATION</h2>
                    <div class="mecha-card-subtitle">Legal framework & guidelines</div>
                </div>
            </div>
            <span class="mecha-badge-yellow">PDF</span>
        </div>
        <p style="font-size: 11px; color: #94A3B8; margin: 10px 0 16px;">Official user agreement, staking guidelines, and regulatory compliance standards.</p>
        <a href="{{ asset('main/assets/documentation/termtosell.pdf') }}" download class="mecha-btn-gold" style="height: 38px; font-size: 10px;">
            <i class="fas fa-download"></i> DOWNLOAD PDF
        </a>
    </div>

    <!-- 5. Business Plan -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-chart-line" style="color: #00FF88;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 13px;">STRATEGIC ROADMAP</h2>
                    <div class="mecha-card-subtitle">5-year expansion vision</div>
                </div>
            </div>
            <span class="mecha-badge-green">PDF</span>
        </div>
        <p style="font-size: 11px; color: #94A3B8; margin: 10px 0 16px;">Comprehensive market entry strategy, liquidity roadmap, and global exchange listing plan.</p>
        <a href="{{ asset('main/assets/documentation/businessplan.pdf') }}" download class="mecha-btn-gold" style="height: 38px; font-size: 10px;">
            <i class="fas fa-download"></i> DOWNLOAD PDF
        </a>
    </div>

    <!-- 6. Ecosystem Overview -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-network-wired" style="color: #B34BFE;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 13px;">ECOSYSTEM MATRIX</h2>
                    <div class="mecha-card-subtitle">AI + DePIN + DeFi synergies</div>
                </div>
            </div>
            <span class="mecha-card-badge">ECOSYSTEM</span>
        </div>
        <p style="font-size: 11px; color: #94A3B8; margin: 10px 0 16px;">Holistic overview of cross-chain bridges, decentralized staking pools, and AI compute layer.</p>
        <a href="{{ asset('main/assets/documentation/ecosystem.pdf') }}" download class="mecha-btn-gold" style="height: 38px; font-size: 10px;">
            <i class="fas fa-download"></i> DOWNLOAD PDF
        </a>
    </div>
</div>
@endsection
