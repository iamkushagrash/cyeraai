<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Upgrade</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Cyera AI Cyera AI ">
	<meta name="author" content="cyeras">
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
				<li class="breadcrumb-item active">Upgrade</li>
			</ul>
			
			<h1 class="page-header">
				Upgrade <!-- <small>page header description goes here...</small> -->
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
			@if(is_null($user))
			<div class="row">
				<form action="/User/getUser" method="post">
					@csrf
					<div class="card">
						<div class="card-body">
							
							<div class="row">
								<div class="col-xl-6">
									<div class="card">
										<div class="card-body pb-2">
												<div class="row">
													<label class="form-label" style="text-align:center;"><h6>Available Balance : @if(!is_null($balance)){{round(\Illuminate\Support\Facades\Crypt::decrypt($balance->amount),6)}} @else 0 @endif $</h6></label>
													<label class="form-label" style="text-align:center;"><h6>CAI Price : {{round($price->price,3)}}$</h6></label>
													<div class="form-group mb-3">
														<label class="form-label" for="exampleFormControlInput1"><h6>User ID</h6></label>
														<input type="text" id="userid" class="form-control @error('userid') is-invalid @enderror" name="userid" value="" placeholder="Enter UserID" required>
														@error('userid')
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
								
								
							</div>
							<!-- <span class="d-lg-inline d-none"><br></span> --><br>
							<button type="submit" class="btn btn-theme mb-1" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Search</button>
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
				<form action="/User/Stake" method="post">
                @csrf
                	<input type="hidden" name="honeypotu" value="{{$user->id}}">
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
								<div class="col-xl-12">
									<div class="card">
										<div class="card-body pb-2">
											<div class="row">
												<div class="col-xl-6 col-md-6 col-xs-6"><label class="form-label" style="text-align:center;"><h6>Available Balance : @if(!is_null($balance)){{round(\Illuminate\Support\Facades\Crypt::decrypt($balance->amount),6)}} @else 0 @endif $</h6></label></div>
												<div class="col-xl-6 col-md-6 col-xs-6"><label class="form-label" style="text-align:center;"><h6>CAI Price : {{round($price->price,3)}}$</h6></label></div>
											</div>
											<div class="row">
												<div class="col-xl-6 col-md-6 col-xs-6">
													<div class="form-group mb-3">
														<label class="form-label" for="exampleFormControlInput1"><h6>UserID</h6></label>
														<input type="text" id="userid" class="form-control @error('userid') is-invalid @enderror" name="userid" value="{{ $user->uuid }}" placeholder="UserID" readonly="" required>
														@error('userid')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>
													<div class="form-group mb-3">
														<label class="form-label" for="exampleFormControlInput1"><h6>Name</h6></label>
														<input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->name }}" placeholder="User Name" readonly="" required>
														@error('username')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>
													<div class="form-group mb-3">
														<label class="form-label" for="cai"><h6>CAI</h6></label>
														<input type="amount" name="cai" class="form-control @error('cai') is-invalid @enderror" id="cai" placeholder="cai" disabled>
														@error('cai')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>														
												</div>
												<div class="col-xl-6 col-md-6 col-xs-6">
													<!-- <div class="form-group mb-3">
														<label class="form-label" for="exampleFormControlInput1"><h6>Email</h6></label>
														<input type="text" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" placeholder="Email" readonly="" required>
														@error('email')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div> -->	
													
													<div class="form-group mb-3">
														<label class="form-label" for="amount"><h6>Amount ($)</h6></label>
														<input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Amount USDT">
														@error('amount')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
													</div>
														
													<div class="form-group mb-3">
														<label class="form-label" for="password"><h6>Password</h6></label>
														<input type="text" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
														@error('password')
			                                                <span class="invalid-feedback" role="alert">
			                                                    <strong>{{ $message }}</strong>
			                                                </span>
			                                            @enderror
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
										
									</div>
								</div>
								
								
							</div>
							<span class="d-lg-inline d-none"><br></span><br>
							<button type="submit" class="btn btn-theme mb-1" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Submit</button>
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
	<script>
		$("#cai").on('blur',function(){
            $("#amount").val(Number.parseFloat($("#cai").val()*{{$price->price}}).toFixed(6));
            $("#amount").blur();
        });
        $("#amount").on('blur',function(){
            $("#cai").val(Number.parseFloat($("#amount").val()/{{$price->price}}).toFixed(6));
            $("#paydetail").show();
        });
	</script>

	
</body>
</html>
