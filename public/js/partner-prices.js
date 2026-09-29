(() => {
    const root = document.querySelector('.partner-prices');
    if (!root) return;

    const english = root.dataset.locale === 'en';
    const copy = english ? {
        agreement: 'Regular cleaning agreement', estimate: 'Approx.', perMonth: '/ month', excluding: 'excl. VAT', including: 'incl. VAT',
        final: 'final offer after on-site assessment', individual: 'Price after on-site assessment', larger: 'Larger premises receive an individual offer after an on-site assessment.',
        plan: 'plan', perVisit: 'Approx.', visitAfter: 'After on-site assessment', fixedCleaner: 'Regular cleaner and contact person', quality: 'Quality control and follow-up',
    } : {
        agreement: 'Fast renholdsavtale', estimate: 'Ca.', perMonth: '/ mnd', excluding: 'ekskl. MVA', including: 'inkl. MVA',
        final: 'endelig tilbud etter befaring', individual: 'Pris etter befaring', larger: 'Større lokaler får et individuelt tilbud etter befaring.',
        plan: 'plan', perVisit: 'Ca.', visitAfter: 'Etter befaring', fixedCleaner: 'Fast renholder og kontaktperson', quality: 'Kvalitetskontroll og oppfølging',
    };
    const packages = [
        { id: 'maintenance', label: english ? 'Maintenance' : 'Vedlikehold', productivity: 200, minimum: 2.5, description: english ? 'For offices that need regular upkeep.' : 'For et kontor som trenger jevnlig vedlikehold.', includes: [english ? 'Floors and waste' : 'Gulv og avfall', english ? 'Kitchen and sanitary areas' : 'Kjøkken og sanitær', english ? 'Touch points' : 'Berøringsflater'] },
        { id: 'standard', label: 'Standard', productivity: 150, minimum: 3, recommended: true, description: english ? 'Thorough, regular cleaning for most offices.' : 'Grundig, fast renhold for de fleste kontorer.', includes: [english ? 'Everything in Maintenance' : 'Alt i Vedlikehold', english ? 'Workstations and meeting rooms' : 'Arbeidsplasser og møterom', english ? 'More thorough kitchen and sanitary cleaning' : 'Mer grundig kjøkken og sanitær'] },
        { id: 'extended', label: english ? 'Extended' : 'Utvidet', productivity: 110, minimum: 3.5, description: english ? 'For premises requiring more detailed cleaning.' : 'Når dere ønsker et mer detaljert nivå.', includes: [english ? 'Everything in Standard' : 'Alt i Standard', english ? 'Doors and accessible glass' : 'Dører og tilgjengelig glass', english ? 'Rotating detail work' : 'Roterende detaljarbeid'] },
    ];
    const frequencies = [
        { id: 'w1', label: english ? 'Once a week' : '1× i uken', perWeek: 1 }, { id: 'w2', label: english ? 'Twice a week' : '2× i uken', perWeek: 2 },
        { id: 'w3', label: english ? 'Three times a week' : '3× i uken', perWeek: 3 }, { id: 'w5', label: english ? 'Weekdays (5×)' : 'Hverdager (5×)', perWeek: 5 },
    ];
    const state = { plan: 'standard', area: 150, frequency: 'w1' };
    const currency = value => new Intl.NumberFormat(english ? 'en-GB' : 'nb-NO', { maximumFractionDigits: 0 }).format(Math.round(value)) + ' kr';
    const round = value => Math.round(value / 100) * 100;
    const priceRate = hours => hours <= 20 ? 650 : hours <= 50 ? 650 - ((hours - 20) * 1) : hours <= 100 ? 620 - ((hours - 50) * .6) : hours <= 200 ? 590 - ((hours - 100) * .3) : 560;
    const calculate = () => {
        const plan = packages.find(item => item.id === state.plan);
        const frequency = frequencies.find(item => item.id === state.frequency);
        const visits = frequency.perWeek * (52 / 12);
        const workPerVisit = state.area / plan.productivity / .95;
        const visitHours = Math.max(plan.minimum, workPerVisit + .25);
        const priceHours = (visitHours + .5) * visits;
        const monthly = round(priceHours * priceRate(priceHours));
        return { plan, frequency, visits, monthly, perVisit: monthly / visits };
    };
    const render = () => {
        const planTarget = root.querySelector('[data-plan-options]');
        planTarget.innerHTML = packages.map(plan => `<button type="button" class="partner-plan ${state.plan === plan.id ? 'is-active' : ''}" data-plan="${plan.id}">${plan.recommended ? `<span>${english ? 'Recommended' : 'Anbefalt'}</span>` : ''}<strong>${plan.label}</strong><small>${plan.description}</small><ul>${plan.includes.map(item => `<li>${item}</li>`).join('')}</ul></button>`).join('');
        const frequencyTarget = root.querySelector('[data-frequency-options]');
        frequencyTarget.innerHTML = frequencies.map(frequency => `<button type="button" class="partner-frequency ${state.frequency === frequency.id ? 'is-active' : ''}" data-frequency="${frequency.id}">${frequency.label}</button>`).join('');
        root.querySelector('#partner-area').value = state.area;
        root.querySelector('#partner-area-range').value = Math.min(3000, Math.max(50, state.area));
        const result = calculate();
        const large = state.area > 1500;
        const range = value => `${currency(round(value * .85))}–${currency(round(value * 1.15))}`;
        root.querySelector('[data-result-label]').textContent = copy.agreement;
        root.querySelector('[data-result-plan]').textContent = `${result.plan.label} ${copy.plan}`;
        root.querySelector('[data-result-monthly]').textContent = large ? copy.individual : `${copy.estimate} ${state.area > 1000 ? range(result.monthly) : currency(result.monthly)} ${copy.perMonth}`;
        root.querySelector('[data-result-note]').textContent = large ? copy.larger : `${copy.excluding} · ${copy.final}`;
        root.querySelector('[data-result-frequency]').textContent = result.frequency.label;
        root.querySelector('[data-result-visit]').textContent = large ? copy.visitAfter : `${copy.perVisit} ${state.area > 1000 ? range(result.perVisit) : currency(result.perVisit)}`;
        root.querySelector('[data-result-includes]').innerHTML = [...result.plan.includes, copy.fixedCleaner, copy.quality].map(item => `<li>${item}</li>`).join('');
    };
    root.addEventListener('click', event => {
        const plan = event.target.closest('[data-plan]');
        const frequency = event.target.closest('[data-frequency]');
        if (plan) { state.plan = plan.dataset.plan; render(); }
        if (frequency) { state.frequency = frequency.dataset.frequency; render(); }
    });
    for (const id of ['partner-area', 'partner-area-range']) root.querySelector(`#${id}`).addEventListener('input', event => { state.area = Math.max(50, Math.min(3000, Number(event.target.value) || 50)); render(); });
    render();
})();
