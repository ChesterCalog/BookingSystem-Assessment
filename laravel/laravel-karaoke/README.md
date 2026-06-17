# 🎤 KaraokeZone — Laravel Booking System

A complete, production-ready karaoke room booking web application built with **PHP Laravel 11** and **PostgreSQL**.

---

## ✨ Features

| Feature | Description |
|---------|-------------|
| **Landing Page** | Hero section, room showcase, testimonials, FAQ |
| **Authentication** | Register, login, profile management, logout |
| **Guest Booking** | Book without an account — name, email, phone collected |
| **Member Booking** | Full booking history, cancellations |
| **Room Management** | Admin CRUD: name, type, size, capacity, price, images |
| **Double-Booking Prevention** | Conflict detection across both booking tables |
| **Auto Cost Calculation** | Live JS preview + server-side validation |
| **Booking Statuses** | Pending → Approved / Rejected → Completed / Cancelled |
| **Admin Dashboard** | Stats, revenue chart, pending approvals, booking calendar |
| **Email Notifications** | Confirmation on booking; status update on approve/reject |
| **Responsive Design** | Tailwind CSS, mobile-first layout |

---

## 🗂 Project Structure

```
laravel-karaoke/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── RoomController.php
│   │   │   ├── BookingController.php
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   └── Admin/
│   │   │       ├── AdminDashboardController.php
│   │   │       ├── AdminRoomController.php
│   │   │       └── AdminBookingController.php
│   │   ├── Kernel.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Mail/
│   │   ├── BookingConfirmation.php
│   │   └── BookingStatusUpdated.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Room.php
│   │   ├── Booking.php
│   │   ├── GuestBooking.php
│   │   └── Payment.php
│   ├── Policies/
│   │   └── BookingPolicy.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── AuthServiceProvider.php
├── bootstrap/
│   └── app.php
├── config/
│   └── database.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_rooms_table.php
│   │   ├── ..._create_bookings_table.php
│   │   ├── ..._create_guest_bookings_table.php
│   │   └── ..._create_payments_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── RoomSeeder.php
│       └── BookingSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php       ← Public layout
│   │   └── admin.blade.php     ← Admin layout
│   ├── home.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   └── profile.blade.php
│   ├── rooms/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── bookings/
│   │   ├── create.blade.php    ← Member booking
│   │   ├── guest.blade.php     ← Guest booking
│   │   └── confirmation.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── rooms/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── _form.blade.php
│   │   └── bookings/
│   │       ├── index.blade.php
│   │       ├── show.blade.php
│   │       └── calendar.blade.php
│   └── emails/
│       ├── booking-confirmation.blade.php
│       └── booking-status.blade.php
└── routes/
    └── web.php
```

---

## 🗄 Database Schema

### `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar | |
| email | varchar | unique |
| phone | varchar | nullable |
| password | varchar | bcrypt hashed |
| role | enum | `admin` \| `user` |
| avatar | varchar | nullable |
| created_at / updated_at | timestamp | |

### `rooms`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| name | varchar | |
| type | enum | `standard` \| `deluxe` \| `vip` \| `party` |
| size | enum | `small` \| `medium` \| `large` \| `xlarge` |
| capacity | integer | max guests |
| price_per_hour | decimal(8,2) | |
| description | text | nullable |
| amenities | json | array of strings |
| image | varchar | storage path, nullable |
| is_available | boolean | default true |
| created_at / updated_at | timestamp | |

### `bookings` (member)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| user_id | FK → users | |
| room_id | FK → rooms | |
| booking_date | date | |
| start_time | time | |
| end_time | time | |
| num_guests | integer | |
| total_cost | decimal(10,2) | auto-calculated |
| status | enum | pending/approved/rejected/completed/cancelled |
| special_requests | text | nullable |
| reference_number | varchar | unique, auto BK-XXXXXXXX |
| created_at / updated_at | timestamp | |

### `guest_bookings`
Same as `bookings` but replaces `user_id` with `full_name`, `email`, `phone`. Reference prefix: `GB-XXXXXXXX`.

### `payments` (optional)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| booking_type | varchar | `booking` or `guest_booking` |
| booking_id | bigint | polymorphic id |
| amount | decimal(10,2) | |
| method | enum | cash/card/gcash/bank_transfer |
| status | enum | pending/paid/refunded/failed |
| transaction_id | varchar | nullable |
| paid_at | timestamp | nullable |

---

## 🚀 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- PostgreSQL >= 14  *(or use SQLite for quick local dev)*
- Node.js >= 18 (for Vite/assets, optional with CDN)

---

### Step 1 — Clone or copy the project

```bash
git clone https://github.com/yourname/laravel-karaoke.git
cd laravel-karaoke
```

---

### Step 2 — Install PHP dependencies

```bash
composer install
```

---

### Step 3 — Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

---

### Step 4 — Configure PostgreSQL connection

Edit `.env` and fill in your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=karaoke_db
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

> **No PostgreSQL?** Change `DB_CONNECTION=sqlite` and create `database/database.sqlite`:
> ```bash
> touch database/database.sqlite
> ```
> Then set `DB_DATABASE=` *(leave blank — Laravel will use the sqlite path)*.

---

### Step 5 — Create the PostgreSQL database

```sql
-- Run in psql or pgAdmin:
CREATE DATABASE karaoke_db;
```

---

### Step 6 — Run migrations

```bash
php artisan migrate
```

---

### Step 7 — Seed sample data

```bash
php artisan db:seed
```

This will create:
- 1 admin account: `admin@karaokeZone.com` / `admin1234`
- 4 regular users (password: `password123`)
- 6 rooms (standard → party)
- 7 sample bookings (member + guest)

---

### Step 8 — Storage symlink (for room images)

```bash
php artisan storage:link
```

---

### Step 9 — Configure email (optional)

For local testing, use [Mailtrap](https://mailtrap.io):

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
MAIL_FROM_ADDRESS="noreply@karaokeZone.com"
MAIL_FROM_NAME="KaraokeZone"
```

To disable emails entirely during development, set:

```env
MAIL_MAILER=log
```

---

### Step 10 — Start the development server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 👤 Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@karaokeZone.com | admin1234 |
| User | maria@example.com | password123 |
| User | james@example.com | password123 |

---

## 🗺 Route Map

### Public
| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Landing page |
| GET | `/rooms` | Room listing (with filters) |
| GET | `/rooms/{id}` | Room detail + cost calculator |
| GET | `/book/guest` | Guest booking form |
| POST | `/book/guest` | Submit guest booking |
| GET | `/book/guest/confirmation/{id}` | Guest confirmation page |

### Auth
| Method | URI | Description |
|--------|-----|-------------|
| GET/POST | `/register` | Registration |
| GET/POST | `/login` | Login |
| POST | `/logout` | Logout |
| GET/PUT | `/profile` | View & update profile |

### Member (auth required)
| Method | URI | Description |
|--------|-----|-------------|
| GET/POST | `/book` | Member booking form |
| GET | `/book/confirmation/{id}` | Member confirmation |
| PATCH | `/bookings/{id}/cancel` | Cancel booking |

### Admin (`/admin/*`)
| Method | URI | Description |
|--------|-----|-------------|
| GET | `/admin` | Dashboard (stats + chart) |
| CRUD | `/admin/rooms` | Room management |
| GET | `/admin/bookings` | All bookings (search/filter) |
| GET | `/admin/bookings/calendar` | FullCalendar view |
| GET | `/admin/bookings/{type}/{id}` | Booking detail |
| PATCH | `/admin/bookings/{type}/{id}/approve` | Approve |
| PATCH | `/admin/bookings/{type}/{id}/reject` | Reject |
| PATCH | `/admin/bookings/{type}/{id}/complete` | Mark complete |

---

## ⚙️ Key Artisan Commands

```bash
# Generate app key
php artisan key:generate

# Run all migrations fresh and seed
php artisan migrate:fresh --seed

# Clear all caches
php artisan optimize:clear

# Create storage symlink
php artisan storage:link

# Run tests
php artisan test
```

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.2, Laravel 11 |
| Database | PostgreSQL 14+ (SQLite fallback) |
| Frontend | Tailwind CSS v3 (CDN), Vanilla JS |
| Icons | Font Awesome 6 |
| Charts | Chart.js 4 |
| Calendar | FullCalendar 6 |
| Fonts | Google Fonts (Inter + Poppins) |
| Email | Laravel Mail (Mailtrap / SMTP) |

---

## 🔒 Security Notes

- All passwords are hashed with **bcrypt** via Laravel's `Hash` facade.
- CSRF protection is enabled on all `POST`/`PATCH`/`DELETE` routes via `@csrf`.
- The `admin` middleware blocks non-admin access to all `/admin/*` routes.
- The `BookingPolicy` prevents users from viewing or cancelling others' bookings.
- File uploads are validated (type + size) and stored in `storage/app/public/rooms`.
- SQL injection is prevented by Eloquent's parameterized query builder throughout.

---

## 📦 Production Deployment Checklist

```bash
# 1. Set APP_ENV and APP_DEBUG
APP_ENV=production
APP_DEBUG=false

# 2. Optimize autoloader
composer install --optimize-autoloader --no-dev

# 3. Cache config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run migrations (never seed in production)
php artisan migrate --force

# 5. Set storage symlink
php artisan storage:link

# 6. Set correct file permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🐛 Troubleshooting

| Problem | Fix |
|---------|-----|
| `could not find driver` (pgsql) | Install `php-pgsql`: `sudo apt install php8.2-pgsql` |
| `SQLSTATE: Connection refused` | Check `DB_HOST`, `DB_PORT`, and that PostgreSQL is running |
| 500 on booking confirmation | Run `php artisan storage:link` for image access |
| Emails not sent | Set `MAIL_MAILER=log` to write to `storage/logs/laravel.log` |
| Pagination broken | Ensure `AppServiceProvider` calls `Paginator::useTailwind()` |

---

## 📄 License

MIT — free to use for personal and commercial projects.
