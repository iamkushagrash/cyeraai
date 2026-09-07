@extends('layouts.user-mecha')

@section('title', 'New Registration - Cyera AI')
@section('page-title', 'New Team Registration')
@section('page-icon', 'fas fa-user-plus')

@section('content')
<div class="mecha-hud-card" style="max-width: 680px; margin: 0 auto;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-id-card-clip"></i>
            <div>
                <h2 class="mecha-card-title">REGISTER DOWNLINE MEMBER</h2>
                <div class="mecha-card-subtitle">Onboard new direct partner to your Cyera network</div>
            </div>
        </div>
        <span class="mecha-card-badge">DIRECT NETWORK</span>
    </div>

    @if(!session('success'))
        <form action="{{ url('/User/NewRegistration') }}" method="POST">
            @csrf

            <!-- Sponsor ID & Sponsor Name -->
            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="referrer">
                        <span>Sponsor ID</span>
                        <span class="label-sub">* Required</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-user-friends mecha-input-icon"></i>
                        <input type="text" name="referrer" id="referrer" class="mecha-input-control @error('referrer') is-invalid @enderror" value="{{ Session::get('user.uuid') ?? old('referrer') }}" placeholder="Sponsor User ID" required>
                    </div>
                    @error('referrer') <small style="color:#FF4757;font-size:10px;">{{ $message }}</small> @enderror
                </div>

                <div class="mecha-form-group" id="spdiv">
                    <label class="mecha-form-label" for="spname">Sponsor Name</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-signature mecha-input-icon"></i>
                        <input type="text" id="spname" class="mecha-input-control" value="{{ Session::get('user.name') ?? '' }}" readonly placeholder="Verifying sponsor...">
                    </div>
                </div>
            </div>

            <!-- Full Name & Email -->
            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="name">
                        <span>Full Name</span>
                        <span class="label-sub">*</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-user mecha-input-icon"></i>
                        <input type="text" name="name" id="name" class="mecha-input-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Member Full Name" required>
                    </div>
                    @error('name') <small style="color:#FF4757;font-size:10px;">{{ $message }}</small> @enderror
                </div>

                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="email">
                        <span>Email Address</span>
                        <span class="label-sub">*</span>
                    </label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-envelope mecha-input-icon"></i>
                        <input type="email" name="email" id="email" class="mecha-input-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="member@email.com" required>
                    </div>
                    @error('email') <small style="color:#FF4757;font-size:10px;">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Mobile Phone with Country Code -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="contact">
                    <span>Mobile Phone Number</span>
                    <span class="label-sub">*</span>
                </label>
                <div style="display: flex; gap: 8px;">
                    <div style="width: 140px; flex-shrink: 0;">
                        <select name="countrycode" class="mecha-select-control" style="padding-left: 10px;">
                            <option value="91" selected>India (+91)</option>
                            <option value="1">USA (+1)</option>
                            <option value="44">UK (+44)</option>
                            <option value="971">UAE (+971)</option>
                            <option value="65">Singapore (+65)</option>
                            <option value="60">Malaysia (+60)</option>
                            <option value="61">Australia (+61)</option>
                            <option value="49">Germany (+49)</option>
                        </select>
                    </div>
                    <div class="mecha-input-wrap" style="flex: 1;">
                        <i class="fas fa-phone mecha-input-icon"></i>
                        <input type="text" name="contact" id="contact" maxlength="15" class="mecha-input-control @error('contact') is-invalid @enderror" value="{{ old('contact') }}" placeholder="Mobile Number" required>
                    </div>
                </div>
                @error('contact') <small style="color:#FF4757;font-size:10px;">{{ $message }}</small> @enderror
            </div>

            <!-- Passwords -->
            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="password">Password</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-lock mecha-input-icon"></i>
                        <input type="password" name="password" id="password" class="mecha-input-control @error('password') is-invalid @enderror" placeholder="Set Password" required autocomplete="new-password">
                    </div>
                    @error('password') <small style="color:#FF4757;font-size:10px;">{{ $message }}</small> @enderror
                </div>

                <div class="mecha-form-group">
                    <label class="mecha-form-label" for="password_confirmation">Confirm Password</label>
                    <div class="mecha-input-wrap">
                        <i class="fas fa-lock-open mecha-input-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mecha-input-control" placeholder="Repeat Password" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="mecha-btn-gold">
                    <i class="fas fa-user-plus"></i> COMPLETE REGISTRATION
                </button>
            </div>
        </form>
    @else
        <!-- Registration Success Card -->
        <div style="text-align: center; padding: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(0, 255, 136, 0.15); border: 2px solid #00FF88; color: #00FF88; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px; box-shadow: 0 0 20px rgba(0, 255, 136, 0.4);">
                <i class="fas fa-circle-check"></i>
            </div>
            <h3 style="color: #00FF88; font-size: 18px; font-weight: 800; margin-bottom: 6px;">REGISTRATION SUCCESSFUL!</h3>
            <p style="color: #8C9BAE; font-size: 11px; margin-bottom: 20px;">New member has been activated on your direct network tree.</p>

            <div class="mecha-metric-box" style="text-align: left; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 11px;">
                    <span style="color: #8C9BAE;">New User ID:</span>
                    <strong style="color: #00E5FF; font-family: monospace;">{{ session('details.uniqueid') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 11px;">
                    <span style="color: #8C9BAE;">Email:</span>
                    <strong style="color: #FFF;">{{ session('details.email') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 6px 0; font-size: 11px;">
                    <span style="color: #8C9BAE;">Password:</span>
                    <strong style="color: #FFD700;">{{ session('details.password') }}</strong>
                </div>
            </div>

            <a href="{{ url('/User/NewRegistration') }}" class="mecha-btn-gold">
                <i class="fas fa-plus"></i> REGISTER ANOTHER MEMBER
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        if ($("#referrer").val() != "") {
            fetchSponsor($("#referrer").val());
        }
    });

    $("#referrer").on('blur input', function(){
        fetchSponsor($(this).val());
    });

    function fetchSponsor(refId) {
        if (!refId) return;
        $.ajax({
            type: 'GET',
            url: '/getSponsorNew/' + encodeURIComponent(refId),
            dataType: "json",
            success: function(data){
                if (data.status == 0) {
                    $("#spname").val(data.name).css('color', '#00FF88');
                } else {
                    $("#spname").val('Invalid Sponsor ID').css('color', '#FF4757');
                }
            }
        });
    }
</script>
@endpush