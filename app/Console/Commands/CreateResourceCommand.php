<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CreateResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:resource {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a migration, model, factory, policy, request, resource, and controller for a given name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name')); // Convert to StudlyCase
        $singularName = Str::singular($name); // Convert to singular StudlyCase
        $pluralName = Str::pluralStudly($singularName); // Convert to plural StudlyCase
        $snakeName = Str::snake($singularName); // Convert to snake_case
        $pluralSnakeName = Str::plural($snakeName); // Convert to plural snake_case

        // Create Migration
        Artisan::call('make:migration', ['name' => 'create_' . $pluralSnakeName . '_table']);

        // Create Model
        Artisan::call('make:model', ['name' => $singularName]);

        // Create Factory
        Artisan::call('make:factory', ['name' => $singularName . 'Factory']);

        // Create Policy
        Artisan::call('make:policy', ['name' => $singularName . 'Policy']);

        // Create Request
        Artisan::call('make:request', ['name' => $singularName . 'Request']);

        // Create Resource
        Artisan::call('make:resource', ['name' => $singularName . 'Resource']);

        // Create Controller
        Artisan::call('make:controller', ['name' => $singularName . 'Controller', '--resource' => true]);

        $this->info('Migration, model, factory, policy, request, resource, and controller created successfully.');
    }
}
