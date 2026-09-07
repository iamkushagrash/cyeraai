@extends('layouts.user-mecha')

@section('title', 'Transactions - Cyera AI')
@section('page-title', 'Staking Transactions')
@section('page-icon', 'fas fa-arrow-right-arrow-left')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-network-wired"></i>
            <div>
                <h2 class="mecha-card-title">NETWORK TRANSACTIONS LOG</h2>
                <div class="mecha-card-subtitle">On-chain activation and staking distribution records</div>
            </div>
        </div>
    </div>

    <div class="mecha-table-wrap">
        <table id="packageTxnTable" class="mecha-cyber-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Amount ($)</th>
                    <th>CAI Tokens</th>
                    <th>Daily Rate</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($team as $row)
                    @php
                        $st = strtolower($row->status ?? '');
                        $pillClass = ($st == '1' || str_contains($st, 'active') || str_contains($st, 'success') || str_contains($st, 'paid')) ? 'success' : 'pending';
                    @endphp
                    <tr>
                        <td style="color: #FFD700; font-weight: 700;">{{ $i }}</td>
                        <td style="font-family: monospace; color: #00E5FF;">{{ $row->userid }}</td>
                        <td style="font-weight: 600;">{{ $row->usersname }}</td>
                        <td style="color: #00FF88; font-weight: 700;">${{ number_format((float)$row->usdt, 2) }}</td>
                        <td style="color: #FFD700; font-weight: 700;">{{ number_format((float)$row->amount, 2) }} CAI</td>
                        <td><span style="background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0,255,136,0.3); color: #00FF88; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 9px;">{{ $row->cps }}% PD</span></td>
                        <td style="color: #8C9BAE; font-size: 10px;">{{ $row->created_at }}</td>
                        <td>
                            <span class="status-pill {{ $pillClass }}">
                                <i class="fas {{ $pillClass === 'success' ? 'fa-check' : 'fa-hourglass-half' }}"></i>
                                {{ $row->status == '1' ? 'ACTIVE' : $row->status }}
                            </span>
                        </td>
                    </tr>
                    @php $i++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#packageTxnTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "",
                searchPlaceholder: "Search transaction records...",
                lengthMenu: "Show _MENU_ records"
            }
        });
    });
</script>
@endpush