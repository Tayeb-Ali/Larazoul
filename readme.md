# Laravel CRUD Generator Web&Api 
[![Build Status](https://travis-ci.org/Tayeb-Ali/ZoolCrud.svg?branch=master)](https://travis-ci.org/Tayeb-Ali/ZoolCrud)

Need faster TDD in Laravel project? This is a simple CRUD generator complete with automated testing suite.
<br>
## About this package

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
soon

#### For Laravel 5.8 or later
soon
## Issue/Proposal

If you find any issue, or want to propose some idea to help this package better, please [create an issue](https://github.com/Tayeb-Ali/issues) in this github repo.

<br>

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
