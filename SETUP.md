# InventoryPro — Setup Guide

A retail inventory management system built with Laravel 13, Tailwind CSS, and MySQL.

---

## What You'll Need

Before you start, make sure the following are installed on your machine:

| Tool | Minimum Version | Download |
|------|----------------|----------|
| PHP | 8.2+ | https://www.php.net/downloads |
| Composer | 2.x | https://getcomposer.org |
| Node.js | 18+ | https://nodejs.org |
| XAMPP (MySQL) | Any recent | https://www.apachefriends.org |

> **Windows users:** XAMPP is the easiest way to get MySQL running locally.
> **Mac users:** XAMPP works, or you can use [DBngin](https://dbngin.com) / [Homebrew MySQL](https://formulae.brew.sh/formula/mysql).

---

## Step 1 — Get the Project

If you received a ZIP file, extract it to a folder of your choice (e.g. `C:\xampp\htdocs\inventory_system` or `~/projects/inventory_system`).

If it's a Git repository:

```bash
git clone <repository-url> inventory_system
cd inventory_system
```

---

## Step 2 — Install PHP Dependencies

```bash
composer install
```

This installs all Laravel packages. It may take a minute on first run.

---

## Step 3 — Install Node Dependencies & Build CSS

```bash
npm install
npm run build
```

This compiles the Tailwind CSS and JavaScript assets into `public/build/`.

---

## Step 4 — Create Your Environment File

Copy the example environment file:

```bash
# Mac / Linux
cp .env.example .env

# Windows (Command Prompt)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env
```

Then generate a unique application key:

```bash
php artisan key:generate
```

---

## Step 5 — Configure the Database

### 5a. Start MySQL in XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **MySQL**
3. Wait until the status turns green

### 5b. Create the Database

Open your browser and go to **http://localhost/phpmyadmin**

Click **New** in the left sidebar and create a database:

- **Database name:** `inventory_pro`
- **Collation:** `utf8mb4_unicode_ci`
- Click **Create**

> Alternatively, run this in a terminal if `mysql` is in your PATH:
> ```bash
> mysql -u root -h 127.0.0.1 -e "CREATE DATABASE inventory_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
> ```
> XAMPP MySQL on Mac:
> ```bash
> /Applications/XAMPP/xamppfiles/bin/mysql -u root -h 127.0.0.1 -e "CREATE DATABASE inventory_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
> ```

### 5c. Update Your `.env` File

Open `.env` in a text editor and set these values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_pro
DB_USERNAME=root
DB_PASSWORD=
```

> **Note:** XAMPP's default MySQL `root` user has no password. If you've set one, enter it after `DB_PASSWORD=`.

---

## Step 6 — Run Migrations

This creates all the database tables:

```bash
php artisan migrate
```

Expected output:
```
  0001_01_01_000000_create_users_table ......... DONE
  0001_01_01_000001_create_cache_table ......... DONE
  ...
  2026_04_08_create_products_table ............. DONE
  2026_04_08_create_sales_table ................ DONE
  (etc.)
```

---

## Step 7 — Seed Demo Data

This loads the database with realistic sample data so you can explore the system straight away:

```bash
php artisan db:seed
```

This creates:
- **3 users** (1 admin, 2 staff)
- **6 categories** — Electronics, Clothing, Food & Beverages, Stationery, Household, Health & Beauty
- **30 products** with SKUs, prices, stock levels
- **5 purchase orders** (received, ordered, and draft)
- **~40 sales** spread over the last 30 days
- **5 shrinkage records** and 1 customer return

> To wipe and re-seed from scratch at any time:
> ```bash
> php artisan migrate:fresh --seed
> ```

---

## Step 8 — Start the Development Server

```bash
php artisan serve
```

The app will be available at **http://localhost:8000**

---

## Login Credentials

| Role  | Email                | Password   |
|-------|----------------------|------------|
| Admin | admin@demo.com       | `password` |
| Staff | sarah@demo.com       | `password` |
| Staff | mike@demo.com        | `password` |

---

## Quick Reference — All Commands in Order

```bash
# 1. Install dependencies
composer install
npm install

# 2. Build frontend assets
npm run build

# 3. Set up environment
cp .env.example .env        # (Windows: copy .env.example .env)
php artisan key:generate

# 4. Configure DB in .env, then:
php artisan migrate
php artisan db:seed

# 5. Start the server
php artisan serve
```

Then open **http://localhost:8000** and log in.

---

## Running on a Local Network (Multiple Users)

If you want other people on the same Wi-Fi/network to connect to the app:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Find your local IP address:

```bash
# Mac / Linux
ipconfig getifaddr en0

# Windows
ipconfig
# Look for "IPv4 Address" under your active adapter
```

Other devices on the same network can then access the app at `http://<your-ip>:8000`.

> Make sure XAMPP MySQL is running on the host machine at all times.

---

## Troubleshooting

**`composer install` fails with PHP version error**
Your PHP version is too old. You need PHP 8.2 or higher.
Check your version with `php -v`.

**`SQLSTATE[HY000] [2002] Connection refused`**
MySQL isn't running. Open XAMPP and start MySQL.

**`SQLSTATE[HY000] [1049] Unknown database 'inventory_pro'`**
You haven't created the database yet. Follow Step 5b.

**`php artisan serve` — page shows "Vite manifest not found"`**
You haven't built the frontend assets. Run `npm install && npm run build`.

**Blank page or CSS not loading**
Run `npm run build` again, then `php artisan view:clear`.

**`Permission denied` on Mac/Linux**
```bash
chmod -R 775 storage bootstrap/cache
```

**Error after pulling new code updates**
```bash
composer install
npm run build
php artisan migrate
php artisan view:clear
php artisan config:clear
```

---

## Project Structure (Quick Overview)

```
inventory_system/
├── app/
│   ├── Http/Controllers/     ← All page logic
│   └── Models/               ← Database models
├── database/
│   ├── migrations/           ← Table definitions
│   └── seeders/              ← Demo data
├── resources/
│   └── views/                ← Blade HTML templates
│       ├── dashboard.blade.php
│       ├── products/
│       ├── sales/
│       ├── purchase-orders/
│       ├── stock/
│       └── reports/
├── routes/
│   └── web.php               ← URL routes
├── .env                      ← Your local config (not committed)
├── .env.example              ← Template for .env
└── SETUP.md                  ← This file
```

---

## Features

- **Dashboard** — KPI cards, 7-day sales chart, low-stock alerts
- **Products** — Add, edit, deactivate, filter by category/stock status
- **Categories** — Organize products into groups
- **Stock Management** — Manual adjustments, shrinkage, returns, full movement log
- **Purchase Orders** — Create orders, receive stock (auto-updates inventory)
- **Sales** — Record sales with multiple items, auto-deducts stock
- **Reports**
  - Stock Valuation (cost vs retail value)
  - Fast/Slow Moving Items
  - Shrinkage Report
  - Sales Report with daily breakdown & top products
