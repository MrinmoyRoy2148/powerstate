---
name: senior-full-stack-engineering
description: Use when implementing or reviewing full-stack PowerStat BD changes across Laravel, Blade, routes, assets, and tests.
---

# Senior Full-Stack Engineering

Deliver small, end-to-end changes using the repository's existing architecture.

## Understand before changing

- Confirm PHP/Laravel and JavaScript package versions from the manifests and
  installed packages.
- Trace the affected request through route, middleware, controller, model,
  migration, Blade view, translations, and tests.
- Read neighboring code to match established patterns.
- Check the worktree and preserve unrelated user changes.

## Laravel implementation

- Keep controllers responsible for HTTP input, authorization, validation,
  application coordination, and response generation.
- Use typed parameters and return types, named routes, implicit binding where
  appropriate, Eloquent relationships, and validated input.
- Apply authorization to protected writes, not just authentication.
- Keep model mass-assignment boundaries explicit and never trust client-provided
  role, publisher, or ownership attributes.
- Validate lifecycle changes, cross-record references, local dates, and status
  transitions. Report failures explicitly.
- Use migrations with foreign keys and useful indexes when schema changes are
  approved. Never change schema through ad hoc production edits.
- Keep public responses scoped to intended active and published records.

## Frontend implementation

- Public interface: Blade and Tailwind CSS v4. Admin interface: Blade and Bootstrap
  5. Follow the relevant surface's conventions.
- Use escaped Blade interpolation for untrusted text.
- Preserve CSRF protection on state-changing forms.
- Ensure controls work with keyboard and touch, expose state accessibly, and have
  real behavior rather than placeholder interactions.
- Keep language strings localized and accommodate Bangla line length.

## Change quality

- Make a minimal coherent diff. Avoid dependencies and speculative abstractions.
- Add focused feature tests for changed request behavior and persisted state.
- Run the smallest relevant tests, then formatting/build checks required by touched
  code.
- Re-read the final diff for scope, behavior, security, and regression risks.
- Do not claim a browser or production environment was tested when it was not.
