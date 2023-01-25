<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\AdminFormTrait;
use Larazoul\Larazoul\Larazoul\Traits\AdminIndexTrait;

class FrontFormCommand extends Command
{

    use AdminFormTrait;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:view_front_form {module}';

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

        $form = $this->generateForms($module->id);

        $smallName = mb_strtolower($module->name);

        $path = base_path('app/Modules/'.$module->name.'/Resources/views/front/'.$smallName);

        $relation = $this->getRelationForm($module->id);

        $this->filesystem->put(
            fixPath($path.'/form.blade.php')
            , $this->buildFile($form , $relation)
        );

    }

    /*
     * get file
     */

    protected function getStub(){
        return __DIR__.'/../../stubs/views/front/form.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($form , $relation){

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub , $relation)->replaceName($stub, $form);

    }

    /**
     * Replace content of migration
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */

    protected function replaceContent(&$stub, $relation)
    {
        $stub = str_replace(
            ['DummyRelation'],
            [$relation],
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
        return str_replace('DummyForm', $name, $stub);
    }





}
