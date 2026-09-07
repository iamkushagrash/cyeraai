<style>
.nav-link {
    position: relative;
    z-index: 10;
    pointer-events: auto;
}
.dropdown-header {
    pointer-events: auto;
}
.nav-link.active {
    pointer-events: auto;
}
</style>   

        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-cube"></i>
                    Cyera AI
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Session::get('user.name') }}</div>
                    <div class="user-id">{{ Session::get('user.uuid') }}</div>
                    <div style="margin-top: 8px; font-size: 0.8rem; color: var(--text-muted);">
                        Sponsor: {{ Session::get('user.sponsorid') }}
                    </div>
                    <div style="margin-top: 3px; font-size: 0.8rem; color: var(--text-muted);">
                        DOJ: {{ Session::get('user.doj') }}
                    </div>
                </div>
            </div>

            <ul class="nav-menu">
                
                <!-- Navigation -->
                <!-- <li class="nav-item">
                    <div class="nav-section-header">
                        <i class="fas fa-compass"></i> Navigation
                    </div>
                </li> -->

                <!-- Winter Blast Bonanza -->
                <!-- <li class="nav-item">
                    <a href="/User/WinterBlastBonanza" style="text-decoration: none;"><div class="nav-section-header">
                        <i class="fas fa-snowflake"></i> Winter Blast Bonanza
                    </div></a>
                </li> -->

                <!-- Winter Blast Bonanza -->
                <li class="nav-item">
                    <a href="/User/Dashboard" style="text-decoration: none;"><div class="nav-section-header">
                        <i class="fas fa-home"></i> My Dashboard
                    </div></a>
                </li>
                
                <!-- <li class="nav-item"><a href="/User/Dashboard" class="nav-link active"><i class="fas fa-home"></i>My Dashboard</a></li> -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-network-wired"></i> Activity Module
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/EditProfile" class="dropdown-link"><i class="fas fa-user-plus"></i>Update Profile</a>
                        <a href="/User/ChangePassword" class="dropdown-link"><i class="fas fa-lock"></i>Update Password</a>
                       
                    </div>
                </li>
              
                <!-- Network Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-network-wired"></i> Network
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/NewRegistration" class="dropdown-link"><i class="fas fa-user-plus"></i>New Registration</a>
                        <a href="/User/DirectTeam" class="dropdown-link"><i class="fas fa-users"></i>Direct Members</a>
                        <a href="/User/AllTeam" class="dropdown-link"><i class="fas fa-list-alt"></i>Team Detail</a>
                        <a href="/User/Treeview" class="dropdown-link"><i class="fas fa-project-diagram"></i>Tree View</a>
                        <a href="/User/SearchTeamBusiness" class="dropdown-link"><i class="fas fa-donate"></i>Search Team Business</a>
                    </div>
                </li>
                
                <!-- Loan Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-hand-holding-usd"></i> Loan
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/getUserLoan" class="dropdown-link"><i class="fas fa-file-invoice-dollar"></i>Get a Loan</a>
                        <a href="/User/RepayLoan" class="dropdown-link"><i class="fas fa-credit-card"></i>Repay Your Loan</a>
                        <a href="/User/RepaymentHistory" class="dropdown-link"><i class="fas fa-history"></i>Repayment History</a>
                    </div>
                </li>
                
                <!-- Deposit & Upgrade Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-wallet"></i> Deposit & Upgrade
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/Deposit" class="dropdown-link"><i class="fas fa-money-check-alt"></i>Deposit Fund</a>
                        <a href="/User/DepositHistory" class="dropdown-link"><i class="fas fa-receipt"></i>Deposit History</a>
                    </div>
                </li>
                
                <!-- Stake CAI Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-coins"></i> Stake CAI
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/Stake" class="dropdown-link"><i class="fas fa-lock"></i>Stake</a>
                        <a href="/User/StakingHistory" class="dropdown-link"><i class="fas fa-chart-line"></i>My Staking</a>
                        <a href="/User/StakingTxnHistory" class="dropdown-link"><i class="fas fa-exchange-alt"></i>Transaction History</a>
                    </div>
                </li>
                
                <!-- Reward & Accounts Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-award"></i> Reward & Accounts
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <!-- <a href="#" class="dropdown-link"><i class="fas fa-gift"></i>Reward Module</a> -->
                        <a href="/User/IncomeOverview" class="dropdown-link"><i class="fas fa-chart-pie"></i>Income Overview</a>
                        <a href="/User/StakingReward" class="dropdown-link"><i class="fas fa-coins"></i>Staking Reward</a>
                        <a href="/User/DirectBonus" class="dropdown-link"><i class="fas fa-user-friends"></i>Direct Reward</a>
                        <a href="/User/StakingReferralReward" class="dropdown-link"><i class="fas fa-user-tag"></i>Staking Referral Reward</a>
                        <a href="/User/TeamDevelopmentReward" class="dropdown-link"><i class="fas fa-users-cog"></i>Team Development Bonus</a>
                        <a href="/User/ClubReward" class="dropdown-link"><i class="fas fa-trophy"></i>Club Reward</a>
                        <a href="/User/LifetimeAchievementReward" class="dropdown-link"><i class="fas fa-crown"></i>Lifetime Achievement Reward</a>
                    </div>
                </li>
                
                <!-- Exchange Module Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-exchange-alt"></i> Exchange Module
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/WithdrawRequest" class="dropdown-link"><i class="fas fa-money-bill-wave"></i>Request for Withdraw</a>
                        <a href="/User/WithdrawalHistory" class="dropdown-link"><i class="fas fa-history"></i>Conversion History</a>
                    </div>
                </li>
                
                <!-- Support Module Dropdown -->
                <li class="nav-item dropdown">
                    <div class="dropdown-header">
                        <i class="fas fa-headset"></i> Support Module
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="/User/Documentation" class="dropdown-link"><i class="fas fa-book"></i>Documentation</a>
                        <a href="/User/ViewTicket" class="dropdown-link"><i class="fas fa-question-circle"></i>Support</a>
                    </div>
                </li>
                
                <!-- Logout -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="follow-title">Follow Us</div>
                <div class="social-links">
                    <a href="https://www.facebook.com/share/19g7j4h78W/" target="_blank" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="https://x.com/cyeras?t=Xf0RXwsjBEUASfL_bV8XKA&s=09" target="_blank" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/meta_wealths/" target="_blank" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="https://youtube.com/@cyeras?si=VdSuga9hMUZaXqaE" target="_blank" class="social-link"><i class="fab fa-youtube"></i></a>
                    <!-- <a href="#" target="_blank" class="social-link"><i class="fab fa-medium"></i></a> -->
                </div>
            </div>
        </div>




        <!-- Script -->



