# Super Admin Panel — Build Prompt

Use this prompt when implementing or extending the **Super Admin** side of Landogz POS.

## Goal

- Build a **Super Admin** web app with **login**.
- Use the **Midone (Enigma top menu)** template located at:
  - `public/midone-html.vercel.app`
- **Copy all necessary template files** (CSS, JS, images) into the project and **refurbish** them for the app.
- **Split layout into separate, reusable files**: header, body (content area), footer, sidebar/menu, and scripts.

## Template Source

- **Path:** `C:\laragon\www\RIARD\LandogzPOS\public\midone-html.vercel.app`
- **Reference pages:**
  - Login: `enigma-top-menu-login-page.html`
  - Dashboard: `enigma-top-menu-dashboard-overview-4-page.html` (or overview 1–3)
- **Assets:** `dist/css/`, `dist/js/`, `dist/images/`
- **Theme:** Enigma, top-menu layout.

## Requirements

1. **Super Admin login**
   - Login page (email/password or PIN as per existing API).
   - Auth via existing Laravel API (e.g. `POST /api/v1/auth/login`), store token (e.g. Sanctum), redirect to dashboard.
   - No page reload on submit: use Axios + SweetAlert for success/error.

2. **Layout structure (refurbished, separated files)**
   - **Head:** `<head>` content (meta, title, CSS) in one partial.
   - **Header / Top bar:** Top bar with logo, breadcrumb, search, notifications, user dropdown.
   - **Mobile menu:** Mobile sidebar/menu (same content as desktop nav, responsive).
   - **Top menu (desktop nav):** Horizontal nav for Dashboard, and future Super Admin menu items.
   - **Body / Content:** Main content wrapper; each page injects its content here.
   - **Footer (optional):** Optional footer bar or credits.
   - **Scripts:** All vendor and app JS at bottom of body.

3. **Tech stack (align with project rules)**
   - **Tailwind only** (template already uses Tailwind via `dist/css/app.css`).
   - **Axios** for all API calls (login, dashboard data, etc.).
   - **SweetAlert2** for confirmations and notifications (no `alert()` / `confirm()`).
   - **Laravel Blade** for layout and partials; keep one global CSS file, no inline styles.

4. **Responsive & cross‑platform**
   - Works on mobile, tablet, desktop; use Tailwind `sm:`, `md:`, `lg:` etc.
   - Test on Chrome, Safari, Firefox, Edge; Apple devices (iPhone, iPad, Mac).

5. **Asset handling**
   - Either:
     - **Option A:** Copy only necessary files from `public/midone-html.vercel.app/dist/` into a project folder (e.g. `public/super-admin/dist/`) and reference via `asset('super-admin/dist/...')`, or
     - **Option B:** Keep template under `public/midone-html.vercel.app/` and reference `asset('midone-html.vercel.app/dist/...')`.
   - Refurbish: remove theme-switcher if not needed; rename “Midone”/“Enigma” to “Landogz POS” or “Super Admin”; ensure all asset paths use Laravel `asset()`.

6. **Security**
   - Super Admin routes protected by auth middleware; only `super_admin` (and optionally `admin`) role can access.
   - Login uses existing API; token stored (e.g. localStorage or cookie) and sent with Axios.

## File Structure (Blade)

```
resources/views/super-admin/
├── layouts/
│   └── app.blade.php          # Main layout: includes head, header, menu, content slot, scripts
├── partials/
│   ├── head.blade.php         # Meta, title, CSS
│   ├── top-bar.blade.php      # Header / top bar (logo, breadcrumb, user dropdown, logout)
│   ├── mobile-menu.blade.php  # Mobile menu
│   ├── top-menu.blade.php     # Desktop top navigation
│   └── footer-scripts.blade.php # Vendor + app JS, logout handler
├── login.blade.php            # Standalone login page (no app layout)
└── dashboard.blade.php        # Dashboard page (uses app layout, content only)
```

**Routes:** `GET /super-admin/login` (login page), `GET /super-admin` (dashboard).  
**Controller:** `App\Http\Controllers\SuperAdmin\SuperAdminViewController`.

## Checklist

- [ ] Copy/refurbish necessary CSS, JS, images from Midone template.
- [ ] Create `partials/head.blade.php`, `top-bar.blade.php`, `mobile-menu.blade.php`, `top-menu.blade.php`, `footer-scripts.blade.php`.
- [ ] Create `layouts/app.blade.php` with `@yield('content')` and optional `@yield('title')`, `@stack('styles')`, `@stack('scripts')`.
- [ ] Create `login.blade.php` (standalone) with form, Axios submit, SweetAlert, redirect on success.
- [ ] Create `dashboard.blade.php` extending app layout with simple dashboard content.
- [ ] Add web routes for `/super-admin/login`, `/super-admin` (dashboard), and optional redirect from `/super-admin` to login if not authenticated.
- [ ] Add middleware or controller checks so only `super_admin` (and optionally `admin`) can access `/super-admin/*`.
- [ ] Use Axios + SweetAlert; no page reload on login.
- [ ] All responsive; Tailwind only; English UI.

## API (existing)

- Login: `POST /api/v1/auth/login` (or login-pin) — use response token for subsequent requests.
- Dashboard data: use existing `/api/v1/dashboard/*` endpoints (e.g. summary, branch-overview for super_admin).

Use this prompt when building or refactoring the Super Admin panel so the flow stays consistent and all rules (API-based, SPA-like, Tailwind, Axios, SweetAlert, separated layout files) are followed.
