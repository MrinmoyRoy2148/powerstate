# PowerStat BD

PowerStat BD is a Bangladesh-focused web application for sharing area-based electricity outage information. The public website is designed to be straightforward to use, displays Bangla by default, and lets visitors switch to English. Authorized administrators maintain outage reports, planned load-shedding schedules, service areas, outage reasons, and public notices.

> Outage restoration times are estimates and may change. Information on the site should be treated as informational, not as a guarantee of service restoration.

## Features

- Bangla-first public interface with an English language switch.
- Public pages for service areas, active outages, outage history, schedules, and announcements.
- Self-service user registration, login, logout, and password reset.
- Signed-in users can save service areas for quick access.
- Protected administration area for managing service areas, outage reasons, schedules, outages, and announcements.
- Admin access controls, active/inactive accounts, and rate-limited login attempts.
- Dhaka timezone defaults for local date and time display.
- Blade interface styled with Tailwind CSS on public pages and Bootstrap on admin pages.

## Technology

- PHP 8.3 or newer
- Laravel 13
- MySQL or MariaDB
- Node.js and npm
- Vite and Tailwind CSS 4

## Local setup on Windows with XAMPP

These instructions use XAMPP for MySQL. You can use its bundled Apache server if needed, but `php artisan serve` is the simplest way to run the app locally.

### 1. Start services and prepare the database

Start MySQL from the XAMPP Control Panel. In phpMyAdmin, create a database named `powerstate` using `utf8mb4` character encoding.

Make sure Composer, Node.js/npm, and a PHP 8.3+ command-line executable are available. XAMPP's PHP executable can be used if it meets the PHP version requirement.

### 2. Install dependencies and create the environment file

From the project directory in PowerShell:

```powershell
cd C:\xampp\htdocs\powerstate
composer install
Copy-Item .env.example .env
```

Edit `.env` and set the application and database values. For a typical local XAMPP installation:

```dotenv
APP_NAME="PowerStat BD"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Dhaka
APP_LOCALE=bn
APP_FALLBACK_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=powerstate
DB_USERNAME=root
DB_PASSWORD=
```

Use the database username and password configured for your own MySQL installation. Do not commit `.env` or share its contents.

### 3. Generate the application key and create database tables

```powershell
php artisan key:generate
php artisan migrate
```

### 4. Install and build frontend assets

```powershell
npm install
npm run build
```

### 5. Run the application

```powershell
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in a browser.

For frontend development with Vite hot reload, run `npm run dev` in a second terminal while `php artisan serve` is running.

## User accounts

Visitors can create a regular user account at `/register` and sign in at `/login`. New registrations always receive the regular `user` role; the registration form cannot grant administrator access.

The password reset form is available from the user login page. For local development, if `MAIL_MAILER=log`, reset emails are written to `storage/logs/laravel.log` instead of being delivered to an inbox. Configure a working mail transport in `.env` to deliver reset links by email.

## Administrator account

The project does not create an administrator with a default password. To create or update the initial administrator, set `ADMIN_NAME`, `ADMIN_EMAIL`, and a strong `ADMIN_PASSWORD` of at least 12 characters in your local `.env`, then run:

```powershell
php artisan db:seed --class=AdminUserSeeder
```

Sign in at [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login) with the configured administrator email and password. Do not commit `.env` or share its contents.

For deployments where the application shell is unavailable, set `ADMIN_REGISTRATION_KEY` to a randomly generated secret of at least 32 characters in the hosting provider's environment settings, then redeploy. Open `/admin/register` and enter that key to create a new administrator account. The same flow can create a replacement admin if the original credentials are lost. Keep the key private and remove it from the hosting environment after registration when you do not need recovery access.

## Tests

Run the test suite with:

```powershell
php artisan test
```

## Project structure

| Path | Purpose |
| --- | --- |
| `app/Http/Controllers` | Public and admin request handling |
| `app/Models` | Eloquent models and relationships |
| `database/migrations` | Application database schema |
| `lang` | English and Bangla translations |
| `resources/views` | Blade pages and shared UI components |
| `routes/web.php` | Public and administrator web routes |
| `tests/Feature` | Application feature tests |

## Language and configuration

Bangla (`bn`) is the default locale and English (`en`) is available through the language switcher. The selected language is stored in the visitor's session. For another environment, configure the database and application settings in that environment's `.env` file; keep secrets out of source control.
