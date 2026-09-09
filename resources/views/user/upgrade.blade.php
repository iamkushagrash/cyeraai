@extends('layouts.user-mecha')

@section('title', 'Web3 Staking & Upgrade - Cyera AI')
@section('page-title', 'On-Chain Staking / Upgrade')
@section('page-icon', 'fas fa-cubes')

@section('content')
    @php
        $caiPrice = (float) ($price->price ?? 1.25);
        $splitterContract = env('INVESTMENT_SPLITTER_ADDRESS', '0x2A1CEBf5Afe686763E915838457ccBC344901ebD');
        $usdtContract = env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955');
        $currentUuid = Session::get('user.userid', 'CYERA');
    @endphp

    <!-- Mini Stats Grid -->
    <div class="mecha-stat-grid-2">
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>NETWORK & PROTOCOL</span>
                <i class="fab fa-ethereum" style="color: #FFD700;"></i>
            </div>
            <div class="mecha-metric-val gold" style="font-size: 1.3rem;">BNB SMART CHAIN</div>
            <div class="mecha-metric-sub">Binance Smart Chain (BEP-20)</div>
        </div>
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>CAI LIVE VALUATION</span>
                <i class="fas fa-chart-line" style="color: #00FF88;"></i>
            </div>
            <div class="mecha-metric-val green">${{ number_format($caiPrice, 2) }}</div>
            <div class="mecha-metric-sub">Real-Time Oracle Valuation</div>
        </div>
    </div>

    <div class="mecha-hud-card" style="max-width: 720px; margin: 0 auto; position: relative;">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-bolt" style="color: #FFD700;"></i>
                <div>
                    <h2 class="mecha-card-title">1-CLICK WEB3 ON-CHAIN STAKING</h2>
                    <div class="mecha-card-subtitle">Direct decentralized investment via Web3 Smart Contract</div>
                </div>
            </div>
            <span class="mecha-card-badge"
                style="background: rgba(0, 255, 136, 0.15); color: #00FF88; border-color: rgba(0, 255, 136, 0.3);">
                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 4px;"></i> BSC MAINNET
            </span>
        </div>

        <!-- Alert Container -->
        <div id="stakeAlert"
            style="display: none; padding: 14px 18px; border-radius: 12px; font-size: 0.88rem; font-weight: 500; margin-bottom: 20px;">
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
            style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.18); border-radius: 14px; padding: 12px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div
                    style="width: 36px; height: 36px; border-radius: 10px; background: rgba(0, 255, 136, 0.1); color: #00FF88; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div
                        style="font-size: 0.72rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                        Authenticated Wallet</div>
                    <div id="connectedWalletDisplay"
                        style="font-family: monospace; font-size: 0.90rem; color: #00FF88; font-weight: 700;">
                        {{ !empty($userWallet) ? substr($userWallet, 0, 6) . '...' . substr($userWallet, -4) : 'Connected' }}
                    </div>
                </div>
            </div>
            <div
                style="display: flex; align-items: center; gap: 6px; font-size: 0.76rem; color: #00FF88; font-weight: 600; background: rgba(0, 255, 136, 0.1); padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(0, 255, 136, 0.25);">
                <span
                    style="width: 6px; height: 6px; border-radius: 50%; background: #00FF88; box-shadow: 0 0 6px #00FF88;"></span>
                <span>ACTIVE</span>
            </div>
        </div>

        <!-- Staking Form -->
        <form id="web3StakeForm">
            @csrf

            <!-- Target Beneficiary User ID -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="targetUserId">
                    <span>Beneficiary User ID</span>
                    <span class="label-sub" style="color: #FFD700;">Account receiving the active stake</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-user-shield mecha-input-icon"></i>
                    <input type="text" id="targetUserId" class="mecha-input-control" name="targetUserId"
                        value="{{ $currentUuid }}" placeholder="Enter User ID (Default: Self)" required>
                </div>
            </div>

            <!-- Staking Amount Input -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="stakeAmount">
                    <span>Staking Amount (USDT)</span>
                    <span class="label-sub" style="color: #00FF88;">Min: $50 — Max: $2,000 USD</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-dollar-sign mecha-input-icon"></i>
                    <input type="number" step="10" min="50" max="2000" id="stakeAmount" class="mecha-input-control"
                        name="amount" placeholder="Enter Amount (e.g. 100)" value="100" required>
                </div>
            </div>

            <!-- Quick Preset Amount Buttons -->
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-bottom: 24px;">
                <button type="button" class="preset-amt-btn" data-amt="50">$50</button>
                <button type="button" class="preset-amt-btn active" data-amt="100">$100</button>
                <button type="button" class="preset-amt-btn" data-amt="250">$250</button>
                <button type="button" class="preset-amt-btn" data-amt="500">$500</button>
                <button type="button" class="preset-amt-btn" data-amt="1000">$1,000</button>
            </div>

            <!-- Action Button -->
            <button type="button" id="btnExecuteStake" class="mecha-btn-gold"
                style="height: 54px; font-size: 1.05rem; letter-spacing: 0.5px;">
                <i class="fas fa-bolt"></i>
                <span id="stakeBtnText">APPROVE & STAKE USDT ON BSC</span>
                <div class="btn-spinner-icon" id="stakeSpinner"
                    style="display: none; width: 22px; height: 22px; border-width: 3px;"></div>
            </button>
        </form>
    </div>

    <!-- ============================================================
         LIVE ON-CHAIN TRANSACTION STATUS MODAL
         ============================================================ -->
    <div id="txnStatusModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(12px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
        <div
            style="background: #0a0b12; border: 1px solid var(--card-border); border-radius: 20px; max-width: 460px; width: 100%; padding: 32px 26px; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.9);">

            <div id="modalIconWrap"
                style="width: 68px; height: 68px; border-radius: 50%; background: rgba(245, 166, 35, 0.12); color: #FFD700; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 18px auto;">
                <i class="fas fa-satellite-dish fa-spin"></i>
            </div>

            <h3 id="modalTitle"
                style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; color: #FFFFFF; font-weight: 700; margin-bottom: 8px;">
                Executing On-Chain Stake
            </h3>
            <p id="modalDesc" style="color: #94A3B8; font-size: 0.90rem; line-height: 1.5; margin-bottom: 22px;">
                Please confirm the transaction in MetaMask...
            </p>

            <!-- Progress Steps -->
            <div
                style="text-align: left; background: #07080d; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px;">
                <div id="step1"
                    style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; color: #94A3B8; font-size: 0.85rem;">
                    <i class="fas fa-circle-notch fa-spin" id="step1Icon"></i>
                    <span id="step1Text">Step 1: Approve USDT Token</span>
                </div>
                <div id="step2"
                    style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; color: #64748B; font-size: 0.85rem;">
                    <i class="far fa-circle" id="step2Icon"></i>
                    <span id="step2Text">Step 2: Staking Activation</span>
                </div>
                <div id="step3" style="display: flex; align-items: center; gap: 10px; color: #64748B; font-size: 0.85rem;">
                    <i class="far fa-circle" id="step3Icon"></i>
                    <span id="step3Text">Step 3: Staking & Yield Activation</span>
                </div>
            </div>

            <div id="modalActionBtn" style="display: none;">
                <button type="button" class="mecha-btn-gold" onclick="window.location.href='{{ url('/User/Dashboard') }}'"
                    style="height: 46px;">
                    <i class="fas fa-chart-line"></i> GO TO DASHBOARD
                </button>
            </div>
        </div>
    </div>

    <style>
        .preset-amt-btn {
            background: #0c0d16;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            color: #94A3B8;
            font-family: 'Outfit', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            padding: 10px 0;
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
            box-shadow: 0 0 12px rgba(245, 166, 35, 0.25);
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
                if (typeof window.ethereum === 'undefined') {
                    return null;
                }
                if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                    const mm = window.ethereum.providers.find(p => p.isMetaMask && !p.isPhantom);
                    if (mm) return mm;
                    const tw = window.ethereum.providers.find(p => p.isTrust || p.isTrustWallet || p.isBinance);
                    if (tw) return tw;
                    const nonPhantom = window.ethereum.providers.find(p => !p.isPhantom);
                    if (nonPhantom) return nonPhantom;
                    return window.ethereum.providers[0];
                }
                if (window.trustwallet) return window.trustwallet;
                return window.ethereum;
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
            function showModal(title, desc) {
                $('#modalTitle').text(title);
                $('#modalDesc').text(desc);
                $('#modalActionBtn').hide();
                $('#modalIconWrap').html('<i class="fas fa-satellite-dish fa-spin"></i>').css('color', '#FFD700');
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
                if (!provider) {
                    showStakeAlert('Please open inside MetaMask or TrustWallet to execute on-chain stake.');
                    return;
                }

                try {
                    const accounts = await provider.request({ method: 'eth_requestAccounts' });
                    if (!accounts || accounts.length === 0) {
                        showStakeAlert('Please select a Web3 account.');
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
                                return;
                            }
                        }
                    }

                    // Re-verify chain after switch
                    currentChain = await provider.request({ method: 'eth_chainId' });
                    if (currentChain !== BSC_CHAIN_ID) {
                        showStakeAlert('Network mismatch! Please switch MetaMask to BNB Smart Chain Mainnet (BSC).');
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

                    const txHash = receipt.hash;
                    setModalStep(2, 'done');

                    // 3. Post to Backend for Full 10-Step Audit & Activation
                    showModal('Step 3: Synchronizing Ledger & Ranks', 'Recording transaction & activating yield accumulation...');
                    setModalStep(3, 'active');

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
                            senderAddress: userAccount
                        })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        setModalStep(3, 'done');
                        $('#modalIconWrap').html('<i class="fas fa-check-circle"></i>').css('color', '#00FF88');
                        $('#modalTitle').text('Staking Successfully Activated!').css('color', '#00FF88');
                        $('#modalDesc').html('Your stake of <strong>$' + amount + ' USDT</strong> is confirmed on-chain.<br><a href="https://bscscan.com/tx/' + txHash + '" target="_blank" style="color: #FFD700; text-decoration: underline; font-family: monospace; font-size: 0.82rem;">View on BscScan <i class="fas fa-external-link-alt"></i></a>');
                        $('#modalActionBtn').show();
                    } else {
                        setModalStep(3, 'error');
                        $('#modalIconWrap').html('<i class="fas fa-triangle-exclamation"></i>').css('color', '#FB7185');
                        $('#modalTitle').text('Ledger Sync Notice');
                        $('#modalDesc').text(data.message || 'Transaction succeeded on-chain, but backend reported an issue.');
                        $('#modalActionBtn').show();
                    }

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

                    if (err.code === 'ACTION_REJECTED' || err.code === 4001 || (err.message && err.message.includes('rejected'))) {
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
                }
            });
        });
    </script>
@endpush