@extends('layouts.user-mecha')

@section('title', 'Lifetime Achievement Reward - Cyera AI')
@section('page-title', 'Achievement Reward')
@section('page-icon', 'fas fa-award')

@section('content')
<!-- Lifetime Earnings History -->
<div class="mecha-hud-card" style="margin-bottom: 16px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-award"></i>
            <div>
                <h2 class="mecha-card-title">ACHIEVEMENT REWARD EARNINGS</h2>
                <div class="mecha-card-subtitle">Lifetime rank milestones reached & claimed bonuses</div>
            </div>
        </div>
        <span class="mecha-card-badge">RANK BONUSES</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="lifetimeEarningsTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>REWARD MILESTONE</th>
                    <th>BONUS ($)</th>
                    <th>CLAIM DATE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($incomelifetime as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td style="color: #FFD700; font-weight: 800;">{{ $row->rewardname }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->amount, 2) }}</td>
                        <td class="col-time">{{ $row->created_at }}</td>
                        <td>
                            @if(strtolower($row->status) == 'completed' || strtolower($row->status) == 'paid' || $row->status == '1')
                                <span class="mecha-badge-green">ACHIEVED</span>
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

<!-- Lifetime Qualification Details -->
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-medal"></i>
            <div>
                <h2 class="mecha-card-title">ACHIEVEMENT QUALIFICATION MATRIX</h2>
                <div class="mecha-card-subtitle">Target turnover & 40% : 30% : 30% leg ratios</div>
            </div>
        </div>
        <span class="mecha-card-badge">CAREER TIERS</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="lifetimeQualTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>REWARD TIER</th>
                    <th>TARGET TURNOVER ($)</th>
                    <th>BONUS ($)</th>
                    <th>LEG 1 (40%)</th>
                    <th>LEG 2 (30%)</th>
                    <th>REST (30%)</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php $j = 1; @endphp
                @foreach($rewardQualifications as $reward)
                    <tr>
                        <td>{{ $j++ }}</td>
                        <td style="color: #FFE082; font-weight: 800;">{{ $reward->rewardname }}</td>
                        <td class="col-amount">${{ number_format((float)$reward->business_min, 2) }}</td>
                        <td class="col-amount gold">${{ number_format((float)$reward->cps_amount, 2) }}</td>
                        <td>${{ number_format((float)($reward->firstline * $reward->business_min / 100), 2) }}</td>
                        <td>${{ number_format((float)($reward->secondline * $reward->business_min / 100), 2) }}</td>
                        <td>${{ number_format((float)($reward->remainingline * $reward->business_min / 100), 2) }}</td>
                        <td>
                            @if(count($user->lifetimeIncome()->where('achievementid', $reward->rewardid)))
                                <span class="mecha-badge-green"><i class="fas fa-check-circle"></i> ACHIEVED</span>
                            @elseif(!is_null($user->lifetimeAchievementBusiness()['achieved']) && !is_null($user->lifetimeAchievementBusiness()['achieved']->last()) && $reward->rewardid == $user->lifetimeAchievementBusiness()['achieved']->last()->id)
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
        $('#lifetimeEarningsTable, #lifetimeQualTable').DataTable({
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