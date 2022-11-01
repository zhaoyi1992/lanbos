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
        'http://192.168.60.42/*',
        'https://www.hsbfa.xyz/*',
        'http://www.hsbfa.xyz/*',
        'https://knwl.juliangtec.com/*'
    ];
}
