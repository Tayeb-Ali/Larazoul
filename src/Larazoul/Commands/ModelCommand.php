<?php

namespace Larazoul\Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Traits\ModelRelationTrait;
use Larazoul\Larazoul\Larazoul\Traits\ModelTrait;
use Larazoul\Larazoul\Larazoul\Traits\MultiValueHandel;

class ModelCommand extends Command
{

    use ModelTrait, ModelRelationTrait , MultiValueHandel;

    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larazoul:model {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We now generate model file based on module name';

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

        $module = Module::where('name', $moduleName)->first();

        $singular = \Illuminate\Support\Str::singular($module->name);

        $fillAble = $this->generateFillAble($module->id);

        $relationString = $this->appendRelationModel($module->id);

        $appendMultiColumnGetter = $this->multiColumnsGetter($module->id);

        $appendMultiValueHandel = $this->generateMultiValueColumnsSetterGetter($module->id);

        $this->filesystem->put(
            fixPath(base_path('app/Modules/' . $module->name . '/Models/' . $singular . '.php'))
            , $this->buildFile($module->name, $singular, $fillAble, $relationString, $appendMultiColumnGetter , $appendMultiValueHandel)
        );

    }

    /*
     * get file
     */

    protected function getStub()
    {
        return __DIR__ . '/../../stubs/model.stub';
    }

    /*
     * replace  stub file with data
     */

    protected function buildFile($name, $singularName, $fillAble, $relationString, $appendMultiColumnGetter , $appendMultiValueHandel)
    {

        $stub = $this->filesystem->get($this->getStub());

        return $this->replaceContent($stub, $name, $singularName, $relationString, $appendMultiColumnGetter , $appendMultiValueHandel)->replaceName($stub, $fillAble);

    }

    /**
     * Replace content of migration
     *
     * @param  string $stub
     * @param  string $name
     * @return $this
     */

    protected function replaceContent(&$stub, $name, $singularName, $relationString, $appendMultiColumnGetter , $appendMultiValueHandel)
    {
        $stub = str_replace(
            ['DummyNameSpace', 'DummyClassName', 'DummyRelation', 'DummyLangColumns' , 'DummyMultiValue'],
            [$name, $singularName, $relationString, $appendMultiColumnGetter , $appendMultiValueHandel],
            $stub
        );
        return $this;
    }

    /**
     * Replace table name
     *
     * @param  string $stub
     * @param  string $name
     * @return string
     */
    protected function replaceName($stub, $name)
    {
        return str_replace('DummyFillAble', $name, $stub);
    }


}
