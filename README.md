#  PHP_Laravel12_Stats

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Composer](https://img.shields.io/badge/Composer-Required-orange)
![License](https://img.shields.io/badge/License-MIT-green)

A Laravel 12 project demonstrating installation and usage of the Laravel Stats package to generate statistics about your Laravel application.

---

#  Overview

This project shows how to:

* Install Laravel 12
* Configure environment settings
* Install and use the `wnx/laravel-stats` package
* Generate statistics about your Laravel project
* Export stats in JSON format
* Test stats using sample generated files

---

#  Features

* Laravel 12 Setup
* Environment Configuration
* Laravel Stats Package Integration
* CLI-Based Project Analysis
* JSON Export Support
* Optional Sample File Generation
* Cache Clearing & Optimization Commands

---

#  Folder Structure

```
Laravel12Stats/
│
├── app/
│   ├── Models/
│   ├── Http/Controllers/
│   ├── Jobs/
│
├── routes/
├── config/
├── database/
├── .env
├── composer.json
└── README.md
```

---

#  System Requirements

Before starting, ensure your system has:

* PHP 8.2+
* Composer
* XAMPP / Apache / Nginx
* MySQL (Optional)
* Git (Optional)

Check PHP version:

```bash
php -v
```

---

#  Installation Guide

## 1️ Create Laravel 12 Project

```bash
composer create-project laravel/laravel Laravel12Stats
```

---

## 2️ Configure Environment File

Open `.env` and update:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Generate Application Key:

```bash
php artisan key:generate
```

---

## 3️ Run the Application

Start development server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

If Laravel welcome page appears, installation is successful.

---

#  Install Laravel Stats Package

Install as development dependency:

```bash
composer require wnx/laravel-stats --dev
```

Laravel automatically discovers and registers the package.

No manual configuration required.

---

#  Verify Installation

Check available commands:

```bash
php artisan list
```

You should see:

```
stats   Generate statistics for this Laravel project
```

Run stats:

```bash
php artisan stats
```

You will see project statistics such as:

<img width="659" height="283" alt="Screenshot 2026-02-13 155554" src="https://github.com/user-attachments/assets/43e55198-39df-495c-8718-ee18a60b4e51" />

---

#  Generate Sample Files (Optional Testing)

### Create Model

```bash
php artisan make:model Product
```

### Create Controller

```bash
php artisan make:controller ProductController
```

### Create Job

```bash
php artisan make:job SendEmailJob
```

Run stats again:

```bash
php artisan stats
```
<img width="665" height="313" alt="Screenshot 2026-02-13 160145" src="https://github.com/user-attachments/assets/ef32b362-8dde-4363-a5b6-eec1af718048" />



You will see updated counts.

---

#  Export Stats as JSON

Generate JSON output:

```bash
php artisan stats --json
```
<img width="1591" height="166" alt="Screenshot 2026-02-13 160605" src="https://github.com/user-attachments/assets/8932be55-6731-41ec-afdd-3e9643f572cb" />


Save output to file:

```bash
php artisan stats --json > stats.json
```

Useful for CI/CD, automation, and monitoring.

---

#  Clear Cache (If Needed)

If any issue occurs:

```bash
php artisan optimize:clear

composer dump-autoload
```

---


