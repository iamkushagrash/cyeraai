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

    <div class="mecha-table-wrap">
        <table id="stakingHistoryTable" class="mecha-cyber-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Invested ($)</th>
                    <th>CAI Tokens</th>
                    <th>Yield Rate</th>
                    <th>Staked Date</th>
                    <th>Status</th>
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
        $('#stakingHistoryTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "",
                searchPlaceholder: "Search staking records...",
                lengthMenu: "Show _MENU_ records"
            }
        });
    });
</script>
@endpush