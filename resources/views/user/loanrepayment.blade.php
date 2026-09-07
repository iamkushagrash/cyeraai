<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Repay Loan</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <!-- Chart.js for charts -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <link href="{{asset('ctassets/css/scriptui.css')}}" rel="stylesheet">
      <style>
         .deposit-container {
         width: 100%;
         max-width: 600px;
         margin: 0 auto;
         }
         .deposit-header {
         text-align: center;
         margin-bottom: 30px;
         }
         .deposit-title {
         font-family: 'Segoe UI', sans-serif;
         font-size: 2.2rem;
         font-weight: 900;
         margin-bottom: 10px;
         background: linear-gradient(90deg, var(--primary-blue), var(--accent-green));
         -webkit-background-clip: text;
         background-clip: text;
         color: transparent;
         letter-spacing: 1px;
         text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
         }
         .deposit-subtitle {
         color: var(--text-muted);
         font-size: 1rem;
         }
         .deposit-card {
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
         .deposit-content {
         padding: 30px;
         }
         .warning-banner {
         background: linear-gradient(135deg, rgba(255, 167, 38, 0.1), rgba(255, 167, 38, 0.05));
         border: 1px solid rgba(255, 167, 38, 0.3);
         border-radius: 10px;
         padding: 20px;
         margin-bottom: 25px;
         display: flex;
         align-items: flex-start;
         gap: 15px;
         }
         .warning-icon {
         color: var(--warning);
         font-size: 1.5rem;
         flex-shrink: 0;
         }
         .warning-text {
         color: var(--text-color);
         font-size: 0.95rem;
         line-height: 1.6;
         }
         .warning-text strong {
         color: var(--warning);
         }
         .form-group {
         margin-bottom: 25px;
         }
         .form-label {
         display: block;
         margin-bottom: 10px;
         color: var(--text-color);
         font-weight: 500;
         font-size: 0.95rem;
         display: flex;
         align-items: center;
         gap: 8px;
         }
         .form-label span {
         color: var(--accent-green);
         margin-left: 3px;
         }
         .form-label i {
         color: var(--primary-blue);
         font-size: 0.95rem;
         }
         .input-group {
         position: relative;
         display: flex;
         align-items: center;
         }
         .input-icon {
         position: absolute;
         left: 16px;
         color: var(--primary-blue);
         font-size: 1.1rem;
         z-index: 2;
         }
         .form-input {
         width: 100%;
         padding: 16px 16px 16px 50px;
         background: rgba(0, 0, 0, 0.4);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 10px;
         color: var(--text-color);
         font-size: 1rem;
         transition: all 0.3s ease;
         letter-spacing: 0.5px;
         }
         .form-input:focus {
         outline: none;
         border-color: var(--accent-green);
         background: rgba(0, 0, 0, 0.5);
         box-shadow: 0 0 0 2px rgba(0, 255, 157, 0.2);
         }
         .form-input::placeholder {
         color: rgba(255, 255, 255, 0.3);
         }
         .currency-select {
         width: 100%;
         padding: 16px 16px 16px 50px;
         background: rgba(0, 0, 0, 0.4);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 10px;
         color: var(--text-color);
         font-size: 1rem;
         transition: all 0.3s ease;
         appearance: none;
         background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300D4FF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
         background-repeat: no-repeat;
         background-position: right 16px center;
         background-size: 16px;
         }
         .currency-select:focus {
         outline: none;
         border-color: var(--accent-green);
         background: rgba(0, 0, 0, 0.5);
         box-shadow: 0 0 0 2px rgba(0, 255, 157, 0.2);
         }
         .payment-info {
         background: rgba(0, 102, 255, 0.05);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 10px;
         padding: 25px;
         margin-top: 20px;
         }
         .payment-info-title {
         display: flex;
         align-items: center;
         gap: 10px;
         margin-bottom: 15px;
         color: var(--primary-blue);
         font-size: 1.1rem;
         }
         .payment-info-title i {
         font-size: 1.2rem;
         }
         .payment-details {
         color: var(--text-muted);
         font-size: 0.95rem;
         line-height: 1.6;
         }
         .payment-address {
         background: rgba(0, 0, 0, 0.3);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 8px;
         padding: 12px;
         margin: 15px 0;
         font-size: 0.9rem;
         word-break: break-all;
         display: flex;
         justify-content: space-between;
         align-items: center;
         }
         .payment-address span {
         flex: 1;
         margin-right: 10px;
         }
         .qr-code-container {
         text-align: center;
         margin: 20px 0;
         }
         .qr-code-placeholder {
         width: 200px;
         height: 200px;
         background: linear-gradient(45deg, rgba(0, 212, 255, 0.1), rgba(0, 102, 255, 0.1));
         margin: 0 auto 15px;
         border-radius: 10px;
         display: flex;
         align-items: center;
         justify-content: center;
         color: var(--primary-blue);
         font-size: 3rem;
         border: 2px dashed var(--primary-blue);
         }
         .transaction-status {
         padding: 25px;
         text-align: center;
         }
         .status-icon {
         width: 80px;
         height: 80px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin: 0 auto 20px;
         font-size: 2.5rem;
         }
         .status-icon.success {
         background: rgba(0, 255, 157, 0.1);
         color: var(--success);
         border: 2px solid rgba(0, 255, 157, 0.3);
         }
         .status-icon.pending {
         background: rgba(255, 167, 38, 0.1);
         color: var(--warning);
         border: 2px solid rgba(255, 167, 38, 0.3);
         }
         .status-title {
         font-size: 1.5rem;
         margin-bottom: 10px;
         color: var(--text-color);
         }
         .status-text {
         color: var(--text-muted);
         font-size: 0.95rem;
         line-height: 1.6;
         max-width: 400px;
         margin: 0 auto;
         }
         .transaction-id {
         background: rgba(0, 0, 0, 0.3);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 8px;
         padding: 12px;
         margin: 20px auto;
         font-size: 0.9rem;
         display: inline-block;
         }
         .form-actions {
         padding: 30px;
         background: rgba(0, 0, 0, 0.2);
         border-top: 1px solid rgba(0, 212, 255, 0.1);
         display: flex;
         justify-content: space-between;
         gap: 20px;
         }
         .btn {
         padding: 16px 32px;
         border: none;
         border-radius: 10px;
         font-size: 1.1rem;
         font-weight: 600;
         cursor: pointer;
         transition: all 0.3s ease;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 12px;
         letter-spacing: 0.5px;
         min-width: 160px;
         }
         .btn-primary {
         background: linear-gradient(135deg, var(--primary-blue), var(--accent-green));
         color: var(--dark-bg);
         box-shadow: 0 8px 20px rgba(0, 102, 255, 0.3);
         }
         .btn-primary:hover {
         transform: translateY(-2px);
         box-shadow: 0 12px 25px rgba(0, 102, 255, 0.5);
         }
         .btn-primary:disabled {
         opacity: 0.6;
         cursor: not-allowed;
         transform: none;
         }
         .btn-secondary {
         background: rgba(255, 255, 255, 0.05);
         color: var(--text-color);
         border: 1px solid rgba(0, 212, 255, 0.2);
         }
         .btn-secondary:hover {
         background: rgba(255, 255, 255, 0.1);
         border-color: var(--primary-blue);
         }
         .btn-icon {
         font-size: 1.2rem;
         }
         .message {
         padding: 16px;
         border-radius: 10px;
         margin-bottom: 25px;
         text-align: center;
         font-weight: 500;
         display: none;
         animation: messageSlide 0.4s ease forwards;
         border: 1px solid transparent;
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
         color: var(--danger);
         }
         .message.info {
         background: rgba(0, 212, 255, 0.1);
         border-color: rgba(0, 212, 255, 0.2);
         color: var(--info);
         }
         /* Animations */
         @keyframes pulse {
         0% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.7); }
         70% { box-shadow: 0 0 0 12px rgba(0, 212, 255, 0); }
         100% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0); }
         }
         .pulse-animation {
         animation: pulse 2s infinite;
         }
         /* Web3 Glow Effects */
         .glow-effect {
         position: relative;
         overflow: hidden;
         }
         .glow-effect::before {
         content: '';
         position: absolute;
         top: -2px;
         left: -2px;
         right: -2px;
         bottom: -2px;
         background: linear-gradient(45deg, var(--primary-blue), var(--accent-purple), var(--secondary-blue), var(--accent-green));
         z-index: -1;
         border-radius: inherit;
         opacity: 0;
         transition: opacity 0.4s ease;
         filter: blur(10px);
         }
         .glow-effect:hover::before {
         opacity: 0.6;
         }
         /* Floating Crypto Icons Animation */
         .crypto-icons {
         position: fixed;
         width: 100%;
         height: 100%;
         pointer-events: none;
         z-index: -1;
         overflow: hidden;
         }
         .crypto-icon {
         position: absolute;
         font-size: 1.5rem;
         opacity: 0.1;
         animation: floatIcon 20s infinite linear;
         color: var(--primary-blue);
         }
         @keyframes floatIcon {
         0% { transform: translateY(100vh) translateX(0) rotate(0deg); }
         100% { transform: translateY(-100px) translateX(calc(100vw - 100px)) rotate(360deg); }
         }
         /* Neon Border Animation */
         @keyframes neonGlow {
         0% { background-position: 0 0; }
         50% { background-position: 400% 0; }
         100% { background-position: 0 0; }
         }
         .neon-border:hover::after {
         opacity: 0.5;
         }
         /* Footer */
         .footer {
         text-align: center;
         padding: 20px;
         color: var(--text-muted);
         font-size: 0.85rem;
         border-top: 1px solid rgba(0, 212, 255, 0.2);
         margin-top: 30px;
         background: rgba(5, 10, 20, 0.5);
         border-radius: 10px;
         }
         /* Responsive Design */
         @media (max-width: 1200px) {
         .investment-section {
         grid-template-columns: 1fr;
         }
         }
         @media (max-width: 992px) {
         .sidebar {
         transform: translateX(-100%);
         width: 280px;
         }
         .sidebar.active {
         transform: translateX(0);
         }
         .main-content {
         margin-left: 0;
         padding: 15px;
         }
         .menu-toggle {
         display: flex;
         }
         .deposit-title {
         font-size: 1.9rem;
         }
         .deposit-content {
         padding: 25px;
         }
         .form-actions {
         flex-direction: column;
         }
         .btn {
         width: 100%;
         }
         }
         @media (max-width: 768px) {
         .dashboard-grid {
         grid-template-columns: repeat(2, 1fr);
         }
         .top-bar {
         flex-direction: column;
         align-items: flex-start;
         gap: 15px;
         }
         .wallet-info {
         width: 100%;
         justify-content: space-between;
         flex-wrap: wrap;
         }
         .investment-amount {
         font-size: 1.8rem;
         }
         .page-title {
         font-size: 1.4rem;
         }
         .charts-section {
         grid-template-columns: 1fr;
         }
         .quick-actions {
         grid-template-columns: repeat(2, 1fr);
         }
         }
         @media (max-width: 576px) {
         .dashboard-grid {
         grid-template-columns: 1fr;
         }
         .wallet-info {
         flex-direction: column;
         align-items: flex-start;
         gap: 10px;
         }
         .referral-link {
         flex-direction: column;
         }
         .referral-input {
         border-radius: 8px 8px 0 0;
         margin-bottom: -1px;
         }
         .copy-btn {
         border-radius: 0 0 8px 8px;
         padding: 12px;
         }
         .charts-section {
         grid-template-columns: 1fr;
         }
         .quick-actions {
         grid-template-columns: 1fr;
         }
         .deposit-title {
         font-size: 1.7rem;
         }
         .deposit-content {
         padding: 20px;
         }
         .form-input,
         .currency-select {
         padding: 14px 14px 14px 45px;
         font-size: 0.95rem;
         }
         .input-icon {
         left: 14px;
         font-size: 1rem;
         }
         .payment-address {
         flex-direction: column;
         gap: 10px;
         text-align: center;
         }
         .copy-btn {
         width: 100%;
         margin-left: 0;
         }
         .btn {
         padding: 14px 20px;
         font-size: 1rem;
         }
         }
         @media (max-width: 400px) {
         .deposit-title {
         font-size: 1.5rem;
         }
         .deposit-subtitle {
         font-size: 0.9rem;
         }
         .warning-banner {
         flex-direction: column;
         align-items: center;
         text-align: center;
         }
         .qr-code-placeholder {
         width: 180px;
         height: 180px;
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
        /* Package Selection Grid */
        #packageSelection .d-flex {
            display: flex !important;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        /* Package Box */
        .package-box {
            width: 120px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(0, 212, 255, 0.25);
            padding: 16px 0;
            transition: all 0.3s ease;
            text-align: center;
        }

        /* Amount Button */
        .package-btn {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 1px;
            text-shadow: 0 0 8px rgba(0, 212, 255, 0.6);
        }

        /* Hover Effect */
        .package-box:hover {
            transform: translateY(-4px);
            border-color: var(--primary-blue);
            box-shadow: 0 10px 25px rgba(0, 212, 255, 0.35);
        }

        /* Checked / Selected State */
        .package-box input[type="radio"]:checked ~ .package-btn {
            color: #ffffff !important;
        }

        /* Active Box Highlight */
        .package-box:has(input[type="radio"]:checked) {
            background: linear-gradient(
                135deg,
                rgba(0, 212, 255, 0.2),
                rgba(0, 255, 157, 0.15)
            );
            border-color: var(--accent-green);
            box-shadow: 
                0 0 25px rgba(0, 255, 157, 0.5),
                inset 0 0 15px rgba(0, 0, 0, 0.4);
        }

        /* Prevent text turning black */
        .package-box:has(input[type="radio"]:checked) .package-btn {
            color: #ffffff !important;
            text-shadow: 0 0 10px rgba(0, 255, 157, 0.8);
        }

        /* Mobile Fix */
        @media (max-width: 576px) {
            .package-box {
                width: 100%;
            }
        }
      </style>
   </head>
   <body>
      <!-- Premium Animated Background -->
      <div class="bg-animation" id="particles"></div>
      <div class="grid-lines"></div>
      <div class="energy-wave"></div>
      <div class="crypto-icons" id="cryptoIcons"></div>
      <div class="container">
         <!-- Sidebar -->
         @include('ui.sidebaruser')
         <!-- Sidebar -->
         <!-- Main Content -->
         <div class="main-content">
            @include('ui.topbaruser')
            <!-- Enter code here -->
            <div class="deposit-container">
               <!-- Deposit Header -->
               <div class="deposit-header">
                  <h1 class="deposit-title">REPAY LOAN</h1>
                  <!-- <p class="deposit-subtitle">Get Loan</p> -->
               </div>
               <!-- Status Messages -->
               <div id="messageContainer"></div>
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
               <!-- Deposit Card -->
               
                @if(!is_null($amount))
                   <div class="deposit-card">
                                        
                      <form action="/User/RepayLoan" method="POST">
                      @csrf
                      
                      <div class="deposit-content">
                            <div class="form-group">
                               <label class="form-label">
                               <i class="fas fa-dollar"></i>
                               Wallet Balance: $ {{$walletamount}}
                               </label>
                               <label class="form-label">
                               <i class="fas fa-dollar"></i>
                               Remaining Loan: $ {{$amount}}
                               </label>
                            </div>
                            @if($amount>0)
                            <div class="form-group">
		                           <label class="form-label">
		                           <i class="fas fa-money"></i>
		                           Amount <span>*</span>
		                           </label>
		                           <div class="input-group">
		                              <i class="fas fa-money input-icon"></i>
		                              <input type="number" id="amount" class="form-input @error('amount') is-invalid @enderror" max="{{$amount}}" name="amount" step="0.01" value="" placeholder="Enter Amount" required>
		                           </div>
		                           @error('amount')
						                    	<small class="invalid-feedback">
			                              {{ $message }}
			                          	</small>
						                   @enderror
		                        </div>
                           	@endif	
                            
                      </div>
                      
                      <div class="form-actions">
                      	@if($amount>0)
                         <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">
                         <i class="fas fa-dollar btn-icon"></i>
                         Submit
                         </button>
                        @endif
                         <a href="/User/Dashboard" style="text-decoration: none;"><button type="button" class="btn btn-secondary" id="backBtn">
                         <i class="fas fa-arrow-left btn-icon"></i>
                         BACK TO DASHBOARD
                         </button></a>
                      </div>
                      </form>
                   </div>
                @else
                    <div class="deposit-card">
                    <div class="deposit-content">
                        <div class="form-group">
                            <label class="form-label">You don't have any pending loan amount
                            </label>
                            
                        </div>
                    </div>
                   </div>
                @endif

            </div>
            <div class="footer">
               <p>© 2026 Cyera AI. All rights reserved.</p>
            </div>
         </div>
      </div>
      <script src="{{asset('ctassets/js/scriptui.js')}}"></script>
      <script>

         
         // Initialize everything when page loads
         window.addEventListener('DOMContentLoaded', () => {
           createParticles();
           createCryptoIcons();
           initCharts();
           
           // Add subtle animation to deposit card on load
           const depositCard = document.querySelector('.deposit-card');
           if (depositCard) {
               depositCard.style.opacity = '0';
               depositCard.style.transform = 'translateY(30px) scale(0.95)';
               
               setTimeout(() => {
                   depositCard.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                   depositCard.style.opacity = '1';
                   depositCard.style.transform = 'translateY(0) scale(1)';
               }, 300);
           }
           
           // Show initial message
           setTimeout(() => {
               showMessage('Welcome to Cyera AI', 'info');
           }, 1000);
           
           // Add ripple animation to CSS
           const style = document.createElement('style');
           style.textContent = `
               @keyframes ripple {
                   to {
                       transform: scale(4);
                       opacity: 0;
                   }
               }
               @keyframes sparkle {
                   0% { transform: scale(0); opacity: 0; }
                   50% { transform: scale(1); opacity: 1; }
                   100% { transform: scale(0); opacity: 0; }
               }
           `;
           document.head.appendChild(style);
         });
         
         // Close sidebar when clicking outside on mobile
         document.addEventListener('click', function(event) {
           const sidebar = document.getElementById('sidebar');
           const menuToggle = document.getElementById('menuToggle');
           
           if (window.innerWidth <= 992 && 
               sidebar.classList.contains('active') && 
               !sidebar.contains(event.target) && 
               !menuToggle.contains(event.target)) {
               sidebar.classList.remove('active');
           }
         });
      </script>


   </body>
</html>