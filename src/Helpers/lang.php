<?php

if (! function_exists('larazoul_lang')) {
    /**
     * Translate a Larazoul language key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @return string
     */
    function larazoul_lang(string $key, array $replace = []): string
    {
        return trans('larazoul::' . $key, $replace);
    }
}