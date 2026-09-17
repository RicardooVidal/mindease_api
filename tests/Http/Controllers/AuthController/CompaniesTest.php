<?php

namespace Http\Controllers\AuthController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CompaniesTest extends TestCase
{
    use WithFaker;

    public function rota(string $uuid): string
    {
        return route('companies', ['user' => $uuid]);
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $uuid = $this->faker->uuid;

        $this->assertEquals(
            $this->rota($uuid),
            URL::action([AuthController::class, 'companies'], ['user' => $uuid])
        );
    }

    #[Test, TestDox('Deve retornar somente empresas no qual o usuário pertence')]
    public function sucesso(): void
    {
        $user = User::factory()->withCompany()->create();

        $this
            ->getJson($this->rota($user->uuid))->ddJson()
            ->assertOk()
            ->assertJsonFragments([
                'data' => [
                    [
                        'uuid' => $user->company->uuid,
                        'company' => $user->company->company,
                    ]
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    [
                        'uuid',
                        'company',
                    ]
                ]
            ]);
    }
}
