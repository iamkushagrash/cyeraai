@extends('layouts.user-mecha')

@section('title', 'Withdrawal Portal - Cyera AI')
@section('page-title', 'Withdrawal Portal')
@section('page-icon', 'fas fa-arrow-up-right-from-square')

@section('content')
@php
    $roiUsdt = (float)($roiRemainingUsdt ?? 0);
    $roiCai = (float)($roiRemainingCai ?? 0);
    $workingUsdt = (float)($workingRemainingUsdt ?? 0);
    $price = (float)($caiPrice ?? 1.0);
    if ($price <= 0) $price = 1.0;

    $isWithdrawalDisabled = (time() < strtotime('2026-09-10 22:00:00'));

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
@endphp

@if($isWithdrawalDisabled)
    <!-- Global Withdrawal Lock Alert Banner -->
    <div style="background: rgba(239, 68, 68, 0.12); border: 1.5px solid rgba(239, 68, 68, 0.4); border-radius: 12px; padding: 18px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(239, 68, 68, 0.2); display: flex; align-items: center; justify-content: center; color: #EF4444; font-size: 20px; flex-shrink: 0; box-shadow: 0 0 15px rgba(239, 68, 68, 0.25);">
            <i class="fas fa-lock"></i>
        </div>
        <div>
            <div style="font-size: 14px; font-weight: 800; color: #FCA5A5; margin-bottom: 3px; letter-spacing: 0.5px;">
                WITHDRAWAL PROTOCOL TEMPORARILY LOCKED
            </div>
            <div style="font-size: 12px; color: #CBD5E1; line-height: 1.45;">
                All withdrawals (<strong>Staking ROI</strong> &amp; <strong>Working Incomes</strong>) are temporarily disabled until <strong style="color: #FFD700; font-weight: 800;">10:00 PM, 10th September 2026</strong>. The portal will automatically resume normal operations after this time.
            </div>
        </div>
    </div>
@endif

<!-- Dual Stream Balance Header -->
<div class="mecha-stat-grid-2" style="margin-bottom: 24px;">
    <!-- Stream 1: Staking ROI Balance -->
    <div class="mecha-metric-box withdraw-balance-card" id="card-roi-balance" style="border-left: 3px solid #FFD700; background: linear-gradient(135deg, rgba(255, 215, 0, 0.05) 0%, rgba(4, 6, 10, 0.95) 100%); position: relative; opacity: {{ $isWithdrawalDisabled ? '0.75' : '1' }};">
        @if($isWithdrawalDisabled)
            <div style="position: absolute; top: 10px; right: 12px; font-size: 9px; font-weight: 800; background: rgba(239, 68, 68, 0.2); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.4); padding: 2px 8px; border-radius: 4px;">
                <i class="fas fa-lock"></i> LOCKED TILL 10 PM 10 SEP
            </div>
        @endif
        <div class="mecha-metric-lbl">
            <span style="letter-spacing: 1px;">STAKING ROI (CPS)</span>
            <i class="fas fa-bolt" style="color: #FFD700;"></i>
        </div>
        <div style="display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; margin: 8px 0 4px;">
            <div class="mecha-metric-val gold" style="font-size: 26px;">{{ number_format($roiCai, 2) }} <span style="font-size: 14px; color: #FFE082;">CAI</span></div>
            <div style="font-size: 14px; font-weight: 700; color: #94A3B8;">(${{ number_format($roiUsdt, 2) }})</div>
        </div>
        <div class="mecha-metric-sub">Dedicated Staking Yield Stream</div>
    </div>

    <!-- Stream 2: Working Income Balance -->
    <div class="mecha-metric-box withdraw-balance-card" id="card-working-balance" style="border-left: 3px solid #00FF88; background: linear-gradient(135deg, rgba(0, 255, 136, 0.05) 0%, rgba(4, 6, 10, 0.95) 100%); position: relative; opacity: {{ $isWithdrawalDisabled ? '0.75' : '1' }};">
        @if($isWithdrawalDisabled)
            <div style="position: absolute; top: 10px; right: 12px; font-size: 9px; font-weight: 800; background: rgba(239, 68, 68, 0.2); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.4); padding: 2px 8px; border-radius: 4px;">
                <i class="fas fa-lock"></i> LOCKED TILL 10 PM 10 SEP
            </div>
        @endif
        <div class="mecha-metric-lbl">
            <span style="letter-spacing: 1px;">WORKING INCOMES</span>
            <i class="fas fa-users-gear" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green" style="font-size: 26px; margin: 8px 0 4px;">${{ number_format($workingUsdt, 2) }}</div>
        <div class="mecha-metric-sub">Direct + Level + Pool + Rank Incomes</div>
    </div>
</div>

<div class="mecha-hud-card" style="max-width: 680px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-money-bill-transfer" style="color: #FFD700;"></i>
            <div>
                <h2 class="mecha-card-title">EXECUTE WITHDRAWAL</h2>
                <div class="mecha-card-subtitle">Convert earnings to on-chain USDT</div>
            </div>
        </div>
        <span class="mecha-card-badge">{{ $isWithdrawalDisabled ? 'LOCKED' : 'DECENTRALIZED' }}</span>
    </div>

    @if($t || $b || $k)
        <form action="{{ url('/User/WithdrawRequest') }}" method="POST" id="withdrawForm">
            @csrf
            <input type="hidden" name="honeypotu" value="{{ Session::get('logtime') }}">
            <input type="hidden" name="withdraw_type" id="withdraw_type" value="{{ !is_null($remaining) ? $remaining->comments : 'working' }}">

            @if(is_null($remaining))
                <!-- Stream Selection Pipeline -->
                <div class="mecha-form-group" style="margin-bottom: 22px;">
                    <label class="mecha-form-label">
                        <span>Select Income Source</span>
                        <span class="label-sub" style="color: #FFE082;">Choose which balance to withdraw</span>
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 6px;">
                        <!-- Option A: Staking ROI -->
                        <div class="stream-option" id="opt-roi" onclick="selectStream('roi')" style="cursor: {{ $isWithdrawalDisabled ? 'not-allowed' : 'pointer' }}; padding: 14px; border-radius: 10px; border: 1.5px solid rgba(255, 255, 255, 0.1); background: rgba(255, 255, 255, 0.02); transition: all 0.25s ease; position: relative; opacity: {{ $isWithdrawalDisabled ? '0.6' : '1' }};">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <div style="font-size: 13px; font-weight: 800; color: #FFF; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-bolt" style="color: #FFD700;"></i> Staking ROI
                                </div>
                                @if($isWithdrawalDisabled)
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; background: rgba(239,68,68,0.2); color: #EF4444; border: 1px solid rgba(239,68,68,0.4); border-radius: 4px;">LOCKED</span>
                                @else
                                    <span class="stream-badge-roi" style="font-size: 10px; font-weight: 800; padding: 2px 6px; background: rgba(255,215,0,0.2); color: #FFD700; border-radius: 4px; display: none;">ACTIVE</span>
                                @endif
                            </div>
                            <div style="font-size: 11px; color: #94A3B8;">Avail: <strong style="color: #FFD700;">${{ number_format($roiUsdt, 2) }}</strong> ({{ number_format($roiCai, 2) }} CAI)</div>
                            @if($isWithdrawalDisabled)
                                <div style="font-size: 9.5px; color: #EF4444; margin-top: 4px; font-weight: 700;">
                                    <i class="fas fa-clock"></i> Opens 10 Sep, 10:00 PM
                                </div>
                            @endif
                        </div>

                        <!-- Option B: Working Incomes -->
                        <div class="stream-option active" id="opt-working" onclick="selectStream('working')" style="cursor: {{ $isWithdrawalDisabled ? 'not-allowed' : 'pointer' }}; padding: 14px; border-radius: 10px; border: 1.5px solid {{ $isWithdrawalDisabled ? 'rgba(239, 68, 68, 0.4)' : '#00FF88' }}; background: {{ $isWithdrawalDisabled ? 'rgba(239, 68, 68, 0.05)' : 'rgba(0, 255, 136, 0.08)' }}; transition: all 0.25s ease; position: relative;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <div style="font-size: 13px; font-weight: 800; color: #FFF; display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-users-gear" style="color: #00FF88;"></i> Working Incomes
                                </div>
                                @if($isWithdrawalDisabled)
                                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; background: rgba(239,68,68,0.2); color: #EF4444; border: 1px solid rgba(239,68,68,0.4); border-radius: 4px;">LOCKED</span>
                                @else
                                    <span class="stream-badge-working" style="font-size: 10px; font-weight: 800; padding: 2px 6px; background: rgba(0,255,136,0.2); color: #00FF88; border-radius: 4px;">ACTIVE</span>
                                @endif
                            </div>
                            <div style="font-size: 11px; color: #94A3B8;">Avail: <strong style="color: #00FF88;">${{ number_format($workingUsdt, 2) }}</strong></div>
                            @if($isWithdrawalDisabled)
                                <div style="font-size: 9.5px; color: #EF4444; margin-top: 4px; font-weight: 700;">
                                    <i class="fas fa-clock"></i> Opens 10 Sep, 10:00 PM
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Active OTP Pending Notice -->
                <div style="padding: 14px; background: rgba(255, 215, 0, 0.08); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: #FFD700; margin-bottom: 4px;">
                        <i class="fas fa-shield-halved"></i> OTP VERIFICATION PENDING
                    </div>
                    <div style="font-size: 11px; color: #E2E8F0;">
                        Withdrawal Type: <strong>{{ $remaining->comments == 'working' ? 'Working Income' : 'Staking ROI' }}</strong> | Amount: <strong>${{ number_format($remaining->amountusdt, 2) }}</strong>
                    </div>
                </div>
            @endif

            <!-- Amount Input -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="amountusdt">
                    <span>Withdrawal Amount ($)</span>
                    <span class="label-sub" id="maxLabel">Max: ${{ number_format($workingUsdt, 2) }}</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-dollar-sign mecha-input-icon"></i>
                    <input type="number" class="mecha-input-control @error('amountusdt') is-invalid @enderror" id="amountusdt" name="amountusdt" min="10" placeholder="e.g. 50 (Multiple of $10)" max="{{ $workingUsdt }}" step="1" @if(!is_null($remaining)) value="{{ $remaining->amountusdt }}" disabled @elseif($isWithdrawalDisabled) disabled @endif required>
                </div>
                <!-- Quick Amount Presets -->
                @if(is_null($remaining) && !$isWithdrawalDisabled)
                    <div style="display: flex; gap: 8px; margin-top: 8px; flex-wrap: wrap;">
                        <button type="button" class="preset-btn" onclick="setPreset(10)">$10</button>
                        <button type="button" class="preset-btn" onclick="setPreset(20)">$20</button>
                        <button type="button" class="preset-btn" onclick="setPreset(30)">$30</button>
                        <button type="button" class="preset-btn" onclick="setPreset(50)">$50</button>
                        <button type="button" class="preset-btn max-btn" onclick="setMaxPreset()">MAX</button>
                    </div>
                @endif
            </div>

            <!-- Currency Selection -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="selltype">
                    <span>Settlement Network</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-network-wired mecha-input-icon"></i>
                    <select class="mecha-select-control @error('currency') is-invalid @enderror" id="selltype" name="currency" @if(!is_null($remaining) || $isWithdrawalDisabled) disabled @endif>
                        @if($detail->usdt_withdrawal_status == 1 && !is_null($user->assetDetail()->usdttrc20addr))
                            <option value="usdt" {{ !is_null($remaining) && $remaining->currency == 'usdt' ? 'selected' : '' }}>USDT TRC20</option>
                        @endif
                        @if($detail->usdtbep20_withdrawal_status == 1 && !is_null($user->assetDetail()->usdtbep20addr))
                            <option value="usdtbep20" {{ !is_null($remaining) && $remaining->currency == 'usdtbep20' ? 'selected' : '' }}>USDT BEP20</option>
                        @endif
                    </select>
                </div>
            </div>

            <!-- Destination Wallet Address (Display) -->
            <div class="mecha-form-group">
                <label class="mecha-form-label">Destination Payout Address</label>
                <div class="mecha-input-wrap" style="background: rgba(0,0,0,0.6); padding: 12px 14px; border: 1px dashed rgba(229, 168, 35, 0.4); border-radius: 8px;">
                    <span id="withdrawaladdress" style="font-family: monospace; font-size: 11px; color: #FFE082; word-break: break-all;">{{ $user->assetDetail()->usdttrc20addr ?? $user->assetDetail()->usdtbep20addr }}</span>
                </div>
            </div>

            @if(!is_null($remaining))
                <!-- OTP Confirmation -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="otp">Security One-Time Password (OTP)</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-key mecha-input-icon" style="color: #FFD700;"></i>
                        <input type="number" class="mecha-input-control @error('otp') is-invalid @enderror" id="otp" name="otp" placeholder="Enter 6-digit OTP from email" required autofocus>
                    </div>
                </div>
            @endif

            <div style="display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;">
                @if($isWithdrawalDisabled && is_null($remaining))
                    <button type="button" class="mecha-btn-gold" style="flex: 1; min-width: 180px; opacity: 0.6; cursor: not-allowed; background: #374151; border-color: #4B5563; color: #9CA3AF;" disabled>
                        <i class="fas fa-lock"></i> WITHDRAWAL LOCKED TILL 10 PM, 10 SEP 2026
                    </button>
                @else
                    <button type="submit" class="mecha-btn-gold" style="flex: 1; min-width: 180px;">
                        <i class="fas fa-lock"></i> {{ !is_null($remaining) ? 'CONFIRM OTP & PROCESS' : 'SUBMIT WITHDRAWAL' }}
                    </button>
                @endif
                @if(!is_null($remaining))
                    <a href="{{ url('/User/resendWithdrawOtp') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                        <i class="fas fa-rotate"></i> RESEND OTP
                    </a>
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

<style>
    .preset-btn {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #94A3B8;
        padding: 5px 12px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .preset-btn:hover {
        background: rgba(255, 215, 0, 0.15);
        border-color: #FFD700;
        color: #FFD700;
    }
    .preset-btn.max-btn {
        background: rgba(0, 255, 136, 0.1);
        border-color: rgba(0, 255, 136, 0.3);
        color: #00FF88;
    }
    .preset-btn.max-btn:hover {
        background: rgba(0, 255, 136, 0.25);
        border-color: #00FF88;
        color: #00FF88;
    }
</style>
@endsection

@push('scripts')
<script>
    const isWithdrawalDisabled = {{ $isWithdrawalDisabled ? 'true' : 'false' }};
    const balances = {
        roi: {{ (float)$roiUsdt }},
        working: {{ (float)$workingUsdt }}
    };

    function selectStream(type) {
        if (isWithdrawalDisabled) {
            alert('All withdrawals are temporarily locked until 10:00 PM, 10 Sep 2026.');
            return;
        }

        $('#withdraw_type').val(type);
        const maxVal = balances[type] || 0;

        if (type === 'roi') {
            $('#opt-roi').css({
                'border-color': '#FFD700',
                'background': 'rgba(255, 215, 0, 0.08)'
            });
            $('.stream-badge-roi').show();

            $('#opt-working').css({
                'border-color': 'rgba(255, 255, 255, 0.1)',
                'background': 'rgba(255, 255, 255, 0.02)'
            });
            $('.stream-badge-working').hide();

            $('#maxLabel').html('Max: $' + maxVal.toFixed(2));
            $('#amountusdt').attr('max', maxVal);
        } else {
            $('#opt-working').css({
                'border-color': '#00FF88',
                'background': 'rgba(0, 255, 136, 0.08)'
            });
            $('.stream-badge-working').show();

            $('#opt-roi').css({
                'border-color': 'rgba(255, 255, 255, 0.1)',
                'background': 'rgba(255, 255, 255, 0.02)'
            });
            $('.stream-badge-roi').hide();

            $('#maxLabel').html('Max: $' + maxVal.toFixed(2));
            $('#amountusdt').attr('max', maxVal);
        }
    }

    function setPreset(amount) {
        if (isWithdrawalDisabled) return;
        const currentType = $('#withdraw_type').val();
        const maxVal = balances[currentType] || 0;
        if (amount <= maxVal) {
            $('#amountusdt').val(amount);
        } else {
            $('#amountusdt').val(Math.floor(maxVal / 10) * 10);
        }
    }

    function setMaxPreset() {
        if (isWithdrawalDisabled) return;
        const currentType = $('#withdraw_type').val();
        const maxVal = balances[currentType] || 0;
        // Nearest multiple of 10 under maxVal
        const multiple = Math.floor(maxVal / 10) * 10;
        $('#amountusdt').val(multiple > 0 ? multiple : maxVal);
    }

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