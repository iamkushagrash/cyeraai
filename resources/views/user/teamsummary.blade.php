@extends('layouts.user-mecha')

@section('title', 'Team Summary - Cyera AI')
@section('page-title', 'Team & Business Summary')
@section('page-icon', 'fas fa-chart-pie')

@section('content')
<div class="mecha-inner-body" style="max-width: 680px; margin: 0 auto;">

    <!-- Top Mini Metrics 4-Grid -->
    <div class="mecha-stat-grid-4">
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>TOTAL DIRECTS</span>
                <i class="fas fa-user-group" style="color: #FFD700;"></i>
            </div>
            <div class="mecha-metric-val gold">{{ $details->totaldirect }}</div>
            <div class="mecha-metric-sub">Active: <span style="color: #00FF88;">{{ $details->activedirect }}</span></div>
        </div>
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>TOTAL TEAM</span>
                <i class="fas fa-sitemap" style="color: #00E5FF;"></i>
            </div>
            <div class="mecha-metric-val cyan">{{ $details->totaldownline }}</div>
            <div class="mecha-metric-sub">Active: <span style="color: #00FF88;">{{ $details->activedownline }}</span></div>
        </div>
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>SELF STAKE</span>
                <i class="fas fa-wallet" style="color: #00FF88;"></i>
            </div>
            <div class="mecha-metric-val green">${{ number_format((float)($details->currentself ?? 0), 2) }}</div>
            <div class="mecha-metric-sub">Personal Capital</div>
        </div>
        <div class="mecha-metric-box">
            <div class="mecha-metric-lbl">
                <span>TOTAL TURNOVER</span>
                <i class="fas fa-vault" style="color: #B34BFE;"></i>
            </div>
            <div class="mecha-metric-val purple" style="color: #B34BFE;">${{ number_format((float)($details->totalbusiness ?? 0), 2) }}</div>
            <div class="mecha-metric-sub">Network Volume</div>
        </div>
    </div>

    <!-- Main Detail Cards (Clean Stack on Mobile, 2-Col on Desktop) -->
    <div class="mecha-card-grid-2">
        <!-- Team Member Distribution Card -->
        <div class="mecha-hud-card" style="margin-bottom: 0;">
            <div class="mecha-card-header">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-users-viewfinder"></i>
                    <div>
                        <h2 class="mecha-card-title">NETWORK MEMBERS</h2>
                        <div class="mecha-card-subtitle">Headcount & Active participation</div>
                    </div>
                </div>
                <span class="mecha-card-badge">HEADCOUNT</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255, 215, 0, 0.12); border: 1px solid rgba(255, 215, 0, 0.3); display: flex; align-items: center; justify-content: center; color: #FFD700; font-size: 11px;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span style="color: #CBD5E1; font-size: 11.5px; font-weight: 700;">Total Direct Referrals</span>
                    </div>
                    <strong style="color: #FFFFFF; font-size: 15px; font-weight: 900;">{{ $details->totaldirect }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(0,255,136,0.05); border: 1px solid rgba(0,255,136,0.25); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(0, 255, 136, 0.12); border: 1px solid rgba(0, 255, 136, 0.3); display: flex; align-items: center; justify-content: center; color: #00FF88; font-size: 11px;">
                            <i class="fas fa-circle-check"></i>
                        </div>
                        <span style="color: #E2E8F0; font-size: 11.5px; font-weight: 700;">Active Direct Members</span>
                    </div>
                    <strong style="color: #00FF88; font-size: 15px; font-weight: 900; text-shadow: 0 0 10px rgba(0,255,136,0.4);">{{ $details->activedirect }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(0, 229, 255, 0.12); border: 1px solid rgba(0, 229, 255, 0.3); display: flex; align-items: center; justify-content: center; color: #00E5FF; font-size: 11px;">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <span style="color: #CBD5E1; font-size: 11.5px; font-weight: 700;">Total Downline Community</span>
                    </div>
                    <strong style="color: #FFFFFF; font-size: 15px; font-weight: 900;">{{ $details->totaldownline }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(0,229,255,0.05); border: 1px solid rgba(0,229,255,0.25); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(0, 229, 255, 0.12); border: 1px solid rgba(0, 229, 255, 0.3); display: flex; align-items: center; justify-content: center; color: #00E5FF; font-size: 11px;">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <span style="color: #E2E8F0; font-size: 11.5px; font-weight: 700;">Active Downline Community</span>
                    </div>
                    <strong style="color: #00E5FF; font-size: 15px; font-weight: 900; text-shadow: 0 0 10px rgba(0,229,255,0.4);">{{ $details->activedownline }}</strong>
                </div>
            </div>
        </div>

        <!-- Financial Volume Card -->
        <div class="mecha-hud-card" style="margin-bottom: 0;">
            <div class="mecha-card-header">
                <div class="mecha-card-title-wrap">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <h2 class="mecha-card-title">FINANCIAL TURNOVER</h2>
                        <div class="mecha-card-subtitle">On-chain staked capital</div>
                    </div>
                </div>
                <span class="mecha-card-badge">FINANCES</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255, 215, 0, 0.12); border: 1px solid rgba(255, 215, 0, 0.3); display: flex; align-items: center; justify-content: center; color: #FFD700; font-size: 11px;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span style="color: #CBD5E1; font-size: 11.5px; font-weight: 700;">Self Active Staking</span>
                    </div>
                    <strong style="color: #FFD700; font-size: 15px; font-weight: 900; text-shadow: 0 0 10px rgba(255,215,0,0.3);">${{ number_format((float)($details->currentself ?? 0), 2) }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(0,255,136,0.05); border: 1px solid rgba(0,255,136,0.25); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(0, 255, 136, 0.12); border: 1px solid rgba(0, 255, 136, 0.3); display: flex; align-items: center; justify-content: center; color: #00FF88; font-size: 11px;">
                            <i class="fas fa-money-bill-trend-up"></i>
                        </div>
                        <span style="color: #E2E8F0; font-size: 11.5px; font-weight: 700;">Direct Business Volume</span>
                    </div>
                    <strong style="color: #00FF88; font-size: 15px; font-weight: 900; text-shadow: 0 0 10px rgba(0,255,136,0.4);">${{ number_format((float)($details->directbusiness ?? 0), 2) }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(179,75,254,0.08); border: 1px solid rgba(179,75,254,0.35); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(179, 75, 254, 0.15); border: 1px solid rgba(179, 75, 254, 0.4); display: flex; align-items: center; justify-content: center; color: #B34BFE; font-size: 11px;">
                            <i class="fas fa-vault"></i>
                        </div>
                        <span style="color: #E2E8F0; font-size: 11.5px; font-weight: 700;">Total Network Turnover</span>
                    </div>
                    <strong style="color: #B34BFE; font-size: 16px; font-weight: 900; text-shadow: 0 0 12px rgba(179,75,254,0.5);">${{ number_format((float)($details->totalbusiness ?? 0), 2) }}</strong>
                </div>

                <!-- Indirect Downline Team Volume -->
                @php
                    $downlineBiz = max(0, (float)($details->totalbusiness ?? 0) - (float)($details->directbusiness ?? 0));
                @endphp
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: rgba(0,229,255,0.05); border: 1px solid rgba(0,229,255,0.25); border-radius: 9px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(0, 229, 255, 0.12); border: 1px solid rgba(0, 229, 255, 0.3); display: flex; align-items: center; justify-content: center; color: #00E5FF; font-size: 11px;">
                            <i class="fas fa-diagram-project"></i>
                        </div>
                        <span style="color: #E2E8F0; font-size: 11.5px; font-weight: 700;">Indirect Team Volume</span>
                    </div>
                    <strong style="color: #00E5FF; font-size: 15px; font-weight: 900; text-shadow: 0 0 10px rgba(0,229,255,0.4);">${{ number_format($downlineBiz, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
