@extends('layouts.user-mecha')

@section('title', 'Withdrawal - Cyera AI')
@section('page-title', 'Withdrawal')
@section('page-icon', 'fas fa-money-bill-transfer')

@section('page-actions')
    <a href="{{ url('/User/WithdrawalHistory') }}" class="mecha-btn-outline"
        style="height: 32px; padding: 0 12px; font-size: 11px;">
        <i class="fas fa-clock-rotate-left"></i> History
    </a>
@endsection

@section('content')
    @php
        $workingUsdt = (float) ($workingRemainingUsdt ?? 0);
        $destWallet = Session::get('user.walletaddress') ?? ($user->assetDetail()->usdtbep20addr ?? ($user->assetDetail()->usdttrc20addr ?? ''));
        if (empty($destWallet)) {
            $u = $user->user();
            if ($u && preg_match('/^0x[a-fA-F0-9]{40}$/', $u->email)) {
                $destWallet = $u->email;
            } elseif ($u && preg_match('/^0x[a-fA-F0-9]{40}$/', $u->uuid)) {
                $destWallet = $u->uuid;
            }
        }
        $vaultAddress = env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x0D1Cf84DcB6Ad9dF2d2f7a5998C441569e87684b');
    @endphp

    <!-- Top Balance Card -->
    <div style="margin-bottom: 16px;">
        <div class="mecha-metric-box" style="padding: 14px 16px;">
            <div class="mecha-metric-lbl">
                <span>AVAILABLE BALANCE</span>
                <i class="fas fa-wallet" style="color: #00FF88;"></i>
            </div>
            <div class="mecha-metric-val green" style="font-size: 1.4rem; line-height: 1.2; margin: 4px 0;">
                ${{ number_format($workingUsdt, 2) }} <small style="font-size: 12px; color: #A7F3D0;">USDT</small>
            </div>
            <div class="mecha-metric-sub" style="color: #8C9BAE;">
                Available for withdrawal
            </div>
        </div>
    </div>

    <!-- Main Withdrawal Card -->
    <div class="mecha-hud-card" style="position: relative;">
        <div class="mecha-card-header" style="margin-bottom: 16px;">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-arrow-up-right-from-square" style="color: #00FF88;"></i>
                <div>
                    <h2 class="mecha-card-title">REQUEST WITHDRAWAL</h2>
                    <div class="mecha-card-subtitle">Transfer funds directly to your wallet</div>
                </div>
            </div>
        </div>

        <!-- Destination Wallet Bar -->
        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 10px 14px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(0, 255, 136, 0.1); color: #00FF88; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 9px; color: #8C9BAE; text-transform: uppercase; font-weight: 700;">
                        Destination Wallet Address (BEP-20)
                    </div>
                    <div id="dispWalletAddress" style="font-family: monospace; font-size: 11.5px; color: #FFF; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ !empty($destWallet) ? $destWallet : 'Connecting Wallet...' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form UI -->
        <div id="withdrawWorkingFormWrap">
            <!-- Amount Input -->
            <div class="mecha-form-group">
                <label class="mecha-form-label" for="amountusdt">
                    <span>Withdrawal Amount ($)</span>
                    <span class="label-sub" style="color: #00FF88;">Available: ${{ number_format($workingUsdt, 2) }}</span>
                </label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-dollar-sign mecha-input-icon" style="color: #00FF88;"></i>
                    <input type="number" class="mecha-input-control"
                        id="amountusdt" name="amountusdt" min="0.01" placeholder="0.00" max="{{ $workingUsdt }}" step="any"
                        value="" autocomplete="off" required>
                    <button type="button" class="mecha-input-suffix-btn"
                        style="color: #00FF88; font-weight: 700;"
                        onclick="setPresetPct(100)">MAX</button>
                </div>

                <!-- Quick Presets -->
                <div style="display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap;">
                    <button type="button" class="mecha-btn-outline preset-btn" style="height: 28px; padding: 0 12px; font-size: 11px;"
                        onclick="setPresetPct(25)">25%</button>
                    <button type="button" class="mecha-btn-outline preset-btn" style="height: 28px; padding: 0 12px; font-size: 11px;"
                        onclick="setPresetPct(50)">50%</button>
                    <button type="button" class="mecha-btn-outline preset-btn" style="height: 28px; padding: 0 12px; font-size: 11px;"
                        onclick="setPresetPct(75)">75%</button>
                    <button type="button" class="mecha-btn-outline preset-btn"
                        style="height: 28px; padding: 0 12px; font-size: 11px; border-color: #00FF88; color: #00FF88; font-weight: 700;"
                        onclick="setPresetPct(100)">MAX</button>
                </div>
            </div>

            <!-- Settlement Breakdown Card -->
            <div style="background: rgba(3, 5, 8, 0.95); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; font-size: 11.5px; margin-bottom: 6px;">
                    <span style="color: #8C9BAE;">Withdrawal Amount:</span>
                    <span style="color: #FFF; font-weight: 700;" id="disp-gross">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11.5px; margin-bottom: 8px;">
                    <span style="color: #8C9BAE;">Admin Charge (10%):</span>
                    <span style="color: #FF4D7D; font-weight: 700;" id="disp-fee">-$0.00</span>
                </div>
                <div style="height: 1px; background: rgba(255, 255, 255, 0.08); margin: 8px 0;"></div>
                <div style="display: flex; justify-content: space-between; font-size: 13.5px; font-weight: 800;">
                    <span style="color: #FFF;">Net Amount:</span>
                    <span style="color: #00FF88;" id="disp-net">$0.00 USDT</span>
                </div>
            </div>

            <!-- Action Button -->
            <div style="margin-top: 14px;">
                <button type="button" class="mecha-btn-cyan" id="btnExecuteInstantWithdraw" onclick="startInstantWithdrawal()"
                    style="height: 44px; font-size: 13px; font-weight: 800; background: linear-gradient(135deg, #00FF88 0%, #00BD68 100%); color: #030508; border-color: #00FF88;">
                    <i class="fas fa-paper-plane" style="font-size: 13px;"></i>
                    <span id="btnWithdrawText">SUBMIT WITHDRAWAL</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Processing Modal Overlay -->
    <div id="withdrawModalOverlay"
        style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: #0A0D15; border: 1px solid rgba(0, 255, 136, 0.3); border-radius: 14px; width: 100%; max-width: 380px; padding: 22px; text-align: center;">
            
            <div style="width: 48px; height: 48px; border-radius: 50%; background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0, 255, 136, 0.3); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
                <i class="fas fa-spinner fa-spin" style="color: #00FF88; font-size: 20px;"></i>
            </div>

            <h3 id="modalTitle" style="font-size: 15px; font-weight: 700; color: #FFF; margin-bottom: 6px;">
                PROCESSING WITHDRAWAL
            </h3>
            <p id="modalSubtitle" style="font-size: 11.5px; color: #8C9BAE; margin-bottom: 0; line-height: 1.4;">
                Please confirm the transaction in your wallet...
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
    <script>
        const MAX_WORKING_AVAILABLE = {{ $workingUsdt }};
        const VAULT_ADDRESS = "{{ $vaultAddress }}";
        const BSC_CHAIN_ID_HEX = "0x38";
        const BSC_CHAIN_ID_DEC = 56;

        const VAULT_ABI = [
            "function executeWithdrawalWithSignature(address recipient, uint256 amount, uint256 withdrawalId, uint256 expiry, bytes calldata signature) external"
        ];

        let activeAccount = "{{ $destWallet }}";

        function setPresetPct(pct) {
            const input = document.getElementById('amountusdt');
            if (!input || input.disabled) return;

            let finalAmt = (MAX_WORKING_AVAILABLE * (pct / 100));
            input.value = finalAmt.toFixed(2);
            updateLedger();
        }

        function updateLedger() {
            const input = document.getElementById('amountusdt');
            if (!input) return;

            let gross = parseFloat(input.value) || 0;
            if (gross < 0) gross = 0;

            let fee = gross * 0.10;
            let net = gross - fee;

            document.getElementById('disp-gross').innerText = '$' + gross.toFixed(2);
            document.getElementById('disp-fee').innerText = '-$' + fee.toFixed(2);
            document.getElementById('disp-net').innerText = '$' + net.toFixed(2) + ' USDT';
        }

        async function getWeb3Provider() {
            if (typeof window.ethereum !== 'undefined') {
                if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
                    const metamask = window.ethereum.providers.find(p => p.isMetaMask);
                    if (metamask) return metamask;
                    return window.ethereum.providers[0];
                }
                return window.ethereum;
            }
            return null;
        }

        async function ensureBscNetwork(rawProvider) {
            let chainId = await rawProvider.request({ method: 'eth_chainId' });
            if (chainId !== BSC_CHAIN_ID_HEX && parseInt(chainId, 16) !== BSC_CHAIN_ID_DEC) {
                try {
                    await rawProvider.request({
                        method: 'wallet_switchEthereumChain',
                        params: [{ chainId: BSC_CHAIN_ID_HEX }]
                    });
                } catch (switchError) {
                    if (switchError.code === 4902 || switchError?.data?.originalError?.code === 4902) {
                        await rawProvider.request({
                            method: 'wallet_addEthereumChain',
                            params: [{
                                chainId: BSC_CHAIN_ID_HEX,
                                chainName: 'BNB Smart Chain Mainnet',
                                nativeCurrency: { name: 'BNB', symbol: 'BNB', decimals: 18 },
                                rpcUrls: ['https://bsc-dataseed.binance.org/'],
                                blockExplorerUrls: ['https://bscscan.com/']
                            }]
                        });
                    } else {
                        throw new Error('Please switch your Web3 wallet network to BNB Smart Chain (BSC Mainnet).');
                    }
                }
            }
        }

        async function startInstantWithdrawal() {
            const input = document.getElementById('amountusdt');
            const grossAmt = parseFloat(input.value) || 0;

            if (grossAmt <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'INVALID AMOUNT',
                    text: 'Please enter a valid withdrawal amount.',
                    background: '#0A0D15',
                    color: '#FFF',
                    confirmButtonColor: '#00FF88'
                });
                return;
            }

            if (grossAmt > MAX_WORKING_AVAILABLE) {
                Swal.fire({
                    icon: 'warning',
                    title: 'EXCEEDS BALANCE',
                    text: `Available balance is $${MAX_WORKING_AVAILABLE.toFixed(2)} USDT.`,
                    background: '#0A0D15',
                    color: '#FFF',
                    confirmButtonColor: '#00FF88'
                });
                return;
            }

            const rawProvider = await getWeb3Provider();
            if (!rawProvider) {
                Swal.fire({
                    icon: 'error',
                    title: 'WALLET NOT DETECTED',
                    text: 'Please open this website inside MetaMask, TrustWallet, or a Web3 browser.',
                    background: '#0A0D15',
                    color: '#FFF',
                    confirmButtonColor: '#00FF88'
                });
                return;
            }

            const modal = document.getElementById('withdrawModalOverlay');
            const btn = document.getElementById('btnExecuteInstantWithdraw');
            const btnTxt = document.getElementById('btnWithdrawText');

            try {
                btn.disabled = true;
                btnTxt.innerText = 'PROCESSING...';

                // Connect Accounts
                const accounts = await rawProvider.request({ method: 'eth_requestAccounts' });
                if (!accounts || accounts.length === 0) {
                    throw new Error('Please connect your Web3 wallet account.');
                }

                const userAccount = accounts[0];
                activeAccount = userAccount;
                document.getElementById('dispWalletAddress').innerText = userAccount;

                // Ensure BSC Mainnet
                await ensureBscNetwork(rawProvider);

                modal.style.display = 'flex';
                document.getElementById('modalSubtitle').innerText = 'Generating withdrawal authorization...';

                // 1. Request Signature from Backend
                const sigResp = await fetch('{{ url("/User/WithdrawWorking/RequestSignature") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amountusdt: grossAmt,
                        wallet_address: userAccount
                    })
                });

                const sigData = await sigResp.json();
                if (sigData.status !== 'success') {
                    throw new Error(sigData.message || 'Withdrawal authorization failed.');
                }

                const d = sigData.data;
                document.getElementById('modalSubtitle').innerText = 'Please confirm the withdrawal in your wallet...';

                // 2. Call executeWithdrawalWithSignature on Vault
                const ethersProvider = new ethers.BrowserProvider(rawProvider);
                const signer = await ethersProvider.getSigner();
                const vaultContract = new ethers.Contract(d.vault_address, VAULT_ABI, signer);

                const tx = await vaultContract.executeWithdrawalWithSignature(
                    d.recipient,
                    d.amount_wei,
                    d.withdrawal_id,
                    d.expiry,
                    d.signature,
                    { gasLimit: 250000 }
                );

                document.getElementById('modalSubtitle').innerText = 'Transaction submitted! Waiting for confirmation...';

                const receipt = await tx.wait(1);
                const txHash = receipt.hash || tx.hash;

                document.getElementById('modalSubtitle').innerText = 'Updating records...';

                // 3. Confirm to Backend & Save Tx Hash
                await fetch('{{ url("/User/WithdrawWorking/Confirm") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tx_hash: txHash,
                        withdrawal_id: d.withdrawal_id,
                        gross_amount: d.gross_amount,
                        net_amount: d.net_amount,
                        recipient: d.recipient
                    })
                });

                modal.style.display = 'none';

                await Swal.fire({
                    icon: 'success',
                    title: 'WITHDRAWAL SUCCESSFUL',
                    html: `
                        <div style="font-size: 13px; color: #CBD5E1; margin-top: 8px; line-height: 1.5;">
                            <p style="margin-bottom: 10px; font-weight: 700; color: #00FF88;">
                                $${d.net_amount.toFixed(2)} USDT transferred to your wallet.
                            </p>
                            <a href="https://bscscan.com/tx/${txHash}" target="_blank" style="color: #00E5FF; font-size: 11.5px; font-weight: 700; text-decoration: none;">
                                View on BscScan →
                            </a>
                        </div>
                    `,
                    background: '#0A0D15',
                    color: '#FFF',
                    confirmButtonText: 'VIEW HISTORY',
                    confirmButtonColor: '#00FF88'
                });

                window.location.href = "{{ url('/User/WithdrawalHistory') }}";

            } catch (err) {
                modal.style.display = 'none';
                btn.disabled = false;
                btnTxt.innerText = 'SUBMIT WITHDRAWAL';

                let errMsg = err.message || 'Withdrawal failed.';
                if (err.code === 'ACTION_REJECTED' || err.code === 4001 || (err.message && err.message.includes('user rejected'))) {
                    errMsg = 'Transaction was cancelled.';
                } else if (err.data && err.data.message) {
                    errMsg = err.data.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'WITHDRAWAL FAILED',
                    text: errMsg,
                    background: '#0A0D15',
                    color: '#FFF',
                    confirmButtonColor: '#FF4D7D'
                });
            }
        }

        document.addEventListener('DOMContentLoaded', async function () {
            const input = document.getElementById('amountusdt');
            if (input) {
                input.addEventListener('input', updateLedger);
                updateLedger();
            }

            const rawProvider = await getWeb3Provider();
            if (rawProvider) {
                try {
                    const accounts = await rawProvider.request({ method: 'eth_accounts' });
                    if (accounts && accounts.length > 0) {
                        activeAccount = accounts[0];
                        const disp = document.getElementById('dispWalletAddress');
                        if (disp) disp.innerText = accounts[0];
                    }
                } catch (e) {
                    console.warn('Auto account detect:', e);
                }
            }
        });
    </script>
@endpush