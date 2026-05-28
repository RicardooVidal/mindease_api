<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PatientNotActiveException extends Exception
{
    public function render(Request $request): Response
    {
        return response('Paciente não está ativo!', Response::HTTP_NOT_ACCEPTABLE);
    }
}
