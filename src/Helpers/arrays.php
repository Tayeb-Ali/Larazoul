<?php

if (! function_exists('larazoul_fix_path')) {
    /**
     * Normalize directory separators in a path.
     */
    function larazoul_fix_path(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}