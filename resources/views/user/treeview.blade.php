@extends('layouts.user-mecha')

@section('title', 'Genealogy Tree - Cyera AI')
@section('page-title', 'Genealogy Tree View')
@section('page-icon', 'fas fa-sitemap')

@push('styles')
<style>
    /* Tree Canvas & Hierarchy */
    .mecha-tree-card {
        background: linear-gradient(180deg, rgba(14, 19, 31, 0.95) 0%, rgba(8, 11, 18, 0.98) 100%);
        border: 1px solid rgba(229, 168, 35, 0.32);
        border-radius: 14px;
        padding: 16px;
        position: relative;
        overflow: hidden;
    }

    .tree-scroll-container {
        position: relative;
        background: rgba(4, 7, 13, 0.85);
        border: 1px solid rgba(229, 168, 35, 0.2);
        border-radius: 10px;
        overflow: auto;
        min-height: 480px;
        width: 100%;
        padding: 30px 20px;
        overscroll-behavior: contain;
    }

    .tree {
        display: inline-block;
        min-width: max-content;
        position: relative;
        margin: 0 auto;
    }

    .tree ul {
        padding-top: 20px;
        position: relative;
        display: flex;
        justify-content: center;
        margin: 0;
        padding-left: 0;
    }

    .tree li {
        list-style-type: none;
        position: relative;
        padding: 20px 10px 0 10px;
        text-align: center;
    }

    /* Connector lines */
    .tree li::before, .tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid rgba(255, 215, 0, 0.4);
        width: 50%;
        height: 20px;
    }

    .tree li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid rgba(255, 215, 0, 0.4);
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
        border-right: 2px solid rgba(255, 215, 0, 0.4);
        border-radius: 0 5px 0 0;
    }

    .tree li:first-child::after {
        border-radius: 5px 0 0 0;
    }

    .tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid rgba(255, 215, 0, 0.4);
        width: 0;
        height: 20px;
    }

    /* Cyber Tree Node */
    .user-node {
        border: 1px solid rgba(229, 168, 35, 0.4);
        padding: 10px 12px;
        border-radius: 10px;
        background: linear-gradient(145deg, rgba(16, 22, 38, 0.95), rgba(9, 13, 24, 0.95));
        color: #FFF;
        cursor: pointer;
        width: 140px;
        font-size: 11px;
        display: inline-flex;
        flex-direction: column;
        gap: 3px;
        text-align: center;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
        transition: all 0.25s ease;
        position: relative;
        z-index: 2;
    }

    .user-node:hover {
        transform: translateY(-3px) scale(1.04);
        border-color: #FFD700;
        box-shadow: 0 0 16px rgba(255, 215, 0, 0.4);
    }

    .user-node.root {
        border: 2px solid #FFD700;
        background: linear-gradient(145deg, rgba(28, 38, 64, 0.95), rgba(16, 22, 38, 0.95));
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
    }

    .user-node strong {
        font-size: 11px;
        color: #FFF;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-node small {
        font-size: 9.5px;
        color: #8C9BAE;
    }

    .user-node .status-active {
        color: #00FF88;
        font-weight: 700;
        font-size: 8.5px;
        text-transform: uppercase;
    }

    .user-node .status-inactive {
        color: #FF4757;
        font-weight: 700;
        font-size: 8.5px;
        text-transform: uppercase;
    }

    .toggle-arrow {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #FFD700;
        color: #000;
        font-size: 9px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 0 8px #FFD700;
        z-index: 3;
    }
</style>
@endpush

@section('content')
<div class="mecha-tree-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-sitemap"></i>
            <div>
                <h2 class="mecha-card-title">GENEALOGY HIERARCHY</h2>
                <div class="mecha-card-subtitle">Click nodes to view details & expand downline branches</div>
            </div>
        </div>

        <div style="display: flex; gap: 6px;">
            <button type="button" class="mecha-btn-outline" onclick="zoomIn()" style="height: 32px; padding: 0 10px; font-size: 10px;">
                <i class="fas fa-search-plus"></i>
            </button>
            <button type="button" class="mecha-btn-outline" onclick="zoomOut()" style="height: 32px; padding: 0 10px; font-size: 10px;">
                <i class="fas fa-search-minus"></i>
            </button>
            <button type="button" class="mecha-btn-outline" onclick="resetZoom()" style="height: 32px; padding: 0 10px; font-size: 10px;">
                <i class="fas fa-crosshairs"></i> Reset
            </button>
        </div>
    </div>

    <!-- Scrollable Tree Container -->
    <div class="tree-scroll-container" id="treeScrollContainer">
        <div class="tree" id="mainTree">
            <ul>
                <li>
                    <div class="user-node root" data-user-id="{{ $rootUser->id }}">
                        <strong>{{ $rootUser->name }}</strong>
                        <small><i class="fas fa-id-badge"></i> {{ $rootUser->userid }}</small>
                        <small><i class="fas fa-box"></i> Pkg: ${{ round($rootUser->package, 0) }}</small>
                        <small><i class="fas fa-chart-line"></i> Biz: ${{ round($rootUser->teamtotal, 0) }}</small>
                        <span class="{{ $rootUser->status == 'Active' ? 'status-active' : 'status-inactive' }}">
                            ● {{ $rootUser->status }}
                        </span>
                        <div class="toggle-arrow" onclick="loadChildren(event, this, {{ $rootUser->id }})">▼</div>
                    </div>
                    <ul class="children-container" data-loaded="0" style="display: none;"></ul>
                </li>
            </ul>
        </div>
    </div>

    <div style="text-align: center; margin-top: 10px; font-size: 10px; color: #718096;">
        <i class="fas fa-info-circle"></i> Drag / Scroll horizontally & vertically to navigate tree branches. Click ▼ to load downline nodes.
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentZoom = 1;

    function zoomIn() {
        if (currentZoom < 1.8) {
            currentZoom += 0.15;
            document.getElementById('mainTree').style.transform = `scale(${currentZoom})`;
            document.getElementById('mainTree').style.transformOrigin = 'top center';
        }
    }

    function zoomOut() {
        if (currentZoom > 0.4) {
            currentZoom -= 0.15;
            document.getElementById('mainTree').style.transform = `scale(${currentZoom})`;
            document.getElementById('mainTree').style.transformOrigin = 'top center';
        }
    }

    function resetZoom() {
        currentZoom = 1;
        document.getElementById('mainTree').style.transform = 'scale(1)';
    }

    function loadChildren(e, btn, parentId) {
        if (e) e.stopPropagation();
        const li = btn.closest("li");
        const container = li.querySelector(".children-container");

        if (container.dataset.loaded === "1") {
            if (container.style.display === "none") {
                container.style.display = "flex";
                btn.innerText = "▲";
            } else {
                container.style.display = "none";
                btn.innerText = "▼";
            }
            return;
        }

        btn.innerText = "⌛";

        fetch(`/User/Treeview/children/${parentId}`)
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                container.dataset.loaded = "1";
                container.style.display = "flex";
                btn.innerText = "▲";
            })
            .catch(() => {
                btn.innerText = "!";
            });
    }
</script>
@endpush