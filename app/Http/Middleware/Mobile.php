<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

/**
 * Add headers for mobile api
 * Class Mobile
 * @package App\Http\Middleware
 */
class Mobile
{


    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var $response Response
         */
        $response=$next($request);


        return $response->header("Access-Control-Allow-Origin", "*")
            ->header("Access-Control-Allow-Headers", "Origin, X-Requested-With, "."Content-Type, Accept")
            ->header("Access-Control-Allow-Methods", "POST, GET, OPTIONS")
            ->header("Access-Control-Max-Age", "1728000");

    }


}
