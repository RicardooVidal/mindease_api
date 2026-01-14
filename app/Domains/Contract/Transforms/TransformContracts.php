<?php

namespace App\Domains\Contract\Transforms;

use Illuminate\Support\Collection;

class TransformContracts
{
    public static function execute(Collection $contracts)
    {
        return $contracts->map(fn($contract) => [
            'id' => $contract->id,
            'patient_id' => $contract->patient_id,
            'patient_name' => $contract->patient->first_name . ' ' . $contract->patient->last_name,
            'description' => $contract->description,
            'valid_until' => $contract->valid_until,
            'document' => $contract->document,
            'created_at' => $contract->created_at,
            'updated_at' => $contract->updated_at,
        ]);
    }
}