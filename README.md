---
title: POS & Stock
emoji: 🛒
colorFrom: sky
colorTo: blue
sdk: docker
pinned: false
license: mit
short_description: POS System + Stock Management + Invoice/Bill Printing
---

# POS & Stock - ລະບົບຂາຍ ແລະ ຈັດການສາງສິນຄ້າ

Full-stack PHP SSR Point-of-Sale system with stock management, customer management, and bill printing.

## Features

- **POS Interface** — Product grid, cart, checkout with Alpine.js
- **Stock Management** — Products, categories, stock alerts
- **Customer Management** — Customer profiles and purchase history
- **Supplier Management** — Supplier directory
- **Sales History** — View past transactions
- **Invoice/ Bill Printing** — Print-friendly invoices
- **Dashboard** — Sales charts, popular products, low stock alerts
- **User Management** — Staff accounts with roles
- **PWA Support** — Installable app with offline fallback
- **Layout Toggle** — Sidebar or navbar mode

## Run Locally (XAMPP)

```bash
# 1. Clone to XAMPP htdocs
git clone <repo> /path/to/xampp/htdocs/pos-stock-billing-system

# 2. Import database.sql into MySQL

# 3. Configure .env with your DB credentials

# 4. Build Tailwind CSS
npx tailwindcss -i ./assets/css/input.css -o ./public/css/app.css --minify

# 5. Open browser
open http://localhost/pos-stock-billing-system
```

## Deploy to Hugging Face Spaces (Embedded MySQL)

This app runs MySQL 8 inside the Docker container with persistent storage in `/data/mysql`.

### Prerequisites

- [Git](https://git-scm.com/) and [HF CLI](https://huggingface.co/docs/hub/spaces-cli) installed
- SSH key set up in [your HF settings](https://huggingface.co/settings/keys)
- A Hugging Face Space already created at `https://huggingface.co/spaces/thiengtham/web`

### Quick Deploy

```bash
# 1. Add HF remote
git remote add hf https://huggingface.co/spaces/thiengtham/web

# 2. Push to deploy (first build takes ~5 mins for MySQL install)
git push hf main
```

### Configuration (Optional)

Set these as **Space Secrets** in the Space Settings page if needed:

| Secret                  | Description          | Default                   |
| ----------------------- | -------------------- | ------------------------- |
| `PROD_APP_URL`          | Your Space URL       | Auto-detected             |
| `MYSQL_ROOT_PASSWORD`   | MySQL root password  | `root`                    |
| `MYSQL_DATABASE`        | Database name        | `if0_42353445_thiengtham` |
| `IMAGEKIT_PUBLIC_KEY`   | ImageKit public key  | (optional)                |
| `IMAGEKIT_PRIVATE_KEY`  | ImageKit private key | (optional)                |
| `IMAGEKIT_URL_ENDPOINT` | ImageKit endpoint    | (optional)                |

### Using External MySQL Instead

If you prefer an external MySQL database, set these Space Secrets instead:

- `PROD_DB_HOSTNAME` — Database host
- `PROD_DB_USERNAME` — Database username
- `PROD_DB_PASSWORD` — Database password
- `PROD_DB_DATABASE` — Database name
- `PROD_APP_URL` — `https://your-username-your-space.hf.space`

## Tech Stack

- **PHP 8.2+** with PDO + MySQL
- **Tailwind CSS 3** + CSS variables theme
- **Alpine.js 3** for reactive UI (cart, modals)
- **SweetAlert2** for toast notifications
- **FontAwesome 6** icons
- **PWA** offline support via Service Worker

## Default Login

| Username | Password | Role  |
| -------- | -------- | ----- |
| `admin`  | `123456` | Admin |
