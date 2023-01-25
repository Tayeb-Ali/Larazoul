<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait AdminFormTrait
{

    use  InputTypesTrait, RelationFormTrait;

    protected $module;

    /*
     * generate view file based on column
     * sored on columns table
     */

    /*
     * generate table headers
     */

    protected function generateForms($module_id, $grid = null)
    {
        /*
         * get module info
         */

        $module = Module::find($module_id);

        $this->module = $module;

        /*
         * get All Columns to generate
         * file
         */

        $columns = $this->getColumnsForForm($module_id);

        return $this->loopAndGenerate($module, $columns, $grid, 'crud' , $module);
    }

    /*
   * generate table filters
   */

    protected function generateFilters($module_id, $grid = null, $type = 'admin_filter')
    {

        /*
         * get module info
         */

        $module = Module::find($module_id);

        /*
         * get All Columns to generate
         * file
         */

        $columns = $this->getColumnsForFilters($module_id, $type);

        return $this->loopAndGenerate($module, $columns, $grid, 'filter' , $module);
    }

    protected function loopAndGenerate($module, $columns, $grid, $mode , $moduleInfo)
    {

        /*
         * generate data
         */

        $data = '';
        foreach ($columns as $column) {
            $label = mb_strtolower($module->name) . "::" . mb_strtolower($module->name);
            if ($column->multi_lang == 1) {
                foreach ($this->languageForForm() as $lang) {
                    $name = $column->name . '_' . $lang;
                    $data .= $this->openGrid($grid);
                    $data .= "\t\t" . $this->checkColumnType($column->details, $name, $label . '.' . $name, $mode , $moduleInfo) . "\n";
                    $data .= $this->closeGrid($grid);
                }
            } else {
                $data .= $this->openGrid($grid);
                $data .= "\t\t" . $this->checkColumnType($column->details, $column->name, $label . '.' . $column->name, $mode , $moduleInfo) . "\n";
                $data .= $this->closeGrid($grid);
            }

        }
        return $data;
    }

    /*
     * open grid div
     */

    protected function openGrid($grid)
    {
        return $grid ? "\t" . '<div class="col-lg-' . $grid . '">' . "\n" : '';
    }

    /*
     * close open grid div
     */

    protected function closeGrid($grid)
    {
        return $grid ? "\t" . '</div>' . "\n" : '';
    }

    protected function checkColumnType($details, $name, $label, $mode = 'crud' , $moduleInfo)
    {
        switch ($details->html_type) {
            case "text":
                return $this->stringInput($name, $label, $mode);
                break;
            case "date":
                return $this->dateInput($name, $label, $mode);
                break;
            case "email":
                return $this->emailInput($name, $label, $mode);
                break;
            case "color":
                return $this->colorInput($name, $label, $mode);
                break;
            case "number":
                return $this->numberInput($name, $label, $mode);
                break;
            case "password":
                return $this->passwordInput($name, $label, $mode);
                break;
            case "url":
                return $this->urlInput($name, $label, $mode);
                break;
            case "textarea":
                return $this->textArea($name, $label, $mode);
                break;
            case "image":
                return $this->image($name, $label, $mode);
                break;
            case "image[]":
                return $this->imageArray($name, $label, $mode ,  $moduleInfo);
                break;
            case "file":
                return $this->file($name, $label, $mode);
                break;
            case "file[]":
                return $this->fileArray($name, $label, $mode ,  $moduleInfo);
                break;
            default:
                return $this->stringInput($name, $label, $mode);
        }
    }

    /*
    * get all columns with details
     */

    protected function getColumnsForForm($module_id)
    {
        return Column::where('module_id', $module_id)->with('details')->get();
    }

    /*
    * get all columns with details if enable filters
     */

    protected function getColumnsForFilters($module_id, $type)
    {
        return Column::where('module_id', $module_id)->whereHas('details', function ($query) use ($type) {
            return $query->where($type, 'yes');
        })->get();
    }


    /*
     * return with  available language
     */

    protected function languageForForm()
    {
        return LaravelLocalization::getSupportedLanguagesKeys();
    }

}