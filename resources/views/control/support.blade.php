
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

    <link href="{{asset('assets/css/minimal.css')}}" rel="stylesheet" type="text/css" media="screen" />

    <!-- HEADER SCRIPTS INCLUDED ON THIS PAGE - END -->
    
    <link rel="stylesheet" href="{{asset('assets/datatable/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/datatable/buttons.dataTables.min.css')}}">

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

                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- MAIN CONTENT AREA STARTS -->

                <div class="col-lg-12">
                    <section class="box nobox ">
                        <div class="content-body">
                            <div class="row">

                                <!-- <div class="col-md-3 col-sm-4 col-xs-12">

                                    <a class="btn btn-primary btn-block" href='#'>Compose</a>

                                </div> -->

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="mail_content">

                                        <div class="row" style="min-height: 500px;">
                                            <div class="col-xs-12">

                                                <h3 class="mail_head">Manage Tickets <sup></sup></h3>
                                                <h3><br></h3>
                                                <!-- <i class='fa fa-refresh icon-primary icon-xs icon-accent mail_head_icon'></i>
                                                <i class='fa fa-search icon-primary icon-xs icon-accent mail_head_icon'></i>
                                                <i class='fa fa-cog icon-primary icon-xs icon-accent mail_head_icon pull-right'></i> -->

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
                                            <div class="clearfix"></div>

                                            <div class="mail_list col-xs-12 table-responsive">
                                                <table id="tech-companies-1" class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>User Id</th>
                                                            <th>Subject</th>
                                                            <th>Title</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i=1; ?>
                                                        @foreach($ticket as $ticket)
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$ticket->userid}}</td>
                                                            <td data-label="Name">
                                                                <span class="font-weight-bold">
                                                                    {{$ticket->sub}}
                                                                </span>
                                                            </td>
                                                            <td>{{$ticket->title}}</td>
                                                            <td data-label="Send Status">
                                                                <span class="badge {{$ticket->statusclass}}">{{$ticket->status}}</span>
                                                            </td>
                                                            <td data-label="Action">
                                                                <a href="/Main/TicketView/{{str_replace(' ','-',$ticket->title)}}/{{ $ticket->subid}}" class="label label-primary" data-toggle="tooltip">
                                                                    <i class="fa fa-edit text--shadow"></i>View Ticket
                                                                </a>
                                                            </td>
                                                            <td>{{$ticket->created_at}}</td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                        @endforeach
                                                    </tbody>
                                                                                                     
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </div>

                <div class="clearfix"></div>

                <div class="modal fade col-xs-12" id="cmpltadminModal-1" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="/User/CreateTicket" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" style="color:#000;">Create Ticket</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">Subject </label>
                                        <select id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror">
                                          <option value="">Select a Topic</option>
                                          <option value="Profile Edit"> Profile Edit </option>
                                          <option value="Deposit"> Deposit </option>
                                          <option value="Withdraw Related"> Withdraw Related </option>
                                        </select>
                                          @error('subject')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">Title </label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Title" required="">
                                          @error('title')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">Message </label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" name="message" required=""></textarea>
                                          @error('message')
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

    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->

    <script src="{{asset('assets/js/icheck.min.js')}}"></script>
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

    <!-- CORE TEMPLATE JS - START -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <!-- END CORE TEMPLATE JS - END -->

</body>

</html>