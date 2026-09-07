<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Deposit</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
				<li class="breadcrumb-item active">Deposit</li>
			</ul>
			
			<h1 class="page-header">
				Deposit <!-- <small>page header description goes here...</small> -->
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
			<div class="row">
				@if(!isset($payment))
				<form   method="post">
					@csrf
					<input type="hidden" name="honeypot" value="{{\Session::get('logtime')}}">
					<div class="card">
						<div class="card-body">
							
							<div class="row">
								<div class="col-xl-12 col-md-12">
									<div class="card">
										<div class="card-body pb-2">
												<div class="row">
													<div class="col-xl-6 col-md-6">
														<div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput1"><h6>Enter Amount ($)</h6></label>
															<input type="number" id="amount" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" step="0.000001" placeholder="Amount *" min="25" required>
															@error('amount')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>	
														
															
													</div>
													<div class="col-xl-6 col-md-6">
														
														<div class="form-group mb-3">
															<label class="form-label" for="currency"><h6>Currency</h6></label>
															<select class="form-select" id="currency" name="currency">
																<!-- 
																<option value="usdt">USDT TRC20</option> -->
																
																<option value="usdtbep20">USDT BEP20</option>
																
															</select>
				                                            @error('currency')
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
							<span class="d-lg-inline d-none"><br></span>
							
							<br>
							 <div style="background: #000; border: 2px solid #FFD700; border-radius: 6px; padding: 6px 12px; text-align:center; margin-bottom:15px; color:#FFD700; font-weight:bold; font-size:14px; box-shadow:0 0 8px rgba(255,215,0,0.5);">
      ⚠️ This is an automated payment system. After making payment, <span style="color:#FF4500;">DO NOT refresh or close</span>.
    </div>
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
				@endif
				@if(isset($payment))
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-3">
								&nbsp
							</div>
							<div class="col-xl-6 col-md-6">
								<div class="col-md-12">
	                              <h6 style="text-align:center;"><label class="form-label" onclick="copyaddr(this);" id="addr" for="addr" style="font-size: .775rem; color:#FFFFFF; word-break: break-all; overflow-wrap: break-word;white-space: normal;">{{$payment->pay_address}}</label><span class="desc"><br>click to copy or scan QR</span></h6><br>
	                              
	                            </div>
	                            <div class="col-md-12 ">
	                              	<div class="input-group-btn"  style="width:100%;text-align:center;">
	                                    <img id="qrimg" class="tab-img-icon" alt="icon" style="position: relative; top: 0; max-width: 200px; margin-right: 10px;" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{$payment->pay_address}}">
	                                </div><br>
	                            </div>
	                            <div class="col-md-12 mb-3" style="text-align:center;">
	                            	<label >Amount Received <b><span id="paid">0</span></b> of amount <span id="total">0</span></label><br>
	                            </div>
	                            <div class="col-md-12 mb-3" style="text-align:center;">
	                            	<label >Payment Status : <span id="status">Waiting</span></label>
	                            </div>
							</div>
							<div class="col-md-3"></div>
						</div>
					</div>
					<div class="card-arrow">
						<div class="card-arrow-top-left"></div>
						<div class="card-arrow-top-right"></div>
						<div class="card-arrow-bottom-left"></div>
						<div class="card-arrow-bottom-right"></div>
					</div>
				</div>
				@endif
			</div>
		</div>
		<!-- END #content -->
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
		<!-- BEGIN theme-panel -->


<div class="modal" id="myModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="modaltext"></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-outline-theme" id="modalbtn">Continue</a>
      </div>
    </div>
  </div>
</div>


		<!-- END theme-panel -->
	</div>
	<!-- END #app -->

	
	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	
	<script type="text/javascript">
		
    function copyaddr(value) {

                      // Create a "hidden" input
                      var aux = document.createElement("input");

                      aux.setAttribute("value", document.getElementById("addr").innerText);
                      // Append it to the body
                      document.body.appendChild(aux);
                      // Highlight its content
                      aux.select();
                      // Copy the highlighted text
                      document.execCommand("copy");
                      // Remove it from the body
                      document.body.removeChild(aux);
                      
                      alert("Address Copied Successfully: " + $(value).html() + ".");
                    }
    
    </script>

    <script type="text/javascript">
    	@if(!is_null($payment))
		
        function paymentStatus(){
        	$.ajax({
	            url: '/Transaction/transactionStatus/{{$payment->payment_id}}',
	            type: 'GET', // or 'POST', 'PUT', 'DELETE'
	            dataType: 'json', // or 'json', 'xml', 'html'
	            success: function(data) {
	                // Success: Process the response
	            	if(data.transaction_status==0){
		              $("#status").html(data.payment_status);
		              $("#paid").html(data.paid);
		              $("#total").html(data.totalAmount);
		            }else if(data.transaction_status>0){
		            	var myModal = new bootstrap.Modal(document.getElementById('myModal'));
		            	$("#modaltext").html(data.transaction_message);
		            	$("#modalbtn").prop('href',"/User/DepositHistory");
		    					myModal.show();
		            }
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	                // Error handling
	                console.error('AJAX error:', textStatus, errorThrown);
	            }
	        });
        }
 				$(document).ready(function(){
 					setInterval(paymentStatus, 30000);
          //setTimeout(paymentStatus, 10);
        });
        @endif
       
    </script>

</body>
</html>
