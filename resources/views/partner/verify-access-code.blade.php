<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $locale === 'en' ? 'Verify access code | FLYN Partner' : 'Bekreft tilgangskode | FLYN Partner' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/partner-auth.css') }}">
</head>
<body class="partner-verify-page">
    <main class="partner-verify-card">
        <div class="partner-verify-card__language">
            @include('partner.language-switcher')
        </div>
        <img src="{{ asset('images/branding/flyn-service.svg') }}" alt="FLYN" class="partner-verify-card__logo">
        <p class="partner-login-intro__eyebrow">FLYN Partner</p>
        <h1>{{ $locale === 'en' ? 'Verify your access code' : 'Bekreft tilgangskoden din' }}</h1>
        <p>{{ $locale === 'en' ? 'Enter the six-digit code we sent to your email.' : 'Skriv inn den sekssifrede koden vi sendte til e-posten din.' }}</p>

        @if (session('status'))
            <p class="partner-verify-card__status">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('partner.access.verify.submit') }}">
            @csrf
            <label for="email">{{ $locale === 'en' ? 'Email address' : 'E-postadresse' }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email">
            @error('email') <p class="partner-access-card__error">{{ $message }}</p> @enderror

            <label for="code">{{ $locale === 'en' ? 'Access code' : 'Tilgangskode' }}</label>
            <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autocomplete="one-time-code">
            @error('code') <p class="partner-access-card__error">{{ $message }}</p> @enderror

            <button type="submit">{{ $locale === 'en' ? 'Continue to Partner' : 'Fortsett til Partner' }}</button>
        </form>
        <a href="{{ url('/partner-panel/login') }}">{{ $locale === 'en' ? 'Back to sign in' : 'Tilbake til innlogging' }}</a>
    </main>
</body>
</html>
