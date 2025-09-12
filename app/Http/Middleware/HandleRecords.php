<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleRecords
{
    public function handle(Request $request, Closure $next)
    {
        // Your logic here
        return $next($request);
    }
}
