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

<div style="max-width: 960px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">

    <!-- ============================================================
         1. HERO PROFILE IDENTITY HEADER CARD
         ============================================================ -->
    <div class="mecha-hud-card" style="position: relative; overflow: hidden; padding: 28px 24px;">
        <!-- Top Edge Gold Shimmer -->
        <div style="position: absolute; top: 0; left: 10%; right: 10%; height: 2px; background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.9), rgba(245, 166, 35, 0.8), transparent);"></div>

        <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            <!-- Left: Avatar & User Identity Info -->
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 76px; height: 76px; border-radius: 50%; padding: 3px; background: linear-gradient(135deg, #FFD700, #F5A623, #B8860B); position: relative; box-shadow: 0 0 24px rgba(245, 166, 35, 0.45); flex-shrink: 0;">
                    <div style="width: 100%; height: 100%; border-radius: 50%; background: #07080d; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img src="{{ asset('images/cai-lion-coin.png') }}" alt="CYERA AI" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <span style="position: absolute; bottom: 2px; right: 2px; width: 14px; height: 14px; border-radius: 50%; background: {{ $isActive ? '#00FF88' : '#64748B' }}; border: 2px solid #07080d; box-shadow: 0 0 8px {{ $isActive ? '#00FF88' : '#64748B' }};"></span>
                </div>

                <div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.55rem; font-weight: 800; color: #FFFFFF; letter-spacing: -0.3px; margin: 0;">
                            {{ $userName }}
                        </h2>
                        <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 6px; background: rgba(245, 166, 35, 0.15); border: 1px solid rgba(245, 166, 35, 0.4); color: #FFD700; font-weight: 700; text-transform: uppercase;">
                            <i class="fas fa-crown"></i> {{ $rankName }}
                        </span>
                        @if($isActive)
                            <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 6px; background: rgba(0, 255, 136, 0.12); border: 1px solid rgba(0, 255, 136, 0.35); color: #00FF88; font-weight: 700;">
                                <i class="fas fa-circle-check"></i> ACTIVE
                            </span>
                        @else
                            <span style="font-size: 0.72rem; padding: 2px 8px; border-radius: 6px; background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.35); color: #FB7185; font-weight: 700;">
                                <i class="fas fa-circle-xmark"></i> INACTIVE
                            </span>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; color: #94A3B8; font-size: 0.84rem; flex-wrap: wrap;">
                        <span><strong style="color: #FFD700;">User ID:</strong> {{ $userUuid }}</span>
                        <span>•</span>
                        <span><i class="far fa-calendar-alt" style="color: #FFD700;"></i> Joined {{ $userDoj }}</span>
                    </div>
                </div>
            </div>

            <!-- Right: Fast Action Shortcuts -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ url('/User/Stake') }}" class="mecha-btn-gold" style="height: 42px; padding: 0 16px; font-size: 0.86rem; text-decoration: none;">
                    <i class="fas fa-bolt"></i> STAKE / UPGRADE
                </a>
                <a href="{{ url('/User/ChangePassword') }}" class="mecha-btn-outline" style="height: 42px; padding: 0 16px; font-size: 0.86rem; text-decoration: none;">
                    <i class="fas fa-key"></i> SECURITY
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================
         2. EXCLUSIVE 1-CLICK REFERRAL SHARE HUD BAR
         ============================================================ -->
    <div class="mecha-hud-card" style="background: linear-gradient(135deg, rgba(245, 166, 35, 0.08) 0%, rgba(6, 9, 18, 0.95) 100%); border-color: rgba(245, 166, 35, 0.35);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255, 215, 0, 0.15); color: #FFD700; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-share-nodes"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.05rem; font-weight: 700; color: #FFFFFF; margin: 0;">
                        YOUR OFFICIAL REFERRAL LINK
                    </h3>
                    <div style="font-size: 0.78rem; color: #94A3B8;">Share your unique referral link to earn 5% Direct Commission + 15-Level Income</div>
                </div>
            </div>
            <span style="font-size: 0.78rem; color: #00FF88; font-weight: 700; background: rgba(0, 255, 136, 0.1); padding: 3px 10px; border-radius: 12px; border: 1px solid rgba(0, 255, 136, 0.3);">
                SPONSOR CODE: {{ $userUuid }}
            </span>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 260px; position: relative; background: #07080d; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; overflow: hidden;">
                <input type="text" id="profRefInput" value="{{ $referralLink }}" readonly style="background: transparent; border: none; outline: none; color: #FFD700; font-family: monospace; font-size: 0.88rem; width: 100%; font-weight: 600;">
            </div>

            <button type="button" class="mecha-btn-gold" onclick="copyProfileRefLink()" style="height: 42px; padding: 0 18px; font-size: 0.88rem; white-space: nowrap;">
                <i class="fas fa-copy"></i> <span id="profCopyBtnText">COPY LINK</span>
            </button>

            <!-- Telegram Share -->
            <a href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text={{ urlencode('Join Cyera AI Decentralized Network under my team:') }}" target="_blank" class="mecha-btn-outline" style="height: 42px; padding: 0 14px; color: #00E5FF; border-color: rgba(0, 229, 255, 0.4); text-decoration: none;" title="Share on Telegram">
                <i class="fab fa-telegram" style="font-size: 1.1rem;"></i>
            </a>

            <!-- WhatsApp Share -->
            <a href="https://api.whatsapp.com/send?text={{ urlencode('Join Cyera AI with my referral link: ' . $referralLink) }}" target="_blank" class="mecha-btn-outline" style="height: 42px; padding: 0 14px; color: #00FF88; border-color: rgba(0, 255, 136, 0.4); text-decoration: none;" title="Share on WhatsApp">
                <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i>
            </a>
        </div>
    </div>

    <!-- ============================================================
         3. 2-COLUMN PROFILE SPECIFICATION HUD GRID
         ============================================================ -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

        <!-- Column 1: Account & Sponsor Credentials -->
        <div class="mecha-hud-card">
            <div class="mecha-card-header" style="margin-bottom: 18px;">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-user-shield" style="color: #FFD700;"></i>
                    <div>
                        <h3 class="mecha-card-title" style="font-size: 1.05rem;">ACCOUNT CREDENTIALS</h3>
                        <div class="mecha-card-subtitle">Verified membership records</div>
                    </div>
                </div>
                <span class="mecha-card-badge">IDENTITY</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <!-- Full Name -->
                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 12px; padding: 12px 14px;">
                    <div style="font-size: 0.74rem; color: #94A3B8; text-transform: uppercase; font-weight: 600; margin-bottom: 2px;">
                        <i class="fas fa-user" style="color: #FFD700; margin-right: 4px;"></i> Member Name
                    </div>
                    <div style="font-size: 0.96rem; font-weight: 700; color: #FFFFFF;">
                        {{ $userName }}
                    </div>
                </div>

                <!-- Email Address -->
                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 12px; padding: 12px 14px;">
                    <div style="font-size: 0.74rem; color: #94A3B8; text-transform: uppercase; font-weight: 600; margin-bottom: 2px;">
                        <i class="fas fa-envelope" style="color: #FFD700; margin-right: 4px;"></i> Email Address
                    </div>
                    <div style="font-size: 0.96rem; font-weight: 700; color: #FFFFFF;">
                        {{ $userEmail }}
                    </div>
                </div>

                <!-- Contact Number -->
                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 12px; padding: 12px 14px;">
                    <div style="font-size: 0.74rem; color: #94A3B8; text-transform: uppercase; font-weight: 600; margin-bottom: 2px;">
                        <i class="fas fa-phone-volume" style="color: #FFD700; margin-right: 4px;"></i> Mobile Contact
                    </div>
                    <div style="font-size: 0.96rem; font-weight: 700; color: #FFFFFF;">
                        {{ $userContact }}
                    </div>
                </div>

                <!-- Sponsor Info -->
                <div style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.2); border-radius: 12px; padding: 12px 14px;">
                    <div style="font-size: 0.74rem; color: #FFD700; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">
                        <i class="fas fa-user-tag" style="color: #FFD700; margin-right: 4px;"></i> Sponsor / Introducer
                    </div>
                    <div style="font-size: 0.96rem; font-weight: 700; color: #FFFFFF; display: flex; align-items: center; justify-content: space-between;">
                        <span>{{ $sponsorName }}</span>
                        <span style="font-family: monospace; font-size: 0.82rem; color: #FFD700; background: rgba(0,0,0,0.5); padding: 2px 8px; border-radius: 6px;">{{ $sponsorId }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 2: Web3 Blockchain & Settlement Vault -->
        <div class="mecha-hud-card">
            <div class="mecha-card-header" style="margin-bottom: 18px;">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-wallet" style="color: #00FF88;"></i>
                    <div>
                        <h3 class="mecha-card-title" style="font-size: 1.05rem;">WEB3 SETTLEMENT VAULT</h3>
                        <div class="mecha-card-subtitle">On-chain BEP-20 payout architecture</div>
                    </div>
                </div>
                <span class="mecha-card-badge" style="background: rgba(0, 255, 136, 0.12); color: #00FF88; border-color: rgba(0, 255, 136, 0.3);">
                    BSC MAINNET
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <!-- Linked BEP20 Wallet Address -->
                <div style="background: rgba(0, 255, 136, 0.04); border: 1px solid rgba(0, 255, 136, 0.25); border-radius: 12px; padding: 14px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <span style="font-size: 0.74rem; color: #00FF88; text-transform: uppercase; font-weight: 700;">
                            <i class="fas fa-shield-check"></i> Linked BEP-20 Payout Address
                        </span>
                        <span style="font-size: 0.70rem; color: #00FF88; background: rgba(0, 255, 136, 0.15); padding: 2px 6px; border-radius: 4px; font-weight: 700;">
                            VERIFIED
                        </span>
                    </div>

                    <div style="font-family: monospace; font-size: 0.88rem; color: #FFFFFF; word-break: break-all; font-weight: 600; line-height: 1.4; margin-bottom: 10px;">
                        {{ !empty($walletAddress) ? $walletAddress : 'Not Bound (Connect MetaMask)' }}
                    </div>

                    @if(!empty($walletAddress))
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $walletAddress }}'); showProfileToast('Wallet Address Copied!');" class="mecha-btn-outline" style="height: 32px; padding: 0 10px; font-size: 0.76rem; color: #00FF88; border-color: rgba(0, 255, 136, 0.3);">
                            <i class="fas fa-copy"></i> Copy Address
                        </button>
                        <a href="https://bscscan.com/address/{{ $walletAddress }}" target="_blank" class="mecha-btn-outline" style="height: 32px; padding: 0 10px; font-size: 0.76rem; color: #94A3B8; text-decoration: none;">
                            <i class="fas fa-arrow-up-right-from-square"></i> BscScan
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Protocol Security Status -->
                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 12px; padding: 12px 14px;">
                    <div style="font-size: 0.74rem; color: #94A3B8; text-transform: uppercase; font-weight: 600; margin-bottom: 2px;">
                        <i class="fas fa-lock" style="color: #FFD700; margin-right: 4px;"></i> Authentication Protocol
                    </div>
                    <div style="font-size: 0.90rem; font-weight: 600; color: #00FF88; display: flex; align-items: center; gap: 6px;">
                        <i class="fas fa-shield-halved"></i> EIP-191 Secp256k1 Cryptographic Proof
                    </div>
                </div>

                <!-- Self Investment Summary -->
                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 12px; padding: 12px 14px;">
                    <div style="font-size: 0.74rem; color: #94A3B8; text-transform: uppercase; font-weight: 600; margin-bottom: 2px;">
                        <i class="fas fa-cubes" style="color: #FFD700; margin-right: 4px;"></i> Total Self Staked
                    </div>
                    <div style="font-size: 1.15rem; font-weight: 800; color: #FFD700; font-family: 'Space Mono', monospace;">
                        ${{ number_format($profile->total_self_investment ?? 0, 2) }} <span style="font-size: 0.75rem; color: #94A3B8;">USDT</span>
                    </div>
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