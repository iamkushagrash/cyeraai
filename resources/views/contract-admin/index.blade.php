<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>CYERA AI | Decentralized Smart Contract Owner Governance</title>
    <meta name="description" content="Decentralized Smart Contract Owner Admin Portal for Cyera Protocol on BNB Smart Chain.">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-base: #040508;
            --bg-card: rgba(10, 13, 22, 0.85);
            --bg-card-elevated: rgba(14, 18, 30, 0.95);
            --border-gold: rgba(255, 215, 0, 0.35);
            --border-dim: rgba(255, 255, 255, 0.08);
            --gold-primary: #FFD700;
            --gold-glow: rgba(255, 215, 0, 0.15);
            --green-neon: #00FF88;
            --green-glow: rgba(0, 255, 136, 0.15);
            --cyan-neon: #00E5FF;
            --red-neon: #FF3366;
            --text-primary: #FFFFFF;
            --text-muted: #94A3B8;
            --text-dim: #64748B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glow & Canvas */
        .cyber-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background: 
                radial-gradient(circle at 15% 15%, rgba(255, 215, 0, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 85%, rgba(0, 255, 136, 0.06) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(0, 229, 255, 0.04) 0%, transparent 60%);
        }

        .cyber-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 1;
        }

        .app-container {
            position: relative;
            z-index: 2;
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 20px 80px;
        }

        /* Header Bar */
        .portal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 16px;
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 20px var(--gold-glow);
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .portal-logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 215, 0, 0.12);
            border: 1px solid var(--border-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-primary);
            font-size: 20px;
            box-shadow: 0 0 15px var(--gold-glow);
        }

        .portal-title-wrap h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .portal-title-wrap h1 span.badge-decentralized {
            font-size: 9px;
            background: rgba(0, 255, 136, 0.15);
            color: var(--green-neon);
            border: 1px solid rgba(0, 255, 136, 0.35);
            padding: 2px 8px;
            border-radius: 20px;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .portal-title-wrap p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .portal-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Buttons */
        .btn-web3 {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
            outline: none;
        }

        .btn-web3-gold {
            background: linear-gradient(135deg, #FFD700 0%, #D97706 100%);
            color: #000;
            box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3);
        }

        .btn-web3-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 166, 35, 0.5);
        }

        .btn-web3-outline {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-dim);
            color: var(--text-primary);
        }

        .btn-web3-outline:hover {
            border-color: var(--border-gold);
            color: var(--gold-primary);
            background: rgba(255, 215, 0, 0.08);
        }

        .btn-web3-red {
            background: rgba(255, 51, 102, 0.15);
            border: 1px solid rgba(255, 51, 102, 0.4);
            color: var(--red-neon);
        }

        .btn-web3-red:hover {
            background: rgba(255, 51, 102, 0.25);
            transform: translateY(-1px);
        }

        .btn-web3-green {
            background: rgba(0, 255, 136, 0.15);
            border: 1px solid rgba(0, 255, 136, 0.4);
            color: var(--green-neon);
        }

        .btn-web3-green:hover {
            background: rgba(0, 255, 136, 0.25);
            transform: translateY(-1px);
        }

        .network-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-dim);
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #64748B;
        }

        .status-dot.active {
            background: var(--green-neon);
            box-shadow: 0 0 8px var(--green-neon);
        }

        /* LOCKED OVERLAY / ACCESS BARRIER */
        .lock-barrier-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            min-height: 60vh;
        }

        .lock-card {
            background: var(--bg-card);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 44px 36px;
            max-width: 540px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(24px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8), 0 0 35px var(--gold-glow);
            animation: lockCardZoom 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes lockCardZoom {
            from { opacity: 0; transform: scale(0.94) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .lock-icon-box {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid var(--border-gold);
            color: var(--gold-primary);
            font-size: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 0 30px var(--gold-glow);
        }

        .lock-icon-box.unauthorized {
            background: rgba(255, 51, 102, 0.1);
            border-color: rgba(255, 51, 102, 0.4);
            color: var(--red-neon);
            box-shadow: 0 0 30px rgba(255, 51, 102, 0.2);
        }

        .lock-card h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .lock-card p {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .lock-details-box {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-dim);
            border-radius: 12px;
            padding: 14px;
            text-align: left;
            margin-bottom: 24px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
        }

        .lock-details-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
        }

        .lock-details-row:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .lock-details-row .lbl {
            color: var(--text-dim);
        }

        .lock-details-row .val {
            color: var(--gold-primary);
            font-weight: 600;
        }

        /* Top Metrics Row */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            border-radius: 16px;
            padding: 18px 20px;
            backdrop-filter: blur(16px);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            border-color: var(--border-gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gold-primary);
        }

        .metric-card.green::before { background: var(--green-neon); }
        .metric-card.cyan::before { background: var(--cyan-neon); }
        .metric-card.red::before { background: var(--red-neon); }

        .metric-lbl {
            font-size: 0.72rem;
            color: var(--text-dim);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .metric-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .metric-sub {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Main Admin Tabs */
        .admin-nav-tabs {
            display: flex;
            gap: 8px;
            background: var(--bg-card);
            padding: 8px;
            border-radius: 14px;
            border: 1px solid var(--border-dim);
            margin-bottom: 24px;
            overflow-x: auto;
        }

        .nav-tab-btn {
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-tab-btn:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-tab-btn.active {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(245, 166, 35, 0.1) 100%);
            border: 1px solid var(--border-gold);
            color: var(--gold-primary);
            box-shadow: 0 0 15px var(--gold-glow);
        }

        /* Tab Content Panel */
        .tab-panel-section {
            display: none;
            animation: tabFadeIn 0.3s ease forwards;
        }

        .tab-panel-section.active {
            display: block;
        }

        @keyframes tabFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Control Card */
        .control-card {
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 20px;
            backdrop-filter: blur(16px);
        }

        .control-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-dim);
            flex-wrap: wrap;
            gap: 12px;
        }

        .control-card-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .control-card-title i {
            font-size: 20px;
            color: var(--gold-primary);
        }

        .control-card-title h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .control-card-title p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .card-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(255, 215, 0, 0.12);
            color: var(--gold-primary);
            border: 1px solid var(--border-gold);
            text-transform: uppercase;
        }

        /* Grid inside tab */
        .control-grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        /* Form Group */
        .control-form-group {
            margin-bottom: 16px;
        }

        .control-label {
            display: block;
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .control-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-dim);
            border-radius: 10px;
            padding: 12px 14px;
            color: var(--text-primary);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            outline: none;
            transition: all 0.2s ease;
        }

        .control-input:focus {
            border-color: var(--gold-primary);
            box-shadow: 0 0 12px var(--gold-glow);
        }

        .control-help {
            font-size: 0.72rem;
            color: var(--text-dim);
            margin-top: 4px;
        }

        /* Info Box */
        .info-matrix-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-dim);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .info-matrix-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 12px;
        }

        .info-matrix-row:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .info-matrix-row .k {
            color: var(--text-muted);
        }

        .info-matrix-row .v {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Event Stream Table */
        .event-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .event-table th {
            text-align: left;
            padding: 10px 12px;
            background: rgba(0, 0, 0, 0.5);
            color: var(--text-dim);
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-dim);
        }

        .event-table td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
        }

        .event-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .event-badge.invest { background: rgba(0, 255, 136, 0.15); color: var(--green-neon); }
        .event-badge.claim { background: rgba(255, 215, 0, 0.15); color: var(--gold-primary); }
        .event-badge.withdraw { background: rgba(0, 229, 255, 0.15); color: var(--cyan-neon); }
        .event-badge.whitelist { background: rgba(179, 75, 254, 0.15); color: #B34BFE; }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-msg {
            background: #0d111d;
            border: 1px solid var(--border-gold);
            border-radius: 12px;
            padding: 14px 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8), 0 0 20px var(--gold-glow);
            font-size: 12px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: toastSlideIn 0.3s ease forwards;
            max-width: 380px;
        }

        @keyframes toastSlideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .toast-msg.success { border-color: var(--green-neon); }
        .toast-msg.error { border-color: var(--red-neon); }

        /* Loading Spinner */
        .spin-loader {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <div class="cyber-bg"></div>
    <div class="cyber-grid"></div>

    <div class="app-container">

        <!-- ============================================================
             1. HEADER BAR & WEB3 CONNECTIVITY
             ============================================================ -->
        <header class="portal-header">
            <div class="portal-brand">
                <div class="portal-logo-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="portal-title-wrap">
                    <h1>
                        CYERA PROTOCOL <span class="badge-decentralized">Contract Owner Admin</span>
                    </h1>
                    <p>Decentralized Governance &amp; Multi-Vault Management Engine</p>
                </div>
            </div>

            <div class="portal-header-actions">
                <div class="network-pill" id="networkPill">
                    <span class="status-dot" id="networkDot"></span>
                    <span id="networkText">BSC Mainnet (Chain 56)</span>
                </div>

                <button type="button" class="btn-web3 btn-web3-gold" id="btnConnectWallet" onclick="connectWallet()">
                    <i class="fas fa-wallet"></i>
                    <span id="btnConnectText">Connect Owner Wallet</span>
                </button>

                <button type="button" class="btn-web3 btn-web3-outline" id="btnDisconnectWallet" onclick="disconnectWallet()" style="display: none;">
                    <i class="fas fa-power-off"></i> Disconnect
                </button>
            </div>
        </header>

        <!-- ============================================================
             2. ACCESS BARRIER / LOCKED STATE (Shown when Not Connected or Not Owner)
             ============================================================ -->
        <div id="lockBarrier" class="lock-barrier-container">
            <div class="lock-card">
                <div class="lock-icon-box" id="lockIconBox">
                    <i class="fas fa-lock" id="lockIcon"></i>
                </div>
                <h2 id="lockTitle">Contract Owner Access Only</h2>
                <p id="lockDesc">
                    This decentralized portal connects directly to Cyera smart contracts on BNB Smart Chain. You must connect the authorized <strong>Contract Owner Wallet</strong> to view and execute protocol commands.
                </p>

                <div class="lock-details-box">
                    <div class="lock-details-row">
                        <span class="lbl">Authorized Owner:</span>
                        <span class="val" id="dispExpectedOwner">{{ substr($contracts['adminOwnerAddress'], 0, 8) . '...' . substr($contracts['adminOwnerAddress'], -6) }}</span>
                    </div>
                    <div class="lock-details-row">
                        <span class="lbl">Connected Wallet:</span>
                        <span class="val" id="dispConnectedWallet">Not Connected</span>
                    </div>
                    <div class="lock-details-row">
                        <span class="lbl">Authorization Status:</span>
                        <span class="val" id="dispAuthStatus" style="color: var(--red-neon);">Locked</span>
                    </div>
                </div>

                <button type="button" class="btn-web3 btn-web3-gold" style="width: 100%; justify-content: center; height: 46px;" onclick="connectWallet()">
                    <i class="fas fa-wallet"></i> Connect Contract Owner Wallet
                </button>
            </div>
        </div>

        <!-- ============================================================
             3. UNLOCKED ADMIN DASHBOARD (Visible Only to Verified Owner)
             ============================================================ -->
        <main id="adminMain" style="display: none;">

            <!-- Live Metrics Grid -->
            <div class="metrics-grid">
                <div class="metric-card gold">
                    <div class="metric-lbl">
                        <span>70% Treasury Vault</span>
                        <i class="fas fa-vault" style="color: var(--gold-primary);"></i>
                    </div>
                    <div class="metric-val" id="metricTreasuryUsdt">0.00 <small style="font-size: 13px;">USDT</small></div>
                    <div class="metric-sub">TreasuryClaimVault Reserves</div>
                </div>

                <div class="metric-card green">
                    <div class="metric-lbl">
                        <span>Splitter Total Volume</span>
                        <i class="fas fa-chart-line" style="color: var(--green-neon);"></i>
                    </div>
                    <div class="metric-val" id="metricSplitterTotal">0.00 <small style="font-size: 13px;">USDT</small></div>
                    <div class="metric-sub"><span id="metricInvestmentCount">0</span> Total Staking Actions</div>
                </div>

                <div class="metric-card cyan">
                    <div class="metric-lbl">
                        <span>CAI Reward Vault</span>
                        <i class="fas fa-coins" style="color: var(--cyan-neon);"></i>
                    </div>
                    <div class="metric-val" id="metricRewardCai">0.00 <small style="font-size: 13px;">CAI</small></div>
                    <div class="metric-sub">CAIRewardClaimVault Reserves</div>
                </div>

                <div class="metric-card">
                    <div class="metric-lbl">
                        <span>USDT Payout Vault</span>
                        <i class="fas fa-money-bill-transfer" style="color: var(--gold-primary);"></i>
                    </div>
                    <div class="metric-val" id="metricPayoutUsdt">0.00 <small style="font-size: 13px;">USDT</small></div>
                    <div class="metric-sub">USDTWithdrawalVault Reserves</div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <nav class="admin-nav-tabs">
                <button type="button" class="nav-tab-btn active" onclick="switchTab('tab-splitter')">
                    <i class="fas fa-arrows-split-up-and-left"></i> 70/30 Investment Splitter
                </button>
                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-treasury')">
                    <i class="fas fa-vault"></i> 70% Treasury Vault
                </button>
                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-cai')">
                    <i class="fas fa-coins"></i> CAI Token &amp; DEX Whitelist
                </button>
                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-claim-vaults')">
                    <i class="fas fa-key"></i> EIP-712 Claim &amp; Payout Vaults
                </button>
                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-events')">
                    <i class="fas fa-satellite-dish"></i> Live Event Explorer
                </button>
            </nav>

            <!-- TAB 1: CYERA INVESTMENT SPLITTER (70/30 Splitter) -->
            <div id="tab-splitter" class="tab-panel-section active">
                <div class="control-card">
                    <div class="control-card-header">
                        <div class="control-card-title">
                            <i class="fas fa-arrows-split-up-and-left"></i>
                            <div>
                                <h3>CyeraInvestmentSplitter Control</h3>
                                <p>Automated 70% Treasury Claim Vault &amp; 30% Liquidity Treasury routing on every user stake</p>
                            </div>
                        </div>
                        <span class="card-badge" id="badgeSplitterStatus">ACTIVE</span>
                    </div>

                    <div class="control-grid-2">
                        <!-- Left: Live State Matrix -->
                        <div>
                            <div class="info-matrix-box">
                                <div class="info-matrix-row">
                                    <span class="k">Splitter Contract:</span>
                                    <span class="v" id="dispSplitterAddr">{{ $contracts['investmentSplitterAddress'] }}</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">70% Treasury Vault:</span>
                                    <span class="v" id="dispSplitter70Vault" style="color: var(--gold-primary);">Reading...</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">30% Liquidity Wallet:</span>
                                    <span class="v" id="dispSplitter30Wallet" style="color: var(--cyan-neon);">Reading...</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Investment Limits:</span>
                                    <span class="v" id="dispSplitterLimits">$50 - $2,000 USDT</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Contract Owner:</span>
                                    <span class="v" id="dispSplitterOwner">Reading...</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Paused State:</span>
                                    <span class="v" id="dispSplitterPaused">No</span>
                                </div>
                            </div>

                            <!-- Emergency Pause/Unpause -->
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <button type="button" class="btn-web3 btn-web3-red" style="flex: 1; justify-content: center;" onclick="togglePause('splitter', true)">
                                    <i class="fas fa-pause"></i> Pause Splitter
                                </button>
                                <button type="button" class="btn-web3 btn-web3-green" style="flex: 1; justify-content: center;" onclick="togglePause('splitter', false)">
                                    <i class="fas fa-play"></i> Unpause Splitter
                                </button>
                            </div>
                        </div>

                        <!-- Right: Actions & Setters -->
                        <div>
                            <!-- Update 70% Vault -->
                            <form onsubmit="handleSetTreasuryVault(event)" class="control-form-group">
                                <label class="control-label">Update 70% Treasury Claim Vault Destination</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" class="control-input" id="inputNew70Vault" placeholder="0x..." required>
                                    <button type="submit" class="btn-web3 btn-web3-gold" style="white-space: nowrap;">
                                        <i class="fas fa-check"></i> Update
                                    </button>
                                </div>
                                <p class="control-help">Updates the on-chain destination for the 70% investment allocation.</p>
                            </form>

                            <!-- Update 30% Liquidity Wallet -->
                            <form onsubmit="handleSetLiquidityWallet(event)" class="control-form-group">
                                <label class="control-label">Update 30% Liquidity Treasury Wallet</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" class="control-input" id="inputNew30Wallet" placeholder="0x..." required>
                                    <button type="submit" class="btn-web3 btn-web3-gold" style="white-space: nowrap;">
                                        <i class="fas fa-check"></i> Update
                                    </button>
                                </div>
                                <p class="control-help">Updates the on-chain destination for the 30% liquidity allocation.</p>
                            </form>

                            <!-- Update Investment Limits -->
                            <form onsubmit="handleSetInvestmentLimits(event)" class="control-form-group">
                                <label class="control-label">Update Investment Limits (Min &amp; Max USDT)</label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px;">
                                    <input type="number" class="control-input" id="inputMinInv" placeholder="Min (e.g. 50)" required>
                                    <input type="number" class="control-input" id="inputMaxInv" placeholder="Max (e.g. 2000)" required>
                                    <button type="submit" class="btn-web3 btn-web3-gold">
                                        <i class="fas fa-sliders"></i> Apply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: TREASURY CLAIM VAULT (70% USDT) -->
            <div id="tab-treasury" class="tab-panel-section">
                <div class="control-card">
                    <div class="control-card-header">
                        <div class="control-card-title">
                            <i class="fas fa-vault"></i>
                            <div>
                                <h3>TreasuryClaimVault (70% USDT Reserve)</h3>
                                <p>Secures the 70% treasury fund. Only the verified Owner can release funds.</p>
                            </div>
                        </div>
                        <span class="card-badge">SECURE VAULT</span>
                    </div>

                    <div class="control-grid-2">
                        <!-- Left: Vault Status & Rescue -->
                        <div>
                            <div class="info-matrix-box">
                                <div class="info-matrix-row">
                                    <span class="k">Treasury Vault Address:</span>
                                    <span class="v">{{ $contracts['treasuryClaimVaultAddress'] }}</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Live Vault USDT Balance:</span>
                                    <span class="v" id="dispTreasuryBalance" style="color: var(--green-neon); font-size: 14px;">0.00 USDT</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Vault Owner:</span>
                                    <span class="v" id="dispTreasuryOwner">Reading...</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Vault Paused:</span>
                                    <span class="v" id="dispTreasuryPaused">No</span>
                                </div>
                            </div>

                            <!-- Rescue Accidentally Sent Non-Treasury Tokens -->
                            <form onsubmit="handleRescueTokens(event)" class="control-form-group" style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-dim); border-radius: 12px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--gold-primary); margin-bottom: 8px;">
                                    <i class="fas fa-hand-holding-dollar"></i> Rescue Non-Treasury Tokens
                                </div>
                                <div style="display: grid; gap: 8px; margin-bottom: 8px;">
                                    <input type="text" class="control-input" id="inputRescueTokenAddr" placeholder="Token Contract Address (0x...)" required>
                                    <input type="text" class="control-input" id="inputRescueDestAddr" placeholder="Recipient Address (0x...)" required>
                                    <input type="number" step="any" class="control-input" id="inputRescueAmount" placeholder="Amount" required>
                                </div>
                                <button type="submit" class="btn-web3 btn-web3-outline" style="width: 100%; justify-content: center;">
                                    <i class="fas fa-life-ring"></i> Execute Rescue
                                </button>
                                <p class="control-help" style="color: #F59E0B; margin-top: 6px;">Note: Treasury USDT cannot be extracted via rescue. It moves exclusively via authorized fund transfer.</p>
                            </form>
                        </div>

                        <!-- Right: Transfer / Release Funds -->
                        <div>
                            <form onsubmit="handleTransferTreasuryFunds(event)" class="control-form-group" style="background: rgba(255, 215, 0, 0.03); border: 1px solid var(--border-gold); border-radius: 14px; padding: 18px;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                    <i class="fas fa-paper-plane" style="color: var(--gold-primary);"></i>
                                    <span style="font-size: 13px; font-weight: 800; font-family: 'Outfit';">Authorize &amp; Release Treasury Funds</span>
                                </div>

                                <div class="control-form-group">
                                    <label class="control-label">Recipient Destination Address</label>
                                    <input type="text" class="control-input" id="inputTransferRecipient" value="{{ $contracts['adminOwnerAddress'] ?? '0x07Bd1494C669a69C89e4566436c3fC629Fd9045E' }}" placeholder="0x..." required>
                                </div>

                                <div class="control-form-group">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <label class="control-label" style="margin-bottom: 0;">USDT Amount (Max: <span id="dispMaxTransferable">0.00</span> USDT)</label>
                                        <button type="button" class="btn-web3 btn-web3-outline" style="padding: 2px 10px; font-size: 10px; height: 22px; border-color: var(--gold-primary); color: var(--gold-primary); font-weight: 800;" onclick="setMaxTransferAmount()">MAX</button>
                                    </div>
                                    <input type="number" step="any" min="0.0001" class="control-input" id="inputTransferAmount" placeholder="0.00" oninput="this.dataset.userEdited = 'true'" required>
                                </div>

                                <div class="control-form-group">
                                    <label class="control-label">Reason / Memo (Logged On-Chain)</label>
                                    <input type="text" class="control-input" id="inputTransferReason" value="Treasury Fund" placeholder="e.g. Treasury Fund" required>
                                </div>

                                <button type="submit" class="btn-web3 btn-web3-gold" style="width: 100%; justify-content: center; height: 42px;">
                                    <i class="fas fa-bolt"></i> Transfer Treasury Funds
                                </button>
                            </form>

                            <!-- Emergency Pause/Unpause -->
                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <button type="button" class="btn-web3 btn-web3-red" style="flex: 1; justify-content: center;" onclick="togglePause('treasury', true)">
                                    <i class="fas fa-pause"></i> Pause Treasury
                                </button>
                                <button type="button" class="btn-web3 btn-web3-green" style="flex: 1; justify-content: center;" onclick="togglePause('treasury', false)">
                                    <i class="fas fa-play"></i> Unpause Treasury
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CAI TOKEN & DEX BUY WHITELIST -->
            <div id="tab-cai" class="tab-panel-section">
                <div class="control-card">
                    <div class="control-card-header">
                        <div class="control-card-title">
                            <i class="fas fa-coins"></i>
                            <div>
                                <h3>CAIToken (BEP-20) &amp; DEX Protection</h3>
                                <p>Fixed supply 300,000 CAI, PancakeSwap pair permanent locking, and AMM Buy Whitelist management.</p>
                            </div>
                        </div>
                        <span class="card-badge">BEP-20 TOKEN</span>
                    </div>

                    <div class="control-grid-2">
                        <!-- Left: Whitelist Controls -->
                        <div>
                            <!-- Single Whitelist Form -->
                            <form onsubmit="handleSetWhitelist(event)" class="control-form-group" style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-dim); border-radius: 12px; padding: 16px;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--gold-primary); margin-bottom: 8px;">
                                    <i class="fas fa-user-check"></i> DEX Buy Whitelist (Single Wallet)
                                </div>
                                <div style="margin-bottom: 8px;">
                                    <input type="text" class="control-input" id="inputWhitelistAddr" placeholder="Wallet Address (0x...)" required>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn-web3 btn-web3-green" style="flex: 1; justify-content: center;" onclick="submitSingleWhitelist(true)">
                                        <i class="fas fa-check"></i> Whitelist
                                    </button>
                                    <button type="button" class="btn-web3 btn-web3-red" style="flex: 1; justify-content: center;" onclick="submitSingleWhitelist(false)">
                                        <i class="fas fa-ban"></i> Remove
                                    </button>
                                </div>
                            </form>

                            <!-- Batch Whitelist Form -->
                            <form onsubmit="handleBatchWhitelist(event)" class="control-form-group" style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-dim); border-radius: 12px; padding: 16px;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--green-neon); margin-bottom: 8px;">
                                    <i class="fas fa-users-viewfinder"></i> Batch Whitelist Upload (Multiple Wallets)
                                </div>
                                <textarea class="control-input" id="inputBatchWhitelist" rows="4" placeholder="Enter addresses separated by comma or new line...&#10;0x1111...&#10;0x2222..." style="resize: vertical; margin-bottom: 8px;" required></textarea>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn-web3 btn-web3-green" style="flex: 1; justify-content: center;" onclick="submitBatchWhitelist(true)">
                                        <i class="fas fa-plus"></i> Batch Enable
                                    </button>
                                    <button type="button" class="btn-web3 btn-web3-red" style="flex: 1; justify-content: center;" onclick="submitBatchWhitelist(false)">
                                        <i class="fas fa-minus"></i> Batch Remove
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right: PancakeSwap & AMM Pair Security -->
                        <div>
                            <div class="info-matrix-box">
                                <div class="info-matrix-row">
                                    <span class="k">CAI Token Address:</span>
                                    <span class="v">{{ $contracts['caiTokenAddress'] }}</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Total Fixed Supply:</span>
                                    <span class="v">300,000 CAI</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">PancakeSwap Pair:</span>
                                    <span class="v" id="dispPancakePair" style="color: var(--cyan-neon);">Reading...</span>
                                </div>
                                <div class="info-matrix-row">
                                    <span class="k">Pair Lock Status:</span>
                                    <span class="v" id="dispPancakePairLocked">Unlocked</span>
                                </div>
                            </div>

                            <!-- Set PancakeSwap Pair -->
                            <form onsubmit="handleSetPancakePair(event)" class="control-form-group">
                                <label class="control-label">Set Official PancakeSwap Pair</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" class="control-input" id="inputPancakePair" placeholder="0x..." required>
                                    <button type="submit" class="btn-web3 btn-web3-gold" id="btnSetPancakePair">
                                        <i class="fas fa-check"></i> Set
                                    </button>
                                </div>
                            </form>

                            <!-- Lock PancakeSwap Pair -->
                            <div style="margin-top: 10px; background: rgba(245, 166, 35, 0.05); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 12px; padding: 14px;">
                                <div style="font-size: 11px; font-weight: 700; color: var(--gold-primary); margin-bottom: 4px;">
                                    <i class="fas fa-lock"></i> Permanent Pair Lock
                                </div>
                                <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px;">Permanently freezes the official PancakeSwap pair address so it can never be changed.</p>
                                <button type="button" class="btn-web3 btn-web3-gold" style="width: 100%; justify-content: center;" onclick="handleLockPancakePair()" id="btnLockPair">
                                    <i class="fas fa-lock"></i> Permanently Lock Pair
                                </button>
                            </div>

                            <!-- Secondary AMM Pair Protection -->
                            <form onsubmit="handleSetAMMPair(event)" class="control-form-group" style="margin-top: 12px;">
                                <label class="control-label">Register Secondary AMM Pair (V3, ApeSwap, Biswap)</label>
                                <div style="display: grid; grid-template-columns: 1fr auto auto; gap: 8px;">
                                    <input type="text" class="control-input" id="inputAMMPairAddr" placeholder="AMM Pair (0x...)" required>
                                    <button type="button" class="btn-web3 btn-web3-green" onclick="submitAMMPair(true)">Register</button>
                                    <button type="button" class="btn-web3 btn-web3-red" onclick="submitAMMPair(false)">Unregister</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: EIP-712 REWARD CLAIM & WITHDRAWAL VAULTS -->
            <div id="tab-claim-vaults" class="tab-panel-section">
                <div class="control-grid-2">
                    <!-- Vault 1: CAIRewardClaimVault -->
                    <div class="control-card">
                        <div class="control-card-header">
                            <div class="control-card-title">
                                <i class="fas fa-bolt" style="color: var(--gold-primary);"></i>
                                <div>
                                    <h3>CAIRewardClaimVault</h3>
                                    <p>EIP-712 CAI Staking ROI Claims</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-matrix-box">
                            <div class="info-matrix-row">
                                <span class="k">Contract Address:</span>
                                <span class="v">{{ substr($contracts['caiRewardClaimVaultAddress'], 0, 8) . '...' . substr($contracts['caiRewardClaimVaultAddress'], -6) }}</span>
                            </div>
                            <div class="info-matrix-row">
                                <span class="k">Vault CAI Balance:</span>
                                <span class="v" id="dispRewardVaultBal" style="color: var(--cyan-neon);">0.00 CAI</span>
                            </div>
                            <div class="info-matrix-row">
                                <span class="k">Hot Backend Signer:</span>
                                <span class="v" id="dispRewardSigner" style="color: var(--gold-primary);">Reading...</span>
                            </div>
                        </div>

                        <!-- Update Signer -->
                        <form onsubmit="handleSetBackendSigner('reward', event)" class="control-form-group">
                            <label class="control-label">Update Backend Signer</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" class="control-input" id="inputNewRewardSigner" placeholder="New Hot Signer (0x...)" required>
                                <button type="submit" class="btn-web3 btn-web3-gold">Set</button>
                            </div>
                        </form>

                        <!-- Emergency Withdraw CAI -->
                        <form onsubmit="handleEmergencyWithdraw('reward', event)" class="control-form-group">
                            <label class="control-label">Emergency Recover CAI</label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px;">
                                <input type="text" class="control-input" id="inputEmDestReward" placeholder="To Address" required>
                                <input type="number" step="any" class="control-input" id="inputEmAmtReward" placeholder="Amount CAI" required>
                                <button type="submit" class="btn-web3 btn-web3-outline">Withdraw</button>
                            </div>
                        </form>
                    </div>

                    <!-- Vault 2: USDTWithdrawalVault -->
                    <div class="control-card">
                        <div class="control-card-header">
                            <div class="control-card-title">
                                <i class="fas fa-hand-holding-dollar" style="color: var(--green-neon);"></i>
                                <div>
                                    <h3>USDTWithdrawalVault</h3>
                                    <p>EIP-712 USDT Working Income Payouts</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-matrix-box">
                            <div class="info-matrix-row">
                                <span class="k">Contract Address:</span>
                                <span class="v">{{ substr($contracts['usdtWithdrawalVaultAddress'], 0, 8) . '...' . substr($contracts['usdtWithdrawalVaultAddress'], -6) }}</span>
                            </div>
                            <div class="info-matrix-row">
                                <span class="k">Vault USDT Balance:</span>
                                <span class="v" id="dispWithdrawalVaultBal" style="color: var(--green-neon);">0.00 USDT</span>
                            </div>
                            <div class="info-matrix-row">
                                <span class="k">Hot Backend Signer:</span>
                                <span class="v" id="dispWithdrawalSigner" style="color: var(--gold-primary);">Reading...</span>
                            </div>
                        </div>

                        <!-- Update Signer -->
                        <form onsubmit="handleSetBackendSigner('withdrawal', event)" class="control-form-group">
                            <label class="control-label">Update Backend Signer</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" class="control-input" id="inputNewWithdrawalSigner" placeholder="New Hot Signer (0x...)" required>
                                <button type="submit" class="btn-web3 btn-web3-gold">Set</button>
                            </div>
                        </form>

                        <!-- Emergency Withdraw USDT -->
                        <form onsubmit="handleEmergencyWithdraw('withdrawal', event)" class="control-form-group">
                            <label class="control-label">Emergency Recover USDT</label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px;">
                                <input type="text" class="control-input" id="inputEmDestWithdrawal" placeholder="To Address" required>
                                <input type="number" step="any" class="control-input" id="inputEmAmtWithdrawal" placeholder="Amount USDT" required>
                                <button type="submit" class="btn-web3 btn-web3-outline">Withdraw</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TAB 5: LIVE EVENT EXPLORER -->
            <div id="tab-events" class="tab-panel-section">
                <div class="control-card">
                    <div class="control-card-header">
                        <div class="control-card-title">
                            <i class="fas fa-satellite-dish" style="color: var(--cyan-neon);"></i>
                            <div>
                                <h3>Real-Time Protocol Event Stream</h3>
                                <p>Live listener querying on-chain events from Cyera smart contracts</p>
                            </div>
                        </div>
                        <button type="button" class="btn-web3 btn-web3-outline" onclick="fetchRecentEvents()">
                            <i class="fas fa-rotate-right"></i> Refresh Logs
                        </button>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="event-table">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Contract</th>
                                    <th>Details / Parameters</th>
                                    <th>Block</th>
                                    <th>Transaction</th>
                                </tr>
                            </thead>
                            <tbody id="eventLogsBody">
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 24px; color: var(--text-dim);">
                                        <i class="fas fa-circle-notch fa-spin" style="margin-right: 6px;"></i> Subscribing to live BSC event stream...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- Toast Notifications Area -->
    <div class="toast-container" id="toastBox"></div>

    <!-- ============================================================
         WEB3 & ETHERS.JS CLIENT SCRIPT
         ============================================================ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
    <script>
        // Configuration from backend
        const CONFIG = {
            ADMIN_OWNER: "{{ strtolower($contracts['adminOwnerAddress']) }}",
            CHAIN_ID: {{ $contracts['chainId'] }},
            CONTRACTS: {
                USDT: "{{ $contracts['usdtTokenAddress'] }}",
                CAI: "{{ $contracts['caiTokenAddress'] }}",
                TREASURY_VAULT: "{{ $contracts['treasuryClaimVaultAddress'] }}",
                SPLITTER: "{{ $contracts['investmentSplitterAddress'] }}",
                REWARD_VAULT: "{{ $contracts['caiRewardClaimVaultAddress'] }}",
                WITHDRAWAL_VAULT: "{{ $contracts['usdtWithdrawalVaultAddress'] }}"
            }
        };

        // ABIs
        const ERC20_ABI = [
            "function balanceOf(address account) view returns (uint256)",
            "function decimals() view returns (uint8)"
        ];

        const SPLITTER_ABI = [
            "function treasuryClaimVault() view returns (address)",
            "function liquidityTreasuryWallet() view returns (address)",
            "function minInvestment() view returns (uint256)",
            "function maxInvestment() view returns (uint256)",
            "function totalInvested() view returns (uint256)",
            "function investmentCount() view returns (uint256)",
            "function owner() view returns (address)",
            "function paused() view returns (bool)",
            "function setTreasuryClaimVault(address _vault) external",
            "function setLiquidityTreasuryWallet(address _wallet) external",
            "function setInvestmentLimits(uint256 _min, uint256 _max) external",
            "function pause() external",
            "function unpause() external",
            "event Invested(address indexed user, uint256 amountUSDT, uint256 timestamp, uint256 investmentId)"
        ];

        const TREASURY_VAULT_ABI = [
            "function getVaultBalance() view returns (uint256)",
            "function owner() view returns (address)",
            "function paused() view returns (bool)",
            "function transferFunds(address recipient, uint256 amount, string reason) external",
            "function rescueTokens(address tokenAddress, address to, uint256 amount) external",
            "function pause() external",
            "function unpause() external",
            "event FundsClaimed(address indexed recipient, uint256 amount, string reason, uint256 timestamp)"
        ];

        const CAI_TOKEN_ABI = [
            "function pancakePair() view returns (address)",
            "function pancakePairLocked() view returns (bool)",
            "function isWhitelisted(address account) view returns (bool)",
            "function isAMMPair(address pair) view returns (bool)",
            "function owner() view returns (address)",
            "function paused() view returns (bool)",
            "function setWhitelist(address account, bool status) external",
            "function setBatchWhitelist(address[] calldata accounts, bool status) external",
            "function setPancakePair(address _pair) external",
            "function lockPancakePair() external",
            "function setAMMPair(address _pair, bool _isPair) external",
            "function pause() external",
            "function unpause() external",
            "event WhitelistUpdated(address indexed account, bool status)"
        ];

        const REWARD_VAULT_ABI = [
            "function backendSigner() view returns (address)",
            "function owner() view returns (address)",
            "function paused() view returns (bool)",
            "function setBackendSigner(address _newSigner) external",
            "function emergencyWithdrawCAI(address to, uint256 amount) external",
            "function pause() external",
            "function unpause() external",
            "event RewardClaimed(address indexed user, uint256 caiAmount, uint256 indexed nonce, uint256 timestamp)"
        ];

        const WITHDRAWAL_VAULT_ABI = [
            "function backendSigner() view returns (address)",
            "function owner() view returns (address)",
            "function paused() view returns (bool)",
            "function setBackendSigner(address _newSigner) external",
            "function emergencyWithdraw(address to, uint256 amount) external",
            "function pause() external",
            "function unpause() external",
            "event WithdrawalPaid(address indexed recipient, uint256 amount, uint256 indexed withdrawalId, uint256 timestamp)"
        ];

        // Web3 State
        let provider = null;
        let signer = null;
        let currentAccount = null;
        let isAuthorized = false;

        // Toast Helper
        function showToast(msg, type = 'info') {
            const box = document.getElementById('toastBox');
            const el = document.createElement('div');
            el.className = `toast-msg ${type}`;
            el.innerHTML = `<i class="fas ${type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-triangle-exclamation' : 'fa-circle-info')}"></i> <span>${msg}</span>`;
            box.appendChild(el);
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                setTimeout(() => el.remove(), 300);
            }, 4500);
        }

        // Tab Switcher
        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel-section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-tab-btn').forEach(el => el.classList.remove('active'));
            
            const target = document.getElementById(tabId);
            if (target) target.classList.add('active');

            event.currentTarget.classList.add('active');
        }

        // Connect Wallet
        async function connectWallet() {
            if (!window.ethereum) {
                showToast("No Web3 wallet detected! Please install MetaMask or TrustWallet.", "error");
                return;
            }

            try {
                provider = new ethers.BrowserProvider(window.ethereum);
                const accounts = await provider.send("eth_requestAccounts", []);
                if (accounts.length === 0) {
                    showToast("No accounts authorized.", "error");
                    return;
                }

                signer = await provider.getSigner();
                currentAccount = accounts[0].toLowerCase();
                
                await verifyAccess();
            } catch (err) {
                console.error("Wallet connect error:", err);
                showToast(err.message || "Failed to connect wallet", "error");
            }
        }

        // Disconnect Wallet
        function disconnectWallet() {
            signer = null;
            currentAccount = null;
            isAuthorized = false;
            
            document.getElementById('lockBarrier').style.display = 'flex';
            document.getElementById('adminMain').style.display = 'none';
            document.getElementById('btnConnectWallet').style.display = 'inline-flex';
            document.getElementById('btnDisconnectWallet').style.display = 'none';
            document.getElementById('btnConnectText').innerText = 'Connect Owner Wallet';
            document.getElementById('dispConnectedWallet').innerText = 'Not Connected';
            document.getElementById('dispAuthStatus').innerText = 'Locked';
            document.getElementById('dispAuthStatus').style.color = 'var(--red-neon)';
            document.getElementById('networkDot').className = 'status-dot';
            
            showToast("Wallet disconnected. Dashboard locked.", "info");
        }

        // Verify On-Chain Owner
        async function verifyAccess() {
            if (!currentAccount) return;

            document.getElementById('dispConnectedWallet').innerText = currentAccount.substring(0, 8) + '...' + currentAccount.substring(currentAccount.length - 6);
            
            // Check Network Chain ID
            const network = await provider.getNetwork();
            const chainId = Number(network.chainId);
            document.getElementById('networkText').innerText = `BSC (${chainId === 56 ? 'Mainnet' : 'Chain ' + chainId})`;

            // Check against configured admin or on-chain owner
            let isContractOwner = (currentAccount === CONFIG.ADMIN_OWNER);

            if (!isContractOwner) {
                try {
                    const splitter = new ethers.Contract(CONFIG.CONTRACTS.SPLITTER, SPLITTER_ABI, provider);
                    const onChainOwner = (await splitter.owner()).toLowerCase();
                    if (currentAccount === onChainOwner) {
                        isContractOwner = true;
                    }
                } catch (e) {
                    console.warn("Could not query splitter owner on-chain:", e);
                }
            }

            if (isContractOwner) {
                isAuthorized = true;
                document.getElementById('lockBarrier').style.display = 'none';
                document.getElementById('adminMain').style.display = 'block';
                document.getElementById('btnConnectWallet').style.display = 'none';
                document.getElementById('btnDisconnectWallet').style.display = 'inline-flex';
                document.getElementById('dispAuthStatus').innerText = 'Authorized Owner';
                document.getElementById('dispAuthStatus').style.color = 'var(--green-neon)';
                document.getElementById('networkDot').className = 'status-dot active';

                showToast("Contract Owner verified! Access granted.", "success");
                loadDashboardData();
            } else {
                isAuthorized = false;
                document.getElementById('lockBarrier').style.display = 'flex';
                document.getElementById('adminMain').style.display = 'none';
                document.getElementById('lockTitle').innerText = "Access Denied: Not Contract Owner";
                document.getElementById('lockDesc').innerText = "The connected wallet is not the authorized owner of the Cyera smart contracts suite. Please switch to the Owner wallet in MetaMask.";
                document.getElementById('lockIconBox').className = "lock-icon-box unauthorized";
                document.getElementById('lockIcon').className = "fas fa-ban";
                document.getElementById('dispAuthStatus').innerText = 'Unauthorized';
                document.getElementById('dispAuthStatus').style.color = 'var(--red-neon)';
                
                showToast("Connected wallet is not the Contract Owner!", "error");
            }
        }

        // Listen for Wallet Account / Chain Switch (Auto-lock guard)
        if (window.ethereum) {
            window.ethereum.on('accountsChanged', (accounts) => {
                if (accounts.length === 0) {
                    disconnectWallet();
                } else {
                    currentAccount = accounts[0].toLowerCase();
                    verifyAccess();
                }
            });

            window.ethereum.on('chainChanged', () => {
                window.location.reload();
            });
        }

        // Load all live on-chain balances & parameters
        async function loadDashboardData() {
            if (!provider) return;

            try {
                // Contracts
                const usdt = new ethers.Contract(CONFIG.CONTRACTS.USDT, ERC20_ABI, provider);
                const cai = new ethers.Contract(CONFIG.CONTRACTS.CAI, CAI_TOKEN_ABI, provider);
                const splitter = new ethers.Contract(CONFIG.CONTRACTS.SPLITTER, SPLITTER_ABI, provider);
                const treasury = new ethers.Contract(CONFIG.CONTRACTS.TREASURY_VAULT, TREASURY_VAULT_ABI, provider);
                const rewardVault = new ethers.Contract(CONFIG.CONTRACTS.REWARD_VAULT, REWARD_VAULT_ABI, provider);
                const withdrawalVault = new ethers.Contract(CONFIG.CONTRACTS.WITHDRAWAL_VAULT, WITHDRAWAL_VAULT_ABI, provider);

                // 1. Treasury Balances
                const [treasuryBalRaw, rewardBalRaw, withdrawalBalRaw] = await Promise.all([
                    usdt.balanceOf(CONFIG.CONTRACTS.TREASURY_VAULT).catch(() => 0n),
                    usdt.balanceOf(CONFIG.CONTRACTS.REWARD_VAULT).catch(() => 0n), // Or CAI token balance
                    usdt.balanceOf(CONFIG.CONTRACTS.WITHDRAWAL_VAULT).catch(() => 0n)
                ]);

                const caiVaultBal = await (new ethers.Contract(CONFIG.CONTRACTS.CAI, ERC20_ABI, provider))
                    .balanceOf(CONFIG.CONTRACTS.REWARD_VAULT).catch(() => 0n);

                const treasuryBal = ethers.formatUnits(treasuryBalRaw, 18);
                const rewardCaiBal = ethers.formatUnits(caiVaultBal, 18);
                const withdrawalBal = ethers.formatUnits(withdrawalBalRaw, 18);

                const maxTransferable = parseFloat(treasuryBal) > 0 ? parseFloat(treasuryBal).toFixed(2) : '0.00';
                document.getElementById('metricTreasuryUsdt').innerHTML = `${parseFloat(treasuryBal).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <small style="font-size: 13px;">USDT</small>`;
                document.getElementById('dispTreasuryBalance').innerText = `${parseFloat(treasuryBal).toFixed(2)} USDT`;
                document.getElementById('dispMaxTransferable').innerText = maxTransferable;

                const transferAmtInput = document.getElementById('inputTransferAmount');
                if (transferAmtInput && (!transferAmtInput.value || transferAmtInput.dataset.userEdited !== 'true')) {
                    transferAmtInput.value = maxTransferable;
                }

                document.getElementById('metricRewardCai').innerHTML = `${parseFloat(rewardCaiBal).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <small style="font-size: 13px;">CAI</small>`;
                document.getElementById('dispRewardVaultBal').innerText = `${parseFloat(rewardCaiBal).toFixed(4)} CAI`;

                document.getElementById('metricPayoutUsdt').innerHTML = `${parseFloat(withdrawalBal).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <small style="font-size: 13px;">USDT</small>`;
                document.getElementById('dispWithdrawalVaultBal').innerText = `${parseFloat(withdrawalBal).toFixed(2)} USDT`;

                // 2. Splitter Stats
                const [splitter70, splitter30, minInvRaw, maxInvRaw, totalInvRaw, invCount] = await Promise.all([
                    splitter.treasuryClaimVault().catch(() => 'N/A'),
                    splitter.liquidityTreasuryWallet().catch(() => 'N/A'),
                    splitter.minInvestment().catch(() => 0n),
                    splitter.maxInvestment().catch(() => 0n),
                    splitter.totalInvested().catch(() => 0n),
                    splitter.investmentCount().catch(() => 0n)
                ]);

                document.getElementById('dispSplitter70Vault').innerText = splitter70;
                document.getElementById('dispSplitter30Wallet').innerText = splitter30;
                
                const minInv = ethers.formatUnits(minInvRaw, 18);
                const maxInv = ethers.formatUnits(maxInvRaw, 18);
                document.getElementById('dispSplitterLimits').innerText = `$${parseFloat(minInv).toFixed(0)} - $${parseFloat(maxInv).toFixed(0)} USDT`;

                const totalInv = ethers.formatUnits(totalInvRaw, 18);
                document.getElementById('metricSplitterTotal').innerHTML = `${parseFloat(totalInv).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} <small style="font-size: 13px;">USDT</small>`;
                document.getElementById('metricInvestmentCount').innerText = invCount.toString();

                // 3. CAI Token Stats
                const [pairAddr, pairLocked] = await Promise.all([
                    cai.pancakePair().catch(() => '0x0000000000000000000000000000000000000000'),
                    cai.pancakePairLocked().catch(() => false)
                ]);

                document.getElementById('dispPancakePair').innerText = (pairAddr && pairAddr !== '0x0000000000000000000000000000000000000000') ? pairAddr : 'Not Set';
                document.getElementById('dispPancakePairLocked').innerText = pairLocked ? 'Permanently Locked 🔒' : 'Unlocked';
                if (pairLocked) {
                    document.getElementById('btnLockPair').disabled = true;
                    document.getElementById('btnLockPair').innerText = 'Pair Permanently Locked';
                    document.getElementById('btnSetPancakePair').disabled = true;
                }

                // 4. Signers
                const [rewardSigner, withdrawalSigner] = await Promise.all([
                    rewardVault.backendSigner().catch(() => 'N/A'),
                    withdrawalVault.backendSigner().catch(() => 'N/A')
                ]);

                document.getElementById('dispRewardSigner').innerText = rewardSigner;
                document.getElementById('dispWithdrawalSigner').innerText = withdrawalSigner;

                // Load event logs
                fetchRecentEvents();

            } catch (err) {
                console.error("Dashboard data load error:", err);
            }
        }

        // ============================================================
        // SMART CONTRACT WRITE ACTIONS
        // ============================================================

        // 1. Splitter: Update 70% Vault
        async function handleSetTreasuryVault(e) {
            e.preventDefault();
            if (!signer) return showToast("Connect Owner wallet first", "error");
            
            const addr = document.getElementById('inputNew70Vault').value.trim();
            if (!ethers.isAddress(addr)) return showToast("Invalid wallet address", "error");

            try {
                showToast("Submitting setTreasuryClaimVault...", "info");
                const splitter = new ethers.Contract(CONFIG.CONTRACTS.SPLITTER, SPLITTER_ABI, signer);
                const tx = await splitter.setTreasuryClaimVault(addr);
                showToast("Transaction submitted: " + tx.hash.substring(0, 10) + "...", "info");
                await tx.wait();
                showToast("70% Treasury Claim Vault destination updated!", "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Transaction failed", "error");
            }
        }

        // 2. Splitter: Update 30% Liquidity Wallet
        async function handleSetLiquidityWallet(e) {
            e.preventDefault();
            if (!signer) return showToast("Connect Owner wallet first", "error");

            const addr = document.getElementById('inputNew30Wallet').value.trim();
            if (!ethers.isAddress(addr)) return showToast("Invalid wallet address", "error");

            try {
                showToast("Submitting setLiquidityTreasuryWallet...", "info");
                const splitter = new ethers.Contract(CONFIG.CONTRACTS.SPLITTER, SPLITTER_ABI, signer);
                const tx = await splitter.setLiquidityTreasuryWallet(addr);
                showToast("Transaction submitted: " + tx.hash.substring(0, 10) + "...", "info");
                await tx.wait();
                showToast("30% Liquidity Treasury Wallet updated!", "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Transaction failed", "error");
            }
        }

        // 3. Splitter: Update Investment Limits
        async function handleSetInvestmentLimits(e) {
            e.preventDefault();
            if (!signer) return showToast("Connect Owner wallet first", "error");

            const min = parseFloat(document.getElementById('inputMinInv').value);
            const max = parseFloat(document.getElementById('inputMaxInv').value);

            if (min <= 0 || max < min) return showToast("Invalid limit parameters", "error");

            try {
                showToast("Submitting setInvestmentLimits...", "info");
                const splitter = new ethers.Contract(CONFIG.CONTRACTS.SPLITTER, SPLITTER_ABI, signer);
                const minRaw = ethers.parseUnits(min.toString(), 18);
                const maxRaw = ethers.parseUnits(max.toString(), 18);
                const tx = await splitter.setInvestmentLimits(minRaw, maxRaw);
                await tx.wait();
                showToast("Investment limits updated successfully!", "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Transaction failed", "error");
            }
        }

        // Helper: Set MAX Treasury Amount
        function setMaxTransferAmount() {
            const maxVal = document.getElementById('dispMaxTransferable').innerText;
            const input = document.getElementById('inputTransferAmount');
            if (input) {
                input.value = parseFloat(maxVal) > 0 ? maxVal : '0.00';
                input.dataset.userEdited = 'true';
            }
        }

        // 4. Treasury: Transfer / Claim Funds
        async function handleTransferTreasuryFunds(e) {
            e.preventDefault();
            if (!signer) return showToast("Connect Owner wallet first", "error");

            const recipient = document.getElementById('inputTransferRecipient').value.trim();
            const amount = parseFloat(document.getElementById('inputTransferAmount').value);
            const reason = document.getElementById('inputTransferReason').value.trim();

            if (!ethers.isAddress(recipient)) return showToast("Invalid recipient address", "error");
            if (amount <= 0) return showToast("Amount must be greater than zero", "error");

            try {
                showToast("Authorizing Treasury transfer on-chain...", "info");
                const treasury = new ethers.Contract(CONFIG.CONTRACTS.TREASURY_VAULT, TREASURY_VAULT_ABI, signer);
                const amountRaw = ethers.parseUnits(amount.toString(), 18);
                const tx = await treasury.transferFunds(recipient, amountRaw, reason);
                showToast("Transaction broadcast: " + tx.hash.substring(0, 10) + "...", "info");
                await tx.wait();
                showToast(`Transferred ${amount} USDT to recipient!`, "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Transfer failed", "error");
            }
        }

        // 5. CAI Token: Single Whitelist Toggle
        async function submitSingleWhitelist(status) {
            if (!signer) return showToast("Connect Owner wallet first", "error");
            const addr = document.getElementById('inputWhitelistAddr').value.trim();
            if (!ethers.isAddress(addr)) return showToast("Invalid wallet address", "error");

            try {
                showToast(`Setting whitelist status = ${status}...`, "info");
                const cai = new ethers.Contract(CONFIG.CONTRACTS.CAI, CAI_TOKEN_ABI, signer);
                const tx = await cai.setWhitelist(addr, status);
                await tx.wait();
                showToast(`Address ${status ? 'whitelisted' : 'removed'} for DEX buys!`, "success");
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Whitelist update failed", "error");
            }
        }

        // 6. CAI Token: Batch Whitelist
        async function submitBatchWhitelist(status) {
            if (!signer) return showToast("Connect Owner wallet first", "error");
            const raw = document.getElementById('inputBatchWhitelist').value;
            const lines = raw.split(/[\n,]/).map(s => s.trim()).filter(s => s.length > 0);

            const validAddrs = [];
            for (let l of lines) {
                if (ethers.isAddress(l)) validAddrs.push(l);
            }

            if (validAddrs.length === 0) return showToast("No valid addresses entered", "error");

            try {
                showToast(`Batch updating ${validAddrs.length} addresses...`, "info");
                const cai = new ethers.Contract(CONFIG.CONTRACTS.CAI, CAI_TOKEN_ABI, signer);
                const tx = await cai.setBatchWhitelist(validAddrs, status);
                await tx.wait();
                showToast(`Batch whitelist (${validAddrs.length} wallets) updated!`, "success");
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Batch whitelist failed", "error");
            }
        }

        // 7. CAI Token: Set PancakeSwap Pair
        async function handleSetPancakePair(e) {
            e.preventDefault();
            if (!signer) return showToast("Connect Owner wallet first", "error");
            const pair = document.getElementById('inputPancakePair').value.trim();
            if (!ethers.isAddress(pair)) return showToast("Invalid pair address", "error");

            try {
                showToast("Setting PancakeSwap pair...", "info");
                const cai = new ethers.Contract(CONFIG.CONTRACTS.CAI, CAI_TOKEN_ABI, signer);
                const tx = await cai.setPancakePair(pair);
                await tx.wait();
                showToast("PancakeSwap pair address set!", "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Failed to set pair", "error");
            }
        }

        // 8. CAI Token: Lock PancakeSwap Pair
        async function handleLockPancakePair() {
            if (!signer) return showToast("Connect Owner wallet first", "error");
            if (!confirm("⚠️ CAUTION: Are you sure you want to permanently lock the official PancakeSwap pair address? This action CANNOT be undone!")) return;

            try {
                showToast("Permanently locking pair...", "info");
                const cai = new ethers.Contract(CONFIG.CONTRACTS.CAI, CAI_TOKEN_ABI, signer);
                const tx = await cai.lockPancakePair();
                await tx.wait();
                showToast("PancakeSwap pair is now permanently locked! 🔒", "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Locking pair failed", "error");
            }
        }

        // 9. CAI Token: Set AMM Pair
        async function submitAMMPair(isPair) {
            if (!signer) return showToast("Connect Owner wallet first", "error");
            const pair = document.getElementById('inputAMMPairAddr').value.trim();
            if (!ethers.isAddress(pair)) return showToast("Invalid pair address", "error");

            try {
                showToast(`Updating AMM pair status...`, "info");
                const cai = new ethers.Contract(CONFIG.CONTRACTS.CAI, CAI_TOKEN_ABI, signer);
                const tx = await cai.setAMMPair(pair, isPair);
                await tx.wait();
                showToast(`AMM pair status updated to ${isPair}!`, "success");
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Failed to update AMM pair", "error");
            }
        }

        // 10. Update Backend Signer (Reward / Withdrawal Vaults)
        async function handleSetBackendSigner(vaultType, e) {
            e.preventDefault();
            if (!signer) return showToast("Connect Owner wallet first", "error");

            const isReward = (vaultType === 'reward');
            const inputId = isReward ? 'inputNewRewardSigner' : 'inputNewWithdrawalSigner';
            const newSigner = document.getElementById(inputId).value.trim();

            if (!ethers.isAddress(newSigner)) return showToast("Invalid signer address", "error");

            try {
                showToast(`Updating ${vaultType} backend signer...`, "info");
                const targetContractAddr = isReward ? CONFIG.CONTRACTS.REWARD_VAULT : CONFIG.CONTRACTS.WITHDRAWAL_VAULT;
                const targetAbi = isReward ? REWARD_VAULT_ABI : WITHDRAWAL_VAULT_ABI;
                const vault = new ethers.Contract(targetContractAddr, targetAbi, signer);
                const tx = await vault.setBackendSigner(newSigner);
                await tx.wait();
                showToast("Backend Signer key updated!", "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Failed to set signer", "error");
            }
        }

        // 11. Emergency Pause / Unpause
        async function togglePause(contractKey, doPause) {
            if (!signer) return showToast("Connect Owner wallet first", "error");

            let targetAddr = null;
            let targetAbi = null;

            if (contractKey === 'splitter') {
                targetAddr = CONFIG.CONTRACTS.SPLITTER;
                targetAbi = SPLITTER_ABI;
            } else if (contractKey === 'treasury') {
                targetAddr = CONFIG.CONTRACTS.TREASURY_VAULT;
                targetAbi = TREASURY_VAULT_ABI;
            }

            try {
                showToast(`Submitting ${doPause ? 'pause' : 'unpause'} transaction...`, "info");
                const c = new ethers.Contract(targetAddr, targetAbi, signer);
                const tx = doPause ? await c.pause() : await c.unpause();
                await tx.wait();
                showToast(`Contract ${doPause ? 'paused' : 'unpaused'} successfully!`, "success");
                loadDashboardData();
            } catch (err) {
                console.error(err);
                showToast(err.reason || err.message || "Pause action failed", "error");
            }
        }

        // 12. Fetch Recent Events
        async function fetchRecentEvents() {
            const tbody = document.getElementById('eventLogsBody');
            if (!provider) return;

            try {
                const currentBlock = await provider.getBlockNumber();
                const fromBlock = Math.max(0, currentBlock - 5000); // Last ~5000 blocks

                const splitter = new ethers.Contract(CONFIG.CONTRACTS.SPLITTER, SPLITTER_ABI, provider);
                const treasury = new ethers.Contract(CONFIG.CONTRACTS.TREASURY_VAULT, TREASURY_VAULT_ABI, provider);

                const [investLogs, claimLogs] = await Promise.all([
                    splitter.queryFilter(splitter.filters.Invested(), fromBlock, currentBlock).catch(() => []),
                    treasury.queryFilter(treasury.filters.FundsClaimed(), fromBlock, currentBlock).catch(() => [])
                ]);

                const allLogs = [];
                investLogs.forEach(l => {
                    allLogs.push({
                        type: 'INVEST',
                        badge: 'invest',
                        contract: 'Splitter',
                        desc: `User: ${l.args[0].substring(0,6)}... | Amount: $${ethers.formatUnits(l.args[1], 18)} USDT | ID: #${l.args[3]}`,
                        block: l.blockNumber,
                        txHash: l.transactionHash
                    });
                });

                claimLogs.forEach(l => {
                    allLogs.push({
                        type: 'FUNDS CLAIMED',
                        badge: 'claim',
                        contract: 'TreasuryVault',
                        desc: `To: ${l.args[0].substring(0,6)}... | Amount: $${ethers.formatUnits(l.args[1], 18)} USDT | Reason: "${l.args[2]}"`,
                        block: l.blockNumber,
                        txHash: l.transactionHash
                    });
                });

                allLogs.sort((a, b) => b.block - a.block);

                if (allLogs.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding: 20px; color: var(--text-dim);">No recent on-chain events in the last ${currentBlock - fromBlock} blocks.</td></tr>`;
                    return;
                }

                tbody.innerHTML = allLogs.map(item => `
                    <tr>
                        <td><span class="event-badge ${item.badge}">${item.type}</span></td>
                        <td>${item.contract}</td>
                        <td style="color: var(--text-primary); font-size: 11px;">${item.desc}</td>
                        <td>#${item.block}</td>
                        <td>
                            <a href="https://bscscan.com/tx/${item.txHash}" target="_blank" style="color: var(--gold-primary); text-decoration: none;">
                                ${item.txHash.substring(0, 10)}... <i class="fas fa-arrow-up-right-from-square" style="font-size: 9px;"></i>
                            </a>
                        </td>
                    </tr>
                `).join('');

            } catch (err) {
                console.error("Events fetch error:", err);
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color: var(--red-neon); padding: 14px;">Failed to query event logs: ${err.message}</td></tr>`;
            }
        }

        // Auto-check on page load if wallet already injected & connected
        document.addEventListener('DOMContentLoaded', () => {
            if (window.ethereum && window.ethereum.selectedAddress) {
                connectWallet();
            }
        });
    </script>
</body>
</html>
