<?php

namespace Http\Controllers\ConsultationController;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\ConsultationController;
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
        return route('consultation.index');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([ConsultationController::class, 'index'])
        );
    }

    #[
        Test,
        TestDox('Deve listar todos as consultas com sucesso'),
        DataProvider('filtroDataProvider')
    ]
    public function sucesso(array $params): void
    {
        $consultas = $this->criarConsultas();

        /** @var Consultation $consultation */
        $consultation = $consultas->first();
        $consultation->date = now()->addMonth()->startOfDay();
        $consultation->save();

        $data = collect(self::mapRecursive($params, compact('consultation')));

        if ($data->has(0)) {
            $data = null;
        }

        $this->login();

        $this
            ->getJson($this->rota() . '?' . http_build_query($data ? $data->toArray() : []))
            ->assertOk()
            ->assertJsonCount($data ? 1 : $consultas->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'uuid',
                        'date',
                        'patient' => [
                            'uuid',
                            'name',
                            'document',
                        ],
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    #[
        Test,
        TestDox('Deve retornar erro para parâmetros inválidos'),
        DataProvider('parametrosInvalidosDataProvider')
    ]
    public function erroParametrosInvalidos(array $parametro): void
    {
        $this->login();

        $this
            ->getJson($this->rota() . '?' . http_build_query($parametro))
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                key($parametro)
            ]);
    }

    #[
        Test,
        TestDox('Deve retornar erro para usuário não logado'),
    ]
    public function naoLogado(): void
    {
        $this
            ->getJson($this->rota())
            ->assertUnauthorized();
    }

    private function criarConsultas(): Collection
    {
        collect(range(1, 5))->each(function() {
            Consultation::factory()
                ->withPatient()
                ->create();
        });

        return Consultation::all();
    }

    public static function filtroDataProvider(): array
    {
        return [
            'sem filtro' => [
                [null]
            ],
            'filtro por uuid' => [
                [
                    'uuid' => fn(Consultation $consultation) => $consultation->uuid
                ]
            ],
            'filtro por uuid do paciente' => [
                [
                    'patient_uuid' => fn(Consultation $consultation) => $consultation->patient->uuid]
                ],
            'filtro por date' => [
                [
                    'date' => fn(Consultation $consultation) => $consultation->date->format('Y-m-d')
                ]
            ],
        ];
    }

    public static function parametrosInvalidosDataProvider(): array
    {
        return [
            'uuid inválido' => [
                [
                    'uuid' => 'ab'
                ]
            ],
            'uuid do paciente inválidoe' => [
                [
                    'patient_uuid' => 'ab'
                ]
            ],
            'data inválido' => [
                [
                    'date' => '2026-22-14'
                ]
            ],
        ];
    }
}
