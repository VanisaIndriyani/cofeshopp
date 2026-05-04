# CoffeeShop UMKM Ordering System

Project fullstack Laravel bertema UMKM Coffee Shop modern: customer scan QR meja lalu order tanpa login, admin kelola pesanan, stok, pembayaran cash/QRIS, dan laporan penjualan.

## Tech Stack

- Laravel 12
- PHP 8.2+ (kompatibel PHP 8.3)
- MySQL
- Tailwind CSS + Flowbite
- Alpine.js
- Export: Excel (maatwebsite/excel) + PDF (laravel-dompdf)
- QR Code: endroid/qr-code

## Default Admin

- Email: `admin@coffee.com`
- Password: `password`

## Install (Windows)

1) Install dependency

```bash
composer install
npm install
```

2) Setup environment

```bash
copy .env.example .env
php artisan key:generate
```

3) Setup database (MySQL)

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffeeshop
DB_USERNAME=root
DB_PASSWORD=
```

4) Migrate + seed

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

5) Build assets

```bash
npm run build
```

6) Run server

```bash
php artisan serve
```

## Routes

- Customer:
  - `/` landing
  - `/table/{code}` menu per meja (QR)
  - `/cart`
  - `/checkout`
  - `/order/{invoice}`
- Admin:
  - `/admin/dashboard`
  - `/admin/orders`
  - `/admin/products`
  - `/admin/stocks`
  - `/admin/reports`
  - `/admin/settings`
  - `/admin/tables`
  - `/admin/pos`
