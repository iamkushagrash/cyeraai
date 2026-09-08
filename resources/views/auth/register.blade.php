<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cyera AI | Register Membership</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@500;600;700;800;900&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #020204;
            --bg-card: rgba(8, 11, 20, 0.88);
            --bg-card-inner: rgba(4, 6, 12, 0.94);
            --gold-pure: #FFD700;
            --gold-main: #F5A623;
            --gold-light: #FFE082;
            --gold-border: rgba(245, 166, 35, 0.35);
            --gold-glow: rgba(245, 166, 35, 0.25);
            --cyan-neon: #00F0FF;
            --cyan-glow: rgba(0, 240, 255, 0.25);
            --green-neon: #00FF9D;
            --text-white: #FFFFFF;
            --text-muted: #8E99A8;
            --text-dim: #5A6475;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --card-radius: 16px;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-white);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 24px 16px;
        }

        /* Ambient Nebula Glows */
        .ambient-glow {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.6;
        }

        .glow-gold {
            top: -10%;
            left: 50%;
            transform: translateX(-50%);
            width: 550px;
            height: 380px;
            background: radial-gradient(circle, rgba(245, 166, 35, 0.22) 0%, rgba(255, 215, 0, 0.1) 50%, transparent 80%);
        }

        .glow-cyan {
            bottom: -10%;
            right: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 240, 255, 0.15) 0%, rgba(0, 255, 157, 0.08) 50%, transparent 80%);
        }

        /* Matrix Grid & Scanlines */
        .cyber-grid-overlay {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(245, 166, 35, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(245, 166, 35, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 1;
        }

        .scanlines {
            position: fixed;
            inset: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%);
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
        }

        /* Main Shell Container */
        .auth-shell {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 18px;
            margin: auto 0;
        }

        /* Brand Header */
        .auth-brand-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .brand-logo-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            border-radius: 40px;
            background: linear-gradient(180deg, rgba(20, 26, 40, 0.9) 0%, rgba(8, 11, 20, 0.95) 100%);
            border: 1px solid var(--gold-border);
            box-shadow: 0 0 30px rgba(245, 166, 35, 0.2), inset 0 1px 1px rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .brand-logo-wrap:hover {
            border-color: var(--gold-pure);
            box-shadow: 0 0 35px rgba(245, 166, 35, 0.35);
            transform: translateY(-1px);
        }

        .brand-logo-img {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 10px rgba(245, 166, 35, 0.4));
        }

        .brand-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(0, 240, 255, 0.08);
            border: 1px solid rgba(0, 240, 255, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--cyan-neon);
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan-neon);
            box-shadow: 0 0 8px var(--cyan-neon);
            animation: livePing 1.8s ease-in-out infinite;
        }

        @keyframes livePing {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* Mecha Auth Card */
        .mecha-auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: var(--card-radius);
            border: 1px solid var(--gold-border);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.85),
                0 0 30px rgba(245, 166, 35, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            padding: 28px 22px;
        }

        /* Mecha Corner Accents */
        .mecha-auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 24px;
            height: 24px;
            border-top: 2px solid var(--gold-pure);
            border-left: 2px solid var(--gold-pure);
            border-top-left-radius: var(--card-radius);
            pointer-events: none;
        }

        .mecha-auth-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 24px;
            height: 24px;
            border-bottom: 2px solid var(--cyan-neon);
            border-right: 2px solid var(--cyan-neon);
            border-bottom-right-radius: var(--card-radius);
            pointer-events: none;
        }

        /* Card Header */
        .card-auth-header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .card-auth-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            background: linear-gradient(135deg, #FFFFFF 0%, var(--gold-light) 60%, var(--gold-pure) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .card-auth-subtitle {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            letter-spacing: 0.8px;
        }

        /* Cyber Form Group */
        .cyber-form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .cyber-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--gold-light);
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 6px;
        }

        .cyber-label span.req {
            color: #FF4D7D;
        }

        .cyber-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            background: var(--bg-card-inner);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .cyber-input-wrap:focus-within {
            border-color: var(--gold-main);
            box-shadow: 0 0 16px rgba(245, 166, 35, 0.25), inset 0 0 10px rgba(245, 166, 35, 0.05);
            background: rgba(8, 12, 22, 0.95);
        }

        .cyber-input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 46px;
            color: var(--gold-main);
            font-size: 0.95rem;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(245, 166, 35, 0.04);
            transition: color 0.3s ease;
        }

        .cyber-input-wrap:focus-within .cyber-input-icon {
            color: var(--gold-pure);
            background: rgba(245, 166, 35, 0.08);
        }

        .cyber-input {
            flex: 1;
            height: 46px;
            padding: 0 12px;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-white);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .cyber-input::placeholder {
            color: var(--text-dim);
            font-size: 0.82rem;
        }

        /* Country Code Select in Phone Input */
        .cyber-country-select {
            height: 46px;
            background: rgba(14, 19, 32, 0.95);
            border: none;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--gold-light);
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0 8px;
            outline: none;
            cursor: pointer;
            max-width: 140px;
        }

        .cyber-country-select option {
            background: #090e1a;
            color: #fff;
        }

        /* Live Verified Sponsor Pill */
        .sponsor-verified-box {
            display: none;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(0, 255, 157, 0.08);
            border: 1px solid rgba(0, 255, 157, 0.3);
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--green-neon);
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.5px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Password Toggle */
        .pwd-toggle-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            width: 42px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.2s ease;
            outline: none;
        }

        .pwd-toggle-btn:hover {
            color: var(--gold-light);
        }

        /* Validation Error */
        .cyber-error-msg {
            display: block;
            margin-top: 5px;
            font-size: 0.75rem;
            color: #FF5A79;
            font-weight: 600;
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.5px;
        }

        /* Alert Messages */
        .cyber-alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: alertSlide 0.3s ease;
        }

        @keyframes alertSlide {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cyber-alert-success {
            background: rgba(0, 255, 157, 0.1);
            border: 1px solid rgba(0, 255, 157, 0.3);
            color: var(--green-neon);
        }

        .cyber-alert-error {
            background: rgba(255, 77, 125, 0.1);
            border: 1px solid rgba(255, 77, 125, 0.3);
            color: #FF4D7D;
        }

        /* Terms Checkbox */
        .terms-row {
            margin: 12px 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .terms-row input[type="checkbox"] {
            accent-color: var(--gold-main);
            width: 17px;
            height: 17px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-row a {
            color: var(--cyan-neon);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-mecha-submit {
            width: 100%;
            height: 52px;
            border: none;
            outline: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #F5A623 0%, #FFD700 50%, #D48806 100%);
            color: #05060A;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(245, 166, 35, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-mecha-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
            transform: none !important;
        }

        .btn-mecha-submit:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(245, 166, 35, 0.55);
        }

        .btn-mecha-submit:not(:disabled):active {
            transform: translateY(0);
        }

        .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0 16px 0;
            color: var(--text-dim);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-subtle);
        }

        /* Login Secondary Button */
        .btn-mecha-secondary {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 240, 255, 0.3);
            color: var(--cyan-neon);
            font-family: 'Orbitron', sans-serif;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-mecha-secondary:hover {
            background: rgba(0, 240, 255, 0.1);
            border-color: var(--cyan-neon);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.25);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Success Registration Credentials Box */
        .cyber-success-box {
            background: rgba(0, 255, 157, 0.05);
            border: 1px solid rgba(0, 255, 157, 0.3);
            border-radius: 12px;
            padding: 20px 16px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .success-cred-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 8px;
            font-family: 'Rajdhani', sans-serif;
        }

        .cred-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
        }

        .cred-val {
            font-size: 1rem;
            font-weight: 800;
            color: var(--gold-light);
            letter-spacing: 1px;
            font-family: 'Orbitron', sans-serif;
        }

        /* Bottom Footer */
        .auth-footer {
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-dim);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-nav {
            display: flex;
            justify-content: center;
            gap: 18px;
        }

        .footer-nav a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-nav a:hover {
            color: var(--gold-light);
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 16px 10px;
            }
            .mecha-auth-card {
                padding: 22px 16px;
            }
            .card-auth-title {
                font-size: 1.2rem;
            }
            .cyber-country-select {
                max-width: 110px;
                font-size: 0.82rem;
            }
        }
    </style>
</head>
<body>

    <!-- Background Ambient & Overlays -->
    <div class="ambient-glow glow-gold"></div>
    <div class="ambient-glow glow-cyan"></div>
    <div class="cyber-grid-overlay"></div>
    <div class="scanlines"></div>

    <!-- Main Shell -->
    <div class="auth-shell">
        
        <!-- Brand Header -->
        <div class="auth-brand-header">
            <a href="{{ url('/') }}" class="brand-logo-wrap" title="Cyera AI">
                <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="brand-logo-img">
            </a>
            <div class="brand-live-pill">
                <span class="live-dot"></span>
                <span>INITIALIZE NEW MEMBERSHIP</span>
            </div>
        </div>

        <!-- Auth Card -->
        <div class="mecha-auth-card">
            
            <div class="card-auth-header">
                <h1 class="card-auth-title">Create Account</h1>
                <p class="card-auth-subtitle">>_ Join the Cyera AI decentralized network</p>
            </div>

            <!-- Session Alerts -->
            @if (session('success'))
                <div class="cyber-alert cyber-alert-success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="cyber-alert cyber-alert-error">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if(!session('success'))
            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf

                <!-- Sponsor ID -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="referrer">
                        <span><i class="fas fa-id-card-clip"></i> Sponsor ID</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <input type="text" name="referrer" id="referrer" class="cyber-input" placeholder="Enter Sponsor ID" @if(!empty($userid)) value="{{$userid}}" @else value="{{old('referrer')}}" @endif required autofocus>
                    </div>
                    <!-- Live Verified Sponsor Name Display -->
                    <div class="sponsor-verified-box" id="spdiv">
                        <i class="fas fa-circle-check"></i>
                        <span>SPONSOR: <strong id="spname_text"></strong></span>
                    </div>
                    <input type="hidden" id="spname" name="referrername">
                    @error('referrer')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Full Name -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="name">
                        <span><i class="fas fa-user"></i> Full Name</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-signature"></i>
                        </div>
                        <input type="text" name="name" id="name" class="cyber-input" placeholder="Enter your full name" value="{{ old('name') }}" required autocomplete="name">
                    </div>
                    @error('name')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="email">
                        <span><i class="fas fa-envelope"></i> Email Address</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-at"></i>
                        </div>
                        <input type="email" name="email" id="email" class="cyber-input" placeholder="Enter your email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    @error('email')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact & Country Code -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="contact">
                        <span><i class="fas fa-phone-volume"></i> Mobile Number</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <select name="countrycode" class="cyber-country-select">
                            <option data-countryCode="SG" value="65">SG (+65)</option>
                            <option data-countryCode="IN" value="91" selected>IN (+91)</option>
                            <option data-countryCode="GB" value="44">UK (+44)</option>
                            <option data-countryCode="US" value="1">USA (+1)</option>
                            <option data-countryCode="AE" value="971">UAE (+971)</option>
                            <option value="61">AU (+61)</option>
                            <option value="1">CA (+1)</option>
                            <option value="49">DE (+49)</option>
                            <option value="33">FR (+33)</option>
                            <option value="81">JP (+81)</option>
                            <option value="60">MY (+60)</option>
                            <option value="63">PH (+63)</option>
                            <option value="966">SA (+966)</option>
                            <option value="66">TH (+66)</option>
                            <option value="84">VN (+84)</option>
                            <option value="27">ZA (+27)</option>
                        </select>
                        <input type="tel" name="contact" id="contact" maxlength="15" class="cyber-input" placeholder="Mobile Number" value="{{ old('contact') }}" required autocomplete="tel">
                    </div>
                    @error('contact')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="password">
                        <span><i class="fas fa-key"></i> Create Password</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" class="cyber-input" placeholder="Minimum 8 characters" required autocomplete="new-password">
                        <button type="button" class="pwd-toggle-btn" id="togglePassword" title="Show / Hide Password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="cyber-error-msg"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="cyber-form-group">
                    <label class="cyber-label" for="password_confirmation">
                        <span><i class="fas fa-shield-check"></i> Confirm Password</span>
                        <span class="req">*</span>
                    </label>
                    <div class="cyber-input-wrap">
                        <div class="cyber-input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="cyber-input" placeholder="Repeat your password" required autocomplete="new-password">
                        <button type="button" class="pwd-toggle-btn" id="togglePasswordConf" title="Show / Hide Password">
                            <i class="fas fa-eye" id="toggleIconConf"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms & Conditions Checkbox -->
                <label class="terms-row">
                    <input type="checkbox" id="agreeTerms" required>
                    <span>I agree to Cyera AI <a href="{{ url('/terms') }}" target="_blank">Terms & Conditions</a></span>
                </label>

                <!-- Submit Button -->
                <button type="submit" class="btn-mecha-submit" id="registerBtn" disabled>
                    <span id="btnText"><i class="fas fa-rocket"></i> INITIALIZE & REGISTER</span>
                    <div class="btn-spinner" id="btnSpinner"></div>
                </button>

                <!-- Divider -->
                <div class="auth-divider">
                    <span>Already have an ID?</span>
                </div>

                <!-- Sign In Link -->
                <a href="{{ url('/login') }}" class="btn-mecha-secondary">
                    <i class="fas fa-sign-in-alt"></i> SIGN IN TO DASHBOARD
                </a>

            </form>
            @endif

            @if(session('success'))
            <!-- Registration Completed Details Card -->
            <div class="cyber-success-box">
                <div class="success-cred-row">
                    <span class="cred-label">Assigned User ID:</span>
                    <span class="cred-val">{{ session('details.uniqueid') }}</span>
                </div>
                <div class="success-cred-row">
                    <span class="cred-label">Email:</span>
                    <span class="cred-val" style="font-size:0.9rem;">{{ session('details.username') }}</span>
                </div>
                <div class="success-cred-row">
                    <span class="cred-label">Password:</span>
                    <span class="cred-val">{{ session('details.password') }}</span>
                </div>
            </div>

            <a href="{{ url('/login') }}" class="btn-mecha-submit" style="text-decoration:none;">
                <i class="fas fa-right-to-bracket"></i> PROCEED TO LOGIN
            </a>

            <div style="margin-top:14px;">
                <a href="{{ url('/register') }}" class="btn-mecha-secondary">
                    <i class="fas fa-user-plus"></i> REGISTER ANOTHER ACCOUNT
                </a>
            </div>
            @endif

        </div>

        <!-- Bottom Footer -->
        <footer class="auth-footer">
            <div class="footer-nav">
                <a href="{{ url('/terms') }}">Terms of Service</a>
                <span>•</span>
                <a href="{{ url('/terms') }}">Privacy Policy</a>
                <span>•</span>
                <a href="https://t.me/" target="_blank">Support</a>
            </div>
            <div>&copy; 2026 CYERA AI. DECENTRALIZED MECHA PROTOCOL.</div>
        </footer>

    </div>

    <!-- jQuery for AJAX Sponsor Lookup -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Sponsor Lookup via AJAX
        function checkSponsor() {
            var val = $("#referrer").val().trim();
            if (val !== "") {
                $.ajax({
                    type: 'GET',
                    url: '/getSponsor/' + val,
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 0 && data.name) {
                            $("#spdiv").css('display', 'flex');
                            $("#spname_text").text(data.name);
                            $("#spname").val(data.name);
                        } else {
                            $("#spdiv").hide();
                            $("#spname").val('');
                        }
                    },
                    error: function() {
                        $("#spdiv").hide();
                    }
                });
            } else {
                $("#spdiv").hide();
            }
        }

        $(document).ready(function() {
            if ($("#referrer").val() != "") {
                checkSponsor();
            }
            $("#referrer").on('blur change', checkSponsor);
        });

        // Terms Checkbox enabler
        const agreeCheckbox = document.getElementById('agreeTerms');
        const registerBtn = document.getElementById('registerBtn');

        if (agreeCheckbox && registerBtn) {
            agreeCheckbox.addEventListener('change', function() {
                registerBtn.disabled = !this.checked;
            });
        }

        // Password Toggles
        function setupPasswordToggle(toggleId, inputId, iconId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (toggle && input && icon) {
                toggle.addEventListener('click', function() {
                    const isPwd = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPwd ? 'text' : 'password');
                    icon.classList.toggle('fa-eye', !isPwd);
                    icon.classList.toggle('fa-eye-slash', isPwd);
                });
            }
        }

        setupPasswordToggle('togglePassword', 'password', 'toggleIcon');
        setupPasswordToggle('togglePasswordConf', 'password_confirmation', 'toggleIconConf');

        // Form Submit Loading State
        const regForm = document.getElementById('registerForm');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        if (regForm && registerBtn) {
            regForm.addEventListener('submit', function() {
                registerBtn.style.pointerEvents = 'none';
                registerBtn.style.opacity = '0.85';
                if (btnText) btnText.style.display = 'none';
                if (btnSpinner) btnSpinner.style.display = 'block';
            });
        }
    </script>
</body>
</html>