@php 
    $isRoot = $isRoot ?? false;
    $isActive = (strtolower($user->status ?? '') == 'active' || $user->status == '1');
    $w = $user->walletaddress ?? $user->bep20address ?? '';
@endphp

<div class="user-node {{ $isRoot ? 'root' : '' }}" data-user-id="{{ $user->id }}">
    <div class="node-head-row" style="justify-content: center; gap: 6px;">
        <span class="node-avatar-circle" style="width: 22px; height: 22px; font-size: 0.65rem;"><i class="fas fa-user"></i></span>
        <span class="node-uid-badge" style="margin: 0; font-size: 0.78rem; font-weight: 800;">{{ $user->userid }}</span>
    </div>

    @if(!empty($w))
    <span style="font-family: monospace; font-size: 0.65rem; color: #00FF88; background: rgba(0, 255, 136, 0.08); border: 1px solid rgba(0, 255, 136, 0.25); border-radius: 6px; padding: 2px 6px; margin: 4px 0; display: inline-block;">
        {{ substr($w, 0, 6) }}...{{ substr($w, -4) }}
    </span>
    @endif

    <div class="node-stats-grid">
        <div class="node-stat-item">
            <span class="node-stat-label">PKG</span>
            <span class="node-stat-value pkg">${{ number_format((float)($user->package ?? 0), 0) }}</span>
        </div>
        <div class="node-stat-item">
            <span class="node-stat-label">BIZ</span>
            <span class="node-stat-value biz">${{ number_format((float)($user->teamtotal ?? 0), 0) }}</span>
        </div>
    </div>

    <span class="node-status-pill {{ $isActive ? 'active' : 'inactive' }}">
        <i class="fas fa-circle" style="font-size: 5px;"></i> {{ $isActive ? 'ACTIVE' : 'INACTIVE' }}
    </span>

    <div class="toggle-arrow" onclick="loadChildren(event, this, {{ $user->id }})" title="Expand Downline">▼</div>
</div>

<ul class="children-container" data-loaded="0" style="display: none;"></ul>
