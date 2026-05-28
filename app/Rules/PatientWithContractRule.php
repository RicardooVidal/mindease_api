<?php

namespace App\Rules;

use App\Domains\Contract\Entities\Contract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class PatientWithContractRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contractExists = Contract::query()
            ->whereHas('patient', fn(Builder $query) => $query->where('uuid', $value))
            ->exists();

        if ($contractExists) {
            $fail('Paciente já tem contrato ativo!');
        }
    }
}
