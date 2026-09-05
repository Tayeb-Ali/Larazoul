# LaraZoul 4

**مولّد CMS و CRUD حديث للارافيل — أعيد بناؤه بالكامل من الصفر**

LaraZoul is a modern Laravel **CMS + CRUD module generator**, fully rebuilt to work with modern Laravel. It is the successor of the legacy `Tayeb-Ali/Larazoul` package, rewritten from scratch with **Blade + Tailwind + Livewire** — no Bootstrap, no jQuery, and no abandoned dependencies.

| | |
|---|---|
| **Package** | [`tayeb-ali/larazoul`](https://packagist.org/packages/tayeb-ali/larazoul) |
| **Repository** | [github.com/Tayeb-Ali/Larazoul](https://github.com/Tayeb-Ali/Larazoul) |
| **License** | MIT |

[![PHP](https://img.shields.io/packagist/php-v/tayeb-ali/larazoul?style=flat-square)](https://packagist.org/packages/tayeb-ali/larazoul)
[![Latest Stable Version](https://img.shields.io/packagist/v/tayeb-ali/larazoul?style=flat-square&label=Packagist%20Version)](https://packagist.org/packages/tayeb-ali/larazoul)
[![Total Downloads](https://img.shields.io/packagist/dt/tayeb-ali/larazoul?style=flat-square&label=Total%20Downloads)](https://packagist.org/packages/tayeb-ali/larazoul)
[![Monthly Downloads](https://img.shields.io/packagist/dm/tayeb-ali/larazoul?style=flat-square&label=Monthly%20Downloads)](https://packagist.org/packages/tayeb-ali/larazoul)
[![License](https://img.shields.io/packagist/l/tayeb-ali/larazoul?style=flat-square)](https://github.com/Tayeb-Ali/Larazoul/blob/master/LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/Tayeb-Ali/Larazoul?style=flat-square)](https://github.com/Tayeb-Ali/Larazoul/stargazers)

---

## Professional Overview

LaraZoul turns a plain Laravel application into a **functional CMS** in minutes. Instead of hand-writing migrations, models, controllers, HTTP requests, API resources, views and routes for every feature you need, you describe your module once and LaraZoul **generates the entire CRUD stack** — from the admin panel or the command line.

It is designed for:

- **Developers** building admin panels / CRUD-heavy backends quickly.
- **Agencies** shipping content-managed websites for Arabic/English audiences.
- **Teams modernising legacy Laravel 4/5/6 applications** built on the original LaraZoul.

### Why version 4?

The original LaraZoul was built for Laravel 4/5 and relied on packages that are now abandoned
(`laravelcollective/html`, `laravel/helpers`, `mcamara/laravel-localization`). **LaraZoul 4**:

- Drops every legacy dependency and uses **native Laravel 11/12/13** features.
- Renders everything with **Tailwind CSS + Livewire 3** — fast, reactive and dependency-free.
- Preserves the original concepts (modules, columns, relations, multi-language menus) with a cleaner, more maintainable codebase.

## Features

- 🧩 **Full CRUD generator** – migrations, models, controllers, form requests, views and routes for any module.
- 🚀 **API generator** – API controllers and JSON Resources out of the box.
- 🧪 **Livewire wizard** – an interactive, dynamic module builder in the browser.
- 🌐 **Multi-language** – Arabic / English admin panel with a locale switcher and RTL support.
- 📦 **Export / Import** – portable `.larazoul` (zip) archives to share modules between projects.
- 🧭 **Menus** – multi-language navigation menus with items.
- 🎨 **Tailwind UI** – clean, responsive admin interface.
- 🔐 **Admin authentication** – protected admin routes with a configurable role column.

## Requirements

- **PHP** ^8.2
- **Laravel** ^11 | ^12 | ^13
- **Livewire** ^3 (installed automatically with the package)

## Installation

### 1) Download the package (Packagist)

```bash
composer require tayeb-ali/larazoul
```

> Local development alternative — a path repository:
>
> ```json
> "repositories": [
>     {
>         "type": "path",
>         "url": "packages/larazoul",
>         "options": { "symlink": true }
>     }
> ]
> ```
>
> ```bash
> composer require tayeb-ali/larazoul:*
> ```

### 2) Install the package

```bash
php artisan larazoul:install
```

This command publishes the package **config**, **language files** and **views**, then runs the **migrations**.

Options:

| Option | Description |
|--------|-------------|
| `--user` | Create an admin user interactively (email + password) |
| `--force` | Overwrite published files even if they already exist |
| `--fresh` | Drop existing package tables before migrating |

## Usage — Working with modules

### From the command line

```bash
php artisan larazoul:make-module Post \
  --fields="title:string" \
  --fields="body:text:nullable" \
  --fields="published:boolean" \
  --api --seed
```

Options:

| Option | Description |
|--------|-------------|
| `--fields` | Repeatable: `name:type[:nullable]` |
| `--api` | Also generate API controller + JSON Resource |
| `--front` | Also generate public-facing views |
| `--seed` | Generate a Database Seeder |
| `--migrate` | Run the generated migration immediately |
| `--force` | Overwrite existing generated files |

### From the admin panel

1. Start your server (see **Running** below) and open `/admin`.
2. Log in with your admin account.
3. Go to **Modules → Create Module** and use the **Livewire wizard**:
   - Enter the module name.
   - Toggle Admin / API / Front-end options.
   - Add columns (name, DB type, HTML input type, nullable).
   - Click **Build Module** — the files are generated and migrations run automatically.

### Export / Import modules

- **Export** — **Transfer** page → choose a module → download its `.larazoul` archive.
- **Import** — **Transfer** page → upload a `.larazoul` archive to recreate the module (including its columns).

## Configuration

The installed config lives in `config/larazoul.php`:

| Key | Description |
|-----|-------------|
| `admin_prefix` | Admin panel URL prefix (default `admin`) |
| `admin_role_column` | User column that marks an account as admin (default `is_admin`) |
| `admin_role_value` | Value granting admin access (default `true`) |
| `locales` | Locales available in the panel |
| `module_namespace` | Namespace used for generated modules |

## Updating

```bash
composer update tayeb-ali/larazoul
php artisan larazoul:install
```

The second command re-runs migrations and keeps published assets in sync after an upgrade. New package versions are released as git tags and appear on [Packagist](https://packagist.org/packages/tayeb-ali/larazoul) automatically.

## Running

```bash
php artisan serve
```

The admin panel is available at `http://localhost:8000/admin` (or your configured `admin_prefix`).

Admin access is controlled by the `admin_role_column` on the authenticated user —
points at your user model via `config/larazoul.php`. Create an admin user with:

```bash
php artisan larazoul:install --user
```

> Laravel 13 note: Larazoul 4 auto-loads generated module routes
> (`routes/larazoul.php` and `routes/larazoul-api.php`) so both admin and API resource routes
> are registered without touching `bootstrap/app.php`.

## Testing

```bash
composer install
vendor/bin/phpunit
```

## Contributing

Bug reports and feature requests are welcome via [GitHub Issues](https://github.com/Tayeb-Ali/Larazoul/issues). Pull requests are reviewed against the requirements above.

## License

LaraZoul 4 is open-sourced software licensed under the [MIT license](LICENSE).