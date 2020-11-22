<?php

namespace App\Http\Middleware;

use Closure;

class SetAuthIfAuthed
{
    public function handle($request, Closure $next)
    {
        if (backpack_auth()->guest())
        {
            return $next($request);
        }
        else
        {
            auth()->loginUsingId(backpack_user()->id);
            return $next($request);
        }
    }
}
