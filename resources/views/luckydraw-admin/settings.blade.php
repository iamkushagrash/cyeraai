@extends('luckydraw-admin.layout')

@section('title', 'Smart Contract Controls & Settings')
@section('header_title', 'Smart Contract Controls & Deployer Settings')

@section('content')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
        gap: 24px;
    }

    .settings-card {
        background: var(--bg-card);
        border: 1px solid var(--border-dim);
        border-radius: 14px;
        padding: 24px;
    }

    .settings-card h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 800;
        color: #FFF;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .settings-card p {
        font-size: 12.5px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .control-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid var(--border-dim);
    }

    .control-row:last-child {
        border-bottom: none;
    }

    .control-info .lbl {
        font-size: 13.5px;
        font-weight: 700;
        color: #FFF;
    }

    .control-info .desc {
        font-size: 11.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }
</style>

<div class="settings-grid">
    <!-- DEPOSIT CONTRACT CONTROLS -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-arrow-circle-down" style="color: var(--gold-primary);"></i>
            RoyalJackpotDeposit Contract
        </h3>
        <p>Manage on-chain deposit gates, collector address and auto-forward switches.</p>

        <!-- Pause Deposit State -->
        <div class="control-row">
            <div class="control-info">
                <div class="lbl">Deposit Contract Pause State</div>
                <div class="desc">Status: <b>{{ $onChain['depositPaused'] ? 'PAUSED' : 'ACTIVE' }}</b></div>
            </div>
            <button class="btn-dim" onclick="toggleContractPause('deposit', {{ $onChain['depositPaused'] ? 'false' : 'true' }})">
                <i class="fas {{ $onChain['depositPaused'] ? 'fa-play' : 'fa-pause' }}"></i>
                {{ $onChain['depositPaused'] ? 'Unpause Deposits' : 'Pause Deposits' }}
            </button>
        </div>

        <!-- Auto Forward State -->
        <div class="control-row">
            <div class="control-info">
                <div class="lbl">Auto-Forward on Ticket Buy</div>
                <div class="desc">Status: <b>{{ $onChain['autoForward'] ? 'ENABLED' : 'DISABLED' }}</b></div>
            </div>
            <button class="btn-dim" onclick="toggleAutoForward({{ $onChain['autoForward'] ? 'false' : 'true' }})">
                <i class="fas fa-toggle-{{ $onChain['autoForward'] ? 'on' : 'off' }}"></i>
                {{ $onChain['autoForward'] ? 'Disable' : 'Enable' }}
            </button>
        </div>

        <!-- Update Whitelisted Collector -->
        <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-dim);">
            <label class="form-label">Update Whitelisted Collector Address</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="newCollectorInput" class="form-input" placeholder="0x..." value="{{ $contracts['collectorWallet'] }}">
                <button class="btn-gold" onclick="updateCollectorAddress()" style="white-space: nowrap;">
                    Update
                </button>
            </div>
        </div>
    </div>

    <!-- VAULT CONTRACT CONTROLS -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-vault" style="color: var(--green-live);"></i>
            RoyalJackpotVault Contract
        </h3>
        <p>Manage prize payout vault, EIP-712 backend signer, and emergency controls.</p>

        <!-- Pause Vault State -->
        <div class="control-row">
            <div class="control-info">
                <div class="lbl">Vault Claim State</div>
                <div class="desc">Status: <b>{{ $onChain['vaultPaused'] ? 'PAUSED' : 'ACTIVE' }}</b></div>
            </div>
            <button class="btn-dim" onclick="toggleContractPause('vault', {{ $onChain['vaultPaused'] ? 'false' : 'true' }})">
                <i class="fas {{ $onChain['vaultPaused'] ? 'fa-play' : 'fa-pause' }}"></i>
                {{ $onChain['vaultPaused'] ? 'Unpause Claims' : 'Pause Claims' }}
            </button>
        </div>

        <!-- Update Backend Signer -->
        <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-dim);">
            <label class="form-label">Update EIP-712 Backend Signer Address</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="newSignerInput" class="form-input" placeholder="0x..." value="{{ $contracts['backendSigner'] }}">
                <button class="btn-gold" onclick="updateBackendSigner()" style="white-space: nowrap;">
                    Update
                </button>
            </div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">
                Winner claim signatures are validated against this address on-chain.
            </div>
        </div>

        <!-- Ownership Transfer -->
        <div style="margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-dim);">
            <label class="form-label">Two-Step Ownership Transfer</label>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="newOwnerInput" class="form-input" placeholder="New Owner 0x...">
                <button class="btn-dim" onclick="startOwnershipTransfer()" style="white-space: nowrap; color: var(--gold-light);">
                    Transfer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- POOL PARAMETERS SUMMARY TABLE -->
<div class="cyber-table-wrap" style="margin-top: 28px;">
    <div class="cyber-table-head">
        <h3><i class="fas fa-layer-group" style="color: var(--gold-primary); margin-right: 6px;"></i> Active Pool Tiers Configuration</h3>
    </div>
    <div style="overflow-x: auto;">
        <table class="cyber-table">
            <thead>
                <tr>
                    <th>Tier</th>
                    <th>Pool Name</th>
                    <th>Ticket Price</th>
                    <th>Max Slots</th>
                    <th>Min Staking Req.</th>
                    <th>Total Prize Pool</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pools as $p)
                <tr>
                    <td><span class="badge-pill badge-gold">Tier {{ $p->pool_tier }}</span></td>
                    <td><strong>{{ $p->pool_name }}</strong></td>
                    <td><strong style="color: var(--gold-light);">${{ number_format($p->ticket_price, 2) }} USDT</strong></td>
                    <td>500 Members</td>
                    <td>${{ number_format($p->min_staking_required, 2) }} (Strict Rule)</td>
                    <td><strong>${{ number_format($p->total_prize_pool, 2) }} USDT</strong></td>
                    <td>
                        <span class="badge-pill badge-green"><i class="fas fa-check"></i> Enabled</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function toggleContractPause(contractType, setPausedState) {
        if (!window.ethereum) return;
        const confirm = await Swal.fire({
            title: `${setPausedState ? 'Pause' : 'Unpause'} ${contractType.toUpperCase()}?`,
            text: `Are you sure you want to change the pause state of this contract?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Proceed',
            confirmButtonColor: '#F5A623',
            background: '#0D111A',
            color: '#FFF'
        });
        if (!confirm.isConfirmed) return;

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const addr = (contractType === 'deposit') ? CONTRACT_CONFIG.deposit : CONTRACT_CONFIG.vault;
            const abi = (contractType === 'deposit') ? DEPOSIT_ABI : VAULT_ABI;
            const contract = new ethers.Contract(addr, abi, signer);

            const tx = await contract.setPaused(setPausedState);
            await tx.wait(1);
            await toast('success', 'Status Updated', 'Contract pause state updated successfully.');
            window.location.reload();
        } catch (err) {
            toast('error', 'Action Failed', err.reason || err.message);
        }
    }

    async function toggleAutoForward(enableState) {
        if (!window.ethereum) return;
        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const deposit = new ethers.Contract(CONTRACT_CONFIG.deposit, DEPOSIT_ABI, signer);
            const tx = await deposit.setAutoForward(enableState);
            await tx.wait(1);
            await toast('success', 'Updated', 'Auto-forward state updated.');
            window.location.reload();
        } catch (err) {
            toast('error', 'Failed', err.reason || err.message);
        }
    }

    async function updateCollectorAddress() {
        const addr = document.getElementById('newCollectorInput').value.trim();
        if (!ethers.isAddress(addr)) {
            toast('error', 'Invalid Address', 'Enter a valid BEP-20 address.');
            return;
        }

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const deposit = new ethers.Contract(CONTRACT_CONFIG.deposit, DEPOSIT_ABI, signer);
            const tx = await deposit.setWhitelistedCollector(addr);
            await tx.wait(1);
            await toast('success', 'Collector Updated', `Whitelisted collector updated to ${addr}`);
            window.location.reload();
        } catch (err) {
            toast('error', 'Update Failed', err.reason || err.message);
        }
    }

    async function updateBackendSigner() {
        const addr = document.getElementById('newSignerInput').value.trim();
        if (!ethers.isAddress(addr)) {
            toast('error', 'Invalid Address', 'Enter a valid BEP-20 address.');
            return;
        }

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const vault = new ethers.Contract(CONTRACT_CONFIG.vault, VAULT_ABI, signer);
            const tx = await vault.setBackendSigner(addr);
            await tx.wait(1);
            await toast('success', 'Signer Updated', `Backend signer updated to ${addr}`);
            window.location.reload();
        } catch (err) {
            toast('error', 'Update Failed', err.reason || err.message);
        }
    }

    async function startOwnershipTransfer() {
        const addr = document.getElementById('newOwnerInput').value.trim();
        if (!ethers.isAddress(addr)) {
            toast('error', 'Invalid Address', 'Enter a valid BEP-20 address.');
            return;
        }

        try {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            const deposit = new ethers.Contract(CONTRACT_CONFIG.deposit, DEPOSIT_ABI, signer);
            const tx = await deposit.transferOwnership(addr);
            await tx.wait(1);
            await toast('success', 'Transfer Initiated', `Nominated ${addr} as pending owner. They must call acceptOwnership().`);
        } catch (err) {
            toast('error', 'Failed', err.reason || err.message);
        }
    }
</script>
@endsection
