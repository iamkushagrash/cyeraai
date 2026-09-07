<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Staking History</title>
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


	<link href="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.css')}}" rel="stylesheet">
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
				<li class="breadcrumb-item active">Staking History</li>
			</ul>
			
			<h1 class="page-header">
				Staking History <!-- <br><small>Current Rank : </small> -->
			</h1>

			<hr class="mb-4">
			
			<!-- BEGIN #datatable -->
								<div id="datatable" class="mb-5">
									
									<div class="card">
										<div class="card-body">
											<table id="datatableDefault" class="table text-nowrap w-100">
												<thead>
													<tr>
														<th>#</th>
														<th>UserId</th>
														<th>Name</th>
														<th>Amount($)</th>
														<th>Amount(CAI)</th>
														<th>Return</th>
														<th>Date</th>
							              				<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; ?>
							            			@foreach($basic as $basic)
													<tr>
														<td>{{$i}}</td>
														<td>{{$basic->userid}}</td>
														<td>{{$basic->usersname}}</td>
														<td>{{round($basic->usdt,2)}}</td>
														<td>{{round($basic->amount,2)}}</td>
														<td>{{$basic->cps}} % PD</td>
														<td>{{$basic->created_at}}</td>
							              				<td>{{$basic->status}}</td>
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

					<div class="row">
						<div class="col-xl-6 col-lg-6">
							<div  class="mb-5">
								<div class="card">
									<div class="card-body" >
										<h6>Capping Details</h6>
										<div class="h-300px w-300px mx-auto" id="donutchart">
											<canvas id="doughnutChart"></canvas>
										</div>
									</div>
									<div class="card-arrow">
										<div class="card-arrow-top-left"></div>
										<div class="card-arrow-top-right"></div>
										<div class="card-arrow-bottom-left"></div>
										<div class="card-arrow-bottom-right"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

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

	<script src="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.min.js')}}"></script>
	<script src="{{asset('ctassets/js/chart.umd.js')}}"/></script>
	<!-- ================== END page-js ================== -->
	
	<script>
	  var ctx6 = document.getElementById('doughnutChart');
	  var doughnutChart = new Chart(ctx6, {
	    type: 'doughnut',
	    data: {
	      labels: ['Earned Reward', 'Remaining Reward'],
	      datasets: [{
	        data: [{{(is_null($userDetail->totalIncomeUSDT())?0:$userDetail->totalIncomeUSDT())}}, {{(is_null($userDetail->remainingCapping())?0:$userDetail->remainingCapping())}}],
	        backgroundColor: ['rgba(255, 99, 71, 1)', 'rgba(11, 156, 49, 1)',],
	        hoverBackgroundColor: [app.color.theme, app.color.theme, app.color.theme],
	        borderWidth: 0
	      }]
	    }
	  });
	</script>

	
</body>
</html>
