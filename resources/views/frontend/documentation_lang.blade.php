@extends('frontend.layouts.app')

@section('title', 'Cyera AI Documentation - Multi Language')
@section('meta_description', 'Download and view Cyera AI documentation in multiple languages.')
@section('meta_keywords', 'Cyera AI docs, Web3 documentation, blockchain pdf, whitepaper')

@section('content')

@php
  // ✅ Languages Map (URL slug => Full Name)
  $languages = [
      'english' => 'English',
      'hindi'   => 'Hindi',
      'spanish' => 'Spanish',
      'french'  => 'French',
      'german'  => 'German',
  ];

  // ✅ Detect language from URL (like /documentation/english)
  $currentLangSlug = request()->segment(2); 
  $currentLang = $languages[$currentLangSlug] ?? 'English'; 
  $langCode = substr($currentLang, 0, 2); // en, hi, es, etc.

  // ✅ Documents List
  $documents = [
      ['title' => 'Company Document', 'file' => 'companydocument'],
      ['title' => 'One Paper', 'file' => 'onepaper'],
      ['title' => 'White Paper', 'file' => 'whitepaper'],
      ['title' => 'Term To Sell', 'file' => 'termtosell'],
      ['title' => 'Business Plan', 'file' => 'businessplan'],
      ['title' => 'Ecosystem', 'file' => 'ecosystem'],
      ['title' => 'Technology Info', 'file' => 'technologyinfo'],
      ['title' => 'Founder Vision', 'file' => 'foundervision'],
      ['title' => 'Project Explainer', 'file' => 'projectexplainer'],
  ];
@endphp

<section class="section inner-banner">
  <div class="container">
    <div class="inner-banner-typography text-center">
      <h1 class="inner-banner-title">Cyera AI Documentation</h1>
      <p class="inner-banner-description">Language: <b>{{ $currentLang }}</b></p>
    </div>
  </div>
</section>

<section class="section development-section">
  <div class="container">
    <div class="development-wrapper">
      <div class="w-layout-grid development-grid">

        <!-- ✅ Loop Documents -->
        @foreach($documents as $index => $doc)
          @php
            $filePath = 'main/assets/documentation/'.$doc['file'].'_'.$langCode.'.pdf';
          @endphp

          <div class="core-features-slider-card">
            <div class="core-features-slider-card-image-wrap">
              <img src="{{ asset('main/assets/images/pdf-icon.png') }}" alt="PDF" class="core-features-slider-card-image"/>
            </div>
            <div class="core-features-card-title-description">
              <h4 class="core-features-card-title">{{ $doc['title'] }}</h4>

              <!-- ✅ View & Download Buttons -->
              <div class="primary-button-wrap" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                <!-- View in PDF Viewer -->
                <a href="{{ url('frontend.viewer?file='.urlencode($filePath)) }}" 
                   style="all: unset; cursor: pointer; display: inline-block; position: relative;">
                  <div class="primary-button">View</div>
                  <div class="primary-button-hover-bg"></div>
                </a>

                <!-- Direct Download -->
                <a href="{{ asset($filePath) }}" download
                   style="all: unset; cursor: pointer; display: inline-block; position: relative;">
                  <div class="primary-button">Download</div>
                  <div class="primary-button-hover-bg"></div>
                </a>
              </div>
            </div>
            <div class="core-features-slider-card-number">[ {{ sprintf("%02d", $index+1) }} ]</div>
          </div>
        @endforeach

      </div>
    </div>
  </div>
</section>

@endsection
