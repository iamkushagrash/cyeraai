@extends('frontend.layouts.app')

@section('title', 'Cyera AI Documentation - Multi Language Resources')
@section('meta_description', 'Download Cyera AI documentation in multiple languages including whitepapers, guides, and ecosystem reports.')
@section('meta_keywords', 'Cyera AI documentation, multi-language docs, Cyera AI whitepaper, blockchain docs, Web3 documentation')

@section('content')

<section class="section inner-banner">
    <div class="inner-banner-content">
        <div class="inner-banner-wrapper">
            <div class="container">
                <div class="inner-banner-typography text-center">
                    <div class="inner-banner-subtitle-wrap">
                        <div class="inner-banner-subtitle">Documentation</div>
                    </div>
                    <div class="inner-banner-title-wrapper">
                        <h1 class="inner-banner-title technology">Cyera AI Documentation (Multi-Language)</h1>
                        <p class="inner-banner-description">Choose your preferred language to download Cyera AI resources.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section development-section">
    <div class="container">
        <div class="development-wrapper">

            <!-- Section Title -->
            <div class="core-features-section-title-wrapper text-center mb-4">
                <h2 class="section-title core-features">Select or Speak Your Language</h2>
            </div>

            <!-- 🔎 Fancy Search Bar with Voice -->
            <div class="text-center mb-5">
                <div style="max-width:500px;margin:0 auto;position:relative;display:flex;align-items:center;gap:10px;">
                    <input type="text" id="languageSearch" 
                           class="form-control language-search" 
                           placeholder="🔎 Search your language... (e.g. Hindi, English)">
                    <button id="voiceSearchBtn" class="voice-btn" title="Search by Voice">
                        🎤
                    </button>
                </div>
                <p id="voiceStatus" style="margin-top:8px;color:#3f60a0;font-size:14px;"></p>
            </div>

            <!-- Languages Grid -->
            <div class="w-layout-grid development-grid" id="languageGrid">

                @php
                $languages = [
                    'English' => '🇬🇧',
                    'Hindi' => '🇮🇳',
                    'Spanish' => '🇪🇸',
                    'French' => '🇫🇷',
                    'German' => '🇩🇪',
                    'Chinese' => '🇨🇳',
                    'Japanese' => '🇯🇵',
                    'Korean' => '🇰🇷',
                    'Arabic' => '🇸🇦',
                    'Russian' => '🇷🇺',
                    'Portuguese' => '🇵🇹',
                    'Italian' => '🇮🇹',
                    'Bengali' => '🇧🇩',
                    'Turkish' => '🇹🇷',
                    'Vietnamese' => '🇻🇳',
                    'Urdu' => '🇵🇰',
                    'Persian' => '🇮🇷',
                    'Thai' => '🇹🇭',
                    'Greek' => '🇬🇷',
                    'Dutch' => '🇳🇱',
                    'Polish' => '🇵🇱',
                    'Swedish' => '🇸🇪',
                    'Malay' => '🇲🇾',
                    'Tamil' => '🇱🇰',
                    'Telugu' => '🇮🇳'
                ];
                @endphp

                @foreach($languages as $language => $flag)
                <div class="core-features-slider-card language-card">
                    <div class="core-features-slider-card-image-wrap text-center" style="font-size:50px;">
                        {{ $flag }}
                    </div>
                    <div class="core-features-card-title-description text-center">
                        <h4 class="core-features-card-title">{{ $language }}</h4>
                        <div class="primary-button-wrap">
                            <a href="{{ url('documentation_lang/' . strtolower($language)) }}" 
                               style="all: unset; cursor: pointer; display: inline-block; position: relative;">
                                <div class="primary-button">Go to Page</div>
                                <div class="primary-button-hover-bg"></div>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

<style>
.language-search {
    width: 100%;
    padding: 16px 20px;
    border-radius: 50px;
    border: 2px solid #3f60a0;
    font-size: 18px;
    box-shadow: 0px 4px 15px rgba(63,96,160,0.15);
    outline: none;
    transition: all 0.3s ease;
    text-align: center;
}
.language-search:focus {
    border-color: orange;
    box-shadow: 0px 6px 20px rgba(255,165,0,0.3);
}
.voice-btn {
    border: none;
    background: #ffffffff;
    color: #fff;
    font-size: 22px;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    cursor: pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: all 0.3s ease;
}
.voice-btn:hover {
    background: orange;
}
</style>

<script>
// Filter Function
function filterLanguages(value) {
    let searchValue = value.toLowerCase();
    let cards = document.querySelectorAll('.language-card');

    cards.forEach(card => {
        let lang = card.querySelector('.core-features-card-title').innerText.toLowerCase();
        card.style.display = lang.includes(searchValue) ? 'block' : 'none';
    });
}

// Text Search
document.getElementById('languageSearch').addEventListener('keyup', function () {
    filterLanguages(this.value);
});

// Voice Search
const voiceBtn = document.getElementById('voiceSearchBtn');
const voiceStatus = document.getElementById('voiceStatus');

voiceBtn.addEventListener('click', () => {
    if (!('webkitSpeechRecognition' in window)) {
        alert('Sorry, your browser does not support voice search.');
        return;
    }

    const recognition = new webkitSpeechRecognition();
    recognition.lang = 'en-IN'; // India ke liye English + Hindi dono detect kar lega
    recognition.interimResults = false;
    recognition.maxAlternatives = 3;

    recognition.start();
    voiceStatus.innerText = "🎤 Listening... Speak the language name";

    recognition.onresult = function(event) {
        const speechResult = event.results[0][0].transcript.toLowerCase().trim();
        document.getElementById('languageSearch').value = speechResult;
        filterLanguages(speechResult);
        voiceStatus.innerText = "✅ Recognized: " + speechResult;
    };

    recognition.onerror = function(event) {
        voiceStatus.innerText = "❌ Error: " + event.error;
    };

    recognition.onend = function() {
        voiceStatus.innerText += " (Stopped)";
    };
});
</script>

@endsection
