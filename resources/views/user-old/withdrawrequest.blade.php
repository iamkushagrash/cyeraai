<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Request for Conversion</title>
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
				<li class="breadcrumb-item active">Withdraw</li>
			</ul>
			
			<h1 class="page-header">
				Request for Withdraw  <!-- <span style="color:white;font-size:18px">Current Price = $0.12</span> --><!-- <small>page header description goes here...</small> -->
			</h1>
			@foreach($errors as $error)
				<div class="alert alert-danger">
					<strong>Alert!</strong> {{$error->message()}}
				</div>
			@endforeach
			<div class="row">
				<form action="/User/WithdrawRequest" method="POST" >
                    <div class="card">
                        <div class="card-body">
                                @csrf
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
                                    <div class="form-group mb-3">
                                        @php
                                        $caiBalance = floor($user->remainingIncome());
                                         $usdtBalance = $caiBalance * 0.12;
                                            @endphp
                                            <h5>
                                                <label class="form-label" for="exampleFormControlInputBalance">
                                                    Balance : {{ $caiBalance }} $ 
                                                    <!-- <span class="text-success"> (≈ ${{ number_format($usdtBalance, 2) }} USDT)</span> -->
                                                </label>
                                            </h5>

                                    </div>
                                     <?php $n=1; $m=0; $msg='';?>
		                        	<?php $t=0; $b=0; $k=0; ?>
		                            @if(!is_null($user->assetDetail()))
		                                @if(($detail->usdt_withdrawal_status==1 && !is_null($user->assetDetail()->usdttrc20addr)) /*||($detail->usdt_withdrawal_status==0 )*/)
		                                    <?php $t=1; ?>
		                                @else
		                                    <?php $t=0; $msg=$msg." TRC20 ";?>
		                                @endif{{-- {{$t.' '.$detail->usdt_withdrawal_status.' '.$user->assetDetail()->first()->usdttrc20addr}}<br> --}}
		                                @if(($detail->usdtbep20_withdrawal_status==1 && !is_null($user->assetDetail()->usdtbep20addr	)) /*|| ($detail->usdtbep20_withdrawal_status==0 )*/)
		                                    <?php $b=1; ?>{{-- {{'hello bep'}} --}}
		                                @else
		                                    <?php $b=0; $msg=$msg." USDT BEP20 ";?>
		                                @endif{{-- {{$b.' '.$detail->usdtbep20_withdrawal_status.' '.$user->assetDetail()->first()->usdtbep20addr}}<br> --}}
		                                @if(($detail->bank_withdrawal_status==1 && !is_null($user->assetDetail()->accountno)) /*|| ($detail->sftc_withdrawal_status==0 && !is_null($user->assetDetail()->first()->bep20addr))*/)
		                                    <?php $k=1; ?>{{-- {{'hellokto'}} --}}
		                                @else
		                                    <?php $k=0; $msg=$msg." Bank Account ";?>
		                                @endif{{-- {{$k.' '.$detail->bank_withdrawal_status.' '.$user->assetDetail()->first()->accountno}}<br> --}}
		                            @else
		                                <?php $n=0; ?>
		                            @endif {{-- {{($t || $b || $k)}} --}}
		                            @if($t || $b || $k)
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body pb-2">
                                                        <div class="row">
                                                            <input type="hidden" name="honeypotu" value="{{\Session::get('logtime')}}">

                                                            <!-- <div class="form-group mb-4">
                                                                <label class="form-label" for="amount"><h6>Sell Amount(CAI)</h6></label>
                                                                <input type="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min ="10" placeholder="0" max="{{ceil($user->remainingIncome())}}" @if(!is_null($remaining)) value="{{$remaining->amountsftc}}" disabled @endif step="0.01" required>
                                                                @error('amount')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div> --> 

                                                            <div class="form-group mb-4">
                                                                <label class="form-label" for="amountusdt"><h6> Amount($)</h6></label>
                                                                <input type="amountusdt" class="form-control @error('amountusdt') is-invalid @enderror" id="amountusdt" name="amountusdt" min ="10" placeholder="0" max="{{floor($user->remainingIncome())}}" step="0.01" @if(!is_null($remaining)) value="{{$remaining->amountusdt}}" disabled @endif required>
                                                                @error('amountusdt')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div> 
                                                            <div class="form-group mb-3">
																<label class="form-label" for="currency"><h6>Currency</h6></label>
																<select type="button" class="form-select" id="selltype" name="currency">
																	@if($detail->usdt_withdrawal_status==1 && !is_null($user->assetDetail()->usdttrc20addr))
																	<option value="usdt">USDT TRC20</option>
																	<?php $m=$m||1; ?>@endif
                                                    				@if($detail->usdtbep20_withdrawal_status==1 && !is_null($user->assetDetail()->usdtbep20addr	))
																	<option value="usdtbep20">USDT BEP20</option>
																	<?php $m=$m||1; ?>@endif
																</select>
					                                            @error('currency')
					                                                <span class="invalid-feedback" role="alert">
					                                                    <strong>{{ $message }}</strong>
					                                                </span>
					                                            @enderror
															</div>

	                                                        <div class="form-group mb-3">
	                                                            <label class="form-check-label" for="defaultCheck2"><h6>Wallet Address</h6></label>
	                                                            <label class="form-label" id="withdrawaladdress" style="font-size: .755rem; word-break: break-all; overflow-wrap: break-word;white-space: normal; color: #fff;">{{$user->assetDetail()->usdttrc20addr}}</label>
	                                                        </div>
                                                            <!-- <div class="form-group mb-4">
                                                                <label class="form-label" for="account"><h6>Password</h6></label>
                                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                                                                @error('password')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div> --> 
                                                            @if(!is_null($remaining))
                                                            <div class="form-group mb-4">
                                                                <label class="form-label" for="otp"><h6>OTP</h6></label>
                                                                <input type="number" class="form-control @error('otp') is-invalid @enderror" id="otp" name="otp" placeholder="One Time Password" required>
                                                                @error('otp')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div> 
                                                            <!-- <div class="form-group mb-4">
                                                                <a class="btn btn-theme" href="/User/resendWithdrawOtp"> Resend OTP</a>
                                                            </div> -->
                                                            @endif          
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-check-label" for="defaultCheck2"><h6>Minimum Request Amount : $ 10 (Amount should be multiple of 10$)</h6></label>
                                                        </div>
                                                        </br>
                                                        @if(ceil($user->remainingIncome())>=10)
                                                        <button type="submit" class="btn btn-theme mb-1" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Submit</button>
                                                            @if(!is_null($remaining))
                                                                &nbsp;&nbsp;&nbsp;<a class="btn btn-theme mb-1" href="/User/resendWithdrawOtp"> Resend OTP</a>
                                                            @endif
                                                        @else
                                                        <h6> You do not have enough amount.Please first have some. Minimum is $ 10 </h6>
                                                        @endif
                                                </div>
                                                <div class="card-arrow">
                                                    <div class="card-arrow-top-left"></div>
                                                    <div class="card-arrow-top-right"></div>
                                                    <div class="card-arrow-bottom-left"></div>
                                                    <div class="card-arrow-bottom-right"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    @else
                                        <h5><label class="form-label" for="exampleFormControlInputBalance">Please Update Wallet Address</label></h5>
                                    @endif
                                </div>
                                <span class="d-lg-inline d-none"><br></span>
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


<div class="modal" id="myModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📢 Official Cyera AI World Update🎉</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body"><p>Dear All Key Leaders and Associates,</p>
        <p>
          We are excited to announce that we have made our 
          <strong>MetaSwap</strong>  live on <strong>CyeraWallet</strong>.
        </p>
        <p>
          <strong>Starting today, all your withdrawals will be processed in CyeraWallet only.</strong>
        </p>
        <p>
          It is a humble request to all of you to download CyeraWallet from our <strong>Console</strong> or from the link given below and place your withdrawal request from there.
        </p>
        <p><button class="btn btn-outline-theme" onclick="location.href='https://console.cyera.ai/cyerawallet10.apk';">Download CyeraWallet</button></p>
        <p>
          We appreciate your understanding and cooperation as Cyera AI World continues to upgrade for a smoother and more transparent experience.
        </p>
        <p>
          Thank you for your support!<br>
          🌐 <strong>Cyera AI World Administration</strong> 💥💥💥🙏
        </p>
      </div>
      <!-- <div class="modal-footer">
        <a class="btn btn-outline-theme" id="modalbtn">Continue</a>
      </div> -->
    </div>
  </div>
</div>

	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	
	 <script type="text/javascript">
        $(document).ready(function(){
            $('#currency').change();
            $('#selltype').change();
        });

        $("#selltype").on('change',function(){
            @if(!is_null($user->assetDetail()))
            @if($detail->usdt_withdrawal_status==1 && !is_null($user->assetDetail()->usdttrc20addr))
            if($("#selltype").val()=='usdt'){
                $("#withdrawaladdress").html('{{$user->assetDetail()->usdttrc20addr}}');
            }@endif  @if($detail->usdtbep20_withdrawal_status==1 && !is_null($user->assetDetail()->usdtbep20addr)) if($("#selltype").val()=='usdtbep20'){
                $("#withdrawaladdress").html('{{$user->assetDetail()->usdtbep20addr}}');
            } @endif @if($detail->bank_withdrawal_status==1 && !is_null($user->assetDetail()->accountno)) if($("#selltype").val()=='bank'){
                $("#withdrawaladdress").html('{{$user->assetDetail()->accountname}}({{$user->assetDetail()->accountno}})');
            } @endif
            @endif
        });


        /*$("#amount").on('blur',function(){
            $("#amountusdt").val(Number.parseFloat($("#amount").val()*{{$detail->price}}).toFixed(6));
        });
        $("#amountusdt").on('blur',function(){
            $("#amount").val(Number.parseFloat($("#amountusdt").val()/{{$detail->price}}).toFixed(6));
        });*/
        
    </script>
    
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var myModal = new bootstrap.Modal(document.getElementById('myModal'));
        myModal.show();
      });
    </script>
	
</body>
</html>
