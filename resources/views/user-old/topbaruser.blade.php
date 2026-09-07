		<div id="header" class="app-header">
			
			<!-- BEGIN desktop-toggler -->
			<div class="desktop-toggler">
				<button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed" data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
					<span class="bar"></span>
					<span class="bar"></span>
					<span class="bar"></span>
				</button>
			</div>
			<!-- BEGIN desktop-toggler -->
			
			<!-- BEGIN mobile-toggler -->
			<div class="mobile-toggler">
				<button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled" data-toggle-target=".app">
					<span class="bar"></span>
					<span class="bar"></span>
					<span class="bar"></span>
				</button>
			</div>
			<!-- END mobile-toggler -->
			
			
			
			<!-- BEGIN brand -->
		<div class="brand" style="display:flex; align-items:center; flex:1;">
  <a href="/User/Dashboard" class="brand-logo" style="display:flex; align-items:center; text-decoration:none;">
    <span class="brand-img" style="margin-right:6px;">
      <img src="{{asset('main/assets/images/fav.png')}}" 
           alt="Cyera AI Logo" 
           style="height:19px; width:auto; margin-left:14px; display:block;">
    </span>
    <span class="brand-text" style="font-size:16px; white-space:nowrap;">Cyera AI</span>
  </a>
</div>


			<!-- END brand -->
			
			<!-- BEGIN menu -->
			<div class="menu">
				<div class="menu-item dropdown">
					<a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app" class="menu-link">
						<!-- <div class="menu-icon"><i class="bi bi-search nav-icon"></i></div> -->
					</a>
				</div>
				
				<div class="menu-item dropdown dropdown-mobile-full">
					<a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
						<div class="menu-img online">
							<img src="{{asset('main/assets/images/coin.png') }}" alt="Profile" height="60">
						</div>
						<div class="menu-text d-sm-block d-none w-170px">{{ Session::get('user.uuid') }}</div>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-lg-3 fs-11px mt-1">
						<a class="dropdown-item d-flex align-items-center" href="/User/EditProfile">Profile <i class="bi bi-person-circle ms-auto text-theme fs-16px my-n1"></i></a>
						<a class="dropdown-item d-flex align-items-center" href="/User/ChangePassword">Change Password <i class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i></a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">LogOut <i class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
					</div>
				</div>
			</div>
			<!-- END menu -->
			
			<!-- BEGIN menu-search -->
			<!-- <form class="menu-search" method="POST" name="header_search_form">
				<div class="menu-search-container">
					<div class="menu-search-icon"><i class="bi bi-search"></i></div>
					<div class="menu-search-input">
						<input type="text" class="form-control form-control-lg" placeholder="Search menu...">
					</div>
					<div class="menu-search-icon">
						<a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i class="bi bi-x-lg"></i></a>
					</div>
				</div>
			</form> -->
			<!-- END menu-search -->
		</div>