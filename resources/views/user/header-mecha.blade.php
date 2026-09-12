@php
    $caiLivePrice = \App\ProfileStore::getLivePrice();
@endphp

<div class="hud-header-container">
    <header class="exact-mecha-header">
        <!-- Left: Hamburger & Brand Logo -->
        <div class="header-left">
            <div class="btn-hamburger" title="Menu" onclick="openMechaSidebar()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <a href="{{ url('/User/Dashboard') }}" class="brand-main-logo">
                <img src="{{ asset('logo.png') }}" alt="CYERA AI">
            </a>
        </div>

        <!-- Right: Live Token Price Capsule -->
        <div class="header-right">
            <div class="header-live-price-capsule" title="Real-Time CAI Valuation">
                <div class="price-pulse-indicator">
                    <span class="live-dot-ping"></span>
                    <span class="live-dot-solid"></span>
                </div>
                <div class="price-content-block">
                    <span class="price-crypto-label">CAI</span>
                    <span class="price-crypto-val">${{ number_format((float)$caiLivePrice, 4) }}</span>
                </div>
            </div>
        </div>
    </header>
</div>

<!-- Global Web3 Account Switch & Multi-Wallet Sync Guard -->
@include('user.web3-guard')


