<?php

namespace Larazoul\Larazoul\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Support\ModuleGenerator;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::withCount('columns')->latest()->paginate(15);

        return view('larazoul::admin.modules.index', compact('modules'));
    }

    public function create(int $id = 0)
    {
        $module = $id ? Module::with('columns.details')->findOrFail($id) : null;

        return view('larazoul::admin.modules.create', compact('module'));
    }

    public function destroy(Module $module)
    {
        // Remove generated files if we can trace them.
        $module->delete();

        return redirect()->route('larazoul.modules.index')
            ->with('success', "Module [{$module->name}] deleted.");
    }

    public function build(Module $module)
    {
        // Generate files for an already-stored module.
        $fields = $module->columns
            ->map(fn ($column) => [
                'name' => $column->name,
                'type' => $column->column ?: 'string',
                'nullable' => str_contains((string) $column->modifiers, 'nullable'),
                'html_type' => $column->details?->html_type ?: 'text',
            ])
            ->values()
            ->all();

        $generator = new ModuleGenerator($module->name, [
            'fields' => $fields,
            'admin' => $module->admin,
            'api' => $module->api,
            'front' => $module->website,
            'seed' => true,
            'migrate' => false,
        ]);

        $generator->generate();

        return redirect()->route('larazoul.modules.index')
            ->with('success', "Module [{$module->name}] rebuilt successfully.");
    }
}