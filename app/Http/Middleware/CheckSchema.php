<?php

namespace App\Http\Middleware;

use App\Helpers\DatabaseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSchema
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user()->company()->first();

        DatabaseHelper::changeSchema($user->company);

        return $next($request);
    }
}
