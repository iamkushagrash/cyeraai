<!doctype html>
<html data-wf-page="67d54e44958467faecf80884" 
      data-wf-site="67d5482886ed2b38b65e6d3d" 
      data-wf-status="1" lang="en">
      

<head>
  <meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>@yield('title', 'Cyera AI - Decentralized Wealth Platform')</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- ✅ Basic SEO -->
<meta name="description" content="@yield('meta_description', 'Cyera AI is a decentralized finance and blockchain-powered wealth management platform.')">
<meta name="keywords" content="@yield('meta_keywords', 'Cyera AI, blockchain, crypto, DeFi, investments, decentralized finance, wealth management, trading')">
<meta name="author" content="Cyera AI">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}">

<!-- ✅ Open Graph (Facebook / LinkedIn) -->
<meta property="og:title" content="@yield('og_title', 'Cyera AI - Decentralized Wealth Platform')">
<meta property="og:description" content="@yield('og_description', 'Invest, trade, and grow wealth securely on Cyera AI - a decentralized blockchain-powered platform.')">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('main/assets/images/og-image.png') }}">
<meta property="og:site_name" content="Cyera AI">

<!-- ✅ Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('twitter_title', 'Cyera AI - Decentralized Wealth Platform')">
<meta name="twitter:description" content="@yield('twitter_description', 'Invest, trade, and grow wealth securely on Cyera AI.')">
<meta name="twitter:image" content="{{ asset('main/assets/images/og-image.png') }}">
<meta name="twitter:site" content="@cyera">

<!-- ✅ Favicon -->
<link rel="icon" href="{{ asset('main/assets/images/fav.png') }}" type="image/x-icon">
<link rel="apple-touch-icon" href="{{ asset('main/assets/images/fav.png') }}">


<!-- ✅ Structured Data (JSON-LD for Google Rich Results) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Cyera AI",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('main/assets/images/logo.png') }}",
  "sameAs": [
    "https://www.facebook.com/cyera",
    "https://twitter.com/cyera",
    "https://www.linkedin.com/company/cyera",
    "https://www.instagram.com/cyera"
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "url": "{{ url('/') }}",
  "name": "Cyera AI",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/') }}/search?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>


    <!-- Styles -->
    <link href="{{ asset('main/assets/css/main.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous"/>

    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: [
                    "Funnel Display:300,regular,500,600,700,800",
                    "Host Grotesk:300,regular,500,600,700,800",
                    "Playfair Display:regular,500,600,700,800,900,italic"
                ]
            }
        });
    </script>

    <script type="text/javascript">
        !function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js",
            ("ontouchstart" in o || (o.DocumentTouch && c instanceof DocumentTouch)) &&
                (n.className += t + "touch")
        }(window, document);
    </script>

    <link href="{{ asset('main/assets/images/fav.png') }}" rel="shortcut icon" type="image/x-icon"/>
    <link href="{{ asset('main/assets/images/fav.png') }}" rel="apple-touch-icon"/>

   

    @stack('styles')
</head>

<body data-w-id="67d54e44958467faecf80889" style="opacity:0">
    <div class="page-wrapper">

        {{-- Header --}}
        @include('frontend.layouts.header')

        {{-- Page Content --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('frontend.layouts.footer')

    </div>

    <!-- Scripts -->
    @stack('scripts')

    <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=67d5482886ed2b38b65e6d3d"
            type="text/javascript"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script src="{{ asset('main/assets/js/webflow.schunk.9d4f303b7d548d30.js') }}" type="text/javascript"></script>
    <script src="{{ asset('main/assets/js/webflow.schunk.63029a2fffc02463.js') }}" type="text/javascript"></script>
    <script src="{{ asset('main/assets/js/webflow.3f26ac81.4985be526892fb59.js') }}" type="text/javascript"></script>
</body>
</html>
