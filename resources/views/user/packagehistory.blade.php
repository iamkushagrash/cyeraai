@extends('layouts.user-mecha')

@section('title', 'My Staking - Cyera AI')
@section('page-title', 'My Staking History')
@section('page-icon', 'fas fa-layer-group')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-cubes-stacked"></i>
            <div>
                <h2 class="mecha-card-title">ACTIVE & PAST STAKING PACKAGES</h2>
                <div class="mecha-card-subtitle">On-chain locked CAI positions & daily yield metrics</div>
            </div>
        </div>
        <a href="{{ url('/User/Stake') }}" class="mecha-btn-gold" style="width: auto; height: 32px; padding: 0 14px; font-size: 10px;">
            <i class="fas fa-plus"></i> STAKE MORE
        </a>
    </div>

    <div class="cyber-cards-table-container">
        <table id="stakingHistoryTable" class="cyber-card-datatable" style="width: 100%;">
            <thead>
                <tr style="display: none;">
                    <th>Card</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($basic as $row)
                    @php
                        $st = strtolower($row->status ?? '');
                        $pillClass = ($st == '1' || str_contains($st, 'active') || str_contains($st, 'success') || str_contains($st, 'paid')) ? 'success' : 'pending';
                    @endphp
                    <tr>
                        <td class="cyber-card-td">
                            <div class="staking-cyber-card">
                                <!-- Top Row: Index, User ID, Level & Status -->
                                <div class="card-header-row">
                                    <div class="card-user-info">
                                        <span class="card-index-tag">#{{ $i }}</span>
                                        <span class="card-user-id"><i class="fas fa-fingerprint"></i> {{ $row->userid }}</span>
                                        <span class="card-user-name">{{ $row->usersname }}</span>
                                    </div>
                                    <div class="card-status-wrap">
                                        <span class="status-pill {{ $pillClass }}">
                                            <i class="fas {{ $pillClass === 'success' ? 'fa-circle-check' : 'fa-clock' }}"></i>
                                            {{ $row->status == '1' ? 'ACTIVE' : $row->status }}
                                        </span>
                                    </div>
                                </div>

                                <!-- 4-Column Responsive Metrics Grid -->
                                <div class="card-metrics-grid">
                                    <div class="metric-item">
                                        <span class="metric-lbl">INVESTED ($)</span>
                                        <span class="metric-val val-green">${{ number_format((float)$row->usdt, 2) }}</span>
                                    </div>
                                    <div class="metric-item">
                                        <span class="metric-lbl">CAI TOKENS</span>
                                        <span class="metric-val val-gold">{{ number_format((float)$row->amount, 2) }} <small>CAI</small></span>
                                    </div>
                                    <div class="metric-item">
                                        <span class="metric-lbl">YIELD RATE</span>
                                        <span class="metric-val val-cyan">{{ $row->cps }}% <small>PD</small></span>
                                    </div>
                                    <div class="metric-item">
                                        <span class="metric-lbl">STAKED DATE</span>
                                        <span class="metric-val val-muted"><i class="far fa-calendar-alt"></i> {{ $row->created_at }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php $i++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    .cyber-cards-table-container {
        width: 100%;
        overflow-x: hidden;
    }

    .cyber-card-datatable {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 10px !important;
        background: transparent !important;
        border: none !important;
    }

    .cyber-card-datatable thead {
        display: none !important;
    }

    .cyber-card-datatable tbody tr {
        background: transparent !important;
        border: none !important;
    }

    .cyber-card-datatable tbody td.cyber-card-td {
        padding: 0 0 12px 0 !important;
        border: none !important;
        background: transparent !important;
    }

    /* Staking Cyber Card */
    .staking-cyber-card {
        background: linear-gradient(135deg, rgba(14, 21, 37, 0.94) 0%, rgba(8, 12, 24, 0.98) 100%);
        border: 1px solid rgba(229, 168, 35, 0.22);
        border-left: 3px solid #FFD700;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .staking-cyber-card:hover {
        border-color: rgba(229, 168, 35, 0.5);
        border-left: 3px solid #00FF88;
        box-shadow: 0 8px 24px rgba(229, 168, 35, 0.12);
        transform: translateY(-2px);
    }

    .card-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 10px;
        margin-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        flex-wrap: wrap;
        gap: 8px;
    }

    .card-user-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .card-index-tag {
        background: rgba(255, 215, 0, 0.15);
        border: 1px solid rgba(255, 215, 0, 0.35);
        color: #FFD700;
        font-weight: 800;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .card-user-id {
        font-family: 'Space Mono', monospace;
        color: #00E5FF;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .card-user-name {
        background: rgba(255, 255, 255, 0.05);
        color: #CBD5E1;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .card-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    @media (max-width: 640px) {
        .card-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        .staking-cyber-card {
            padding: 12px;
        }
    }

    .metric-item {
        background: rgba(0, 0, 0, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .metric-lbl {
        font-size: 9px;
        font-weight: 700;
        color: #8C9BAE;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .metric-val {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
    }

    .metric-val small {
        font-size: 9px;
        opacity: 0.8;
    }

    .val-green { color: #00FF88; text-shadow: 0 0 10px rgba(0, 255, 136, 0.25); }
    .val-gold { color: #FFD700; text-shadow: 0 0 10px rgba(255, 215, 0, 0.25); }
    .val-cyan { color: #00E5FF; text-shadow: 0 0 10px rgba(0, 229, 255, 0.25); }
    .val-muted { color: #CBD5E1; font-size: 11px; }

    /* DataTables Controls Layout */
    .dataTables_wrapper {
        padding: 8px 0;
    }

    .dataTables_wrapper .dataTables_length {
        float: left;
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
    }

    .dataTables_wrapper .dataTables_info {
        float: left;
        padding-top: 14px;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        padding-top: 10px;
    }

    @media (max-width: 540px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: center;
            width: 100%;
            margin-bottom: 8px;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
            box-sizing: border-box;
            margin-top: 4px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stakingHistoryTable').DataTable({
            responsive: false,
            pageLength: 10,
            ordering: false,
            language: {
                search: "",
                searchPlaceholder: "Search staking records...",
                lengthMenu: "SHOW _MENU_ RECORDS",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            }
        });
    });
</script>
@endpush