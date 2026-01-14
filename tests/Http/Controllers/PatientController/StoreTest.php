<?php

namespace Http\Controllers\PatientController;

use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function rota(): string
    {
        return route('patient.store');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([PatientController::class, 'store'])
        );
    }

    #[
        Test,
        TestDox('Deve cadastrar um paciente com sucesso'),
    ]
    public function sucesso(): void
    {
        $parametros = [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'document' => $this->faker->cpf(false),
            'active' => true,
            'notes' => $this->faker->text,
        ];

        $this->login();

        $this
            ->postJson($this->rota(), $parametros)
            ->assertCreated()
            ->assertJsonFragment([
                'first_name' => $parametros['first_name'],
                'last_name' => $parametros['last_name'],
                'document' => $parametros['document'],
                'active' => $parametros['active'],
                'notes' => $parametros['notes'],
            ])
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'first_name',
                    'last_name',
                    'document',
                    'active',
                    'notes',
                ]
            ]);
    }

    #[
        Test,
        TestDox('Deve retornar erro para parâmetros inválidos'),
        DataProvider('parametrosInvalidosDataProvider')
    ]
    public function parametrosInvalidos(array $parametro): void
    {
        $this->login();

        $this
            ->postJson($this->rota(), $parametro)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                array_key_first($parametro),
            ]);
    }

    public static function parametrosInvalidosDataProvider(): array
    {
        return [
            'first_name inválido' => [
                ['first_name' => true]
            ],
            'first_name min' => [
                ['first_name' => 'a']
            ],
            'first_name max' => [
                ['first_name' => str_repeat('a', 256)]
            ],
            'last_name inválido' => [
                ['last_name' => true]
            ],
            'last_name min' => [
                ['last_name' => 'a']
            ],
            'last_name max' => [
                ['last_name' => str_repeat('a', 256)]
            ],
            'document inválido' => [
                ['document' => true]
            ],
            'document min' => [
                ['document' => '123456789']
            ],
            'active inválido' => [
                ['active' => 'invalido']
            ],
            'notes inválido' => [
                ['notes' => true]
            ],
        ];
    }
}
