<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait ResourcesTrait
{

    /*
     * generate filter function
     * this filter will filter the data
     * based on column details user store
     */

    public function generateResources($module_id){

        $columns = $this->getColumnsForResources($module_id);

        $data = '';
        $data  .= $this->addResourceLine('id');
        foreach ($columns as $column){
            if ($column->multi_lang == 1) {
                foreach ($this->languageForResources() as $lang) {
                    $data  .= $this->addResourceLine($column->name.'_'.$lang);
                }
            } else {
                $data  .= $this->addResourceLine($column->name);
            }
        }
        $data  .= $this->addResourceLine('created_at');
        $data  .= $this->addResourceLine('updated_at');
        return $data;
    }

    /*
     * generate relation in resources
     */

    protected function generateRelation($module_id){

        $relations = Relation::where('module_from_id' , $module_id)->with('module_from' , 'module_to')->get();

        $nameSpace = '';

        $data = '';

        foreach ($relations as $relation){

            $nameSpace .= 'use App\\Modules\\'.$relation->module_to->name.'\\Http\\Resources\\'.$relation->module_to->name .' as '.$relation->module_to->name.'Resources;'."\n";

            if($relation->type != 'one_to_one'){
                $name = strtolower($relation->module_to->name);
                $data  .= "\t\t\t".'"'.$name.'" => '.$relation->module_to->name.'Resources::collection($this->'.\Illuminate\Support\Str::singular($name).'),'."\n";
            }else{
                $name = strtolower(\Illuminate\Support\Str::singular($relation->module_to->name));
                $data  .= "\t\t\t".'"'.$name.'" => new '.$relation->module_to->name.'Resources($this->'.$name.'),'."\n";
            }
        }

        return ['namespace' => $nameSpace , 'data' => $data];

    }


    public function addResourceLine($name){
     $data = '';
     $data  .= "\t\t\t".'"'.$name.'" => $this->'.$name.','."\n";
     return $data;
    }


    /*
    * get all columns with details if enable filters
    */

    protected function getColumnsForResources($module_id)
    {
        return Column::where('module_id', $module_id)->whereHas('details' , function ($query){
            return $query->where('transformer' , 'yes');
        })->get();
    }

    /*
   * return with  available language
   */

    protected function languageForResources()
    {
        return LaravelLocalization::getSupportedLanguagesKeys();
    }


}