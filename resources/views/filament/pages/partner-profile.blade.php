@php($isEnglish = $locale === 'en')
<x-filament-panels::page>
    <section class="partner-profile-panel">
        <div class="partner-profile-card partner-profile-card--panel">
            <header class="partner-profile-panel__intro">
                <p>{{ $isEnglish ? 'Update the information FLYN uses for invoices and operational communication.' : 'Oppdater opplysningene FLYN bruker for faktura og praktisk kommunikasjon.' }}</p>
            </header>

            @include('partner.partials.profile-form')
        </div>
    </section>
</x-filament-panels::page>
