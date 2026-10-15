<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lucky Draw Deployer Admin') | Cyera AI</title>
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Ethers.js v6 & SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-base: #050608;
            --bg-surface: #090C12;
            --bg-card: #0D111A;
            --bg-card-hover: #121824;
            --border-dim: rgba(255, 255, 255, 0.07);
            --border-gold: rgba(245, 166, 35, 0.35);
            --gold-primary: #F5A623;
            --gold-light: #FFD700;
            --gold-glow: rgba(245, 166, 35, 0.15);
            --green-live: #00E676;
            --green-glow: rgba(0, 230, 118, 0.2);
            --red-alert: #FF3366;
            --text-main: #FFFFFF;
            --text-sub: #94A3B8;
            --text-muted: #64748B;
            --sidebar-w: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background: var(--bg-base);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
        }

        /* Ambient Cyber Background */
        .ambient-glow {
            position: fixed;
            top: -200px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(245, 166, 35, 0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--bg-surface);
            border-right: 1px solid var(--border-dim);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-dim);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #F5A623, #B87708);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 18px;
            font-weight: 900;
            box-shadow: 0 4px 16px rgba(245, 166, 35, 0.3);
        }

        .brand-text h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            background: linear-gradient(135deg, #FFF, #F5A623);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-text span {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: var(--gold-primary);
            text-transform: uppercase;
        }

        .deployer-badge-pill {
            margin: 14px 16px;
            padding: 8px 12px;
            background: rgba(245, 166, 35, 0.08);
            border: 1px solid rgba(245, 166, 35, 0.25);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .deployer-badge-pill .lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--gold-light);
        }

        .deployer-badge-pill .addr {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #FFF;
        }

        .sidebar-menu {
            padding: 12px 10px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-cat {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 12px 12px 6px 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            color: var(--text-sub);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .nav-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .nav-item:hover {
            color: #FFF;
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-item:hover i {
            color: var(--gold-primary);
        }

        .nav-item.active {
            color: #FFF;
            background: linear-gradient(90deg, rgba(245, 166, 35, 0.15), rgba(245, 166, 35, 0.03));
            border-left: 3px solid var(--gold-primary);
        }

        .nav-item.active i {
            color: var(--gold-primary);
        }

        .sidebar-foot {
            padding: 16px;
            border-top: 1px solid var(--border-dim);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .chain-status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            color: var(--text-sub);
        }

        .dot-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green-live);
            box-shadow: 0 0 10px var(--green-live);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        /* Top Bar */
        .admin-topbar {
            height: 70px;
            background: rgba(9, 12, 18, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-dim);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #FFF;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wallet-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12.5px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .wallet-pill.connected {
            border-color: rgba(245, 166, 35, 0.4);
            background: rgba(245, 166, 35, 0.08);
        }

        .wallet-pill .icon-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #FF3366;
        }

        .wallet-pill.connected .icon-indicator {
            background: var(--green-live);
            box-shadow: 0 0 8px var(--green-live);
        }

        .wallet-pill .addr-txt {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            color: #FFF;
        }

        .btn-topbar {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dim);
            color: var(--text-sub);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-topbar:hover {
            color: #FFF;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Content Container */
        .content-body {
            padding: 28px;
            flex: 1;
        }

        /* Cards & Grid */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.2s;
        }

        .stat-card:hover {
            border-color: rgba(245, 166, 35, 0.3);
            transform: translateY(-2px);
        }

        .stat-card .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-card .card-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-sub);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(245, 166, 35, 0.1);
            color: var(--gold-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .stat-card .card-val {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #FFF;
            letter-spacing: -0.5px;
        }

        .stat-card .card-sub {
            margin-top: 6px;
            font-size: 11.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-card.gold-accent {
            border-color: rgba(245, 166, 35, 0.35);
            background: linear-gradient(180deg, rgba(245, 166, 35, 0.05), var(--bg-card));
        }

        .stat-card.gold-accent .card-val {
            color: var(--gold-light);
        }

        /* Tables */
        .cyber-table-wrap {
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            border-radius: 12px;
            overflow: hidden;
        }

        .cyber-table-head {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-dim);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cyber-table-head h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #FFF;
        }

        .cyber-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .cyber-table th {
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-dim);
        }

        .cyber-table td {
            padding: 14px 18px;
            font-size: 13px;
            color: var(--text-sub);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            vertical-align: middle;
        }

        .cyber-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
            color: #FFF;
        }

        .hash-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--gold-light);
            text-decoration: none;
            background: rgba(245, 166, 35, 0.08);
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .hash-code:hover {
            background: rgba(245, 166, 35, 0.18);
        }

        /* Badges */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-gold {
            background: rgba(245, 166, 35, 0.15);
            color: var(--gold-light);
            border: 1px solid rgba(245, 166, 35, 0.3);
        }

        .badge-green {
            background: rgba(0, 230, 118, 0.15);
            color: var(--green-live);
            border: 1px solid rgba(0, 230, 118, 0.3);
        }

        .badge-red {
            background: rgba(255, 51, 102, 0.15);
            color: var(--red-alert);
            border: 1px solid rgba(255, 51, 102, 0.3);
        }

        /* Buttons */
        .btn-gold {
            background: linear-gradient(135deg, #F5A623, #D48806);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(245, 166, 35, 0.25);
        }

        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245, 166, 35, 0.4);
            filter: brightness(1.08);
        }

        .btn-gold:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-dim {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-sub);
            border: 1px solid var(--border-dim);
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-dim:hover {
            color: #FFF;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Input Groups */
        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-sub);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }

        .form-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-dim);
            padding: 12px 14px;
            border-radius: 8px;
            color: #FFF;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: var(--gold-primary);
            box-shadow: 0 0 10px rgba(245, 166, 35, 0.2);
        }

        /* Access Gate Modal / Overlay */
        .access-gate-modal {
            position: fixed;
            inset: 0;
            background: rgba(5, 6, 8, 0.92);
            backdrop-filter: blur(16px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .gate-card {
            background: #0C1018;
            border: 1px solid rgba(245, 166, 35, 0.4);
            border-radius: 16px;
            padding: 36px 30px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 16px 50px rgba(0, 0, 0, 0.8), 0 0 40px rgba(245, 166, 35, 0.15);
            position: relative;
        }

        .gate-crown {
            width: 68px;
            height: 68px;
            margin: 0 auto 20px auto;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(245, 166, 35, 0.2), rgba(245, 166, 35, 0.05));
            border: 1px solid var(--gold-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: var(--gold-light);
            box-shadow: 0 0 25px rgba(245, 166, 35, 0.3);
        }

        .gate-card h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #FFF;
            margin-bottom: 8px;
        }

        .gate-card p {
            font-size: 13.5px;
            color: var(--text-sub);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .deployer-target-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 24px;
            text-align: left;
        }

        .deployer-target-box .t-lbl {
            font-size: 10px;
            font-weight: 700;
            color: var(--gold-light);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }

        .deployer-target-box .t-addr {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: #FFF;
            word-break: break-all;
        }

        @media (max-width: 900px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="brand-text">
                <h1>ROYAL JACKPOT</h1>
                <span>DEPLOYER ADMIN</span>
            </div>
        </div>

        <div class="deployer-badge-pill">
            <div>
                <div class="lbl"><i class="fas fa-shield-alt"></i> Deployer</div>
                <div class="addr">{{ substr($contracts['primaryDeployer'], 0, 6) }}...{{ substr($contracts['primaryDeployer'], -4) }}</div>
            </div>
            <span class="badge-pill badge-gold"><i class="fas fa-check"></i> Owner</span>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-cat">Management Suite</div>
            <a href="{{ url('/luckydraw/admin') }}" class="nav-item {{ request()->is('luckydraw/admin') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Overview & Analytics</span>
            </a>
            <a href="{{ url('/luckydraw/admin/funds') }}" class="nav-item {{ request()->is('luckydraw/admin/funds*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-transfer"></i>
                <span>Fund Management</span>
            </a>
            <a href="{{ url('/luckydraw/admin/rounds') }}" class="nav-item {{ request()->is('luckydraw/admin/rounds*') ? 'active' : '' }}">
                <i class="fas fa-dice"></i>
                <span>Pools & Rounds</span>
            </a>
            <a href="{{ url('/luckydraw/admin/winners') }}" class="nav-item {{ request()->is('luckydraw/admin/winners*') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i>
                <span>Winners & Claims</span>
            </a>
            <a href="{{ url('/luckydraw/admin/settings') }}" class="nav-item {{ request()->is('luckydraw/admin/settings*') ? 'active' : '' }}">
                <i class="fas fa-sliders"></i>
                <span>Contract Controls</span>
            </a>

            <div class="menu-cat" style="margin-top: 14px;">User Portal</div>
            <a href="{{ url('/User/RoyalJackpot') }}" target="_blank" class="nav-item">
                <i class="fas fa-external-link-alt"></i>
                <span>User Royal Jackpot</span>
            </a>
        </nav>

        <div class="sidebar-foot">
            <div class="chain-status-indicator">
                <span class="dot-pulse"></span>
                <span>BSC Mainnet (Chain 56)</span>
            </div>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="admin-main">
        <!-- TOPBAR -->
        <header class="admin-topbar">
            <div class="topbar-title">
                <h2>@yield('header_title', 'Dashboard')</h2>
            </div>
            <div class="topbar-actions">
                <div class="wallet-pill" id="btnWalletPill" onclick="handleWalletClick()">
                    <span class="icon-indicator" id="walletStatusIndicator"></span>
                    <span class="addr-txt" id="walletStatusText">Connect Wallet</span>
                </div>
                <button class="btn-topbar" onclick="syncBlockchainData()">
                    <i class="fas fa-sync-alt" id="syncSpinner"></i>
                    <span>Sync</span>
                </button>
                <button class="btn-topbar" onclick="adminLogout()" title="Logout Session">
                    <i class="fas fa-power-off"></i>
                </button>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="content-body">
            @yield('content')
        </main>
    </div>

    <!-- ACCESS GATE MODAL (DEPLOYER ONLY) -->
    <div class="access-gate-modal" id="accessGateModal">
        <div class="gate-card">
            <div class="gate-crown">
                <i class="fas fa-lock"></i>
            </div>
            <h2>DEPLOYER AUTHENTICATION</h2>
            <p>This administrative terminal is cryptographically restricted. You must connect with the verified Lucky Draw contract deployer wallet.</p>

            <div class="deployer-target-box">
                <div class="t-lbl">Authorized Deployer Address:</div>
                <div class="t-addr">{{ $contracts['primaryDeployer'] }}</div>
            </div>

            <div id="gateStatusMsg" style="display:none; font-size:12px; margin-bottom:14px; padding:8px 12px; border-radius:6px;"></div>

            <button class="btn-gold" id="btnGateConnect" onclick="connectAndAuthenticateDeployer()" style="width: 100%;">
                <i class="fas fa-wallet"></i> Connect Deployer Wallet & Enter
            </button>
        </div>
    </div>

    <!-- GLOBAL WEB3 JAVASCRIPT ENGINE -->
    <script>
        const CONTRACT_CONFIG = {
            deposit: "{{ $contracts['depositContract'] }}",
            vault: "{{ $contracts['vaultContract'] }}",
            collector: "{{ $contracts['collectorWallet'] }}",
            owner: "{{ $contracts['adminOwnerAddress'] }}",
            usdt: "{{ $contracts['usdtToken'] }}",
            primaryDeployer: "{{ strtolower($contracts['primaryDeployer']) }}",
            authorizedWallets: @json($contracts['authorizedWallets']),
            chainId: 56,
            csrfToken: "{{ csrf_token() }}",
            sessionWallet: "{{ strtolower($contracts['currentSession'] ?? '') }}"
        };

        const DEPOSIT_ABI = [
            "function owner() view returns (address)",
            "function whitelistedCollector() view returns (address)",
            "function autoForward() view returns (bool)",
            "function paused() view returns (bool)",
            "function totalDepositedUsdt() view returns (uint256)",
            "function totalDepositsCount() view returns (uint256)",
            "function withdrawCollectedFunds(address recipient, uint256 amount) external",
            "function sweepAllToCollector() external",
            "function setWhitelistedCollector(address _newCollector) external",
            "function setAutoForward(bool _autoForward) external",
            "function setPaused(bool _paused) external",
            "function transferOwnership(address newOwner) external",
            "function acceptOwnership() external"
        ];

        const VAULT_ABI = [
            "function owner() view returns (address)",
            "function backendSigner() view returns (address)",
            "function paused() view returns (bool)",
            "function totalPrizesPaid() view returns (uint256)",
            "function totalClaimsCount() view returns (uint256)",
            "function emergencyWithdraw(address token, address to, uint256 amount) external",
            "function setBackendSigner(address _newSigner) external",
            "function setPaused(bool _paused) external",
            "function transferOwnership(address newOwner) external",
            "function acceptOwnership() external"
        ];

        const USDT_ABI = [
            "function balanceOf(address account) view returns (uint256)",
            "function transfer(address to, uint256 amount) returns (bool)",
            "function approve(address spender, uint256 amount) returns (bool)",
            "function allowance(address owner, address spender) view returns (uint256)"
        ];

        let currentWallet = null;

        function toast(icon, title, text) {
            return Swal.fire({
                icon: icon,
                title: title,
                text: text,
                background: '#0D111A',
                color: '#FFF',
                confirmButtonColor: '#F5A623',
                customClass: { popup: 'cyber-swal' }
            });
        }

        // Initialize Web3 Wallet State
        async function initWeb3Auth() {
            if (!window.ethereum) {
                showGateModal("MetaMask or Web3 wallet not detected. Please install MetaMask to access deployer portal.", true);
                return;
            }

            try {
                const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                if (accounts && accounts.length > 0) {
                    currentWallet = accounts[0].toLowerCase();
                    updateWalletUI(currentWallet);

                    // Check if current wallet is authorized
                    if (!isWalletAuthorized(currentWallet)) {
                        showGateModal(`Connected wallet (${currentWallet.slice(0,6)}...${currentWallet.slice(-4)}) is not authorized.<br>Please switch to Deployer: <b>${CONTRACT_CONFIG.primaryDeployer.slice(0,6)}...${CONTRACT_CONFIG.primaryDeployer.slice(-4)}</b>`, true);
                    } else {
                        hideGateModal();
                    }
                } else {
                    // Not connected yet
                    if (!CONTRACT_CONFIG.sessionWallet) {
                        showGateModal(null, false);
                    } else {
                        updateWalletUI(CONTRACT_CONFIG.sessionWallet);
                    }
                }

                // Listen for account change
                window.ethereum.on('accountsChanged', (newAccounts) => {
                    if (newAccounts.length === 0) {
                        currentWallet = null;
                        updateWalletUI(null);
                        showGateModal("Wallet disconnected. Connect deployer wallet.", false);
                    } else {
                        currentWallet = newAccounts[0].toLowerCase();
                        updateWalletUI(currentWallet);
                        if (!isWalletAuthorized(currentWallet)) {
                            showGateModal(`Connected wallet (${currentWallet.slice(0,6)}...${currentWallet.slice(-4)}) is not authorized.<br>Please switch to Deployer.`, true);
                        } else {
                            hideGateModal();
                            window.location.reload();
                        }
                    }
                });

                window.ethereum.on('chainChanged', () => {
                    window.location.reload();
                });
            } catch (err) {
                console.error("Web3 init error:", err);
            }
        }

        function isWalletAuthorized(wallet) {
            if (!wallet) return false;
            return CONTRACT_CONFIG.authorizedWallets.includes(wallet.toLowerCase());
        }

        function updateWalletUI(wallet) {
            const pill = document.getElementById('btnWalletPill');
            const txt = document.getElementById('walletStatusText');
            if (wallet && isWalletAuthorized(wallet)) {
                pill.classList.add('connected');
                txt.textContent = wallet.slice(0, 6) + '...' + wallet.slice(-4);
            } else if (wallet) {
                pill.classList.remove('connected');
                txt.textContent = 'Unauthorized (' + wallet.slice(0, 4) + '...)';
            } else {
                pill.classList.remove('connected');
                txt.textContent = 'Connect Wallet';
            }
        }

        function showGateModal(msg, isError) {
            const modal = document.getElementById('accessGateModal');
            const msgEl = document.getElementById('gateStatusMsg');
            if (msg) {
                msgEl.style.display = 'block';
                msgEl.innerHTML = msg;
                msgEl.style.background = isError ? 'rgba(255, 51, 102, 0.15)' : 'rgba(245, 166, 35, 0.15)';
                msgEl.style.color = isError ? '#FF3366' : '#FFD700';
                msgEl.style.border = isError ? '1px solid rgba(255, 51, 102, 0.3)' : '1px solid rgba(245, 166, 35, 0.3)';
            } else {
                msgEl.style.display = 'none';
            }
            modal.style.display = 'flex';
        }

        function hideGateModal() {
            document.getElementById('accessGateModal').style.display = 'none';
        }

        async function handleWalletClick() {
            if (!currentWallet) {
                await connectAndAuthenticateDeployer();
            } else {
                toast('info', 'Deployer Connected', `Address: ${currentWallet}\nAuthorized: ${isWalletAuthorized(currentWallet) ? 'YES' : 'NO'}`);
            }
        }

        // Connect, Verify Challenge, and Create Authenticated Admin Session
        async function connectAndAuthenticateDeployer() {
            if (!window.ethereum) {
                toast('error', 'Wallet Required', 'Please install MetaMask or TrustWallet.');
                return;
            }

            const btn = document.getElementById('btnGateConnect');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';

            try {
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                const selected = accounts[0].toLowerCase();

                if (!isWalletAuthorized(selected)) {
                    showGateModal(`Access Denied! Connected wallet (${selected.slice(0,6)}...${selected.slice(-4)}) is not an authorized deployer.<br>Switch to: <b>${CONTRACT_CONFIG.primaryDeployer.slice(0,6)}...${CONTRACT_CONFIG.primaryDeployer.slice(-4)}</b>`, true);
                    btn.disabled = false;
                    btn.innerHTML = orig;
                    return;
                }

                // Verify BSC Network
                const chainIdHex = await window.ethereum.request({ method: 'eth_chainId' });
                if (parseInt(chainIdHex, 16) !== CONTRACT_CONFIG.chainId) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Switching to BSC Mainnet...';
                    try {
                        await window.ethereum.request({
                            method: 'wallet_switchEthereumChain',
                            params: [{ chainId: '0x38' }]
                        });
                    } catch (switchError) {
                        if (switchError.code === 4902) {
                            await window.ethereum.request({
                                method: 'wallet_addEthereumChain',
                                params: [{
                                    chainId: '0x38',
                                    chainName: 'BNB Smart Chain Mainnet',
                                    rpcUrls: ['https://bsc-dataseed.binance.org/'],
                                    nativeCurrency: { name: 'BNB', symbol: 'BNB', decimals: 18 },
                                    blockExplorerUrls: ['https://bscscan.com']
                                }]
                            });
                        }
                    }
                }

                // Request Challenge Nonce from Backend
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Requesting Challenge...';
                const chalRes = await fetch("{{ url('/luckydraw/admin/api/auth-challenge') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CONTRACT_CONFIG.csrfToken },
                    body: JSON.stringify({ address: selected })
                });

                const chalData = await chalRes.json();
                if (chalData.status !== 'success') {
                    showGateModal(chalData.message || 'Authorization failed.', true);
                    btn.disabled = false;
                    btn.innerHTML = orig;
                    return;
                }

                // Request Personal Sign from Deployer
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sign challenge in MetaMask...';
                const signature = await window.ethereum.request({
                    method: 'personal_sign',
                    params: [chalData.message, selected]
                });

                // Submit Signature to Backend
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
                const authRes = await fetch("{{ url('/luckydraw/admin/api/auth-login') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CONTRACT_CONFIG.csrfToken },
                    body: JSON.stringify({ address: selected, signature: signature, message: chalData.message })
                });

                const authData = await authRes.json();
                if (authData.status === 'success') {
                    hideGateModal();
                    await toast('success', 'Authenticated', 'Welcome, Deployer. Admin access granted.');
                    window.location.reload();
                } else {
                    showGateModal(authData.message || 'Signature verification failed.', true);
                }
            } catch (err) {
                console.error("Auth error:", err);
                toast('error', 'Authentication Canceled', err.message || 'Action canceled.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        }

        async function adminLogout() {
            await fetch("{{ url('/luckydraw/admin/api/logout') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CONTRACT_CONFIG.csrfToken }
            });
            window.location.reload();
        }

        async function syncBlockchainData() {
            const spinner = document.getElementById('syncSpinner');
            spinner.classList.add('fa-spin');
            try {
                const res = await fetch("{{ url('/luckydraw/admin/api/stats') }}");
                const d = await res.json();
                if (d.status === 'success') {
                    await toast('success', 'Blockchain Synced', 'Live balances and round stats updated from BSC.');
                    window.location.reload();
                }
            } catch (e) {
                toast('error', 'Sync Failed', e.message);
            } finally {
                spinner.classList.remove('fa-spin');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initWeb3Auth();
        });
    </script>
    @yield('scripts')
</body>
</html>
