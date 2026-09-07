@extends('frontend.layouts.app')

{{-- ✅ Page Title --}}
@section('title', 'Meta Swap - Decentralized Token Swapping Platform')

{{-- ✅ SEO Meta Tags --}}
@section('meta_description', 'Meta Swap is a decentralized exchange (DEX) that lets you instantly swap tokens with low fees and full transparency. Trade securely with blockchain technology.')
@section('meta_keywords', 'Meta Swap, decentralized exchange, DEX, crypto swap, token swap, DeFi, blockchain trading, instant exchange')

{{-- ✅ Open Graph (for Facebook/LinkedIn) --}}
@section('og_title', 'Meta Swap - Decentralized Token Swapping Platform')
@section('og_description', 'Swap tokens instantly and securely on Meta Swap. Enjoy low fees, transparency, and full control of your assets.')

{{-- ✅ Twitter Card --}}
@section('twitter_title', 'Meta Swap - Decentralized Token Swapping Platform')
@section('twitter_description', 'Trade smarter with Meta Swap – the decentralized exchange for instant, secure, and low-fee token swaps.')

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
                                <div class="inner-banner-subtitle">About Meta Swap</div>
                            </div>
          <div class="inner-banner-title-wrapper">
             <h1 class="inner-banner-title about-us">
                                    Meta Swap – Decentralized & Web3 Powered Exchange
                                </h1>
                                <p class="inner-banner-description about-us">
                                    Meta Swap is a Web3-based decentralized exchange (DEX) that allows users to securely swap, trade, and provide liquidity for crypto tokens without centralized intermediaries.  
                                    Powered by smart contracts and blockchain verification, Meta Swap ensures transparent, trustless, and fast token transactions.
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
                <div class="about-us-gallery-image-wrapper"><img src="{{ asset('main/assets/images/metaswapbanner1.png') }}" loading="lazy" alt="" class="about-us-gallery-image left" /><img src="{{ asset('main/assets/images/metaswapbanner2.png') }}"
                    loading="lazy" sizes="(max-width: 840px) 100vw, 840px" srcset="{{ asset('main/assets/images/metaswapbanner2.png') }} 500w, {{ asset('main/assets/images/metaswapbanner2.png') }} 800w,{{ asset('main/assets/images/metaswapbanner2.png') }} 840w"
                    alt="" class="about-us-gallery-image middle" /><img src="{{ asset('main/assets/images/metaswapbanner1.png') }}" loading="lazy" alt="" class="about-us-gallery-image right"
                    /></div>
               <div class="about-us-gallery-description-wrapper">
        <p class="about-us-gallery-description-text">
                            Meta Swap enables seamless token swaps and liquidity provision, empowering users with decentralized, trustless, and blockchain-verified trading experiences.
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
                                        <img sizes="100vw" src="{{ asset('main/assets/images/metaswap1.png') }}" alt="Meta Swap Dashboard" loading="lazy" class="history-image" />
                                    </div>
                                </div>
                                <div class="split-wrapper">
                                    <div class="history-content-wrap">
                                        <div class="history-year">(Dashboard)</div>
                                        <div class="history-details-wrap">
                                            <h2 class="history-title">Dashboard</h2>
                                            <p class="history-details">
                                                View your token balances, active pools, and swap history in an intuitive decentralized interface.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Swap Tokens -->
                            <div class="single-history-wrap reverse">
                                <div class="split-wrapper">
                                    <div class="history-content-wrap align-right">
                                        <div class="history-year">(Swap)</div>
                                        <div class="history-details-wrap">
                                            <h2 class="history-title">Swap Tokens</h2>
                                            <p class="history-details">
                                                Instantly swap crypto tokens with smart contract-powered liquidity pools — fast, secure, and without intermediaries.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="split-wrapper">
                                    <div class="history-image-wrap">
                                        <img sizes="100vw" src="{{ asset('main/assets/images/metaswap2.png') }}" alt="Meta Swap Tokens" loading="lazy" class="history-image" />
                                    </div>
                                </div>
                            </div>

                            <!-- Liquidity & Analytics -->
                            <div class="single-history-wrap">
                                <div class="split-wrapper">
                                    <div class="history-image-wrap">
                                        <img sizes="100vw" src="{{ asset('main/assets/images/metaswap3.png') }}" alt="Meta Swap Liquidity" loading="lazy" class="history-image" />
                                    </div>
                                </div>
                                <div class="split-wrapper">
                                    <div class="history-content-wrap">
                                        <div class="history-year">(Liquidity)</div>
                                        <div class="history-details-wrap">
                                            <h2 class="history-title">Liquidity & Analytics</h2>
                                            <p class="history-details">
                                                Add liquidity to pools, track your earnings, and monitor analytics — all on-chain and decentralized.
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
   
