
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Company Profile</title>
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
                            <h1 class="title">Company Profile</h1>
                            <!-- PAGE HEADING TAG - END -->
                        </div>

                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left"><a data-toggle="modal" href="#cmpltadminModal-1" style="color: #fff;">Edit Details</a></h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="form-container">
                                        <form action="/Main/CompanyProfile" method="post">
                                            @csrf
                                            <div class="row">
                                                
                                                <div class="col-xs-12">
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
                                                    
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">USDT Address</label>
                                                                <span class="desc">"enter TRC20 Address"</span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('usdtaddress') is-invalid @enderror" name="usdtaddress"  value="{{ \Illuminate\Support\Facades\Crypt::decrypt($address->usdtaddress) }}" required>
                                                                    @error('usdtaddress')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">USDT BEP20 Address</label>
                                                                <span class="desc">"enter USDT BEP20 Address"</span>
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('usdtbep20address') is-invalid @enderror" name="usdtbep20address"  value="{{ \Illuminate\Support\Facades\Crypt::decrypt($address->usdtbep20address) }}" required>
                                                                    @error('usdtbep20address')
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
                                                                <label class="form-label">Coin Price </label>
                                                                <span class="desc"></span>
                                                                <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $address->price }}" step="0.000001">
                                                                @error('price')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">MGPT Address</label>
                                                                <span class="desc">"enter MGPT Address"</span>
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('mgptaddress') is-invalid @enderror" name="mgptaddress"  value="{{ \Illuminate\Support\Facades\Crypt::decrypt($address->mgptaddress) }}">
                                                                    @error('mgptaddress')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                            </div>
                                                        </div> -->
                                                        <div class="col-lg-6 no-pr">
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="pull-left">
                                                        <h4><i class="fa fa-info-circle color-primary complete f-s-14"></i><small>Please fill carefully</small></h4>
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
<div class="modal fade col-xs-12" id="cmpltadminModal-1" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="/Main/StatusCompanyProfile" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" style="color:#000;">Change Permissions</h4>
                            </div>
                            <div class="modal-body">
                                <!-- <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">MGPT Deposit Status </label>
                                        <select id="mgptdepstatus" name="mgptdepstatus" class="form-control @error('mgptdepstatus') is-invalid @enderror ">
                                            <option value="{{$address->mgptdepstatus}}">{{$address->mgptdepstatus}}</option>
                                            <option value="On">On</option>
                                            <option value="Off">Off</option>
                                          </select>
                                          @error('mgptdepstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">MGPT Withdrawal Status </label>
                                        <select id="mgptwithstatus" name="mgptwithstatus" class="form-control @error('mgptwithstatus') is-invalid @enderror">
                                            <option value="{{$address->mgptwithstatus}}">{{$address->mgptwithstatus}}</option>
                                            <option value="On">On</option>
                                            <option value="Off">Off</option>
                                          </select>
                                          @error('mgptwithstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">USDT TRC20 Deposit Status </label>
                                        <select id="usdttrcdepstatus" name="usdttrcdepstatus" class="form-control @error('usdttrcdepstatus') is-invalid @enderror">
                                            <option value="{{$address->usdttrcdepstatus}}">{{$address->usdttrcdepstatus}}</option>
                                            <option value="On">On</option>
                                            <option value="Off">Off</option>
                                          </select>
                                          @error('usdttrcdepstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">USDT TRC20 Withdrawal Status </label>
                                        <select id="usdttrcwithdstatus" name="usdttrcwithdstatus" class="form-control @error('usdttrcwithdstatus') is-invalid @enderror">
                                            <option value="{{$address->usdttrcwithdstatus}}">{{$address->usdttrcwithdstatus}}</option>
                                            <option value="On">On</option>
                                            <option value="Off">Off</option>
                                          </select>
                                          @error('usdttrcwithdstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">USDT BEP20 Deposit Status </label>
                                        <select id="usdtbep20depstatus" name="usdtbep20depstatus" class="form-control @error('usdtbep20depstatus') is-invalid @enderror">
                                            <option value="{{$address->usdtbep20depstatus}}">{{$address->usdtbep20depstatus}}</option>
                                            <option value="On">On</option>
                                            <option value="Off">Off</option>
                                          </select>
                                          @error('usdtbep20depstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">USDT BEP20 Withdrawal Status </label>
                                        <select id="usdtbep20withstatus" name="usdtbep20withstatus" class="form-control @error('usdtbep20withstatus') is-invalid @enderror">
                                            <option value="{{$address->usdtbep20withstatus}}">{{$address->usdtbep20withstatus}}</option>
                                            <option value="On">On</option>
                                            <option value="Off">Off</option>
                                          </select>
                                          @error('usdtbep20withstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                <button class="btn btn-success" type="submit">Save changes</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
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