# Calculator rules and change control

**Status:** active  
**Last reviewed:** 2026-09-29  
**Read this when:** changing price logic, areas, cleaning frequency, package productivity, price thresholds or wording that describes an estimate.

## Business source

The starting point is the owner’s existing local B2B calculator. The Partner page reuses its customer estimate intention, but not its development-only operational display.

## Current display policy

| Area | Customer display |
| --- | --- |
| 50–1,000 m² | One rounded monthly guide price, excluding VAT |
| 1,001–1,500 m² | Rounded monthly range, ±15%, excluding VAT |
| Over 1,500 m² | No numeric price; individual offer after on-site assessment |

The estimate is for ordinary office premises only. A visit frequency of 1, 2, 3 or 5 times per week is supported.

## Current package assumptions

| Plan | Working capacity used by the estimate | Minimum on-site time per visit |
| --- | ---: | ---: |
| Vedlikehold / Maintenance | 200 m²/hour | 2.5 hours |
| Standard | 150 m²/hour | 3.0 hours |
| Utvidet / Extended | 110 m²/hour | 3.5 hours |

The browser implementation also applies the source calculator’s current market-rate curve and VAT rate to make the interactive guide estimate. These values are business assumptions, not a contractual price list. Do not change them silently: first confirm the intended commercial rule with the owner, update this document’s review date and test the representative areas/frequencies manually.

## Security and transparency boundary

The visible page must never present internal cost, margin, worker schedules, exact labour cost or an operational breakdown. It may present only: chosen plan, frequency, a guide monthly price/price range, a guide visit price, included service scope and the assessment disclaimer.

If price calculation later needs to be treated as confidential business logic, move it from the browser into an authenticated server-side endpoint before introducing lead/order creation. That is a separate implementation decision; the current page is a transparent, non-binding estimate only.
