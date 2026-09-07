@php
    $walletAddressFull = $data['userdetails']->walletaddress ?? (Session::get('user.walletaddress') ?? (Auth::user()->walletaddress ?? ''));
    $userUuidFull = $data['userdetails']->uuid ?? (Session::get('user.uuid') ?? (Auth::user()->uuid ?? '0x4D4a'));
    $userRankVal = $data['userdetails']->userstate ?? (Session::get('user.userstate') ?? 2);
    $rankLabel = $userRankVal > 0 ? 'V'.$userRankVal : 'V2';
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

        <!-- Center: Winged Rank Shield -->
        <div class="header-center-rank">
            <div class="rank-top-txt">RANK</div>
            <div class="rank-shield-composite">
                <!-- Left 3D Wings -->
                <div class="rank-wing-left">
                    <div class="wing-bar wb1"></div>
                    <div class="wing-bar wb2"></div>
                    <div class="wing-bar wb3"></div>
                </div>
                <!-- Shield Core -->
                <div class="rank-shield-core">
                    <span class="rank-v2-val">{{ $rankLabel }}</span>
                </div>
                <!-- Right 3D Wings -->
                <div class="rank-wing-right">
                    <div class="wing-bar wb1"></div>
                    <div class="wing-bar wb2"></div>
                    <div class="wing-bar wb3"></div>
                </div>
            </div>
        </div>

        <!-- Right: Wallet Pill -->
        <div class="header-right">
            <div class="wallet-capsule" title="{{ $walletAddressFull }}">
                <i class="fas fa-wallet wallet-icon"></i>
                <span>
                    @if(!empty($walletAddressFull))
                        {{ substr($walletAddressFull, 0, 6) }}...{{ substr($walletAddressFull, -4) }}
                    @else
                        {{ substr($userUuidFull, 0, 6) }}...1e15
                    @endif
                </span>
                <i class="fas fa-chevron-down wallet-arrow"></i>
            </div>
        </div>
    </header>
</div>
