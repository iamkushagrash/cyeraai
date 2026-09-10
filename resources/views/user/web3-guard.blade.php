@php
    $guardAuthUser = Auth::user();
    $guardSessionDetailId = Session::get('user.id');
    $guardSessionWallet = '';
    
    if ($guardSessionDetailId) {
        $gAsset = \App\AssetDetail::where('userid', $guardSessionDetailId)->first();
        if ($gAsset && !empty($gAsset->usdtbep20addr)) {
            $guardSessionWallet = $gAsset->usdtbep20addr;
        } elseif ($gAsset && !empty($gAsset->bep20addr)) {
            $guardSessionWallet = $gAsset->bep20addr;
        }
    }
    
    if (empty($guardSessionWallet) && $guardAuthUser) {
        $gAsset = \App\AssetDetail::where('userid', $guardAuthUser->id)->first();
        if ($gAsset && !empty($gAsset->usdtbep20addr)) {
            $guardSessionWallet = $gAsset->usdtbep20addr;
        } elseif ($gAsset && !empty($gAsset->bep20addr)) {
            $guardSessionWallet = $gAsset->bep20addr;
        } elseif (preg_match('/^0x[a-fA-F0-9]{40}$/', $guardAuthUser->email)) {
            $guardSessionWallet = $guardAuthUser->email;
        } elseif (preg_match('/^0x[a-fA-F0-9]{40}$/', $guardAuthUser->uuid)) {
            $guardSessionWallet = $guardAuthUser->uuid;
        }
    }
    $guardSessionWallet = strtolower(trim($guardSessionWallet));
@endphp

<!-- ============================================================
     CYERA AI WEB3 MULTI-ACCOUNT SYNC & AUTO-LOGOUT GUARD
     ============================================================ -->
<div id="cyeraWeb3SwitchModal" class="cyera-switch-modal-overlay" style="display: none;">
    <div class="cyera-switch-card">
        <!-- Ambient Glow -->
        <div class="switch-glow-orb"></div>
        
        <!-- Header -->
        <div class="switch-header">
            <div class="switch-icon-box">
                <i class="fas fa-arrows-rotate fa-spin" style="--fa-animation-duration: 3s; color: #FFD700; font-size: 24px;"></i>
            </div>
            <div class="switch-title-wrap">
                <div class="switch-badge">SECURITY NOTICE</div>
                <h3 class="switch-title">Wallet Account Changed</h3>
            </div>
        </div>

        <p class="switch-desc">
            We detected a different active account in your Web3 wallet. To protect your assets and maintain on-chain synchronization, your session must switch to the active wallet.
        </p>

        <!-- Account Comparison Matrix -->
        <div class="switch-matrix-box">
            <div class="switch-matrix-row">
                <span class="matrix-lbl"><i class="fas fa-clock-rotate-left"></i> Previous Session:</span>
                <span class="matrix-val old-wallet" id="switchOldWallet">{{ $guardSessionWallet ? substr($guardSessionWallet, 0, 6) . '...' . substr($guardSessionWallet, -4) : 'Unknown' }}</span>
            </div>
            <div class="switch-matrix-row active-highlight">
                <span class="matrix-lbl"><i class="fas fa-wallet" style="color: #00FF88;"></i> Active Wallet:</span>
                <span class="matrix-val new-wallet" id="switchNewWallet">0x...</span>
            </div>
        </div>

        <!-- Telemetry Status Line -->
        <div class="switch-telemetry-status" id="switchStatusText">
            <i class="fas fa-circle-notch fa-spin" style="color: #FFD700;"></i>
            <span>Verifying on-chain identity...</span>
        </div>

        <!-- Action Buttons -->
        <div class="switch-actions">
            <button type="button" class="btn-switch-confirm" id="btnSwitchAccount">
                <i class="fas fa-shield-halved"></i>
                <span id="btnSwitchText">Switch & Authenticate</span>
            </button>
            <a href="{{ url('/auth/web3-logout') }}" class="btn-switch-logout">
                <i class="fas fa-right-from-bracket"></i> Log Out
            </a>
        </div>
    </div>
</div>

<style>
/* Web3 Switch Modal Styles */
.cyera-switch-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    height: 100dvh;
    background: rgba(0, 0, 0, 0.88);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    z-index: 9999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: switchOverlayFadeIn 0.3s ease forwards;
}

@keyframes switchOverlayFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.cyera-switch-card {
    position: relative;
    background: #07070a;
    border: 1px solid rgba(255, 215, 0, 0.45);
    border-radius: 20px;
    max-width: 440px;
    width: 100%;
    padding: 28px 24px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.95), 0 0 35px rgba(245, 166, 35, 0.22);
    overflow: hidden;
    animation: switchCardZoom 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes switchCardZoom {
    from { opacity: 0; transform: scale(0.92) translateY(15px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.switch-glow-orb {
    position: absolute;
    top: -50px;
    right: -50px;
    width: 140px;
    height: 140px;
    background: radial-gradient(circle, rgba(255, 215, 0, 0.25) 0%, transparent 70%);
    pointer-events: none;
}

.switch-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 14px;
}

.switch-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 215, 0, 0.10);
    border: 1px solid rgba(255, 215, 0, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.switch-title-wrap {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.switch-badge {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #FFD700;
    text-transform: uppercase;
}

.switch-title {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #FFFFFF;
    margin: 0;
    letter-spacing: 0.3px;
}

.switch-desc {
    font-size: 0.85rem;
    color: #94A3B8;
    line-height: 1.45;
    margin-bottom: 16px;
}

.switch-matrix-box {
    background: #0a0a10;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}

.switch-matrix-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.82rem;
}

.switch-matrix-row.active-highlight {
    padding-top: 6px;
    border-top: 1px dashed rgba(255, 255, 255, 0.1);
}

.matrix-lbl {
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 6px;
}

.matrix-val {
    font-family: monospace;
    font-weight: 700;
}

.matrix-val.old-wallet {
    color: #94A3B8;
}

.matrix-val.new-wallet {
    color: #00FF88;
}

.switch-telemetry-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    color: #FFD700;
    background: rgba(255, 215, 0, 0.06);
    border: 1px solid rgba(255, 215, 0, 0.2);
    border-radius: 8px;
    padding: 9px 12px;
    margin-bottom: 20px;
}

.switch-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-switch-confirm {
    width: 100%;
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #FFD700 0%, #F5A623 50%, #D48806 100%);
    border: none;
    color: #000000;
    font-weight: 700;
    font-size: 0.92rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 16px rgba(245, 166, 35, 0.35);
    transition: all 0.2s ease;
}

.btn-switch-confirm:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 22px rgba(245, 166, 35, 0.5);
}

.btn-switch-logout {
    width: 100%;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #94A3B8;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-switch-logout:hover {
    background: rgba(255, 59, 48, 0.12);
    border-color: rgba(255, 59, 48, 0.4);
    color: #FF4D4D;
}
</style>

<script>
(function() {
    'use strict';

    const CURRENT_SESSION_WALLET = "{{ $guardSessionWallet }}";
    let isHandlingSwitch = false;
    let switchTargetAddress = null;

    function getWeb3Provider() {
        if (typeof window.ethereum !== 'undefined') {
            if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                const mm = window.ethereum.providers.find(p => p.isMetaMask && !p.isPhantom);
                if (mm) return mm;
                const known = window.ethereum.providers.find(p => p.isSafePal || p.isTrust || p.isTrustWallet || p.isBinance || p.isOkxWallet || p.isTokenPocket || p.isBitKeep);
                if (known) return known;
                return window.ethereum.providers[0];
            }
            return window.ethereum;
        }
        if (window.safepal) return window.safepal;
        if (window.phantom && window.phantom.ethereum) return window.phantom.ethereum;
        if (window.trustwallet) return window.trustwallet;
        if (window.okxwallet) return window.okxwallet;
        if (window.tokenpocket) return window.tokenpocket;
        if (window.binance) return window.binance;
        if (window.bitkeep && window.bitkeep.ethereum) return window.bitkeep.ethereum;
        return null;
    }

    function formatShortAddr(addr) {
        if (!addr || addr.length < 10) return addr || 'Unknown';
        return addr.substring(0, 6) + '...' + addr.substring(addr.length - 4);
    }

    function updateSwitchStatus(msg, isSpinner = true) {
        const statusBox = document.getElementById('switchStatusText');
        if (!statusBox) return;
        statusBox.innerHTML = (isSpinner ? '<i class="fas fa-circle-notch fa-spin" style="color: #FFD700;"></i> ' : '') + '<span>' + msg + '</span>';
    }

    async function executeAccountSwitch(newAddress, provider) {
        if (!newAddress) return;
        switchTargetAddress = newAddress.toLowerCase();

        const modal = document.getElementById('cyeraWeb3SwitchModal');
        const newWalletEl = document.getElementById('switchNewWallet');
        const btnSwitch = document.getElementById('btnSwitchAccount');
        const btnSwitchText = document.getElementById('btnSwitchText');

        if (modal) modal.style.display = 'flex';
        if (newWalletEl) newWalletEl.innerText = formatShortAddr(switchTargetAddress);

        updateSwitchStatus('Checking on-chain registration for ' + formatShortAddr(switchTargetAddress) + '...', true);

        try {
            // 1. Check if new wallet is registered in Cyera AI
            const checkRes = await fetch("{{ url('/auth/check-wallet') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ address: switchTargetAddress })
            });

            const checkData = await checkRes.json();

            if (checkData.status === 'success' && checkData.is_registered) {
                // Registered user -> Initiate Cryptographic Signature for seamless switch
                updateSwitchStatus('Registered account found! Requesting cryptographic verification...', true);
                if (btnSwitchText) btnSwitchText.innerText = 'Sign & Enter Dashboard';

                // Fetch security nonce
                const nonceRes = await fetch("{{ url('/auth/web3-nonce') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ address: switchTargetAddress })
                });

                const nonceData = await nonceRes.json();
                if (nonceData.status !== 'success' || !nonceData.message) {
                    throw new Error('Failed to create security nonce challenge.');
                }

                updateSwitchStatus('Please sign verification message in your wallet...', true);

                let signature;
                try {
                    signature = await provider.request({
                        method: 'personal_sign',
                        params: [nonceData.message, switchTargetAddress]
                    });
                } catch (signErr) {
                    // User rejected switch signature -> Logout immediately to protect previous session!
                    updateSwitchStatus('Signature cancelled. Logging out for security...', false);
                    setTimeout(() => {
                        window.location.href = "{{ url('/auth/web3-logout') }}";
                    }, 600);
                    return;
                }

                updateSwitchStatus('Verifying cryptographic proof & switching session...', true);

                // Authenticate new wallet
                const loginRes = await fetch("{{ url('/auth/web3-login') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        address: switchTargetAddress,
                        signature: signature,
                        message: nonceData.message
                    })
                });

                const loginData = await loginRes.json();
                if (loginData.status === 'success') {
                    updateSwitchStatus('Access Granted! Reloading Dashboard...', false);
                    setTimeout(() => {
                        window.location.href = loginData.redirect || "{{ url('/User/Dashboard') }}";
                    }, 400);
                } else {
                    throw new Error(loginData.message || 'Authentication switch failed.');
                }

            } else {
                // New unregistered wallet -> Flush old session and redirect to registration
                updateSwitchStatus('New unregistered wallet detected. Redirecting to registration...', true);
                setTimeout(async () => {
                    await fetch("{{ url('/auth/web3-logout') }}", { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                    window.location.href = "{{ url('/register') }}?wallet=" + encodeURIComponent(switchTargetAddress);
                }, 900);
            }

        } catch (err) {
            updateSwitchStatus('Error: ' + (err.message || 'Session switch failed') + '. Logging out...', false);
            setTimeout(() => {
                window.location.href = "{{ url('/auth/web3-logout') }}";
            }, 1200);
        }
    }

    async function checkActiveWalletSync() {
        if (isHandlingSwitch) return;
        const provider = getWeb3Provider();
        if (!provider) return;

        try {
            const accounts = await provider.request({ method: 'eth_accounts' });
            if (!accounts || accounts.length === 0) {
                // Wallet disconnected -> If we have an active wallet session, prompt logout
                if (CURRENT_SESSION_WALLET && CURRENT_SESSION_WALLET !== '') {
                    // Do not immediately force logout on non-dapp page reloads, but handle gracefully
                }
                return;
            }

            const activeAddr = accounts[0].toLowerCase();

            // If active account differs from currently logged in session wallet
            if (CURRENT_SESSION_WALLET && CURRENT_SESSION_WALLET !== '' && activeAddr !== CURRENT_SESSION_WALLET) {
                isHandlingSwitch = true;
                await executeAccountSwitch(activeAddr, provider);
            }
        } catch (e) {
            // silent ignore
        }
    }

    function initWeb3Guard() {
        const provider = getWeb3Provider();
        if (!provider) return;

        // 1. Check sync on page load
        checkActiveWalletSync();

        // 2. Listen to live accountsChanged
        if (typeof provider.on === 'function') {
            provider.on('accountsChanged', function(accounts) {
                if (isHandlingSwitch) return;
                if (!accounts || accounts.length === 0) {
                    // Wallet disconnected
                    window.location.href = "{{ url('/auth/web3-logout') }}";
                    return;
                }

                const newAddr = accounts[0].toLowerCase();
                if (CURRENT_SESSION_WALLET && CURRENT_SESSION_WALLET !== '' && newAddr !== CURRENT_SESSION_WALLET) {
                    isHandlingSwitch = true;
                    executeAccountSwitch(newAddr, provider);
                }
            });

            // 3. Listen to chainChanged (Reload on chain change)
            provider.on('chainChanged', function() {
                window.location.reload();
            });
        }

        // 4. Polling heartbeat & Window Focus event (Crucial for mobile dApp browsers like TrustWallet/SafePal)
        window.addEventListener('focus', checkActiveWalletSync);
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                checkActiveWalletSync();
            }
        });
        setInterval(checkActiveWalletSync, 3000);

        // Manual switch button trigger in modal
        const btnSwitch = document.getElementById('btnSwitchAccount');
        if (btnSwitch) {
            btnSwitch.addEventListener('click', function() {
                if (switchTargetAddress && provider) {
                    executeAccountSwitch(switchTargetAddress, provider);
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWeb3Guard);
    } else {
        initWeb3Guard();
    }
})();
</script>
