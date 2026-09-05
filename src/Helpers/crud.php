<?php

if (! function_exists('larazoul_crud_fields')) {
    /**
     * Normalize the CRUD fields given by the module builder.
     *
     * @param array $fields
     * @return array
     */
    function larazoul_crud_fields(array $fields): array
    {
        return collect($fields)->filter(fn ($f) => ! empty($f['name']))->values()->all();
    }
}