
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Approve Withdraw</title>
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon" />
    <!-- For iPhone -->
    <link rel="apple-touch-icon-precomposed" href="{{asset('assets/images/apple-touch-icon-57-precomposed.png')}}">
    <!-- For iPhone 4 Retina display -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('assets/images/apple-touch-icon-114-precomposed.png')}}">
    <!-- For iPad -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('assets/images/apple-touch-icon-72-precomposed.png')}}">
    <!-- For iPad Retina display -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('assets/images/apple-touch-icon-144-precomposed.png')}}">


    <!-- CORE CSS FRAMEWORK - START -->
    <link href="{{asset('assets/css/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/font-awesome.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/cryptocoins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/animate.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
    <!-- CORE CSS FRAMEWORK - END -->

    <!-- HEADER SCRIPTS INCLUDED ON THIS PAGE - START -->
    <link href="{{asset('assets/css/all.css')}}" rel="stylesheet" type="text/css" media="screen">
    <!-- HEADER SCRIPTS INCLUDED ON THIS PAGE - END -->


    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
    <!-- CORE CSS TEMPLATE - END -->

</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class=" ">
    <!-- START TOPBAR -->
@include('control.topbaradmin')
    <!-- END TOPBAR -->
    <!-- START CONTAINER -->
    <div class="page-container row-fluid container-fluid">

        <!-- SIDEBAR - START -->
@include('control.sidebaradmin')
        <!--  SIDEBAR - END -->


        <!-- START CONTENT -->
        <div id="main-content" class=" ">
            <section class="wrapper main-wrapper row" style=''>

                <div class='col-xs-12'>
                    <div class="page-title">

                        <div class="pull-left">
                            <!-- PAGE HEADING TAG - START -->
                            <h1 class="title">Approve Withdraw</h1>
                            <!-- PAGE HEADING TAG - END -->
                        </div>

                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12">
                    <section class="box over-h">
                        <div class="content-body">    
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Withdraw Details</h4>
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade in">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                {{ session('success') }}
                                        </div>
                                    @endif
                                    @if (session('warning'))
                                        <div class="alert alert-error alert-dismissible fade in">
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            {{ session('warning') }}
                                        </div>
                                    @endif
                                   
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="clearfix"></div>


                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                           
                            <div class="content-body">    
                                <div class="row">
                                    <div class="form-container">
                                        <form action="/Main/WithdrawUpdate" method="post">
                                            @csrf
                                            <input type="hidden" name="paymentid" value="{{ $withdata->txnid }}">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="this.disabled=true;this.innerText='Processing, please wait...';window.location.href='/Main/WithdrawApproveByGateway/{{ $withdata->txnid }}';" style="float: right;"><i class="fa fa-dollar"></i> Pay with Gateway</button>
                                                </div>
                                                <div class="col-xs-12">
                                                                                                        
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Username</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="userid" id="userid" class="form-control @error('userid') is-invalid @enderror" value="{{ $withdata->uuid }}" placeholder="UserId" required="" readonly="">
                                                                        @error('userid')
                                                                            <span class="invalid-feedback" role="alert">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Name</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('usersname') is-invalid @enderror" name="usersname" value="{{ $withdata->usersname }}" required="" readonly="">
                                                                    @error('usersname')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Withdraw Amount (CAI) </label>
                                                                <span class="desc"></span>
                                                                <input type="text" class="form-control @error('amountsftc') is-invalid @enderror" name="amountsftc" value="{{ $withdata->amountsftc }}" required="" readonly="">
                                                                @error('amountsftc')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Amount ($)</label>
                                                                <span class="desc"></span>
                                                                <input type="text" class="form-control @error('amountusdt') is-invalid @enderror" name="amountusdt" value="{{ $withdata->amountusdt }}" required="" readonly="">
                                                                @error('amountusdt')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Deduction</label>
                                                                <span class="desc"></span>
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('deduction') is-invalid @enderror" name="deduction" value="{{ $withdata->deduction }}" required="" readonly="">
                                                                    @error('deduction')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Net Amount</label>
                                                                <span class="desc"></span>
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('netamount') is-invalid @enderror" name="netamount" value="{{ $withdata->net_amount }}" required="" readonly="">
                                                                    @error('netamount')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Withdraw Address</label>
                                                                <span class="desc"></span>
                                                                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ $withdata->address }}" required="" readonly="">
                                                                    @error('address')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($withdata->currency=='bank')
                                                    <div class="col-lg-12">
                                                        
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Bank Name</label>
                                                                <span class="desc"></span>
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('bankname') is-invalid @enderror" name="bankname" value="{{ $withdata->bankname }}" required="" readonly="">
                                                                    @error('bankname')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Account Holder Name</label>
                                                                <span class="desc"></span>
                                                                    <input type="text" class="form-control @error('accountname') is-invalid @enderror" name="accountname" value="{{ $withdata->accountname }}" required="" readonly="">
                                                                    @error('accountname')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Withdraw Status</label>
                                                                <span class="desc"></span>
                                                                <select id="withstatus" name="withstatus" class="form-control input-style @error('withstatus') is-invalid @enderror">
                                                                    <option value="{{ $withdata->status }}">{{ $withdata->status }}</option>
                                                                    <option value="Pending">Pending</option>
                                                                    <option value="Success">Success</option>
                                                                    <option value="Reject">Reject</option>
                                                                </select>
                                                                @error('withstatus')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Transaction Hash</label>
                                                                <span class="desc"></span>
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('txnhash') is-invalid @enderror" name="txnhash" value="{{ $withdata->txnhash }}" required="">
                                                                    @error('txnhash')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        
                                                        <div class="col-lg-6 no-pr">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="pull-left">
                                                        <h4><i class="fa fa-info-circle color-primary complete f-s-14"></i><small>Note that All the information must be right</small></h4>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary btn-corner right15"><i class="fa fa-check"></i> Update</button>
                                                        <button type="button" class="btn btn-default btn-corner"><i class="fa fa-times"></i></button>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </section>
                </div>


                <!-- MAIN CONTENT AREA ENDS -->
            </section>
        </div>
        <!-- END CONTENT -->

    </div>
    <!-- END CONTAINER -->
    <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->


    <!-- CORE JS FRAMEWORK - START -->
    <script src="{{asset('assets/js/jquery-1.11.2.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.easing.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/pace.min.js')}}"></script>
    <script src="{{asset('assets/js/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/viewportchecker.js')}}"></script>
    <script>
        window.jQuery||document.write('<script src="{{asset('assets/js/jquery-1.11.2.min.js')}}"><\/script>');
    </script>
    <!-- CORE JS FRAMEWORK - END -->


    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script> 
    <script src="{{asset('assets/js/additional-methods.min.js')}}"></script> 
    <script src="{{asset('assets/js/form-validation.js')}}"></script> 
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->


    <!-- CORE TEMPLATE JS - START -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <!-- END CORE TEMPLATE JS - END -->


</body>

</html>