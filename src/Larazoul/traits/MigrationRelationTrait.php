<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Larazoul\Larazoul\Larazoul\Models\Relation;

trait MigrationRelationTrait
{

    /*
     * append to migration file depend on
     * relation type one to one , one to many
     */

    protected function appendRelationMigration($module_id){

        $data = '';

        $oneToOneRelation = $this->getModuleRelation($module_id , 'one_to_one');

        $data .= $this->appendToMigration($oneToOneRelation);

        $oneToMayRelation = $this->getModuleRelation($module_id , 'one_to_many');

        $data .= $this->appendToMigration($oneToMayRelation);

        return $data;
    }

    /*
     * new migration file to many to many relation
     * this will generate new table
     */

    protected function manyToManyMigration($module_id){

        $manyToManyRelation = $this->getModuleRelation($module_id , 'many_to_many');

        $data =  $this->appendManyToMany($manyToManyRelation);

        return $data;

    }

    /*
     * migration lines
     */

    protected function appendManyToMany($manyToManyRelation){

        if(!empty($manyToManyRelation)){

            $array = [];

            foreach ($manyToManyRelation as $relation){

                $data = '';

                $foreignKeyId = $relation->module_to->getKeyName();

                $primaryKeyId = $relation->module_from->getKeyName();

                $primaryName = \Illuminate\Support\Str::singular(mb_strtolower($relation->module_from->name)).'_'.$primaryKeyId;

                $foreignName = \Illuminate\Support\Str::singular(mb_strtolower($relation->module_to->name)).'_'.$foreignKeyId;

                $foreignTable = mb_strtolower($relation->module_to->name);

                $primaryTable = mb_strtolower($relation->module_from->name);

                $data .= "\t\t\t\t".'$table->unsignedInteger("'.$primaryName.'");'."\n";

                $data .= "\t\t\t\t".'$table->foreign("'.$primaryName.'")->references("'.$primaryKeyId.'")->on("'.$primaryTable.'")->onDelete("cascade")->onUpdate("cascade");'."\n";

                $data .= "\t\t\t\t".'$table->unsignedInteger("'.$foreignName.'");'."\n";

                $data .= "\t\t\t\t".'$table->foreign("'.$foreignName.'")->references("'.$foreignKeyId.'")->on("'.$foreignTable.'")->onDelete("cascade")->onUpdate("cascade");'."\n";

                $array[] =  [ $relation->module_to->name => $data ];

            }

        }

        return $array;

    }


    /*
     * add lines to migration files
     */

    protected function appendToMigration($relations){

        if(!empty($relations)){

            $data = '';

            foreach ($relations as $relation){

                $primaryKey = $relation->module_from->getKeyName();

                $name = \Illuminate\Support\Str::singular(mb_strtolower($relation->module_to->name)).'_'.$primaryKey;

                $foreignTable = mb_strtolower($relation->module_to->name);

                $data .= "\t\t\t\t".'$table->unsignedInteger("'.$name.'");'."\n";

                $data .= "\t\t\t\t".'$table->foreign("'.$name.'")->references("'.$primaryKey.'")->on("'.$foreignTable.'")->onDelete("cascade")->onUpdate("cascade");'."\n";

            }

        }

        return $data;

    }

    /*
     * get module relation
     */

    protected function getModuleRelation($module_id , $type){
        return Relation::where('module_from_id' , $module_id)->where('type' , $type)->with('module_to' , 'module_from')->get();
    }

}