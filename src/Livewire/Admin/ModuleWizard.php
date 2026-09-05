<?php

namespace Larazoul\Larazoul\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Models\ColumnDetail;
use Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Support\ModuleGenerator;
use Livewire\Component;
use Livewire\WithFileUploads;

class ModuleWizard extends Component
{
    use WithFileUploads;

    // ----------------------------------------------------------------
    // Step 1 – module information
    // ----------------------------------------------------------------
    public string $name = '';

    public bool $admin = true;

    public bool $api = false;

    public bool $front = false;

    public bool $seed = true;

    public bool $migrate = true;

    // ----------------------------------------------------------------
    // Step 2 – columns
    // ----------------------------------------------------------------
    public array $columns = [];

    // Supported column types
    public array $types = [
        'string', 'text', 'longText', 'integer', 'bigInteger',
        'boolean', 'date', 'dateTime', 'time', 'decimal', 'json',
    ];

    public function mount(?int $moduleId = null): void
    {
        if ($moduleId) {
            $this->loadModule($moduleId);
        }

        $this->columns[] = $this->emptyColumn();
    }

    protected function emptyColumn(): array
    {
        return [
            'name' => '',
            'type' => 'string',
            'nullable' => false,
            'html_type' => 'text',
        ];
    }

    protected function loadModule(int $moduleId): void
    {
        $module = Module::with('columns.details')->findOrFail($moduleId);

        $this->name = $module->name;
        $this->admin = $module->admin;
        $this->api = $module->api;
        $this->front = $module->website;

        $this->columns = $module->columns
            ->map(fn (Column $column) => [
                'name' => $column->name,
                'type' => $column->column ?: 'string',
                'nullable' => str_contains((string) $column->modifiers, 'nullable'),
                'html_type' => $column->details?->html_type ?: 'text',
            ])
            ->values()
            ->all();

        if (empty($this->columns)) {
            $this->columns[] = $this->emptyColumn();
        }
    }

    // ----------------------------------------------------------------
    // Column rows
    // ----------------------------------------------------------------
    public function addColumn(): void
    {
        $this->columns[] = $this->emptyColumn();
    }

    public function removeColumn(int $index): void
    {
        unset($this->columns[$index]);
        $this->columns = array_values($this->columns);

        if (empty($this->columns)) {
            $this->columns[] = $this->emptyColumn();
        }
    }

    public function moveColumn(int $index, int $direction): void
    {
        $target = $index + $direction;

        if ($target < 0 || $target >= count($this->columns)) {
            return;
        }

        $tmp = $this->columns[$index];
        $this->columns[$index] = $this->columns[$target];
        $this->columns[$target] = $tmp;
    }

    // ----------------------------------------------------------------
    // Generation
    // ----------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'columns' => ['required', 'array', 'min:1'],
            'columns.*.name' => ['required', 'string', 'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/', 'distinct'],
            'columns.*.type' => ['required', 'in:' . implode(',', $this->types)],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'module name',
            'columns.*.name' => 'column name',
            'columns.*.type' => 'column type',
        ];
    }

    public function saveAndGenerate()
    {
        $this->validate();

        $fields = collect($this->columns)
            ->map(fn (array $c) => [
                'name' => $c['name'],
                'type' => $c['type'],
                'nullable' => (bool) ($c['nullable'] ?? false),
                'html_type' => $c['html_type'] ?? 'text',
            ])
            ->values()
            ->all();

        $module = Module::firstOrCreate(
            ['name' => $this->name],
            [
                'admin' => $this->admin,
                'website' => $this->front,
                'api' => $this->api,
            ]
        );

        // Persist columns
        $module->columns()->delete();
        foreach ($fields as $field) {
            $column = $module->columns()->create([
                'name' => $field['name'],
                'column' => $field['type'],
                'modifiers' => $field['nullable'] ? 'nullable' : null,
            ]);

            $column->details()->create([
                'module_id' => $module->id,
                'html_type' => $field['html_type'],
                'admin_crud' => $this->admin,
                'site_crud' => $this->front,
            ]);
        }

        try {
            $generator = new ModuleGenerator($module->name, [
                'fields' => $fields,
                'admin' => $this->admin,
                'api' => $this->api,
                'front' => $this->front,
                'seed' => $this->seed,
                'migrate' => $this->migrate,
            ]);

            $generator->generate();

            if ($this->migrate) {
                Artisan::call('migrate');
            }

            session()->flash('success', "Module [{$module->name}] generated successfully.");

            return redirect()->route('larazoul.modules.index');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('larazoul::livewire.admin.module-wizard');
    }
}