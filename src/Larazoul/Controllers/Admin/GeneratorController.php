<?php

namespace Larazoul\Larazoul\Larazoul\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Larazoul\Larazoul\Larazoul\Models\MenuItem;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Larazoul\Larazoul\Larazoul\Traits\GeneratorTrait;
use Larazoul\Larazoul\Larazoul\Traits\SeedsTrait;

class GeneratorController extends Controller
{

    use GeneratorTrait , SeedsTrait;

    /*
     * delete relation and rebuild the
     * module after delete this relation
     */

    public function deleteRelation($id ,  Relation $relation){

        $relation = $relation->findOrFail($id);

        $this->buildModule($relation->module_from_id , new Module());

        $relation->delete();

        return redirect(route('view-step-four', ['id' => $relation->module_from_id]));

    }

    /*
     * delete module
     */

    public function deleteModule($id, Module $module){

        /*
        * get all module information
        */

        $module = $module->findOrFail($id);

        /*
         * delete module item form the menu
         */

        $ids = MenuItem::where('slug' , $module->name)->pluck('id');

        MenuItem::destroy($ids);

        /*
         * delete module if exists
         */

        $this->deleteFolder(fixPath(base_path('app/Modules/' . $module->name)));

        $module->delete();

        Artisan::call('migrate:fresh' , ['--seed' => true]);

        return redirect(route('modules'));
    }

    /*
     * build module
     */

    public function buildModule($id, Module $module)
    {

        /*
         * get all module information
         */

        $module = $module->findOrFail($id);

        /*
         * insert module to the menu
         */

       // $this->insertModuleToMenuItem($module);

        /*
       * delete module if exists
       */

        $this->deleteFolder(fixPath(base_path('app/Modules/' . $module->name)));

        /*
         * create Necessary folders
         */

        $this->createFolders($module);

        /*
         * generate provider file
         */

        $this->createProviderFile($module);

        /*
         * Add config File
         */

        $this->createConfigFile($module);

        /*
         * generate migration files
         */

        $this->createModelFile($module);

        /*
         * check if user generate admin crud
         */

        if ($module->admin == 'yes') {

            /*
             * admin routes file
             */

            $this->createAdminRouteFile($module);

            /*
             * admin index  file
             */

            $this->createAdminIndexCrud($module);

            /*
            * admin Add Edit  files
            */

            $this->createAddEdit($module);

            /*
             * admin controller
             */

            $this->createAdminController($module);

            /*
             * admin from builder
             */

            $this->createAdminForm($module);

            /*
             * admin validation
             */

            $this->createAdminRequest($module);

        }

        /*
         * check if user generate front end
         */

        if ($module->website == 'yes') {

            /*
             * front routes file
             */

            $this->createFrontRouteFile($module);


            /*
             * front index  file
             */

            $this->createFrontIndexCrud($module);

            /*
            * front Add Edit  files
            */

            $this->createFrontAddEdit($module);

            /*
             * front controller
             */

            $this->createFrontController($module);

            /*
             * front from builder
             */

            $this->createFrontForm($module);


            /*
            * front validation
            */

            $this->createFrontRequest($module);

        }


        /*
        * check if user generate api
        */

        if ($module->api == 'yes') {

            /*
            * generate resource  file
             * to transform api return
            */

            $this->createApiResourceFile($module);

            /*
             * generate route  file
             */

            $this->createApiRouteFile($module);

            /*
             * generate controller  file
             */

            $this->createApiControllerFile($module);

            /*
             * generate controller  file
             */

            $this->createApiRequest($module);

        }


        /*
         * generate language files
         */

        $this->createLanguageFile($module);

        /*
        * generate seeder files
        */

        $this->createSeederFile($module);

        /*
        * rediredct back to the same page
        * then the next step to migrate 
        */

        return redirect()->back();

    }

    public function migrateModule($id , Module $module){

        $module = $module->findOrFail($id);

        /*
        * generate migration files
        */

        $this->createMigrationFile($module);

        /*
         * return to the next modules page
         */

        return redirect(route('view-step-four' , ['id'  => $module->id ]));
    }


    /*
      * this function will generate admin request file
      */

    protected function createApiRequest($module)
    {
        Artisan::call('larazoul:api_request', ['module' => $module->name]);
    }

    /*
    * this function will generate api controller file
    */

    protected function createApiControllerFile($module)
    {
        Artisan::call('larazoul:api_controller', ['module' => $module->name]);
    }


    /*
     * this function will generate api route file
     */

    protected function createFrontRouteFile($module)
    {
        Artisan::call('larazoul:route_front', ['module' => $module->name]);
    }

    /*
     * this function will generate api route file
     */

    protected function createApiRouteFile($module)
    {
        Artisan::call('larazoul:route_api', ['module' => $module->name]);
    }


    /*
     * this function will generate config file
     */

    protected function createApiResourceFile($module)
    {
        Artisan::call('larazoul:api_resource', ['module' => $module->name]);
    }

    /*
     * this function will generate config file
     */

    protected function createLanguageFile($module)
    {
        Artisan::call('larazoul:lang', ['module' => $module->name]);
    }


    /*
     * this function will generate config file
     */

    protected function createSeederFile($module)
    {
        Artisan::call('larazoul:seeder', ['module' => $module->name]);
    }

    /*
      * this function will generate config file
      */

    protected function createConfigFile($module)
    {
        Artisan::call('larazoul:config', ['module' => $module->name]);
    }

    /*
     * this function will generate admin request file
     */

    protected function createFrontRequest($module)
    {
        Artisan::call('larazoul:front_request', ['module' => $module->name]);
    }

    /*
     * this function will generate admin request file
     */

    protected function createAdminRequest($module)
    {
        Artisan::call('larazoul:admin_request', ['module' => $module->name]);
    }

    /*
     * this function will generate admin Form file
     */

    protected function createFrontForm($module)
    {
        Artisan::call('larazoul:view_front_form', ['module' => $module->name]);
    }

    /*
      * this function will generate admin Form file
      */

    protected function createAdminForm($module)
    {
        Artisan::call('larazoul:view_admin_form', ['module' => $module->name]);
    }


    /*
     * this function will generate front controller file
     */

    protected function createFrontController($module)
    {
        Artisan::call('larazoul:front_controller', ['module' => $module->name]);
    }


    /*
     * this function will generate admin controller file
     */

    protected function createAdminController($module)
    {
        Artisan::call('larazoul:admin_controller', ['module' => $module->name]);
    }

    /*
     * this function will generate admin index file
     */

    protected function createFrontAddEdit($module)
    {
        Artisan::call('larazoul:view_front_add_edit', ['module' => $module->name]);
    }

    /*
     * this function will generate admin index file
     */

    protected function createAddEdit($module)
    {
        Artisan::call('larazoul:view_admin_add_edit', ['module' => $module->name]);
    }


    /*
     * this function will generate admin index file
     */

    protected function createFrontIndexCrud($module)
    {
        Artisan::call('larazoul:view_front_index', ['module' => $module->name]);
    }

    /*
     * this function will generate admin index file
     */

    protected function createAdminIndexCrud($module)
    {
        Artisan::call('larazoul:view_admin_index', ['module' => $module->name]);
    }

    /*
     * this function will generate all migration file
     */

    protected function createMigrationFile($module)
    {
        Artisan::call('larazoul:migration', ['module' => $module->name]);
    }

    /*
   * this function will generate provider file
   */

    protected function createProviderFile($module)
    {
        Artisan::call('larazoul:provider', ['module' => $module->name]);
    }

    /*
     * generate admin route file
     */

    protected function createAdminRouteFile($module)
    {
        Artisan::call('larazoul:route_admin', ['module' => $module->name]);
    }

    /*
     * generate Model file
     */

    protected function createModelFile($module)
    {
        Artisan::call('larazoul:model', ['module' => $module->name]);
    }

    /*
     * generate all folder structure
     * controllers , models , requests , transformers
     */

    protected function createFolders($module)
    {
        /*
         * set some important params
         */

        $name = $module->name;

        $admin = $module->admin == 'yes' ? true : false;

        $api = $module->api == 'yes' ? true : false;

        $website = $module->website == 'yes' ? true : false;

        /*
         * first will check if this module folder exits
         * if not create new folder
         */
        $this->generateModuleMainFolder($name);

        /*
         * generate provider for this module this
         * is important to load migrations routes lang files
         * and the hole package
         */

        $this->createProvidersFolder($name);

        /*
         * generate migration for this module we will use
         * columns on database to build this files
         */

        $this->createMigrationFolder($name);

        /*
         * generate Seeder folder
         */

        $this->createSeedersFolder($name);


        /*
         * generate migration for this module we will use
         * columns on database to build this files
         */

        $this->createConfigFolder($name);

        /*
        * generate routes folder
        */

        $this->createRoutesFolder($name);

        /*
         * generate models folder
         */

        $this->createModelFolder($name);

        /*
         * generate controller folder
         */

        $this->createControllerFolder($name);

        /*
         * generate Requests folder
         */

        $this->createRequestFolder($name);

        /*
         * generate language folder
         */

        $this->createLangFolder($name);

        /*
        * generate language folder
        */

        $this->createViewsFolder($name);

        if ($api) {

            /*
            * generate APi Controller folder
            */

            $this->createApiControllerFolder($name);

            /*
             * generate APi Requests folder
             */

            $this->createApiRequestsFolder($name);

            /*
            * generate APi Resources folder
            */

            $this->createApiResourcesFolder($name);

        }


        if ($website) {

            /*
            * generate FrontEnd Controller folder
            */

            $this->createWebsiteControllerFolder($name);

            /*
             * generate website Requests folder
             */

            $this->createWebsiteRequestsFolder($name);

        }

        if ($admin) {

            /*
            * generate Admin Controller folder
            */

            $this->createAdminControllerFolder($name);

            /*
             * generate Admin Requests folder
             */

            $this->createAdminRequestsFolder($name);

            /*
             * generate Admin views folder
             */

            $this->createAdminViewsFolder($name);
        }

    }



}
