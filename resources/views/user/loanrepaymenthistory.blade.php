<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Loan Repayment History</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <!-- DataTables CSS -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <link href="{{asset('ctassets/css/scriptui.css')}}" rel="stylesheet">
   <style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* smooth scroll on mobile */
    scrollbar-width: thin;             /* Firefox: thin scrollbar */
    scrollbar-color: rgba(0,212,255,0.6) rgba(0,0,0,0.1); /* thumb and track */
}

/* Chrome, Edge, Safari */
.table-responsive::-webkit-scrollbar {
    height: 6px; /* horizontal scrollbar height */
}

.table-responsive::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.05); /* light track */
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: rgba(0,212,255,0.6); /* colored thumb */
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: rgba(0,212,255,0.8);
}
   </style>
   </head>
   <body>
      <!-- Premium Animated Background -->
      <div class="bg-animation" id="particles"></div>
      <div class="grid-lines"></div>
      <div class="energy-wave"></div>
      <div class="crypto-icons" id="cryptoIcons"></div>
      <div class="container">
         <!-- Sidebar -->
         @include('ui.sidebaruser')
         <!-- Sidebar -->
         <!-- Main Content -->
         <div class="main-content">
            @include('ui.topbaruser')
            <!-- Enter code here -->
            <div class="deposit-history-container">
               <h2 class="history-title">LOAN REPAYMENT HISTORY</h2>
               <div class="deposit-history-card">
                  <div class="table-responsive">         
                  <table id="depositHistoryTable" class="display nowrap" style="width:100%">
                     <thead>
                        <tr>
													<th>#</th>
													<th>Amount($)</th>
													<th>Date</th>
												</tr>
                     </thead>
                     <tbody>
                     	<?php $i=1; ?>
								      @foreach($reportloan as $reportloan)
												<tr>
													<td>{{$i}}</td>
													<td>{{round($reportloan->amount,2)}}</td>
													<td>{{$reportloan->created_at}}</td>
												</tr>
								      <?php $i++; ?>
					      			@endforeach
                        
                     </tbody>
                  </table>
                  </div>
               </div>
            </div>
         
            <div class="footer">
               <p>© 2026 Cyera AI. All rights reserved.</p>
            </div>
         </div>
      </div>
      <script src="{{asset('ctassets/js/scriptui.js')}}"></script>
      <!-- DataTables JS -->
      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
      <script>
         // Initialize DataTable with custom pagination styling
         $(document).ready(function() {
            var table = $('#depositHistoryTable').DataTable({
               responsive: false, // disable automatic hiding
               scrollX: true,
               pageLength: 10,
               lengthMenu: [[5, 10, 15, 25, 50], [5, 10, 15, 25, 50]],
               order: [[0, 'asc']],
               language: {
                  search: "",
                  searchPlaceholder: "Search transactions...",
                  lengthMenu: "Show _MENU_ entries",
                  info: "Showing _START_ to _END_ of _TOTAL_ entries",
                  infoEmpty: "Showing 0 to 0 of 0 entries",
                  infoFiltered: "(filtered from _MAX_ total entries)",
                  zeroRecords: "No matching records found",
                  paginate: {
                     first: "«",
                     last: "»",
                     next: "›",
                     previous: "‹"
                  }
               },
               initComplete: function() {
                  // Custom styling for pagination
                  $('.dataTables_paginate').addClass('custom-pagination');
                  
                  // Update pagination text
                  $('.paginate_button').each(function() {
                     var text = $(this).text();
                     if (text === '‹') $(this).text('‹');
                     if (text === '›') $(this).text('›');
                     if (text === '«') $(this).text('«');
                     if (text === '»') $(this).text('»');
                  });
                  
                  // Animation for table rows
                  $('#depositHistoryTable tbody tr').each(function(i) {
                     $(this).css('opacity', '0');
                     $(this).css('transform', 'translateX(-10px)');
                     
                     setTimeout(() => {
                        $(this).animate({
                           opacity: 1,
                           transform: 'translateX(0)'
                        }, 300);
                     }, i * 50);
                  });
               }
            });
            
            // Add hover effect
            $('#depositHistoryTable tbody').on('mouseenter', 'tr', function() {
               $(this).addClass('hover-effect');
               $(this).css('transform', 'translateY(-2px)');
               $(this).css('box-shadow', '0 4px 12px rgba(0, 212, 255, 0.1)');
            }).on('mouseleave', 'tr', function() {
               $(this).removeClass('hover-effect');
               $(this).css('transform', 'translateY(0)');
               $(this).css('box-shadow', 'none');
            });
         });
         
         
      </script>
   </body>
</html>