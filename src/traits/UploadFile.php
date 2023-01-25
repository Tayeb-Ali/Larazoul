<?php

namespace Larazoul\Larazoul\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFile
{

    /*
     * if request have file upload
     * this function will upload file
     */

    protected function saveFileToPath($name , $row){
        if(isset($this->request[$name])){
            if(is_array($this->request[$name])){
                $image = [];
                foreach ($this->request[$name] as $key => $file){
                   $image[] = $this->request[$name][$key]->store(fixPath('public/'.$name));
                }
                if($row){
                    $image = array_merge($image , $row->{$name});
                }

            }else{
                $image = $this->request[$name]->store(fixPath('public/'.$name));
            }
            $this->request[$name] = $image;
        }
    }

    /*
     * save files in upload path
     */

    protected function uploadFile($uploads = [] , $row = null){

        if(!empty($uploads)){
            foreach ($uploads as $upload){
                $this->saveFileToPath($upload , $row);
            }
        }
    }

   /*
    * delete file if there are multi files uploads
    */

    public function deleteFile($id , $field){
        $row = $this->model->findOrFail($id);
        $this->deleteFileFromRow($field , request()->get('file') , $row);
        return redirect()->back();
    }

    /*
    * delete file
    */

    protected function deleteFileFromRow($name , $file , $row){
        $name = str_replace('[]' , '' , $name);
        $attr = $row->getAttributes();
        if(array_has($attr , $name)){
            if(is_array($row->{$name})){
                if(in_array($file , $row->{$name})){
                    $array = $row->{$name};
                    if (($key = array_search($file, $array)) !== false) {
                        Storage::delete($file);
                        unset($array[$key]);
                        $row->{$name} = array_values($array);
                        $row->save();
                    }
                }
            }
        }
    }

}