<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\AdminRequestTrait;
use Larazoul\Larazoul\Larazoul\Traits\FileTrait;

class FrontRequestCommand extends Command
{

    use AdminRequestTrait , FileTrait;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:front_request {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate front validation file based on module name';

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

        $rules = $this->generateRules($module->id)['rules'];

        $overRide =  $this->generateRules($module->id)['overRide'];

        $smallName = mb_strtolower($module->name);

        $path = fixPath(base_path('app/Modules/'.$module->name.'/Http/Requests/Front/'.$smallName));

        $this->createFolder($path);

        $this->filesystem->put(
            fixPath($path.'/'.$module->name.'Request.php')
            , $this->buildFile($rules  , $overRide , $module->name)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/requests/front.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($rules ,$overRide , $name){

        $stub = $this->filesystem->get($this->getStub());

        $model = \Illuminate\Support\Str::singular($name);

        return $this->replaceContent($stub, $name , $model , mb_strtolower($model) , $overRide)->replaceRules($stub, $rules);

    }


    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $class , $model , $smallModel , $overRide)
    {
        $stub = str_replace(
            ['DummyClass' , 'DummyModel' , 'DummySmallModel' , 'DummyOverRide'],
            [$class , $model , $smallModel , $overRide],
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
    protected function replaceRules($stub, $rules)
    {
        return str_replace('DummyRules', $rules, $stub);
    }





}
