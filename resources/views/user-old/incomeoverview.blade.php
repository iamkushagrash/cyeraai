<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Income Overview</title>
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
	<link href="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.css')}}" rel="stylesheet">
	<!-- ================== END page-css ================== -->
	<style>
	#myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
    }

    .progress-wrapper {
      max-width: 600px;
      margin: 30px auto;
      position: relative;
    }

    progress {
      width: 100%;
      height: 40px;
      -webkit-appearance: none;
      appearance: none;
      border: none;
      border-radius: 20px;
      background-color: #eaeaea;
      overflow: hidden;
    }

    /* WebKit (Chrome, Safari, Edge) */
    progress::-webkit-progress-bar {
      background-color: #eaeaea;
      border-radius: 20px;
    }

    /* Label inside progress */
    .progress-label {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      pointer-events: none;
      font-size: 16px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    /* GOLDEN progress fill */
    progress.gold::-webkit-progress-value {
      background: linear-gradient(90deg, #FFD700, #FFA500);
      border-radius: 20px 0 0 20px;
      transition: width 0.6s ease-in-out;
    }

    progress.gold::-moz-progress-bar {
      background: linear-gradient(90deg, #FFD700, #FFA500);
      border-radius: 20px 0 0 20px;
      transition: width 0.6s ease-in-out;
    }

    /* BLUE progress fill */
    progress.blue::-webkit-progress-value {
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      border-radius: 20px 0 0 20px;
      transition: width 0.6s ease-in-out;
    }

    progress.blue::-moz-progress-bar {
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      border-radius: 20px 0 0 20px;
      transition: width 0.6s ease-in-out;
    }

    .bar-title {
	  margin-bottom: 12px; /* Increased spacing */
	  font-weight: 600;
	  text-align: center;
	  font-size: 16px;
	  color: #fff;
	}
	.progress-box {
	  position: relative;
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
				<li class="breadcrumb-item active">Income Overview</li>
			</ul>
			
			<h1 class="page-header">
				Income Overview 
			</h1>
			<!-- BEGIN row -->
			<div class="row">
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
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Total Income</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0"> {{round($data['userDetail']->totalIncomeUSDT(),2)}} $</h3>
									<!-- <h3 class="mb-0"> ~ ({{round($data['userDetail']->totalIncomeUSDT(),2)}}) $</h3> -->
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release :  {{round($data['userDetail']->remainingIncome(),2)}} $<br>
								<!-- <i class="far fa-user fa-fw me-1"></i> 45.5% new visitors<br>
								<i class="far fa-times-circle fa-fw me-1"></i> 3.25% bounce rate -->
							</div>
							<div style="text-align:right; margin-top:30px;"><p></p><!-- <a href="/User/MonthlyRental" class="btn btn-outline-theme btn-sm">View</a> --></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Total Withdraw</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0">$ {{round($data['totalwithdraw']->amount,2)}}</h3>
								</div>
								<!-- <div class="col-5">
									<div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors" data-height="45"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate"><br>
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> 5.3% more than last week<br>
								<i class="far fa-hdd fa-fw me-1"></i> 10.5% from total usage<br>
								<i class="far fa-hand-point-up fa-fw me-1"></i> 2MB per visit -->
							</div>
							<div style="text-align:right;"><a href="/User/WithdrawalHistory" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->

				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Direct Reward</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0"> {{round($data['userDetail']->bonusReward()->where('status','!=',3)->sum('amt_usdt'),2)}} $</h3>
									<!-- <h3 class="mb-0"> ~ ({{round($data['userDetail']->bonusReward()->where('status','!=',3)->sum('amt_usdt'),2)}}) $</h3> -->
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release : {{round($data['userDetail']->bonusReward()->where('status','!=',3)->sum('remaining_usdt'),2)}} $<br>
								<!-- <i class="far fa-user fa-fw me-1"></i> 45.5% new visitors<br>
								<i class="far fa-times-circle fa-fw me-1"></i> 3.25% bounce rate -->
							</div>
							<div style="text-align:right;"><a href="/User/DirectBonus" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->

				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Staking Reward</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0">{{round($data['userDetail']->stackingIncome()->sum('amt_usdt'),2)}} $</h3>
									<!-- <h3 class="mb-0"> ~ ({{round($data['userDetail']->stackingIncome()->sum('amt_usdt'),2)}}) $</h3> -->
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release : {{round($data['userDetail']->stackingIncome()->sum('remaining_usdt'),2)}} $<br>
								<!-- <i class="far fa-user fa-fw me-1"></i> 45.5% new visitors<br>
								<i class="far fa-times-circle fa-fw me-1"></i> 3.25% bounce rate -->
							</div>
							<div style="text-align:right;"><a href="/User/StakingReward" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Staking Referral Reward</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0"> {{round($data['userDetail']->levelIncome()->where('description','l')->sum('amt_usdt'),2)}} $</h3>
									<!-- <h3 class="mb-0"> ~ ({{round($data['userDetail']->levelIncome()->where('description','l')->sum('amt_usdt'),2)}}) $</h3> -->
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release : {{round($data['userDetail']->levelIncome()->where('description','l')->sum('remaining_usdt'),2)}} $<br> 
								<!-- <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br>
								<i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate -->
							</div>
							<div style="text-align:right;"><a href="/User/StakingReferralReward" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Team Development Reward</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0"> {{round($data['userDetail']->levelIncome()->where('description','r')->sum('amt_usdt'),2)}} $</h3>
									<!-- <h3 class="mb-0"> ~ ({{round($data['userDetail']->levelIncome()->where('description','r')->sum('amt_usdt'),2)}}) $</h3> -->
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release : {{round($data['userDetail']->levelIncome()->where('description','r')->sum('remaining_usdt'),2)}} $<br> 
								<!-- <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br>
								<i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate -->
							</div>
							<div style="text-align:right;"><a href="/User/TeamDevelopmentReward" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Club Reward</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0"> {{round($data['userDetail']->clubIncome()->sum('amt_usdt'),2)}} $</h3>
									<!-- <h3 class="mb-0"> ~ ({{round($data['userDetail']->clubIncome()->sum('amt_usdt'),2)}}) $</h3> -->
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release : {{round($data['userDetail']->clubIncome()->sum('remaining_usdt'),2)}} $<br> 
								<!-- <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br>
								<i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate -->
							</div>
							<div style="text-align:right;"><a href="/User/ClubReward" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Achievement Reward</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h3 class="mb-0">{{round($data['userDetail']->lifetimeIncome()->sum('amount'),2)}} $</h3>
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> Ready to release : {{round($data['userDetail']->lifetimeIncome()->sum('remaining'),2)}} $
								<!-- <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br>
								<i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate -->
							</div>
							<div style="text-align:right;"><a href="/User/LifetimeAchievementReward" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Booster Status</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									@if($data['userDetail']->booster==2)
									<h3 class="mb-0" style="color:#3cd2a5;"> Active</h3>
									@else
									<h3 class="mb-0" style="color:#FF0000;"> Inactive</h3>
									@endif
								</div>
								<!-- <div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate"><br>
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> --> <!-- Ready to release : 0 M<br>  -->
								<!-- <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br>
								<i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate -->
							</div>
							<div style="text-align:right;"><a href="/User/StakingReward" class="btn btn-outline-theme btn-sm">View</a></div>
							<!-- END stat-sm -->
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->

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

					<div class="col-xl-6 col-lg-6">
						<div  class="mb-5">
							<div class="card">
								<div class="card-body" >
									<h6>Business Details (Club Rewards)</h6>
									<h6 style="color:#3cd2a5;">Achieved Club: @if(!is_null($data['userDetail']->clubBusiness()['achieved'])){{$data['userDetail']->clubBusiness()['achieved']->clubname}} ({{round($data['userDetail']->clubBusiness()['achieved']->business_min)}} $) @else Not Achieved @endif</h6>
									<h6>Next Club: {{$data['userDetail']->clubBusiness()['next']->clubname}} ({{round($data['userDetail']->clubBusiness()['next']->business_min)}} $)</h6>

									<!-- Golden Progress Bar -->
									  <div class="progress-wrapper">
									    <div class="bar-title">Power Leg ({{$data['userDetail']->clubBusiness()['first']}} $)</div>
									     <div class="progress-box">
										    <progress class="gold" id="goldProgress" max="100" value="0"></progress>
										    <div class="progress-label" id="goldLabel">0%</div>
										 </div>
									  </div>

									  <!-- Blue Progress Bar -->
									  <div class="progress-wrapper">
									    <div class="bar-title">Other Legs ({{$data['userDetail']->clubBusiness()['rest']}} $)</div>
									     <div class="progress-box">
										    <progress class="blue" id="blueProgress" max="100" value="0"></progress>
										    <div class="progress-label" id="blueLabel">0%</div>
										 </div>
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


					<div class="col-xl-6 col-lg-6">
						<div  class="mb-5">
							<div class="card">
								<div class="card-body" >
									<h6>Business Details (Lifetime Achievement Rewards)</h6>
									<h6 style="color:#3cd2a5;">Achieved Reward: @if(!is_null($data['userDetail']->lifetimeAchievementBusiness()['achieved']) && !is_null($data['userDetail']->lifetimeAchievementBusiness()['achieved']->last())){{$data['userDetail']->lifetimeAchievementBusiness()['achieved']->last()->rewardname}} ({{round($data['userDetail']->lifetimeAchievementBusiness()['achieved']->last()->business_min)}} $) @else Not Achieved @endif</h6>
									<h6>Next Reward: {{$data['userDetail']->lifetimeAchievementBusiness()['next']->rewardname}} ({{round($data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)}} $)</h6>

									<!-- Golden Progress Bar -->
									  <div class="progress-wrapper">
									    <div class="bar-title">Power Leg ({{$data['userDetail']->lifetimeAchievementBusiness()['first']}} $)</div>
									     <div class="progress-box">
										    <progress class="gold" id="goldProgress1" max="100" value="0"></progress>
										    <div class="progress-label" id="goldLabel1">0%</div>
										 </div>
									  </div>

									  <!-- Blue Progress Bar -->
									  <div class="progress-wrapper">
									    <div class="bar-title">Second Leg ({{$data['userDetail']->lifetimeAchievementBusiness()['second']}} $)</div>
									     <div class="progress-box">
										    <progress class="blue" id="blueProgress1" max="100" value="0"></progress>
										    <div class="progress-label" id="blueLabel1">0%</div>
										 </div>
									  </div>

									  <!-- Blue Progress Bar -->
									  <div class="progress-wrapper">
									    <div class="bar-title">Other Legs ({{$data['userDetail']->lifetimeAchievementBusiness()['rest']}} $)</div>
									     <div class="progress-box">
										    <progress class="blue" id="blueProgress2" max="100" value="0"></progress>
										    <div class="progress-label" id="blueLabel2">0%</div>
										 </div>
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
			<!-- END row -->
		</div>
		<!-- END #content -->
		
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	
	<!-- ================== BEGIN page-js ================== -->
	<script src="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.min.js')}}"></script><!-- 
	<script src="{{asset('ctassets/plugins/jvectormap-content/world-mill.js')}}"></script>
	<script src="{{asset('ctassets/plugins/apexcharts/dist/apexcharts.min.js')}}"></script> --><!-- 
	<script src="{{asset('ctassets/js/demo/dashboard.demo.js')}}"></script> --><script src="{{asset('ctassets/js/chart.umd.js')}}"/></script>
	<!-- ================== END page-js ================== -->
	<script type="text/javascript">
    function copylnk() {
      
      /*var copyText = document.getElementById("lnktxt");

      copyText.select();
      copyText.setSelectionRange(0, 99999);*/ /*For mobile devices*/
      var text = document.getElementById("p1").innerText;
	    var elem = document.createElement("textarea");
	    document.body.appendChild(elem);
	    elem.value = text;
	    elem.select();

      document.execCommand("copy");

    	document.body.removeChild(elem);

      alert("Link Copied : " + elem.value);
    }
    
    </script>
    <script>
	  var ctx6 = document.getElementById('doughnutChart');
	  var doughnutChart = new Chart(ctx6, {
	    type: 'doughnut',
	    data: {
	      labels: ['Earned Reward : {{round((is_null($data['userDetail']->totalIncomeUSDT())?0:$data['userDetail']->totalIncomeUSDT()),2)}}', 'Remaining Reward : {{round((is_null($data['userDetail']->remainingCapping())?0:$data['userDetail']->remainingCapping()),2)}}'],
	      datasets: [{
	        data: [{{(is_null($data['userDetail']->totalIncomeUSDT())?0:$data['userDetail']->totalIncomeUSDT())}}, {{(is_null($data['userDetail']->remainingCapping())?0:$data['userDetail']->remainingCapping())}}],
	        backgroundColor: ['rgba(255, 99, 71, 1)', 'rgba(11, 156, 49, 1)',],
	        hoverBackgroundColor: [app.color.theme, app.color.theme, app.color.theme],
	        borderWidth: 0
	      }]
	    }
	  });
	</script>
	<script>
    // Set your values
    const total1 = {{(is_null($data['userDetail']->clubBusiness()['next']->business_min)?0:$data['userDetail']->clubBusiness()['next']->business_min)}}, current1 = {{$data['userDetail']->clubBusiness()['first']}}; // Golden progress
    const total2 = {{(is_null($data['userDetail']->clubBusiness()['next']->business_min)?0:$data['userDetail']->clubBusiness()['next']->business_min)}}, current2 = {{$data['userDetail']->clubBusiness()['rest']}};  // Blue progress

    const percent1 = (current1 / (total1*0.4)) * 100;
    const percent2 = (current2 / (total2*0.6)) * 100;

    // Golden bar
    document.getElementById('goldProgress').value = percent1;
    document.getElementById('goldLabel').textContent = percent1.toFixed(1) + '%';

    // Blue bar
    document.getElementById('blueProgress').value = percent2;
    document.getElementById('blueLabel').textContent = percent2.toFixed(1) + '%';
  </script>
	<script>
    // Set your values
    const total11 = {{(is_null($data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)?0:$data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)}}, current11 = {{$data['userDetail']->lifetimeAchievementBusiness()['first']}}; // Golden progress
    const total22 = {{(is_null($data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)?0:$data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)}}, current22 = {{$data['userDetail']->lifetimeAchievementBusiness()['second']}};  // Blue progress
    const total33 = {{(is_null($data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)?0:$data['userDetail']->lifetimeAchievementBusiness()['next']->business_min)}}, current33 = {{$data['userDetail']->lifetimeAchievementBusiness()['rest']}};  // Blue progress

    const percent11 = (current11 / (total11*0.4)) * 100;
    const percent22 = (current22 / (total22*0.3)) * 100;
    const percent33 = (current33 / (total33*0.3)) * 100;

    // Golden bar
    document.getElementById('goldProgress1').value = percent11;
    document.getElementById('goldLabel1').textContent = percent11.toFixed(1) + '%';

    // Blue bar
    document.getElementById('blueProgress1').value = percent22;
    document.getElementById('blueLabel1').textContent = percent22.toFixed(1) + '%';

    // Blue bar
    document.getElementById('blueProgress2').value = percent33;
    document.getElementById('blueLabel2').textContent = percent33.toFixed(1) + '%';
  </script>
    
	
</body>
</html>