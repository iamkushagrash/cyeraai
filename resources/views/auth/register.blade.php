<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cyera AI | Register Account</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Dashboard Theme CSS -->
    <link href="{{ asset('css/cyera-dashboard.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #000000;
            --card-bg: #060609;
            --card-border: rgba(245, 166, 35, 0.32);
            --input-bg: #09090d;
            --gold-primary: #F5A623;
            --gold-gradient: linear-gradient(135deg, #FFD700 0%, #F5A623 50%, #D48806 100%);
            --gold-glow: rgba(245, 166, 35, 0.45);
            --cyan-accent: #FFD700;
            --green-active: #00FF88;
            --text-primary: #FFFFFF;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --radius-card: 24px;
            --radius-btn: 14px;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif !important;
            background-color: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
            padding: 24px 16px;
        }

        /* Master Container */
        .auth-master-shell {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin: auto;
            animation: authFadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes authFadeUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Luxury Unified Card (Pitch Black Cyber Aesthetic) */
        .auth-card-unified {
            background: #060609;
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border-radius: var(--radius-card);
            border: 1px solid var(--card-border);
            box-shadow: 
                0 32px 64px -16px rgba(0, 0, 0, 0.98),
                0 0 35px rgba(245, 166, 35, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.12);
            padding: 30px 26px 26px 26px;
            position: relative;
            overflow: hidden;
        }

        /* Top Edge Gold Shimmer */
        .auth-card-unified::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.9), rgba(245, 166, 35, 0.8), transparent);
        }

        /* Inside Card Header */
        .card-brand-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .inside-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .inside-logo-wrap:hover {
            transform: scale(1.05);
        }

        .inside-logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 16px rgba(245, 166, 35, 0.5));
        }

        .inside-live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.32);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #FFD700;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .live-dot-pulse {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #FFD700;
            box-shadow: 0 0 8px #FFD700;
            animation: liveDotPing 1.8s ease-in-out infinite;
        }

        @keyframes liveDotPing {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.35; transform: scale(0.8); }
        }

        .card-auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF3C4 60%, var(--gold-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 3px;
        }

        .card-auth-subtitle {
            font-size: 0.82rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        /* Wallet Connected Banner */
        .wallet-connected-banner {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: rgba(0, 255, 136, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 12px;
            margin-bottom: 14px;
            font-size: 0.82rem;
            color: #00FF88;
            word-break: break-all;
        }

        .wallet-prompt-banner {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 14px;
        }

        /* Form Fields */
        .form-field-group {
            margin-bottom: 13px;
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            font-weight: 600;
            color: #E2E8F0;
            margin-bottom: 5px;
            letter-spacing: 0.2px;
        }

        .field-label .label-icon {
            color: var(--gold-primary);
            margin-right: 6px;
        }

        .field-label .req-star {
            color: #F43F5E;
            font-size: 0.85rem;
        }

        .input-glass-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: #09090d;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .input-glass-wrap:focus-within {
            border-color: var(--gold-primary);
            box-shadow: 0 0 20px rgba(245, 166, 35, 0.3), inset 0 0 8px rgba(245, 166, 35, 0.08);
            background: #0d0d12;
        }

        .input-leading-icon {
            width: 42px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-primary);
            font-size: 0.95rem;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(245, 166, 35, 0.04);
            transition: all 0.2s ease;
        }

        .input-glass-wrap:focus-within .input-leading-icon {
            color: #FFD700;
            background: rgba(245, 166, 35, 0.08);
        }

        .input-control-styled {
            flex: 1;
            height: 44px;
            padding: 0 12px;
            background: transparent;
            border: none;
            outline: none;
            color: #FFFFFF;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .input-control-styled::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .country-select-styled {
            height: 44px;
            background: #0d0d12;
            border: none;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            color: #FFD700;
            font-family: 'Inter', sans-serif;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 0 22px 0 10px;
            outline: none;
            cursor: pointer;
            max-width: 125px;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%23F5A623' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 7px center;
        }

        .country-select-styled option {
            background: #050508;
            color: #fff;
        }

        /* Verified Sponsor Pill */
        .sponsor-verified-badge {
            display: none;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            font-size: 0.80rem;
            font-weight: 600;
            color: #00FF88;
            animation: badgeSlideIn 0.3s ease;
        }

        @keyframes badgeSlideIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .eye-toggle-action {
            background: transparent;
            border: none;
            color: var(--text-muted);
            width: 42px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.90rem;
            transition: color 0.2s ease;
            outline: none;
        }

        .eye-toggle-action:hover {
            color: #FFFFFF;
        }

        /* Error Hint */
        .error-hint-msg {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 5px;
            font-size: 0.76rem;
            color: #FB7185;
            font-weight: 500;
        }

        /* Alerts */
        .auth-alert-banner {
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: alertFadeIn 0.3s ease;
        }

        @keyframes alertFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-alert-success {
            background: rgba(0, 255, 136, 0.12);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: #00FF88;
        }

        .auth-alert-error {
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #FB7185;
        }

        /* Terms & Conditions Row */
        .terms-condition-row {
            margin: 12px 0 18px 0;
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 0.81rem;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        .terms-condition-row input[type="checkbox"] {
            accent-color: var(--gold-primary);
            width: 16px;
            height: 16px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-condition-row a {
            color: var(--gold-primary);
            text-decoration: underline;
            font-weight: 500;
        }

        /* Primary Submit Button */
        .btn-submit-gold {
            width: 100%;
            height: 48px;
            border: none;
            outline: none;
            border-radius: var(--radius-btn);
            background: var(--gold-gradient);
            color: #060912;
            font-family: 'Outfit', sans-serif;
            font-size: 0.98rem;
            font-weight: 800;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            cursor: pointer;
            box-shadow: 0 10px 24px var(--gold-glow), inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-submit-gold:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            filter: grayscale(0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            transform: none !important;
        }

        .btn-submit-gold:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(245, 166, 35, 0.6);
        }

        .btn-submit-gold:not(:disabled):active {
            transform: translateY(0);
        }

        .btn-spinner-icon {
            display: none;
            width: 22px;
            height: 22px;
            border: 2.5px solid #060912;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinCircle 0.8s linear infinite;
        }

        @keyframes spinCircle {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .auth-divider-line {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 22px 0 18px 0;
            color: var(--text-muted);
            font-size: 0.76rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .auth-divider-line::before,
        .auth-divider-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-subtle);
        }

        /* Secondary Button (Sign In) */
        .btn-action-secondary {
            width: 100%;
            height: 48px;
            border-radius: var(--radius-btn);
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(245, 166, 35, 0.35);
            color: #FFD700;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
        }

        .btn-action-secondary:hover {
            background: rgba(245, 166, 35, 0.08);
            border-color: var(--gold-primary);
            box-shadow: 0 0 22px rgba(245, 166, 35, 0.25);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Success Registration Credentials Box */
        .success-cred-box {
            background: rgba(0, 255, 136, 0.06);
            border: 1px solid rgba(0, 255, 136, 0.25);
            border-radius: 14px;
            padding: 22px 18px;
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cred-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            background: rgba(6, 10, 20, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
        }

        .cred-item-label {
            font-size: 0.82rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .cred-item-value {
            font-size: 1rem;
            font-weight: 700;
            color: #FFD700;
            letter-spacing: 0.5px;
        }

        /* Clean Footer */
        .auth-footer-block {
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-nav-links a:hover {
            color: var(--gold-primary);
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
            }
            .auth-card-unified {
                padding: 28px 20px 24px 20px;
                border-radius: 20px;
            }
            .card-auth-title {
                font-size: 1.45rem;
            }
            .country-select-styled {
                max-width: 100px;
                font-size: 0.82rem;
            }
            .inside-logo-img {
                height: 40px;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================================
         LIVE ANIMATED CYBER VIDEO CANVAS BACKGROUND (DITTO DASHBOARD)
         ============================================================ -->
    <div class="cyber-video-bg-container" aria-hidden="true">
        <div class="cyber-nebula-orb orb-1"></div>
        <div class="cyber-nebula-orb orb-2"></div>
        <div class="cyber-nebula-orb orb-3"></div>
        <div class="cyber-grid-scan-layer"></div>
        <canvas id="cyberMatrixCanvas"></canvas>
    </div>

    <!-- Main Container -->
    <div class="auth-master-shell">
        
        <!-- Luxury Unified Card (With Logo Inside) -->
        <div class="auth-card-unified">
            
            <!-- Inside Card Brand Header -->
            <div class="card-brand-header">
                <a href="{{ url('/') }}" class="inside-logo-wrap" title="Cyera AI">
                    <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="inside-logo-img">
                </a>
                <div class="inside-live-badge">
                    <span class="live-dot-pulse"></span>
                    <span>WEB3 REGISTRATION</span>
                </div>
                <h1 class="card-auth-title">Create Account</h1>
                <p class="card-auth-subtitle">Join the Cyera AI decentralized network</p>
            </div>

            <!-- Session Alerts -->
            @if (session('success'))
                <div class="auth-alert-banner auth-alert-success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="auth-alert-banner auth-alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="auth-alert-banner auth-alert-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="auth-alert-banner auth-alert-error" style="display: flex; flex-direction: column; align-items: flex-start; gap: 5px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 700;">
                        <i class="fas fa-triangle-exclamation"></i>
                        <span>Registration Failed:</span>
                    </div>
                    <ul style="margin: 0; padding-left: 20px; font-size: 0.82rem; list-style-type: disc; color: #FFFFFF;">
                        @foreach ($errors->all() as $errorMsg)
                            <li>{{ $errorMsg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="regWeb3Alert" class="auth-alert-banner auth-alert-error" style="display: none;">
                <i class="fas fa-circle-exclamation"></i>
                <span id="regWeb3AlertText"></span>
            </div>

            @if(!session('success'))
            
            @php
                $prefilledWallet = old('wallet_address', request('wallet', $wallet ?? ''));
                $prefilledRef = old('referrer', !empty($userid) ? $userid : request('ref', request('sponsor', request('referral', ''))));
            @endphp

            <!-- Web3 Wallet Connection Banner -->
            <div class="wallet-connected-banner" id="walletConnectedBanner" style="{{ !empty($prefilledWallet) ? 'display: flex;' : 'display: none;' }}">
                <i class="fas fa-circle-check" style="color: #00FF88;"></i>
                <span>Linked BEP-20 Wallet: <strong id="walletDisplay">{{ !empty($prefilledWallet) ? substr($prefilledWallet, 0, 6) . '...' . substr($prefilledWallet, -4) : '' }}</strong></span>
            </div>

            @error('wallet_address')
                <div class="auth-alert-banner auth-alert-error" style="margin-top: -6px;">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <div class="wallet-prompt-banner" id="walletPromptBox" style="{{ empty($prefilledWallet) ? 'display: flex;' : 'display: none;' }}">
                <button type="button" class="btn-submit-gold" id="btnConnectRegWallet" style="height: 46px; font-size: 0.92rem; background: linear-gradient(135deg, #F5A623 0%, #D48806 100%);">
                    <i class="fas fa-wallet"></i>
                    <span id="regWalletBtnText">Connect MetaMask / TrustWallet (Required)</span>
                    <div class="btn-spinner-icon" id="regWalletSpinner" style="display: none;"></div>
                </button>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf

                <input type="hidden" name="wallet_address" id="wallet_address" value="{{ $prefilledWallet }}">

                <!-- Sponsor ID -->
                <div class="form-field-group">
                    <label class="field-label" for="referrer">
                        <span><i class="fas fa-id-card-clip label-icon"></i> Sponsor ID</span>
                        <span class="req-star">*</span>
                    </label>
                    <div class="input-glass-wrap">
                        <div class="input-leading-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <input type="text" name="referrer" id="referrer" class="input-control-styled" placeholder="Enter Sponsor ID (e.g. CAI000001)" value="{{ $prefilledRef }}" required autofocus>
                    </div>
                    <!-- Live Verified Sponsor Name Display -->
                    <div class="sponsor-verified-badge" id="spdiv">
                        <i class="fas fa-circle-check"></i>
                        <span>Sponsor: <strong id="spname_text"></strong></span>
                    </div>
                    <div class="error-hint-msg" id="sperr" style="display: none;">
                        <i class="fas fa-circle-exclamation"></i> Invalid Sponsor ID. Please verify your sponsor code.
                    </div>
                    <input type="hidden" id="spname" name="referrername">
                    @error('referrer')
                        <div class="error-hint-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Full Name -->
                <div class="form-field-group">
                    <label class="field-label" for="name">
                        <span><i class="fas fa-user label-icon"></i> Full Name</span>
                        <span class="req-star">*</span>
                    </label>
                    <div class="input-glass-wrap">
                        <div class="input-leading-icon">
                            <i class="fas fa-signature"></i>
                        </div>
                        <input type="text" name="name" id="name" class="input-control-styled" placeholder="Enter your full name" value="{{ old('name') }}" required autocomplete="name">
                    </div>
                    @error('name')
                        <div class="error-hint-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Terms & Conditions Checkbox -->
                <label class="terms-condition-row">
                    <input type="checkbox" id="agreeTerms" required>
                    <span>I agree to Cyera AI <a href="{{ url('/terms') }}" target="_blank">Terms & Conditions</a></span>
                </label>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit-gold" id="registerBtn" disabled>
                    <span id="btnText"><i class="fas fa-user-plus"></i> Create Account</span>
                    <div class="btn-spinner-icon" id="btnSpinner"></div>
                </button>

                <!-- Divider -->
                <div class="auth-divider-line">
                    <span>Already have an account?</span>
                </div>

                <!-- Sign In Link -->
                <a href="{{ url('/login') }}" class="btn-action-secondary">
                    <i class="fas fa-arrow-right-to-bracket"></i> Sign In to Dashboard
                </a>

            </form>
            @endif

            @if(session('success'))
            <!-- Registration Completed Details Card -->
            <div class="success-cred-box">
                <div class="cred-item-row">
                    <span class="cred-item-label">Assigned User ID:</span>
                    <span class="cred-item-value">{{ session('details.uniqueid') }}</span>
                </div>
                <div class="cred-item-row">
                    <span class="cred-item-label">Full Name:</span>
                    <span class="cred-item-value" style="font-size:0.95rem; color: #FFFFFF;">{{ session('details.name') }}</span>
                </div>
                @if(!empty(session('details.wallet')))
                <div class="cred-item-row" style="flex-wrap: wrap; gap: 6px;">
                    <span class="cred-item-label" style="white-space: nowrap;">Linked BEP-20:</span>
                    <span class="cred-item-value" style="font-size:0.80rem; font-family: monospace; color: #00FF88; word-break: break-all;">{{ session('details.wallet') }}</span>
                </div>
                @endif
            </div>

            <a href="{{ url('/login') }}" class="btn-submit-gold" style="text-decoration:none;">
                <i class="fas fa-arrow-right-to-bracket"></i> Proceed to Login
            </a>
            @endif

        </div>

        <!-- Clean Footer -->
        <footer class="auth-footer-block">
            <div class="footer-nav-links">
                <a href="{{ url('/terms') }}">Terms of Service</a>
                <span>•</span>
                <a href="{{ url('/terms') }}">Privacy Policy</a>
                <span>•</span>
                <a href="https://t.me/" target="_blank">Support</a>
            </div>
            <div>&copy; 2026 CYERA AI. All Rights Reserved.</div>
        </footer>

    </div>

    <!-- ============================================================
         CYBER PARTICLES & BEAM MATRIX CANVAS SCRIPT (DITTO DASHBOARD)
         ============================================================ -->
    <script>
    (function () {
        const canvas = document.getElementById('cyberMatrixCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        let circuitBeams = [];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            initElements();
        }

        function initElements() {
            particles = [];
            circuitBeams = [];
            const count = Math.min(Math.floor((width * height) / 20000), 45);

            for (let i = 0; i < count; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    radius: Math.random() * 2.2 + 1.0,
                    vx: (Math.random() - 0.5) * 0.45,
                    vy: (Math.random() - 0.5) * 0.45,
                    alpha: Math.random() * 0.65 + 0.35,
                    color: Math.random() > 0.45 ? '#FFD700' : (Math.random() > 0.5 ? '#00FF88' : '#00E5FF'),
                    pulsing: Math.random() * Math.PI,
                    pulseSpeed: Math.random() * 0.005 + 0.002
                });
            }

            for (let i = 0; i < 6; i++) {
                circuitBeams.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    length: Math.random() * 90 + 45,
                    speed: Math.random() * 0.4 + 0.2,
                    vertical: Math.random() > 0.45,
                    alpha: Math.random() * 0.45 + 0.25,
                    color: Math.random() > 0.4 ? 'rgba(255, 215, 0, ' : (Math.random() > 0.5 ? 'rgba(0, 255, 136, ' : 'rgba(0, 229, 255, ')
                });
            }
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            // Connect nearest nodes with laser circuit lines
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 130) {
                        const alpha = (1 - dist / 130) * 0.35;
                        ctx.strokeStyle = `rgba(255, 215, 0, ${alpha})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].y, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            // Draw and update glowing particles
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;
                p.pulsing += p.pulseSpeed;
                if (p.y < -15) p.y = height + 15;
                if (p.x < -15) p.x = width + 15;
                if (p.x > width + 15) p.x = -15;

                const currentAlpha = Math.max(0.2, p.alpha + Math.sin(p.pulsing) * 0.3);
                ctx.save();
                ctx.globalAlpha = currentAlpha;
                ctx.shadowBlur = 14;
                ctx.shadowColor = p.color;
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }

            // Draw high-speed laser beams
            for (let i = 0; i < circuitBeams.length; i++) {
                const b = circuitBeams[i];
                ctx.save();
                ctx.lineWidth = 1.8;
                ctx.shadowBlur = 10;
                ctx.shadowColor = b.color + '0.8)';
                const grad = b.vertical
                    ? ctx.createLinearGradient(b.x, b.y - b.length, b.x, b.y)
                    : ctx.createLinearGradient(b.x - b.length, b.y, b.x, b.y);
                grad.addColorStop(0, b.color + '0)');
                grad.addColorStop(0.7, b.color + (b.alpha * 0.8) + ')');
                grad.addColorStop(1, b.color + b.alpha + ')');
                ctx.strokeStyle = grad;
                ctx.beginPath();
                if (b.vertical) {
                    ctx.moveTo(b.x, b.y - b.length);
                    ctx.lineTo(b.x, b.y);
                    b.y += b.speed;
                    if (b.y - b.length > height) {
                        b.y = 0;
                        b.x = Math.random() * width;
                    }
                } else {
                    ctx.moveTo(b.x - b.length, b.y);
                    ctx.lineTo(b.x, b.y);
                    b.x += b.speed;
                    if (b.x - b.length > width) {
                        b.x = 0;
                        b.y = Math.random() * height;
                    }
                }
                ctx.stroke();
                ctx.restore();
            }

            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', resize);
        resize();
        requestAnimationFrame(animate);
    })();
    </script>

    <!-- jQuery for AJAX Sponsor Lookup & Web3 Provider -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function getMetaMaskProvider() {
            if (typeof window.ethereum !== 'undefined') {
                if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                    const mm = window.ethereum.providers.find(p => p.isMetaMask && !p.isPhantom);
                    if (mm) return mm;
                    const known = window.ethereum.providers.find(p => p.isSafePal || p.isTrust || p.isTrustWallet || p.isBinance || p.isOkxWallet || p.isTokenPocket || p.isBitKeep);
                    if (known) return known;
                    return window.ethereum.providers[0];
                }
                return window.ethereum;
            }
            if (window.safepal) return window.safepal;
            if (window.phantom && window.phantom.ethereum) return window.phantom.ethereum;
            if (window.trustwallet) return window.trustwallet;
            if (window.okxwallet) return window.okxwallet;
            if (window.tokenpocket) return window.tokenpocket;
            if (window.binance) return window.binance;
            if (window.bitkeep && window.bitkeep.ethereum) return window.bitkeep.ethereum;
            return null;
        }

        function showRegError(msg) {
            $("#regWeb3AlertText").text(msg);
            $("#regWeb3Alert").removeClass('auth-alert-success').addClass('auth-alert-error').show();
        }

        let isConnectingWallet = false;

        async function connectRegisterWallet(isAutoPrompt = false) {
            if (isConnectingWallet) return;
            const currentWallet = $("#wallet_address").val();
            if (currentWallet && currentWallet.trim() !== '') return;

            const provider = getMetaMaskProvider();
            if (!provider) {
                if (!isAutoPrompt) {
                    showRegError('MetaMask / Web3 Wallet not detected! Please open inside MetaMask or TrustWallet dApp browser.');
                }
                return;
            }

            $("#regWeb3Alert").hide();
            isConnectingWallet = true;

            const btnConnectRegWallet = document.getElementById('btnConnectRegWallet');
            const regWalletBtnText = document.getElementById('regWalletBtnText');
            const regWalletSpinner = document.getElementById('regWalletSpinner');

            if (regWalletBtnText) regWalletBtnText.innerText = 'Connecting Web3 Wallet...';
            if (regWalletSpinner) regWalletSpinner.style.display = 'block';
            if (btnConnectRegWallet) btnConnectRegWallet.style.pointerEvents = 'none';

            try {
                // First check if already authorized
                let accounts = [];
                try {
                    accounts = await provider.request({ method: 'eth_accounts' });
                } catch (e) {}

                // If not authorized or prompted explicitly, request accounts
                if (!accounts || accounts.length === 0) {
                    accounts = await provider.request({ method: 'eth_requestAccounts' });
                }

                if (!accounts || accounts.length === 0) {
                    throw new Error('No wallet account selected.');
                }

                const walletAddr = accounts[0];

                // Check if this wallet is ALREADY registered in Cyera AI
                const checkRes = await fetch("{{ url('/auth/check-wallet') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        address: walletAddr
                    })
                });

                const checkData = await checkRes.json();

                if (checkData.status === 'success' && checkData.is_registered) {
                    if (regWalletBtnText) regWalletBtnText.innerText = 'Wallet Registered! Redirecting...';
                    $("#regWeb3AlertText").html('<i class="fas fa-circle-check" style="color: #00FF88;"></i> This wallet is already registered. Redirecting to Sign In...');
                    $("#regWeb3Alert").removeClass('auth-alert-error').addClass('auth-alert-success').show();
                    setTimeout(function() {
                        window.location.href = "{{ url('/login') }}";
                    }, 1200);
                    return;
                }

                // New unregistered wallet -> Attach to registration form
                $("#wallet_address").val(walletAddr);
                const shortAddr = walletAddr.substring(0, 6) + '...' + walletAddr.substring(walletAddr.length - 4);
                $("#walletDisplay").text(shortAddr);
                $("#walletConnectedBanner").css('display', 'flex');
                $("#walletPromptBox").hide();

                if (regWalletBtnText) regWalletBtnText.innerText = 'Connect MetaMask / TrustWallet (Required)';
                if (regWalletSpinner) regWalletSpinner.style.display = 'none';
                if (btnConnectRegWallet) btnConnectRegWallet.style.pointerEvents = 'auto';

                updateSubmitButtonState();

                // Setup live accountsChanged listener
                if (provider.on) {
                    provider.on('accountsChanged', function (newAccounts) {
                        if (newAccounts && newAccounts.length > 0) {
                            $("#wallet_address").val(newAccounts[0]);
                            const newShort = newAccounts[0].substring(0, 6) + '...' + newAccounts[0].substring(newAccounts[0].length - 4);
                            $("#walletDisplay").text(newShort);
                        } else {
                            $("#wallet_address").val('');
                            $("#walletConnectedBanner").hide();
                            $("#walletPromptBox").show();
                            updateSubmitButtonState();
                        }
                    });
                }

            } catch (err) {
                if (regWalletBtnText) regWalletBtnText.innerText = 'Connect MetaMask / TrustWallet (Required)';
                if (regWalletSpinner) regWalletSpinner.style.display = 'none';
                if (btnConnectRegWallet) btnConnectRegWallet.style.pointerEvents = 'auto';
                if (!isAutoPrompt) {
                    showRegError(err.message || 'Wallet connection was cancelled.');
                }
            } finally {
                isConnectingWallet = false;
            }
        }

        // Sponsor Lookup via AJAX
        function checkSponsor() {
            var val = $("#referrer").val().trim();
            if (val.length >= 4) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/getSponsor') }}/" + encodeURIComponent(val),
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 0 && data.name) {
                            $("#spdiv").css('display', 'flex');
                            $("#sperr").hide();
                            $("#spname_text").text(data.name);
                            $("#spname").val(data.name);
                        } else {
                            $("#spdiv").hide();
                            $("#sperr").show();
                            $("#spname").val('');
                        }
                    },
                    error: function() {
                        $("#spdiv").hide();
                        $("#sperr").show();
                    }
                });
            } else {
                $("#spdiv").hide();
                $("#sperr").hide();
                $("#spname").val('');
            }
        }

        $(document).ready(function() {
            // Check sponsor if prefilled
            if ($("#referrer").val() != "") {
                checkSponsor();
            }
            $("#referrer").on('keyup blur change', function() {
                checkSponsor();
            });

            // Connect button click trigger
            const btnConnectReg = document.getElementById('btnConnectRegWallet');
            if (btnConnectReg) {
                btnConnectReg.addEventListener('click', function() {
                    connectRegisterWallet(false);
                });
            }

            // AUTO-CONNECT / POPUP ON PAGE LOAD (especially via referral link)
            const currentWallet = $("#wallet_address").val();
            if (!currentWallet || currentWallet.trim() === '') {
                setTimeout(function() {
                    connectRegisterWallet(true);
                }, 350);
            }

            updateSubmitButtonState();
        });

        // Terms & Wallet Checkbox enabler
        const agreeCheckbox = document.getElementById('agreeTerms');
        const registerBtn = document.getElementById('registerBtn');

        function updateSubmitButtonState() {
            if (!registerBtn) return;
            const isWalletConnected = $("#wallet_address").val() && $("#wallet_address").val().trim() !== '';
            const isTermsAgreed = agreeCheckbox ? agreeCheckbox.checked : false;

            registerBtn.disabled = !(isWalletConnected && isTermsAgreed);
        }

        if (agreeCheckbox) {
            agreeCheckbox.addEventListener('change', updateSubmitButtonState);
        }

        // Form Submit Loading State
        const regForm = document.getElementById('registerForm');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        if (regForm && registerBtn) {
            regForm.addEventListener('submit', function(e) {
                const wAddr = $("#wallet_address").val();
                if (!wAddr || wAddr.trim() === '') {
                    e.preventDefault();
                    showRegError('Please connect your Web3 Wallet before submitting the registration form.');
                    return false;
                }
                registerBtn.style.pointerEvents = 'none';
                registerBtn.style.opacity = '0.9';
                if (btnText) btnText.style.display = 'none';
                if (btnSpinner) btnSpinner.style.display = 'block';
            });
        }
    </script>
</body>
</html>