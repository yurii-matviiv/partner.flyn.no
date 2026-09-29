# Prices and services

**Status:** active  
**Last reviewed:** 2026-09-29  
**Read this when:** changing the Partner panel page **Priser og tjenester**, its customer-facing package descriptions, service scope or estimate UI.

## Purpose

The page gives existing Partner users a clear overview of regular cleaning services and a non-binding monthly estimate. It is informational: it does not create an order, send an offer, or change ERM data.

## Current implementation

- Filament page: `App\Filament\Pages\PricesAndServicesPage`
- Route in the Partner panel: `/partner-panel/priser-og-tjenester`
- View: `resources/views/filament/pages/prices-and-services.blade.php`
- Browser interaction: `public/js/partner-prices.js`
- Page styling: `public/css/partner-prices.css`
- Source reference: the owner’s local `b2b-calculator.html`. The Partner page deliberately excludes its Ukrainian development controls, operational cost breakdown, margin display and fixed bottom price bar.

## Customer-facing rules

- Three plans: **Vedlikehold / Maintenance**, **Standard**, **Utvidet / Extended**.
- Input: total area from 50 to 3,000 m² and one to five cleanings per week.
- Up to 1,000 m²: show one guide price per month, excluding VAT.
- 1,001–1,500 m²: show a ±15% guide range.
- Above 1,500 m²: show **Pris etter befaring / Price after on-site assessment**.
- Always state that the result is non-binding and the final scope and price are confirmed after an on-site assessment.
- Do not show cost, margin, worker time, hourly rate or any operational calculation to the customer.

For the current price assumptions and what may be changed only with an owner decision, read [Calculator rules](calculator-rules.md).
