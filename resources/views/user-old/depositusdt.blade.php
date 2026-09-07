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
			<div class="row">
				<form method="post">
					@csrf
					<input type="hidden" name="honeypot" value="{{\Session::get('logtime')}}">
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
								<div class="col-xl-12 col-md-12">
									<div class="card">
										<div class="card-body pb-2">
												<div class="row">
													<div class="col-xl-6 col-md-6">
														<div class="form-group mb-3">
															<label class="form-label" for="exampleFormControlInput1"><h6>Enter Amount ($)</h6></label>
															<input type="number" id="amountusdt" class="form-control @error('amountusdt') is-invalid @enderror" name="amountusdt" value="" step="0.000001" placeholder="Amount *" min="25" required>
															@error('amountusdt')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>
														<div class="form-group mb-3">
															<label class="form-label" for="cai"><h6>CAI</h6></label>
															<input type="number" id="cai" class="form-control @error('cai') is-invalid @enderror" name="cai" value="" step="0.000001" placeholder="Amount *" min="25" required>
				                                            @error('cai')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>
														
														<div class="form-group mb-3">
															<label class="form-label" for="txnhash"><h6>Transaction Hash</h6></label>
															<input type="text" name="txnhash" class="form-control @error('txnhash') is-invalid @enderror" id="txnhash" placeholder="Transaction Hash *">
															@error('txnhash')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>	
														
															
													</div>
													<div class="col-xl-6 col-md-6">
														
														@if((!is_null($detail->usdt) && $detail->usdt_deposit_status==1) || (!is_null($detail->usdtbep20) && $detail->usdtbep20_deposit_status==1))
														<div class="form-group mb-3">
															<label class="form-label" for="currency"><h6>Currency</h6></label>
															<select class="form-select" id="currency" name="currency">
																@if((!is_null($detail->usdt) && $detail->usdt_deposit_status==1))
																<option value="usdt">USDT TRC20</option>
																@endif
	                                                            @if((!is_null($detail->usdtbep20) && $detail->usdtbep20_deposit_status==1))
																<option value="usdtbep20">USDT BEP20</option>
																@endif
															</select>
				                                            @error('currency')
				                                                <span class="invalid-feedback" role="alert">
				                                                    <strong>{{ $message }}</strong>
				                                                </span>
				                                            @enderror
														</div>
														<div class="form-group mb-3">
															<label class="form-label" onclick="copyaddr(this);" id="addr" for="addr" style="font-size: .775rem; color:#FFFFFF; word-break: break-all; overflow-wrap: break-word;white-space: normal;"><h6>{{ \Illuminate\Support\Facades\Crypt::decrypt($detail->usdt) }}</h6></label>
															<span class="desc"><br>click to copy or scan QR</span>
														</div>
														<div class="form-group mb-3">
															<div class="input-group-btn"  style="width:100%">
	                                                        <img id="qrimg" class="tab-img-icon" alt="icon" style="position: relative; top: 0; max-width: 200px; margin-right: 10px;" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ \Illuminate\Support\Facades\Crypt::decrypt($detail->usdt) }}">
	                                                    </div>
														</div>
															
														
														@else
														<div class="form-group mb-3">
															<label class="form-label" for="deposits"><h6>Deposits are not available right now. Please try again later.</h6></label>
														</div>
														@endif	
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
							@if((!is_null($detail->usdt) && $detail->usdt_deposit_status==1) || (!is_null($detail->usdtbep20) && $detail->usdtbep20_deposit_status==1))
							<br>
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
	
	<script type="text/javascript">
		$("#cai").on('blur',function(){
            $("#amountusdt").val(Number.parseFloat($("#cai").val()*{{$detail->price}}).toFixed(6));
            $("#amountusdt").blur();
        });
        $("#amountusdt").on('blur',function(){
            $("#cai").val(Number.parseFloat($("#amountusdt").val()/{{$detail->price}}).toFixed(6));
            $("#paydetail").show();
        });
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
                      
                      alert("Address Copied Successfully: (" + $(value).html() + ").");
                    }
    
    </script>

    <script type="text/javascript">
        /*function copyaddr(value){
        navigator.clipboard.writeText($(value).html());
        alert("Address Copied Successfully: (" + $(value).html() + ").");
        }*/

        /*$("#amountusdtb").on('blur',function(){
            $("#styb").val(Number.parseFloat($("#amountusdtb").val()/{{$detail->price}}).toFixed(6));
            $("#paydetail").show();
        });*/

        $("#currency").on('change',function(){
            if($("#currency").val()=='usdt'){
                $("#qrimg").attr('src','https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{\Illuminate\Support\Facades\Crypt::decrypt($detail->usdt)}}');
                $("#addr").html('{{(\Illuminate\Support\Facades\Crypt::decrypt($detail->usdt))}}');
            }else if($("#currency").val()=='usdtbep20'){
                $("#qrimg").attr('src','https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{\Illuminate\Support\Facades\Crypt::decrypt($detail->usdtbep20)}}');
                $("#addr").html('{{(\Illuminate\Support\Facades\Crypt::decrypt($detail->usdtbep20))}}');
            }
        });

        $(document).ready(function(){
            $('#currency').change();
        });
    </script>
	
</body>
</html>
