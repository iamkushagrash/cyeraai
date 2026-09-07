@extends('layouts.user-mecha')

@section('title', 'Withdrawal Request - Cyera AI')
@section('page-title', 'Withdrawal Request')
@section('page-icon', 'fas fa-arrow-up-right-from-square')

@section('content')
@php
    $caiBalance = floor($user->remainingIncome());
    $usdtBalance = $caiBalance;
    $t = 0; $b = 0; $k = 0; $msg = '';
    if (!is_null($user->assetDetail())) {
        if ($detail->usdt_withdrawal_status == 1 && !is_null($user->assetDetail()->usdttrc20addr)) {
            $t = 1;
        } else {
            $msg .= " TRC20 ";
        }
        if ($detail->usdtbep20_withdrawal_status == 1 && !is_null($user->assetDetail()->usdtbep20addr)) {
            $b = 1;
        } else {
            $msg .= " USDT BEP20 ";
        }
        if ($detail->bank_withdrawal_status == 1 && !is_null($user->assetDetail()->accountno)) {
            $k = 1;
        } else {
            $msg .= " Bank Account ";
        }
    }
@endphp

<!-- Mini Stats Grid -->
<div class="mecha-stat-grid-2">
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>AVAILABLE BALANCE</span>
            <i class="fas fa-wallet" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold">${{ number_format($caiBalance, 2) }}</div>
        <div class="mecha-metric-sub">Eligible for Withdrawal</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>MINIMUM WITHDRAW</span>
            <i class="fas fa-circle-info" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green">$10.00</div>
        <div class="mecha-metric-sub">Multiple of $10</div>
    </div>
</div>

<div class="mecha-hud-card" style="max-width: 650px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-money-bill-transfer"></i>
            <div>
                <h2 class="mecha-card-title">EXECUTE WITHDRAWAL</h2>
                <div class="mecha-card-subtitle">Convert earnings to on-chain USDT</div>
            </div>
        </div>
        <span class="mecha-card-badge">DECENTRALIZED</span>
    </div>

    @if($t || $b || $k)
        <form action="{{ url('/User/WithdrawRequest') }}" method="POST">
            @csrf
            <input type="hidden" name="honeypotu" value="{{ Session::get('logtime') }}">

            <!-- Amount Input -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="amountusdt">
                    <span>Withdrawal Amount ($)</span>
                    <span class="label-sub">Max: ${{ number_format($caiBalance, 2) }}</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-dollar-sign mecha-input-icon"></i>
                    <input type="number" class="mecha-input-control @error('amountusdt') is-invalid @enderror" id="amountusdt" name="amountusdt" min="10" placeholder="e.g. 50" max="{{ $caiBalance }}" step="0.01" @if(!is_null($remaining)) value="{{ $remaining->amountusdt }}" disabled @endif required>
                </div>
            </div>

            <!-- Currency Selection -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="selltype">
                    <span>Settlement Network</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-network-wired mecha-input-icon"></i>
                    <select class="mecha-select-control @error('currency') is-invalid @enderror" id="selltype" name="currency">
                        @if($detail->usdt_withdrawal_status == 1 && !is_null($user->assetDetail()->usdttrc20addr))
                            <option value="usdt">USDT TRC20</option>
                        @endif
                        @if($detail->usdtbep20_withdrawal_status == 1 && !is_null($user->assetDetail()->usdtbep20addr))
                            <option value="usdtbep20">USDT BEP20</option>
                        @endif
                    </select>
                </div>
            </div>

            <!-- Destination Wallet Address (Display) -->
            <div class="mecha-form-group">
                <label class="mecha-form-label">Destination Payout Address</label>
                <div class="mecha-input-wrap" style="background: rgba(0,0,0,0.6); padding: 12px 14px; border: 1px dashed rgba(229, 168, 35, 0.4); border-radius: 8px;">
                    <span id="withdrawaladdress" style="font-family: monospace; font-size: 11px; color: #FFE082; word-break: break-all;">{{ $user->assetDetail()->usdttrc20addr }}</span>
                </div>
            </div>

            @if(!is_null($remaining))
                <!-- OTP Confirmation -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="otp">One-Time Password (OTP)</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-key mecha-input-icon"></i>
                        <input type="number" class="mecha-input-control @error('otp') is-invalid @enderror" id="otp" name="otp" placeholder="Enter Security OTP" required>
                    </div>
                </div>
            @endif

            <div style="display: flex; gap: 12px; margin-top: 22px; flex-wrap: wrap;">
                @if(ceil($user->remainingIncome()) >= 10)
                    <button type="submit" class="mecha-btn-gold" style="flex: 1; min-width: 160px;">
                        <i class="fas fa-lock"></i> SUBMIT WITHDRAWAL
                    </button>
                    @if(!is_null($remaining))
                        <a href="{{ url('/User/resendWithdrawOtp') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                            RESEND OTP
                        </a>
                    @endif
                @endif
                <a href="{{ url('/User/Dashboard') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                    CANCEL
                </a>
            </div>
        </form>
    @else
        <div style="text-align: center; padding: 24px 12px;">
            <i class="fas fa-triangle-exclamation" style="font-size: 32px; color: #FFA000; margin-bottom: 12px;"></i>
            <div style="font-size: 13px; font-weight: 800; color: #FFF; margin-bottom: 6px;">PAYOUT ADDRESS REQUIRED</div>
            <p style="font-size: 11px; color: #94A3B8; margin-bottom: 16px;">Please update your USDT payout wallet address in your Profile before requesting a withdrawal.</p>
            <a href="{{ url('/User/EditProfile') }}" class="mecha-btn-gold" style="display: inline-flex; width: auto; padding: 0 24px;">
                <i class="fas fa-user-pen"></i> UPDATE PROFILE WALLET
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#selltype').on('change', function(){
            @if(!is_null($user->assetDetail()))
                @if($detail->usdt_withdrawal_status == 1 && !is_null($user->assetDetail()->usdttrc20addr))
                    if ($(this).val() == 'usdt') {
                        $("#withdrawaladdress").html('{{ $user->assetDetail()->usdttrc20addr }}');
                    }
                @endif
                @if($detail->usdtbep20_withdrawal_status == 1 && !is_null($user->assetDetail()->usdtbep20addr))
                    if ($(this).val() == 'usdtbep20') {
                        $("#withdrawaladdress").html('{{ $user->assetDetail()->usdtbep20addr }}');
                    }
                @endif
            @endif
        }).trigger('change');
    });
</script>
@endpush