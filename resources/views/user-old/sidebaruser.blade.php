<style>
/* Social Media Section at the bottom */
.social-media {
    /*position: absolute;*/
    bottom: 20px; /* Adjust the space from the bottom of the sidebar */
    width: 100%;
    padding: 10px;
    display: flex; /* Use flexbox to align items horizontally */
    justify-content: center; /* Align the icons horizontally */
    gap: 20px; /* Add space between the icons */
}

/* Social Media Icons */
.social-media .menu-link .menu-icon {
    font-size: 24px; /* Icon size */
    color: rgba(210, 171, 60, 0.75); /* Set icon color to your theme color */
    transition: color 0.3s ease;
}

/* Hover effect for the social media icons */
.social-media .menu-link:hover .menu-icon {
    color: rgba(210, 171, 60, 1); /* Full opacity on hover */
}

/* Menu Header (Follow Us) */
.social-media .menu-header {
    color: rgba(210, 171, 60, 0.75); /* Set header text color to your theme color */
    font-size: 14px; /* Adjust the size as needed */
    text-align: center; /* Align text in the center */
    margin-bottom: 10px; /* Optional, to add space between the header and icons */
}

/* Styling for social media items */
.social-media .menu-item {
    list-style-type: none;
}

/* Hide menu text (optional) */
.social-media .menu-text {
    display: none;
}

</style>
		<div id="sidebar" class="app-sidebar">
			  <video autoplay="" muted="" loop="" playsinline="" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; z-index:-1;filter: sepia(100%) hue-rotate(15deg) saturate(600%) brightness(120%);">
			    <source src="https://cyera.ai/ctassets/myvideo1.mp4" type="video/mp4">
			  </video>
			<!-- BEGIN scrollbar -->
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<!-- BEGIN menu -->
				<div class="menu">
					<div><br></div>
					<div class="menu-header">Your Details</div>
					<div class="menu-item">
						<a href="#" class="menu-link">
							<span class="menu-text">Name : {{ Session::get('user.name') }}</span>
						</a>
					</div>
					<div class="menu-item">
						<a href="#" class="menu-link">
							<span class="menu-text">User Id : {{ Session::get('user.uuid') }}</span>
						</a>
					</div>
					<div class="menu-item">
						<a href="#" class="menu-link">
							<span class="menu-text">Sponsor Id : {{ Session::get('user.sponsorid') }}</span>
						</a>
					</div>
					<div class="menu-item">
						<a href="#" class="menu-link">
							<span class="menu-text">DOJ : {{ Session::get('user.doj') }}</span>
						</a>
					</div>
					<div class="menu-header">Navigation</div>
					<!-- <div class="menu-item">
						<a href="/User/ArbitrageDashboard" class="menu-link">
							<span class="menu-icon"><i class="bi bi-globe"></i></span>
							<span class="menu-text">Arbitrage Panel</span>
						</a>
					</div> -->
					<div class="menu-item">
						<a href="/User/WinterBlastBonanza" class="menu-link">
							<span class="menu-icon"><i class="bi bi-globe"></i></span>
							<span class="menu-text">Winter Blast Bonanza</span>
						</a>
					</div>
					<div class="menu-item">
						<a href="/User/Dashboard" class="menu-link">
							<span class="menu-icon"><i class="bi bi-cpu"></i></span>
							<span class="menu-text">My Dashboard</span>
						</a>
					</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-layout-sidebar"></i></span>
							<span class="menu-text">Activity Module</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/EditProfile" class="menu-link">
									<span class="menu-text">Update Profile</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/ChangePassword" class="menu-link">
									<span class="menu-text">Update Password</span>
								</a>
							</div>
						</div>
					</div>
					
					<div class="menu-header">Network</div>
					<div class="menu-item">
						<a href="/User/NewRegistration" class="menu-link">
							<span class="menu-icon"><i class="bi bi-person"></i></span>
							<span class="menu-text">New Registration</span>
						</a>
					</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-people"></i></span>
							<span class="menu-text">Network Module</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/DirectTeam" class="menu-link">
									<span class="menu-text">Direct Members</span>
								</a>
							</div>

							<div class="menu-item">
								<a href="/User/AllTeam" class="menu-link">
									<span class="menu-text">Team Detail</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/Treeview" class="menu-link">
									<span class="menu-text">Tree View</span>
								</a>
							</div>
							<!-- <div class="menu-item">
								<a href="/User/TeamSummary" class="menu-link">
									<span class="menu-text">Team Summary</span>
								</a>
							</div> -->
						</div>
					</div>

					<div class="menu-header">Loan</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-currency-dollar"></i></span>
							<span class="menu-text">Loan</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/getUserLoan" class="menu-link">
									<span class="menu-text">Get a Loan</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/RepayLoan" class="menu-link">
									<span class="menu-text">Repay Your Loan</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/RepaymentHistory" class="menu-link">
									<span class="menu-text">Repayment History</span>
								</a>
							</div>
						</div>
					</div>
					
					<div class="menu-header">Deposit & Upgrade</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-download"></i></span>
							<span class="menu-text">Deposit</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/Deposit" class="menu-link">
									<span class="menu-text">Deposit Fund</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/DepositHistory" class="menu-link">
									<span class="menu-text">Deposit History</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-globe"></i></span>
							<span class="menu-text">Stake CAI</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/Stake" class="menu-link">
									<span class="menu-text">Stake</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/StakingHistory" class="menu-link">
									<span class="menu-text">My Staking</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/StakingTxnHistory" class="menu-link">
									<span class="menu-text">Transaction History</span>
								</a>
							</div>
						</div>
					</div>

					<div class="menu-divider"></div>
					<div class="menu-header">Reward & Accounts</div>
					<div class="menu-item has-sub">
						<!-- <a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-columns-gap"></i></span>
							<span class="menu-text">Account Module</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a> -->
						<!-- <div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/MyPackage" class="menu-link">
									<span class="menu-text">Orbit Detail</span>
								</a>
							</div>
						</div> -->
					</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-bar-chart"></i></span>
							<span class="menu-text">Reward Module</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/IncomeOverview" class="menu-link">
									<span class="menu-text">Income Overview</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/StakingReward" class="menu-link">
									<span class="menu-text">Staking Reward</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/DirectBonus" class="menu-link">
									<span class="menu-text">Direct Reward</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/StakingReferralReward" class="menu-link">
									<span class="menu-text">Staking Referral Reward</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/TeamDevelopmentReward" class="menu-link">
									<span class="menu-text">Team Development Bonus</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/ClubReward" class="menu-link">
									<span class="menu-text">Club Reward</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/LifetimeAchievementReward" class="menu-link">
									<span class="menu-text">Lifetime Achievement Reward</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-calendar4"></i></span>
							<span class="menu-text">Exchange Module</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/WithdrawRequest" class="menu-link">
									<span class="menu-text">Request for Withdraw</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/WithdrawalHistory" class="menu-link">
									<span class="menu-text">Conversion History</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub">
						<a href="#" class="menu-link">
							<span class="menu-icon"><i class="bi bi-envelope"></i></span>
							<span class="menu-text">Support Module</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="/User/Documentation" class="menu-link">
									<span class="menu-text">Documentation</span>
								</a>
							</div>
							<div class="menu-item">
								<a href="/User/ViewTicket" class="menu-link">
									<span class="menu-text">Support</span>
								</a>
							</div>
						</div>
						
					</div>

					<div class="menu-item">
						<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link">
							<span class="menu-icon"><i class="bi bi-toggle-off"></i></span>
							<span class="menu-text">Logout</span>
						</a>
					</div>
					

					<div class="menu-divider"></div>
					<div class="menu-header">Follow Us</div>

				</div>
				<!-- END menu -->
				<!-- Social Media Section -->
				
		        <div class="social-media">
				    <!-- <div class="menu-header">Follow Us</div> -->
				    <div class="menu-item">
				        <a href="https://www.facebook.com/share/19g7j4h78W/" target="_blank" class="menu-link">
				            <span class="menu-icon"><i class="bi bi-facebook"></i></span>
				        </a>
				    </div>
				    <div class="menu-item">
				        <a href="https://x.com/cyeras?t=Xf0RXwsjBEUASfL_bV8XKA&s=09" target="_blank" class="menu-link">
				            <span class="menu-icon"><i class="bi bi-twitter"></i></span>
				        </a>
				    </div>
				    <div class="menu-item">
				        <a href="https://www.instagram.com/meta_wealths/" target="_blank" class="menu-link">
				            <span class="menu-icon"><i class="bi bi-instagram"></i></span>
				        </a>
				    </div>
				    <div class="menu-item">
				        <a href="https://youtube.com/@cyeras?si=VdSuga9hMUZaXqaE" target="_blank" class="menu-link">
				            <span class="menu-icon"><i class="bi bi-youtube"></i></span>
				        </a>
				    </div>
				</div>
        		<!-- END Social Media Section -->

				
			</div>
			<!-- END scrollbar -->
		</div>