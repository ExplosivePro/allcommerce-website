<?php

namespace App\Http\Middleware;

use App\Aggregates\Clients\ClientAccountAggregate;
use Closure;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class LocateMerchantsOrCreate
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
        if(!Bouncer::is(backpack_user())->an('admin'))
        {
            $aggy = ClientAccountAggregate::retrieve(backpack_user()->client_id);

            if($aggy->merchantCount() == 0)
            {
                return redirect('/access/merchants/create');
            }
        }

        return $next($request);
    }
}
