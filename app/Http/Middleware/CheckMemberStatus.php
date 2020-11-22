<?php

namespace App\Http\Middleware;

use App\Aggregates\Users\UserProfileAggregate;
use Closure;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CheckMemberStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $aggy = UserProfileAggregate::retrieve(backpack_user()->id);
        if($aggy->isRegistered())
        {
            return $next($request);
        }
        else
        {
            session()->put('needs_registration', true);
            return redirect('/access/edit-account-info');
        }
    }
}
