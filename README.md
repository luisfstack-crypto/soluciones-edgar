# Soluciones Edgar

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-FFA116?style=for-the-badge&logo=filament&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)

Soluciones Edgar is a web-based administrative platform developed for efficient order management, service cataloging, and secure delivery of results to clients.

## Key Features

* **Order Management:** Create, track, and manage client orders through a dedicated admin panel.
* **Secure Result Delivery:** Upload and distribute PDF results to users via Cloudflare R2 (S3-compatible storage).
* **Role-Based Access Control:** Distinct interfaces and permission levels for administrators and standard end-users.
* **Smart Storage Fallback:** Automated routing to handle legacy local files and new cloud uploads seamlessly.

## Tech Stack

* **Core Framework:** Laravel 11
* **Admin Interface:** FilamentPHP
* **Database:** PostgreSQL
* **Cloud Storage:** Cloudflare R2 (S3 API)
* **Deployment & Hosting:** Railway
* **Mail Delivery:** Brevo (SMTP)

## Local Setup Instructions

1. Clone the repository:
```bash
git clone https://github.com/luisfstack-crypto/soluciones-edgar.git
cd soluciones-edgar
Install PHP dependencies:

Bash
composer install
Set up environment configuration:

Bash
cp .env.example .env
php artisan key:generate
Configure your .env file with your local database connection and AWS/S3 credentials for Cloudflare R2.

Execute database migrations:

Bash
php artisan migrate
Launch the development server:

Bash
php artisan serve