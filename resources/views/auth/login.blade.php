<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyera AI | Login</title>
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
                    <p class="card-subtitle">>_ Sign in to start your session</p>
                </div>

                <!-- Messages -->
                <div id="messageContainer" style="padding: 0 30px;"></div>
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
                <!-- Progress Bar -->
                <!-- <div class="progress-container" id="progressContainer">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-text" id="progressText">PROCESSING...</div>
                </div> -->

                <!-- Forms Container -->
                <div class="form-container">
                    
                	<br>
                    <!-- Login Form -->
                    <form id="msg_validate" action="{{ route('login') }}" method="POST" class="form active">
                    	@csrf
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                USER ID <span>*</span>
                            </label>
                            <div class="input-group">
                                <i class="fas fa-user-circle input-icon"></i>
                                <input type="text" id="email" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="Enter User ID" required>
                            </div>
                                @error('email')
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
                                <input name="password" type="password" id="password" class="form-input password-input @error('password') is-invalid @enderror" placeholder="Enter Password" required>
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

                        <div class="form-footer">
                            <button type="submit" class="submit-btn" id="loginBtn">
                                <i class="fas fa-sign-in-alt"></i> LOGIN TO DASHBOARD
                                <div class="loading-spinner" id="loginSpinner"></div>
                            </button>
                            
                            <div class="form-links">
                                <a href="/register" class="form-link">
                                    <i class="fas fa-user-plus"></i> CREATE NEW ACCOUNT
                                </a>
                                <a href="/password/reset" class="form-link">
                                    <i class="fas fa-lock"></i> Forgot Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="portal-footer">
            <p>>_ © 2026 Cyera AI. </p>
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

        // Show progress bar
        function showProgress(show) {
            const progressContainer = document.getElementById('progressContainer');
            progressContainer.style.display = show ? 'block' : 'none';
        }

        // Update progress bar
        function updateProgress(percentage, text) {
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            
            progressFill.style.width = `${percentage}%`;
            progressText.textContent = `>_ ${text}`;
        }


        // Form submission - Login
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('.submit-btn');
            const spinner = document.getElementById('loginSpinner');
            
            submitBtn.disabled = true;
            spinner.style.display = 'block';
            
            // Show progress bar
            showProgress(true);
            
            // Simulate login process
            updateProgress(20, 'AUTHENTICATING...');
            await sleep(600);
            
            updateProgress(60, 'LOADING PROFILE...');
            await sleep(800);
            
            updateProgress(90, 'PREPARING DASHBOARD...');
            await sleep(500);
            
            updateProgress(100, 'LOGIN SUCCESSFUL!');
            await sleep(500);
            
            // Hide spinner
            spinner.style.display = 'none';
            
            // Hide progress bar
            setTimeout(() => {
                showProgress(false);
            }, 1000);
            
            // Show success message
            showMessage('LOGIN SUCCESSFUL! REDIRECTING TO DASHBOARD...', 'success');
            
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
</body>
</html>