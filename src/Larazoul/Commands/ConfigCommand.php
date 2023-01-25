<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\ModelTrait;

class ConfigCommand extends Command
{

    protected $DS = DIRECTORY_SEPARATOR;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:config {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate config file based on module name';

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

        $singular = Str::singular($module->name);

        $smallName = mb_strtolower($singular);

        $this->filesystem->put(
            fixPath(base_path('app/Modules/'.$module->name.'/Config/'.$smallName .'.php'))
            , $this->buildFile($singular)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/config.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($singularName){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceName($stub, $singularName);

    }


    /**
     * Replace table name
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceName($stub, $name)
    {
        return str_replace('DummyFillAble', $name, $stub);
    }





}
