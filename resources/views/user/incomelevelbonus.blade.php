@extends('layouts.user-mecha')

@section('title', 'Team Development Reward - Cyera AI')
@section('page-title', 'Team Development Reward')
@section('page-icon', 'fas fa-chart-line')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-chart-line"></i>
            <div>
                <h2 class="mecha-card-title">TEAM DEVELOPMENT LEDGER</h2>
                <div class="mecha-card-subtitle">Performance bonuses from organization development</div>
            </div>
        </div>
        <span class="mecha-card-badge">TEAM MATCHING</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="teamDevTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>AMOUNT ($)</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($referral as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->amountusdt, 3) }}</td>
                        <td class="col-time">{{ $row->txndate }}</td>
                        <td>
                            <a href="{{ url('/User/TeamDevelopmentReward/' . $row->txndate) }}" class="mecha-btn-outline" style="padding: 4px 10px; font-size: 9px; height: auto;">
                                <i class="fas fa-eye"></i> BREAKDOWN
                            </a>
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
        $('#teamDevTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush