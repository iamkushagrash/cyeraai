<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CYERA AI — Smart Contract Admin Login</title>
    <meta name="description" content="Dual-Factor Secure Authentication Gate for Cyera AI Smart Contract Governance & MLM Command Center.">
    <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Ethers.js for Web3 Wallet Connect -->
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
            max-width: 480px;
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
            margin-bottom: 30px;
        }

        .brand-avatar {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 28px;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.28);
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

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 8px;
        }

        .label-badge {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary);
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-light);
            font-size: 15px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            height: 48px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 0 16px 0 42px;
            font-size: 0.90rem;
            color: var(--text-heading);
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .form-input:focus + .input-icon {
            color: var(--primary);
        }

        .form-input.font-mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.84rem;
        }

        .btn-toggle-eye {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 14px;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: color 0.2s;
        }
        .btn-toggle-eye:hover {
            color: var(--text-body);
        }

        /* Web3 Connect Capsule */
        .web3-connect-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--primary-soft);
            border: 1px solid var(--primary-border);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            margin-bottom: 22px;
            gap: 10px;
        }

        .web3-connect-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .web3-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid var(--primary-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 14px;
        }

        .web3-connect-info .title {
            font-size: 0.80rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .web3-connect-info .desc {
            font-size: 0.70rem;
            color: var(--text-muted);
            line-height: 1.2;
        }

        .btn-connect-web3 {
            background: #ffffff;
            border: 1px solid var(--primary-border);
            color: var(--primary);
            font-size: 0.76rem;
            font-weight: 700;
            padding: 7px 12px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.2s;
            box-shadow: var(--shadow-subtle);
        }

        .btn-connect-web3:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        .btn-connect-web3.connected {
            background: var(--emerald-soft);
            color: var(--emerald-text);
            border-color: var(--emerald-border);
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            margin-top: 26px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            transform: translateY(-1px);
        }

        .btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert / Messages */
        .alert-box {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font-size: 0.82rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            animation: fadeIn 0.2s ease-out;
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
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-autofill {
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid var(--primary-border);
            font-size: 0.70rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }
        .btn-autofill:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .owner-capsule-notice {
            background: #ffffff;
            border: 1px dashed var(--primary-border);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .owner-capsule-notice .oc-title {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .owner-capsule-notice .oc-address {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--primary);
            word-break: break-all;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
                border-radius: var(--radius-lg);
            }
            .brand-title {
                font-size: 1.45rem;
            }
            .web3-connect-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-connect-web3 {
                justify-content: center;
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
                <i class="fa-solid fa-lock"></i> Protocol V2 Security Gate
            </div>
            <h1 class="brand-title">Smart Contract Admin</h1>
            <p class="brand-sub">Dual-Factor Verification: Web3 Wallet & Passcode</p>
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

        <!-- Protocol Owner Target Capsule -->
        <div class="owner-capsule-notice">
            <div>
                <div class="oc-title"><i class="fa-solid fa-crown" style="color: #f59e0b; margin-right: 4px;"></i> Designated Protocol Owner</div>
                <div class="oc-address">{{ $ownerWallet ?? '0xB68e29B51BB9B0856211cb2856215e3F68243622' }}</div>
            </div>
            <button type="button" class="btn-autofill" onclick="autofillOwnerWallet()" title="Click to auto-fill Owner Wallet">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Fill
            </button>
        </div>

        <!-- Web3 Quick Connect Bar -->
        <div class="web3-connect-bar">
            <div class="web3-connect-left">
                <div class="web3-icon-badge">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="web3-connect-info">
                    <div class="title">Web3 Auto-Detect</div>
                    <div class="desc" id="web3StatusText">MetaMask / Trust / Injected</div>
                </div>
            </div>
            <button type="button" class="btn-connect-web3" id="btnConnectWeb3" onclick="handleWeb3Connect()">
                <i class="fa-solid fa-plug"></i>
                <span id="btnConnectText">Connect Wallet</span>
            </button>
        </div>

        <!-- Login Form -->
        <form id="adminLoginForm" method="POST" action="{{ url('/dishi/yashi/login') }}">
            {{ csrf_field() }}

            <!-- Factor 1: Wallet Address -->
            <div class="form-group">
                <label class="form-label" for="wallet_address">
                    <span><i class="fa-solid fa-user-shield" style="margin-right: 4px; color: var(--primary);"></i> Protocol Owner Wallet Address</span>
                    <span class="label-badge">FACTOR 1</span>
                </label>
                <div class="input-wrap">
                    <input 
                        type="text" 
                        id="wallet_address" 
                        name="wallet_address" 
                        class="form-input font-mono" 
                        placeholder="0x..." 
                        required 
                        autocomplete="off" 
                        spellcheck="false"
                        value="{{ old('wallet_address') }}"
                    >
                    <i class="fa-solid fa-cube input-icon"></i>
                </div>
            </div>

            <!-- Factor 2: Security Passcode -->
            <div class="form-group">
                <label class="form-label" for="password">
                    <span><i class="fa-solid fa-key" style="margin-right: 4px; color: var(--primary);"></i> Master Passcode</span>
                    <span class="label-badge">FACTOR 2</span>
                </label>
                <div class="input-wrap">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input font-mono" 
                        placeholder="Enter Security Passcode (797979)" 
                        required 
                        autocomplete="current-password"
                    >
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility()" title="Show/Hide Passcode">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="fa-solid fa-arrow-right-to-bracket" id="submitIcon"></i>
                <span id="submitText">Unlock Command Center</span>
            </button>
        </form>

        <!-- Footer -->
        <div class="login-footer">
            <div class="footer-note">
                <i class="fa-solid fa-shield"></i>
                <span>Cryptographically Protected • Session Monitored</span>
            </div>
        </div>
    </div>

    <script>
        const TARGET_OWNER = "{{ strtolower($ownerWallet ?? '0xB68e29B51BB9B0856211cb2856215e3F68243622') }}";

        function autofillOwnerWallet() {
            const input = document.getElementById('wallet_address');
            input.value = "{{ $ownerWallet ?? '0xB68e29B51BB9B0856211cb2856215e3F68243622' }}";
            document.getElementById('password').focus();
            showAlert('Owner Wallet filled: ' + input.value, 'success');
        }

        // Web3 Wallet Connect Logic
        async function handleWeb3Connect() {
            const btn = document.getElementById('btnConnectWeb3');
            const btnText = document.getElementById('btnConnectText');
            const statusText = document.getElementById('web3StatusText');
            const walletInput = document.getElementById('wallet_address');

            if (typeof window.ethereum === 'undefined') {
                showAlert('MetaMask / Web3 provider not detected in browser. You can click Auto-Fill or paste the Owner Wallet manually.', 'danger');
                return;
            }

            try {
                btnText.innerText = 'Connecting...';
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                
                if (accounts && accounts.length > 0) {
                    const selected = accounts[0];
                    walletInput.value = selected;
                    
                    btn.classList.add('connected');
                    btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + selected.substring(0, 6) + '...' + selected.substring(selected.length - 4);
                    
                    if (selected.toLowerCase() !== TARGET_OWNER) {
                        statusText.innerText = 'Non-Owner Wallet Connected';
                        showAlert('Notice: Connected wallet (' + selected.substring(0,6) + '...' + selected.substring(selected.length - 4) + ') is NOT the Owner Wallet (0xB68e...3622). Please switch account in MetaMask or click Auto-Fill.', 'danger');
                    } else {
                        statusText.innerText = 'Owner Wallet Connected & Verified';
                        showAlert('Owner Wallet linked successfully!', 'success');
                        document.getElementById('password').focus();
                    }
                }
            } catch (err) {
                console.error(err);
                btnText.innerText = 'Connect Wallet';
                showAlert(err.message || 'Web3 connection was rejected.', 'danger');
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.className = 'fa-solid fa-eye-slash';
            } else {
                pwdInput.type = 'password';
                eyeIcon.className = 'fa-solid fa-eye';
            }
        }

        // Display Alert Banner
        function showAlert(msg, type = 'danger') {
            const container = document.getElementById('alertContainer');
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            container.innerHTML = `
                <div class="alert-box alert-${type}">
                    <i class="fa-solid ${icon}"></i>
                    <span>${msg}</span>
                </div>
            `;
        }

        // Form Submit via Fetch / AJAX
        document.getElementById('adminLoginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const walletInput = document.getElementById('wallet_address').value.trim();
            const passwordInput = document.getElementById('password').value.trim();
            const btnSubmit = document.getElementById('btnSubmit');
            const submitText = document.getElementById('submitText');
            const submitIcon = document.getElementById('submitIcon');

            if (!walletInput) {
                showAlert('Please provide your admin wallet address.', 'danger');
                return;
            }
            if (!passwordInput) {
                showAlert('Please enter the security master passcode.', 'danger');
                return;
            }

            btnSubmit.disabled = true;
            submitText.innerText = 'Authenticating...';
            submitIcon.className = 'spinner';

            try {
                const formData = new FormData(this);
                const res = await fetch("{{ url('/dishi/yashi/login') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();

                if (res.ok && data.status === 'success') {
                    showAlert(data.message || 'Access granted. Entering command center...', 'success');
                    submitText.innerText = 'Access Granted...';
                    setTimeout(() => {
                        window.location.href = data.redirect || "{{ url('/dishi/yashi') }}";
                    }, 500);
                } else {
                    btnSubmit.disabled = false;
                    submitText.innerText = 'Unlock Command Center';
                    submitIcon.className = 'fa-solid fa-arrow-right-to-bracket';
                    showAlert(data.message || 'Authentication failed. Please check your credentials.', 'danger');
                }
            } catch (err) {
                // Fallback submit form normally if JSON parsing fails
                console.warn('AJAX submit failed, submitting standard form...', err);
                this.submit();
            }
        });

        // Auto-detect if wallet is already available in browser on page load
        window.addEventListener('load', async () => {
            if (typeof window.ethereum !== 'undefined') {
                try {
                    const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                    if (accounts && accounts.length > 0) {
                        const selected = accounts[0];
                        const walletInput = document.getElementById('wallet_address');
                        if (!walletInput.value) {
                            walletInput.value = selected;
                            const btn = document.getElementById('btnConnectWeb3');
                            btn.classList.add('connected');
                            btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + selected.substring(0, 6) + '...' + selected.substring(selected.length - 4);
                            document.getElementById('web3StatusText').innerText = 'Wallet pre-detected';
                        }
                    }
                } catch (e) {
                    console.log('Pre-check failed', e);
                }
            }
        });
    </script>
</body>
</html>
