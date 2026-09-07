<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Team Summary</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<link rel="icon" type="image/x-icon" href="{{asset('ctassets/img/favicon.ico')}}"/>
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{asset('ctassets/css/vendor.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/css/app.min.css')}}" rel="stylesheet">
	<!-- ================== END core-css ================== -->
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
				<li class="breadcrumb-item active">Team Summary</li>
			</ul>
			
			<h1 class="page-header">
				Team Summary <!-- <small>page header description goes here...</small> -->
			</h1>
			
			<div id="basicUsage" class="mb-5">
									
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-xl-6">
													<div class="card">
														<div class="card-header fw-bold small">
															Team Summary
														</div>
														<div class="card-body">
															<h5 class="card-title">Total Direct : {{$details->totaldirect}}</h5>
															<h5 class="card-title">Active Direct : {{$details->activedirect}}</h5>
															<h5 class="card-title">Total Downline : {{$details->totaldownline}}</h5>
															<h5 class="card-title">Active Downline : {{$details->activedownline}}</h5>
															<h5 class="card-title"></h5>
														</div>
														<div class="card-arrow">
															<div class="card-arrow-top-left"></div>
															<div class="card-arrow-top-right"></div>
															<div class="card-arrow-bottom-left"></div>
															<div class="card-arrow-bottom-right"></div>
														</div>
													</div>
												</div>
												<div class="col-xl-6">
													<div class="card">
														<div class="card-header fw-bold small">
															Business Summary
														</div>
														<div class="card-header bg-none p-0">
															<ul class="list-group list-group-flush">
																<li class="list-group-item">Self Investment : $ {{(int)$details->currentself}}</li>
																<li class="list-group-item">Direct Business : $ {{(int)$details->directbusiness}}</li>
																<li class="list-group-item">Total Business : $ {{(int)($details->totalbusiness)}}</li>
																<li class="list-group-item">&nbsp;</li>
															</ul>
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
										<div class="card-arrow">
											<div class="card-arrow-top-left"></div>
											<div class="card-arrow-top-right"></div>
											<div class="card-arrow-bottom-left"></div>
											<div class="card-arrow-bottom-right"></div>
										</div>
										<div class="hljs-container">
											<pre><code class="xml" data-url="ctassets/data/ui-card/code-1.json"></code></pre>
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
	
	
</body>
</html>
