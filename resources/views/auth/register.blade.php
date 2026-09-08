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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #03060E;
            --card-bg: rgba(10, 16, 30, 0.82);
            --input-bg: rgba(6, 10, 20, 0.85);
            --gold-primary: #F5A623;
            --gold-gradient: linear-gradient(135deg, #FFD700 0%, #F5A623 50%, #D48806 100%);
            --gold-glow: rgba(245, 166, 35, 0.35);
            --cyan-accent: #00F0FF;
            --green-active: #10B981;
            --text-primary: #FFFFFF;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;
            --border-glass: rgba(245, 166, 35, 0.22);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --radius-card: 20px;
            --radius-btn: 12px;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 30px 16px;
        }

        /* Ambient Fluid Glow Effects */
        .ambient-aurora {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.55;
            transition: all 0.5s ease;
        }

        .aurora-gold {
            top: -12%;
            left: 50%;
            transform: translateX(-50%);
            width: 580px;
            height: 420px;
            background: radial-gradient(circle, rgba(245, 166, 35, 0.28) 0%, rgba(255, 215, 0, 0.12) 40%, transparent 75%);
        }

        .aurora-cyan {
            bottom: -15%;
            right: 5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 240, 255, 0.15) 0%, rgba(16, 185, 129, 0.08) 50%, transparent 75%);
        }

        .bg-subtle-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            z-index: 1;
        }

        /* Main Container Shell */
        .auth-container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin: auto 0;
        }

        /* Brand Header */
        .brand-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .brand-logo-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .brand-logo-link:hover {
            transform: scale(1.03);
        }

        .brand-logo-img {
            height: 44px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 16px rgba(245, 166, 35, 0.45));
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 14px;
            border-radius: 30px;
            background: rgba(0, 240, 255, 0.1);
            border: 1px solid rgba(0, 240, 255, 0.28);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--cyan-accent);
            text-transform: uppercase;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--cyan-accent);
            box-shadow: 0 0 8px var(--cyan-accent);
            animation: pulseGlow 2s infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* Luxury Glass Card */
        .luxury-card {
            background: var(--card-bg);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-radius: var(--radius-card);
            border: 1px solid var(--border-glass);
            box-shadow: 
                0 30px 60px -15px rgba(0, 0, 0, 0.85),
                0 0 35px rgba(245, 166, 35, 0.08),
                inset 0 1px 1px rgba(255, 255, 255, 0.12);
            padding: 34px 28px;
            position: relative;
        }

        .card-header-block {
            text-align: center;
            margin-bottom: 24px;
        }

        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF3C4 60%, var(--gold-primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }

        .card-subtitle {
            font-size: 0.86rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        /* Input Form Groups */
        .input-group-item {
            margin-bottom: 18px;
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 600;
            color: #E2E8F0;
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }

        .field-label .icon-tag {
            color: var(--gold-primary);
            margin-right: 6px;
        }

        .field-label .required-dot {
            color: #F43F5E;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background: var(--input-bg);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .input-wrapper:focus-within {
            border-color: var(--gold-primary);
            box-shadow: 0 0 20px rgba(245, 166, 35, 0.25), inset 0 0 8px rgba(245, 166, 35, 0.05);
            background: rgba(10, 16, 30, 0.95);
        }

        .input-icon-box {
            width: 46px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-primary);
            font-size: 1rem;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.04);
            background: rgba(245, 166, 35, 0.04);
            transition: color 0.2s ease;
        }

        .input-wrapper:focus-within .input-icon-box {
            color: #FFD700;
            background: rgba(245, 166, 35, 0.08);
        }

        .form-control-custom {
            flex: 1;
            height: 48px;
            padding: 0 14px;
            background: transparent;
            border: none;
            outline: none;
            color: #FFFFFF;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            font-weight: 500;
        }

        .form-control-custom::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        .country-select-custom {
            height: 48px;
            background: rgba(14, 20, 36, 0.95);
            border: none;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            color: #FFD700;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0 10px;
            outline: none;
            cursor: pointer;
            max-width: 130px;
        }

        .country-select-custom option {
            background: #090e1a;
            color: #fff;
        }

        /* Verified Sponsor Pill */
        .sponsor-verified-box {
            display: none;
            align-items: center;
            gap: 8px;
            margin-top: 7px;
            padding: 7px 14px;
            border-radius: 10px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            font-size: 0.82rem;
            font-weight: 600;
            color: #34D399;
            animation: fadeIn 0.3s ease;
        }

        .eye-toggle-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            width: 44px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            outline: none;
        }

        .eye-toggle-btn:hover {
            color: #FFFFFF;
        }

        /* Error Text */
        .error-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 0.78rem;
            color: #FB7185;
            font-weight: 500;
        }

        /* Alerts */
        .alert-box {
            padding: 13px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-box-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34D399;
        }

        .alert-box-error {
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #FB7185;
        }

        /* Terms & Conditions Row */
        .terms-row {
            margin: 14px 0 22px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.83rem;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        .terms-row input[type="checkbox"] {
            accent-color: var(--gold-primary);
            width: 17px;
            height: 17px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-row a {
            color: var(--cyan-accent);
            text-decoration: underline;
        }

        /* Primary Submit Button */
        .btn-primary-gold {
            width: 100%;
            height: 52px;
            border: none;
            outline: none;
            border-radius: var(--radius-btn);
            background: var(--gold-gradient);
            color: #060912;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 10px 25px var(--gold-glow), inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-gold:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            box-shadow: none;
            transform: none !important;
        }

        .btn-primary-gold:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(245, 166, 35, 0.5);
        }

        .btn-primary-gold:not(:disabled):active {
            transform: translateY(0);
        }

        .spinner-loader {
            display: none;
            width: 22px;
            height: 22px;
            border: 2.5px solid #060912;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Divider */
        .or-divider {
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

        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-subtle);
        }

        /* Secondary Button (Sign In) */
        .btn-secondary-cyan {
            width: 100%;
            height: 48px;
            border-radius: var(--radius-btn);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 240, 255, 0.28);
            color: var(--cyan-accent);
            font-family: 'Outfit', sans-serif;
            font-size: 0.94rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
        }

        .btn-secondary-cyan:hover {
            background: rgba(0, 240, 255, 0.08);
            border-color: var(--cyan-accent);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
            color: #FFFFFF;
            transform: translateY(-1px);
        }

        /* Success Registration Credentials Box */
        .success-cred-box {
            background: rgba(16, 185, 129, 0.06);
            border: 1px solid rgba(16, 185, 129, 0.25);
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
        .auth-footer-clean {
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links-row {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-links-row a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links-row a:hover {
            color: var(--gold-primary);
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 12px;
            }
            .luxury-card {
                padding: 26px 18px;
                border-radius: 16px;
            }
            .card-title {
                font-size: 1.35rem;
            }
            .country-select-custom {
                max-width: 100px;
                font-size: 0.82rem;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Aurora Background -->
    <div class="ambient-aurora aurora-gold"></div>
    <div class="ambient-aurora aurora-cyan"></div>
    <div class="bg-subtle-grid"></div>

    <!-- Main Container -->
    <div class="auth-container">
        
        <!-- Brand Header -->
        <div class="brand-header">
            <a href="{{ url('/') }}" class="brand-logo-link" title="Cyera AI">
                <img src="{{ asset('logo.png') }}" alt="Cyera AI" class="brand-logo-img">
            </a>
            <div class="brand-badge">
                <span class="pulse-dot"></span>
                <span>NEW MEMBERSHIP</span>
            </div>
        </div>

        <!-- Luxury Card -->
        <div class="luxury-card">
            
            <div class="card-header-block">
                <h1 class="card-title">Create Account</h1>
                <p class="card-subtitle">Join the Cyera AI decentralized network</p>
            </div>

            <!-- Session Alerts -->
            @if (session('success'))
                <div class="alert-box alert-box-success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert-box alert-box-error">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if(!session('success'))
            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf

                <!-- Sponsor ID -->
                <div class="input-group-item">
                    <label class="field-label" for="referrer">
                        <span><i class="fas fa-id-card-clip icon-tag"></i> Sponsor ID</span>
                        <span class="required-dot">*</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon-box">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <input type="text" name="referrer" id="referrer" class="form-control-custom" placeholder="Enter Sponsor ID" @if(!empty($userid)) value="{{$userid}}" @else value="{{old('referrer')}}" @endif required autofocus>
                    </div>
                    <!-- Live Verified Sponsor Name Display -->
                    <div class="sponsor-verified-box" id="spdiv">
                        <i class="fas fa-circle-check"></i>
                        <span>Sponsor: <strong id="spname_text"></strong></span>
                    </div>
                    <input type="hidden" id="spname" name="referrername">
                    @error('referrer')
                        <div class="error-hint"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Full Name -->
                <div class="input-group-item">
                    <label class="field-label" for="name">
                        <span><i class="fas fa-user icon-tag"></i> Full Name</span>
                        <span class="required-dot">*</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon-box">
                            <i class="fas fa-signature"></i>
                        </div>
                        <input type="text" name="name" id="name" class="form-control-custom" placeholder="Enter your full name" value="{{ old('name') }}" required autocomplete="name">
                    </div>
                    @error('name')
                        <div class="error-hint"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="input-group-item">
                    <label class="field-label" for="email">
                        <span><i class="fas fa-envelope icon-tag"></i> Email Address</span>
                        <span class="required-dot">*</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon-box">
                            <i class="fas fa-at"></i>
                        </div>
                        <input type="email" name="email" id="email" class="form-control-custom" placeholder="Enter your email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    @error('email')
                        <div class="error-hint"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Contact & Country Code -->
                <div class="input-group-item">
                    <label class="field-label" for="contact">
                        <span><i class="fas fa-phone-volume icon-tag"></i> Mobile Number</span>
                        <span class="required-dot">*</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon-box">
                            <i class="fas fa-globe"></i>
                        </div>
                        <select name="countrycode" class="country-select-custom">
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
                        <input type="tel" name="contact" id="contact" maxlength="15" class="form-control-custom" placeholder="Mobile Number" value="{{ old('contact') }}" required autocomplete="tel">
                    </div>
                    @error('contact')
                        <div class="error-hint"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group-item">
                    <label class="field-label" for="password">
                        <span><i class="fas fa-key icon-tag"></i> Create Password</span>
                        <span class="required-dot">*</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon-box">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" class="form-control-custom" placeholder="Minimum 8 characters" required autocomplete="new-password">
                        <button type="button" class="eye-toggle-btn" id="togglePassword" title="Show / Hide Password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-hint"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="input-group-item">
                    <label class="field-label" for="password_confirmation">
                        <span><i class="fas fa-shield-halved icon-tag"></i> Confirm Password</span>
                        <span class="required-dot">*</span>
                    </label>
                    <div class="input-wrapper">
                        <div class="input-icon-box">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control-custom" placeholder="Repeat password" required autocomplete="new-password">
                        <button type="button" class="eye-toggle-btn" id="togglePasswordConf" title="Show / Hide Password">
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
                <button type="submit" class="btn-primary-gold" id="registerBtn" disabled>
                    <span id="btnText"><i class="fas fa-user-plus"></i> Create Account</span>
                    <div class="spinner-loader" id="btnSpinner"></div>
                </button>

                <!-- Divider -->
                <div class="or-divider">
                    <span>Already have an account?</span>
                </div>

                <!-- Sign In Link -->
                <a href="{{ url('/login') }}" class="btn-secondary-cyan">
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
                    <span class="cred-item-label">Email:</span>
                    <span class="cred-item-value" style="font-size:0.92rem;">{{ session('details.username') }}</span>
                </div>
                <div class="cred-item-row">
                    <span class="cred-item-label">Password:</span>
                    <span class="cred-item-value">{{ session('details.password') }}</span>
                </div>
            </div>

            <a href="{{ url('/login') }}" class="btn-primary-gold" style="text-decoration:none;">
                <i class="fas fa-arrow-right-to-bracket"></i> Proceed to Login
            </a>

            <div style="margin-top:14px;">
                <a href="{{ url('/register') }}" class="btn-secondary-cyan">
                    <i class="fas fa-user-plus"></i> Register Another Account
                </a>
            </div>
            @endif

        </div>

        <!-- Clean Footer -->
        <footer class="auth-footer-clean">
            <div class="footer-links-row">
                <a href="{{ url('/terms') }}">Terms of Service</a>
                <span>•</span>
                <a href="{{ url('/terms') }}">Privacy Policy</a>
                <span>•</span>
                <a href="https://t.me/" target="_blank">Support</a>
            </div>
            <div>&copy; 2026 CYERA AI. All Rights Reserved.</div>
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
                registerBtn.style.opacity = '0.9';
                if (btnText) btnText.style.display = 'none';
                if (btnSpinner) btnSpinner.style.display = 'block';
            });
        }
    </script>
</body>
</html>