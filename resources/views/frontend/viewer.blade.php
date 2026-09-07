@extends('frontend.layouts.app')

@section('title', 'View PDF - Cyera AI')
@section('content')

@php
  $file = request()->get('file');
@endphp

<section class="section">
  <div class="container text-center">
    <h2 class="mb-4">PDF Viewer</h2>

    <!-- ✅ Responsive PDF Viewer -->
    <div style="width:100%; height:80vh; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
      <iframe src="{{ asset($file) }}" width="100%" height="100%" style="border:none;"></iframe>
    </div>

    <div class="mt-4">
      <a href="{{ asset($file) }}" download class="primary-button">Download PDF</a>
    </div>
  </div>
</section>

@endsection
