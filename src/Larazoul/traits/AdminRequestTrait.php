<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait AdminRequestTrait
{


    protected $cusotmeRule = [
        'unique'
    ];

    protected $removeRequireInUpdate = [
        'image', 'password', 'file', 'image[]', 'file[]'
    ];

    protected $countRules = 0;

    /*
     * generate Request file based on column
     * sored on columns table
     */

    protected function generateRules($module_id)
    {

        /*
         * get module info
         */

        $module = Module::find($module_id);

        /*
         * get All Columns to generate
         * file
         */

        $columns = $this->getColumns($module_id);

        /*
         * generate data
         */

        $data = '';
        $data1 = '';
        foreach ($columns as $column) {
            if ($column->multi_lang == 1) {
                foreach ($this->language() as $lang) {
                    $data .= $this->buildRules($column->details->validation, $column->details->custom_validation, $column->name . '_' . $lang, $module, $column);
                    $data1 .= $this->buildOverRideRules($column->details->validation, $column->details->custom_validation, $column->name . '_' . $lang, $module, $column);
                }
            } else {
                $data .= $this->buildRules($column->details->validation, $column->details->custom_validation, $column->name, $module, $column);
                $data1 .= $this->buildOverRideRules($column->details->validation, $column->details->custom_validation, $column->name, $module, $column);
            }
        }
        $data .= $this->relationRequest($module_id);
        return ['rules' => $data, 'overRide' => $data1];
    }


    /*
     * transform rules to from json to text
     */

    protected function buildRules($rules, $custom_rule, $name, $module, $column)
    {

        $data = '';

        $rules = json_decode($rules, true);

        $name = str_contains($column->details->html_type, '[]') ? $name . '.*' : $name;

        $data .= "\t\t\t" . "'" . $name . "' => '";

        $match = array_intersect($rules, $this->cusotmeRule);

        $data .= $this->normalValidation(array_diff($rules, $this->cusotmeRule));

        if (!empty($match)) {
            $data .= $this->customValidation($match, $module);
        }

        if ($custom_rule != '') {
            $data .= '|' . $custom_rule;
        }

        $data .= "'," . "\n";

        return $data;
    }


    protected function buildOverRideRules($rules, $custom_rule, $name, $module, $column)
    {

        $data = '';

        $rules = json_decode($rules, true);

        $match = array_intersect($rules, $this->cusotmeRule);

        if (!empty($match)) {

            $name = str_contains($column->details->html_type, '[]') ? $name . '.*' : $name;

            $data .= "\t\t\t\t" . "'" . $name . "' => '";

            $data .= $this->normalValidation(array_diff($rules, $this->cusotmeRule));

            if ($custom_rule != '') {
                $data .= '|' . $custom_rule;
            }

            $data .= $this->customValidationOverRide($match, $module, $name);

            $data .= "," . "\n";

            return $data;

        }

        /*
         * remove required in update if the column is image
         * or if the column is password
         */

        if (in_array($column->details->html_type, $this->removeRequireInUpdate)) {

            $data = "\t\t\t\t" . "'" . $name . "' => '";

            $validation = $this->normalValidation(array_diff($rules, $this->cusotmeRule));

            $validation = str_replace('required', '', $validation);

            $validation = str_replace('nullable', '', $validation);

            if ($custom_rule != '') {
                $validation .= '|' . $custom_rule;
            }

            $validation = trim($validation, '|');

            $validation .= $this->customValidationOverRide([$column->details->html_type], $module, $name);

            $validation = trim($validation, '|');

            $data .= $validation;

            $data .= "'," . "\n";

            return $data;

        }

    }

    /*
     * start to write custom validation For Override fields
     */

    protected function customValidationOverRide($match, $module, $name)
    {
        $data = '';
        foreach ($match as $key => $value) {
            $data .= $this->handelCustomValidationOverRider($value, $module, $name);
        }
        return $data;
    }


    /*
    * switch on every custom validation for override
    */

    protected function handelCustomValidationOverRider($match, $module, $name)
    {
        switch ($match) {
            case "unique":
                $smallName = mb_strtolower($module->name);
                $sprated = $this->countRules !== 0 ? '|' : '';
                return $sprated . "unique:" . $smallName . ',' . $name . ',\'.$' . \Illuminate\Support\Str::singular($smallName) . '->id';
                break;
            case "image":
                return "|nullable";
                break;
            case "password":
                return "|nullable";
                break;
            case "file":
                return "|nullable";
                break;
            case "file[]":
                return "|nullable";
                break;
            case "image[]":
                return "|nullable";
                break;
        }
    }


    /*
     * start to write custom validation
     */

    protected function customValidation($match, $module)
    {
        $data = '';
        foreach ($match as $key => $value) {
            $data .= $this->handelCustomValidation($value, $module);
        }
        return $data;
    }

    /*
     * switch on every custom validation
     */

    protected function handelCustomValidation($match, $module)
    {
        switch ($match) {
            case "unique":
                $sprated = $this->countRules !== 0 ? '|' : '';
                return $sprated . "unique:" . mb_strtolower($module->name);
                break;
        }
    }


    /*
     * normal validation handel
     */

    protected function normalValidation($rules)
    {
        $data = '';
        $this->countRules = count($rules);

        $i = 0;

        foreach ($rules as $key => $rule) {
            if ($key == $rule) {
                $data .= $key;
                $data .= $this->checkIfThisLastLoop($i);
            } else {
                if ($rule != '') {
                    $data .= $key . ':' . $rule;
                    $data .= $this->checkIfThisLastLoop($i);
                }
            }
            $i++;
        }
        return $data;
    }

    /*
     * check if this loop is the end
     */

    protected function checkIfThisLastLoop($i)
    {
        if ($i !== ($this->countRules - 1)) {
            return '|';
        }
    }

    /*
    * get all columns with details
     */

    protected function getColumns($module_id)
    {
        return Column::where('module_id', $module_id)->with('details')->get();
    }

    /*
     * return with  available language
     */

    protected function language()
    {
        return LaravelLocalization::getSupportedLanguagesKeys();
    }

    /*
     * if relation append relation fields
     * to request
     */

    protected function relationRequest($module_id)
    {

        $data = '';

        foreach ($this->getModuleRelationRequest($module_id) as $relation) {

            $moduleName = mb_strtolower(\Illuminate\Support\Str::singular($relation->module_to->name));

            $moduleKey = $relation->module_to->getKeyName();

            $data .= "\t\t\t" . '"' . $moduleName . '_' . $moduleKey . '" => "required|integer" ,' . "\n";

        }

        return $data;

    }

    /*
   * get module relation
   */

    protected function getModuleRelationRequest($module_id)
    {
        return Relation::where('module_from_id', $module_id)
            ->with('module_to', 'module_from')
            ->where('type', '!=', 'many_to_many')
            ->get();
    }


}