(() => {
    const root = document.querySelector('.partner-prices');
    if (!root) return;

    const english = root.dataset.locale === 'en';
    const copy = english ? {
        agreement: 'Regular cleaning agreement', estimate: 'Approx.', perMonth: '/ month', excluding: 'excl. VAT', including: 'incl. VAT',
        final: 'final offer after on-site assessment', individual: 'Price after on-site assessment', larger: 'Larger premises receive an individual offer after an on-site assessment.',
        plan: 'plan', perVisit: 'Approx.', visitAfter: 'After on-site assessment',
        recommended: 'Recommended', calculatorFor: 'Calculator', planFits: 'This plan is a good fit for', chooseDays: 'Choose one or more weekdays. The price updates from your selection.', weekdaysSet: 'Weekdays', saturdaySupplement: 'Sat +25%', sundaySupplement: 'Sun +50%',
    } : {
        agreement: 'Fast renholdsavtale', estimate: 'Ca.', perMonth: '/ mnd', excluding: 'ekskl. MVA', including: 'inkl. MVA',
        final: 'endelig tilbud etter befaring', individual: 'Pris etter befaring', larger: 'Større lokaler får et individuelt tilbud etter befaring.',
        plan: 'plan', perVisit: 'Ca.', visitAfter: 'Etter befaring',
        recommended: 'Anbefalt', calculatorFor: 'Kalkulator', planFits: 'Denne planen passer godt for', chooseDays: 'Velg én eller flere ukedager. Prisen oppdateres fra valget deres.', weekdaysSet: 'Hverdager', saturdaySupplement: 'Lør +25 %', sundaySupplement: 'Søn +50 %',
    };
    const packages = [
        { id: 'maintenance', label: english ? 'Maintenance' : 'Vedlikehold', productivity: 200, minimum: 2.5, description: english ? 'For offices that need regular upkeep.' : 'For et kontor som trenger jevnlig vedlikehold.', includes: [english ? 'Floors and waste' : 'Gulv og avfall', english ? 'Kitchen and sanitary areas' : 'Kjøkken og sanitær', english ? 'Touch points' : 'Berøringsflater'] },
        { id: 'standard', label: 'Standard', productivity: 150, minimum: 3, recommended: true, description: english ? 'Thorough, regular cleaning for most offices.' : 'Grundig, fast renhold for de fleste kontorer.', includes: [english ? 'Everything in Maintenance' : 'Alt i Vedlikehold', english ? 'Workstations and meeting rooms' : 'Arbeidsplasser og møterom', english ? 'More thorough kitchen and sanitary cleaning' : 'Mer grundig kjøkken og sanitær'] },
        { id: 'extended', label: english ? 'Extended' : 'Utvidet', productivity: 110, minimum: 3.5, description: english ? 'For premises requiring more detailed cleaning.' : 'Når dere ønsker et mer detaljert nivå.', includes: [english ? 'Everything in Standard' : 'Alt i Standard', english ? 'Doors and accessible glass' : 'Dører og tilgjengelig glass', english ? 'Rotating detail work' : 'Roterende detaljarbeid'] },
    ];
    const planIcons = {
        maintenance: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5.5 5h13A1.5 1.5 0 0 1 20 6.5v12a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-12A1.5 1.5 0 0 1 5.5 5Z"/></svg>',
        standard: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h16M6 20V9h12v11M8 9V5h8v4M9 13h2m2 0h2m-6 4h2m2 0h2"/></svg>',
        extended: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 .8 3.2L16 7l-3.2.8L12 11l-.8-3.2L8 7l3.2-.8L12 3Zm6 8 .6 2.4L21 14l-2.4.6L18 17l-.6-2.4L15 14l2.4-.6L18 11ZM6 14l.8 3.2L10 18l-3.2.8L6 22l-.8-3.2L2 18l3.2-.8L6 14Z"/></svg>',
    };
    const weekdays = [
        { id: 'mon', label: english ? 'Mon' : 'Man' }, { id: 'tue', label: english ? 'Tue' : 'Tir' },
        { id: 'wed', label: english ? 'Wed' : 'Ons' }, { id: 'thu', label: english ? 'Thu' : 'Tor' },
        { id: 'fri', label: english ? 'Fri' : 'Fre' }, { id: 'sat', label: english ? 'Sat' : 'Lør' },
        { id: 'sun', label: english ? 'Sun' : 'Søn' },
    ];
    const state = { plan: 'standard', area: 150, weekdays: ['mon'] };
    const dayPriceMultiplier = { sat: 1.25, sun: 1.5 };
    const currency = value => new Intl.NumberFormat(english ? 'en-GB' : 'nb-NO', { maximumFractionDigits: 0 }).format(Math.round(value)) + ' kr';
    const round = value => Math.round(value / 100) * 100;
    const priceRate = hours => hours <= 20 ? 650 : hours <= 50 ? 650 - ((hours - 20) * 1) : hours <= 100 ? 620 - ((hours - 50) * .6) : hours <= 200 ? 590 - ((hours - 100) * .3) : 560;
    const calculate = () => {
        const plan = packages.find(item => item.id === state.plan);
        const visits = state.weekdays.length * (52 / 12);
        const workPerVisit = state.area / plan.productivity / .95;
        const visitHours = Math.max(plan.minimum, workPerVisit + .25);
        const priceHours = (visitHours + .5) * visits;
        const rate = priceRate(priceHours);
        const baseMonthly = (visitHours + .5) * (52 / 12) * rate * state.weekdays.length;
        const monthly = round((visitHours + .5) * (52 / 12) * rate * state.weekdays.reduce((total, day) => total + (dayPriceMultiplier[day] ?? 1), 0));
        return { plan, visits, monthly, perVisit: monthly / visits, weekendSupplement: monthly - round(baseMonthly) };
    };
    const render = () => {
        const planTarget = root.querySelector('[data-plan-options]');
        planTarget.innerHTML = packages.map(plan => `<button type="button" class="partner-plan-tab ${state.plan === plan.id ? 'is-active' : ''}" data-plan="${plan.id}" role="tab" aria-selected="${state.plan === plan.id}"><span class="partner-plan-tab__icon">${planIcons[plan.id]}</span><span class="partner-plan-tab__label">${plan.label}<small>${plan.recommended ? copy.recommended : ''}</small></span>${plan.recommended ? '<span class="partner-plan-tab__check">✓</span>' : ''}</button>`).join('');
        const activePlan = packages.find(plan => plan.id === state.plan);
        root.querySelector('[data-calculator-title]').textContent = `${copy.calculatorFor} – ${activePlan.label}`;
        const calculatorBadge = root.querySelector('[data-calculator-badge]');
        calculatorBadge.hidden = !activePlan.recommended;
        calculatorBadge.textContent = activePlan.recommended ? copy.recommended : '';
        root.querySelector('[data-plan-summary]').innerHTML = `<p>${copy.planFits}</p><h3>${activePlan.description}</h3><ul>${activePlan.includes.map(item => `<li>${item}</li>`).join('')}</ul>`;
        const weekdayTarget = root.querySelector('[data-weekday-options]');
        weekdayTarget.innerHTML = weekdays.map(day => `<button type="button" class="partner-weekday ${state.weekdays.includes(day.id) ? 'is-active' : ''}" data-weekday="${day.id}" aria-pressed="${state.weekdays.includes(day.id)}">${day.label}</button>`).join('');
        root.querySelector('[data-weekday-hint]').textContent = copy.chooseDays;
        root.querySelector('#partner-area').value = state.area;
        root.querySelector('#partner-area-range').value = Math.min(3000, Math.max(50, state.area));
        const result = calculate();
        const large = state.area > 1500;
        const range = value => `${currency(round(value * .85))}–${currency(round(value * 1.15))}`;
        root.querySelector('[data-result-label]').textContent = copy.agreement;
        root.querySelector('[data-result-plan]').textContent = `${result.plan.label} ${copy.plan}`;
        root.querySelector('[data-result-monthly]').textContent = large ? copy.individual : `${copy.estimate} ${state.area > 1000 ? range(result.monthly) : currency(result.monthly)} ${copy.perMonth}`;
        root.querySelector('[data-result-note]').textContent = large ? copy.larger : `${copy.excluding} · ${copy.final}`;
        const selectedDayLabels = weekdays.filter(day => state.weekdays.includes(day.id)).map(day => day.label).join(', ');
        root.querySelector('[data-result-frequency]').textContent = state.weekdays.length === weekdays.length ? copy.weekdaysSet : selectedDayLabels;
        root.querySelector('[data-result-visit]').textContent = large ? copy.visitAfter : `${copy.perVisit} ${state.area > 1000 ? range(result.perVisit) : currency(result.perVisit)}`;
        const weekendDetails = [];
        if (state.weekdays.includes('sat')) weekendDetails.push(copy.saturdaySupplement);
        if (state.weekdays.includes('sun')) weekendDetails.push(copy.sundaySupplement);
        const weekendRow = root.querySelector('[data-result-weekend-row]');
        weekendRow.hidden = weekendDetails.length === 0;
        root.querySelector('[data-result-weekend]').textContent = weekendDetails.join(' · ');
    };
    root.addEventListener('click', event => {
        const plan = event.target.closest('[data-plan]');
        const weekday = event.target.closest('[data-weekday]');
        if (plan) { state.plan = plan.dataset.plan; render(); }
        if (weekday) {
            const id = weekday.dataset.weekday;
            if (state.weekdays.includes(id) && state.weekdays.length > 1) state.weekdays = state.weekdays.filter(day => day !== id);
            else if (!state.weekdays.includes(id)) state.weekdays = [...state.weekdays, id];
            render();
        }
    });
    for (const id of ['partner-area', 'partner-area-range']) root.querySelector(`#${id}`).addEventListener('input', event => { state.area = Math.max(50, Math.min(3000, Number(event.target.value) || 50)); render(); });
    render();
})();
