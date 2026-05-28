<?php

namespace Http\Controllers\ContractController;

use App\Domains\Contract\Entities\Contract;
use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PatientController;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

/**
 * DEPRECATED - não faz sentido editar um contrato
 */
class UpdateTest extends TestCase
{
    public function rota(): string
    {
        return route('contract.update');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([ContractController::class, 'update'])
        );
    }

    #[
        Test,
        TestDox('Deve atualizar um contrato com sucesso'),
    ]
    public function sucesso(): void
    {
        Storage::fake('app_files');

        $contract = $this->criarContrato();

        /** @var Patient $patient */
        $patient = Patient::factory()->create();

        $date = now()->addDay();

        $parametros = [
            'uuid' => $contract->uuid,
            'patient' => [
                'uuid' => $patient->uuid,
            ],
            'description' => $this->faker->text,
            'valid_until' => $date->format('Y-m-d H:i:s'),
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ];

        $this->login();

        $this
            ->putJson($this->rota(), $parametros)
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $contract->uuid,
                'patient' => [
                    'uuid' => $patient->uuid,
                    'first_name' => $patient->first_name,
                    'last_name' => $patient->last_name,
                    'document' => null,
                    'active' => null,
                    'notes' => null,
                ],
                'description' => $parametros['description'],
                'valid_until' => Carbon::parse($parametros['valid_until'])->toW3cString(),
                'file' => $parametros['file'],
            ])
            ->assertJsonStructure([
                'data' => [
                    'uuid',
                    'patient',
                    'description',
                    'valid_until',
                    'file',
                ]
            ]);
    }

    #[Test, TestDox('Deve retornar 404 para contrato inexistente')]
    public function contratoInexistente(): void
    {
        $this->login();

        $patient = Patient::factory()->create();

        $parametros = [
            'uuid' => $this->faker->uuid,
            'patient' => [
                'uuid' => $patient->uuid,
            ],
            'description' => $this->faker->text,
            'valid_until' => Carbon::now()->addYear(),
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ];

        $this
            ->putJson($this->rota(), $parametros)
            ->assertNotFound();
    }

    #[Test, TestDox('Deve retornar 404 para paciente inexistente')]
    public function pacienteInexistente(): void
    {
        $this->login();

        $contrato = $this->criarContrato();

        $parametros = [
            'uuid' => $contrato->uuid,
            'patient' => [
                'uuid' => $this->faker->uuid,
            ],
            'description' => $this->faker->text,
            'valid_until' => Carbon::now()->addYear(),
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ];

        $this
            ->putJson($this->rota(), $parametros)
            ->assertUnprocessable();
    }

    #[Test, TestDox('Não deve permitir inserir um contrato para outro paciente que já tem contrato')]
    public function pacienteComContrato(): void
    {
        /** @var Contract $contract */
        $contract = Contract::factory()->withPatient()->create();

        $date = now()->addDay();

        $parametros = [
            'patient' => [
                'uuid' => $contract->patient->uuid,
            ],
            'description' => $this->faker->text,
            'valid_until' => $date->format('Y-m-d H:i:s'),
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ];

        $this->login();

        $this
            ->postJson($this->rota(), $parametros)
            ->assertUnprocessable()
            ->assertInvalid(['patient' => 'Paciente já tem contrato ativo!']);
    }

    #[
        Test,
        TestDox('Deve retornar erro para parâmetros inválidos'),
        DataProvider('parametrosInvalidosDataProvider')
    ]
    public function erroParametrosInvalidos(array $parametro): void
    {
        $contrato = $this->criarContrato();
        $parametro['uuid'] = $contrato->uuid;

        $this->login();

        $key = array_key_first($parametro);

        if ($key === 'patient') {
            $key = 'patient.uuid';
        }

        $this
            ->putJson($this->rota(), $parametro)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([$key]);
    }

    #[Test, TestDox('Deve retornar erro ao tentar atualizar um paciente sem estar logado')]
    public function erroNaoLogado(): void
    {
        $contrato = $this->criarContrato();

        $parametros = [
            'uuid' => $contrato->uuid,
            'patient' => [
                'uuid' => $this->faker->uuid,
            ],
            'description' => $this->faker->text,
            'valid_until' => Carbon::now()->addYear(),
            'file' => UploadedFile::fake()->create('contract.pdf', 100, 'application/pdf'),
        ];
        $this
            ->putJson($this->rota(), $parametros)
            ->assertUnauthorized();
    }

    public static function parametrosInvalidosDataProvider(): array
    {
        return [
            'patient inválido' => [
                ['patient' => ['uuid' => '00886b9a-e933-4815-add3-9a76fe7f46be']],
            ],
            'description min' => [
                ['description' => 'a']
            ],
            'description max' => [
                ['description' => str_repeat('a', 256)]
            ],
            'valid_until inválido' => [
                ['valid_until' => true]
            ],
            'file inválido' => [
                ['file' => true]
            ],
        ];
    }

    private function criarContrato(): contract
    {
        return Contract::factory()
            ->withPatient()
            ->create();
    }
}
