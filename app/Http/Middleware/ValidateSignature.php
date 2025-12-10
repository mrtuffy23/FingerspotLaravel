<?php

namespace App\Http\Middleware;

use Illuminate\Validation\Middleware\ValidateSignature as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * The names of the query string parameters that should be ignored.
     */
    protected $ignore = [
        'fbclid',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_source',
        'utm_term',
    ];
}
