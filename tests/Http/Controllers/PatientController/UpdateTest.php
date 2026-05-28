<?php

namespace Tests\Http\Controllers\PatientController;

use App\Domains\Patient\Entities\Patient;
use App\Enums\GenderEnum;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function rota(): string
    {
        return route('patient.update');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([PatientController::class, 'update'])
        );
    }

    #[
        Test,
        TestDox('Deve atualizar um paciente com sucesso'),
    ]
    public function sucesso(): void
    {
        $patient = $this->criarPaciente();

        $parametros = [
            'uuid' => $patient->uuid,
            'name' => $this->faker->name,
            'document' => $this->faker->cpf(false),
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(GenderEnum::cases()),
            'email' => $this->faker->email,
            'active' => false,
            'notes' => $this->faker->text,
        ];

        $this->login();

        $this
            ->putJson($this->rota(), $parametros)
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $patient->uuid,
                'name' => $parametros['name'],
                'document' => $parametros['document'],
                'phone' => $parametros['phone'],
                'gender' => $parametros['gender'],
                'email' => $parametros['email'],
                'active' => $parametros['active'],
                'notes' => $parametros['notes'],
            ])
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'document',
                    'phone',
                    'gender',
                    'email',
                    'active',
                    'notes',
                ]
            ]);
    }

    #[Test, TestDox('Deve retornar 404 para paciente inexistente')]
    public function pacienteInexistente(): void
    {
        $this->login();

        $parametros = [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'document' => $this->faker->cpf(false),
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(GenderEnum::cases()),
            'email' => $this->faker->email,
            'active' => false,
            'notes' => $this->faker->text,
        ];

        $this
            ->putJson($this->rota(), $parametros)
            ->assertNotFound();
    }

    #[
        Test,
        TestDox('Deve retornar erro para parâmetros inválidos'),
        DataProvider('parametrosInvalidosDataProvider')
    ]
    public function erroParametrosInvalidos(array $parametro): void
    {
        $paciente = $this->criarPaciente();
        $parametro['uuid'] = $paciente->uuid;

        $this->login();

        $this
            ->putJson($this->rota(), $parametro)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                array_key_first($parametro),
            ]);
    }

    #[Test, TestDox('Deve retornar erro ao tentar atualizar um paciente sem estar logado')]
    public function erroNaoLogado(): void
    {
        $patient = $this->criarPaciente();

        $parametros = [
            'uuid' => $patient->uuid,
            'name' => $this->faker->name,
            'document' => $this->faker->cpf(false),
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(GenderEnum::cases()),
            'email' => $this->faker->email,
            'active' => false,
            'notes' => $this->faker->text,
        ];

        $this
            ->putJson($this->rota(), $parametros)
            ->assertUnauthorized();
    }

    public static function parametrosInvalidosDataProvider(): array
    {
        return [
            'name inválido' => [
                ['name' => true]
            ],
            'name min' => [
                ['name' => 'a']
            ],
            'name max' => [
                ['name' => str_repeat('a', 256)]
            ],
            'document inválido' => [
                ['document' => true]
            ],
            'document min' => [
                ['document' => '123456789']
            ],
            'phone inválido' => [
                ['phone' => true]
            ],
            'phone min' => [
                ['phone' => '1234']
            ],
            'phone max' => [
                ['phone' => '1234567890123456']
            ],
            'email inválido' => [
                ['email' => true]
            ],
            'email max' => [
                ['email' => str_repeat('a', 101)]
            ],
            'gender inválido' => [
                ['gender' => 'invalido']
            ],
            'active inválido' => [
                ['active' => 'invalido']
            ],
            'notes inválido' => [
                ['notes' => true]
            ],
        ];
    }

    private function criarPaciente(): Patient
    {
        return Patient::factory()->create();
    }
}
