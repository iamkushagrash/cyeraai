<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
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
    <link href="{{asset('assets/css/jquery-jvectormap-2.0.1.css')}}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{asset('assets/css/morris.css')}}" rel="stylesheet" type="text/css" media="screen" />
    <!-- HEADER SCRIPTS INCLUDED ON THIS PAGE - END -->

    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
    <!-- CORE CSS TEMPLATE - END -->
    
    <style>
        .total-section {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
        }
        .total-header {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>

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
                            <h1 class="title">Dashboard</h1>
                        </div>
                    </div>
                </div>
                
                <!-- Date Filter Form -->
                <div class="col-xs-12">
                    <div class="box">
                        <div class="content-body">
                            <form method="GET" action="/Main/Dashboard" class="form-inline">
                                <div class="form-group">
                                    <label for="filter">Filter By: </label>
                                    <select name="filter" id="filter" class="form-control" onchange="toggleCustomDates()">
                                        <option value="today" {{ $data['current_filter'] == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="weekly" {{ $data['current_filter'] == 'weekly' ? 'selected' : '' }}>This Week ({{ $data['week_start'] }} to {{ $data['week_end'] }})</option>
                                        <option value="monthly" {{ $data['current_filter'] == 'monthly' ? 'selected' : '' }}>This Month ({{ $data['month_start'] }} to {{ $data['month_end'] }})</option>
                                        <option value="custom" {{ $data['current_filter'] == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                                    </select>
                                </div>
                                
                                <div id="custom-dates" class="form-group" style="{{ $data['current_filter'] == 'custom' ? '' : 'display:none;' }}">
                                    <label for="start_date">From: </label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $data['start_date'] ?? $data['today'] }}">
                                    
                                    <label for="end_date">To: </label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $data['end_date'] ?? $data['today'] }}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Date Filter Form -->
                
                <!-- Filtered Data Section -->
                <div class="col-lg-12">
                    <section class="box nobox marginBottom0">
                        <div class="content-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Filtered Data ({{ $data['current_filter'] == 'today' ? 'Today' : ($data['current_filter'] == 'weekly' ? 'This Week' : ($data['current_filter'] == 'monthly' ? 'This Month' : 'Custom Range')) }})</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/AllMembers" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5">{{$data['totalmember']->count()}}</h3>
                                            <span>Total Members</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/PaidMembers" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5">{{$data['totalpaid']->count()}}</h3>
                                            <span>Paid Members</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/UnpaidMembers" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5">{{$data['totalunpaid']->count()}}</h3>
                                            <span>Unpaid Members</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/AdminUSDTReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['walletrevenue']->totalrevenue,2)}} $</h3>
                                            <span>Wallet Deposit</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/UserUSDTDeposit" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round(($data['totalrevenue']->totalrevenue-$data['walletrevenue']->totalrevenue),2)}} $</h3>
                                            <span>Gateway Deposit</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/UserPackageHistory" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['basicrevenue']->basicrevenue,2)}} $</h3>
                                            <span>Wallet Topup</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/AdminSystemTopupReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['zeropin']->amountusdt,2)}} $</h3>
                                            <span>Gold Pin</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/AdminROITopupReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['silverpin']->amountusdt,2)}} $</h3>
                                            <span>Silver Pin</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/UserLoanReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round($data['totalloan']->totalloan,2)}} $</h3>
                                            <span>Total Loan</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/UserLoanReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totalloan']->remainingloan,2)}} $</h3>
                                            <span>Remaining</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round($data['unpaidwithdraw'],2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['unpaidwithdraw']*$data['price']),2)}} $</span><br> -->
                                            <span>Ready to Release</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/WithdrawRequests" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['pendingwithdraw']->pendingwithdraw,2)}} $</h3>
                                            <span>Pending Withdrawal</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/WithdrawHistory" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totalwithdraw']->totalwithdraw,2)}} $</h3>
                                            <span>Total Withdrawal</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/DirectIncomeReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totalbonus']->totalbonus,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['totalbonus']->totalbonus*$data['price']),2)}} $</span><br> -->
                                            <span>Today Direct</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/StakingIncomeReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totalroi']->totalroi,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['totalroi']->totalroi*$data['price']),2)}} $</span><br> -->
                                            <span>Today ROI</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/StakingReferralIncome" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round($data['totallevel']->totallevel,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['totallevel']->totallevel*$data['price']),2)}} $</span><br> -->
                                            <span>Today Referral</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/TeamDevelopmentIncome" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totaldevelopment']->totallevel,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['totaldevelopment']->totallevel*$data['price']),2)}} $</span><br> -->
                                            <span>Today Development</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/ClubIncome" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totalclub']->totalclub,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['totalclub']->totalclub*$data['price']),2)}} $</span><br> -->
                                            <span>Today Club</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/LifetimeReward" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['totalachievement']->totallifetime,2)}} $</h3>
                                            
                                            <span>Today Achievement</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
                <!-- End Filtered Data Section -->
                
                <!-- Total Data Section (Always shows all-time data) -->
                <div class="col-lg-12">
                    <section class="box nobox marginBottom0">
                        <div class="content-body total-section">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="total-header">All Time Totals (Without Date Filter)</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/AllMembers" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5">{{$data['total_member_all_time']->count()}}</h3>
                                            <span>Total Members (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/PaidMembers" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5">{{$data['total_paid_all_time']->count()}}</h3>
                                            <span>Paid Members (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/UnpaidMembers" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5">{{$data['total_unpaid_all_time']->count()}}</h3>
                                            <span>Unpaid Members (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/AdminUSDTReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['wallet_revenue_all_time']->totalrevenue,2)}} $</h3>
                                            <span>Wallet Deposit (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/UserUSDTDeposit" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round(($data['total_revenue_all_time']->totalrevenue-$data['wallet_revenue_all_time']->totalrevenue),2)}} $</h3>
                                            <span>Gateway Deposit (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/UserPackageHistory" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['basic_revenue_all_time']->basicrevenue,2)}} $</h3>
                                            <span>Wallet Topup (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/AdminSystemTopupReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['zero_pin_all_time']->amountusdt,2)}} $</h3>
                                            <span>Gold Pin (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/AdminROITopupReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['silver_pin_all_time']->amountusdt,2)}} $</h3>
                                            <span>Silver Pin (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/UserLoanReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round($data['total_loan_all_time']->totalloan,2)}} $</h3>
                                            <span>Total Loan (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/UserLoanReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_loan_all_time']->remainingloan,2)}} $</h3>
                                            <span>Remaining (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round($data['unpaid_withdraw_all_time'],2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['unpaid_withdraw_all_time']*$data['price']),2)}} $</span><br> -->
                                            <span>Ready to Release (All Time)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/WithdrawRequests" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['pending_withdraw_all_time']->pendingwithdraw,2)}} $</h3>
                                            <span>Pending Withdrawal (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                    <a href="/Main/WithdrawHistory" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_withdraw_all_time']->totalwithdraw,2)}} $</h3>
                                            <span>Total Withdrawal (All Time)</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/DirectIncomeReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_bonus_all_time']->totalbonus,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['total_bonus_all_time']->totalbonus*$data['price']),2)}} $</span><br> -->
                                            <span>Total Direct Bonus</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/StakingIncomeReport" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_roi_all_time']->totalroi,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['total_roi_all_time']->totalroi*$data['price']),2)}} $</span><br> -->
                                            <span>Total ROI</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/StakingReferralIncome" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-white mt-10'></i>
                                        <div class="stats">
                                            <h3 class="color-white mb-5"> {{round($data['total_level_all_time']->totallevel,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['total_level_all_time']->totallevel*$data['price']),2)}} $</span><br> -->
                                            <span>Total Referral</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/TeamDevelopmentIncome" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_development_all_time']->totallevel,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['total_development_all_time']->totallevel*$data['price']),2)}} $</span><br> -->
                                            <span>Total Development</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/ClubIncome" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_club_all_time']->totalclub,2)}} $</h3>
                                            <!-- <span style="color:#fff;">~ {{round(($data['total_club_all_time']->totalclub*$data['price']),2)}} $</span><br> -->
                                            <span>Total Club</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <a href="/Main/LifetimeReward" style="text-decoration: none;">
                                    <div class="r4_counter db_box">
                                        <div class="icon-after cc"></div>
                                        <i class='pull-left cc icon-md icon-primary mt-10'></i>
                                        <div class="stats">
                                            <h3 class="mb-5"> {{round($data['total_achievement_all_time']->totallifetime,2)}} $</h3>
                                            
                                            <span>Total Achievement</span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>


                        </div>
                    </section>
                </div>
                <!-- End Total Data Section -->
            </div>
        </section>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->

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
    <script src="{{asset('assets/js/echarts-custom-for-dashboard.js')}}"></script>
    <script src="{{asset('assets/js/jquery.flot.js')}}"></script>
    <script src="{{asset('assets/js/jquery.flot.time.js')}}"></script>
    <script src="{{asset('assets/js/chart-flot.js')}}"></script>
    <script src="{{asset('assets/js/raphael-min.js')}}"></script>
    <script src="{{asset('assets/js/morris.min.js')}}"></script>
    <script src="{{asset('assets/js/chart-morris.js')}}"></script>
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

    <!-- CORE TEMPLATE JS - START -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <!-- END CORE TEMPLATE JS - END -->

    <script>
        function toggleCustomDates() {
            var filter = document.getElementById('filter').value;
            var customDates = document.getElementById('custom-dates');
            
            if (filter === 'custom') {
                customDates.style.display = 'block';
            } else {
                customDates.style.display = 'none';
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomDates();
        });
    </script>
</body>
</html>