---
name: professional-qa-testing
description: Use when planning, writing, or executing tests and quality checks for PowerStat BD workflows.
---

# Professional QA and Testing

Verify observable workflows instead of only implementation details.

## Test strategy

- Read the implementation and nearby PHPUnit tests before choosing test structure.
- Use PHPUnit and Laravel feature tests already installed in this repository.
- For each changed request, cover authentication, role/activity permissions,
  validation, success behavior, and persisted state as applicable.
- Assert meaningful response behavior as well as database state for writes.
- Exercise failed writes and verify that no unintended state change occurred.
- Test boundary conditions for dates, schedule state, announcement visibility,
  inactive service areas, and record ownership/relationships where applicable.
- Keep test data isolated and deterministic. Avoid date assumptions that become
  incorrect as the calendar advances.

## Product workflows

- Browse without signing in, switch language, find an area, filter schedules and
  outages, open record details, review history, and browse announcements.
- Verify admin login/logout, inactive-account denial, role authorization, CRUD,
  publish/unpublish, restore/cancel, and invalid form handling.
- Check that pagination preserves filters and that empty states explain the
  result without implying the portal has exhaustive live data.

## Manual and browser checks

- Test representative Bangla and English content at 360, 390, 768, 1024, and
  desktop widths when browser tooling is available.
- Check keyboard-only navigation, focus visibility, dialogs, menu expansion,
  form labels, error messaging, contrast, and screen-reader status text.
- Inspect browser console and Laravel logs after exercising workflows.
- Check low-bandwidth rendering and avoid claiming measured performance without a
  reproducible dataset and stated threshold.

## Reporting

Record the exact command, pass/fail count, and any skipped checks. Distinguish
automated coverage from visual/manual review. Do not declare a workflow complete
from a successful page render alone.
