@extends('frontend.layouts.app')

{{-- ✅ Page Title --}}
@section('title', 'Cyera AI - Decentralized Wealth Platform')

{{-- ✅ SEO Meta Tags --}}
@section('meta_description', 'Cyera AI is a decentralized finance and blockchain-based wealth management platform.')
@section('meta_keywords', 'Cyera AI, blockchain, crypto, DeFi, investments, decentralized finance, wealth management, trading')

{{-- ✅ Open Graph (for Facebook/LinkedIn) --}}
@section('og_title', 'Cyera AI - Decentralized Wealth Platform')
@section('og_description', 'Invest, trade, and grow wealth securely on Cyera AI - a decentralized blockchain-powered platform.')

{{-- ✅ Twitter Card --}}
@section('twitter_title', 'Cyera AI - Decentralized Wealth Platform')
@section('twitter_description', 'Invest, trade, and grow wealth securely on Cyera AI.')

{{-- ✅ Page Content --}}
@section('content')

 
         <section data-w-id="ff16e7a9-f7e3-77c2-015f-a1152daaa887" class="section banner">
  <div class="banner-container">
    <div class="banner-content">
      <div data-w-id="6e58a63e-55a6-96bc-8457-502e6779cd81" class="banner-typography">
        <div class="hero-banner-subtitle-wrapper">
          <img src="{{ asset('main/assets/images/herobanner1.webp') }}" loading="lazy" alt="" class="hero-banner-subtitle-img left" />
          <div class="hero-banner-subtitle-number">№1</div>
          <div style="font-weight:bold;color:white"><span>Blockchain</span></div>
          <img src="{{ asset('main/assets/images/herobanner2.webp') }}" loading="lazy" alt="" class="hero-banner-subtitle-img right" />
        </div>
        <div class="banner-title-description">
  <h1 class="banner-title">
    Cyera AI - Shaping the Future of Blockchain
  </h1>
  <p class="banner-description-text">
    An ecosystem of trust, transparency, and growth — unlocking decentralized finance, communication, storage, gaming, and more for the digital freedom of tomorrow.
  </p>
</div>

       <div class="banner-button-wrapper">
  <!-- Explore Cyera AI Button -->
  <div class="primary-button-wrapper">
    <a href="https://mwtscan.com" class="inner-button w-inline-block">
      <div class="primary-button-border-wrap">
        <div class="inner-button-wrap">
          <div class="inner-button-text-wrap">
            <div class="inner-button-text">Explore Cyera AI</div>
            <div class="inner-button-hover-text">Explore Cyera AI</div>
          </div>
          <div class="inner-button-star-wrap">
            <img loading="lazy" src="{{ asset('main/assets/images/star1.svg') }}" alt="" class="inner-button-star _1" />
            <img loading="lazy" src="{{ asset('main/assets/images/star2.svg') }}" alt="" class="inner-button-star _2" />
            <img loading="lazy" src="{{ asset('main/assets/images/star3.svg') }}" alt="" class="inner-button-star _3" />
          </div>
          <div class="inner-button-hover-bg"></div>
        </div>
      </div>
      <div class="line-wrap-inner">
        <div class="inner-line-wrap _1">
          <div class="line"></div>
          <div class="line _2"></div>
        </div>
        <div class="inner-line-wrap _2">
          <div class="line _3"></div>
          <div class="line _4"></div>
        </div>
        <div class="inner-line-wrap _3">
          <div class="line _5"></div>
          <div class="line _6"></div>
        </div>
        <div class="inner-line-wrap _4">
          <div class="line _7"></div>
          <div class="line _8"></div>
        </div>
      </div>
    </a>
  </div>

  <!-- Meta Console Button -->
  <div class="primary-button-wrapper">
    <a href="https://console.cyera.ai" class="inner-button w-inline-block">
      <div class="primary-button-border-wrap">
        <div class="inner-button-wrap">
          <div class="inner-button-text-wrap">
            <div class="inner-button-text">Meta Console</div>
            <div class="inner-button-hover-text">Meta Console</div>
          </div>
          <div class="inner-button-star-wrap">
            <img loading="lazy" src="{{ asset('main/assets/images/star1.svg') }}" alt="" class="inner-button-star _1" />
            <img loading="lazy" src="{{ asset('main/assets/images/star2.svg') }}" alt="" class="inner-button-star _2" />
            <img loading="lazy" src="{{ asset('main/assets/images/star3.svg') }}" alt="" class="inner-button-star _3" />
          </div>
          <div class="inner-button-hover-bg"></div>
        </div>
      </div>
      <div class="line-wrap-inner">
        <div class="inner-line-wrap _1">
          <div class="line"></div>
          <div class="line _2"></div>
        </div>
        <div class="inner-line-wrap _2">
          <div class="line _3"></div>
          <div class="line _4"></div>
        </div>
        <div class="inner-line-wrap _3">
          <div class="line _5"></div>
          <div class="line _6"></div>
        </div>
        <div class="inner-line-wrap _4">
          <div class="line _7"></div>
          <div class="line _8"></div>
        </div>
      </div>
    </a>
  </div>
</div>

      </div>

      <!-- Globe + Tags -->
      <div class="banner-world-element-wrapper">
        <img src="{{ asset('main/assets/images/coin1.png') }}" loading="lazy"
          style="transform:translate3d(0, 0, 0) scale3d(0.5, 0.5, 1);opacity:0"
          sizes="(max-width: 635px) 100vw, 635px" alt=""
          srcset="{{ asset('main/assets/images/coin1.png') }} 500w, {{ asset('main/assets/images/coin1.png') }} 635w"
          class="banner-world-element-image" />
        <div style="opacity:0" class="banner-world-element-tag-content">
          <div class="banner-world-element-tag-wrapper">
           
           
          </div>
        </div>
      </div>

      <!-- Shapes -->
      <img 
    src="{{ asset('main/assets/images/bannershape1.webp') }}" 
    loading="lazy" 
    style="opacity:0"
    sizes="(max-width: 1879px) 100vw, 1879px" 
    alt="Banner Shape"
    srcset="
        {{ asset('main/assets/images/bannerwidth500.webp') }} 500w, 
        {{ asset('main/assets/images/bannershape2.webp') }} 800w, 
        {{ asset('main/assets/images/bannershape2.webp') }} 1080w, 
        {{ asset('main/assets/images/bannershape2.webp') }} 1600w, 
        {{ asset('main/assets/images/bannershape2.webp') }} 1879w
    "
    class="banner-shape"
/>

      <img src="{{ asset('main/assets/images/plusicon.svg') }}" loading="lazy" alt="" class="banner-pluse-icon top-left" />
      <img src="{{ asset('main/assets/images/pulseicon.svg') }}" loading="lazy" alt="" class="banner-pluse-icon top-right" />
      <img src="{{ asset('main/assets/images/pulsesvg.svg') }}" loading="lazy" alt="" class="banner-pluse-icon bottom-left" />
      <img src="{{ asset('main/assets/images/pulse.svg') }}" loading="lazy" alt="" class="banner-pluse-icon bottom-right" />
    </div>
  </div>
</section>

        <section class="section headline-section">
         
  <div class="headline-content">
    <div class="headline-ticker">
      <div class="headline-ticker-single">
        <div class="headline-ticker-inner-ticker">
          <h2 class="footer-ticker-name">Cyera AI — Shaping the Future of Blockchain</h2>
          <h2 class="footer-ticker-name">Decentralized. Transparent. Trusted.</h2>
        </div>
        <div class="headline-ticker-inner-ticker">
          <h2 class="footer-ticker-name">Powering Finance, Communication, and Digital Freedom</h2>
          <h2 class="footer-ticker-name">One Ecosystem. Seven dApps. Infinite Possibilities.</h2>
        </div>
        <div class="headline-ticker-inner-ticker">
          <h2 class="footer-ticker-name">Cyera AI — Where Innovation Meets Trust</h2>
          <h2 class="footer-ticker-name">Cyera AI — Building the Decentralized Future</h2>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section historical-numbers">
  <div class="historical-numbers-content">
    <div class="historical-ticker top">
      <div class="historical-ticker-single left">
        <div class="headline-ticker-inner-ticker left">
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">10K+</div>
            </div>
            <div class="historical-ticker-title">Wallet Installs</div>
          </div>
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">50K+</div>
            </div>
            <div class="historical-ticker-title">Secure Transactions</div>
          </div>
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">5K+</div>
            </div>
            <div class="historical-ticker-title">Active Users</div>
          </div>
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">20+</div>
            </div>
            <div class="historical-ticker-title">Global Partners</div>
          </div>
        </div>
      </div>
    </div>

    <div class="historical-ticker bottom">
      <div class="historical-ticker-single right">
        <div class="headline-ticker-inner-ticker right">
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">10K+</div>
            </div>
            <div class="historical-ticker-title">Wallet Installs</div>
          </div>
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">50K+</div>
            </div>
            <div class="historical-ticker-title">Secure Transactions</div>
          </div>
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">5K+</div>
            </div>
            <div class="historical-ticker-title">Active Users</div>
          </div>
          <div class="historical-ticker-card">
            <div class="historical-ticker-number-wrap">
              <div class="historical-ticker-number">20+</div>
            </div>
            <div class="historical-ticker-title">Global Partners</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <section class="section community-spotlight">
            <div data-w-id="5b30ccf4-9c4d-b796-8be3-2831dab6d2ae" class="community-spotlight-content">
               <div class="community-spotlight-typography">
                  <div class="community-spotlight-icon-wrapper"><img src="{{ asset('main/assets/images/45.webp') }}" loading="lazy" style="-webkit-transform:translate3d(0, 0, 0) scale3d(0.6, 0.6, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(0.6, 0.6, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(0.6, 0.6, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(0.6, 0.6, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-icon"/></div>
                  <div style="opacity:0;-webkit-transform:translate3d(0, 0, 0) scale3d(0.8, 0.8, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(0.8, 0.8, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(0.8, 0.8, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(0.8, 0.8, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)" class="community-spotlight-title-description">
                     <h2 class="community-spotlight-title">Empowering the Future with Cyera AI dApps</h2>
                     <p class="community-spotlight-description">Discover our decentralized ecosystem — built for trust, transparency, and growth</p>
                  </div>
                  <img src="{{ asset('main/assets/images/46.webp') }}" loading="lazy" style="opacity:0" sizes="(max-width: 536px) 100vw, 536px" alt="" srcset="{{ asset('main/assets/images/47.png') }} 500w,{{ asset('main/assets/images/449.webp') }} 536w" class="community-spotlight-shape"/>
               </div>
               <div data-w-id="f5204fb9-5501-c729-be95-6f1e3f47b79b" class="community-spotlight-image-wrap">
                  <img src="{{ asset('main/assets/images/49.webp') }}" loading="lazy" sizes="(max-width: 1437px) 100vw, 1437px" srcset="assets/images/187.webp 500w, {{ asset('main/assets/images/188.webp') }} 800w, {{asset('main/assets/images/189.webp') }} 1080w, {{ asset('main/assets/images/190.webp') }} 1437w" alt="" class="community-spotlight-image-bg-shape"/>
                  <div class="community-spotlight-top-images">
                    <img src="{{ asset('main/assets/images/products/1.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -430px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -430px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -430px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -430px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _1"/>
                    <img src="{{ asset('main/assets/images/products/2.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _2"/><img src="{{ asset('main/assets/images/products/3.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _3"/><img src="{{ asset('main/assets/images/products/4.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _4"/><img src="{{ asset('main/assets/images/products/5.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _5"/><img src="{{ asset('main/assets/images/products/6.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _6"/><img src="{{ asset('main/assets/images/products/1.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -420px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _7"/></div>
                  <div class="community-spotlight-bottom-images"><img src="{{ asset('main/assets/images/products/2.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _8"/><img src="{{ asset('main/assets/images/products/3.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _9"/><img src="{{ asset('main/assets/images/products/4.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _10"/><img src="{{ asset('main/assets/images/products/5.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _11"/><img src="{{ asset('main/assets/images/products/6.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _12"/><img src="{{ asset('main/assets/images/products/1.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _13"/><img src="{{ asset('main/assets/images/products/2.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _14"/><img src="{{ asset('main/assets/images/products/3.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _15"/><img src="{{ asset('main/assets/images/products/4.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _16"/><img src="{{ asset('main/assets/images/products/5.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _17"/><img src="{{ asset('main/assets/images/products/6.png') }}" loading="lazy" style="-webkit-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, -400px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);opacity:0" alt="" class="community-spotlight-image _18"/></div>
               </div>
               <div class="community-spotlight-button-wrapper">
                  <div class="primary-button-wrapper">
                     <a href="/contact-us" class="inner-button w-inline-block">
                        <div class="primary-button-border-wrap">
                           <div class="inner-button-wrap">
                              <div class="inner-button-text-wrap">
                                 <div class="inner-button-text">Join with community</div>
                                 <div class="inner-button-hover-text">Join with community</div>
                              </div>
                              <div class="inner-button-star-wrap"><img loading="lazy" src="{{ asset('main/assets/images/175.svg') }}" alt="" class="inner-button-star _1"/><img loading="lazy" src="{{ asset('main/assets/images/174.svg') }}" alt="" class="inner-button-star _2"/><img loading="lazy" src="{{ asset('main/assets/images/173.svg') }}" alt="" class="inner-button-star _3"/></div>
                              <div class="inner-button-hover-bg"></div>
                           </div>
                        </div>
                        <div class="line-wrap-inner">
                           <div class="inner-line-wrap _1">
                              <div class="line"></div>
                              <div class="line _2"></div>
                           </div>
                           <div class="inner-line-wrap _2">
                              <div class="line _3"></div>
                              <div class="line _4"></div>
                           </div>
                           <div class="inner-line-wrap _3">
                              <div class="line _5"></div>
                              <div class="line _6"></div>
                           </div>
                           <div class="inner-line-wrap _4">
                              <div class="line _7"></div>
                              <div class="line _8"></div>
                           </div>
                        </div>
                     </a>
                  </div>
               </div>
            </div>
         </section>
        
         
        
        <section class="section solving">

  <div class="container">
 
    <div class="solving-section-title-wrapper">
      <div class="section-subtitle-wrapper">
        <div class="section-subtitle">[ technology ]</div>
      </div>
      <h2 class="solving-section-title">Technologies Powering the Cyera AI Ecosystem</h2>
    </div>

    <div class="solving-content">
      <div class="w-layout-grid solving-grid">

        <div class="solving-grid-single _1st">
          <div class="solving-card one">
            <div class="solving-card-icon-wrapper"><img src="{{ asset('main/assets/images/solvingcard.svg') }}" alt="" class="solving-card-icon"/></div>
            <div class="solving-card-title-description">
              <h4 class="solving-card-title">Decentralized Finance (DeFi)</h4>
              <p class="solving-card-description-text">Enable seamless, borderless transactions and lending without intermediaries.</p>
            </div>
          </div>

          <div class="solving-card four">
            <div class="solving-card-icon-wrapper"><img src="{{ asset('main/assets/images/icon12.svg') }}" alt="" class="solving-card-icon"/></div>
            <div class="solving-card-title-description">
              <h4 class="solving-card-title">Decentralized Storage</h4>
              <p class="solving-card-description-text">On-chain encrypted storage for secure, private, and globally accessible data.</p>
            </div>
          </div>
        </div>

        <div class="solving-grid-single _2nd">
          <div class="solving-card two">
            <div class="solving-card-icon-wrapper"><img src="{{ asset('main/assets/images/solvingcard1.svg') }}" alt="" class="solving-card-icon"/></div>
            <div class="solving-card-title-description">
              <h4 class="solving-card-title">Blockchain Security</h4>
              <p class="solving-card-description-text">Advanced cryptographic protection ensures integrity and safeguards against fraud.</p>
            </div>
          </div>

          <div class="solving-card five">
            <div class="solving-card-icon-wrapper"><img src="{{ asset('main/assets/images/icon5.svg') }}" alt="" class="solving-card-icon"/></div>
            <div class="solving-card-title-description">
              <h4 class="solving-card-title">Smart Contracts</h4>
              <p class="solving-card-description-text">Automated, trustless agreements powering financial services, gaming, and governance.</p>
            </div>
          </div>
        </div>

        <div class="solving-grid-single _3rd">
          <div class="solving-card three">
            <div class="solving-card-icon-wrapper"><img src="{{ asset('main/assets/images/icon8.svg') }}" alt="" class="solving-card-icon"/></div>
            <div class="solving-card-title-description">
              <h4 class="solving-card-title">DAO Governance</h4>
              <p class="solving-card-description-text">Fair decision-making through decentralized autonomous organizations (DAO).</p>
            </div>
          </div>

          <div class="solving-card six">
            <div class="solving-card-icon-wrapper"><img src="{{ asset('main/assets/images/icon11.svg') }}" alt="" class="solving-card-icon"/></div>
            <div class="solving-card-title-description">
              <h4 class="solving-card-title">Cross-Chain Interoperability</h4>
              <p class="solving-card-description-text">Seamlessly bridge assets and data across multiple blockchain networks.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <img src="{{ asset('main/assets/images/feature.webp') }}" loading="lazy" sizes="(max-width: 1440px) 100vw, 1440px" srcset="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67f79fb270d82a04a3eb763c_Solving%20Image-p-500.webp 500w, https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67f79fb270d82a04a3eb763c_Solving%20Image-p-800.webp 800w, https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67f79fb270d82a04a3eb763c_Solving%20Image-p-1080.webp 1080w, https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67f79fb270d82a04a3eb763c_Solving%20Image.webp 1440w" alt="" class="solving-image">
</section>

       <section class="section features">
  <div class="container">
    <div class="features-content">

      <!-- Integrations -->
      <div class="features-flex">
        <div class="features-typography-card top-border-none">
          <div class="features-typography-card-single">
            <div class="features-subtitle-wrapper">
              <div class="features-subtitle">[ Integrations ]</div>
            </div>
            <div class="features-title-description">
              <h2 class="features-title">Seamless Integration Across Web3 Ecosystem</h2>
              <p class="features-description-text">
                Cyera AI connects with major blockchain networks, wallets, and protocols to deliver an
                interoperable experience for finance, gaming, storage, and communication.
              </p>
            </div>
            <div class="features-button-wrapper">
              <div class="primary-button-wrapper">
                <a href="/register" class="inner-button w-inline-block">
                  <div class="primary-button-border-wrap">
                    <div class="inner-button-wrap">
                      <div class="inner-button-text-wrap">
                        <div class="inner-button-text">Get started</div>
                        <div class="inner-button-hover-text">Get started</div>
                      </div>
                      <div class="inner-button-star-wrap">
                        <img src="{{ asset('main/assets/images/icon20.svg') }}" alt="" class="inner-button-star _1"/>
                        <img src="{{ asset('main/assets/images/icon21.svg') }}" alt="" class="inner-button-star _2"/>
                        <img src="{{ asset('main/assets/images/22.svg') }}" alt="" class="inner-button-star _3"/>
                      </div>
                      <div class="inner-button-hover-bg"></div>
                    </div>
                  </div>
                  <div class="line-wrap-inner">
                    <div class="inner-line-wrap _1">
                      <div class="line"></div><div class="line _2"></div>
                    </div>
                    <div class="inner-line-wrap _2">
                      <div class="line _3"></div><div class="line _4"></div>
                    </div>
                    <div class="inner-line-wrap _3">
                      <div class="line _5"></div><div class="line _6"></div>
                    </div>
                    <div class="inner-line-wrap _4">
                      <div class="line _7"></div><div class="line _8"></div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="features-card-image-wrapper">
          <img src="{{ asset('main/assets/images/27.webp') }}" alt="" class="features-card-image"/>
        </div>
      </div>

      <!-- Development -->
      <div class="features-flex">
        <div class="features-card-image-wrapper">
          <img src="{{ asset('main/assets/images/28.webp') }}" alt="" class="features-card-image two"/>
        </div>
        <div class="features-typography-card">
          <div class="features-typography-card-single">
            <div class="features-subtitle-wrapper">
              <div class="features-subtitle">[ Development ]</div>
            </div>
            <div class="features-title-description mb40">
              <h2 class="features-title">Built for Businesses & Communities</h2>
              <p class="features-description-text">
                Cyera AI simplifies Web3 adoption by offering user-friendly apps for communication, 
                trading, gaming, and storage—designed for both enterprises and everyday users.
              </p>
            </div>
            <div class="features-list-wrapper">
              <ul class="features-list">
                <li class="features-list-item">
                  <img src="{{ asset('main/assets/images/29.svg') }}" alt="" class="features-list-item-icon"/>
                  <div class="features-list-item-text">Blockchain as the backbone for finance, games, and cloud storage.</div>
                </li>
                <li class="features-list-item">
                  <img src="{{ asset('main/assets/images/30.svg') }}" alt="" class="features-list-item-icon"/>
                  <div class="features-list-item-text">Smart contracts powering DeFi, swaps, and community governance.</div>
                </li>
                <li class="features-list-item">
                  <img src="{{ asset('main/assets/images/31.svg') }}" alt="" class="features-list-item-icon"/>
                  <div class="features-list-item-text">Immersive Web3 experiences with Meta Play, Cyera Chat & Cyera Browser.</div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Security -->
      <div class="features-flex">
        <div class="features-typography-card">
          <div class="features-typography-card-single">
            <div class="features-subtitle-wrapper">
              <div class="features-subtitle">[ Security ]</div>
            </div>
            <div class="features-title-description">
              <h2 class="features-title">Enterprise-Grade Blockchain Security</h2>
              <p class="features-description-text">
                Every Cyera AI app is powered by end-to-end encryption, decentralized architecture, 
                and cryptographic protocols to ensure safety, privacy, and trust.
              </p>
            </div>
            <div class="features-single-card">
              <div class="features-single">
                <div class="features-card-icon-wrap">
                  <img src="{{ asset('main/assets/images/38.svg') }}" alt="" class="features-card-icon"/>
                </div>
                <div class="features-card-title-description">
                  <h4 class="features-card-title">Advanced Encryption</h4>
                  <p class="features-card-description">
                    Protects data, assets, and identities with zero compromises.
                  </p>
                </div>
              </div>
              <div class="features-single">
                <div class="features-card-icon-wrap">
                  <img src="{{ asset('main/assets/images/39.svg') }}" alt="" class="features-card-icon"/>
                </div>
                <div class="features-card-title-description">
                  <h4 class="features-card-title">Transparency & Trust</h4>
                  <p class="features-card-description">
                    Immutable ledgers and open governance for full accountability.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="features-card-image-wrapper">
          <img src="{{ asset('main/assets/images/44.webp') }}" alt="" class="features-card-image three"/>
        </div>
      </div>

    </div>
  </div>
</section>

        <section class="section core-features">
  <div class="core-features-content">
    <div class="core-features-top-content">
      <div class="container">
        <div class="core-features-section-title-wrapper">
          <div class="section-subtitle-wrapper">
            <div class="section-subtitle">[ Core features ]</div>
          </div>
          <h2 class="section-title core-features">Core Features of Cyera AI Ecosystem</h2>
        </div>
      </div>
    </div>

    <div class="core-features-bottom-content">
      <div data-delay="4000" data-animation="slide" class="core-features-slider w-slider" data-autoplay="false" data-easing="ease" data-hide-arrows="false" data-disable-swipe="false" data-autoplay-limit="0" data-nav-spacing="3" data-duration="500" data-infinite="true">
        <div class="core-features-mask w-slider-mask">

          <!-- Feature 1 -->
          <div class="core-features-single-slide w-slide">
            <div class="core-features-slider-card">
              <div class="core-features-slider-card-image-wrap">
                <img src="{{ asset('main/assets/images/172.webp') }}" alt="" class="core-features-slider-card-image"/>
              </div>
              <div class="core-features-card-title-description">
                <h4 class="core-features-card-title">Meta Swap – Decentralized Trading</h4>
                <p class="core-features-card-description-text">
                  Trade tokens instantly with low fees, high liquidity, and full transparency using our decentralized exchange.
                </p>
              </div>
              <div class="core-features-slider-card-number">[ 01 ]</div>
            </div>
          </div>

          <!-- Feature 2 -->
          <div class="core-features-single-slide w-slide">
            <div class="core-features-slider-card">
              <div class="core-features-slider-card-image-wrap">
                <img src="{{ asset('main/assets/images/191.webp') }}" alt="" class="core-features-slider-card-image"/>
              </div>
              <div class="core-features-card-title-description">
                <h4 class="core-features-card-title _2nd">Cyera Cloud – Secure Decentralized Storage</h4>
                <p class="core-features-card-description-text">
                  Store and protect your data with blockchain-backed security, encryption, and full ownership control.
                </p>
              </div>
              <div class="core-features-slider-card-number">[ 02 ]</div>
            </div>
          </div>

          <!-- Feature 3 -->
          <div class="core-features-single-slide w-slide">
            <div class="core-features-slider-card">
              <div class="core-features-slider-card-image-wrap">
                <img src="{{ asset('main/assets/images/172.webp') }}" alt="" class="core-features-slider-card-image"/>
              </div>
              <div class="core-features-card-title-description">
                <h4 class="core-features-card-title _3rd">Cyera Chat – Private & Encrypted Messaging</h4>
                <p class="core-features-card-description-text">
                  Communicate freely with end-to-end encrypted messages, decentralized servers, and zero data tracking.
                </p>
              </div>
              <div class="core-features-slider-card-number">[ 03 ]</div>
            </div>
          </div>

          <!-- Feature 4 -->
          <div class="core-features-single-slide w-slide">
            <div class="core-features-slider-card">
              <div class="core-features-slider-card-image-wrap">
                <img src="{{ asset('main/assets/images/191.webp') }}" alt="" class="core-features-slider-card-image"/>
              </div>
              <div class="core-features-card-title-description">
                <h4 class="core-features-card-title">Cyera Browser – Web3 Powered Surfing</h4>
                <p class="core-features-card-description-text">
                  Browse the decentralized internet with privacy-first features and direct integration to Cyera AI apps.
                </p>
              </div>
              <div class="core-features-slider-card-number">[ 04 ]</div>
            </div>
          </div>

          <!-- Feature 5 -->
          <div class="core-features-single-slide w-slide">
            <div class="core-features-slider-card">
              <div class="core-features-slider-card-image-wrap">
                <img src="{{ asset('main/assets/images/172.webp') }}" alt="" class="core-features-slider-card-image"/>
              </div>
              <div class="core-features-card-title-description">
                <h4 class="core-features-card-title">Meta Play – Gaming on Blockchain</h4>
                <p class="core-features-card-description-text">
                  Experience play-to-earn gaming with NFTs, token rewards, and a community-driven gaming ecosystem.
                </p>
              </div>
              <div class="core-features-slider-card-number">[ 05 ]</div>
            </div>
          </div>

          <!-- Feature 6 -->
          <div class="core-features-single-slide w-slide">
            <div class="core-features-slider-card">
              <div class="core-features-slider-card-image-wrap">
                <img src="{{ asset('main/assets/images/191.webp') }}" alt="" class="core-features-slider-card-image"/>
              </div>
              <div class="core-features-card-title-description">
                <h4 class="core-features-card-title">Meta Wallet – Your Digital Asset Vault</h4>
                <p class="core-features-card-description-text">
                  Manage, send, and receive cryptocurrencies with full control, multi-chain support, and bank-level security.
                </p>
              </div>
              <div class="core-features-slider-card-number">[ 06 ]</div>
            </div>
          </div>

         

        </div>

        <!-- Slider arrows -->
        <div class="core-features-slider-arrow left w-slider-arrow-left">
          <div class="core-features-slider-arrow-wrap">
            <img src="{{ asset('main/assets/images/158.svg') }}" alt="" class="core-features-slider-arrow-icon"/>
          </div>
        </div>
        <div class="core-features-slider-arrow right w-slider-arrow-right">
          <div class="core-features-slider-arrow-wrap">
            <img src="{{ asset('main/assets/images/157.svg') }}" alt="" class="core-features-slider-arrow-icon"/>
          </div>
        </div>
        <div class="core-features-slide-nav w-slider-nav w-round w-num"></div>
      </div>
    </div>
  </div>
</section>

      <section class="section testimonial">
            <div data-w-id="d1c43377-0abd-fb74-1663-3b34e3b073f7" class="testimonial-content">
               <div class="testimonial-wrapper">
                  <div class="container">
                     <div class="section-subtitle-wrapper center">
                        <div class="section-subtitle">[ Core features ]</div>
                     </div>
                     <div class="testimonial-section-title-wrapper">
                        <div class="testimonial-section-title-single">
                           <h2 class="section-title">What our clients have to say about technology.</h2>
                        </div>
                        <div class="testimonial-section-button-wrap">
                           <div class="primary-button-wrapper">
                              <a href="/reviews" class="inner-button w-inline-block">
                                 <div class="primary-button-border-wrap">
                                    <div class="inner-button-wrap">
                                       <div class="inner-button-text-wrap">
                                          <div class="inner-button-text">EXPLORE REVIEW</div>
                                          <div class="inner-button-hover-text">EXPLORE REVIEW</div>
                                       </div>
                                       <div class="inner-button-star-wrap"><img loading="lazy" src="{{ asset('main/assets/images/156.svg') }}" alt="" class="inner-button-star _1"/><img loading="lazy" src="{{ asset('main/assets/images/155.svg') }}" alt="" class="inner-button-star _2"/><img loading="lazy" src="{{ asset('main/assets/images/154.svg') }}" alt="" class="inner-button-star _3"/></div>
                                       <div class="inner-button-hover-bg"></div>
                                    </div>
                                 </div>
                                 <div class="line-wrap-inner">
                                    <div class="inner-line-wrap _1">
                                       <div class="line"></div>
                                       <div class="line _2"></div>
                                    </div>
                                    <div class="inner-line-wrap _2">
                                       <div class="line _3"></div>
                                       <div class="line _4"></div>
                                    </div>
                                    <div class="inner-line-wrap _3">
                                       <div class="line _5"></div>
                                       <div class="line _6"></div>
                                    </div>
                                    <div class="inner-line-wrap _4">
                                       <div class="line _7"></div>
                                       <div class="line _8"></div>
                                    </div>
                                 </div>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="testimonial-top-content">
                     <div class="testimonial-top-left-card">
                        <div class="testimonial-single-card one">
                           <div class="testimonial-description-wrapper">
                             <p class="testimonial-description-text">“As a blockchain developer, I found Cyera AI’s ecosystem incredibly smooth to integrate with. From Cyera Cloud to Meta Swap, everything runs seamlessly and supports multi-chain operations.”</p>
            </div>
                           <div class="testimonial-author-wrapper">
                              <div class="testimonial-author-image-wrap"><img src="{{ asset('main/assets/images/153.webp') }}" loading="lazy" alt="" class="testimonial-author-image"/></div>
                              <div class="testimonial-author-name-bio-wrapper">
                                  <h6 class="testimonial-author-name">Rachel T.</h6>
                <div class="testimonial-author-bio">Blockchain Developer, Singapore</div>
             
                              </div>
                           </div>
                           <img src="{{ asset('main/assets/images/152.svg') }}" loading="lazy" alt="" class="testimonial-line top-left"/><img src="{{ asset('main/assets/images/151.svg') }}" loading="lazy" alt="" class="testimonial-line top-right"/><img src="{{ asset('main/assets/images/150.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-left"/><img src="{{ asset('main/assets/images/149.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-right"/>
                        </div>
                        <div class="testimonial-single-card two">
                           <div class="testimonial-description-wrapper">
                             <p class="testimonial-description-text">“We are building an NFT marketplace, and Cyera AI’s tools saved us months of development. The decentralized storage and transaction speed are game changers for our users.”</p>
            </div>
                           <div class="testimonial-author-wrapper">
                              <div class="testimonial-author-image-wrap"><img src="{{ asset('main/assets/images/148.webp') }}" loading="lazy" alt="" class="testimonial-author-image"/></div>
                              <div class="testimonial-author-name-bio-wrapper">
                                  <h6 class="testimonial-author-name">Kelvin Y.</h6>
                <div class="testimonial-author-bio">NFT Entrepreneur, Singapore</div>
                              </div>
                           </div>
                           <img src="{{ asset('main/assets/images/147.svg') }}" loading="lazy" alt="" class="testimonial-line top-left"/><img src="{{ asset('main/assets/images/146.svg') }}" loading="lazy" alt="" class="testimonial-line top-right"/><img src="{{ asset('main/assets/images/145.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-left"/><img src="{{ asset('main/assets/images/144.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-right"/>
                        </div>
                     </div>
                     <div class="testimonial-top-right-card">
                        <div class="testimonial-single-card three">
                           <div class="testimonial-description-wrapper">
                             <p class="testimonial-description-text">“Cyera AI provided exactly what our DAO community needed — transparent governance tools and reliable smart contract execution. It has simplified onboarding and boosted engagement.”</p>
            </div>
                           <div class="testimonial-author-wrapper">
                              <div class="testimonial-author-image-wrap"><img src="{{ asset('main/assets/images/143.webp') }}" loading="lazy" alt="" class="testimonial-author-image"/></div>
                              <div class="testimonial-author-name-bio-wrapper">
                                  <h6 class="testimonial-author-name">Amanda C.</h6>
                <div class="testimonial-author-bio">DAO Manager, Singapore</div>
                              </div>
                           </div>
                           <img src="{{ asset('main/assets/images/142.svg') }}" loading="lazy" alt="" class="testimonial-line top-left"/><img src="{{ asset('main/assets/images/141.svg') }}" loading="lazy" alt="" class="testimonial-line top-right"/><img src="{{ asset('main/assets/images/140.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-left"/><img src="{{ asset('main/assets/images/139.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-right"/>
                        </div>
                     </div>
                  </div>
                  <div class="testimonial-bottom-content">
                     <div class="testimonial-bottom-left-card">
                        <div class="testimonial-single-card four">
                           <div class="testimonial-description-wrapper">
                             <p class="testimonial-description-text">“For our Metaverse project, Cyera AI has been a perfect partner. The decentralized browser and wallet integrations give our users privacy, freedom, and an immersive experience.”</p>
          </div>
                           <div class="testimonial-author-wrapper">
                              <div class="testimonial-author-image-wrap"><img src="{{ asset('main/assets/images/138.webp') }}" loading="lazy" alt="" class="testimonial-author-image"/></div>
                              <div class="testimonial-author-name-bio-wrapper">
                                 <h6 class="testimonial-author-name">Wei Jian H.</h6>
              <div class="testimonial-author-bio">Metaverse Founder, Singapore</div>
                              </div>
                           </div>
                           <img src="{{ asset('main/assets/images/137.svg') }}" loading="lazy" alt="" class="testimonial-line top-left"/><img src="{{ asset('main/assets/images/136.svg') }}" loading="lazy" alt="" class="testimonial-line top-right"/><img src="{{ asset('main/assets/images/135.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-left"/><img src="{{ asset('main/assets/images/134.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-right"/>
                        </div>
                     </div>
                     <div class="testimonial-single-card five">
                        <div class="testimonial-description-wrapper">
                           <p class="testimonial-description-text">“We wanted a futuristic and immersive design for our Metaverse project, and this template delivered beyond expectations. The Web3 integrations, stunning visuals, and lightning-fast performance make it stand out. Our community loves the experience, and we’re proud to build on this foundation!&quot;</p>
                        </div>
                        <div class="testimonial-author-wrapper">
                           <div class="testimonial-author-image-wrap"><img src="{{ asset('main/assets/images/133.webp') }}" loading="lazy" alt="" class="testimonial-author-image"/></div>
                           <div class="testimonial-author-name-bio-wrapper">
                              <h6 class="testimonial-author-name">Ryan T.</h6>
                              <div class="testimonial-author-bio">Metaverse Founder</div>
                           </div>
                        </div>
                        <img src="{{ asset('main/assets/images/132.svg') }}" loading="lazy" alt="" class="testimonial-line top-left"/><img src="{{ asset('main/assets/images/131.svg') }}" loading="lazy" alt="" class="testimonial-line top-right"/><img src="{{ asset('main/assets/images/130.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-left"/><img src="{{ asset('main/assets/images/129.svg') }}" loading="lazy" alt="" class="testimonial-line bottom-right"/>
                     </div>
                  </div>
                  <img src="{{ asset('main/assets/images/128.webp') }}" loading="lazy" sizes="(max-width: 540px) 100vw, 540px" srcset="{{ asset('main/assets/images/127.webp') }} 500w, {{ asset('main/assets/images/127.webp') }} 540w" alt="" class="testimonial-world-img"/><img src="{{ asset('main/assets/images/125.svg') }}" loading="lazy" alt="" class="testimonial-pluse top-left"/><img src="{{ asset('main/assets/images/124.svg') }}" loading="lazy" alt="" class="testimonial-pluse top-right"/><img src="{{ asset('main/assets/images/123.svg') }}" loading="lazy" alt="" class="testimonial-pluse bottom-left"/><img src="{{ asset('main/assets/images/122.svg') }}" loading="lazy" alt="" class="testimonial-pluse bottom-right"/>
               </div>
            </div>
         </section>

         <section class="section company-section">
            <div class="company-wrapper">
               <div class="company-content">
                  <div class="company-title-wrapper">
                     <h6 class="company-title">Partnered with 150+ companies</h6>
                  </div>
                  <div class="sponsors-ticker">
                     <div class="ticker">
                        <div class="inner-ticker-wrapper"><img loading="lazy" src="{{ asset('main/assets/images/101.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/102.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/103.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/104.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/105.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/106.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/107.svg') }}" alt="" class="single-sponsor-img"/></div>
                        <div class="inner-ticker-wrapper"><img loading="lazy" src="{{ asset('main/assets/images/108.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/109.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/110.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/111.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/112.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/113.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/114.svg') }}" alt="" class="single-sponsor-img"/></div>
                        <div class="inner-ticker-wrapper"><img loading="lazy" src="{{ asset('main/assets/images/115.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/116.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/117.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/118.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/119.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/120.svg') }}" alt="" class="single-sponsor-img"/><img loading="lazy" src="{{ asset('main/assets/images/121.svg') }}" alt="" class="single-sponsor-img"/></div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         
       <section data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d72e" class="section cta">
   <div class="container">
      <div class="cta-content">
         <div data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d731" class="cta-typography">
            <div class="cta-title-description">
               <h2 class="cta-title">Building the Future of Web3 from Singapore.</h2>
               <p class="cta-description-text">Scalable, secure, and lightning-fast blockchain solutions powering enterprises, startups, and global innovation.</p>
            </div>
            <div class="cta-button-wrapper">
               <div class="primary-button-wrapper">
                  <a href="/register" class="inner-button w-inline-block">
                     <div class="primary-button-border-wrap">
                        <div class="inner-button-wrap">
                           <div class="inner-button-text-wrap">
                              <div class="inner-button-text">Start Your Journey</div>
                              <div class="inner-button-hover-text">Start Your Journey</div>
                           </div>
                           <div class="inner-button-star-wrap">
                              <img loading="lazy" src="{{ asset('main/assets/images/73.svg') }}" alt="" class="inner-button-star _1"/>
                              <img loading="lazy" src="{{ asset('main/assets/images/72.svg') }}" alt="" class="inner-button-star _2"/>
                              <img loading="lazy" src="{{ asset('main/assets/images/71.svg') }}" alt="" class="inner-button-star _3"/>
                           </div>
                           <div class="inner-button-hover-bg"></div>
                        </div>
                     </div>
                     <div class="line-wrap-inner">
                        <div class="inner-line-wrap _1">
                           <div class="line"></div>
                           <div class="line _2"></div>
                        </div>
                        <div class="inner-line-wrap _2">
                           <div class="line _3"></div>
                           <div class="line _4"></div>
                        </div>
                        <div class="inner-line-wrap _3">
                           <div class="line _5"></div>
                           <div class="line _6"></div>
                        </div>
                        <div class="inner-line-wrap _4">
                           <div class="line _7"></div>
                           <div class="line _8"></div>
                        </div>
                     </div>
                  </a>
               </div>
            </div>
         </div>
         <img src="{{ asset('main/assets/images/62.svg') }}" loading="lazy" alt="" class="cta-top-left-element"/>
         <img src="{{ asset('main/assets/images/63.svg') }}" loading="lazy" alt="" class="cta-top-right-element"/>
         <img src="{{ asset('main/assets/images/64.svg') }}" loading="lazy" alt="" class="cta-bottom-left-element"/>
         <img src="{{ asset('main/assets/images/65.svg') }}" loading="lazy" alt="" class="cta-bottom-right-element"/>
         <img src="{{ asset('main/assets/images/66.webp') }}" loading="lazy" data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d752" sizes="100vw" alt="" srcset="{{ asset('main/assets/images/67.png') }} 500w, {{ asset('main/assets/images/68.webp') }} 537w" class="cta-shape"/>
         <img src="{{ asset('main/assets/images/ctaside.webp') }}" loading="lazy" data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d753" alt="" class="cta-elment-one"/>
         <img src="{{ asset('main/assets/images/70.webp') }}" loading="lazy" data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d754" alt="" class="cta-elment-two"/>
      </div>
   </div>
</section>

  

      </div>
    
@endsection
