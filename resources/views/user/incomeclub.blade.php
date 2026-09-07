@extends('layouts.user-mecha')

@section('title', 'Club Reward - Cyera AI')
@section('page-title', 'Club Reward')
@section('page-icon', 'fas fa-trophy')

@section('content')
<!-- Club Reward History -->
<div class="mecha-hud-card" style="margin-bottom: 16px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-trophy"></i>
            <div>
                <h2 class="mecha-card-title">CLUB REWARD EARNINGS</h2>
                <div class="mecha-card-subtitle">Global pool dividends distributed to qualified clubs</div>
            </div>
        </div>
        <span class="mecha-card-badge">GLOBAL POOL</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="clubEarningsTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CLUB NAME</th>
                    <th>DIVIDEND ($)</th>
                    <th>DISTRIBUTION DATE</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($incomeclub as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td style="color: #FFD700; font-weight: 800;">{{ $row->clubname }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->amountusdt, 2) }}</td>
                        <td class="col-time">{{ $row->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Club Qualification Matrix -->
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-award"></i>
            <div>
                <h2 class="mecha-card-title">CLUB QUALIFICATION MATRIX</h2>
                <div class="mecha-card-subtitle">Required turnover and leg distribution criteria</div>
            </div>
        </div>
        <span class="mecha-card-badge">QUALIFICATIONS</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="clubQualTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>CLUB TIER</th>
                    <th>REQ. TURNOVER ($)</th>
                    <th>POWER LEG (40%)</th>
                    <th>REST LEGS (60%)</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php $j = 1; @endphp
                @foreach($clubQualifications as $club)
                    <tr>
                        <td>{{ $j++ }}</td>
                        <td style="color: #FFE082; font-weight: 800;">{{ $club->clubname }}</td>
                        <td class="col-amount">${{ number_format((float)$club->business_min, 2) }}</td>
                        <td>${{ number_format((float)($club->powerline * $club->business_min / 100), 2) }}</td>
                        <td>${{ number_format((float)($club->remainingline * $club->business_min / 100), 2) }}</td>
                        <td>
                            @if((!is_null($userDetail->clubBusiness()['achieved'])) && ($userDetail->clubBusiness()['achieved']->business_min >= $club->business_min))
                                <span class="mecha-badge-green"><i class="fas fa-check-circle"></i> ACHIEVED</span>
                            @else
                                <span class="mecha-badge-yellow">PENDING</span>
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
        $('#clubEarningsTable, #clubQualTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush