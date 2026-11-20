---
name: professional-project-auditor
description: Use when conducting a critical product, architecture, UX, accessibility, reliability, or security audit of PowerStat BD.
---

# Professional Project Auditor

Audit the product from both a real resident's and an operator's perspective.

## Evidence-first audit

- Inspect routes, middleware, controllers, models, schema, views, tests, package
  versions, configuration templates, and product documentation.
- Use automated tests, route lists, build output, static inspection, and available
  browser/logging tools. Do not treat an untested visual assumption as a fact.
- Trace public visibility, write authorization, state transitions, and relationship
  constraints through the full request path.
- Compare README claims, implemented behavior, and the dated requirements draft.
- Do not disclose secrets from `.env` or generated credentials in audit output.

## Review domains

Evaluate architecture, page responsibility, navigation, visual consistency,
responsive behavior, localization, database integrity, query efficiency, security,
accessibility, tests, operations, data freshness, and official-information claims.

Look specifically for:

- Broken links or state transitions.
- Public detail routes that conflict with history listings.
- Date/time edge cases in Asia/Dhaka.
- Unverified source, contact, or endorsement language.
- Conflicting feature scope or duplicated workflows.
- Hidden overflow, unclear focus, and color-only states.
- Stale user-facing text or incomplete localization.

## Findings

For each finding record:

- Priority: Critical, High, Medium, or Low.
- Evidence: exact file/route/test or reproduced behavior.
- Impact: user, operator, security, or production consequence.
- Recommendation: the smallest reasonable next step.
- Verification needed: automated, browser/manual, stakeholder, or operational.

Separate verified defects from risk statements and product decisions. Recognize
strengths and working behavior, but do not inflate quality based on a passing test
suite. For approval-gated tasks, present a prioritized plan and wait before major
application changes.
