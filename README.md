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

## Deploy to Hugging Face Spaces

1. Create a new Space on Hugging Face → select **Docker** SDK
2. Push this repository to the Space
3. Set Environment **Secrets** in Space Settings:
   - `PROD_APP_URL` → `https://your-username-your-space.hf.space`
   - `PROD_DB_HOSTNAME` → Database host
   - `PROD_DB_USERNAME` → Database username
   - `PROD_DB_PASSWORD` → Database password
   - `PROD_DB_DATABASE` → Database name

## Tech Stack

- **PHP 8.2+** with PDO + MySQL
- **Tailwind CSS 3** + CSS variables theme
- **Alpine.js 3** for reactive UI (cart, modals)
- **SweetAlert2** for toast notifications
- **FontAwesome 6** icons
- **PWA** offline support via Service Worker

## Default Login

| Username | Password | Role |
|----------|----------|------|
| `admin` | `123456` | Admin |
