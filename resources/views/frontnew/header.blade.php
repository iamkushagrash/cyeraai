 <header class="main-header">
    <div class="container">

      <!-- Logo -->
      <div class="logo">
        <a href="/"><img src="{{ asset('front/assets/images/logo.svg')}}" alt="Cyera AI"></a>
      </div>

      <nav class="menu">
        <a href="/" data-title="Home"><span>Home</span></a>
        <a href="/about" data-title="About"><span>About</span></a>
        <a href="/features" data-title="Features"><span>Features</span></a>
        <a href="/our-products" data-title="Our Products"><span>Our Products</span></a>
        <a href="/help" data-title="Help"><span>Help</span></a>
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
        <a href="{{ $dashboardUrl }}" target="_blank" data-title="{{ $buttonText }}" class="btn btn-primary">
          <span>{{ $buttonText }}</span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
    <div class="mobile-menu-wrapper">
      <nav class="menu mb-8">
        <ul>
          <li><a href="/" data-title="Home"><span>Home</span></a></li>
          <li><a href="/about" data-title="About"><span>About</span></a></li>
          <li><a href="/features" data-title="Features"><span>Features</span></a></li>
          <li><a href="/our-products" data-title="Our Products"><span>Our Products</span></a></li>
          <li><a href="/help" data-title="Help"><span>Help</span></a></li>
        </ul>
      </nav>
      <a href="{{ $dashboardUrl }}" target="_blank" data-title="{{ $buttonText }}" class="btn btn-primary">
        <span>{{ $buttonText }}</span>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </header>