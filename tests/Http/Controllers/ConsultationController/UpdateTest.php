<?php

namespace Http\Controllers\ConsultationController;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use App\Exceptions\PatientNotActiveException;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientController;
use Carbon\Carbon;
use Database\Factories\PatientFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(now()->startOfSecond());
    }

    public function rota(): string
    {
        return route('consultation.update');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([ConsultationController::class, 'update'])
        );
    }

    #[
        Test,
        TestDox('Deve atualizar uma consulta com sucesso'),
    ]
    public function sucesso(): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        $this->login();

        $patient = Patient::factory()
            ->create([
                'active' => true,
            ]);

        $date = now()->addDay();

        $parametros = [
            'uuid' => $consultation->uuid,
            'date' => $date->format('Y-m-d'),
            'time' => ConsultationTimeEnum::THIRTY_MINUTES,
            'type' => ConsultationTypeEnum::DAILY,
            'patient' => [
                'uuid' => $patient->uuid,
            ],
        ];

        $response = $this
            ->putJson($this->rota(), $parametros)
            ->assertOk();

        $response->assertJsonPath('data.date', $date->clone()->startOfDay()->toW3cString());
        $response->assertJsonPath('data.patient.uuid', $patient->uuid);
        $response->assertJsonPath('data.patient.first_name', $patient->first_name);
        $response->assertJsonPath('data.patient.last_name', $patient->last_name);
        $response->assertJsonPath('data.type', $parametros['type']->value);
        $response->assertJsonPath('data.time', $parametros['time']->value);
    }

    #[
        Test,
        TestDox('Não deve atualizar consulta para um paciente inativo'),
    ]
    public function erroPacienteInativo(): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        /** @var Patient $patient */
        $patient = Patient::factory()->create(['active' => false]);

        $parametros = [
            'consultation' => $consultation->uuid,
            'patient' => [
                'uuid' => $patient->uuid,
            ],
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => ConsultationTimeEnum::FIFTY_MINUTES,
            'type' => ConsultationTypeEnum::WEEKLY,
        ];

        $this->login();
        $this->putJson($this->rota(), $parametros)
            ->assertNotAcceptable();
    }

    #[
        Test,
        TestDox('Não deve atualizar uma consulta sem estar logado'),
    ]
    public function erroNaoLogado(): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        $parametros = [
            'consultation' => $consultation->uuid,
            'patient' => [
                'uuid' => $consultation->patient->uuid,
            ],
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => ConsultationTimeEnum::FIFTY_MINUTES,
            'type' => ConsultationTypeEnum::WEEKLY,
        ];

        $this->putJson($this->rota(), $parametros)
            ->assertUnauthorized();
    }

    #[
        Test,
        TestDox('Deve retornar erro para parâmetros inválidos'),
        DataProvider('parametrosInvalidosDataProvider')
    ]
    public function parametrosInvalidos(array $parametro): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        $parametro['consultation'] = $consultation->uuid;

        $this->login();

        $this
            ->putJson($this->rota(), $parametro)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                array_key_first($parametro),
            ]);
    }

    public static function parametrosInvalidosDataProvider(): array
    {
        return [
            'patient inválido' => [
                ['patient' => null]
            ],
            'date inválido' => [
                ['date' => 'invalid-date']
            ],
            'date no passado' => [
                ['date' => now()->subDays(5)->format('Y-m-d')]
            ],
            'time inválido' => [
                ['time' => 'invalid-time']
            ],
            'type inválido' => [
                ['type' => 'invalid-type']
            ],
        ];
    }
}
