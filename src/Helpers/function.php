<?php

if (! function_exists('larazoul_is_rtl')) {
    /**
     * Whether the current/larazoul locale is right-to-left.
     */
    function larazoul_is_rtl(string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();

        return in_array($locale, ['ar', 'fa', 'he', 'ur'], true);
    }
}