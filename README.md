# Laravel API for Organizational Chart
I use laravel 10 to develop an API in managing and visualizing an organizational chart. This aims to handle position names and define connections between positions.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)

## Requirements
Before you begin, ensure you have met the following requirements:
- PHP 8.1 or higher
- Composer
- MySQL 8
- Laravel Installer (optional)

## Installation

Clone Repository
```bash
git clone https://github.com/MrDrich-24/laravel_orgChartApi.git
cd laravel_orgChartApi
```

Install Dependencies
```bash
composer install
```

Set Up the Environment
```bash
cp .env.example .env
```

Generate an Application Key
```bash
php artisan key:generate
```

## Configuration of Environment Variables (.env)
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

## Running the Application

Migrate the Database
```bash
php artisan migrate
```

Serve the Application
```bash
php artisan serve
```