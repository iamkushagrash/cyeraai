@extends('layouts.user-mecha')

@section('title', 'Deposit USDT - Cyera AI')
@section('page-title', 'Deposit Funds')
@section('page-icon', 'fas fa-arrow-down-to-bracket')

@section('content')
<div class="mecha-inner-body" style="max-width: 640px; margin: 0 auto;">

    <!-- Top Alert Messages -->
    @if (session('success'))
        <div class="mecha-alert mecha-alert-success" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0, 255, 136, 0.35); border-radius: 10px; color: #00FF88; font-size: 11.5px; font-weight: 700; margin-bottom: 12px;">
            <i class="fas fa-circle-check" style="font-size: 16px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('warning'))
        <div class="mecha-alert mecha-alert-danger" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: rgba(255, 77, 125, 0.1); border: 1px solid rgba(255, 77, 125, 0.35); border-radius: 10px; color: #FF4D7D; font-size: 11.5px; font-weight: 700; margin-bottom: 12px;">
            <i class="fas fa-triangle-exclamation" style="font-size: 16px;"></i>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if(!isset($payment))
        <!-- Deposit Request Form Card -->
        <div class="mecha-hud-card">
            <div class="mecha-card-header">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-vault"></i>
                    <div>
                        <h2 class="mecha-card-title">INSTANT USDT DEPOSIT</h2>
                        <div class="mecha-card-subtitle">Automated Web3 Gateway (BEP-20)</div>
                    </div>
                </div>
                <span class="mecha-card-badge">AUTOMATED</span>
            </div>

            <!-- Important Warning Note -->
            <div style="background: rgba(255, 160, 0, 0.08); border: 1px solid rgba(255, 160, 0, 0.3); border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 12px;">
                <i class="fas fa-triangle-exclamation" style="color: #FFA000; font-size: 18px; margin-top: 2px; flex-shrink: 0;"></i>
                <div style="font-size: 10.5px; color: #E2E8F0; line-height: 1.55;">
                    <strong style="color: #FFA000; letter-spacing: 0.5px;">IMPORTANT:</strong>
                    This is an automated payment gateway. After making payment, <strong>DO NOT refresh or close</strong> this page until transaction is confirmed. Your deposit will be credited automatically.
                </div>
            </div>

            <form method="POST" action="{{ url('/User/Deposit') }}">
                @csrf
                <input type="hidden" name="honeypot" value="{{ \Session::get('logtime') }}">

                <!-- Amount Input -->
                <div class="mecha-form-group" style="margin-bottom: 18px;">
                    <label class="mecha-form-label" for="amount">
                        <span>Enter Deposit Amount</span>
                        <span class="label-sub" style="color: #00FF88;">Min: $25 USDT</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-dollar-sign mecha-input-icon"></i>
                        <input type="number" id="amount" class="mecha-input-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" step="0.000001" min="25" placeholder="Amount (Minimum $25)" required>
                    </div>
                    @error('amount')
                        <small style="color: #FF4D7D; font-size: 10px; font-weight: 700; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Currency Selector -->
                <div class="mecha-form-group" style="margin-bottom: 24px;">
                    <label class="mecha-form-label" for="currency">
                        <span>Payment Network / Currency</span>
                        <span class="label-sub">Binance Smart Chain</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-coins mecha-input-icon"></i>
                        <select class="mecha-select-control @error('currency') is-invalid @enderror" id="currency" name="currency">
                            <option value="usdtbep20" selected>USDT BEP20 (BSC Network)</option>
                        </select>
                    </div>
                    @error('currency')
                        <small style="color: #FF4D7D; font-size: 10px; font-weight: 700; margin-top: 5px; display: block;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="mecha-btn-gold" style="height: 48px; font-size: 13px;">
                        <i class="fas fa-arrow-right" style="margin-right: 6px;"></i> PROCEED TO PAYMENT
                    </button>
                    <a href="{{ url('/User/Dashboard') }}" style="text-decoration: none;">
                        <button type="button" class="mecha-btn-secondary" style="width: 100%; height: 42px; font-size: 11px;">
                            <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> BACK TO DASHBOARD
                        </button>
                    </a>
                </div>
            </form>
        </div>
    @endif

    @if(isset($payment))
        <!-- Payment Checkout / QR Gateway Card -->
        <div class="mecha-hud-card">
            <div class="mecha-card-header">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-qrcode"></i>
                    <div>
                        <h2 class="mecha-card-title">AWAITING PAYMENT CONFIRMATION</h2>
                        <div class="mecha-card-subtitle">Scan QR or send USDT to the address below</div>
                    </div>
                </div>
                <span class="mecha-card-badge" style="background: rgba(0, 255, 136, 0.15); color: #00FF88; border-color: rgba(0, 255, 136, 0.4);">LIVE ORDER</span>
            </div>

            <!-- Payment Summary Box -->
            <div style="background: rgba(20, 26, 40, 0.7); border: 1px solid rgba(229, 168, 35, 0.35); border-radius: 12px; padding: 18px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                    <span style="font-size: 11px; color: #94A3B8; text-transform: uppercase; font-weight: 700;">Required Amount:</span>
                    <span style="font-size: 18px; font-weight: 900; color: #FFD700; letter-spacing: 0.5px;">${{ round($payment->amount, 2) }} USDT</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 11px; color: #94A3B8;">Network:</span>
                    <span style="font-size: 11px; font-weight: 800; color: #00FF88;">USDT (BEP-20 / BSC)</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 11px; color: #94A3B8;">Status:</span>
                    <span id="status" style="font-size: 11px; font-weight: 800; color: #FFA000; text-transform: uppercase;">
                        <i class="fas fa-spinner fa-spin" style="margin-right: 4px;"></i> Waiting for transaction...
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 11px; color: #94A3B8;">Received:</span>
                    <span style="font-size: 11px; font-weight: 800; color: #FFFFFF;"><span id="paid">0</span> / <span id="total">{{ round($payment->amount, 2) }}</span> USDT</span>
                </div>
            </div>

            <!-- QR Code Section -->
            <div style="text-align: center; margin: 20px 0;">
                <div style="display: inline-block; padding: 12px; background: #FFFFFF; border-radius: 12px; box-shadow: 0 0 25px rgba(255, 215, 0, 0.3);">
                    <img id="qrimg" alt="Deposit QR Code" style="width: 170px; height: 170px; display: block;" src="https://api.qrserver.com/v1/create-qr-code/?size=170x170&data={{ $payment->pay_address }}">
                </div>
                <div style="font-size: 11px; color: #94A3B8; margin-top: 10px; font-weight: 600;">Scan QR code with your crypto wallet to send payment</div>
            </div>

            <!-- Deposit Address Box with Copy Button -->
            <div class="mecha-form-group" style="margin-top: 20px;">
                <label class="mecha-form-label">
                    <span>Deposit Address (BEP20)</span>
                    <span class="label-sub" style="color: #FF4D7D;">Send BEP20 USDT Only</span>
                </label>
                <div class="mecha-input-wrap" style="position: relative;">
                    <input type="text" id="walletAddress" class="mecha-input-control" value="{{ $payment->pay_address }}" readonly style="padding-right: 90px; font-family: monospace; font-size: 11px; color: #FFE082;">
                    <button type="button" class="mecha-input-suffix-btn" id="copyAddressBtn" style="position: absolute; right: 6px; top: 6px; height: 32px; padding: 0 12px;">
                        <i class="fas fa-copy"></i> COPY
                    </button>
                </div>
            </div>

            <!-- Instructions Bullet Points -->
            <div style="background: rgba(0, 0, 0, 0.35); border-radius: 10px; padding: 14px; margin-top: 18px; font-size: 10.5px; color: #94A3B8; line-height: 1.6;">
                <div>• Minimum 3 network confirmations required on the blockchain.</div>
                <div>• Processing time is typically 1-2 minutes.</div>
                <div>• Do not close this browser window until the order confirms.</div>
            </div>

            <!-- Action Link -->
            <div style="margin-top: 20px;">
                <a href="{{ url('/User/DepositHistory') }}" style="text-decoration: none;">
                    <button type="button" class="mecha-btn-secondary" style="width: 100%;">
                        <i class="fas fa-clock-rotate-left" style="margin-right: 6px;"></i> VIEW DEPOSIT HISTORY
                    </button>
                </a>
            </div>
        </div>
    @endif

</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="myModal" tabindex="-1" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 99999; align-items: center; justify-content: center;">
    <div style="max-width: 400px; width: 90%; margin: 0 auto; background: #0B0F19; border: 1.5px solid #FFD700; border-radius: 14px; padding: 24px; text-align: center; box-shadow: 0 0 35px rgba(255, 215, 0, 0.4);">
        <i class="fas fa-circle-check" style="font-size: 42px; color: #00FF88; margin-bottom: 14px;"></i>
        <h3 style="font-size: 16px; font-weight: 800; color: #FFFFFF; margin-bottom: 8px;">TRANSACTION STATUS</h3>
        <p id="modaltext" style="font-size: 12px; color: #CBD5E1; margin-bottom: 20px; line-height: 1.5;"></p>
        <a class="mecha-btn-gold" id="modalbtn" href="{{ url('/User/DepositHistory') }}" style="display: block; width: 100%; text-decoration: none; padding: 12px; text-align: center;">
            CONTINUE
        </a>
    </div>
</div>

<script>
    // Copy Address Button
    document.getElementById('copyAddressBtn')?.addEventListener('click', function() {
        const address = document.getElementById('walletAddress')?.value || document.getElementById('walletAddress')?.textContent;
        if (!address) return;

        navigator.clipboard.writeText(address).then(() => {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> COPIED!';
            this.style.background = '#00FF88';
            this.style.color = '#000';
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.background = '';
                this.style.color = '';
            }, 2000);
        });
    });

    @if(isset($payment) && !is_null($payment))
    function paymentStatus() {
        fetch('/Transaction/transactionStatus/{{ $payment->payment_id }}')
            .then(res => res.json())
            .then(data => {
                if (data.transaction_status === 0) {
                    const statusEl = document.getElementById('status');
                    const paidEl = document.getElementById('paid');
                    const totalEl = document.getElementById('total');
                    if (statusEl) statusEl.innerText = data.payment_status || 'Waiting...';
                    if (paidEl) paidEl.innerText = data.paid || 0;
                    if (totalEl) totalEl.innerText = data.totalAmount || {{ round($payment->amount, 2) }};
                } else if (data.transaction_status > 0) {
                    const modalText = document.getElementById('modaltext');
                    const modal = document.getElementById('myModal');
                    if (modalText) modalText.innerText = data.transaction_message || 'Deposit processed successfully!';
                    if (modal) modal.style.display = 'flex';
                }
            })
            .catch(err => console.error('Status check error:', err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        setInterval(paymentStatus, 15000);
        paymentStatus();
    });
    @endif
</script>
@endsection