@extends('layouts.user-mecha')

@section('title', 'Deposit & Invest - Cyera AI')
@section('page-title', 'Deposit Funds')
@section('page-icon', 'fas fa-coins')

@section('content')
@php
    $trcAddr = (!is_null($detail->usdt) && $detail->usdt_deposit_status==1) ? \Illuminate\Support\Facades\Crypt::decrypt($detail->usdt) : '';
    $bepAddr = (!is_null($detail->usdtbep20) && $detail->usdtbep20_deposit_status==1) ? \Illuminate\Support\Facades\Crypt::decrypt($detail->usdtbep20) : '';
    $initialAddr = $trcAddr ?: $bepAddr;
@endphp

<div class="row" style="display: flex; flex-wrap: wrap; gap: 16px;">
    <!-- Left Column: Deposit Form -->
    <div style="flex: 1 1 320px;">
        <div class="mecha-hud-card">
            <div class="mecha-card-header">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-wallet"></i>
                    <div>
                        <h2 class="mecha-card-title">DEPOSIT FUNDS</h2>
                        <div class="mecha-card-subtitle">Fast on-chain deposit & CAI conversion</div>
                    </div>
                </div>
                <span class="mecha-card-badge">LIVE 24/7</span>
            </div>

            <form method="post" action="{{ url('/User/Deposit') }}" id="depositForm">
                @csrf
                <input type="hidden" name="honeypot" value="{{ \Session::get('logtime') }}">

                <!-- Amount ($) Input -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="amountusdt">
                        <span>Enter Amount ($)</span>
                        <span class="label-sub">Min: $25.00</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-dollar-sign mecha-input-icon"></i>
                        <input type="number" id="amountusdt" class="mecha-input-control @error('amountusdt') is-invalid @enderror" name="amountusdt" step="0.000001" placeholder="e.g. 500.00" min="25" required>
                    </div>
                </div>

                <!-- Equivalent CAI Tokens (Auto-calculated) -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="cai">
                        <span>Equivalent CAI Tokens</span>
                        <span class="label-sub">1 CAI = ${{ number_format($detail->price, 2) }}</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-circle-nodes mecha-input-icon"></i>
                        <input type="number" id="cai" class="mecha-input-control @error('cai') is-invalid @enderror" name="cai" step="0.000001" placeholder="Calculated CAI" required>
                    </div>
                </div>

                <!-- Currency Selector -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="currency">
                        <span>Payment Network / Currency</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-network-wired mecha-input-icon"></i>
                        <select class="mecha-select-control" id="currency" name="currency">
                            @if(!empty($trcAddr))
                                <option value="usdt" selected>USDT (TRC20 Network)</option>
                            @endif
                            @if(!empty($bepAddr))
                                <option value="usdtbep20">USDT (BEP20 BSC Network)</option>
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Transaction Hash Input -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="txnhash">
                        <span>Transaction Hash (TxID)</span>
                        <span class="label-sub">After sending, paste hash</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-receipt mecha-input-icon"></i>
                        <input type="text" name="txnhash" class="mecha-input-control @error('txnhash') is-invalid @enderror" id="txnhash" placeholder="0x... or TxID hash" required>
                    </div>
                </div>

                @if(!empty($initialAddr))
                    <div style="margin-top: 20px;">
                        <button type="submit" class="mecha-btn-gold" id="btnSubmitDeposit">
                            <i class="fas fa-bolt"></i> SUBMIT DEPOSIT
                        </button>
                    </div>
                @else
                    <div class="mecha-alert mecha-alert-danger" style="margin-top: 16px;">
                        <div class="alert-icon"><i class="fas fa-ban"></i></div>
                        <div class="alert-content">
                            <p>Deposits are temporarily offline. Please contact support.</p>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Right Column: QR Code & Wallet Address -->
    <div style="flex: 1 1 320px;">
        <div class="mecha-hud-card">
            <div class="mecha-card-header">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-qrcode"></i>
                    <div>
                        <h2 class="mecha-card-title">SCAN & SEND</h2>
                        <div class="mecha-card-subtitle">Official Cyera AI Receiver Address</div>
                    </div>
                </div>
                <span class="mecha-card-badge" id="networkBadge">TRC20</span>
            </div>

            <!-- QR Code Card -->
            <div class="mecha-qr-card">
                <div class="mecha-qr-frame">
                    <img id="qrimg" alt="Deposit QR Code" src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ $initialAddr }}">
                </div>

                <div class="mecha-form-group" style="width: 100%; margin-top: 8px;">
                    <label class="mecha-form-label" style="justify-content: center;">
                        <span id="networkLabel">DEPOSIT ADDRESS (TRC20)</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <input type="text" id="addrInput" class="mecha-input-control no-icon" readonly value="{{ $initialAddr }}" style="text-align: center; font-size: 11px; padding-right: 75px;">
                        <button type="button" class="mecha-input-suffix-btn" onclick="copyAddress()">
                            <i class="fas fa-copy"></i> COPY
                        </button>
                    </div>
                </div>

                <div style="margin-top: 10px; width: 100%; font-size: 10px; color: #8C9BAE; line-height: 1.5; text-align: left; background: rgba(0,0,0,0.3); padding: 10px 12px; border-radius: 6px; border: 1px dashed rgba(229, 168, 35, 0.25);">
                    <div style="color: #FFD700; font-weight: 700; margin-bottom: 4px;"><i class="fas fa-info-circle"></i> IMPORTANT INSTRUCTIONS:</div>
                    • Send only the selected currency (USDT) to this address.<br>
                    • Minimum deposit amount is <strong>$25.00</strong>.<br>
                    • Once broadcasted on-chain, copy the transaction hash and submit on the left form.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const pricePerCai = {{ (float)$detail->price }};
    const trcAddr = @json($trcAddr);
    const bepAddr = @json($bepAddr);

    $('#cai').on('input', function() {
        const val = parseFloat($(this).val()) || 0;
        if (val > 0 && pricePerCai > 0) {
            $('#amountusdt').val((val * pricePerCai).toFixed(2));
        }
    });

    $('#amountusdt').on('input', function() {
        const val = parseFloat($(this).val()) || 0;
        if (val > 0 && pricePerCai > 0) {
            $('#cai').val((val / pricePerCai).toFixed(4));
        }
    });

    $('#currency').on('change', function() {
        const cur = $(this).val();
        let targetAddr = (cur === 'usdtbep20') ? bepAddr : trcAddr;
        let badge = (cur === 'usdtbep20') ? 'BEP20 (BSC)' : 'TRC20';
        let label = 'DEPOSIT ADDRESS (' + badge + ')';

        $('#addrInput').val(targetAddr);
        $('#networkBadge').text(badge);
        $('#networkLabel').text(label);
        $('#qrimg').attr('src', 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + encodeURIComponent(targetAddr));
    });

    function copyAddress() {
        const copyText = document.getElementById("addrInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value).then(() => {
            alert("Deposit Address Copied:\n" + copyText.value);
        });
    }
</script>
@endpush
