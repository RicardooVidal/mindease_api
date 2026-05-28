<?php

namespace Tests\Http\Controllers\PatientController;

use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function rota(): string
    {
        return route('patient.index');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([PatientController::class, 'index'])
        );
    }

    #[
        Test,
        TestDox('Deve listar todos os pacientes com sucesso'),
        DataProvider('filtroDataProvider')
    ]
    public function sucesso(array $params): void
    {
        $patients = $this->criarPacientes();

        /** @var Patient $patient */
        $patient = $patients->first();

        $data = collect(self::mapRecursive($params, compact('patient')));

        if (!empty($params['active'])) {
            $patient->update(['active' => false]);
        }

        if ($data->has(0)) {
            $data = null;
        }

        $this->login();

        $this
            ->getJson($this->rota() . '?' . http_build_query($data ? $data->toArray() : []))
            ->assertOk()
            ->assertJsonCount($data ? 1 : $patients->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'uuid',
                        'name',
                        'document',
                        'active',
                        'notes',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    #[Test, TestDox('Deve retornar erro para parâmetros inválidos')]
    public function erroParametrosInvalidos(): void
    {
        $parametros = [
            'uuid' => 'uuid-invalido',
            'name' => 'ab',
            'document' => str_repeat('1', 50),
        ];

        $this->login();

        $this
            ->getJson($this->rota() . '?' . http_build_query($parametros))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'uuid',
                'name',
                'document',
            ]);
    }

    #[
        Test,
        TestDox('Deve retornar erro ao tentar deletar um paciente sem estar logado'),
    ]
    public function erroNaoLogado(): void
    {
        $this->getJson($this->rota())
            ->assertUnauthorized();
    }

    public static function filtroDataProvider(): array
    {
        return [
            'sem filtro' => [
                [null]
            ],
            'filtro por uuid' => [
                ['uuid' => fn(Patient $patient) => $patient->uuid]
            ],
            'filtro por nome' => [
                ['name' => fn(Patient $patient) => $patient->name]
            ],
            'filtro por documento' => [
                ['document' => fn(Patient $patient) => $patient->document]
            ],
            'filtro por ativo' => [
                ['active' => fn(Patient $patient) => false]
            ],
        ];
    }

    protected function criarPacientes(): Collection
    {
        collect(range(1, 5))->each(function() {
            Patient::factory()->create();
        });

        return Patient::all();
    }
}
