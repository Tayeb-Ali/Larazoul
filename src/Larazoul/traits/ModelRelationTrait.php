<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Larazoul\Larazoul\Larazoul\Models\Relation;

trait ModelRelationTrait
{

    /*
     * append to model file depend on
     * relation type one to one , one to many , many to many
     */

    protected function appendRelationModel($module_id){

        $data = '';

        $oneToOneRelation = $this->getModuleRelation($module_id , 'one_to_one');

        $data .= $this->appendRelation($oneToOneRelation ,'one_to_one');

        $oneToMayRelation = $this->getModuleRelation($module_id , 'one_to_many');

        $data .= $this->appendRelation($oneToMayRelation , 'one_to_many');

        $manyToMayRelation = $this->getModuleRelation($module_id , 'many_to_many');

        $data .= $this->appendRelation($manyToMayRelation , 'many_to_many');

        return $data;
    }

    /*
     * add lines to migration files
     */

    protected function appendRelation($relations , $type){

        if(!empty($relations)){

            $data = '';

            foreach ($relations as $relation){

                $relationName = $relation->module_to->name;

                $relationModelSingular = \Illuminate\Support\Str::singular($relationName);

                $data .= "\t".'public function '.mb_strtolower($relationModelSingular).'(){'."\n";

                if($type == 'one_to_one'){
                    $data .= $this->oneToOneLine($relationModelSingular , $relationName);
                }elseif ($type == 'one_to_many'){
                    $data .= $this->oneToManyLine($relationModelSingular , $relationName);
                }elseif ($type == 'many_to_many'){
                    $tableName = mb_strtolower($relation->module_from->name).'_'.mb_strtolower($relation->module_to->name);
                    $data .= $this->manyToManyLine($relationModelSingular , $relationName , $tableName);
                }

                $data .= "\t".'}'."\n";

            }

        }

        return $data;
    }

    protected function oneToOneLine($relationModelSingular , $relationName){
        return  "\t\t".'return $this->belongsTo(\\App\\Modules\\'.$relationName.'\\Models\\'.$relationModelSingular.'::class);'."\n";
    }

    protected function oneToManyLine($relationModelSingular , $relationName){
        return  "\t\t".'return $this->belongsTo(\\App\\Modules\\'.$relationName.'\\Models\\'.$relationModelSingular.'::class);'."\n";
    }

    protected function manyToManyLine($relationModelSingular , $relationName , $tableName){
        return  "\t\t".'return $this->belongsToMany(\\App\\Modules\\'.$relationName.'\\Models\\'.$relationModelSingular.'::class , "'.$tableName.'");'."\n";
    }

    /*
     * get module relation
     */

    protected function getModuleRelation($module_id , $type){
        return Relation::where('module_from_id' , $module_id)->where('type' , $type)->with('module_to' , 'module_from')->get();
    }

}