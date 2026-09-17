<?php

namespace Http\Controllers\ConsultationController;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\ConsultationController;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(now()->startOfSecond());
    }

    public function rota(string $uuid): string
    {
        return route('consultation.show', ['consultation' => $uuid]);
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $uuid = $this->faker->uuid;

        $this->assertEquals(
            $this->rota($uuid),
            URL::action([ConsultationController::class, 'show'], ['consultation' => $uuid])
        );
    }

    #[
        Test,
        TestDox('Deve retornar uma consulta com sucesso'),
    ]
    public function sucesso(): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        $this->login();

        $response = $this
            ->getJson($this->rota($consultation->uuid))
            ->assertJsonFragment([
                'data' => [
                    'uuid' => $consultation->uuid,
                    'date' => $consultation->date->toW3cString(),
                    'presence' => $consultation->presence->value,
                    'value' => $consultation->value,
                    'notes' => $consultation->notes,
                    'patient' => [
                        'uuid' => $consultation->patient->uuid,
                        'name' => $consultation->patient->name,
                        'type' => $consultation->patient->type->value,
                        'time' => $consultation->patient->time->value,
                        'email' => null,
                        'gender' => null,
                        'phone' => null,
                        'active' => null,
                        'notes' => null,
                        'document' => null,
                    ],
                ]
            ])
            ->assertOk();
    }

    #[
        Test,
        TestDox('Não deve exibir uma consulta com um uuid inexistente'),
    ]
    public function erro(): void
    {
        $this->login();
        $this->deleteJson($this->rota($this->faker->uuid))
            ->assertNotFound();
    }

    #[
        Test,
        TestDox('Não deve exibir uma consulta sem estar logado'),
    ]
    public function erroNaoLogado(): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        $this->deleteJson($this->rota($consultation->uuid))
            ->assertUnauthorized();
    }
}
