# Tourism App Server (Laravel)

Backend REST API + Blade frontends for destination catalogue, reviews, saved destinations, and user profiles.

## Tech Stack
- PHP 8.2+, Laravel 10 + Sanctum
- MySQL/MariaDB
- Tailwind (CDN) + Blade for frontend pages
- Axios for API calls

## Requirements
- PHP 8.2+ with `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `json`, `ctype`, `fileinfo`
- Composer
- Node 18+ (only needed if you want to rebuild assets; current Blade pages use CDN Tailwind)
- MySQL/MariaDB server

## Quick Start
```bash
cp .env.example .env      # set DB_*, APP_URL
composer install
php artisan key:generate
php artisan storage:link  # needed for uploaded profile photos

# Option A: use migrations + seeders (recommended)
php artisan migrate --seed

# Option B: import provided SQL snapshot instead of seeding
mysql -uUSER -pPASSWORD db_advance_extend < dump-db_advance_extend-202512300924.sql

(Optional, but if you want it's okay)
npm install/bun install/yarn install

php artisan serve/composer run dev
```

## Database & Seed Data
- Seeders create:
  - Users: admin@example.com, budi@example.com, siti@example.com (password: `password123`)
  - Categories (9): Pantai, Gunung, Taman, Museum, Kuliner, Belanja, Air Terjun, Danau, Sejarah
  - Provinces: Jawa Tengah, Jawa Barat, Jawa Timur, Bali, DKI Jakarta (+ cities)
  - Destinations: Parangtritis, Bromo, Kawah Putih, Tanah Lot, Monas, Curug Cimahi, Lawang Sewu, Ranu Kumbolo (+ photos)
- SQL dump `dump-db_advance_extend-202512300924.sql` mirrors the seed data; import if you want ready records without running seeders.

## Running Tests
```bash
php artisan test
```

## API Overview (prefix: /api)
- Auth: `POST /register`, `POST /login`, `POST /logout`
- Profile: `GET /profile`, `POST /profile/update` (name/photo multipart), `POST /profile/password`
- Destinations: `GET /destinations`, `GET /destinations/slider`, `GET /destinations/{id}`, `POST /destinations` (admin/auth)
- Reviews: `POST /reviews`, `GET /reviews/my`, `DELETE /reviews/{id}`
- Saved: `GET /saved`, `POST /saved`, `DELETE /saved/{id}`

## Frontend Pages
- `/login`, `/register`
- `/dashboard`
- `/frontend/destinations`, `/frontend/saved`
- `/frontend/profile`

## File Uploads
- Profile photos are stored on `storage/app/public/profile-photos` and served via `/storage/...`. Run `php artisan storage:link` before uploading.

## Common Issues
- 404 on `/storage/...`: run `php artisan storage:link`.
- Auth 401: ensure `Authorization: Bearer <token>` header (token from `/api/login`).
- SQL import errors: create database first (`CREATE DATABASE db_advance_extend;`) and ensure user has privileges.
