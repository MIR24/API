<?php

namespace App\Http\Middleware;

use Closure;

class APIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->get('token')){
            return $next($request);
        }

        return response()->json([
            'message' => 'token not found.',
            'status'=>400
        ]);
    }
}
