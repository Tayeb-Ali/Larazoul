<?php

namespace Larazoul\Larazoul\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleGenerator
{
    protected string $name;

    protected array $options;

    protected string $table;

    protected string $class;

    protected string $variable;

    protected string $pluralVariable;

    protected ?string $base;

    public function __construct(string $name, array $options = [])
    {
        $this->name = ucfirst(Str::camel($name));
        $this->options = $options;

        $this->table = Str::snake(Str::plural(strtolower($this->name)));
        $this->class = $this->name;
        $this->variable = lcfirst($this->name);
        $this->pluralVariable = Str::camel(Str::plural(strtolower($this->name)));

        // Optional custom application base path (used by tests / CLI tooling).
        $this->base = $options['base'] ?? null;
    }

    /**
     * Resolve a path relative to the module output root.
     */
    protected function path(string $relative): string
    {
        if ($this->base) {
            return rtrim($this->base, '/\\') . DIRECTORY_SEPARATOR . $relative;
        }

        return match (true) {
            str_starts_with($relative, 'app/') => app_path(substr($relative, 4)),
            str_starts_with($relative, 'database/') => database_path(substr($relative, 9)),
            str_starts_with($relative, 'resources/') => resource_path(substr($relative, 10)),
            default => base_path($relative),
        };
    }

    public function generate(): void
    {
        $this->generateMigration();
        $this->generateModel();

        if ($this->option('admin', true)) {
            $this->generateController();
            $this->generateRequest();
            $this->generateViews();
            $this->appendRoutes();
        }

        if ($this->option('api')) {
            $this->generateApiResources();
            $this->generateApiRequest();
            $this->generateApiController();
            $this->appendApiRoutes();
        }

        if ($this->option('front')) {
            $this->generateFrontController();
        }

        if ($this->option('seed')) {
            $this->generateSeeder();
        }
    }

    protected function option(string $key, $default = false)
    {
        return $this->options[$key] ?? $default;
    }

    /* -----------------------------------------------------------------
     | Schema helpers
     | ---------------------------------------------------------------- */

    protected function fields(): array
    {
        return $this->options['fields'] ?? [];
    }

    protected function hasField(string $name): bool
    {
        return collect($this->fields())->contains('name', $name);
    }

    /**
     * Field name for the current locale when the field is translatable.
     */
    protected function localized(string $name): string
    {
        return $name;
    }

    /* -----------------------------------------------------------------
     | Migration
     | ---------------------------------------------------------------- */

    protected function generateMigration(): void
    {
        $migrationsDir = $this->path('database/migrations');

        // Skip if a migration for this table already exists.
        if (glob($migrationsDir . '/*_create_' . $this->table . '_table.php')) {
            return;
        }

        $stub = $this->stub('migration');

        $fields = collect($this->fields())
            ->map(fn (array $f) => $this->migrationColumn($f))
            ->implode("\n            ");

        $content = str_replace(
            ['DummyTable', 'DummyClass', 'DummyColumns'],
            [$this->table, $this->migrationClassName(), $fields],
            $stub
        );

        $filename = now()->format('Y_m_d_His') . '_create_' . $this->table . '_table.php';

        File::ensureDirectoryExists($this->path('database/migrations'));
        File::put($this->path('database/migrations/' . $filename), $content);
    }

    protected function migrationClassName(): string
    {
        return 'Create' . Str::studly($this->table) . 'Table';
    }

    protected function migrationColumn(array $field): string
    {
        $name = $field['name'];
        $type = $field['type'];
        $nullable = $field['nullable'] ?? false;

        $line = '$table->' . $this->migrationType($type) . "('{$name}')";

        if ($nullable) {
            $line .= '->nullable()';
        }

        $line .= ';';

        return $line;
    }

    protected function migrationType(string $type): string
    {
        return match ($type) {
            'bool', 'boolean' => 'boolean',
            'datetime' => 'dateTime',
            default => $type,
        };
    }

    /* -----------------------------------------------------------------
     | Model
     | ---------------------------------------------------------------- */

    protected function generateModel(): void
    {
        $stub = $this->stub('model');

        $fills = collect($this->fields())
            ->map(fn (array $f) => "'{$f['name']}'")
            ->implode(', ');

        $content = str_replace(
            ['DummyClass', 'DummyTable', 'DummyFillable'],
            [$this->class, $this->table, $fills],
            $stub
        );

        File::ensureDirectoryExists($this->path('app/Models'));
        File::put($this->path('app/Models/' . $this->class . '.php'), $content);
    }

    /* -----------------------------------------------------------------
     | Controllers
     | ---------------------------------------------------------------- */

    protected function generateController(): void
    {
        $stub = $this->stub('controller');

        $content = str_replace(
            ['DummyClass', 'DummyModelClass', 'DummyVariable', 'DummyPluralRoute', 'DummyRequestClass'],
            [
                $this->class . 'Controller',
                $this->class,
                $this->variable,
                $this->pluralVariable,
                'Store' . $this->class . 'Request',
            ],
            $stub
        );

        File::ensureDirectoryExists($this->path('app/Http/Controllers'));
        File::put($this->path('app/Http/Controllers/' . $this->class . 'Controller.php'), $content);
    }

    /* -----------------------------------------------------------------
     | Form Requests
     | ---------------------------------------------------------------- */

    protected function generateRequest(): void
    {
        $this->writeRequest('Store' . $this->class . 'Request');
        $this->writeRequest('Update' . $this->class . 'Request');
    }

    protected function writeRequest(string $requestClass): void
    {
        $stub = $this->stub('request');

        $rules = collect($this->fields())
            ->map(fn (array $f) => "'{$f['name']}' => '"
                . ($f['nullable'] ?? false ? 'nullable' : 'required')
                . '|' . $this->validationType($f['type'])
                . "',")
            ->implode("\n            ");

        $content = str_replace(
            ['DummyClass', 'DummyRules'],
            [$requestClass, $rules],
            $stub
        );

        File::ensureDirectoryExists($this->path('app/Http/Requests'));
        File::put($this->path('app/Http/Requests/' . $requestClass . '.php'), $content);
    }

    protected function validationType(string $type): string
    {
        return match ($type) {
            'string', 'text', 'textarea', 'longText' => 'string',
            'integer', 'bigInteger', 'smallInteger', 'tinyInteger' => 'integer',
            'boolean', 'bool' => 'boolean',
            'date' => 'date',
            'datetime', 'timestamp' => 'date',
            'decimal', 'float', 'double' => 'numeric',
            'json' => 'array',
            'email' => 'email',
            default => 'string',
        };
    }

    protected function generateApiRequest(): void
    {
        $this->writeRequest('ApiStore' . $this->class . 'Request');
        $this->writeRequest('ApiUpdate' . $this->class . 'Request');
    }

    /* -----------------------------------------------------------------
     | API Controller + Resources
     | ---------------------------------------------------------------- */

    protected function generateApiController(): void
    {
        $stub = $this->stub('api-controller');

        $content = str_replace(
            ['DummyClass', 'DummyModelClass', 'DummyResourceClass', 'DummyVariable'],
            [$this->class . 'ApiController', $this->class, $this->class . 'Resource', $this->variable],
            $stub
        );

        File::ensureDirectoryExists($this->path('app/Http/Controllers/Api'));
        File::put($this->path('app/Http/Controllers/Api/' . $this->class . 'ApiController.php'), $content);
    }

    protected function generateApiResources(): void
    {
        $stub = $this->stub('resource');

        $fields = collect($this->fields())
            ->map(fn (array $f) => "'{$f['name']}' => \$this->{$f['name']},")
            ->implode("\n            ");

        $content = str_replace(
            ['DummyClass', 'DummyFields'],
            [$this->class . 'Resource', $fields],
            $stub
        );

        File::ensureDirectoryExists($this->path('app/Http/Resources'));
        File::put($this->path('app/Http/Resources/' . $this->class . 'Resource.php'), $content);
    }

    /* -----------------------------------------------------------------
     | Front Controller
     | ---------------------------------------------------------------- */

    protected function generateFrontController(): void
    {
        $stub = $this->stub('front-controller');

        $content = str_replace(
            ['DummyClass', 'DummyModelClass', 'DummyVariable'],
            [$this->class . 'Controller', $this->class, $this->variable],
            $stub
        );

        $path = $this->path('app/Http/Controllers/Front');
        File::ensureDirectoryExists($path);
        File::put($path . '/' . $this->class . 'Controller.php', $content);
    }

    /* -----------------------------------------------------------------
     | Views
     | ---------------------------------------------------------------- */

    protected function generateViews(): void
    {
        $path = $this->path('resources/views/admin/' . $this->pluralVariable);
        File::ensureDirectoryExists($path);

        foreach (['index', 'create', 'edit', 'form'] as $view) {
            $stub = $this->stub('views-' . $view);
            $content = str_replace(
                ['DummyVariable', 'DummyPluralVariable', 'DummyModelClass', 'DummyTitle'],
                [$this->variable, $this->pluralVariable, $this->class, $this->class],
                $stub
            );
            File::put($path . '/' . $view . '.blade.php', $content);
        }

        if ($this->option('front')) {
            $frontPath = $this->path('resources/views/front/' . $this->pluralVariable);
            File::ensureDirectoryExists($frontPath);
            $stub = $this->stub('views-front');
            $content = str_replace(
                ['DummyVariable', 'DummyPluralVariable', 'DummyModelClass', 'DummyTitle'],
                [$this->variable, $this->pluralVariable, $this->class, $this->class],
                $stub
            );
            File::put($frontPath . '/index.blade.php', $content);
            File::put($frontPath . '/show.blade.php', $content);
        }
    }

    /* -----------------------------------------------------------------
     | Routes
     | ---------------------------------------------------------------- */

    protected function appendRoutes(): void
    {
        $routesPath = $this->path('routes/larazoul.php');
        File::ensureDirectoryExists($this->path('routes'));

        $content = str_replace(
            ['DummyPluralRoute', 'DummyControllerClass'],
            [$this->pluralVariable, 'App\\Http\\Controllers\\' . $this->class . 'Controller'],
            <<<'PHP'
            <?php

            use Illuminate\Support\Facades\Route;

            Route::middleware('web')->name('admin.')->prefix('admin')->group(function () {
                Route::resource('DummyPluralRoute', DummyControllerClass::class);
            });

            PHP
        );

        if (! File::exists($routesPath)) {
            File::put($routesPath, $content);
        } elseif (str_contains(File::get($routesPath), $this->pluralVariable)) {
            // already present
        } else {
            File::append($routesPath, PHP_EOL . $content);
        }
    }

    protected function appendApiRoutes(): void
    {
        $routesPath = $this->path('routes/larazoul-api.php');
        File::ensureDirectoryExists($this->path('routes'));

        $content = str_replace(
            ['DummyPluralRoute', 'DummyControllerClass'],
            [$this->pluralVariable, 'App\\Http\\Controllers\\Api\\' . $this->class . 'ApiController'],
            <<<'PHP'
            <?php

            use Illuminate\Support\Facades\Route;

            Route::middleware('api')->name('api.')->prefix('api')->group(function () {
                Route::apiResource('DummyPluralRoute', DummyControllerClass::class);
            });

            PHP
        );

        if (! File::exists($routesPath)) {
            File::put($routesPath, $content);
        } elseif (str_contains(File::get($routesPath), $this->pluralVariable)) {
            // already present
        } else {
            File::append($routesPath, PHP_EOL . $content);
        }
    }

    /* -----------------------------------------------------------------
     | Seeder
     | ---------------------------------------------------------------- */

    protected function generateSeeder(): void
    {
        $stub = $this->stub('seeder');

        $content = str_replace(
            ['DummyClass', 'DummyTable'],
            [$this->class . 'Seeder', $this->table],
            $stub
        );

        File::ensureDirectoryExists($this->path('database/seeders'));
        File::put($this->path('database/seeders/' . $this->class . 'Seeder.php'), $content);
    }

    /* -----------------------------------------------------------------
     | Stubs
     | ---------------------------------------------------------------- */

    protected function stub(string $name): string
    {
        $path = __DIR__ . '/../stubs/' . $name . '.stub';

        if (! File::exists($path)) {
            throw new \RuntimeException("Stub [{$name}] was not found at {$path}");
        }

        return File::get($path);
    }
}