<?php

namespace Tayeb\ZoolCrud;

class CrudSimpleMake extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud-simple {name : The model name}
                            {--p|parent= : The generated controller parent directory}
                            {--t|tests-only : Generate CRUD testcases only}
                            {--f|formfield : Generate CRUD with FormField facades}
                            {--r|form-requests : Generate CRUD with Form Request on create and update actions}
                            {--bs3 : Generate CRUD with Bootstrap 3 views}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create simple Laravel CRUD files of given model name.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->getModelName();

        if ($this->modelExists()) {
            $this->error("{$this->modelNames['model_name']} model already exists.");
            return;
        }

        // Warn if it has no default layout view based on
        // simple-crud.default_layout_view config
        if ($this->defaultLayoutNotExists()) {
            $this->warn(config('simple-crud.default_layout_view').' view does not exists.');
        }

        if ($this->option('tests-only')) {
            $this->generateTestFiles();

            $this->info('Test files generated successfully!');
            return;
        }

        $this->generateRoutes();
        $this->generateModel();
        $this->generateController();
        $this->generateResources();
        $this->generateTestFiles();

        $this->info('CRUD files generated successfully!');
    }

    /**
     * Generate test files
     *
     * @return void
     */
    public function generateTestFiles()
    {
        app('Tayeb\ZoolCrud\Generators\ModelTestGenerator', ['command' => $this])->generate();
        app('Tayeb\ZoolCrud\Generators\FeatureTestGenerator', ['command' => $this])->generate('simple');
        app('Tayeb\ZoolCrud\Generators\ModelPolicyTestGenerator', ['command' => $this])->generate();
    }

    /**
     * Generate Controller
     *
     * @return void
     */
    public function generateController()
    {
        app('Tayeb\ZoolCrud\Generators\ControllerGenerator', ['command' => $this])->generate('simple');
    }

    /**
     * Generate Model
     *
     * @return void
     */
    public function generateModel()
    {
        app('Tayeb\ZoolCrud\Generators\ModelGenerator', ['command' => $this])->generate();
        app('Tayeb\ZoolCrud\Generators\MigrationGenerator', ['command' => $this])->generate();
        app('Tayeb\ZoolCrud\Generators\ModelPolicyGenerator', ['command' => $this])->generate();
        app('Tayeb\ZoolCrud\Generators\ModelFactoryGenerator', ['command' => $this])->generate();
    }

    /**
     * Generate Route Route
     *
     * @return void
     */
    public function generateRoutes()
    {
        app('Tayeb\ZoolCrud\Generators\RouteGenerator', ['command' => $this])->generate();
    }

    /**
     * Generate Resources
     *
     * @return void
     */
    public function generateResources()
    {
        app('Tayeb\ZoolCrud\Generators\LangFileGenerator', ['command' => $this])->generate();
        app('Tayeb\ZoolCrud\Generators\FormViewGenerator', ['command' => $this])->generate('simple');
        app('Tayeb\ZoolCrud\Generators\IndexViewGenerator', ['command' => $this])->generate('simple');
    }
}
