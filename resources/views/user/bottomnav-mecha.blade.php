<nav class="hud-bottom-nav">
    <!-- 1. HOME -->
    <a href="{{ url('/User/Dashboard') }}" class="hud-nav-tab {{ request()->is('User/Dashboard*') ? 'active' : '' }}" id="nav-home">
        <div class="tab-ico-wrap">
            <svg viewBox="0 0 24 24" class="nav-svg-icon" fill="none">
                <path d="M3 10.2L12 3L21 10.2V19.5C21 20.3284 20.3284 21 19.5 21H14.5V14.5H9.5V21H4.5C3.67157 21 3 20.3284 3 19.5V10.2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="currentColor" fill-opacity="0.22"/>
                <circle cx="12" cy="9.5" r="1.8" fill="currentColor"/>
            </svg>
        </div>
        <span class="tab-lbl">HOME</span>
        <div class="tab-active-bar"></div>
    </a>

    <!-- 2. STAKING -->
    <a href="{{ url('/User/Stake') }}" class="hud-nav-tab {{ request()->is('User/Stake*') || request()->is('User/Deposit*') || request()->is('User/Staking*') ? 'active' : '' }}" id="nav-staking">
        <div class="tab-ico-wrap">
            <svg viewBox="0 0 24 24" class="nav-svg-icon" fill="none">
                <ellipse cx="12" cy="6.5" rx="8" ry="3.2" stroke="currentColor" stroke-width="1.9" fill="currentColor" fill-opacity="0.25"/>
                <path d="M4 6.5V11.5C4 13.27 7.58 14.7 12 14.7C16.42 14.7 20 13.27 20 11.5V6.5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                <path d="M4 11.5V16.5C4 18.27 7.58 19.7 12 19.7C16.42 19.7 20 18.27 20 16.5V11.5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                <path d="M12 5V8M10.2 6.5H13.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </div>
        <span class="tab-lbl">STAKING</span>
        <div class="tab-active-bar"></div>
    </a>

    <!-- 3. CENTER CAI COIN BUTTON -->
    <a href="{{ url('/User/Stake') }}" class="hud-center-lion-btn" title="Stake / CAI">
        <div class="nav-lion-corona">
            <div class="nav-lion-ring">
                <img src="{{ asset('images/cai-token-coin.png') }}" alt="CYERA AI">
            </div>
        </div>
        <span class="nav-cai-lbl">CAI</span>
    </a>

    <!-- 4. TEAM -->
    <a href="{{ url('/User/DirectTeam') }}" class="hud-nav-tab {{ request()->is('User/DirectTeam*') || request()->is('User/AllTeam*') || request()->is('User/Treeview*') || request()->is('User/NewRegistration*') ? 'active' : '' }}" id="nav-team">
        <div class="tab-ico-wrap">
            <svg viewBox="0 0 24 24" class="nav-svg-icon" fill="none">
                <circle cx="12" cy="7" r="3.2" stroke="currentColor" stroke-width="1.9" fill="currentColor" fill-opacity="0.25"/>
                <path d="M6 19C6 15.6863 8.68629 13 12 13C15.3137 13 18 15.6863 18 19" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                <circle cx="4.5" cy="9" r="2.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" fill="currentColor" fill-opacity="0.18"/>
                <path d="M1.5 18C1.5 15.5 2.8 13.8 4.5 13.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <circle cx="19.5" cy="9" r="2.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" fill="currentColor" fill-opacity="0.18"/>
                <path d="M22.5 18C22.5 15.5 21.2 13.8 19.5 13.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </div>
        <span class="tab-lbl">TEAM</span>
        <div class="tab-active-bar"></div>
    </a>

    <!-- 5. WALLET -->
    <a href="{{ url('/User/IncomeOverview') }}" class="hud-nav-tab {{ request()->is('User/IncomeOverview*') || request()->is('User/Withdraw*') ? 'active' : '' }}" id="nav-wallet">
        <div class="tab-ico-wrap">
            <svg viewBox="0 0 24 24" class="nav-svg-icon" fill="none">
                <rect x="2" y="5" width="20" height="15" rx="3.5" stroke="currentColor" stroke-width="1.9" fill="currentColor" fill-opacity="0.22"/>
                <path d="M2 9.5H22" stroke="currentColor" stroke-width="1.6"/>
                <path d="M15 14H19.5C20.3284 14 21 14.6716 21 15.5C21 16.3284 20.3284 17 19.5 17H15C14.1716 17 13.5 16.3284 13.5 15.5C13.5 14.6716 14.1716 14 15 14Z" stroke="currentColor" stroke-width="1.6" fill="currentColor" fill-opacity="0.45"/>
                <circle cx="16.5" cy="15.5" r="0.9" fill="currentColor"/>
            </svg>
        </div>
        <span class="tab-lbl">WALLET</span>
        <div class="tab-active-bar"></div>
    </a>
</nav>
