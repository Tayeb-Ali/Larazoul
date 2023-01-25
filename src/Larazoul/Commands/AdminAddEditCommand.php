<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;


class AdminAddEditCommand extends Command
{

    protected $DS = DIRECTORY_SEPARATOR;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:view_admin_add_edit {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate admin route file based on module name';

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

        $smallName = mb_strtolower($module->name);

        $path = fixPath(base_path('app/Modules/'.$module->name.'/Resources/views/admin/'.$smallName));

        $this->filesystem->put(
            $path.$this->DS.'add.blade.php'
            , $this->buildFile($this->getAddStub() , $smallName)
        );

        $this->filesystem->put(
            $path.$this->DS.'edit.blade.php'
            , $this->buildFile($this->getEditStub() , $smallName)
        );

    }

    /*
     * get file
     */

    protected function getAddStub(){
        return __DIR__.'/../../stubs/views/admin/add.stub';
    }

    /*
 * get file
 */

    protected function getEditStub(){
        return __DIR__.'/../../stubs/views/admin/edit.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($stub , $smallName){

        $stub = $this->filesystem->get($stub);

        return $this->replaceName($stub, $smallName);

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





}
