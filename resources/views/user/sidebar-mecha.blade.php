<!-- ============================================================
     DITTO MECHA CYBERPUNK HUD SIDEBAR (100% MATCH)
     ============================================================ -->
<div class="mecha-sidebar-backdrop" id="mechaBackdrop" onclick="closeMechaSidebar()"></div>

<aside class="mecha-sidebar-drawer" id="mechaSidebar">
    <div class="mecha-sidebar-scroll">
        <!-- 1. Header Card: Crowned Lion Emblem + Gear + Badges + Connected Wallet + 4 Mini Stats -->
        <div class="mecha-sidebar-brand-card">
            <!-- Vector Mecha SVG Outer & Inner Frame Overlay -->
            <svg class="profile-card-svg-frame" viewBox="0 0 300 215" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Outer Chamfered Border -->
                <path d="M 16 3 H 284 L 297 16 V 199 L 284 212 H 16 L 3 199 V 16 Z" fill="none" stroke="#E5A823" stroke-width="1.8" />
                <!-- Inner Contour Hairline -->
                <path d="M 19 7 H 281 L 293 19 V 196 L 281 208 H 19 L 7 196 V 19 Z" fill="none" stroke="rgba(229, 168, 35, 0.35)" stroke-width="1" />
                <!-- Corner L-Bracket Accents -->
                <path d="M 3 24 V 16 L 16 3 H 24" fill="none" stroke="#FFD700" stroke-width="2.5" />
                <path d="M 276 3 H 284 L 297 16 V 24" fill="none" stroke="#FFD700" stroke-width="2.5" />
                <path d="M 297 191 V 199 L 284 212 H 276" fill="none" stroke="#FFD700" stroke-width="2.5" />
                <path d="M 24 212 H 16 L 3 199 V 191" fill="none" stroke="#FFD700" stroke-width="2.5" />
                <!-- Screw Rivets -->
                <circle cx="12" cy="12" r="1.5" fill="#FFD700" opacity="0.8" />
                <circle cx="288" cy="12" r="1.5" fill="#FFD700" opacity="0.8" />
                <circle cx="288" cy="203" r="1.5" fill="#FFD700" opacity="0.8" />
                <circle cx="12" cy="203" r="1.5" fill="#FFD700" opacity="0.8" />
            </svg>

            <!-- Gear Settings Button Top-Right -->
            <button type="button" class="btn-header-gear" onclick="closeMechaSidebar()" title="Close / Settings">
                <i class="fas fa-gear"></i>
            </button>

            <!-- Top Row: Crowned Lion Medallion & Info Stack -->
            <div class="brand-card-top-row">
                <div class="mecha-emblem-dial">
                    <img src="{{ asset('images/cai-lion-coin.png') }}" alt="CYERA AI">
                </div>

                <div class="brand-info-col">
                    <div class="mecha-brand-title">CYERA AI</div>
                    <div class="mecha-brand-subtitle">— CAI ECOSYSTEM —</div>

                    <!-- Rank & Capping Tier Badges Row -->
                    <div class="mecha-user-quick-rank">
                        <div class="rank-tag-badge">
                            <span class="lbl">RANK</span>
                            <span class="val">V2</span>
                        </div>
                        <div class="tier-tag-badge">
                            <span class="lbl">CAPPING TIER</span>
                            <span class="val">5X</span>
                        </div>
                    </div>

                    <!-- Connected Wallet Capsule -->
                    <div class="header-wallet-capsule">
                        <div class="hwc-left">
                            <i class="fas fa-wallet"></i>
                            <span>
                                @if(!empty(Session::get('user.walletaddress')))
                                    {{ substr(Session::get('user.walletaddress'), 0, 6) }}...{{ substr(Session::get('user.walletaddress'), -4) }}
                                @else
                                    0x4D4a...1e15
                                @endif
                            </span>
                        </div>
                        <div class="hwc-right">
                            <span class="mecha-live-dot"></span>
                            <span>CONNECTED</span>
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: 4 Mini Stat Boxes Grid -->
            <div class="header-4stat-grid">
                <div class="stat-mini-box">
                    <div class="stat-mini-hdr">
                        <span class="stat-mini-icon-circle"><i class="fas fa-coins"></i></span>
                        <span class="stat-mini-lbl">TOTAL PORTFOLIO</span>
                    </div>
                    <div class="stat-mini-val">$1,250.00</div>
                    <div class="stat-mini-sub">$</div>
                </div>

                <div class="stat-mini-box">
                    <div class="stat-mini-hdr">
                        <span class="stat-mini-icon-circle"><i class="fas fa-bolt"></i></span>
                        <span class="stat-mini-lbl">CLAIMABLE CAI</span>
                    </div>
                    <div class="stat-mini-val">45.50 CAI</div>
                    <div class="stat-mini-sub">≈ $56.87</div>
                </div>

                <div class="stat-mini-box">
                    <div class="stat-mini-hdr">
                        <span class="stat-mini-icon-circle"><i class="fas fa-chart-pie"></i></span>
                        <span class="stat-mini-lbl">TOTAL EARNED</span>
                    </div>
                    <div class="stat-mini-val">$1,480.00</div>
                    <div class="stat-mini-sub">$</div>
                </div>

                <div class="stat-mini-box">
                    <div class="stat-mini-hdr">
                        <span class="stat-mini-icon-circle"><i class="fas fa-calendar-check"></i></span>
                        <span class="stat-mini-lbl">JOINED</span>
                    </div>
                    <div class="stat-mini-val">26 APR 2025</div>
                    <div class="stat-mini-sub">12:45 PM</div>
                </div>
            </div>
        </div>

        <!-- 2. Main Navigation Section -->
        <div class="mecha-nav-section">
            <div class="mecha-section-label">
                <span>— MAIN NAVIGATION —</span>
            </div>

            <!-- Dashboard (Active) -->
            <div class="mecha-nav-item">
                <a href="{{ url('/User/Dashboard') }}" class="mecha-nav-link {{ request()->is('User/Dashboard*') ? 'active' : '' }}">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-table-cells-large"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">Dashboard</span>
                            <span class="nav-sub-txt">Control Center & Stats</span>
                        </div>
                    </div>
                    <span class="nav-active-badge">ACTIVE</span>
                </a>
            </div>

            <!-- Invest / Staking (Dropdown) -->
            <div class="mecha-nav-item mecha-nav-has-sub {{ request()->is('User/Deposit*') || request()->is('User/Stake*') || request()->is('User/StakingHistory*') ? 'open' : '' }}">
                <div class="mecha-nav-link" onclick="toggleMechaSubmenu(this)">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">Invest / Staking</span>
                            <span class="nav-sub-txt">Deposit & Staking Pools</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down nav-caret"></i>
                </div>
                <div class="mecha-submenu">
                    <a href="{{ url('/User/Deposit') }}" class="mecha-sub-link {{ request()->is('User/Deposit') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> New Deposit</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/DepositHistory') }}" class="mecha-sub-link {{ request()->is('User/DepositHistory') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Deposit History</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/Stake') }}" class="mecha-sub-link {{ request()->is('User/Stake') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Stake CAI</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/StakingHistory') }}" class="mecha-sub-link {{ request()->is('User/StakingHistory') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> My Staking</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                </div>
            </div>

            <!-- My Team (Dropdown) -->
            <div class="mecha-nav-item mecha-nav-has-sub {{ request()->is('User/NewRegistration*') || request()->is('User/DirectTeam*') || request()->is('User/AllTeam*') || request()->is('User/Treeview*') || request()->is('User/SearchTeamBusiness*') ? 'open' : '' }}">
                <div class="mecha-nav-link" onclick="toggleMechaSubmenu(this)">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">My Team</span>
                            <span class="nav-sub-txt">Network & Referrals</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down nav-caret"></i>
                </div>
                <div class="mecha-submenu">
                    <a href="{{ url('/User/NewRegistration') }}" class="mecha-sub-link {{ request()->is('User/NewRegistration') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> New Registration</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/DirectTeam') }}" class="mecha-sub-link {{ request()->is('User/DirectTeam') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Direct Members</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/AllTeam') }}" class="mecha-sub-link {{ request()->is('User/AllTeam') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Team Details</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/Treeview') }}" class="mecha-sub-link {{ request()->is('User/Treeview') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Tree View</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/SearchTeamBusiness') }}" class="mecha-sub-link {{ request()->is('User/SearchTeamBusiness') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Team Search</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                </div>
            </div>

            <!-- Income & Rewards (Dropdown) -->
            <div class="mecha-nav-item mecha-nav-has-sub {{ request()->is('User/IncomeOverview*') || request()->is('User/StakingReward*') || request()->is('User/DirectBonus*') || request()->is('User/StakingReferralReward*') || request()->is('User/TeamDevelopmentReward*') || request()->is('User/ClubReward*') || request()->is('User/LifetimeAchievementReward*') ? 'open' : '' }}">
                <div class="mecha-nav-link" onclick="toggleMechaSubmenu(this)">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">Income & Rewards</span>
                            <span class="nav-sub-txt">All Reward Streams & Pools</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down nav-caret"></i>
                </div>
                <div class="mecha-submenu">
                    <a href="{{ url('/User/IncomeOverview') }}" class="mecha-sub-link {{ request()->is('User/IncomeOverview') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Income Overview</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/StakingReward') }}" class="mecha-sub-link {{ request()->is('User/StakingReward') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Daily Staking ROI</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/DirectBonus') }}" class="mecha-sub-link {{ request()->is('User/DirectBonus') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Direct Referral Bonus</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/StakingReferralReward') }}" class="mecha-sub-link {{ request()->is('User/StakingReferralReward*') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Staking Referral Reward</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/TeamDevelopmentReward') }}" class="mecha-sub-link {{ request()->is('User/TeamDevelopmentReward*') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Team Development</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/ClubReward') }}" class="mecha-sub-link {{ request()->is('User/ClubReward*') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Club & Pool Rewards</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/LifetimeAchievementReward') }}" class="mecha-sub-link {{ request()->is('User/LifetimeAchievementReward*') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Achievement Rewards</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                </div>
            </div>

            <!-- Wallet & Exchange (Dropdown) -->
            <div class="mecha-nav-item mecha-nav-has-sub {{ request()->is('User/WithdrawRequest*') || request()->is('User/WithdrawalHistory*') ? 'open' : '' }}">
                <div class="mecha-nav-link" onclick="toggleMechaSubmenu(this)">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">Wallet & Exchange</span>
                            <span class="nav-sub-txt">Withdrawals & Vault</span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down nav-caret"></i>
                </div>
                <div class="mecha-submenu">
                    <a href="{{ url('/User/WithdrawRequest') }}" class="mecha-sub-link {{ request()->is('User/WithdrawRequest') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Request Withdraw</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                    <a href="{{ url('/User/WithdrawalHistory') }}" class="mecha-sub-link {{ request()->is('User/WithdrawalHistory') ? 'active' : '' }}">
                        <div class="sub-left-txt"><span class="sub-dot"></span> Withdrawal History</div>
                        <i class="fas fa-arrow-right sub-arr"></i>
                    </a>
                </div>
            </div>

            <!-- Leaderboard / Tree -->
            <div class="mecha-nav-item">
                <a href="{{ url('/User/Treeview') }}" class="mecha-nav-link {{ request()->is('User/Treeview') ? 'active' : '' }}">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">Leaderboard / Tree</span>
                            <span class="nav-sub-txt">Genealogy & Top Earners</span>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right nav-link-arr"></i>
                </a>
            </div>

            <!-- Transactions -->
            <div class="mecha-nav-item">
                <a href="{{ url('/User/StakingTxnHistory') }}" class="mecha-nav-link {{ request()->is('User/StakingTxnHistory') ? 'active' : '' }}">
                    <div class="nav-link-left-grp">
                        <div class="nav-ico-orb">
                            <i class="fas fa-arrow-right-arrow-left"></i>
                        </div>
                        <div class="nav-txt-stack">
                            <span class="nav-link-txt">Transactions</span>
                            <span class="nav-sub-txt">On-Chain History</span>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right nav-link-arr"></i>
                </a>
            </div>
        </div>

        <!-- 3. Support Banner Card -->
        <div class="mecha-support-banner-card">
            <div class="sup-card-left">
                <div class="sup-ico-hex">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="sup-txt-col">
                    <span class="sup-title-txt">24/7 SUPPORT</span>
                    <span class="sup-sub-txt">We are here to help you!</span>
                </div>
            </div>
            <a href="{{ url('/User/ViewTicket') }}" class="btn-contact-sup">
                CONTACT SUPPORT <i class="fas fa-arrow-right" style="font-size: 7px;"></i>
            </a>
        </div>

        <!-- 4. Social Links Bar (Bottom) -->
        <div class="mecha-social-bar">
            <div class="mecha-social-title">OFFICIAL COMMUNITY</div>
            <div class="mecha-social-icons">
                <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn insta" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn fb" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn twitter" title="Twitter / X">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <a href="https://t.me" target="_blank" rel="noopener noreferrer" class="social-icon-btn telegram" title="Telegram">
                    <i class="fab fa-telegram"></i>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn youtube" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>

        <div style="height: 16px;"></div>
    </div>
</aside>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function openMechaSidebar() {
        document.getElementById('mechaSidebar')?.classList.add('active');
        document.getElementById('mechaBackdrop')?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMechaSidebar() {
        document.getElementById('mechaSidebar')?.classList.remove('active');
        document.getElementById('mechaBackdrop')?.classList.remove('active');
        document.body.style.overflow = '';
    }

    function toggleMechaSubmenu(elem) {
        const parent = elem.closest('.mecha-nav-item');
        if (parent) {
            parent.classList.toggle('open');
        }
    }
</script>
