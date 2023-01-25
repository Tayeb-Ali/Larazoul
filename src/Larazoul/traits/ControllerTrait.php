<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait ControllerTrait
{

    /*
     * generate filter function
     * this filter will filter the data
     * based on column details user store
     */

    public function generateFilters($module_id){

        $columns = $this->getColumnsForFilters($module_id);

        $data = '';

        foreach ($columns as $column){
            if ($column->multi_lang == 1) {
                foreach ($this->languageForForm() as $lang) {
                    $data  .= $this->addLineToFilterFunction($column->name.'_'.$lang);
                }
            } else {
                $data  .= $this->addLineToFilterFunction($column->name);
            }
        }
        return $data;
    }


    public function addLineToFilterFunction($name){
     $data = '';
     $data  .= "\t\t".'if($this->request->has("'.$name.'") && $this->request->get("'.$name.'") != ""){'."\n";
     $data  .= "\t\t\t".'$rows->where("'.$name.'" , $this->request->get("'.$name.'"));'."\n";
     $data  .= "\t\t".'}'."\n";
     return $data;
    }


    /*
    * get all columns with details if enable filters
    */

    protected function getColumnsForFilters($module_id)
    {
        return Column::where('module_id', $module_id)->whereHas('details' , function ($query){
            return $query->where('admin_filter' , 'yes');
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