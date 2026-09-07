@php
    $caiLivePrice = \App\ProfileStore::where('id', 1)->value('price') ?? 1.25;
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
                    <span class="price-crypto-val">${{ number_format((float)$caiLivePrice, 2) }}</span>
                </div>
                <span class="price-growth-chip"><i class="fas fa-arrow-trend-up"></i> +4.2%</span>
            </div>
        </div>
    </header>
</div>

