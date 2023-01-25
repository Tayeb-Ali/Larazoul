<?php

namespace Larazoul\Larazoul\Providers;


use Larazoul\Larazoul\Chumper\Zipper\Zipper;
use Larazoul\Larazoul\Chumper\Zipper\ZipperServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Larazoul\Larazoul\Larazoul\Commands\AdminAddEditCommand;
use Larazoul\Larazoul\Larazoul\Commands\AdminControllerCommand;
use Larazoul\Larazoul\Larazoul\Commands\AdminFormCommand;
use Larazoul\Larazoul\Larazoul\Commands\AdminIndexCommand;
use Larazoul\Larazoul\Larazoul\Commands\AdminRelationCommand;
use Larazoul\Larazoul\Larazoul\Commands\AdminRequestCommand;
use Larazoul\Larazoul\Larazoul\Commands\AdminRouteCommand;
use Larazoul\Larazoul\Larazoul\Commands\ApiControllerCommand;
use Larazoul\Larazoul\Larazoul\Commands\ApiRequestCommand;
use Larazoul\Larazoul\Larazoul\Commands\ApiResourcesCommand;
use Larazoul\Larazoul\Larazoul\Commands\ApiRouteCommand;
use Larazoul\Larazoul\Larazoul\Commands\ConfigCommand;
use Larazoul\Larazoul\Larazoul\Commands\FrontAddEditCommand;
use Larazoul\Larazoul\Larazoul\Commands\FrontControllerCommand;
use Larazoul\Larazoul\Larazoul\Commands\FrontFormCommand;
use Larazoul\Larazoul\Larazoul\Commands\FrontIndexCommand;
use Larazoul\Larazoul\Larazoul\Commands\FrontRequestCommand;
use Larazoul\Larazoul\Larazoul\Commands\FrontRouteCommand;
use Larazoul\Larazoul\Larazoul\Commands\InstallCommand;
use Larazoul\Larazoul\Larazoul\Commands\LangCommand;
use Larazoul\Larazoul\Larazoul\Commands\MigrationCommand;
use Larazoul\Larazoul\Larazoul\Commands\ModelCommand;
use Larazoul\Larazoul\Larazoul\Commands\SeederCommand;
use Larazoul\Larazoul\Larazoul\Commands\ServiceProviderCommand;
use Larazoul\Larazoul\Larazoul\Traits\FileTrait;


class LarazoulServiceProvider extends ServiceProvider
{

    use FileTrait;

    protected $DS = DIRECTORY_SEPARATOR;

    /**
     * Bootstrap services.
     *
     * @return void
     */

    public function boot()
    {

        app()->register(ZipperServiceProvider::class);

        $modulePath = app_path('Modules');

        $this->createFolder($modulePath);

        $location = __DIR__ . $this->DS . '../Resources' . $this->DS . 'Modules' . $this->DS . 'Users.zip';

        if ($this->fileExists($location)) {
            $destination = app_path('Modules');

            $zip = new Zipper();

            $zip->zip($location)->extractTo($destination);
        }

        /*
         * change the auth to larazoul auth
         */

        $this->publishes([
            __DIR__ . '/../Resources/config' => base_path('config'),
        ], 'larazoul');


        /*
         * publish Admin panel Style
         * first put all js and css in public folder
         */

        $this->publishes([
            __DIR__ . '/../Resources/assets' => public_path('assets'),
        ], 'larazoul');

        /*
         * copy All users files to modules
         */

        $this->publishes([
            __DIR__ . '/../Resources/Modules' => $modulePath,
        ], 'larazoul');


        /*
         * load larazoul language files
         */

        $this->loadTranslationsFrom(__DIR__ . '/../Larazoul/lang', 'larazoul');

        /*
         * load larazoul migrations files
         */

        $this->loadMigrationsFrom(__DIR__ . '/../Larazoul/migrations');

        /*
         * load larazoul routes
         * generators routes
         */

        $this->loadRoutesFrom(__DIR__ . '/../Larazoul/routes/admin.php');

        /*
         * loads larazoul files
         * generators views
         */

        $this->loadViewsFrom(__DIR__ . '/../Larazoul/views', 'larazoul');

        /*
       * load all Providers
       */

        $this->loadProviders();

        /*
         * register command
         */

        $this->commands([
            MigrationCommand::class,
            ServiceProviderCommand::class,
            AdminRouteCommand::class,
            ModelCommand::class,
            AdminIndexCommand::class,
            AdminAddEditCommand::class,
            AdminControllerCommand::class,
            AdminFormCommand::class,
            AdminRequestCommand::class,
            ConfigCommand::class,
            SeederCommand::class,
            InstallCommand::class,
            LangCommand::class,
            ApiResourcesCommand::class,
            ApiRouteCommand::class,
            ApiControllerCommand::class,
            ApiRequestCommand::class,
            FrontRouteCommand::class,
            FrontIndexCommand::class,
            FrontAddEditCommand::class,
            FrontControllerCommand::class,
            FrontFormCommand::class,
            FrontRequestCommand::class
        ]);

    }

    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {

        /*
         * register helpers files
         */

        $this->registerHelpers('arrays');
        $this->registerHelpers('path');
        $this->registerHelpers('crud');
        $this->registerHelpers('lang');
        $this->registerHelpers('function');

    }

    /**
     * load helpers.
     *
     * @return void
     */

    public function registerHelpers($file)
    {
        // Load the helpers in app/Http/helpers.php
        if (file_exists($file = __DIR__ . '/../Larazoul/Helpers/' . $file . '.php')) {
            require $file;
        }
    }


    /*
     * load All Providers
     * that will generated with larazoul
     */

    public function loadProviders()
    {

        $path = base_path('app' . $this->DS . 'Modules');

        if (is_dir($path)) {

            $directories = File::directories($path);

            if (!empty($directories)) {

                foreach ($directories as $directory) {

                    $moduleName = explode($this->DS, $directory);

                    $moduleName = end($moduleName);

                    $fullProviderPath = $directory . $this->DS . 'Providers' . $this->DS . 'Larazoul' . $moduleName . 'ServicesProvider.php';

                    if (file_exists($fullProviderPath)) {

                        $nameSpace = 'App\\Modules\\' . $moduleName . '\\Providers\\' . 'Larazoul' . $moduleName . 'ServicesProvider';

                        app()->register($nameSpace);

                    }
                }
            }
        }

    }
}
