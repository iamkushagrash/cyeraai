
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Income</title>
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
        <div id="main-content" class=" ">
            <section class="wrapper main-wrapper row" style=''>

                <div class='col-xs-12'>
                    <div class="page-title">

                        <div class="pull-left">
                            <!-- PAGE HEADING TAG - START -->
                            <h1 class="title">User Income</h1>
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
                                        <form action="/Main/UserIdIncomeSearch" method="POST">
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

                <?php $membera=$userdata?(array)$userdata:array();?>
                @if(sizeof($membera))

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left"><a href="/Main/User/{{$userdata['user']->userid}}" style="color: #fff;">Personal Information ({{ $userdata['user']->userid }} - {{ $userdata['user']->name }} - {{ $userdata['user']->email }})</a></h2>
                                
                                
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12" style="color:#fff;">
                                        <p>Total Staking Income : {{round($userdata['sumroi']->totalroi,2)}} $</p>
                                        <p>Total Direct Income : {{round($userdata['sumdirect']->totaldirect,2)}} $</p>
                                        <p>Total Staking Referral Income  : {{round($userdata['sumreferral']->totallevel,2)}} $</p>
                                        <p>Total Team Development : {{round($userdata['sumteamdevelopment']->totaltd,2)}} $</p>
                                        <p>Total Club Income: {{round($userdata['sumclub']->totalclub,2)}} $</p>
                                        <p>Total Income : {{round($userdata['totalincome'],4)}} $</p>
                                        <p>Total Withdraw : {{round($userdata['totalwithdraw']->amountusdt,2)}} $</p>
                                    </div>
                                </div>
                            </div>
                    </section>
                </div>

                <div class="clearfix"></div>

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Package History</h2>
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
                                                        <th style="text-align:left;">Amount (M)</th>
                                                        <th style="text-align:left;">Amount ($)</th>
                                                        <th style="text-align:left;">PaidBy</th>
                                                        <th style="text-align:left;">Type</th>
                                                        <th style="text-align:left;">Date</th>
                                                        <th style="text-align:left;">Status</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                                        @foreach($userdata['activeplan'] as $activeplan)
                                                        <tr style="color: #fff;">
                                                            <td>{{$i}}</td>
                                                            <td>{{$activeplan->userid}}</td>
                                                            <td>{{$activeplan->email}}</td>
                                                            <td>{{$activeplan->usersname}}</td>
                                                            <td>{{round($activeplan->amount,2)}}</td>
                                                            <td>{{round($activeplan->usdt,2)}}</td>
                                                            <td>{{$activeplan->fromname}} ({{$activeplan->fromid}})</td>
                                                            <td>{{$activeplan->staketype}}</td>
                                                            <td>{{$activeplan->created_at}}</td>
                                                            <td><span class="{{$activeplan->statusclass}}">{{$activeplan->status}}</span></td>
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Staking Income</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-2" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">Amount(M)</th>
                                                        <th style="text-align:left;">Amount($)</th>
                                                        <th style="text-align:left;">Package(M)</th>
                                                        <th style="text-align:left;">Package($)</th>
                                                        <th style="text-align:left;">Date</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                                        @foreach($userdata['totalroireceived'] as $reportbasic)
                                                        <tr style="color: #fff;">
                                                            <td>{{$i}}</td>
                                                            <td>{{round($reportbasic->amount,2)}}</td>
                                                            <td>{{round($reportbasic->amountusdt,2)}}</td>
                                                            <td>{{round($reportbasic->principal,2)}}</td>
                                                            <td>{{round($reportbasic->principalusdt,2)}}</td>
                                                            <td>{{$reportbasic->created_at}}</td>
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Direct Income</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-3" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">From ID</th>
                                                        <th style="text-align:left;">From Name</th>
                                                        <th style="text-align:left;">Amount (M)</th>
                                                        <th style="text-align:left;">Amount ($)</th>
                                                        <th style="text-align:left;">Date</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                
                                                        @foreach($userdata['totaldirectreceived'] as $directamount)
                                                        
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$directamount->fromid}}</td>
                                                            <td>{{$directamount->fromname}}</td>
                                                            <td><span style="color:#{{$directamount->statusclass}}">{{round($directamount->amount,2)}}</span></td>
                                                            <td>{{round($directamount->amountusdt,2)}}</td>
                                                            <td>{{$directamount->created_at}}</td>
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Staking Referral Income</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-4" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">Amount (M)</th>
                                                        <th style="text-align:left;">Amount ($)</th>
                                                        <th style="text-align:left;">Date</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                
                                                        @foreach($userdata['totalstakingreferralreceived'] as $referral)
                                                        
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{round($referral->amount,2)}}</td>
                                                            <td>{{round($referral->amountusdt,2)}}</td>
                                                            <td>{{$referral->txndate}}</td>
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Team Development Income</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-5" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">Amount (M)</th>
                                                        <th style="text-align:left;">Amount ($)</th>
                                                        <th style="text-align:left;">Date</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                
                                                        @foreach($userdata['totalteamdevelopmentreceived'] as $teamdev)
                                                        
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{round($teamdev->amount,2)}}</td>
                                                            <td>{{round($teamdev->amountusdt,2)}}</td>
                                                            <td>{{$teamdev->txndate}}</td>
                                                            
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Club Income</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-6" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">Amount (M)</th>
                                                        <th style="text-align:left;">Club Name</th>
                                                        <th style="text-align:left;">Date</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                
                                                        @foreach($userdata['totalclubreceived'] as $totalclubreceived)
                                                        
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{round($totalclubreceived->amount,2)}}</td>
                                                            <td>{{$totalclubreceived->clubname}}</td>
                                                            <td>{{$totalclubreceived->created_at}}</td>
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Lifetime Achievement Income</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-9" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">Reward name</th>
                                                        <th style="text-align:left;">Amount ($)</th>
                                                        <th style="text-align:left;">Date</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                
                                                        @foreach($userdata['totalrewardreceived'] as $totalrewardreceived)
                                                        
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$totalrewardreceived->rewardname}}</td>
                                                            <td>{{round($totalrewardreceived->amount,2)}}</td>
                                                            <td>{{$totalrewardreceived->created_at}}</td>
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

                <div class="col-lg-12">
                    <section class="box has-border-left-3">
                            <header class="panel_header">
                                <h2 class="title pull-left">Total Withdrawal</h2>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-8" class="table table-small-font no-mb table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:left;">S.No.</th>
                                                        <th style="text-align:left;">UserID</th>
                                                        <th style="text-align:left;">Amount(M)</th>
                                                        <th style="text-align:left;">Amount($)</th>
                                                        <th style="text-align:left;">Deduction ($)</th>
                                                        <th style="text-align:left;">Net Amount ($)</th>
                                                        <th style="text-align:left;">Type</th>
                                                        <th style="text-align:left;">Date</th>
                                                        <th style="text-align:left;">Status</th>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1; ?>
                                
                                                        @foreach($userdata['withdrawhistory'] as $withdrawhistory)
                                                        
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$withdrawhistory->uuid}}</td>
                                                            <td>{{round($withdrawhistory->amountsftc,2)}}</td>
                                                            <td>{{round($withdrawhistory->amountusdt,2)}}</td>
                                                            <td>{{round($withdrawhistory->deduction,2)}}</td>
                                                            <td>{{round($withdrawhistory->net_amount,2)}}</td>
                                                            <td>{{$withdrawhistory->currency}}</td>
                                                            <td>{{$withdrawhistory->created_at}}</td>
                                                            <td><span class="{{$withdrawhistory->statusclass}}">{{$withdrawhistory->status}}</span></td>
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
        $('#tech-companies-2').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
        $('#tech-companies-3').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
        $('#tech-companies-4').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
        $('#tech-companies-5').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
        $('#tech-companies-6').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
        $('#tech-companies-7').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );
        $('#tech-companies-8').DataTable( {
            dom: 'Blfrtip',
            "lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
            buttons: [
                 'excel',
            ]
        } );

        $('#tech-companies-9').DataTable( {
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