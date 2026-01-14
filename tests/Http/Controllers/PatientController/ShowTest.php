<?php

namespace Http\Controllers\PatientController;

use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function rota(string $uuid): string
    {
        return route('patient.show', ['patient' => $uuid]);
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $uuid = $this->faker->uuid;

        $this->assertEquals(
            $this->rota($uuid),
            URL::action([PatientController::class, 'show'], ['patient' => $uuid])
        );
    }

    #[
        Test,
        TestDox('Deve exibir um paciente com sucesso'),
    ]
    public function sucesso(): void
    {
        $patient = $this->criarPaciente();

        $this->login();

        $this
            ->getJson($this->rota($patient->uuid))
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $patient->uuid,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'document' => $patient->document,
                'active' => $patient->active,
                'notes' => $patient->notes,
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

    #[Test, TestDox('Deve retornar 404 para paciente inexistente')]
    public function pacienteInexistente(): void
    {
        $this->login();

        $this
            ->getJson($this->rota($this->faker->uuid))
            ->assertNotFound();
    }

    private function criarPaciente(): Patient
    {
        return Patient::factory()->create();
    }
}
