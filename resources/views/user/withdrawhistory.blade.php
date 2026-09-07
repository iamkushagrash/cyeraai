@extends('layouts.user-mecha')

@section('title', 'Withdrawal History - Cyera AI')
@section('page-title', 'Withdrawal History')
@section('page-icon', 'fas fa-clock-rotate-left')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-clock-rotate-left"></i>
            <div>
                <h2 class="mecha-card-title">WITHDRAWAL TRANSACTIONS</h2>
                <div class="mecha-card-subtitle">Complete ledger of all payout requests & blockchain settlements</div>
            </div>
        </div>
        <span class="mecha-card-badge">SETTLEMENTS</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="withdrawalHistoryTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>USER ID</th>
                    <th>NAME</th>
                    <th>AMOUNT ($)</th>
                    <th>FEE ($)</th>
                    <th>NET PAYOUT ($)</th>
                    <th>CURRENCY</th>
                    <th>TIMESTAMP</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($history as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td class="col-wallet">{{ $row->uuid }}</td>
                        <td>{{ $row->usersname }}</td>
                        <td class="col-amount">${{ number_format((float)$row->amountusdt, 2) }}</td>
                        <td style="color: #FF4D7D;">${{ number_format((float)$row->deduction, 2) }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->net_amount, 2) }}</td>
                        <td style="text-transform: uppercase; font-weight: 700; color: #00E5FF;">{{ $row->currency }}</td>
                        <td class="col-time">{{ $row->created_at }}</td>
                        <td>
                            @if(strtolower($row->status) == 'success' || strtolower($row->status) == 'paid' || $row->status == '1' || strtolower($row->status) == 'completed')
                                <span class="mecha-badge-green">PAID</span>
                            @elseif(strtolower($row->status) == 'pending' || strtolower($row->status) == 'processing' || $row->status == '0')
                                <span class="mecha-badge-yellow">PENDING</span>
                            @else
                                <span class="mecha-badge-red">{{ strtoupper($row->status) }}</span>
                            @endif
                        </td>
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
        $('#withdrawalHistoryTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search payouts...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush