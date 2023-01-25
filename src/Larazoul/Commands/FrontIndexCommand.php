<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\AdminFormTrait;
use Larazoul\Larazoul\Larazoul\Traits\AdminIndexTrait;
use Larazoul\Larazoul\Larazoul\Traits\FileTrait;

class FrontIndexCommand extends Command
{

    use AdminIndexTrait , AdminFormTrait , FileTrait;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:view_front_index {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate front route file based on module name';

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

        $header = $this->generateHeader($module->id , 'site_crud');

        $body = $this->generateBody($module->id , 'site_crud');

        $filters = $this->generateFilters($module->id , 2, 'website_filter');

        $smallName = mb_strtolower($module->name);

        $path = fixPath(base_path('app/Modules/'.$module->name.'/Resources/views/front/'.$smallName));

        $this->createFolder($path);

        $this->filesystem->put(
            fixPath($path.'/index.blade.php')
            , $this->buildFile($header , $body , $smallName)
        );

        $this->filesystem->put(
            fixPath($path.'/filters.blade.php')
            , $this->buildFilters($filters , $smallName)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/views/front/index.stub';
    }

    /*
    * replace  stub file with data
    */

    protected function buildFile($header , $body  , $smallName){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub , $header , $body )->replaceName($stub, $smallName);

    }


    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $header , $body )
    {
        $stub = str_replace(
            ['DummyHeader' , 'DummyBody'],
            [ $header , $body],
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
    protected function replaceName($stub, $name)
    {
        return str_replace('DummySmallName', $name, $stub);
    }

    /*
     * get file
     */

    protected function getStubFilters(){
        return __DIR__.'/../../stubs/views/front/filters.stub';
    }


    /*
    * replace  stub file with data
    */

    protected function buildFilters($filters , $name){

        $stub = $this->filesystem->get($this->getStubFilters());

        return $this->replaceContentFilter($stub , $name)->replaceFilters($stub, $filters);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContentFilter(&$stub, $name )
    {
        $stub = str_replace(
            ['DummyName'],
            [ $name],
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
    protected function replaceFilters($stub, $name)
    {
        return str_replace('DummyFilters', $name, $stub);
    }





}
