# Master Development Prompt (1st Prompt) — Gap Analysis

This document compares the **first prompt** (Pharmacy POS & Inventory System — single-stack, Blade/Tailwind, Philippine BIR) with the **current codebase** (built from the second prompt — multi-node Cloud/Local/POS architecture).

---

## Implemented (post “proceed” pass)

- **Auth**: `POST /api/v1/auth/login`, `POST /api/v1/auth/login-pin`, `POST /api/v1/auth/logout`, `GET /api/v1/auth/me`; login returns `{ token, user, branch, permissions[] }`.
- **Users**: Full CRUD `GET/POST/GET/PUT/DELETE /api/v1/users` with role, PIN, branch, pagination, search, sort.
- **Dashboard**: `GET /api/v1/dashboard/summary`, `low-stock-alerts`, `expiring-soon`, `sales-today`, `branch-overview`.
- **BIR**: `GET/PUT /api/v1/bir/settings`; `GET /api/v1/receipts/{transaction_id}` (BIR-style receipt JSON); `POST /api/v1/receipts/reprint/{id}` (logs to audit_logs).
- **Reports**: `GET /api/v1/reports/sales`, `inventory`, `profit-margin`, `expiring-products`, `top-selling`, `cashier-summary`, `vat-summary`, `audit-log` (filters: branch_id, date_from, date_to, etc.).
- **Branches**: `GET/POST /api/v1/branches`, `GET /api/v1/branches/{id}`, `GET /api/v1/branches/{id}/dashboard`, `GET /api/v1/branches/{id}/stock`, `POST /api/v1/branches/{id}/replenishment-request`.
- **Sync**: `POST /api/v1/sync/heartbeat` (local + cloud handlers).
- **Packages**: `spatie/laravel-permission` installed; User uses `HasRoles`; roles created in seeder.
- **Seeder**: `PharmaPOSDemoSeeder` — 1 company, 3 branches, 5 users (one per role), 51 products with batches (17 per branch), 100 transactions, BIR settings per branch, Spatie roles (web + sanctum).
- **API**: Consistent envelope `status: "success"`, `data`, `message`, `meta` where applicable.
- **API Resource**: `UserResource` (optional use in controllers).

---

## 1. Tech Stack Alignment

| Requirement (1st prompt) | Current state | Gap |
|--------------------------|---------------|-----|
| Laravel **11** | Laravel **10** | Minor: upgrade when PHP allows, or keep 10. |
| Backend: REST API | ✅ All endpoints are API, JSON | OK |
| Frontend: Blade + Tailwind or Inertia+Vue | ❌ No Blade/Inertia views; API-only + `resources/js/api/axios.js` | **Missing**: Full frontend (Blade/Tailwind or SPA). |
| HTTP Client: Axios | ✅ Axios config with `localApi` / `cloudApi` | OK |
| Database: MySQL (local) + online sync | ✅ MySQL + SyncService (push/pull/heartbeat) | OK (we also have node-based local/cloud). |
| Auth: Laravel Sanctum | ✅ Sanctum in use; all API routes use `auth:sanctum` | OK |
| Queue/Jobs for sync | ✅ SyncService + scheduler; **no** dedicated Job classes yet | Partial: add `app/Jobs` for sync. |
| Offline: IndexedDB / LocalStorage | ❌ Not implemented | **Missing**: Dexie.js / IndexedDB and offline queue in frontend. |

---

## 2. Architecture & Structure

| Requirement | Current state | Gap |
|-------------|---------------|-----|
| Repository Pattern + Service Layer | Only `App\Services\SyncService` | **Missing**: `App\Repositories\*`, and Services for POS/Inventory/Reports. |
| API Resource Transformers | No `app/Http/Resources` | **Missing**: API Resources for consistent JSON shape. |
| API versioning `/api/v1/` | ✅ Routes under `api` + `v1` prefix | OK |
| Response envelope: status, data, message, errors | Controllers return `status`, `data`, `message`; not always `meta`; sometimes `status: true` vs `"success"` | **Partial**: Standardize to `status: "success"|"error"`, add `meta` for pagination. |
| Folder: `Api/V1/`, Resources, Requests, Services, Repositories, Jobs | Controllers in `API\` (not `Api\V1\`); no Resources, no FormRequests; one Service; no Repositories; no Jobs | **Missing**: FormRequests, Resources, Repositories, Jobs. |

---

## 3. Database Schema — Core Tables

| Table (1st prompt) | Current migrations | Gap |
|--------------------|--------------------|-----|
| companies | ✅ | OK |
| branches | ✅ | OK |
| users (branch_id, role) | ✅ + pin_hash, is_active | OK (roles are string; prompt wants Spatie). |
| products | ✅ | OK |
| product_batches | ✅ | OK |
| categories | ✅ | OK |
| suppliers | ✅ | OK |
| stock_ins / stock_outs / stock_transfers | ✅ | OK |
| transactions / transaction_items | ✅ | OK |
| discounts | ✅ | OK |
| official_receipts | ✅ | OK |
| bir_settings | ✅ | OK |
| audit_logs | ✅ | OK |
| sync_logs | ✅ | OK |

Schema is aligned. Optional: add `dosage` on products if required by prompt “dosage”.

---

## 4. Module-by-Module: API Endpoints

### 4.1 Authentication & User Management

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `POST /api/v1/auth/login` | ❌ | **Missing**. |
| `POST /api/v1/auth/logout` | ❌ | **Missing**. |
| `GET /api/v1/auth/me` | ✅ `/api/user` (Sanctum) | Align path to `/api/v1/auth/me` or keep and document. |
| `POST/GET/PUT/DELETE /api/v1/users` | ❌ | **Missing**: User CRUD API. |
| Roles: super_admin, admin, manager, pharmacist, cashier | ✅ User has `role` string | **Partial**: No Spatie roles/permissions yet. |
| PIN-based quick login for cashiers | ✅ `pin_hash` on users | **Partial**: No PIN login endpoint. |
| Login response: `{ token, user, branch, permissions[] }` | ❌ | **Missing**. |

**Action**: Add auth routes (login, logout, me), User CRUD, optional Spatie permission + PIN login endpoint.

---

### 4.2 Dashboard Module

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `GET /api/v1/dashboard/summary` | ❌ | **Missing**. |
| `GET /api/v1/dashboard/low-stock-alerts` | ✅ `GET /api/v1/inventory/low-stock` (local) | Path differs; no single “dashboard” namespace. |
| `GET /api/v1/dashboard/expiring-soon` | ✅ `GET /api/v1/inventory/expiring` | Same. |
| `GET /api/v1/dashboard/sales-today` | ❌ | **Missing** (chain has `dashboard/chain`). |
| `GET /api/v1/dashboard/branch-overview` | ✅ Cloud: `dashboard/chain`, `reports/consolidated` | Concept covered; path/name differ. |

**Action**: Add `DashboardController` with summary, sales-today, low-stock-alerts, expiring-soon, branch-overview (or alias existing endpoints under `/api/v1/dashboard/*`).

---

### 4.3 POS Sales Module

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `POST /api/v1/transactions` | ✅ `POST /api/v1/pos/transactions` | Same behavior; path differs. |
| `GET /api/v1/transactions` | ✅ `GET /api/v1/transactions` (shared) | OK |
| `GET /api/v1/transactions/{id}` | ✅ | OK |
| `POST /api/v1/transactions/{id}/void` | ✅ `POST /api/v1/pos/transactions/{id}/void` | OK |
| `GET /api/v1/transactions/{id}/receipt` | ✅ `GET /api/v1/pos/transactions/{id}/receipt` | OK |
| `POST /api/v1/products/lookup` | ✅ `GET /api/v1/pos/products/search?q=` | Same; prompt says POST lookup. |

Features: barcode lookup ✅, FEFO deduction ✅, OR number ✅, payment methods ✅. Offline queue ❌ (no IndexedDB).

**Action**: Optional: add alias routes under `/api/v1/transactions` and `/api/v1/products/lookup` to match prompt verbatim; implement offline queue in frontend.

---

### 4.4 Inventory Management

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `GET/POST/PUT/DELETE /api/v1/products` | ✅ Under `inventory/products` and shared `v1/products` | Paths differ; prompt uses `/api/v1/products`. |
| `GET /api/v1/products/{id}/batches` | ✅ `GET /api/v1/inventory/batches/{product}` | Path differs. |
| `POST /api/v1/stock-ins` | ✅ `POST /api/v1/inventory/stock-in` | Path differs. |
| `POST /api/v1/stock-outs` | ✅ `POST /api/v1/inventory/stock-out` | Path differs. |
| `POST /api/v1/stock-transfers` | ✅ `POST /api/v1/inventory/stock-transfer` | Path differs. |
| `GET /api/v1/inventory/valuation` | ✅ | OK |

Product images (Spatie Media Library): ❌ Not installed.

**Action**: Either add route aliases to match prompt (`/api/v1/products`, `/api/v1/stock-ins`, etc.) or document current paths; add Spatie Media Library for product images.

---

### 4.5 BIR-Compliant Receipts

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `GET /api/v1/receipts/{transaction_id}` | ✅ Data via `GET pos/transactions/{id}/receipt` | Prompt path: `receipts/{transaction_id}`. |
| `POST /api/v1/receipts/reprint/{id}` | ❌ | **Missing**: reprint logging. |
| `GET/PUT /api/v1/bir/settings` | ❌ | **Missing**: BIR settings API. |
| Receipt content (OR#, TIN, VAT, Senior/PWD, footer) | Partial in DB (official_receipts, bir_settings) | **Missing**: Full receipt JSON structure and 80mm HTML/print template. |

**Action**: Add `GET/PUT /api/v1/bir/settings`, `GET /api/v1/receipts/{transaction_id}`, `POST /api/v1/receipts/reprint/{id}`; implement receipt template (80mm) and optional dompdf/print.

---

### 4.6 Reporting & Analytics

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `GET /api/v1/reports/sales` | ❌ | **Missing**. |
| `GET /api/v1/reports/inventory` | ❌ | **Missing** (we have stock-levels, valuation). |
| `GET /api/v1/reports/profit-margin` | ❌ | **Missing**. |
| `GET /api/v1/reports/expiring-products` | ✅ `inventory/expiring` | Path differs. |
| `GET /api/v1/reports/top-selling` | ❌ | **Missing**. |
| `GET /api/v1/reports/cashier-summary` | ✅ `pos/cashier/summary` | Path differs. |
| `GET /api/v1/reports/vat-summary` | ❌ | **Missing**. |
| `GET /api/v1/reports/audit-log` | ❌ | **Missing**. |
| Export PDF (dompdf) / Excel (maatwebsite/excel) | ❌ | **Missing**; packages not installed. |

**Action**: Add Reports module (sales, inventory, profit-margin, top-selling, vat-summary, audit-log); add PDF/Excel export and install dompdf + laravel-excel.

---

### 4.7 Sync (Offline-First)

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `POST /api/v1/sync/push` | ✅ (local node) | OK |
| `GET /api/v1/sync/pull` | ✅ (local node) | OK |
| `POST /api/v1/sync/heartbeat` | ✅ In scheduler; no HTTP route | **Missing**: Expose `POST /api/v1/sync/heartbeat` for terminals. |
| Queue `sync_queue` for processing | SyncService runs in scheduler; no Job class | **Partial**: Add Job for push processing. |
| Conflict resolution / sync_logs | ✅ SyncLog model; conflict logic minimal | OK |

**Action**: Add `POST /api/v1/sync/heartbeat`; optional Job for sync push.

---

### 4.8 Multi-Branch / Central Monitoring

| Endpoint (1st prompt) | Current | Gap |
|-----------------------|---------|-----|
| `GET /api/v1/branches` | ✅ (cloud) | OK |
| `POST /api/v1/branches` | ❌ | **Missing**. |
| `GET /api/v1/branches/{id}/dashboard` | ❌ | **Missing**. |
| `GET /api/v1/branches/{id}/stock` | ❌ | **Missing**. |
| `POST /api/v1/branches/{id}/replenishment-request` | ❌ | **Missing** (table exists). |

**Action**: Add branch create, branch dashboard, branch stock, replenishment-request.

---

## 5. Laravel Packages (1st prompt)

| Package | Installed | Gap |
|---------|-----------|-----|
| spatie/laravel-permission | ❌ | **Missing**. |
| spatie/laravel-medialibrary | ❌ | **Missing**. |
| barryvdh/laravel-dompdf | ❌ | **Missing**. |
| maatwebsite/laravel-excel | ❌ | **Missing**. |
| laravel/sanctum | ✅ | OK |
| spatie/laravel-activitylog | ❌ | **Missing** (we have audit_logs table; no package). |
| laravel/horizon | ❌ | **Missing**. |

**Action**: Install and configure Spatie permission + medialibrary, dompdf, laravel-excel; optionally activitylog and Horizon.

---

## 6. Frontend (1st prompt)

| Item | Current | Gap |
|------|---------|-----|
| TailwindCSS | Not configured for this project | **Missing**. |
| Alpine.js | Not added | **Missing**. |
| Axios (base URL, auth, 401/422/offline) | ✅ `resources/js/api/axios.js` | OK |
| POS: full-screen, large touch targets | No POS UI | **Missing**. |
| Dashboard: responsive grid | No dashboard UI | **Missing**. |
| 80mm thermal receipt CSS | No print template | **Missing**. |

**Action**: Add Tailwind + Alpine; build Blade (or SPA) for login, dashboard, POS, receipt print.

---

## 7. Security & Mobile API

| Requirement | Current | Gap |
|-------------|---------|-----|
| HTTPS | Config | Document for production. |
| Sanctum token expiry (e.g. 8h) | Default | Configure in config/sanctum.php. |
| Audit log for sensitive actions | audit_logs table exists; no automatic logging | **Partial**: Use Observers or activitylog. |
| Rate limit: 60/min cashier, 200/min admin | Default throttle only | **Missing**: Per-role rate limiting. |
| FormRequest validation | Inline `$request->validate()` | **Partial**: Add FormRequests. |
| CORS for mobile | Laravel default | Configure allowed origins. |
| Pagination / sort / filter | Some controllers; not standardized | **Partial**: Add `?page=&per_page=&sort_by=&sort_dir=`. |
| JSON envelope + meta | Inconsistent | Standardize. |

---

## 8. Seeder & Demo Data (1st prompt)

| Item | Current | Gap |
|------|---------|-----|
| 1 company, 3 branches | ❌ | **Missing**. |
| 5 users (one per role) | ❌ | **Missing**. |
| 50 products with batches | ❌ | **Missing**. |
| 100 transactions | ❌ | **Missing**. |
| BIR settings per branch | ❌ | **Missing**. |
| Roles and permissions | ❌ (no Spatie) | **Missing**. |

**Action**: Implement `DatabaseSeeder` (and RoleSeeder) as per prompt.

---

## 9. Summary: Priority Actions to Align with 1st Prompt

1. **Auth & users**: Add `POST /api/v1/auth/login`, `logout`, `GET /api/v1/auth/me`, full User CRUD; optional Spatie permission + PIN login.
2. **Dashboard**: Add `/api/v1/dashboard/summary`, `sales-today`, and alias or move low-stock/expiring under dashboard.
3. **Route aliases**: Optionally add prompt-style routes (`/api/v1/products`, `/api/v1/stock-ins`, `/api/v1/receipts/{id}`, `/api/v1/bir/settings`, etc.) or keep current and document.
4. **BIR**: Add `GET/PUT /api/v1/bir/settings`, `GET /api/v1/receipts/{transaction_id}`, `POST /api/v1/receipts/reprint/{id}`; receipt template.
5. **Reports**: Add sales, inventory, profit-margin, top-selling, vat-summary, audit-log; PDF/Excel (dompdf, laravel-excel).
6. **Branches**: Add `POST /api/v1/branches`, `GET /api/v1/branches/{id}/dashboard`, `branches/{id}/stock`, `branches/{id}/replenishment-request`.
7. **Sync**: Add `POST /api/v1/sync/heartbeat`; optional Job for push.
8. **Packages**: Install Spatie permission + medialibrary, dompdf, laravel-excel; optional activitylog, Horizon.
9. **Architecture**: Add FormRequests, API Resources, Repositories, and Service layer for main modules.
10. **Frontend**: Tailwind, Alpine, Blade (or SPA) for login, dashboard, POS, receipts.
11. **Seeder**: Company, branches, users (roles), products, transactions, BIR settings.

Use this as the single source of truth to close gaps between the first prompt and the current codebase.
