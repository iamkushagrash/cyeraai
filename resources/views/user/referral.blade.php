@extends('layouts.user-mecha')

@section('title', 'Invite & Referral - Cyera AI')
@section('page-title', 'Invite & Referral Hub')
@section('page-icon', 'fas fa-share-nodes')

@section('content')
@php
    $refUuid = Session::get('user.uuid') ?? 'CYERA';
    $referralLink = url('/register/' . $refUuid);
@endphp

<div class="mecha-inner-body" style="max-width: 680px; margin: 0 auto;">

    <!-- 1. Top Referral Stats Grid -->
    <div class="mecha-stat-grid-2" style="margin-bottom: 14px;">
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>TOTAL DIRECTS</span>
                <i class="fas fa-user-group" style="color: #FFD700;"></i>
            </div>
            <div class="mecha-metric-val gold">{{ $totalDirects ?? 0 }}</div>
            <div class="mecha-metric-sub">Direct Partners</div>
        </div>
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>DIRECT BONUS</span>
                <i class="fas fa-percent" style="color: #00FF88;"></i>
            </div>
            <div class="mecha-metric-val green">5.0%</div>
            <div class="mecha-metric-sub">Instant Sponsor Reward</div>
        </div>
    </div>

    <!-- 2. Main Referral Card with QR Code & Link -->
    <div class="mecha-hud-card" style="margin-bottom: 14px;">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-qrcode"></i>
                <div>
                    <h2 class="mecha-card-title">YOUR EXCLUSIVE REFERRAL LINK</h2>
                    <div class="mecha-card-subtitle">Share your unique link or QR code to build your team</div>
                </div>
            </div>
            <span class="mecha-card-badge">SPONSOR ID: {{ $refUuid }}</span>
        </div>

        <!-- Center QR Code Stage -->
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 18px 0 14px;">
            <div style="position: relative; width: 170px; height: 170px; padding: 10px; background: #04070F; border: 2px solid #00D2FF; border-radius: 14px; box-shadow: 0 0 20px rgba(0, 210, 255, 0.25); display: flex; align-items: center; justify-content: center;">
                <!-- Decorative Frame Corners -->
                <div style="position: absolute; top: -2px; left: -2px; width: 14px; height: 14px; border-top: 3px solid #00FF88; border-left: 3px solid #00FF88; border-top-left-radius: 4px;"></div>
                <div style="position: absolute; top: -2px; right: -2px; width: 14px; height: 14px; border-top: 3px solid #00FF88; border-right: 3px solid #00FF88; border-top-right-radius: 4px;"></div>
                <div style="position: absolute; bottom: -2px; left: -2px; width: 14px; height: 14px; border-bottom: 3px solid #00FF88; border-left: 3px solid #00FF88; border-bottom-left-radius: 4px;"></div>
                <div style="position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; border-bottom: 3px solid #00FF88; border-right: 3px solid #00FF88; border-bottom-right-radius: 4px;"></div>

                <img id="refQrImg" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($referralLink) }}&color=00D2FF&bgcolor=04070F" alt="Referral QR Code" style="width: 146px; height: 146px; border-radius: 8px; display: block;">
            </div>

            <div style="margin-top: 12px; font-size: 10px; color: #94A3B8; font-weight: 700; letter-spacing: 0.5px;">
                SCAN TO REGISTER INSTANTLY
            </div>
        </div>

        <!-- Cyber Referral URL Copy Container -->
        <div class="mecha-form-group" style="margin-top: 10px; margin-bottom: 18px;">
            <label class="mecha-form-label">
                <span><i class="fas fa-link" style="color: #FFD700; margin-right: 5px;"></i> UNIQUE REGISTRATION URL</span>
                <span class="label-sub" style="color: #00FF88;"><i class="fas fa-circle-check" style="font-size: 8px;"></i> Ready to Share</span>
            </label>
            <div class="mecha-ref-copy-box">
                <div class="ref-icon-cell">
                    <i class="fas fa-link"></i>
                </div>
                <input type="text" id="refLinkInput" class="ref-url-input" value="{{ $referralLink }}" readonly spellcheck="false">
                <button type="button" id="btnCopyRef" onclick="copyReferralLink()" class="ref-copy-btn">
                    <i class="fas fa-copy"></i> <span id="copyBtnTxt">COPY</span>
                </button>
            </div>
        </div>

        <!-- Social Share Buttons -->
        <div style="margin-top: 18px;">
            <div style="font-size: 9.5px; font-weight: 800; color: #94A3B8; letter-spacing: 0.5px; margin-bottom: 8px; text-align: center;">
                QUICK SHARE VIA SOCIAL PLATFORMS
            </div>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                <a href="https://api.whatsapp.com/send?text={{ urlencode('Join Cyera AI with my referral link: ' . $referralLink) }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 4px; background: rgba(37, 211, 102, 0.1); border: 1px solid rgba(37, 211, 102, 0.35); border-radius: 8px; color: #25D366; font-size: 9px; font-weight: 800; text-align: center; transition: all 0.2s;">
                        <i class="fab fa-whatsapp" style="font-size: 16px;"></i>
                        <span>WhatsApp</span>
                    </div>
                </a>
                <a href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text={{ urlencode('Join Cyera AI Ecosystem!') }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 4px; background: rgba(0, 136, 204, 0.1); border: 1px solid rgba(0, 136, 204, 0.35); border-radius: 8px; color: #0088CC; font-size: 9px; font-weight: 800; text-align: center; transition: all 0.2s;">
                        <i class="fab fa-telegram" style="font-size: 16px;"></i>
                        <span>Telegram</span>
                    </div>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode('Join Cyera AI today and explore decentralized staking: ' . $referralLink) }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 4px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 8px; color: #FFF; font-size: 9px; font-weight: 800; text-align: center; transition: all 0.2s;">
                        <i class="fab fa-x-twitter" style="font-size: 16px;"></i>
                        <span>Twitter / X</span>
                    </div>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 4px; background: rgba(24, 119, 242, 0.1); border: 1px solid rgba(24, 119, 242, 0.35); border-radius: 8px; color: #1877F2; font-size: 9px; font-weight: 800; text-align: center; transition: all 0.2s;">
                        <i class="fab fa-facebook-f" style="font-size: 16px;"></i>
                        <span>Facebook</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Referral Program Benefits Card -->
    <div class="mecha-hud-card" style="margin-bottom: 16px;">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-gift"></i>
                <div>
                    <h2 class="mecha-card-title">HOW REFERRAL REWARDS WORK</h2>
                    <div class="mecha-card-subtitle">Maximize your income by inviting new members</div>
                </div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 4px;">
            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 8px 10px; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <span style="width: 22px; height: 22px; border-radius: 50%; background: #FFD700; color: #000; font-weight: 900; font-size: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">1</span>
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #FFF;">Share Your Invitation Link</div>
                    <div style="font-size: 9.5px; color: #94A3B8;">Send your exclusive referral URL to friends, community, or partners.</div>
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 8px 10px; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <span style="width: 22px; height: 22px; border-radius: 50%; background: #00FF88; color: #000; font-weight: 900; font-size: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">2</span>
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #FFF;">Member Activates Staking</div>
                    <div style="font-size: 9.5px; color: #94A3B8;">When your referral deposits and activates a staking tier, rewards trigger automatically.</div>
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 10px; padding: 8px 10px; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <span style="width: 22px; height: 22px; border-radius: 50%; background: #00D2FF; color: #000; font-weight: 900; font-size: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">3</span>
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #FFF;">Receive 5% Direct + 15-Level Unilevel Bonus</div>
                    <div style="font-size: 9.5px; color: #94A3B8;">Earn instant 5% direct commission and unlock up to 15 levels of recurring daily rewards.</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function copyReferralLink() {
        const input = document.getElementById('refLinkInput');
        const btnTxt = document.getElementById('copyBtnTxt');
        if (!input) return;

        input.select();
        input.setSelectionRange(0, 99999);

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(() => {
                showCopiedFeedback();
            }).catch(() => {
                document.execCommand('copy');
                showCopiedFeedback();
            });
        } else {
            document.execCommand('copy');
            showCopiedFeedback();
        }
    }

    function showCopiedFeedback() {
        const btnTxt = document.getElementById('copyBtnTxt');
        if (btnTxt) {
            btnTxt.textContent = 'COPIED!';
            setTimeout(() => {
                btnTxt.textContent = 'COPY';
            }, 2000);
        }
    }
</script>
@endpush
