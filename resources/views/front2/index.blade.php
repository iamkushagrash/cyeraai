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

<body class="home">
<!-- Google Tag Manager -->
<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-TX2FSLZG&gtm.url=SOURCE_URL" data-credentials="include"></amp-analytics>

  @include('front2.header')

  <button id="backTop" aria-label="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
  </button>

  <div class="site-wrapper">

    <section class="hero ">
      <div class="container">
        <div class="hero-section-circle-wrapper">
          <div class="hero-icon-holder">
            <img src="{{ asset('front2/assets/images/hero-logo.png')}}" alt="">
          </div>
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
        </div>
        <div class="hero-title flex flex-col justify-center items-center">
          <div class="heading">
            <h1 class="mb-4 text-gradient-primary"><span>Cyera AI - Intelligent Strategies for Modern Wealth</span></h1>
          </div>
          <p class="description mb-8">An ecosystem built on trust, transparency, and sustainable growth—empowering decentralized finance, communication, storage, gaming, and more for a freer digital
            future.</p>
          <div class="flex flex-col lg:flex-row gap-4 justify-center hero-btns">
            <a href="https://mwtscan.com/" target="_blank" class="btn btn-primary" data-title="Explore Cyera AI">
              <div class="text">
                <span class="front">Explore Cyera AI</span>
                <span class="back">Explore Cyera AI</span>
              </div>
              <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="https://console.cyera.ai/" target="_blank" class="btn" data-title="Meta Console">
              <div class="text">
                <span class="front">Meta Console</span>
                <span class="back">Meta Console</span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="partners" data-anim="fade-up">
      <div class="container pt-10">
        <div class="flex flex-col">
          <h4 class="text-center"><span class="text-gradient-primary">Partnering with the world's leading Companies</span></h4>
          <div class="container">
            <div class="partners-swiper">
              <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/101.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/102.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/103.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/104.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/105.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/106.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/107.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/108.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/109.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/110.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/111.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/112.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/113.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/114.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/115.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/116.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/117.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/118.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/119.svg')}}" alt=""></div>
                <div class="swiper-slide"><img src="{{ asset('front2/assets/images/partners/120.svg')}}" alt=""></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="about my-20 md:my-24 lg:my-28">
      <div class="container">
        <div class="flex flex-col lg:flex-row lg:items-end justify-end mb-8 lg:mb-16">
          <div class="lg:w-[50%]" data-anim="fade-up">
            <div class="sub-title lg:mb-3">Introducing Cyera AI</div>
            <h2 class="mb-4"><span class="split-text text-white-gradient">Powering the Future of Digital Wealth</span></h2>
          </div>
          <div class="lg:w-[50%] lg:pl-8" data-anim="fade-up" data-delay="0.2">
            <p class="lead mb-4">Cyera AI empowers the future of digital wealth through decentralized infrastructure, transparent systems, and secure technologies, enabling users to grow, manage,
              and
              protect assets with confidence in a scalable, trust-driven ecosystem designed for long-term value creation.
            </p>
          </div>
        </div>

        <div class="anim-line one"></div>

        <div class="flex flex-col md:flex-row">
          <div class="feat lg:w-1/3" data-anim="fade-up">
            <img src="{{ asset('front2/assets/images/shield.svg')}}" alt="">
            <h4 class="text-pri-gradient mb-3">Trust-First Ecosystem</h4>
            <p>Built on trust and transparency, delivering secure, reliable user experiences across every interaction and transaction.</p>
          </div>
          <div class="feat lg:w-1/3" data-anim="fade-up" data-delay="0.2">
            <img src="{{ asset('front2/assets/images/cube.svg')}}" alt="">
            <h4 class="text-pri-gradient mb-3">Decentralized by Design</h4>
            <p>Architected to distribute control, reduce intermediaries, and empower users through open, resilient protocols.</p>
          </div>
          <div class="feat lg:w-1/3" data-anim="fade-up" data-delay="0.4">
            <img src="{{ asset('front2/assets/images/growth.svg')}}" alt="">
            <h4 class="text-pri-gradient mb-3">Built for Long-Term Growth</h4>
            <p>Designed to scale sustainably, enabling continuous innovation, resilience, and long-term value for all participants.</p>
          </div>
        </div>

        <div class="anim-line two"></div>

        <div class="flex flex-col md:flex-row">
          <div class="feat lg:w-1/3" data-anim="fade-up" data-delay="0.1">
            <img src="{{ asset('front2/assets/images/user.svg')}}" alt="">
            <h4 class="text-pri-gradient mb-3">User-Owned & Governed</h4>
            <p>Empowering participants with ownership rights and governance tools that enable fair participation and shared decision-making.</p>
          </div>
          <div class="feat lg:w-1/3" data-anim="fade-up" data-delay="0.2">
            <img src="{{ asset('front2/assets/images/chip.svg')}}" alt="">
            <h4 class="text-pri-gradient mb-3">Scalable Technology</h4>
            <p>Flexible, modular infrastructure enabling seamless expansion, integration, and evolution with emerging decentralized technologies.</p>
          </div>
          <div class="feat lg:w-1/3" data-anim="fade-up" data-delay="0.3">
            <img src="{{ asset('front2/assets/images/server.svg')}}" alt="">
            <h4 class="text-pri-gradient mb-3">Secure & Transparent</h4>
            <p>Advanced security practices and transparent systems ensure protection, accountability, and verifiable operations across the ecosystem.</p>
          </div>
        </div>

        <div class="anim-line three"></div>
      </div>

      <div class="container mt-28">
        <div class="counters-wrapper">
          <h4 class="counters-title pb-4" data-anim="fade-up"><span class="text-gradient-primary">The Cyera AI Story in Numbers</span></h4>
          <div class="counters" data-children data-anim="fade-up" data-stagger="0.2">
            <div class="counter">
              <div class="counter-number">
                <span class="number" data-number="50">00</span>
                <span class="suffix">k+</span>
              </div>
              <div class="counter-text">
                Secure Transactions
              </div>
            </div>
            <div class="counter">
              <div class="counter-number">
                <span class="number" data-number="20">00</span>
                <span class="suffix">+</span>
              </div>
              <div class="counter-text">
                Global Partners
              </div>
            </div>
            <div class="counter">
              <div class="counter-number">
                <span class="number" data-number="10">00</span>
                <span class="suffix">k+</span>
              </div>
              <div class="counter-text">
                Wallet Installs
              </div>
            </div>
            <div class="counter">
              <div class="counter-number">
                <span class="number" data-number="05">00</span>
                <span class="suffix">k+</span>
              </div>
              <div class="counter-text">
                Active Users
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

    <section class="features-home md:my-24 lg:my-28">
      <div class="container flex flex-col lg:flex-row items-start justify-between" data-anim="fade-up">
        <div class="lg:w-2/5 feat-intro">
          <div class="sub-title mb-4">Cyera AI Ecosystem</div>
          <h2 class="flex text-white-gradient w-fit">
            Built on Trust. Designed for Growth
          </h2>
          <img class="my-8" src="{{ asset('front2/assets/images/image-feat.avif')}}" alt="">
          <p class="mb-6 lead ">Cyera AI empowers digital wealth through decentralized, transparent, secure systems, enabling asset growth, management, and protection long-term.</p>
          <a href="https://mwtscan.com/" target="_blank" class="btn btn-primary w-auto" data-title="Explore Cyera AI"><span>Explore Cyera AI</span> <i class="fa-solid fa-arrow-right"></i></a>
          <img src="{{ asset('front2/assets/images/arrow.avif')}}" class="w-full arrow-image hidden lg:block" alt="">
        </div>
        <div class="feat-cards lg:w-[55%] lg:pl-12 pt-12 lg:pt-0">
          <div class="feat-card">
            <div class="icon mb-4">
              <img src="{{ asset('front2/assets/images/solvingcard.svg')}}" alt="">
            </div>
            <h3 class="mb-2">Decentralized Finance (DeFi)</h3>
            <p>Decentralized financial services enabling transparent asset management, secure transactions, trading, lending, and yield generation without intermediaries, empowering users with full
              control.</p>
          </div>
          <div class="feat-card">
            <div class="icon mb-4">
              <img src="{{ asset('front2/assets/images/globe-shield.svg')}}" alt="">
            </div>
            <h3 class="mb-2">Blockchain Security</h3>
            <p>Advanced cryptographic protection safeguards transactions, ensures data integrity, and protects the blockchain ecosystem against fraud, manipulation, and evolving security threats.</p>
          </div>
          <div class="feat-card">
            <div class="icon mb-4">
              <img src="{{ asset('front2/assets/images/globe-shield.svg')}}" alt="">
            </div>
            <h3 class="mb-2">DAO Governance</h3>
            <p>Fair decision-making through decentralized autonomous organizations, enabling transparent governance, community-driven proposals, collective voting, and equitable participation without
              centralized control or authority.</p>
          </div>
          <div class="feat-card">
            <div class="icon mb-4">
              <img src="{{ asset('front2/assets/images/icon-storage.svg')}}" alt="">
            </div>
            <h3 class="mb-2">Decentralized Storage</h3>
            <p>On-chain encrypted storage providing secure, private, and globally accessible data, ensuring user ownership, censorship resistance, and reliable availability across the decentralized
              ecosystem.</p>
          </div>
          <div class="feat-card">
            <div class="icon mb-4">
              <img src="{{ asset('front2/assets/images/smart-contract.svg')}}" alt="">
            </div>
            <h3 class="mb-2">Smart Contracts</h3>
            <p>Automated, trustless smart contracts power financial services, gaming interactions, and decentralized governance, ensuring transparency, efficiency, and reliability without
              intermediaries
              across the ecosystem.</p>
          </div>
          <div class="feat-card">
            <div class="icon mb-4">
              <img src="{{ asset('front2/assets/images/crolss-chain.svg')}}" alt="">
            </div>
            <h3 class="mb-2">Cross-Chain Interoperability</h3>
            <p>Seamlessly bridge assets and data across multiple blockchain networks, enabling interoperability, flexibility, and unified experiences within a decentralized multi-chain ecosystem.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="why-cyera my-32">
      <div class="container">
        <div class="lg:w-[75%] mx-auto flex flex-col items-center mb-16 lg:mb-24 intro">
          <div data-anim="fade-up">
            <div class="sub-title mb-2">Why Cyera AI</div>
          </div>
          <h2 class="text-center" data-anim="fade-up" data-delay="0.2">
            <div class="block text-white-gradient">Built for People, Backed by Backed by Powerful Technology</div>
          </h2>
        </div>

        <div class="cards-wrapper" data-anim="fade-up" data-delay="0.2">
          <div class="card integration flex flex-col lg:flex-row items-center">
            <div class="card-content lg:w-1/2 order-2">
              <div class="sub-title mb-3">INTEGRATIONS</div>
              <h3 class="mb-4">Works seamlessly across the entire decentralized Web3 ecosystem</h3>
              <p class="mb-4">Cyera AI empowers the future of digital wealth through decentralized infrastructure, transparent systems, and secure technologies, enabling users to grow, manage, and
                protect assets with confidence in a scalable, trust-driven ecosystem designed for long-term value creation.</p>

              <ul class="check-list">
                <li>Seamless Interoperability - Connects smoothly across Web3 platforms, enabling consistent experiences and efficient value exchange.</li>
                <li>Enterprise-Grade Security - Advanced cryptography and decentralized architecture protect assets, data, and transactions at scale.</li>
                <li>Future-Ready Scalability - Built to evolve continuously, supporting innovation, growth, and expanding ecosystem demands over time.</li>
              </ul>

            </div>

            <div class="card-image lg:w-1/2 order-1">
              <img src="{{ asset('front2/assets/images/integrations.avif')}}" alt="">
              <div class="bg-blur"></div>
            </div>
          </div>
          <div class="card development flex flex-col lg:flex-row items-center">
            <div class="card-content lg:w-1/2 order-2 lg:order-1">
              <div class="sub-title mb-3">Development</div>
              <h3 class="mb-4">Designed to support businesses and empower growing global communities</h3>
              <p class="mb-6">Built to support businesses and communities with scalable infrastructure, intuitive tools, and secure systems that enable collaboration, growth, and long-term value
                creation
                across diverse Web3 use cases.</p>

              <ul class="check-list">
                <li>Scalable infrastructure supporting business growth and expanding community ecosystems across Web3 platforms.</li>
                <li>Secure, enterprise-grade systems ensuring trust, reliability, and protection for users and organizations.</li>
                <li>Intuitive tools enabling seamless collaboration, governance, and long-term value creation together.</li>
              </ul>

            </div>

            <div class="card-image lg:w-1/2 order-1 lg:order-2">
              <img src="{{ asset('front2/assets/images/development.avif')}}" alt="">
              <div class="bg-blur"></div>
            </div>
          </div>
          <div class="card security flex flex-col lg:flex-row items-center">
            <div class="card-content lg:w-1/2 order-2">
              <div class="sub-title mb-3">Security</div>
              <h3 class="mb-4">Enterprise-grade blockchain security built for trust and scalability</h3>
              <p class="mb-4">Enterprise-grade blockchain security delivers advanced cryptographic protection, decentralized architecture, and continuous resilience, safeguarding assets, data, and
                transactions while ensuring trust, compliance, and scalability across the entire Web3 ecosystem.</p>

              <ul class="check-list">
                <li>Seamless Interoperability - Connects smoothly across Web3 platforms, enabling consistent experiences and efficient value exchange.</li>
                <li>Enterprise-Grade Security - Advanced cryptography and decentralized architecture protect assets, data, and transactions at scale.</li>
                <li>Future-Ready Scalability - Built to evolve continuously, supporting innovation, growth, and expanding ecosystem demands over time.</li>
              </ul>

            </div>

            <div class="card-image lg:w-1/2 order-1">
              <img src="{{ asset('front2/assets/images/security.avif')}}" alt="">
              <div class="bg-blur"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="core-features bg-grad-primary my-20 lg:my-28">
      <div class="container mb-12">
        <div class="flex flex-col lg:flex-row items-end justify-end mb-16">
          <div class="lg:w-[45%]">
            <div class="sub-title mb-3 lg:mb-4" data-anim="fade-up">M-Connect Ecosystem</div>
            <h2 data-anim="fade-up" data-delay="0.4">
              <span class="text-white-gradient">One Gateway. Every Meta Experience</span>
            </h2>
          </div>
          <div class="lg:w-[55%] lg:pl-8 pt-4 lg:pt-0">
            <p class="mb-4" data-anim="fade-up" data-delay="0.6">M-Connect is the unified digital ecosystem of Cyera AI — a powerful hub that brings together all Meta decentralized applications in
              one
              seamless environment. Designed as a single entry point, M-Connect allows users to access, manage, and interact with the entire Meta ecosystem without friction, complexity, or multiple
              logins.</p>
            <p data-anim="fade-up" data-delay="0.8">From finance and communication to AI, storage, gaming, and Web3 utilities — M-Connect is where everything connects.</p>
          </div>
        </div>
      </div>
      <div class="container">

        <div class="anim-line one"></div>

        <div class="flex flex-col lg:flex-row">
          <div class="card lg:w-1/2" data-anim="fade-up" data-delay="0">
            <img src="{{ asset('front2/assets/images/feat/wallet.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Wallet</div>
            <h4 class="mb-3 text-white-gradient"><span>Your Digital Asset Vault</span></h4>
            <p>The foundation of the Meta ecosystem. Meta Wallet enables secure digital asset management, transactions, and ecosystem access with enterprise-grade security and full user control. It
              acts
              as
              the coreidentity and transaction layer across all Meta applications.</p>
          </div>
          <div class="card lg:w-1/2" data-anim="fade-up" data-delay="0.2">
            <img src="{{ asset('front2/assets/images/feat/trading.avif')}}" alt="">
            <div class="sub-title mb-2">Cyera AI</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Decentralized Infrastructure</span>
            </h4>
            <p>Cyera AI is a decentralized networking platform designed to help individuals and teams build, manage, and scale their digital income networks. It provides a structured environment
              where
              users can
              connect, refer, collaborate, and track their network growth and earnings transparently within a decentralized framework.</p>
          </div>
        </div>

        <div class="anim-line two"></div>

        <div class="flex flex-col lg:flex-row">
          <div class="card lg:w-2/6" data-anim="fade-up" data-delay="0">
            <img src="{{ asset('front2/assets/images/feat/chat.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Talk</div>
            <h4 class="mb-3 text-white-gradient"><span>Your Digital Asset Vault</span></h4>
            <p>A next-generation decentralized communication platform offering secure messaging, voice, and real-time interaction. Meta Talk is designed for privacy-first conversations within the Meta
              ecosystem.</p>
          </div>
          <div class="card lg:w-2/6" data-anim="fade-up" data-delay="0.4">
            <img src="{{ asset('front2/assets/images/feat/meet.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Meet</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Decentralized Networking</span>
            </h4>
            <p>Decentralized video conferencing built for global collaboration. Meta Meet supports secure virtual meetings, events, leadership summits, and community interactions without reliance on
              centralized
              platforms.</p>
          </div>
          <div class="card lg:w-2/6" data-anim="fade-up" data-delay="0.8">
            <img src="{{ asset('front2/assets/images/feat/ai.avif')}}" alt="">
            <div class="sub-title mb-2">Cyera AI</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Enhanced Assistance</span>
            </h4>
            <p>An intelligent layer powering automation, insights, and decision support across the Meta ecosystem. Cyera AI enhances user experience through smart assistance, analytics, and adaptive
              intelligence</p>
          </div>
        </div>

        <div class="anim-line three" data-anim="fade-up" data-delay="0"></div>

        <div class="flex flex-col lg:flex-row">
          <div class="card lg:w-1/2" data-anim="fade-up" data-delay="0.2">
            <img src="{{ asset('front2/assets/images/feat/play.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Play</div>
            <h4 class="mb-3 text-white-gradient"><span>Decentralized Entertainment</span></h4>
            <p>A decentralized entertainment and gaming platform where digital engagement meets rewards. Meta Play enables immersive experiences while integrating Web3 ownership and ecosystem
              incentives.</p>
          </div>
          <div class="card lg:w-1/2" data-anim="fade-up" data-delay="0.4">
            <img src="{{ asset('front2/assets/images/feat/storage.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Storage</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Decentralized Digital Storage</span>
            </h4>
            <p>Secure, decentralized digital storage designed for privacy, reliability, and ownership. Meta Storage ensures users retain control over their data while accessing scalable cloud-like
              performance.</p>
          </div>
        </div>

        <div class="anim-line one"></div>

        <div class="flex flex-col lg:flex-row">
          <div class="card lg:w-2/6" data-anim="fade-up" data-delay="0.2">
            <img src="{{ asset('front2/assets/images/feat/browser.avif')}}" alt="">
            <div class="sub-title mb-2">Cyera Browser</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Decentralized Browser</span>
            </h4>
            <p>A Web3-enabled browser optimized for decentralized applications, blockchain interactions, and secure digital navigation. Cyera Browser provides seamless access to the decentralized web
              within the Meta
              ecosystem.</p>
          </div>
          <div class="card lg:w-2/6" data-anim="fade-up" data-delay="0.4">
            <img src="{{ asset('front2/assets/images/feat/cloud.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Swap</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Decentralized Exchange Layer</span>
            </h4>
            <p>A decentralized exchange layer enabling seamless asset swaps within the ecosystem. Meta Swap supports secure, transparent, and efficient digital asset conversions without
              intermediaries.
            </p>
          </div>
          <div class="card lg:w-2/6" data-anim="fade-up" data-delay="0.6">
            <img src="{{ asset('front2/assets/images/feat/mail.avif')}}" alt="">
            <div class="sub-title mb-2">Meta Mail</div>
            <h4 class="mb-3 text-white-gradient">
              <span>Decentralized Email</span>
            </h4>
            <p>A decentralized email and communication system designed for data ownership and privacy. Meta Mail removes centralized dependencies while delivering reliable, encrypted digital
              correspondence.</p>
          </div>
        </div>

        <div class="anim-line two"></div>

      </div>
      <div class="container pt-28" data-anim="fade-up" data-delay="0.2">
        <div class="coin-download flex flex-col lg:flex-row items-center justify-center">
          <div class="lg:w-1/2 relative flex flex-col items-center mb-8 lg:mb-0">
            <img src="{{ asset('front2/assets/images/m-connet-1.avif')}}" alt="" class="relative z-20 mx-auto spin-bounce">
            <img src="{{ asset('front2/assets/images/blurred-bg.avif')}}" alt="" class="bg-blur w-[60%] absolute z-10 blur-[100px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
          </div>
          <div class="lg:w-1/2 ">
            <div class="sub-title mb-2">M-Connect</div>
            <h2 class="mb-4 text-center lg:text-left">One Gateway. Infinite Meta Experiences</h2>
            <p class="mb-8 text-center lg:text-left">A unified access point connecting every Meta application, enabling seamless interaction, control, and growth across the ecosystem. </p>
            <a href="#" class="btn btn-primary" data-title="Explore Cyera AI"><span>Download M-Connect</span> <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </section>

    <section class="my-20 lg:my-28">
      <div class="container">
        <div class="CAI-mall-slider">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/phone.avif')}}" alt="">
              <div class="description">
                <h4>CAI FOLD PHONES</h4>
                <h3>Redefining Mobile Technology Through Decentralized Trust</h3>
                <p class="mb-6">The CAI Fold Phone is a flagship real-world device on CAI Mall, combining advanced mobile engineering with decentralized infrastructure. Users can digitally reserve a
                  privacy-focused, next-generation foldable smartphone through a transparent booking process, with verified allocation and global physical delivery.</p>

                <a href="/" target="_blank" class="btn btn-primary">
                  <div class="text">
                    <span class="front">Know more on mwtmall.com</span>
                    <span class="back">Know more on mwtmall.com</span>
                  </div>
                </a>
              </div>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/diamond.avif')}}" alt="">
              <div class="description">
                <h4>CAI DIAMOND</h4>
                <h3>Where Timeless Diamonds Meet Decentralized Trust</h3>
                <p class="mb-6">The CAI Diamond is the flagship real-world asset on CAI Mall combining certified luxury with decentralized infrastructure. Users can digitally reserve an IGI-certified,
                  lab-grown
                  diamond through a transparent, verifiable booking process, with secure allocation and physical delivery.</p>
                <a href="/" target="_blank" class="btn btn-primary">
                  <div class="text">
                    <span class="front">Know more on mwtmall.com</span>
                    <span class="back">Know more on mwtmall.com</span>
                  </div>
                </a>
              </div>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/card.avif')}}" alt="">
              <div class="description">
                <h4>CAI crypto card</h4>
                <h3>Where Digital Value Meets Decentralized Trust</h3>
                <p class="mb-6">The CAI Crypto Card is a flagship payment solution on CAI Mall, combining real-world spending with decentralized infrastructure. Users can seamlessly convert and spend
                  supported digital assets through a transparent, secure payment framework, enabling everyday purchases with global acceptance.</p>
                <a href="/" target="_blank" class="btn btn-primary">
                  <div class="text">
                    <span class="front">Know more on mwtmall.com</span>
                    <span class="back">Know more on mwtmall.com</span>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>

    <section class="CAI-coins bg-grad-primary my-20 lg:my-28">
      <div class="container">
        <div class="flex flex-col justify-center items-center pb-16">
          <div class="lg:w-2/3 mx-auto">
            <div data-anim="fade-up">
              <div class="sub-title text-center mb-4 w-fit mx-auto">CAI Coin</div>
            </div>
            <div data-anim="fade-up" data-delay="0.2">
              <h2 class="text-center text-white-gradient">
                <span class="block">Utility-Driven Foundation of the Cyera AI Ecosystem</span>
              </h2>
            </div>
          </div>
        </div>
      </div>
      <div class="container overflow-hidden" data-anim="fade-up" data-delay="0.4">
        <div class="CAI-coins-swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/CAI-coins/icon-1.avif')}}" alt="" class="mb-6">
              <h4 class="text-white-gradient mb-4  w-fit">Decentralized Value Layer</h4>
              <p class="mb-4">CAI Coin is Cyera AI's native utility token enabling secure transactions, active participation, and scalable decentralized value exchange, engineered for sustainable
                adoption, transparency, and
                long-term utility.</p>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/CAI-coins/icon-2.avif')}}" alt="" class="mb-6">
              <h4 class="text-white-gradient mb-4  w-fit">Purpose-Built Utility</h4>
              <p class="mb-4">CAI Coin powers the Cyera AI network by enabling value transfer, platform access, rewards, staking, and governance—strengthening ecosystem stability while giving users
                ownership, transparency, and control.</p>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/CAI-coins/icon-3.avif')}}" alt="" class="mb-6">
              <h4 class="text-white-gradient mb-4  w-fit">Market Reach & Accessibility</h4>
              <p class="mb-4">CAI Coin is listed on CoinMarketCap and traded on DexTrade, delivering global visibility, real-time pricing, open access, and trusted transparency for worldwide
                participation.</p>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/CAI-coins/icon-4.avif')}}" alt="" class="mb-6">
              <h4 class="text-white-gradient mb-4  w-fit">Token Structure</h4>
              <p class="mb-4">CAI features 18-decimal precision for micro-transactions, with a fixed 2.10 crore supply ensuring controlled issuance, predictable economics, scalability, and long-term
                network sustainability.</p>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/CAI-coins/icon-6.avif')}}" alt="" class="mb-6">
              <h4 class="text-white-gradient mb-4  w-fit">Tokenomics Overview</h4>
              <p class="mb-4">CAI uses a transparent allocation model supporting staking rewards, ecosystem growth, strategic reserves, contributor incentives, public participation, social impact
                initiatives, and long-term network resilience.</p>
            </div>
            <div class="swiper-slide">
              <img src="{{ asset('front2/assets/images/CAI-coins/icon-5.avif')}}" alt="" class="mb-6">
              <h4 class="text-white-gradient mb-4  w-fit">Long-Term Vision</h4>
              <p class="mb-4">CAI Coin is a utility-first digital asset enabling real usage, decentralized participation, trust, transparency, and sustainable long-term value within Cyera AI’s
                decentralized ecosystem.</p>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>

    <section class="my-20 lg:my-24">
      <div class="container">
        <div class="flex flex-col text-center lg:w-[65%] mx-auto ">
          <h2 class="text-center mb-5" data-anim="fade-up" data-delay="0.2">
            <span class="text-white-gradient block">Decentralized Trading Platforms Built for Global Markets</span>
          </h2>
        </div>
        <div class="tab-container flex flex-col justify-center" data-anim="fade-up" data-delay="0.4">
          <div class="tab-nav">
            <a href="#MetaX" data-tab="MetaX" class="active">MetaX</a>
            <a href="#MetaFX" data-tab="MetaFX">MetaFX</a>
            <div class="nav-indicator"></div>
          </div>
          <div id="MetaX" class="tab-content active">
            <div class="card integration flex flex-col lg:flex-row items-center">
              <div class="card-image lg:w-1/2">
                <img src="{{ asset('front2/assets/images/integrations.avif')}}" alt="">
                <div class="bg-blur"></div>
              </div>
              <div class="card-content lg:w-1/2">
                <div class="sub-title mb-3">MetaX</div>
                <h3 class="mb-4">Decentralized Crypto Exchange</h3>
                <p class="mb-4">MetaX is Cyera AI's decentralized crypto exchange built for secure, transparent, and intermediary-free digital asset trading. It enables users to trade crypto assets
                  directly from their wallets with full ownership, on-chain transparency, and advanced trading infrastructure.</p>

                <p class="mb-4">MetaX delivers speed, security, and liquidity without compromising decentralization.</p>

                <ul class="check-list">
                  <li>Trade directly from wallets with full ownership and on-chain transparency.</li>
                  <li>Fast, secure transactions powered by decentralized, intermediary-free trading infrastructure.</li>
                  <li>High liquidity and performance without compromising decentralization or user control.</li>
                </ul>
              </div>
            </div>
          </div>
          <div id="MetaFX" class="tab-content">
            <div class="card development flex flex-col lg:flex-row items-center">
              <div class="card-image lg:w-1/2">
                <img src="{{ asset('front2/assets/images/development.avif')}}" alt="">
                <div class="bg-blur"></div>
              </div>
              <div class="card-content lg:w-1/2">
                <div class="sub-title mb-3">MetaFX</div>
                <h3 class="mb-4">Decentralized Foreign Exchange Trading Platform</h3>
                <p class="mb-6">MetaFX is the world's first decentralized foreign exchange trading platform, designed to enable transparent, borderless currency trading powered by blockchain
                  technology.
                  It removes traditional intermediaries while offering users direct access to global FX markets through a secure and decentralized framework.</p>
                <ul class="check-list">
                  <li>True Decentralization - Eliminates traditional intermediaries, giving users direct control over currency trading without centralized restrictions or manipulation.</li>
                  <li>Transparent & Trustless Trading - All transactions are recorded on-chain, ensuring full transparency, verifiable execution, and fair market participation.</li>
                  <li>Borderless Global Access - Enables seamless access to global FX markets anytime, anywhere, without geographic limitations or legacy banking barriers.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



    <section class="testimonial-wrapper lg:mt-16 lg:py-16">
      <div class="container">
        <div class="lg:w-[60%] mx-auto flex flex-col items-center mb-16 intro-text">
          <div data-anim="fade-up">
            <div class="sub-title mb-2">Cyera AI Stories</div>
          </div>
          <h2 class="text-center" data-anim="fade-up" data-delay="0.2">
            <div class="block text-white-gradient">Voices of Trust from the Cyera AI Community</div>
          </h2>
        </div>

        <div class="testimonial-swiper" data-anim="fade-up" data-delay="0.2">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <p class="quote">“As a blockchain developer, I found Cyera AI's ecosystem incredibly smooth to integrate with. From Cyera Cloud to Meta Swap, everything runs seamlessly and supports
                multi-chain operations.”</p>
              <div class="user">
                <div class="user-pic">
                  <img src="{{ asset('front2/assets/images/testimonials/153.webp')}}" alt="">
                </div>
                <div class="user-info">
                  <div class="user-info-name">Rachel T</div>
                  <div class="user-info-desc">Blockchain Developer, Singapore</div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <p class="quote">“We are building an NFT marketplace, and Cyera AI's tools saved us months of development. The decentralized storage and transaction speed are game changers for our
                users.”</p>
              <div class="user">
                <div class="user-pic">
                  <img src="{{ asset('front2/assets/images/testimonials/148.webp')}}" alt="">
                </div>
                <div class="user-info">
                  <div class="user-info-name">Kelvin Y</div>
                  <div class="user-info-desc">NFT Entrepreneur, Singapore</div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <p class="quote">“Cyera AI provided exactly what our DAO community needed — transparent governance tools and reliable smart contract execution. It has simplified onboarding and
                boosted
                engagement.”</p>
              <div class="user">
                <div class="user-pic">
                  <img src="{{ asset('front2/assets/images/testimonials/143.webp')}}" alt="">
                </div>
                <div class="user-info">
                  <div class="user-info-name">Amanda C</div>
                  <div class="user-info-desc">DAO Manager, Singapore</div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <p class="quote">“For our Metaverse project, Cyera AI has been a perfect partner. The decentralized browser and wallet integrations give our users privacy, freedom, and an immersive
                experience.”</p>
              <div class="user">
                <div class="user-pic">
                  <img src="{{ asset('front2/assets/images/testimonials/138.webp')}}" alt="">
                </div>
                <div class="user-info">
                  <div class="user-info-name">Wei Jian H</div>
                  <div class="user-info-desc">Metaverse Founder, Singapore</div>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <p class="quote">“We wanted a futuristic and immersive design for our Metaverse project, and this template delivered beyond expectations. The Web3 integrations, stunning visuals, and
                lightning-fast performance make it stand out. Our community loves the experience, and we're proud to build on this foundation!"</p>
              <div class="user">
                <div class="user-pic">
                  <img src="{{ asset('front2/assets/images/testimonials/133.webp')}}" alt="">
                </div>
                <div class="user-info">
                  <div class="user-info-name">Ryan T</div>
                  <div class="user-info-desc">Metaverse Founder</div>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
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