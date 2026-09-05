<?php

if (! function_exists('larazoul_path')) {
    /**
     * Return a Larazoul package path.
     */
    function larazoul_path(string $path = ''): string
    {
        $base = dirname(__DIR__, 2);

        return $path ? $base . DIRECTORY_SEPARATOR . $path : $base;
    }
}