<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

trait TypesTrait
{

    /*
     * get all Migration types From laravel
     * return as array
     * https://laravel.com/docs/5.6/migrations#columns
     */

    public function migrationColumns()
    {
        return [
            'string' => 'String',
            'char' => 'Char',
            'date' => 'DATE',
            'boolean' => 'Boolean',
            'double' => 'Double',
            'float' => 'Float',
            'integer' => 'Integer',
            'tinyInteger' => 'Tiny Integer',
            'ipAddress' => 'Ip Address',
            'json' => 'json',
            'text' => 'Text',
            'longText' => 'Long Text',
            'mediumText' => 'medium Text',
            'macAddress' => "Mac Address",
            'bigInteger' => 'Big Integer',
            'binary' => 'Binary',
            'year' => 'year',
        ];
    }

    /*
     * get all column Modifiers from laravel
     * return as array
     * https://laravel.com/docs/5.6/migrations#creating-indexes
     */

    public function migrationModifiers()
    {
        return [
            'autoIncrement' => 'Set INTEGER columns as auto-increment (primary key)',
            'first' => 'Place the column "first" in the table (MySQL)',
            'nullable' => 'Allows (by default) NULL values to be inserted into the column',
            'unsigned' => 'Set INTEGER columns as UNSIGNED (MySQL)',
            'useCurrent' => 'Set TIMESTAMP columns to use CURRENT_TIMESTAMP as default value',
            'unique' => 'Unique Value'
        ];
    }

    /*
     * get all html input type to set the columns
     * input type
     * https://www.w3schools.com/tags/att_input_type.asp
     */

    public function htmlInputType()
    {
        return [
            'text' => 'text',
            'color' => 'color',
            'date' => 'date',
            'email' => 'email',
            'number' => 'number',
            'password' => 'password',
            'tel' => 'tel',
            'time' => 'time',
            'url'=> 'url',
            'textarea' => 'textarea',
            'image' => 'image',
            'file' => 'file',
            'image[]' => 'image array',
            'file[]' => 'file array',
            'youtube' => 'youtube',
        ];
    }

    /*
     * get all validation  Rules form laravel
     * https://laravel.com/docs/5.6/validation#available-validation-rules
     */

    public function validationRules()
    {
        return [
            'required' => false,
            'email' => false,
            'url' => false,
            'integer' => false,
            'nullable' => false,
            'unique' => false,
            'image' => false,
            'array' => false,
            'date' => false,
            'ip' => false,
            'min' => true,
            'max' => true
        ];
    }


    public function relationType()
    {
        return [
            'one_to_one' => 'One to One',
            'one_to_many' => 'One to many',
            'many_to_many' => 'many to many',
        ];
    }


    public function relationInput()
    {
        return [
            'checkbox' => 'Checkbox',
            'select' => 'Select',
        ];
    }

}