<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect', 'localizationRedirect'
]], function () {
    Route::group([
        'prefix' => '/admin',
        'namespace' => 'Larazoul\Larazoul\Larazoul\Controllers\Admin',
        'middleware' => ['web', 'adminMiddleWare']
    ], function () {

        /*
         * home route
         */

        Route::get('/', 'HomeController@index')->name('home');

        /*
         * list of all modules
         * that generated with larazoul
         */

        Route::get('generator/modules', 'ModuleBuilderController@index')->name('modules');

        /*
         * Step one routes
         * just save module name
         * in database
         */

        Route::get('generator/module/step-one/{id?}', 'ModuleBuilderController@stepOneView')->name('view-step-one');
        Route::post('generator/module/step-one', 'ModuleBuilderController@stepOneStore')->name('store-step-one');
        Route::post('generator/module/update/{id}', 'ModuleBuilderController@stepOneUpdate')->name('update-step-one');

        /*
         * Step Two routes
         * create migration file with types
         */

        Route::get('generator/module/step-two/{id}', 'ModuleBuilderController@stepTwoView')->name('view-step-two');
        Route::post('generator/module/step-two/{id}', 'ModuleBuilderController@stepTwoStore')->name('store-step-two');
        Route::get('generator/module/delete-column/{id}', 'ModuleBuilderController@deleteColumn')->name('delete-column');

        /*
         * Step three routes
         * customise the crud and the transformers
         */

        Route::get('generator/module/step-three/{id}', 'ModuleBuilderController@stepThreeView')->name('view-step-three');
        Route::post('generator/module/step-three/{id}', 'ModuleBuilderController@stepThreeStore')->name('store-step-three');

        /*
         * Step four routes
         * relation handel
         */

        Route::get('generator/module/step-four/{id}', 'ModuleBuilderController@stepFourView')->name('view-step-four');
        Route::post('generator/module/step-four/{id}', 'ModuleBuilderController@stepFourStore')->name('store-step-four');
        Route::get('generator/module/get-columns/{id}', 'ModuleBuilderController@getColumns')->name('get-columns');

        /*
         * Step five translation
         */

        Route::get('generator/module/step-five/{id}', 'ModuleBuilderController@stepFiveView')->name('view-step-five');
        Route::post('generator/module/step-five/{id}', 'ModuleBuilderController@stepFiveStore')->name('store-step-five');

        /*
        * Now use all data stored in database to
        * build module
        */

        Route::post('generator/module/build-module/{id}', 'GeneratorController@buildModule')->name('build-module');
        Route::get('generator/module/delete-module/{id}', 'GeneratorController@deleteModule')->name('delete-module');

        /*
         * migrate module
         */

        Route::post('generator/module/migrate-module/{id}', 'GeneratorController@migrateModule')->name('migrate-module');


        /*
         * relation delete and
         */

        Route::get('generator/module/delete-relation/{id}', 'GeneratorController@deleteRelation')->name('delete-relation');

        /*
         * export module
         */

        Route::get('module/export', 'ModuleController@exportModule')->name('view-export');
        Route::post('module/export', 'ModuleController@postExportModule')->name('post-export');


        /*
         * import module
         */

        Route::get('module/import', 'ModuleController@importModule')->name('view-import');
        Route::post('module/import', 'ModuleController@postImportModule')->name('post-import');

        /*
         * menu controll
         */

        Route::get('menu', 'MenuController@index')->name('menu');
        Route::post('menu', 'MenuController@store')->name('post-menu');
        Route::get('menu/delete/{id}', 'MenuController@delete')->name('delete-menu');
        Route::get('build/menu/{id}', 'MenuController@build')->name('build-menu');

        /*
         * itmes control
         */

        Route::resource('itmes', 'ItemsController');

    });

});
