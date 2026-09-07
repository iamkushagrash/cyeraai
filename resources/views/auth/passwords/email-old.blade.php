<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Reset Password</title>
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
<body class='pace-top'>
	<video autoplay muted loop id="myVideo">
      <source src="{{asset('ctassets/myvideo2.mp4')}}" type="video/mp4">
    </video>
	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN login -->
		<div class="login">
			<!-- BEGIN login-content -->
			<div class="login-content">
				<form action="{{ route('password.email') }}" method="POST" name="form1" id="msg_validate">
					@csrf
					<h1 class="text-center">Reset Password</h1>
					<div class="text-inverse text-opacity-50 text-center mb-4">
						Please enter your UserID or Email to reset password
					</div>
							@if (session('success'))
                              <div class="alert alert-success green">
                                 {{ session('success') }}
                              </div>
                           @endif
                           @if (session('warning'))
                              <div class="alert alert-warning red">
                                 {{ session('warning') }}
                              </div>
                           @endif
					<div class="mb-3">
						<label class="form-label">User ID <span class="text-danger">*</span></label>
						<input name="email" type="text" id="email" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter User ID / Email">
						@error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					
					
					<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Send Reset Link</button>
					<div class="text-center text-inverse text-opacity-50">
						Have an account ? <a href="/login">Log In</a>.
					</div>
				</form>
			</div>
			<!-- END login-content -->
		</div>
		<!-- END login -->
		

		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	
	
</body>
</html>
