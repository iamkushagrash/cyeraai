
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin USDT Transfer Report</title>
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
    <link rel="stylesheet" href="{{asset('assets/datatable/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/datatable/buttons.dataTables.min.css')}}">

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
                            <h1 class="title">Admin USDT Transfer Report</h1>
                            <!-- PAGE HEADING TAG - END -->
                        </div>

                        <div class="pull-right hidden-xs">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="/Main/Dashboard"><i class="fa fa-home"></i>Home</a>
                                </li>
                                
                                <li class="active">
                                    <strong>Admin Wallet Report</strong>
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
               

                <div class="clearfix"></div>
                <!-- MAIN CONTENT AREA STARTS -->

                <div class="col-xs-12">
                    <section class="box over-h">
                        <div class="content-body">    
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Search</h4>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <form action="/Main/AdminUSDTReport" method="POST">
                                        @csrf
                                           <div class="col-lg-3">
                                            <input type="date" name="fromdate" class="form-control" value="">
                                          </div>
                                          <div class="col-lg-3">
                                            <input type="date" name="todate" class="form-control" value="">
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

                <div class="col-xs-12">
                    <section class="box over-h">
                        <div class="content-body">    
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Total : {{round($sumamount->amountusdt,2)}}$ </h4>
                                    
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="clearfix"></div>


                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                        <header class="panel_header">
                            <h2 class="title pull-left">Admin USDT Transfer Report</h2>
                            
                        </header>
                        <div class="content-body">
                            <div class="row">
                                <div class="col-xs-12">

                                    <div class="table-responsive" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table table-small-font no-mb table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:left;">S.No.</th>
                                                    <th style="text-align:left;">UserID</th>
                                                    <th style="text-align:left;">Email</th>
                                                    <th style="text-align:left;">Name</th>
                                                    <th style="text-align:left;">Amount ($)</th>
                                                    <th style="text-align:left;">Date</th>
                                                    <th style="text-align:left;">Status</th>
                                                    <th style="text-align:left;">Remark</th>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                                    @foreach($topup as $topup)
                                                    <tr style="color: #fff;">
                                                        <td>{{$i}}</td>
                                                        <td>{{$topup->userid}}</td>
                                                        <td>{{$topup->email}}</td>
                                                        <td>{{$topup->usersname}}</td>
                                                        <td>{{round($topup->amountusdt,2)}}</td>
                                                        <td>{{$topup->created_at}}</td>
                                                        <td><span class="{{$topup->statusclass}}">{{$topup->status}}</span></td>
                                                        <td>{{$topup->cmnt}}</td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                    @endforeach                                         
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>


                <div class="clearfix"></div>


                


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
        window.jQuery||document.write('<script src="{{asset('assets/js/jquery-1.11.2.min.js')}}"><\/script>');
    </script>
    <!-- CORE JS FRAMEWORK - END -->

    <script src="{{asset('assets/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/datatable/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/datatable/jszip.min.js')}}"></script>
    <script src="{{asset('assets/datatable/buttons.html5.min.js')}}"></script>
    <script>
        $(document).ready( function () {
        $('#tech-companies-1').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
    } );
    </script>


    <!-- CORE TEMPLATE JS - START -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <!-- END CORE TEMPLATE JS - END -->

</body>

</html>