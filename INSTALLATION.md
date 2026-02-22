# PharmaPOS / Landogz POS — Installation Guide

**Concepts:** Inventory is **per branch**. Each branch has its own products, batches, and stock levels. POS terminals and users are assigned to a branch and see only that branch’s inventory.

## 1. Create database

**Local node (branch PC):**
```bash
# MySQL (Laragon / XAMPP / etc.)
mysql -u root -e "CREATE DATABASE pharmapos_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Cloud node (optional, for central server):**
```bash
mysql -u root -e "CREATE DATABASE pharmapos_cloud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## 2. Configure environment

- Copy `.env.example` to `.env` if not already done.
- Set in `.env`:
  - **Local server:** `NODE_TYPE=local`, `BRANCH_ID=1`, `DB_DATABASE=pharmapos_local`, and DB credentials.
  - **Cloud server:** `NODE_TYPE=cloud`, `DB_DATABASE=pharmapos_cloud`, `BRANCH_ID=` (null). Optionally add `CLOUD_DB_*` and `CLOUD_API_*` for sync.
- Generate key: `php artisan key:generate`

## 3. Run migrations

```bash
php artisan migrate
```

Optional seed (create company, branch, admin user):

```bash
php artisan db:seed
```

(You can add a `DatabaseSeeder` that creates a company, branch, and first user.)

## 4. Run local server (branch PC)

```bash
# Serve on all interfaces so POS terminals on LAN can connect
php artisan serve --host=0.0.0.0 --port=8000
```

## 5. Queue worker (for sync jobs, local node)

```bash
php artisan queue:work --queue=sync_queue,default
```

## 6. Scheduler (local node: push/pull to cloud)

**Windows (Task Scheduler):** Run every minute:
```text
php c:\laragon\www\RIARD\LandogzPOS\artisan schedule:run
```

**Linux (cron):**
```text
* * * * * cd /path/to/LandogzPOS && php artisan schedule:run >> /dev/null 2>&1
```

## 7. POS terminal frontend

- Set `VITE_LOCAL_API_URL=http://<LAN_IP_OF_INVENTORY_PC>:8000/api/v1` in `.env`.
- Build: `npm install && npm run build`
- POS terminals open the app and use `localApi` (Axios) to talk to the local server.

## 8. Cloud server

- Deploy the same codebase with `NODE_TYPE=cloud`, cloud DB, and web server (e.g. Nginx + PHP-FPM).
- Configure `CLOUD_API_URL` and `CLOUD_API_TOKEN` on local nodes so they can push to this API.
- Run queue worker and scheduler on cloud for receiving sync and reports.

## Quick test after install

```bash
# Create DB then:
php artisan migrate
php artisan serve --host=0.0.0.0 --port=8000
# Visit http://localhost:8000 — then use API with Sanctum token for /api/v1/* routes.
```
