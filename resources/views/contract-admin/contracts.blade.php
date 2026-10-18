<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CYERA AI — Smart Contract Operations & Governance Console</title>
    <meta name="description" content="Dedicated Smart Contract Administrative Operations Console for Cyera Protocol V2 on BNB Smart Chain.">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
    
    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Ethers.js for Web3 Blockchain Controls -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.13.2/ethers.umd.min.js"></script>

    <style>
        :root {
            --bg-base: #f8fafc;
            --bg-surface: #ffffff;
            --bg-elevated: #f1f5f9;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-soft: #eef2ff;
            --primary-border: #c7d2fe;
            
            --emerald: #10b981;
            --emerald-soft: #ecfdf5;
            --emerald-border: #a7f3d0;
            --emerald-text: #047857;
            
            --amber: #f59e0b;
            --amber-soft: #fffbeb;
            --amber-border: #fde68a;
            --amber-text: #b45309;
            
            --rose: #ef4444;
            --rose-soft: #fef2f2;
            --rose-border: #fecaca;
            --rose-text: #b91c1c;

            --cyan: #06b6d4;
            --cyan-soft: #ecfeff;
            --cyan-border: #a5f3fc;
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            
            --shadow-subtle: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            --shadow-card: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-float: 0 12px 24px -4px rgba(0, 0, 0, 0.08), 0 4px 8px -2px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-body);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Header */
        .admin-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 12px 24px;
        }

        .nav-inner {
            max-width: 1540px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-cluster {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .brand-avatar {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .brand-text h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.70rem;
            font-weight: 700;
        }

        .badge-v2 {
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid var(--primary-border);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .wallet-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.80rem;
            font-weight: 600;
            color: var(--text-heading);
            box-shadow: var(--shadow-subtle);
        }

        .wallet-pill.owner-verified {
            border-color: var(--emerald-border);
            background: var(--emerald-soft);
            color: var(--emerald-text);
        }

        .btn-nav-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-heading);
            padding: 7px 14px;
            border-radius: var(--radius-md);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: var(--shadow-subtle);
        }

        .btn-nav-action:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-nav-action.primary {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        .btn-nav-action.primary:hover {
            background: var(--primary-hover);
            color: #ffffff;
        }

        .btn-nav-action.danger {
            color: var(--rose-text);
            border-color: var(--rose-border);
            background: var(--rose-soft);
        }

        /* Main Content Container */
        .portal-main {
            max-width: 1540px;
            margin: 0 auto;
            padding: 24px;
            width: 100%;
            flex: 1;
        }

        /* Top Hero Header */
        .hero-banner {
            background: linear-gradient(135deg, #1e1b4b, #312e81, #1e293b);
            border-radius: var(--radius-xl);
            padding: 28px 32px;
            color: #ffffff;
            margin-bottom: 24px;
            box-shadow: var(--shadow-float);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-desc {
            font-size: 0.88rem;
            color: #cbd5e1;
            max-width: 720px;
            line-height: 1.5;
        }

        .hero-stats-row {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-stat-badge {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--radius-md);
            padding: 10px 16px;
            text-align: left;
        }

        .hero-stat-label {
            font-size: 0.70rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.05em;
        }

        .hero-stat-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.15rem;
            font-weight: 800;
            color: #38bdf8;
            margin-top: 2px;
        }

        /* Quick Navigation Subbar */
        .contracts-nav-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
            margin-bottom: 24px;
        }

        .nav-chip {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-body);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-chip:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .nav-chip.active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Contract Card Box */
        .contract-section {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            margin-bottom: 28px;
            overflow: hidden;
            transition: all 0.2s;
        }

        .contract-section:hover {
            box-shadow: var(--shadow-float);
        }

        .section-header {
            padding: 20px 26px;
            background: #fafaf9;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .section-title-cluster {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .section-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            flex-shrink: 0;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-subtitle {
            font-size: 0.80rem;
            color: var(--text-muted);
            margin-top: 2px;
            font-family: 'JetBrains Mono', monospace;
        }

        .section-body {
            padding: 26px;
        }

        /* Metrics Row */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .metric-pill {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 12px 14px;
        }

        .metric-pill-label {
            font-size: 0.70rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }

        .metric-pill-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-heading);
            margin-top: 4px;
            word-break: break-all;
        }

        /* Action Grid */
        .action-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 18px;
        }

        .action-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 18px;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .action-card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-card);
        }

        .action-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .action-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-card-desc {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 14px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.74rem;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 6px;
        }

        .quick-fill-btn {
            font-size: 0.68rem;
            color: var(--primary);
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            border-radius: 4px;
            padding: 1px 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .quick-fill-btn:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .form-input {
            width: 100%;
            height: 40px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 12px;
            font-size: 0.85rem;
            color: var(--text-heading);
            outline: none;
            transition: all 0.2s;
            font-family: 'JetBrains Mono', monospace;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .btn-execute {
            width: 100%;
            height: 42px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
            margin-top: 6px;
        }

        .btn-execute:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .btn-execute.emerald {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }
        .btn-execute.emerald:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }

        .btn-execute.amber {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.25);
        }
        .btn-execute.amber:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .btn-execute.rose {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
        }
        .btn-execute.rose:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .btn-execute:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Toast Container */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-box {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 18px;
            box-shadow: var(--shadow-float);
            min-width: 320px;
            max-width: 440px;
            font-size: 0.84rem;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .toast-success {
            border-left: 4px solid var(--emerald);
        }
        .toast-error {
            border-left: 4px solid var(--rose);
        }
        .toast-info {
            border-left: 4px solid var(--primary);
        }

        .toast-icon {
            font-size: 18px;
            margin-top: 2px;
        }
        .toast-success .toast-icon { color: var(--emerald); }
        .toast-error .toast-icon { color: var(--rose); }
        .toast-info .toast-icon { color: var(--primary); }

        .toast-content {
            flex: 1;
            word-break: break-word;
        }

        .toast-link {
            display: block;
            margin-top: 4px;
            font-size: 0.76rem;
            color: var(--primary);
            text-decoration: underline;
        }

        /* Spinner */
        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .portal-main {
                padding: 16px;
            }
            .hero-banner {
                padding: 20px;
            }
            .action-cards-grid {
                grid-template-columns: 1fr;
            }
            .nav-inner {
                flex-direction: column;
                align-items: stretch;
            }
            .nav-actions {
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

    <!-- Top Sticky Header -->
    <header class="admin-nav">
        <div class="nav-inner">
            <div class="brand-cluster">
                <div class="brand-avatar">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <div class="brand-text">
                    <h1>
                        CYERA AI 
                        <span class="badge-pill badge-v2">PROTOCOL V2</span>
                    </h1>
                    <p>Smart Contract Operations & Governance Console</p>
                </div>
            </div>

            <div class="nav-actions">
                <a href="{{ url('/dishi/yashi') }}" class="btn-nav-action">
                    <i class="fa-solid fa-arrow-left"></i> Command Center
                </a>

                <div class="wallet-pill owner-verified" id="navOwnerBadge">
                    <i class="fa-solid fa-crown" style="color: #f59e0b;"></i>
                    <span id="navOwnerText">Owner: 0xB68e...3622</span>
                </div>

                <div class="wallet-pill" id="navBnbBadge" title="Owner BNB Gas Balance">
                    <i class="fa-brands fa-ethereum" style="color: #f5841f;"></i>
                    <span id="navBnbText">... BNB</span>
                </div>

                <div class="wallet-pill" id="navUsdtBadge" title="Owner USDT Balance">
                    <i class="fa-solid fa-dollar-sign" style="color: #10b981;"></i>
                    <span id="navUsdtText">... USDT</span>
                </div>

                <button type="button" class="btn-nav-action primary" onclick="refreshAllOnChainStats()">
                    <i class="fa-solid fa-arrows-rotate" id="refreshIcon"></i> Sync RPC
                </button>

                <a href="{{ url('/dishi/yashi/logout') }}" class="btn-nav-action danger" onclick="return confirm('Disconnect and terminate admin session?');">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="portal-main">

        <!-- Hero Banner -->
        <div class="hero-banner">
            <div>
                <div class="hero-title">
                    <i class="fa-solid fa-gears" style="color: #38bdf8;"></i> Smart Contract Governance Suite
                </div>
                <p class="hero-desc">
                    Direct on-chain execution portal for all 7 deployed Protocol V2 smart contracts on BNB Smart Chain. 
                    Every function connects directly to your verified MetaMask Owner wallet with automated gas protection.
                </p>
            </div>

            <div class="hero-stats-row">
                <div class="hero-stat-badge">
                    <div class="hero-stat-label">Network / Chain</div>
                    <div class="hero-stat-val">BSC (56)</div>
                </div>
                <div class="hero-stat-badge">
                    <div class="hero-stat-label">Total Verified Contracts</div>
                    <div class="hero-stat-val">7 Core V2</div>
                </div>
                <div class="hero-stat-badge">
                    <div class="hero-stat-label">Execution Mode</div>
                    <div class="hero-stat-val" style="color: #4ade80;">MetaMask Direct</div>
                </div>
            </div>
        </div>

        <!-- Quick Jump Navigation Chips -->
        <div class="contracts-nav-bar">
            <a href="#sec-engine" class="nav-chip active"><i class="fa-solid fa-microchip"></i> Mining Engine</a>
            <a href="#sec-splitter" class="nav-chip"><i class="fa-solid fa-code-fork"></i> Revenue Splitter</a>
            <a href="#sec-vault" class="nav-chip"><i class="fa-solid fa-vault"></i> Withdrawal Vault</a>
            <a href="#sec-jackpot-dep" class="nav-chip"><i class="fa-solid fa-trophy"></i> Jackpot Deposit</a>
            <a href="#sec-jackpot-vault" class="nav-chip"><i class="fa-solid fa-crown"></i> Jackpot Vault</a>
            <a href="#sec-pool" class="nav-chip"><i class="fa-solid fa-water"></i> PancakeSwap Pool</a>
            <a href="#sec-inspector" class="nav-chip"><i class="fa-solid fa-magnifying-glass"></i> Wallet Inspector</a>
        </div>

        <!-- ========================================================== -->
        <!-- CONTRACT 1: CYERA MINING ENGINE                           -->
        <!-- ========================================================== -->
        <section id="sec-engine" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            Cyera Mining Engine V2
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-circle-check"></i> Primary Engine</span>
                        </div>
                        <div class="section-subtitle">
                            {{ $contracts['miningEngineAddress'] }}
                            <a href="https://bscscan.com/address/{{ $contracts['miningEngineAddress'] }}#code" target="_blank" style="color: var(--primary); margin-left: 8px;">
                                View on BscScan <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="btn-nav-action" onclick="copyToClipboard('{{ $contracts['miningEngineAddress'] }}')">
                    <i class="fa-regular fa-copy"></i> Copy Address
                </button>
            </div>

            <div class="section-body">
                <!-- Live Stats -->
                <div class="metrics-grid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">Buffer CAAI Balance</div>
                        <div class="metric-pill-val" id="engineBufferCaai" style="color: #10b981;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Invested USDT</div>
                        <div class="metric-pill-val" id="engineTotalInvested">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total CAAI Purchased</div>
                        <div class="metric-pill-val" id="engineTotalCaiPurchased">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total ROI Paid (USDT)</div>
                        <div class="metric-pill-val" id="engineTotalRoiPaid">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Vault Funded (USDT)</div>
                        <div class="metric-pill-val" id="engineVaultFunded">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Splitter Funded (USDT)</div>
                        <div class="metric-pill-val" id="engineSplitterFunded">Loading...</div>
                    </div>
                </div>

                <!-- Interactive Functions -->
                <div class="action-cards-grid">
                    <!-- Function: rescueTokens -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-hand-holding-dollar" style="color: #f59e0b;"></i> rescueTokens</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Safely recover any BEP-20 tokens sent to the Mining Engine contract.</p>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <span>Token Address</span>
                                    <span>
                                        <button type="button" class="quick-fill-btn" onclick="fillInput('engineRescueToken', '{{ $contracts['usdtTokenAddress'] }}')">USDT</button>
                                        <button type="button" class="quick-fill-btn" onclick="fillInput('engineRescueToken', '{{ $contracts['caiTokenAddress'] }}')">CAAI</button>
                                    </span>
                                </label>
                                <input type="text" id="engineRescueToken" class="form-input" placeholder="0x..." value="{{ $contracts['usdtTokenAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <span>Destination (To)</span>
                                    <button type="button" class="quick-fill-btn" onclick="fillInput('engineRescueTo', '{{ $contracts['adminOwnerAddress'] }}')">Owner</button>
                                </label>
                                <input type="text" id="engineRescueTo" class="form-input" placeholder="0x..." value="{{ $contracts['adminOwnerAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Amount (Human Units, e.g. 50)</label>
                                <input type="number" id="engineRescueAmount" class="form-input" placeholder="0.0">
                            </div>
                        </div>

                        <button type="button" class="btn-execute amber" onclick="executeEngineRescueTokens()">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i> Execute rescueTokens
                        </button>
                    </div>

                    <!-- Function: setWithdrawalVault -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-vault" style="color: #4f46e5;"></i> setWithdrawalVault</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the designated USDT Withdrawal Vault address (currently 38% funding).</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Withdrawal Vault Address</label>
                                <input type="text" id="engineNewVault" class="form-input" placeholder="0x..." value="{{ $contracts['usdtWithdrawalVaultAddress'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeEngineSetWithdrawalVault()">
                            <i class="fa-solid fa-floppy-disk"></i> Update Withdrawal Vault
                        </button>
                    </div>

                    <!-- Function: setRevenueSplitter -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-code-fork" style="color: #06b6d4;"></i> setRevenueSplitter</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the Revenue Splitter contract address (currently 32% funding).</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Revenue Splitter Address</label>
                                <input type="text" id="engineNewSplitter" class="form-input" placeholder="0x..." value="{{ $contracts['revenueSplitterAddress'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeEngineSetRevenueSplitter()">
                            <i class="fa-solid fa-floppy-disk"></i> Update Revenue Splitter
                        </button>
                    </div>

                    <!-- Function: setBackendSigner -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-key" style="color: #ec4899;"></i> setBackendSigner</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the authorized backend signer key address for ROI claims.</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Backend Signer Address</label>
                                <input type="text" id="engineNewSigner" class="form-input" placeholder="0x..." value="{{ $contracts['backendSignerAddress'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeEngineSetBackendSigner()">
                            <i class="fa-solid fa-shield-halved"></i> Update Backend Signer
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- CONTRACT 2: REVENUE SPLITTER V2                           -->
        <!-- ========================================================== -->
        <section id="sec-splitter" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fa-solid fa-code-fork"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            Revenue Splitter V2 (68 / 20 / 10 / 2)
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-arrows-split-up-and-left"></i> Automated Split</span>
                        </div>
                        <div class="section-subtitle">
                            {{ $contracts['revenueSplitterAddress'] }}
                            <a href="https://bscscan.com/address/{{ $contracts['revenueSplitterAddress'] }}#code" target="_blank" style="color: var(--primary); margin-left: 8px;">
                                View on BscScan <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="btn-nav-action" onclick="copyToClipboard('{{ $contracts['revenueSplitterAddress'] }}')">
                    <i class="fa-regular fa-copy"></i> Copy Address
                </button>
            </div>

            <div class="section-body">
                <!-- Live Stats -->
                <div class="metrics-grid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">Undistributed USDT Balance</div>
                        <div class="metric-pill-val" id="splitterHeldUsdt" style="color: #f59e0b;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Distributed USDT</div>
                        <div class="metric-pill-val" id="splitterTotalDistributed">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Trading Paid (20%)</div>
                        <div class="metric-pill-val" id="splitterTradingPaid">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Partners Paid (10%)</div>
                        <div class="metric-pill-val" id="splitterPartnersPaid">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Reserve Paid (2%)</div>
                        <div class="metric-pill-val" id="splitterReservePaid">Loading...</div>
                    </div>
                </div>

                <!-- Interactive Functions -->
                <div class="action-cards-grid">
                    <!-- Big Action: distribute() -->
                    <div class="action-card" style="grid-column: 1 / -1; background: var(--amber-soft); border-color: var(--amber-border);">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title" style="color: #b45309;"><i class="fa-solid fa-bolt"></i> distribute() — Trigger Instant 68/20/10/2 On-Chain Distribution</span>
                                <span class="badge-pill" style="background: #ffffff; color: #b45309; border: 1px solid var(--amber-border);">Public / Owner</span>
                            </div>
                            <p class="action-card-desc" style="color: #78350f;">
                                Atomically splits all accumulated USDT currently held in this contract into Trading Operations (20%), 5 Designated Partners (2% each = 10%), and Operations Reserve (2%). 
                                Single atomic transaction ensures exact 100% zero-loss distribution.
                            </p>
                        </div>
                        <button type="button" class="btn-execute amber" id="btnDistribute" onclick="executeSplitterDistribute()" style="height: 48px; font-size: 0.95rem;">
                            <i class="fa-solid fa-paper-plane"></i> Execute Instant On-Chain Distribution
                        </button>
                    </div>

                    <!-- Function: setTradingWallet -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-chart-line"></i> setTradingWallet</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the Trading Operations wallet address (receives 20% of package).</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Trading Wallet Address</label>
                                <input type="text" id="splitterNewTrading" class="form-input" placeholder="0x..." value="{{ $contracts['tradingWallet'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeSplitterSetTradingWallet()">
                            <i class="fa-solid fa-floppy-disk"></i> Update Trading Wallet
                        </button>
                    </div>

                    <!-- Function: setReserveWallet -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-shield-cat"></i> setReserveWallet</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the Reserve/Operations wallet address (receives 2% of package).</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Reserve Wallet Address</label>
                                <input type="text" id="splitterNewReserve" class="form-input" placeholder="0x..." value="{{ $contracts['reserveWallet'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeSplitterSetReserveWallet()">
                            <i class="fa-solid fa-floppy-disk"></i> Update Reserve Wallet
                        </button>
                    </div>

                    <!-- Function: setPartnerWallet -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-handshake"></i> setPartnerWallet</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update any of the 5 individual partner wallets (receives 2% each).</p>
                            
                            <div class="form-group">
                                <label class="form-label">Select Partner Index</label>
                                <select id="splitterPartnerIndex" class="form-input" style="cursor: pointer;">
                                    <option value="0">Partner 1 (Index 0)</option>
                                    <option value="1">Partner 2 (Index 1)</option>
                                    <option value="2">Partner 3 (Index 2)</option>
                                    <option value="3">Partner 4 (Index 3)</option>
                                    <option value="4">Partner 5 (Index 4)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">New Partner Wallet Address</label>
                                <input type="text" id="splitterNewPartnerAddr" class="form-input" placeholder="0x...">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeSplitterSetPartnerWallet()">
                            <i class="fa-solid fa-floppy-disk"></i> Update Partner Wallet
                        </button>
                    </div>

                    <!-- Function: rescueTokens -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-life-ring"></i> rescueTokens</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Emergency recovery for any non-USDT tokens sent accidentally.</p>
                            
                            <div class="form-group">
                                <label class="form-label">Token Address</label>
                                <input type="text" id="splitterRescueToken" class="form-input" placeholder="0x..." value="{{ $contracts['caiTokenAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Destination Address</label>
                                <input type="text" id="splitterRescueTo" class="form-input" placeholder="0x..." value="{{ $contracts['adminOwnerAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Amount (Human Units)</label>
                                <input type="number" id="splitterRescueAmount" class="form-input" placeholder="0.0">
                            </div>
                        </div>

                        <button type="button" class="btn-execute amber" onclick="executeSplitterRescueTokens()">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i> Execute rescueTokens
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- CONTRACT 3: USDT WITHDRAWAL VAULT                         -->
        <!-- ========================================================== -->
        <section id="sec-vault" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #4f46e5, #3730a3);">
                        <i class="fa-solid fa-vault"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            USDT Withdrawal Vault
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-shield"></i> EIP-712 Verified</span>
                        </div>
                        <div class="section-subtitle">
                            {{ $contracts['usdtWithdrawalVaultAddress'] }}
                            <a href="https://bscscan.com/address/{{ $contracts['usdtWithdrawalVaultAddress'] }}#code" target="_blank" style="color: var(--primary); margin-left: 8px;">
                                View on BscScan <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="btn-nav-action" onclick="copyToClipboard('{{ $contracts['usdtWithdrawalVaultAddress'] }}')">
                    <i class="fa-regular fa-copy"></i> Copy Address
                </button>
            </div>

            <div class="section-body">
                <!-- Live Stats -->
                <div class="metrics-grid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">Vault USDT Liquidity</div>
                        <div class="metric-pill-val" id="vaultHeldUsdt" style="color: #4f46e5;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Authorized Backend Signer</div>
                        <div class="metric-pill-val" id="vaultSignerAddr" style="font-size: 0.8rem;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Vault Status</div>
                        <div class="metric-pill-val" id="vaultPausedStatus">Loading...</div>
                    </div>
                </div>

                <!-- Interactive Functions -->
                <div class="action-cards-grid">
                    <!-- Function: emergencyWithdraw -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-triangle-exclamation" style="color: #ef4444;"></i> emergencyWithdraw</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Safely withdraw USDT from the vault to the Owner or specified treasury address.</p>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <span>Recipient (To)</span>
                                    <button type="button" class="quick-fill-btn" onclick="fillInput('vaultEmergencyTo', '{{ $contracts['adminOwnerAddress'] }}')">Owner</button>
                                </label>
                                <input type="text" id="vaultEmergencyTo" class="form-input" placeholder="0x..." value="{{ $contracts['adminOwnerAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">USDT Amount</label>
                                <input type="number" id="vaultEmergencyAmount" class="form-input" placeholder="0.0">
                            </div>
                        </div>

                        <button type="button" class="btn-execute rose" onclick="executeVaultEmergencyWithdraw()">
                            <i class="fa-solid fa-arrow-down-to-bracket"></i> Execute Emergency Withdrawal
                        </button>
                    </div>

                    <!-- Function: setBackendSigner -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-key"></i> setBackendSigner</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the authorized backend signer address that verifies user withdrawals.</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Backend Signer Address</label>
                                <input type="text" id="vaultNewSigner" class="form-input" placeholder="0x..." value="{{ $contracts['backendSignerAddress'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeVaultSetBackendSigner()">
                            <i class="fa-solid fa-shield-halved"></i> Update Vault Signer
                        </button>
                    </div>

                    <!-- Function: pause / unpause -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-pause"></i> pause / unpause</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Toggle the emergency paused state of all withdrawal claims.</p>
                            
                            <div class="form-group">
                                <div style="font-size: 0.82rem; font-weight: 600; padding: 10px; background: var(--bg-base); border-radius: 8px;" id="vaultPauseStateDisplay">
                                    Current Status: Querying...
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-execute" id="btnToggleVaultPause" onclick="executeVaultTogglePause()">
                            <i class="fa-solid fa-shield"></i> Toggle Pause State
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- CONTRACT 4: ROYAL JACKPOT DEPOSIT                         -->
        <!-- ========================================================== -->
        <section id="sec-jackpot-dep" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #b45309);">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            Royal Jackpot Deposit Contract
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-ticket"></i> Jackpot Inflow</span>
                        </div>
                        <div class="section-subtitle">
                            {{ $contracts['royalJackpotDepositAddress'] }}
                            <a href="https://bscscan.com/address/{{ $contracts['royalJackpotDepositAddress'] }}#code" target="_blank" style="color: var(--primary); margin-left: 8px;">
                                View on BscScan <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="btn-nav-action" onclick="copyToClipboard('{{ $contracts['royalJackpotDepositAddress'] }}')">
                    <i class="fa-regular fa-copy"></i> Copy Address
                </button>
            </div>

            <div class="section-body">
                <!-- Live Stats -->
                <div class="metrics-grid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">Deposit Contract USDT</div>
                        <div class="metric-pill-val" id="jackpotDepHeldUsdt" style="color: #f59e0b;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Whitelisted Collector</div>
                        <div class="metric-pill-val" id="jackpotCollectorAddr" style="font-size: 0.8rem;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Auto-Forwarding</div>
                        <div class="metric-pill-val" id="jackpotAutoForwardStatus">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Deposits Count</div>
                        <div class="metric-pill-val" id="jackpotTotalCount">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Deposited USDT</div>
                        <div class="metric-pill-val" id="jackpotTotalDeposited">Loading...</div>
                    </div>
                </div>

                <!-- Interactive Functions -->
                <div class="action-cards-grid">
                    <!-- Function: sweepAllToCollector -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-broom" style="color: #10b981;"></i> sweepAllToCollector</span>
                                <span class="badge-pill badge-v2">Authorized</span>
                            </div>
                            <p class="action-card-desc">Instantly sweep 100% of accumulated USDT to the whitelisted collector wallet.</p>
                        </div>
                        <button type="button" class="btn-execute emerald" onclick="executeJackpotSweepAll()">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Sweep All to Collector
                        </button>
                    </div>

                    <!-- Function: setWhitelistedCollector -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-user-shield"></i> setWhitelistedCollector</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the designated whitelisted collector wallet address.</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Collector Wallet Address</label>
                                <input type="text" id="jackpotNewCollector" class="form-input" placeholder="0x..." value="{{ $contracts['royalJackpotCollector'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeJackpotSetCollector()">
                            <i class="fa-solid fa-floppy-disk"></i> Update Collector Wallet
                        </button>
                    </div>

                    <!-- Function: setAutoForward -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-forward"></i> setAutoForward</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Toggle automatic forwarding of jackpot deposits directly to collector on deposit.</p>
                            
                            <div class="form-group">
                                <select id="jackpotAutoFwdSelect" class="form-input">
                                    <option value="true">Enable Auto-Forward (true)</option>
                                    <option value="false">Disable Auto-Forward (false)</option>
                                </select>
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeJackpotSetAutoForward()">
                            <i class="fa-solid fa-toggle-on"></i> Update Auto-Forward State
                        </button>
                    </div>

                    <!-- Function: setPaused -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-pause"></i> setPaused</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Pause or unpause user jackpot deposits into the contract.</p>
                            
                            <div class="form-group">
                                <select id="jackpotPausedSelect" class="form-input">
                                    <option value="false">Active / Unpaused (false)</option>
                                    <option value="true">Paused (true)</option>
                                </select>
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeJackpotSetPaused()">
                            <i class="fa-solid fa-shield"></i> Update Paused State
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- CONTRACT 5: ROYAL JACKPOT VAULT                           -->
        <!-- ========================================================== -->
        <section id="sec-jackpot-vault" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            Royal Jackpot Prize Vault
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-gift"></i> Prize Payouts</span>
                        </div>
                        <div class="section-subtitle">
                            {{ $contracts['royalJackpotVaultAddress'] }}
                            <a href="https://bscscan.com/address/{{ $contracts['royalJackpotVaultAddress'] }}#code" target="_blank" style="color: var(--primary); margin-left: 8px;">
                                View on BscScan <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="btn-nav-action" onclick="copyToClipboard('{{ $contracts['royalJackpotVaultAddress'] }}')">
                    <i class="fa-regular fa-copy"></i> Copy Address
                </button>
            </div>

            <div class="section-body">
                <!-- Live Stats -->
                <div class="metrics-grid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">Prize Liquidity (USDT)</div>
                        <div class="metric-pill-val" id="jackpotVaultHeldUsdt" style="color: #ef4444;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Backend Signer</div>
                        <div class="metric-pill-val" id="jackpotVaultSignerAddr" style="font-size: 0.8rem;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Prizes Paid</div>
                        <div class="metric-pill-val" id="jackpotTotalPrizesPaid">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Claims Count</div>
                        <div class="metric-pill-val" id="jackpotTotalClaimsCount">Loading...</div>
                    </div>
                </div>

                <!-- Interactive Functions -->
                <div class="action-cards-grid">
                    <!-- Function: emergencyWithdraw -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-hand-holding-dollar"></i> emergencyWithdraw</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Safely withdraw prize liquidity tokens for rebalancing.</p>
                            
                            <div class="form-group">
                                <label class="form-label">Token Address</label>
                                <input type="text" id="jackpotVaultToken" class="form-input" placeholder="0x..." value="{{ $contracts['usdtTokenAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Destination Address</label>
                                <input type="text" id="jackpotVaultTo" class="form-input" placeholder="0x..." value="{{ $contracts['adminOwnerAddress'] }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Amount</label>
                                <input type="number" id="jackpotVaultAmount" class="form-input" placeholder="0.0">
                            </div>
                        </div>

                        <button type="button" class="btn-execute rose" onclick="executeJackpotVaultEmergencyWithdraw()">
                            <i class="fa-solid fa-arrow-down-to-bracket"></i> Execute Emergency Withdrawal
                        </button>
                    </div>

                    <!-- Function: setBackendSigner -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-key"></i> setBackendSigner</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Update the cryptographic backend signer that authorizes prize claims.</p>
                            
                            <div class="form-group">
                                <label class="form-label">New Backend Signer Address</label>
                                <input type="text" id="jackpotVaultNewSigner" class="form-input" placeholder="0x..." value="{{ $contracts['backendSignerAddress'] }}">
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeJackpotVaultSetBackendSigner()">
                            <i class="fa-solid fa-shield-halved"></i> Update Vault Signer
                        </button>
                    </div>

                    <!-- Function: setPaused -->
                    <div class="action-card">
                        <div>
                            <div class="action-card-header">
                                <span class="action-card-title"><i class="fa-solid fa-pause"></i> setPaused</span>
                                <span class="badge-pill badge-v2">Only Owner</span>
                            </div>
                            <p class="action-card-desc">Pause or resume jackpot prize claims.</p>
                            
                            <div class="form-group">
                                <select id="jackpotVaultPauseSelect" class="form-input">
                                    <option value="false">Active / Unpaused (false)</option>
                                    <option value="true">Paused (true)</option>
                                </select>
                            </div>
                        </div>

                        <button type="button" class="btn-execute" onclick="executeJackpotVaultSetPaused()">
                            <i class="fa-solid fa-shield"></i> Update Paused State
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- CONTRACT 6: PANCAKESWAP V2 POOL                           -->
        <!-- ========================================================== -->
        <section id="sec-pool" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            PancakeSwap V2 CAAI / USDT Liquidity Pool
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-chart-line"></i> Verified Pair</span>
                        </div>
                        <div class="section-subtitle">
                            {{ $contracts['pancakePairAddress'] }}
                            <a href="https://dexscreener.com/bsc/{{ $contracts['pancakePairAddress'] }}" target="_blank" style="color: var(--primary); margin-left: 8px;">
                                DexScreener Chart <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="btn-nav-action" onclick="copyToClipboard('{{ $contracts['pancakePairAddress'] }}')">
                    <i class="fa-regular fa-copy"></i> Copy Address
                </button>
            </div>

            <div class="section-body">
                <div class="metrics-grid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">Live CAAI Price</div>
                        <div class="metric-pill-val" id="poolLivePrice" style="color: #06b6d4;">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">CAAI Reserve</div>
                        <div class="metric-pill-val" id="poolCaiReserve">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">USDT Reserve</div>
                        <div class="metric-pill-val" id="poolUsdtReserve">Loading...</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Total Pool Liquidity</div>
                        <div class="metric-pill-val" id="poolTotalLiquidity">Loading...</div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="https://pancakeswap.finance/swap?outputCurrency={{ $contracts['caiTokenAddress'] }}&inputCurrency={{ $contracts['usdtTokenAddress'] }}" target="_blank" class="btn-nav-action primary" style="padding: 10px 18px;">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i> Trade on PancakeSwap
                    </a>
                    <a href="https://bscscan.com/address/{{ $contracts['pancakePairAddress'] }}" target="_blank" class="btn-nav-action" style="padding: 10px 18px;">
                        <i class="fa-solid fa-magnifying-glass"></i> Pair on BscScan
                    </a>
                    <a href="https://dexscreener.com/bsc/{{ $contracts['pancakePairAddress'] }}" target="_blank" class="btn-nav-action" style="padding: 10px 18px;">
                        <i class="fa-solid fa-chart-line"></i> DexScreener Live Chart
                    </a>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- SECTION 7: MULTI-WALLET ON-CHAIN INSPECTOR                 -->
        <!-- ========================================================== -->
        <section id="sec-inspector" class="contract-section">
            <div class="section-header">
                <div class="section-title-cluster">
                    <div class="section-icon" style="background: linear-gradient(135deg, #6366f1, #4338ca);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <div>
                        <div class="section-title">
                            Multi-Wallet On-Chain Inspector Tool
                            <span class="badge-pill badge-v2"><i class="fa-solid fa-bolt"></i> Real-Time BSC Node</span>
                        </div>
                        <div class="section-subtitle">
                            Inspect live BNB, USDT, CAAI balances and contract spending allowances for any address on BSC Mainnet.
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                    <input type="text" id="inspectorInputAddr" class="form-input" placeholder="Enter any BSC address (0x...)" style="flex: 1; min-width: 300px;" value="{{ $contracts['adminOwnerAddress'] }}">
                    <button type="button" class="btn-nav-action primary" onclick="runWalletInspection()" style="padding: 0 20px; height: 40px;">
                        <i class="fa-solid fa-magnifying-glass"></i> Query Live Node
                    </button>
                    <button type="button" class="btn-nav-action" onclick="fillInput('inspectorInputAddr', '{{ $contracts['adminOwnerAddress'] }}'); runWalletInspection();">
                        Owner Wallet
                    </button>
                    <button type="button" class="btn-nav-action" onclick="fillInput('inspectorInputAddr', '{{ $contracts['backendSignerAddress'] }}'); runWalletInspection();">
                        Backend Signer
                    </button>
                </div>

                <div class="metrics-grid" id="inspectorResultGrid">
                    <div class="metric-pill">
                        <div class="metric-pill-label">BNB Gas Balance</div>
                        <div class="metric-pill-val" id="inspBnbBal">-</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">USDT Token Balance</div>
                        <div class="metric-pill-val" id="inspUsdtBal">-</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">CAAI Token Balance</div>
                        <div class="metric-pill-val" id="inspCaiBal">-</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Allowance -> Mining Engine</div>
                        <div class="metric-pill-val" id="inspEngineAllow">-</div>
                    </div>
                    <div class="metric-pill">
                        <div class="metric-pill-label">Allowance -> Jackpot Deposit</div>
                        <div class="metric-pill-val" id="inspJackpotAllow">-</div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Application Script & Web3 Functions -->
    <script>
        const CONFIG = {
            caiToken: "{{ $contracts['caiTokenAddress'] }}",
            pancakePair: "{{ $contracts['pancakePairAddress'] }}",
            miningEngine: "{{ $contracts['miningEngineAddress'] }}",
            revenueSplitter: "{{ $contracts['revenueSplitterAddress'] }}",
            usdtVault: "{{ $contracts['usdtWithdrawalVaultAddress'] }}",
            jackpotDeposit: "{{ $contracts['royalJackpotDepositAddress'] }}",
            jackpotVault: "{{ $contracts['royalJackpotVaultAddress'] }}",
            usdtToken: "{{ $contracts['usdtTokenAddress'] }}",
            adminOwner: "{{ $contracts['adminOwnerAddress'] }}",
            backendSigner: "{{ $contracts['backendSignerAddress'] }}",
            rpcUrl: "{{ $contracts['bscMainnetRpc'] }}",
            chainId: 56
        };

        const TARGET_OWNER = CONFIG.adminOwner.toLowerCase();

        // Standard Minimal ABIs
        const ERC20_ABI = [
            "function balanceOf(address account) view returns (uint256)",
            "function allowance(address owner, address spender) view returns (uint256)",
            "function decimals() view returns (uint8)",
            "function symbol() view returns (string)",
            "function totalSupply() view returns (uint256)"
        ];

        const PAIR_ABI = [
            "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
            "function token0() view returns (address)",
            "function token1() view returns (address)"
        ];

        const MINING_ENGINE_ABI = [
            "function totalInvestedUsdt() view returns (uint256)",
            "function totalCaiPurchased() view returns (uint256)",
            "function totalRoiUsdtPaid() view returns (uint256)",
            "function totalVaultFundedUsdt() view returns (uint256)",
            "function totalSplitterFundedUsdt() view returns (uint256)",
            "function rescueTokens(address token, address to, uint256 amount) external",
            "function setWithdrawalVault(address _newVault) external",
            "function setRevenueSplitter(address _newSplitter) external",
            "function setBackendSigner(address _newSigner) external",
            "function transferOwnership(address newOwner) external"
        ];

        const SPLITTER_ABI = [
            "function totalDistributedUsdt() view returns (uint256)",
            "function totalTradingPaid() view returns (uint256)",
            "function totalPartnersPaid() view returns (uint256)",
            "function totalReservePaid() view returns (uint256)",
            "function distribute() external returns (uint256, uint256, uint256)",
            "function setTradingWallet(address _newTrading) external",
            "function setReserveWallet(address _newReserve) external",
            "function setPartnerWallet(uint8 index, address _newPartner) external",
            "function rescueTokens(address token, address to, uint256 amount) external"
        ];

        const WITHDRAWAL_VAULT_ABI = [
            "function backendSigner() view returns (address)",
            "function paused() view returns (bool)",
            "function emergencyWithdraw(address to, uint256 amount) external",
            "function setBackendSigner(address _newSigner) external",
            "function pause() external",
            "function unpause() external",
            "function transferOwnership(address newOwner) external"
        ];

        const JACKPOT_DEP_ABI = [
            "function totalDepositsCount() view returns (uint256)",
            "function totalDepositedUsdt() view returns (uint256)",
            "function whitelistedCollector() view returns (address)",
            "function autoForward() view returns (bool)",
            "function paused() view returns (bool)",
            "function sweepAllToCollector() external",
            "function withdrawCollectedFunds(address recipient, uint256 amount) external",
            "function setWhitelistedCollector(address _newCollector) external",
            "function setAutoForward(bool _autoForward) external",
            "function setPaused(bool _paused) external"
        ];

        const JACKPOT_VAULT_ABI = [
            "function backendSigner() view returns (address)",
            "function totalPrizesPaid() view returns (uint256)",
            "function totalClaimsCount() view returns (uint256)",
            "function paused() view returns (bool)",
            "function emergencyWithdraw(address token, address to, uint256 amount) external",
            "function setBackendSigner(address _newSigner) external",
            "function setPaused(bool _paused) external"
        ];

        let rpcProvider = null;
        let isVaultCurrentlyPaused = false;

        function getRpc() {
            if (!rpcProvider) {
                rpcProvider = new ethers.JsonRpcProvider(CONFIG.rpcUrl);
            }
            return rpcProvider;
        }

        // Quick Input Filler Helper
        function fillInput(id, val) {
            const el = document.getElementById(id);
            if (el) el.value = val;
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('Copied to clipboard: ' + text.substring(0, 10) + '...', 'info');
        }

        // Toast Helper
        function showToast(msg, type = 'info', txHash = null) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-box toast-${type}`;

            let icon = 'fa-circle-info';
            if (type === 'success') icon = 'fa-circle-check';
            if (type === 'error') icon = 'fa-circle-exclamation';

            let hashHtml = '';
            if (txHash) {
                hashHtml = `<a href="https://bscscan.com/tx/${txHash}" target="_blank" class="toast-link">View Transaction on BscScan <i class="fa-solid fa-arrow-up-right-from-square"></i></a>`;
            }

            toast.innerHTML = `
                <i class="fa-solid ${icon} toast-icon"></i>
                <div class="toast-content">
                    <div>${msg}</div>
                    ${hashHtml}
                </div>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 6000);
        }

        // Get Connected MetaMask Signer with Owner Verification
        async function getVerifiedSigner() {
            if (typeof window.ethereum === 'undefined') {
                showToast('MetaMask not detected in browser.', 'error');
                throw new Error('MetaMask not found');
            }

            const browserProvider = new ethers.BrowserProvider(window.ethereum);
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            if (!accounts || accounts.length === 0) {
                showToast('No Web3 account selected.', 'error');
                throw new Error('No account');
            }

            const activeAccount = accounts[0].toLowerCase();
            if (activeAccount !== TARGET_OWNER) {
                const shortAddr = activeAccount.substring(0, 6) + '...' + activeAccount.substring(activeAccount.length - 4);
                showToast(`Access Denied: Connected wallet (${shortAddr}) is NOT the Owner Wallet. Switch to 0xB68e...3622 in MetaMask.`, 'error');
                throw new Error('Unauthorized wallet');
            }

            return await browserProvider.getSigner();
        }

        // ============================================================
        // 1. REFRESH ALL LIVE ON-CHAIN STATS
        // ============================================================
        async function refreshAllOnChainStats() {
            const refreshIcon = document.getElementById('refreshIcon');
            if (refreshIcon) refreshIcon.classList.add('fa-spin');

            const rpc = getRpc();
            const usdt = new ethers.Contract(CONFIG.usdtToken, ERC20_ABI, rpc);
            const cai = new ethers.Contract(CONFIG.caiToken, ERC20_ABI, rpc);
            const pair = new ethers.Contract(CONFIG.pancakePair, PAIR_ABI, rpc);

            const engine = new ethers.Contract(CONFIG.miningEngine, MINING_ENGINE_ABI, rpc);
            const splitter = new ethers.Contract(CONFIG.revenueSplitter, SPLITTER_ABI, rpc);
            const vault = new ethers.Contract(CONFIG.usdtVault, WITHDRAWAL_VAULT_ABI, rpc);
            const jackpotDep = new ethers.Contract(CONFIG.jackpotDeposit, JACKPOT_DEP_ABI, rpc);
            const jackpotVault = new ethers.Contract(CONFIG.jackpotVault, JACKPOT_VAULT_ABI, rpc);

            try {
                // Nav Owner Balances
                const [ownerBnb, ownerUsdt] = await Promise.all([
                    rpc.getBalance(CONFIG.adminOwner).catch(() => 0n),
                    usdt.balanceOf(CONFIG.adminOwner).catch(() => 0n)
                ]);
                document.getElementById('navBnbText').innerText = parseFloat(ethers.formatEther(ownerBnb)).toFixed(4) + ' BNB';
                document.getElementById('navUsdtText').innerText = '$' + parseFloat(ethers.formatUnits(ownerUsdt, 18)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' USDT';

                // 1. Mining Engine Stats
                const [engineCaiBal, engineInvested, enginePurchased, engineRoiPaid, engineVaultFund, engineSplitFund] = await Promise.all([
                    cai.balanceOf(CONFIG.miningEngine).catch(() => 0n),
                    engine.totalInvestedUsdt().catch(() => 0n),
                    engine.totalCaiPurchased().catch(() => 0n),
                    engine.totalRoiUsdtPaid().catch(() => 0n),
                    engine.totalVaultFundedUsdt().catch(() => 0n),
                    engine.totalSplitterFundedUsdt().catch(() => 0n),
                ]);
                document.getElementById('engineBufferCaai').innerText = parseFloat(ethers.formatUnits(engineCaiBal, 18)).toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' CAAI';
                document.getElementById('engineTotalInvested').innerText = '$' + parseFloat(ethers.formatUnits(engineInvested, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('engineTotalCaiPurchased').innerText = parseFloat(ethers.formatUnits(enginePurchased, 18)).toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' CAAI';
                document.getElementById('engineTotalRoiPaid').innerText = '$' + parseFloat(ethers.formatUnits(engineRoiPaid, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('engineVaultFunded').innerText = '$' + parseFloat(ethers.formatUnits(engineVaultFund, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('engineSplitterFunded').innerText = '$' + parseFloat(ethers.formatUnits(engineSplitFund, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });

                // 2. Revenue Splitter Stats
                const [splitterBal, splitterTotal, splitterTrading, splitterPartners, splitterReserve] = await Promise.all([
                    usdt.balanceOf(CONFIG.revenueSplitter).catch(() => 0n),
                    splitter.totalDistributedUsdt().catch(() => 0n),
                    splitter.totalTradingPaid().catch(() => 0n),
                    splitter.totalPartnersPaid().catch(() => 0n),
                    splitter.totalReservePaid().catch(() => 0n)
                ]);
                document.getElementById('splitterHeldUsdt').innerText = '$' + parseFloat(ethers.formatUnits(splitterBal, 18)).toFixed(2) + ' USDT';
                document.getElementById('splitterTotalDistributed').innerText = '$' + parseFloat(ethers.formatUnits(splitterTotal, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('splitterTradingPaid').innerText = '$' + parseFloat(ethers.formatUnits(splitterTrading, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('splitterPartnersPaid').innerText = '$' + parseFloat(ethers.formatUnits(splitterPartners, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('splitterReservePaid').innerText = '$' + parseFloat(ethers.formatUnits(splitterReserve, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });

                // 3. Withdrawal Vault Stats
                const [vaultBal, vaultSigner, vaultPaused] = await Promise.all([
                    usdt.balanceOf(CONFIG.usdtVault).catch(() => 0n),
                    vault.backendSigner().catch(() => 'Error'),
                    vault.paused().catch(() => false)
                ]);
                document.getElementById('vaultHeldUsdt').innerText = '$' + parseFloat(ethers.formatUnits(vaultBal, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' USDT';
                document.getElementById('vaultSignerAddr').innerText = vaultSigner.substring(0, 8) + '...' + vaultSigner.substring(vaultSigner.length - 6);
                isVaultCurrentlyPaused = vaultPaused;
                document.getElementById('vaultPausedStatus').innerText = vaultPaused ? 'Paused (Halted)' : 'Active (Healthy)';
                document.getElementById('vaultPausedStatus').style.color = vaultPaused ? '#ef4444' : '#10b981';
                document.getElementById('vaultPauseStateDisplay').innerText = `Current Status: ${vaultPaused ? '⚠️ PAUSED (Withdrawals Halted)' : '✅ ACTIVE (Withdrawals Open)'}`;
                document.getElementById('btnToggleVaultPause').innerHTML = vaultPaused ? '<i class="fa-solid fa-play"></i> Unpause Vault' : '<i class="fa-solid fa-pause"></i> Pause Vault';

                // 4. Jackpot Deposit Stats
                const [jpDepBal, jpCollector, jpAutoFwd, jpTotalCount, jpTotalAmt] = await Promise.all([
                    usdt.balanceOf(CONFIG.jackpotDeposit).catch(() => 0n),
                    jackpotDep.whitelistedCollector().catch(() => 'Error'),
                    jackpotDep.autoForward().catch(() => false),
                    jackpotDep.totalDepositsCount().catch(() => 0n),
                    jackpotDep.totalDepositedUsdt().catch(() => 0n)
                ]);
                document.getElementById('jackpotDepHeldUsdt').innerText = '$' + parseFloat(ethers.formatUnits(jpDepBal, 18)).toFixed(2) + ' USDT';
                document.getElementById('jackpotCollectorAddr').innerText = jpCollector.substring(0, 8) + '...' + jpCollector.substring(jpCollector.length - 6);
                document.getElementById('jackpotAutoForwardStatus').innerText = jpAutoFwd ? 'Enabled (Instant Sweep)' : 'Disabled';
                document.getElementById('jackpotAutoForwardStatus').style.color = jpAutoFwd ? '#10b981' : '#f59e0b';
                document.getElementById('jackpotTotalCount').innerText = jpTotalCount.toString() + ' Deposits';
                document.getElementById('jackpotTotalDeposited').innerText = '$' + parseFloat(ethers.formatUnits(jpTotalAmt, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });

                // 5. Jackpot Vault Stats
                const [jpVaultBal, jpVaultSigner, jpPrizesPaid, jpClaimsCount] = await Promise.all([
                    usdt.balanceOf(CONFIG.jackpotVault).catch(() => 0n),
                    jackpotVault.backendSigner().catch(() => 'Error'),
                    jackpotVault.totalPrizesPaid().catch(() => 0n),
                    jackpotVault.totalClaimsCount().catch(() => 0n)
                ]);
                document.getElementById('jackpotVaultHeldUsdt').innerText = '$' + parseFloat(ethers.formatUnits(jpVaultBal, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' USDT';
                document.getElementById('jackpotVaultSignerAddr').innerText = jpVaultSigner.substring(0, 8) + '...' + jpVaultSigner.substring(jpVaultSigner.length - 6);
                document.getElementById('jackpotTotalPrizesPaid').innerText = '$' + parseFloat(ethers.formatUnits(jpPrizesPaid, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 });
                document.getElementById('jackpotTotalClaimsCount').innerText = jpClaimsCount.toString() + ' Claims';

                // 6. PancakeSwap V2 Pool Reserves & Price
                const [reserves, token0] = await Promise.all([
                    pair.getReserves().catch(() => [0n, 0n]),
                    pair.token0().catch(() => '')
                ]);
                let rCai = 0, rUsdt = 0;
                if (token0.toLowerCase() === CONFIG.caiToken.toLowerCase()) {
                    rCai = parseFloat(ethers.formatUnits(reserves[0], 18));
                    rUsdt = parseFloat(ethers.formatUnits(reserves[1], 18));
                } else {
                    rUsdt = parseFloat(ethers.formatUnits(reserves[0], 18));
                    rCai = parseFloat(ethers.formatUnits(reserves[1], 18));
                }

                if (rCai > 0) {
                    const price = rUsdt / rCai;
                    const totalLiq = rUsdt * 2;
                    document.getElementById('poolLivePrice').innerText = '$' + price.toFixed(4);
                    document.getElementById('poolCaiReserve').innerText = rCai.toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' CAAI';
                    document.getElementById('poolUsdtReserve').innerText = '$' + rUsdt.toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' USDT';
                    document.getElementById('poolTotalLiquidity').innerText = '~$' + totalLiq.toLocaleString('en-US', { maximumFractionDigits: 2 });
                }

                showToast('All on-chain metrics synced with BSC Mainnet.', 'success');
            } catch (err) {
                console.error(err);
                showToast('Error syncing on-chain stats: ' + err.message, 'error');
            } finally {
                if (refreshIcon) refreshIcon.classList.remove('fa-spin');
            }
        }

        // ============================================================
        // 2. EXECUTIONS: CYERA MINING ENGINE
        // ============================================================
        async function executeEngineRescueTokens() {
            try {
                const token = document.getElementById('engineRescueToken').value.trim();
                const to = document.getElementById('engineRescueTo').value.trim();
                const amt = parseFloat(document.getElementById('engineRescueAmount').value) || 0;

                if (!ethers.isAddress(token) || !ethers.isAddress(to) || amt <= 0) {
                    return showToast('Please enter valid token, destination address and amount.', 'error');
                }

                const signer = await getVerifiedSigner();
                const engine = new ethers.Contract(CONFIG.miningEngine, MINING_ENGINE_ABI, signer);
                const amtWei = ethers.parseUnits(amt.toString(), 18);

                showToast('Confirm rescueTokens transaction in MetaMask...', 'info');
                const tx = await engine.rescueTokens(token, to, amtWei, { gasLimit: 250000 });
                showToast('Transaction submitted! Awaiting confirmation...', 'info', tx.hash);
                await tx.wait();
                showToast('Tokens successfully rescued!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Execution failed', 'error');
            }
        }

        async function executeEngineSetWithdrawalVault() {
            try {
                const newVault = document.getElementById('engineNewVault').value.trim();
                if (!ethers.isAddress(newVault)) return showToast('Invalid vault address.', 'error');

                const signer = await getVerifiedSigner();
                const engine = new ethers.Contract(CONFIG.miningEngine, MINING_ENGINE_ABI, signer);

                showToast('Confirm setWithdrawalVault in MetaMask...', 'info');
                const tx = await engine.setWithdrawalVault(newVault, { gasLimit: 150000 });
                showToast('Updating withdrawal vault...', 'info', tx.hash);
                await tx.wait();
                showToast('Withdrawal vault updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeEngineSetRevenueSplitter() {
            try {
                const newSplitter = document.getElementById('engineNewSplitter').value.trim();
                if (!ethers.isAddress(newSplitter)) return showToast('Invalid splitter address.', 'error');

                const signer = await getVerifiedSigner();
                const engine = new ethers.Contract(CONFIG.miningEngine, MINING_ENGINE_ABI, signer);

                showToast('Confirm setRevenueSplitter in MetaMask...', 'info');
                const tx = await engine.setRevenueSplitter(newSplitter, { gasLimit: 150000 });
                showToast('Updating revenue splitter...', 'info', tx.hash);
                await tx.wait();
                showToast('Revenue splitter updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeEngineSetBackendSigner() {
            try {
                const newSigner = document.getElementById('engineNewSigner').value.trim();
                if (!ethers.isAddress(newSigner)) return showToast('Invalid signer address.', 'error');

                const signer = await getVerifiedSigner();
                const engine = new ethers.Contract(CONFIG.miningEngine, MINING_ENGINE_ABI, signer);

                showToast('Confirm setBackendSigner in MetaMask...', 'info');
                const tx = await engine.setBackendSigner(newSigner, { gasLimit: 150000 });
                showToast('Updating backend signer...', 'info', tx.hash);
                await tx.wait();
                showToast('Backend signer updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        // ============================================================
        // 3. EXECUTIONS: REVENUE SPLITTER
        // ============================================================
        async function executeSplitterDistribute() {
            try {
                const signer = await getVerifiedSigner();
                const splitter = new ethers.Contract(CONFIG.revenueSplitter, SPLITTER_ABI, signer);

                showToast('Confirm distribute() transaction in MetaMask...', 'info');
                const tx = await splitter.distribute({ gasLimit: 450000 });
                showToast('Revenue distribution broadcasted...', 'info', tx.hash);
                await tx.wait();
                showToast('Instant 68/20/10/2 distribution completed successfully!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Distribution failed (check if contract holds USDT)', 'error');
            }
        }

        async function executeSplitterSetTradingWallet() {
            try {
                const newTrading = document.getElementById('splitterNewTrading').value.trim();
                if (!ethers.isAddress(newTrading)) return showToast('Invalid trading wallet address.', 'error');

                const signer = await getVerifiedSigner();
                const splitter = new ethers.Contract(CONFIG.revenueSplitter, SPLITTER_ABI, signer);

                showToast('Confirm setTradingWallet in MetaMask...', 'info');
                const tx = await splitter.setTradingWallet(newTrading, { gasLimit: 150000 });
                showToast('Updating trading wallet...', 'info', tx.hash);
                await tx.wait();
                showToast('Trading wallet updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeSplitterSetReserveWallet() {
            try {
                const newReserve = document.getElementById('splitterNewReserve').value.trim();
                if (!ethers.isAddress(newReserve)) return showToast('Invalid reserve wallet address.', 'error');

                const signer = await getVerifiedSigner();
                const splitter = new ethers.Contract(CONFIG.revenueSplitter, SPLITTER_ABI, signer);

                showToast('Confirm setReserveWallet in MetaMask...', 'info');
                const tx = await splitter.setReserveWallet(newReserve, { gasLimit: 150000 });
                showToast('Updating reserve wallet...', 'info', tx.hash);
                await tx.wait();
                showToast('Reserve wallet updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeSplitterSetPartnerWallet() {
            try {
                const index = parseInt(document.getElementById('splitterPartnerIndex').value);
                const addr = document.getElementById('splitterNewPartnerAddr').value.trim();
                if (!ethers.isAddress(addr)) return showToast('Invalid partner wallet address.', 'error');

                const signer = await getVerifiedSigner();
                const splitter = new ethers.Contract(CONFIG.revenueSplitter, SPLITTER_ABI, signer);

                showToast(`Confirm setPartnerWallet(Index: ${index}) in MetaMask...`, 'info');
                const tx = await splitter.setPartnerWallet(index, addr, { gasLimit: 150000 });
                showToast('Updating partner wallet...', 'info', tx.hash);
                await tx.wait();
                showToast(`Partner ${index + 1} wallet updated!`, 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeSplitterRescueTokens() {
            try {
                const token = document.getElementById('splitterRescueToken').value.trim();
                const to = document.getElementById('splitterRescueTo').value.trim();
                const amt = parseFloat(document.getElementById('splitterRescueAmount').value) || 0;

                if (!ethers.isAddress(token) || !ethers.isAddress(to) || amt <= 0) {
                    return showToast('Invalid inputs.', 'error');
                }

                const signer = await getVerifiedSigner();
                const splitter = new ethers.Contract(CONFIG.revenueSplitter, SPLITTER_ABI, signer);
                const amtWei = ethers.parseUnits(amt.toString(), 18);

                showToast('Confirm rescueTokens in MetaMask...', 'info');
                const tx = await splitter.rescueTokens(token, to, amtWei, { gasLimit: 200000 });
                await tx.wait();
                showToast('Tokens rescued successfully!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        // ============================================================
        // 4. EXECUTIONS: USDT WITHDRAWAL VAULT
        // ============================================================
        async function executeVaultEmergencyWithdraw() {
            try {
                const to = document.getElementById('vaultEmergencyTo').value.trim();
                const amt = parseFloat(document.getElementById('vaultEmergencyAmount').value) || 0;
                if (!ethers.isAddress(to) || amt <= 0) return showToast('Invalid destination or amount.', 'error');

                const signer = await getVerifiedSigner();
                const vault = new ethers.Contract(CONFIG.usdtVault, WITHDRAWAL_VAULT_ABI, signer);
                const amtWei = ethers.parseUnits(amt.toString(), 18);

                showToast('Confirm emergencyWithdraw in MetaMask...', 'info');
                const tx = await vault.emergencyWithdraw(to, amtWei, { gasLimit: 250000 });
                showToast('Submitting emergency withdrawal...', 'info', tx.hash);
                await tx.wait();
                showToast('Emergency withdrawal confirmed!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeVaultSetBackendSigner() {
            try {
                const newSigner = document.getElementById('vaultNewSigner').value.trim();
                if (!ethers.isAddress(newSigner)) return showToast('Invalid signer address.', 'error');

                const signer = await getVerifiedSigner();
                const vault = new ethers.Contract(CONFIG.usdtVault, WITHDRAWAL_VAULT_ABI, signer);

                showToast('Confirm setBackendSigner in MetaMask...', 'info');
                const tx = await vault.setBackendSigner(newSigner, { gasLimit: 150000 });
                await tx.wait();
                showToast('Withdrawal vault signer updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeVaultTogglePause() {
            try {
                const signer = await getVerifiedSigner();
                const vault = new ethers.Contract(CONFIG.usdtVault, WITHDRAWAL_VAULT_ABI, signer);

                const doPause = !isVaultCurrentlyPaused;
                showToast(`Confirm ${doPause ? 'pause()' : 'unpause()'} in MetaMask...`, 'info');
                const tx = doPause ? await vault.pause({ gasLimit: 150000 }) : await vault.unpause({ gasLimit: 150000 });
                await tx.wait();
                showToast(`Vault ${doPause ? 'paused' : 'unpaused'} successfully!`, 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        // ============================================================
        // 5. EXECUTIONS: ROYAL JACKPOT DEPOSIT
        // ============================================================
        async function executeJackpotSweepAll() {
            try {
                const signer = await getVerifiedSigner();
                const dep = new ethers.Contract(CONFIG.jackpotDeposit, JACKPOT_DEP_ABI, signer);

                showToast('Confirm sweepAllToCollector in MetaMask...', 'info');
                const tx = await dep.sweepAllToCollector({ gasLimit: 200000 });
                await tx.wait();
                showToast('Jackpot USDT swept to collector successfully!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed (check if balance > 0)', 'error');
            }
        }

        async function executeJackpotSetCollector() {
            try {
                const newCollector = document.getElementById('jackpotNewCollector').value.trim();
                if (!ethers.isAddress(newCollector)) return showToast('Invalid collector address.', 'error');

                const signer = await getVerifiedSigner();
                const dep = new ethers.Contract(CONFIG.jackpotDeposit, JACKPOT_DEP_ABI, signer);

                showToast('Confirm setWhitelistedCollector in MetaMask...', 'info');
                const tx = await dep.setWhitelistedCollector(newCollector, { gasLimit: 150000 });
                await tx.wait();
                showToast('Collector wallet updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeJackpotSetAutoForward() {
            try {
                const autoFwd = document.getElementById('jackpotAutoFwdSelect').value === 'true';
                const signer = await getVerifiedSigner();
                const dep = new ethers.Contract(CONFIG.jackpotDeposit, JACKPOT_DEP_ABI, signer);

                showToast('Confirm setAutoForward in MetaMask...', 'info');
                const tx = await dep.setAutoForward(autoFwd, { gasLimit: 150000 });
                await tx.wait();
                showToast('Auto-forward state updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeJackpotSetPaused() {
            try {
                const paused = document.getElementById('jackpotPausedSelect').value === 'true';
                const signer = await getVerifiedSigner();
                const dep = new ethers.Contract(CONFIG.jackpotDeposit, JACKPOT_DEP_ABI, signer);

                showToast('Confirm setPaused in MetaMask...', 'info');
                const tx = await dep.setPaused(paused, { gasLimit: 150000 });
                await tx.wait();
                showToast('Jackpot deposit paused state updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        // ============================================================
        // 6. EXECUTIONS: ROYAL JACKPOT VAULT
        // ============================================================
        async function executeJackpotVaultEmergencyWithdraw() {
            try {
                const token = document.getElementById('jackpotVaultToken').value.trim();
                const to = document.getElementById('jackpotVaultTo').value.trim();
                const amt = parseFloat(document.getElementById('jackpotVaultAmount').value) || 0;
                if (!ethers.isAddress(token) || !ethers.isAddress(to) || amt <= 0) return showToast('Invalid inputs.', 'error');

                const signer = await getVerifiedSigner();
                const jVault = new ethers.Contract(CONFIG.jackpotVault, JACKPOT_VAULT_ABI, signer);
                const amtWei = ethers.parseUnits(amt.toString(), 18);

                showToast('Confirm emergencyWithdraw in MetaMask...', 'info');
                const tx = await jVault.emergencyWithdraw(token, to, amtWei, { gasLimit: 250000 });
                await tx.wait();
                showToast('Jackpot vault emergency withdrawal confirmed!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeJackpotVaultSetBackendSigner() {
            try {
                const newSigner = document.getElementById('jackpotVaultNewSigner').value.trim();
                if (!ethers.isAddress(newSigner)) return showToast('Invalid signer address.', 'error');

                const signer = await getVerifiedSigner();
                const jVault = new ethers.Contract(CONFIG.jackpotVault, JACKPOT_VAULT_ABI, signer);

                showToast('Confirm setBackendSigner in MetaMask...', 'info');
                const tx = await jVault.setBackendSigner(newSigner, { gasLimit: 150000 });
                await tx.wait();
                showToast('Jackpot vault signer updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        async function executeJackpotVaultSetPaused() {
            try {
                const paused = document.getElementById('jackpotVaultPauseSelect').value === 'true';
                const signer = await getVerifiedSigner();
                const jVault = new ethers.Contract(CONFIG.jackpotVault, JACKPOT_VAULT_ABI, signer);

                showToast('Confirm setPaused in MetaMask...', 'info');
                const tx = await jVault.setPaused(paused, { gasLimit: 150000 });
                await tx.wait();
                showToast('Jackpot prize claims paused state updated!', 'success', tx.hash);
                refreshAllOnChainStats();
            } catch (e) {
                showToast(e.reason || e.message || 'Failed', 'error');
            }
        }

        // ============================================================
        // 7. MULTI-WALLET ON-CHAIN INSPECTOR TOOL
        // ============================================================
        async function runWalletInspection() {
            const addr = document.getElementById('inspectorInputAddr').value.trim();
            if (!ethers.isAddress(addr)) {
                return showToast('Please enter a valid BSC wallet address.', 'error');
            }

            const rpc = getRpc();
            const usdt = new ethers.Contract(CONFIG.usdtToken, ERC20_ABI, rpc);
            const cai = new ethers.Contract(CONFIG.caiToken, ERC20_ABI, rpc);

            document.getElementById('inspBnbBal').innerText = 'Querying...';
            document.getElementById('inspUsdtBal').innerText = 'Querying...';
            document.getElementById('inspCaiBal').innerText = 'Querying...';
            document.getElementById('inspEngineAllow').innerText = 'Querying...';
            document.getElementById('inspJackpotAllow').innerText = 'Querying...';

            try {
                const [bnbBal, usdtBal, caiBal, allowEngine, allowJackpot] = await Promise.all([
                    rpc.getBalance(addr).catch(() => 0n),
                    usdt.balanceOf(addr).catch(() => 0n),
                    cai.balanceOf(addr).catch(() => 0n),
                    usdt.allowance(addr, CONFIG.miningEngine).catch(() => 0n),
                    usdt.allowance(addr, CONFIG.jackpotDeposit).catch(() => 0n)
                ]);

                document.getElementById('inspBnbBal').innerText = parseFloat(ethers.formatEther(bnbBal)).toFixed(4) + ' BNB';
                document.getElementById('inspUsdtBal').innerText = '$' + parseFloat(ethers.formatUnits(usdtBal, 18)).toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' USDT';
                document.getElementById('inspCaiBal').innerText = parseFloat(ethers.formatUnits(caiBal, 18)).toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' CAAI';
                document.getElementById('inspEngineAllow').innerText = '$' + parseFloat(ethers.formatUnits(allowEngine, 18)).toLocaleString('en-US', { maximumFractionDigits: 2 });
                document.getElementById('inspJackpotAllow').innerText = '$' + parseFloat(ethers.formatUnits(allowJackpot, 18)).toLocaleString('en-US', { maximumFractionDigits: 2 });

                showToast('Wallet inspection completed for ' + addr.substring(0, 6) + '...' + addr.substring(addr.length - 4), 'success');
            } catch (e) {
                showToast('Inspection query failed: ' + e.message, 'error');
            }
        }

        // On Page Load: Pre-load all metrics
        window.addEventListener('load', () => {
            refreshAllOnChainStats();
            setTimeout(runWalletInspection, 800);
        });
    </script>
</body>
</html>
