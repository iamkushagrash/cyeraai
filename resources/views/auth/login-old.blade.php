<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Log in </title>
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
	.field-icon {
	  float: right;
	  margin-left: -25px;
	  margin-top: -25px;
	  margin-right: 5px;
	  position: relative;
	  z-index: 2;
	}
	#myVideo {
  position: fixed;
  right: 0;
  bottom: 0;
  min-width: 100%;
  min-height: 100%;
  object-fit: cover;
  z-index: -1;

  /* Combine all effects */
  filter: brightness(25%);
}

	</style>
</head>
<body class='pace-top'>
	<video autoplay muted loop id="myVideo">
      <source src="{{asset('ctassets/video1.mp4')}}" type="video/mp4">
    </video>
	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN login -->
		<div class="login">
			<!-- BEGIN login-content -->
			<div class="login-content">
				<form action="{{ route('login') }}" method="POST" name="form1" id="msg_validate">
					@csrf
					<h1 class="text-center">Get started with Us</h1>
					<div class="text-inverse text-opacity-50 text-center mb-4">
						Sign in to start your session
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
						<input name="email" type="text" id="email" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('email') is-invalid @enderror" required="" value="" autofocus placeholder="Enter User ID">
						@error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-3">
						<div class="d-flex">
							<label class="form-label">Password <span class="text-danger">*</span></label>
							<a href="/password/reset" class="ms-auto text-inverse text-decoration-none text-opacity-50">Forgot password?</a>
						</div>
						<input name="password" type="password" id="password" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('password') is-invalid @enderror" value="" required="" autocomplete="current-password" placeholder="Enter password">
						<!-- <i class="fas fa-eye" id="togglePassword"></i> -->
						<span toggle="#password" class="fas fa-fw fa-eye field-icon toggle-password"></span>
						@error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-3">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="customCheck1">
							<label class="form-check-label" for="customCheck1">Remember me</label>
						</div>
					</div>
					<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
					<div class="text-center text-inverse text-opacity-50">
						Don't have an account yet? <a href="/register">Sign up</a>.
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
	
	<script type="text/javascript">
		/*const togglePassword = document.querySelector('#togglePassword');
		  const password = document.querySelector('#password');

		  togglePassword.addEventListener('click', function (e) {
		    // toggle the type attribute
		    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
		    password.setAttribute('type', type);
		    // toggle the eye slash icon
		    this.classList.toggle('fa-eye-slash');
		});*/
		$(".toggle-password").click(function() {

		  $(this).toggleClass("fa-eye fa-eye-slash");
		  var input = $($(this).attr("toggle"));
		  if (input.attr("type") == "password") {
		    input.attr("type", "text");
		  } else {
		    input.attr("type", "password");
		  }
		});
	</script>
</body>
</html>
