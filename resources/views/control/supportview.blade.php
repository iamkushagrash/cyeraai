
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Support</title>
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
    <link href="{{asset('assets/css/animate.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
    <!-- CORE CSS FRAMEWORK - END -->

    <!-- HEADER SCRIPTS INCLUDED ON THIS PAGE - START -->

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
        <section id="main-content" class=" ">
            <div class="wrapper main-wrapper row" style=''>

                <div class='col-xs-12'>
                    <div class="page-title">

                        <div class="pull-left">
                            <!-- PAGE HEADING TAG - START -->
                            <h1 class="title">Support</h1>
                            <!-- PAGE HEADING TAG - END -->
                        </div>

                        <div class="pull-right hidden-xs">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="/Main/Dashboard"><i class="fa fa-home"></i>Home</a>
                                </li>
                                <li>
                                    <a href="/Main/Support">Support</a>
                                </li>
                                <li class="active">
                                    <strong>View</strong>
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- MAIN CONTENT AREA STARTS -->

                <div class="col-lg-12">
                    <section class="box nobox ">
                        <div class="content-body">
                            <div class="row">

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="mail_content">

                                        <div class="row" style="min-height: 500px;">
                                            <div class="col-xs-12">
                                                <h3 class="mail_head"><a href="/Main/User/{{$viewticket[0]->uuid}}">UserID - {{$viewticket[0]->uuid}}</a><br>Title - {{$viewticket[0]->title}}</h3>
                                                <h3 class="mail_head"></h3>
                                                
                                            </div>

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
                                            </div>

                                            <form action="/Main/ReplyTicket" method="POST">
                                                @csrf
                                            <div class="col-xs-12 mail_view">
                                                @foreach ($viewticket as $view)
                                                <?php if($view->ustatus==0){ ?>
                                                    <h4>User : <span style="color: #FF0000;"><b>{!! $view->htext !!} </b>({{$view->created_at}})</span></h4>
                                                <?php }else{ ?>
                                                    <h4>Support : <span style="color: #fff;"><b>{!! $view->htext !!} </b>({{$view->created_at}})</span></h4>
                                                <?php } ?>
                                                @endforeach
                                                
                                            </div>

                                            <div class="col-xs-12 mail_view_reply">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <input type="hidden" value="{{ $viewticket[0]->subid}}" name="ticket">
                                                        <input type="hidden" value="{{ $viewticket[0]->userid}}" name="user">
                                                        <textarea name="textmsg" class="form-control autogrow" cols="3" id="field-7" placeholder="Add Reply" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 120px;"></textarea>
                                                        @error('textmsg')
                                                           <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $message }}</strong>
                                                           </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary btn-corner right15"><i class="fa fa-check"></i> Submit</button>
                                                        <button type="button" class="btn btn-default btn-corner" onclick="window.location.href='/Main/Support'"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            </form>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </div>

                <!-- MAIN CONTENT AREA ENDS -->
            </div>
        </section>
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
        window.jQuery || document.write('<script src="{{asset('assets/js/jquery-1.11.2.min.js')}}"><\/script>');
    </script>
    <!-- CORE JS FRAMEWORK - END -->

    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->

    <script src="{{asset('assets/js/autosize.min.js')}}"></script>
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

    <!-- CORE TEMPLATE JS - START -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <!-- END CORE TEMPLATE JS - END -->

</body>

</html>