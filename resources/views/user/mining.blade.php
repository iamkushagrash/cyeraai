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
                    <th style="padding: 10px 12px; text-align: right; font-size: 9.5px; font-weight: 800; color: #8C9BAE; text-transform: uppercase; letter-spacing: 0.6px; white-space: nowrap;">GROSS USDT</th>
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
                    <div style="font-size: 8px; font-weight: 800; color: #8C9BAE; text-transform: uppercase;">Gross USD (Rate: ${{ number_format((float)$entry->cai_price, 6) }})</div>
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

@endsection
