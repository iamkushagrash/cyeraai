@extends('layouts.user-mecha')

@section('title', 'Mining & DEX Audit History - Cyera AI')
@section('page-title', 'Mining & DEX History')
@section('page-icon', 'fas fa-receipt')

@section('page-actions')
<a href="{{ url('/User/Dashboard') }}" class="btn-solid-gold-claim" style="height: 30px; padding: 0 12px; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; border-radius: 6px; font-family: 'Inter', sans-serif;">
    <i class="fas fa-arrow-left"></i> Dashboard
</a>
<a href="https://bscscan.com/address/{{ $contracts['miningEngine'] }}" target="_blank" class="mecha-btn-outline" style="height: 30px; padding: 0 10px; font-size: 0.72rem; border-color: rgba(245, 166, 35, 0.4); color: #FFD700; font-family: 'Inter', sans-serif;">
    <i class="fas fa-file-contract"></i> Contract
</a>
@endsection

@section('content')
@php
    $unmined = (float)($unminedUsdt ?? 0);
    $price = (float)($livePrice ?? 1.0);
    if ($price <= 0) $price = 1.0;
    $estCai = (float)($estimatedCaiToMine ?? ($price > 0 ? $unmined / $price : 0));
    $holding = (float)($holdingCai ?? 0);
    $holdingUsdt = (float)($holdingValueUsdt ?? ($holding * $price));
    $capping = (float)($remainingCapping ?? 0);
@endphp

<!-- Top Mini Stats Grid -->
<div class="mecha-stat-grid-2" style="margin-bottom: 16px;">
    <!-- 1. Protocol CAI Balance -->
    <div class="mecha-metric-box" style="border-color: rgba(245, 166, 35, 0.35); background: linear-gradient(180deg, rgba(8, 10, 16, 0.95) 0%, rgba(3, 4, 8, 0.98) 100%);">
        <div class="mecha-metric-lbl">
            <span style="color: #FFD700; font-family: 'Inter', sans-serif;">PROTOCOL CAI HOLDINGS</span>
            <i class="fas fa-coins" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val gold" style="font-size: 1.20rem; line-height: 1.2; font-family: 'Space Mono', monospace; color: #FFD700;">
            {{ ($holding < 1 && $holding > 0) ? number_format($holding, 4) : number_format($holding, 2) }} <small style="font-size: 11px; color: #FFFFFF;">CAI</small>
        </div>
        <div class="mecha-metric-sub" style="color: #00FF88; font-weight: 700; font-family: 'Space Mono', monospace;">
            ≈ ${{ number_format($holdingUsdt, 2) }} USDT
        </div>
    </div>

    <!-- 2. Active Capping Limit -->
    <div class="mecha-metric-box" style="border-color: rgba(245, 166, 35, 0.35); background: linear-gradient(180deg, rgba(8, 10, 16, 0.95) 0%, rgba(3, 4, 8, 0.98) 100%);">
        <div class="mecha-metric-lbl">
            <span style="color: #FFD700; font-family: 'Inter', sans-serif;">ACTIVE CAPPING LIMIT</span>
            <i class="fas fa-shield-halved" style="color: #FFD700;"></i>
        </div>
        <div class="mecha-metric-val green" style="font-size: 1.20rem; line-height: 1.2; font-family: 'Space Mono', monospace; color: #00FF88;">
            ${{ number_format($capping, 2) }} <small style="font-size: 11px; color: #A7F3D0;">USDT</small>
        </div>
        <div class="mecha-metric-sub" style="color: #FFD700; font-family: 'Space Mono', monospace;">
            Live DEX: ${{ number_format($price, 6) }} / CAI
        </div>
    </div>
</div>

<!-- ============================================================
     2-STEP DIRECT MINING & CUSTOM DEX LIQUIDATION ACTIONS
     ============================================================ -->
<div class="mecha-hud-card" style="padding: 14px 16px; border-color: rgba(245, 166, 35, 0.35); background: linear-gradient(180deg, rgba(8, 10, 16, 0.95) 0%, rgba(3, 4, 8, 0.98) 100%); border-radius: 14px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 215, 0, 0.08); margin-bottom: 16px; font-family: 'Inter', sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(245, 166, 35, 0.15);">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-bolt" style="color: #FFD700; font-size: 14px;"></i>
            <span style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.3px;">CAI MINING &amp; DEX SWAP CONSOLE</span>
        </div>
        <span style="font-size: 0.65rem; color: #00FF88; font-family: 'Space Mono', monospace; font-weight: 700; background: rgba(0, 255, 136, 0.1); padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(0, 255, 136, 0.3);">LIVE BSC POOL</span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 10px;">
        <!-- STEP 1: MINE UNMINED ROI -->
        <div style="background: rgba(245, 166, 35, 0.03); border: 1px solid rgba(245, 166, 35, 0.22); border-radius: 9px; padding: 10px 12px; display: flex; flex-direction: column; justify-content: space-between; gap: 8px;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <span style="font-size: 0.70rem; font-weight: 800; color: #FFD700; text-transform: uppercase;">STEP 1: MINE TO PROTOCOL</span>
                    <span style="font-size: 0.58rem; color: #00FF88; background: rgba(0, 255, 136, 0.1); padding: 1px 4px; border-radius: 3px; border: 1px solid rgba(0, 255, 136, 0.3); font-weight: 700;">0% Cap Minus</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px;">
                    <span style="font-size: 0.68rem; color: #8E99A8;">Unmined ROI:</span>
                    <strong style="font-size: 0.88rem; color: #FFD700; font-family: 'Space Mono', monospace;" id="pageUnminedUsdtTxt">${{ number_format($unmined, 2) }} USDT</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: baseline;">
                    <span style="font-size: 0.68rem; color: #8E99A8;">Yield in CAI:</span>
                    <strong style="font-size: 0.76rem; color: #FFE082; font-family: 'Space Mono', monospace;">≈ {{ number_format($estCai, 4) }} CAI</strong>
                </div>
            </div>
            <button type="button" onclick="executeDashAutoMine()" id="btnPageMineAll"
                style="width: 100%; height: 34px; background: linear-gradient(135deg, #FFD700 0%, #F5A623 100%); color: #000000; font-weight: 800; font-size: 0.74rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; box-shadow: 0 3px 10px rgba(245, 166, 35, 0.25); text-transform: uppercase;"
                @if($unmined <= 0) disabled style="opacity: 0.4; cursor: not-allowed; width: 100%; height: 34px; background: rgba(255, 255, 255, 0.05); color: #666; border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.74rem; border-radius: 6px;" @endif>
                <span id="btnPageMineAllTxt">MINE ALL (${{ number_format($unmined, 2) }})</span>
            </button>
        </div>

        <!-- STEP 2: SELL CAI ON PANCAKESWAP (CUSTOM / MAX) -->
        <div style="background: rgba(245, 166, 35, 0.03); border: 1px solid rgba(245, 166, 35, 0.22); border-radius: 9px; padding: 10px 12px; display: flex; flex-direction: column; justify-content: space-between; gap: 8px;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <span style="font-size: 0.70rem; font-weight: 800; color: #FFD700; text-transform: uppercase;">STEP 2: SELL ON DEX</span>
                    <span style="font-size: 0.58rem; color: #FFD700; background: rgba(255, 215, 0, 0.1); padding: 1px 4px; border-radius: 3px; border: 1px solid rgba(255, 215, 0, 0.3); font-weight: 700;">Custom Amount</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px;">
                    <span style="font-size: 0.68rem; color: #8E99A8;">Holding Balance:</span>
                    <strong style="font-size: 0.88rem; color: #FFD700; font-family: 'Space Mono', monospace;">{{ number_format($holding, 4) }} CAI <small style="color:#8E99A8;">(${{ number_format($holdingUsdt, 2) }})</small></strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: baseline;">
                    <span style="font-size: 0.68rem; color: #8E99A8;">Max Sellable:</span>
                    <strong style="font-size: 0.76rem; color: #00FF88; font-family: 'Space Mono', monospace;">${{ number_format((float)($maxSellableUsdt ?? 0), 2) }} USDT ({{ number_format((float)($maxSellableCai ?? 0), 2) }} CAI)</strong>
                </div>
            </div>
            <button type="button" onclick="openCustomSellModal()" id="btnPageSellAll"
                style="width: 100%; height: 34px; background: linear-gradient(135deg, #F5A623 0%, #D97706 100%); color: #000000; font-weight: 800; font-size: 0.74rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; box-shadow: 0 3px 10px rgba(245, 166, 35, 0.25); text-transform: uppercase;"
                @if((float)($maxSellableUsdt ?? 0) <= 0) disabled style="opacity: 0.4; cursor: not-allowed; width: 100%; height: 34px; background: rgba(255, 255, 255, 0.05); color: #666; border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.74rem; border-radius: 6px;" @endif>
                <span id="btnPageSellAllTxt">SELL CAI ON DEX (${{ number_format((float)($maxSellableUsdt ?? 0), 2) }})</span>
            </button>
        </div>
    </div>
</div>

<!-- ============================================================
     AUDIT LEDGER TABLE & RESPONSIVE MOBILE CARDS
     ============================================================ -->
<div class="mecha-hud-card" style="padding: 14px 16px; border-color: rgba(245, 166, 35, 0.35); background: linear-gradient(180deg, rgba(8, 10, 16, 0.95) 0%, rgba(3, 4, 8, 0.98) 100%); border-radius: 14px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 215, 0, 0.08); font-family: 'Inter', sans-serif;">
    <div class="mecha-card-header" style="margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid rgba(245, 166, 35, 0.15);">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-receipt" style="color: #FFD700; font-size: 15px;"></i>
            <div>
                <h2 class="mecha-card-title" style="font-size: 0.85rem; font-weight: 800; letter-spacing: 0.4px; color: #FFFFFF; font-family: 'Inter', sans-serif;">MINING &amp; DEX AUDIT LEDGER</h2>
                <div class="mecha-card-subtitle" style="font-size: 0.68rem; color: #8C9BAE; font-family: 'Inter', sans-serif;">Complete audit trail of all 1-click mining conversions and DEX sell transactions</div>
            </div>
        </div>
        <span class="mecha-card-badge" style="font-size: 0.60rem; padding: 2px 7px; color: #FFD700; border-color: rgba(245, 166, 35, 0.35); background: rgba(245, 166, 35, 0.1); font-family: 'Space Mono', monospace;">IMMUTABLE</span>
    </div>

    <!-- Desktop Table View -->
    <div class="ledger-desktop-wrap" style="overflow-x: auto; -webkit-overflow-scrolling: touch; border: 1px solid rgba(245, 166, 35, 0.15); border-radius: 10px; background: rgba(5, 7, 12, 0.7);">
        <table style="width: 100%; border-collapse: collapse; min-width: 820px; font-size: 11px; font-family: 'Inter', sans-serif;">
            <thead>
                <tr style="background: rgba(15, 23, 42, 0.85); border-bottom: 1px solid rgba(245, 166, 35, 0.2);">
                    <th style="padding: 10px 12px; text-align: left; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">DATE &amp; TIME</th>
                    <th style="padding: 10px 12px; text-align: left; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">ACTION</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">CAI AMOUNT</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">RATE / PRICE</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">USDT AMOUNT</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">CAP BEFORE</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">CAP DEDUCTED</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">CAP REMAINING</th>
                    <th style="padding: 10px 12px; text-align: center; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">TX HASH</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ledgers as $entry)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04); transition: background 0.15s ease;" onmouseover="this.style.background='rgba(255,215,0,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 10px 12px; font-family: 'Space Mono', monospace; color: #8C9BAE; white-space: nowrap;">
                        {{ $entry->created_at ? \Carbon\Carbon::parse($entry->created_at)->format('d M Y, H:i') : '--' }}
                    </td>
                    <td style="padding: 10px 12px; white-space: nowrap;">
                        @if($entry->type == 'mine')
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 7px; border-radius: 5px; font-size: 9.5px; font-weight: 800; background: rgba(255, 215, 0, 0.12); color: #FFD700; border: 1px solid rgba(255, 215, 0, 0.35);">
                                <i class="fas fa-bolt"></i> MINED
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 7px; border-radius: 5px; font-size: 9.5px; font-weight: 800; background: rgba(245, 166, 35, 0.15); color: #F5A623; border: 1px solid rgba(245, 166, 35, 0.4);">
                                <i class="fas fa-arrow-right-arrow-left"></i> DEX SOLD
                            </span>
                        @endif
                    </td>
                    <td style="padding: 10px 12px; text-align: right; font-weight: 700; color: #FFD700; font-family: 'Space Mono', monospace; white-space: nowrap;">
                        {{ number_format((float)$entry->cai_amount, 4) }} CAI
                    </td>
                    <td style="padding: 10px 12px; text-align: right; font-family: 'Space Mono', monospace; color: #FFD700; font-weight: 700; white-space: nowrap;">
                        ${{ number_format((float)$entry->cai_price, 6) }}
                    </td>
                    <td style="padding: 10px 12px; text-align: right; font-weight: 700; color: #FFFFFF; font-family: 'Space Mono', monospace; white-space: nowrap;">
                        ${{ number_format((float)$entry->usdt_amount, 2) }}
                        @if($entry->type == 'sell')
                            <div style="font-size: 8.5px; color: #8C9BAE; font-weight: normal;">(90% Net)</div>
                        @endif
                    </td>
                    <td style="padding: 10px 12px; text-align: right; font-family: 'Space Mono', monospace; color: #8C9BAE; white-space: nowrap;">
                        ${{ number_format((float)$entry->capping_before, 2) }}
                    </td>
                    <td style="padding: 10px 12px; text-align: right; font-family: 'Space Mono', monospace; font-weight: 700; color: {{ (float)$entry->capping_deducted > 0 ? '#F5A623' : '#00FF88' }}; white-space: nowrap;">
                        {{ (float)$entry->capping_deducted > 0 ? '-$' . number_format((float)$entry->capping_deducted, 2) : '$0.00' }}
                    </td>
                    <td style="padding: 10px 12px; text-align: right; font-family: 'Space Mono', monospace; font-weight: 800; color: #00FF88; white-space: nowrap;">
                        ${{ number_format((float)$entry->capping_after, 2) }}
                    </td>
                    <td style="padding: 10px 12px; text-align: center; white-space: nowrap;">
                        @if($entry->tx_hash)
                            <a href="https://bscscan.com/tx/{{ $entry->tx_hash }}" target="_blank" style="color: #FFD700; font-family: 'Space Mono', monospace; font-size: 10.5px; text-decoration: underline;">
                                {{ substr($entry->tx_hash, 0, 6) }}...{{ substr($entry->tx_hash, -4) }} <i class="fas fa-arrow-up-right-from-square" style="font-size: 8px;"></i>
                            </a>
                        @else
                            <span style="color: #8C9BAE; font-size: 10px; font-family: 'Inter', sans-serif;">Protocol</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 28px 12px; color: #8C9BAE;">
                        <i class="fas fa-receipt" style="font-size: 26px; margin-bottom: 8px; display: block; opacity: 0.35; color: #FFD700;"></i>
                        <span style="font-size: 11px; font-family: 'Inter', sans-serif;">No mining or DEX liquidation transactions recorded yet.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="ledger-mobile-wrap" style="display: none; flex-direction: column; gap: 10px; font-family: 'Inter', sans-serif;">
        @forelse($ledgers as $entry)
        <div style="background: linear-gradient(160deg, #0A0D15 0%, #05070B 100%); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 12px; padding: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: {{ $entry->type == 'mine' ? '#FFD700' : '#F5A623' }};"></div>
            
            <!-- Header Row -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px solid rgba(245, 166, 35, 0.12);">
                <div style="display: flex; align-items: center; gap: 6px;">
                    @if($entry->type == 'mine')
                        <span style="padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 800; background: rgba(255, 215, 0, 0.12); color: #FFD700; border: 1px solid rgba(255, 215, 0, 0.35);">
                            <i class="fas fa-bolt"></i> MINED
                        </span>
                    @else
                        <span style="padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 800; background: rgba(245, 166, 35, 0.15); color: #F5A623; border: 1px solid rgba(245, 166, 35, 0.4);">
                            <i class="fas fa-arrow-right-arrow-left"></i> DEX SOLD
                        </span>
                    @endif
                    <span style="font-size: 10px; color: #8C9BAE; font-family: 'Space Mono', monospace;">
                        {{ $entry->created_at ? \Carbon\Carbon::parse($entry->created_at)->format('d M Y, H:i') : '--' }}
                    </span>
                </div>

                @if($entry->tx_hash)
                    <a href="https://bscscan.com/tx/{{ $entry->tx_hash }}" target="_blank" style="color: #FFD700; font-size: 10px; font-family: 'Space Mono', monospace; text-decoration: underline;">
                        BscScan <i class="fas fa-arrow-up-right-from-square" style="font-size: 8px;"></i>
                    </a>
                @else
                    <span style="color: #8C9BAE; font-size: 9.5px; font-family: 'Inter', sans-serif;">Protocol</span>
                @endif
            </div>

            <!-- Main Metric Banner -->
            <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.15); border-radius: 8px; padding: 8px 10px; margin-bottom: 8px;">
                <div>
                    <div style="font-size: 8px; font-weight: 800; color: #8C9BAE; text-transform: uppercase;">Amount</div>
                    <div style="font-size: 13px; font-weight: 800; color: {{ $entry->type == 'mine' ? '#FFD700' : '#F5A623' }}; font-family: 'Space Mono', monospace;">
                        {{ number_format((float)$entry->cai_amount, 4) }} <small style="font-size: 9px; color: #FFFFFF;">CAI</small>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 8px; font-weight: 800; color: #8C9BAE; text-transform: uppercase;">{{ $entry->type == 'mine' ? 'USDT Value' : 'Net Received (90%)' }} (Rate: ${{ number_format((float)$entry->cai_price, 6) }})</div>
                    <div style="font-size: 13px; font-weight: 800; color: #FFFFFF; font-family: 'Space Mono', monospace;">
                        ${{ number_format((float)$entry->usdt_amount, 2) }} <small style="font-size: 9px; color: #00FF88;">USDT</small>
                    </div>
                </div>
            </div>

            <!-- Capping Breakdown Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; font-size: 9.5px; background: rgba(0, 0, 0, 0.4); padding: 6px 8px; border-radius: 6px; border: 1px solid rgba(255, 255, 255, 0.04);">
                <div>
                    <div style="color: #8C9BAE; font-size: 8px;">Cap Before</div>
                    <div style="color: #FFFFFF; font-family: 'Space Mono', monospace; font-weight: 700;">${{ number_format((float)$entry->capping_before, 2) }}</div>
                </div>
                <div style="text-align: center;">
                    <div style="color: #8C9BAE; font-size: 8px;">Deducted</div>
                    <div style="color: {{ (float)$entry->capping_deducted > 0 ? '#F5A623' : '#00FF88' }}; font-family: 'Space Mono', monospace; font-weight: 700;">
                        {{ (float)$entry->capping_deducted > 0 ? '-$' . number_format((float)$entry->capping_deducted, 2) : '$0.00' }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="color: #8C9BAE; font-size: 8px;">Cap After</div>
                    <div style="color: #00FF88; font-family: 'Space Mono', monospace; font-weight: 800;">${{ number_format((float)$entry->capping_after, 2) }}</div>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 24px 12px; color: #8C9BAE; background: rgba(5, 7, 12, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px;">
            <i class="fas fa-receipt" style="font-size: 24px; margin-bottom: 6px; display: block; opacity: 0.35; color: #FFD700;"></i>
            <span style="font-size: 11px;">No mining or DEX liquidation transactions recorded yet.</span>
        </div>
        @endforelse
    </div>

    @if($ledgers->hasPages())
    <div style="margin-top: 14px;">
        {{ $ledgers->links() }}
    </div>
    @endif
</div>

<style>
@media (max-width: 768px) {
    .ledger-desktop-wrap {
        display: none !important;
    }
    .ledger-mobile-wrap {
        display: flex !important;
    }
}
@media (min-width: 769px) {
    .ledger-desktop-wrap {
        display: block !important;
    }
    .ledger-mobile-wrap {
        display: none !important;
    }
}
</style>

<script>
    const PAGE_UNMINED_USDT = {{ (float)($unminedUsdt ?? 0) }};
    const PAGE_LIVE_PRICE = {{ (float)($livePrice ?? 1.0) }};
    const PAGE_HOLDING_CAI = {{ (float)($holdingCai ?? 0) }};
    const PAGE_HOLDING_USDT = {{ (float)($holdingValueUsdt ?? 0) }};
    const PAGE_REMAINING_CAPPING = {{ (float)($remainingCapping ?? 0) }};
    const PAGE_MAX_SELL_USDT = {{ (float)($maxSellableUsdt ?? 0) }};
    const PAGE_MAX_SELL_CAI = {{ (float)($maxSellableCai ?? 0) }};
    const PAGE_MINING_ENGINE = '{{ $contracts['miningEngine'] }}';

    const PAGE_MINING_ABI = [
        "function sellPortfolio(uint256 caiAmount, uint256 minUsdtOut, uint256 nonce, uint256 expiry, bytes calldata signature) external"
    ];

    /* Helper: Resolve Web3 Provider */
    async function resolvePageWeb3Provider() {
        const isMobileDApp = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent) && (window.ethereum || window.trustwallet || window.binance);
        if (isMobileDApp) {
            return window.ethereum || window.trustwallet || window.binance;
        }
        if (window.ethereum) return window.ethereum;
        Swal.fire({
            title: 'NO WEB3 WALLET',
            html: '<div style="font-size: 0.80rem; color: #CBD5E1; text-align: center;">Please connect a Web3 wallet (MetaMask, TrustWallet, Binance Wallet).</div>',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            background: '#080A10',
            customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
        });
        return null;
    }

    /* 1-Click Mine Action */
    async function executeDashAutoMine() {
        const btn = document.getElementById('btnPageMineAll');
        const btnTxt = document.getElementById('btnPageMineAllTxt');

        if (PAGE_UNMINED_USDT <= 0) return;

        try {
            if (btn) btn.disabled = true;
            if (btnTxt) btnTxt.innerText = 'MINING TO PROTOCOL...';

            const resp = await fetch('{{ url("/User/Mining/MineCAI") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const res = await resp.json();
            if (res.status !== 'success') {
                throw new Error(res.message || 'Failed to mine ROI into CAI.');
            }

            await Swal.fire({
                title: 'MINING COMPLETED!',
                html: `
                    <div style="font-size: 0.82rem; color: #CBD5E1; text-align: center; margin-bottom: 8px;">
                        Successfully converted <strong style="color: #00FF88;">$${PAGE_UNMINED_USDT.toFixed(2)} USDT</strong> ROI into <strong style="color: #FFD700;">${res.data.mined_cai} CAI</strong>.
                    </div>
                `,
                confirmButtonText: 'GREAT',
                buttonsStyling: false,
                background: '#080A10',
                customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
            });

            window.location.reload();
        } catch (e) {
            Swal.fire({
                title: 'MINING FAILED',
                html: `<div style="font-size: 0.80rem; color: #EF4444; text-align: center;">${e.message}</div>`,
                confirmButtonText: 'CLOSE',
                buttonsStyling: false,
                background: '#080A10',
                customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
            });
            if (btn) btn.disabled = false;
            if (btnTxt) btnTxt.innerText = 'MINE ALL (${{ number_format($unmined, 2) }})';
        }
    }

    /* Open Interactive Custom CAI Liquidation Modal */
    async function openCustomSellModal() {
        if (PAGE_MAX_SELL_CAI <= 0 || PAGE_MAX_SELL_USDT <= 0) {
            Swal.fire({
                title: 'NO SELLABLE BALANCE',
                html: '<div style="font-size: 0.80rem; color: #94A3B8; text-align: center;">You currently have $0.00 eligible sell balance.</div>',
                confirmButtonText: 'UNDERSTOOD',
                buttonsStyling: false,
                background: '#080A10',
                customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
            });
            return;
        }

        const livePrice = PAGE_LIVE_PRICE > 0 ? PAGE_LIVE_PRICE : 1.0;
        const maxCai = PAGE_MAX_SELL_CAI;
        const defaultCai = maxCai;
        const defaultGrossUsdt = defaultCai * livePrice;
        const defaultFeeUsdt = defaultGrossUsdt * 0.10;
        const defaultNetUsdt = defaultGrossUsdt - defaultFeeUsdt;

        const { value: customCaiAmount, isConfirmed } = await Swal.fire({
            title: 'SWAP CAI FOR USDT ON DEX',
            html: `
                <div style="text-align: left; font-family: 'Inter', sans-serif;">
                    <div style="background: rgba(245, 166, 35, 0.05); border: 1px solid rgba(245, 166, 35, 0.22); border-radius: 10px; padding: 10px 12px; margin-bottom: 14px; font-size: 0.72rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="color: #8C9BAE;">Protocol CAI Holding:</span>
                            <strong style="color: #FFD700; font-family: 'Space Mono', monospace;">${PAGE_HOLDING_CAI.toFixed(4)} CAI (~$${PAGE_HOLDING_USDT.toFixed(2)})</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="color: #8C9BAE;">Active Capping Limit:</span>
                            <strong style="color: #00FF88; font-family: 'Space Mono', monospace;">$${PAGE_REMAINING_CAPPING.toFixed(2)} USDT (Max ${PAGE_MAX_SELL_CAI.toFixed(4)} CAI)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #8C9BAE;">Live DEX Price:</span>
                            <strong style="color: #FFE082; font-family: 'Space Mono', monospace;">$${livePrice.toFixed(6)} / CAI</strong>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-size: 0.74rem; font-weight: 800; color: #FFFFFF; margin: 0; text-transform: uppercase;">
                            ENTER CAI TO SELL:
                        </label>
                        <span style="font-size: 0.65rem; color: #8C9BAE;">Max: <strong style="color: #FFD700; cursor: pointer;" onclick="setPageSellPercent(100)">${maxCai.toFixed(4)} CAI</strong></span>
                    </div>

                    <div style="position: relative; margin-bottom: 8px;">
                        <input type="number" id="pageSellCaiInput" value="${defaultCai}" step="any" min="0.0001" max="${maxCai}"
                            style="width: 100%; height: 40px; background: rgba(5, 7, 12, 0.9); border: 1px solid rgba(245, 166, 35, 0.4); border-radius: 8px; padding: 0 54px 0 12px; color: #FFD700; font-family: 'Space Mono', monospace; font-size: 1rem; font-weight: 700; outline: none;"
                            oninput="updatePageSellCalculations()"
                        />
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 0.72rem; font-weight: 800; color: #FFD700; font-family: 'Space Mono', monospace; pointer-events: none;">
                            CAI
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 14px;">
                        <button type="button" onclick="setPageSellPercent(25)" style="height: 26px; border-radius: 6px; background: rgba(255, 215, 0, 0.08); border: 1px solid rgba(255, 215, 0, 0.25); color: #FFD700; font-size: 0.68rem; font-weight: 800; cursor: pointer;">25%</button>
                        <button type="button" onclick="setPageSellPercent(50)" style="height: 26px; border-radius: 6px; background: rgba(255, 215, 0, 0.08); border: 1px solid rgba(255, 215, 0, 0.25); color: #FFD700; font-size: 0.68rem; font-weight: 800; cursor: pointer;">50%</button>
                        <button type="button" onclick="setPageSellPercent(75)" style="height: 26px; border-radius: 6px; background: rgba(255, 215, 0, 0.08); border: 1px solid rgba(255, 215, 0, 0.25); color: #FFD700; font-size: 0.68rem; font-weight: 800; cursor: pointer;">75%</button>
                        <button type="button" onclick="setPageSellPercent(100)" style="height: 26px; border-radius: 6px; background: linear-gradient(135deg, rgba(245, 166, 35, 0.25), rgba(217, 119, 6, 0.35)); border: 1px solid #FFD700; color: #FFFFFF; font-size: 0.68rem; font-weight: 800; cursor: pointer;">MAX</button>
                    </div>

                    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 10px 12px; margin-bottom: 10px; font-size: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="color: #8C9BAE;">Gross Value:</span>
                            <span id="pageGrossUsdt" style="color: #FFFFFF; font-family: 'Space Mono', monospace; font-weight: 700;">$${defaultGrossUsdt.toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="color: #8C9BAE;">10% Protocol Fee:</span>
                            <span id="pageFeeUsdt" style="color: #F5A623; font-family: 'Space Mono', monospace; font-weight: 700;">-$${defaultFeeUsdt.toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 7px; padding-top: 5px; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                            <span style="color: #00FF88; font-weight: 800;">Est. Delivered to Wallet:</span>
                            <span id="pageNetUsdt" style="color: #00FF88; font-family: 'Space Mono', monospace; font-weight: 900; font-size: 0.88rem;">$${defaultNetUsdt.toFixed(2)} USDT</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 0.68rem; color: #8C9BAE; padding-top: 5px; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                            <span>Remaining CAI Holding:</span>
                            <span id="pageRemCai" style="color: #FFE082; font-family: 'Space Mono', monospace;">${Math.max(0, PAGE_HOLDING_CAI - defaultCai).toFixed(4)} CAI</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.68rem; color: #8C9BAE;">
                            <span>Remaining Capping:</span>
                            <span id="pageRemCap" style="color: #6EE7B7; font-family: 'Space Mono', monospace;">$${Math.max(0, PAGE_REMAINING_CAPPING - defaultGrossUsdt).toFixed(2)}</span>
                        </div>
                    </div>

                    <div id="pageSellErrBox" style="display: none; font-size: 0.70rem; color: #EF4444; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; padding: 6px 10px; margin-bottom: 8px; text-align: center;"></div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'CONFIRM &amp; SIGN ON-CHAIN',
            cancelButtonText: 'CANCEL',
            buttonsStyling: false,
            background: '#080A10',
            customClass: {
                popup: 'mecha-swal-popup',
                title: 'mecha-swal-title',
                htmlContainer: 'mecha-swal-html',
                actions: 'mecha-swal-actions',
                confirmButton: 'mecha-swal-confirm',
                cancelButton: 'mecha-swal-cancel'
            },
            didOpen: () => {
                window.updatePageSellCalculations = function() {
                    const input = document.getElementById('pageSellCaiInput');
                    const errBox = document.getElementById('pageSellErrBox');
                    const confirmBtn = Swal.getConfirmButton();
                    if (!input) return;

                    let val = parseFloat(input.value) || 0;
                    if (val < 0) val = 0;

                    const maxAllowed = PAGE_MAX_SELL_CAI;
                    const p = PAGE_LIVE_PRICE > 0 ? PAGE_LIVE_PRICE : 1.0;
                    const gross = val * p;
                    const fee = gross * 0.10;
                    const net = gross - fee;

                    const remCai = Math.max(0, PAGE_HOLDING_CAI - val);
                    const remCap = Math.max(0, PAGE_REMAINING_CAPPING - gross);

                    const grossEl = document.getElementById('pageGrossUsdt');
                    const feeEl = document.getElementById('pageFeeUsdt');
                    const netEl = document.getElementById('pageNetUsdt');
                    const remCaiEl = document.getElementById('pageRemCai');
                    const remCapEl = document.getElementById('pageRemCap');

                    if (grossEl) grossEl.innerText = '$' + gross.toFixed(2);
                    if (feeEl) feeEl.innerText = '-$' + fee.toFixed(2);
                    if (netEl) netEl.innerText = '$' + net.toFixed(2) + ' USDT';
                    if (remCaiEl) remCaiEl.innerText = remCai.toFixed(4) + ' CAI';
                    if (remCapEl) remCapEl.innerText = '$' + remCap.toFixed(2);

                    if (val <= 0) {
                        if (errBox) {
                            errBox.innerText = 'Please enter a CAI amount greater than 0.';
                            errBox.style.display = 'block';
                        }
                        if (confirmBtn) confirmBtn.disabled = true;
                    } else if (val > PAGE_HOLDING_CAI) {
                        if (errBox) {
                            errBox.innerText = 'Amount exceeds your Protocol CAI Holding (' + PAGE_HOLDING_CAI.toFixed(4) + ' CAI).';
                            errBox.style.display = 'block';
                        }
                        if (confirmBtn) confirmBtn.disabled = true;
                    } else if (val > (maxAllowed + 0.0001)) {
                        if (errBox) {
                            errBox.innerText = 'Amount exceeds your Active Capping Limit (Max ' + maxAllowed.toFixed(4) + ' CAI).';
                            errBox.style.display = 'block';
                        }
                        if (confirmBtn) confirmBtn.disabled = true;
                    } else {
                        if (errBox) errBox.style.display = 'none';
                        if (confirmBtn) confirmBtn.disabled = false;
                    }
                };

                window.setPageSellPercent = function(pct) {
                    const input = document.getElementById('pageSellCaiInput');
                    if (!input) return;
                    let target = 0;
                    if (pct === 100) {
                        target = PAGE_MAX_SELL_CAI;
                    } else {
                        target = parseFloat(((PAGE_MAX_SELL_CAI * pct) / 100).toFixed(4));
                    }
                    input.value = target;
                    window.updatePageSellCalculations();
                };

                window.updatePageSellCalculations();
            },
            preConfirm: () => {
                const input = document.getElementById('pageSellCaiInput');
                const val = parseFloat(input ? input.value : 0);
                if (isNaN(val) || val <= 0) {
                    Swal.showValidationMessage('Please enter a valid CAI amount to sell.');
                    return false;
                }
                if (val > PAGE_HOLDING_CAI) {
                    Swal.showValidationMessage('Amount exceeds your Protocol CAI balance.');
                    return false;
                }
                if (val > (PAGE_MAX_SELL_CAI + 0.0001)) {
                    Swal.showValidationMessage('Amount exceeds your remaining capping limit.');
                    return false;
                }
                return val;
            }
        });

        if (!isConfirmed || !customCaiAmount) return;

        await executePageCustomSell(customCaiAmount);
    }

    /* Execute Web3 Swap on Mining Engine */
    async function executePageCustomSell(selectedCai) {
        const btn = document.getElementById('btnPageSellAll');
        const btnTxt = document.getElementById('btnPageSellAllTxt');

        const chosenRawProvider = await resolvePageWeb3Provider();
        if (!chosenRawProvider) return;

        try {
            if (btn) btn.disabled = true;
            if (btnTxt) btnTxt.innerText = 'CONNECTING WALLET...';

            const provider = new ethers.providers.Web3Provider(chosenRawProvider);
            await provider.send("eth_requestAccounts", []);
            const signer = provider.getSigner();

            const network = await provider.getNetwork();
            if (network.chainId !== 56) {
                try {
                    await chosenRawProvider.request({
                        method: 'wallet_switchEthereumChain',
                        params: [{ chainId: '0x38' }],
                    });
                } catch (switchError) {
                    Swal.fire({
                        title: 'SWITCH TO BSC',
                        html: '<div style="font-size: 0.80rem; color: #CBD5E1; text-align: center;">Please switch your wallet to BNB Smart Chain (BSC Mainnet).</div>',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        background: '#080A10',
                        customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
                    });
                    if (btn) btn.disabled = false;
                    if (btnTxt) btnTxt.innerText = 'SELL CAI ON DEX';
                    return;
                }
            }

            if (btnTxt) btnTxt.innerText = 'REQUESTING SIGNATURE...';

            const userAccount = await signer.getAddress();
            const sigResp = await fetch('{{ url("/User/Mining/RequestSellSignature") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cai_amount: selectedCai,
                    wallet_address: userAccount
                })
            });

            const sigData = await sigResp.json();
            if (sigData.status !== 'success') {
                throw new Error(sigData.message || 'Signature authorization failed.');
            }

            const d = sigData.data;

            if (btnTxt) btnTxt.innerText = 'CONFIRM IN WALLET...';

            const miningContract = new ethers.Contract(PAGE_MINING_ENGINE, PAGE_MINING_ABI, signer);

            const tx = await miningContract.sellPortfolio(
                d.caiAmountWei,
                d.minUsdtOutWei,
                d.nonce,
                d.expiry,
                d.signature,
                { gasLimit: 500000 }
            );

            if (btnTxt) btnTxt.innerText = 'WAITING CONFIRMATION...';

            const receipt = await tx.wait(1);

            if (btnTxt) btnTxt.innerText = 'FINALIZING SETTLEMENT...';

            const confirmResp = await fetch('{{ url("/User/Mining/ConfirmSell") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tx_hash: receipt.transactionHash,
                    cai_amount: d.caiAmount,
                    gross_cai: d.grossCai,
                    usdt_received: d.netUsdt,
                    gross_usdt: d.grossUsdt
                })
            });

            await confirmResp.json();

            await Swal.fire({
                title: 'PORTFOLIO SOLD ON DEX!',
                html: `
                    <div style="font-size: 0.82rem; color: #CBD5E1; margin-bottom: 12px; text-align: center;">
                        Successfully swapped <strong style="color:#FFD700; font-family:'Space Mono', monospace;">${d.caiAmount} CAI</strong> on PancakeSwap!
                    </div>
                    <div style="background: rgba(245, 166, 35, 0.04); border: 1px solid rgba(245, 166, 35, 0.25); border-radius: 10px; padding: 10px 12px; text-align: left; font-size: 0.78rem;">
                        <div style="color: #00FF88; margin-bottom: 5px;">✓ Delivered $${parseFloat(d.netUsdt).toFixed(2)} USDT directly to wallet.</div>
                        <div style="color: #FFD700; margin-bottom: 5px;">✓ Protocol Fee (10%): $${parseFloat(d.adminFeeUsdt).toFixed(2)} USDT.</div>
                        <div style="color: #6EE7B7;">✓ Deducted $${parseFloat(d.grossUsdt).toFixed(2)} from active deposit capping.</div>
                    </div>
                `,
                confirmButtonText: 'DONE',
                buttonsStyling: false,
                background: '#080A10',
                customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
            });

            window.location.reload();

        } catch (e) {
            console.error(e);
            let errMsg = e.reason || e.data?.message || e.message || 'Swap transaction rejected.';
            Swal.fire({
                title: 'SWAP FAILED',
                html: `<div style="font-size: 0.80rem; color: #EF4444; text-align: center;">${errMsg}</div>`,
                confirmButtonText: 'CLOSE',
                buttonsStyling: false,
                background: '#080A10',
                customClass: { popup: 'mecha-swal-popup', confirmButton: 'mecha-swal-confirm' }
            });
            if (btn) btn.disabled = false;
            if (btnTxt) btnTxt.innerText = 'SELL CAI ON DEX';
        }
    }
</script>

@endsection
