@extends('frontend.layouts.app')

{{-- ✅ Page Title --}}
@section('title', 'Cyera Chat - Secure Decentralized Messaging App')

{{-- ✅ SEO Meta Tags --}}
@section('meta_description', 'Cyera Chat is a decentralized, blockchain-powered chat application that ensures privacy, security, and true ownership of your conversations.')
@section('meta_keywords', 'Cyera Chat, decentralized chat, blockchain messaging, secure chat, Web3 chat, private messaging, crypto communication, Cyera AI')

{{-- ✅ Open Graph (for Facebook/LinkedIn) --}}
@section('og_title', 'Cyera Chat - Private & Secure Decentralized Messaging')
@section('og_description', 'Experience next-generation communication with Cyera Chat – a Web3 messaging app built on blockchain for privacy, security, and complete freedom.')

{{-- ✅ Twitter Card --}}
@section('twitter_title', 'Cyera Chat - Private & Secure Decentralized Messaging')
@section('twitter_description', 'Chat securely and freely with Cyera Chat – a blockchain-based decentralized messaging platform that puts your privacy first.')

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
                                <div class="inner-banner-subtitle">About Cyera Chat</div>
                            </div>
          <div class="inner-banner-title-wrapper">
              <h1 class="inner-banner-title about-us">
                                    Cyera Chat – Decentralized, Secure Messaging
                                </h1>
                                <p class="inner-banner-description about-us">
                                    Cyera Chat is a Web3-based decentralized messaging platform, providing secure, end-to-end encrypted conversations.  
                                    Communicate with friends, communities, and businesses without centralized servers, ensuring privacy, trustless communication, and blockchain verification for all messages.
                                </p>

          </div>
        </div>
      </div>

      <!-- Decorative Icons -->
      <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon top-left" />
      <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon top-right" />
      <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon bottom-left" />
      <img src="https://cdn.prod.website-files.com/67d5482886ed2b38b65e6d7c/67d658b33ed28aa00cc36ea1_Banner%20Pluse%20Icon.svg" loading="lazy" alt="" class="inner-banner-pluse-icon bottom-right" />
    </div>
  </div>
</section>

    <section class="section about-us-gallery">
        <div class="about-us-gallery-container">
            <div data-w-id="b5b2be2c-67e6-7ba9-6d32-f33422506a58" style="opacity:0" class="about-us-gallery-wrapper">
                <div class="about-us-gallery-image-wrapper"><img src="{{ asset('main/assets/images/cyerachatbanner1.png') }}" loading="lazy" alt="" class="about-us-gallery-image left" /><img src="{{ asset('main/assets/images/cyerachatbanner3.png') }}"
                    loading="lazy" sizes="(max-width: 840px) 100vw, 840px" srcset="{{ asset('main/assets/images/cyerachatbanner3.png') }} 500w, {{ asset('main/assets/images/cyerachatbanner3.png') }} 800w, {{ asset('main/assets/images/cyerachatbanner3.png') }} 840w"
                    alt="" class="about-us-gallery-image middle" /><img src="{{ asset('main/assets/images/cyerachatbanner1.png') }}" loading="lazy" alt="" class="about-us-gallery-image right"
                    /></div>
               <div class="about-us-gallery-description-wrapper">
       <p class="about-us-gallery-description-text">
                            Cyera Chat provides decentralized, encrypted messaging that ensures privacy, security, and trustless communication. All messages are verified on the blockchain, preventing tampering and central control.
                        </p>
</div>

            </div>
        </div>
    </section>
   
        <section class="section history">
            <div class="container">
                <div class="history-container">
                    <div class="history-wrapper">
                        <div class="histories-flex-wrap">

                            <!-- Dashboard UI -->
                            <div class="single-history-wrap">
                                <div class="split-wrapper">
                                    <div class="history-image-wrap">
                                        <img sizes="100vw" src="{{ asset('main/assets/images/cyerachat1.png') }}" alt="Cyera Chat Dashboard" loading="lazy" class="history-image" />
                                    </div>
                                </div>
                                <div class="split-wrapper">
                                    <div class="history-content-wrap">
                                        <div class="history-year">(Dashboard)</div>
                                        <div class="history-details-wrap">
                                            <h2 class="history-title">Dashboard</h2>
                                            <p class="history-details">
                                                View your chat rooms, active conversations, and contacts in a clean, decentralized interface.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Rooms -->
                            <div class="single-history-wrap reverse">
                                <div class="split-wrapper">
                                    <div class="history-content-wrap align-right">
                                        <div class="history-year">(Chat)</div>
                                        <div class="history-details-wrap">
                                            <h2 class="history-title">Secure Messaging</h2>
                                            <p class="history-details">
                                                Send and receive messages with end-to-end encryption. Every message is verified on the blockchain for trustless communication.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="split-wrapper">
                                    <div class="history-image-wrap">
                                        <img sizes="100vw" src="{{ asset('main/assets/images/cyerachat2.png') }}" alt="Cyera Chat Messaging" loading="lazy" class="history-image" />
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy & Security -->
                            <div class="single-history-wrap">
                                <div class="split-wrapper">
                                    <div class="history-image-wrap">
                                        <img sizes="100vw" src="{{ asset('main/assets/images/cyerachat3.png') }}" alt="Cyera Chat Security" loading="lazy" class="history-image" />
                                    </div>
                                </div>
                                <div class="split-wrapper">
                                    <div class="history-content-wrap">
                                        <div class="history-year">(Privacy)</div>
                                        <div class="history-details-wrap">
                                            <h2 class="history-title">Privacy & Encryption</h2>
                                            <p class="history-details">
                                                Messages are fully encrypted, stored securely on a decentralized network, and free from central server control.
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
   
