<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [

        //
    ];

    protected function inExceptArray($request): boolss
    {
        if ($request->ip() === '91.108.6.1' || $request->ip() === '91.108.6.110') {
            return true;
        }

        return parent::inExceptArray($request);
    }


}
