# Partner dashboard

**Status:** active
**Last reviewed:** 2026-09-29
**Read this when:** changing the Partner dashboard cards, active services, visits, invoice sections or their empty states.

## Purpose

The dashboard answers the client’s immediate questions: what services are active, when the next visit is, whether something needs payment, and what has been paid. It is client-facing, not an ERM administration screen.

## Current state

No Partner user is yet linked to ERM orders, schedules or invoices. Therefore every dashboard value is deliberately a zero/empty state; no demo data is invented.

The four clickable cards and their dedicated pages are:

1. **Aktive tjenester / Active services** — `0`
2. **Neste besøk / Next visit** — `—`
3. **Ubetalte fakturaer / Unpaid invoices** — `0 kr`
4. **Betalt i år / Paid this year** — `0 kr`

Each destination explains what will appear there once real data is available. The pages are the future stable homes for the lists; do not create parallel dashboard-only lists.

The dashboard title is **Oversikt** in Norwegian and **Overview** in English. If there are Filament database notifications, a separate card below the indicators shows only the newest one and links to the full notification list at `/partner-panel/varsler`.

## Before connecting real data

The user-to-company relationship and the allowed ERM data boundary must be defined first. A Partner user must only receive services, visits and invoices that belong to their authorised company/contact. The integration must not be implemented by copying ERP data into Partner tables without an explicit ownership and synchronisation decision.
