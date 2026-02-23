# Landogz POS – Setup & Network Access

This guide covers:

1. **Network sharing** – Run the app so you can access it from your phone or other devices on the same Wi‑Fi.
2. **Local environment** – `.env` and config for development on your machine.
3. **Server (production) environment** – `.env` and config for the deployed app.

---

## 1. Network sharing (access from phone / same network)

Use this when you want to open the app on your phone or another device that is on the **same Wi‑Fi** as your computer.

### 1.1 Run Laravel so it accepts connections from the network

By default, `php artisan serve` only listens on `127.0.0.1` (localhost). To allow other devices, bind to all interfaces:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Keep this terminal open while testing from your phone.

### 1.2 Find your computer’s local IP address

**Windows (Command Prompt or PowerShell):**

```bash
ipconfig
```

Look for **IPv4 Address** under your active adapter (e.g. **Wireless LAN adapter Wi-Fi** or **Ethernet**). Example: `192.168.1.105`.

**macOS / Linux:**

```bash
ip addr show
# or
ifconfig
```

Use the `inet` address of your Wi‑Fi or Ethernet interface (e.g. `192.168.1.105`).

### 1.3 Open the app on your phone

On your phone (same Wi‑Fi), open the browser and go to:

```
http://<YOUR_PC_IP>:8000
```

Example: `http://192.168.1.105:8000`

For the API base URL (e.g. in an app or Postman on your phone), use:

```
http://<YOUR_PC_IP>:8000/api/v1
```

### 1.4 Use the same URL in `.env` when testing from the network

So that links, redirects, and API calls work when you use the IP from your phone, set:

**`.env` (while testing from phone):**

```env
APP_URL=http://192.168.1.105:8000
```

Replace `192.168.1.105` with your PC’s actual IPv4. When you’re done testing from the network, you can set it back to `http://localhost:8000` if you prefer.

If you use **Vite** and the frontend calls the API by URL, set the local API URL to the same host:

```env
VITE_LOCAL_API_URL=http://192.168.1.105:8000/api/v1
```

Then rebuild assets if needed: `npm run build` or `npm run dev`.

### 1.5 If your phone cannot connect – Windows Firewall

If the phone cannot reach the app, allow inbound TCP on the port you use (e.g. 8000):

1. Open **Windows Defender Firewall** → **Advanced settings**.
2. **Inbound Rules** → **New Rule**.
3. **Port** → **TCP** → **Specific local ports**: `8000` → **Allow the connection**.
4. Apply to **Private** (and **Domain** if needed).
5. Name the rule (e.g. “Laravel dev port 8000”).

---

## 2. Local environment setup (your computer)

Use this for development on a single machine (localhost or network access as above).

### 2.1 Clone and install

```bash
cd path/to/LandogzPOS
composer install
cp .env.example .env
php artisan key:generate
```

### 2.2 Database

- Create a MySQL database (e.g. `pharmapos_local`).
- In `.env`, set:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmapos_local
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then:

```bash
php artisan migrate
# Optional: seed
php artisan db:seed
```

### 2.3 Local `.env` checklist

| Variable | Recommended for local |
|----------|------------------------|
| `APP_ENV` | `local` |
| `APP_DEBUG` | `true` |
| `APP_URL` | `http://localhost:8000` (or `http://<YOUR_IP>:8000` for phone testing) |
| `NODE_TYPE` | `local` |
| `NODE_ID` | `1` (or your local node id) |
| `BRANCH_ID` / `BRANCH_NAME` | Your default branch (fallback for sync/CLI) |
| `DB_*` | Local MySQL credentials |
| `CLOUD_DB_*` | Only if this local node syncs to a cloud DB (optional) |
| `CLOUD_APP_URL` | Cloud app URL (e.g. for logos); leave empty if not used |
| `CLOUD_API_URL` / `CLOUD_API_TOKEN` | Only if using API sync mode |
| `SYNC_MODE` | `direct_db` or `api` depending on how you sync to cloud |
| `QUEUE_CONNECTION` | `database` or `sync` |
| `CACHE_DRIVER` | `file` |
| `SESSION_DRIVER` | `file` |

Leave BIR, mail, Semaphore, OTP, and production-only options as in `.env.example` unless you need them locally.

### 2.4 Run locally

- **Artisan server (network access):**  
  `php artisan serve --host=0.0.0.0 --port=8000`

- **Artisan server (localhost only):**  
  `php artisan serve`

- **Laragon:** Start Laragon and open the project URL (e.g. `http://landogzpos.test`). To allow phone access, use your PC IP and ensure Apache/Nginx listens on the right interface/port.

---

## 3. Server (production) environment setup

Use this for the **deployed** app (e.g. VPS, shared hosting, AlwaysData, etc.).

### 3.1 Server setup overview

- PHP (e.g. 8.1+), Composer, MySQL/MariaDB.
- Web server (Apache or Nginx) pointing document root to `public/`.
- SSL (HTTPS) recommended.

### 3.2 Production `.env` checklist

| Variable | Recommended for server |
|----------|------------------------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://yourdomain.com` (no trailing slash) |
| `APP_KEY` | Generated with `php artisan key:generate` (keep secret) |
| `NODE_TYPE` | `cloud` (this is the central/cloud node) |
| `NODE_ID` | Unique id for this server node |
| `BRANCH_ID` / `BRANCH_NAME` | Default branch if applicable |
| `DB_*` | **Server** MySQL credentials (production DB) |
| `CLOUD_DB_*` | Not used on cloud node (or leave empty) |
| `CLOUD_APP_URL` | Same as `APP_URL` or your main app URL (for logos, etc.) |
| `CLOUD_API_URL` | Not needed on cloud node (it is the API server) |
| `CLOUD_API_TOKEN` | Not needed on cloud node |
| `SYNC_MODE` | N/A or `api` if this server receives sync from locals |
| `LOG_CHANNEL` | `stack` |
| `LOG_LEVEL` | `warning` or `error` |
| `CACHE_DRIVER` | `file` or `redis` if you use Redis |
| `SESSION_DRIVER` | `file` or `database` / `redis` for multi-server |
| `QUEUE_CONNECTION` | `database` or `redis` |
| Mail, BIR, Semaphore, OTP | Configure as needed for production |

Never set `APP_DEBUG=true` or expose `.env` or `APP_KEY` in production.

### 3.3 After deploying code

On the server (SSH or similar):

```bash
cd /path/to/landogzpos
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env with production values (see table above)
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
# If using queue workers
php artisan queue:restart
```

Set correct permissions (e.g. `storage/` and `bootstrap/cache/` writable by the web server user).

### 3.4 Sync: local vs server

- **Local node:**  
  `NODE_TYPE=local`, and set `CLOUD_DB_*` or `CLOUD_API_URL`/`CLOUD_API_TOKEN` so this instance syncs **to** the cloud.

- **Server (cloud) node:**  
  `NODE_TYPE=cloud`, uses `DB_*` as the main database. Local nodes push to this server (via `direct_db` or `api`). No need to set `CLOUD_DB_*` for the cloud node itself.

---

## 4. Quick reference

| Goal | Action |
|------|--------|
| Open app on phone (same Wi‑Fi) | `php artisan serve --host=0.0.0.0 --port=8000`, then visit `http://<PC_IP>:8000` on phone |
| Get PC IP (Windows) | `ipconfig` → IPv4 under Wi‑Fi or Ethernet |
| Local dev `.env` | `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://localhost:8000` (or PC IP for phone), `NODE_TYPE=local` |
| Server `.env` | `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://yourdomain.com`, `NODE_TYPE=cloud` |
| Phone can’t connect | Add Windows Firewall inbound rule for TCP port 8000 (or 80 if using Laragon) |

For API usage and examples, see [POSTMAN-GUIDE.md](POSTMAN-GUIDE.md).
