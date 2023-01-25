<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\ControllerRelationTrait;
use Larazoul\Larazoul\Larazoul\Traits\ControllerTrait;
use Larazoul\Larazoul\Larazoul\Traits\Upload;

class ApiControllerCommand extends Command
{

    protected $DS = DIRECTORY_SEPARATOR;

    protected $filesystem;

    use ControllerTrait , ControllerRelationTrait , Upload;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:api_controller {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate controller file based on module name';

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

        $smallClass = mb_strtolower($module->name);

        $modelName = \Illuminate\Support\Str::singular($module->name);

        $smallModelName = mb_strtolower($modelName);

        $filters = $this->generateFilters($module->id);

        $relation = $this->appendRelation($module->id);

        $eagerLoad =  $this->addToEagerLoad($module->id);

        $upload = $this->addUploadFiles($module->id);

        $uploadFiles = $upload['data'];

        $uploadForEdit = str_replace(']' , '] , $row' , $uploadFiles);

        $deleteFiles = $upload['delete'];

        $index = $eagerLoad['index'];

        $showEdit =  $eagerLoad['showEdit'];

        $this->filesystem->put(
            fixPath(base_path('app/Modules/'.$module->name.'/Http/Controllers/Api/'.$module->name.'Controller.php'))
            , $this->buildFile($class , $smallClass  ,$modelName , $smallModelName , $filters , $relation , $index , $showEdit , $uploadFiles , $deleteFiles , $uploadForEdit)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/controllers/api.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($class , $smallClass  ,$modelName , $smallModelName , $filters  , $relation , $index , $showEdit,  $uploadFiles , $deleteFiles , $uploadForEdit){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub, $class , $smallClass  ,$modelName , $filters  , $relation , $index , $showEdit , $uploadFiles , $deleteFiles , $uploadForEdit)->replaceName($stub, $smallModelName);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $class , $smallClass  ,$modelName , $filters  , $relation , $index , $showEdit , $uploadFiles , $deleteFiles , $uploadForEdit)
    {
        $stub = str_replace(
            ['DummyClass' , 'DummyModelName' , 'DummySmallClass' , 'DummyFilters' , 'DummyRelation' , 'DummyIndex' ,'DummyShowEdit' ,'DummyUploadFile' ,'DummyDeleteFile' , 'DummyEditUpload'],
            [$class , $modelName , $smallClass , $filters ,$relation , $index , $showEdit , $uploadFiles , $deleteFiles , $uploadForEdit],
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
    protected function replaceName($stub, $smallModelName)
    {
        return str_replace('DummySmallModelName', $smallModelName, $stub);
    }

}
