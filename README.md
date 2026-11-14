# Haroon & Sons — Laravel Website & Admin CMS

Laravel 12 application preserving the supplied Haroon & Sons homepage, responsive styling, JavaScript, logo, and product images. It adds a MySQL-backed public posts section, secure admin sign-in, post management, and database-backed notifications.

## Requirements

- PHP 8.2+ with `pdo_mysql`, `fileinfo`, `mbstring`, and `openssl`
- Composer 2
- MySQL or MariaDB
- Windows PowerShell (commands below use PowerShell)

The original contact form opens the visitor's email application to compose a message. It does not save contact messages in the database.

## Windows PowerShell setup

1. Extract the ZIP. If PowerShell is open in the folder that contains the extracted project folder, enter:

   ```powershell
   Set-Location .\haroonandsons-laravel
   ```

   If you opened PowerShell directly inside `haroonandsons-laravel`, stay in that folder.

2. Check PHP, Composer, and PHP extensions:

   ```powershell
   php -v
   composer --version
   php -m
   ```

   Make sure PHP is 8.2 or newer and that `pdo_mysql`, `fileinfo`, `mbstring`, and `openssl` appear in the module list.

3. Install the locked PHP dependencies:

   ```powershell
   composer install
   ```

4. Create a private local environment file:

   ```powershell
   Copy-Item .env.example .env
   ```

   Edit `.env` and set:

   ```dotenv
   APP_NAME="Haroon & Sons"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=haroonandsons
   DB_USERNAME=root
   DB_PASSWORD=
   SESSION_DRIVER=file
   CACHE_STORE=file
   ```

   `DB_PASSWORD=` is blank intentionally for a local MySQL root account without a password. If your MySQL account has a password, enter it only in `.env`. Never share or package `.env`.

5. Create the database if it does not exist. If the MySQL command-line client is installed:

   ```powershell
   mysql -h 127.0.0.1 -P 3307 -u root -e "CREATE DATABASE IF NOT EXISTS haroonandsons CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

   Otherwise create a database called `haroonandsons` using phpMyAdmin, MySQL Workbench, or your MySQL tool. The migrations add this app's tables; they do not drop or overwrite existing tables. Do not use `migrate:fresh`.

6. Generate the application key, run migrations, and create the public uploads link:

   ```powershell
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   ```

7. Create an admin account interactively:

   ```powershell
   php artisan admin:create
   ```

   Enter the admin's name and email, then a private password of at least 12 characters. Password input is hidden. No default admin/password or public registration is provided.

8. Start the website:

   ```powershell
   php artisan serve
   ```

   - Website: `http://127.0.0.1:8000`
   - Admin sign-in: `http://127.0.0.1:8000/admin/login`

## Features

- Admin-only session authentication, CSRF protection, and five-attempt login throttling.
- Post CRUD with draft/published status, unique slugs, featured image upload, category, SEO fields, search, filters, and pagination.
- Public listing and detail pages show only published posts whose publication time has arrived.
- Image uploads accept JPG, PNG, and WebP up to 5 MB and use Laravel's public storage disk.
- Public update notifications come from published posts; read status is stored in each visitor's session.
- Admin notifications record post creation, edits, publishing, unpublishing, and deletion.
- Post content is escaped plain text; pasted HTML is not executed.

## Tests

Run automated feature tests with an in-memory SQLite database:

```powershell
php artisan test
```

The tests cover the homepage, public post visibility and draft privacy, admin route protection and login failure, post create/publish/edit/delete, and activity records. The tests do not verify your local MySQL service; `php artisan migrate` during setup is the actual MySQL connection check.

## Troubleshooting

- **Could not find driver:** enable `pdo_mysql` in the PHP installation used by PowerShell, then check `php -m`.
- **Connection refused:** make sure MySQL is running on port `3307` and `.env` has the correct host and port.
- **Unknown database:** create `haroonandsons` before running migrations.
- **Access denied:** check the MySQL username/password. For a blank local password, leave `DB_PASSWORD=` empty.
- **Uploaded images return 404:** run `php artisan storage:link` and confirm the `storage` directory is writable.
- **Windows blocks the storage link:** enable Windows Developer Mode or run PowerShell as an administrator, then run `php artisan storage:link` again.
- **Changed `.env` values are not picked up:** run `php artisan config:clear`.
- **Composer reports a PHP platform error:** ensure Composer and PowerShell use the same PHP 8.2+ installation.

## Production notes

Set a unique `APP_KEY`, `APP_DEBUG=false`, a strong database password, HTTPS, and the production `APP_URL`. Keep `.env` private, back up production data before schema changes, and run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` as part of deployment. Create the first admin from a trusted shell with `php artisan admin:create`.
