<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Cyera AI - Dashboard</title>
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
                  <h1 class="deposit-title">FUND DEPOSIT</h1>
                  <p class="deposit-subtitle">Add funds to your Cyera AI account</p>
               </div>
               <!-- Status Messages -->
               <div id="messageContainer"></div>
               <!-- Deposit Card -->
               <div class="deposit-card">
                  <!-- Warning Banner -->
                  <div class="warning-banner">
                     <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                     </div>
                     <div class="warning-text">
                        <strong>⚠️ IMPORTANT:</strong> This is an automated payment system. 
                        After making payment, <strong>DO NOT refresh or close</strong> this page until transaction is confirmed.
                        Your deposit will be processed automatically.
                     </div>
                  </div>
                  <!-- Deposit Content -->
                  <div class="deposit-content">
                     <!-- Main Form -->
                     <form id="depositForm">
                        <!-- Amount Input -->
                        <div class="form-group">
                           <label class="form-label">
                           <i class="fas fa-money-bill-wave"></i>
                           ENTER AMOUNT ($) <span>*</span>
                           </label>
                           <div class="input-group">
                              <i class="fas fa-dollar-sign input-icon"></i>
                              <input type="number" class="form-input" id="amount" 
                                 placeholder="Enter deposit amount" 
                                 required 
                                 min="10" 
                                 step="10">
                           </div>
                           <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">
                              Minimum deposit: $10
                           </div>
                        </div>
                        <!-- Currency Selection -->
                        <div class="form-group">
                           <label class="form-label">
                           <i class="fas fa-coins"></i>
                           SELECT CURRENCY <span>*</span>
                           </label>
                           <div class="input-group">
                              <i class="fas fa-wallet input-icon"></i>
                              <select class="currency-select" id="currency" required>
                                 <option value="">Select currency</option>
                                 <option value="USDT_BEP20" selected>USDT BEP20</option>
                                 <option value="USDT_ERC20">USDT ERC20</option>
                                 <option value="USDT_TRC20">USDT TRC20</option>
                                 <option value="BTC">Bitcoin (BTC)</option>
                                 <option value="ETH">Ethereum (ETH)</option>
                                 <option value="BNB">BNB (BEP20)</option>
                              </select>
                           </div>
                        </div>
                        <!-- Payment Info (Initially Hidden) -->
                        <div class="payment-info" id="paymentInfo" style="display: none;">
                           <div class="payment-info-title">
                              <i class="fas fa-qrcode"></i>
                              PAYMENT INSTRUCTIONS
                           </div>
                           <div class="payment-details">
                              <p>1. Send <strong id="paymentAmount">$0</strong> to the address below</p>
                              <p>2. Use <strong id="paymentCurrency">USDT BEP20</strong> network</p>
                              <p>3. Minimum 3 network confirmations required</p>
                              <p>4. Processing time: 5-15 minutes</p>
                           </div>
                           <!-- QR Code -->
                           <div class="qr-code-container" id="qrCodeContainer">
                              <div class="qr-code-placeholder" id="qrCode">
                                 <i class="fas fa-qrcode"></i>
                              </div>
                              <p style="color: var(--text-muted); font-size: 0.9rem;">
                                 Scan QR code to send payment
                              </p>
                           </div>
                           <!-- Wallet Address -->
                           <div class="payment-details" style="margin-top: 20px;">
                              <p><strong>Send to this address:</strong></p>
                              <div class="payment-address">
                                 <span id="walletAddress">0x742d35Cc6634C0532925a3b844Bc9e9...</span>
                                 <button type="button" class="copy-btn" id="copyAddressBtn">
                                 <i class="fas fa-copy"></i> COPY
                                 </button>
                              </div>
                              <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 10px;">
                                 ⚠️ Send only <strong id="networkType">USDT BEP20</strong> to this address
                              </p>
                           </div>
                        </div>
                        <!-- Transaction Status (Initially Hidden) -->
                        <div class="transaction-status" id="transactionStatus" style="display: none;">
                           <div class="status-icon pending" id="statusIcon">
                              <i class="fas fa-spinner fa-spin"></i>
                           </div>
                           <h3 class="status-title" id="statusTitle">Processing Payment</h3>
                           <p class="status-text" id="statusText">
                              Waiting for network confirmation. Please do not close this page.
                           </p>
                           <div class="transaction-id" id="transactionId">
                              TXN: MW-<span id="txnId">000000</span>
                           </div>
                           <div style="margin-top: 20px;">
                              <button type="button" class="btn btn-secondary" id="viewTransactionBtn">
                              <i class="fas fa-external-link-alt"></i> VIEW ON EXPLORER
                              </button>
                           </div>
                        </div>
                     </form>
                  </div>
                  <!-- Form Actions -->
                  <div class="form-actions">
                     <button type="button" class="btn btn-secondary" id="backBtn">
                     <i class="fas fa-arrow-left btn-icon"></i>
                     BACK TO DASHBOARD
                     </button>
                     <button type="button" class="btn btn-primary" id="generatePaymentBtn">
                     <i class="fas fa-qrcode btn-icon"></i>
                     GENERATE PAYMENT
                     </button>
                     <button type="submit" class="btn btn-primary" id="confirmPaymentBtn" style="display: none;">
                     <i class="fas fa-check-circle btn-icon"></i>
                     CONFIRM PAYMENT
                     </button>
                  </div>
               </div>
            </div>
            <div class="footer">
               <p>© 2026 Cyera AI. All rights reserved.</p>
            </div>
         </div>
      </div>
      <script src="{{asset('ctassets/js/scriptui.js')}}"></script>
      <script>
         function generateWalletAddress() {
           const chars = '0123456789abcdef';
           let address = '0x';
           for (let i = 0; i < 40; i++) {
               address += chars[Math.floor(Math.random() * chars.length)];
           }
           return address;
         }
         
         function generateTransactionId() {
           return Math.random().toString(36).substring(2, 10).toUpperCase();
         }
         
         function formatAmount(amount, currency) {
           const currencyNames = {
               'USDT_BEP20': 'USDT',
               'USDT_ERC20': 'USDT',
               'USDT_TRC20': 'USDT',
               'BTC': 'BTC',
               'ETH': 'ETH',
               'BNB': 'BNB'
           };
           return `${amount} ${currencyNames[currency]}`;
         }
         
         function formatNetwork(currency) {
           const networks = {
               'USDT_BEP20': 'BEP20',
               'USDT_ERC20': 'ERC20',
               'USDT_TRC20': 'TRC20',
               'BTC': 'Bitcoin',
               'ETH': 'Ethereum',
               'BNB': 'BEP20'
           };
           return `${currency.split('_')[0] || currency} ${networks[currency]}`;
         }
         
         function showMessage(text, type) {
           const container = document.getElementById('messageContainer');
           
           // Remove existing message
           const existingMsg = container.querySelector('.message');
           if (existingMsg) existingMsg.remove();
           
           // Create new message
           const message = document.createElement('div');
           message.className = `message ${type}`;
           message.textContent = `${text}`;
           message.style.display = 'block';
           
           container.appendChild(message);
           
           // Auto remove after 5 seconds
           setTimeout(() => {
               message.style.opacity = '0';
               message.style.transform = 'translateY(-10px)';
               setTimeout(() => message.remove(), 400);
           }, 5000);
         }
         
         // Generate payment button click
         document.getElementById('generatePaymentBtn').addEventListener('click', function() {
           const amount = document.getElementById('amount').value;
           const currency = document.getElementById('currency').value;
           
           // Validation
           if (!amount || amount < 10) {
               showMessage('Minimum deposit amount is $10', 'error');
               document.getElementById('amount').focus();
               return;
           }
           
           if (!currency) {
               showMessage('Please select a currency', 'error');
               return;
           }
           
           // Generate wallet address
           const walletAddress = generateWalletAddress();
           const transactionId = generateTransactionId();
           
           // Update payment info
           document.getElementById('paymentAmount').textContent = formatAmount(amount, currency);
           document.getElementById('paymentCurrency').textContent = formatNetwork(currency);
           document.getElementById('networkType').textContent = formatNetwork(currency);
           document.getElementById('walletAddress').textContent = walletAddress;
           document.getElementById('txnId').textContent = transactionId;
           
           // Show payment info
           document.getElementById('paymentInfo').style.display = 'block';
           document.getElementById('qrCodeContainer').style.display = 'block';
           
           // Switch buttons
           document.getElementById('generatePaymentBtn').style.display = 'none';
           document.getElementById('confirmPaymentBtn').style.display = 'flex';
           
           showMessage(`Payment details generated. Please send ${amount} ${currency.split('_')[0] || currency}`, 'info');
         });
         
         // Confirm payment button click
         document.getElementById('confirmPaymentBtn').addEventListener('click', async function() {
           const amount = document.getElementById('amount').value;
           const currency = document.getElementById('currency').value;
           
           // Show transaction status
           document.getElementById('paymentInfo').style.display = 'none';
           document.getElementById('transactionStatus').style.display = 'block';
           document.getElementById('confirmPaymentBtn').style.display = 'none';
           document.getElementById('backBtn').style.display = 'none';
           
           // Simulate payment processing
           const statusIcon = document.getElementById('statusIcon');
           const statusTitle = document.getElementById('statusTitle');
           const statusText = document.getElementById('statusText');
           
           // Step 1: Pending
           statusIcon.className = 'status-icon pending';
           statusIcon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
           statusTitle.textContent = 'Processing Payment';
           statusText.textContent = 'Waiting for network confirmation...';
           
           await new Promise(resolve => setTimeout(resolve, 3000));
           
           // Step 2: Confirming
           statusTitle.textContent = 'Confirming Transaction';
           statusText.textContent = 'Scanning blockchain for confirmations...';
           
           await new Promise(resolve => setTimeout(resolve, 4000));
           
           // Step 3: Success
           statusIcon.className = 'status-icon success';
           statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
           statusTitle.textContent = 'Payment Successful!';
           statusText.textContent = `Your deposit of ${amount} ${currency.split('_')[0] || currency} has been confirmed and added to your wallet.`;
           
           // Show success message
           showMessage(`Deposit of ${amount} ${currency.split('_')[0] || currency} completed successfully!`, 'success');
           
           // Show back button
           document.getElementById('backBtn').style.display = 'flex';
           document.getElementById('backBtn').innerHTML = '<i class="fas fa-home btn-icon"></i> RETURN TO DASHBOARD';
         });
         
         // Copy wallet address
         document.getElementById('copyAddressBtn').addEventListener('click', function() {
           const address = document.getElementById('walletAddress').textContent;
           
           navigator.clipboard.writeText(address).then(() => {
               const originalText = this.innerHTML;
               this.innerHTML = '<i class="fas fa-check"></i> COPIED!';
               this.style.background = 'rgba(0, 255, 157, 0.2)';
               this.style.borderColor = 'rgba(0, 255, 157, 0.3)';
               
               setTimeout(() => {
                   this.innerHTML = originalText;
                   this.style.background = '';
                   this.style.borderColor = '';
               }, 2000);
               
               showMessage('Wallet address copied to clipboard', 'success');
           });
         });
         
         // Back button
         document.getElementById('backBtn').addEventListener('click', function() {
           if (confirm('Are you sure you want to go back? Any ongoing transaction will be cancelled.')) {
               // Reset form
               document.getElementById('paymentInfo').style.display = 'none';
               document.getElementById('transactionStatus').style.display = 'none';
               document.getElementById('generatePaymentBtn').style.display = 'flex';
               document.getElementById('confirmPaymentBtn').style.display = 'none';
               document.getElementById('backBtn').innerHTML = '<i class="fas fa-arrow-left btn-icon"></i> BACK TO DASHBOARD';
               
               showMessage('Transaction cancelled', 'info');
           }
         });
         
         // View transaction button
         document.getElementById('viewTransactionBtn').addEventListener('click', function() {
           const txnId = document.getElementById('txnId').textContent;
           showMessage(`Opening transaction ${txnId} in explorer...`, 'info');
           
           setTimeout(() => {
               showMessage('Blockchain explorer opened in new tab', 'success');
           }, 1000);
         });
         
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
               showMessage('Welcome to Cyera AI Deposit Portal', 'info');
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