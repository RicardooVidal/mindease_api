<?php

namespace Tests;

use ReflectionException;
use ReflectionFunction;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Closure;

abstract class TestCase extends BaseTestCase
{
    use WithFaker, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        $this->artisan('migrate:fresh', ['--force' => true]);
        $this->artisan('migrate', ['--path' => 'database/migrations/tenant', '--force' => true]);
        $this->artisan('db:seed', ['--force' => true]);
    }

    protected function createUser(): User
    {
        /** @var User */
        return User::factory()
            ->withCompany()
            ->create();
    }

    protected function login(): User
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $company = Company::first();
        session(['selected_company' => [
            'id' => $company->id,
            'name' => $company->name,
        ]]);

        return $user;
    }

    public function mapRecursive(array $data, array $contexts): array
    {
        return collect($data)->map(function ($valor) use ($contexts) {
           return match(true) {
                is_callable($valor) => self::chamarCallbackComParametros($valor, $contexts),
                is_array($valor) => self::mapRecursive($valor, $contexts),
                default => $valor,
           };
        })->toArray();
    }

    /**
     * @throws ReflectionException
     */
    private static function chamarCallbackComParametros(callable $callback, array $params): string
    {
        $refFunc = new ReflectionFunction(Closure::fromCallable($callback));

        $argumentosFiltrados = [];
        foreach ($refFunc->getParameters() as $parametro) {
            $nome = $parametro->getName();
            if (array_key_exists($nome, $params)) {
                $argumentosFiltrados[] = $params[$nome];
            } elseif ($parametro->isDefaultValueAvailable()) {
                $argumentosFiltrados[] = $parametro->getDefaultValue();
            }
        }

        return $callback(...$argumentosFiltrados);
    }

    protected function tearDown(): void
    {
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::statement('PRAGMA foreign_keys = ON');
        }

        parent::tearDown();
    }
}
