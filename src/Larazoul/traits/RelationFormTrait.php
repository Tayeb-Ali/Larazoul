<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Relation;

trait RelationFormTrait
{


    /*
     * build relation form
     */

    protected function getRelationForm($module_id){

        $data = '';

        $oneToOneRelation = $this->getModuleRelation($module_id , 'one_to_one');

        $data .= $this->appendToForm($oneToOneRelation , 'one_to_one');

        $oneToManyRelation = $this->getModuleRelation($module_id , 'one_to_many');

        $data .= $this->appendToForm($oneToManyRelation , 'one_to_many');

        $manyToManyRelation = $this->getModuleRelation($module_id , 'many_to_many');

        $data .= $this->appendToForm($manyToManyRelation , 'many_to_many');

        return $data;

    }


    protected function appendToForm($oneToOneRelation , $type){

        $data = '';

        foreach ($oneToOneRelation as $relation){

            $primaryKey = $relation->module_to->getkeyName();

            $module = \Illuminate\Support\Str::singular(mb_strtolower($relation->module_to->name));

            $name = $module.'_'.$primaryKey;

            if($type == 'one_to_one'){
                $data .= $this->appendInput($relation , $name);
            }elseif ($type == 'one_to_many'){
                $data .= $this->appendInput($relation , $name);
            }elseif ($type == 'many_to_many'){
                $data .= $this->appendInputManyToMany($relation , $name , $module);
            }

        }

        return $data;
    }

    protected function getLabelAndArray($relation){
        $label = mb_strtolower($relation->module_from->name). '::'.mb_strtolower($relation->module_from->name).'.'.$relation->module_to->name;

        $pluckName = "'".$relation->column->name."'";

        if($relation->column->multi_lang == 1){
            $pluckName = '"'.$relation->column->name.'_".l()';
        }

        $array = '\\App\\Modules\\'.$relation->module_to->name.'\\Models\\'.\Illuminate\Support\Str::singular($relation->module_to->name).'::active()->pluck('.$pluckName.', "'.$relation->module_to->getkeyName().'")->toArray()';

        return ['label' => $label , 'array' => $array];
    }

    protected function appendInput($relation , $name ){

        $info = $this->getLabelAndArray($relation);

        if($relation->input_type == 'select'){
            return '@include("larazoul::fileds.php.select" , [  "label"  => trans("'.$info['label'].'") , "array" => '.$info['array'].'  , "value" => $row->' . $name . ' ?? null'.'  ,  "name" =>"' . $name . '" ])'."\n";
        }elseif ($relation->input_type == 'checkbox'){
            return '@include("larazoul::fileds.php.checkbox" , [  "label"  => trans("'.$info['label'].'") , "array" => '.$info['array'].'   , "selectedArray" => $row->' . $name . ' ?? null'.'  ,  "name" =>"' . $name . '" ])'."\n";
        }
    }

    protected function appendInputManyToMany($relation , $name  , $module){

        $info = $this->getLabelAndArray($relation);

        $pluck = '->pluck("'.$relation->module_to->getkeyName().'")->toArray()';

        if($relation->input_type == 'select'){
            return '@include("larazoul::fileds.php.select" , [  "label"  => trans("'.$info['label'].'") , "array" => '.$info['array'].'  , "value" => $row->' . $module . $pluck. ' ?? null'.'  ,  "name" =>"' . $name . '[]" , "multiSelect" => true ])'."\n";
        }elseif ($relation->input_type == 'checkbox'){
            return '@include("larazoul::fileds.php.checkbox" , [  "label"  => trans("'.$info['label'].'") , "array" => '.$info['array'].'   , "selectedArray" => $row->' . $module .$pluck . ' ?? null'.'  ,  "name" =>"' . $name . '[]" ])'."\n";
        }
    }


    /*
     * get module relation
     */

    protected function getModuleRelation($module_id , $type){
        return Relation::where('module_from_id' , $module_id)->where('type' , $type)->with('module_to' , 'module_from')->get();
    }


}