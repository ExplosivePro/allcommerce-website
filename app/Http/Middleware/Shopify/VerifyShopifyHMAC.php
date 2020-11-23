<?php

namespace App\Http\Middleware\Shopify;

use Closure;
use Illuminate\Http\Request;

class VerifyShopifyHMAC
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
        // @todo - verify HMAC is valid or Fail.

        return $next($request);
    }
}
