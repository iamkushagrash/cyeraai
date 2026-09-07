@extends('layouts.user-mecha')

@section('title', 'Team Search - Cyera AI')
@section('page-title', 'Search Team Business')
@section('page-icon', 'fas fa-magnifying-glass-chart')

@section('content')
<!-- Search Form Card -->
<div class="mecha-hud-card" style="margin-bottom: 16px;">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-filter"></i>
            <div>
                <h2 class="mecha-card-title">BUSINESS VOLUME FILTER</h2>
                <div class="mecha-card-subtitle">Search turnover records by User ID and Date Range</div>
            </div>
        </div>
        <span class="mecha-card-badge">QUERY ENGINE</span>
    </div>

    <form action="{{ url('/User/SearchUserTeamBusiness') }}" method="POST">
        @csrf
        <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; align-items: flex-end;">
            <!-- User ID -->
            <div class="mecha-form-group" style="margin-bottom: 0;">
                <label class="mecha-form-label" for="userrid">User ID</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-user-tag mecha-input-icon"></i>
                    <input type="text" id="userrid" name="userrid" class="mecha-input-control @error('userrid') is-invalid @enderror" value="{{ Session::get('user.uuid') }}" placeholder="Search User ID" required>
                </div>
            </div>

            <!-- From Date -->
            <div class="mecha-form-group" style="margin-bottom: 0;">
                <label class="mecha-form-label" for="fromdate">From Date</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-calendar mecha-input-icon"></i>
                    <input type="date" id="fromdate" name="fromdate" class="mecha-input-control @error('fromdate') is-invalid @enderror" value="{{ old('fromdate') }}" required>
                </div>
            </div>

            <!-- To Date -->
            <div class="mecha-form-group" style="margin-bottom: 0;">
                <label class="mecha-form-label" for="todate">To Date</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-calendar-check mecha-input-icon"></i>
                    <input type="date" id="todate" name="todate" class="mecha-input-control @error('todate') is-invalid @enderror" value="{{ old('todate') }}" required>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="mecha-btn-gold" style="height: 44px;">
                    <i class="fas fa-magnifying-glass"></i> RUN SEARCH
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Results Table Card -->
@php $membera = $data ? (array)$data : []; @endphp
@if(sizeof($membera))
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-list-check"></i>
            <div>
                <h2 class="mecha-card-title">SEARCH RESULTS</h2>
                <div class="mecha-card-subtitle">Found matching turnover transactions</div>
            </div>
        </div>
        <div style="background: rgba(0,255,136,0.1); border: 1px solid rgba(0,255,136,0.3); color: #00FF88; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 11px;">
            TOTAL VOLUME: ${{ number_format((float)($totalAmount ?? 0), 2) }}
        </div>
    </div>

    <div class="mecha-table-wrap">
        <table id="searchResultTable" class="mecha-cyber-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Transaction Date</th>
                    <th>Amount ($)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($data as $row)
                    @php
                        $st = strtolower($row->status ?? '');
                        $pillClass = ($st == '1' || str_contains($st, 'active') || str_contains($st, 'paid') || str_contains($st, 'success')) ? 'success' : 'pending';
                    @endphp
                    <tr>
                        <td style="color: #FFD700; font-weight: 700;">{{ $i }}</td>
                        <td style="font-family: monospace; color: #00E5FF;">{{ $row->userid }}</td>
                        <td style="font-weight: 600;">{{ $row->name }}</td>
                        <td style="color: #8C9BAE; font-size: 10px;">{{ $row->txndate }}</td>
                        <td style="color: #00FF88; font-weight: 700;">${{ number_format((float)($row->amountusdt ?? 0), 2) }}</td>
                        <td>
                            <span class="status-pill {{ $pillClass }}">
                                <i class="fas {{ $pillClass === 'success' ? 'fa-circle-check' : 'fa-hourglass-half' }}"></i>
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
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#searchResultTable').length) {
            $('#searchResultTable').DataTable({
                responsive: false,
                scrollX: true,
                pageLength: 10,
                order: [[0, 'asc']],
                language: {
                    search: "",
                    searchPlaceholder: "Filter results..."
                }
            });
        }
    });
</script>
@endpush