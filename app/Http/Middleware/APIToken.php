<?php

namespace App\Http\Middleware;

use App\Library\Services\TokenValidation\TokenValidation;
use Closure;

class APIToken
{

    private  $validation;
    /**
     * APIToken constructor.
     */
    public function __construct(TokenValidation $validation)
    {
        $this->validation=$validation;
    }


    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->validation->isValid($request)){
            return $next($request);
        }
        return response()->json(["message"=>'error','status'=>'404'],200);
    }
}
