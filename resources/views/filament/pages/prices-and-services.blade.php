@php($isEnglish = app()->getLocale() === 'en')
<x-filament-panels::page>
    <div class="partner-prices" data-locale="{{ $isEnglish ? 'en' : 'nb' }}">
        <section class="partner-prices__hero">
            <p class="partner-prices__eyebrow">FLYN PARTNER</p>
            <h1>{{ $isEnglish ? 'Prices and services' : 'Priser og tjenester' }}</h1>
            <p>{{ $isEnglish
                ? 'See what is included in a regular cleaning agreement and get a non-binding monthly estimate for your premises.'
                : 'Se hva som inngår i en fast renholdsavtale og få et uforpliktende månedsestimat for lokalene deres.' }}</p>
        </section>

        <section class="partner-price-calculator" aria-labelledby="partner-calculator-title">
            <div class="partner-price-calculator__controls">
                <div>
                    <h2 id="partner-calculator-title">{{ $isEnglish ? 'Estimate your monthly price' : 'Beregn en veiledende månedspris' }}</h2>
                    <p>{{ $isEnglish ? 'The final agreement is always tailored after an on-site assessment.' : 'Den endelige avtalen tilpasses alltid etter befaring.' }}</p>
                </div>

                <fieldset class="partner-price-fieldset">
                    <legend>{{ $isEnglish ? '1. Choose a cleaning plan' : '1. Velg renholdsplan' }}</legend>
                    <div class="partner-plan-grid" data-plan-options></div>
                </fieldset>

                <fieldset class="partner-price-fieldset">
                    <legend>{{ $isEnglish ? '2. Your premises' : '2. Lokalet deres' }}</legend>
                    <label class="partner-price-label" for="partner-area">{{ $isEnglish ? 'Total area' : 'Totalt areal' }}</label>
                    <div class="partner-area-input">
                        <input id="partner-area-range" type="range" min="50" max="3000" step="10" value="150">
                        <input id="partner-area" type="number" min="50" max="3000" step="10" value="150" inputmode="numeric">
                        <span>m²</span>
                    </div>
                    <p class="partner-price-hint">{{ $isEnglish ? 'For ordinary office premises. Larger or more specialised premises are assessed individually.' : 'Gjelder ordinære kontorlokaler. Større eller mer spesialiserte lokaler vurderes individuelt.' }}</p>

                    <span class="partner-price-label">{{ $isEnglish ? 'How often?' : 'Hvor ofte?' }}</span>
                    <div class="partner-frequency-options" data-frequency-options></div>
                </fieldset>
            </div>

            <aside class="partner-price-result" aria-live="polite">
                <p class="partner-prices__eyebrow" data-result-label></p>
                <p class="partner-price-result__plan" data-result-plan></p>
                <p class="partner-price-result__amount" data-result-monthly></p>
                <p class="partner-price-result__note" data-result-note></p>
                <dl class="partner-price-result__details">
                    <div><dt>{{ $isEnglish ? 'Cleaning frequency' : 'Renholdsfrekvens' }}</dt><dd data-result-frequency></dd></div>
                    <div><dt>{{ $isEnglish ? 'Guide price per visit' : 'Veiledende pris per rengjøring' }}</dt><dd data-result-visit></dd></div>
                </dl>
                <div class="partner-price-result__includes">
                    <h3>{{ $isEnglish ? 'Included as a starting point' : 'Inkludert som utgangspunkt' }}</h3>
                    <ul data-result-includes></ul>
                </div>
                <p class="partner-price-result__disclaimer">{{ $isEnglish ? 'The estimate is non-binding. The scope, start-up cleaning and final price are confirmed in the offer after an on-site assessment.' : 'Estimatet er uforpliktende. Omfang, oppstartsrengjøring og endelig pris bekreftes i tilbudet etter befaring.' }}</p>
            </aside>
        </section>

        <section class="partner-services" aria-labelledby="partner-services-title">
            <h2 id="partner-services-title">{{ $isEnglish ? 'What is included in the agreement?' : 'Dette er inkludert i renholdsavtalen' }}</h2>
            <p>{{ $isEnglish ? 'Each agreement has its own task list. This overview shows the usual starting point; the final content and frequency are agreed after an on-site assessment.' : 'Hver avtale får sin egen arbeidsliste. Oversikten viser det vanlige utgangspunktet; endelig innhold og frekvens avtales etter befaring.' }}</p>
            <div class="partner-services__table-wrap">
                <table class="partner-services__table">
                    <thead><tr><th>{{ $isEnglish ? 'Work area' : 'Arbeidsområde' }}</th><th>{{ $isEnglish ? 'Maintenance' : 'Vedlikehold' }}</th><th>{{ $isEnglish ? 'Standard' : 'Standard' }}</th><th>{{ $isEnglish ? 'Extended' : 'Utvidet' }}</th></tr></thead>
                    <tbody>
                        <tr><td>{{ $isEnglish ? 'Floors and waste' : 'Gulv og avfall' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td></tr>
                        <tr><td>{{ $isEnglish ? 'Kitchen and sanitary areas' : 'Kjøkken og sanitær' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td><td>{{ $isEnglish ? 'More thorough' : 'Mer grundig' }}</td><td>{{ $isEnglish ? 'More thorough' : 'Mer grundig' }}</td></tr>
                        <tr><td>{{ $isEnglish ? 'Touch points' : 'Berøringsflater' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td></tr>
                        <tr><td>{{ $isEnglish ? 'Workstations and meeting rooms' : 'Arbeidsplasser og møterom' }}</td><td>–</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td></tr>
                        <tr><td>{{ $isEnglish ? 'Doors and accessible glass' : 'Dører og tilgjengelig glass' }}</td><td>–</td><td>–</td><td>{{ $isEnglish ? 'Included' : 'Inkludert' }}</td></tr>
                        <tr><td>{{ $isEnglish ? 'Detail work' : 'Detaljarbeid' }}</td><td>–</td><td>{{ $isEnglish ? 'According to task list' : 'Etter arbeidsliste' }}</td><td>{{ $isEnglish ? 'Rotating tasks' : 'Roterende arbeid' }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="partner-services__cards">
                <article><h3>{{ $isEnglish ? 'Periodic and extra services' : 'Periodiske og ekstra tjenester' }}</h3><p>{{ $isEnglish ? 'These are not part of the monthly estimate. You always receive a clear offer before the work is ordered.' : 'Dette er ikke en del av månedsestimatet. Dere får alltid et tydelig tilbud før arbeidet bestilles.' }}</p><ul><li>{{ $isEnglish ? 'Window cleaning' : 'Vindusvask' }}</li><li>{{ $isEnglish ? 'Deep cleaning' : 'Hovedrengjøring' }}</li><li>{{ $isEnglish ? 'Carpet cleaning and floor treatment' : 'Tepperens og gulvbehandling' }}</li></ul></article>
                <article><h3>{{ $isEnglish ? 'How the agreement works' : 'Slik fungerer avtalen' }}</h3><ul><li>{{ $isEnglish ? 'On-site assessment and task list' : 'Befaring og arbeidsliste' }}</li><li>{{ $isEnglish ? 'Fixed monthly price for agreed work' : 'Fast månedspris for avtalt arbeid' }}</li><li>{{ $isEnglish ? 'One contact point for changes and extra orders' : 'Én kontaktperson for endringer og ekstrabestillinger' }}</li><li>{{ $isEnglish ? 'Estimate before any additional work starts' : 'Pris eller estimat før ekstra arbeid starter' }}</li></ul></article>
            </div>
        </section>
    </div>

    <script src="{{ asset('js/partner-prices.js') }}" defer></script>
</x-filament-panels::page>
