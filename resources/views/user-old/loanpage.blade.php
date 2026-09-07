<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Get Loan</title>
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
    .package-box .package-btn {
  padding: 15px;
  border: 2px solid gold;
  border-radius: 12px;
  background: transparent;
  color: gold;
  transition: all 0.3s ease;
  box-shadow: 0 0 10px rgba(255,215,0,0.3);
  position: relative;
  overflow: hidden;
}

/* Golden glow animation */
.package-box .package-btn::after {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(120deg, transparent, rgba(255,215,0,0.5), transparent);
  transform: rotate(25deg);
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%) rotate(25deg); }
  100% { transform: translateX(100%) rotate(25deg); }
}

/* Active (selected) */
.package-box input:checked + .package-btn {
  background: gold;
  color: #000;
  border: 3px solid #b8860b;
  box-shadow: 0 0 20px rgba(255,215,0,0.8);
}

.loan-popup {
  border: 3px solid gold !important;
  border-radius: 20px !important;
  box-shadow: 0 0 40px rgba(255,215,0,0.5),
              inset 0 0 20px rgba(255,215,0,0.2) !important;
  padding: 30px !important;
}
.loan-title {
  font-size: 24px !important;
  font-weight: 700 !important;
  color: gold !important;
  text-shadow: 0 0 10px rgba(255,215,0,0.6);
}

/* Installer style loader bar */
.install-loader {
  width: 100%;
  height: 12px;
  background: rgba(255,215,0,0.2);
  border-radius: 10px;
  margin-top: 20px;
  overflow: hidden;
  box-shadow: inset 0 0 10px rgba(255,215,0,0.4);
}
.install-bar {
  width: 0%;
  height: 100%;
  background: linear-gradient(90deg, gold, orange);
  border-radius: 10px;
  transition: width 1s ease-in-out;
}

/* Moving dots animation (like installing software) */
.install-dots {
  margin-top: 15px;
}
.install-dots span {
  display: inline-block;
  width: 10px;
  height: 10px;
  margin: 0 4px;
  background: gold;
  border-radius: 50%;
  animation: bounce 1.2s infinite;
}
.install-dots span:nth-child(2) { animation-delay: 0.2s; }
.install-dots span:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
  0%, 80%, 100% { transform: scale(0.8); opacity: 0.6; }
  40% { transform: scale(1.3); opacity: 1; }
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
				<li class="breadcrumb-item active">Get Loan</li>
			</ul>
			
			<h1 class="page-header">
				Get Loan<small> </small>
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
				@if(sizeof($user->stackingDeposite()->get()) && is_null($loan))
					<!-- <h4>You already have an active package in your account so you are not eligible to take a loan.</h4> -->
                    <h4>The loan feature was available only during the initial phase and has now been discontinued. Users who wish to upgrade their account must do so using their real wallet balance.</h4>
				@else
					@if(is_null($loan))
                <h4>The loan feature was available only during the initial phase and has now been discontinued. Users who wish to upgrade their account must do so using their real wallet balance.</h4>          
                <!-- <h4>Advance Loan for Staking</h4>
          <p>We offer a convenient advance loan service for topping up your ID. Instantly credited, this short-term loan ensures uninterrupted access or services. Repay later with ease, while continuing to enjoy seamless usage. Fast, reliable, and hassle-free top-up funding when you need it most.</p> -->

          <!-- Loan Terms + Package Form -->
          <!-- <form id="loanForm" method="POST" action="/User/GetLoan">
          @csrf
                  
            <div class="card bg-dark text-white p-4 mb-4">
                <h4 class="mb-3">📜 Loan Terms & Conditions</h4>
                <ul style="line-height:1.8;">
                    <li>After taking a loan, you cannot top up your wallet until the repayment is completed.</li>
                    <li>The loan must be repaid within <b>45 days</b>.</li>
                    <li>Direct Bonus will continue to generate but will remain <b>locked until the loan is repaid</b>.</li>
                </ul>


                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="agreeTerms" name="agree_terms" required>
                    <label class="form-check-label" for="agreeTerms">
                        I have read and agree to the terms.
                    </label>
                </div>
            </div>

                       
                        <div id="packageSelection" class="d-none">
                            <h5 class="mb-3">Select your package</h5>
                            <div class="d-flex gap-3 mb-3 flex-wrap">
                                @foreach([100,200,300,400,500] as $amt)
                                    <label class="package-box text-center flex-fill" style="cursor:pointer; max-width:140px;">
                                        <input type="radio" name="amount" value="{{ $amt }}" class="d-none" required>
                                        <div class="package-btn fw-bold">${{ $amt }}</div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" 
                                    class="btn btn-success mt-3 px-5 py-2 fw-bold" 
                                    style="font-size:16px; border-radius:8px;">
                                Submit
                            </button>
                        </div>


          </form> -->
                  @else
                      <h4>You already have an active loan of ${{$loan->amount}} received on {{$loan->created_at}}. Details below</h4>
                      <h4>Remaining Loan Amount : <span style="color: #FF0000;">${{$loan->remaining}}</span></h4>
                      @if($loan->remaining > 0)
                          <h5>Remaining time to repay above loan: 
                              <span id="timer" style="color: #FF0000;"></span>
                          </h5>
                      @endif
                  @endif

				@endif
									
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
	
	<script>
                const startTime = "{{(!is_null($loan))?date('Y-m-d H:i:s',strtotime('+ 45 days',strtotime($loan->created_at))):now()}}";
                // Set the date we're counting down to
                var countDownDate = new Date(startTime).getTime();

                // Update the count down every 1 second
                var x = setInterval(function() {

                  // Get today's date and time
                  var now = new Date().getTime();

                  // Find the distance between now and the count down date
                  var distance = countDownDate - now;

                  // Time calculations for days, hours, minutes and seconds
                  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                  // Display the result in the element with id="timer"
                  document.getElementById("timer").innerHTML = days + " days " + hours + " hours "
                  + minutes + " minutes " + seconds + " seconds ";

                  // If the count down is finished, write some text
                  if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("timer").innerHTML = "EXPIRED";
                  }
                }, 1000);
    </script>
		<!-- SweetAlert2 & Canvas-Confetti Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<script>
@if(session('success'))
    // Full-screen fireworks/Confetti
    function launchEpicFireworks() {
        var duration = 7000;
        var animationEnd = Date.now() + duration;
        var defaults = {
            startVelocity: 35,
            spread: 360,
            ticks: 60,
            zIndex: 99999,
            gravity: 0.5
        };

        var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) return clearInterval(interval);

            var particleCount = 50 * (timeLeft / duration);

            // Top bursts
            confetti(Object.assign({}, defaults, {
                particleCount: particleCount,
                origin: { x: Math.random(), y: Math.random() - 0.2 }
            }));
            // Left bursts
            confetti(Object.assign({}, defaults, {
                particleCount: particleCount / 2,
                origin: { x: Math.random() - 0.2, y: Math.random() }
            }));
            // Right bursts
            confetti(Object.assign({}, defaults, {
                particleCount: particleCount / 2,
                origin: { x: Math.random() + 0.2, y: Math.random() }
            }));
        }, 250);
    }

    // SweetAlert2 Dark Fullscreen Popup
    Swal.fire({
        title: '🎉 Congratulations! 🎉',
        html: '<h2 style="color:#fff;">You got your loan successfully! 💰</h2><p style="color:#ccc;">Enjoy your funds and continue your journey!</p>',
        background: '#121212',  // Dark theme
        color: '#ffffff',
        confirmButtonText: 'Awesome!',
        width: '600px',
        padding: '2em',
        showClass: { popup: 'animate__animated animate__zoomIn' },
        hideClass: { popup: 'animate__animated animate__zoomOut' },
        didOpen: () => {
            launchEpicFireworks();
        }
    });
@endif
</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- <script>
@if(session('warning'))
let messages = [
  "⚡ Checking your eligibility...",
  "🔒 Analyzing your account history...",
  "📂 Verifying your past transactions...",
  "💡 Running final checks...",
  "✨ Almost there...",
  "❌ Sorry, currently you're not eligible. Please try again."
];

let i = 0;

Swal.fire({
  title: 'Loan Verification',
  html: `
    <div id="loanMessage" style="font-size:18px; font-weight:500; color:#FFD700;">
      ${messages[0]}
    </div>
    <div class="install-loader">
      <div class="install-bar"></div>
    </div>
    <div class="install-dots">
      <span></span><span></span><span></span>
    </div>
  `,
  background: '#1a1a1a',
  color: '#FFD700',
  width: '600px',
  allowOutsideClick: false,
  showConfirmButton: false,
  didOpen: () => {
    const msgBox = Swal.getHtmlContainer().querySelector('#loanMessage');
    const bar = Swal.getHtmlContainer().querySelector('.install-bar');
    let interval = setInterval(() => {
      i++;
      if (i < messages.length) {
        msgBox.innerHTML = messages[i];
        bar.style.width = `${(i / (messages.length-1)) * 100}%`;
      } else {
        clearInterval(interval);
        Swal.update({
          icon: 'error',
          title: 'Not Eligible',
          html: `<p style="color:#ccc;">Sorry, currently you're not eligible.<br>Please try again later.</p>`,
          showConfirmButton: true,
          confirmButtonColor: '#FFD700'
        });
      }
    }, 3000);
  },
  customClass: {
    popup: 'loan-popup',
    title: 'loan-title'
  }
});
@endif
</script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('input[name="amount"]').forEach(el => {
    el.addEventListener('change', function() {
        let value = this.value;
        Swal.fire({
            title: 'Package Selected',
            html: `<h3 style="color:gold;">You have selected <b>$${value}</b> package</h3>`,
            icon: 'info',
            confirmButtonText: 'OK',
            background: '#1a1a1a',
            color: '#fff',
            confirmButtonColor: 'gold',
            customClass: {
                popup: 'animated fadeInDown'
            }
        });
    });
});
</script>
<script>
document.getElementById('agreeTerms').addEventListener('change', function() {
    let packageDiv = document.getElementById('packageSelection');
    if(this.checked){
        packageDiv.classList.remove('d-none');
        // Optional: smooth scroll to package selection
        packageDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        packageDiv.classList.add('d-none');
    }
});
</script>




</body>
</html>

