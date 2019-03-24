# Laravel CRUD Generator Web&Api 
[![Build Status](https://travis-ci.org/Tayeb-Ali/ZoolCrud.svg?branch=master)](https://travis-ci.org/Tayeb-Ali/ZoolCrud)
[![Total Downloads](https://poser.pugx.org/tayeb-ali/zool-crud/downloads)](https://packagist.org/packages/tayeb-ali/zool-crud)
[![Latest Stable Version](https://poser.pugx.org/tayeb-ali/zool-crud/version)](https://packagist.org/packages/tayeb-ali/zool-crud)
[![Latest Unstable Version](https://poser.pugx.org/tayeb-ali/zool-crud/v/unstable)](//packagist.org/packages/tayeb-ali/zool-crud)
[![License](https://poser.pugx.org/tayeb-ali/zool-crud/license)](https://packagist.org/packages/tayeb-ali/zool-crud)

Need faster TDD in Laravel project? This is a simple CRUD generator complete with automated testing suite.
<br>
## About this package
This is sample Package for Start Up Project if you need something professional use  [laravel-generator](https://github.com/InfyOmLabs/laravel-generator) .

This package contains artisan `make:crud` commands to create a simple CRUD feature with test classes on our Laravel 5.8 (and later) application. This package is fairly simple, to **boost test-driven development** method on our laravel application.

With this package installed on local environment, we can use (e.g.) `php artisan make:crud Booking` command to generate some files :

- `App\Booking.php` eloquent model
- `xxx_create_bookings_table.php` migration file
- `BookingController.php`
- `index.blade.php` and `forms.blade.php` view file in `resources/views/bookings` directory
- `resources/lang/booking.php` lang file
- `BookingFactory.php` model factory file
- `BookingPolicy.php` model policy file in `app/Policies` directory
- `ManageBookingTest.php` feature test class in `tests/Feature` directory
- `BookingTest.php` unit test class in `tests/Unit/Models` directory
- `BookingPolicyTest.php` unit test class in `tests/Unit/Policies` directory

It will update some file :

- Update `routes/web.php` to add `bookings` resource route
- Update `app/providers/AuthServiceProvider.php` to add Vehicle model Policy class in `$policies` property

It will also create this file **if it not exists** :

- `resources/lang/app.php` lang file if it not exists
- `tests/BrowserKitTest.php` base Feature TestCase class if it not exists

<br>

#### Main purposef

The main purpose of this package is for *TDD*.
**faster Test-driven Development**, it generates model CRUD scaffolds complete **with Testing Classes** which will use [Laravel Browserkit Testing](https://github.com/laravel/browser-kit-testing) package and [PHPUnit](https://packagist.org/packages/phpunit/phpunit).

<br>

## How to install

#### Only work on Laravel **5.8** or later

```bash
# Get the package
$ composer require tayeb-ali/zool-crud --dev
```

> The package will **auto-discovered** and ready to go.

## How to use
Just type in terminal `$ php artisan make:crud ModelName` command, it will create simple Laravel CRUD files of given **model name** completed with tests.

For example we want to create CRUD for '**App\Booking**' model.

```bash
$ php artisan make:crud-simple Booking 
or add to model file
$ php artisan make:crud-simple {fileName}/Booking

Booking resource route generated on routes/web.php.
Booking model generated.
Booking table migration generated.
BookingController generated.
Booking index view file generated.
Booking form view file generated.
lang/app.php generated.
Booking lang files generated.
Booking model factory generated.
Booking model policy generated.
AuthServiceProvider class has been updated.
BrowserKitTest generated.
ManageVehiclesTest generated.
BookingTest (model) generated.
BookingPolicyTest (model policy) generated.
ZoolCRUD files generated successfully!
```

Make sure we have **set database credential** on `.env` file, then :

```bash
$ php artisan migrate
$ php artisan serve
```

Then visit our application url: `http://localhost:8000/bookings`.


<br>

#### Usage on Fresh Install Laravel

If you are using this package from from the fresh laravel project.

```bash
# This is example commands for Ubuntu users.
$ composer create-project laravel/laravel --prefer-dist project-directory
$ cd project-directory
$ vim .env # Edit your .env file to update database configuration

# Install the package
$ composer require tayeb-ali/zool-crud --dev

# Create auth scaffolds to get layout.app view, register and login feature
$ php artisan make:auth

$ php artisan make:crud Booking # Model name in singular
# I really suggest "git commit" your project right before run make:crud command

$ php artisan migrate
$ php artisan serve
# Visit your route http://127.0.0.1:8000
# Register as new user
# Visit your route http://127.0.0.1:8000/bookings
```

#### Available Commands

```bash
# Create Full CRUD feature with tests
$ php artisan make:crud ModelName

# Create Full CRUD feature with tests and Bootstrap 3 views
$ php artisan make:crud ModelName --bs3

# Create Simple CRUD feature with tests
$ php artisan make:crud-simple ModelName

# Create Simple CRUD feature with tests and Bootstrap 3 views
$ php artisan make:crud-simple ModelName --bs3

# Create API CRUD feature with tests
$ php artisan make:crud-api ModelName
```

<br>

#### Model Attribute/column

The Model and table will **only have 2 pre-definded** attributes or columns : **name** and **description** on each generated model and database table. You can continue working on other column on the table.

<br>

## Issue/Proposal

If you find any issue, or want to propose some idea to help this package better, please [create an issue](https://github.com/Tayeb-Ali/issues) in this github repo.

<br>

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
