<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Larazoul\Larazoul\Larazoul\Models\Column;

trait Upload
{


    /*
     * get upload columns and generate
     * code to save them values in controller
     */

    protected function addUploadFiles($id){

        /*
         * get upload columns first
         */

        $columns = $this->getColumnsForUploadFiles($id);

        $data = '';

        $delete = '';

        if(!empty($columns)){

            $data .= "\t\t".'$this->uploadFile([';

            foreach ($columns as $key => $column){

                $data .= "'".$column->name."'";

                if(count($columns) != ($key +1)){
                    $data .= ",";
                }

                $delete .= $this->generateDeleteFile($column);
            }

            $data .= ']);';

        }

        return [
            'data' => $data,
            'delete' => $delete
        ];

    }

    /*
     * get columns that are upload files
     */

    protected function getColumnsForUploadFiles($id){

        return Column::where('module_id' , $id)->whereHas('details' , function($query){
                return $query->whereIn('html_type' , $this->arrayOfUploadFields());
        })->get();

    }

    /*
     * delete image if there image
     * upload
     */

    protected function generateDeleteFile($column){
        return "\t\t".'Storage::delete($row->'.$column->name.');'."\n";
    }


    /*
     * detect if column is file
     */

    protected function arrayOfUploadFields(){
        return [
            'file', 'image' , 'image[]' , 'file[]'
        ];
    }


}