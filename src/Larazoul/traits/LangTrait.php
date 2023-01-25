<?php

namespace Larazoul\Larazoul\Larazoul\Traits;



use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Relation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;

trait LangTrait
{

   /*
    * generate seeder file based on column
    * module data store in database
    */

    protected function getLanguageArray($key , $module){

        $data = '';

        $data .=  $this->addLine($module->name , $module->name );

        $small = strtolower($module->name);

        $data .=  $this->addLine( $small, $small );

        $single = \Illuminate\Support\Str::singular($module->name);

        $data .=  $this->addLine($single , $single );

        $small = strtolower($single);

        $data .=  $this->addLine($small , $small );

        /*
         * add column translation
         */

        foreach ($module->columns as $column){
            if($column->multi_lang == 1){
                foreach (LaravelLocalization::getSupportedLanguagesKeys() as $key){
                    $nameWithLanguage = $column->name.'_'.$key;
                    $data .=  $this->addLine($nameWithLanguage , $nameWithLanguage);
                }
            }else{
                $data .=  $this->addLine($column->name ,$column->name );
            }
        }

        /*
        * add default translation
        */


        foreach ($this->defaultLanguageKey() as $key =>  $value){
            $data .=  $this->addLine($key , $value );
        }

        return $data;

    }

    /*
     * translate relation modules columns
     * this will translate modules labels
     */

    public function getRelationTranslation($key , $module_id){

        $data = '';

        $relations = Relation::where('module_from_id' , $module_id)->with('module_to' , 'module_from')->get();

        foreach ($relations as $relation){

                $data .=  $this->addLine($relation->module_to->name , $relation->module_to->name);

        }

        return $data;

    }

    /*
     * default keys for all modules
     */

    protected function defaultLanguageKey(){
        return [
            'Delete' => 'Delete',
            'Edit' => 'Edit',
            'create' => 'create',
            'index' => 'index',
            'home' => 'home',
            'add' => 'add',
            'Save' => 'Save',
            'edit' => 'edit',
            'Search' => 'Search',
            'Reset' => 'Reset',
            'Active' => 'Active'
        ];
    }

    /*
     * add line
     */

    protected function addLine($key , $value){
        return "\t"."'".$key."' => '".Str::studly($value) ."',"."\n";
    }

}