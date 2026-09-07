@extends('layouts.user-mecha')

@section('title', 'Deposit History - Cyera AI')
@section('page-title', 'Deposit History')
@section('page-icon', 'fas fa-clock-rotate-left')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-list-check"></i>
            <div>
                <h2 class="mecha-card-title">DEPOSIT TRANSACTIONS</h2>
                <div class="mecha-card-subtitle">All your past incoming deposits and status</div>
            </div>
        </div>
        <a href="{{ url('/User/Deposit') }}" class="mecha-btn-outline" style="font-size: 10px; height: 32px; padding: 0 12px;">
            <i class="fas fa-plus"></i> NEW DEPOSIT
        </a>
    </div>

    <div class="mecha-table-wrap">
        <table id="depositHistoryTable" class="mecha-cyber-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Amount ($)</th>
                    <th>Network</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($history as $row)
                    @php
                        $st = strtolower($row->status ?? '');
                        $pillClass = 'pending';
                        if (str_contains($st, 'approve') || str_contains($st, 'success') || str_contains($st, 'paid') || $st == '1') {
                            $pillClass = 'success';
                        } elseif (str_contains($st, 'reject') || str_contains($st, 'cancel') || str_contains($st, 'fail')) {
                            $pillClass = 'danger';
                        }
                    @endphp
                    <tr>
                        <td style="color: #FFD700; font-weight: 700;">{{ $i }}</td>
                        <td style="font-family: monospace; color: #00E5FF;">{{ $row->userid }}</td>
                        <td style="font-weight: 600;">{{ $row->usersname }}</td>
                        <td style="color: #00FF88; font-weight: 700; font-size: 12px;">${{ number_format((float)$row->amountusdt, 2) }}</td>
                        <td><span style="background: rgba(255,255,255,0.06); padding: 2px 6px; border-radius: 4px; font-size: 9px; font-family: monospace;">{{ strtoupper($row->currency) }}</span></td>
                        <td style="color: #8C9BAE; font-size: 10px;">{{ $row->created_at }}</td>
                        <td>
                            <span class="status-pill {{ $pillClass }}">
                                <i class="fas {{ $pillClass === 'success' ? 'fa-check' : ($pillClass === 'danger' ? 'fa-xmark' : 'fa-hourglass-half') }}"></i>
                                {{ $row->status }}
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
        $('#depositHistoryTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "",
                searchPlaceholder: "Search deposit records...",
                lengthMenu: "Show _MENU_ records"
            }
        });
    });
</script>
@endpush