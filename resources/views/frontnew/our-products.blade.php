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

<body class="products">

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
          <span>Our Products</span>
        </nav>
        <div class="lg:w-3/5 flex flex-col items-center text-center">
          <h1 class="mb-6 ">
            <span class="block text-gradient-primary w-fit">Powering the Future of Digital Freedom</span>
          </h1>
          <p class="lead">Discover Cyera AI's vision for a transparent, decentralized ecosystem enabling secure digital ownership, innovation, and sustainable growth worldwide.</p>
        </div>
      </div>
    </section>

    <section class="my-16 lg:my-28 mx-auto">
      <div class="container products">
        <!-- <div class="products-scroll-indicator"></div> -->
        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/m-connect.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">M-Connect</h5>
            <h3 class="text-white-gradient w-fit mb-4">The Unified Gateway to the Meta Ecosystem</h3>
            <p class="mb-4">M-Connect is the central access platform that brings all Meta applications into one seamless digital experience. It allows users to access, manage, and interact with
              multiple Meta services through a single login and a unified interface.</p>
            <p class="mb-4">Built on decentralized infrastructure, M-Connect eliminates fragmentation by connecting finance, networking, communication, AI, trading, and utility platforms under one
              ecosystem. It is designed for security, scalability, and global accessibility.</p>
            <p>M-Connect is not just an app launcher — it is the digital foundation that connects every Meta service into a cohesive decentralized environment.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/wallet.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Wallet</h5>
            <h3 class="text-white-gradient w-fit mb-4">Secure Digital Assets & Identity Management</h3>
            <p class="mb-4">Meta Wallet is a secure, decentralized wallet designed to store, manage, and transact digital assets with full user ownership. It serves as the identity and transaction
              layer across the Meta ecosystem.</p>
            <p class="mb-4">Users can send, receive, and manage assets seamlessly while maintaining complete control over their private keys. Meta Wallet integrates directly with all Meta
              applications, enabling secure authentication and frictionless transactions within M-Connect.</p>
            <p>Built with enterprise-grade security and user-first design, Meta Wallet ensures trust, transparency, and ease of use.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/wealth.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Cyera AI</h5>
            <h3 class="text-white-gradient w-fit mb-4">Decentralized Networking & Income Management Platform</h3>
            <p class="mb-4">Cyera AI is a decentralized networking platform where users can build and manage their personal networks, track referred teams, and monitor income and performance
              transparently.</p>
            <p class="mb-4">The platform enables users to grow their networks, analyze team activity, and participate in ecosystem-driven earning opportunities. All activity is recorded transparently,
              ensuring clarity, trust, and accountability.</p>
            <p>Cyera AI empowers individuals and communities to grow collectively through structured networking within a decentralized framework.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/meet.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Talk</h5>
            <h3 class="text-white-gradient w-fit mb-4">Private & Decentralized Messaging</h3>
            <p class="mb-4">Meta Talk is a decentralized communication platform designed for secure messaging and real-time interaction. It allows users and teams to communicate without relying on
              centralized messaging systems.</p>
            <p>With privacy-first architecture, Meta Talk ensures that conversations remain encrypted, secure, and user-controlled. It is built for personal communication, team collaboration, and
              community engagement.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/meet.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Meet</h5>
            <h3 class="text-white-gradient w-fit mb-4">Decentralized Video Conferencing</h3>
            <p class="mb-4">Meta Meet is a decentralized video conferencing platform built for meetings, events, and global collaboration. It enables high-quality virtual interactions while
              prioritizing privacy and security.</p>
            <p>Users can host meetings, connect with teams, and conduct virtual events without centralized control. Meta Meet integrates seamlessly within M-Connect for effortless access and
              participation.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/wealth.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Cyera AI</h5>
            <h3 class="text-white-gradient w-fit mb-4">Intelligent Assistance & Automation</h3>
            <p class="mb-4">Cyera AI is the intelligence layer of the Meta ecosystem. It enhances user experience through automation, insights, and smart assistance across applications.</p>
            <p>From decision support to workflow optimization, Cyera AI helps users interact more efficiently with Meta platforms. It continuously adapts to user behavior, improving accuracy and
              usability over time.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/m-connect.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Play</h5>
            <h3 class="text-white-gradient w-fit mb-4">Decentralized Entertainment & Engagement</h3>
            <p class="mb-4">Meta Play is a decentralized engagement platform that brings interactive entertainment into the Meta ecosystem. It allows users to participate in digital experiences while
              earning rewards through engagement.</p>
            <p>Designed for fun, participation, and community interaction, Meta Play blends entertainment with Web3 ownership and decentralized incentives.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/meet.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Storage</h5>
            <h3 class="text-white-gradient w-fit mb-4">Secure Decentralized Data Storage</h3>
            <p class="mb-4">Meta Storage is a decentralized storage solution designed to give users full ownership of their data. It provides secure, scalable, and privacy-focused storage without
              dependence on centralized servers.</p>
            <p>Users can store, access, and manage files confidently while maintaining control over data access and usage. Meta Storage is built for reliability, security, and future scalability.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/storage.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Cyera Browser</h5>
            <h3 class="text-white-gradient w-fit mb-4">Web3-Ready Secure Browsing</h3>
            <p class="mb-4">Cyera Browser is a Web3-enabled browser optimized for decentralized applications and blockchain interactions. It allows users to explore the decentralized web safely and
              seamlessly.</p>
            <p>Integrated with Meta Wallet and M-Connect, Cyera Browser provides a secure browsing environment tailored for Web3 users and decentralized platforms.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/wallet.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Swap</h5>
            <h3 class="text-white-gradient w-fit mb-4">Decentralized Asset Swapping</h3>
            <p class="mb-4">Meta Swap enables fast, transparent, and intermediary-free digital asset exchanges. Built on decentralized protocols, it allows users to swap assets directly from their
              wallets with full control and visibility.</p>
            <p>Meta Swap prioritizes efficiency, transparency, and security while supporting seamless asset conversion within the Meta ecosystem.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/wealth.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">Meta Mail</h5>
            <h3 class="text-white-gradient w-fit mb-4">Decentralized Email & Digital Communication</h3>
            <p class="mb-4">Meta Mail is a decentralized email and communication platform built for privacy, encryption, and data ownership. It removes reliance on centralized email providers while
              offering secure and reliable communication.</p>
            <p>Users maintain full control over their data and communications, ensuring confidentiality and protection in a decentralized digital environment.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/m-connect.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">MetaX</h5>
            <h3 class="text-white-gradient w-fit mb-4">Secure Decentralized Data Storage</h3>
            <p class="mb-4">MetaX is the decentralized crypto exchange within the Meta ecosystem. It enables users to trade digital assets directly from their wallets with full ownership and on-chain
              transparency.</p>
            <p>MetaX removes intermediaries while offering a secure, efficient, and user-controlled trading environment designed for global crypto participation.</p>
          </div>
        </div>

        <div class="products-card flex flex-col lg:flex-row lg:gap-12 items-center justify-center">
          <div class="products-image">
            <img src="{{ asset('front/assets/images/products/meet.avif')}}" class="w-[75%] mx-auto" alt="">
            <img class="blur-image" src="{{ asset('front/assets/images/blurred-bg.avif')}}" alt="">
          </div>
          <div class="products-content">
            <h5 class="text-gradient-primary w-fit font-semibold mb-3">MetaFX</h5>
            <h3 class="text-white-gradient w-fit mb-4">Decentralized Foreign Exchange Trading Platform</h3>
            <p class="mb-4">MetaFX is a decentralized foreign exchange trading platform designed to enable transparent and borderless currency trading powered by blockchain technology.</p>
            <p>By removing traditional intermediaries, MetaFX provides users with direct access to global FX markets through a decentralized and trustless framework, redefining how forex trading
              operates.</p>
          </div>
        </div>

      </div>
    </section>

    <section class="ctoa my-32">
      <div class="container">
        <div class="ctoa-circles-wrapper">
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
        <div class="flex flex-col items-center lg:w-[45%] mx-auto">
          <div class="ctoa-icon-holder">
            <img src="{{ asset('front/assets/images/hero-logo.png')}}" alt="">
          </div>
          <div class="ctoa-heading text-center">
            <h2 class="text-gradient-primary my-6">Join the Global Cyera AI Revolution</h2>
          </div>
          <p class="lead mb-6 text-center">Scalable, secure, and lightning-fast blockchain solutions powering enterprises, startups, and global innovation.</p>
          <a href="https://cyera.ai/register" target="_blank" class="btn btn-primary" data-title="Start Your Journey"><span>Start Your Journey</span><i class="fa-solid fa-arrow-right"></i></a>
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