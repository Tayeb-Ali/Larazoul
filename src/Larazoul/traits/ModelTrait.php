<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;

trait ModelTrait
{


    /*
     * generate model file based on column
     * sored on columns table
     */

    protected function generateFillAble($module_id)
    {

        /*
          * get supported language
          * to add multi language fields
          */

        $languages = LaravelLocalization::getSupportedLanguagesKeys();

        /*
         * get All Columns to generate
         * file
         */

        $columns = Column::where('module_id', $module_id)->get();

        /*
         * generate data
         */

        $data = '';
        foreach ($columns as $column) {
            if ($column->multi_lang == 1) {
                foreach ($languages as $lang) {
                    $data .= $this->tableLine($column, $column->name . '_' . $lang);
                }
            } else {
                $data .= $this->tableLine($column);
            }
        }


        foreach ($this->getModuleRelationForFillAble($module_id) as $relation) {

            $moduleName = mb_strtolower(\Illuminate\Support\Str::singular($relation->module_to->name));

            $moduleKey = $relation->module_to->getKeyName();

            $data .= $this->tableLine($moduleName . '_' . $moduleKey);

        }

        return $data.$this->addExtraFillAble();
    }

    /*
     * add more fill able fields
     */

    protected function addExtraFillAble(){

        $array = [
            'active'
        ];

        $fillAble = '';

        foreach ($array as $key){
           $fillAble =  $this->tableLine($key);
        }

        return $fillAble;

    }



    /*
     * generate getter for multi languages columns
     * generate with column name based on user language select
     */

    protected function multiColumnsGetter($module_id)
    {

        $data = '';

        /*
         * get All Columns to generate
         * file
         */

        $columns = Column::where('module_id', $module_id)->where('multi_lang', 1)->get();

        foreach ($columns as $column) {
            $data .= $this->generateGetter($column);
        }

        return $data;

    }

    /*
     * build function of getter
     */

    protected function generateGetter($column)
    {

        $data = '';

        $data .= "\t" . '/**' . "\n";
        $data .= "\t" . '* Get the users '.ucfirst(Str::camel($column->name)) . "\n";
        $data .= "\t" . '*' . "\n";
        $data .= "\t" . '* @return string' . "\n";
        $data .= "\t" . '*/' . "\n\n";
        $data .= "\t" . 'public function get' . ucfirst(Str::camel($column->name)) . 'Attribute()' . "\n";
        $data .= "\t" . '{' . "\n";
        $data .= "\t\t" . 'return $this->{\''.$column->name.'_\'.l()};' . "\n";
        $data .= "\t" . '}' . "\n\n";
        return $data;
    }


    /*
     * generate line of file
     */

    protected function tableLine($column, $name = null)
    {
        $data = '';
        $nameAfterCheckLang = $name ? $name : (isset($column->name) ? $column->name : $column);
        $data .= "\t\t\t" . "'" . $nameAfterCheckLang . "',";
        $data .= "\n";
        return $data;
    }


    /*
     * get module relation
     */

    protected function getModuleRelationForFillAble($module_id)
    {
        return Relation::where('module_from_id', $module_id)
            ->with('module_to', 'module_from')
            ->where('type', '!=', 'many_to_many')
            ->get();
    }

}