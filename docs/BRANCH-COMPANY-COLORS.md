# Branch & Company Color System

The Super Admin **Branches** page uses a **per-company color** so that branches of the same company share a consistent accent (cards, B1/B2 badges, accordion preview).

## How it works

- **Source:** `COMPANY_BORDER_COLORS` in `resources/views/super-admin/pages/branches.blade.php`.
- **Assignment:** `companyBorderColor(companyId)` returns `COMPANY_BORDER_COLORS[companyId % length]`, so every company gets a stable color based on its ID.
- **Usage:** Same color is used for:
  - Card left border and avatar badge (B1, B2, …)
  - Accordion header branch preview badges when the section is collapsed

## Adding or changing colors

1. Edit the `COMPANY_BORDER_COLORS` array (hex values).
2. New companies automatically get a color; if you add more hex values, the cycle extends.
3. Keep colors distinct from status (e.g. avoid reusing emerald for non-status elements).

No backend or config change is required—colors are purely front-end and deterministic by `company_id`.
