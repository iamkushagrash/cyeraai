<!DOCTYPE html>
<html lang="eng">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Basic Meta Tags -->
  <title>Cyera AI | Decentralized Wealth & DeFi Trading Platform</title>
  <meta name="description" content="Cyera AI is a decentralized wealth ecosystem to trade, manage, and grow crypto assets securely using DeFi technology." />
  <meta name="author" content="Cyera AI" />
  <meta name="robots" content="index, follow" />

  <!-- Open Graph -->
  <meta property="og:title" content="Cyera AI | Decentralized Wealth & DeFi Trading Platform" />
  <meta property="og:description" content="Cyera AI is a decentralized wealth ecosystem to trade, manage, and grow crypto assets securely using DeFi technology." />
  <meta property="og:image" content="https://cyera.ai/assets/images/ogg-image.avif" />
  <meta property="og:url" content="https://cyera.ai/" />
  <meta property="og:type" content="website" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Cyera AI | Decentralized Wealth & DeFi Trading Platform" />
  <meta name="twitter:description" content="Cyera AI is a decentralized wealth ecosystem to trade, manage, and grow crypto assets securely using DeFi technology." />

  <!-- Canonical -->
  <link rel="canonical" href="https://cyera.ai/" />

  <!-- Hreflang -->
  <link rel="alternate" hreflang="en" href="https://cyera.ai/" />
  <link rel="alternate" hreflang="fr" href="https://cyera.ai/fr/" />

  <link rel="stylesheet" href="{{ asset('front2/assets/css/style.css')}}" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="icon" href="{{ asset('front2/assets/images/favicon.svg')}}" />
  <!-- AMP Analytics -->
  <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HR1991Q5TL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HR1991Q5TL');
</script>
</head>

<body class="help">
<!-- Google Tag Manager -->
<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-TX2FSLZG&gtm.url=SOURCE_URL" data-credentials="include"></amp-analytics>

  @include('front2.header')

  <button id="backTop" aria-label="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
  </button>
  <div class="site-wrapper">

    <section class="page-header">
      <div class="hero-circles-wrapper">
        <div class="circle one">
          <img src="{{ asset('front2/assets/images/half-elipse.png')}}" alt="">
        </div>
        <div class="circle two">
          <img src="{{ asset('front2/assets/images/half-elipse.png')}}" alt="">
        </div>
        <div class="circle three">
          <img src="{{ asset('front2/assets/images/half-elipse.png')}}" alt="">
        </div>
      </div>
      <div class="container">
        <nav class="breadcrumbs">
          <a href="/index">Home </a>
          <span>Our publications</span>
        </nav>
        <div class="lg:w-[75%] flex flex-col items-center text-center">
          <h1 class="mb-6 ">
            <span class="block text-gradient-primary w-fit">Insights, Reports, and Research from Cyera AI</span>
          </h1>
        </div>
      </div>
    </section>

    <section class="posts my-20 lg:my-28">
      <div class="container">
        <div id="post-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6"></div>
        <div id="loader" style="display:none;text-align:center;padding:40px;">Loading more…</div>
      </div>
    </section>

    <section class="ctoa my-32">
      <div class="container">
        <div class="ctoa-circles-wrapper">
          <div class="circle one">
            <img src="{{ asset('front2/assets/images/half-elipse.png')}}" alt="">
          </div>
          <div class="circle two">
            <img src="{{ asset('front2/assets/images/half-elipse.png')}}" alt="">
          </div>
          <div class="circle three">
            <img src="{{ asset('front2/assets/images/half-elipse.png')}}" alt="">
          </div>
        </div>
        <div class="flex flex-col items-center lg:w-[45%] mx-auto">
          <div class="ctoa-icon-holder">
            <img src="{{ asset('front2/assets/images/hero-logo.png')}}" alt="">
          </div>
          <div class="ctoa-heading text-center">
            <h2 class="text-gradient-primary my-6">Join the Global Cyera AI Revolution</h2>
          </div>
          <p class="lead mb-6 text-center">Scalable, secure, and lightning-fast blockchain solutions powering enterprises, startups, and global innovation.</p>
          <a href="https://cyera.ai/register" target="_blank" class="btn btn-primary">
            <div class="text">
              <span class="front">Start Your Journey</span>
              <span class="back">Start Your Journey</span>
            </div>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>
  </div>

  @include('front2.footer')

  <script src="{{ asset('front2/assets/js/bundle.js')}}"></script>
  <script src="{{ asset('front2/data/post-data.js')}}"></script>
  <script src="{{ asset('front2/assets/js/publications.js')}}"></script>
  <script src="{{ asset('front2/assets/js/init.js')}}"></script>
  <script src='https://in.fw-cdn.com/32686977/1507487.js' chat='true'></script>

</body>

</html>