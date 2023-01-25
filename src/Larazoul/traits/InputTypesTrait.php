<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

trait InputTypesTrait
{

    /*
     * string input
     */

    protected function stringInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.text" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * date input
     */

    protected function dateInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.date" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * date input
     */

    protected function timeInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.time" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }


    /*
     * email input
     */

    protected function emailInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.email" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * color input
     */

    protected function colorInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.color" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * number input
     */

    protected function numberInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.number" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * password input
     */

    protected function passwordInput($name, $label, $mode)
    {
        return '@include("larazoul::fileds.php.password" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }


    /*
     * url input
     */

    protected function urlInput($name, $label, $mode)
    {
        
        return '@include("larazoul::fileds.php.url" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * file input
     */

    protected function file($name, $label, $mode)
    {

        return '@include("larazoul::fileds.php.file" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * file input array
     */

    protected function fileArray($name, $label, $mode , $module)
    {

        return '@include("larazoul::fileds.php.file-array" , ["url" => "'.strtolower($module->name).'" ,  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '[]" ])';
    }

    /*
     * image input
     */

    protected function image($name, $label, $mode)
    {

        return '@include("larazoul::fileds.php.image" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }

    /*
     * image input array
     */

    protected function imageArray($name, $label, $mode , $module)
    {
        return '@include("larazoul::fileds.php.image-array" , [ "url" => "'.strtolower($module->name).'" , "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '[]" ])';
    }

    /*
     * textArea
     */

    protected function textArea($name, $label, $mode)
    {
        
        return '@include("larazoul::fileds.php.textarea" , [  "label"  => trans("' . $label . '")  , "value" => '.$this->checkTheValue($mode , $name ).'  ,  "name" =>"' . $name . '" ])';
    }
    
    /*
     * if mode curd we will get name from 
     * $row object other wise we will get value
     * 
     */
    
    protected function checkTheValue($mode , $name){
        return $mode == 'crud' ? '$row->' . $name . ' ?? null' : 'request()->has("' . $name . '")  && request()->get("' . $name . '")  != "" ?  request()->get("' . $name . '") : null';
    }


}