@extends('layouts.user-mecha')

@section('title', 'Royal Jackpot - Cyera AI')

@section('page-header')
<div class="rjx-strip">
    <div class="rjx-crumbs">
        <a href="{{ url('/User/Dashboard') }}"><i class="fas fa-home"></i> Home</a> / <b>Royal Jackpot</b>
    </div>
    <a href="{{ url('/User/Dashboard') }}" class="rjx-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>
@endsection

@section('content')
@include('user.royaljackpot-style')

@php
    $tierIcons = [1 => 'fa-medal', 2 => 'fa-award', 3 => 'fa-trophy', 4 => 'fa-gem'];
@endphp

<!-- ======================= HERO + ROYAL WALLET ======================= -->
<section class="rjx-hero">
    <div class="rjx-hero-head">
        <div class="rjx-crown"><i class="fas fa-crown"></i></div>
        <div>
            <h1>ROYAL <span>JACKPOT</span></h1>
            <p>4 prize pools &bull; 500 members each &bull; 50 winners &bull; instant draw</p>
        </div>
    </div>

    <div class="rjx-wallet">
        <div class="rjx-wbox main">
            <span class="rjx-wlbl"><i class="fas fa-wallet"></i> Royal Wallet</span>
            <span class="rjx-wval" id="royalWalletVal">${{ number_format($royalWalletBalance, 2) }} <small>USDT</small></span>
            <button type="button" class="rjx-claim" onclick="openClaimModal()" {{ $royalWalletBalance < 1.0 ? 'disabled' : '' }}>
                <i class="fas fa-arrow-up-right-from-square"></i> Claim
            </button>
        </div>
        <div class="rjx-wbox">
            <span class="rjx-wlbl">Total Won</span>
            <span class="rjx-wval">${{ number_format($totalWonAmount, 2) }}</span>
        </div>
        <div class="rjx-wbox">
            <span class="rjx-wlbl">Claimed</span>
            <span class="rjx-wval">${{ number_format($totalClaimedAmount, 2) }}</span>
        </div>
    </div>
</section>

<!-- ======================= POOLS ======================= -->
<div class="rjx-sec">
    <h2><i class="fas fa-layer-group"></i> Choose Your Pool</h2>
    <span class="live"><span class="rjx-dot"></span> Live</span>
</div>

<div class="rjx-grid">
    @foreach($pools as $p)
    @php
        $shortName = trim(preg_replace('/\s*\$\d+$/', '', $p->pool_name));
    @endphp
    <a href="{{ url('/User/RoyalJackpot/Pool/' . $p->pool_tier) }}" class="rjx-card t{{ $p->pool_tier }}">
        <div class="rjx-card-top">
            <span class="rjx-tier"><i class="fas {{ $tierIcons[$p->pool_tier] ?? 'fa-trophy' }}"></i> Pool {{ $p->pool_tier }}</span>
            <span class="rjx-live"><span class="rjx-dot"></span> R#{{ $p->activeRound->round_number }}</span>
        </div>

        @if($p->userTicketsCount > 0)
            <span class="rjx-mine">{{ $p->userTicketsCount }} ticket{{ $p->userTicketsCount > 1 ? 's' : '' }}</span>
        @endif

        <div class="rjx-cname">{{ $shortName }}</div>
        <div class="rjx-price">${{ number_format($p->ticket_price, 0) }} <small>/ ticket</small></div>

        <div class="rjx-win">
            <span>Win up to</span>
            <b>${{ number_format($p->prizes_json['rank_1'] ?? 0, 0) }}</b>
        </div>

        <div class="rjx-prog">
            <div class="rjx-track"><div class="rjx-fill" id="progress-bar-{{ $p->pool_tier }}" style="width: {{ max(2, $p->percentageFilled) }}%"></div></div>
            <div class="rjx-pmeta">
                <span>Filled <b id="slots-text-{{ $p->pool_tier }}">{{ $p->slotsFilled }} / 500</b></span>
                <span id="pct-text-{{ $p->pool_tier }}">{{ $p->percentageFilled }}%</span>
            </div>
        </div>

        <div class="rjx-cta">Open Pool <i class="fas fa-arrow-right"></i></div>
    </a>
    @endforeach
</div>

<!-- ======================= HOW IT WORKS ======================= -->
<div class="rjx-steps">
    <div class="rjx-step"><i class="fas fa-hand-pointer"></i><b>Pick a Pool</b><span>$10 to $100 tickets</span></div>
    <div class="rjx-step"><i class="fas fa-ticket"></i><b>Buy Ticket</b><span>One tap on-chain entry</span></div>
    <div class="rjx-step"><i class="fas fa-bolt"></i><b>Instant Draw</b><span>50 winners at 500</span></div>
</div>

<!-- ======================= TABS ======================= -->
<div class="rjx-panel">
    <div class="rjx-tabs">
        <button class="rjx-tab active" onclick="switchTab('my-tickets', this)"><i class="fas fa-ticket"></i> My Tickets ({{ $myEntries->total() ?? 0 }})</button>
        <button class="rjx-tab" onclick="switchTab('hall-of-fame', this)"><i class="fas fa-trophy"></i> Hall of Fame</button>
        <button class="rjx-tab" onclick="switchTab('rounds-history', this)"><i class="fas fa-clock-rotate-left"></i> Rounds</button>
    </div>

    <div id="tab-my-tickets" class="rjx-tabpane">
        @if($myEntries->count() > 0)
        <div class="rjx-scroll">
            <table class="rjx-table">
                <thead><tr><th>Ticket</th><th>Pool</th><th>Round</th><th>Entry</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @foreach($myEntries as $entry)
                    <tr>
                        <td><b style="color:#FFD700;">#{{ $entry->ticket_number }}</b></td>
                        <td><a href="{{ url('/User/RoyalJackpot/Pool/' . $entry->pool_tier) }}" style="color:#F5A623;text-decoration:none;">Pool {{ $entry->pool_tier }}</a></td>
                        <td>R#{{ $entry->round->round_number ?? '-' }}</td>
                        <td style="color:#00FF88;font-weight:700;">${{ number_format($entry->amount_paid, 2) }}</td>
                        <td>
                            @if(($entry->round->status ?? 0) == 1)
                                @php $win = \App\LuckyDrawWinner::where('round_id', $entry->round_id)->where('ticket_number', $entry->ticket_number)->first(); @endphp
                                @if($win)
                                    <span class="rjx-pill ok">🏆 Rank #{{ $win->rank }} · ${{ number_format($win->prize_amount, 0) }}</span>
                                @else
                                    <span class="rjx-pill dim">Drawn</span>
                                @endif
                            @else
                                <span class="rjx-pill gold">In Play</span>
                            @endif
                        </td>
                        <td style="color:#8A94A6;font-size:10px;">{{ $entry->created_at->format('d M, h:i A') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:8px 12px;">{{ $myEntries->links() }}</div>
        @else
            <div class="rjx-empty"><i class="fas fa-ticket"></i>No tickets yet. Open a pool above to enter!</div>
        @endif
    </div>

    <div id="tab-hall-of-fame" class="rjx-tabpane" style="display:none;">
        @if($recentWinners->count() > 0)
        <div class="rjx-scroll">
            <table class="rjx-table">
                <thead><tr><th>Rank</th><th>Wallet</th><th>Ticket</th><th>Pool</th><th>Round</th><th>Prize</th></tr></thead>
                <tbody>
                @foreach($recentWinners as $w)
                    <tr>
                        <td>
                            @if($w->rank == 1) <b style="color:#FFD700;">🥇 1st</b>
                            @elseif($w->rank == 2) <b style="color:#E2E8F0;">🥈 2nd</b>
                            @elseif($w->rank == 3) <b style="color:#CD7F32;">🥉 3rd</b>
                            @else <b>#{{ $w->rank }}</b> @endif
                        </td>
                        <td style="font-family:monospace;">{{ substr($w->wallet_address, 0, 6) }}...{{ substr($w->wallet_address, -4) }}</td>
                        <td><b style="color:#FFD700;">#{{ $w->ticket_number }}</b></td>
                        <td>Pool {{ $w->pool_tier }}</td>
                        <td>R#{{ $w->round->round_number ?? '-' }}</td>
                        <td style="color:#00FF88;font-weight:800;">+${{ number_format($w->prize_amount, 0) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="rjx-empty"><i class="fas fa-trophy"></i>Winners will appear here the moment a pool hits 500 members.</div>
        @endif
    </div>

    <div id="tab-rounds-history" class="rjx-tabpane" style="display:none;">
        @if($completedRounds->count() > 0)
        <div class="rjx-scroll">
            <table class="rjx-table">
                <thead><tr><th>Round</th><th>Pool</th><th>Members</th><th>Collected</th><th>Drawn</th></tr></thead>
                <tbody>
                @foreach($completedRounds as $rnd)
                    <tr>
                        <td><b style="color:#FFD700;">R#{{ $rnd->round_number }}</b></td>
                        <td>Pool {{ $rnd->pool_tier }} (${{ number_format($rnd->ticket_price, 0) }})</td>
                        <td>500 / 500</td>
                        <td style="color:#00FF88;font-weight:700;">${{ number_format($rnd->total_collected, 2) }}</td>
                        <td style="color:#8A94A6;font-size:10px;">{{ $rnd->drawn_at ? $rnd->drawn_at->format('d M, h:i A') : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="rjx-empty"><i class="fas fa-clock-rotate-left"></i>Round #1 is collecting entries.</div>
        @endif
    </div>
</div>

<!-- ======================= CLAIM MODAL ======================= -->
<div id="claimModal" class="rjx-modal-bg">
    <div class="rjx-modal">
        <div class="rjx-mhead">
            <h3><i class="fas fa-arrow-up-right-from-square" style="color:#00FF88;"></i> Claim Royal Wallet</h3>
            <button type="button" class="rjx-x" onclick="closeClaimModal()"><i class="fas fa-times"></i></button>
        </div>

        <div style="background:#000;border:1px solid rgba(0,255,136,.25);border-radius:12px;padding:11px 13px;margin-bottom:12px;">
            <div style="font-size:9.5px;color:#8A94A6;text-transform:uppercase;font-weight:700;letter-spacing:.5px;">Available Balance</div>
            <div style="font-size:24px;font-weight:900;color:#00FF88;">${{ number_format($royalWalletBalance, 2) }} <small style="font-size:11px;color:#8A94A6;">USDT</small></div>
        </div>

        <label style="font-size:11px;color:#CBD5E1;font-weight:700;display:block;margin-bottom:5px;">Amount (USDT)</label>
        <div style="display:flex;gap:6px;margin-bottom:6px;">
            <input type="number" id="claimAmountInput" value="{{ $royalWalletBalance }}" max="{{ $royalWalletBalance }}" min="1.00" step="0.01"
                   style="flex:1;height:38px;background:#000;border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:0 12px;color:#fff;font-size:13px;">
            <button type="button" class="rjx-btn ghost" style="height:38px;" onclick="document.getElementById('claimAmountInput').value='{{ $royalWalletBalance }}'">MAX</button>
        </div>
        <small style="font-size:10px;color:#5B6475;display:block;margin-bottom:12px;">Sent straight to your connected Web3 wallet on BNB Smart Chain via the Royal Jackpot Vault.</small>

        <div id="claimStatusBox" style="display:none;padding:9px 11px;border-radius:9px;margin-bottom:12px;font-size:11px;"></div>

        <div style="display:flex;gap:8px;justify-content:flex-end;">
            <button type="button" class="rjx-btn ghost" onclick="closeClaimModal()">Cancel</button>
            <button type="button" class="rjx-btn" id="btnExecuteClaim" onclick="executeClaimWeb3()">Confirm Withdrawal</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
<script>
const CONTRACT_VAULT = "{{ $contracts['vaultContract'] }}";
const IS_DEMO_MODE   = {{ $contracts['demoMode'] ? 'true' : 'false' }};
const CSRF_TOKEN     = "{{ csrf_token() }}";

function toast(icon, title, text) {
    if (window.Swal) {
        return Swal.fire({
            icon: icon,
            title: title,
            text: text,
            background: '#0A0C11',
            color: '#fff',
            confirmButtonColor: '#F5A623',
            confirmButtonText: 'OK',
            showCancelButton: false
        });
    }
    alert(title + (text ? '\n' + text : ''));
    return Promise.resolve();
}

function switchTab(tabId, btn) {
    document.querySelectorAll('.rjx-tab').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.rjx-tabpane').forEach(c => c.style.display = 'none');
    btn.classList.add('active');
    document.getElementById('tab-' + tabId).style.display = 'block';
}

function getWeb3Provider() {
    if (window.safepalProvider) return window.safepalProvider;
    if (window.safepal && window.safepal.ethereum) return window.safepal.ethereum;
    if (window.safepal && typeof window.safepal.request === 'function') return window.safepal;

    if (window.tokenpocket && window.tokenpocket.ethereum) return window.tokenpocket.ethereum;
    if (window.tokenpocket && typeof window.tokenpocket.request === 'function') return window.tokenpocket;

    if (window.trustwallet && window.trustwallet.ethereum) return window.trustwallet.ethereum;
    if (window.trustwallet && typeof window.trustwallet.request === 'function') return window.trustwallet;

    if (window.okxwallet && window.okxwallet.ethereum) return window.okxwallet.ethereum;
    if (window.okxwallet && typeof window.okxwallet.request === 'function') return window.okxwallet;

    if (window.bitkeep && window.bitkeep.ethereum) return window.bitkeep.ethereum;
    if (window.bitkeep && typeof window.bitkeep.request === 'function') return window.bitkeep;

    if (window.binance && typeof window.binance.request === 'function') return window.binance;

    if (typeof window.ethereum !== 'undefined') {
        if (window.ethereum.providers && Array.isArray(window.ethereum.providers)) {
            const prioritized = window.ethereum.providers.find(p => p.isSafePal || p.isTokenPocket || p.isTrust || p.isTrustWallet || p.isBinance || p.isOkxWallet || (p.isMetaMask && !p.isPhantom));
            if (prioritized) return prioritized;
            return window.ethereum.providers[0];
        }
        return window.ethereum;
    }

    if (window.phantom && window.phantom.ethereum) return window.phantom.ethereum;
    return null;
}

function openClaimModal()  { document.getElementById('claimModal').classList.add('active'); }
function closeClaimModal() { document.getElementById('claimModal').classList.remove('active'); }

async function executeClaimWeb3() {
    const amount = parseFloat(document.getElementById('claimAmountInput').value);
    const statusBox = document.getElementById('claimStatusBox');
    const btn = document.getElementById('btnExecuteClaim');

    if (!amount || amount < 1.0) { toast('warning', 'Minimum claim is $1.00 USDT'); return; }

    const rawProvider = getWeb3Provider();
    if (!rawProvider) {
        toast('warning', 'Wallet not found', 'Open this page inside SafePal, TokenPocket, TrustWallet or MetaMask dApp browser.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authorizing...';
    statusBox.style.display = 'block';
    statusBox.style.background = 'rgba(245,166,35,.12)';
    statusBox.style.color = '#FDE68A';
    statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting wallet...';

    try {
        let accounts = [];
        try {
            accounts = await rawProvider.request({ method: 'eth_requestAccounts' });
        } catch (accErr) {
            try {
                if (typeof rawProvider.send === 'function') {
                    accounts = await rawProvider.send('eth_requestAccounts', []);
                }
            } catch (e2) {
                try {
                    accounts = await rawProvider.request({ method: 'eth_accounts' });
                } catch (e3) {}
            }
        }

        if (!accounts || accounts.length === 0) {
            throw new Error('Please select or unlock an account in your wallet.');
        }

        const rawAddr = accounts[0];
        const userAddress = ethers.getAddress(rawAddr.toLowerCase());

        // Check BSC network
        try {
            const chain = await rawProvider.request({ method: 'eth_chainId' });
            const chainIdNum = parseInt(chain, 16) || parseInt(chain, 10);
            if (chainIdNum !== 56) {
                try {
                    await rawProvider.request({
                        method: 'wallet_switchEthereumChain',
                        params: [{ chainId: '0x38' }]
                    });
                } catch (swErr) {}
            }
        } catch (netErr) {}

        const provider = new ethers.BrowserProvider(rawProvider);
        const signer = await provider.getSigner(userAddress);

        statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Requesting withdrawal signature...';
        const sigRes = await fetch("{{ url('/User/RoyalJackpot/RequestClaim') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: JSON.stringify({ amount: amount, wallet_address: userAddress })
        });
        const sigData = await sigRes.json();
        if (sigData.status !== 'success') throw new Error(sigData.message || 'Signature authorization rejected.');

        const auth = sigData.data;
        statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirm the transaction in your wallet...';

        let claimTxHash = 'claim_' + Date.now();
        if (!IS_DEMO_MODE) {
            const cleanVault = ethers.getAddress(CONTRACT_VAULT.toLowerCase());
            const vaultAbi = ["function claimPrize(uint256 amount, uint256 claimId, uint256 expiry, bytes calldata signature) external"];
            const vault = new ethers.Contract(cleanVault, vaultAbi, signer);
            let tx;
            try {
                tx = await vault.claimPrize(auth.amountWei, auth.claimId, auth.expiry, auth.signature, { gasLimit: 250000 });
            } catch (gErr) {
                tx = await vault.claimPrize(auth.amountWei, auth.claimId, auth.expiry, auth.signature);
            }
            const receipt = await tx.wait(1);
            claimTxHash = (receipt && receipt.hash) ? receipt.hash : tx.hash;
        }

        statusBox.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finalizing ledger...';
        const confRes = await fetch("{{ url('/User/RoyalJackpot/ConfirmClaim') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: JSON.stringify({ amount: amount, claim_id: auth.claimId, tx_hash: claimTxHash })
        });
        const confData = await confRes.json();
        statusBox.style.background = 'rgba(0,255,136,.12)';
        statusBox.style.color = '#6EE7B7';
        statusBox.innerHTML = '<i class="fas fa-check-circle"></i> ' + (confData.message || 'Claim completed successfully!');
        setTimeout(() => location.reload(), 1200);

    } catch (err) {
        console.error('Claim error:', err);
        statusBox.style.background = 'rgba(239,68,68,.15)';
        statusBox.style.color = '#FCA5A5';
        statusBox.innerHTML = '<i class="fas fa-times-circle"></i> ' + (err?.info?.error?.message || err?.message || 'Withdrawal failed.');
        btn.disabled = false;
        btn.innerHTML = 'Confirm Withdrawal';
    }
}

async function refreshStats() {
    try {
        const res = await fetch("{{ url('/User/RoyalJackpot/Stats') }}");
        const json = await res.json();
        if (json.status === 'success' && json.pools) {
            for (const [tier, p] of Object.entries(json.pools)) {
                const s = document.getElementById('slots-text-' + tier);
                const b = document.getElementById('progress-bar-' + tier);
                const c = document.getElementById('pct-text-' + tier);
                if (s) s.innerText = p.slots_filled + ' / 500';
                if (b) b.style.width = Math.max(2, p.percentage) + '%';
                if (c) c.innerText = p.percentage + '%';
            }
        }
    } catch (e) {}
}
setInterval(refreshStats, 10000);
</script>
@endsection
