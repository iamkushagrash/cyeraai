<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Profile</title>
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
				<li class="breadcrumb-item active">Profile</li>
			</ul>
			
			<h1 class="page-header">
				Update Profile <!-- <small>page header description goes here...</small> -->
			</h1>
			
			<div class="row">
				<form action="/User/EditProfile" method="post">
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
									<div class="col-xl-6">
										<div class="card">
											<div class="card-body pb-2">
													<div class="row">
														<div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput1">Member Name</label>
															<input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ $profile->usersname }}" id="exampleFormControlInput1" placeholder="Member Name" required="" readonly>
															@error('username')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>
														<div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput2">Mobile No.</label>
															<input type="number" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ $profile->contact }}" id="exampleFormControlInput2" placeholder="Mobile No." readonly>
															@error('contact')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>
														<div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput3">Email Id</label>
															<input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $profile->email }}" id="exampleFormControlInput3" placeholder="Email" required="" readonly>
															@error('email')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
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
									<div class="col-xl-6">
										<div class="card">
											<div class="card-body pb-2">
													<div class="row">
														<div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput6">USDT BEP20 Address</label>
															@if(is_null($profile->usdtbep20address))
															<input type="text" name="usdtbep20address" class="form-control @error('usdtbep20address') is-invalid @enderror" id="exampleFormControlInput6" placeholder="USDT BEP20 Address " @if(!is_null($changeasset)) value="{{$changeasset->usdtbep20addr}}" disabled @else value="{{ $profile->usdtbep20address }}"  @endif>
															@else
								                            <label class="form-label" style="font-size: .755rem; word-break: break-all; overflow-wrap: break-word;white-space: normal; color: #fff;">{{ $profile->usdtbep20address }}</label>
								                            @endif
															@error('usdtbep20address')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>
														<!-- <div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput7">USDT TRC20 Address</label>
															@if(is_null($profile->usdttrc20address))
															<input type="text" name="usdttrc20address" class="form-control @error('usdttrc20address') is-invalid @enderror" value="{{ $profile->usdttrc20address }}" id="exampleFormControlInput7" placeholder="USDT TRC20 Address">
															@else
								                              <label class="form-label" for="exampleFormControlInput7" style="font-size: .755rem; word-break: break-all; overflow-wrap: break-word;white-space: normal; color: #fff;">{{ $profile->usdttrc20address }}</label>
								                            @endif
															@error('usdttrc20address')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>	 -->
														@if(!is_null($changeasset))
														<div class="form-group mb-3">
															<label class="form-label" for="otp">OTP</label>
															<input type="number" name="otp" class="form-control @error('otp') is-invalid @enderror" value="" id="otp" placeholder="OTP" required="" >
															@error('otp')
				                          <span class="invalid-feedback" role="alert">
				                              <strong>{{ $message }}</strong>
				                          </span>
				                      @enderror
														</div>
														<!-- <div class="form-group mb-3">
															<a class="btn btn-theme mb-1" href="/User/resendProfileOtp"> Resend OTP</a>
														</div> -->
														@endif
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
								<span class="d-lg-inline d-none"><br></span>
								@if(is_null($profile->usdtbep20address) || is_null($profile->usdttrc20address))
									<button type="submit" class="btn btn-theme mb-1" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Submit</button>
									@if(!is_null($changeasset))
									&nbsp;&nbsp;&nbsp;<a class="btn btn-theme mb-1" href="/User/resendProfileOtp"> Resend OTP</a>
									@endif
								@endif
							</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						
					</div>
				
				</form>
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
