<!DOCTYPE html>
<html lang="@@lang">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />



  <!-- Basic Meta Tags -->
  <title>Cyera AI - Decentralized Wealth Platform</title>
  <meta name="description" content="Cyera AI is a decentralized finance and blockchain-based wealth management platform." />
  <meta name="author" content="Cyera AI" />
  <meta name="robots" content="index, follow" />

  <!-- Open Graph -->
  <meta property="og:title" content="Cyera AI - Decentralized Wealth Platform" />
  <meta property="og:description" content="Cyera AI is a decentralized finance and blockchain-based wealth management platform." />
  <meta property="og:image" content="https://cyera.ai/images/branding-showcase.jpg" />
  <meta property="og:url" content="https://cyera.ai/@@path" />
  <meta property="og:type" content="website" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Cyera AI - Decentralized Wealth Platform" />
  <meta name="twitter:description" content="Cyera AI is a decentralized finance and blockchain-based wealth management platform." />

  <!-- Canonical -->
  <link rel="canonical" href="https://cyera.ai/@@path" />

  <!-- Hreflang -->
  <link rel="alternate" hreflang="en" href="https://cyera.ai/" />
  <link rel="alternate" hreflang="fr" href="https://cyera.ai/fr/" />

  <link rel="stylesheet" href="{{ asset('front/assets/css/style.css?v=1.2')}}" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100..1000&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="icon" href="{{ asset('front/assets/images/favicon.svg')}}" />
</head>

<body class="help">

  @include('frontnew.header')


  <div class="site-wrapper">



    <section class="page-header">
      <div class="hero-circles-wrapper">
        <div class="circle one">
          <img src="{{ asset('front/assets/images/half-elipse.png')}}" alt="">
        </div>
        <div class="circle two">
          <img src="{{ asset('front/assets/images/half-elipse.png')}}" alt="">
        </div>
        <div class="circle three">
          <img src="{{ asset('front/assets/images/half-elipse.png')}}" alt="">
        </div>
      </div>
      <div class="container">
        <nav class="breadcrumbs">
          <a href="/">Home </a>
          <span>Help</span>
        </nav>
        <div class="lg:w-3/5 flex flex-col items-center text-center">
          <h1 class="mb-6 text-gradient-primary">Cyera AI Tutorials</h1>
          <p class="lead">Step-by-step guides to help you get started and explore the Cyera AI platform.</p>
        </div>
      </div>
    </section>

    <section class="tutorials mb-32">
      <div class="container flex flex-col lg:flex-row gap-16">
        <div class="tutorials-col flex flex-col lg:w-1/2 gap-16">
          <div class="tutorial">
            <h4 class="tutorial-title text-white-gradient w-fit">About Cyera AI</h4>
            <div class="youtube-wrapper">
              <iframe src="https://www.youtube.com/embed/WOLZKm_wIng" title="YouTube video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
          <div class="tutorial">
            <h4 class="tutorial-title text-white-gradient w-fit">How To Join / Register in Cyera AI</h4>
            <div class="youtube-wrapper">
              <iframe src="https://www.youtube.com/embed/UQXoJolbdNc" title="YouTube video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
          <div class="tutorial">
            <h4 class="tutorial-title text-white-gradient w-fit">How to Access Dashboard & View Reports</h4>
            <div class="youtube-wrapper">
              <iframe src="https://www.youtube.com/embed/nWSTeWlTplg" title="YouTube video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="tutorials-col flex flex-col lg:w-1/2 gap-16">
          <div class="tutorial">
            <h4 class="tutorial-title text-white-gradient w-fit">How to Topup Wallet?</h4>
            <div class="youtube-wrapper">
              <iframe src="https://www.youtube.com/embed/i7ySMkNNXGg" title="YouTube video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
          <div class="tutorial">
            <h4 class="tutorial-title text-white-gradient w-fit">How to Sell CAI / Withdraw</h4>
            <div class="youtube-wrapper">
              <iframe src="https://www.youtube.com/embed/XJ_n-wNSfjY" title="YouTube video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
          <div class="tutorial">
            <h4 class="tutorial-title text-white-gradient w-fit">Meta Meet Complete Tutorial</h4>
            <div class="youtube-wrapper">
              <iframe src="https://www.youtube.com/embed/ujdIdif1W-s" title="YouTube video" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
          </div>
        </div>
      </div>


    </section>


  </div>

  @include('frontnew.footer')

  <script src="{{ asset('front/assets/js/bundle.js')}}"></script>

  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
      var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/695395609053fb197ca46095/1jdn7v0bo';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
  <!--End of Tawk.to Script-->

</body>

</html>