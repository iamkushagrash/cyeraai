@php
    $isSubAdminUser = \App\Http\Controllers\ContractAdminController::isSubAdmin();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CYERA AI — Executive Governance & MLM Command Center</title>
    <meta name="description" content="Decentralized Smart Contract Owner & MLM Administrative Command Center for Cyera Protocol on BNB Smart Chain.">
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

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-badge {
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid var(--primary-border);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .brand-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .network-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--emerald-soft);
            border: 1px solid var(--emerald-border);
            color: var(--emerald-text);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .network-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--emerald);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .btn-wallet {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .btn-wallet:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-wallet.connected {
            background: #ffffff;
            color: var(--text-heading);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-subtle);
        }

        .auth-wallet-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-heading);
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            box-shadow: var(--shadow-subtle);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #ffffff;
            color: var(--rose-text);
            border: 1px solid var(--rose-border);
            border-radius: var(--radius-md);
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: var(--shadow-subtle);
        }

        .btn-logout:hover {
            background: var(--rose-soft);
            border-color: var(--rose);
            color: var(--rose);
            transform: translateY(-1px);
        }

        .btn-mobile-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-body);
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
        }

        /* Layout Container */
        .portal-layout {
            max-width: 1540px;
            margin: 0 auto;
            width: 100%;
            padding: 24px 20px 60px;
            flex: 1;
        }

        /* Navigation Tabs Bar */
        .tab-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            padding: 6px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-subtle);
            margin-bottom: 24px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .tab-bar::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .tab-btn i {
            font-size: 14px;
        }

        .tab-btn:hover {
            color: var(--text-heading);
            background: var(--bg-elevated);
        }

        .tab-btn.active {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
        }

        /* Metric Grid */
        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-subtle);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card);
            border-color: var(--border-hover);
        }

        .metric-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .metric-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .metric-icon-box {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .metric-icon-box.indigo { background: var(--primary-soft); color: var(--primary); }
        .metric-icon-box.emerald { background: var(--emerald-soft); color: var(--emerald); }
        .metric-icon-box.amber { background: var(--amber-soft); color: var(--amber); }
        .metric-icon-box.rose { background: var(--rose-soft); color: var(--rose); }
        .metric-icon-box.cyan { background: var(--cyan-soft); color: var(--cyan); }

        .metric-value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.02em;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .metric-sub {
            font-size: 0.76rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Section Cards */
        .portal-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-subtle);
            margin-bottom: 24px;
        }

        .portal-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-heading {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-heading i {
            color: var(--primary);
        }

        .card-caption {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 400;
            margin-top: 2px;
        }

        /* Filter Controls Bar */
        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 240px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 14px;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: #ffffff;
            font-size: 0.84rem;
            color: var(--text-heading);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .filter-select {
            padding: 10px 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: #ffffff;
            font-size: 0.84rem;
            color: var(--text-body);
            font-weight: 500;
            outline: none;
            cursor: pointer;
        }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: #ffffff;
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.82rem;
        }

        table.admin-table th {
            background: var(--bg-elevated);
            color: var(--text-muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        table.admin-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            color: var(--text-body);
            vertical-align: middle;
            white-space: nowrap;
        }

        table.admin-table tbody tr:hover {
            background-color: #f8fafc;
        }

        table.admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1;
        }

        .badge-active { background: var(--emerald-soft); color: var(--emerald-text); border: 1px solid var(--emerald-border); }
        .badge-inactive { background: var(--amber-soft); color: var(--amber-text); border: 1px solid var(--amber-border); }
        .badge-blocked { background: var(--rose-soft); color: var(--rose-text); border: 1px solid var(--rose-border); }
        .badge-indigo { background: var(--primary-soft); color: var(--primary); border: 1px solid var(--primary-border); }
        .badge-cyan { background: var(--cyan-soft); color: var(--cyan); border: 1px solid var(--cyan-border); }

        /* Buttons & Actions */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text-body);
            font-size: 0.74rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-action:hover {
            background: var(--bg-elevated);
            border-color: var(--border-hover);
            color: var(--text-heading);
        }

        .btn-action.primary {
            background: var(--primary-soft);
            color: var(--primary);
            border-color: var(--primary-border);
        }

        .btn-action.primary:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-action.danger {
            background: var(--rose-soft);
            color: var(--rose-text);
            border-color: var(--rose-border);
        }

        .btn-action.danger:hover {
            background: var(--rose);
            color: #ffffff;
        }

        /* Typography & Helpers */
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar-mini {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
        }

        /* Live Pool Box */
        .pool-banner {
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 50%, #06b6d4 100%);
            border-radius: var(--radius-lg);
            padding: 24px;
            color: #ffffff;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.3);
        }

        .pool-stats-row {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .pool-stat-col {
            border-left: 2px solid rgba(255, 255, 255, 0.2);
            padding-left: 16px;
        }

        .pool-stat-col:first-child {
            border-left: none;
            padding-left: 0;
        }

        .pool-stat-label {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }

        .pool-stat-num {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1.1;
            margin-top: 4px;
        }

        /* Contract Cards Grid */
        .contracts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }

        .contract-box {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 16px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.2s;
        }

        .contract-box:hover {
            border-color: var(--primary-border);
            box-shadow: var(--shadow-card);
        }

        .contract-box-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .contract-box-title {
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contract-addr-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-elevated);
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.76rem;
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-body);
            margin-bottom: 10px;
        }

        .contract-metrics-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.76rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            padding-top: 10px;
        }

        /* Interactive Genealogy Visual Tree Explorer */
        .tree-container {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            min-height: 500px;
            overflow-x: auto;
        }

        .tree-node-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 16px;
            width: 260px;
            box-shadow: var(--shadow-subtle);
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .tree-node-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-card);
            transform: translateY(-2px);
        }

        .tree-node-card.root-card {
            border: 2px solid var(--primary);
            background: #faf5ff;
        }

        .tree-level-block {
            margin-bottom: 24px;
        }

        .tree-level-header {
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tree-cards-row {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* Modal Dialog */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-float);
            border: 1px solid var(--border);
            animation: modalIn 0.2s ease-out;
        }

        @keyframes modalIn {
            from { transform: scale(0.96); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
        }

        /* Responsive Mobile Drawer */
        @media (max-width: 992px) {
            .btn-mobile-toggle {
                display: flex;
            }
            .header-actions .network-pill {
                display: none;
            }
            .pool-banner {
                flex-direction: column;
                align-items: flex-start;
            }
            .pool-stat-col {
                border-left: none;
                padding-left: 0;
            }
            .metric-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .portal-layout {
                padding: 16px 12px 60px;
            }
            .metric-grid {
                grid-template-columns: 1fr;
            }
            .tab-btn {
                padding: 8px 12px;
                font-size: 0.78rem;
            }
            .btn-wallet span {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Top Sticky Header -->
    <nav class="admin-nav">
        <div class="nav-inner">
            <div class="brand-cluster">
                <div class="brand-avatar">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <div>
                    <div class="brand-title">
                        CYERA AI <span class="brand-badge">Protocol V2</span>
                    </div>
                    <div class="brand-sub">MLM & Smart Contract Command Center</div>
                </div>
            </div>

            <div class="header-actions">
                <div class="network-pill">
                    <span class="network-dot"></span>
                    <span>BNB Smart Chain (56)</span>
                </div>

                @if(Session::has('contract_admin_wallet'))
                    @if($isSubAdminUser)
                    <div class="auth-wallet-pill" style="border-color: #fde68a; background: #fffbeb;" title="Subadmin Wallet: {{ Session::get('contract_admin_wallet') }}">
                        <i class="fa-solid fa-user-shield" style="color: #b45309;"></i>
                        <span style="font-weight: 700; color: #b45309; font-size: 0.72rem;">SUBADMIN:</span>
                        <span>{{ substr(Session::get('contract_admin_wallet'), 0, 6) }}...{{ substr(Session::get('contract_admin_wallet'), -4) }}</span>
                    </div>
                    @else
                    <div class="auth-wallet-pill" title="Authenticated Admin Wallet: {{ Session::get('contract_admin_wallet') }}">
                        <i class="fa-solid fa-user-shield" style="color: var(--primary);"></i>
                        <span>{{ substr(Session::get('contract_admin_wallet'), 0, 6) }}...{{ substr(Session::get('contract_admin_wallet'), -4) }}</span>
                    </div>
                    @endif
                @endif

                <button class="btn-wallet" id="btnConnectWallet" onclick="connectAdminWallet()">
                    <i class="fa-solid fa-wallet"></i>
                    <span id="walletBtnText">Connect Wallet</span>
                </button>

                <a href="{{ url('/dishi/yashi/logout') }}" class="btn-logout" title="Sign Out of Command Center">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>

                <button class="btn-mobile-toggle" onclick="toggleMobileTabs()">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main App Container -->
    <main class="portal-layout">

        <!-- Navigation Tabs -->
        <div class="tab-bar" id="portalTabBar">
            <button class="tab-btn active" onclick="switchTab('tab-overview', this)">
                <i class="fa-solid fa-chart-pie"></i> Overview Dashboard
            </button>
            <button class="tab-btn" onclick="switchTab('tab-users', this)">
                <i class="fa-solid fa-users"></i> Users ({{ $userSummary['totalUsers'] }})
            </button>
            <button class="tab-btn" onclick="switchTab('tab-genealogy', this)">
                <i class="fa-solid fa-network-wired"></i> Genealogy Tree
            </button>
            <button class="tab-btn" onclick="switchTab('tab-directs', this)">
                <i class="fa-solid fa-user-check"></i> Active Directs ({{ $userSummary['activeDirects'] }})
            </button>
            <button class="tab-btn" onclick="switchTab('tab-stakings', this)">
                <i class="fa-solid fa-vault"></i> Stakings (${{ number_format($stakingSummary['totalUsdt'], 0) }})
            </button>
            @if(!$isSubAdminUser)
            <button class="tab-btn" onclick="switchTab('tab-contracts', this)">
                <i class="fa-solid fa-cube"></i> Smart Contract Hub
            </button>
            <a href="{{ url('/dishi/yashi/contracts') }}" class="tab-btn" style="text-decoration: none; background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; font-weight: 700;">
                <i class="fa-solid fa-gears"></i> Contract Functions Console <span class="badge" style="background: #4f46e5; color: #ffffff; font-size: 9px; padding: 2px 6px; border-radius: 999px; margin-left: 4px;">NEW PAGE</span>
            </a>
            @else
            <button class="tab-btn" style="opacity: 0.6; cursor: not-allowed; background: #f8fafc; color: var(--text-muted);" title="Smart contract operations restricted to Owner" onclick="alert('Access Denied: Subadmins are strictly restricted from performing or accessing Smart Contract operations.')">
                <i class="fa-solid fa-lock"></i> Smart Contracts (Owner Only)
            </button>
            @endif
        </div>

        <!-- ======================================================== -->
        <!-- TAB 1: OVERVIEW DASHBOARD                                 -->
        <!-- ======================================================== -->
        <div id="tab-overview" class="tab-content-panel">
            
            <!-- Live Pool Banner -->
            <div class="pool-banner">
                <div>
                    <div style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; opacity: 0.9; letter-spacing: 0.05em;">
                        <i class="fa-solid fa-water"></i> PancakeSwap V2 Verified Liquidity Pool
                    </div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 800; margin: 4px 0 2px;">
                        CAAI / USDT Official Pair
                    </div>
                    <div style="font-size: 0.8rem; opacity: 0.85;" class="font-mono">
                        {{ $contracts['pancakePairAddress'] }}
                    </div>
                </div>

                <div class="pool-stats-row">
                    <div class="pool-stat-col">
                        <div class="pool-stat-label">Live CAAI Price</div>
                        <div class="pool-stat-num" id="livePoolPrice">$0.8276</div>
                    </div>
                    <div class="pool-stat-col">
                        <div class="pool-stat-label">Pool Liquidity</div>
                        <div class="pool-stat-num" id="livePoolLiq">~$6,573.98</div>
                    </div>
                    <div class="pool-stat-col">
                        <div class="pool-stat-label">CAAI Reserves</div>
                        <div class="pool-stat-num" id="livePoolCaai">3,971.58 CAAI</div>
                    </div>
                    <div class="pool-stat-col">
                        <div class="pool-stat-label">USDT Reserves</div>
                        <div class="pool-stat-num" id="livePoolUsdt">3,286.99 USDT</div>
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <a href="https://dexscreener.com/bsc/{{ $contracts['pancakePairAddress'] }}" target="_blank" class="btn-action primary" style="background: rgba(255,255,255,0.9); color: var(--primary); font-weight: 700;">
                        <i class="fa-solid fa-chart-line"></i> DexScreener
                    </a>
                    <a href="https://bscscan.com/address/{{ $contracts['pancakePairAddress'] }}" target="_blank" class="btn-action" style="background: rgba(255,255,255,0.2); color: #ffffff; border-color: rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> BscScan
                    </a>
                </div>
            </div>

            <!-- Key Metric KPI Cards -->
            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Total Registered Users</div>
                        <div class="metric-icon-box indigo"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <div class="metric-value">{{ $userSummary['totalUsers'] }}</div>
                    <div class="metric-sub">
                        <span class="badge badge-active"><i class="fa-solid fa-check"></i> {{ $userSummary['activeUsers'] }} Paid</span>
                        <span class="badge badge-inactive">{{ $userSummary['inactiveUsers'] }} Free</span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Total Business Volume</div>
                        <div class="metric-icon-box emerald"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                    </div>
                    <div class="metric-value">${{ number_format($stakingSummary['totalUsdt'], 2) }}</div>
                    <div class="metric-sub">
                        <span class="badge badge-active">${{ number_format($stakingSummary['activeUsdt'], 2) }} Active Locked</span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Active Direct Sponsors</div>
                        <div class="metric-icon-box amber"><i class="fa-solid fa-user-check"></i></div>
                    </div>
                    <div class="metric-value">{{ $userSummary['activeDirects'] }}</div>
                    <div class="metric-sub">
                        <span>Total Direct Connections: <strong>{{ $userSummary['totalDirects'] }}</strong></span>
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Mining Buffer Reserves</div>
                        <div class="metric-icon-box cyan"><i class="fa-solid fa-microchip"></i></div>
                    </div>
                    <div class="metric-value" id="miningBufferVal">40,454 CAAI</div>
                    <div class="metric-sub">
                        <span class="badge badge-cyan">In Mining Engine Buffer</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Protocol Status -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
                
                <div class="portal-card">
                    <div class="portal-card-header">
                        <div>
                            <div class="card-heading"><i class="fa-solid fa-bolt"></i> Fast Administrative Actions</div>
                            <div class="card-caption">Execute immediate protocol, MLM and blockchain controls</div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button class="btn-action primary" style="padding: 12px 16px; font-size: 0.85rem;" onclick="switchTab('tab-genealogy', document.querySelectorAll('.tab-btn')[2])">
                            <i class="fa-solid fa-network-wired"></i> Open Full Interactive Genealogy Downline Tree
                        </button>
                        <button class="btn-action" style="padding: 12px 16px; font-size: 0.85rem;" onclick="triggerSplitterDistribute()">
                            <i class="fa-solid fa-code-fork"></i> Trigger Revenue Splitter On-Chain (68/20/10/2)
                        </button>
                        <button class="btn-action" style="padding: 12px 16px; font-size: 0.85rem;" onclick="refreshLiveOnChainBalances()">
                            <i class="fa-solid fa-arrows-rotate"></i> Sync Real-Time Blockchain Pool Reserves & Balances
                        </button>
                        <a href="/Main/Dashboard" class="btn-action" style="padding: 12px 16px; font-size: 0.85rem;">
                            <i class="fa-solid fa-sliders"></i> Access Legacy Control Panel
                        </a>
                    </div>
                </div>

                <div class="portal-card">
                    <div class="portal-card-header">
                        <div>
                            <div class="card-heading"><i class="fa-solid fa-shield-halved"></i> V2 Security & Architecture Audit</div>
                            <div class="card-caption">All 7 smart contracts fully verified on BSC Mainnet</div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 0.82rem;">
                        <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                            <span>CAAI Token Supply</span>
                            <strong class="font-mono">777,777 CAAI (Fixed)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                            <span>Token Ownership</span>
                            <span class="badge badge-active"><i class="fa-solid fa-lock"></i> Renounced (0x000...000)</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                            <span>Revenue Split Formula</span>
                            <strong>68% Mining, 20% Trading, 10% Partners, 2% Reserve</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                            <span>Royal Jackpot Auto-Forward</span>
                            <span class="badge badge-active"><i class="fa-solid fa-check"></i> Enabled (to Collector)</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>USDT Withdrawal Vault</span>
                            <span class="badge badge-indigo">EIP-712 Cryptographic Signature</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: USERS & DIRECTS                                    -->
        <!-- ======================================================== -->
        <div id="tab-users" class="tab-content-panel" style="display: none;">
            <div class="portal-card">
                <div class="portal-card-header">
                    <div>
                        <div class="card-heading"><i class="fa-solid fa-users"></i> Member Network & MLM Registry</div>
                        <div class="card-caption">Comprehensive overview of all registered members, investments and downline stats</div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button class="btn-action primary" onclick="exportUsersToCSV()">
                            <i class="fa-solid fa-file-csv"></i> Export CSV
                        </button>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="filter-bar">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="userSearchInput" class="search-input" placeholder="Search by UUID, Name, Sponsor, or Wallet..." onkeyup="filterUsersTable()">
                    </div>

                    <select id="userStatusFilter" class="filter-select" onchange="filterUsersTable()">
                        <option value="all">All Statuses</option>
                        <option value="active">Active (Paid)</option>
                        <option value="inactive">Inactive</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="admin-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>User (UUID / Name)</th>
                                <th>Sponsor</th>
                                <th>Self Staking</th>
                                <th>Team Volume</th>
                                <th>Directs</th>
                                <th>Active Directs</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Wallet Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                            <tr data-status="{{ $u->permission == 0 ? 'blocked' : ($u->is_active ? 'active' : 'inactive') }}" data-search="{{ strtolower($u->uuid . ' ' . $u->name . ' ' . $u->sponsor_uuid . ' ' . $u->wallet) }}">
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-mini">{{ substr($u->uuid, -2) }}</div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--text-heading);">{{ $u->uuid }}</div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $u->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-indigo">{{ $u->sponsor_uuid }}</span>
                                </td>
                                <td>
                                    <strong style="color: var(--emerald-text);">${{ number_format($u->self_investment, 2) }}</strong>
                                </td>
                                <td>
                                    <strong>${{ number_format($u->team_investment, 2) }}</strong>
                                </td>
                                <td>
                                    <span style="font-weight: 700;">{{ $u->total_direct }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $u->active_direct > 0 ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $u->active_direct }} Active
                                    </span>
                                </td>
                                <td>
                                    @if($u->permission == 0)
                                        <span class="badge badge-blocked"><i class="fa-solid fa-ban"></i> Blocked</span>
                                    @elseif($u->is_active)
                                        <span class="badge badge-active"><i class="fa-solid fa-circle-check"></i> Paid</span>
                                    @else
                                        <span class="badge badge-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $u->doj }}</td>
                                <td>
                                    @if($u->wallet)
                                        <span class="font-mono" style="font-size: 0.72rem; color: var(--text-muted);" title="{{ $u->wallet }}">
                                            {{ substr($u->wallet, 0, 6) }}...{{ substr($u->wallet, -4) }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-light); font-size: 0.72rem;">None</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        <button class="btn-action primary" onclick="viewUserGenealogy('{{ $u->uuid }}')" title="View Tree">
                                            <i class="fa-solid fa-network-wired"></i>
                                        </button>
                                        <button class="btn-action" onclick="openUserModal('{{ $u->id }}')" title="View Profile">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn-action {{ $u->permission == 1 ? 'danger' : 'primary' }}" onclick="toggleUserPermission('{{ $u->id }}', this)" title="{{ $u->permission == 1 ? 'Block User' : 'Unblock User' }}">
                                            <i class="fa-solid {{ $u->permission == 1 ? 'fa-lock' : 'fa-unlock' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 3: INTERACTIVE GENEALOGY TREE                         -->
        <!-- ======================================================== -->
        <div id="tab-genealogy" class="tab-content-panel" style="display: none;">
            <div class="portal-card">
                <div class="portal-card-header">
                    <div>
                        <div class="card-heading"><i class="fa-solid fa-network-wired"></i> Interactive MLM Genealogy Tree Explorer</div>
                        <div class="card-caption">Visual hierarchical network downline viewer with direct & multi-level tree branching</div>
                    </div>

                    <div style="display: flex; gap: 8px;">
                        <button class="btn-action" onclick="loadGenealogyTree('')">
                            <i class="fa-solid fa-house"></i> Root Sponsor
                        </button>
                    </div>
                </div>

                <!-- Tree Search Box -->
                <div class="filter-bar">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="genealogySearchInput" class="search-input" placeholder="Enter User UUID (e.g. CAI9999999 or CAI4729062)...">
                    </div>
                    <button class="btn-action primary" style="padding: 10px 20px;" onclick="searchGenealogy()">
                        <i class="fa-solid fa-sitemap"></i> Load Downline Tree
                    </button>
                </div>

                <!-- Tree Canvas -->
                <div class="tree-container" id="treeViewerCanvas">
                    <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-spinner fa-spin fa-2x" style="color: var(--primary);"></i>
                        <div style="margin-top: 12px; font-weight: 600;">Loading interactive genealogy downline...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 4: ACTIVE DIRECTS & SPONSORS                          -->
        <!-- ======================================================== -->
        <div id="tab-directs" class="tab-content-panel" style="display: none;">
            <div class="portal-card">
                <div class="portal-card-header">
                    <div>
                        <div class="card-heading"><i class="fa-solid fa-user-check"></i> Network Leaders & Active Directs</div>
                        <div class="card-caption">Ranking of top sponsors and active referral connections across the protocol</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Leader (UUID / Name)</th>
                                <th>Active Directs</th>
                                <th>Total Directs</th>
                                <th>Direct Business Volume</th>
                                <th>Total Team Business</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users->sortByDesc('active_direct') as $leader)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar-mini">{{ substr($leader->uuid, -2) }}</div>
                                        <div>
                                            <div style="font-weight: 700; color: var(--text-heading);">{{ $leader->uuid }}</div>
                                            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $leader->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-active" style="font-size: 0.8rem; padding: 5px 12px;">
                                        <i class="fa-solid fa-bolt"></i> {{ $leader->active_direct }} Active
                                    </span>
                                </td>
                                <td><strong>{{ $leader->total_direct }}</strong> Referrals</td>
                                <td><strong style="color: var(--emerald-text);">${{ number_format($leader->self_investment, 2) }}</strong></td>
                                <td><strong>${{ number_format($leader->team_investment, 2) }}</strong></td>
                                <td>
                                    <button class="btn-action primary" onclick="viewUserGenealogy('{{ $leader->uuid }}')">
                                        <i class="fa-solid fa-network-wired"></i> View Direct Downline
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 5: STAKINGS & BUSINESS                                -->
        <!-- ======================================================== -->
        <div id="tab-stakings" class="tab-content-panel" style="display: none;">
            
            <div class="metric-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Total Staking Deposits</div>
                        <div class="metric-icon-box indigo"><i class="fa-solid fa-layer-group"></i></div>
                    </div>
                    <div class="metric-value">${{ number_format($stakingSummary['totalUsdt'], 2) }}</div>
                    <div class="metric-sub">{{ $stakingSummary['totalCount'] }} Total Records</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Active Locked Staking</div>
                        <div class="metric-icon-box emerald"><i class="fa-solid fa-lock"></i></div>
                    </div>
                    <div class="metric-value">${{ number_format($stakingSummary['activeUsdt'], 2) }}</div>
                    <div class="metric-sub">{{ $stakingSummary['activeCount'] }} Active Contracts</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Average Stake Amount</div>
                        <div class="metric-icon-box amber"><i class="fa-solid fa-calculator"></i></div>
                    </div>
                    <div class="metric-value">${{ number_format($stakingSummary['avgStake'], 2) }}</div>
                    <div class="metric-sub">Top Stake: ${{ number_format($stakingSummary['topStake'], 2) }}</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <div class="metric-title">Unique Stakers</div>
                        <div class="metric-icon-box cyan"><i class="fa-solid fa-user-shield"></i></div>
                    </div>
                    <div class="metric-value">{{ $stakingSummary['uniqueUsers'] }} Members</div>
                    <div class="metric-sub">Active Network Stakers</div>
                </div>
            </div>

            <div class="portal-card">
                <div class="portal-card-header">
                    <div>
                        <div class="card-heading"><i class="fa-solid fa-vault"></i> Real-Time Staking Deposits Ledger</div>
                        <div class="card-caption">All mining package stakings and multiplier allocations</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Member (UUID / Name)</th>
                                <th>Staked USDT</th>
                                <th>Allocated CAAI</th>
                                <th>Multiplier</th>
                                <th>Status</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stakingList as $stk)
                            <tr>
                                <td class="font-mono">#{{ $stk->id }}</td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-heading);">{{ $stk->uuid }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $stk->name }}</div>
                                </td>
                                <td>
                                    <strong style="color: var(--emerald-text); font-size: 0.9rem;">${{ number_format($stk->usdt, 2) }}</strong>
                                </td>
                                <td>
                                    <strong class="font-mono">{{ number_format($stk->cai, 2) }} CAAI</strong>
                                </td>
                                <td>
                                    <span class="badge badge-indigo">{{ $stk->multiplier }}x Multiplier</span>
                                </td>
                                <td>
                                    @if($stk->status == 1)
                                        <span class="badge badge-active"><i class="fa-solid fa-check"></i> Active</span>
                                    @else
                                        <span class="badge badge-inactive">Completed</span>
                                    @endif
                                </td>
                                <td style="font-size: 0.74rem; color: var(--text-muted);">{{ $stk->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        @if(!$isSubAdminUser)
        <!-- ======================================================== -->
        <!-- TAB 6: SMART CONTRACT HUB                                 -->
        <!-- ======================================================== -->
        <div id="tab-contracts" class="tab-content-panel" style="display: none;">
            
            <div class="portal-card-header" style="margin-bottom: 16px;">
                <div>
                    <div class="card-heading"><i class="fa-solid fa-cube"></i> Deployed Smart Contract Infrastructure</div>
                    <div class="card-caption">Real-time on-chain verifiable addresses and status on BSC Mainnet (Chain ID 56)</div>
                </div>

                <button class="btn-action primary" onclick="refreshLiveOnChainBalances()">
                    <i class="fa-solid fa-arrows-rotate"></i> Query Live RPC
                </button>
            </div>

            <!-- Dedicated Full Operations Console Banner -->
            <div style="background: linear-gradient(135deg, #1e1b4b, #312e81); border-radius: var(--radius-lg); padding: 20px 24px; color: #ffffff; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; box-shadow: var(--shadow-card);">
                <div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-gears" style="color: #38bdf8;"></i> Dedicated Smart Contract Operations & Governance Console
                    </div>
                    <div style="font-size: 0.82rem; color: #cbd5e1; margin-top: 4px;">
                        Execute all contract owner functions directly on-chain: rescueTokens, distribute (68/20/10/2), update wallets, emergency withdrawal, pause/unpause, and sweep jackpot funds.
                    </div>
                </div>
                <a href="{{ url('/dishi/yashi/contracts') }}" class="btn-action" style="background: #38bdf8; color: #0f172a; font-weight: 800; text-decoration: none; padding: 10px 20px; border-radius: 8px; white-space: nowrap; border: none;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Full Console Page
                </a>
            </div>

            <!-- Smart Contracts Grid -->
            <div class="contracts-grid">
                
                <!-- CAAI Token -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-coins" style="color: #4f46e5;"></i> CAAI Token (BEP-20)
                        </div>
                        <span class="badge badge-active"><i class="fa-solid fa-lock"></i> Renounced</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['caiTokenAddress'], 0, 8) }}...{{ substr($contracts['caiTokenAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['caiTokenAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>Total Supply: <strong>777,777 CAAI</strong></span>
                        <a href="https://bscscan.com/token/{{ $contracts['caiTokenAddress'] }}" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            BscScan <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

                <!-- PancakeSwap Pair -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-water" style="color: #06b6d4;"></i> PancakeSwap V2 Pair
                        </div>
                        <span class="badge badge-active">Live Pool</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['pancakePairAddress'], 0, 8) }}...{{ substr($contracts['pancakePairAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['pancakePairAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>Live Price: <strong id="boxPrice">$0.8276</strong></span>
                        <a href="https://dexscreener.com/bsc/{{ $contracts['pancakePairAddress'] }}" target="_blank" style="color: var(--emerald); text-decoration: none; font-weight: 600;">
                            Chart <i class="fa-solid fa-chart-line" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Cyera Mining Engine -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-microchip" style="color: #10b981;"></i> Cyera Mining Engine
                        </div>
                        <span class="badge badge-active">Verified</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['miningEngineAddress'], 0, 8) }}...{{ substr($contracts['miningEngineAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['miningEngineAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>Buffer: <strong id="boxBuffer">40,454 CAAI</strong></span>
                        <a href="https://bscscan.com/address/{{ $contracts['miningEngineAddress'] }}#code" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            BscScan <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Revenue Splitter -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-code-fork" style="color: #f59e0b;"></i> Revenue Splitter V2
                        </div>
                        <span class="badge badge-active">Active</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['revenueSplitterAddress'], 0, 8) }}...{{ substr($contracts['revenueSplitterAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['revenueSplitterAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>Split: <strong>68/20/10/2</strong></span>
                        <a href="https://bscscan.com/address/{{ $contracts['revenueSplitterAddress'] }}#code" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            BscScan <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

                <!-- USDT Withdrawal Vault -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-vault" style="color: #8b5cf6;"></i> USDT Withdrawal Vault
                        </div>
                        <span class="badge badge-active">Secure</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['usdtWithdrawalVaultAddress'], 0, 8) }}...{{ substr($contracts['usdtWithdrawalVaultAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['usdtWithdrawalVaultAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>USDT: <strong id="boxVaultUsdt">0.00 USDT</strong></span>
                        <a href="https://bscscan.com/address/{{ $contracts['usdtWithdrawalVaultAddress'] }}#code" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            BscScan <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Royal Jackpot Deposit -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-trophy" style="color: #f59e0b;"></i> Royal Jackpot Deposit
                        </div>
                        <span class="badge badge-active">Auto-Forward</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['royalJackpotDepositAddress'], 0, 8) }}...{{ substr($contracts['royalJackpotDepositAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['royalJackpotDepositAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>Collector: <strong>0xBb83...</strong></span>
                        <a href="https://bscscan.com/address/{{ $contracts['royalJackpotDepositAddress'] }}#code" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            BscScan <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Royal Jackpot Vault -->
                <div class="contract-box">
                    <div class="contract-box-top">
                        <div class="contract-box-title">
                            <i class="fa-solid fa-crown" style="color: #ef4444;"></i> Royal Jackpot Vault
                        </div>
                        <span class="badge badge-active">Claim Ready</span>
                    </div>
                    <div class="contract-addr-row">
                        <span>{{ substr($contracts['royalJackpotVaultAddress'], 0, 8) }}...{{ substr($contracts['royalJackpotVaultAddress'], -6) }}</span>
                        <button class="btn-action" style="padding: 2px 6px;" onclick="copyToClipboard('{{ $contracts['royalJackpotVaultAddress'] }}')">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                    <div class="contract-metrics-row">
                        <span>Signer: <strong>0xEf53...</strong></span>
                        <a href="https://bscscan.com/address/{{ $contracts['royalJackpotVaultAddress'] }}#code" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                            BscScan <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>
        @else
        <!-- Subadmin Restricted Placeholder -->
        <div id="tab-contracts" class="tab-content-panel" style="display: none;">
            <div class="portal-card" style="text-align: center; padding: 60px 24px;">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px;">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 800; color: var(--text-heading); margin-bottom: 8px;">Smart Contract Operations Restricted</h3>
                <p style="font-size: 0.88rem; color: var(--text-muted); max-width: 520px; margin: 0 auto 20px; line-height: 1.5;">
                    Your subadmin account has full management access for MLM members, genealogy trees, stakings, and directs. All smart contract executions, parameters, and on-chain functions are strictly restricted to the Protocol Owner.
                </p>
                <button class="btn-action primary" onclick="switchTab('tab-overview', document.querySelector('.tab-bar .tab-btn'))">
                    <i class="fa-solid fa-arrow-left"></i> Return to Overview
                </button>
            </div>
        </div>
        @endif

    </main>

    <!-- User Profile Modal -->
    <div class="modal-overlay" id="userModal">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-title" id="modalUserName">Member Profile</div>
                <button class="modal-close" onclick="closeUserModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" id="modalUserContent">
                <div style="text-align: center; padding: 20px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>
    </div>

    <!-- Application Logic & Scripts -->
    <script>
        // Contract configuration from Laravel backend
        const CONFIG = {
            caiToken: "{{ $contracts['caiTokenAddress'] }}",
            pancakePair: "{{ $contracts['pancakePairAddress'] }}",
            miningEngine: "{{ $contracts['miningEngineAddress'] }}",
            revenueSplitter: "{{ $contracts['revenueSplitterAddress'] }}",
            usdtVault: "{{ $contracts['usdtWithdrawalVaultAddress'] }}",
            jackpotDeposit: "{{ $contracts['royalJackpotDepositAddress'] }}",
            jackpotVault: "{{ $contracts['royalJackpotVaultAddress'] }}",
            usdtToken: "{{ $contracts['usdtTokenAddress'] }}",
            rpcUrl: "{{ $contracts['bscMainnetRpc'] }}",
            chainId: 56
        };

        const IS_SUBADMIN = {{ $isSubAdminUser ? 'true' : 'false' }};

        // Tab Switching
        function switchTab(tabId, btn) {
            if (IS_SUBADMIN && tabId === 'tab-contracts') {
                alert('Access Denied: Subadmins are strictly restricted from performing or accessing Smart Contract operations.');
                return;
            }

            document.querySelectorAll('.tab-content-panel').forEach(p => p.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            
            const target = document.getElementById(tabId);
            if (target) target.style.display = 'block';
            if (btn) btn.classList.add('active');

            if (tabId === 'tab-genealogy') {
                loadGenealogyTree('');
            }
        }

        function toggleMobileTabs() {
            const bar = document.getElementById('portalTabBar');
            bar.scrollIntoView({ behavior: 'smooth' });
        }

        // Live Search Filter for Users Table
        function filterUsersTable() {
            const search = document.getElementById('userSearchInput').value.toLowerCase();
            const status = document.getElementById('userStatusFilter').value;
            const rows = document.querySelectorAll('#usersTable tbody tr');

            rows.forEach(r => {
                const text = r.getAttribute('data-search') || '';
                const rStatus = r.getAttribute('data-status') || '';
                const matchSearch = text.includes(search);
                const matchStatus = status === 'all' || rStatus === status;

                r.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        }

        // Interactive Genealogy Tree Fetcher & Renderer
        async function loadGenealogyTree(searchQuery) {
            const canvas = document.getElementById('treeViewerCanvas');
            canvas.innerHTML = `
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <i class="fa-solid fa-spinner fa-spin fa-2x" style="color: var(--primary);"></i>
                    <div style="margin-top: 12px; font-weight: 600;">Fetching downline network hierarchy...</div>
                </div>
            `;

            try {
                const res = await fetch(`/dishi/yashi/genealogy?search=${encodeURIComponent(searchQuery)}`);
                const data = await res.json();

                if (!data.success || !data.data) {
                    canvas.innerHTML = `<div style="text-align: center; padding: 40px; color: var(--rose); font-weight: 600;">User not found or no downline available.</div>`;
                    return;
                }

                renderTreeHierarchy(data.data, canvas);
            } catch (err) {
                canvas.innerHTML = `<div style="text-align: center; padding: 40px; color: var(--rose);">Error loading genealogy: ${err.message}</div>`;
            }
        }

        function searchGenealogy() {
            const input = document.getElementById('genealogySearchInput').value.trim();
            loadGenealogyTree(input);
        }

        function viewUserGenealogy(uuid) {
            switchTab('tab-genealogy', document.querySelectorAll('.tab-btn')[2]);
            document.getElementById('genealogySearchInput').value = uuid;
            loadGenealogyTree(uuid);
        }

        function renderTreeHierarchy(root, container) {
            let html = `
                <div class="tree-level-block">
                    <div class="tree-level-header">
                        <i class="fa-solid fa-crown"></i> Root Sponsor Node
                    </div>
                    <div class="tree-cards-row">
                        <div class="tree-node-card root-card" onclick="loadGenealogyTree('${root.uuid}')">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <strong style="font-size: 0.95rem; color: var(--primary);">${root.uuid}</strong>
                                <span class="badge ${root.userstatus == 1 ? 'badge-active' : 'badge-inactive'}">
                                    ${root.userstatus == 1 ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 600; color: var(--text-heading); margin-bottom: 6px;">
                                ${root.name || 'Member'}
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.72rem; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 6px;">
                                <span>Self: <strong style="color: var(--emerald-text);">$${Number(root.self_inv || 0).toFixed(0)}</strong></span>
                                <span>Team: <strong>$${Number(root.team_inv || 0).toFixed(0)}</strong></span>
                                <span>Directs: <strong>${root.active_direct || 0}/${root.total_direct || 0}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            if (root.children && root.children.length > 0) {
                html += renderTreeChildrenLevels(root.children, 1);
            } else {
                html += `<div style="color: var(--text-muted); font-size: 0.82rem; padding: 12px 0;">No active direct team members enrolled under this user.</div>`;
            }

            container.innerHTML = html;
        }

        function renderTreeChildrenLevels(children, level) {
            let html = `
                <div class="tree-level-block">
                    <div class="tree-level-header">
                        <i class="fa-solid fa-turn-down"></i> Level ${level} Downline (${children.length} Members)
                    </div>
                    <div class="tree-cards-row">
            `;

            children.forEach(c => {
                html += `
                    <div class="tree-node-card" onclick="loadGenealogyTree('${c.uuid}')">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <strong style="font-size: 0.88rem; color: var(--text-heading);">${c.uuid}</strong>
                            <span class="badge ${c.userstatus == 1 ? 'badge-active' : 'badge-inactive'}">
                                ${c.userstatus == 1 ? 'Paid' : 'Free'}
                            </span>
                        </div>
                        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-body); margin-bottom: 6px;">
                            ${c.name || 'Member'}
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.72rem; color: var(--text-muted); border-top: 1px solid var(--border); padding-top: 6px;">
                            <span>Self: <strong style="color: var(--emerald-text);">$${Number(c.self_inv || 0).toFixed(0)}</strong></span>
                            <span>Team: <strong>$${Number(c.team_inv || 0).toFixed(0)}</strong></span>
                            <span>Directs: <strong>${c.active_direct || 0}</strong></span>
                        </div>
                    </div>
                `;
            });

            html += `</div></div>`;

            // Nested Next Levels
            children.forEach(c => {
                if (c.children && c.children.length > 0) {
                    html += renderTreeChildrenLevels(c.children, level + 1);
                }
            });

            return html;
        }

        // Toggle User Block/Unblock
        async function toggleUserPermission(userId, btn) {
            if (!confirm("Are you sure you want to change this member's permission?")) return;

            try {
                btn.disabled = true;
                const token = "{{ csrf_token() }}";
                const res = await fetch('/dishi/yashi/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ userId: userId })
                });

                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            } catch (e) {
                alert("Failed: " + e.message);
            } finally {
                btn.disabled = false;
            }
        }

        // User Detail Modal
        async function openUserModal(userId) {
            const modal = document.getElementById('userModal');
            const content = document.getElementById('modalUserContent');
            modal.classList.add('open');
            content.innerHTML = `<div style="text-align: center; padding: 20px;"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</div>`;

            try {
                const res = await fetch(`/dishi/yashi/user-details/${userId}`);
                const data = await res.json();
                if (data.success) {
                    const u = data.user;
                    document.getElementById('modalUserName').innerText = `${u.uuid} — ${u.name}`;
                    content.innerHTML = `
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px; font-size: 0.82rem;">
                            <div style="background: var(--bg-elevated); padding: 10px; border-radius: var(--radius-sm);">
                                <div style="color: var(--text-muted); font-size: 0.72rem;">Sponsor</div>
                                <strong>${u.sponsor_uuid || 'Root'} (${u.sponsor_name || 'System'})</strong>
                            </div>
                            <div style="background: var(--bg-elevated); padding: 10px; border-radius: var(--radius-sm);">
                                <div style="color: var(--text-muted); font-size: 0.72rem;">Wallet Address</div>
                                <strong class="font-mono" style="font-size: 0.74rem;">${u.wallet || 'None'}</strong>
                            </div>
                            <div style="background: var(--bg-elevated); padding: 10px; border-radius: var(--radius-sm);">
                                <div style="color: var(--text-muted); font-size: 0.72rem;">Self Investment</div>
                                <strong style="color: var(--emerald-text);">$${Number(u.self_inv).toFixed(2)}</strong>
                            </div>
                            <div style="background: var(--bg-elevated); padding: 10px; border-radius: var(--radius-sm);">
                                <div style="color: var(--text-muted); font-size: 0.72rem;">Team Turnover</div>
                                <strong>$${Number(u.team_inv).toFixed(2)}</strong>
                            </div>
                        </div>

                        <div style="font-size: 0.84rem; font-weight: 700; margin-bottom: 8px;">Staking History (${data.stakings.length})</div>
                        <div style="max-height: 180px; overflow-y: auto; border: 1px solid var(--border); border-radius: var(--radius-sm); margin-bottom: 16px;">
                            <table class="admin-table">
                                <thead>
                                    <tr><th>#ID</th><th>Amount</th><th>Multiplier</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    ${data.stakings.map(s => `
                                        <tr>
                                            <td>#${s.id}</td>
                                            <td>$${s.usdt}</td>
                                            <td>${s.istatus || 2.0}x</td>
                                            <td>${s.created_at}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>

                        <div style="text-align: right;">
                            <button class="btn-action primary" onclick="viewUserGenealogy('${u.uuid}'); closeUserModal();">
                                <i class="fa-solid fa-network-wired"></i> View Tree
                            </button>
                        </div>
                    `;
                }
            } catch (err) {
                content.innerHTML = `<div style="color: var(--rose);">Failed to load user info.</div>`;
            }
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.remove('open');
        }

        // Web3 Wallet Connect
        let userWalletAddress = null;
        async function connectAdminWallet() {
            if (typeof window.ethereum === 'undefined') {
                alert("Please install MetaMask or a Web3 compatible browser wallet.");
                return;
            }

            try {
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                userWalletAddress = accounts[0];
                const btn = document.getElementById('btnConnectWallet');
                btn.classList.add('connected');
                document.getElementById('walletBtnText').innerText = `${userWalletAddress.slice(0, 6)}...${userWalletAddress.slice(-4)}`;
            } catch (e) {
                console.error(e);
            }
        }

        // Copy Helper
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            alert("Copied to clipboard: " + text);
        }

        // Trigger Splitter Distribute On-Chain
        async function triggerSplitterDistribute() {
            if (IS_SUBADMIN) {
                alert("Access Denied: Subadmins are strictly restricted from executing contract transactions.");
                return;
            }

            if (!window.ethereum) {
                alert("Please connect MetaMask with an authorized wallet.");
                return;
            }

            try {
                const provider = new ethers.BrowserProvider(window.ethereum);
                const signer = await provider.getSigner();
                const splitterAbi = ["function distribute() external"];
                const splitterContract = new ethers.Contract(CONFIG.revenueSplitter, splitterAbi, signer);

                const tx = await splitterContract.distribute();
                alert("Transaction submitted: " + tx.hash);
                await tx.wait();
                alert("Revenue Splitter distribute() confirmed successfully!");
                refreshLiveOnChainBalances();
            } catch (err) {
                alert("Distribute failed: " + (err.reason || err.message));
            }
        }

        // Real-Time On-Chain Balance Sync via BSC Provider
        async function refreshLiveOnChainBalances() {
            try {
                const provider = new ethers.JsonRpcProvider(CONFIG.rpcUrl);
                const pairAbi = [
                    "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
                    "function token0() view returns (address)"
                ];
                const erc20Abi = ["function balanceOf(address) view returns (uint256)"];

                const pair = new ethers.Contract(CONFIG.pancakePair, pairAbi, provider);
                const cai = new ethers.Contract(CONFIG.caiToken, erc20Abi, provider);
                const usdt = new ethers.Contract(CONFIG.usdtToken, erc20Abi, provider);

                // Pool reserves
                const reserves = await pair.getReserves();
                const token0 = await pair.token0();
                const isCaaiToken0 = token0.toLowerCase() === CONFIG.caiToken.toLowerCase();
                const rCaai = isCaaiToken0 ? reserves[0] : reserves[1];
                const rUsdt = isCaaiToken0 ? reserves[1] : reserves[0];

                const caaiNum = Number(ethers.formatEther(rCaai));
                const usdtNum = Number(ethers.formatEther(rUsdt));
                const price = (usdtNum / caaiNum).toFixed(4);
                const totalLiq = (usdtNum * 2).toFixed(2);

                document.getElementById('livePoolPrice').innerText = `$${price}`;
                document.getElementById('livePoolLiq').innerText = `~$${totalLiq}`;
                document.getElementById('livePoolCaai').innerText = `${caaiNum.toFixed(2)} CAAI`;
                document.getElementById('livePoolUsdt').innerText = `${usdtNum.toFixed(2)} USDT`;
                document.getElementById('boxPrice').innerText = `$${price}`;

                // Mining Buffer
                const bufferBal = await cai.balanceOf(CONFIG.miningEngine);
                const bufferFormatted = Number(ethers.formatEther(bufferBal)).toFixed(0) + " CAAI";
                document.getElementById('miningBufferVal').innerText = bufferFormatted;
                document.getElementById('boxBuffer').innerText = bufferFormatted;

                // USDT Vault Balance
                const vaultBal = await usdt.balanceOf(CONFIG.usdtVault);
                document.getElementById('boxVaultUsdt').innerText = Number(ethers.formatEther(vaultBal)).toFixed(2) + " USDT";

                console.log("✔ Real-time on-chain stats synchronized!");
            } catch (err) {
                console.warn("Could not sync live RPC:", err.message);
            }
        }

        // Export Users CSV
        function exportUsersToCSV() {
            let csv = "UUID,Name,Sponsor,Self_Investment,Team_Investment,Directs,Active_Directs,Status,Registered,Wallet\n";
            document.querySelectorAll('#usersTable tbody tr').forEach(row => {
                const cols = row.querySelectorAll('td');
                if (cols.length >= 9) {
                    const uuid = cols[0].innerText.replace(/\n/g, ' - ').trim();
                    const sponsor = cols[1].innerText.trim();
                    const self = cols[2].innerText.replace('$', '').trim();
                    const team = cols[3].innerText.replace('$', '').trim();
                    const directs = cols[4].innerText.trim();
                    const activeDirects = cols[5].innerText.trim();
                    const status = cols[6].innerText.trim();
                    const reg = cols[7].innerText.trim();
                    const wallet = cols[8].innerText.trim();
                    csv += `"${uuid}","${sponsor}","${self}","${team}","${directs}","${activeDirects}","${status}","${reg}","${wallet}"\n`;
                }
            });

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `cyera_users_${new Date().toISOString().slice(0, 10)}.csv`;
            a.click();
        }

        // Initial Load Actions
        window.addEventListener('DOMContentLoaded', () => {
            refreshLiveOnChainBalances();
            loadGenealogyTree('');
        });
    </script>
</body>
</html>
