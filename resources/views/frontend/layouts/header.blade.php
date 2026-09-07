<style>


</style>

<div data-w-id="3af1c84f-ff74-c014-cb3b-40446b73b1e3" 
   data-animation="default" 
   data-collapse="medium" 
   data-duration="400" 
   data-easing="ease" 
   data-easing2="ease" 
   role="banner" 
   class="navbar w-nav">

   <div class="nav-container _1430">
      <div class="navbar-container">

         <!-- Logo -->
         <a href="/" class="navbar-logo w-nav-brand" style="margin-left:10px;">
            <img src="{{ asset('main/assets/images/logo.png') }}" style="width:200px" loading="lazy" alt="" class="navbar-logo-image"/>
         </a>

         <!-- Navigation Menu -->
         <nav role="navigation" class="nav-menu w-nav-menu">
            <!-- Home -->
            <a href="/" class="nav-menu-link w-nav-link">Home</a>

            <!-- About -->
            <a href="/about" class="nav-menu-link w-nav-link">About</a>

            <!-- Features -->
            <a href="/features" class="nav-menu-link w-nav-link">Features</a>

            <!-- Projects -->
            <a href="/projects" class="nav-menu-link w-nav-link">Projects</a>
             <a href="/documentation" class="nav-menu-link w-nav-link">Documentation</a>
              <a href="/help" class="nav-menu-link w-nav-link">Help</a>

          

            <!-- ✅ Mobile Login/Dashboard Button -->
            @php
               $isGuest = auth()->guest();
               $dashboardUrl = $isGuest ? url('/login') : (session('user.licence')=='3' ? url('/Main/Dashboard') : (session('user.licence')=='2' ? url('/Manage/Dashboard') : url('/User/Dashboard')));
               $buttonText = $isGuest ? 'Login' : 'Dashboard';
            @endphp
            
            <form action="{{ $dashboardUrl }}" method="get">
               <button type="submit" class="primary-button mobile-login-btn">{{ $buttonText }}</button>
            </form>
         </nav>

         <!-- Desktop Login/Dashboard Button -->
         <div class="navbar-button-wrapper">
            <div class="primary-button-wrapper desktop">
               <a href="/login" data-w-id="3af1c84f-ff74-c014-cb3b-40446b73b1f6" class="primary-button w-inline-block">
                  <div class="primary-button-border-wrap">
                     <div class="primary-button-wrap">
                        <form action="{{ $dashboardUrl }}" method="get" style="display:inline;">
                           <button type="submit" style="all: unset; cursor: pointer;">
                              <div class="primary-button">{{ $buttonText }}</div>
                              <div class="primary-button-hover-bg"></div>
                           </button>
                        </form>
                     </div>
                  </div>
                  <div class="line-wrap">
                     <div class="primary-line-wrap _1">
                        <div class="line _1"></div>
                        <div class="line _2"></div>
                     </div>
                     <div class="primary-line-wrap _2">
                        <div class="line _3"></div>
                        <div class="line _4"></div>
                     </div>
                     <div class="primary-line-wrap _3">
                        <div class="line _5"></div>
                        <div class="line _6"></div>
                     </div>
                     <div class="primary-line-wrap _4">
                        <div class="line _7"></div>
                        <div class="line _8"></div>
                     </div>
                  </div>
               </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="menu-button w-nav-button">
               <div data-is-ix2-target="1" class="hamburger-menu-icon"
                  data-w-id="b58e0106-6be1-80f9-2e9e-29cc3a30b8aa"
                  data-animation-type="lottie"
                  data-src="https://cdn.prod.website-files.com/64d728cd40ba078bc56626e6/64d728cd40ba078bc566288f_Hamburger%20menu.lottie"
                  data-loop="0"
                  data-direction="1"
                  data-autoplay="0"
                  data-renderer="svg"
                  data-duration="0"
                  data-ix2-initial-state="0"></div>
            </div>
         </div>

      </div>
   </div>
</div>
