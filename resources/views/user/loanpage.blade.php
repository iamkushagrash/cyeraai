@extends('layouts.user-mecha')

@section('title', 'Advance Loan - Cyera AI')
@section('page-title', 'Protocol Loan')
@section('page-icon', 'fas fa-hand-holding-dollar')

@section('content')
<div class="mecha-hud-card" style="max-width: 650px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-hand-holding-dollar"></i>
            <div>
                <h2 class="mecha-card-title">ADVANCE LOAN PROTOCOL</h2>
                <div class="mecha-card-subtitle">Decentralized temporary staking advance</div>
            </div>
        </div>
        <span class="mecha-card-badge">STATUS</span>
    </div>

    @if(sizeof($user->stackingDeposite()->get()) && is_null($loan))
        <div style="text-align: center; padding: 24px 12px;">
            <i class="fas fa-circle-info" style="font-size: 32px; color: #FFD700; margin-bottom: 12px;"></i>
            <div style="font-size: 13px; font-weight: 800; color: #FFF; margin-bottom: 8px;">FEATURE DISCONTINUED</div>
            <p style="font-size: 11px; color: #94A3B8; line-height: 1.6; margin-bottom: 16px;">
                The advance loan feature was available only during the genesis launch phase and has now been discontinued. Users who wish to upgrade their account must do so using their real wallet balance.
            </p>
            <a href="{{ url('/User/Stake') }}" class="mecha-btn-gold" style="display: inline-flex; width: auto; padding: 0 24px;">
                <i class="fas fa-layer-group"></i> GO TO STAKING
            </a>
        </div>
    @else
        @if(is_null($loan))
            <div style="text-align: center; padding: 24px 12px;">
                <i class="fas fa-circle-info" style="font-size: 32px; color: #FFD700; margin-bottom: 12px;"></i>
                <div style="font-size: 13px; font-weight: 800; color: #FFF; margin-bottom: 8px;">FEATURE DISCONTINUED</div>
                <p style="font-size: 11px; color: #94A3B8; line-height: 1.6; margin-bottom: 16px;">
                    The advance loan feature was available only during the genesis launch phase and has now been discontinued. Users who wish to upgrade their account must do so using their real wallet balance.
                </p>
                <a href="{{ url('/User/Stake') }}" class="mecha-btn-gold" style="display: inline-flex; width: auto; padding: 0 24px;">
                    <i class="fas fa-layer-group"></i> GO TO STAKING
                </a>
            </div>
        @else
            <div style="padding: 12px 0;">
                <div class="mecha-metric-box" style="margin-bottom: 14px;">
                    <div class="mecha-metric-lbl">ACTIVE LOAN PRINCIPAL</div>
                    <div class="mecha-metric-val gold">${{ number_format((float)$loan->amount, 2) }}</div>
                    <div class="mecha-metric-sub">Issued: {{ $loan->created_at }}</div>
                </div>

                <div class="mecha-metric-box">
                    <div class="mecha-metric-lbl">REMAINING REPAYMENT</div>
                    <div class="mecha-metric-val red">${{ number_format((float)$loan->remaining, 2) }}</div>
                    <div class="mecha-metric-sub">Pending settlement</div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <a href="{{ url('/User/RepayLoan') }}" class="mecha-btn-gold">
                        <i class="fas fa-money-bill-transfer"></i> REPAY LOAN NOW
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection