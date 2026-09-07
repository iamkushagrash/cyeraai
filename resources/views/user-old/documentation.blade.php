<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Documentation</title>
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
			
		<!-- BEGIN row -->
			<div class="row">
				<div class="col-xl-12">
					<h1 class="page-header">Company Documentation</h1>
					<p>Download our official documents to gain insights into our company’s vision, strategy, and technology.</p>
				</div>
			</div>
			<div class="row">
				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Company Document [01]</h5>
							<p class="card-text">Overview of our company’s mission and values.</p>
							<a href="{{ asset('main/assets/documentation/companydocument.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">One Paper [02]</h5>
							<p class="card-text">A concise summary of our core objectives.</p>
							<a href="{{ asset('main/assets/documentation/onepaper.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">White Paper [03]</h5>
							<p class="card-text">Detailed explanation of our project and technology.</p>
							<a href="{{ asset('main/assets/documentation/whitepaper.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Terms to Sale [04]</h5>
							<p class="card-text">Legal terms and conditions for participation.</p>
							<a href="{{ asset('main/assets/documentation/termtosell.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Business Plan [05]</h5>
							<p class="card-text">Our strategic roadmap for growth and success.</p>
							<a href="{{ asset('main/assets/documentation/businessplan.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Ecosystem [06]</h5>
							<p class="card-text">Overview of our interconnected systems and services.</p>
							<a href="{{ asset('main/assets/documentation/ecosystem.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Technology Info [07]</h5>
							<p class="card-text">Technical details of our platform and solutions.</p>
							<a href="{{ asset('main/assets/documentation/technologyinfo.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Founder Vision [08]</h5>
							<p class="card-text">Insights from our founders on the company’s future.</p>
							<a href="{{ asset('main/assets/documentation/foundervision.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->

				<!-- BEGIN col-4 -->
				<div class="col-xl-4 col-lg-6">
					<div class="card mb-3 document-card">
						<div class="card-body">
							<h5 class="card-title">Project Explainer [09]</h5>
							<p class="card-text">A comprehensive guide to our project’s goals.</p>
							<a href="{{ asset('main/assets/documentation/businessplan.pdf') }}" class="btn btn-outline-theme" download>Download Now</a>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<!-- END col-4 -->
			</div>
			<!-- END row -->
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

	
</body>
</html>
