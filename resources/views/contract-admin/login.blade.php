<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CYERA AI — Smart Contract Admin Authentication</title>
    <meta name="description" content="MetaMask Web3 Cryptographic Authentication Gate for Cyera AI Smart Contract Governance & MLM Command Center.">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Ethers.js for Web3 Helper Utilities -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.13.2/ethers.umd.min.js"></script>

    <style>
        :root {
            --bg-base: #f8fafc;
            --bg-surface: #ffffff;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            --border-focus: #6366f1;
            
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

            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            
            --shadow-subtle: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            --shadow-card: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(15, 23, 42, 0.04);
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient subtle background patterns */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 0;
        }
        .glow-1 {
            top: -150px;
            left: -150px;
            background: #e0e7ff;
        }
        .glow-2 {
            bottom: -150px;
            right: -150px;
            background: #cffafe;
        }

        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            padding: 40px 36px;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Cluster */
        .brand-cluster {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 26px;
        }

        .brand-avatar {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, #f5841f, #e2761b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 32px;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(226, 118, 27, 0.28);
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid var(--primary-border);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .brand-sub {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Protocol Owner Target Capsule */
        .owner-capsule-notice {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-subtle);
        }

        .owner-capsule-notice .oc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .owner-capsule-notice .oc-title {
            font-size: 0.70rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .owner-capsule-notice .oc-badge {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .owner-capsule-notice .oc-address {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--primary);
            word-break: break-all;
        }

        /* Connected Web3 Live Status Box */
        .web3-status-box {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            margin-bottom: 22px;
            transition: all 0.2s;
        }

        .web3-status-box.active-owner {
            background: var(--emerald-soft);
            border-color: var(--emerald-border);
        }

        .web3-status-box.wrong-account {
            background: var(--rose-soft);
            border-color: var(--rose-border);
        }

        .ws-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ws-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ws-icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #f5841f;
            flex-shrink: 0;
            box-shadow: var(--shadow-subtle);
        }

        .ws-text-cluster .ws-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .ws-text-cluster .ws-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-heading);
            word-break: break-all;
        }

        .btn-switch-account {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-heading);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            box-shadow: var(--shadow-subtle);
        }
        .btn-switch-account:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Action Steps Capsule */
        .auth-steps {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            margin-bottom: 24px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .auth-step {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .auth-step.completed {
            color: var(--emerald-text);
        }

        .auth-step.active {
            color: var(--primary);
            font-weight: 700;
        }

        .auth-step-num {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--bg-base);
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
        }

        .auth-step.completed .auth-step-num {
            background: var(--emerald);
            color: #ffffff;
            border-color: var(--emerald);
        }

        .auth-step.active .auth-step-num {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Primary MetaMask Action Button */
        .btn-metamask-auth {
            width: 100%;
            height: 54px;
            background: linear-gradient(135deg, #f5841f, #e2761b);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 18px rgba(226, 118, 27, 0.35);
        }

        .btn-metamask-auth:hover {
            background: linear-gradient(135deg, #e2761b, #c96312);
            box-shadow: 0 6px 24px rgba(226, 118, 27, 0.45);
            transform: translateY(-1px);
        }

        .btn-metamask-auth:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .btn-metamask-auth i.fa-wallet,
        .btn-metamask-auth img {
            width: 22px;
            height: 22px;
        }

        /* Alert / Messages */
        .alert-box {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font-size: 0.82rem;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            animation: fadeIn 0.2s ease-out;
            line-height: 1.4;
        }

        .alert-box i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-danger {
            background: var(--rose-soft);
            border: 1px solid var(--rose-border);
            color: var(--rose-text);
        }

        .alert-success {
            background: var(--emerald-soft);
            border: 1px solid var(--emerald-border);
            color: var(--emerald-text);
        }

        .alert-warning {
            background: var(--amber-soft);
            border: 1px solid var(--amber-border);
            color: var(--amber-text);
        }

        /* Footer */
        .login-footer {
            margin-top: 26px;
            text-align: center;
            border-top: 1px solid var(--border);
            padding-top: 18px;
        }

        .footer-note {
            font-size: 0.74rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Spinner */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
                border-radius: var(--radius-lg);
            }
            .brand-title {
                font-size: 1.45rem;
            }
            .ws-row {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-switch-account {
                text-align: center;
            }
            .auth-steps {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient background glows -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="login-card">
        <!-- Brand Header -->
        <div class="brand-cluster">
            <div class="brand-avatar">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="brand-badge">
                <i class="fa-solid fa-lock"></i> Protocol V2 Executive Gateway
            </div>
            <h1 class="brand-title">Smart Contract Admin</h1>
            <p class="brand-sub">MetaMask Cryptographic Authentication Gate</p>
        </div>

        <!-- Alert Notification (if any) -->
        <div id="alertContainer">
            @if(session('error'))
                <div class="alert-box alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-box alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
        </div>

        <!-- Designated Protocol Owner Capsule -->
        <div class="owner-capsule-notice">
            <div class="oc-header">
                <span class="oc-title"><i class="fa-solid fa-crown" style="color: #f59e0b;"></i> Designated Protocol Owner</span>
                <span class="oc-badge">Strict Whitelist</span>
            </div>
            <div class="oc-address" id="targetOwnerText">0xB68e29B51BB9B0856211cb2856215e3F68243622</div>
        </div>

        <!-- Live Web3 Connection Status Box -->
        <div class="web3-status-box" id="web3StatusCard">
            <div class="ws-row">
                <div class="ws-left">
                    <div class="ws-icon-circle" id="wsIcon">
                        <i class="fa-brands fa-ethereum"></i>
                    </div>
                    <div class="ws-text-cluster">
                        <div class="ws-label" id="wsLabel">MetaMask Status</div>
                        <div class="ws-value" id="wsValue">Not Connected</div>
                    </div>
                </div>
                <button type="button" class="btn-switch-account" id="btnSwitchAccount" onclick="requestAccountSwitch()" style="display: none;">
                    <i class="fa-solid fa-repeat"></i> Switch
                </button>
            </div>
        </div>

        <!-- Authentication Progress Steps -->
        <div class="auth-steps">
            <div class="auth-step active" id="step1">
                <span class="auth-step-num">1</span>
                <span>Connect</span>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--border);"></i>
            <div class="auth-step" id="step2">
                <span class="auth-step-num">2</span>
                <span>Sign Challenge</span>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: var(--border);"></i>
            <div class="auth-step" id="step3">
                <span class="auth-step-num">3</span>
                <span>Enter Admin</span>
            </div>
        </div>

        <!-- Primary Action Button: Authenticate with MetaMask -->
        <button type="button" class="btn-metamask-auth" id="btnAuth" onclick="handleMetaMaskAuth()">
            <i class="fa-solid fa-fingerprint" id="btnIcon"></i>
            <span id="btnText">Authenticate with MetaMask</span>
        </button>

        <!-- Footer -->
        <div class="login-footer">
            <div class="footer-note">
                <i class="fa-solid fa-shield"></i>
                <span>EIP-191 Cryptographic Verification • No Password Required</span>
            </div>
        </div>
    </div>

    <script>
        // Target Owner Addresses (Case-insensitive comparison)
        const PRIMARY_OWNER = '0xb68e29b51bb9b0856211cb2856215e3f68243622';
        const TARGET_OWNER_DISPLAY = '0xB68e29B51BB9B0856211cb2856215e3F68243622';
        const AUTHORIZED_OWNERS = [
            PRIMARY_OWNER,
            '0xbb83f1dbc26f6a414350e3eabb5f69e28bfef3d9'
        ];

        let currentAccount = null;

        // Display Alert Banner
        function showAlert(msg, type = 'danger') {
            const container = document.getElementById('alertContainer');
            let icon = 'fa-circle-exclamation';
            if (type === 'success') icon = 'fa-circle-check';
            if (type === 'warning') icon = 'fa-triangle-exclamation';
            
            container.innerHTML = `
                <div class="alert-box alert-${type}">
                    <i class="fa-solid ${icon}"></i>
                    <span>${msg}</span>
                </div>
            `;
        }

        function clearAlert() {
            document.getElementById('alertContainer').innerHTML = '';
        }

        // Set Step Active
        function setStep(stepNum) {
            const s1 = document.getElementById('step1');
            const s2 = document.getElementById('step2');
            const s3 = document.getElementById('step3');

            s1.className = 'auth-step' + (stepNum > 1 ? ' completed' : (stepNum === 1 ? ' active' : ''));
            s2.className = 'auth-step' + (stepNum > 2 ? ' completed' : (stepNum === 2 ? ' active' : ''));
            s3.className = 'auth-step' + (stepNum === 3 ? ' active' : '');
        }

        // Update UI based on detected account
        function updateAccountState(account) {
            const card = document.getElementById('web3StatusCard');
            const wsLabel = document.getElementById('wsLabel');
            const wsValue = document.getElementById('wsValue');
            const wsIcon = document.getElementById('wsIcon');
            const btnSwitch = document.getElementById('btnSwitchAccount');

            if (!account) {
                currentAccount = null;
                card.className = 'web3-status-box';
                wsLabel.innerText = 'MetaMask Status';
                wsValue.innerText = 'Not Connected';
                wsIcon.innerHTML = '<i class="fa-brands fa-ethereum"></i>';
                wsIcon.style.color = '#f5841f';
                btnSwitch.style.display = 'none';
                setStep(1);
                return;
            }

            currentAccount = account.trim();
            const normalized = currentAccount.toLowerCase();
            const shortAddr = currentAccount.substring(0, 6) + '...' + currentAccount.substring(currentAccount.length - 4);

            btnSwitch.style.display = 'inline-block';

            if (AUTHORIZED_OWNERS.includes(normalized)) {
                // Verified Protocol Owner
                card.className = 'web3-status-box active-owner';
                wsLabel.innerText = 'Owner Wallet Connected';
                wsValue.innerText = shortAddr + ' (Verified)';
                wsIcon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
                wsIcon.style.color = '#10b981';
                setStep(2);
                showAlert('Owner Wallet detected! Click "Authenticate with MetaMask" to sign in.', 'success');
            } else {
                // Non-Owner Connected
                card.className = 'web3-status-box wrong-account';
                wsLabel.innerText = 'Unauthorized Wallet';
                wsValue.innerText = shortAddr;
                wsIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                wsIcon.style.color = '#ef4444';
                setStep(1);
                showAlert(`Notice: Connected wallet (${shortAddr}) is NOT the Protocol Owner. Please switch account to ${TARGET_OWNER_DISPLAY} in MetaMask.`, 'warning');
            }
        }

        // Request MetaMask Account Switch
        async function requestAccountSwitch() {
            if (typeof window.ethereum === 'undefined') {
                showAlert('MetaMask is not installed.', 'danger');
                return;
            }
            try {
                await window.ethereum.request({
                    method: 'wallet_requestPermissions',
                    params: [{ eth_accounts: {} }]
                });
                const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                if (accounts && accounts.length > 0) {
                    updateAccountState(accounts[0]);
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Main 1-Click MetaMask Authentication Flow
        async function handleMetaMaskAuth() {
            const btn = document.getElementById('btnAuth');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            if (typeof window.ethereum === 'undefined') {
                showAlert('MetaMask / Web3 provider not detected in browser. Please open this page in MetaMask or install the MetaMask extension.', 'danger');
                return;
            }

            try {
                btn.disabled = true;
                btnIcon.className = 'spinner';
                btnText.innerText = 'Connecting MetaMask...';
                setStep(1);

                // 1. Connect / Request Accounts
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                if (!accounts || accounts.length === 0) {
                    throw new Error('No Ethereum accounts found or connection rejected.');
                }

                const selectedAccount = accounts[0].trim();
                updateAccountState(selectedAccount);

                // 2. Verify Account matches Owner (Case-Insensitive)
                if (!AUTHORIZED_OWNERS.includes(selectedAccount.toLowerCase())) {
                    btn.disabled = false;
                    btnIcon.className = 'fa-solid fa-fingerprint';
                    btnText.innerText = 'Authenticate with MetaMask';
                    const shortAddr = selectedAccount.substring(0, 6) + '...' + selectedAccount.substring(selectedAccount.length - 4);
                    showAlert(`Access Denied: Connected account ${shortAddr} is not an authorized Owner Wallet. Switch to ${TARGET_OWNER_DISPLAY} in MetaMask.`, 'danger');
                    return;
                }

                // 3. Request Challenge from Server
                btnText.innerText = 'Fetching Security Challenge...';
                setStep(2);

                const challengeRes = await fetch("{{ url('/dishi/yashi/challenge') }}", {
                    method: 'GET',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const challengeData = await challengeRes.json();

                if (!challengeRes.ok || challengeData.status !== 'success') {
                    throw new Error(challengeData.message || 'Failed to retrieve authentication challenge from server.');
                }

                const messageToSign = challengeData.message;

                // 4. Prompt Personal Sign in MetaMask
                btnText.innerText = 'Please Sign in MetaMask...';
                showAlert('A signature request has been sent to your MetaMask. Please click "Sign" to verify your identity.', 'warning');

                const signature = await window.ethereum.request({
                    method: 'personal_sign',
                    params: [messageToSign, selectedAccount]
                });

                if (!signature) {
                    throw new Error('Signature was not provided.');
                }

                // 5. Submit Signature to Backend for Cryptographic Verification
                btnText.innerText = 'Verifying Signature...';
                setStep(3);

                const loginRes = await fetch("{{ url('/dishi/yashi/login') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        address: selectedAccount,
                        signature: signature,
                        message: messageToSign
                    })
                });

                const loginData = await loginRes.json();

                if (loginRes.ok && loginData.status === 'success') {
                    showAlert(loginData.message || 'Signature verified! Access granted.', 'success');
                    btnText.innerText = 'Access Granted! Entering...';
                    btnIcon.className = 'fa-solid fa-circle-check';
                    setTimeout(() => {
                        window.location.href = loginData.redirect || "{{ url('/dishi/yashi') }}";
                    }, 500);
                } else {
                    throw new Error(loginData.message || 'Cryptographic verification failed.');
                }

            } catch (err) {
                console.error(err);
                btn.disabled = false;
                btnIcon.className = 'fa-solid fa-fingerprint';
                btnText.innerText = 'Authenticate with MetaMask';
                showAlert(err.message || 'Authentication process failed.', 'danger');
                if (currentAccount) {
                    updateAccountState(currentAccount);
                }
            }
        }

        // On Page Load: Pre-check connected accounts
        window.addEventListener('load', async () => {
            if (typeof window.ethereum !== 'undefined') {
                try {
                    const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                    if (accounts && accounts.length > 0) {
                        updateAccountState(accounts[0]);
                    }

                    // Listen for Account changes in MetaMask
                    window.ethereum.on('accountsChanged', (newAccounts) => {
                        if (newAccounts && newAccounts.length > 0) {
                            updateAccountState(newAccounts[0]);
                        } else {
                            updateAccountState(null);
                        }
                    });

                    // Listen for Chain / Network changes
                    window.ethereum.on('chainChanged', () => {
                        window.location.reload();
                    });
                } catch (e) {
                    console.warn('Auto-detect error:', e);
                }
            } else {
                showAlert('MetaMask not detected in browser. Please open in MetaMask browser or install MetaMask.', 'warning');
            }
        });
    </script>
</body>
</html>
