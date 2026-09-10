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

    /* Interactive Canvas Stage */
    .tree-scroll-container {
        position: relative;
        background-color: #05070D;
        background-image: 
            linear-gradient(rgba(245, 166, 35, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(245, 166, 35, 0.05) 1px, transparent 1px);
        background-size: 24px 24px;
        border: 1px solid rgba(245, 166, 35, 0.20);
        border-radius: 12px;
        overflow: hidden;
        min-height: 480px;
        height: 520px;
        width: 100%;
        cursor: grab;
        text-align: center;
        user-select: none;
        -webkit-user-select: none;
        touch-action: pan-y;
        position: relative;
    }

    .tree-scroll-container:active {
        cursor: grabbing;
    }

    .tree-viewport-stage {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 30px;
        transform-origin: 50% 30px;
        will-change: transform;
    }

    .tree-zoom-badge {
        font-family: 'Space Mono', monospace;
        font-size: 0.72rem;
        font-weight: 800;
        color: #FFD700;
        background: rgba(245, 166, 35, 0.12);
        border: 1px solid rgba(245, 166, 35, 0.3);
        padding: 4px 8px;
        border-radius: 8px;
        min-width: 48px;
        text-align: center;
        line-height: 1.2;
    }

    .tree {
        display: inline-block;
        min-width: max-content;
        position: relative;
        margin: 0 auto;
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
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFD700, #F5A623);
        color: #000;
        font-weight: 900;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 8px rgba(255, 215, 0, 0.4);
    }

    .node-user-name {
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem;
        font-weight: 800;
        color: #FFFFFF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
    }

    .node-uid-badge {
        font-family: 'Space Mono', monospace;
        font-size: 0.64rem;
        font-weight: 700;
        color: #00D2FF;
        background: rgba(0, 210, 255, 0.1);
        border: 1px solid rgba(0, 210, 255, 0.3);
        padding: 2px 6px;
        border-radius: 6px;
        align-self: center;
        letter-spacing: 0.5px;
    }

    /* Node Stats Matrix (Package & Biz) */
    .node-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px;
        background: rgba(0, 0, 0, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        padding: 4px;
        margin: 2px 0;
    }

    .node-stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .node-stat-label {
        font-size: 0.52rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
    }

    .node-stat-value {
        font-family: 'Space Mono', monospace;
        font-size: 0.68rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .node-stat-value.pkg { color: #00FF88; }
    .node-stat-value.biz { color: #00D2FF; }

    /* Node Status Badge */
    .node-status-pill {
        font-size: 0.56rem;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        align-self: center;
        letter-spacing: 0.4px;
    }

    .node-status-pill.active {
        background: rgba(0, 255, 136, 0.12);
        color: #00FF88;
        border: 1px solid rgba(0, 255, 136, 0.35);
    }

    .node-status-pill.inactive {
        background: rgba(244, 63, 94, 0.12);
        color: #F43F5E;
        border: 1px solid rgba(244, 63, 94, 0.35);
    }

    /* Sleek Toggle Button */
    .toggle-arrow {
        position: absolute;
        bottom: -11px;
        left: 50%;
        transform: translateX(-50%);
        width: 22px;
        height: 22px;
        background: linear-gradient(135deg, #FFD700, #F5A623);
        border: 2px solid #06080F;
        border-radius: 50%;
        color: #000;
        font-size: 8px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.6);
        transition: all 0.2s ease;
    }

    .toggle-arrow:hover {
        transform: translateX(-50%) scale(1.15);
        box-shadow: 0 0 14px rgba(255, 215, 0, 0.9);
    }

    /* Footer Hint */
    .tree-footer-hint {
        margin-top: 12px;
        font-size: 0.72rem;
        color: #64748B;
        text-align: center;
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
            <span class="tree-zoom-badge" id="zoomBadge">100%</span>
            <button type="button" class="tree-tool-btn" onclick="zoomOut()" title="Zoom Out">
                <i class="fas fa-search-minus"></i>
            </button>
            <button type="button" class="tree-tool-btn" onclick="resetZoom()" title="Reset View & Center">
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

    <!-- Interactive Tree Canvas -->
    <div class="tree-scroll-container" id="treeScrollContainer">
        <div class="tree-viewport-stage" id="treeViewportStage">
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
    </div>

    <div class="tree-footer-hint">
        <i class="fas fa-hand-pointer"></i> Drag or pinch to explore tree. Click <span style="color:#FFD700; font-weight:800;">▼</span> to dynamically expand downlines.
    </div>
</div>
@endsection

@push('scripts')
<script>
    let scale = 1;
    let panX = 0;
    let panY = 0;
    const minScale = 0.35;
    const maxScale = 2.2;

    const container = document.getElementById('treeScrollContainer');
    const stage = document.getElementById('treeViewportStage');
    const zoomBadge = document.getElementById('zoomBadge');

    function updateTransform(smooth = false) {
        if (!stage) return;
        stage.style.transition = smooth ? 'transform 0.25s cubic-bezier(0.16, 1, 0.3, 1)' : 'none';
        stage.style.transform = `translate3d(${panX}px, ${panY}px, 0) scale(${scale})`;
        if (zoomBadge) {
            zoomBadge.innerText = Math.round(scale * 100) + '%';
        }
    }

    function zoomIn() {
        if (scale < maxScale) {
            scale = Math.min(maxScale, Number((scale + 0.15).toFixed(2)));
            updateTransform(true);
        }
    }

    function zoomOut() {
        if (scale > minScale) {
            scale = Math.max(minScale, Number((scale - 0.15).toFixed(2)));
            updateTransform(true);
        }
    }

    function resetZoom() {
        scale = 1;
        panX = 0;
        panY = 0;
        updateTransform(true);
    }

    // Mouse Drag Panning (Desktop)
    let isMouseDown = false;
    let startMouseX = 0;
    let startMouseY = 0;
    let startPanX = 0;
    let startPanY = 0;

    if (container) {
        container.addEventListener('mousedown', (e) => {
            if (e.target.closest('.toggle-arrow')) return;
            isMouseDown = true;
            startMouseX = e.clientX;
            startMouseY = e.clientY;
            startPanX = panX;
            startPanY = panY;
            container.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return;
            panX = startPanX + (e.clientX - startMouseX);
            panY = startPanY + (e.clientY - startMouseY);
            updateTransform(false);
        });

        window.addEventListener('mouseup', () => {
            if (isMouseDown) {
                isMouseDown = false;
                if (container) container.style.cursor = 'grab';
            }
        });

        // Wheel Zoom
        container.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.1 : -0.1;
            const newScale = Math.min(maxScale, Math.max(minScale, Number((scale + delta).toFixed(2))));
            if (newScale !== scale) {
                scale = newScale;
                updateTransform(false);
            }
        }, { passive: false });

        // Touch Gestures (Mobile - 1 Finger Pan, 2 Finger Pinch Zoom, Natural Vertical Scroll)
        let touchStartX = 0;
        let touchStartY = 0;
        let initialPanX = 0;
        let initialPanY = 0;
        let initialPinchDistance = null;
        let initialScale = 1;
        let gestureType = null; // 'pan' | 'scroll' | 'pinch'

        container.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                if (e.target.closest('.toggle-arrow')) return;
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                initialPanX = panX;
                initialPanY = panY;
                gestureType = null;
            } else if (e.touches.length === 2) {
                gestureType = 'pinch';
                initialPinchDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                initialScale = scale;
            }
        }, { passive: true });

        container.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2 && gestureType === 'pinch') {
                if (e.cancelable) e.preventDefault();
                const currentDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                if (initialPinchDistance) {
                    const factor = currentDist / initialPinchDistance;
                    scale = Math.min(maxScale, Math.max(minScale, Number((initialScale * factor).toFixed(2))));
                    updateTransform(false);
                }
            } else if (e.touches.length === 1) {
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const dx = currentX - touchStartX;
                const dy = currentY - touchStartY;

                // Determine user intent:
                if (!gestureType) {
                    if (Math.abs(dx) > 6 && Math.abs(dx) > Math.abs(dy) * 1.1) {
                        gestureType = 'pan'; // Horizontal/diagonal intent -> Pan tree canvas
                    } else if (Math.abs(dy) > 7) {
                        gestureType = 'scroll'; // Vertical swipe intent -> Let outer page scroll smoothly
                    }
                }

                if (gestureType === 'pan') {
                    if (e.cancelable) e.preventDefault();
                    panX = initialPanX + dx;
                    panY = initialPanY + dy;
                    updateTransform(false);
                }
            }
        }, { passive: false });

        container.addEventListener('touchend', (e) => {
            if (e.touches.length === 0) {
                gestureType = null;
                initialPinchDistance = null;
            } else if (e.touches.length === 1) {
                gestureType = null;
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                initialPanX = panX;
                initialPanY = panY;
            }
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

    // Initial render
    document.addEventListener('DOMContentLoaded', () => {
        updateTransform(false);
    });
</script>
@endpush