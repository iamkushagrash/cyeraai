@extends('layouts.user-mecha')

@section('title', 'User Profile - Cyera AI')
@section('page-title', 'My Profile')
@section('page-icon', 'fas fa-user-gear')

@section('content')
<div class="mecha-hud-card" style="max-width: 650px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-id-card"></i>
            <div>
                <h2 class="mecha-card-title">ACCOUNT CREDENTIALS</h2>
                <div class="mecha-card-subtitle">Verified on-chain identity & contact records</div>
            </div>
        </div>
        <span class="mecha-card-badge">IDENTITY</span>
    </div>

    <form action="{{ url('/User/EditProfile') }}" method="POST">
        @csrf

        <!-- Member Name -->
        <div class="mecha-form-group">
            <label class="mecha-form-label">Member Name</label>
            <div class="mecha-input-wrap">
                <i class="fas fa-user mecha-input-icon"></i>
                <input type="text" class="mecha-input-control" value="{{ $profile->usersname }}" readonly>
            </div>
        </div>

        <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <!-- Mobile No -->
            <div class="mecha-form-group">
                <label class="mecha-form-label">Mobile Contact</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-phone mecha-input-icon"></i>
                    <input type="text" class="mecha-input-control" value="{{ $profile->contact }}" readonly>
                </div>
            </div>

            <!-- Email Address -->
            <div class="mecha-form-group">
                <label class="mecha-form-label">Email Address</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-envelope mecha-input-icon"></i>
                    <input type="text" class="mecha-input-control" value="{{ $profile->email }}" readonly>
                </div>
            </div>
        </div>

        <!-- USDT BEP20 Address -->
        @if(is_null($profile->usdtbep20address))
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="usdtbep20address">
                    <span>USDT BEP20 Address</span>
                    <span class="label-sub">Permanent payout wallet</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-wallet mecha-input-icon"></i>
                    <input type="text" name="usdtbep20address" class="mecha-input-control @error('usdtbep20address') is-invalid @enderror" id="usdtbep20address" placeholder="Enter 0x... BEP20 address" @if(!is_null($changeasset)) value="{{ $changeasset->usdtbep20addr }}" disabled @else value="{{ $profile->usdtbep20address }}" @endif required>
                </div>
            </div>
        @else
            <div class="mecha-form-group">
                <label class="mecha-form-label">USDT BEP20 Payout Address</label>
                <div class="mecha-input-wrap" style="background: rgba(0,0,0,0.6); padding: 12px 14px; border: 1px solid rgba(0, 255, 136, 0.4); border-radius: 8px;">
                    <i class="fas fa-shield-check" style="color: #00FF88; margin-right: 8px;"></i>
                    <span style="font-family: monospace; font-size: 11px; color: #00FF88; word-break: break-all;">{{ $profile->usdtbep20address }}</span>
                </div>
            </div>
        @endif

        @if(!is_null($changeasset))
            <!-- OTP Input -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="otp">Security OTP</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-key mecha-input-icon"></i>
                    <input type="number" name="otp" class="mecha-input-control @error('otp') is-invalid @enderror" id="otp" placeholder="Enter verification OTP" required>
                </div>
            </div>
        @endif

        <div style="display: flex; gap: 12px; margin-top: 22px;">
            @if(is_null($profile->usdtbep20address))
                <button type="submit" class="mecha-btn-gold">
                    <i class="fas fa-floppy-disk"></i> SAVE PROFILE
                </button>
                @if(!is_null($changeasset))
                    <a href="{{ url('/User/resendProfileOtp') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                        RESEND OTP
                    </a>
                @endif
            @endif
            <a href="{{ url('/User/Dashboard') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                DASHBOARD
            </a>
        </div>
    </form>
</div>
@endsection