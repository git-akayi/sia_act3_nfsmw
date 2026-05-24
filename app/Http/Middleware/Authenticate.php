<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * Returning null for JSON/API requests causes Laravel to return a 401 JSON
     * response instead of redirecting to the login page.
     */
    protected function redirectTo($request)
    {
        return $request->expectsJson() ? null : route('login');
    }
}
