<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait GeneratorTrait
{

    use FileTrait;

    protected function generateModuleMainFolder($module){
        $this->createInModuleFolder($module);
    }

    protected function createProvidersFolder($module){
        $this->createInModuleFolder($module.'/Providers');
    }

    protected function createRoutesFolder($module){
        $this->createInModuleFolder($module.'/Routes');
    }

    protected function createMigrationFolder($module){
        $this->createInModuleFolder($module.'/Database');
        $this->createInModuleFolder($module.'/Database/migrations');
    }

    protected function createSeedersFolder($module){
        $this->createInModuleFolder($module.'/Database/seeds');
    }

    protected function createModelFolder($module){
        $this->createInModuleFolder($module.'/Models');
    }

    protected function createControllerFolder($module){
        $this->createInModuleFolder($module.'/Http');
        $this->createInModuleFolder($module.'/Http/Controllers');
    }

    protected function createApiControllerFolder($module){
        $this->createInModuleFolder($module.'/Http/Controllers/Api');
    }

    protected function createWebsiteControllerFolder($module){
        $this->createInModuleFolder($module.'/Http/Controllers/FrontEnd');
    }

    protected function createAdminControllerFolder($module){
        $this->createInModuleFolder($module.'/Http/Controllers/Admin');
    }

    protected function createRequestFolder($module){
        $this->createInModuleFolder($module.'/Http');
        $this->createInModuleFolder($module.'/Http/Requests');
    }

    protected function createApiRequestsFolder($module){
        $this->createInModuleFolder($module.'/Http/Requests/Api');
    }

    protected function createApiResourcesFolder($module){
        $this->createInModuleFolder($module.'/Http/Resources');
    }

    protected function createWebsiteRequestsFolder($module){
        $this->createInModuleFolder($module.'/Http/Requests/Front');
    }

    protected function createAdminRequestsFolder($module){
        $this->createInModuleFolder($module.'/Http/Requests/Admin');
    }

    protected function createLangFolder($module){
        $this->createInModuleFolder($module.'/Resources');
        $this->createInModuleFolder($module.'/Resources/lang');
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $lang){
           $this->createInModuleFolder($module.'/Resources/lang/'.$lang);
        }
    }

    protected function createViewsFolder($module){
        $this->createInModuleFolder($module.'/Resources/views');
    }

    protected function createAdminViewsFolder($module){
        $this->createInModuleFolder($module.'/Resources/views/admin');
    }

    protected function createFrontEndViewsFolder($module){
        $this->createInModuleFolder($module.'/Resources/views/front-end');
    }

    protected function createConfigFolder($module){
        $this->createInModuleFolder($module.'/Config');
    }





}