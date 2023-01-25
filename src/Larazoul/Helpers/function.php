<?php

/*
 * remove back slash
 * support arabic
 * encode data
 */

function json($value){
    return json_encode($value , JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

/*
 * check if json is valid
 * then return with decode value
 * or return empty array
 *
 * @ return array
 */

function jsonDecode($json){
    return  json_decode($json) ?? [];
}

/*
 * get first element in array
 * this important to show
 * the first image in array
 */

function getFirstElement($array){

    /*
     * convert object to array first
     */

    if(is_object($array)){
        $array = (array) $array;
    }


    /*
     * check if this array is
     * php array
     */

    if(is_array($array)){
       return getElement($array);
    }

    /*
     * if json array
     * check if json is valid
     * and decode the array
     */

    $array = jsonDecode($array);

    /*
     * return with the first element
     */

    return getElement($array);

}

/*
 * get the first element in array
 * or get by index
 */

function getElement($array , $index = 0){

    if($index != 0){
        return isset($array[$index]) ? $array[$index] : '';
    }

    $array = array_values($array);

    return  array_shift($array);
}

/*
 * zip folder
 * [recursively]
 */


function zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

/*
 * extract zip file
 */

function unzip($source , $destination){
    $zip = new ZipArchive;
    $res = $zip->open($source);
    if ($res === TRUE) {
        $zip->extractTo($destination);
        $zip->close();
        return true;
    } else {
        return false;
    }
}
