<?php

namespace Larazoul\Larazoul\Larazoul\Traits;

use Illuminate\Support\Facades\File;

trait FileTrait
{

    /*
     * check if file exists
     */

    protected function fileExists($fileName){
        return File::exists($fileName) ? true : false;
    }

    /*
     * if module folder not exists
     * create new folder
     */

    protected function createInModuleFolder($moduleName){
       $path =  fixPath(base_path('app/Modules/'.$moduleName));
       $this->createFolder($path);
    }

    /*
    * if module folder not exists
    * create new folder
    */

    protected function createFolder($path){
        if(!$this->fileExists($path)){
            File::makeDirectory($path , 0775 , true , true);
        }
    }

    /*
    * Delete folder Recursively
    */

    protected function deleteFolder($directory){
        return File::deleteDirectory($directory, true);
    }


}