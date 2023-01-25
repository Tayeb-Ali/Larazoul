<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 6/5/18
 * Time: 2:37 AM
 */

namespace Larazoul\Larazoul\Larazoul\Controllers\Admin;

use Larazoul\Larazoul\Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Larazoul\Larazoul\Larazoul\Models\Module;

class ModuleController
{

    /*
     * export module
     */

    public function exportModule(Module $module){

        /*
       * get all modules from
       */

        $modules = $module->pluck('name' , 'id');

        return view('larazoul::admin.export.index', compact('modules'));
    }

    /*
     * zip the module folder
     * then download this file
     */

    public function postExportModule(Request $request , Module $module){


        /*
         * find module or fail
         */

        $module = $module->findOrFail($request->module);

        /*
         * zip folder
         */

        $nameAfterZip = $module->name.'.zip';

        $files = glob(module_path($module->name));

        $zipper = new \Larazoul\Larazoul\Chumper\Zipper\Zipper;

        $zipper->make(fixPath(public_path($nameAfterZip)))->add($files)->close();

        /*
         * return download files
         */

        return response()->download(fixPath(public_path($nameAfterZip)));
    }


    /*
     * import module
     */

    public function importModule(){

        return view('larazoul::admin.import.index');

    }

    /*
     * extract module on
     * module path and run migrate and seeder
     */

    public function postImportModule(Request $request){


        if($request->hasFile('module')){

            $file = $request->file('module');

            /*
             * get module name from zip filename
             */

            $name = $this->clearModuleName($file);

            $file = $request->file('module')->store('modules');

            dd(storage_path($file));

            $zipper = new \Larazoul\Larazoul\Chumper\Zipper\Zipper;

            $zipper->zip(storage_path($file))->extractTo(module_path($name));

            Storage::delete($file);

        }

        return redirect()->back();


    }

    protected function clearModuleName($file){
       return str_replace('zip' , '' , preg_replace("/[^A-Za-z?!]/",'',$file->getClientOriginalName()));
    }

}
