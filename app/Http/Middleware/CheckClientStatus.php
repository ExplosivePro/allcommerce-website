<?php

namespace App\Http\Middleware;

use App\Aggregates\Clients\ClientAccountAggregate;
use Closure;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CheckClientStatus
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
            $client = backpack_user()->client()->first();
            $aggy = ClientAccountAggregate::retrieve($client->id);

            if($aggy->getAccountOwner() == backpack_user()->id)
            {
                if(!$aggy->getReadyStatus())
                {
                    return redirect('access/clients/'.$client->id.'/edit');
                }
            }
        }

        return $next($request);
    }
}
