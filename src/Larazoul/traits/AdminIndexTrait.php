<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Models\Column;
use Larazoul\Larazoul\Larazoul\Models\Module;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait AdminIndexTrait
{


    /*
     * generate view file based on column
     * sored on columns table
     */

    /*
     * generate table headers
     */

    protected function generateHeader($module_id  , $type = 'admin_crud')
    {

        /*
         * get module info
         */

        $module = Module::find($module_id);

        /*
         * get All Columns to generate
         * file
         */

        $columns = $this->getColumnsAdminIndex($module_id , $type);

        /*
         * generate data
         */

        $data = '';
        foreach ($columns as $column) {
            if($column->multi_lang == 1){
                foreach ($this->languageAdminIndex() as $lang){
                    $data .= $this->headerLine($column , $module , $column->name.'_'.$lang);
                }
            }else{
                $data .= $this->headerLine($column , $module);
            }
        }
        return $data;
    }

    /*
     * generate line of file
     */

    protected function headerLine($column , $module , $name = null)
    {
        $nameAfterCheckLang = $name ? $name : $column->name;
        $moduleName = mb_strtolower($module->name);
        $data = '';
        $data .= "\t\t\t\t\t\t" . "<th>";
        $data .= "\n";
        $data .= "\t\t\t\t\t\t\t" . '<a href="{{ route("'.$moduleName.'.index") }}?orderBy='.$name.'{{ orderType("'.$name.'") }}">';
        $data .= "\n";
        $data .= "\t\t\t\t\t\t\t\t" . "@lang('".$moduleName."::".$moduleName.".".$nameAfterCheckLang."')";
        $data .= "\n";
        $data .= "\t\t\t\t\t\t\t" . '</a>';
        $data .= "\n";
        $data .= "\t\t\t\t\t\t" . "</th>";
        $data .= "\n";
        return $data;
    }

    /*
     * generate table rows
     */

    protected function generateBody ($module_id , $type = 'admin_crud'){

        /*
         * get All Columns to generate
         * file
         */

        $columns = $this->getColumnsAdminIndex($module_id , $type);

        /*
         * generate data
         */

        $data = '';
        foreach ($columns as $column) {
            if($column->multi_lang == 1){
                foreach ($this->languageAdminIndex() as $lang){
                    if(in_array($column->details->html_type , $this->arrayOfImageFieldsForAdminIndex())){
                        $data .= $this->imageLine($column, $column->name.'_'.$lang);
                    }else if(in_array($column->details->html_type , $this->arrayOfFilesFieldsForAdminIndex())){
                        $data .= $this->fileLine($column, $column->name.'_'.$lang);
                    }else{
                        $data .= $this->bodyLine($column, $column->name.'_'.$lang);
                    }
                }
            }else{
                if(in_array($column->details->html_type , $this->arrayOfImageFieldsForAdminIndex())){
                    $data .= $this->imageLine($column);
                }else if(in_array($column->details->html_type , $this->arrayOfFilesFieldsForAdminIndex())){
                    $data .= $this->fileLine($column);
                }else{
                    $data .= $this->bodyLine($column);
                }

            }
        }

        return $data;
    }


    /*
     * generate  image line of file
     */

    protected function imageLine($column , $name = null)
    {
        $nameAfterCheckLang = $name ? $name : $column->name;
        if(str_contains($column->details->html_type , '[]')){
            return "\t\t\t\t\t\t\t" . '<td><a href="{{ Storage::url(getFirstElement($row->'.$nameAfterCheckLang.')) }}"><img src="{{ Storage::url(getFirstElement($row->'.$nameAfterCheckLang.')) }}" width=\'100\'></a></td>'."\n";
        }else{
            return "\t\t\t\t\t\t\t" . '<td><a href="{{ Storage::url($row->'.$nameAfterCheckLang.') }}"><img src="{{ Storage::url($row->'.$nameAfterCheckLang.') }}" width=\'100\'></a></td>'."\n";
        }
//        return "\t\t\t\t\t\t\t" . '<td><a href="{{ url(\'storage/app/\'.$row->'.$nameAfterCheckLang.') }}"><img src="{{ url(\'storage/app/\'.$row->'.$nameAfterCheckLang.') }}" width=\'100\'></a></td>'."\n";

    }

    /*
     * generate file line of file
     */

    protected function fileLine($column , $name = null)
    {
        $nameAfterCheckLang = $name ? $name : $column->name;
        if(str_contains($column->details->html_type , '[]')){
            return "\t\t\t\t\t\t\t" . '<td><a href="{{ Storage::url(getFirstElement($row->'.$nameAfterCheckLang.')) }}"><i class="fa fa-file"></i></a></td>'."\n";
        }else{
            return "\t\t\t\t\t\t\t" . '<td><a href="{{ Storage::url($row->'.$nameAfterCheckLang.') }}"><i class="fa fa-file"></i></a></td>'."\n";
        }
//        return "\t\t\t\t\t\t\t" . '<td><a href="{{ url(\'storage/app/\'.$row->'.$nameAfterCheckLang.') }}"><i class="fa fa-file"></i></a></td>'."\n";

    }


    /*
     * generate  body line of file
     */

    protected function bodyLine($column , $name = null)
    {
        $nameAfterCheckLang = $name ? $name : $column->name;
        return "\t\t\t\t\t\t\t" . '<td>{{ $row->'.$nameAfterCheckLang.'  }}</td>'."\n";

    }

    /*
     * query and get columns users select to show
     * in admin crud
     */

    protected function getColumnsAdminIndex($module_id , $type){
       return Column::where('module_id', $module_id)->whereHas('details' , function ($query) use ($type){
           return $query->where($type, 'yes');
       })->get();

    }

    /*
     * return with  available language
     */

    protected function languageAdminIndex(){
        return LaravelLocalization::getSupportedLanguagesKeys();
    }

    /*
     * upload files
     */

    protected function arrayOfFilesFieldsForAdminIndex(){
        return [
            'file', 'file[]'
        ];
    }


    /*
     * upload files
     */

    protected function arrayOfImageFieldsForAdminIndex(){
        return [
            'image' , 'image[]'
        ];
    }


}