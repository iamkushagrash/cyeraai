@extends('layouts.user-mecha')

@section('title', 'Change Password - Cyera AI')
@section('page-title', 'Security Settings')
@section('page-icon', 'fas fa-shield-halved')

@section('content')
<div class="mecha-hud-card" style="max-width: 580px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-key"></i>
            <div>
                <h2 class="mecha-card-title">UPDATE ACCESS CREDENTIALS</h2>
                <div class="mecha-card-subtitle">Change account login and transaction authorization password</div>
            </div>
        </div>
        <span class="mecha-card-badge">ENCRYPTED</span>
    </div>

    <form action="{{ url('/User/ChangePassword') }}" method="POST">
        @csrf

        <!-- Old Password -->
        <div class="mecha-form-group">
            <label class="mecha-form-label" for="oldpassword">
                <span>Current Password</span>
            </label>
            <div class="mecha-input-wrap">
                <i class="fas fa-lock-open mecha-input-icon"></i>
                <input type="password" class="mecha-input-control @error('oldpassword') is-invalid @enderror" id="oldpassword" name="oldpassword" placeholder="Enter current password" required>
            </div>
        </div>

        <!-- New Password -->
        <div class="mecha-form-group">
            <label class="mecha-form-label" for="password">
                <span>New Password</span>
                <span class="label-sub">Min 6 characters</span>
            </label>
            <div class="mecha-input-wrap">
                <i class="fas fa-lock mecha-input-icon"></i>
                <input type="password" class="mecha-input-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter new password" required>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mecha-form-group">
            <label class="mecha-form-label" for="password_confirmation">
                <span>Confirm New Password</span>
            </label>
            <div class="mecha-input-wrap">
                <i class="fas fa-circle-check mecha-input-icon"></i>
                <input type="password" name="password_confirmation" class="mecha-input-control" id="password_confirmation" placeholder="Re-enter new password" required>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 22px;">
            <button type="submit" class="mecha-btn-gold">
                <i class="fas fa-key"></i> UPDATE PASSWORD
            </button>
            <a href="{{ url('/User/Dashboard') }}" class="mecha-btn-outline" style="width: auto; height: 44px;">
                CANCEL
            </a>
        </div>
    </form>
</div>
@endsection