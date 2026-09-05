<?php

namespace Larazoul\Larazoul\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Support\ModuleGenerator;
use ZipArchive;

class ModuleTransferController extends Controller
{
    /**
     * Show the export/import screen.
     */
    public function index()
    {
        $modules = Module::with('columns.details')->latest()->get();

        return view('larazoul::admin.modules.transfer', compact('modules'));
    }

    /**
     * Export a module definition as a .larazoul (zip) file.
     */
    public function export(Module $module)
    {
        $payload = $this->modulePayload($module);

        $zipPath = sys_get_temp_dir() . '/' . Str::slug($module->name) . '.larazoul';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Unable to create archive.');
        }

        $zip->addFromString('module.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $zip->close();

        return response()
            ->download($zipPath, Str::slug($module->name) . '.larazoul')
            ->deleteFileAfterSend(true);
    }

    /**
     * Import a module from an uploaded .larazoul (zip) file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimetypes:application/zip,application/x-zip-compressed,multipart/x-zip'],
        ]);

        $file = $request->file('file');

        $zip = new ZipArchive();

        if ($zip->open($file->getRealPath()) !== true) {
            return back()->with('error', 'Unable to open the uploaded archive.');
        }

        $json = $zip->getFromName('module.json');

        if ($json === false) {
            $zip->close();

            return back()->with('error', 'Invalid module archive: module.json not found.');
        }

        $payload = json_decode($json, true);
        $zip->close();

        if (! is_array($payload) || empty($payload['name'])) {
            return back()->with('error', 'Invalid module archive payload.');
        }

        $module = Module::updateOrCreate(
            ['name' => $payload['name']],
            [
                'admin' => $payload['admin'] ?? true,
                'website' => $payload['website'] ?? false,
                'api' => $payload['api'] ?? false,
            ]
        );

        $module->columns()->delete();

        foreach ($payload['columns'] ?? [] as $col) {
            $column = $module->columns()->create([
                'name' => $col['name'],
                'column' => $col['type'] ?? 'string',
                'modifiers' => ($col['nullable'] ?? false) ? 'nullable' : null,
            ]);

            $column->details()->create([
                'module_id' => $module->id,
                'html_type' => $col['html_type'] ?? 'text',
                'admin_crud' => $module->admin,
                'site_crud' => $module->website,
            ]);
        }

        return redirect()->route('larazoul.modules.index')
            ->with('success', "Module [{$module->name}] imported successfully.");
    }

    /**
     * Build the portable payload for a module.
     */
    protected function modulePayload(Module $module): array
    {
        $columns = $module->columns->map(fn (Column $column) => [
            'name' => $column->name,
            'type' => $column->column ?: 'string',
            'nullable' => str_contains((string) $column->modifiers, 'nullable'),
            'html_type' => $column->details?->html_type ?: 'text',
        ])->values()->all();

        return [
            'name' => $module->name,
            'admin' => (bool) $module->admin,
            'website' => (bool) $module->website,
            'api' => (bool) $module->api,
            'columns' => $columns,
        ];
    }
}