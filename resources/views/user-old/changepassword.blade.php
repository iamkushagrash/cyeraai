<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Update Password</title>
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
				Update Password <!-- <small>page header description goes here...</small> -->
			</h1>
			
			<div class="row">
				<form action="/User/ChangePassword" method="post">
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
														<label class="form-label" for="exampleFormControlInput1">Old Password</label>
														<input type="text" class="form-control @error('oldpassword') is-invalid @enderror" name="oldpassword"  value="" placeholder="Old Password *" required>
														@error('oldpassword')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>
													<div class="form-group mb-3">
														<label class="form-label" for="exampleFormControlInput2">New Password</label>
														<input type="text" class="form-control @error('password') is-invalid @enderror" name="password" value="" placeholder="New Password *" required="">
			                                            @error('password')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>
													<div class="form-group mb-3">
														<label class="form-label" for="exampleFormControlInput3">Confirm Password</label>
														<input type="text" name="password_confirmation" class="form-control" id="inputConfirmPassword1" placeholder="Confirm Password *">
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
							<span class="d-lg-inline d-none"><br></span>
							<button type="submit" class="btn btn-theme mb-1">Submit</button>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<div class="hljs-container">
							<pre><code class="xml" data-url="{{asset('ctassets/data/ui-card/code-1.json')}}"></code></pre>
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
