@php($isEnglish = app()->getLocale() === 'en')

<section class="partner-access-card" id="register">
    <div class="partner-access-card__icon" aria-hidden="true">✦</div>
    <div>
        <h2>{{ $isEnglish ? 'New to FLYN Partner?' : 'Ny hos FLYN Partner?' }}</h2>
        <p>{{ $isEnglish ? 'Register or sign in without a password. We will send a one-time code to your email.' : 'Registrer deg eller logg inn uten passord. Vi sender en engangskode til e-posten din.' }}</p>
    </div>

    <form method="POST" action="{{ route('partner.access.request-code') }}" class="partner-access-card__form">
        @csrf
        <label for="partner-access-email">{{ $isEnglish ? 'Email address' : 'E-postadresse' }}</label>
        <div class="partner-access-card__controls">
            <input id="partner-access-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
            <button type="submit">{{ $isEnglish ? 'Get access code' : 'Få tilgangskode' }}</button>
        </div>
        @error('email')
            <p class="partner-access-card__error">{{ $message }}</p>
        @enderror
    </form>

    <a href="{{ route('partner.access.verify') }}" class="partner-access-card__verify-link">{{ $isEnglish ? 'Already have a code? Verify it here' : 'Har du allerede en kode? Bekreft den her' }}</a>
</section>
