<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\LangTrait;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LangCommand extends Command
{

    use LangTrait;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:lang {module}';

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

        $languages =  LaravelLocalization::getSupportedLocales();

        $name = strtolower($module->name);

        foreach ($languages as $key => $language){

            $content = $this->getLanguageArray($key , $module);

            $content .= $this->getRelationTranslation($key , $module->id);

            $this->filesystem->put(
                fixPath(base_path('app/Modules/'.$module->name.'/Resources/lang/'.$key.'/'.$name.'.php'))
                , $this->buildFile($content)
            );

        }

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/lang.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($content){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceTableName($stub, $content);

    }


    /**
     * Replace table name
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceTableName($stub, $content)
    {
        return str_replace('DummyArray', $content, $stub);
    }





}
