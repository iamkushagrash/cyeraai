@php 
    $isRoot = $isRoot ?? false;
    $isActive = (strtolower($user->status ?? '') == 'active' || $user->status == '1');
    $mWallet = $user->walletaddress ?? $user->bep20address ?? '';
    $nodeDisplay = !empty($mWallet) ? (substr($mWallet, 0, 6) . '...' . substr($mWallet, -4)) : ($user->userid ?? 'Member');
    $initial = !empty($mWallet) ? '0x' : 'C';
@endphp

<div class="user-node {{ $isRoot ? 'root' : '' }}" data-user-id="{{ $user->id }}">
    <div class="node-head-row">
        <span class="node-avatar-circle" style="font-size: 8px; font-family: monospace;">{{ $initial }}</span>
        <span class="node-user-name" style="font-family: monospace; color: #00FF88;">{{ $nodeDisplay }}</span>
    </div>

    <span class="node-uid-badge">{{ $user->userid }}</span>

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
