@extends('layouts.user-mecha')

@section('title', 'Direct Members - Cyera AI')
@section('page-title', 'Direct Team Members')
@section('page-icon', 'fas fa-users-gear')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-users"></i>
            <div>
                <h2 class="mecha-card-title">DIRECT REFERRALS DIRECTORY</h2>
                <div class="mecha-card-subtitle">All partners sponsored directly by your User ID</div>
            </div>
        </div>
        <a href="{{ url('/User/NewRegistration') }}" class="mecha-btn-gold" style="width: auto; height: 32px; padding: 0 14px; font-size: 10px;">
            <i class="fas fa-user-plus"></i> NEW REGISTRATION
        </a>
    </div>

    <div class="mecha-table-wrap">
        <table id="directTeamTable" class="mecha-cyber-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Joined Date</th>
                    <th>Self Staked ($)</th>
                    <th>Level Unlock</th>
                    <th>Team Turnover ($)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($direct as $row)
                    @php
                        $st = strtolower($row->status ?? '');
                        $pillClass = ($st == '1' || str_contains($st, 'active') || str_contains($st, 'paid')) ? 'success' : 'danger';
                    @endphp
                    <tr>
                        <td style="color: #FFD700; font-weight: 700;">{{ $i }}</td>
                        <td style="font-family: monospace; color: #00E5FF;">{{ $row->userid }}</td>
                        <td style="font-weight: 600;">{{ $row->name }}</td>
                        <td style="color: #8C9BAE; font-size: 10px;">{{ $row->doj }}</td>
                        <td style="color: #00FF88; font-weight: 700;">${{ number_format((float)($row->shares ?? 0), 2) }}</td>
                        <td><span style="background: rgba(255, 215, 0, 0.1); border: 1px solid rgba(255,215,0,0.3); color: #FFD700; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 700;">{{ $row->levelStatus() >= $row->leveluser ? round($row->levelStatus()) : round($row->leveluser) }}%</span></td>
                        <td style="color: #00E5FF; font-weight: 700;">${{ number_format((float)($row->teamtotal ?? 0), 2) }}</td>
                        <td>
                            <span class="status-pill {{ $pillClass }}">
                                <i class="fas {{ $pillClass === 'success' ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
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
        $('#directTeamTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search direct members...",
                lengthMenu: "Show _MENU_ records"
            }
        });
    });
</script>
@endpush