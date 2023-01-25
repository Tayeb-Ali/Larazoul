<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\MigrationTrait;
use Illuminate\Support\Str;
use Larazoul\Larazoul\Larazoul\Traits\SeedsTrait;

class MigrationCommand extends Command
{

    use MigrationTrait, SeedsTrait;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:migration {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate migration file based on module name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $moduleName = $this->argument('module');

        $module = Module::where('name' , $moduleName)->first();

        Schema::disableForeignKeyConstraints();

        $string = $this->generateMigration($module->id);

        $filename = '_create_'.Str::snake(trim($module->name)).'_table';
        
        $name = date('Y_m_d').$filename;

        $tableName = mb_strtolower($module->name);

        $this->filesystem->put(
            fixPath(base_path('app/Modules/'.$module->name.'/'.'Database/migrations/'.$name.'.php'))
            , $this->buildFile($string , $tableName)
        );

//        $this->handelDataBase($filename , $tableName);

        $relationManyToMany = $this->manyToManyMigration($module->id);

        foreach ($relationManyToMany  as  $relation){

            foreach ($relation as $key => $rel){

                $filename = '_create_'.Str::snake(trim($module->name)).'_'.mb_strtolower($key).'_table';

                $name = date('Y_m_d').$filename;

                $tableName = mb_strtolower($module->name).'_'.mb_strtolower($key);

                $this->filesystem->put(
                    fixPath(base_path('app/Modules/'.$module->name.'/'.'Database/migrations/'.$name.'.php'))
                    , $this->buildFile($rel , $tableName)
                );

//                $this->handelDataBase($filename , $tableName);

            }

        }

        /*
        * migrate Again
        */

//        Artisan::call('migrate');

        shell_exec('composer --working-dir=' . fixPath(app_path("/")) . ' dump-autoload');

        Artisan::call('migrate:fresh' , ['--seed' => true]);

        Schema::enableForeignKeyConstraints();

//        $this->generateAllModulesItems();
    }

  /*
   * get file
   */

    protected function getStub(){
        return __DIR__.'/../../stubs/migration.stub';
    }

   /*
    * replace  stub file with data
    */

    protected function buildFile($content , $name){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub, $content , $name)->replaceTableName($stub, $name);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $content , $name)
    {
        $stub = str_replace(
            ['DummyContent' , 'DummyClass'],
            [$content , ucfirst(Str::camel(Str::plural($name))) ],
            $stub
        );
        return $this;
    }

    /**
     * Replace table name
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceTableName($stub, $name)
    {
        return str_replace('DummyTable', $name, $stub);
    }


    /*
     * here we will remove table and
     * remove migration row
     * this for if user make update in fields
     * the original table must reset
     */

    protected function handelDataBase($filename , $tableName){
        /*
         * drop row from migration table
         */

        DB::table('migrations')->where('migration' , 'like' , '%'.$filename)->delete();

        /*
         * drop module Table
         */

        Schema::dropIfExists($tableName);
    }





}
