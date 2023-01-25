<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\ControllerTrait;
use Larazoul\Larazoul\Larazoul\Traits\ResourcesTrait;

class ApiResourcesCommand extends Command
{

    protected $filesystem;

    use ResourcesTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:api_resource {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate api resources file based on module name';

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

        $class = $module->name;

        $array = $this->generateResources($module->id);

        $relation = $this->generateRelation($module->id);

        $nameSpace = $relation['namespace'];

        $array .= $relation['data'];

        $this->filesystem->put(
            fixPath(base_path('app/Modules/'.$module->name.'/Http/Resources/'.$module->name.'.php'))
            , $this->buildFile($class , $array , $nameSpace)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/api/resources.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($class , $array ,$nameSpace ){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub, $class , $nameSpace)->replaceName($stub, $array);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $class , $nameSpace)
    {
        $stub = str_replace(
            ['DummyClass'  , 'DummyNameSpace'],
            [$class , $nameSpace],
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
    protected function replaceName($stub, $array)
    {
        return str_replace('DummyArray', $array, $stub);
    }

}
