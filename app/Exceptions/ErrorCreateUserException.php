<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ErrorCreateUserException extends HttpException
{

    public function __construct(int $statusCode = 422, string $message = null, $details = null, \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct($statusCode, $message ?? 'Error creating user', $previous, $headers, $code);
    }
}
