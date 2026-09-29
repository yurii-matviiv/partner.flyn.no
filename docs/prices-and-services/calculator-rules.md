# Calculator rules and change control

**Status:** active  
**Last reviewed:** 2026-09-29  
**Read this when:** changing price logic, areas, cleaning frequency, package productivity, price thresholds or wording that describes an estimate.

## Authoritative source

The Partner page does **not** calculate prices in JavaScript. It sends only the
selected plan, area and weekdays to the same-origin Partner route, which
forwards the request to CRM's canonical endpoint:

`POST https://erm.flyn.no/api/website/pricing/calculate`

with `calculator: b2b_regular_cleaning`.

CRM stores the three B2B plans in `service_b2b_cleaning_plans` (linked to
`services.slug = renhold-bedrift`) and the common formula values in
`pricing_settings.key = b2b_regular_cleaning_rules`. This is the one source
for the Partner portal, the future public-site calculator and the CRM UI.

Never restore a price formula, rates, weekend percentages or rounding values in
this JavaScript file. The page may contain presentation text only.

## Current display policy

| Area | Customer display |
| --- | --- |
| 50–1,000 m² | One rounded monthly guide price, excluding VAT |
| 1,001–1,500 m² | Rounded monthly range, ±15%, excluding VAT |
| Over 1,500 m² | No numeric price; individual offer after on-site assessment |

The estimate is for ordinary office premises only. A customer chooses one or
more exact weekdays. The number of selected days is the visit frequency.

## Current package assumptions

| Plan | Working capacity used by the estimate | Minimum on-site time per visit |
| --- | ---: | ---: |
| Vedlikehold / Maintenance | 200 m²/hour | 2.5 hours |
| Standard | 150 m²/hour | 3.0 hours |
| Utvidet / Extended | 110 m²/hour | 3.5 hours |

The numeric assumptions — market-rate curve, VAT rate, utilisation, visit time,
rounding, display thresholds and weekend supplements — live only in CRM's
`pricing_settings` JSON rule. Saturday and Sunday are currently configured as
`+25%` and `+50%`. These values are business assumptions, not a contractual
price list. Do not change them silently: first confirm the intended commercial
rule with the owner, update this document’s review date and test representative
weekday selections through the CRM API.

## Security and transparency boundary

The visible page must never present internal cost, margin, worker schedules, exact labour cost or an operational breakdown. It may present only: chosen plan, frequency, a guide monthly price/price range, a guide visit price, included service scope and the assessment disclaimer.

The formula is already server-side. The public result intentionally contains
only the non-binding customer estimate, selected weekdays and weekend
supplement labels; it exposes no internal costs, margin or worker scheduling.
