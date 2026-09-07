<header class="main-header">
    <div class="container">

      <!-- Logo -->
      <div class="logo">
        <a href="/"><img src="{{ asset('front2/assets/images/logo.svg')}}" alt="Cyera AI"></a>
      </div>

      <!-- Navigation -->
      <nav class="menu">
        <a href="/index">
          <span class="front">Home</span>
          <span class="back">Home</span>
        </a>
        <a href="/about">
          <span class="front">About</span>
          <span class="back">About</span>
        </a>

        <a href="/features">
          <span class="front">Features</span>
          <span class="back">Features</span>
        </a>

        <a href="/our-products">
          <span class="front">Our Products</span>
          <span class="back">Our Products</span>
        </a>

        <a href="/publications">
          <span class="front">Publications</span>
          <span class="back">Publications</span>
        </a>

        <!-- <a href="/documentation">
          <span class="front">Documentation</span>
          <span class="back">Documentation</span>
        </a> -->

        <a href="/help">
          <span class="front" data-key="nav.help">Help</span>
          <span class="back" data-key="nav.help">Help</span>
        </a>
      </nav>

      <!-- Header Buttons -->
      <div class="header-btns flex flex-row">
        <div class="mobile-menu">
          <div class="menu-wrapper">
            <div class="hamburger-menu"></div>
          </div>
        </div>
        @php
            $isGuest = auth()->guest();
            $dashboardUrl = $isGuest ? url('/login') : (session('user.licence')=='3' ? url('/Main/Dashboard') : (session('user.licence')=='2' ? url('/Main/Dashboard') : url('/User/Dashboard')));
            $buttonText = $isGuest ? 'Login' : 'Dashboard';
        @endphp
        <a href="{{ $dashboardUrl }}" target="_blank" class="btn btn-primary">
          <div class="text">
            <span class="front">{{ $buttonText }}</span>
            <span class="back">{{ $buttonText }}</span>
          </div>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
    <div class="mobile-menu-wrapper">
      <nav class="menu mb-8">
        <ul>
          <li><a href="/"><span class="front">Home</span><span class="back">Home</span></a></li>
          <li><a href="/about"><span class="front">About</span><span class="back">About</span></a></li>
          <li><a href="/features"><span class="front">Features</span><span class="back">Features</span></a></li>
          <li><a href="/our-products"><span class="front">Our Products</span><span class="back">Our Products</span></a></li>
          <li><a href="/publications"><span class="front">Publications</span><span class="back">Publications</span></a></li>
          <li><a href="/help"><span class="front" data-key="nav.help">Help</span><span class="back" data-key="nav.help">Help</span></a></li>
        </ul>
      </nav>
      <a href="{{ $dashboardUrl }}" target="_blank" class="btn btn-primary">
        <div class="text">
          <span class="front">{{ $buttonText }}</span>
          <span class="back">{{ $buttonText }}</span>
        </div>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </header>