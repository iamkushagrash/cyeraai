@extends('layouts.user-mecha')

@section('title', 'Staking Referral Breakdown - Cyera AI')
@section('page-title', 'Staking Referral Details')
@section('page-icon', 'fas fa-list-check')

@section('page-actions')
<a href="{{ url('/User/StakingReferralReward') }}" class="mecha-btn-outline" style="padding: 4px 10px; font-size: 9px; height: auto;">
    <i class="fas fa-arrow-left"></i> BACK
</a>
@endsection

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-list-check"></i>
            <div>
                <h2 class="mecha-card-title">REFERRAL REWARD BREAKDOWN</h2>
                <div class="mecha-card-subtitle">Detailed user transactions for selected date</div>
            </div>
        </div>
        <span class="mecha-card-badge">DAILY AUDIT</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="referralDateTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>FROM USER ID</th>
                    <th>FROM NAME</th>
                    <th>AMOUNT ($)</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($referral as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td class="col-wallet">{{ $row->fromuserid }}</td>
                        <td>{{ $row->fromname }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->amountusdt, 3) }}</td>
                        <td class="col-time">{{ $row->txndate }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#referralDateTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search breakdown...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush
