<?php

namespace Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Larazoul\Larazoul\Support\ModuleGenerator;

class MakeModuleCommand extends Command
{
    protected $signature = 'larazoul:make-module
        {name : The module (table) name, e.g. Post}
        {--fields=* : List of fields. Syntax: name:type (types: string,text,integer,boolean,date,datetime,decimal,json) or name:type:nullable}
        {--api : Generate API routes/resources too}
        {--front : Generate front-end routes/views too}
        {--seed : Generate a seeder with fake data}
        {--migrate : Run migration after generation}
        {--force : Overwrite existing files}';

    protected $description = 'Generate a full CRUD module (migration, model, controller, requests, views, routes).';

    public function handle(): int
    {
        $name = $this->argument('name');
        $fields = $this->parseFields($this->option('fields'));

        if (empty($fields)) {
            $this->error('At least one field is required. Use --fields="title:string" --fields="body:text".');

            return self::FAILURE;
        }

        try {
            $generator = new ModuleGenerator($name, [
                'fields' => $fields,
                'api' => $this->option('api'),
                'front' => $this->option('front'),
                'seed' => $this->option('seed'),
                'migrate' => $this->option('migrate'),
                'force' => $this->option('force'),
            ]);

            $generator->generate();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if ($this->option('migrate')) {
            $this->call('migrate');
        }

        $this->info("Module [{$name}] generated successfully.");

        return self::SUCCESS;
    }

    /**
     * Parse --fields args: "title:string" or "title:string:nullable".
     */
    protected function parseFields(array $fields): array
    {
        $parsed = [];

        foreach ($fields as $field) {
            $parts = explode(':', $field);
            $name = $parts[0] ?? null;
            $type = $parts[1] ?? 'string';
            $nullable = in_array('nullable', $parts, true);

            if (! $name) {
                continue;
            }

            $parsed[] = [
                'name' => trim($name),
                'type' => trim($type),
                'nullable' => $nullable,
            ];
        }

        return $parsed;
    }
}