<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;


use Larazoul\Larazoul\Larazoul\Models\Module;

class ServiceProviderCommand extends Command
{

    protected $DS = DIRECTORY_SEPARATOR;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:provider {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate services provider file based on module name';

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

        $route = "";

        if($module->admin == 'yes'){

            $route .= "\t\t\t".'$this->loadRoutesFrom(__DIR__ . \'/../Routes/admin.php\');'."\n\n";

        }

        if($module->website == 'yes'){

            $route .= "\t\t\t".'$this->loadRoutesFrom(__DIR__ . \'/../Routes/front.php\');'."\n\n";

        }

        if($module->api == 'yes'){

            $route .= "\t\t\t".'$this->loadRoutesFrom(__DIR__ . \'/../Routes/api.php\');'."\n\n";

        }

        $this->filesystem->put(
            fixPath(base_path('app/Modules/'.$module->name.'/Providers/Larazoul'.$module->name.'ServicesProvider.php'))
            , $this->buildFile($module->name , mb_strtolower($module->name) , $route)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/services_provider.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($className , $name , $route){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub, $className , $route)->replaceName($stub, $name);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $content , $route)
    {
        $stub = str_replace(
            ['DummyClassName' , 'DummyConfigName' , 'DummyRoute'],
            [$content , Str::singular($content) , $route],
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
        return str_replace('DummyName', $name, $stub);
    }





}
