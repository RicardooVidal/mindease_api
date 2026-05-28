<?php

namespace Tests\Http\Controllers\ConsultationController;

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

class DestroyTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(now()->startOfSecond());
    }

    public function rota(string $uuid): string
    {
        return route('consultation.destroy', ['consultation' => $uuid]);
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $uuid = $this->faker->uuid;

        $this->assertEquals(
            $this->rota($uuid),
            URL::action([ConsultationController::class, 'destroy'], ['consultation' => $uuid])
        );
    }

    #[
        Test,
        TestDox('Deve remover uma consulta com sucesso'),
    ]
    public function sucesso(): void
    {
        $consultation = Consultation::factory()
            ->withPatient()
            ->create();

        $this->login();

        $response = $this
            ->deleteJson($this->rota($consultation->uuid))
            ->assertOk();

        $this->assertSoftDeleted(
            'consultations',
            [
                'uuid' => $consultation->uuid,
            ]
        );
    }

    #[
        Test,
        TestDox('Não deve excluir uma consulta com um uuid inexistente'),
    ]
    public function erro(): void
    {
        $this->login();
        $this->deleteJson($this->rota($this->faker->uuid))
            ->assertNotFound();
    }

    #[
        Test,
        TestDox('Não deve excluir uma consulta sem estar logado'),
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
