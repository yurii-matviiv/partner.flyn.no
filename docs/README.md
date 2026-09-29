# Partner project documentation index

**Status:** active  
**Last reviewed:** 2026-09-29  
**Read this:** at the start of every Partner-project task.

This is the short, mandatory entry point for the project. It is deliberately not a full history: read only the linked document that matches the change you are about to make.

## Current product boundaries

- **FLYN Partner** is a separate Laravel/Filament application and uses its own `partner` guard, user records and session cookie. It must not use ERM users as Partner users.
- The application is developed locally first. Do not deploy, push to GitHub, change the hosting database or alter ERM unless the owner explicitly asks for that step.
- Database changes are prepared as copy-ready SQL and executed by the owner; do not create Laravel migrations for hosting changes.
- Customer-facing content is Norwegian and English only. The current locale must be respected in a page’s text and controls.
- The signed-in panel uses Filament’s sidebar, notification bell and avatar menu. Public/access pages may use their own visible language control; the authenticated panel keeps language choice inside the avatar menu.
- A Partner profile is **optional**. A verified email grants access to the panel; missing company/contact data creates a persistent notification with a link to the profile page, not a redirect or access block.

## Choose the relevant document

| When changing… | Read… |
| --- | --- |
| The page **Priser og tjenester**, its estimate, package wording or included services | [Prices and services overview](prices-and-services/README.md) |
| A price rule, estimate threshold or the source calculator’s assumptions | [Calculator rules](prices-and-services/calculator-rules.md) |
| Partner dashboard cards, empty states, services, visits or invoice presentation | [Dashboard overview](dashboard/README.md) |
| Login, email-code access, Partner users, sessions or profile onboarding | Create/read the dedicated authentication document when that module is next changed; do not infer it from the price-page documentation. |
| Hosting, GitHub Actions or FTP | Use the existing deployment workflow documentation and verify against current hosting configuration before any deploy. |

## Documentation convention

Every topic document must state **Status**, **Last reviewed**, and **Read this when** at the top. Update the date only after the content has been checked against the running code or the owner’s confirmed business decision. Keep facts close to their topic; link from this index instead of duplicating them here.
