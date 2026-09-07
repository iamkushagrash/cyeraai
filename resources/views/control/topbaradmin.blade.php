    <div class='page-topbar '>
        <div class='logo-area' style="background-size: 0;">
            <h2>&nbsp;Cyera AI</h2>
        </div>
        <div class='quick-area'>
            <div class='pull-left'>
                <ul class="info-menu left-links list-inline list-unstyled">
                    <li class="sidebar-toggle-wrap">
                        <a href="#" data-toggle="sidebar" class="sidebar_toggle">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                    
                    <li class="hidden-sm hidden-xs searchform">
                        <form action="#">
                            <div class="input-group">
                                <span class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </span>
                                <input type="text" class="form-control animated fadeIn" placeholder="Search & Enter">
                            </div>
                            <input type='submit' value="">
                        </form>
                    </li>
                </ul>
            </div>
            <div class='pull-right'>
                <ul class="info-menu right-links list-inline list-unstyled">
                    <li class="profile">
                        <a href="#" data-toggle="dropdown" class="toggle">
                            <img src="{{asset('assets/images/avatar/profile.jpg')}}" alt="user-image" class="img-circle img-inline">
                            <span>Admin <i class="fa fa-angle-down"></i></span>
                        </a>
                        <ul class="dropdown-menu profile animated fadeIn">
                            <li>
                                <a href="#">
                                    <i class="fa fa-wrench"></i> Settings
                                </a>
                            </li>
                            <!-- <li>
                                <a href="/Main/ActualDashboard">
                                    <i class="fa fa-user"></i> Profile
                                </a>
                            </li> -->
                            <li class="last">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fa fa-lock"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                 </form>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </div>

    </div>