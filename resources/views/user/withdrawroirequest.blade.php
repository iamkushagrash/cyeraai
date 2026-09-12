@extends('layouts.user-mecha')

@section('title', 'Staking ROI Claim & Withdrawal - Cyera AI')
@section('page-title', 'Staking ROI Claim')
@section('page-icon', 'fas fa-bolt-lightning')

@section('page-actions')
<a href="{{ url('/User/RoiWithdrawalHistory') }}" class="mecha-btn-outline" style="height: 32px; padding: 0 10px; font-size: 10px;">
    <i class="fas fa-clock-rotate-left" style="color: #FFD700;"></i> History
</a>
@endsection

@section('content')
@php
    $caiTokens = (float)($roiRemainingCai ?? 0);
    $caiUsdVal = (float)($roiUsdDynamic ?? 0);
    $capRemain = (float)($totalRemainingCapping ?? 0);
    $capInCai = (float)($cappingInCai ?? 0);
    $maxClaimCai = (float)($maxClaimableCai ?? 0);
    $maxClaimUsdt = (float)($maxClaimableUsdt ?? 0);
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

<!-- Top Mining Hub Link Banner -->
<div style="background: rgba(0, 229, 255, 0.08); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-microchip" style="font-size: 20px; color: #00E5FF;"></i>
        <div>
            <strong style="color: #00E5FF; font-size: 13px;">⚡ Decentralized CAI Mining &amp; DEX Swap</strong>
            <div style="color: #8C9BAE; font-size: 10.5px;">Mine your ROI into protocol holdings with 0% capping deduction and sell on PancakeSwap with dynamic capping protection.</div>
        </div>
    </div>
    <a href="{{ url('/User/Mining') }}" class="mecha-btn-gold" style="height: 34px; padding: 0 16px; font-size: 11px; background: linear-gradient(135deg, #00E5FF 0%, #0088FF 100%); color: #000; font-weight: 800; border-color: #00E5FF;">
        <i class="fas fa-hammer"></i> Open Mining Hub
    </a>
</div>

<!-- Top Stats Grid -->
<div class="mecha-stat-grid-2">
    <div class="mecha-metric-box" style="border-color: rgba(255, 215, 0, 0.4);">
        <div class="mecha-metric-lbl">
            <span style="color: #FFD700;">ACCUMULATED YIELD</span>
            <i class="fas fa-bolt-lightning" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold" style="font-size: 1.15rem; line-height: 1.2;">
            {{ ($caiTokens < 1 && $caiTokens > 0) ? number_format($caiTokens, 4) : number_format($caiTokens, 2) }} <small style="font-size: 11px; color: #FFF;">CAI</small>
        </div>
        <div class="mecha-metric-sub" style="color: #38BDF8; font-weight: 700;">
            ≈ ${{ number_format($caiUsdVal, 2) }} USDT
        </div>
    </div>

    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>ACTIVE CAPPING LIMIT</span>
            <i class="fas fa-shield-halved" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green" style="font-size: 1.15rem; line-height: 1.2;">
            ${{ number_format($capRemain, 2) }} <small style="font-size: 11px; color: #A7F3D0;">USDT</small>
        </div>
        <div class="mecha-metric-sub" style="color: #FFD700;">
            Max Cap: {{ ($capInCai < 1 && $capInCai > 0) ? number_format($capInCai, 4) : number_format($capInCai, 2) }} CAI
        </div>
    </div>
</div>

<!-- Main Withdrawal HUD Card -->
<div class="mecha-hud-card" style="position: relative;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-bolt" style="color: #FFD700;"></i>
            <div>
                <h2 class="mecha-card-title">CLAIM STAKING ROI</h2>
                <div class="mecha-card-subtitle">Burns CAI tokens at live rate &amp; deducts deposit capping</div>
            </div>
        </div>
        <span class="mecha-card-badge">DAILY YIELD</span>
    </div>

    @if($t || $b || $k)
        <!-- Authenticated Destination Wallet Bar -->
        <div style="background: rgba(245, 166, 35, 0.05); border: 1px solid rgba(245, 166, 35, 0.2); border-radius: 10px; padding: 10px 12px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255, 215, 0, 0.12); color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 8.5px; color: #8C9BAE; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Connected Web3 Destination</div>
                    <div style="font-family: monospace; font-size: 11px; color: #38BDF8; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ !empty($destWallet) ? (substr($destWallet, 0, 10) . '...' . substr($destWallet, -8)) : 'No address linked' }}
                    </div>
                </div>
            </div>
            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 9px; color: #FFD700; font-weight: 700; background: rgba(245,166,35,0.1); padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(245,166,35,0.3); flex-shrink: 0;">
                <i class="fas fa-lock"></i> Web3 Locked
            </span>
        </div>

        <form action="{{ url('/User/RoiWithdrawRequest') }}" method="POST" id="withdrawRoiForm">
            @csrf
            <input type="hidden" name="honeypotu" value="{{ Session::get('logtime') }}">

            <!-- Amount Input (CAI Tokens) -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="amountcai">
                    <span>CLAIM AMOUNT (CAI)</span>
                    <span class="label-sub" style="color: #FFD700;">Max Claimable: {{ ($maxClaimCai < 1 && $maxClaimCai > 0) ? number_format($maxClaimCai, 4) : number_format($maxClaimCai, 2) }} CAI</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-coins mecha-input-icon" style="color: #FFD700;"></i>
                    <input type="number" 
                           class="mecha-input-control @error('amountcai') is-invalid @enderror" 
                           id="amountcai" 
                           name="amountcai" 
                           min="0.00000001" 
                           placeholder="0.0000" 
                           max="{{ $maxClaimCai }}" 
                           step="any" 
                           required>
                    <button type="button" class="mecha-input-suffix-btn" onclick="setPercentPreset(100)">MAX</button>
                </div>

                <!-- Quick Percentage Presets -->
                <div style="display: flex; gap: 6px; margin-top: 6px; flex-wrap: wrap;">
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px;" onclick="setPercentPreset(25)">25%</button>
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px;" onclick="setPercentPreset(50)">50%</button>
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px;" onclick="setPercentPreset(75)">75%</button>
                    <button type="button" class="mecha-btn-outline" style="height: 28px; padding: 0 10px; font-size: 11px; border-color: #FFD700; color: #FFD700;" onclick="setPercentPreset(100)">MAX</button>
                </div>

                <div style="font-size: 10px; color: #8C9BAE; margin-top: 5px; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fas fa-bolt" style="color: #FFD700;"></i> Live Valuation: <strong id="live-est-usdt" style="color: #00FF88;">$0.00 USDT</strong></span>
                    <span style="color: #FFD700; font-family: monospace;">1 CAI = ${{ number_format($price, 2) }}</span>
                </div>

                @error('amountcai')
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
                    <i class="fas fa-network-wired mecha-input-icon"></i>
                    <select class="mecha-select-control" id="currency" name="currency" @if(!is_null($remaining)) disabled @endif required>
                        @if($b)<option value="usdtbep20" {{ (!is_null($remaining) && $remaining->currency == 'usdtbep20') ? 'selected' : '' }}>USDT (BEP-20 / BNB Chain)</option>@endif
                        @if($t)<option value="usdt" {{ (!is_null($remaining) && $remaining->currency == 'usdt') ? 'selected' : '' }}>USDT (TRC-20 / Tron)</option>@endif
                        @if($k)<option value="bank" {{ (!is_null($remaining) && $remaining->currency == 'bank') ? 'selected' : '' }}>Bank Wire Transfer</option>@endif
                    </select>
                </div>
            </div>

            <!-- Real-Time Settlement Breakdown Card -->
            <div style="background: rgba(3, 5, 8, 0.9); border: 1px solid rgba(229, 168, 35, 0.2); border-radius: 10px; padding: 10px 12px; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; font-size: 9.5px; font-weight: 800; color: #8C9BAE; letter-spacing: 0.8px; margin-bottom: 8px;">
                    <i class="fas fa-receipt" style="color: #FFD700;"></i> SETTLEMENT BREAKDOWN
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span style="color: #8C9BAE;">CAI Tokens Burned:</span>
                    <span style="color: #FFD700; font-weight: 700;" id="disp-cai">0.0000 CAI</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span style="color: #8C9BAE;">Live Gross Equivalent:</span>
                    <span style="color: #FFF; font-weight: 700;" id="disp-gross">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px;">
                    <span style="color: #8C9BAE;">Deposit Capping Deducted:</span>
                    <span style="color: #00FF88; font-weight: 700;" id="disp-capping">-$0.00</span>
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
                <button type="submit" class="mecha-btn-gold" id="btnSubmitRoiWithdraw">
                    <i class="fas fa-bolt"></i>
                    <span>CLAIM STAKING ROI</span>
                </button>
            </div>

        </form>
    @else
        <!-- Missing Wallet Address State -->
        <div style="text-align: center; padding: 24px 12px;">
            <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255, 215, 0, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 20px; margin: 0 auto 12px;">
                <i class="fas fa-wallet"></i>
            </div>
            <h3 style="font-size: 14px; font-weight: 800; color: #FFF; margin-bottom: 6px;">WALLET ADDRESS REQUIRED</h3>
            <p style="font-size: 11px; color: #8C9BAE; max-width: 280px; margin: 0 auto 16px;">Please configure your BEP-20 USDT payout address in your profile to request withdrawals.</p>
            <a href="{{ url('/User/EditProfile') }}" class="mecha-btn-gold" style="display: inline-flex; width: auto; padding: 0 20px; height: 38px;">
                <i class="fas fa-gear"></i> Setup Wallet Address
            </a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
const CAI_PRICE = {{ $price }};
const MAX_CLAIMABLE_CAI = {{ $maxClaimCai }};

function setPercentPreset(percent) {
    const input = document.getElementById('amountcai');
    if (!input || input.disabled) return;
    
    let target = (MAX_CLAIMABLE_CAI * percent) / 100;
    if (target < 1 && target > 0) {
        input.value = parseFloat(target.toFixed(6));
    } else {
        input.value = parseFloat(target.toFixed(4));
    }
    updateLedger();
}

function updateLedger() {
    const input = document.getElementById('amountcai');
    if (!input) return;
    
    let caiAmt = parseFloat(input.value) || 0;
    if (caiAmt < 0) caiAmt = 0;

    let grossUsdt = caiAmt * CAI_PRICE;
    let fee = grossUsdt * 0.10;
    let net = grossUsdt - fee;

    let formattedCai = (caiAmt < 1 && caiAmt > 0) ? caiAmt.toFixed(4) : caiAmt.toFixed(2);
    
    const liveEst = document.getElementById('live-est-usdt');
    if (liveEst) liveEst.innerText = '$' + grossUsdt.toFixed(2) + ' USDT';

    const dispCai = document.getElementById('disp-cai');
    if (dispCai) dispCai.innerText = formattedCai + ' CAI';

    const dispGross = document.getElementById('disp-gross');
    if (dispGross) dispGross.innerText = '$' + grossUsdt.toFixed(2);

    const dispCapping = document.getElementById('disp-capping');
    if (dispCapping) dispCapping.innerText = '-$' + grossUsdt.toFixed(2);

    const dispFee = document.getElementById('disp-fee');
    if (dispFee) dispFee.innerText = '-$' + fee.toFixed(2);

    const dispNet = document.getElementById('disp-net');
    if (dispNet) dispNet.innerText = '$' + net.toFixed(2) + ' USDT';
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('amountcai');
    if (input) {
        input.addEventListener('input', updateLedger);
        updateLedger();
    }

    const form = document.getElementById('withdrawRoiForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmitRoiWithdraw') || document.getElementById('btnConfirmRoiWithdraw');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    }
});
</script>
@endpush
