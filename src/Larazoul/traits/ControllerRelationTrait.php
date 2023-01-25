<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Larazoul\Larazoul\Larazoul\Models\Relation;

trait ControllerRelationTrait
{

    /*
     * build the relation logic to store in database
     *  depend on relation type
     */

    protected function appendRelation($module_id)
    {

        $manyToManyRelation = $this->getModuleRelationForController($module_id, 'many_to_many');

        $save = $this->saveCode($manyToManyRelation);

        return $save;
    }


    /*
     * generate sync code
     * to sync relation on store and update functions
     */


    protected function saveCode($manyToManyRelation)
    {

        $data = '';
        if (!empty($manyToManyRelation)) {

            foreach ($manyToManyRelation as $relation) {

                $relationName = \Illuminate\Support\Str::singular(mb_strtolower($relation->module_to->name));

                $data .= "\t\t" . '$row->' . $relationName . '()->sync($request->' . $relationName . '_' . $relation->module_to->getKeyName() . ');' . "\n";

            }
        }
        return $data;
    }


    /*
    * eager load the many to many
     * one to many , one to one
     * relation in controller
     * for more performance
   */

    protected function addToEagerLoad($module_id)
    {
        $index = '';
        $showEdit = '';

        $manyToManyRelation = $this->getModuleRelationForController($module_id);

        if ($manyToManyRelation->count() > 0) {

            $text = $this->addToViewEdit($manyToManyRelation);

            if ($text != '') {
                $index = "\n\t\t" . '$rows = $rows->' . $text . ';' . "\n";

                $showEdit = $this->addToViewEdit($manyToManyRelation) . '->';
            }

        }

        return ['index' => $index, 'showEdit' => $showEdit];
    }


    /*
     * lines to eager load
     */

    protected function addToViewEdit($manyToManyRelation)
    {
        $data = '';

        if (!empty($manyToManyRelation)) {

            $data = 'with([';

            foreach ($manyToManyRelation as $key => $relation) {

                $relationName = \Illuminate\Support\Str::singular(mb_strtolower($relation->module_to->name));

                $data .= '"' . $relationName . '"';

                if (count($manyToManyRelation) - 1 != $key) {
                    $data .= ',';
                }

            }

            $data .= '])';
        }
        return $data;
    }


    /*
     * get module relation
     */

    protected function getModuleRelationForController($module_id, $type = null)
    {
        $relation = Relation::where('module_from_id', $module_id);
        if ($type != null) {
            $relation = $relation->where('type', $type);
        }
        $relation = $relation->with('module_to', 'module_from')->get();
        return $relation;
    }

}