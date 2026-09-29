@php($isEnglish = app()->getLocale() === 'en')

<section class="partner-login-intro">
    <div class="partner-login-intro__topline">
        <span class="partner-login-intro__eyebrow">FLYN Partner</span>
        @include('partner.language-switcher')
    </div>

    <h1>{{ $isEnglish ? 'Your operations partner for cleaning and facilities' : 'Din driftspartner for renhold og drift' }}</h1>
    <p>{{ $isEnglish ? 'One place for communication, practical information, prices and updates from FLYN.' : 'Ett sted for kommunikasjon, praktisk informasjon, priser og oppdateringer fra FLYN.' }}</p>
</section>
