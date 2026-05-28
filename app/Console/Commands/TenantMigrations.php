<?php

namespace App\Console\Commands;

use App\Helpers\DatabaseHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tenant-migrations {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schemaName = $this->argument('company');

        $this->createSchemaIfNotExists($schemaName);

        $this->createMigrationsTableIfNotExists();

        $this->runTenantMigrations();

        $this->info("Migrations executed successfully for schema: {$schemaName}");
    }

    protected function createSchemaIfNotExists(string $schemaName): void
    {
        $query = "CREATE SCHEMA IF NOT EXISTS {$schemaName}";
        DB::statement($query);

        DatabaseHelper::changeSchema(schema: $schemaName);

        $this->info("Schema {$schemaName} checked successfully.");
    }

    protected function runTenantMigrations(): void
    {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--force' => true,  // Run migrations without asking for confirmation
        ]);

        $this->info(Artisan::output());
    }

    protected function createMigrationsTableIfNotExists(): void
    {
        $tableExists = DB::select("SELECT to_regclass('migrations') AS exists");

        if (empty($tableExists) || !$tableExists[0]->exists) {
            Artisan::call('migrate:install');
            $this->info('Migrations table created successfully.');
        }
    }

}
