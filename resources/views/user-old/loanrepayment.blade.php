<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Repay Loan</title>
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
				<li class="breadcrumb-item active">Loan</li>
			</ul>
			
			<h1 class="page-header">
				Repay Loan <!-- <small>page header description goes here...</small> -->
			</h1>
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
			
			@if(!is_null($amount))
			<div class="row">
				<form action="/User/RepayLoan" method="post">
					@csrf
					<div class="card">
						<div class="card-body">
							
							<div class="row">
								<div class="col-xl-6">
									<div class="card">
										<div class="card-body pb-2">
												<div class="row">
													<label class="form-label" style="text-align:center;"><h6>Wallet Balance: $ {{$walletamount}}</h6></label>
													<label class="form-label" style="text-align:center;"><h6>Remaining Loan: $ {{$amount}}</h6></label>

													@if($amount>0)
													<div class="form-group mb-3">
														<label class="form-label" for="amount"><h6>Amount</h6></label>
														<input type="text" id="amount" class="form-control @error('amount') is-invalid @enderror" max="{{$amount}}" name="amount" value="" placeholder="Enter Amount" required>
														@error('amount')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>
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
							<!-- <span class="d-lg-inline d-none"><br></span> --><br>
							@if($amount>0)
							<button type="submit" class="btn btn-theme mb-1" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Submit</button>
							@endif
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
			@else
			<div class="row">
					<div class="card">
						<div class="card-body">
							<h4>You don't have any pending loan amount<</h4>
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
			</div>

			@endif
			




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
