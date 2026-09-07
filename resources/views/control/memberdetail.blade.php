
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Member Profile</title>
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
                            <h1 class="title">Member Profile</h1>
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
                                    <h4>Search</h4>
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
                                    <div class="form-group" style="margin-top: 10px;">
                                        <form action="/Main/SearchUserId" method="POST">
                                        @csrf
                                           <div class="col-lg-3">
                                            <input type="text" class="form-control @error('userrid') is-invalid @enderror" name="userrid" value="" required="">
                                            @error('userrid')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                          </div>
                                          
                                          <div class="col-lg-3">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                          </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="clearfix"></div>

                <?php $membera=$editdata?(array)$editdata:array();?>
                @if(sizeof($membera))

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left"><a data-toggle="modal" href="#cmpltadminModal-1" style="color: #fff;">Personal Information ({{ $editdata->uuid }})</a> <br>Sponsor ({{ $editdata->guiderid }} - {{ $editdata->guidername }})</h2>
                                
                                <?php if ($editdata->permission == 1) { ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="location.href='/Main/Lock/{{ $editdata->uuid }}';" style="float: right;"><i class="fa fa-lock"></i> Lock</button>
                                <?php } else{ ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="location.href='/Main/Unlock/{{ $editdata->uuid }}';" style="float: right;"><i class="fa fa-lock"></i> Unlock</button>
                                <?php } ?>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="form-container">
                                        <form action="/Main/EditUser" method="post">
                                            @csrf
                                            <div class="row">
                                                
                                                <div class="col-xs-12">
                                                                                                        
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Email</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="hidden" name="oldemail" class="form-control" value="{{ $editdata->email }}" placeholder="Email">
                                                                    <input type="text" name="email" class="form-control" value="{{ $editdata->email }}" placeholder="Email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Sponsor</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="sponser" class="form-control" value="{{ $editdata->guiderid }} ({{ $editdata->guidername }})" placeholder="Sponser" readonly="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Name </label>
                                                                <span class="desc"></span>
                                                                <input type="text" name="name" class="form-control" value="{{ $editdata->usersname }}" placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">Contact</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                <div class="col-lg-3" style="margin:0; padding:0;">
                                                                    <i class=""></i>
                                                                    <input type="text" name="countrycode" class="form-control" value="+{{ $editdata->ccode }}" readonly="">
                                                                </div>
                                                                <div class="col-lg-9" style="margin:0; padding: 0;">
                                                                    <i class=""></i>
                                                                    <input type="text" name="contact" class="form-control" value="{{ $editdata->contact }}" placeholder="Phone">
                                                                </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Userid</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="uuid" class="form-control" value="{{ $editdata->uuid }}" placeholder="Userid" readonly="">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="form-group">
                                                                <label class="form-label">Coin Address (BEP20)</label>
                                                                <span class="desc">"enter correct address"</span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="bep20address" class="form-control @error('bep20address') is-invalid @enderror" @error('bep20address') value="" @else value="{{ $editdata->bep20address }}" @enderror placeholder="Coin Address">
                                                                  @error('bep20address')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                        <div class="col-lg-6 no-pr">
                                                            <div class="form-group">
                                                                <label class="form-label">USDT TRC20 Address</label>
                                                                <span class="desc">"enter TRC20 address"</span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="usdtaddress" class="form-control @error('usdtaddress') is-invalid @enderror" @error('usdtaddress') value="" @else value="{{ $editdata->usdtaddress }}" @enderror placeholder="USDT TRC20 Address">
                                                                  @error('usdtaddress')
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
                                                                <label class="form-label">USDT BEP20 Address</label>
                                                                <span class="desc">"enter USDT BEP20 address"</span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="usdtbep20address" class="form-control @error('usdtbep20address') is-invalid @enderror" @error('usdtbep20address') value="" @else value="{{ $editdata->usdtbep20address }}" @enderror placeholder="USDT BEP20 Address">
                                                                  @error('usdtbep20address')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                                </div>
                                                            </div>
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

                <div class="clearfix"></div>

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Change Password (Current Password : @if(!is_null($editdata->showpassword)) {{ \Crypt::decrypt($editdata->showpassword) }} @endif)</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="form-container">
                                        <form action="/Main/UserChangePassword" method="post">
                                            @csrf
                                            <div class="row">
                                                
                                                <div class="col-xs-12">
                                                                                                        
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-6 no-pl">
                                                            <div class="form-group">
                                                                <label class="form-label">Username</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('userid') is-invalid @enderror" name="userid" value="{{ $editdata->uuid }}" required="" readonly="">
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
                                                                <label class="form-label">New Password</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" class="form-control @error('password') is-invalid @enderror" name="password" value="" required="">
                                                                    @error('password')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="pull-left">
                                                        <h4><i class="fa fa-info-circle color-primary complete f-s-14"></i><small>Enter a strong password</small></h4>
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

                <div class="modal fade col-xs-12" id="cmpltadminModal-1" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="/Main/UserPermissions/{{ $editdata->uuid }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" style="color:#000;">Change Permissions</h4>
                            </div>
                            <div class="modal-body">
                                

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Withdrawal Status </label>
                                        <select id="assetstatus" name="assetstatus" class="form-control @error('assetstatus') is-invalid @enderror">
                                            <option value="{{$editdata->asset_status}}">{{$editdata->asset_status}}</option>
                                            <option value="Not Open">Not Open</option>
                                            <option value="Open">Open</option>
                                          </select>
                                          @error('assetstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">USDT TRC20 Address </label>
                                        <select id="usdtnull" name="usdtnull" class="form-control @error('usdtnull') is-invalid @enderror">
                                            <option value=""></option>
                                            @if(!is_null($editdata->usdtaddress))
                                            <option value="NULL">NULL</option>
                                            @endif
                                          </select>
                                          @error('usdtnull')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">USDT BEP20 Address </label>
                                        <select id="usdtnull" name="usdtbep20null" class="form-control @error('usdtbep20null') is-invalid @enderror">
                                            <option value=""></option>
                                            @if(!is_null($editdata->usdtbep20address))
                                            <option value="NULL">NULL</option>
                                            @endif
                                          </select>
                                          @error('usdtbep20null')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Level Percentage</label>
                                        <input type="number" name="levelpercentage" class="form-control" value="{{ $editdata->levelpercentage }}" placeholder="Level Percentage" max="60">
                                          @error('levelpercentage')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">ROI Status </label>
                                        <select id="roistatus" name="roistatus" class="form-control @error('roistatus') is-invalid @enderror">
                                            <option value="{{$editdata->roi_status}}">{{$editdata->roi_status}}</option>
                                            <option value="Not Open">Not Open</option>
                                            <option value="Open">Open</option>
                                          </select>
                                          @error('roistatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Booster Status </label>
                                        <select id="roistatus" name="boosterstatus" class="form-control @error('roistatus') is-invalid @enderror">
                                            <option value="{{$editdata->booster_status}}">{{$editdata->booster_status}}</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="Active">Active</option>
                                          </select>
                                          @error('boosterstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Club Power Leg </label>
                                        <select id="powerstatus" name="powerstatus" class="form-control @error('powerstatus') is-invalid @enderror">
                                            <option value="{{$editdata->power_status}}">{{$editdata->power_status}}</option>
                                            <option value="Not Open">Not Open</option>
                                            <option value="Open">Open</option>
                                          </select>
                                          @error('powerstatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Lifetime Achievement Power Leg </label>
                                        <select id="lifetimestatus" name="lifetimestatus" class="form-control @error('lifetimestatus') is-invalid @enderror">
                                            <option value="{{$editdata->lifetime_status}}">{{$editdata->lifetime_status}}</option>
                                            <option value="Not Open">Not Open</option>
                                            <option value="Open">Open</option>
                                          </select>
                                          @error('lifetimestatus')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Silver Roi Status </label>
                                        <select id="silverstatus" name="silverstatus" class="form-control @error('silverstatus') is-invalid @enderror">
                                            <option value="{{$editdata->silver_status}}">{{$editdata->silver_status}}</option>
                                            <option value="Not Open">Not Open</option>
                                            <option value="Open">Open</option>
                                          </select>
                                          @error('silverstatus')
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

                @endif

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