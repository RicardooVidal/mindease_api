<?php

namespace Tests\Http\Controllers\PatientController;

use App\Domains\Patient\Entities\Patient;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;

class SelectTest extends IndexTest
{
    public function rota(): string
    {
        return route('patient.select');
    }

    #[Test, TestDox('Deve retornar a rota correta')]
    public function rotaCorreta(): void
    {
        $this->assertEquals(
            $this->rota(),
            URL::action([PatientController::class, 'select'])
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

//        dd($patient->count());

        $this
            ->getJson($this->rota() . '?' . http_build_query($data ? $data->toArray() : []))
            ->assertOk()
            ->assertJsonCount($data ? 1 : $patients->count(), 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'uuid',
                        'name',
                    ]
                ]
            ]);
    }
}
