<?php

namespace App\Console\Commands;

use App\Helpers\DatabaseHelper;
use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Artisan;

class MigrateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Roda as migrations dos tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DatabaseHelper::changeSchema($this->argument('tenant'));

        $migrator = app(Migrator::class);
        $path = database_path('migrations/tenant');
        $migrator->run($path);
        
    }
}
