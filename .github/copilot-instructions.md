# PowerStat BD Copilot Instructions

## Product context

PowerStat BD is a Bangladesh-focused, Bangla-first public information portal for
service areas, administrator-published outages, planned load-shedding schedules,
outage history, and announcements. It is not a utility control system, live
telemetry feed, official hotline, or guarantee of restoration. Do not imply
official endorsement or invent source, verification, or contact details.

The stakeholder baseline is a desk-based draft and explicitly requires validation
with residents and participating utilities before production. Treat unclear
product decisions as open questions rather than confirmed requirements.

## Before changing code

- Inspect the relevant routes, controllers, models, migrations, Blade views,
  translations, tests, and sibling implementations before editing.
- Preserve working behavior and the existing Laravel application structure.
- Make the smallest coherent change that solves an approved requirement.
- Do not add a feature, dependency, abstraction, or migration without a clear
  requirement and approval where the change is substantial.
- If an implementation plan is requested for a major change, present findings
  and the plan first, then wait for approval.
- Never use populated `.env` values in source or commit `.env` secrets.

## Product and information architecture

- Keep public browsing available without signing in.
- Keep each page focused on one user goal and avoid duplicate routes or actions.
- Make area, outage type/status, local timestamps, and estimate uncertainty clear.
- Distinguish administrator-entered information from verified official information.
- Do not add public reports, alerts, complaint tracking, or external utility
  integrations without product, operational, privacy, and moderation approval.
- Empty states must explain what the page represents and offer a relevant next step.

## Design and accessibility

- Use the established PowerStat BD visual language and the styling conventions of
  the page being changed. Public pages currently use Tailwind CSS v4; admin pages
  currently use Bootstrap 5. Do not migrate either surface without approval.
- Prefer restrained layouts, clear hierarchy, intentional whitespace, legible
  typography, and content-driven components over generic dashboard templates.
- Avoid decorative gradients, excessive cards, shadows, borders, animation, and
  oversized rounded containers unless they serve a specific purpose.
- Design for Bangla and English text, right-sized touch targets, and widths down to
  360 CSS pixels. Do not mask overflow as a substitute for fixing its cause.
- Use semantic headings, explicit form labels, visible keyboard focus, accessible
  names, and status text that does not rely on color alone.
- Preserve localization conventions and ensure new user-facing text is available
  in both supported locales where translation files are used.

## Engineering standards

- Stack: PHP 8.5 runtime, Laravel 13, Blade, Eloquent, PHPUnit 12, Vite, Tailwind
  CSS 4 on public pages, and Bootstrap 5 on admin pages. Confirm installed package
  versions before using version-sensitive APIs.
- Follow existing route naming, controller boundaries, Eloquent relationships,
  validation, authorization, error handling, and formatting patterns.
- Validate all request input; authorize protected writes; use named routes and
  escaped Blade output; never pass unvalidated request arrays to models.
- Preserve database integrity with appropriate foreign keys, uniqueness,
  validation, and tested lifecycle rules. Do not silently hide failed writes.
- Keep public queries limited to active/published records and paginate collections.
- Add focused feature tests for request behavior, authorization, validation,
  persistence, and user-visible outcomes.
- Run the narrow relevant test set, `php artisan test --compact` when appropriate,
  `vendor/bin/pint --dirty --format agent` for PHP edits, and `npm run build` for
  frontend edits.

## Security and operations

- Keep admin access role- and activity-checked; do not rely on authentication alone.
- Keep rate limits on sensitive authentication and password-reset endpoints.
- Do not add credentials, default admin passwords, or unverified utility contact
  information to source control.
- Treat dependency audit findings as production risks; do not suppress advisories
  or update dependencies without reviewing compatibility and approval.
- Before a production-readiness claim, check HTTPS/session settings, mail delivery,
  database backups, monitoring, recovery ownership, content freshness, and the
  utility's permission to publish information.

## Review standard

Report evidence-backed defects with priority, impact, and concrete file/behavior
references. Separate verified issues from open product decisions and untested
assumptions. Do not claim visual, accessibility, browser, or performance QA unless
those checks were actually performed.
