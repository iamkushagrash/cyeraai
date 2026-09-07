@extends('layouts.user-mecha')

@section('title', 'Help & Support - Cyera AI')
@section('page-title', 'Support Tickets')
@section('page-icon', 'fas fa-headset')

@section('page-actions')
<button type="button" class="mecha-btn-gold" onclick="openTicketModal()" style="padding: 6px 14px; font-size: 10px; height: auto;">
    <i class="fas fa-plus"></i> NEW TICKET
</button>
@endsection

@section('content')
<div class="mecha-hud-card">
    <div class="mecha-card-header">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-ticket"></i>
            <div>
                <h2 class="mecha-card-title">SUPPORT REQUESTS</h2>
                <div class="mecha-card-subtitle">Direct communication with Cyera protocol engineers</div>
            </div>
        </div>
        <span class="mecha-card-badge">24/7 HELPDESK</span>
    </div>

    <div class="mecha-table-wrap">
        <table id="supportTicketsTable" class="mecha-cyber-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>TOPIC</th>
                    <th>TITLE</th>
                    <th>STATUS</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($ticket as $t)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td style="color: #FFE082; font-weight: 700;">{{ $t->sub }}</td>
                        <td>{{ $t->title }}</td>
                        <td>
                            @if($t->status == 'Open')
                                <span class="mecha-badge-yellow">OPEN</span>
                            @else
                                <span class="mecha-badge-green">CLOSED</span>
                            @endif
                        </td>
                        <td class="col-time">{{ $t->created_at }}</td>
                        <td>
                            <a href="{{ url('/User/TicketView/' . str_replace(' ', '-', $t->title) . '/' . $t->subid) }}" class="mecha-btn-outline" style="padding: 4px 10px; font-size: 9px; height: auto;">
                                <i class="fas fa-comments"></i> VIEW
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Cyber New Ticket Modal -->
<div class="modal" id="ticketModal" style="position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.85); z-index: 99999; backdrop-filter: blur(8px);">
    <div class="mecha-hud-card" style="max-width: 520px; width: 92%; margin: 0 auto;">
        <div class="mecha-card-header" style="border-bottom: 1px solid rgba(229, 168, 35, 0.3); padding-bottom: 10px; margin-bottom: 16px;">
            <div class="mecha-card-title-wrap">
                <i class="fas fa-headset" style="color: #FFD700;"></i>
                <div>
                    <h3 class="mecha-card-title" style="font-size: 13px;">CREATE SUPPORT TICKET</h3>
                    <div class="mecha-card-subtitle">Submit your query to support</div>
                </div>
            </div>
            <button type="button" class="btn-close" onclick="closeTicketModal()" style="background: none; border: none; color: #FFF; font-size: 18px; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ url('/User/CreateTicket') }}" method="POST">
            @csrf

            <div class="mecha-form-group">
                <label class="mecha-form-label">Support Category</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-list mecha-input-icon"></i>
                    <select name="subject" class="mecha-select-control" required>
                        <option value="">Select Topic</option>
                        <option value="Profile Edit">Profile Edit</option>
                        <option value="Deposit">Deposit</option>
                        <option value="Withdraw Related">Withdraw</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>

            <div class="mecha-form-group">
                <label class="mecha-form-label">Ticket Subject / Title</label>
                <div class="mecha-input-wrap">
                    <i class="fas fa-heading mecha-input-icon"></i>
                    <input type="text" name="title" class="mecha-input-control" placeholder="Brief summary of issue" required>
                </div>
            </div>

            <div class="mecha-form-group">
                <label class="mecha-form-label">Message Details</label>
                <div class="mecha-input-wrap">
                    <textarea name="message" class="mecha-input-control" rows="4" style="height: auto; padding: 10px 14px;" placeholder="Describe your issue or question in detail..." required></textarea>
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="mecha-btn-outline" onclick="closeTicketModal()" style="width: auto; height: 40px; padding: 0 16px;">
                    CANCEL
                </button>
                <button type="submit" class="mecha-btn-gold" style="width: auto; height: 40px; padding: 0 20px;">
                    <i class="fas fa-paper-plane"></i> SUBMIT TICKET
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openTicketModal() {
        $('#ticketModal').fadeIn(200).css('display', 'flex');
    }

    function closeTicketModal() {
        $('#ticketModal').fadeOut(200);
    }

    $(document).ready(function() {
        $('#supportTicketsTable').DataTable({
            responsive: false,
            scrollX: true,
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: "",
                searchPlaceholder: "Search tickets...",
                lengthMenu: "Show _MENU_",
                info: "_START_-_END_ of _TOTAL_",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
@endpush