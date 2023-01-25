<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Support\Facades\Artisan;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Illuminate\Support\Str;
use Larazoul\Larazoul\Larazoul\Traits\SeederTrait;

class SeederCommand extends Command
{

    use SeederTrait;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:seeder {module}';

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

        $module = Module::where('name' , $moduleName)->with('columns.details')->first();

        $moduleText = $this->getModuleText($module);

        $columnText = $this->getColumnText($module->columns);

        $columnDetailsText = $this->getColumnDetailsText($module->columns);

        $relationText = $this->getModuleRelation($module->id);

        $menuText = $this->getMenuItems($module->id);

        $parentMenu = $menuText['parent'];

        $childMenu = $menuText['child'];

        $this->filesystem->put(
            fixPath(base_path('app/Modules/'.$module->name.'/Database/seeds/'.$module->name.'Seeder.php'))
            , $this->buildFile($moduleText , $columnText , $module->name , $columnDetailsText ,$relationText , $parentMenu , $childMenu)
        );

//        Artisan::call('migrate');
    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/seed.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($moduleText , $columnText , $name , $columnDetailsText , $relationText , $parentMenu , $childMenu){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub, $moduleText , $columnText , $name , $relationText , $parentMenu , $childMenu)->replaceTableName($stub, $columnDetailsText);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $moduleText , $columnText , $name , $relationText , $parentMenu , $childMenu)
    {
        $stub = str_replace(
            ['DummyModule' , 'DummyColumn' , 'DummyClass' , 'DummyRelation' , 'DummyParentMenuItems' , 'DummyChildMenuItems'],
            [$moduleText , $columnText , $name ,$relationText , $parentMenu , $childMenu],
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
    protected function replaceTableName($stub, $columnDetailsText)
    {
        return str_replace('DummyDetails', $columnDetailsText, $stub);
    }





}
