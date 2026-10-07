# TERAPOS Security Review

Review date: 2026-10-05

## Findings

| # | Severity | File | Lines | Finding | Confidence |
|---|---|---|---|---|---:|
| 1 | HIGH — RESOLVED | `app/Http/Controllers/Api/AuthController.php` | 52-68 | Public API registration created a `co_owner` account and issued an authentication token. The public `POST /api/register` route has been removed; the controller method remains unexposed and never takes the role from the request. | 9/10 |
| 2 | MEDIUM — RESOLVED | `app/Http/Controllers/ProductController.php` | 143-146 | Product deletion could cascade-delete sale details, purchase details, and stock movements. Product deletion now checks these history relationships and refuses deletion when any history exists. | 9/10 |

## Reviewed Areas With No Additional Findings

The review found no additional issues in the checked product mass-assignment handling, sale and purchase cancellation authorization, stock adjustment access and mutation flow, backup access/path checks, CSRF and route verbs, search query construction, Blade output escaping, or tracked secrets.

## Scope

This document records source-level findings and their remediation. No database changes, transactions, backup creation, migrations, or seeders were run as part of the review or fixes.
