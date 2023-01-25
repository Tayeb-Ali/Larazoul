<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Module;
use Larazoul\Larazoul\Larazoul\Models\Relation;

trait SeederTrait
{

    /*
     * generate seeder file based on column
     * module data store in database
     */

    protected function getModuleText($module)
    {

        $data = '';

        $data .= "\t\t" . '$array = [' . "\n";

        $data .= $this->moduleLine('id', $module);

        foreach ($module->getFillable() as $field) {

            $data .= $this->moduleLine($field, $module);

        }

        $data .= "\t\t" . '];' . "\n";

        return $data;

    }

    /*
     * generate line for module
     */

    protected function moduleLine($field, $module)
    {

        return "\t\t\t" . "'" . $field . "' => '" . $module->{$field} . "'," . "\n";

    }

    /*
     * generate seeder file based on column
     *  data store in database
     */

    protected function getColumnText($columns)
    {

        $data = '';

        $data .= "\t\t" . '$array = [' . "\n";

        foreach ($columns as $column) {

            $data .= "\t\t\t" . '[' . "\n";

            $data .= $this->columnLine('id', $column);

            foreach ($column->getFillable() as $field) {

                $data .= $this->columnLine($field, $column);

            }

            $data .= "\t\t\t" . '],' . "\n";

        }

        $data .= "\t\t" . '];' . "\n";
        return $data;

    }


    /*
     * generate line for column
     */

    protected function columnLine($field, $column)
    {

        return "\t\t\t\t" . "'" . $field . "' => '" . $column->{$field} . "'," . "\n";

    }

    /*
     * generate seeder file based on column
     * details data store in database
     */

    protected function getColumnDetailsText($columns)
    {

        $data = '';

        $data .= "\t\t" . '$array = [' . "\n";

        foreach ($columns as $column) {

            $data .= "\t\t\t" . '[' . "\n";

            $data .= $this->detailLine('id', $column->details);

            foreach ($column->details->getFillable() as $detail) {

                $data .= $this->detailLine($detail, $column->details);

            }

            $data .= "\t\t\t" . '],' . "\n";

        }

        $data .= "\t\t" . '];' . "\n";

        return $data;

    }

    /*
  * generate line for column
  */

    protected function detailLine($field, $column)
    {

        return "\t\t\t\t" . "'" . $field . "' => '" . $column->{$field} . "'," . "\n";

    }


    /*
    * generate seeder file based on module
    * relation data store in database
    */

    protected function getModuleRelation($module_id)
    {

        $relations = Relation::where('module_from_id', $module_id)->get();

        $data = '';

        $data .= "\t\t" . '$array = [' . "\n";

        foreach ($relations as $relation) {

            $data .= "\t\t\t" . '[' . "\n";

            $data .= $this->detailLine('id', $relation);

            foreach ($relation->getFillable() as $detail) {

                $data .= $this->detailLine($detail, $relation);

            }

            $data .= "\t\t\t" . '],' . "\n";

        }

        $data .= "\t\t" . '];' . "\n";

        return $data;

    }


    protected function getMenuItems($module_id)
    {

        $module = Module::findOrFail($module_id);


        $parent = "\t\t\t\t" . '$parent = [' . "\n";
        $parent .= "\t\t\t\t\t" . '"name_ar"=>"' . $module->name . '",' . "\n";
        $parent .= "\t\t\t\t\t" . '"name_en"=>"' . $module->name . '",' . "\n";
        $parent .= "\t\t\t\t\t" . '"slug"=>"' . $module->name . '",' . "\n";
        $parent .= "\t\t\t\t\t" . '"order"=>0,' . "\n";
        $parent .= "\t\t\t\t\t" . '"menu_id"=>1,' . "\n";
        $parent .= "\t\t\t\t\t" . '"parent_id"=>0,' . "\n";
        $parent .= "\t\t\t\t\t" . '"icon"=>"<i class=\"fa fa-server\"></i>",' . "\n";
        $parent .= "\t\t\t\t\t" . '"link"=>"",' . "\n";
        $parent .= "\t\t\t" . '];' . "\n";


        $child = "\t\t\t" . '$child = [' . "\n";

        $child .= "\t\t\t\t" . '[' . "\n";
        $child .= "\t\t\t\t\t" . '"name_ar"=>"Control ' . $module->name . '",' . "\n";
        $child .= "\t\t\t\t\t" . '"name_en"=>"Control ' . $module->name . '",' . "\n";
        $child .= "\t\t\t\t\t" . '"slug"=>"' . $module->name . '",' . "\n";
        $child .= "\t\t\t\t\t" . '"order"=>0,' . "\n";
        $child .= "\t\t\t\t\t" . '"menu_id"=>1,' . "\n";
        $child .= "\t\t\t\t\t" . '"parent_id"=> $parent->id ,' . "\n";
        $child .= "\t\t\t\t\t" . '"icon"=>"",' . "\n";
        $child .= "\t\t\t\t\t" . '"link"=>"/admin/'.strtolower($module->name).'",' . "\n";
        $child .= "\t\t\t" . '],' . "\n";

        $child .= "\t\t\t\t" . '[' . "\n";
        $child .= "\t\t\t\t\t" . '"name_ar"=>"Create ' . $module->name . '",' . "\n";
        $child .= "\t\t\t\t\t" . '"name_en"=>"Create ' . $module->name . '",' . "\n";
        $child .= "\t\t\t\t\t" . '"slug"=>"' . $module->name . '",' . "\n";
        $child .= "\t\t\t\t\t" . '"order"=>0,' . "\n";
        $child .= "\t\t\t\t\t" . '"menu_id"=>1,' . "\n";
        $child .= "\t\t\t\t\t" . '"parent_id"=> $parent->id ,' . "\n";
        $child .= "\t\t\t\t\t" . '"icon"=>"",' . "\n";
        $child .= "\t\t\t\t\t" . '"link"=>"/admin/'.strtolower($module->name).'/create",' . "\n";
        $child .= "\t\t\t" . '],' . "\n";

        $child .= "\t\t\t" . '];' . "\n";


        return ['parent' => $parent , 'child' => $child ];

    }

}