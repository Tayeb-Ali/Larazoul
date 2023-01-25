<?php

namespace Larazoul\Larazoul\Larazoul\Traits;


use Larazoul\Larazoul\Larazoul\Seeders\ItemsSeeder;

trait LaraflatSeederTrait
{


    /*
    * load laraFlat modules
    * loop in modules folder
    * get all seeders
    * add them
    */

    protected function getLaraFlatSeeder(){

        $this->call(ItemsSeeder::class);

        $path = fixPath(base_path('app/Modules'));

        if(is_dir($path)){

            $directories = \Illuminate\Support\Facades\File::directories($path);

            if(!empty($directories)){

                foreach ($directories as $directory){

                    $moduleName = explode(DIRECTORY_SEPARATOR , $directory);

                    $moduleName = end($moduleName);

                    $fullProviderPath = fixPath($directory.'/Database/seeds/'.$moduleName.'Seeder.php');

                    if(file_exists($fullProviderPath)){

                        $nameSpace = 'App\\Modules\\'.$moduleName.'\\Database\\Seeds\\'.$moduleName.'Seeder';

                        $this->call($nameSpace);

                    }
                }
            }
        }

    }

}