<?php

function DS(){
    return DIRECTORY_SEPARATOR;
}

function fixPath($path){
    return str_replace(['/' , '\\'] , DS() ,  $path);
}


function module_path($moduleName){
    return fixPath(app_path('Modules/'.$moduleName));
}