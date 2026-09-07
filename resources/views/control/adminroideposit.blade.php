
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Silver(ROI) Topup</title>
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
    {{-- <link href="{{asset('assets/css/all.css')}}" rel="stylesheet" type="text/css" media="screen"> --}}
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
                            <h1 class="title">Admin Silver(ROI) Topup to User</h1>
                            <h3 style="text-align:left;"></h3>
                            <!-- PAGE HEADING TAG - END -->
                        </div>

                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-lg-12">

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
                    @if ($errors->any())
                        @foreach($errors->all() as $error)
                          <div class="alert alert-error alert-dismissible fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                             {{ $error }}
                          </div>
                        @endforeach
                    @endif
                    <section class="box has-border-left-3">
                        
                            <div class="content-body">    
                                <div class="row">
                                    @if(is_null($user))
                                    <div class="form-container">
                                        <form action="" method="post" id="findUser">
                                            <header class="panel_header">
                                                <h2 class="title pull-left">Search User</h2>
                                            </header>
                                            @csrf
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="form-label">User ID</label>
                                                            <div class="controls">
                                                                <i class="fa"></i>
                                                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="" placeholder="UserID" required="" >
                                                                @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">     
                                                <div class="pull-right">
                                                    <button type="submit" class="btn btn-primary btn-corner right15"><i class="fa fa-check"></i> Submit</button>
                                                    <button type="button" class="btn btn-default btn-corner"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @else
                                    @if (count($activeplan))
                                          <div class="alert alert-success alert-dismissible fade in">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                             User already have an package
                                          </div>
                                    @endif
                                    <div class="form-container">
                                        <form action="/Main/AdminROIopupToUser" method="post" id="detail">
                                            @csrf
                                            <input type="hidden" name="honeypotu" value="{{$user->id}}">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    {{-- <div class="col-lg-12"> --}}
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label">UserID</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <i class=""></i>
                                                                    <input type="text" name="uuid" class="form-control @error('uuid') is-invalid @enderror" value="{{$user->userid}}" placeholder="UserID" required="" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Name</label>
                                                                <span class="desc"></span>
                                                                <div class="controls">
                                                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{$user->name}}" placeholder="Name" required="" readonly >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {{-- </div> --}}
                                                    
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Amount ($)</label>
                                                            <span class="desc">"Minimum 100 $"</span>
                                                            <div class="controls">
                                                                
                                                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="" placeholder="USD Quantity"  required="" >
                                                                @error('amount')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Account Password</label>
                                                            <span class="desc"></span>
                                                            <div class="controls">
                                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"placeholder="Enter Password"> 
                                                                @error('password')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror                       
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="pull-left">
                                                        <h4><i class="fa fa-info-circle color-primary complete f-s-14"></i><small>Note that transaction cannot revert</small></h4>
                                                    </div>
                                                    <div class="pull-right">
                                                        
                                                        <button type="submit" class="btn btn-primary btn-corner right15" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();"><i class="fa fa-check"></i> Submit</button>
                                                        <button type="button" class="btn btn-default btn-corner"><i class="fa fa-times"></i></button>
                                                                                                               
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
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
    <script type="text/javascript">
        $(document).ready(function(){
            
        });

        
    </script>

</body>

</html>