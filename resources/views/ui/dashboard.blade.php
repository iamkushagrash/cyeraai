<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyera AI - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link href="{{asset('ctassets/css/scriptui.css')}}" rel="stylesheet">
</head>
<body>
    <!-- Premium Animated Background -->
    <div class="bg-animation" id="particles"></div>
    <div class="grid-lines"></div>
    <div class="energy-wave"></div>
    <div class="crypto-icons" id="cryptoIcons"></div>

    <div class="container">
        <!-- Sidebar -->
		 @include('ui.sidebaruser')
        <!-- Sidebar -->
      

        <!-- Main Content -->
        <div class="main-content">
             @include('ui.topbaruser')
                <!-- Personal Details -->
            <div class="personal-details neon-border">
                <h2 class="details-title">Personal Details</h2>
                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Member Id</div>
                        <div class="detail-value">CAI8082650</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">Test</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">abhi....@gmail.com</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Sponsor Id</div>
                        <div class="detail-value">CAI7655995</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">DOJ</div>
                        <div class="detail-value">2025-09-05</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value" style="color: var(--accent-green);">Active</div>
                    </div>
                </div>
            </div>


         


            <!-- Quick Actions -->
            <h2 style="margin: 20px 0 15px; color: var(--primary-blue); text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);">Quick Actions</h2>
            <div class="quick-actions">
                <div class="action-btn glow-effect">
                    <i class="fas fa-plus-circle"></i>
                    <div class="action-text">
                        <h4>Add Funds</h4>
                        <p>Deposit to your wallet</p>
                    </div>
                </div>
                <div class="action-btn glow-effect">
                    <i class="fas fa-exchange-alt"></i>
                    <div class="action-text">
                        <h4>Withdraw</h4>
                        <p>Withdraw your earnings</p>
                    </div>
                </div>
                <div class="action-btn glow-effect">
                    <i class="fas fa-user-plus"></i>
                    <div class="action-text">
                        <h4>Invite Friends</h4>
                        <p>Share referral link</p>
                    </div>
                </div>
                <div class="action-btn glow-effect">
                    <i class="fas fa-chart-bar"></i>
                    <div class="action-text">
                        <h4>View Reports</h4>
                        <p>Check your performance</p>
                    </div>
                </div>
            </div>

            <!-- Wallet Grid -->
            <h2 style="margin: 20px 0 15px; color: var(--primary-blue); text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);">Wallet Statement</h2>
            <div class="wallet-grid">
                <div class="wallet-card glow-effect">
                    <i class="fas fa-money-bill-wave"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Direct Reward</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-coins"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Staking Reward</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-user-friends"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Staking Referral Reward</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-chart-line"></i>
                    <div class="wallet-value" style="color: var(--accent-green);">0%</div>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Team Development</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-trophy"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Club Reward</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-award"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Achievement Reward</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-hand-holding-usd"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Total Earnings</div>
                </div>
                
                <div class="wallet-card glow-effect">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <div class="wallet-value">$ 0</div>
                    <div class="wallet-label">Total Withdraw</div>
                </div>
            </div>
               <!-- Investment Section -->
            <div class="investment-section">
                <div class="investment-card neon-border">
                    <h3 class="investment-title">MY INVESTMENT / Loan</h3>
                    <div class="investment-amount">$ 0 / <span style="font-size: 1.3rem;">Current: $ 0</span></div>
                    <p class="investment-subtitle">Total investment and loan amount</p>
                    
                    <h4 class="investment-title" style="margin-top: 20px;">Invite Referral Link</h4>
                    <div class="referral-link">
                        <input type="text" class="referral-input" value="https://cyera.ai/register/CAI8082650" readonly>
                        <button class="copy-btn" id="copyBtn">COPY</button>
                    </div>
                    <div class="investment-subtitle" style="text-align: center; margin-top: 10px;">Or Share</div>
                </div>
                
                <div class="investment-card neon-border">
                    <h3 class="investment-title">Booster Status</h3>
                    <div class="card-header">
                        <div class="card-value">Inactive</div>
                        <div class="status-badge status-inactive">INACTIVE</div>
                    </div>
                    <p class="investment-subtitle">Activate booster to increase rewards</p>
                  <div class="booster-grid">
                    <div style="margin-top: 25px;">
                        
                        <h4 class="investment-title">Direct Team</h4>
                        <div class="card-value">6 <span style="font-size: 1rem; color: var(--text-muted);">Active: 0</span></div>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <h4 class="investment-title">Total Team</h4>
                        <div class="card-value">9 <span style="font-size: 1rem; color: var(--text-muted);">Active: 0</span></div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- NEW: Leg Analysis Section -->
            <div class="leg-analysis-section neon-border">
                <h2 class="details-title">Leg Analysis - Power & Weaker Leg</h2>
                
                <div class="leg-stats">
                    <div class="leg-stat power-leg">
                        <div class="leg-stat-label">Power Leg (Left)</div>
                        <div class="leg-stat-value" style="color: var(--accent-green);">78%</div>
                        <div class="leg-stat-label">Total Members: 45</div>
                        <div class="leg-stat-label">Business: $12,450</div>
                    </div>
                    
                    <div class="leg-stat weaker-leg">
                        <div class="leg-stat-label">Weaker Leg (Right)</div>
                        <div class="leg-stat-value" style="color: var(--accent-red);">22%</div>
                        <div class="leg-stat-label">Total Members: 13</div>
                        <div class="leg-stat-label">Business: $3,210</div>
                    </div>
                    
                    <div class="leg-stat">
                        <div class="leg-stat-label">Leg Difference</div>
                        <div class="leg-stat-value" style="color: var(--primary-blue);">56%</div>
                        <div class="leg-stat-label">Balance Required: $9,240</div>
                        <div class="leg-stat-label">To Next Rank</div>
                    </div>
                    
                    <div class="leg-stat">
                        <div class="leg-stat-label">Carry Forward</div>
                        <div class="leg-stat-value" style="color: var(--accent-purple);">$9,240</div>
                        <div class="leg-stat-label">From Weaker Leg</div>
                        <div class="leg-stat-label">Will carry to next cycle</div>
                    </div>
                </div>
                
                <div class="leg-tree">
                    <h4 style="margin-bottom: 15px; color: var(--primary-blue);">Leg Tree View</h4>
                    
                    <div class="tree-node">
                        <div class="node-icon power">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="node-info">
                            <h4>Power Leg (Left)</h4>
                            <p>Total: 45 members | Business: $12,450 | Active: 38</p>
                        </div>
                    </div>
                    
                    <div class="tree-node">
                        <div class="node-icon weaker">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="node-info">
                            <h4>Weaker Leg (Right)</h4>
                            <p>Total: 13 members | Business: $3,210 | Active: 8</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(0, 212, 255, 0.2);">
                        <div>
                            <div style="font-size: 0.9rem; color: var(--text-muted);">Recommendation:</div>
                            <div style="color: var(--accent-green); font-weight: 600; margin-top: 5px;">
                                <i class="fas fa-lightbulb"></i> Focus on building your right leg
                            </div>
                        </div>
                        <button style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer;">
                            View Full Tree
                        </button>
                    </div>
                </div>
            </div>

            <!-- NEW: Charts Section -->
            <div class="charts-section">
                <div class="chart-container neon-border">
                    <div class="chart-header">
                        <h3 class="chart-title">Investment Growth</h3>
                        <select id="timeFilter" style="background: rgba(0, 0, 0, 0.3); color: white; border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 6px; padding: 5px 10px;">
                            <option value="7d">Last 7 Days</option>
                            <option value="30d">Last 30 Days</option>
                            <option value="90d">Last 90 Days</option>
                        </select>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="investmentChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-container neon-border">
                    <div class="chart-header">
                        <h3 class="chart-title">Working Performance</h3>
                        <select id="performanceFilter" style="background: rgba(0, 0, 0, 0.3); color: white; border: 1px solid rgba(0, 212, 255, 0.3); border-radius: 6px; padding: 5px 10px;">
                            <option value="team">Team Performance</option>
                            <option value="personal">Personal Performance</option>
                            <option value="comparison">Leg Comparison</option>
                        </select>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card glow-effect">
                    <div class="stat-label">BUSINESS</div>
                    <div class="stat-value">$ 0</div>
                    <div class="stat-label">Direct: $ 0</div>
                </div>
                
                <div class="stat-card glow-effect">
                    <div class="stat-label">Wallet Statement</div>
                    <div class="stat-value">$ 0 / 0</div>
                    <div class="stat-label">Total / Transactions</div>
                </div>
                
                <div class="stat-card glow-effect">
                    <div class="stat-label">Remaining Withdraw</div>
                    <div class="stat-value">0</div>
                    <div class="stat-label">Available withdrawals</div>
                </div>
                
                <div class="stat-card glow-effect">
                    <div class="stat-label">Rank</div>
                    <div class="stat-value">Starter</div>
                    <div class="stat-label">Current Level</div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-section neon-border">
                <h2 class="details-title">Recent Activity</h2>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-details">
                            <h5>New Member Joined</h5>
                            <p>CAI8082651 joined your team</p>
                        </div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="activity-details">
                            <h5>Reward Credited</h5>
                            <p>Direct reward from CAI8082651</p>
                        </div>
                        <div class="activity-time">1 day ago</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="activity-details">
                            <h5>Wallet Updated</h5>
                            <p>Your wallet balance was updated</p>
                        </div>
                        <div class="activity-time">2 days ago</div>
                    </div>
                </div>
            </div>

        
            <div class="footer">
                <p>© 2026 Cyera AI. All rights reserved. </p>
               
            </div>
        </div>
    </div>

	<script src="{{asset('ctassets/js/scriptui.js')}}"></script>


</body>
</html>