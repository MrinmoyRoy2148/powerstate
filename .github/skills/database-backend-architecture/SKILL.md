---
name: database-backend-architecture
description: Use when changing PowerStat BD Eloquent models, migrations, persistence, authorization, validation, or backend query behavior.
---

# Database and Backend Architecture

Keep PowerStat BD's records consistent, secure, and understandable.

## Schema and relationships

- Inspect existing migrations and Eloquent relations before adding columns, joins,
  or tables.
- Preserve primary keys and foreign-key behavior. Choose cascade, restrict, or
  null-on-delete deliberately according to record history requirements.
- Use database uniqueness and indexes for invariants and common filters where
  justified by actual queries.
- Keep status vocabulary consistent between forms, validation, controllers,
  public scopes, and tests.
- Protect hierarchical service-area data from invalid parent relationships and
  cycles when changing that workflow.

## Input and authorization

- Validate all write input at the request boundary and pass only validated fields.
- Enforce both authentication and role/activity authorization for admin actions.
- Derive owner, reporter, and creator identifiers from the authenticated user,
  never from request data.
- Scope related records to the selected area; reject mismatched schedules and
  inactive entities where the domain requires it.
- Keep mass assignment guarded and use explicit fields for force-fill operations.

## Record lifecycle and public visibility

- Model draft, published, active, restored, cancelled, and expiry semantics
  consistently. Define legal transitions and test failure cases.
- Public queries should not expose drafts, inactive areas, future publication
  windows, expired notices, or records that should remain private.
- Keep history intact when consumers need past records; do not physically remove
  records that serve as audit history without an approved retention policy.
- Mark restoration times only during restoration, and keep estimates distinct from
  actual restoration.

## Query and operational safety

- Eager-load relationships used by views and paginate public/admin collections.
- Avoid query work in Blade and repeated per-row queries.
- Keep secrets in environment/configuration, not application code.
- Do not invent utility source data or claim an integration exists unless verified.
- Add feature tests that assert response, authorization, validation, and database
  state for important write operations.
