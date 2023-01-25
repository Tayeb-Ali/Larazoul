<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Larazoul\Larazoul\Larazoul\Models\ColumnDetail;
use Illuminate\Support\Str;

trait MultiValueHandel{

    use TypesTrait;

    /*
     * generate setter and getter for multi columns
     * value to save in model
     */

    protected function generateMultiValueColumnsSetterGetter($id){

        $data = '';

        /*
         * get columns of this module
         * that have multi value
         */

        $columns = ColumnDetail::where('module_id'  , $id)->whereIn('html_type' , $this->multiColumnArray())->with('column')->get();


        /*
         * now passed columns to generator
         */

        foreach ($columns as $column){

            if($column->column->mlti_lang == 1){

            }else{
                $data .= $this->generateGetterForMultiColumnsArrayValue($column->column);
                $data .= $this->generateGetterForMultiColumnsValue($column->column);
                $data .= $this->generateSetterForMultiColumnsValue($column->column);
            }

        }

        return $data;


    }


    /*
     * generate getter for each column
     */


    protected function generateGetterForMultiColumnsArrayValue($column)
    {
        $data = "\t" . '/**' . "\n";
        $data .= "\t" . '* Get the  '.ucfirst(Str::camel($column->name)) . "\n";
        $data .= "\t" . '*' . "\n";
        $data .= "\t" . '* @return string' . "\n";
        $data .= "\t" . '*/' . "\n\n";
        $data .= "\t" . 'public function get' . ucfirst(Str::camel($column->name)) . 'ArrayAttribute()' . "\n";
        $data .= "\t" . '{' . "\n";
        $data .= "\t\t" . 'return json_decode($this->attributes["'.$column->name.'"]);' . "\n";
        $data .= "\t" . '}' . "\n\n";
        return $data;
    }


    /*
     * generate getter for each column
     */

    protected function generateGetterForMultiColumnsValue($column)
    {
        $data = "\t" . '/**' . "\n";
        $data .= "\t" . '* Get the  '.ucfirst(Str::camel($column->name)) . "\n";
        $data .= "\t" . '*' . "\n";
        $data .= "\t" . '* @return string' . "\n";
        $data .= "\t" . '*/' . "\n\n";
        $data .= "\t" . 'public function get' . ucfirst(Str::camel($column->name)) . 'Attribute($value)' . "\n";
        $data .= "\t" . '{' . "\n";
        $data .= "\t\t" . 'return json_decode($value);' . "\n";
        $data .= "\t" . '}' . "\n\n";
        return $data;
    }


    /*
     * generate setter for each column
     */


    protected function generateSetterForMultiColumnsValue($column)
    {
        $data = "\t" . '/**' . "\n";
        $data .= "\t" . '* Set the  '.ucfirst(Str::camel($column->name)) . "\n";
        $data .= "\t" . '*' . "\n";
        $data .= "\t" . '* @param  string  $value' . "\n";
        $data .= "\t" . '* @return void' . "\n";
        $data .= "\t" . '*/' . "\n\n";
        $data .= "\t" . 'public function set' . ucfirst(Str::camel($column->name)) . 'Attribute($value)' . "\n";
        $data .= "\t" . '{' . "\n";
        $data .= "\t\t" . '$this->attributes["'.$column->name.'"] = json($value);' . "\n";
        $data .= "\t" . '}' . "\n\n";
        return $data;
    }

    /*
     * detect multi value column
     * @return array
     */

    protected function multiColumnArray(){

        $array = [];

        $types = $this->htmlInputType();

        foreach ($types as $key => $value){

            if(str_contains($key , '[]')){
                $array[] = $key;
            }

        }

        return $array;

    }


}