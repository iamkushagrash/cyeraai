@extends('layouts.user-mecha')

@section('title', 'Staking Reward - Cyera AI')
@section('page-title', 'Staking Reward')
@section('page-icon', 'fas fa-coins')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-coins"></i>
            <div>
                <h2 class="mecha-card-title">STAKING REWARD LEDGER</h2>
                <div class="mecha-card-subtitle">Daily staking yield accrual history</div>
            </div>
        </div>
        <span class="mecha-card-badge">DAILY ROI</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="stakingRewardTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>AMOUNT ($)</th>
                    <th>STAKING ($)</th>
                    <th>TIMESTAMP</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($roiamount as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->amountusdt, 2) }}</td>
                        <td>${{ number_format((float)$row->principalusdt, 2) }}</td>
                        <td class="col-time">{{ $row->created_at }}</td>
                        <td>
                            @if(strtolower($row->status) == 'success' || strtolower($row->status) == 'paid' || $row->status == '1')
                                <span class="mecha-badge-green">SUCCESS</span>
                            @else
                                <span class="mecha-badge-yellow">{{ strtoupper($row->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(isset($silveramount) && $silveramount > 0)
<!-- Cyber Notice Modal -->
<div class="modal" id="myModal" style="position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.85); z-index: 99999; backdrop-filter: blur(8px);">
    <div class="mecha-hud-card" style="max-width: 480px; width: 92%; margin: 0 auto; max-height: 85vh; overflow-y: auto;">
        <div class="mecha-card-header" style="border-bottom: 1px solid rgba(229, 168, 35, 0.3); padding-bottom: 10px; margin-bottom: 12px;">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-shield-halved" style="color: #FFD700;"></i>
                <div>
                    <h3 class="mecha-card-title" style="font-size: 13px;">IMPORTANT NOTICE</h3>
                    <div class="mecha-card-subtitle">Cyera Protocol Security Policy</div>
                </div>
            </div>
            <button type="button" class="btn-close" onclick="document.getElementById('myModal').remove()" style="background: none; border: none; color: #FFF; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <div style="font-size: 11px; color: #CBD5E1; line-height: 1.6;">
            <p>Dear All Key Leaders and Associates,</p>
            <p style="margin-top: 8px;"><strong style="color: #FFD700;">This is to inform you of an important update regarding Rewards eligibility (Silver Top-Up only).</strong></p>
            <p style="margin-top: 8px;">To further strengthen the system and ensure long-term sustainability, management has implemented a new policy to reinforce stability.</p>
            <p style="margin-top: 8px;"><strong style="color: #00FF88;">You must top up your Silver Wallet with a minimum of 30% of the Silver top-up amount from your Real Wallet to unlock ROI benefits.</strong></p>
            <p style="margin-top: 12px; text-align: right; color: #FFE082; font-weight: 700;">— Cyera AI Management</p>
        </div>
        <div style="margin-top: 16px; text-align: right;">
            <button type="button" class="mecha-btn-gold" onclick="document.getElementById('myModal').remove()" style="width: auto; padding: 6px 18px;">
                UNDERSTOOD
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stakingRewardTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search rewards...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush