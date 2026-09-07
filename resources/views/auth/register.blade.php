<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyera AI | Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-blue: #0066FF;
            --primary-cyan: #00D4FF;
            --matrix-green: #00FF41;
            --hack-green: #00FF9D;
            --dark-bg: #0A0A0F;
            --darker-bg: #050508;
            --card-bg: rgba(10, 15, 25, 0.95);
            --glass-border: rgba(0, 212, 255, 0.15);
            --text-light: #FFFFFF;
            --text-muted: #A0A0C0;
            --success: #00FF9D;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Main Container */
        .main-container {
            width: 100%;
            max-width: 1200px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        /* Hacking Matrix Background */
        .matrix-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
            opacity: 0.1;
        }

        .matrix-stream {
            position: absolute;
            top: -50px;
            color: var(--matrix-green);
            font-size: 18px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 0 8px var(--matrix-green);
            white-space: nowrap;
            animation: matrixFall linear infinite;
        }

        @keyframes matrixFall {
            0% {
                transform: translateY(-100px);
                opacity: 0;
            }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        /* Scanning Lines Effect */
        .scan-lines {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                rgba(0, 0, 0, 0.15) 0px,
                rgba(0, 0, 0, 0.15) 1px,
                transparent 1px,
                transparent 2px
            );
            z-index: -1;
            pointer-events: none;
            animation: scanMove 20s linear infinite;
        }

        @keyframes scanMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(100px); }
        }

        /* Header */
        .portal-header {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-cyan));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 102, 255, 0.4);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .logo-icon i {
            font-size: 2rem;
            color: white;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--primary-cyan), var(--hack-green));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1.5px;
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        }

        .tagline {
            font-size: 1rem;
            color: var(--hack-green);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
        }

        /* Content Wrapper */
        .content-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
            margin-bottom: 40px;
        }

        /* Portal Card */
        .portal-card {
            width: 100%;
            max-width: 480px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(0, 212, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            overflow: hidden;
            position: relative;
        }

        /* Card Header */
        .card-header {
            padding: 30px 30px 20px;
            text-align: center;
            background: linear-gradient(to bottom, rgba(0, 102, 255, 0.05), transparent);
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }

        .card-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(90deg, var(--primary-cyan), var(--hack-green));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
        }

        .card-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-family: 'Courier New', monospace;
        }

        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            margin: 0 30px 25px;
            padding: 6px;
            border: 1px solid rgba(0, 212, 255, 0.1);
        }

        .tab-btn {
            flex: 1;
            padding: 14px;
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .tab-btn.active {
            background: rgba(0, 102, 255, 0.2);
            color: var(--text-light);
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
            border: 1px solid rgba(0, 212, 255, 0.3);
        }

        .tab-btn i {
            font-size: 1.1rem;
        }

        /* Form Container */
        .form-container {
            padding: 0 30px 30px;
        }

        .form {
            display: none;
            animation: formFadeIn 0.4s ease forwards;
        }

        @keyframes formFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form.active {
            display: block;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Courier New', monospace;
        }

        .form-label span {
            color: var(--hack-green);
            margin-left: 2px;
        }

        .form-label i {
            color: var(--primary-cyan);
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--primary-cyan);
            font-size: 1.1rem;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--hack-green);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 2px rgba(0, 255, 157, 0.2);
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
            font-family: 'Inter', sans-serif;
        }

        /* Country Code Select */
        .country-select {
            width: 140px;
            padding: 16px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 10px 0 0 10px;
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            margin-right: -1px;
            border-right: none;
        }

        .phone-input {
            border-radius: 0 10px 10px 0;
            padding-left: 16px;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--hack-green);
        }

        /* Form Footer */
        .form-footer {
            margin-top: 25px;
        }

        .submit-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--primary-blue), var(--hack-green));
            border: none;
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(0, 102, 255, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 102, 255, 0.5);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Form Links */
        .form-links {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 30px;
            font-size: 0.9rem;
        }

        .form-link {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Courier New', monospace;
        }

        .form-link:hover {
            color: var(--hack-green);
        }

        /* Footer */
        .portal-footer {
            width: 100%;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            padding: 20px;
            margin-top: auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.85rem;
            font-family: 'Courier New', monospace;
        }

        .footer-link:hover {
            color: var(--hack-green);
        }

        /* Status Messages */
        .message {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            display: none;
            animation: messageSlide 0.4s ease forwards;
            border: 1px solid transparent;
            font-family: 'Courier New', monospace;
        }

        @keyframes messageSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.success {
            background: rgba(0, 255, 157, 0.1);
            border-color: rgba(0, 255, 157, 0.2);
            color: var(--success);
        }

        .message.error {
            background: rgba(255, 77, 125, 0.1);
            border-color: rgba(255, 77, 125, 0.2);
            color: #FF4D7D;
        }

        /* Progress Bar */
        .progress-container {
            margin-top: 25px;
            display: none;
        }

        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 157, 0.4), transparent);
            animation: progressShine 2s infinite;
        }

        @keyframes progressShine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--hack-green));
            width: 0%;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .progress-text {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-family: 'Courier New', monospace;
        }

        /* Loading Animation */
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--hack-green);
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Input Glitch Effect */
        .form-input:focus {
            animation: inputGlitch 0.3s ease;
        }

        @keyframes inputGlitch {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-2px); }
            40% { transform: translateX(2px); }
            60% { transform: translateX(-1px); }
            80% { transform: translateX(1px); }
        }

        /* Submit Button Pulse */
        .submit-btn {
            animation: buttonPulse 2s infinite;
        }

        @keyframes buttonPulse {
            0%, 100% { box-shadow: 0 8px 20px rgba(0, 102, 255, 0.3); }
            50% { box-shadow: 0 8px 25px rgba(0, 102, 255, 0.5), 0 0 15px rgba(0, 212, 255, 0.3); }
        }

        /* ========== RESPONSIVE DESIGN ========== */

        /* Desktop Large */
        @media (min-width: 1200px) {
            .logo-text {
                font-size: 2.8rem;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
            }
            
            .logo-icon i {
                font-size: 2.3rem;
            }
            
            .portal-card {
                max-width: 500px;
            }
        }

        /* Desktop */
        @media (min-width: 992px) {
            .content-wrapper {
                flex-direction: row;
                justify-content: center;
                align-items: stretch;
                gap: 60px;
            }
            
            .portal-card {
                margin: 0;
            }
        }

        /* Tablet */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .logo {
                flex-direction: column;
                gap: 10px;
            }
            
            .logo-text {
                font-size: 2rem;
                text-align: center;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
            }
            
            .logo-icon i {
                font-size: 1.6rem;
            }
            
            .tagline {
                font-size: 0.9rem;
                letter-spacing: 1.5px;
            }
            
            .portal-header {
                margin-bottom: 30px;
            }
            
            .portal-card {
                max-width: 100%;
            }
            
            .card-header {
                padding: 25px 20px 15px;
            }
            
            .form-container {
                padding: 0 20px 20px;
            }
            
            .tab-navigation {
                margin: 0 20px 20px;
            }
            
            .form-links {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
        }

        /* Mobile */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .logo-text {
                font-size: 1.8rem;
            }
            
            .logo-icon {
                width: 45px;
                height: 45px;
            }
            
            .logo-icon i {
                font-size: 1.4rem;
            }
            
            .tagline {
                font-size: 0.8rem;
                letter-spacing: 1px;
            }
            
            .portal-header {
                margin-bottom: 25px;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .card-subtitle {
                font-size: 0.85rem;
            }
            
            .tab-navigation {
                flex-direction: column;
                gap: 5px;
                padding: 8px;
            }
            
            .tab-btn {
                padding: 12px;
            }
            
            .form-input {
                padding: 14px 14px 14px 45px;
                font-size: 0.95rem;
            }
            
            .input-icon {
                left: 14px;
                font-size: 1rem;
            }
            
            .country-select {
                width: 120px;
                padding: 14px;
                font-size: 0.9rem;
            }
            
            .submit-btn {
                padding: 16px;
                font-size: 1rem;
            }
            
            .footer-links {
                gap: 15px;
            }
        }

        /* Extra Small Mobile */
        @media (max-width: 400px) {
            .logo-text {
                font-size: 1.6rem;
            }
            
            .card-title {
                font-size: 1.3rem;
            }
            
            .form-input {
                padding: 12px 12px 12px 40px;
                font-size: 0.9rem;
            }
            
            .input-icon {
                left: 12px;
                font-size: 0.9rem;
            }
            
            .country-select {
                width: 100px;
                padding: 12px;
                font-size: 0.8rem;
            }
            
            .submit-btn {
                padding: 14px;
                font-size: 0.95rem;
            }
        }

        /* Height Adjustments */
        @media (max-height: 700px) {
            .portal-header {
                margin-bottom: 20px;
            }
            
            .content-wrapper {
                gap: 20px;
                margin-bottom: 20px;
            }
            
            .portal-footer {
                padding: 10px 20px;
            }
        }

        /* Fix for very small screens */
        @media (max-width: 350px) {
            .logo-text {
                font-size: 1.4rem;
            }
            
            .tagline {
                font-size: 0.75rem;
            }
            
            .form-links {
                flex-direction: column;
                gap: 10px;
            }
            
            .form-link {
                font-size: 0.8rem;
            }
        }

        .invalid-feedback {
		    display: block;
		    margin-top: 6px;
		    color: #FF4D7D;
		    font-size: 0.85rem;
		    font-family: 'Courier New', monospace;
		    animation: messageSlide 0.3s ease forwards;
		}

		.form-input.is-invalid {
		    border-color: #FF4D7D !important;
		    box-shadow: 0 0 0 2px rgba(255, 77, 125, 0.2);
		}
    </style>
</head>
<body>
    <!-- Hacking Matrix Background -->
    <div class="matrix-bg" id="matrixBg"></div>
    <div class="scan-lines"></div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Header -->
        <header class="portal-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="logo-text">Cyera AI</div>
            </div>
            <div class="tagline">>_ ACCESS WEB3 FUTURE</div>
        </header>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Portal Card -->
            <div class="portal-card">
                <div class="card-header">
                    <h2 class="card-title">GET STARTED WITH US</h2>
                    <p class="card-subtitle">>_ Register a new membership</p>
                </div>


                <!-- Messages -->
                
                @if (session('success'))
                    <div class="message success" style="display:block;">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('warning'))
                    <div class="message error" style="display:block;">
                        {{ session('warning') }}
   		    	    </div>
                @endif

                <!-- Forms Container -->
                <div class="form-container">
                    <!-- Registration Form -->
                    @if(!session('success'))
                    <form action="{{ route('register') }}" method="POST" id="registerForm" class="form active">
                    @csrf
                    	<br>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-friends"></i>
                                SPONSOR ID <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-id-card input-icon"></i>
                                <input name="referrer" type="text" id="referrer" class="form-input @error('referrer') is-invalid @enderror" placeholder="Enter Sponsor ID" @if(!empty($userid)) value="{{$userid}}" @else value="{{old('referrer')}}" @endif required autofocus>
                            </div>
                                @error('referrer')
		                            <small class="invalid-feedback">
							            {{ $message }}
							        </small>
		                        @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-tag"></i>
                                SPONSOR NAME <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-user input-icon"></i>
                                <input name="referrername" type="text" id="spname" disabled="disabled" class="form-input" placeholder="Sponsor Name" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                FULL NAME <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-signature input-icon"></i>
                                <input name="name" type="text" id="name" class="form-input @error('name') is-invalid @enderror" placeholder="Full Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                                @error('name')
		                            <small class="invalid-feedback">
							            {{ $message }}
							        </small>
		                        @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                EMAIL ADDRESS <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-at input-icon"></i>
                                <input name="email" type="text" id="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email Address">
                            </div>
                                @error('email')
		                            <small class="invalid-feedback">
							            {{ $message }}
							        </small>
		                        @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                MOBILE NO <span>*</span>
                            </label>
                            <div class="input-group">
                                <select name="countrycode" class="country-select">
                                    <option data-countryCode="SG" value="65">Singapore (+65)</option>
								    <option data-countryCode="IN" value="91">India (+91)</option>
	                                <option data-countryCode="GB" value="44">UK (+44)</option>
								    <option data-countryCode="US" value="1">USA (+1)</option>
								    <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
								    <optgroup label="Other countries">
								        <option value="93">Afghanistan (+93)</option>
										<option value="355">Albania (+355)</option>
										<option value="213">Algeria (+213)</option>
										<option value="1684">American Samoa (+1684)</option>
										<option value="376">Andorra (+376)</option>
										<option value="244">Angola (+244)</option>
										<option value="1264">Anguilla (+1264)</option>
										<option value="1268">Antigua & Barbuda (+1268)</option>
										<option value="54">Argentina (+54)</option>
										<option value="374">Armenia (+374)</option>
										<option value="297">Aruba (+297)</option>
										<option value="61">Australia (+61)</option>
										<option value="43">Austria (+43)</option>
										<option value="994">Azerbaijan (+994)</option>

										<option value="1242">Bahamas (+1242)</option>
										<option value="973">Bahrain (+973)</option>
										<option value="880">Bangladesh (+880)</option>
										<option value="1246">Barbados (+1246)</option>
										<option value="375">Belarus (+375)</option>
										<option value="32">Belgium (+32)</option>
										<option value="501">Belize (+501)</option>
										<option value="229">Benin (+229)</option>
										<option value="1441">Bermuda (+1441)</option>
										<option value="975">Bhutan (+975)</option>
										<option value="591">Bolivia (+591)</option>
										<option value="387">Bosnia & Herzegovina (+387)</option>
										<option value="267">Botswana (+267)</option>
										<option value="55">Brazil (+55)</option>
										<option value="673">Brunei (+673)</option>
										<option value="359">Bulgaria (+359)</option>
										<option value="226">Burkina Faso (+226)</option>
										<option value="257">Burundi (+257)</option>

										<option value="855">Cambodia (+855)</option>
										<option value="237">Cameroon (+237)</option>
										<option value="1">Canada (+1)</option>
										<option value="238">Cape Verde (+238)</option>
										<option value="1345">Cayman Islands (+1345)</option>
										<option value="236">Central African Republic (+236)</option>
										<option value="235">Chad (+235)</option>
										<option value="56">Chile (+56)</option>
										<option value="86">China (+86)</option>
										<option value="57">Colombia (+57)</option>
										<option value="269">Comoros (+269)</option>
										<option value="243">Congo (DRC) (+243)</option>
										<option value="242">Congo (Republic) (+242)</option>
										<option value="682">Cook Islands (+682)</option>
										<option value="506">Costa Rica (+506)</option>
										<option value="385">Croatia (+385)</option>
										<option value="53">Cuba (+53)</option>
										<option value="357">Cyprus (+357)</option>
										<option value="420">Czech Republic (+420)</option>

										<option value="45">Denmark (+45)</option>
										<option value="253">Djibouti (+253)</option>
										<option value="1767">Dominica (+1767)</option>
										<option value="1809">Dominican Republic (+1809)</option>

										<option value="593">Ecuador (+593)</option>
										<option value="20">Egypt (+20)</option>
										<option value="503">El Salvador (+503)</option>
										<option value="240">Equatorial Guinea (+240)</option>
										<option value="291">Eritrea (+291)</option>
										<option value="372">Estonia (+372)</option>
										<option value="251">Ethiopia (+251)</option>

										<option value="500">Falkland Islands (+500)</option>
										<option value="298">Faroe Islands (+298)</option>
										<option value="679">Fiji (+679)</option>
										<option value="358">Finland (+358)</option>
										<option value="33">France (+33)</option>

										<option value="594">French Guiana (+594)</option>
										<option value="689">French Polynesia (+689)</option>

										<option value="241">Gabon (+241)</option>
										<option value="220">Gambia (+220)</option>
										<option value="995">Georgia (+995)</option>
										<option value="49">Germany (+49)</option>
										<option value="233">Ghana (+233)</option>
										<option value="350">Gibraltar (+350)</option>
										<option value="30">Greece (+30)</option>
										<option value="299">Greenland (+299)</option>
										<option value="1473">Grenada (+1473)</option>
										<option value="590">Guadeloupe (+590)</option>
										<option value="1671">Guam (+1671)</option>
										<option value="502">Guatemala (+502)</option>
										<option value="224">Guinea (+224)</option>
										<option value="245">Guinea-Bissau (+245)</option>
										<option value="592">Guyana (+592)</option>

										<option value="509">Haiti (+509)</option>
										<option value="504">Honduras (+504)</option>
										<option value="852">Hong Kong (+852)</option>
										<option value="36">Hungary (+36)</option>

										<option value="354">Iceland (+354)</option>
										<option value="91">India (+91)</option>
										<option value="62">Indonesia (+62)</option>
										<option value="98">Iran (+98)</option>
										<option value="964">Iraq (+964)</option>
										<option value="353">Ireland (+353)</option>
										<option value="972">Israel (+972)</option>
										<option value="39">Italy (+39)</option>

										<option value="1876">Jamaica (+1876)</option>
										<option value="81">Japan (+81)</option>
										<option value="962">Jordan (+962)</option>

										<option value="7">Kazakhstan (+7)</option>
										<option value="254">Kenya (+254)</option>
										<option value="686">Kiribati (+686)</option>
										<option value="850">North Korea (+850)</option>
										<option value="82">South Korea (+82)</option>
										<option value="965">Kuwait (+965)</option>
										<option value="996">Kyrgyzstan (+996)</option>

										<option value="856">Laos (+856)</option>
										<option value="371">Latvia (+371)</option>
										<option value="961">Lebanon (+961)</option>
										<option value="266">Lesotho (+266)</option>
										<option value="231">Liberia (+231)</option>
										<option value="218">Libya (+218)</option>
										<option value="423">Liechtenstein (+423)</option>
										<option value="370">Lithuania (+370)</option>
										<option value="352">Luxembourg (+352)</option>

										<option value="853">Macau (+853)</option>
										<option value="389">North Macedonia (+389)</option>
										<option value="261">Madagascar (+261)</option>
										<option value="265">Malawi (+265)</option>
										<option value="60">Malaysia (+60)</option>
										<option value="960">Maldives (+960)</option>
										<option value="223">Mali (+223)</option>
										<option value="356">Malta (+356)</option>
										<option value="692">Marshall Islands (+692)</option>
										<option value="596">Martinique (+596)</option>
										<option value="222">Mauritania (+222)</option>
										<option value="230">Mauritius (+230)</option>
										<option value="262">Mayotte (+262)</option>
										<option value="52">Mexico (+52)</option>
										<option value="691">Micronesia (+691)</option>
										<option value="373">Moldova (+373)</option>
										<option value="377">Monaco (+377)</option>
										<option value="976">Mongolia (+976)</option>
										<option value="382">Montenegro (+382)</option>
										<option value="1664">Montserrat (+1664)</option>
										<option value="212">Morocco (+212)</option>
										<option value="258">Mozambique (+258)</option>
										<option value="95">Myanmar (+95)</option>

										<option value="264">Namibia (+264)</option>
										<option value="674">Nauru (+674)</option>
										<option value="977">Nepal (+977)</option>
										<option value="31">Netherlands (+31)</option>
										<option value="599">Netherlands Antilles (+599)</option>
										<option value="687">New Caledonia (+687)</option>
										<option value="64">New Zealand (+64)</option>
										<option value="505">Nicaragua (+505)</option>
										<option value="227">Niger (+227)</option>
										<option value="234">Nigeria (+234)</option>
										<option value="683">Niue (+683)</option>
										<option value="672">Norfolk Island (+672)</option>
										<option value="1670">Northern Mariana Islands (+1670)</option>
										<option value="47">Norway (+47)</option>

										<option value="968">Oman (+968)</option>

										<option value="92">Pakistan (+92)</option>
										<option value="680">Palau (+680)</option>
										<option value="507">Panama (+507)</option>
										<option value="675">Papua New Guinea (+675)</option>
										<option value="595">Paraguay (+595)</option>
										<option value="51">Peru (+51)</option>
										<option value="63">Philippines (+63)</option>
										<option value="48">Poland (+48)</option>
										<option value="351">Portugal (+351)</option>
										<option value="1787">Puerto Rico (+1787)</option>

										<option value="974">Qatar (+974)</option>

										<option value="242">Reunion (+262)</option>
										<option value="40">Romania (+40)</option>
										<option value="7">Russia (+7)</option>
										<option value="250">Rwanda (+250)</option>

										<option value="590">Saint Barthelemy (+590)</option>
										<option value="290">Saint Helena (+290)</option>
										<option value="1869">Saint Kitts & Nevis (+1869)</option>
										<option value="1758">Saint Lucia (+1758)</option>
										<option value="1599">Saint Martin (+1599)</option>
										<option value="508">Saint Pierre & Miquelon (+508)</option>
										<option value="1784">Saint Vincent & Grenadines (+1784)</option>

										<option value="685">Samoa (+685)</option>
										<option value="378">San Marino (+378)</option>
										<option value="239">Sao Tome & Principe (+239)</option>
										<option value="966">Saudi Arabia (+966)</option>
										<option value="221">Senegal (+221)</option>
										<option value="381">Serbia (+381)</option>
										<option value="248">Seychelles (+248)</option>
										<option value="232">Sierra Leone (+232)</option>
										<option value="65">Singapore (+65)</option>
										<option value="421">Slovakia (+421)</option>
										<option value="386">Slovenia (+386)</option>
										<option value="677">Solomon Islands (+677)</option>
										<option value="252">Somalia (+252)</option>
										<option value="27">South Africa (+27)</option>
										<option value="34">Spain (+34)</option>
										<option value="94">Sri Lanka (+94)</option>
										<option value="249">Sudan (+249)</option>
										<option value="597">Suriname (+597)</option>
										<option value="268">Eswatini (+268)</option>
										<option value="46">Sweden (+46)</option>
										<option value="41">Switzerland (+41)</option>
										<option value="963">Syria (+963)</option>

										<option value="886">Taiwan (+886)</option>
										<option value="992">Tajikistan (+992)</option>
										<option value="255">Tanzania (+255)</option>
										<option value="66">Thailand (+66)</option>
										<option value="228">Togo (+228)</option>
										<option value="690">Tokelau (+690)</option>
										<option value="676">Tonga (+676)</option>
										<option value="1868">Trinidad & Tobago (+1868)</option>
										<option value="216">Tunisia (+216)</option>
										<option value="90">Turkey (+90)</option>
										<option value="993">Turkmenistan (+993)</option>
										<option value="1649">Turks & Caicos Islands (+1649)</option>
										<option value="688">Tuvalu (+688)</option>

										<option value="256">Uganda (+256)</option>
										<option value="380">Ukraine (+380)</option>
										<option value="971">United Arab Emirates (+971)</option>
										<option value="44">United Kingdom (+44)</option>
										<option value="1">United States (+1)</option>
										<option value="598">Uruguay (+598)</option>
										<option value="998">Uzbekistan (+998)</option>

										<option value="678">Vanuatu (+678)</option>
										<option value="379">Vatican City (+379)</option>
										<option value="58">Venezuela (+58)</option>
										<option value="84">Vietnam (+84)</option>
										<option value="1284">Virgin Islands (British) (+1284)</option>
										<option value="1340">Virgin Islands (US) (+1340)</option>

										<option value="681">Wallis & Futuna (+681)</option>
										<option value="967">Yemen (+967)</option>
										<option value="260">Zambia (+260)</option>
										<option value="263">Zimbabwe (+263)</option>
								    </optgroup>
                                </select>
                                <input  name="contact" type="text" maxlength="11" id="contact" class="form-input phone-input @error('contact') is-invalid @enderror" value="{{ old('contact') }}" autocomplete="contact" placeholder="Mobile No">
                            </div>
                                @error('contact')
		                            <small class="invalid-feedback">
							            {{ $message }}
							        </small>
		                        @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                PASSWORD <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-key input-icon"></i>
                                <input name="password" type="password" id="password" class="form-input password-input @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Create Password">
                                <button type="button" class="password-toggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                                @error('password')
		                            <small class="invalid-feedback">
							            {{ $message }}
							        </small>
		                        @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                CONFIRM PASSWORD <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" id="password-confirm" name="password_confirmation" class="form-input confirm-password-input" required autocomplete="new-password" placeholder="repeat password">
                                <button type="button" class="password-toggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="form-label" style="font-size: 0.95rem; color: var(--text-light); display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="agreeTerms" style="width:18px; height:18px; cursor:pointer;">
                                I agree with <a href="/terms" target="_blank" style="color: var(--primary-cyan); text-decoration: underline;">Terms & Conditions</a>
                            </label>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="submit-btn" id="registerBtn" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();" disabled>
                                <i class="fas fa-rocket"></i> CREATE ACCOUNT
                                <div class="loading-spinner" id="registerSpinner"></div>
                            </button>
                            
                            <div class="form-links">
                                <a href="/login" class="form-link" id="switchToLogin">
                                    <i class="fas fa-sign-in-alt"></i> ALREADY HAVE AN ACCOUNT?
                                </a>
                            </div>
                        </div>
                    </form>
                    @endif
                	@if (session('success'))
                	<form action="#" name="form2" id="form2" class="form active">
                		<h3 class="card-title"><br>Registration Successful</h3>
                		<div class="form-group">
                			 <label class="form-label"> User ID : {{session('details.uniqueid') }} </label>
                		</div>
                		<div class="form-group">
                			 <label class="form-label"> Email : {{session('details.username') }} </label>
                		</div>
                		<div class="form-group">
                			 <label class="form-label"> Password : {{session('details.password') }} </label>
                		</div>
                		<div class="form-footer">
                            <a href="/register"><button type="button" class="submit-btn">
                                Back
                            </button></a>
                            
                            <div class="form-links">
                                <a href="/login" class="form-link" id="switchToLogin">
                                    <i class="fas fa-sign-in-alt"></i> ALREADY HAVE AN ACCOUNT?
                                </a>
                            </div>
                        </div>
                	</form>
                	@endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="portal-footer">
            <p>>_ © 2026 Cyera AI.</p>
            <div class="footer-links">
                <a href="#" class="footer-link">TERMS</a>
                <a href="#" class="footer-link">PRIVACY</a>
                <a href="#" class="footer-link">SUPPORT</a>
                <a href="#" class="footer-link">DOCS</a>
            </div>
        </footer>
    </div>

    <script>
        // Create Matrix Rain Effect
        function createMatrixRain() {
            const container = document.getElementById('matrixBg');
            const chars = "01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン";
            
            for (let i = 0; i < 40; i++) {
                const stream = document.createElement('div');
                stream.className = 'matrix-stream';
                
                // Random position
                stream.style.left = `${Math.random() * 100}vw`;
                
                // Random speed
                const duration = 3 + Math.random() * 5;
                stream.style.animationDuration = `${duration}s`;
                
                // Random delay
                stream.style.animationDelay = `${Math.random() * 5}s`;
                
                // Random characters
                let content = '';
                const length = 15 + Math.floor(Math.random() * 10);
                for (let j = 0; j < length; j++) {
                    content += chars[Math.floor(Math.random() * chars.length)];
                }
                
                stream.textContent = content;
                container.appendChild(stream);
            }
        }

        // Tab switching functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const forms = document.querySelectorAll('.form');
        const switchToLogin = document.getElementById('switchToLogin');
        const switchToRegister = document.getElementById('switchToRegister');


        // Password toggle functionality
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.password-input, .confirm-password-input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Show message function
        function showMessage(text, type) {
            const container = document.getElementById('messageContainer');
            
            // Remove existing message
            const existingMsg = container.querySelector('.message');
            if (existingMsg) existingMsg.remove();
            
            // Create new message
            const message = document.createElement('div');
            message.className = `message ${type}`;
            message.textContent = `>_ ${text}`;
            message.style.display = 'block';
            
            container.appendChild(message);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transform = 'translateY(-10px)';
                setTimeout(() => message.remove(), 400);
            }, 4000);
        }


        // Form submission - Registration
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = this.querySelector('.password-input').value;
            const confirmPassword = this.querySelector('.confirm-password-input').value;
            
            // Validation
            if (password !== confirmPassword) {
                showMessage('PASSWORDS DO NOT MATCH!', 'error');
                return;
            }
            
            if (password.length < 6) {
                showMessage('PASSWORD MUST BE AT LEAST 6 CHARACTERS', 'error');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('.submit-btn');
            const spinner = document.getElementById('registerSpinner');
            
            submitBtn.disabled = true;
            spinner.style.display = 'block';
            
            // Show progress bar
            showProgress(true);
            
            // Simulate registration process
            updateProgress(10, 'VALIDATING DATA...');
            await sleep(500);
            
            updateProgress(30, 'CHECKING SPONSOR...');
            await sleep(800);
            
            updateProgress(50, 'CREATING ACCOUNT...');
            await sleep(600);
            
            updateProgress(80, 'SETTING UP WALLET...');
            await sleep(700);
            
            updateProgress(100, 'ACCOUNT CREATED!');
            await sleep(500);
            
            // Hide spinner
            spinner.style.display = 'none';
            
            // Hide progress bar
            setTimeout(() => {
                showProgress(false);
            }, 1000);
            
            // Show success message
            showMessage('REGISTRATION SUCCESSFUL! WELCOME TO CYERA AI.', 'success');
            
            // Reset form after delay
            setTimeout(() => {
                this.reset();
                submitBtn.disabled = false;
            }, 2000);
        });


        // Form input validation
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = 'rgba(255, 77, 125, 0.3)';
                } else {
                    this.style.borderColor = 'rgba(0, 212, 255, 0.2)';
                }
            });
            
            input.addEventListener('input', function() {
                this.style.borderColor = 'rgba(0, 102, 255, 0.3)';
            });
        });

        // Sleep helper function
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            
            // Submit with Enter
            if (e.key === 'Enter') {
                const activeForm = document.querySelector('.form.active');
                if (activeForm) {
                    e.preventDefault();
                    activeForm.dispatchEvent(new Event('submit'));
                }
            }
        });

        // Initialize
        window.addEventListener('DOMContentLoaded', () => {
            // Create background effects
            createMatrixRain();
            
            // Add some subtle animation to the card
            const card = document.querySelector('.portal-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px) scale(0.95)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
            }, 300);
            
            // Show initial message
            setTimeout(() => {
                showMessage('WELCOME TO CYERA AIS WEB3 PORTAL', 'success');
            }, 1000);
        });
    </script>


    <script src="{{asset('assets/js/jquery-1.11.2.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            if($("#referrer").val()!="")
                $("#referrer").blur();
        });
        $("#referrer").on('blur',function(){
            $("#spdiv").hide();
            $.ajax({
                     type:'GET',
                     url:'/getSponsor/'+$("#referrer").val(),
                     dataType: "json",
                     success:function(data){
                        if(data.status==0){
                           $("#spdiv").show();
                           $("#spname").val(data.name);
                        }else{
                           $("#spdiv").hide();
                        }
                     }
                 });
        });
    </script>

    <script>
        const agreeCheckbox = document.getElementById('agreeTerms');
        const registerBtn = document.getElementById('registerBtn');

        agreeCheckbox.addEventListener('change', function() {
            registerBtn.disabled = !this.checked;
        });
    </script>


</body>
</html>