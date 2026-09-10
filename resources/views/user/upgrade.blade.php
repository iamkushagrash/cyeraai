@extends('layouts.user-mecha')

@section('title', 'Web3 Staking & Upgrade - Cyera AI')
@section('page-title', 'On-Chain Staking')
@section('page-icon', 'fas fa-cubes')

@section('content')
    @php
        $caiPrice = (float) ($price->price ?? 1.25);
        $splitterContract = env('INVESTMENT_SPLITTER_ADDRESS', '0x2A1CEBf5Afe686763E915838457ccBC344901ebD');
        $usdtContract = env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955');
        
        $currentUuid = '';
        if (Session::has('user.uuid') && !empty(Session::get('user.uuid'))) {
            $currentUuid = Session::get('user.uuid');
        } elseif (Session::has('user.userid')) {
            $uObj = \App\User::where('id', Session::get('user.userid'))->orWhere('uuid', Session::get('user.userid'))->first();
            if ($uObj) $currentUuid = $uObj->uuid;
        } elseif (Session::has('user.id')) {
            $uObj = \App\User::where('id', Session::get('user.id'))->first();
            if ($uObj) $currentUuid = $uObj->uuid;
        }
        if (!$currentUuid) $currentUuid = 'Self';
    @endphp

    <!-- Mini Stats Grid (Compact) -->
    <div class="mecha-stat-grid-2" style="margin-bottom: 10px; gap: 8px;">
        <div class="mecha-metric-box" style="padding: 8px 10px;">
            <div class="mecha-metric-lbl" style="font-size: 8px; margin-bottom: 2px;">
                <span>NETWORK &amp; PROTOCOL</span>
                <i class="fab fa-ethereum" style="color: #FFD700; font-size: 11px;"></i>
            </div>
            <div class="mecha-metric-val gold" style="font-size: 13px; font-weight: 800; line-height: 1.2;">BNB SMART CHAIN</div>
            <div class="mecha-metric-sub" style="font-size: 7.5px; margin-top: 1px;">BEP-20 • BSC Mainnet</div>
        </div>
        <div class="mecha-metric-box" style="padding: 8px 10px;">
            <div class="mecha-metric-lbl" style="font-size: 8px; margin-bottom: 2px;">
                <span>ORACLE VALUATION</span>
                <i class="fas fa-chart-line" style="color: #00FF88; font-size: 11px;"></i>
            </div>
            <div class="mecha-metric-val green" style="font-size: 14px; font-weight: 800; line-height: 1.2;">${{ number_format($caiPrice, 2) }} <small style="font-size: 9px; color: #FFF;">/ CAI</small></div>
            <div class="mecha-metric-sub" style="font-size: 7.5px; margin-top: 1px; color: #00FF88;">Live Price Feed</div>
        </div>
    </div>

    <div class="mecha-hud-card" style="padding: 14px 14px; position: relative;">
        <div class="mecha-card-header" style="padding-bottom: 8px; margin-bottom: 10px;">
            <div class="mecha-card-title-wrap" style="gap: 8px;">
                <i class="fas fa-bolt" style="color: #FFD700; font-size: 13px;"></i>
                <div>
                    <h2 class="mecha-card-title" style="font-size: 12px; letter-spacing: 1px;">WEB3 ON-CHAIN STAKING</h2>
                    <div class="mecha-card-subtitle" style="font-size: 8.5px;">Direct decentralized investment via Web3 Smart Contract</div>
                </div>
            </div>
            <span class="mecha-card-badge"
                style="background: rgba(0, 255, 136, 0.12); color: #00FF88; border-color: rgba(0, 255, 136, 0.3); font-size: 8.5px; padding: 2px 6px;">
                <i class="fas fa-circle" style="font-size: 5px; margin-right: 3px;"></i> BSC MAINNET
            </span>
        </div>

        <!-- Alert Container -->
        <div id="stakeAlert"
            style="display: none; padding: 10px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; margin-bottom: 12px;">
        </div>

        @php
            $userWallet = Session::get('user.walletaddress', Session::get('user.usdtbep20address', ''));
            if (!$userWallet) {
                $asset = \App\AssetDetail::where('userid', Session::get('user.id'))->first();
                $userWallet = $asset ? ($asset->usdtbep20addr ?: $asset->bep20addr) : '';
            }
        @endphp

        <!-- Connected dApp Account Bar (Single Authenticated Wallet) -->
        <div
            style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.18); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; gap: 6px;">
            <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1;">
                <div
                    style="width: 28px; height: 28px; border-radius: 6px; background: rgba(0, 255, 136, 0.1); color: #00FF88; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div
                        style="font-size: 8px; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">
                        Authenticated Wallet</div>
                    <div id="connectedWalletDisplay"
                        style="font-family: monospace; font-size: 11px; color: #00FF88; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ !empty($userWallet) ? substr($userWallet, 0, 6) . '...' . substr($userWallet, -4) : 'Connected' }}
                    </div>
                </div>
            </div>
            <div
                style="display: flex; align-items: center; gap: 4px; font-size: 9px; color: #00FF88; font-weight: 700; background: rgba(0, 255, 136, 0.1); padding: 2px 7px; border-radius: 12px; border: 1px solid rgba(0, 255, 136, 0.25); flex-shrink: 0;">
                <span
                    style="width: 5px; height: 5px; border-radius: 50%; background: #00FF88; box-shadow: 0 0 5px #00FF88;"></span>
                <span>ACTIVE</span>
            </div>
        </div>

        <!-- Staking Form -->
        <form id="web3StakeForm">
            @csrf

            <!-- Target Beneficiary User ID -->
            <div class="mecha-form-group" style="margin-bottom: 10px; gap: 4px;">
                <label class="mecha-form-label" for="targetUserId" style="font-size: 9.5px;">
                    <span>Beneficiary User ID</span>
                    <span class="label-sub" style="color: #FFD700; font-size: 8px;">Target Account</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-user-shield mecha-input-icon" style="left: 10px; font-size: 11px;"></i>
                    <input type="text" id="targetUserId" class="mecha-input-control" name="targetUserId"
                        value="{{ $currentUuid }}" placeholder="Enter User ID (Default: Self)" style="height: 38px; padding-left: 32px; font-size: 12px;" required>
                </div>
            </div>

            <!-- Staking Amount Input -->
            <div class="mecha-form-group" style="margin-bottom: 10px; gap: 4px;">
                <label class="mecha-form-label" for="stakeAmount" style="font-size: 9.5px;">
                    <span>Staking Amount (USDT)</span>
                    <span class="label-sub" style="color: #00FF88; font-size: 8px;">Min: $50 — Max: $2,000</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-dollar-sign mecha-input-icon" style="left: 10px; font-size: 11px;"></i>
                    <input type="number" step="10" min="50" max="2000" id="stakeAmount" class="mecha-input-control"
                        name="amount" placeholder="Enter Amount (e.g. 100)" value="100" style="height: 38px; padding-left: 32px; font-size: 12px;" required>
                </div>
            </div>

            <!-- Quick Preset Amount Buttons -->
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px; margin-bottom: 12px;">
                <button type="button" class="preset-amt-btn" data-amt="50">$50</button>
                <button type="button" class="preset-amt-btn active" data-amt="100">$100</button>
                <button type="button" class="preset-amt-btn" data-amt="250">$250</button>
                <button type="button" class="preset-amt-btn" data-amt="500">$500</button>
                <button type="button" class="preset-amt-btn" data-amt="1000">$1K</button>
            </div>

            <!-- Verified Genuine USDT Security Assurance Badge -->
            <div style="background: rgba(10, 13, 25, 0.7); border: 1px solid rgba(0, 255, 136, 0.22); border-radius: 8px; padding: 7px 10px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 7px; min-width: 0;">
                    <i class="fas fa-shield-check" style="color: #00FF88; font-size: 13px; flex-shrink: 0;"></i>
                    <div style="min-width: 0;">
                        <div style="font-size: 8.5px; color: #E2E8F0; font-weight: 700; display: flex; align-items: center; gap: 5px;">
                            <span>Official Binance-Peg BSC-USD</span>
                            <span style="background: rgba(0, 255, 136, 0.15); color: #00FF88; font-size: 7.5px; padding: 1px 4px; border-radius: 4px;">VERIFIED</span>
                        </div>
                        <div style="font-family: monospace; font-size: 8px; color: #94A3B8; margin-top: 1px;">
                            0x55d398326f99059fF775485246999027B3197955
                        </div>
                    </div>
                </div>
                <div style="font-size: 8px; color: #F59E0B; text-align: right; flex-shrink: 0; line-height: 1.2;">
                    <i class="fas fa-ban" style="margin-right: 2px;"></i> No Wrapped/Fake<br>Tokens Accepted
                </div>
            </div>

            <!-- Action Button -->
            <button type="button" id="btnExecuteStake" class="mecha-btn-gold"
                style="height: 42px; font-size: 12px; letter-spacing: 0.8px; font-weight: 900;">
                <i class="fas fa-bolt"></i>
                <span id="stakeBtnText">APPROVE &amp; STAKE USDT</span>
                <div class="btn-spinner-icon" id="stakeSpinner"
                    style="display: none; width: 16px; height: 16px; border-width: 2px;"></div>
            </button>
        </form>
    </div>

    <!-- ============================================================
         LIVE ON-CHAIN TRANSACTION STATUS MODAL (LOCKED DURING VERIFICATION)
         ============================================================ -->
    <div id="txnStatusModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.88); backdrop-filter: blur(14px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
        <div
            style="background: #0a0b12; border: 1px solid var(--card-border, rgba(245, 166, 35, 0.25)); border-radius: 16px; max-width: 440px; width: 100%; padding: 26px 20px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.95); position: relative;">

            <div id="modalIconWrap"
                style="width: 58px; height: 58px; border-radius: 50%; background: rgba(245, 166, 35, 0.12); color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 14px auto;">
                <i class="fas fa-satellite-dish fa-spin"></i>
            </div>

            <h3 id="modalTitle"
                style="font-family: 'Outfit', sans-serif; font-size: 1.2rem; color: #FFFFFF; font-weight: 700; margin-bottom: 6px;">
                Executing On-Chain Stake
            </h3>
            <p id="modalDesc" style="color: #94A3B8; font-size: 0.82rem; line-height: 1.45; margin-bottom: 16px;">
                Please confirm the transaction in your wallet popup...
            </p>

            <!-- Progress Steps -->
            <div
                style="text-align: left; background: #07080d; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; border: 1px solid rgba(255, 255, 255, 0.05);">
                <div id="step1"
                    style="display: flex; align-items: center; gap: 9px; margin-bottom: 10px; color: #94A3B8; font-size: 0.82rem;">
                    <i class="fas fa-circle-notch fa-spin" id="step1Icon"></i>
                    <span id="step1Text">Step 1: Approve Official USDT Token</span>
                </div>
                <div id="step2"
                    style="display: flex; align-items: center; gap: 9px; margin-bottom: 10px; color: #64748B; font-size: 0.82rem;">
                    <i class="far fa-circle" id="step2Icon"></i>
                    <span id="step2Text">Step 2: On-Chain Contract Deposit (70/30 Splitter)</span>
                </div>
                <div id="step3" style="display: flex; align-items: center; gap: 9px; color: #64748B; font-size: 0.82rem;">
                    <i class="far fa-circle" id="step3Icon"></i>
                    <span id="step3Text">Step 3: Blockchain Proof &amp; Ledger Synchronization</span>
                </div>
            </div>

            <!-- Lock Notice Box (Shown during verification) -->
            <div id="modalLockNotice" style="display: none; background: rgba(245, 166, 35, 0.08); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 8px; padding: 9px 12px; margin-bottom: 16px; text-align: left;">
                <div style="display: flex; align-items: center; gap: 7px; color: #FFD700; font-size: 10.5px; font-weight: 700; margin-bottom: 3px;">
                    <i class="fas fa-shield-halved"></i>
                    <span>VERIFICATION LOCK ACTIVE</span>
                </div>
                <div style="font-size: 8.5px; color: #CBD5E1; line-height: 1.35;">
                    Please stay on this page. Our multi-RPC cluster is validating transaction consensus and updating tree commissions. Do not refresh or close.
                </div>
            </div>

            <!-- Action Buttons Area -->
            <div id="modalActionBtn" style="display: none; flex-direction: column; gap: 8px;">
                <button type="button" id="btnRetrySync" class="mecha-btn-gold" style="display: none; height: 40px; font-size: 12px; background: linear-gradient(135deg, #F59E0B, #D97706);">
                    <i class="fas fa-rotate-right"></i> RETRY LEDGER SYNCHRONIZATION
                </button>
                <button type="button" class="mecha-btn-gold" onclick="window.location.href='{{ url('/User/Dashboard') }}'"
                    style="height: 40px; font-size: 12px;">
                    <i class="fas fa-chart-line"></i> GO TO DASHBOARD
                </button>
            </div>
        </div>
    </div>

    <style>
        .preset-amt-btn {
            background: #0c0d16;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            color: #94A3B8;
            font-family: 'Outfit', sans-serif;
            font-size: 0.80rem;
            font-weight: 700;
            padding: 6px 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .preset-amt-btn:hover {
            color: #FFD700;
            border-color: rgba(245, 166, 35, 0.4);
            background: rgba(245, 166, 35, 0.08);
        }

        .preset-amt-btn.active {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(245, 166, 35, 0.1));
            border-color: #FFD700;
            color: #FFD700;
            box-shadow: 0 0 10px rgba(245, 166, 35, 0.25);
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
    <script>
        $(document).ready(function () {
            const SPLITTER_ADDRESS = "{{ $splitterContract }}";
            const USDT_ADDRESS = "{{ $usdtContract }}";
            const BSC_CHAIN_ID = '0x38'; // 56 BSC Mainnet
            const IS_DEMO_MODE = {{ (config('app.demo_mode') || env('DEMO_MODE') == 'true' || env('DEMO_MODE') === true || env('TEST_MODE') == 'true' || env('TEST_MODE') === true) ? 'true' : 'false' }};

            // Minimal ABIs
            const ERC20_ABI = [
                "function approve(address spender, uint256 amount) public returns (bool)",
                "function allowance(address owner, address spender) public view returns (uint256)",
                "function balanceOf(address account) public view returns (uint256)",
                "function decimals() public view returns (uint8)"
            ];

            const SPLITTER_ABI = [
                "function invest(uint256 amount) external",
                "event Invested(address indexed user, uint256 amountUSDT, uint256 timestamp, uint256 investmentId)"
            ];

            let userAccount = null;
            let lastConfirmedTxHash = null;
            let lastAmount = 0;
            let lastTargetUser = '';

            // Presets Click Handler
            $('.preset-amt-btn').on('click', function () {
                $('.preset-amt-btn').removeClass('active');
                $(this).addClass('active');
                const amt = $(this).data('amt');
                $('#stakeAmount').val(amt);
            });

            // Alert helper
            function showStakeAlert(msg, type = 'error') {
                const el = $('#stakeAlert');
                el.removeClass('auth-alert-success auth-alert-error');
                if (type === 'success') {
                    el.css({ 'background': 'rgba(0, 255, 136, 0.12)', 'border': '1px solid rgba(0, 255, 136, 0.3)', 'color': '#00FF88', 'display': 'block' });
                    el.html('<i class="fas fa-circle-check"></i> ' + msg);
                } else {
                    el.css({ 'background': 'rgba(244, 63, 94, 0.12)', 'border': '1px solid rgba(244, 63, 94, 0.3)', 'color': '#FB7185', 'display': 'block' });
                    el.html('<i class="fas fa-circle-exclamation"></i> ' + msg);
                }
            }

            function getMetaMaskProvider() {
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

            // Connect / Check Wallet
            async function checkWallet() {
                const provider = getMetaMaskProvider();
                if (!provider) {
                    $('#connectedWalletDisplay').html('<span style="color: #FB7185;">No Web3 Wallet Found</span>');
                    return null;
                }

                try {
                    const accounts = await provider.request({ method: 'eth_accounts' });
                    if (accounts && accounts.length > 0) {
                        userAccount = accounts[0];
                        $('#connectedWalletDisplay').html('<span style="color: #00FF88;">' + userAccount.substring(0, 6) + '...' + userAccount.substring(userAccount.length - 4) + '</span> (Connected)');
                        return userAccount;
                    } else {
                        $('#connectedWalletDisplay').html('<span style="color: #94A3B8;">Not Connected</span>');
                        return null;
                    }
                } catch (e) {
                    $('#connectedWalletDisplay').html('<span style="color: #FB7185;">Error reading wallet</span>');
                    return null;
                }
            }

            checkWallet();

            // Modal UI Helpers
            function showModal(title, desc, isLocked = false) {
                $('#modalTitle').text(title);
                $('#modalDesc').html(desc);
                $('#modalActionBtn').hide();
                $('#btnRetrySync').hide();
                $('#modalIconWrap').html('<i class="fas fa-satellite-dish fa-spin"></i>').css('color', '#FFD700');
                if (isLocked) {
                    $('#modalLockNotice').show();
                } else {
                    $('#modalLockNotice').hide();
                }
                $('#txnStatusModal').css('display', 'flex');
            }

            function setModalStep(step, status) { // status: 'active', 'done', 'error'
                const icon = $('#step' + step + 'Icon');
                const text = $('#step' + step);

                if (status === 'active') {
                    icon.attr('class', 'fas fa-circle-notch fa-spin').css('color', '#FFD700');
                    text.css('color', '#FFFFFF');
                } else if (status === 'done') {
                    icon.attr('class', 'fas fa-circle-check').css('color', '#00FF88');
                    text.css('color', '#00FF88');
                } else if (status === 'error') {
                    icon.attr('class', 'fas fa-circle-xmark').css('color', '#FB7185');
                    text.css('color', '#FB7185');
                }
            }

            // Sync with backend API (Thorough verification handling)
            async function syncWithBackend(txHash, amount, targetUserId, senderAddress) {
                showModal(
                    'Step 3: Multi-RPC Consensus Verification',
                    'Validating on-chain proof & synchronizing ledger...<br><span style="font-size: 0.76rem; color: #FFD700; font-family: monospace;">Tx: ' + txHash.substring(0, 10) + '...' + txHash.substring(txHash.length - 8) + '</span>',
                    true
                );
                setModalStep(3, 'active');

                try {
                    const response = await fetch("{{ url('/User/Web3UnifiedStake') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            amount: amount,
                            txHash: txHash,
                            targetUserId: targetUserId,
                            senderAddress: senderAddress
                        })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        setModalStep(3, 'done');
                        $('#modalLockNotice').hide();
                        $('#modalIconWrap').html('<i class="fas fa-check-circle"></i>').css('color', '#00FF88');
                        $('#modalTitle').text('Staking Successfully Activated!').css('color', '#00FF88');
                        $('#modalDesc').html('Your stake of <strong>$' + amount + ' USDT</strong> is 100% verified on BSC Mainnet.<br><a href="https://bscscan.com/tx/' + txHash + '" target="_blank" style="color: #FFD700; text-decoration: underline; font-family: monospace; font-size: 0.82rem;">View on BscScan <i class="fas fa-external-link-alt"></i></a>');
                        $('#modalActionBtn').css('display', 'flex');
                        $('#btnRetrySync').hide();
                    } else {
                        setModalStep(3, 'error');
                        $('#modalLockNotice').hide();
                        $('#modalIconWrap').html('<i class="fas fa-triangle-exclamation"></i>').css('color', '#FB7185');
                        $('#modalTitle').text('Verification In Progress / Notice');
                        $('#modalDesc').html((data.message || 'Transaction is confirmed on-chain, but multi-node indexing took longer than usual.') + '<br><small style="color: #94A3B8;">Click "Retry Ledger Synchronization" below to re-verify without spending gas again.</small>');
                        $('#modalActionBtn').css('display', 'flex');
                        $('#btnRetrySync').show();
                    }
                } catch (networkErr) {
                    console.error('Network sync error:', networkErr);
                    setModalStep(3, 'error');
                    $('#modalLockNotice').hide();
                    $('#modalIconWrap').html('<i class="fas fa-rotate-right fa-spin"></i>').css('color', '#F59E0B');
                    $('#modalTitle').text('Blockchain Sync Delayed');
                    $('#modalDesc').html('Your transaction is securely mined on BSC Mainnet, but network verification timed out.<br><br><strong>Do not re-stake or send funds again!</strong> Click below to finalize synchronization.');
                    $('#modalActionBtn').css('display', 'flex');
                    $('#btnRetrySync').show();
                }
            }

            // Retry Button Click Handler
            $('#btnRetrySync').on('click', function () {
                if (lastConfirmedTxHash) {
                    syncWithBackend(lastConfirmedTxHash, lastAmount, lastTargetUser, userAccount);
                }
            });

            // ============================================================
            // 1-CLICK REAL WEB3 ON-CHAIN STAKING EXECUTION
            // ============================================================
            $('#btnExecuteStake').on('click', async function () {
                $('#stakeAlert').hide();

                const amount = parseFloat($('#stakeAmount').val()) || 0;
                const targetUserId = $('#targetUserId').val().trim();

                if (amount < 50 || amount > 2000) {
                    showStakeAlert('Staking amount must be between $50 and $2,000 USDT.');
                    return;
                }

                const provider = getMetaMaskProvider();
                if (!provider && !IS_DEMO_MODE) {
                    showStakeAlert('Please open inside MetaMask or TrustWallet to execute on-chain stake.');
                    return;
                }

                const btn = $('#btnExecuteStake');
                btn.prop('disabled', true).css('opacity', '0.7');
                $('#stakeSpinner').show();
                $('#stakeBtnText').text('PROCESSING...');

                try {
                    let txHash = '';

                    if (IS_DEMO_MODE) {
                        if (provider) {
                            try {
                                const accs = await provider.request({ method: 'eth_accounts' });
                                if (accs && accs.length > 0) userAccount = accs[0];
                            } catch (e) {}
                        }
                        if (!userAccount) {
                            userAccount = "{{ Session::get('user.walletaddress', '') }}" || "0x1111111111111111111111111111111111111111";
                        }
                        showModal('Step 1: Approving USDT Token (Demo Mode)', 'Simulating instant USDT approval...');
                        setModalStep(1, 'active');
                        await new Promise(r => setTimeout(r, 600));
                        setModalStep(1, 'done');

                        showModal('Step 2: Executing Staking Deposit (Demo Mode)', 'Simulating staking investment...');
                        setModalStep(2, 'active');
                        await new Promise(r => setTimeout(r, 600));
                        const randBytes = new Uint8Array(32);
                        window.crypto.getRandomValues(randBytes);
                        txHash = '0x' + Array.from(randBytes).map(b => b.toString(16).padStart(2, '0')).join('');
                        setModalStep(2, 'done');
                    } else {
                        const accounts = await provider.request({ method: 'eth_requestAccounts' });
                        if (!accounts || accounts.length === 0) {
                            showStakeAlert('Please select a Web3 account.');
                            btn.prop('disabled', false).css('opacity', '1');
                            $('#stakeSpinner').hide();
                            $('#stakeBtnText').text('APPROVE & STAKE USDT');
                            return;
                        }
                        userAccount = accounts[0];

                        // Strictly switch network to BSC Mainnet (56 / 0x38)
                        let currentChain = await provider.request({ method: 'eth_chainId' });
                        if (currentChain !== BSC_CHAIN_ID) {
                            try {
                                await provider.request({
                                    method: 'wallet_switchEthereumChain',
                                    params: [{ chainId: BSC_CHAIN_ID }],
                                });
                            } catch (switchError) {
                                if (switchError.code === 4902 || switchError?.data?.originalError?.code === 4902) {
                                    await provider.request({
                                        method: 'wallet_addEthereumChain',
                                        params: [{
                                            chainId: BSC_CHAIN_ID,
                                            chainName: 'BNB Smart Chain Mainnet',
                                            nativeCurrency: { name: 'BNB', symbol: 'BNB', decimals: 18 },
                                            rpcUrls: ['https://bsc-dataseed.binance.org/'],
                                            blockExplorerUrls: ['https://bscscan.com/']
                                        }],
                                    });
                                } else {
                                    showStakeAlert('Please switch your wallet network to BNB Smart Chain (BSC Mainnet).');
                                    btn.prop('disabled', false).css('opacity', '1');
                                    $('#stakeSpinner').hide();
                                    $('#stakeBtnText').text('APPROVE & STAKE USDT');
                                    return;
                                }
                            }
                        }

                        // Re-verify chain after switch
                        currentChain = await provider.request({ method: 'eth_chainId' });
                        if (currentChain !== BSC_CHAIN_ID) {
                            showStakeAlert('Network mismatch! Please switch MetaMask to BNB Smart Chain Mainnet (BSC).');
                            btn.prop('disabled', false).css('opacity', '1');
                            $('#stakeSpinner').hide();
                            $('#stakeBtnText').text('APPROVE & STAKE USDT');
                            return;
                        }

                        const ethersProvider = new ethers.BrowserProvider(provider);
                        const signer = await ethersProvider.getSigner();

                        const usdtContract = new ethers.Contract(USDT_ADDRESS, ERC20_ABI, signer);
                        const splitterContract = new ethers.Contract(SPLITTER_ADDRESS, SPLITTER_ABI, signer);

                        const amountWei = ethers.parseUnits(amount.toString(), 18);

                        // Open Status Modal & Trigger Wallet Approval Popup
                        showModal('Step 1: Approving USDT Token', 'Please confirm the USDT approval transaction in your wallet popup...');
                        setModalStep(1, 'active');

                        // 1. Trigger Approval in Wallet
                        let allowance = 0n;
                        try {
                            allowance = await usdtContract.allowance(userAccount, SPLITTER_ADDRESS);
                        } catch (allowErr) {
                            console.warn('Allowance check, triggering direct approve:', allowErr);
                        }

                        if (allowance < amountWei) {
                            const approveTx = await usdtContract.approve(SPLITTER_ADDRESS, amountWei);
                            await approveTx.wait();
                        }

                        setModalStep(1, 'done');

                        // 2. Execute On-Chain Splitter Invest
                        showModal('Step 2: Executing Staking Deposit', 'Please confirm the staking investment transaction in your wallet popup...');
                        setModalStep(2, 'active');

                        const investTx = await splitterContract.invest(amountWei);
                        const receipt = await investTx.wait();

                        txHash = receipt.hash;
                        setModalStep(2, 'done');
                    }

                    // Save state for potential retry without re-spending gas
                    lastConfirmedTxHash = txHash;
                    lastAmount = amount;
                    lastTargetUser = targetUserId;

                    // 3. Post to Backend for Full Multi-RPC Consensus Verification & Activation
                    await syncWithBackend(txHash, amount, targetUserId, userAccount);

                } catch (err) {
                    console.error('Web3 Staking Error:', err);
                    $('#txnStatusModal').hide();

                    let errMsg = '';
                    const bscRpc = new ethers.JsonRpcProvider('https://bsc-dataseed.binance.org/');
                    const readUsdt = new ethers.Contract(USDT_ADDRESS, ERC20_ABI, bscRpc);

                    let usdtBalance = 0;
                    let bnbBalance = 0;
                    try {
                        const rawUsdt = await readUsdt.balanceOf(userAccount);
                        usdtBalance = parseFloat(ethers.formatUnits(rawUsdt, 18));
                        const rawBnb = await bscRpc.getBalance(userAccount);
                        bnbBalance = parseFloat(ethers.formatEther(rawBnb));
                    } catch(e) {}

                    if (IS_DEMO_MODE) {
                        errMsg = 'Demo Mode Error: ' + (err.message || 'Simulation execution failed');
                    } else if (err.code === 'ACTION_REJECTED' || err.code === 4001 || (err.message && err.message.includes('rejected'))) {
                        errMsg = 'Transaction was rejected in your wallet.';
                    } else if (usdtBalance < amount) {
                        errMsg = 'Transaction Failed (Insufficient USDT): Your wallet (' + userAccount.substring(0, 6) + '...' + userAccount.substring(userAccount.length - 4) + ') has $' + usdtBalance.toFixed(2) + ' USDT (BEP-20). Staking requires $' + amount.toFixed(2) + ' USDT.';
                    } else if (bnbBalance < 0.001) {
                        errMsg = 'Transaction Failed (Insufficient Gas): Your wallet has ' + bnbBalance.toFixed(4) + ' BNB. Please deposit at least 0.003 BNB to pay for network gas fees.';
                    } else {
                        errMsg = err.reason || err.shortMessage || err.message || 'Transaction execution failed on blockchain.';
                        if (errMsg.includes('require(false)')) {
                            errMsg = 'Smart Contract Revert: Insufficient USDT balance ($' + usdtBalance.toFixed(2) + ' available) or missing spending allowance.';
                        }
                    }

                    showStakeAlert(errMsg);
                } finally {
                    btn.prop('disabled', false).css('opacity', '1');
                    $('#stakeSpinner').hide();
                    $('#stakeBtnText').text('APPROVE & STAKE USDT');
                }
            });
        });
    </script>
@endpush