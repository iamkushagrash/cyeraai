@extends('layouts.user-mecha')

@section('title', 'Total Team - Cyera AI')
@section('page-title', 'Total Network Team')
@section('page-icon', 'fas fa-sitemap')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-network-wired"></i>
            <div>
                <h2 class="mecha-card-title">UNILEVEL DOWNLINE DIRECTORY</h2>
                <div class="mecha-card-subtitle">Complete team structure filtered by depth levels</div>
            </div>
        </div>
        
        <!-- Level Filter Dropdown -->
        <form method="get" style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 10px; color: #8C9BAE; font-weight: 700; text-transform: uppercase;">FILTER LEVEL:</span>
            <div style="width: 130px;">
                <select name="level" class="mecha-select-control" style="height: 34px; font-size: 11px; padding: 0 8px;" onchange="this.form.submit()">
                    @for($lvl = 1; $lvl <= 101; $lvl++)
                        <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}>
                            Level {{ $lvl }}
                        </option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <div class="mecha-table-wrap">
        <table id="totalTeamTable" class="mecha-cyber-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Registration Date</th>
                    <th>Level</th>
                    <th>Level(%)</th>
                    <th>Staked Package ($)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($totaldown as $row)
                    @php
                        $st = strtolower($row->status ?? '');
                        $pillClass = ($st == '1' || str_contains($st, 'active') || str_contains($st, 'paid')) ? 'success' : 'danger';
                    @endphp
                    <tr>
                        <td style="color: #FFD700; font-weight: 700;">{{ $i }}</td>
                        <td style="font-family: monospace; color: #00E5FF;">{{ $row->userid }}</td>
                        <td style="font-weight: 600;">{{ $row->name }}</td>
                        <td style="color: #8C9BAE; font-size: 10px;">{{ $row->doj }}</td>
                        <td><span style="background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); color: #00E5FF; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 9px;">L{{ $row->level }}</span></td>
                        <td><span style="color: #FFD700; font-weight: 700;">{{ $row->levelStatus() >= $row->leveluser ? round($row->levelStatus()) : round($row->leveluser) }}%</span></td>
                        <td style="color: #00FF88; font-weight: 700;">${{ number_format((float)($row->shares ?? 0), 2) }}</td>
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
        $('#totalTeamTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search downline members...",
                lengthMenu: "Show _MENU_ records"
            }
        });
    });
</script>
@endpush