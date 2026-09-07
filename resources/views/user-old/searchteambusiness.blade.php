<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Search Team Business</title>
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
				<li class="breadcrumb-item active">Search Team BusinessL</li>
			</ul>
			
			<h1 class="page-header">
				Search Team Business <!-- <small>page header description goes here...</small> -->
			</h1>
			@foreach($errors as $error)
				<div class="alert alert-danger">
					<strong>Alert!</strong> {{$error->message()}}
				</div>
			@endforeach
			<hr class="mb-4">

			<div class="row">
				<form action="/User/SearchUserTeamBusiness" method="post">
					@csrf
					<div class="card">
						<div class="card-body">
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
							<div class="row">
									<div class="col-xl-3 col-md-3">
										<div class="form-group mb-3">
											<label class="form-label" for="exampleFormControlInput1"><h6>Userid</h6></label>
											<input type="text" id="userrid" class="form-control @error('userrid') is-invalid @enderror" name="userrid" value="{{ Session::get('user.uuid') }}" placeholder="Search UserID" required>
											@error('userrid')
				                <span class="invalid-feedback" role="alert">
				                  <strong>{{ $message }}</strong>
				                </span>
				              @enderror
										</div>
									</div>
									<div class="col-xl-3 col-md-3">
										<div class="form-group mb-3">
											<label class="form-label" for="exampleFormControlInput1"><h6>From Date</h6></label>
											<input type="date" id="fromdate" class="form-control @error('fromdate') is-invalid @enderror" name="fromdate" value="" placeholder="From Date" required>
											@error('fromdate')
				                <span class="invalid-feedback" role="alert">
				                  <strong>{{ $message }}</strong>
				                </span>
				              @enderror
										</div>
									</div>
									<div class="col-xl-3 col-md-3">
										<div class="form-group mb-3">
											<label class="form-label" for="exampleFormControlInput1"><h6>To Date</h6></label>
											<input type="date" id="todate" class="form-control @error('todate') is-invalid @enderror" name="todate" value="" placeholder="To Date" required>
											@error('todate')
				                <span class="invalid-feedback" role="alert">
				                  <strong>{{ $message }}</strong>
				                </span>
				              @enderror
										</div>
									</div>
									<div class="col-xl-3 col-md-3">
										<label class="form-label"><br><br></label>
										<button type="submit" class="btn btn-theme mb-1" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Search</button>
									</div>
							</div>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<div class="hljs-container">
							<pre><code class="xml" data-url="{{asset('assets/data/ui-card/code-1.json')}}"></code></pre>
						</div>
					</div>
				</form>
			</div>
			<!-- BEGIN #datatable -->
							<?php $membera=$data?(array)$data:array();?>
                @if(sizeof($membera))
                <h3><br>Total Amount: $ {{ round($totalAmount, 2) }}</h3>
								<div id="datatable" class="mb-5">
									
									<div class="card">
										<div class="card-body">
											<table id="datatableDefault" class="table text-nowrap w-100">
												<thead>
													<tr>
														<th>#</th>
														<th>User ID</th>
														<th>Name</th>
														<th>Date</th>
														<th>Amount($)</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; ?>
                          @foreach($data as $data)
                            <tr style="color: #fff;">
                              <td>{{$i}}</td>
                              <td>{{$data->userid}}</td>
                              <td>{{$data->name}}</td>
                              <td>{{$data->txndate}}</td>
                              <td>{{round($data->amountusdt,2)}}</td>
                              <td><span class="{{$data->statusclass}}">{{$data->status}}</span></td>
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
								 @endif
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
