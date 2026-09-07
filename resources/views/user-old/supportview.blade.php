<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Support</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<link rel="icon" type="image/x-icon" href="{{asset('ctassets/img/favicon.ico')}}"/>
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{asset('ctassets/css/vendor.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/css/app.min.css')}}" rel="stylesheet">
	<!-- ================== END core-css ================== -->

	<!-- ================== BEGIN page-css ================== -->
	<link href="{{asset('ctassets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet">
	<!-- ================== END page-css ================== -->
	<style type="text/css">
    #myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
    }
    </style>
</head>
<body>
	<video autoplay muted loop id="myVideo">
      <source src="{{asset('ctassets/myvideo2.mp4')}}" type="video/mp4">
    </video>
	<!-- BEGIN #app -->
	<div id="app" class="app" style="position:relative;">
		<!-- BEGIN #header -->
@include('user.topbaruser')
		<!-- END #header -->
		
		<!-- BEGIN #sidebar -->
@include('user.sidebaruser')
		<!-- END #sidebar -->
			
		<!-- BEGIN mobile-sidebar-backdrop -->
		<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app" data-toggle-class="app-sidebar-mobile-toggled"></button>
		<!-- END mobile-sidebar-backdrop -->
		
		<!-- BEGIN #content -->
		<div id="content" class="app-content">
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="/User/Dashboard">DASHBOARD</a></li>
				<li class="breadcrumb-item active">SUPPORT</li>
			</ul>
			
			<h1 class="page-header">
				Support <!-- <small>page header description goes here...</small> -->
			</h1>
			<button type="button" class="btn btn-outline-theme" data-bs-toggle="modal" data-bs-target="#inputFormModal">Create Ticket</button>
			<hr class="mb-4">
			@if (session('success'))
				<div class="alert alert-success">
					<strong>Success!</strong> {{ session('success') }}
				</div>
			@endif
            @if (session('warning'))
				<div class="alert alert-danger">
					<strong>Alert!</strong> {{ session('warning') }}
				</div>
			@endif
			<!-- BEGIN #datatable -->
								<div id="datatable" class="mb-5">
									
									<div class="card">
										<div class="card-body">
											<table id="datatableDefault" class="table text-nowrap w-100">
												<thead>
													<tr>
														<th>#</th>
			                                            <th>Subject</th>
			                                            <th>Title</th>
			                                            <th>Status</th>
			                                            <th>Action</th>
			                                            <th>Date</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; ?>
                                         			@foreach($ticket as $ticket)
													<tr>
														<td>{{$i}}</td>
														<td>{{$ticket->sub}}</td>
														<td>{{$ticket->title}}</td>
														<td>{{$ticket->status}}</td>
														<td><a href="/User/TicketView/{{str_replace(' ','-',$ticket->title)}}/{{ $ticket->subid}}"><span class="btn btn-outline-theme">View Ticket</span></a></td>
														<td>{{$ticket->created_at}}</td>
													</tr>
													<?php $i++; ?>
                                        			@endforeach
												</tbody>
											</table>
										</div>
										<div class="card-arrow">
											<div class="card-arrow-top-left"></div>
											<div class="card-arrow-top-right"></div>
											<div class="card-arrow-bottom-left"></div>
											<div class="card-arrow-bottom-right"></div>
										</div>
										<div class="hljs-container">
											<pre><code class="xml" data-url="{{asset('ctassets/data/table-plugins/code-1.json')}}"></code></pre>
										</div>
									</div>
								</div>
								<!-- END #datatable -->
		</div>
		<!-- END #content -->
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
		<!-- BEGIN theme-panel -->

		<!-- END theme-panel -->
	</div>
	<!-- END #app -->

	<div class="modal fade" id="inputFormModal">
		<div class="modal-dialog">
			<form action="/User/CreateTicket" method="POST">
            @csrf
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Create Ticket</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="form-group mb-3">
							<label class="form-label" for="exampleFormControlInput1">Select A Topic</label>
							<select id="subject" name="subject" class="form-select @error('subject') is-invalid @enderror">
                                <option value="">Select a Topic</option>
                                <option value="Profile Edit"> Profile Edit </option>
                                <option value="Deposit"> Deposit </option>
                                <option value="Withdraw Related"> Withdraw Related </option>
                                <option value="Others"> Others </option>
                            </select>
							@error('subject')
			                    <span class="invalid-feedback" role="alert">
			                        <strong>{{ $message }}</strong>
			                    </span>
			                @enderror
						</div>
						<div class="form-group mb-3">
							<label class="form-label" for="exampleFormControlInput2">Title</label>
							<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Title" required="">
							@error('title')
			                    <span class="invalid-feedback" role="alert">
			                        <strong>{{ $message }}</strong>
			                    </span>
			                @enderror
						</div>
						<div class="form-group mb-3">
							<label class="form-label" for="exampleFormControlInput1">Message</label>
							<textarea class="form-control @error('message') is-invalid @enderror" name="message" required="" placeholder="Message"></textarea>
							@error('message')
			                    <span class="invalid-feedback" role="alert">
			                        <strong>{{ $message }}</strong>
			                    </span>
			                @enderror
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-outline-theme">Submit</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	<!-- ================== BEGIN page-js ================== -->
	<script src="{{asset('ctassets/plugins/datatables.net/js/dataTables.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/bootstrap-table/dist/bootstrap-table.min.js')}}"></script>
	<script src="{{asset('ctassets/js/demo/table-plugins.demo.js')}}"></script>
	<script src="{{asset('ctassets/js/demo/sidebar-scrollspy.demo.js')}}"></script>
	<!-- ================== END page-js ================== -->
	
	
</body>
</html>
