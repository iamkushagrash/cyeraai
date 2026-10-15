@extends('layouts.user-mecha')

@php
    $shortName = trim(preg_replace('/\s*\$\d+$/', '', $pool->pool_name));
    $tierIcons = [1 => 'fa-medal', 2 => 'fa-award', 3 => 'fa-trophy', 4 => 'fa-gem'];
    $pz = $pool->prizes_json;
    $ladder = [
        ['label' => '1st Place',    'sub' => 'Grand Prize',  'count' => 1,  'each' => (float) ($pz['rank_1'] ?? 0),     'medal' => '🥇'],
        ['label' => '2nd Place',    'sub' => 'Runner-up',    'count' => 1,  'each' => (float) ($pz['rank_2'] ?? 0),     'medal' => '🥈'],
        ['label' => '3rd Place',    'sub' => 'Podium',       'count' => 1,  'each' => (float) ($pz['rank_3'] ?? 0),     'medal' => '🥉'],
        ['label' => '4th – 10th',   'sub' => 'Top 10',       'count' => 7,  'each' => (float) ($pz['rank_4_10'] ?? 0),  'medal' => ''],
        ['label' => '11th – 25th',  'sub' => 'Top 25',       'count' => 15, 'each' => (float) ($pz['rank_11_25'] ?? 0), 'medal' => ''],
        ['label' => '26th – 50th',  'sub' => 'Top 50',       'count' => 25, 'each' => (float) ($pz['rank_26_50'] ?? 0), 'medal' => ''],
    ];
    $ladderTotal = 0;
    foreach ($ladder as $row) { $ladderTotal += $row['count'] * $row['each']; }
    $topPrize = max(1, (float) ($pz['rank_1'] ?? 1));
    $stakePct = min(100, round(($eligibility['staked_amount'] / 100) * 100));
@endphp

@section('title', $shortName . ' - Royal Jackpot - Cyera AI')

@section('page-header')
<div class="rjx-strip">
    <div class="rjx-crumbs">
        <a href="{{ url('/User/Dashboard') }}"><i class="fas fa-home"></i></a> /
        <a href="{{ url('/User/RoyalJackpot') }}">Royal Jackpot</a> / <b>Pool {{ $pool->pool_tier }}</b>
    </div>
    <a href="{{ url('/User/RoyalJackpot') }}" class="rjx-back"><i class="fas fa-arrow-left"></i> All Pools</a>
</div>
@endsection

@section('content')
@include('user.royaljackpot-style')

<style>
/* ---------- Pool page specific ---------- */
.rjp-hero {
    position: relative; overflow: hidden; border-radius: 18px; padding: 16px;
    background: linear-gradient(165deg, #12100B 0%, #0A0B0F 50%, #060608 100%);
    border: 1px solid var(--accent-line);
    box-shadow: 0 12px 44px rgba(0,0,0,.85);
}
.rjp-hero::before { content: ''; position: absolute; left: 0; right: 0; top: 0; height: 4px; background: var(--accent-grad); }
.rjp-hero::after {
    content: ''; position: absolute; top: -80px; right: -60px; width: 260px; height: 260px; border-radius: 50%;
    background: radial-gradient(circle, var(--accent-soft) 0%, transparent 65%); pointer-events: none;
}
.rjp-hero-top { display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1; }
.rjp-title-row { display: flex; align-items: center; gap: 13px; margin: 14px 0 14px; position: relative; z-index: 1; }
.rjp-medal {
    width: 54px; height: 54px; border-radius: 15px; flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: #150E02; background: var(--accent-grad); box-shadow: 0 8px 22px rgba(0,0,0,.6);
}
.rjp-title-row h1 { margin: 0; font-size: 21px; font-weight: 900; color: #fff; line-height: 1.1; letter-spacing: .2px; }
.rjp-title-row p { margin: 4px 0 0; font-size: 11px; color: var(--rj-dim); }
.rjp-title-row p b { color: var(--accent); font-weight: 800; }
.rjp-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; position: relative; z-index: 1; }
.rjp-stat { background: rgba(0,0,0,.6); border: 1px solid var(--rj-line); border-radius: 12px; padding: 9px 8px; text-align: center; min-width: 0; }
.rjp-stat span { display: block; font-size: 9px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--rj-dim); margin-bottom: 3px; }
.rjp-stat b { font-size: 16px; font-weight: 900; color: #fff; white-space: nowrap; }
.rjp-stat.prize b { color: var(--rj-green); }
.rjp-stat.first b { color: var(--accent); }

.rjp-layout { display: grid; grid-template-columns: 1fr; gap: 12px; }
.rjp-card { background: var(--rj-card); border: 1px solid var(--rj-line); border-radius: 16px; padding: 14px; }
.rjp-card-h { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.rjp-card-h h2 { margin: 0; font-size: 12px; font-weight: 800; letter-spacing: .7px; text-transform: uppercase; color: #fff; display: flex; align-items: center; gap: 8px; }
.rjp-card-h h2 i { color: var(--accent); }
.rjp-card-h small { font-size: 10px; color: var(--rj-dim); font-weight: 600; }

/* Progress */
.rjp-big { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 8px; }
.rjp-big b { font-size: 26px; font-weight: 900; color: #fff; line-height: 1; }
.rjp-big b small { font-size: 13px; color: var(--rj-mute); font-weight: 700; }
.rjp-big span { font-size: 12px; font-weight: 800; color: var(--accent); }
.rjp-bar { position: relative; height: 12px; background: rgba(255,255,255,.07); border-radius: 12px; overflow: hidden; }
.rjp-bar-fill { height: 100%; border-radius: 12px; background: var(--accent-grad); transition: width .6s ease; box-shadow: 0 0 14px var(--accent-soft); }
.rjp-bar .tick { position: absolute; top: 0; bottom: 0; width: 1px; background: rgba(0,0,0,.65); }
.rjp-bar-meta { display: flex; justify-content: space-between; margin-top: 7px; font-size: 10.5px; font-weight: 600; color: var(--rj-dim); }
.rjp-bar-meta b { color: #fff; }
.rjp-note { margin-top: 10px; display: flex; align-items: center; gap: 8px; background: var(--accent-soft); border: 1px solid var(--accent-line); border-radius: 10px; padding: 8px 10px; font-size: 10.5px; color: #E5E7EB; line-height: 1.35; }
.rjp-note i { color: var(--accent); font-size: 14px; }

/* Ladder */
.rjp-row { display: grid; grid-template-columns: 1fr auto; gap: 4px 10px; align-items: center; padding: 9px 0; border-bottom: 1px solid rgba(255,255,255,.05); }
.rjp-row:last-of-type { border-bottom: none; }
.rjp-rank { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: #fff; }
.rjp-rank em { font-style: normal; font-size: 16px; width: 20px; text-align: center; }
.rjp-rank i.d { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); margin: 0 7px; }
.rjp-rank small { font-size: 9.5px; font-weight: 600; color: var(--rj-mute); margin-left: 2px; }
.rjp-each { font-size: 14px; font-weight: 900; color: var(--accent); text-align: right; }
.rjp-each small { font-size: 9px; color: var(--rj-mute); font-weight: 700; }
.rjp-rbar { grid-column: 1; height: 4px; background: rgba(255,255,255,.06); border-radius: 4px; overflow: hidden; }
.rjp-rbar i { display: block; height: 100%; background: var(--accent-grad); border-radius: 4px; }
.rjp-sub { grid-column: 2; text-align: right; font-size: 9.5px; color: var(--rj-dim); font-weight: 600; }
.rjp-total { margin-top: 8px; padding: 10px 12px; border-radius: 11px; background: rgba(0,255,136,.07); border: 1px solid rgba(0,255,136,.25); display: flex; justify-content: space-between; align-items: center; }
.rjp-total span { font-size: 11px; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: .5px; }
.rjp-total b { font-size: 17px; font-weight: 900; color: var(--rj-green); }

/* Join card */
.rjp-join { border-color: var(--accent-line); background: linear-gradient(170deg, #0F0E0B 0%, #08090C 100%); position: relative; overflow: hidden; }
.rjp-join::before { content: ''; position: absolute; left: 0; right: 0; top: 0; height: 3px; background: var(--accent-grad); }
.rjp-ticket { display: flex; align-items: center; justify-content: space-between; background: #000; border: 1px dashed var(--accent-line); border-radius: 13px; padding: 11px 13px; margin-bottom: 12px; position: relative; }
.rjp-ticket::before, .rjp-ticket::after { content: ''; position: absolute; top: 50%; width: 12px; height: 12px; margin-top: -6px; border-radius: 50%; background: #0B0B0E; border: 1px solid var(--accent-line); }
.rjp-ticket::before { left: -7px; } .rjp-ticket::after { right: -7px; }
.rjp-ticket span { font-size: 9.5px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--rj-dim); display: block; }
.rjp-ticket b { font-size: 28px; font-weight: 900; color: #fff; line-height: 1.1; }
.rjp-ticket b small { font-size: 11px; color: var(--rj-mute); }
.rjp-ticket i { font-size: 26px; color: var(--accent); opacity: .9; }
.rjp-elig { display: flex; gap: 10px; border-radius: 12px; padding: 10px 12px; margin-bottom: 12px; font-size: 11px; line-height: 1.4; }
.rjp-elig i { font-size: 16px; margin-top: 1px; }
.rjp-elig.ok { background: rgba(0,255,136,.08); border: 1px solid rgba(0,255,136,.28); color: #B9F6D9; }
.rjp-elig.ok i { color: var(--rj-green); }
.rjp-elig.no { background: rgba(239,68,68,.09); border: 1px solid rgba(239,68,68,.32); color: #FECACA; }
.rjp-elig.no i { color: #F87171; }
.rjp-elig b { color: #fff; }
.rjp-stk { margin-top: 7px; }
.rjp-stk .tr { height: 6px; background: rgba(255,255,255,.1); border-radius: 6px; overflow: hidden; }
.rjp-stk .tr i { display: block; height: 100%; background: linear-gradient(90deg,#FF9A9A,#EF4444); border-radius: 6px; }
.rjp-stk small { display: flex; justify-content: space-between; margin-top: 4px; font-size: 9.5px; color: #FCA5A5; }
.rjp-facts { margin-bottom: 12px; }
.rjp-fact { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid rgba(255,255,255,.05); font-size: 11px; color: var(--rj-dim); font-weight: 600; }
.rjp-fact:last-child { border-bottom: none; }
.rjp-fact b { color: #fff; font-weight: 800; }
.rjp-buy { width: 100%; height: 46px; border-radius: 13px; font-size: 13.5px; letter-spacing: .3px; }
.rjp-buy.accent { background: var(--accent-grad); color: #150E02; box-shadow: 0 8px 22px rgba(0,0,0,.55); }
.rjp-secure { margin-top: 10px; font-size: 10px; color: var(--rj-mute); text-align: center; line-height: 1.4; }
.rjp-secure i { color: var(--rj-green); }
.rjp-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,.06); }
.rjp-chips > span:first-child { width: 100%; font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--rj-dim); }
.rjp-chip { background: var(--accent-soft); border: 1px solid var(--accent-line); color: var(--accent); font-size: 11px; font-weight: 800; padding: 3px 9px; border-radius: 20px; }

/* Activity list */
.rjp-act { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-bottom: 1px solid rgba(255,255,255,.045); }
.rjp-act:last-child { border-bottom: none; }
.rjp-av { width: 30px; height: 30px; border-radius: 50%; background: var(--accent-soft); border: 1px solid var(--accent-line); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
.rjp-act div { flex: 1; min-width: 0; }
.rjp-act b { display: block; font-size: 11.5px; color: #fff; font-family: monospace; }
.rjp-act small { font-size: 10px; color: var(--rj-mute); }
.rjp-act em { font-style: normal; font-size: 11px; font-weight: 800; color: var(--accent); }

.rjp-rule { display: flex; gap: 10px; padding: 9px 14px; font-size: 11.5px; color: #CBD5E1; line-height: 1.4; border-bottom: 1px solid rgba(255,255,255,.04); }
.rjp-rule:last-child { border-bottom: none; }
.rjp-rule i { color: var(--accent); margin-top: 2px; width: 14px; text-align: center; }

.rjp-join { order: 1; }
.rjp-progress { order: 2; }
.rjp-ladder { order: 3; }
.rjp-tabs { order: 4; }

@media (min-width: 900px) {
    .rjp-layout { grid-template-columns: 1fr 360px; align-items: start; }
    .rjp-join { grid-column: 2; grid-row: 1 / span 4; position: sticky; top: 8px; }
    .rjp-progress, .rjp-ladder, .rjp-tabs { grid-column: 1; }
}
</style>

<!-- ======================= POOL HERO ======================= -->
<section class="rjp-hero t{{ $pool->pool_tier }}">
    <div class="rjp-hero-top">
        <span class="rjx-tier"><i class="fas {{ $tierIcons[$pool->pool_tier] ?? 'fa-trophy' }}"></i> Pool {{ $pool->pool_tier }}</span>
        <span class="rjx-live"><span class="rjx-dot"></span> Live &bull; Round #{{ $activeRound->round_number }}</span>
    </div>

    <div class="rjp-title-row">
        <div class="rjp-medal"><i class="fas {{ $tierIcons[$pool->pool_tier] ?? 'fa-trophy' }}"></i></div>
        <div>
            <h1>{{ $shortName }}</h1>
            <p>Entry <b>${{ number_format($pool->ticket_price, 0) }} USDT</b> &bull; 500 members &bull; instant draw</p>
        </div>
    </div>

    <div class="rjp-stats">
        <div class="rjp-stat prize"><span>Total Prize</span><b>${{ number_format($pool->total_prize_pool, 0) }}</b></div>
        <div class="rjp-stat first"><span>1st Prize</span><b>${{ number_format($pz['rank_1'] ?? 0, 0) }}</b></div>
        <div class="rjp-stat"><span>Winners</span><b>50</b></div>
    </div>
</section>

<div class="rjp-layout t{{ $pool->pool_tier }}">

    <!-- ======================= JOIN CARD ======================= -->
    <div class="rjp-card rjp-join">
        <div class="rjp-card-h">
            <h2><i class="fas fa-ticket"></i> Join This Pool</h2>
            <small>Round #{{ $activeRound->round_number }}</small>
        </div>

        <div class="rjp-ticket">
            <div>
                <span>Ticket Price</span>
                <b>${{ number_format($pool->ticket_price, 0) }} <small>USDT</small></b>
            </div>
            <i class="fas fa-ticket"></i>
        </div>

        @if($eligibility['is_eligible'])
            <div class="rjp-elig ok">
                <i class="fas fa-circle-check"></i>
                <div><b>You're eligible.</b> Active staking of <b>${{ number_format($eligibility['staked_amount'], 2) }}</b> meets the $100 requirement.</div>
            </div>
        @else
            <div class="rjp-elig no">
                <i class="fas fa-lock"></i>
                <div>
                    <b>$100+ active staking required</b> to buy tickets. Your active staking: <b>${{ number_format($eligibility['staked_amount'], 2) }}</b>.
                    <div class="rjp-stk">
                        <div class="tr"><i style="width: {{ max(3, $stakePct) }}%"></i></div>
                        <small><span>${{ number_format($eligibility['staked_amount'], 0) }}</span><span>$100 needed</span></small>
                    </div>
                </div>
            </div>
        @endif

        <div class="rjp-facts">
            <div class="rjp-fact"><span>Slots left</span><b id="factLeft">{{ $slotsRemaining }}</b></div>
            <div class="rjp-fact"><span>Your tickets (this round)</span><b id="factMine">{{ $myTicketsInRound->count() }}</b></div>
            <div class="rjp-fact"><span>Your tickets (all rounds)</span><b>{{ $myTotalTicketsAllRounds }}</b></div>
        </div>

        @if($eligibility['is_eligible'])
            <button type="button" id="btnBuy" class="rjx-btn rjp-buy accent" onclick="enterPool({{ $pool->pool_tier }}, {{ (float) $pool->ticket_price }}, {{ $activeRound->id }})">
                <i class="fas fa-ticket"></i> Buy Ticket &bull; ${{ number_format($pool->ticket_price, 0) }} USDT
            </button>
        @else
            <a href="{{ url('/User/Stake') }}" class="rjx-btn red rjp-buy"><i class="fas fa-plus"></i> Stake $100+ to Unlock</a>
        @endif

        <div class="rjp-secure"><i class="fas fa-shield-halved"></i> Secured by smart contract. Winners are credited instantly to your Royal Wallet.</div>

        @if($myTicketsInRound->count() > 0)
        <div class="rjp-chips">
            <span>Your tickets in this round</span>
            @foreach($myTicketsInRound as $t)
                <span class="rjp-chip">#{{ $t->ticket_number }}</span>
            @endforeach
        </div>
        @endif
    </div>

    <!-- ======================= PROGRESS ======================= -->
    <div class="rjp-card rjp-progress">
        <div class="rjp-card-h">
            <h2><i class="fas fa-chart-simple"></i> Round Progress</h2>
            <small><span class="rjx-dot"></span> Auto-refreshing</small>
        </div>
        <div class="rjp-big">
            <b><span id="slotsFilled">{{ $slotsFilled }}</span> <small>/ 500</small></b>
            <span id="pctText">{{ $percentFilled }}%</span>
        </div>
        <div class="rjp-bar">
            <div class="rjp-bar-fill" id="barFill" style="width: {{ max(1.5, $percentFilled) }}%"></div>
            <span class="tick" style="left:25%"></span><span class="tick" style="left:50%"></span><span class="tick" style="left:75%"></span>
        </div>
        <div class="rjp-bar-meta">
            <span><b id="slotsLeft">{{ $slotsRemaining }}</b> slots remaining</span>
            <span>Draw at 500</span>
        </div>
        <div class="rjp-note">
            <i class="fas fa-bolt"></i>
            <div>The moment the 500th ticket is bought, <b>50 winners are drawn instantly</b> and a fresh round starts automatically.</div>
        </div>
    </div>

    <!-- ======================= PRIZE LADDER ======================= -->
    <div class="rjp-card rjp-ladder">
        <div class="rjp-card-h">
            <h2><i class="fas fa-trophy"></i> Prize Structure</h2>
            <small>50 winners</small>
        </div>

        @foreach($ladder as $row)
        <div class="rjp-row">
            <div class="rjp-rank">
                @if($row['medal']) <em>{{ $row['medal'] }}</em> @else <i class="d"></i> @endif
                {{ $row['label'] }} <small>{{ $row['count'] > 1 ? '· ' . $row['count'] . ' winners' : '' }}</small>
            </div>
            <div class="rjp-each">${{ number_format($row['each'], 0) }} <small>{{ $row['count'] > 1 ? 'each' : '' }}</small></div>
            <div class="rjp-rbar"><i style="width: {{ max(4, round(($row['each'] / $topPrize) * 100)) }}%"></i></div>
            <div class="rjp-sub">{{ $row['count'] > 1 ? '$' . number_format($row['count'] * $row['each'], 0) . ' total' : '' }}</div>
        </div>
        @endforeach

        <div class="rjp-total"><span>Total Prize Pool</span><b>${{ number_format($ladderTotal, 0) }}</b></div>
    </div>

    <!-- ======================= TABS ======================= -->
    <div class="rjx-panel rjp-tabs">
        <div class="rjx-tabs">
            <button class="rjx-tab active" onclick="switchTab('joins', this)"><i class="fas fa-bolt"></i> Live Joins</button>
            <button class="rjx-tab" onclick="switchTab('winners', this)"><i class="fas fa-trophy"></i> Winners</button>
            <button class="rjx-tab" onclick="switchTab('rounds', this)"><i class="fas fa-clock-rotate-left"></i> Rounds</button>
            <button class="rjx-tab" onclick="switchTab('rules', this)"><i class="fas fa-circle-info"></i> Rules</button>
        </div>

        <div id="tab-joins" class="rjx-tabpane">
            @forelse($recentJoins as $j)
                @php $w = $j->wallet_address; @endphp
                <div class="rjp-act">
                    <div class="rjp-av"><i class="fas fa-user"></i></div>
                    <div>
                        <b>{{ $w ? substr($w, 0, 6) . '...' . substr($w, -4) : 'Member' }}</b>
                        <small>{{ $j->created_at->diffForHumans() }}</small>
                    </div>
                    <em>Ticket #{{ $j->ticket_number }}</em>
                </div>
            @empty
                <div class="rjx-empty"><i class="fas fa-ticket"></i>No tickets yet in this round. Be the first to join!</div>
            @endforelse
        </div>

        <div id="tab-winners" class="rjx-tabpane" style="display:none;">
            @if($pastWinners->count() > 0)
            <div class="rjx-scroll">
                <table class="rjx-table">
                    <thead><tr><th>Rank</th><th>Wallet</th><th>Ticket</th><th>Round</th><th>Prize</th></tr></thead>
                    <tbody>
                    @foreach($pastWinners as $w)
                        <tr>
                            <td>
                                @if($w->rank == 1) <b style="color:#FFD700;">🥇 1st</b>
                                @elseif($w->rank == 2) <b style="color:#E2E8F0;">🥈 2nd</b>
                                @elseif($w->rank == 3) <b style="color:#CD7F32;">🥉 3rd</b>
                                @else <b>#{{ $w->rank }}</b> @endif
                            </td>
                            <td style="font-family:monospace;">{{ substr($w->wallet_address, 0, 6) }}...{{ substr($w->wallet_address, -4) }}</td>
                            <td><b style="color:#FFD700;">#{{ $w->ticket_number }}</b></td>
                            <td>R#{{ $w->round->round_number ?? '-' }}</td>
                            <td style="color:#00FF88;font-weight:800;">+${{ number_format($w->prize_amount, 0) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="rjx-empty"><i class="fas fa-trophy"></i>No draws yet. Winners will appear here instantly after the first draw.</div>
            @endif
        </div>

        <div id="tab-rounds" class="rjx-tabpane" style="display:none;">
            @if($completedRounds->count() > 0)
            <div class="rjx-scroll">
                <table class="rjx-table">
                    <thead><tr><th>Round</th><th>Members</th><th>Collected</th><th>Drawn</th></tr></thead>
                    <tbody>
                    @foreach($completedRounds as $rnd)
                        <tr>
                            <td><b style="color:#FFD700;">R#{{ $rnd->round_number }}</b></td>
                            <td>500 / 500</td>
                            <td style="color:#00FF88;font-weight:700;">${{ number_format($rnd->total_collected, 2) }}</td>
                            <td style="color:#8A94A6;font-size:10px;">{{ $rnd->drawn_at ? $rnd->drawn_at->format('d M, h:i A') : '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="rjx-empty"><i class="fas fa-clock-rotate-left"></i>Round #{{ $activeRound->round_number }} is currently collecting entries.</div>
            @endif
        </div>

        <div id="tab-rules" class="rjx-tabpane" style="display:none;">
            <div class="rjp-rule"><i class="fas fa-user-lock"></i><span>Only members with an <b>active staking of $100 USDT or above</b> can buy tickets.</span></div>
            <div class="rjp-rule"><i class="fas fa-users"></i><span>Each round has <b>500 slots</b>. You can buy multiple tickets to improve your chances.</span></div>
            <div class="rjp-rule"><i class="fas fa-bolt"></i><span>No waiting &amp; no cron. The draw runs <b>instantly</b> when the 500th ticket is bought.</span></div>
            <div class="rjp-rule"><i class="fas fa-trophy"></i><span><b>50 winners</b> per round, ranked 1 to 50, each with a fixed prize.</span></div>
            <div class="rjp-rule"><i class="fas fa-wallet"></i><span>Prizes are credited to your isolated <b>Royal Wallet</b> and can be claimed to your Web3 wallet anytime.</span></div>
            <div class="rjp-rule"><i class="fas fa-shield-halved"></i><span>Royal Jackpot is fully separate from staking income, ROI, capping and team income.</span></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/ethers@6.13.2/dist/ethers.umd.min.js"></script>
<script>
const TIER           = {{ $pool->pool_tier }};
const CONTRACT_DEPOSIT = "{{ $contracts['depositContract'] }}";
const CONTRACT_USDT    = "{{ $contracts['usdtToken'] }}";
const IS_DEMO_MODE     = {{ $contracts['demoMode'] ? 'true' : 'false' }};
const CSRF_TOKEN       = "{{ csrf_token() }}";

function toast(icon, title, text) {
    if (window.Swal) {
        return Swal.fire({ icon: icon, title: title, text: text, background: '#0A0C11', color: '#fff', confirmButtonColor: '#F5A623' });
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

async function enterPool(tier, price, roundId) {
    const btn = document.getElementById('btnBuy');
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    let txHash = null;
    let walletAddress = null;

    try {
        if (window.ethereum) {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();
            walletAddress = await signer.getAddress();

            if (!IS_DEMO_MODE) {
                const usdtAbi = [
                    "function approve(address spender, uint256 amount) public returns (bool)",
                    "function allowance(address owner, address spender) public view returns (uint256)"
                ];
                const depositAbi = ["function depositJackpot(uint256 poolTier, uint256 roundId, uint256 amountUsdt) external"];

                const usdt = new ethers.Contract(CONTRACT_USDT, usdtAbi, signer);
                const amountWei = ethers.parseUnits(price.toString(), 18);

                const allowance = await usdt.allowance(walletAddress, CONTRACT_DEPOSIT);
                if (allowance < amountWei) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Step 1/2: Approve USDT in wallet...';
                    const appTx = await usdt.approve(CONTRACT_DEPOSIT, amountWei);
                    await appTx.wait(1);
                }

                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Step 2/2: Confirm Ticket in wallet...';
                const deposit = new ethers.Contract(CONTRACT_DEPOSIT, depositAbi, signer);
                const tx = await deposit.depositJackpot(tier, roundId, amountWei);
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming on BSC...';
                const receipt = await tx.wait(1);
                txHash = (receipt && receipt.hash) ? receipt.hash : tx.hash;
            } else {
                txHash = 'demo_' + Date.now() + '_' + Math.floor(Math.random() * 9000 + 1000);
            }
        } else {
            if (!IS_DEMO_MODE) {
                await toast('warning', 'Wallet Required', 'Please connect MetaMask or TrustWallet on BSC to purchase tickets.');
                btn.disabled = false;
                btn.innerHTML = original;
                return;
            }
            txHash = 'web_' + Date.now() + '_' + Math.floor(Math.random() * 9000 + 1000);
        }

        const res = await fetch("{{ url('/User/RoyalJackpot/Enter') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: JSON.stringify({ pool_tier: tier, wallet_address: walletAddress, tx_hash: txHash })
        });

        const data = await res.json();
        if (data.status === 'success') {
            await toast('success', 'Ticket Purchased!', data.message);
            location.reload();
        } else {
            await toast('error', 'Could not join', data.message || 'Failed to enter Royal Jackpot.');
            btn.disabled = false;
            btn.innerHTML = original;
        }
    } catch (err) {
        console.error('Enter Jackpot error:', err);
        await toast('error', 'Transaction failed', err.message || 'Transaction cancelled or failed.');
        btn.disabled = false;
        btn.innerHTML = original;
    }
}

async function refreshStats() {
    try {
        const res = await fetch("{{ url('/User/RoyalJackpot/Stats') }}");
        const json = await res.json();
        if (json.status === 'success' && json.pools && json.pools[TIER]) {
            const p = json.pools[TIER];
            document.getElementById('slotsFilled').innerText = p.slots_filled;
            document.getElementById('slotsLeft').innerText = p.slots_remaining;
            document.getElementById('factLeft').innerText = p.slots_remaining;
            document.getElementById('pctText').innerText = p.percentage + '%';
            document.getElementById('barFill').style.width = Math.max(1.5, p.percentage) + '%';
        }
    } catch (e) {}
}
setInterval(refreshStats, 8000);
</script>
@endsection
