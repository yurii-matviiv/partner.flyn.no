(() => {
    const root = document.querySelector('.partner-prices');
    if (!root) return;

    const english = root.dataset.locale === 'en';
    const copy = english ? {
        agreement: 'Regular cleaning agreement', estimate: 'Approx.', perMonth: '/ month', excluding: 'excl. VAT', final: 'final offer after on-site assessment', individual: 'Price after on-site assessment', larger: 'Larger premises receive an individual offer after an on-site assessment.', plan: 'plan', perVisit: 'Approx.', visitAfter: 'After on-site assessment', recommended: 'Recommended', calculatorFor: 'Calculator', planFits: 'This plan is a good fit for', chooseDays: 'Choose one or more weekdays. The price updates from your selection.', weekdaysSet: 'Weekdays', calculating: 'Calculating price…', unavailable: 'The price could not be calculated right now. Please try again shortly.',
    } : {
        agreement: 'Fast renholdsavtale', estimate: 'Ca.', perMonth: '/ mnd', excluding: 'ekskl. MVA', final: 'endelig tilbud etter befaring', individual: 'Pris etter befaring', larger: 'Større lokaler får et individuelt tilbud etter befaring.', plan: 'plan', perVisit: 'Ca.', visitAfter: 'Etter befaring', recommended: 'Anbefalt', calculatorFor: 'Kalkulator', planFits: 'Denne planen passer godt for', chooseDays: 'Velg én eller flere ukedager. Prisen oppdateres fra valget deres.', weekdaysSet: 'Hverdager', calculating: 'Beregner pris…', unavailable: 'Prisen kunne ikke beregnes akkurat nå. Prøv igjen om litt.',
    };
    // These only describe the visible service scope. All price values and
    // calculation rules come from the CRM API.
    const plans = [
        { id: 'maintenance', label: english ? 'Maintenance' : 'Vedlikehold', description: english ? 'For offices that need regular upkeep.' : 'For et kontor som trenger jevnlig vedlikehold.', includes: [english ? 'Floors and waste' : 'Gulv og avfall', english ? 'Kitchen and sanitary areas' : 'Kjøkken og sanitær', english ? 'Touch points' : 'Berøringsflater'] },
        { id: 'standard', label: 'Standard', recommended: true, description: english ? 'Thorough, regular cleaning for most offices.' : 'Grundig, fast renhold for de fleste kontorer.', includes: [english ? 'Everything in Maintenance' : 'Alt i Vedlikehold', english ? 'Workstations and meeting rooms' : 'Arbeidsplasser og møterom', english ? 'More thorough kitchen and sanitary cleaning' : 'Mer grundig kjøkken og sanitær'] },
        { id: 'extended', label: english ? 'Extended' : 'Utvidet', description: english ? 'For premises requiring more detailed cleaning.' : 'Når dere ønsker et mer detaljert nivå.', includes: [english ? 'Everything in Standard' : 'Alt i Standard', english ? 'Doors and accessible glass' : 'Dører og tilgjengelig glass', english ? 'Rotating detail work' : 'Roterende detaljarbeid'] },
    ];
    const icons = {
        maintenance: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5.5 5h13A1.5 1.5 0 0 1 20 6.5v12a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-12A1.5 1.5 0 0 1 5.5 5Z"/></svg>',
        standard: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h16M6 20V9h12v11M8 9V5h8v4M9 13h2m2 0h2m-6 4h2m2 0h2"/></svg>',
        extended: '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 .8 3.2L16 7l-3.2.8L12 11l-.8-3.2L8 7l3.2-.8L12 3Zm6 8 .6 2.4L21 14l-2.4.6L18 17l-.6-2.4L15 14l2.4-.6L18 11ZM6 14l.8 3.2L10 18l-3.2.8L6 22l-.8-3.2L2 18l3.2-.8L6 14Z"/></svg>',
    };
    const weekdays = [
        { id: 'mon', label: english ? 'Mon' : 'Man' }, { id: 'tue', label: english ? 'Tue' : 'Tir' }, { id: 'wed', label: english ? 'Wed' : 'Ons' }, { id: 'thu', label: english ? 'Thu' : 'Tor' }, { id: 'fri', label: english ? 'Fri' : 'Fre' }, { id: 'sat', label: english ? 'Sat' : 'Lør' }, { id: 'sun', label: english ? 'Sun' : 'Søn' },
    ];
    const state = { plan: 'standard', area: 150, weekdays: ['mon'] };
    const currency = value => new Intl.NumberFormat(english ? 'en-GB' : 'nb-NO', { maximumFractionDigits: 0 }).format(Math.round(value)) + ' kr';
    const find = selector => root.querySelector(selector);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    let result = null;
    let timer = null;
    let requestId = 0;

    const currentPlan = () => plans.find(plan => plan.id === state.plan) ?? plans[1];
    const selectedDays = () => weekdays.filter(day => state.weekdays.includes(day.id)).map(day => day.label).join(', ');

    function render() {
        const plan = currentPlan();
        find('[data-plan-options]').innerHTML = plans.map(item => `<button type="button" class="partner-plan-tab ${state.plan === item.id ? 'is-active' : ''}" data-plan="${item.id}" role="tab" aria-selected="${state.plan === item.id}"><span class="partner-plan-tab__icon">${icons[item.id]}</span><span class="partner-plan-tab__label">${item.label}<small>${item.recommended ? copy.recommended : ''}</small></span>${item.recommended ? '<span class="partner-plan-tab__check">✓</span>' : ''}</button>`).join('');
        find('[data-calculator-title]').textContent = `${copy.calculatorFor} – ${plan.label}`;
        const badge = find('[data-calculator-badge]');
        badge.hidden = !plan.recommended;
        badge.textContent = plan.recommended ? copy.recommended : '';
        find('[data-plan-summary]').innerHTML = `<p>${copy.planFits}</p><h3>${plan.description}</h3><ul>${plan.includes.map(item => `<li>${item}</li>`).join('')}</ul>`;
        find('[data-weekday-options]').innerHTML = weekdays.map(day => `<button type="button" class="partner-weekday ${state.weekdays.includes(day.id) ? 'is-active' : ''}" data-weekday="${day.id}" aria-pressed="${state.weekdays.includes(day.id)}">${day.label}</button>`).join('');
        find('[data-weekday-hint]').textContent = copy.chooseDays;
        find('#partner-area').value = state.area;
        find('#partner-area-range').value = Math.min(3000, Math.max(50, state.area));
        renderResult();
    }

    function renderResult(errorMessage = '') {
        const error = find('[data-calculator-error]');
        error.hidden = !errorMessage;
        error.textContent = errorMessage;
        find('[data-result-label]').textContent = copy.agreement;
        find('[data-result-plan]').textContent = `${currentPlan().label} ${copy.plan}`;
        find('[data-result-frequency]').textContent = state.weekdays.length === weekdays.length ? copy.weekdaysSet : selectedDays();
        if (!result) {
            find('[data-result-monthly]').textContent = copy.calculating;
            find('[data-result-note]').textContent = copy.excluding;
            find('[data-result-visit]').textContent = '—';
            find('[data-result-weekend-row]').hidden = true;
            return;
        }
        const display = result.display;
        const assessment = display.kind === 'assessment';
        const range = display.kind === 'range';
        find('[data-result-monthly]').textContent = assessment ? copy.individual : `${copy.estimate} ${range ? `${currency(display.netMonthlyLow)}–${currency(display.netMonthlyHigh)}` : currency(result.netMonthlyPrice)} ${copy.perMonth}`;
        find('[data-result-note]').textContent = assessment ? copy.larger : `${copy.excluding} · ${copy.final}`;
        find('[data-result-visit]').textContent = assessment ? copy.visitAfter : `${copy.perVisit} ${range ? `${currency(display.netVisitLow)}–${currency(display.netVisitHigh)}` : currency(result.netPricePerVisit)}`;
        const weekendDays = state.weekdays.filter(day => day === 'sat' || day === 'sun');
        const weekendRow = find('[data-result-weekend-row]');
        weekendRow.hidden = weekendDays.length === 0 || !result.weekendSupplementNet;
        find('[data-result-weekend]').textContent = weekendDays.map(day => `${weekdays.find(item => item.id === day).label} +${Math.round(((result.weekdayMultipliers?.[day] ?? 1) - 1) * 100)} %`).join(' · ');
    }

    async function calculate() {
        const currentRequest = ++requestId;
        result = null;
        renderResult();
        try {
            const response = await fetch('/partner-panel/pricing/calculate', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}) },
                body: JSON.stringify(state),
            });
            const payload = await response.json();
            if (!response.ok || !payload.data) throw new Error(payload.message || copy.unavailable);
            if (currentRequest !== requestId) return;
            result = payload.data;
            renderResult();
        } catch (error) {
            if (currentRequest === requestId) renderResult(error.message || copy.unavailable);
        }
    }

    function scheduleCalculation() {
        clearTimeout(timer);
        timer = setTimeout(calculate, 180);
    }

    root.addEventListener('click', event => {
        const plan = event.target.closest('[data-plan]');
        const weekday = event.target.closest('[data-weekday]');
        if (plan) state.plan = plan.dataset.plan;
        if (weekday) {
            const day = weekday.dataset.weekday;
            if (state.weekdays.includes(day) && state.weekdays.length > 1) state.weekdays = state.weekdays.filter(item => item !== day);
            else if (!state.weekdays.includes(day)) state.weekdays = [...state.weekdays, day];
        }
        if (plan || weekday) { render(); scheduleCalculation(); }
    });
    ['partner-area', 'partner-area-range'].forEach(id => find(`#${id}`).addEventListener('input', event => {
        state.area = Math.max(50, Math.min(3000, Number(event.target.value) || 50));
        render();
        scheduleCalculation();
    }));
    render();
    calculate();
})();
