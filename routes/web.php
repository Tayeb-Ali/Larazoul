<?php

use Illuminate\Support\Facades\Route;
use Larazoul\Larazoul\Http\Controllers\Admin\DashboardController;
use Larazoul\Larazoul\Http\Controllers\Admin\MenuController;
use Larazoul\Larazoul\Http\Controllers\Admin\ModuleController;
use Larazoul\Larazoul\Http\Controllers\Admin\ModuleTransferController;

$adminPrefix = config('larazoul.admin_prefix', 'admin');

Route::middleware(['web'])->group(function () use ($adminPrefix) {

    // Authentication (login/logout) for the admin panel
    Route::prefix($adminPrefix)->name('larazoul.')->group(function () {
        Route::get('login', [DashboardController::class, 'showLogin'])->name('login');
        Route::post('login', [DashboardController::class, 'login'])->name('login.post');
        Route::post('logout', [DashboardController::class, 'logout'])->name('logout');
        Route::get('locale/{locale}', [DashboardController::class, 'setLocale'])->name('locale');
    });

    // Authenticated admin panel routes
    Route::middleware(config('larazoul.admin_middleware', ['larazoul.admin']))
        ->prefix($adminPrefix)
        ->name('larazoul.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('modules', [ModuleController::class, 'index'])->name('modules.index');
            Route::get('modules/create/{id?}', [ModuleController::class, 'create'])->name('modules.create');
            Route::post('modules/{module}/build', [ModuleController::class, 'build'])->name('modules.build');
            Route::delete('modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

            // Module export / import
            Route::get('modules/transfer', [ModuleTransferController::class, 'index'])->name('modules.transfer');
            Route::get('modules/{module}/export', [ModuleTransferController::class, 'export'])->name('modules.export');
            Route::post('modules/import', [ModuleTransferController::class, 'import'])->name('modules.import');

            // Menus
            Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
            Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
            Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
            Route::delete('menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
            Route::delete('menus/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
        });
});