<?php

namespace Larazoul\Larazoul\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    protected $signature = 'larazoul:install
        {--force : Overwrite existing published files}
        {--fresh : Drop and re-run all Larazoul migrations}
        {--user : Create an admin user during install}';

    protected $description = 'Install LaraZoul (publish assets, run migrations, optional admin user).';

    public function handle(): int
    {
        $this->info('Publishing LaraZoul configuration...');
        $this->call('vendor:publish', [
            '--tag' => 'larazoul-config',
            '--force' => $this->option('force'),
        ]);

        $this->info('Publishing LaraZoul language files...');
        $this->call('vendor:publish', [
            '--tag' => 'larazoul-lang',
            '--force' => $this->option('force'),
        ]);

        $this->info('Publishing LaraZoul views...');
        $this->call('vendor:publish', [
            '--tag' => 'larazoul-views',
            '--force' => $this->option('force'),
        ]);

        $this->line('');

        $fresh = $this->option('fresh');
        $this->info($fresh ? 'Refreshing LaraZoul migrations...' : 'Running LaraZoul migrations...');

        if ($fresh) {
            $this->call('migrate:fresh', ['--path' => $this->migrationPath()]);
        } else {
            $this->call('migrate', ['--path' => $this->migrationPath()]);
        }

        if ($this->option('user')) {
            $this->createAdminUser();
        }

        $this->newLine();
        $this->info('LaraZoul installed successfully.');

        return self::SUCCESS;
    }

    protected function migrationPath(): string
    {
        return 'vendor/tayeb-ali/larazoul/database/migrations';
    }

    protected function createAdminUser(): void
    {
        $model = config('larazoul.user_model', 'App\\Models\\User');

        if (! class_exists($model)) {
            $this->warn("User model [{$model}] does not exist. Skipping admin user creation.");
            $this->warn('Run a user migration (e.g. Laravel Breeze / auth) first.');

            return;
        }

        $email = $this->ask('Admin email', 'admin@larazoul.com');
        $password = $this->secret('Admin password (leave blank to use "larazoul")') ?: 'larazoul';
        $name = $this->ask('Admin name', 'Admin');

        $column = config('larazoul.admin_role_column', 'is_admin');

        $user = $model::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            $column => config('larazoul.admin_role_value', true),
        ]);

        $this->info("Admin user created: {$user->email}");
    }
}
