@extends('layouts.user-mecha')

@section('title', 'Direct Bonus - Cyera AI')
@section('page-title', 'Direct Bonus')
@section('page-icon', 'fas fa-money-bill-wave')

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-money-bill-wave"></i>
            <div>
                <h2 class="mecha-card-title">DIRECT SPONSOR REWARDS</h2>
                <div class="mecha-card-subtitle">Commission earned from direct referrals</div>
            </div>
        </div>
        <span class="mecha-card-badge">DIRECT BONUS</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="directBonusTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>FROM USER ID</th>
                    <th>FROM NAME</th>
                    <th>BONUS ($)</th>
                    <th>DATE & TIME</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($directamount as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td class="col-wallet">{{ $row->fromid }}</td>
                        <td>{{ $row->fromname }}</td>
                        <td class="col-amount gold">${{ number_format((float)$row->amountusdt, 2) }}</td>
                        <td class="col-time">{{ $row->created_at }}</td>
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
        $('#directBonusTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search bonus...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush