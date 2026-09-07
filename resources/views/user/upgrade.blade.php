@extends('layouts.user-mecha')

@section('title', 'Stake CAI - Cyera AI')
@section('page-title', 'Stake CAI / Upgrade')
@section('page-icon', 'fas fa-layer-group')

@section('content')
@php
    $balanceVal = !is_null($balance) ? round((float)\Illuminate\Support\Facades\Crypt::decrypt($balance->amount), 2) : 0.00;
@endphp

<!-- Mini Stats Grid -->
<div class="mecha-stat-grid-2">
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>AVAILABLE BALANCE</span>
            <i class="fas fa-wallet" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold">${{ number_format($balanceVal, 2) }}</div>
        <div class="mecha-metric-sub">Wallet Available for Staking</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">
            <span>CAI LIVE PRICE</span>
            <i class="fas fa-chart-line" style="color: #00FF88;"></i>
        </div>
        <div class="mecha-metric-val green">${{ number_format((float)($price->price ?? 1.25), 2) }}</div>
        <div class="mecha-metric-sub">Real-Time Oracle Valuation</div>
    </div>
</div>

<div class="mecha-hud-card" style="max-width: 680px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-microchip"></i>
            <div>
                <h2 class="mecha-card-title">{{ is_null($user) ? 'IDENTIFY USER FOR STAKING' : 'EXECUTE CAI STAKE' }}</h2>
                <div class="mecha-card-subtitle">Secure staking & decentralized yield accumulation</div>
            </div>
        </div>
        <span class="mecha-card-badge">DECENTRALIZED</span>
    </div>

    @if(is_null($user))
        <!-- Step 1: Search User ID -->
        <form action="{{ url('/User/getUser') }}" method="POST">
            @csrf
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="userid">
                    <span>Target User ID</span>
                    <span class="label-sub">Self or Team Member ID</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-user-tag mecha-input-icon"></i>
                    <input type="text" id="userid" class="mecha-input-control @error('userid') is-invalid @enderror" name="userid" placeholder="Enter User ID (e.g. CAI12345)" required>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" class="mecha-btn-gold">
                    <i class="fas fa-magnifying-glass"></i> VERIFY & PROCEED
                </button>
                <a href="{{ url('/User/Dashboard') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                    CANCEL
                </a>
            </div>
        </form>
    @else
        <!-- Step 2: Stake CAI Form -->
        <form action="{{ url('/User/Stake') }}" method="POST">
            @csrf
            <input type="hidden" name="honeypotu" value="{{ $user->id }}">

            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <!-- User ID (Readonly) -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label">User ID</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-id-badge mecha-input-icon"></i>
                        <input type="text" class="mecha-input-control" value="{{ $user->uuid }}" readonly>
                    </div>
                </div>

                <!-- User Name (Readonly) -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label">Account Name</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-user mecha-input-icon"></i>
                        <input type="text" class="mecha-input-control" value="{{ $user->name }}" readonly>
                    </div>
                </div>
            </div>

            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <!-- Amount ($) -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="amount">
                        <span>Amount ($)</span>
                        <span class="label-sub">Min: $25</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-dollar-sign mecha-input-icon"></i>
                        <input type="number" step="0.01" name="amount" id="amount" class="mecha-input-control @error('amount') is-invalid @enderror" placeholder="e.g. 500.00" required>
                    </div>
                </div>

                <!-- CAI Tokens -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="cai">
                        <span>Equivalent CAI</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-circle-nodes mecha-input-icon"></i>
                        <input type="number" step="0.0001" name="cai" id="cai" class="mecha-input-control" placeholder="0.00 CAI" readonly>
                    </div>
                </div>
            </div>

            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <!-- Wallet Source -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="staketype">Debit Wallet Source</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-wallet mecha-input-icon"></i>
                        <select class="mecha-select-control" id="staketype" name="staketype">
                            <option value="Cyera AIWallet">Cyera AI Main Wallet</option>
                            <option value="ProductWallet">Product / Activation Wallet</option>
                        </select>
                    </div>
                </div>

                <!-- Transaction Password -->
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="password">Transaction Password</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-lock mecha-input-icon"></i>
                        <input type="password" name="password" id="password" class="mecha-input-control @error('password') is-invalid @enderror" placeholder="Enter Password" required>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <button type="submit" class="mecha-btn-gold">
                    <i class="fas fa-bolt"></i> CONFIRM & STAKE CAI
                </button>
                <a href="{{ url('/User/Stake') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                    CHANGE USER
                </a>
            </div>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const caiOraclePrice = {{ (float)($price->price ?? 1.25) }};
    
    $('#amount').on('input', function() {
        const val = parseFloat($(this).val()) || 0;
        if (val > 0 && caiOraclePrice > 0) {
            $('#cai').val((val / caiOraclePrice).toFixed(4));
        } else {
            $('#cai').val('');
        }
    });
</script>
@endpush