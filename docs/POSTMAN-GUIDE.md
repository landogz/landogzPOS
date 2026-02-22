# PharmaPOS / Landogz POS — Postman Guide

Use this guide to test the API with Postman.

**Developers:** For a single-page API reference with example code in **cURL, Ruby, Python, PHP, Java, C# (.NET), and JavaScript**, open [api-documentation.html](api-documentation.html) in a browser.

---

## 1. Base URL & Environment

- **Base URL:** `http://localhost:8000` (or your server, e.g. `http://192.168.1.100:8000`)
- **API prefix:** All endpoints use `/api/v1/`

In Postman:

1. Create an **Environment** (e.g. "PharmaPOS Local").
2. Add a variable:
   - **Variable:** `base_url`  
   - **Initial Value:** `http://localhost:8000`
3. Add:
   - **Variable:** `token`  
   - **Initial Value:** *(leave empty; will be set after login)*

Use `{{base_url}}` and `{{token}}` in requests.

---

## 2. Authentication

### Step 1: Login (get token)

**Request**

- **Method:** `POST`
- **URL:** `{{base_url}}/api/v1/auth/login`
- **Headers:** `Content-Type: application/json`
- **Body (raw, JSON):**

```json
{
  "email": "admin@demo.pharmapos.test",
  "password": "password"
}
```

**Response (200)** — copy `data.token`:

```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "token": "1|abc123...",
    "user": { "id": 1, "name": "Admin User", "email": "admin@demo.pharmapos.test", "role": "admin", ... },
    "branch": { "id": 1, "name": "Main Branch", ... },
    "permissions": ["dashboard", "users", "products", ...]
  }
}
```

**Save the token**

- In Postman: **Tests** tab for this request, add:

```javascript
var json = pm.response.json();
if (json.data && json.data.token) {
  pm.environment.set("token", json.data.token);
}
```

- Or manually set `token` in your Environment to the value of `data.token`.

### Step 2: Use token on all other requests

For every request that needs auth, set **HTTP Headers** (not query params):

- Open the **Headers** tab (not Params / Query Params).
- Add:
  - **Key:** `Authorization` → **Value:** `Bearer {{token}}` (space after "Bearer", include the full token including the part like `5|...`)
  - **Key:** `Accept` → **Value:** `application/json`
  - **Key:** `Content-Type` → **Value:** `application/json`

**Recommended:** Use Postman's **Authorization** tab: set Type to **Bearer Token** and paste the token in the Token field. Postman will send it as the `Authorization` header.

**Do not** put `Authorization`, `Token`, `Accept`, or `Content-Type` in **Query Params** (Params tab). The server only reads the token from the `Authorization` header.

---

### Troubleshooting: "Unauthenticated"

If you get `{"message":"Unauthenticated."}`:

1. **Token in Headers, not in URL**  
   Remove any `Authorization`, `Token`, `Accept`, or `Content-Type` from the **Params** tab. The URL must be only:  
   `{{base_url}}/api/v1/dashboard/summary` (no `?Authorization=...`).

2. **Use the Headers tab**  
   Add `Authorization: Bearer <your-full-token>` in the **Headers** tab (or use Authorization → Bearer Token and paste the token).

3. **Full token**  
   Use the whole token from login (e.g. `5|IGEXy4tXHYNR8w94XxmsluRv46BcPpYOgRZzTd6d9afcbd6a`). No extra spaces.

4. **New token**  
   Login again (POST `/api/v1/auth/login`) and use the new `data.token`; old tokens are revoked on login.

5. **If using Laragon/Apache**  
   The app fixes the Authorization header in `public/index.php`. If it still fails, try running `php artisan serve` and use `http://127.0.0.1:8000` as base URL to test.

---

## 3. Auth Endpoints

| Method | URL | Body / Notes |
|--------|-----|--------------|
| POST | `{{base_url}}/api/v1/auth/login` | `{"email":"admin@demo.pharmapos.test","password":"password"}` |
| POST | `{{base_url}}/api/v1/auth/login-pin` | `{"branch_id":1,"pin":"1234"}` (cashier quick login) |
| GET | `{{base_url}}/api/v1/auth/me` | Needs `Authorization: Bearer {{token}}` |
| POST | `{{base_url}}/api/v1/auth/logout` | Needs token; revokes current token |

---

## 4. Dashboard

All need: `Authorization: Bearer {{token}}`, `Accept: application/json`.

| Method | URL |
|--------|-----|
| GET | `{{base_url}}/api/v1/dashboard/summary` |
| GET | `{{base_url}}/api/v1/dashboard/sales-today` |
| GET | `{{base_url}}/api/v1/dashboard/low-stock-alerts` |
| GET | `{{base_url}}/api/v1/dashboard/expiring-soon` (optional: `?days=30`) |
| GET | `{{base_url}}/api/v1/dashboard/branch-overview` |

---

## 5. Users

All need: `Authorization: Bearer {{token}}`.

**Manager accounts:** Can only create and manage users for **their own branch**, and only with roles **`cashier`** or **`inventory_manager`**. They cannot create admins, managers, or pharmacists. Admin and super_admin can create any role and any branch.

| Method | URL | Body (JSON) / Query |
|--------|-----|----------------------|
| GET | `{{base_url}}/api/v1/users` | Query: `?per_page=15&page=1&branch_id=1&role=cashier&search=admin&sort_by=name&sort_dir=asc`. Manager sees only their branch. |
| POST | `{{base_url}}/api/v1/users` | See below |
| GET | `{{base_url}}/api/v1/users/1` | — |
| PUT | `{{base_url}}/api/v1/users/1` | See below. Manager can only update users in their branch; role limited to cashier/inventory_manager. |
| DELETE | `{{base_url}}/api/v1/users/1` | Deactivates user. Manager can only deactivate users in their branch. |

**POST /users** body example:

```json
{
  "branch_id": 1,
  "name": "New Cashier",
  "email": "newcashier@demo.test",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "cashier",
  "pin": "5678",
  "is_active": true
}
```

Allowed `role` values: `super_admin`, `admin`, `manager`, `inventory_manager`, `pharmacist`, `cashier`. Manager can only send `cashier` or `inventory_manager`; `branch_id` is forced to the manager's branch.

**PUT /users/{id}** body example (all fields optional):

```json
{
  "name": "Updated Name",
  "email": "updated@demo.test",
  "role": "manager",
  "pin": "9999",
  "is_active": true
}
```

---

## 6. Products (catalog)

**Branch scoping:** Super admin and admin see all products across all branches; manager, inventory_manager, and cashier see only their assigned branch (branch_id required).

| Method | URL | Notes |
|--------|-----|--------|
| GET | `{{base_url}}/api/v1/products` | Query: `?per_page=15&page=1&branch_id=1`. Super admin/admin see all; others see their branch only. |
| GET | `{{base_url}}/api/v1/products/1` | Branch-scoped users can only view products in their branch. |

---

## 7. Inventory (local node)

**Branch scoping:** Super admin and admin see all products and inventory across branches; manager and inventory_manager must have an assigned branch and see only that branch (403 otherwise). Stock-in/out and receive-delivery require product in user's branch; stock-transfer from_branch_id must be user's branch for manager/inventory_manager.

If your app is running as **local** node (`NODE_TYPE=local`), these work. Same headers: `Authorization: Bearer {{token}}`, `Accept: application/json`.

| Method | URL | Body (JSON) |
|--------|-----|-------------|
| GET | `{{base_url}}/api/v1/inventory/products` | Query: `?per_page=15` |
| POST | `{{base_url}}/api/v1/inventory/products` | See below |
| GET | `{{base_url}}/api/v1/inventory/products/1` | — |
| PUT | `{{base_url}}/api/v1/inventory/products/1` | Partial update |
| DELETE | `{{base_url}}/api/v1/inventory/products/1` | Deactivates |
| GET | `{{base_url}}/api/v1/inventory/stock-levels` | — |
| GET | `{{base_url}}/api/v1/inventory/batches/1` | 1 = product_id |
| POST | `{{base_url}}/api/v1/inventory/stock-in` | See below |
| POST | `{{base_url}}/api/v1/inventory/stock-out` | See below |
| POST | `{{base_url}}/api/v1/inventory/stock-transfer` | See below |
| GET | `{{base_url}}/api/v1/inventory/expiring` | Optional: `?days=90` |
| GET | `{{base_url}}/api/v1/inventory/low-stock` | — |
| GET | `{{base_url}}/api/v1/inventory/valuation` | — |
| GET | `{{base_url}}/api/v1/inventory/purchase-history` | Query: `?per_page=15` |

**POST /inventory/products:**

```json
{
  "barcode": "8901234567890",
  "name": "Paracetamol 500mg",
  "generic_name": "Paracetamol",
  "brand": "Generic",
  "category_id": 1,
  "unit": "box",
  "price": 25.00,
  "cost": 15.00,
  "reorder_level": 10
}
```

**POST /inventory/stock-in:**

```json
{
  "product_id": 1,
  "supplier_id": 1,
  "quantity": 100,
  "cost": 10.50,
  "batch_number": "BATCH-001",
  "expiry_date": "2026-12-31"
}
```

**POST /inventory/stock-out:**

```json
{
  "product_id": 1,
  "product_batch_id": 1,
  "quantity": 5,
  "reason": "Expired"
}
```

**POST /inventory/stock-transfer:**

```json
{
  "from_branch_id": 1,
  "to_branch_id": 2,
  "product_id": 1,
  "product_batch_id": 1,
  "quantity": 20
}
```

---

## 8. POS (local node)

Requires **local** node (`NODE_TYPE=local`). All need `Authorization: Bearer {{token}}`, `Accept: application/json`. **Terminal must be registered** (by Super Admin in Terminals settings); if not, POS returns 403 "POS is not registered. Please register this terminal in Settings."

| Method | URL | Body (JSON) |
|--------|-----|-------------|
| GET | `{{base_url}}/api/v1/pos/terminal/check?terminal_id=1` | Query: `terminal_id`. Use on POS startup; 403 if not registered. |
| POST | `{{base_url}}/api/v1/pos/session/open` | See below. **terminal_id required.** |
| POST | `{{base_url}}/api/v1/pos/session/close` | `{"session_id":1,"closing_cash":1500}` |
| GET | `{{base_url}}/api/v1/pos/products/search?q=paracetamol` | Query: `q` = barcode or name |
| POST | `{{base_url}}/api/v1/pos/transactions` | See below. **terminal_id required.** |
| GET | `{{base_url}}/api/v1/pos/transactions/1/receipt` | — |
| POST | `{{base_url}}/api/v1/pos/transactions/1/void` | — |
| GET | `{{base_url}}/api/v1/pos/cashier/summary` | — |

**GET /pos/terminal/check?terminal_id=1**  
Call on POS startup. Returns 200 with terminal data if registered and active; 403 "POS is not registered" otherwise.

**POST /pos/session/open:**  
**terminal_id is required.** Must be a registered, active terminal for the cashier's branch (from `GET /api/v1/branches/{branch}/terminals`). If not registered or inactive, response is 403 "POS is not registered."

```json
{
  "terminal_id": 1,
  "opening_cash": 1000
}
```

**POST /pos/transactions** (complete sale):  
**terminal_id is required.** Must be a registered, active terminal. If not, response is 403 "POS is not registered."

```json
{
  "items": [
    {
      "product_id": 1,
      "product_batch_id": 1,
      "quantity": 2,
      "unit_price": 25.00
    }
  ],
  "payment_method": "cash",
  "discount_amount": 0,
  "terminal_id": 1
}
```

---

## 9. Transactions (list / detail)

| Method | URL |
|--------|-----|
| GET | `{{base_url}}/api/v1/transactions` — Query: `?per_page=15&page=1&branch_id=1` |
| GET | `{{base_url}}/api/v1/transactions/1` |

---

## 10. Receipts & BIR

| Method | URL | Body / Notes |
|--------|-----|--------------|
| GET | `{{base_url}}/api/v1/receipts/1` | 1 = transaction_id; returns receipt JSON for printing |
| POST | `{{base_url}}/api/v1/receipts/print` | Body: `{"transaction_id":1}` |
| POST | `{{base_url}}/api/v1/receipts/reprint/1` | 1 = transaction_id; logs reprint |
| GET | `{{base_url}}/api/v1/bir/settings` | Query: `?branch_id=1` |
| PUT | `{{base_url}}/api/v1/bir/settings` | See below |

**PUT /bir/settings** body:

```json
{
  "branch_id": 1,
  "tin": "123-456-789-000",
  "accreditation_number": "BIR-ACC-001",
  "series_start": "0001",
  "series_end": "0999",
  "valid_from": "2025-01-01",
  "valid_until": "2025-12-31",
  "footer_text": "This document is not valid for claim of input tax."
}
```

---

## 11. Reports

All GET; add query params as needed. Headers: `Authorization: Bearer {{token}}`, `Accept: application/json`.

| Method | URL | Query params |
|--------|-----|--------------|
| GET | `{{base_url}}/api/v1/reports/sales` | `date_from`, `date_to`, `group_by` (day\|month\|branch), `branch_id` |
| GET | `{{base_url}}/api/v1/reports/inventory` | `branch_id`, `category_id` |
| GET | `{{base_url}}/api/v1/reports/profit-margin` | `date_from`, `date_to`, `branch_id` |
| GET | `{{base_url}}/api/v1/reports/top-selling` | `date_from`, `date_to`, `per_page`, `branch_id` |
| GET | `{{base_url}}/api/v1/reports/expiring-products` | `days`, `branch_id` |
| GET | `{{base_url}}/api/v1/reports/cashier-summary` | `date_from`, `date_to`, `branch_id` |
| GET | `{{base_url}}/api/v1/reports/vat-summary` | `date_from`, `date_to`, `branch_id` |
| GET | `{{base_url}}/api/v1/reports/audit-log` | `branch_id`, `user_id`, `action`, `date_from`, `date_to`, `per_page` |

Example:  
`{{base_url}}/api/v1/reports/sales?date_from=2025-02-01&date_to=2025-02-20&group_by=day`

---

## 12. Branches

**Only super_admin and admin can create branches** (e.g. to set up manager branches). Managers and other roles can only view and use their own branch.

| Method | URL | Body (JSON) |
|--------|-----|-------------|
| GET | `{{base_url}}/api/v1/branches` | — (manager sees only their branch) |
| POST | `{{base_url}}/api/v1/branches` | See below. **Super admin / admin only.** |
| GET | `{{base_url}}/api/v1/branches/1` | — (non-admin only if branch is theirs) |
| GET | `{{base_url}}/api/v1/branches/1/dashboard` | — |
| GET | `{{base_url}}/api/v1/branches/1/stock` | — |
| POST | `{{base_url}}/api/v1/branches/1/replenishment-request` | See below |

**POST /branches:** (super_admin or admin only)

```json
{
  "company_id": 1,
  "name": "Branch 4",
  "address": "456 New St",
  "tin": "123-456-789-004",
  "bir_series_start": "4001",
  "bir_series_end": "4999"
}
```

**POST /branches/{id}/replenishment-request:**

```json
{
  "product_id": 1,
  "quantity_requested": 50
}
```

### Terminals (POS terminal settings — Super Admin only)

POS terminals must be registered per branch. **Only Super Admin** can add, edit, or delete terminals (Settings for adding POS terminals). If a terminal is not registered, POS returns 403 "POS is not registered. Please register this terminal in Settings."

| Method | URL | Body (JSON) |
|--------|-----|-------------|
| GET | `{{base_url}}/api/v1/branches/1/terminals` | — |
| POST | `{{base_url}}/api/v1/branches/1/terminals` | See below. **Super Admin only.** |
| GET | `{{base_url}}/api/v1/branches/1/terminals/1` | — |
| PUT | `{{base_url}}/api/v1/branches/1/terminals/1` | See below. **Super Admin only.** |
| DELETE | `{{base_url}}/api/v1/branches/1/terminals/1` | **Super Admin only.** |

**POST /branches/{id}/terminals:** (Super Admin only)

```json
{
  "code": "T1",
  "name": "Counter 1",
  "is_active": true
}
```

POS session open and POS transactions **require** a registered, active `terminal_id`; otherwise the API returns 403 "POS is not registered."

---

## 13. Sync (local node)

| Method | URL | Body |
|--------|-----|------|
| POST | `{{base_url}}/api/v1/sync/push` | — |
| GET | `{{base_url}}/api/v1/sync/pull` | — |
| POST | `{{base_url}}/api/v1/sync/heartbeat` | Optional: `{"branch_id":1,"node_id":1}` |

---

## 14. Quick checklist in Postman

1. Create Environment with `base_url` and `token`.
2. **POST** `{{base_url}}/api/v1/auth/login` with email/password; in **Tests** save `data.token` into `token`.
3. Set **Authorization** to Bearer Token `{{token}}` at folder or collection level.
4. Add **Headers** on the collection: `Accept: application/json`, `Content-Type: application/json`.
5. Clone requests from the tables above and replace `1` in URLs with real IDs after you create data.

---

## 15. Demo credentials (after seeding)

| Role        | Email                         | Password | PIN (for login-pin) |
|------------|--------------------------------|----------|----------------------|
| Super Admin| super_admin@demo.pharmapos.test | password | —                    |
| Admin      | admin@demo.pharmapos.test      | password | —                    |
| Manager    | manager@demo.pharmapos.test     | password | —                    |
| Pharmacist | pharmacist@demo.pharmapos.test  | password | —                    |
| Cashier    | cashier@demo.pharmapos.test     | password | 1234                 |

PIN login: **POST** `/api/v1/auth/login-pin` with `{"branch_id":1,"pin":"1234"}`.
