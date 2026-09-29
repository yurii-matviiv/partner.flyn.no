@php($isEnglish = $locale === 'en')
<!doctype html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isEnglish ? 'Complete profile | FLYN Partner' : 'Fullfør profil | FLYN Partner' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/partner-auth.css') }}">
</head>
<body class="partner-profile-page">
    <main class="partner-profile-card">
        <header class="partner-profile-header">
            <div class="partner-profile-header__actions">
                @include('partner.language-switcher')
            </div>
            <img src="{{ asset('images/branding/flyn-service.svg') }}" alt="FLYN" class="partner-verify-card__logo">
            <p class="partner-login-intro__eyebrow">FLYN Partner</p>
            <h1>{{ $isEnglish ? 'Complete your profile' : 'Fullfør profilen din' }}</h1>
            <p>{{ $isEnglish ? 'We need the billing details for your company and one main contact person before you continue.' : 'Vi trenger fakturadetaljene for bedriften og én hovedkontakt før du fortsetter.' }}</p>
        </header>

        <form method="POST" action="{{ route('partner.profile.store') }}" class="partner-profile-form">
            @csrf

            <div class="partner-profile-tabs" role="tablist" aria-label="{{ $isEnglish ? 'Profile sections' : 'Profilseksjoner' }}">
                <input class="partner-profile-tabs__input" type="radio" id="company-tab" name="profile-section" checked>
                <label class="partner-profile-tabs__label" for="company-tab" role="tab">1. {{ $isEnglish ? 'Company' : 'Bedrift' }}</label>
                <input class="partner-profile-tabs__input" type="radio" id="contact-tab" name="profile-section">
                <label class="partner-profile-tabs__label" for="contact-tab" role="tab">2. {{ $isEnglish ? 'Contact person' : 'Kontaktperson' }}</label>

                <section class="partner-profile-tabs__panel partner-profile-tabs__panel--company" role="tabpanel">
                    <h2>{{ $isEnglish ? 'Company details' : 'Bedriftsopplysninger' }}</h2>
                    <p>{{ $isEnglish ? 'These are the legal details used when FLYN invoices the company.' : 'Dette er de juridiske opplysningene som brukes når FLYN fakturerer bedriften.' }}</p>

                    <div class="partner-profile-grid">
                        <div class="partner-profile-field partner-profile-field--wide">
                            <label for="legal_name">{{ $isEnglish ? 'Legal company name' : 'Juridisk bedriftsnavn' }}</label>
                            <input id="legal_name" name="legal_name" value="{{ old('legal_name', $company?->legal_name) }}" required autocomplete="organization">
                            @error('legal_name') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="org_number">{{ $isEnglish ? 'Organisation number' : 'Organisasjonsnummer' }}</label>
                            <input id="org_number" name="org_number" inputmode="numeric" value="{{ old('org_number', $company?->org_number) }}" required placeholder="123 456 789">
                            <span>{{ $isEnglish ? 'Nine digits, without “MVA”.' : 'Ni sifre, uten «MVA».' }}</span>
                            @error('org_number') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="legal_form">{{ $isEnglish ? 'Company form' : 'Selskapsform' }}</label>
                            <select id="legal_form" name="legal_form" required>
                                <option value="">{{ $isEnglish ? 'Choose form' : 'Velg form' }}</option>
                                @foreach (['as' => 'AS', 'asa' => 'ASA', 'enk' => 'ENK', 'ans' => 'ANS', 'da' => 'DA', 'nuf' => 'NUF', 'sa' => 'SA', 'stiftelse' => ($isEnglish ? 'Foundation' : 'Stiftelse'), 'other' => ($isEnglish ? 'Other' : 'Annet')] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('legal_form', $company?->legal_form) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('legal_form') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field partner-profile-field--wide">
                            <label for="address_line">{{ $isEnglish ? 'Business address' : 'Forretningsadresse' }}</label>
                            <input id="address_line" name="address_line" value="{{ old('address_line', $company?->address_line) }}" required autocomplete="street-address">
                            @error('address_line') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="postal_code">{{ $isEnglish ? 'Postcode' : 'Postnummer' }}</label>
                            <input id="postal_code" name="postal_code" inputmode="numeric" maxlength="4" value="{{ old('postal_code', $company?->postal_code) }}" required autocomplete="postal-code">
                            @error('postal_code') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="city">{{ $isEnglish ? 'City' : 'Poststed' }}</label>
                            <input id="city" name="city" value="{{ old('city', $company?->city) }}" required autocomplete="address-level2">
                            @error('city') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="invoice_email">{{ $isEnglish ? 'Invoice email' : 'Faktura e-post' }}</label>
                            <input id="invoice_email" name="invoice_email" type="email" value="{{ old('invoice_email', $company?->email) }}" required autocomplete="email">
                            @error('invoice_email') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="company_phone">{{ $isEnglish ? 'Company phone' : 'Bedriftens telefonnummer' }}</label>
                            <input id="company_phone" name="company_phone" type="tel" value="{{ old('company_phone', $company?->phone) }}" required autocomplete="tel">
                            @error('company_phone') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <label class="partner-profile-next" for="contact-tab">{{ $isEnglish ? 'Continue to contact person' : 'Fortsett til kontaktperson' }} <span aria-hidden="true">→</span></label>
                </section>

                <section class="partner-profile-tabs__panel partner-profile-tabs__panel--contact" role="tabpanel">
                    <h2>{{ $isEnglish ? 'Main contact person' : 'Hovedkontakt' }}</h2>
                    <p>{{ $isEnglish ? 'This person receives operational communication from FLYN.' : 'Denne personen mottar praktisk kommunikasjon fra FLYN.' }}</p>

                    <div class="partner-profile-grid">
                        <div class="partner-profile-field">
                            <label for="first_name">{{ $isEnglish ? 'First name' : 'Fornavn' }}</label>
                            <input id="first_name" name="first_name" value="{{ old('first_name', $contact?->first_name) }}" required autocomplete="given-name">
                            @error('first_name') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field">
                            <label for="last_name">{{ $isEnglish ? 'Last name' : 'Etternavn' }}</label>
                            <input id="last_name" name="last_name" value="{{ old('last_name', $contact?->last_name) }}" required autocomplete="family-name">
                            @error('last_name') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field partner-profile-field--wide">
                            <label for="contact_job_title">{{ $isEnglish ? 'Job title' : 'Stilling' }} <em>{{ $isEnglish ? 'optional' : 'valgfritt' }}</em></label>
                            <input id="contact_job_title" name="contact_job_title" value="{{ old('contact_job_title', $profile?->contact_job_title) }}" autocomplete="organization-title">
                            @error('contact_job_title') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                        <div class="partner-profile-field partner-profile-field--wide">
                            <label for="contact_email">{{ $isEnglish ? 'Verified email' : 'Bekreftet e-postadresse' }}</label>
                            <input id="contact_email" type="email" value="{{ $user->email }}" readonly aria-readonly="true">
                            <span>{{ $isEnglish ? 'Verified with your access code.' : 'Bekreftet med tilgangskoden din.' }}</span>
                        </div>
                        <div class="partner-profile-field partner-profile-field--wide">
                            <label for="contact_phone">{{ $isEnglish ? 'Mobile phone' : 'Mobilnummer' }}</label>
                            <input id="contact_phone" name="contact_phone" type="tel" value="{{ old('contact_phone', $contact?->phone) }}" required autocomplete="tel">
                            @error('contact_phone') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <label class="partner-profile-authorisation">
                        <input type="checkbox" name="authorised_to_represent" value="1" @checked(old('authorised_to_represent')) required>
                        <span>{{ $isEnglish ? 'I confirm that I am authorised to provide these company details.' : 'Jeg bekrefter at jeg har fullmakt til å oppgi disse bedriftsopplysningene.' }}</span>
                    </label>
                    @error('authorised_to_represent') <p class="partner-access-card__error">{{ $message }}</p> @enderror
                </section>
            </div>

            <div class="partner-profile-actions">
                <button type="submit" class="partner-profile-submit">
                    {{ $isEnglish ? 'Save details and continue' : 'Lagre opplysningene og fortsett' }}
                </button>
                <button
                    type="submit"
                    class="partner-profile-skip"
                    formaction="{{ route('partner.profile.skip') }}"
                    formmethod="POST"
                    formnovalidate
                >
                    {{ $isEnglish ? 'Continue without completing now' : 'Fortsett uten å fylle ut nå' }}
                </button>
            </div>
        </form>
    </main>
</body>
</html>
