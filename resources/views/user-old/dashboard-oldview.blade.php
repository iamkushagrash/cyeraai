<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<link rel="icon" type="image/x-icon" href="{{asset('ctassets/img/favicon.ico')}}"/>
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{asset('ctassets/css/vendor.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/css/app.min.css')}}" rel="stylesheet">
	<!-- Confetti Library -->
	<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
	<!-- ================== END core-css ================== -->
	
	<!-- ================== BEGIN page-css ================== -->
	<link href="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.css')}}" rel="stylesheet">
	<!-- ================== END page-css ================== -->
	<style type="text/css">
	.blink_me {
	  animation: blinker 1s linear infinite;
	}

	@keyframes blinker {
	  50% {
	    opacity: 0;
	  }
	}
	.table-container {
	  max-height: 150px; /* or fixed height like height: 300px */
	  overflow-y: auto;
	  display: block;
	}

	/* Optional: make the table take full width */
	.table-container table {
	  width: 100%;
	  border-collapse: collapse;
	}
	#myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
	  filter: sepia(100%) hue-rotate(15deg) saturate(600%) brightness(120%);
    }
.marquee {
  width: 100%;
  overflow: hidden;
  padding: 10px 0;
  font-weight: bold;
  position: relative;
}

.marquee-content {
  display: inline-block;
  white-space: nowrap;
  animation: scroll-left 20s linear infinite; /* slower speed */
  font-size: 15px;
  color: #fff; /* all text white */
}


.username-highlight {
  background: #d2ab34; /* golden shade */
  color: #000; /* black text for username */
  padding: 2px 6px;
  border-radius: 4px;
  font-weight: 600;
}

@keyframes scroll-left {
  0%   { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}

	.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.popup-content {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.popup-content video {
    width: 100%;
    height: auto;
    display: block;
}

/* Close button */
.close-popup {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    color: #fff;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    cursor: pointer;
    transition: background 0.2s;
    z-index: 2000; 
}
.close-popup:hover {
    background: rgba(255, 0, 0, 0.8);
}

/* Click to play button */
.click-to-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 14px 22px;
    border-radius: 40px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    transition: background 0.3s, transform 0.2s;
    display: none; /* hide by default */
}
.click-to-play:hover {
    background: rgba(255, 165, 0, 0.9);
    transform: translate(-50%, -50%) scale(1.05);
}

/* Responsive adjustments */
@media (min-width: 768px) {
    .popup-content {
        max-width: 600px;
    }
}

@media (max-width: 767px) {
    .popup-content {
        max-width: 95%;
    }
    .click-to-play {
        font-size: 14px;
        padding: 12px 18px;
    }
}


	</style>
	<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
</style>
</head>
<body>
	<video autoplay muted loop id="myVideo">
      <source src="{{asset('ctassets/myvideo2.mp4')}}" type="video/mp4">
    </video>
	<!-- <div id="popupOverlay" class="popup-overlay">
		<div class="popup-content">
			<span class="close-popup">&times;</span>
			<video id="popupVideo" playsinline>
				<source src="{{asset('ctassets/popupvideo.mp4')}}" type="video/mp4">
				Your browser does not support the video tag.
			</video>
			<div id="clickToPlay" class="click-to-play">▶ Tap to Play</div>
		</div>
	</div> -->


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
			<div class="marquee">
 <div class="marquee-content">
   🚀 Hello <span class="username-highlight">{{ Session::get('user.name') }} !</span> Your journey to more rewards, growth, and success on CAI Dashboard starts now. Keep earning more every day! 💰
</div>
</div>
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
				<div class="row">

				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">DIRECT TEAM</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h4 class="mb-0">{{$data['userdetails']->totaldirect}}</h4>
								</div>
								<div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="bar" data-title="Visitors" data-height="30"></div>
								</div>
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<i class="fa fa-chevron-up fa-fw me-1"></i> Active : {{$data['userdetails']->activedirect}}<br>
								<!-- <i class="far fa-user fa-fw me-1"></i> 45.5% new visitors<br>
								<i class="far fa-times-circle fa-fw me-1"></i> 3.25% bounce rate -->
							</div>
							<div style="text-align:right;"><a href="/User/DirectTeam" class="btn btn-outline-theme btn-sm">View</a></div>
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
								<span class="flex-grow-1">TOTAL TEAM</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h4 class="mb-0">{{$data['userdetails']->totaldownline}}</h4>
								</div>
								<div class="col-5">
									<div class="mt-n2" data-render="apexchart" data-type="line" data-title="Visitors" data-height="30"></div>
								</div>
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<i class="fa fa-chevron-up fa-fw me-1"></i> Active : {{$data['userdetails']->activedownline}}<br>
								<!-- <i class="fa fa-shopping-bag fa-fw me-1"></i> 33.5% new orders<br>
								<i class="fa fa-dollar-sign fa-fw me-1"></i> 6.21% conversion rate -->
							</div>
							<div style="text-align:right;"><a href="/User/AllTeam" class="btn btn-outline-theme btn-sm">View</a></div>
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
				<span class="flex-grow-1">MY INVESTMENT / <span style=color:red> Loan</span></span>
			</div>
			<!-- END title -->

			<!-- BEGIN stat-lg -->
			<div class="row align-items-center mb-2">
				<div class="col-7">
					<h4 class="mb-0">$ {{ round($data['userdetails']->currentself,2) }} /<br>
					
					{{-- ✅ Show loan amount if available --}}
					@if(!is_null($data['userDetail']->userLoanStatus()))
						
					<span style="color:red">$ {{ round($data['userDetail']->userLoanStatus()->amount,2) }}</span>
					
					@endif
					</h4>
				</div>
				<div class="col-5">
					<div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors" data-height="45"></div>
				</div>
			</div>
			<!-- END stat-lg -->

			<!-- BEGIN stat-sm -->
			<div class="small text-inverse text-opacity-50 text-truncate">
				<i class="fa fa-chevron-up fa-fw me-1"></i> Current : $ {{ round($data['userdetails']->currentself,2) }}<br>
			</div>
			<div style="text-align:right;">
				<a href="/User/StakingHistory" class="btn btn-outline-theme btn-sm">View</a>
			</div>
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
								<span class="flex-grow-1">BUSINESS</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h4 class="mb-0">$ {{round($data['userdetails']->levelbusiness+$data['userdetails']->directbusiness)}}</h4>
								</div>
								<div class="col-5">
									<div class="mt-n3 mb-n2" data-render="apexchart" data-type="pie" data-title="Visitors" data-height="45"></div>
								</div>
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<div class="small text-inverse text-opacity-50 text-truncate">
								<i class="fa fa-chevron-up fa-fw me-1"></i> Direct : $ {{round($data['userdetails']->directbusiness,2)}}<br>
								<!-- <i class="fab fa-facebook-f fa-fw me-1"></i> 45.5% from facebook<br>
								<i class="fab fa-youtube fa-fw me-1"></i> 15.25% from youtube -->
							</div>
							<div style="text-align:right;"><a href="/User/TeamSummary" class="btn btn-outline-theme btn-sm">View</a></div>
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
				</div>
				
				<div class="row">
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">TOTAL WITHDRAW</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h4 class="mb-0">$ {{round($data['totalwithdraw']->amountusdt,2)}}</h4>
								</div>
								<div class="col-5">
									<div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors" data-height="45"></div>
								</div>
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
				

				<!-- BEGIN col-6 -->
				<div class="col-xl-6 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title">Invite Referral Link</h5>
							<p id="p1" class="card-text">{{url('/').'/register/'.Session::get('user.uuid')}}</p>
							<input type="text" id="lnktxt" class="form-control-static with-border" value="{{url('/').'/register/'.Session::get('user.userid')}}" style=" width: 100%; padding: 6px 12px;display: none;" readonly="">
							<br>
							<a href="javascript:void(0);" onclick="copylnk()" class="btn btn-outline-theme btn-sm">Copy Link</a>
							 &nbsp;Or Share
							<a href="https://www.facebook.com/sharer.php?u={{url('/').'/register/'.Session::get('user.uuid')}}" target="_blank"><i class="fab fa-lg fa-fw me-2 fa-facebook"></i></a>
                    		<a href="https://twitter.com/intent/tweet?url={{url('/').'/register/'.Session::get('user.uuid')}}&text=CAI%20Referral%20Link" target="_blank"><i class="fab fa-lg fa-fw me-2 fa-twitter"></i></a>
                    		<a href="https://wa.me/?text=Use%20my%20referral%20link%20to%20join%CAI%20{{url('/').'/register/'.Session::get('user.uuid')}}" target="_blank"><i class="fab fa-lg fa-fw me-2 fa-whatsapp"></i></a>
                    		<a href="https://telegram.me/share/url?url={{url('/').'/register/'.Session::get('user.uuid')}}&text=Use my referral link to join CAI" target="_blank"><i class="fab fa-lg fa-fw me-2 fa-telegram"></i></a>
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
				<!-- END col-6 -->
				 	<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">Wallet Statement</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-12">
									<h4 class="mb-0">$ {{round($data['outgoingfund'],2)}} / {{round($data['incomingfund'],2)}}</h4>
									<h4 class="mb-0"><br></h4>
								</div>
								<!-- <div class="col-5">
									<div class="mt-n3 mb-n2" data-render="apexchart" data-type="donut" data-title="Visitors" data-height="45"></div>
								</div> -->
							</div>
							<!-- END stat-lg -->
							<!-- BEGIN stat-sm -->
							<!-- <div class="small text-inverse text-opacity-50 text-truncate"> -->
								<!-- <i class="fa fa-chevron-up fa-fw me-1"></i> 5.3% more than last week<br>
								<i class="far fa-hdd fa-fw me-1"></i> 10.5% from total usage<br>
								<i class="far fa-hand-point-up fa-fw me-1"></i> 2MB per visit -->
							<!-- </div> -->
							<div style="text-align:right;"><a href="/User/StakingTxnHistory" class="btn btn-outline-theme btn-sm">View</a></div>
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
				</div>

				<div class="row">
				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title"> <img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['userDetail']->stackingIncome()->sum('amount'),2)}}</h5>
								<p class="card-text">Staking Reward</p>
								<a href="/User/StakingReward" class="btn btn-outline-theme">View</a>
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
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title"><img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['totaldirectreceived']->totaldirect,2)}}</h5>
								<p class="card-text">Direct Reward</p>
								<a href="/User/DirectBonus" class="btn btn-outline-theme">View</a>
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
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title d-flex justify-content-between align-items-center">
								<span>
									<img src="{{ asset('main/assets/images/coinblack.png') }}" 
										alt="coin" 
										style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;">
									{{ round($data['userDetail']->levelIncome()->where('description','l')->sum('amount'),2) }}
								</span>

								<span class="badge border border-theme text-theme fs-6">
									{{ $data['level'] }}%
								</span>
							</h5>

								<p class="card-text">Staking Referral Reward</p>
								<a href="/User/StakingReferralReward" class="btn btn-outline-theme">View</a>
								
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
				<!-- END col-4 -->

				<!-- </div>

				<div class="row"> -->
				

				</div>


				<div class="row">

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title"><img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['userDetail']->levelIncome()->where('description','r')->sum('amount'),2)}}</h5>
								<p class="card-text">Team Development</p>
								<a href="/User/TeamDevelopmentReward" class="btn btn-outline-theme">View</a>
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
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title"><img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['userDetail']->clubIncome()->sum('amount'),2)}}</h5>
								<p class="card-text">Club Reward</p>
								<a href="/User/ClubReward" class="btn btn-outline-theme">View</a>
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
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title">$ {{round($data['userDetail']->lifetimeIncome()->sum('amount'),2)}}</h5>
								<p class="card-text">Achievement Reward</p>
								<a href="/User/LifetimeAchievementReward" class="btn btn-outline-theme">View</a>
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
				<!-- END col-4 -->

				</div>

				<div class="row">

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
				    <!-- BEGIN card -->
				    <div class="card mb-3">
				        <!-- BEGIN card-body -->
				        <div class="card-body text-center">
				            
				            <div class="d-flex justify-content-between align-items-center mb-2">
				                <span class="fw-bold">Booster Status</span>
				                @if($data['userdetails']->booster==2)
				                   <h5 class="card-title" style="color:#3cd2a5;"> Active</h5>
				                @else
				                   <h5 class="card-title mb-1" style="color:#FF0000;">Inactive</h5>
				                @endif
				            </div>

				            @if($data['userdetails']->booster!=2 && !is_null($data['activationdate']))
				                <div class="small text-muted">
				                    <span class="badge border border-theme text-theme px-2 py-1 d-block mb-1">
				                        <span id="booster-timer" style="font-size:12px;"></span>
				                    </span>
				                    <div class="d-flex justify-content-between" style="font-size:12px;">
				                        <span>Achieved: <b>{{ $data['boosterDirectsAchieved'] }}</b></span>
				                        <span>Needed: <b>{{ $data['boosterDirectsNeeded'] }}</b></span>
				                    </div>
				                </div>
				            @else
				            	<div class="small text-muted">
				                    
				                    <div class="d-flex justify-content-between" style="font-size:12px;">
				                        <br><br>
				                    </div>
				                </div>
				            @endif

				            <a href="/User/StakingReward" class="btn btn-outline-theme btn-sm mt-2">View</a>
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
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title"><img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['userDetail']->totalIncome(),2)}}</h5>
								<p class="card-text">Total Earnings</p>
								<a href="/User/IncomeOverview" class="btn btn-outline-theme">View</a>
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
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-4">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<h5 class="card-title">$ @if(!is_null($data['availblewallet'])){{round(\Illuminate\Support\Facades\Crypt::decrypt($data['availblewallet']->amount),6)}} @else 0 @endif</h5>
								<p class="card-text">Wallet Amount</p>
								<a href="/User/Stake" class="btn btn-outline-theme">Upgrade</a>
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
				<!-- END col-4 -->
					
				</div>

				<div class="row">
				<!-- BEGIN col-6 -->
				<div class="col-xl-6">
					<!-- BEGIN card -->
					<div class="card">
						<div class="card-body">
							<div class="row">
												
								<div class="col-xl-12">
									<div class="card">
										<div class="card-header fw-bold small">
											Personal Details
										</div>
										<div class="card-header bg-none p-0">
											<ul class="list-group list-group-flush">
												<li class="list-group-item">Member Id <span style="float: right;">{{ Session::get('user.uuid') }}</span></li>
												<li class="list-group-item">Full Name <span style="float: right;">{{ Session::get('user.name') }}</span></li>
												<!-- <li class="list-group-item">Mobile <span style="float: right;">{{ Session::get('user.contact') }}</span></li> -->
												<li class="list-group-item">Email <span style="float: right;">{{ Session::get('user.email') }}</span></li>
																
											</ul>
										</div>
										<!-- <div class="card-body">
												<a href="#" class="card-link">Card link</a>
												<a href="#" class="card-link">Another link</a>
											</div> -->
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
							<!-- <div class="hljs-container">
									<pre><code class="xml" data-url="{{asset('ctassets/data/ui-card/code-1.json')}}"></code></pre>
								</div> -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-6 -->

				<!-- BEGIN col-6 -->
				<div class="col-xl-6">
					<!-- BEGIN card -->
					<div class="card">
						<div class="card-body">
							<div class="row">
												
								<div class="col-xl-12">
									<div class="card">
										<div class="card-header fw-bold small text-center">
    Loan Details

    @if(!is_null($data['userDetail']->userLoanStatus()) && $data['userDetail']->userLoanStatus()->remaining > 0)
        <span style="color:white;background:red">Loan Status : Active</span>
        <br>
        Remaining Time
        @php
            $loanStart = $data['userDetail']->userLoanStatus()->created_at;
            $expiryDate = \Carbon\Carbon::parse($loanStart)->addDays(45);
        @endphp

        <span id="loan-timer" 
              data-expiry="{{ $expiryDate->format('Y-m-d H:i:s') }}"
              style="color:red;">
            Loading...
        </span>
    @endif
</div>

										<div class="card-header bg-none p-0">
											<ul class="list-group list-group-flush">
												@if(!is_null($data['userDetail']->userLoanStatus()))
													<li class="list-group-item">Loan Amount <span style="float: right; color:#FF0000;">$ {{round($data['userDetail']->userLoanStatus()->amount,2)}}</span></li>
													<li class="list-group-item">Due <span style="float: right; color:#FF0000;">$ {{round($data['userDetail']->userLoanStatus()->remaining,2)}}</span></li>
													<li class="list-group-item">Locked Income <span style="float: right; color:#FF0000;"><img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['userDetail']->lockedIncome(),2)}}</span></li>
												@else
													<li class="list-group-item">Loan Amount <span style="float: right; color:#FF0000;">$ 0</span></li>
													<li class="list-group-item">Due <span style="float: right; color:#FF0000;">$ 0</span></li>
													<li class="list-group-item">Locked Income <span style="float: right; color:#FF0000;"><img src="{{ asset('main/assets/images/coinblack.png') }}" alt="coin" style="width:30px; height:30px; vertical-align:middle; animation:spin 4s linear infinite;"> {{round($data['userDetail']->lockedIncome(),2)}}</span></li>
												@endif			
											</ul>
										</div>
										<!-- <div class="card-body">
												<a href="#" class="card-link">Card link</a>
												<a href="#" class="card-link">Another link</a>
											</div> -->
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
							<!-- <div class="hljs-container">
									<pre><code class="xml" data-url="{{asset('ctassets/data/ui-card/code-1.json')}}"></code></pre>
								</div> -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-6 -->
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
	<script src="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/jvectormap-content/world-mill.js')}}"></script>
	<script src="{{asset('ctassets/plugins/apexcharts/dist/apexcharts.min.js')}}"></script>
	<script src="{{asset('ctassets/js/demo/dashboard.demo.js')}}"></script>
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

		function showPopup() {
			const popup = document.getElementById('popupOverlay');
			const video = document.getElementById('popupVideo');
			const clickBtn = document.getElementById('clickToPlay');

			popup.style.display = 'flex';

			// Hide click button initially
			clickBtn.style.display = "none";

			// Try autoplay with sound
			video.muted = false;
			video.play().then(() => {
				triggerConfetti();
			}).catch(function(error) {
				console.log("Autoplay with sound blocked. Showing Click button.", error);

				// Show existing button for user interaction
				clickBtn.style.display = "block";

				clickBtn.addEventListener("click", function playWithSound() {
					video.play();
					triggerConfetti();
					clickBtn.style.display = "none"; // hide button after click
					clickBtn.removeEventListener("click", playWithSound);
				});
			});

			// Close popup when video ends
			video.onended = function() {
				closePopup();
			};
		}

		function triggerConfetti() {
			confetti({
				particleCount: 100,
				spread: 70,
				origin: { y: 0.6 },
				zIndex: 1001
			});
		}

		function closePopup() {
			const popup = document.getElementById('popupOverlay');
			const video = document.getElementById('popupVideo');
			const clickBtn = document.getElementById('clickToPlay');

			video.pause();
			video.currentTime = 0;

			popup.style.display = 'none';
			clickBtn.style.display = "none"; // reset button hidden
		}

		// Run after page load
		window.onload = function() {
			showPopup();

			// Close popup on click (X)
			document.querySelector('.close-popup').addEventListener('click', closePopup);

			// Close popup when clicking outside video
			document.getElementById('popupOverlay').addEventListener('click', function(e) {
				if (e.target === this) {
					closePopup();
				}
			});
		};
    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const timerElement = document.getElementById("loan-timer");
    const expiry = new Date(timerElement.getAttribute("data-expiry")).getTime();

    function updateTimer() {
        const now = new Date().getTime();
        const distance = expiry - now;

        if (distance <= 0) {
            timerElement.innerHTML = "Expired";
            timerElement.style.background = "black";
            timerElement.style.color = "red";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.innerHTML = days + "d " + hours + "h " 
                               + minutes + "m " + seconds + "s";
    }

    updateTimer();             
    setInterval(updateTimer, 1000); 
});
</script>
<script>
						document.addEventListener("DOMContentLoaded", function() {
							// Booster expiry = activation date + 7 days
							let expiryDate = new Date("{{ \Carbon\Carbon::parse($data['activationdate'])->addDays(7) }}").getTime();

							let x = setInterval(function() {
								let now = new Date().getTime();
								let distance = expiryDate - now;

								if (distance > 0) {
									let days = Math.floor(distance / (1000 * 60 * 60 * 24));
									let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
									let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
									let seconds = Math.floor((distance % (1000 * 60)) / 1000);

									document.getElementById("booster-timer").innerHTML =
										days + "d " + hours + "h " + minutes + "m " + seconds + "s";
								} else {
									clearInterval(x);
									document.getElementById("booster-timer").innerHTML = "Expired";
								}
							}, 1000);
						});
					</script>
	
</body>
</html>
