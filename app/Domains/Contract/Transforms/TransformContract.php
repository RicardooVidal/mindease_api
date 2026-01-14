<?php

namespace App\Domains\Contract\Transforms;

use App\Domains\Contract\Entities\Contract;

class TransformContract
{
    public static function execute(Contract $contract)
    {
        return [
            'id' => $contract->id,
            'patient_id' => $contract->patient_id,
            'description' => $contract->description,
            'valid_until' => $contract->valid_until,
            'document' => $contract->document,
            'created_at' => $contract->created_at,
            'updated_at' => $contract->updated_at,
        ];
    }
}