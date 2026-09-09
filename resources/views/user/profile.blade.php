@extends('layouts.user-mecha')

@section('title', 'My Profile & Web3 Identity - Cyera AI')
@section('page-title', 'My Profile')
@section('page-icon', 'fas fa-id-card-clip')

@section('content')
@php
    $userUuid = $profile->uuid ?? Session::get('user.userid', 'CAI000000');
    $userName = $profile->usersname ?? Session::get('user.name', 'Cyera Member');
    $userEmail = $profile->email ?? Session::get('user.email', 'member@cyera.ai');
    $userContact = ($profile->ccode ?? '+91') . ' ' . ($profile->contact ?? 'N/A');
    $userDoj = !empty($profile->doj) ? date('d M Y', strtotime($profile->doj)) : date('d M Y');
    $sponsorId = $profile->guiderid ?? Session::get('user.sponsorid', 'Root Sponsor');
    $sponsorName = $profile->guidername ?? 'Cyera Network';
    $walletAddress = $profile->usdtbep20address ?? ($profile->bep20address ?? Session::get('user.walletaddress', ''));
    $isActive = ($profile->userstatus ?? 0) == 1 || ($profile->total_self_investment ?? 0) > 0;
    $rankName = !empty($profile->rank_name) && $profile->rank_name !== 'None' ? $profile->rank_name : 'NO RANK';
    $referralLink = url('/register/' . $userUuid);
@endphp

<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 14px;">

    <!-- ============================================================
         1. COMPACT HERO IDENTITY HEADER
         ============================================================ -->
    <div class="mecha-hud-card" style="padding: 16px 18px; position: relative; overflow: hidden;">
        <!-- Top Edge Gold Hairline -->
        <div style="position: absolute; top: 0; left: 10%; right: 10%; height: 2px; background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.9), rgba(245, 166, 35, 0.8), transparent);"></div>

        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <!-- Left: Avatar + Identity Info -->
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 52px; height: 52px; border-radius: 50%; padding: 2px; background: linear-gradient(135deg, #FFD700, #F5A623, #B8860B); position: relative; box-shadow: 0 0 16px rgba(245, 166, 35, 0.35); flex-shrink: 0;">
                    <div style="width: 100%; height: 100%; border-radius: 50%; background: #07080d; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img src="{{ asset('images/cai-lion-coin.png') }}" alt="CYERA AI" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <span style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; border-radius: 50%; background: {{ $isActive ? '#00FF88' : '#64748B' }}; border: 2px solid #07080d; box-shadow: 0 0 6px {{ $isActive ? '#00FF88' : '#64748B' }};"></span>
                </div>

                <div>
                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 800; color: #FFFFFF; margin: 0; line-height: 1.2;">
                            {{ $userName }}
                        </h2>
                        <span style="font-size: 0.65rem; padding: 2px 7px; border-radius: 5px; background: rgba(245, 166, 35, 0.15); border: 1px solid rgba(245, 166, 35, 0.35); color: #FFD700; font-weight: 700;">
                            <i class="fas fa-crown"></i> {{ $rankName }}
                        </span>
                        @if($isActive)
                            <span style="font-size: 0.65rem; padding: 2px 7px; border-radius: 5px; background: rgba(0, 255, 136, 0.12); border: 1px solid rgba(0, 255, 136, 0.35); color: #00FF88; font-weight: 700;">
                                ACTIVE
                            </span>
                        @else
                            <span style="font-size: 0.65rem; padding: 2px 7px; border-radius: 5px; background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.35); color: #FB7185; font-weight: 700;">
                                INACTIVE
                            </span>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; color: #94A3B8; font-size: 0.78rem; margin-top: 3px; flex-wrap: wrap;">
                        <span><strong style="color: #FFD700;">ID:</strong> {{ $userUuid }}</span>
                        <span>•</span>
                        <span><i class="far fa-calendar-alt" style="color: #FFD700;"></i> Joined {{ $userDoj }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Action Pills -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <a href="{{ url('/User/Stake') }}" class="mecha-btn-gold" style="height: 34px; padding: 0 14px; font-size: 0.78rem; text-decoration: none; border-radius: 8px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fas fa-bolt"></i> STAKE
                </a>
                <a href="{{ url('/User/ChangePassword') }}" class="mecha-btn-outline" style="height: 34px; padding: 0 12px; font-size: 0.78rem; text-decoration: none; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fas fa-key"></i> SECURITY
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================
         2. ULTRA-COMPACT 1-LINE REFERRAL BAR
         ============================================================ -->
    <div style="background: rgba(8, 9, 15, 0.9); backdrop-filter: blur(16px); border: 1px solid rgba(245, 166, 35, 0.35); border-radius: 12px; padding: 5px 6px 5px 12px; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.06);">
        <i class="fas fa-link" style="color: #FFD700; font-size: 0.85rem; flex-shrink: 0;"></i>
        <input type="text" id="profRefInput" value="{{ $referralLink }}" readonly style="flex: 1; min-width: 0; background: transparent; border: none; outline: none; color: #FFD700; font-family: 'Space Mono', monospace; font-size: 0.80rem; font-weight: 600; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; padding: 0;">
        <button type="button" onclick="copyProfileRefLink()" class="btn-solid-gold-claim" style="height: 32px; padding: 0 12px; font-size: 0.75rem; border-radius: 8px; font-weight: 800; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; flex-shrink: 0; cursor: pointer; border: none;">
            <i class="fas fa-copy"></i> <span id="profCopyBtnText">COPY</span>
        </button>
    </div>

    <!-- ============================================================
         3. COMPACT PROFILE SPECIFICATION CARDS
         ============================================================ -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">

        <!-- Account Info -->
        <div class="mecha-hud-card" style="padding: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                <div style="font-family: 'Outfit', sans-serif; font-size: 0.92rem; font-weight: 800; color: #FFFFFF; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-shield" style="color: #FFD700;"></i> ACCOUNT INFO
                </div>
                <span style="font-size: 0.65rem; color: #94A3B8; font-weight: 700; letter-spacing: 0.5px;">IDENTITY</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                <!-- Full Name -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 7px 10px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px;">
                    <span style="font-size: 0.75rem; color: #94A3B8;"><i class="fas fa-user" style="color: #FFD700; width: 14px;"></i> Name</span>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF;">{{ $userName }}</span>
                </div>

                <!-- Email -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 7px 10px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px;">
                    <span style="font-size: 0.75rem; color: #94A3B8;"><i class="fas fa-envelope" style="color: #FFD700; width: 14px;"></i> Email</span>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $userEmail }}</span>
                </div>

                <!-- Mobile -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 7px 10px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px;">
                    <span style="font-size: 0.75rem; color: #94A3B8;"><i class="fas fa-phone" style="color: #FFD700; width: 14px;"></i> Mobile</span>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #FFFFFF;">{{ $userContact }}</span>
                </div>

                <!-- Sponsor -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 7px 10px; background: rgba(245, 166, 35, 0.05); border: 1px solid rgba(245, 166, 35, 0.2); border-radius: 8px;">
                    <span style="font-size: 0.75rem; color: #FFD700;"><i class="fas fa-user-tag" style="color: #FFD700; width: 14px;"></i> Sponsor</span>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #FFD700;">{{ $sponsorName }} ({{ $sponsorId }})</span>
                </div>
            </div>
        </div>

        <!-- Web3 & Staking Vault -->
        <div class="mecha-hud-card" style="padding: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
                <div style="font-family: 'Outfit', sans-serif; font-size: 0.92rem; font-weight: 800; color: #FFFFFF; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-wallet" style="color: #00FF88;"></i> WEB3 SETTLEMENT
                </div>
                <span style="font-size: 0.65rem; color: #00FF88; font-weight: 700; background: rgba(0, 255, 136, 0.1); padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(0, 255, 136, 0.3);">BEP-20</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                <!-- Wallet Address Row -->
                <div style="padding: 9px 10px; background: rgba(0, 255, 136, 0.04); border: 1px solid rgba(0, 255, 136, 0.25); border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <span style="font-size: 0.70rem; color: #00FF88; font-weight: 700;"><i class="fas fa-shield-check"></i> Connected Wallet</span>
                        @if(!empty($walletAddress))
                        <a href="https://bscscan.com/address/{{ $walletAddress }}" target="_blank" style="font-size: 0.68rem; color: #94A3B8; text-decoration: none;">
                            BscScan <i class="fas fa-arrow-up-right-from-square"></i>
                        </a>
                        @endif
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.78rem; color: #FFFFFF; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ !empty($walletAddress) ? (substr($walletAddress, 0, 10) . '...' . substr($walletAddress, -8)) : 'Not Bound' }}
                        </span>
                        @if(!empty($walletAddress))
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $walletAddress }}'); showProfileToast('Wallet Copied!');" style="background: transparent; border: none; color: #00FF88; cursor: pointer; padding: 2px 6px; font-size: 0.78rem;" title="Copy Wallet Address">
                            <i class="fas fa-copy"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Self Staked Total -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 7px 10px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px;">
                    <span style="font-size: 0.75rem; color: #94A3B8;"><i class="fas fa-cubes" style="color: #FFD700; width: 14px;"></i> Total Staked</span>
                    <span style="font-size: 0.88rem; font-weight: 800; color: #FFD700; font-family: 'Space Mono', monospace;">${{ number_format($profile->total_self_investment ?? 0, 2) }} <span style="font-size: 0.70rem; color: #94A3B8;">USDT</span></span>
                </div>

                <!-- Auth Protocol -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 7px 10px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 8px;">
                    <span style="font-size: 0.75rem; color: #94A3B8;"><i class="fas fa-lock" style="color: #FFD700; width: 14px;"></i> Auth Type</span>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #00FF88;"><i class="fas fa-shield-halved"></i> EIP-191 Secp256k1</span>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Copy Toast Notification -->
<div id="profToast" style="display: none; position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: rgba(6, 10, 20, 0.95); border: 1px solid #00FF88; color: #00FF88; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 0.90rem; z-index: 99999; box-shadow: 0 10px 30px rgba(0,0,0,0.8); backdrop-filter: blur(12px);">
    <i class="fas fa-circle-check" style="margin-right: 8px;"></i>
    <span id="profToastMsg">Referral link copied to clipboard!</span>
</div>

<script>
function showProfileToast(msg) {
    const toast = document.getElementById('profToast');
    const msgEl = document.getElementById('profToastMsg');
    if (toast && msgEl) {
        msgEl.innerText = msg;
        toast.style.display = 'block';
        setTimeout(function() {
            toast.style.display = 'none';
        }, 2200);
    }
}

function copyProfileRefLink() {
    const input = document.getElementById('profRefInput');
    const btnText = document.getElementById('profCopyBtnText');
    if (input) {
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);
        if (btnText) btnText.innerText = 'COPIED!';
        showProfileToast('Referral link copied to clipboard!');
        setTimeout(function() {
            if (btnText) btnText.innerText = 'COPY LINK';
        }, 2000);
    }
}
</script>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection