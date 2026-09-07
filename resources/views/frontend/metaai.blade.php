@extends('frontend.layouts.app')

{{-- ✅ Page Title --}}
@section('title', 'Cyera AI - Decentralized Web3 AI | Cyera AI')

{{-- ✅ SEO Meta Tags --}}
@section('meta_description', 'Cyera AI is a decentralized Web3-based artificial intelligence platform, delivering secure, transparent, and community-driven AI solutions for the future of digital intelligence.')
@section('meta_keywords', 'Cyera AI, decentralized AI, Web3 AI, blockchain AI, secure AI, private AI, Cyera AI tools')

{{-- ✅ Open Graph (for Facebook/LinkedIn) --}}
@section('og_title', 'Cyera AI - Decentralized Web3 AI')
@section('og_description', 'Cyera AI empowers users with decentralized, transparent, and blockchain-secured artificial intelligence — reshaping the future of digital intelligence.')

{{-- ✅ Twitter Card --}}
@section('twitter_title', 'Cyera AI | Cyera AI')
@section('twitter_description', 'Cyera AI brings you decentralized Web3 intelligence — private, secure, and community-driven AI for the next era of technology.')

{{-- ✅ Page Content --}}
@section('content') 
<!DOCTYPE html>
<html data-wf-domain="zocor.webflow.io" data-wf-page="67e8c68d888006cd08bbc8c5" data-wf-site="67d5482886ed2b38b65e6d7c" data-wf-status="1" lang="en">
 <link href="{{ asset('main/assets/images/fav.png') }}" rel="shortcut icon" type="image/x-icon"/>
    <link href="{{ asset('main/assets/images/fav.png') }}" rel="apple-touch-icon"/>


<body data-w-id="67e8c68d888006cd08bbc8ce" style="opacity:0">
    <div class="page-wrapper">
      
   <section class="section inner-banner">
      <div class="inner-banner-content">
         <div class="inner-banner-wrapper">
            <div class="container">
               <div class="inner-banner-typography">
                  <div class="inner-banner-subtitle-wrap">
                     <div class="inner-banner-subtitle">About Cyera AI</div>
                  </div>
                  <div class="inner-banner-title-wrapper">
                     <h1 class="inner-banner-title about-us">
                        Cyera AI – Decentralized, Intelligent, & Web3-Powered
                     </h1>
                     <p class="inner-banner-description about-us">
                        Cyera AI is a decentralized Web3-based AI platform designed to bring intelligence without central control.  
                        From blockchain-powered learning models to secure, community-driven algorithms, Cyera AI delivers transparency, fairness, and trust in artificial intelligence.
                     </p>
                  </div>
               </div>
            </div>

            <!-- Decorative Icons (unchanged) -->
            <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon top-left" />
            <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon top-right" />
            <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon bottom-left" />
            <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon bottom-right" />
         </div>
      </div>
   </section>

   <!-- About Section -->
   <section class="section about-us-gallery">
      <div class="about-us-gallery-container">
         <div data-w-id="b5b2be2c-67e6-7ba9-6d32-f33422506a58" style="opacity:0" class="about-us-gallery-wrapper">
            <div class="about-us-gallery-image-wrapper">
               <img src="{{ asset('main/assets/images/cyerabrowserbanner1.png') }}" loading="lazy" alt="" class="about-us-gallery-image left" />
               <img src="{{ asset('main/assets/images/cyerabrowserbanner2.png') }}" loading="lazy" sizes="(max-width: 840px) 100vw, 840px"
                  srcset="{{ asset('main/assets/images/cyerabrowserbanner2.png') }} 500w, {{ asset('main/assets/images/cyerabrowserbanner2.png') }} 800w, {{ asset('main/assets/images/cyerabrowserbanner2.png') }} 840w"
                  alt="" class="about-us-gallery-image middle" />
               <img src="{{ asset('main/assets/images/cyerabrowserbanner1.png') }}" loading="lazy" alt="" class="about-us-gallery-image right"/>
            </div>
            <div class="about-us-gallery-description-wrapper">
               <p class="about-us-gallery-description-text">
                  Cyera AI combines decentralized infrastructure with advanced AI models — enabling private, secure, and transparent intelligence solutions for enterprises, developers, and individuals.
               </p>
            </div>
         </div>
      </div>
   </section>
   
   <!-- Features Section -->
   <section class="section history">
      <div class="container">
         <div class="history-container">
            <div class="history-wrapper">
               <div class="histories-flex-wrap">

                  <!-- AI Dashboard -->
                  <div class="single-history-wrap">
                     <div class="split-wrapper">
                        <div class="history-image-wrap">
                           <img sizes="100vw" src="{{ asset('main/assets/images/cyerabrowser1.png') }}" alt="Cyera AI Dashboard" loading="lazy" class="history-image" />
                        </div>
                     </div>
                     <div class="split-wrapper">
                        <div class="history-content-wrap">
                           <div class="history-year">(Dashboard)</div>
                           <div class="history-details-wrap">
                              <h2 class="history-title">AI Dashboard</h2>
                              <p class="history-details">
                                 Manage decentralized AI tools, models, and analytics in one unified, blockchain-powered dashboard.
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- AI Models -->
                  <div class="single-history-wrap reverse">
                     <div class="split-wrapper">
                        <div class="history-content-wrap align-right">
                           <div class="history-year">(Models)</div>
                           <div class="history-details-wrap">
                              <h2 class="history-title">Decentralized AI Models</h2>
                              <p class="history-details">
                                 Access AI models hosted on decentralized networks, ensuring fairness, transparency, and censorship resistance.
                              </p>
                           </div>
                        </div>
                     </div>
                     <div class="split-wrapper">
                        <div class="history-image-wrap">
                           <img sizes="100vw" src="{{ asset('main/assets/images/cyerabrowser2.png') }}" alt="Cyera AI Models" loading="lazy" class="history-image" />
                        </div>
                     </div>
                  </div>

                  <!-- Privacy & Security -->
                  <div class="single-history-wrap">
                     <div class="split-wrapper">
                        <div class="history-image-wrap">
                           <img sizes="100vw" src="{{ asset('main/assets/images/cyerabrowser3.png') }}" alt="Cyera AI Security" loading="lazy" class="history-image" />
                        </div>
                     </div>
                     <div class="split-wrapper">
                        <div class="history-content-wrap">
                           <div class="history-year">(Security)</div>
                           <div class="history-details-wrap">
                              <h2 class="history-title">Privacy & Security</h2>
                              <p class="history-details">
                                 Protect your data with end-to-end encryption and blockchain verification — giving you full control over how AI uses your information.
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>

               </div>

               <!-- Divider -->
               <div class="history-divider-wrap">
                  <div class="history-star-wrap">
                     <img loading="lazy" src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67f28907900a9acb78b0b2fc_History%20Star.svg" alt="" class="history-star" />
                  </div>
                  <div class="history-divider-line"></div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- CTA Section -->
   <section data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d72e" class="section cta">
      <div class="container">
         <div class="cta-content">
            <div data-w-id="1c3f6ead-2444-a223-fec9-b2e0c543d731" class="cta-typography">
               <div class="cta-title-description">
                  <h2 class="cta-title">Decentralized Intelligence for Everyone</h2>
                  <p class="cta-description-text">Cyera AI is revolutionizing artificial intelligence with Web3 — secure, transparent, and user-controlled.</p>
               </div>
               <div class="cta-button-wrapper">
                  <div class="primary-button-wrapper">
                     <a href="/register" class="inner-button w-inline-block">
                        <div class="primary-button-border-wrap">
                           <div class="inner-button-wrap">
                              <div class="inner-button-text-wrap">
                                 <div class="inner-button-text">Get Started</div>
                                 <div class="inner-button-hover-text">Get Started</div>
                              </div>
                              <div class="inner-button-star-wrap">
                                 <img loading="lazy" src="{{ asset('main/assets/images/73.svg') }}" alt="" class="inner-button-star _1"/>
                                 <img loading="lazy" src="{{ asset('main/assets/images/72.svg') }}" alt="" class="inner-button-star _2"/>
                                 <img loading="lazy" src="{{ asset('main/assets/images/71.svg') }}" alt="" class="inner-button-star _3"/>
                              </div>
                              <div class="inner-button-hover-bg"></div>
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
            <img src="{{ asset('main/assets/images/66.webp') }}" loading="lazy" sizes="100vw" alt="" class="cta-shape"/>
            <img src="{{ asset('main/assets/images/ctaside.webp') }}" loading="lazy" alt="" class="cta-elment-one"/>
            <img src="{{ asset('main/assets/images/70.webp') }}" loading="lazy" alt="" class="cta-elment-two"/>
         </div>
      </div>
   </section>

  
    </div>
   
