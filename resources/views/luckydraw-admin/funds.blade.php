@extends('luckydraw-admin.layout')

@section('title', 'Fund Management & Direct Transfer')
@section('header_title', 'Dedicated Fund Management & On-Chain Transfers')

@section('content')
<style>
    .fund-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
        gap: 24px;
        margin-bottom: 28px;
    }

    .contract-box {
        background: var(--bg-card);
        border: 1px solid var(--border-dim);
        border-radius: 14px;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }

    .contract-box.deposit-accent {
        border-color: rgba(245, 166, 35, 0.4);
        background: linear-gradient(180deg, rgba(245, 166, 35, 0.04), var(--bg-card));
    }

    .contract-box.vault-accent {
        border-color: rgba(0, 230, 118, 0.3);
        background: linear-gradient(180deg, rgba(0, 230, 118, 0.03), var(--bg-card));
    }

    .box-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border-dim);
    }

    .box-title h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #FFF;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .box-title p {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .balance-banner {
        background: rgba(0, 0, 0, 0.35);
        border: 1px solid var(--border-dim);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .balance-banner .bal-lbl {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
    }

    .balance-banner .bal-amt {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #FFF;
    }

    .quick-tag-btn {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid var(--border-dim);
        color: var(--gold-light);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quick-tag-btn:hover {
        background: rgba(245, 166, 35, 0.2);
        border-color: var(--gold-primary);
        color: #FFF;
    }

    .action-panel {
        background: rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 16px;
    }

    .action-panel h4 {
        font-size: 13.5px;
        font-weight: 700;
        color: #FFF;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<!-- Top Contract Metrics Summary -->
<div class="grid-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card gold-accent">
        <div class="card-head">
            <span class="card-label">Collected Tickets Balance</span>
            <div class="card-icon"><i class="fas fa-coins"></i></div>
        </div>
        <div class="card-val" id="dispDepositBal">${{ number_format($onChain['depositUsdt'], 2) }} USDT</div>
        <div class="card-sub">
            <span class="badge-pill badge-green"><i class="fas fa-circle" style="font-size: 6px;"></i> Ready to Sweep</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="card-head">
            <span class="card-label">Vault Payout Liquidity</span>
            <div class="card-icon"><i class="fas fa-vault"></i></div>
        </div>
        <div class="card-val" id="dispVaultBal">${{ number_format($onChain['vaultUsdt'], 2) }} USDT</div>
        <div class="card-sub">
            <span>Winner withdrawal pool</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="card-head">
            <span class="card-label">Collector Wallet USDT</span>
            <div class="card-icon"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="card-val">${{ number_format($onChain['collectorUsdt'], 2) }} USDT</div>
        <div class="card-sub">
            <span>{{ number_format($onChain['collectorBnb'], 4) }} BNB Gas</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="card-head">
            <span class="card-label">Pending Claim Requests</span>
            <div class="card-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="card-val">{{ $metrics['pendingClaimsCount'] }}</div>
        <div class="card-sub">
            <span>${{ number_format($metrics['totalClaimedOnChain'], 2) }} paid out</span>
        </div>
    </div>
</div>

<!-- TWO MAIN SECTIONS: DEPOSIT FUND TRANSFER & VAULT WITHDRAWAL MANAGEMENT -->
<div class="fund-grid">
    <!-- ==============================================
         1. DEPOSIT CONTRACT (COLLECTIONS & TRANSFER)
         ============================================== -->
    <div class="contract-box deposit-accent">
        <div class="box-header">
            <div class="box-title">
                <h3>
                    <i class="fas fa-arrow-circle-down" style="color: var(--gold-primary);"></i>
                    Deposit Contract (Collected Funds)
                </h3>
                <p>USDT collected from ticket purchases. Decoupled from core MLM contracts.</p>
            </div>
            <span class="badge-pill badge-gold">Collections</span>
        </div>

        <div style="margin-bottom: 14px; font-size: 12px; color: var(--text-sub);">
            <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
                <span>Contract Address:</span>
                <a href="https://bscscan.com/address/{{ $contracts['depositContract'] }}" target="_blank" class="hash-code">
                    {{ substr($contracts['depositContract'], 0, 10) }}...{{ substr($contracts['depositContract'], -8) }}
                    <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                </a>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Whitelisted Collector:</span>
                <span class="hash-code" style="color: #FFF;">
                    {{ substr($contracts['collectorWallet'], 0, 8) }}...{{ substr($contracts['collectorWallet'], -6) }}
                </span>
            </div>
        </div>

        <div class="balance-banner">
            <div>
                <div class="bal-lbl">Current Available Balance</div>
                <div class="bal-amt" style="color: var(--gold-light);" id="cardDepositUsdt">
                    ${{ number_format($onChain['depositUsdt'], 2) }} <span style="font-size: 14px; color: var(--text-muted);">USDT</span>
                </div>
            </div>
            <button class="btn-dim" onclick="sweepAllFunds()" style="padding: 8px 14px; font-size: 12px;">
                <i class="fas fa-bolt" style="color: var(--gold-primary);"></i> Quick Sweep All
            </button>
        </div>

        <!-- Direct Transfer Form to ANY Wallet -->
        <div class="action-panel">
            <h4>
                <i class="fas fa-paper-plane" style="color: var(--gold-primary);"></i>
                Transfer Collected Funds to Any Wallet
            </h4>
            <p style="font-size: 12px; color: var(--text-sub); margin-bottom: 14px;">
                Directly calls <code>withdrawCollectedFunds(recipient, amount)</code> on the Deposit smart contract using your connected deployer wallet.
            </p>

            <div style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="form-label" style="margin: 0;">Recipient Wallet Address</label>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="quick-tag-btn" onclick="fillRecipient('my')">My Wallet</button>
                        <button type="button" class="quick-tag-btn" onclick="fillRecipient('collector')">Collector</button>
                    </div>
                </div>
                <input type="text" id="targetRecipient" class="form-input" placeholder="0x... (Destination BEP-20 address)">
            </div>

            <div style="margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="form-label" style="margin: 0;">Amount in USDT</label>
                    <button type="button" class="quick-tag-btn" onclick="fillMaxDeposit({{ $onChain['depositUsdt'] }})">
                        MAX ({{ number_format($onChain['depositUsdt'], 2) }})
                    </button>
                </div>
                <input type="number" id="transferAmount" class="form-input" placeholder="0.00" step="any" min="0.01">
            </div>

            <button class="btn-gold" id="btnExecuteTransfer" onclick="executeFundTransfer()" style="width: 100%;">
                <i class="fas fa-arrow-right"></i> Execute Transfer On-Chain
            </button>
        </div>
    </div>

    <!-- ==============================================
         2. WITHDRAWAL / VAULT CONTRACT (PAYOUT LIQUIDITY)
         ============================================== -->
    <div class="contract-box vault-accent">
        <div class="box-header">
            <div class="box-title">
                <h3>
                    <i class="fas fa-vault" style="color: var(--green-live);"></i>
                    Prize Vault Contract (Winner Withdrawals)
                </h3>
                <p>Holds prize liquidity. Winners claim directly on-chain via EIP-712 cryptographic proofs.</p>
            </div>
            <span class="badge-pill badge-green">Payout Vault</span>
        </div>

        <div style="margin-bottom: 14px; font-size: 12px; color: var(--text-sub);">
            <div style="display:flex; justify-content:space-between; margin-bottom: 6px;">
                <span>Vault Contract Address:</span>
                <a href="https://bscscan.com/address/{{ $contracts['vaultContract'] }}" target="_blank" class="hash-code">
                    {{ substr($contracts['vaultContract'], 0, 10) }}...{{ substr($contracts['vaultContract'], -8) }}
                    <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                </a>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Backend Signer Address:</span>
                <span class="hash-code" style="color: #FFF;">
                    {{ substr($contracts['backendSigner'], 0, 8) }}...{{ substr($contracts['backendSigner'], -6) }}
                </span>
            </div>
        </div>

        <div class="balance-banner">
            <div>
                <div class="bal-lbl">Current Payout Liquidity</div>
                <div class="bal-amt" style="color: var(--green-live);" id="cardVaultUsdt">
                    ${{ number_format($onChain['vaultUsdt'], 2) }} <span style="font-size: 14px; color: var(--text-muted);">USDT</span>
                </div>
            </div>
            <div>
                <span class="badge-pill {{ $onChain['vaultUsdt'] > 0 ? 'badge-green' : 'badge-gold' }}">
                    <i class="fas fa-shield-alt"></i> {{ $onChain['vaultUsdt'] > 0 ? 'Liquidity Active' : 'Low Liquidity' }}
                </span>
            </div>
        </div>

        <!-- Action: Fund / Top-up Vault Liquidity -->
        <div class="action-panel">
            <h4>
                <i class="fas fa-plus-circle" style="color: var(--green-live);"></i>
                Fund / Top-Up Vault Liquidity
            </h4>
            <p style="font-size: 12px; color: var(--text-sub); margin-bottom: 14px;">
                Deposit USDT into the Prize Vault so winners can withdraw their Royal Wallet prizes directly on BSC.
            </p>

            <div style="margin-bottom: 16px;">
                <label class="form-label">Deposit Amount (USDT)</label>
                <input type="number" id="vaultDepositAmount" class="form-input" placeholder="e.g. 500.00" step="any" min="1">
            </div>

            <button class="btn-gold" id="btnDepositVault" onclick="depositToVault()" style="width: 100%; background: linear-gradient(135deg, #00E676, #00A854); color: #000;">
                <i class="fas fa-shield-alt"></i> Approve & Deposit USDT to Vault
            </button>
        </div>

        <!-- Action: Emergency Withdraw from Vault -->
        <div class="action-panel" style="margin-bottom: 0;">
            <h4>
                <i class="fas fa-exclamation-triangle" style="color: var(--red-alert);"></i>
                Emergency Vault Rebalance / Withdraw
            </h4>
            <p style="font-size: 12px; color: var(--text-sub); margin-bottom: 14px;">
                Calls <code>emergencyWithdraw(token, recipient, amount)</code> to safely reallocate vault liquidity.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px;">
                <div>
                    <label class="form-label">Recipient Address</label>
                    <input type="text" id="emergencyRecipient" class="form-input" placeholder="0x...">
                </div>
                <div>
                    <label class="form-label">Amount (USDT)</label>
                    <input type="number" id="emergencyAmount" class="form-input" placeholder="0.00">
                </div>
            </div>

            <button class="btn-dim" id="btnEmergencyWithdraw" onclick="executeEmergencyVaultWithdraw()" style="width: 100%; color: var(--red-alert); border-color: rgba(255, 51, 102, 0.3);">
                <i class="fas fa-exclamation-circle"></i> Emergency Withdraw from Vault
            </button>
        </div>
    </div>
</div>

<!-- RECENT CLAIM & TRANSACTION LOGS -->
<div class="cyber-table-wrap">
    <div class="cyber-table-head">
        <h3><i class="fas fa-receipt" style="color: var(--gold-primary); margin-right: 6px;"></i> Recent Winner Claims & Payout Logs</h3>
        <span class="badge-pill badge-gold">{{ count($claimLedgers) }} Recent Claims</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="cyber-table">
            <thead>
                <tr>
                    <th>Claim ID</th>
                    <th>User ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>On-Chain Tx Hash</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claimLedgers as $cl)
                <tr>
                    <td><strong style="color: var(--gold-light);">#{{ $cl->id }}</strong></td>
                    <td>UID: {{ $cl->userid }}</td>
                    <td><strong>${{ number_format($cl->amount, 2) }} USDT</strong></td>
                    <td>
                        @if($cl->status === 'COMPLETED')
                            <span class="badge-pill badge-green"><i class="fas fa-check"></i> Completed</span>
                        @elseif($cl->status === 'PENDING')
                            <span class="badge-pill badge-gold"><i class="fas fa-clock"></i> Pending</span>
                        @else
                            <span class="badge-pill badge-red">{{ $cl->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($cl->tx_hash) && strpos($cl->tx_hash, '0x') === 0)
                        <a href="https://bscscan.com/tx/{{ $cl->tx_hash }}" target="_blank" class="hash-code">
                            {{ substr($cl->tx_hash, 0, 8) }}...{{ substr($cl->tx_hash, -6) }}
                            <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                        </a>
                        @else
                        <span style="color: var(--text-muted); font-size: 11px;">Not confirmed</span>
                        @endif
                    </td>
                    <td style="font-size: 11px; color: var(--text-muted);">
                        {{ $cl->created_at ? $cl->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">No prize claims recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fillRecipient(type) {
        const input = document.getElementById('targetRecipient');
        if (type === 'my') {
            if (currentWallet) {
                input.value = currentWallet;
            } else {
                toast('warning', 'Wallet Not Connected', 'Please connect your deployer wallet first.');
            }
        } else if (type === 'collector') {
            input.value = CONTRACT_CONFIG.collector;
        }
    }

    function fillMaxDeposit(maxAmt) {
        document.getElementById('transferAmount').value = maxAmt;
    }

    // 1. Transfer Collected Funds from Deposit Contract to Any Wallet
    async function executeFundTransfer() {
        const recipient = document.getElementById('targetRecipient').value.trim();
        const amountStr = document.getElementById('transferAmount').value.trim();

        if (!ethers.isAddress(recipient)) {
            toast('error', 'Invalid Address', 'Please enter a valid BSC BEP-20 recipient address.');
            return;
        }

        const amount = parseFloat(amountStr);
        if (isNaN(amount) || amount <= 0) {
            toast('error', 'Invalid Amount', 'Please enter a valid USDT amount greater than 0.');
            return;
        }

        if (!window.ethereum) {
            toast('error', 'Wallet Required', 'MetaMask is required to sign this transaction.');
            return;
        }

        const confirm = await Swal.fire({
            title: 'Confirm On-Chain Transfer',
            html: `You are about to transfer <b>${amount} USDT</b> from the Deposit Contract to:<br><code style="color:#F5A623;">${recipient}</code><br><br>Are you sure?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Execute Transfer',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#F5A623',
            background: '#0D111A',
            color: '#FFF'
        });

        if (!confirm.isConfirmed) return;

        const btn = document.getElementById('btnExecuteTransfer');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming in wallet...';

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const depositContract = new ethers.Contract(CONTRACT_CONFIG.deposit, DEPOSIT_ABI, signer);

            const amountWei = ethers.parseUnits(amount.toString(), 18);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Waiting for BSC block...';

            const tx = await depositContract.withdrawCollectedFunds(recipient, amountWei);
            const receipt = await tx.wait(1);

            await Swal.fire({
                icon: 'success',
                title: 'Transfer Executed!',
                html: `Successfully transferred <b>${amount} USDT</b> to:<br><code style="color:#00E676;">${recipient}</code><br><br><a href="https://bscscan.com/tx/${receipt.hash}" target="_blank" style="color:#F5A623; text-decoration:underline;">View on BSCScan</a>`,
                background: '#0D111A',
                color: '#FFF',
                confirmButtonColor: '#F5A623'
            });

            window.location.reload();
        } catch (err) {
            console.error("Fund transfer error:", err);
            toast('error', 'Transfer Failed', err.reason || err.message || 'Transaction reverted.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    }

    // 2. 1-Click Sweep All to Collector
    async function sweepAllFunds() {
        if (!window.ethereum) {
            toast('error', 'Wallet Required', 'MetaMask is required to sign this transaction.');
            return;
        }

        const confirm = await Swal.fire({
            title: 'Sweep All to Collector?',
            text: `This will sweep 100% of available USDT in the Deposit contract directly to the Whitelisted Collector (${CONTRACT_CONFIG.collector.slice(0,6)}...${CONTRACT_CONFIG.collector.slice(-4)}).`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Sweep All',
            confirmButtonColor: '#F5A623',
            background: '#0D111A',
            color: '#FFF'
        });

        if (!confirm.isConfirmed) return;

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const depositContract = new ethers.Contract(CONTRACT_CONFIG.deposit, DEPOSIT_ABI, signer);

            const tx = await depositContract.sweepAllToCollector();
            const receipt = await tx.wait(1);

            await Swal.fire({
                icon: 'success',
                title: 'Sweep Complete!',
                html: `All funds swept to collector.<br><br><a href="https://bscscan.com/tx/${receipt.hash}" target="_blank" style="color:#F5A623; text-decoration:underline;">View on BSCScan</a>`,
                background: '#0D111A',
                color: '#FFF',
                confirmButtonColor: '#F5A623'
            });

            window.location.reload();
        } catch (err) {
            console.error("Sweep error:", err);
            toast('error', 'Sweep Failed', err.reason || err.message || 'Transaction reverted.');
        }
    }

    // 3. Fund / Top-up Vault Liquidity
    async function depositToVault() {
        const amtStr = document.getElementById('vaultDepositAmount').value.trim();
        const amount = parseFloat(amtStr);
        if (isNaN(amount) || amount <= 0) {
            toast('error', 'Invalid Amount', 'Enter a valid USDT amount to deposit into Vault.');
            return;
        }

        if (!window.ethereum) {
            toast('error', 'Wallet Required', 'MetaMask is required.');
            return;
        }

        const btn = document.getElementById('btnDepositVault');
        const orig = btn.innerHTML;
        btn.disabled = true;

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const walletAddr = await signer.getAddress();

            const usdt = new ethers.Contract(CONTRACT_CONFIG.usdt, USDT_ABI, signer);
            const amountWei = ethers.parseUnits(amount.toString(), 18);

            // Check allowance
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking USDT allowance...';
            const allowance = await usdt.allowance(walletAddr, CONTRACT_CONFIG.vault);

            // In USDT on BSC, standard ERC20 transfer directly to the Vault contract funds it!
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Transferring USDT to Vault in MetaMask...';
            const tx = await usdt.transfer(CONTRACT_CONFIG.vault, amountWei);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming on BSC...';
            const receipt = await tx.wait(1);

            await Swal.fire({
                icon: 'success',
                title: 'Vault Funded!',
                html: `Successfully deposited <b>${amount} USDT</b> into the Prize Vault.<br><br><a href="https://bscscan.com/tx/${receipt.hash}" target="_blank" style="color:#F5A623; text-decoration:underline;">View on BSCScan</a>`,
                background: '#0D111A',
                color: '#FFF',
                confirmButtonColor: '#00E676'
            });

            window.location.reload();
        } catch (err) {
            console.error("Vault funding error:", err);
            toast('error', 'Deposit Failed', err.reason || err.message || 'Transaction reverted.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    }

    // 4. Emergency Withdraw from Vault
    async function executeEmergencyVaultWithdraw() {
        const recipient = document.getElementById('emergencyRecipient').value.trim();
        const amountStr = document.getElementById('emergencyAmount').value.trim();

        if (!ethers.isAddress(recipient)) {
            toast('error', 'Invalid Address', 'Enter a valid recipient address.');
            return;
        }

        const amount = parseFloat(amountStr);
        if (isNaN(amount) || amount <= 0) {
            toast('error', 'Invalid Amount', 'Enter a valid amount.');
            return;
        }

        if (!window.ethereum) return;

        const btn = document.getElementById('btnEmergencyWithdraw');
        const orig = btn.innerHTML;
        btn.disabled = true;

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const vault = new ethers.Contract(CONTRACT_CONFIG.vault, VAULT_ABI, signer);
            const amountWei = ethers.parseUnits(amount.toString(), 18);

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sign in MetaMask...';
            const tx = await vault.emergencyWithdraw(CONTRACT_CONFIG.usdt, recipient, amountWei);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming on BSC...';
            const receipt = await tx.wait(1);

            await Swal.fire({
                icon: 'success',
                title: 'Emergency Withdrawal Executed',
                html: `Withdrawn ${amount} USDT to ${recipient}.<br><br><a href="https://bscscan.com/tx/${receipt.hash}" target="_blank" style="color:#F5A623; text-decoration:underline;">View on BSCScan</a>`,
                background: '#0D111A',
                color: '#FFF'
            });

            window.location.reload();
        } catch (err) {
            console.error("Emergency withdraw error:", err);
            toast('error', 'Withdrawal Failed', err.reason || err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    }
</script>
@endsection
