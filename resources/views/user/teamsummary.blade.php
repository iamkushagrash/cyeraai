@extends('layouts.user-mecha')

@section('title', 'Team Summary - Cyera AI')
@section('page-title', 'Team & Business Summary')
@section('page-icon', 'fas fa-chart-pie')

@section('content')
<!-- Top Mini Metrics 4-Grid -->
<div class="mecha-stat-grid-4">
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">TOTAL DIRECTS</div>
        <div class="mecha-metric-val gold">{{ $details->totaldirect }}</div>
        <div class="mecha-metric-sub">Active: {{ $details->activedirect }}</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">TOTAL TEAM</div>
        <div class="mecha-metric-val cyan">{{ $details->totaldownline }}</div>
        <div class="mecha-metric-sub">Active: {{ $details->activedownline }}</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">SELF STAKE</div>
        <div class="mecha-metric-val green">${{ number_format((float)($details->currentself ?? 0), 2) }}</div>
        <div class="mecha-metric-sub">Personal Capital</div>
    </div>
    <div class="mecha-metric-box">
        <div class="mecha-metric-lbl">TOTAL TURNOVER</div>
        <div class="mecha-metric-val purple">${{ number_format((float)($details->totalbusiness ?? 0), 2) }}</div>
        <div class="mecha-metric-sub">Network Volume</div>
    </div>
</div>

<div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
    <!-- Team Member Distribution Card -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-users-viewfinder"></i>
                <div>
                    <h2 class="mecha-card-title">NETWORK MEMBERS</h2>
                    <div class="mecha-card-subtitle">Headcount & Active participation metrics</div>
                </div>
            </div>
            <span class="mecha-card-badge">HEADCOUNT</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-user-tag" style="color: #FFD700; margin-right: 6px;"></i> Total Direct Referrals:</span>
                <strong style="color: #FFF; font-size: 13px;">{{ $details->totaldirect }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(0,255,136,0.05); border: 1px solid rgba(0,255,136,0.2); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-circle-check" style="color: #00FF88; margin-right: 6px;"></i> Active Direct Members:</span>
                <strong style="color: #00FF88; font-size: 13px;">{{ $details->activedirect }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-sitemap" style="color: #00E5FF; margin-right: 6px;"></i> Total Downline Community:</span>
                <strong style="color: #FFF; font-size: 13px;">{{ $details->totaldownline }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(0,229,255,0.05); border: 1px solid rgba(0,229,255,0.2); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-bolt" style="color: #00E5FF; margin-right: 6px;"></i> Active Downline Community:</span>
                <strong style="color: #00E5FF; font-size: 13px;">{{ $details->activedownline }}</strong>
            </div>
        </div>
    </div>

    <!-- Financial Volume Card -->
    <div class="mecha-hud-card">
        <div class="mecha-card-header">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h2 class="mecha-card-title">FINANCIAL TURNOVER</h2>
                    <div class="mecha-card-subtitle">On-chain staked capital breakdown</div>
                </div>
            </div>
            <span class="mecha-card-badge">FINANCES</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-wallet" style="color: #FFD700; margin-right: 6px;"></i> Self Active Staking:</span>
                <strong style="color: #FFD700; font-size: 13px;">${{ number_format((float)($details->currentself ?? 0), 2) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-users" style="color: #00FF88; margin-right: 6px;"></i> Direct Business Volume:</span>
                <strong style="color: #00FF88; font-size: 13px;">${{ number_format((float)($details->directbusiness ?? 0), 2) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: rgba(179,75,254,0.08); border: 1px solid rgba(179,75,254,0.3); border-radius: 8px;">
                <span style="color: #CBD5E1; font-size: 11px;"><i class="fas fa-vault" style="color: #B34BFE; margin-right: 6px;"></i> Total Network Turnover:</span>
                <strong style="color: #B34BFE; font-size: 14px; font-weight: 800;">${{ number_format((float)($details->totalbusiness ?? 0), 2) }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection
