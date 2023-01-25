<?php

namespace Larazoul\Larazoul\Larazoul\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\ColumnDetail;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Larazoul\Larazoul\Larazoul\Requests\StepFourRequest;
use Larazoul\Larazoul\Larazoul\Requests\StepOneRequest;
use Larazoul\Larazoul\Larazoul\Requests\StepTwoRequest;
use Larazoul\Larazoul\Larazoul\Traits\TypesTrait;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;

class ModuleBuilderController extends Controller
{
    use TypesTrait;

    /*
     * this class just for handel Module and
     * columns and columns details
     * all of this stuff have database record for edit
     * and delete reasons we not build forms from here
     * we build forms with include files
     */

    public function index(Module $modules)
    {

        /*
        * get all modules from
        */

        $modules = $modules->get();

        return view('larazoul::admin.generator.index', compact('modules'));
    }

    /*
     * step one will save the name of
     * module in data base
     */

    public function stepOneView($id = null, Module $module)
    {

        /*
         * if use back to this view
         * select by id
         */

        if ($id) {
            $module = $module->find($id);
        } else {
            $module = null;
        }

        return view('larazoul::admin.generator.steps.step-one.index', compact(
            'module'
        ));
    }

    /*
     * validate module name and save it on
     * database
     */

    public function stepOneStore(Module $modules, StepOneRequest $request)
    {
        $module = $modules->create($request->all());
        return redirect(route('view-step-two', ['id' => $module->id]));
    }

    /*
     * update the module
     */

    public function stepOneUpdate($id, Module $modules, StepOneRequest $request)
    {
        /*
         * find module or fail to 404
         */

        $module = $modules->findOrFail($id);

        /*
         * update with new data
         */

        $module->update($request->all());

        return redirect(route('view-step-one', ['id' => $module->id]));
    }

    /*
     * Step two will save columns in to database
     * with type generate migration file
     */

    public function stepTwoView($id, Module $module)
    {
        /*
        * get migration Columns from trait
        * key will be col type value will be description
        */

        $migrationTypes = $this->migrationColumns();

        /*
       * get Column Modifiers from trait
       * key will be col type value will be description
       */

        $migrationModifiers = $this->migrationModifiers();

        /*
         * find Module By Name
         */

        $module = $module->with('columns')->findOrFail($id);

        return view('larazoul::admin.generator.steps.step-two.index', compact(
            'module',
            'migrationTypes',
            'migrationModifiers'
        ));
    }

    /*
     * validate column name and save it on
     * database
     */

    public function stepTwoStore($id, Column $column, StepTwoRequest $request, Module $module)
    {

        /*
         * clear All column first
         */

        $module->FindOrFail($id)->columns()->delete();

        /*
         * loop on all columns
         * save all columns to database
         */

        foreach ($request->name as $key => $name) {
            $array = [
                'name' => $name,
                'column' => Str::snake($request->column[$key]),
                'modifiers' => $request->modifiers[$key],
                'module_id' => $id,
                'multi_lang' => $request->multi_lang[$key],
            ];
            $column->create($array);
        }
        return redirect(route('view-step-two', ['id' => $id]));
    }

    /*
     * delete column from column
     * table
     */

    public function deleteColumn($id, Column $column)
    {
        $item = $column->findOrFail($id);
        $item->delete();
        return redirect(route('view-step-two', ['id' => $item->module_id]));
    }

    /*
     * customise curd , fields type transformers ,
     * api , website
     */

    public function stepThreeView($id, Module $module)
    {

        /*
         * get module and columns
         */

        $module = $module->with('columns.details')->findOrFail($id);

        /*
         * get html input type
         * to set columns type base on html
         */

        $htmlInputType = $this->htmlInputType();

        /*
         * get validation rules
         */

        $validationRules = $this->validationRules();

        return view('larazoul::admin.generator.steps.step-three.index', compact(
            'module',
            'htmlInputType',
            'validationRules'
        ));

    }

    /*
     * save column details
     */

    public function stepThreeStore($id, Request $request, ColumnDetail $columnDetail, Module $module)
    {

        /*
         * check if their column details
         */

        if (!empty($request->column_id)) {

            /*
             * remove old details
             */

            $module->findOrFail($id)->details()->delete();

            /*
            * loop on column first
            */

            foreach ($request->column_id as $column) {

                $array = [
                    'validation' => isset($request->validation[$column]) ? json_encode(array_filter($request->validation[$column])) : '',
                    'transformer' => isset($request->transformer[$column]) ? $request->transformer[$column] : 'no',
                    'admin_crud' => isset($request->admin_crud[$column]) ? $request->admin_crud[$column] : 'no',
                    'site_crud' => isset($request->site_crud[$column]) ? $request->site_crud[$column] : 'no',
                    'html_type' => isset($request->html_type[$column]) ? $request->html_type[$column] : 'text',
                    'website_filter' => isset($request->website_filter[$column]) ? $request->website_filter[$column] : 'no',
                    'admin_filter' => isset($request->admin_filter[$column]) ? $request->admin_filter[$column] : 'no',
                    'custom_validation' => isset($request->custom_validation[$column]) ? $request->custom_validation[$column] : '',
                    'column_id' => $column,
                    'module_id' => $id,
                ];

                $columnDetail->create($array);

            }

        }

        return redirect(route('view-step-three', ['id' => $id]));
    }

    /*
    * relation sections
     * here will appear relation config
     * to connect between to relation
    */

    public function stepFourView($id, Module $module, Relation $relation)
    {

        /*
         * get relation types
         * to set on select
         */

        $relationType = $this->relationType();

        /*
         * get all other modules to select
         * the relation destination
         */

        $modules = $module->where('id', '!=', $id)->pluck('name', 'id');

        /*
        * get module and columns
       */

        $module = $module->with('columns.details')->findOrFail($id);

        /*
         * get this module relations
         * form data base
         */

        $relations = $relation->where('module_from_id', $module->id)->get();

        /*
         * get input type for relation
         */

        $relationInput = $this->relationInput();

        return view('larazoul::admin.generator.steps.step-four.index', compact(
            'module',
            'relationType',
            'modules',
            'relations',
            'relationInput'
        ));

    }

    /*
     * save relation setting in database this will save the
     * relation structure to build again if the folder structure
     * have been updated
     */

    public function stepFourStore($id, StepFourRequest $request, Module $module, Relation $relation)
    {
        $primaryModule = $module->findOrFail($id);

        /*
         * if there relation with this module
         * remove it first
         */

        $exists = $relation->where('module_from_id', $primaryModule->id)->where('module_to_id', $request->module_to_id)->first();

        if ($exists) {
            $exists->delete();
        }

        /*
         * add primary module id
         */

        $request->request->add([
            'module_from_id' => $primaryModule->id
        ]);

        /*
         * store relation to database
         */

        $relation->create($request->all());

        return redirect(route('view-step-four', ['id' => $primaryModule->id]));
    }

    /*
     * translation section  we will return translation file based on
     *  available language and then edit them
     */

    public function stepFiveView($id, Module $module)
    {

        /*
         * get module info
         */

        $module = $module->findOrFail($id);

        $pathToLanguage = $this->getPathOfLanguagesFile($module->name);

        $arrays = [];

        $name = strtolower($module->name);

        $languages = LaravelLocalization::getSupportedLocales();

        foreach ($languages as $key => $lang) {

            $path = fixPath($pathToLanguage . $key . '/' . $name . '.php');

            if (file_exists($path)) {
                $arrays[$key] = File::getRequire($path);
            }

        }

        return view('larazoul::admin.generator.steps.step-five.index', compact(
            'module',
            'arrays',
            'languages'
        ));

    }

    /*
     * save translation to files
     */

    public function stepFiveStore($id, Module $module, Request $request)
    {

        /*
         * get module info
         */

        $module = $module->findOrFail($id);

        $pathToLanguage = $this->getPathOfLanguagesFile($module->name);

        $languages = LaravelLocalization::getSupportedLocales();

        $name = strtolower($module->name);

        foreach ($languages as $key => $lang) {

            $path = fixPath($pathToLanguage . $key . '/' . $name . '.php');

            if (file_exists($path)) {

                $string = $this->returnWithKeysAndText($request->string[$key], $request->keys);

                $bytes_written = File::put($path, $string);

                if ($bytes_written === false) {
                    die("Error writing to file");
                }

            }

        }

        return redirect(route('modules'));

    }

    /*
     * this function will filter the request
     * and return with all language array
     */

    protected function returnWithKeysAndText($values, $keys)
    {

        $data = '<?php' . "\n";

        $data .= 'return [' . "\n";

        foreach ($values as $key => $value) {

            $data .= "\t" . "'" . $keys[$key] . "' => '" . $value . "'," . "\n";

        }

        $data .= '];';

        return $data;

    }


    /*
    * get available language files
    */

    protected function getPathOfLanguagesFile($name)
    {
        return base_path('app/Modules/' . $name . '/Resources/lang/');
    }


    /*
     * get module columns
     * ajax request
     */

    public function getColumns($id, Column $column)
    {
        return ['columns' => $column->where('module_id', $id)->get()];
    }

}
