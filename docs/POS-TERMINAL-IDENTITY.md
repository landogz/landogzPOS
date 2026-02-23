# POS: How the system identifies the terminal

This document explains what a **terminal** is in the system and how the POS (layout and API flow) identifies **which terminal** is in use.

---

## Terminal API key (Super Admin)

- **Super Admin** can generate a **terminal API key** from **Dashboard → Terminals**.
- Each terminal can have one key. **Generate key** creates a new key (e.g. `poskey_xxxxx...`); the value is shown **once** — copy it and add it to your POS device **.env**:
  ```env
  TERMINAL_API_KEY=poskey_xxxxxxxxxxxxxxxxxxxxxxxx
  ```
- The POS app sends this key in **every request** (e.g. `X-API-Key: poskey_...` or `Authorization: Bearer poskey_...`). The server then **identifies which terminal** is calling and treats the request as that terminal (no need to send `terminal_id` in the body when using the key).
- **Revoke key** removes the key so the device can no longer identify as that terminal. You can generate a new key anytime.
- **Registered** means the terminal has a key. **Last used** shows when the key was last used, so Super Admin can see which terminals are in use.

---

## 1. What is a terminal?

- A **terminal** is one physical POS counter or device (e.g. “Counter 1”, “T1”) at a branch.
- It is stored in the `terminals` table: `id`, `branch_id`, `code`, `name`, `is_active`.
- Each branch can have many terminals (e.g. T1, T2, T3). Super Admin creates them under **Branches → [Branch] → Terminals** (or via API `GET/POST /api/v1/branches/{branch}/terminals`).

---

## 2. How the system identifies the terminal (current design)

The server does **not** detect the terminal by itself. The **client** (POS app or browser) must tell the server which terminal is in use by sending **`terminal_id`**.

### 2.1 Where the client gets `terminal_id`

1. **Cashier logs in** (Sanctum token; user has `branch_id`).
2. **Client loads terminals for that branch:**  
   `GET /api/v1/branches/{branch_id}/terminals`  
   (Super Admin only, or a role that can list terminals; otherwise the POS might get the list after login via an endpoint that returns “my branch’s terminals”.)
3. **Client chooses one terminal:**
   - **Option A – Selector in POS UI:** On POS screen, show a dropdown “Terminal: [Counter 1 ▼]”. User picks the counter they’re at. Store the chosen `terminal_id` (e.g. in memory or `localStorage`).
   - **Option B – Fixed per device:** Each device (tablet, PC) is configured once with a single `terminal_id` (e.g. in config or env). That value is always sent.

So in the **POS design and layout**, you need a clear place where the terminal is either **selected** (and optionally persisted) or **read from device config**.

---

## 3. API flow: where `terminal_id` is sent

Once the client has a `terminal_id`, it must send it on every POS call that requires a terminal:

| Step | API | How terminal is sent |
|------|-----|------------------------|
| 1. Startup | `GET /api/v1/pos/terminal/check?terminal_id=1` | Query: `terminal_id`. Use on POS load; 403 if not registered/inactive. |
| 2. Open session | `POST /api/v1/pos/session/open` | Body: `{ "terminal_id": 1, "opening_cash": 1000 }`. |
| 3. Complete sale | `POST /api/v1/pos/transactions` | Body: `{ "items": [...], "terminal_id": 1, ... }`. |

Server rules:

- Terminal must **exist**, belong to the **cashier’s branch** (`user->branch_id`), and be **active**.
- If not: 403 “POS is not registered. Please register this terminal in Settings.”

So the **actual** way the system identifies the terminal is: **by the `terminal_id` the client sends** (query or body), validated against the logged-in user’s branch and terminal’s active status.

---

## 4. POS layout / UX summary

- **Login:** Cashier logs in (branch is fixed by their account).
- **Terminal selection:**  
  - Either a **Terminal** dropdown (or list) in the POS layout, filled from the branch’s terminals, with the chosen `terminal_id` stored for the session (and optionally for the device).  
  - Or a **fixed terminal per device** (no selector; terminal comes from config).
- **Before use:** Call `GET /api/v1/pos/terminal/check?terminal_id=X`. If 403, show “Register this terminal in Settings” and don’t allow sales.
- **Session and sales:** Use the same `terminal_id` in `session/open` and `transactions` so all sales are tied to that terminal and appear in **Company Summary → Terminals & sales**.

This is how the POS design and layout connect to how the system identifies the terminal: **the client chooses or configures one terminal and sends its `terminal_id` on every relevant request.**
