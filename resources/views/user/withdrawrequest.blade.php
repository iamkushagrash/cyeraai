@extends('layouts.user-mecha')

@section('title', 'Working Income Withdrawal - Cyera AI')
@section('page-title', 'Working Income Withdrawal')
@section('page-icon', 'fas fa-arrow-up-right-from-square')

@section('page-actions')
<a href="{{ url('/User/WithdrawalHistory') }}" class="mecha-btn-outline" style="height: 32px; padding: 0 10px; font-size: 10px; border-color: rgba(0, 255, 136, 0.4); color: #00FF88;">
    <i class="fas fa-clock-rotate-left" style="color: #00FF88;"></i> History
</a>
@endsection

@section('content')
@php
    $workingUsdt = (float)($workingRemainingUsdt ?? 0);
    $price = (float)($caiPrice ?? 1.0);
    if ($price <= 0) $price = 1.0;

    $t = 0; $b = 0; $k = 0;
    if (!is_null($user->assetDetail())) {
        if ($detail->usdt_withdrawal_status == 1 && !is_null($user->assetDetail()->usdttrc20addr)) {
            $t = 1;
        }
        if ($detail->usdtbep20_withdrawal_status == 1 && !is_null($user->assetDetail()->usdtbep20addr)) {
            $b = 1;
        }
        if ($detail->bank_withdrawal_status == 1 && !is_null($user->assetDetail()->accountno)) {
            $k = 1;
        }
    }

    $destWallet = $user->assetDetail()->usdtbep20addr ?? ($user->assetDetail()->usdttrc20addr ?? '');
@endphp

<!-- Top Stats Grid -->
<div class="mecha-stat-grid-2">
    <div class="mecha-metric-box" style="border-color: rgba(0, 255, 136, 0.4);">
        <div class="mecha-metric-lbl">
            <span style="color: #00FF88;">WORKING BALANCE</span>
            <i class="fas fa-users-gear" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green" style="font-size: 1.15rem; line-height: 1.2;">
            ${{ number_format($workingUsdt, 2) }} <small style="font-size: 11px; color: #A7F3D0;">USDT</small>
        </div>
        <div class="mecha-metric-sub" style="color: #6EE7B7;">
            Network Earnings
        </div>
    </div>

    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>SETTLEMENT SPEED</span>
            <i class="fas fa-bolt" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold" style="font-size: 1.15rem; line-height: 1.2;">
            INSTANT
        </div>
        <div class="mecha-metric-sub" style="color: #8C9BAE;">
            BEP-20 / TRC-20
        </div>
    </div>
</div>

<!-- Main Withdrawal HUD Card -->
<div class="mecha-hud-card" style="position: relative;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-money-bill-transfer" style="color: #00FF88;"></i>
            <div>
                <h2 class="mecha-card-title">EXECUTE WORKING WITHDRAWAL</h2>
                <div class="mecha-card-subtitle">Direct payout of Direct, Level, Pool &amp; Rank earnings</div>
            </div>
        </div>
        <span class="mecha-card-badge" style="background: rgba(0, 255, 136, 0.12); color: #00FF88; border-color: rgba(0, 255, 136, 0.35);">
            WORKING
        </span>
    </div>

    @if($t || $b || $k)
        <!-- Authenticated Destination Wallet Bar -->
        <div style="background: rgba(0, 255, 136, 0.04); border: 1px solid rgba(0, 255, 136, 0.2); border-radius: 10px; padding: 10px 12px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(0, 255, 136, 0.12); color: #00FF88; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 8.5px; color: #8C9BAE; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Connected Web3 Destination</div>
                    <div style="font-family: monospace; font-size: 11px; color: #00FF88; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ !empty($destWallet) ? (substr($destWallet, 0, 10) . '...' . substr($destWallet, -8)) : 'No address linked' }}
                    </div>
                </div>
            </div>
            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 9px; color: #00FF88; font-weight: 700; background: rgba(0,255,136,0.1); padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(0,255,136,0.3); flex-shrink: 0;">
                <i class="fas fa-lock"></i> Web3 Locked
            </span>
        </div>

        <form action="{{ url('/User/WithdrawRequest') }}" method="POST" id="withdrawWorkingForm">
            @csrf
            <input type="hidden" name="honeypotu" value="{{ Session::get('logtime') }}">

            <!-- Amount Input -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="amountusdt">
                    <span>Withdrawal Amount ($)</span>
                    <span class="label-sub" style="color: #00FF88;">Available: ${{ number_format($workingUsdt, 2) }}</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-dollar-sign mecha-input-icon" style="color: #00FF88;"></i>
                    <input type="number" 
                           class="mecha-input-control @error('amountusdt') is-invalid @enderror" 
                           id="amountusdt" 
                           name="amountusdt" 
                           min="0.01" 
                           placeholder="0.00" 
                           max="{{ $workingUsdt }}" 
                           step="any" 
                           required>
                    <button type="button" class="mecha-input-suffix-btn" style="border-color: rgba(0, 255, 136, 0.4); color: #00FF88;" onclick="setPresetPct(100)">MAX</button>
                </div>

                <!-- Quick Presets -->
                <div style="display: flex; gap: 6px; margin-top: 6px; flex-wrap: wrap;">
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px;" onclick="setPresetPct(25)">25%</button>
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px;" onclick="setPresetPct(50)">50%</button>
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px;" onclick="setPresetPct(75)">75%</button>
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px; border-color: #00FF88; color: #00FF88;" onclick="setPresetPct(100)">MAX</button>
                </div>

                <div style="font-size: 9.5px; color: #8C9BAE; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                    <i class="fas fa-circle-info" style="color: #00FF88;"></i> Instant BEP-20 / TRC-20 on-chain settlement
                </div>

                @error('amountusdt')
                    <span style="color: #FF4D7D; font-size: 11px; font-weight: 700; margin-top: 3px; display: block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Settlement Network Selection -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="currency">
                    <span>Settlement Network</span>
                    <span class="label-sub">Select payout blockchain</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-network-wired mecha-input-icon" style="color: #00FF88;"></i>
                    <select class="mecha-select-control" id="currency" name="currency" required>
                        @if($b)<option value="usdtbep20">USDT (BEP-20 / BNB Chain)</option>@endif
                        @if($t)<option value="usdt">USDT (TRC-20 / Tron)</option>@endif
                        @if($k)<option value="bank">Bank Wire Transfer</option>@endif
                    </select>
                </div>
            </div>

            <!-- Real-Time Settlement Breakdown Card -->
            <div style="background: rgba(3, 5, 8, 0.9); border: 1px solid rgba(0, 255, 136, 0.2); border-radius: 10px; padding: 10px 12px; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; font-size: 9.5px; font-weight: 800; color: #8C9BAE; letter-spacing: 0.8px; margin-bottom: 8px;">
                    <i class="fas fa-receipt" style="color: #00FF88;"></i> SETTLEMENT BREAKDOWN
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span style="color: #8C9BAE;">Requested Gross:</span>
                    <span style="color: #FFF; font-weight: 700;" id="disp-gross">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 6px;">
                    <span style="color: #8C9BAE;">Protocol Fee (10%):</span>
                    <span style="color: #FF4D7D; font-weight: 700;" id="disp-fee">-$0.00</span>
                </div>
                <div style="height: 1px; background: rgba(255, 255, 255, 0.08); margin: 6px 0;"></div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 800;">
                    <span style="color: #FFF;">Net USDT Payable:</span>
                    <span style="color: #00FF88;" id="disp-net">$0.00 USDT</span>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="margin-top: 12px;">
                <button type="submit" class="mecha-btn-cyan" id="btnSubmitWorkingWithdraw">
                    <i class="fas fa-money-bill-transfer"></i>
                    <span>SUBMIT WITHDRAWAL REQUEST</span>
                </button>
            </div>

        </form>
    @else
        <!-- Missing Wallet Address State -->
        <div style="text-align: center; padding: 24px 12px;">
            <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0, 255, 136, 0.3); color: #00FF88; display: flex; align-items: center; justify-content: center; font-size: 20px; margin: 0 auto 12px;">
                <i class="fas fa-wallet"></i>
            </div>
            <h3 style="font-size: 14px; font-weight: 800; color: #FFF; margin-bottom: 6px;">WALLET ADDRESS REQUIRED</h3>
            <p style="font-size: 11px; color: #8C9BAE; max-width: 280px; margin: 0 auto 16px;">Please configure your BEP-20 USDT payout address in your profile to request working withdrawals.</p>
            <a href="{{ url('/User/EditProfile') }}" class="mecha-btn-cyan" style="display: inline-flex; width: auto; padding: 0 20px; height: 38px;">
                <i class="fas fa-gear"></i> Setup Wallet Address
            </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
const MAX_WORKING_AVAILABLE = {{ $workingUsdt }};

function setPresetPct(pct) {
    const input = document.getElementById('amountusdt');
    if (!input || input.disabled) return;
    
    let finalAmt = (MAX_WORKING_AVAILABLE * (pct / 100));
    input.value = finalAmt.toFixed(2);
    updateLedger();
}

function updateLedger() {
    const input = document.getElementById('amountusdt');
    if (!input) return;
    
    let gross = parseFloat(input.value) || 0;
    if (gross < 0) gross = 0;

    let fee = gross * 0.10;
    let net = gross - fee;

    document.getElementById('disp-gross').innerText = '$' + gross.toFixed(2);
    document.getElementById('disp-fee').innerText = '-$' + fee.toFixed(2);
    document.getElementById('disp-net').innerText = '$' + net.toFixed(2) + ' USDT';
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('amountusdt');
    if (input) {
        input.addEventListener('input', updateLedger);
        updateLedger();
    }

    const form = document.getElementById('withdrawWorkingForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmitWorkingWithdraw') || document.getElementById('btnConfirmWorkingWithdraw');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    }
});
</script>
@endpush