<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Club Reward</title>
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
    .table-responsive {
    overflow-x: auto;
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
				<li class="breadcrumb-item active">Club Reward</li>
			</ul>
			
			<h1 class="page-header">
				Club Reward 
				<br><small>Power Leg : {{$userDetail->clubBusiness()['first']}} $ @if($userDetail->clubBusiness()['power']) (Power Leg Open) @endif</small>
				<br><small>Other Legs : {{$userDetail->clubBusiness()['rest']}} $</small>
			</h1>

			<hr class="mb-4">
			@foreach($errors as $error)
				<div class="alert alert-danger">
					<strong>Alert!</strong> {{$error->message()}}
				</div>
			@endforeach

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
				@if(sizeof($userDetail->clubIncome()) && ($userDetail->clubIncome()->where('remaining_usdt','>',0)->sum('remaining_usdt')>0))						
						<h4>Request for Club Reward Withdrawal</h4>
						<h5>Pending Amount : <span style="color: #008000;">$ {{$userDetail->clubIncome()->where('remaining_usdt','>',0)->sum('remaining_usdt')}}</span></h5>
						<h5 style="color:#FF0000;">Once the Club Reward withdrawal is initiated, it will be processed within 24 hours.</h5>
						<h5>If you want to exchange the Club for a wallet, there will be no deduction and if you want to take the same as the reward, then 10% deduction will be applicable.</h5>
						<form action="/User/WithdrawClubReward" method="post">
							@csrf
							<div class="row">
								<div class="col-md-4">
									<select type="button" class="form-select" id="selltype" name="currency">
										<option value="wallet">Wallet</option>
										<option value="usdtbep20">USDT</option>
									</select>
								</div>
								<button type="submit" class="btn btn-outline-theme col-md-4" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Request For Withdraw</button>
							</div>
						</form>
						<p><br></p>
					
				@endif
									
			</div>
			
			<!-- BEGIN #datatable -->
								<div id="datatable" class="mb-5">
									
									<div class="card">
										<div class="card-body">
											<table id="datatableDefault" class="table text-nowrap w-100">
												<thead>
													<tr>
														<th>#</th>
														<th>Club Name</th>
														<!-- <th>Amount(CAI)</th> -->
														<th>Amount($)</th>
														<th>Date</th>
													</tr>
												</thead>
												<tbody>
													<?php $i=1; ?>
							            			@foreach($incomeclub as $incomeclub)
													<tr>
														<td>{{$i}}</td>
														<td>{{$incomeclub->clubname}}</td>
														<!-- <td><span style="color:#{{$incomeclub->statusclass}}">{{round($incomeclub->amount,2)}}</span></td> -->
														<td><span style="color:#{{$incomeclub->statusclass}}">{{round($incomeclub->amountusdt,2)}}</span></td>
														<td>{{$incomeclub->created_at}}</td>
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
								 <!-- BEGIN Qualification Table -->
								<div id="qualificationTable" class="mb-5">
									<div class="card">
										<div class="card-body table-responsive">
											<h4 class="mb-3">Club Qualification Details</h4>
											<table id="datatableDefault" class="table text-nowrap w-100">
												<thead>
													<tr>
														<th>#</th>
														<th>Club Name</th>
														<th>Required Business ($)</th>
													
														<th>Amount</th>
														<th>PowerLine($)</th>
														<th>Remaining Line($)</th>
														<th>Achievement Status</th>
													</tr>
												</thead>
												<tbody>
													@php $i = 1; @endphp
													@foreach($clubQualifications as $club)
													<tr>
														<td>{{ $i++ }}</td>
														<td>{{ $club->clubname }}</td>
														<td>{{ number_format($club->business_min, 2) }}</td>
														
														<td>{{ $club->cps_amount }}</td>
														<td>{{ round(($club->powerline*$club->business_min/100),2) }}</td>
														<td>{{ round(($club->remainingline*$club->business_min/100),2) }}</td>
														<td>
															@if((!is_null($userDetail->clubBusiness()['achieved'])) && ($userDetail->clubBusiness()['achieved']->business_min >= $club->business_min))
																<span class="badge bg-success">Achieved</span>
															@else
																<span class="badge bg-danger">Pending</span>
															@endif
														</td>
													</tr>
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
									</div>
								</div>
								<!-- END Qualification Table -->
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
