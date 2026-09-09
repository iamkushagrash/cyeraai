@extends('layouts.user-mecha')

@section('title', 'Genealogy Tree - Cyera AI')
@section('page-title', 'Genealogy Tree View')
@section('page-icon', 'fas fa-sitemap')

@push('styles')
<style>
    /* Tree Card Container */
    .mecha-tree-card {
        background: #060609;
        border: 1px solid rgba(245, 166, 35, 0.25);
        border-radius: 16px;
        padding: 16px 14px;
        position: relative;
        overflow: hidden;
    }

    /* Top Controls Bar */
    .tree-header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        flex-wrap: wrap;
    }

    .tree-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tree-title-group i {
        font-size: 1.2rem;
        color: #FFD700;
    }

    .tree-title-text {
        font-family: 'Outfit', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 0.5px;
        margin: 0;
        line-height: 1.2;
    }

    .tree-subtitle-text {
        font-size: 0.72rem;
        color: #64748B;
        margin-top: 2px;
    }

    .tree-actions-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tree-tool-btn {
        height: 32px;
        padding: 0 12px;
        border-radius: 8px;
        background: #0D111A;
        border: 1px solid rgba(255, 215, 0, 0.25);
        color: #FFD700;
        font-family: 'Outfit', sans-serif;
        font-size: 0.76rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
    }

    .tree-tool-btn:hover {
        background: rgba(245, 166, 35, 0.15);
        border-color: #FFD700;
        box-shadow: 0 0 12px rgba(255, 215, 0, 0.35);
        transform: translateY(-1px);
    }

    /* Interactive Legend Bar */
    .tree-legend-strip {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-bottom: 12px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #94A3B8;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .legend-dot.root { background: #FFD700; box-shadow: 0 0 8px #FFD700; }
    .legend-dot.active { background: #00FF88; box-shadow: 0 0 8px #00FF88; }
    .legend-dot.inactive { background: #F43F5E; box-shadow: 0 0 8px #F43F5E; }

    /* Scrollable Interactive Canvas */
    .tree-scroll-container {
        position: relative;
        background-color: #05070D;
        background-image: 
            linear-gradient(rgba(245, 166, 35, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(245, 166, 35, 0.05) 1px, transparent 1px);
        background-size: 24px 24px;
        border: 1px solid rgba(245, 166, 35, 0.20);
        border-radius: 12px;
        overflow: auto;
        min-height: 480px;
        width: 100%;
        padding: 36px 20px;
        overscroll-behavior: contain;
        cursor: grab;
        text-align: center;
    }

    .tree-scroll-container:active {
        cursor: grabbing;
    }

    .tree {
        display: inline-block;
        min-width: max-content;
        position: relative;
        margin: 0 auto;
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1);
    }

    .tree ul {
        padding-top: 24px;
        position: relative;
        display: flex;
        justify-content: center;
        margin: 0;
        padding-left: 0;
    }

    .tree li {
        list-style-type: none;
        position: relative;
        padding: 24px 10px 0 10px;
        text-align: center;
    }

    /* Connector lines */
    .tree li::before, .tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid rgba(245, 166, 35, 0.45);
        width: 50%;
        height: 24px;
        filter: drop-shadow(0 0 4px rgba(245, 166, 35, 0.3));
    }

    .tree li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid rgba(245, 166, 35, 0.45);
    }

    .tree li:only-child::after, .tree li:only-child::before {
        display: none;
    }

    .tree li:only-child {
        padding-top: 0;
    }

    .tree li:first-child::before, .tree li:last-child::after {
        border: 0 none;
    }

    .tree li:last-child::before {
        border-right: 2px solid rgba(245, 166, 35, 0.45);
        border-radius: 0 8px 0 0;
    }

    .tree li:first-child::after {
        border-radius: 8px 0 0 0;
    }

    .tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid rgba(245, 166, 35, 0.45);
        width: 0;
        height: 24px;
        filter: drop-shadow(0 0 4px rgba(245, 166, 35, 0.3));
    }

    /* Cyber Tree Node Pod */
    .user-node {
        border: 1px solid rgba(245, 166, 35, 0.30);
        padding: 10px 12px 14px 12px;
        border-radius: 12px;
        background: linear-gradient(145deg, #0C101A 0%, #06080F 100%);
        color: #FFF;
        cursor: pointer;
        width: 156px;
        display: inline-flex;
        flex-direction: column;
        gap: 5px;
        text-align: center;
        box-shadow: 0 8px 24px -6px rgba(0, 0, 0, 0.9), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.25s ease;
        position: relative;
        z-index: 2;
    }

    .user-node:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 215, 0, 0.65);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.95), 0 0 16px rgba(245, 166, 35, 0.3);
    }

    .user-node.root {
        border: 1.5px solid #FFD700;
        background: linear-gradient(145deg, #161C2C 0%, #080C16 100%);
        box-shadow: 0 0 18px rgba(255, 215, 0, 0.35), 0 8px 24px rgba(0, 0, 0, 0.9);
    }

    .node-head-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .node-avatar-circle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(245, 166, 35, 0.15);
        border: 1px solid rgba(245, 166, 35, 0.4);
        color: #FFD700;
        font-size: 9px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .node-user-name {
        font-family: 'Outfit', sans-serif;
        font-size: 0.84rem;
        font-weight: 800;
        color: #FFFFFF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 110px;
        line-height: 1.2;
    }

    .node-uid-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        font-weight: 700;
        color: #00E5FF;
        background: rgba(0, 229, 255, 0.08);
        border: 1px solid rgba(0, 229, 255, 0.25);
        padding: 1px 5px;
        border-radius: 4px;
        margin: 0 auto;
        letter-spacing: 0.3px;
    }

    /* Node Stats Matrix (Package & Biz) */
    .node-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        padding: 4px;
        margin-top: 2px;
    }

    .node-stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1px;
    }

    .node-stat-label {
        font-size: 0.58rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
    }

    .node-stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 0.72rem;
        font-weight: 800;
    }

    .node-stat-value.pkg { color: #00FF88; }
    .node-stat-value.biz { color: #00E5FF; }

    /* Node Status Badge */
    .node-status-pill {
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        margin-top: 1px;
    }

    .node-status-pill.active { color: #00FF88; }
    .node-status-pill.inactive { color: #FB7185; }

    /* Sleek Toggle Button */
    .toggle-arrow {
        position: absolute;
        bottom: -11px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFD700, #F5A623);
        border: 2px solid #05070D;
        color: #000000;
        font-size: 8.5px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        z-index: 3;
        transition: all 0.2s ease;
    }

    .toggle-arrow:hover {
        transform: translateX(-50%) scale(1.15);
        box-shadow: 0 0 14px rgba(255, 215, 0, 0.8);
    }

    /* Footer Hint */
    .tree-footer-hint {
        text-align: center;
        margin-top: 12px;
        font-size: 0.72rem;
        color: #64748B;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .tree-footer-hint i {
        color: #FFD700;
    }
</style>
@endpush

@section('content')
<div class="mecha-tree-card">
    <div class="tree-header-controls">
        <div class="tree-title-group">
            <i class="fas fa-sitemap"></i>
            <div>
                <h2 class="tree-title-text">GENEALOGY HIERARCHY</h2>
                <div class="tree-subtitle-text">Interactive visual network tree with expandable downline branches</div>
            </div>
        </div>

        <!-- Zoom & View Tools -->
        <div class="tree-actions-wrap">
            <button type="button" class="tree-tool-btn" onclick="zoomIn()" title="Zoom In">
                <i class="fas fa-search-plus"></i>
            </button>
            <button type="button" class="tree-tool-btn" onclick="zoomOut()" title="Zoom Out">
                <i class="fas fa-search-minus"></i>
            </button>
            <button type="button" class="tree-tool-btn" onclick="resetZoom()" title="Reset Zoom">
                <i class="fas fa-crosshairs"></i> Reset
            </button>
        </div>
    </div>

    <!-- Legend Strip -->
    <div class="tree-legend-strip">
        <div class="legend-item">
            <span class="legend-dot root"></span>
            <span>Root Node</span>
        </div>
        <div class="legend-item">
            <span class="legend-dot active"></span>
            <span>Active Partner</span>
        </div>
        <div class="legend-item">
            <span class="legend-dot inactive"></span>
            <span>Inactive Partner</span>
        </div>
    </div>

    <!-- Scrollable Tree Canvas -->
    <div class="tree-scroll-container" id="treeScrollContainer">
        <div class="tree" id="mainTree">
            <ul>
                <li>
                    @php
                        $isRootActive = (strtolower($rootUser->status ?? '') == 'active' || $rootUser->status == '1');
                        $rootInitial = strtoupper(substr($rootUser->name ?? $rootUser->userid ?? 'U', 0, 1));
                    @endphp
                    <div class="user-node root" data-user-id="{{ $rootUser->id }}">
                        <div class="node-head-row">
                            <span class="node-avatar-circle">{{ $rootInitial }}</span>
                            <span class="node-user-name" title="{{ $rootUser->name }}">{{ $rootUser->name ?: 'Root Leader' }}</span>
                        </div>

                        <span class="node-uid-badge">{{ $rootUser->userid }}</span>

                        <div class="node-stats-grid">
                            <div class="node-stat-item">
                                <span class="node-stat-label">PKG</span>
                                <span class="node-stat-value pkg">${{ number_format((float)($rootUser->package ?? 0), 0) }}</span>
                            </div>
                            <div class="node-stat-item">
                                <span class="node-stat-label">BIZ</span>
                                <span class="node-stat-value biz">${{ number_format((float)($rootUser->teamtotal ?? 0), 0) }}</span>
                            </div>
                        </div>

                        <span class="node-status-pill {{ $isRootActive ? 'active' : 'inactive' }}">
                            <i class="fas fa-circle" style="font-size: 5px;"></i> {{ $isRootActive ? 'ACTIVE' : 'INACTIVE' }}
                        </span>

                        <div class="toggle-arrow" onclick="loadChildren(event, this, {{ $rootUser->id }})" title="Expand Downline">▼</div>
                    </div>
                    <ul class="children-container" data-loaded="0" style="display: none;"></ul>
                </li>
            </ul>
        </div>
    </div>

    <div class="tree-footer-hint">
        <i class="fas fa-hand-pointer"></i> Drag or scroll to explore tree canvas. Click <span style="color:#FFD700; font-weight:800;">▼</span> to dynamically expand downline members.
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentZoom = 1;

    function zoomIn() {
        if (currentZoom < 1.8) {
            currentZoom += 0.15;
            applyZoom();
        }
    }

    function zoomOut() {
        if (currentZoom > 0.4) {
            currentZoom -= 0.15;
            applyZoom();
        }
    }

    function resetZoom() {
        currentZoom = 1;
        applyZoom();
    }

    function applyZoom() {
        const tree = document.getElementById('mainTree');
        if (tree) {
            tree.style.transform = `scale(${currentZoom})`;
            tree.style.transformOrigin = 'top center';
        }
    }

    // Drag to Pan inside Tree Canvas
    const container = document.getElementById('treeScrollContainer');
    let isDown = false;
    let startX, startY, scrollLeft, scrollTop;

    if (container) {
        container.addEventListener('mousedown', (e) => {
            if (e.target.closest('.user-node') || e.target.closest('.toggle-arrow')) return;
            isDown = true;
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
        });

        container.addEventListener('mouseleave', () => { isDown = false; });
        container.addEventListener('mouseup', () => { isDown = false; });

        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const y = e.pageY - container.offsetTop;
            const walkX = (x - startX) * 1.5;
            const walkY = (y - startY) * 1.5;
            container.scrollLeft = scrollLeft - walkX;
            container.scrollTop = scrollTop - walkY;
        });
    }

    function loadChildren(e, btn, parentId) {
        if (e) e.stopPropagation();
        const li = btn.closest("li");
        const container = li.querySelector(".children-container");

        if (container.dataset.loaded === "1") {
            if (container.style.display === "none") {
                container.style.display = "flex";
                btn.innerHTML = '▲';
            } else {
                container.style.display = "none";
                btn.innerHTML = '▼';
            }
            return;
        }

        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:8px;"></i>';

        fetch(`/User/Treeview/children/${parentId}`)
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                container.dataset.loaded = "1";
                container.style.display = "flex";
                btn.innerHTML = '▲';
            })
            .catch(() => {
                btn.innerHTML = '!';
            });
    }
</script>
@endpush